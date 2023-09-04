<?php
/**
* @version      4.8.0 03.11.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerUserGroups extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );
        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        checkAccessController("usergroups");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.usergroups";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "usergroup_id", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
		$_usergroups = JSFactory::getModel("usergroups");
		$rows = $_usergroups->getAllUsergroups($filter_order, $filter_order_Dir);
		        
        $view=$this->getView("usergroups", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set("rows", $rows);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayUserGroups', array(&$view));
        $view->displayList();
    }
	function custom_options($cachable = false, $urlparams = false) {
		$jshopConfig = JSFactory::getConfig();
        $config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');

		
		$view=$this->getView("usergroups", 'html');
        $view->setLayout("configurations");
		$view->set('canDo', $canDo ?? '');
		$jshopConfig = JSFactory::getConfig();
        $view->set("other_config", $other_config);
        $view->set("other_config_checkbox", $other_config_checkbox);
        $view->set("other_config_select", $other_config_select);
        $view->set("config", $jshopConfig);
        $view->set('etemplatevar', '');		
        $view->displayConfigurations();
	}  
    function configurations_apply($cachable = false, $urlparams = false) {
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$post = $this->input->post->getArray();
	
	
		$array = array('display_user_groups_info','display_user_group');	
		foreach ($array as $key => $value) {
			if (!isset($post[$value])) $post[$value] = 0;
		}
		
		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		foreach($catprod_other_config as $k){
			$result[$k] = $post[$k];
		}
		$post['other_config'] = serialize($result);
		
		
		
		$result = array();
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		$result['display_user_groups_info'] = $post['display_user_groups_info'];
		$result['display_user_group'] = $post['display_user_group'];
		$post['other_config'] = serialize($result);		
		
		
		
		$config = new jshopConfig($db);
		$config->id = $jshopConfig->load_id;
		
		if (!$config->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=taxes');
			return 0;
		}		
		if (!$config->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')." ".$config->_error,'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=taxes');
			return 0;
		} 
		$this->setRedirect('index.php?option=com_jshopping&controller=usergroups',JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
	}
	
	function edit(){
		$usergroup_id = JFactory::getApplication()->input->getInt("usergroup_id");
		$usergroup = JSFactory::getTable('userGroup', 'jshop');
		$usergroup->load($usergroup_id);
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
	
        $edit = ($usergroup_id) ? 1 : 0;
        JFilterOutput::objectHTMLSafe($usergroup, ENT_QUOTES, "usergroup_description");
        
		$view=$this->getView("usergroups", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set("usergroup", $usergroup);
        $view->set('etemplatevar', '');
        $view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditUserGroups', array(&$view));
        $view->displayEdit();
	}
	
	function save(){
	    $mainframe = JFactory::getApplication();
		$usergroup_id = JFactory::getApplication()->input->getInt("usergroup_id");
		$usergroup = JSFactory::getTable('userGroup', 'jshop');
		$_usergroups = JSFactory::getModel("usergroups");        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $lang = JSFactory::getLang();
		
        $post = $this->input->post->getArray();
        foreach($languages as $v){
            $post['name_'.$v->language] = trim($post['name_'.$v->language]);
            $post['description_'.$v->language] = $_POST['description'.$v->id];
        }        
        $post['usergroup_name'] = $post[$lang->get("name")];
        $post['usergroup_description'] = $post[$lang->get("description")];
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveUserGroup', array(&$post));
       
		if (!$usergroup->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=usergroups");
		}
		if ($usergroup->usergroup_is_default){
			$default_usergroup_id = $_usergroups->resetDefaultUsergroup();
		}

		if (!$usergroup->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$_usergroups->setDefaultUsergroup($default_usergroup_id);
			$this->setRedirect("index.php?option=com_jshopping&controller=usergroups");
		}
        
        if(!$post['usergroup_id']){
			$_usergroups->addToShippingPayment($usergroup->usergroup_id);
		}
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onAfterSaveUserGroup', array(&$usergroup) );
        
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=usergroups&task=edit&usergroup_id=".$usergroup->usergroup_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=usergroups");
        }
		
	}
	
	function remove(){
		$_usergroups = JSFactory::getModel("usergroups");
		$cid = JFactory::getApplication()->input->getVar("cid");				
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveUserGroup', array(&$cid) );
		$text = "";
		$defaultUserGroup = $_usergroups->getDefaultUsergroup();
		foreach ($cid as $key=>$value){
			$usergroup_name=$_usergroups->getUsergroupNameById($value);	
			if($defaultUserGroup == $value){				
				\JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_USERGROUP_DEFAULT_DELETED_ERROR', $usergroup_name), 'error');
			}else{
				if ($_usergroups->deleteUsergroupById($value)){
					$text .= JText::sprintf('COM_SMARTSHOP_USERGROUP_DELETED', $usergroup_name)."<br>"; 
				}
			}			
		}
        $dispatcher->triggerEvent( 'onAfterRemoveUserGroup', array(&$cid) );
        
		$this->setRedirect("index.php?option=com_jshopping&controller=usergroups", $text);		
	}
       
}
?>