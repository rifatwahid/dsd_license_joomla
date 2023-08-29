<?php
/**
* @version      4.3.0 24.07.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerProductLabels extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("productlabels");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

	function display($cachable = false, $urlparams = false){
        $jshopConfig = JSFactory::getConfig();
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.productlabels";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "name", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
		$_productLabels = JSFactory::getModel("productLabels");
		$rows = $_productLabels->getList($filter_order, $filter_order_Dir);
        
		$view=$this->getView("product_labels", 'html');
        $view->setLayout("list");		
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);
        $view->set('config', $jshopConfig);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);       
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayProductLabels', array(&$view));		
		$view->displayList();
	}
	
	function edit(){
        $jshopConfig = JSFactory::getConfig();
		$id = JFactory::getApplication()->input->getInt("id");
		$productLabel = JSFactory::getTable('productLabel', 'jshop');
		$productLabel->load($id);
		$edit = ($id)?(1):(0);
		$_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
        JFilterOutput::objectHTMLSafe($productLabel, ENT_QUOTES);

		$view=$this->getView("product_labels", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('productLabel', $productLabel);
        $view->set('config', $jshopConfig);
        $view->set('edit', $edit);
		$view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditProductLabels', array(&$view));
		$view->displayEdit();
	}
	
	function save(){
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        require_once($jshopConfig->path.'lib/uploadfile.class.php');
	    
		$id = JFactory::getApplication()->input->getInt("id");
		$productLabel = JSFactory::getTable('productLabel', 'jshop');
        $post = $this->input->post->getArray();
		$lang = JSFactory::getLang();
        $post['name'] = $post[$lang->get("name")];
        $post['image'] = $post[$lang->get('image')];
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveProductLabel', array(&$post));
                
		if (!$productLabel->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=productlabels");
			return 0;
		}
	
		if (!$productLabel->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=productlabels");
			return 0;
		}
        
        $dispatcher->triggerEvent('onAfterSaveProductLabel', array(&$productLabel));
		
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=productlabels&task=edit&id=".$productLabel->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=productlabels");
        }
	}
	
	function remove(){
        $jshopConfig = JSFactory::getConfig();		
		$text = array();
        $productLabel = JSFactory::getTable('productLabel', 'jshop');
		$cid = JFactory::getApplication()->input->getVar("cid");
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveProductLabel', array(&$cid) );
		foreach ($cid as $key => $value) {
            $productLabel->load($value);
            @unlink($jshopConfig->image_labels_path."/".$productLabel->image);
            $productLabel->delete();			
            $text[] = JText::_('COM_SMARTSHOP_ITEM_DELETED')."<br>";			
		}
        $dispatcher->triggerEvent( 'onAfterRemoveProductLabel', array(&$cid) );
        
		$this->setRedirect("index.php?option=com_jshopping&controller=productlabels", implode("</li><li>", $text));
	}
    
    function delete_foto(){
        //$jshopConfig = JSFactory::getConfig();
        $id = JFactory::getApplication()->input->getInt("id");
		$lang = JFactory::getApplication()->input->getVar("lang");
        $productLabel = JSFactory::getTable('productLabel', 'jshop');
        $productLabel->load($id);
		$image='image_'.$lang;
        //@unlink($jshopConfig->image_labels_path."/".$productLabel->$image);		
        $productLabel->$image = "";
        $productLabel->image = "";
        $productLabel->store();
        die();               
    } 
    
}
?>