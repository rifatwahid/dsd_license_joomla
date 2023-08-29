<?php
/**
* @version      2.9.4 02.11.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerUnits extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("units");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

	function display($cachable = false, $urlparams = false){
		$_units = JSFactory::getModel("units");
		$rows = $_units->getUnits();
        
		$view=$this->getView("units", 'html');
        $view->setLayout("list");		
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);       
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayUnits', array(&$view)); 		
		$view->displayList();
	}
	
    function edit() {
		$config = JSFactory::getConfig();
        $id = JFactory::getApplication()->input->getInt("id");
        $units = JSFactory::getTable('unit', 'jshop');
        $units->load($id);
        $edit = ($id)?(1):(0);
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
        if (!$units->qty) $units->qty = 1;
		$unit_number_format_list = $config->unit_number_format;
		$unit_number_format = $units->unit_number_format;
		$list = JHTML::_('select.genericlist', $unit_number_format_list,'unit_number_format','class = "inputbox form-select" size = "1"','unit_number_format','unit_number_format',$unit_number_format);
       
        
        JFilterOutput::objectHTMLSafe( $units, ENT_QUOTES);

		$view=$this->getView("units", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('units', $units);        
        $view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('etemplatevar', '');
        $view->set('list', $list);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditUnitss', array(&$view));
		$view->displayEdit();
	}
	
	function save() {
	    $mainframe = JFactory::getApplication();
		$id = JFactory::getApplication()->input->getInt("id");
		$units = JSFactory::getTable('unit', 'jshop');
        $post = $this->input->post->getArray();
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveUnit', array(&$post) );        
        
		if (!$units->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=units");
			return 0;
		}
	
		if (!$units->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=units");
			return 0;
		}
        
        $dispatcher->triggerEvent( 'onAfterSaveUnit', array(&$units) );
		
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=units&task=edit&id=".$units->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=units");
        }	
	}
	
	function remove() {		
		$_units = JSFactory::getModel("units");
		$text = array();
		$cid = JFactory::getApplication()->input->getVar("cid");
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveUnit', array(&$cid) );

		foreach ($cid as $key => $value) {
			if($_units->deleteUnitById($value)) $text[] = JText::_('COM_SMARTSHOP_ITEM_DELETED')."<br>";			
		}
        $dispatcher->triggerEvent( 'onAfterRemoveUnit', array(&$cid) );
        
		$this->setRedirect("index.php?option=com_jshopping&controller=units", implode("</li><li>", $text));
	} 
    
    
}

?>