<?php
/**
* @version      2.9.4 23.09.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerDeliveryTimes extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("deliverytimes");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.deliverytimes";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "name", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
        $_deliveryTimes = JSFactory::getModel("deliveryTimes");
        $rows = $_deliveryTimes->getDeliveryTimes($filter_order, $filter_order_Dir);
        $view=$this->getView("deliverytimes", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows); 
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayDeliveryTimes', array(&$view));
        $view->displayList();
    }
	
	function edit() {
		$id = JFactory::getApplication()->input->getInt("id");
		$deliveryTimes = JSFactory::getTable('deliveryTimes', 'jshop');
		$deliveryTimes->load($id);
		$edit = ($id)?(1):(0);
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
        JFilterOutput::objectHTMLSafe( $deliveryTimes, ENT_QUOTES);

		$view=$this->getView("deliverytimes", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('deliveryTimes', $deliveryTimes);        
        $view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditDeliverytimes', array(&$view));
		$view->displayEdit();
	}
	
	function save() {
	    $mainframe = JFactory::getApplication();
		$id = JFactory::getApplication()->input->getInt("id");
		$deliveryTimes = JSFactory::getTable('deliveryTimes', 'jshop');
        $post = $this->input->post->getArray();
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveDeliveryTime', array(&$post) );
        
		if (!$deliveryTimes->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=deliverytimes");
			return 0;
		}
	
		if (!$deliveryTimes->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=deliverytimes");
			return 0;
		}
        
        $dispatcher->triggerEvent( 'onAfterSaveDeliveryTime', array(&$deliveryTimes) );
		
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=deliverytimes&task=edit&id=".$deliveryTimes->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=deliverytimes");
        }	
	}
	
	function remove() {				
		$cid = JFactory::getApplication()->input->getVar("cid");
        $_deliverytimes = JSFactory::getModel('deliverytimes');	        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveDeliveryTime', array(&$cid) );
        $text=$_deliverytimes->deleteItems($cid);
        $dispatcher->triggerEvent( 'onAfterRemoveDeliveryTime', array(&$cid) );        
		$this->setRedirect("index.php?option=com_jshopping&controller=deliverytimes", implode("</li><li>", $text));
	} 
    
    public function deliveryTimeOptions()
    {
        $jshopConfig = JSFactory::getConfig();
        $view = $this->getView('deliverytimes', 'html');
        $view->setLayout('options');
		$view->set('canDo', $canDo ?? '');
        $view->set('jshopConfig', $jshopConfig);
        $view->displayOptions();
    }

    public function saveDeliveryTimesOptions()
    {
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $post = JFactory::getApplication()->input->post->getArray();
        
        $result = [];
        $result = $post['params'];

        $otherConfig = [];
        if ($jshopConfig->other_config!=''){
            $otherConfig = unserialize($jshopConfig->other_config);
        }

        $allow = ['show_delivery_time_checkout', 'display_delivery_time_for_product_in_order_mail', 'show_delivery_date'];
        $config = new stdClass();
        include($jshopConfig->path.'lib/default_config.php');
        foreach($checkout_other_config as $k){
            if (in_array($k, $allow)) $otherConfig[$k] = $post[$k];
        }
        
        $result['other_config'] = serialize($otherConfig);

        $config = new jshopConfig($db);
        $config->id = $jshopConfig->load_id;
        $config->bind($result);
        $config->store();

        $this->setRedirect('index.php?option=com_jshopping&controller=deliverytimes');
    }
}