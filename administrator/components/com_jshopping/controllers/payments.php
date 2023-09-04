<?php
/**
* @version      4.8.0 22.10.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
include_once(JPATH_COMPONENT_SITE."/payments/payment.php");

class JshoppingControllerPayments extends JControllerLegacy{
	
	protected $canDo;

    public function __construct($config = []){
        parent::__construct($config);
        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        checkAccessController('payments');
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu('other',$this->canDo);
    }
	
    public function display($cachable = false, $urlparams = false) 
    {
        $jshopConfig = JSFactory::getConfig();
        $_payments = JSFactory::getModel('payments');
        $mainframe = JFactory::getApplication();
        $context = 'jshoping.list.admin.payments';
        $filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'payment_ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
        
        $rows = $_payments->getAllPaymentMethods(0, $filter_order, $filter_order_Dir);
		
        $view=$this->getView('payments', 'html');
        $view->setLayout('list');
		$view->set('canDo', $canDo ?? '');
	    $view->set('rows', $rows);		
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
        $view->set('config', $jshopConfig);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayPayments', [&$view]);
        $view->displayList();
    }
	
    public function edit()
    {
        $jshopConfig = JSFactory::getConfig();
        $payment_id = JFactory::getApplication()->input->getInt('payment_id');        
        $payment = JSFactory::getTable('paymentMethod', 'jshop');
        $payment->load($payment_id);
        $parseString = new parseString($payment->payment_params);
        $params = $parseString->parseStringToParams();
        $edit = ($payment_id) ? ($edit = 1) : ($edit = 0);
                
        $_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages) > 1;
		
        $_payments = JSFactory::getModel('payments');
		
        if ($edit) {
            $paymentsysdata = $payment->getPaymentSystemData();

            if ($paymentsysdata->paymentSystem){
                ob_start();                
                $paymentsysdata->paymentSystem->showAdminFormParams($params);
                $lists['html'] = ob_get_contents();
                ob_get_clean();
            } else {
                $lists['html'] = '';
            }
		} else {
			$lists['html'] = '';
        }
        
        $currencyCode = getMainCurrencyCode();
        
        if ($jshopConfig->tax){
            $_taxes = JSFactory::getModel('taxes');
            $all_taxes = $_taxes->getAllTaxes();
            $list_tax = array();
            foreach($all_taxes as $tax) {
                $list_tax[] = JHTML::_('select.option', $tax->tax_id, $tax->tax_name . ' (' . $tax->tax_value . '%)','tax_id','tax_name');
            }
            $list_tax[] = JHTML::_('select.option', -1,JText::_('COM_SMARTSHOP_PRODUCT_TAX_RATE'),'tax_id','tax_name');        
            $lists['tax'] = JHTML::_('select.genericlist', $list_tax, 'tax_id', 'class = "inputbox form-select"','tax_id','tax_name', $payment->tax_id);
        }
        
        $list_price_type = array();
        $list_price_type[] = JHTML::_('select.option', "1", $currencyCode, 'id','name');
        $list_price_type[] = JHTML::_('select.option', "2", "%", 'id','name');
        $lists['price_type'] = JHTML::_('select.genericlist', $list_price_type, 'price_type', 'class = "inputbox form-select"', 'id', 'name', $payment->price_type);
        
        $payment_type = $_payments->getTypes();
        $opt = [];
        foreach($payment_type as $key => $value) {
            $opt[] = JHTML::_('select.option', $key, $value, 'id', 'name');
        }

        /*if ($jshopConfig->shop_mode==0 && $payment_id) {
            $disabled = 'disabled';
        } else {
            $disabled = '';
        }*/
		$disabled = '';

        $lists['type_payment'] = JHTML::_('select.genericlist', $opt, 'payment_type', 'class = "inputbox form-select" ' . $disabled, 'id', 'name', $payment->payment_type);
        
        $nofilter = [];
        JFilterOutput::objectHTMLSafe($payment, ENT_QUOTES, $nofilter);
        $_usergroups = JSFactory::getModel("usergroups");
        $usergroups_list['*'] = JText::_('COM_SMARTSHOP_ALL');
		$usergroups_list = $usergroups_list + $_usergroups->getAllUsergroupsSelect();
        $payment->usergroup_id = ($payment->usergroup_id == implode(',', $_usergroups->getAllUserGroupsIdsWithGuest())) ? '*': $payment->usergroup_id;

        $view=$this->getView('payments', 'html');
        $view->setLayout('edit');
		$view->set('canDo', $canDo ?? '');
        $view->set('payment', $payment);
		$view->set('usergroups_list', $usergroups_list);
        $view->set('edit', $edit);
        $view->set('params', $params);
        $view->set('lists', $lists);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('config', $jshopConfig);
        $view->set('etemplatevar', '');
        if (empty($params)) {
            $view->set('order_status', JSFactory::getModel('orders', 'JshoppingModel')->getAllOrderStatus());
            $view->set('payment_status', $payment->payment_status);
        }
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditPayments', [&$view]);
        $view->displayEdit();
    }	
	
    public function save() {
        $jshopConfig = JSFactory::getConfig();
        $payment_id = JFactory::getApplication()->input->getInt('payment_id');
        
        $dispatcher = \JFactory::getApplication();        
        $payment = JSFactory::getTable('paymentMethod', 'jshop');
        $post = JFactory::getApplication()->input->post->getArray();
        if (!empty($post['usergroup_id']['0']) && $post['usergroup_id']['0'] == '*') {
            $usergroupsModel = JSFactory::getModel('usergroups');
            $post['usergroup_id'] = $usergroupsModel->getAllUserGroupsIdsWithGuest();
        }
		$post['usergroup_id']=implode(",",(array)$post['usergroup_id']);
        if (!isset($post['payment_publish'])) {
            $post['payment_publish'] = 0;
        }

        if (!isset($post['show_descr_in_email'])) {
            $post['show_descr_in_email'] = 0;
        }

        $post['price'] = saveAsPrice($post['price']);
        $post['payment_class'] = JFactory::getApplication()->input->getCmd('payment_class');

        if (!$payment_id) {
            $post['payment_type'] = 1;
        }
        
        $dispatcher->triggerEvent( 'onBeforeSavePayment', [&$post]);
        
        $_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);

        foreach($languages as $lang) {
            $post['description_' . $lang->language] = $_POST['description' . $lang->id];
        }
		
		$_payments = JSFactory::getModel('payments');		
		$payment->bind($post);

        if (!$payment->payment_id){
            $payment->payment_ordering = $_payments->getMaxOrdering() + 1;
        }
        
		if (isset($post['pm_params'])) {
			$parseString = new parseString($post['pm_params']);
			$payment->payment_params = $parseString->splitParamsToString();
        }
        
        if (isset($post['payment_status'])) {
			$payment->payment_status = $post['payment_status'];
        }
        
        if (!$payment->check()){
            \JFactory::getApplication()->enqueueMessage($payment->getError(),'error');
            $this->setRedirect('index.php?option=com_jshopping&controller=payments&task=edit&payment_id=' . $payment->payment_id);
            return 0;
        }
		
		$payment->store();

        $dispatcher->triggerEvent('onAfterSavePayment', [&$payment]);
		
        if ($this->getTask() == 'apply') {
            $this->setRedirect('index.php?option=com_jshopping&controller=payments&task=edit&payment_id=' . $payment->payment_id); 
        }else{
            $this->setRedirect('index.php?option=com_jshopping&controller=payments');
        }
	}
	
    public function delete_foto()
    {
        $payment_id = JFactory::getApplication()->input->getInt('payment_id');
        //$jshopConfig = JSFactory::getConfig();
        $payment = JSFactory::getTable('paymentMethod', 'jshop');
        $payment->load($payment_id);
        //@unlink($jshopConfig->image_payments_path . '/' . substr($payment->image, strrpos($payment->image, '/') + 1,strlen($payment->image)-strrpos($payment->image,'/')));
        $payment->image = '';
        $payment->store();

        die();
    }

	
    public function remove()
    {
		$cid = JFactory::getApplication()->input->getVar('cid');		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemovePayment', array(&$cid) );
		$_payments = JSFactory::getModel('payments');
		$text=$_payments->deletePayments($cid);
        $dispatcher->triggerEvent( 'onAfterRemovePayment', array(&$cid) );
		$this->setRedirect('index.php?option=com_jshopping&controller=payments', $text);
	}
	
    public function publish()
    {
        $this->publishPayment(1);
    }
    
    public function unpublish()
    {
        $this->publishPayment(0);
    }
	
    public function publishPayment($flag) 
    {		
		$cid = JFactory::getApplication()->input->getVar("cid");        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforePublishPayment', array(&$cid, &$flag) );
		$_payments = JSFactory::getModel('payments');
		$_payments->publishPayments($cid,$flag);		
		$dispatcher->triggerEvent( 'onAfterPublishPayment', [&$cid, &$flag]);		
		$this->setRedirect('index.php?option=com_jshopping&controller=payments');
	}
	
    public function order() 
    {
		$order = JFactory::getApplication()->input->getVar('order');
		$cid = JFactory::getApplication()->input->getInt('id');
		$number = JFactory::getApplication()->input->getInt('number');
		$_payments = JSFactory::getModel('payments');	        
		$_payments->orderingChange($order,$cid,$number);
		$this->setRedirect('index.php?option=com_jshopping&controller=payments');
	}

    public function saveorder()
    {
        $cid = JFactory::getApplication()->input->getVar('cid', [], 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar('order', [], 'post', 'array' );
        
        foreach($cid as $k=>$id){
            $table = JSFactory::getTable('paymentMethod', 'jshop');
            $table->load($id);
            if ($table->payment_ordering != $order[$k]) {
                $table->payment_ordering = $order[$k];
                $table->store();
            }
        }

        $this->setRedirect('index.php?option=com_jshopping&controller=payments');
    }
	   
}