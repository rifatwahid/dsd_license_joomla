<?php
/**
* @version      3.3.0 12.12.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerFreeAttributes extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("freeattributes");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.freeattributes";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
    	$_freeattributes = JSFactory::getModel("freeattribut");    	
        $rows = $_freeattributes->getAll($filter_order, $filter_order_Dir);
        $view=$this->getView("freeattributes", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayFreeAttributes', array(&$view));
        $view->displayList();
    }
	
    function edit() {
        $jshopConfig = JSFactory::getConfig();
        $id = JFactory::getApplication()->input->getInt("id");
	
        $freeattribut = JSFactory::getTable('freeattribut', 'jshop');
        $freeattribut->load($id);
        $unitModel = JSFactory::getModel("units");
        $_lang = \JSFactory::getModel("languages");
		$unitList = $unitModel->getFreeAttrUnitsList($freeattribut->unit_id);
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
        
        JFilterOutput::objectHTMLSafe($freeattribut, ENT_QUOTES);		

        $view = $this->getView("freeattributes", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('attribut', $freeattribut);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('unitList', $unitList);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeEditFreeAtribut', array(&$view, &$freeattribut) );
        $view->displayEdit();
		
	}
	
	function save() {
		$id = JFactory::getApplication()->input->getInt('id');
		$_lang = \JSFactory::getModel("languages");
        
        $attribut = JSFactory::getTable('freeattribut', 'jshop');    
        $post = $this->input->post->getArray();
        if (!isset($post['required']) || !$post['required']) $post['required'] = 0;
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveFreeAtribut', array(&$post) );
        
        if (!$id){
            $attribut->ordering = null;
            $attribut->ordering = $attribut->getNextOrder();            
        }
                
		$languages = $_lang->getAllLanguages();	
		foreach($languages as $lang){
            $post['description_'.$lang->language] = $_POST['description_'.$lang->language];
        }
		
        if (!$attribut->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=freeattributes");
            return 0;
        }

        if (!$attribut->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=freeattributes");
            return 0;
        }
        
        $dispatcher->triggerEvent( 'onAfterSaveFreeAtribut', array(&$attribut) );
        
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=freeattributes&task=edit&id=".$attribut->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=freeattributes");
        }        
	}
	
	function remove(){
		$cid = JFactory::getApplication()->input->getVar("cid");
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveFreeAtribut', array(&$cid) );
		$_freeattribut = JSFactory::getModel('freeattribut');
		$_freeattribut->deleteFreeattribut($cid);
        $dispatcher->triggerEvent( 'onAfterRemoveFreeAtribut', array(&$cid) );
		$this->setRedirect("index.php?option=com_jshopping&controller=freeattributes", JText::_('COM_SMARTSHOP_ATTRIBUT_DELETED'));
	}
	
	function order(){
		$id = JFactory::getApplication()->input->getInt("id");
        $move = JFactory::getApplication()->input->getInt("move");        
        $obj = JSFactory::getTable('freeattribut', 'jshop');
        $obj->load($id);
        $obj->move($move);
        $this->setRedirect("index.php?option=com_jshopping&controller=freeattributes");
	}
    
    function saveorder(){
        $cid = JFactory::getApplication()->input->getVar('cid', array(), 'post', 'array');
        $order = JFactory::getApplication()->input->getVar('order', array(), 'post', 'array');

        foreach($cid as $k=>$id){
            $table = JSFactory::getTable('freeattribut', 'jshop');
            $table->load($id);
            if ($table->ordering!=$order[$k]){
                $table->ordering = $order[$k];
                $table->store();
            }
        }
        
        $table = JSFactory::getTable('freeattribut', 'jshop');
        $table->ordering = null;
        $table->reorder();
                
        $this->setRedirect("index.php?option=com_jshopping&controller=freeattributes");
    }
      
}
?>