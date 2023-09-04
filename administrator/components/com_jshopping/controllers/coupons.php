<?php
/**
* @version      4.1.0 22.12.2012
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/extrascoupon/admin_extrascoupon_mambot.php';

class JshoppingControllerCoupons extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("coupons");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.coupons";
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "C.coupon_code", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
        $jshopConfig = JSFactory::getConfig();  	        		
        
        $_coupons = JSFactory::getModel("coupons");
        $total = $_coupons->getCountCoupons();
        
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);        
        $rows = $_coupons->getAllCoupons($pageNav->limitstart, $pageNav->limit, $filter_order, $filter_order_Dir);
        
        $currency = JSFactory::getTable('currency', 'jshop');
        $currency->load($jshopConfig->mainCurrency);
                        
		$view=$this->getView("coupons", 'html');
        $view->setLayout("list");		
		$view->set('canDo', $this->canDo);
        $view->set('rows', $rows);        
        $view->set('currency', $currency->currency_code);
        $view->set('pageNav', $pageNav);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);   
		
        $dispatcher = \JFactory::getApplication();
        AdminExtrascouponMambot::getInstance()->onBeforeDisplayCoupons($view);
        $dispatcher->triggerEvent('onBeforeDisplayCoupons', array(&$view));		
		$view->displayList(); 
    }
    
    function edit() {
        $coupon_id = JFactory::getApplication()->input->getInt('coupon_id');
        $coupon = JSFactory::getTable('coupon', 'jshop'); 
        $coupon->load($coupon_id);
        $edit = ($coupon_id)?($edit = 1):($edit = 0);
        $arr_type_coupon = array();
		$arr_type_coupon[0] = new StdClass();
        $arr_type_coupon[0]->coupon_type = 0;
        $arr_type_coupon[0]->coupon_value = JText::_('COM_SMARTSHOP_COUPON_PERCENT');

		$arr_type_coupon[1] = new StdClass();
        $arr_type_coupon[1]->coupon_type = 1;
        $arr_type_coupon[1]->coupon_value = JText::_('COM_SMARTSHOP_COUPON_ABS_VALUE');
        
        if (!$coupon_id){
          $coupon->coupon_type = 0;  
          $coupon->finished_after_used = 1;
          $coupon->for_user_id = 0;
        }
        $currency_code = getMainCurrencyCode();

        $lists['coupon_type'] = JHTML::_('select.radiolist', $arr_type_coupon, 'coupon_type', 'onchange="shopCoupon.changeType()"', 'coupon_type', 'coupon_value', $coupon->coupon_type);

        $_tax = JSFactory::getModel("taxes");
        $all_taxes = $_tax->getAllTaxes();
        $list_tax = array();        
        foreach ($all_taxes as $tax) {
            $list_tax[] = JHTML::_('select.option', $tax->tax_id, $tax->tax_name . ' (' . $tax->tax_value . '%)','tax_id','tax_name');
        }
        $lists['tax'] = JHTML::_('select.genericlist', $list_tax, 'tax_id', 'class="inputbox form-select" size = "1" ', 'tax_id', 'tax_name', $coupon->tax_id);
        
        $view=$this->getView("coupons", 'html');
        $view->setLayout("edit");        
		$view->set('canDo', $this->canDo);
        $view->set('coupon', $coupon);        
        $view->set('lists', $lists);        
        $view->set('edit', $edit);
        $view->set('currency_code', $currency_code);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        AdminExtrascouponMambot::getInstance()->onBeforeEditCoupons($view);
        $dispatcher->triggerEvent('onBeforeEditCoupons', array(&$view));
        $view->displayEdit();
    }
    
    function save() {        

        $coupon_id = JFactory::getApplication()->input->getInt("coupon_id");        
        $coupon = JSFactory::getTable('coupon', 'jshop');
        
        $dispatcher = \JFactory::getApplication();        
        
        $post = $this->input->post->getArray();        
        $post['coupon_code'] = JFactory::getApplication()->input->getString("coupon_code");
        $post['coupon_publish'] = JFactory::getApplication()->input->getInt("coupon_publish");
        $post['finished_after_used'] = JFactory::getApplication()->input->getInt("finished_after_used");
        $post['coupon_value'] = saveAsPrice($post['coupon_value']);
        
        AdminExtrascouponMambot::getInstance()->onBeforeSaveCoupon($post);
        $dispatcher->triggerEvent('onBeforeSaveCoupon', array(&$post));
        
        if (!$post['coupon_code']){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_COUPON_CODE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=coupons&task=edit&coupon_id=".$coupon->coupon_id);
            return 0;
        }
        
        if ($post['coupon_value']<0 || ($post['coupon_value']>100 && $post['coupon_type']==0)){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_COUPON_VALUE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=coupons&task=edit&coupon_id=".$coupon->coupon_id);    
            return 0;
        }        
        
        if(!$coupon->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=coupons");
            return 0;
        }
        
        if ($coupon->getExistCode()){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_COUPON_EXIST'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=coupons");
            return 0;
        }

        if (!$coupon->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=coupons");
            return 0;
        }
        
        AdminExtrascouponMambot::getInstance()->onAfterSaveCoupon($coupon);
        $dispatcher->triggerEvent('onAfterSaveCoupon', array(&$coupon));
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=coupons&task=edit&coupon_id=".$coupon->coupon_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=coupons");
        }
                
    }
    
    function remove() {
        $cid = JFactory::getApplication()->input->getVar("cid");       
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveCoupon', array(&$cid) );
		$_coupons = JSFactory::getModel('coupons');
		$text=$_coupons->deleteCoupons($cid); 
        $dispatcher->triggerEvent( 'onAfterRemoveCoupon', array(&$cid) );        
        $this->setRedirect("index.php?option=com_jshopping&controller=coupons", $text);
    }
    
    function publish(){
        $this->publishCoupon(1);
    }
    
    function unpublish(){
        $this->publishCoupon(0);
    }

    function publishCoupon($flag) {
        $cid = JFactory::getApplication()->input->getVar("cid");        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforePublishCoupon', array(&$cid,&$flag) );
        $_coupons = JSFactory::getModel('coupons');
        $_coupons->publishСoupons($cid,$flag);   
        $dispatcher->triggerEvent( 'onAfterPublishCoupon', array(&$cid,&$flag) );        
        $this->setRedirect("index.php?option=com_jshopping&controller=coupons");
    }

    public function custom_options($cachable = false, $urlparams = false) 
    {
        $jshopConfig = JSFactory::getConfig();
        $config = new stdClass();
        include($jshopConfig->path.'lib/default_config.php');
		$view = $this->getView("coupons", 'html');
        $view->setLayout("configurations");
		$view->set('canDo', $canDo ?? '');
        $view->set("other_config_checkbox", $other_config_checkbox);
        $view->displayConfigurations();
    }
    
    function configurations_apply($cachable = false, $urlparams = false) {
        $db = \JFactory::getDBO();
        $post = $this->input->post->getArray();
		$jshopConfig = JSFactory::getConfig();
        
        if (!isset($post['discount_use_full_sum'])) $post['discount_use_full_sum'] = 0;
        if (!isset($post['use_rabatt_code'])) $post['use_rabatt_code'] = 0;
        
		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
        }
        $result['user_discount_not_apply_prod_old_price'] = $post['user_discount_not_apply_prod_old_price'];
        $post['other_config'] = serialize($result);
		
		$config = new jshopConfig($db);
		$config->id = $jshopConfig->load_id;
		if (!$config->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=coupons');
			return 0;
		}		
		if (!$config->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')." ".$config->_error,'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=coupons');
			return 0;
        }
		$this->setRedirect('index.php?option=com_jshopping&controller=coupons',JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
	}
        
}
?>