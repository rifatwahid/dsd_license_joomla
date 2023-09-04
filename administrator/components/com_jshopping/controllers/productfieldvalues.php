<?php
/**
* @version      3.7.0 26.12.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerProductFieldValues extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("productfieldvalues");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    
    function display($cachable = false, $urlparams = false){
        $field_id = JFactory::getApplication()->input->getInt("field_id");        
        $_productfieldvalues = JSFactory::getModel("productFieldValues");
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.productfieldvalues";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        $text_search = $mainframe->getUserStateFromRequest($context.'text_search', 'text_search', '');
        
        $filter = array("text_search"=>$text_search);
        
        $rows = $_productfieldvalues->getList($field_id, $filter_order, $filter_order_Dir, $filter);
        
        $view = $this->getView("product_field_values", 'html');
        $view->setLayout("list");
		$view->set("canDo", $this->canDo);
        $view->set('rows', $rows);
        $view->set('field_id', $field_id);
		$view->set('text_search', $text_search);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayProductFieldValues', array(&$view));
        $view->displayList();
    }
    
    function edit(){        
        $field_id = JFactory::getApplication()->input->getInt("field_id");
        $id = JFactory::getApplication()->input->getInt("id");
        $jshopConfig = JSFactory::getConfig();
        
        $productfieldvalue = JSFactory::getTable('productFieldValue', 'jshop');
        $productfieldvalue->load($id);
        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;    
                
        $view = $this->getView("product_field_values", 'html');
        $view->setLayout("edit");
		$view->set("canDo", $this->canDo);
        JFilterOutput::objectHTMLSafe($productfieldvalue, ENT_QUOTES);
        $view->set('row', $productfieldvalue);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('field_id', $field_id);
        $view->set('config', $jshopConfig);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditProductFieldValues', array(&$view));
        $view->displayEdit();
    }

    function save(){
        $jshopConfig = JSFactory::getConfig();
        require_once ($jshopConfig->path.'lib/uploadfile.class.php');
        $id = JFactory::getApplication()->input->getInt("id");
        $field_id = JFactory::getApplication()->input->getInt("field_id");
        $productfieldvalue = JSFactory::getTable('productFieldValue', 'jshop');
        $post = $this->input->post->getArray();
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveProductFieldValue', array(&$post) );
		
        if (!$productfieldvalue->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=productfieldvalues");
            return 0;
        }
        
        if (!$id){
            $productfieldvalue->ordering = null;
            $productfieldvalue->ordering = $productfieldvalue->getNextOrder('field_id="'.$field_id.'"');            
        }
        
        if (!$productfieldvalue->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=productfieldvalues");
            return 0; 
        }
        
        $dispatcher->triggerEvent( 'onAfterSaveProductFieldValue', array(&$productfieldvalue) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=productfieldvalues&task=edit&field_id=".$field_id."&id=".$productfieldvalue->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=productfieldvalues&field_id=".$field_id);
        }
                        
    }

    function remove(){
        $field_id = JFactory::getApplication()->input->getInt("field_id");
        $cid = JFactory::getApplication()->input->getVar("cid");        
		$_productfieldvalues = JSFactory::getModel('productfieldvalues');
		$text=$_productfieldvalues->deleteProductfieldvalues($cid);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onAfterRemoveProductFieldValue', array(&$cid) );        
        $this->setRedirect("index.php?option=com_jshopping&controller=productfieldvalues&field_id=".$field_id, implode("</li><li>",$text));
    }
    
    function back(){
        $this->setRedirect("index.php?option=com_jshopping&controller=productfields");
    }
    
    function order(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $field_id = JFactory::getApplication()->input->getInt("field_id");
        $move = JFactory::getApplication()->input->getInt("move");        
        $productfieldvalue = JSFactory::getTable('productFieldValue', 'jshop');
        $productfieldvalue->load($id);
        $productfieldvalue->move($move, 'field_id="'.$field_id.'"');
        $this->setRedirect("index.php?option=com_jshopping&controller=productfieldvalues&field_id=".$field_id);
    }
    
    function saveorder(){
        $cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar( 'order', array(), 'post', 'array' );
        $field_id = JFactory::getApplication()->input->getInt("field_id");
        
        foreach ($cid as $k=>$id){
            $table = JSFactory::getTable('productFieldValue', 'jshop');
            $table->load($id);
            if ($table->ordering!=$order[$k]){
                $table->ordering = $order[$k];
                $table->store();
            }        
        }
        
        $table = JSFactory::getTable('productFieldValue', 'jshop');
        $table->ordering = null;
        $table->reorder('field_id="'.$field_id.'"');        
                
        $this->setRedirect("index.php?option=com_jshopping&controller=productfieldvalues&field_id=".$field_id);
    }
	
	function delete_foto(){
		//$jshopConfig = JSFactory::getConfig();
        
        $id = JFactory::getApplication()->input->getInt("id");
        $productField = JSFactory::getTable('productfieldvalue', 'jshop');
        $productField->load($id);
        //@unlink($jshopConfig->image_productfield_path."/".$productField->image);
        $productField->image = "";
        $productField->store();
        die();               
	}
    
}
?>		