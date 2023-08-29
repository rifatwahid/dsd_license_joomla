<?php
/**
* @version      3.9.0 22.07.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerManufacturers extends JControllerLegacy{
	
	protected $canDo;    

    function __construct( $config = array() ){
        parent::__construct( $config );        

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("manufacturers");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');        
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false) {
        $mainframe = JFactory::getApplication();
        $context = "jshopping.list.admin.manufacturers";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        $_manufacturer = JSFactory::getModel("manufacturers");
        $rows = $_manufacturer->getAllManufacturers(0, $filter_order, $filter_order_Dir);        
        $view=$this->getView("manufacturer", 'html');
        $view->setLayout("list");		
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayManufacturers', array(&$view));
        $view->displayList();
    }

    function edit() {
        $man_id = JFactory::getApplication()->input->getInt("man_id");
        $manufacturer = JSFactory::getTable('manufacturer', 'jshop');
        $manufacturer->load($man_id);
        $edit = ($man_id)?(1):(0);
        
        if (!$man_id){
            $manufacturer->manufacturer_publish = 1;
        }
        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
        
        $nofilter = array();
        JFilterOutput::objectHTMLSafe( $manufacturer, ENT_QUOTES, $nofilter);

        $view=$this->getView("manufacturer", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('manufacturer', $manufacturer);        
        $view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('etemplatevar', '');
        $view->set('multilang', $multilang);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditManufacturers', array(&$view));        
        $view->displayEdit();
    }

    function save(){
        $jshopConfig = JSFactory::getConfig();
        
        require_once ($jshopConfig->path.'lib/image.lib.php');
        require_once ($jshopConfig->path.'lib/uploadfile.class.php');
        
        
        $dispatcher = \JFactory::getApplication();
        
        $apply = JFactory::getApplication()->input->getVar("apply");
        $_alias = JSFactory::getModel("alias");        
        $man = JSFactory::getTable('manufacturer', 'jshop');        
        $man_id = JFactory::getApplication()->input->getInt("manufacturer_id");

        $post = $this->input->post->getArray();
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        foreach($languages as $lang){
            $post['name_'.$lang->language] = trim($post['name_'.$lang->language]);
            if (empty($post['alias_'.$lang->language])) {
                $post['alias_'.$lang->language] = $post['name_'.$lang->language];
            }
            $post['alias_'.$lang->language] = \JApplicationHelper::stringURLSafe($post['alias_'.$lang->language]);
            if ($post['alias_'.$lang->language]!="" && !$_alias->checkExistAlias1Group($post['alias_'.$lang->language], $lang->language, 0, $man_id)){
                $post['alias_'.$lang->language] =  strtolower($_alias->randomStringGenerator(10));
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_ALIAS_ALREADY_EXIST'),'error');
            }
            $post['description_'.$lang->language] = $_POST['description'.$lang->id];
            $post['short_description_'.$lang->language] = $_POST['short_description_'.$lang->id];
        }
        
        if (!$post['manufacturer_publish']){
            $post['manufacturer_publish'] = 0;
        }
        
        $dispatcher->triggerEvent( 'onBeforeSaveManufacturer', array(&$post) );
        
        if (!$man->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=manufacturers");
            return 0;
        }
        
        if (!$man_id){
            $man->ordering = null;
            $man->ordering = $man->getNextOrder();            
        }        
        
        $man->manufacturer_logo = $post['manufacturer_logo'];
        
        if (!$man->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=manufacturers");
            return 0;
        }
        
        $dispatcher->triggerEvent( 'onAfterSaveManufacturer', array(&$man) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=manufacturers&task=edit&man_id=".$man->manufacturer_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=manufacturers");
        }
        
    }

    function remove(){
        $cid = JFactory::getApplication()->input->getVar("cid");        
        $jshopConfig = JSFactory::getConfig();
        $text = array();
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveManufacturer', array(&$cid) );
        foreach ($cid as $key => $value) {
            $manuf = JSFactory::getTable('manufacturer', 'jshop');
            $manuf->load($value);
            $manuf->delete();
            
            $text[]= JText::sprintf('COM_SMARTSHOP_MANUFACTURER_DELETED', $value);
            if ($manuf->manufacturer_logo){
                @unlink($jshopConfig->image_manufs_path.'/'.$manuf->manufacturer_logo);
            }            
        }
        $dispatcher->triggerEvent( 'onAfterRemoveManufacturer', array(&$cid) );
        
        $this->setRedirect("index.php?option=com_jshopping&controller=manufacturers", implode("</li><li>",$text));
    }
    
    function publish(){
        $this->publishManufacturer(1);
    }
    
    function unpublish(){
        $this->publishManufacturer(0);
    }

    function publishManufacturer($flag) {
        $cid = JFactory::getApplication()->input->getVar("cid");
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforePublishManufacturer', array(&$cid, &$flag) );
		$_manufacturers = JSFactory::getModel('manufacturers');
        $_manufacturers->publishManufacturers($cid,$flag);
        $dispatcher->triggerEvent( 'onAfterPublishManufacturer', array(&$cid, &$flag) );        
        $this->setRedirect("index.php?option=com_jshopping&controller=manufacturers");
    }
    
    function delete_foto(){
        $id = JFactory::getApplication()->input->getInt("id");
        //$jshopConfig = JSFactory::getConfig();
        $manuf = JSFactory::getTable('manufacturer', 'jshop');
        $manuf->load($id);
        //@unlink($jshopConfig->image_manufs_path.'/'.$manuf->manufacturer_logo);
        $manuf->manufacturer_logo = "";
        $manuf->store();        
        die();
    }
    
    function order(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $move = JFactory::getApplication()->input->getInt("move");        
        $manuf = JSFactory::getTable('manufacturer', 'jshop');
        $manuf->load($id);
        $manuf->move($move);
        $this->setRedirect("index.php?option=com_jshopping&controller=manufacturers");
    }
    
    function saveorder(){
        $cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar( 'order', array(), 'post', 'array' );
        
        foreach ($cid as $k=>$id){
            $table = JSFactory::getTable('manufacturer', 'jshop');
            $table->load($id);
            if ($table->ordering!=$order[$k]){
                $table->ordering = $order[$k];
                $table->store();
            }        
        }
        
        //$table = JSFactory::getTable('manufacturer', 'jshop');
        //$table->ordering = null;
        //$table->reorder();        
                
        $this->setRedirect("index.php?option=com_jshopping&controller=manufacturers");
    }

    public function manufacturerOptions()
    {
        $jshopConfig = JSFactory::getConfig();
        $view = $this->getView('manufacturer', 'html');
        $view->setLayout('options');
		$view->set('canDo', $canDo ?? '');
        $view->set('jshopConfig', $jshopConfig);
        $view->displayOptions();
    }

    public function saveManufacturerOptions()
    {
        $jshopConfig = JSFactory::getTable('config');
        $jshopConfig->load(1);
        $savedParams = JFactory::getApplication()->input->post->getArray()['params'];

        $jshopConfig->bind($savedParams);
        $jshopConfig->store();

        $this->setRedirect('index.php?option=com_jshopping&controller=manufacturers');
    }

}
