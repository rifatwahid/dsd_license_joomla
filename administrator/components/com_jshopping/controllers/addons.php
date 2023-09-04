<?php
/**
* @version      4.6.1 05.11.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerAddons extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct($config = array()){
        parent::__construct( $config );        
        checkAccessController("addons");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    
    function display($cachable = false, $urlparams = false){        
        $_addons = JSFactory::getModel("addons");
        $rows = $_addons->getAddonsListWithConfigFiles();		        
        $view=$this->getView("addons", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows); 
        $view->set('back64', base64_encode("index.php?option=com_jshopping&controller=addons"));
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayAddons', array(&$view));		
        $view->displayList();
    }
    
    function edit(){
        $id = JFactory::getApplication()->input->getVar("id");
        
        $dispatcher = \JFactory::getApplication();
        $row = JSFactory::getTable('addon', 'jshop');
        $row->load($id);
        $config_file_patch = JPATH_COMPONENT_SITE."/addons/".$row->alias."/config.tmpl.php";
        $config_file_exist = file_exists($config_file_patch);

        $view=$this->getView("addons", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('row', $row);
        $view->set('params', $row->getParams());
        $view->set('config_file_patch', $config_file_patch);
        $view->set('config_file_exist', $config_file_exist);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditAddons', array(&$view));
        $view->displayEdit();
    }
    
    function save(){
        $this->saveConfig('save');
    }
    
    function apply(){
        $this->saveConfig();
    }
    
    private function saveConfig($task = 'apply'){
        $post = JFactory::getApplication()->input->post->getArray();
        $row = JSFactory::getTable('addon', 'jshop');
        $params = $post['params'];
        if (!is_array($params)) $params = array();
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveAddons', array(&$params, &$post, &$row));
        $row->bind($post);
        $row->setParams($params);
        $row->store();
		$dispatcher->triggerEvent('onAfterSaveAddons', array(&$params, &$post, &$row));
		
        if ($task == 'save'){
            $this->setRedirect("index.php?option=com_jshopping&controller=addons");
        } else {
            $this->setRedirect("index.php?option=com_jshopping&controller=addons&task=edit&id=".$post['id']);
        }
    }

    function remove(){
        $id = JFactory::getApplication()->input->getVar("id");        
        $text = '';
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeRemoveAddons', array(&$id) );
        $row = JSFactory::getTable('addon', 'jshop');
        $row->load($id);
        if ($row->uninstall){
            include(JPATH_ROOT.$row->uninstall);
        }
        $row->delete();
        $dispatcher->triggerEvent( 'onAfterRemoveAddons', array(&$id, &$text) );        
        $this->setRedirect("index.php?option=com_jshopping&controller=addons", $text);
    }
    
    function info(){
        $id = JFactory::getApplication()->input->getVar("id");        
        $dispatcher = \JFactory::getApplication();
        $row = JSFactory::getTable('addon', 'jshop');
        $row->load($id);
        $file_patch = JPATH_COMPONENT_SITE."/addons/".$row->alias."/info.tmpl.php";
        $file_exist = file_exists($file_patch);

        $view=$this->getView("addons", 'html');
        $view->setLayout("info");
		$view->set('canDo', $canDo ?? '');
        $view->set('row', $row);
        $view->set('params', $row->getParams());
        $view->set('file_patch', $file_patch);
        $view->set('file_exist', $file_exist);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeInfoAddons', array(&$view));
        $view->displayInfo();
    }
    
    function version(){
        $id = JFactory::getApplication()->input->getVar("id");        
        $dispatcher = \JFactory::getApplication();
        $row = JSFactory::getTable('addon', 'jshop');
        $row->load($id);
        $file_patch = JPATH_COMPONENT_SITE."/addons/".$row->alias."/version.tmpl.php";
        $file_exist = file_exists($file_patch);

        $view=$this->getView("addons", 'html');
        $view->setLayout("info");
		$view->set('canDo', $canDo ?? '');
        $view->set('row', $row);
        $view->set('params', $row->getParams());
        $view->set('file_patch', $file_patch);
        $view->set('file_exist', $file_exist);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeVersionAddons', array(&$view));
        $view->displayVersion();
    }
    
}
?>