<?php
/**
* @version      4.7.0 08.09.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerLogs extends JControllerLegacy{
	
	protected $canDo;    
    
    function __construct( $config = array() ){
        parent::__construct( $config );
        checkAccessController("logs");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');        
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();        
        $jshopConfig = JSFactory::getConfig();        
        $_logs = JSFactory::getModel("logs");
        $rows = $_logs->getList();
        
		$view = $this->getView("logs", 'html');
        $view->setLayout("list");	
		$view->set("canDo", $this->canDo);
        $view->set('rows', $rows);        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayLogs', array(&$view));
		$view->displayList();
    }
    
    function edit() {
        $id = JFactory::getApplication()->input->getVar('id');
        $filename = str_replace(array('..','/',), '', $id);
        $_logs = JSFactory::getModel("logs");
        $data = $_logs->read($filename);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
                
        $view=$this->getView("logs", 'html');
        $view->setLayout("edit");        
		$view->set("canDo", $this->canDo);
        $view->set('filename', $filename);                
        $view->set('data', $data);                
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditLogs', array(&$view));
        $view->displayEdit();
    }
}
?>