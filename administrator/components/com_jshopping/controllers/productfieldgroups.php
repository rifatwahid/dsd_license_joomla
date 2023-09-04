<?php
/**
* @version      3.3.0 10.12.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerProductFieldGroups extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("productfieldgroups");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    
    function display($cachable = false, $urlparams = false){                
        $_productfieldgroups = JSFactory::getModel("productFieldGroups");
        $rows = $_productfieldgroups->getList();
        
        $view = $this->getView("product_field_groups", 'html');
        $view->setLayout("list");
		$view->set("canDo", $this->canDo);
        $view->set('rows', $rows);    
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayProductsFieldGroups', array(&$view));		
        $view->displayList();
    }
    
    function edit(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $productfieldgroup = JSFactory::getTable('productFieldGroup', 'jshop');
        $productfieldgroup->load($id);
        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;    
                
        $view = $this->getView("product_field_groups", 'html');
        $view->setLayout("edit");
		$view->set("canDo", $this->canDo);
        JFilterOutput::objectHTMLSafe($productfieldgroup, ENT_QUOTES);
        $view->set('row', $productfieldgroup);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditProductFieldGroups', array(&$view));
        $view->displayEdit();
    }

    function save(){
        $productfieldgroup = JSFactory::getTable('productFieldGroup', 'jshop');
        $post = $this->input->post->getArray();
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveProductFieldGroup', array(&$post) );
        
        if (!$productfieldgroup->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=productfieldgroups");
            return 0;
        }
        
        if (!$id){
            $productfieldgroup->ordering = null;
            $productfieldgroup->ordering = $productfieldgroup->getNextOrder();
        }
        
        if (!$productfieldgroup->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=productfieldgroups");
            return 0; 
        }
        
        $dispatcher->triggerEvent( 'onAfterSaveProductFieldGroup', array(&$productfieldgroup) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=productfieldgroups&task=edit&id=".$productfieldgroup->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=productfieldgroups");
        }
                        
    }

    function remove(){        
        $cid = JFactory::getApplication()->input->getVar("cid");        
		$_productfieldgroups = JSFactory::getModel('productfieldgroups');
		$text=$_productfieldgroups->deleteProductfieldgroups($cid);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onAfterRemoveProductFieldGroup', array(&$cid) );        
        $this->setRedirect("index.php?option=com_jshopping&controller=productfieldgroups", implode("</li><li>",$text));
    }
    
    function back(){
        $this->setRedirect("index.php?option=com_jshopping&controller=productfields");
    }
    
    function order(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $move = JFactory::getApplication()->input->getInt("move");        
        $productfieldvalue = JSFactory::getTable('productFieldGroup', 'jshop');
        $productfieldvalue->load($id);
        $productfieldvalue->move($move);
        $this->setRedirect("index.php?option=com_jshopping&controller=productfieldgroups");
    }
    
    function saveorder(){
        $cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar( 'order', array(), 'post', 'array' );        
        
        foreach ($cid as $k=>$id){
            $table = JSFactory::getTable('productFieldGroup', 'jshop');
            $table->load($id);
            if ($table->ordering!=$order[$k]){
                $table->ordering = $order[$k];
                $table->store();
            }        
        }
        
        $table = JSFactory::getTable('productFieldGroup', 'jshop');
        $table->ordering = null;
        $table->reorder();
                
        $this->setRedirect("index.php?option=com_jshopping&controller=productfieldgroups");
    }
    
}
?>		