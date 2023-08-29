<?php
/**
* @version      2.7.0 16.12.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
include_once(JPATH_COMPONENT_ADMINISTRATOR."/importexport/iecontroller.php");

class JshoppingControllerImportExport extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );        
        checkAccessController("importexport");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){
        if ($this->getTask()!="" && $this->getTask()!="backtolistie" && JFactory::getApplication()->input->getInt("ie_id")){
            $this->view();
            return 1;
        }
    	$_importexport = JSFactory::getModel("importexport");    	
        
		$rows = $_importexport->getList();		
        $view=$this->getView("import_export_list", 'html');
		$view->set('rows', $rows);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayImportExport', array(&$view));
        $view->display();
    }
    
    function remove() {        
        $cid = JFactory::getApplication()->input->getInt("cid");        
        $_importexport = JSFactory::getTable('ImportExport', 'jshop'); 
        $_importexport->load($cid);        
        $_importexport->delete();        
        $this->setRedirect('index.php?option=com_jshopping&controller=importexport', JText::_('COM_SMARTSHOP_ITEM_DELETED'));
    }
    
    function setautomaticexecution(){
        $cid = JFactory::getApplication()->input->getInt("cid");        
        $_importexport = JSFactory::getTable('ImportExport', 'jshop'); 
        $_importexport->load($cid);
        if ($_importexport->steptime > 0){
            $_importexport->steptime = 0;
        }else{
            $_importexport->steptime = 1;
        }
        $_importexport->store();
        $this->setRedirect('index.php?option=com_jshopping&controller=importexport');
    }
    
    function view(){
        $ie_id = JFactory::getApplication()->input->getInt("ie_id");
        $_importexport = JSFactory::getTable('ImportExport', 'jshop'); 
        $_importexport->load($ie_id);
        $alias = $_importexport->get('alias');
        if (!file_exists(JPATH_COMPONENT_ADMINISTRATOR."/importexport/".$alias."/".$alias.".php")){
            \JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_ERROR_FILE_NOT_EXIST', "/importexport/".$alias."/".$alias.".php"),'error');
            return 0;
        }
        
        include_once(JPATH_COMPONENT_ADMINISTRATOR."/importexport/".$alias."/".$alias.".php");
        
        $classname    = 'Ie'.$alias;
        $controller   = new $classname($ie_id);
        $controller->set('ie_id', $ie_id);
        $controller->set('alias', $alias);
        $controller->execute( JFactory::getApplication()->input->getVar( 'task' ) );        
    }
		      
}
?>