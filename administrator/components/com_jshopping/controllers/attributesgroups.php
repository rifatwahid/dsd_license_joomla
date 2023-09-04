<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerAttributesGroups extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct($config = array()){
        parent::__construct( $config );
        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        checkAccessController("attributesgroups");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    
    function display($cachable = false, $urlparams = false){                
        $_attributesgroups = JSFactory::getModel("attributesGroups");
        $rows = $_attributesgroups->getList();        
        $view = $this->getView("attributesgroups", 'html');
        $view->setLayout("list");
		$view->set("canDo", $this->canDo);
        $view->set('rows', $rows);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayAttributesGroups', array(&$view));
        $view->displayList();
    }
    
    function edit(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $row = JSFactory::getTable('attributesgroup', 'jshop');
        $row->load($id);
        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;    
                
        $view = $this->getView("attributesgroups", 'html');
        $view->setLayout("edit");
		$view->set("canDo", $this->canDo);
        JFilterOutput::objectHTMLSafe($row, ENT_QUOTES);
        $view->set('row', $row);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditAttributesGroups', array(&$view));
        $view->displayEdit();
    }

    function save(){
        $row = JSFactory::getTable('attributesgroup', 'jshop');
        $post = $this->input->post->getArray();
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveAttributesGroups', array(&$post));
        
        $row->bind($post);
        if (!$post['id']){
            $row->ordering = null;
            $row->ordering = $row->getNextOrder();
        }        
        $row->store();
        
        $dispatcher->triggerEvent('onAfterSaveAttributesGroups', array(&$row) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=attributesgroups&task=edit&id=".$row->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=attributesgroups");
        }
    }

    function remove(){
        $cid = JFactory::getApplication()->input->getVar("cid");
		$_attributesgroups = JSFactory::getModel('attributesgroups');        
        $text = array();
        foreach ($cid as $key => $value) {            			            
            if ($_attributesgroups->deleteAttrGroupsById($value)){
                $text[] = JText::_('COM_SMARTSHOP_ITEM_DELETED');
            }    
        }        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterRemoveAttributesGroups', array(&$cid));
        
        $this->setRedirect("index.php?option=com_jshopping&controller=attributesgroups", implode("</li><li>", $text));
    }
    
    function back(){
        $this->setRedirect("index.php?option=com_jshopping&controller=attributes");
    }
    
    function order(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $move = JFactory::getApplication()->input->getInt("move");        
        $row = JSFactory::getTable('attributesgroup', 'jshop');
        $row->load($id);
        $row->move($move);
        $this->setRedirect("index.php?option=com_jshopping&controller=attributesgroups");
    }
    
    function saveorder(){
        $cid = JFactory::getApplication()->input->getVar('cid', array(), 'post', 'array');
        $order = JFactory::getApplication()->input->getVar('order', array(), 'post', 'array');        
        
        foreach ($cid as $k=>$id){
            $table = JSFactory::getTable('attributesgroup', 'jshop');
            $table->load($id);
            if ($table->ordering!=$order[$k]){
                $table->ordering = $order[$k];
                $table->store();
            }
        }
        
          // print_r( $table);die; 
        /*$table = JSFactory::getTable('attributesgroup', 'jshop');
        $table->ordering = null;
        $table->reorder();*/    
        $this->setRedirect("index.php?option=com_jshopping&controller=attributesgroups");
    }
}