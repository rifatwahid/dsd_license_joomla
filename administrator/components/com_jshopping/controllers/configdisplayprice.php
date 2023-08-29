<?php
/**
* @version      3.3.0 03.11.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerConfigDisplayPrice extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("configdisplayprice");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("config",$this->canDo);        
    }
    
    function display($cachable = false, $urlparams = false){        
        $_configdisplayprice = JSFactory::getModel("configDisplayPrice");
        $rows = $_configdisplayprice->getList();
        
        $_countries = JSFactory::getModel("countries");
        $list = $_countries->getAllCountries(0);    
        $countries_name = array();
        foreach($list as $v){
            $countries_name[$v->country_id] = $v->name;
        }
        
        foreach($rows as $k=>$v){
            $list = unserialize($v->zones);
            
            foreach($list as $k2=>$v2){
                $list[$k2] = $countries_name[$v2];
            }
            if (count($list) > 10){
                $tmp = array_slice($list, 0, 10);
                $rows[$k]->countries = implode(", ", $tmp)."...";
            }else{
                $rows[$k]->countries = implode(", ", $list);
            }
        }
        
        $typedisplay = array(0=>JText::_('COM_SMARTSHOP_PRODUCT_BRUTTO_PRICE'), 1=>JText::_('COM_SMARTSHOP_PRODUCT_NETTO_PRICE'));
        
        $view = $this->getView("config_display_price", 'html');
        $view->setLayout("list");
		$view->set("canDo", $this->canDo);
        $view->set('rows', $rows);
        $view->set('typedisplay', $typedisplay); 
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayConfigDisplayPrice', array(&$view)); 
        $view->displayList();
    }
    
    function edit() {     
        loadingStatesScriptsAdmin();   
        $id = JFactory::getApplication()->input->getInt("id");
        
        $configdisplayprice = JSFactory::getTable('configDisplayPrice', 'jshop');
        $configdisplayprice->load($id);
        
        $list_c = $configdisplayprice->getZones();
        $zone_countries = array();        
        foreach($list_c as $v){
            $obj = new stdClass();
            $obj->country_id = $v;
            $zone_countries[] = $obj;
        }        
        
        $display_price_list = array();
        $display_price_list[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_PRODUCT_BRUTTO_PRICE'), 'id', 'name');
        $display_price_list[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PRODUCT_NETTO_PRICE'), 'id', 'name');
        
        $lists['display_price'] = JHTML::_('select.genericlist', $display_price_list, 'display_price', 'class="form-select"', 'id', 'name', $configdisplayprice->display_price);
        $lists['display_price_firma'] = JHTML::_('select.genericlist', $display_price_list, 'display_price_firma', 'class="form-select"', 'id', 'name', $configdisplayprice->display_price_firma);
        
        $_countries = JSFactory::getModel("countries");
        $lists['countries'] = JHTML::_('select.genericlist', $_countries->getAllCountries(0), 'countries_id[]', 'class="form-select" size = "10", multiple = "multiple"', 'country_id', 'name', $zone_countries);
        
        JFilterOutput::objectHTMLSafe($configdisplayprice, ENT_QUOTES);

        $view = $this->getView("config_display_price", 'html');
        $view->setLayout("edit");
		$view->set("canDo", $this->canDo);
        $view->set('row', $configdisplayprice);
        $view->set('lists', $lists);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigDisplayPrice', array(&$view));
        $view->displayEdit();
    }

    function save(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $configdisplayprice = JSFactory::getTable('configDisplayPrice', 'jshop');        
        $post = $this->input->post->getArray();
        
        
        $dispatcher = \JFactory::getApplication();
        
        $dispatcher->triggerEvent( 'onBeforeSaveConfigDisplayPrice', array(&$post) );
                
        if (!$post['countries_id']){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=configdisplayprice&task=edit&id=".$post['id']);
            return 0;
        }
        
        if (!$configdisplayprice->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=configdisplayprice");
            return 0;
        }
        $configdisplayprice->setZones($post['countries_id']);

        if (!$configdisplayprice->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=configdisplayprice");
            return 0; 
        }
        
        updateCountConfigDisplayPrice();
        
        $dispatcher->triggerEvent( 'onAftetSaveConfigDisplayPrice', array(&$configdisplayprice) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=configdisplayprice&task=edit&id=".$configdisplayprice->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=configdisplayprice");
        }
                        
    }

    function remove(){
        $cid = JFactory::getApplication()->input->getVar("cid");        
		$_config = JSFactory::getModel("config");
        
        $dispatcher = \JFactory::getApplication();
        
        $dispatcher->triggerEvent( 'onBeforeDeleteConfigDisplayPrice', array(&$cid) );
        $text = array();
        foreach ($cid as $key => $value) {            			
            if ($_config->deleteDisplayPricesById($value)){
                $text[] = JText::_('COM_SMARTSHOP_ITEM_DELETED');
            }    
        }
        
        updateCountConfigDisplayPrice();
        
        $dispatcher->triggerEvent( 'onAfterDeleteConfigDisplayPrice', array(&$cid) );
        
        $this->setRedirect("index.php?option=com_jshopping&controller=configdisplayprice", implode("</li><li>",$text));
    }
    
    function back(){
        $this->setRedirect("index.php?option=com_jshopping&controller=config&task=general");
    }
    
}
?>		