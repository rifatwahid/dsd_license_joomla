<?php
/**
* @version      3.5.2 20.03.2012
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerStates extends JControllerLegacy
{
	protected $canDo; 
	
	public function __construct($config = [])
	{
        parent::__construct( $config );
		//JFactory::getLanguage()->load('addon_smartshop_states');
        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        checkAccessController("states");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? ''); 
        addSubmenu("other", $this->canDo);
    }

	public function display($cachable = false, $urlparams = false) : void
	{
        $mainframe = JFactory::getApplication();
		$context = "jshoping.list.admin.states";
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
        $publish = $mainframe->getUserStateFromRequest( $context.'publish', 'publish', 0, 'int' );
        $country_id = $mainframe->getUserStateFromRequest( $context.'country_id', 'country_id', 0, 'int' );  

		$states = $this->getModel("states");

        if ($publish == 0) {
            $total = $states->getCountAllStates($country_id); 
        } else {
            $total = $states->getCountPublishStates($publish % 2, $country_id);
        }
		
		jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);
		$rows = $states->getAllStates($publish, $pageNav->limitstart,$pageNav->limit, 0, $country_id);
        
        $countries = $states->getAllCountries(); 
		$parentTop = new stdClass();
        $parentTop->country_id = 0;
        $parentTop->name = JText::_('COM_SMARTSHOP_ALL');

        array_unshift($countries, $parentTop);    
        $filter = array();
		$filter['countries'] = JHTML::_('select.genericlist', $countries,'country_id','class="inputbox form-select" onchange = "document.adminForm.submit();"','country_id','name', $country_id );

        $f_option = array();
        $f_option[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_ALL'), 'id', 'name');
        $f_option[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PUBLISH'), 'id', 'name');
        $f_option[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_UNPUBLISH'), 'id', 'name');
        
        $filter['publish'] = JHTML::_('select.genericlist', $f_option, 'publish', 'class="form-select" onchange="document.adminForm.submit();"', 'id', 'name', $publish);
        
        $country_active= $states->getCountryById($country_id);
                
		$view = $this->getView("states", 'html');
        $view->setLayout("list");	
        $view->set('country_active', $country_active); 	
        $view->set('rows', $rows); 
        $view->set('pageNav', $pageNav);       
        $view->set('filter', $filter);
		$view->displayList(); 
    }
    
	public function edit() : void
	{
		$state_id = JFactory::getApplication()->input->getInt("state_id");
        
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.states";        
        $country_id = $mainframe->getUserStateFromRequest( $context.'country_id', 'country_id', 0, 'int' );          
        
		$states = $this->getModel("states");   
		$state = JTable::getInstance('state', 'jshop');
		$state->load($state_id);
        
		$first = array();
		$first[] = JHTML::_('select.option', '0', JText::_('COM_SMARTSHOP_ORDERING_FIRST') ,'ordering','name');
		$rows = array_merge($first, $states->getAllStates(0,null,null,1,$country_id));
		$lists = array();
		$lists['order_states'] = JHTML::_('select.genericlist', $rows,'ordering','class="inputbox form-select" size="1"','ordering','name', $state->ordering);
        
        $_lang = $this->getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;        
        
		$edit = ($state_id)?($edit = 1):($edit = 0);                
        
        JFilterOutput::objectHTMLSafe( $state, ENT_QUOTES);

        $countries = $states->getAllCountries(); 
        $parentTop = new stdClass();
		$parentTop->country_id = 0;
        $parentTop->name = JText::_('COM_SMARTSHOP_ALL');

        if ($state_id) $country_id = $state->country_id;
        
        array_unshift($countries, $parentTop);    
        $countries_list = JHTML::_('select.genericlist', $countries,'country_id','class="inputbox form-select" ','country_id','name', $country_id );

		$view=$this->getView("states", 'html');
        $view->setLayout("edit");
        $view->set('countries_list', $countries_list);		
        $view->set('state', $state); 
        $view->set('lists', $lists);       
        $view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        JPluginHelper::importPlugin('jshoppingadmin');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditStates', array(&$view));
		$view->displayEdit();
	}

	public function save()
	{
        JPluginHelper::importPlugin('jshoppingadmin');
        $dispatcher = \JFactory::getApplication();
        
		$state_id = JFactory::getApplication()->input->getInt("state_id");		
		$post = JFactory::getApplication()->input->post->getArray();
        
        $dispatcher->triggerEvent( 'onBeforeSaveState', array(&$post) );
        
		$state = JTable::getInstance('state', 'jshop');
        
		if (!$state->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=states");
			return 0;
		}
	
		if (!$state->state_publish){
			$state->state_publish = 0;
		}
		
		$this->_reorderState($state);
		if (!$state->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=states");
			return 0;
		}
                
        $dispatcher->triggerEvent( 'onAfterSaveState', array(&$state) );
        
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=states&task=edit&state_id=".$state->state_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=states");
        }
	}
	
	function _reorderState(&$state) : void
	{
		$db = \JFactory::getDBO();
		$query = "UPDATE `#__jshopping_states` SET `ordering` = ordering + 1
					WHERE `ordering` > '" . $state->ordering . "' ";
		$db->setQuery($query);
		$db->execute();
		$state->ordering++;
	}
	
	public function order() : void
	{
		$order = JFactory::getApplication()->input->getVar("order"); 
		$cid = JFactory::getApplication()->input->getInt("id");
		$number = JFactory::getApplication()->input->getInt("number");
		$db = \JFactory::getDBO();
        
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.states";        
        $country_id = $mainframe->getUserStateFromRequest( $context.'country_id', 'country_id', 0, 'int' );  
        if ($country_id!=0)   $where_country= " AND country_id = ".$country_id." ";
        else $where_country = "";    

		switch ($order) {
			case 'up':
				$query = "SELECT a.state_id, a.ordering
					   FROM `#__jshopping_states` AS a
					   WHERE a.ordering < '" . $number . "' ".$where_country." 
					   ORDER BY a.ordering DESC
					   LIMIT 1";
				break;
			case 'down':
				$query = "SELECT a.state_id, a.ordering
					   FROM `#__jshopping_states` AS a
					   WHERE a.ordering > '" . $number . "' ".$where_country." 
					   ORDER BY a.ordering ASC
					   LIMIT 1";
		}
	
		$db->setQuery($query);
		$row = $db->loadObject();
	
	
		$query1 = "UPDATE `#__jshopping_states` AS a
					 SET a.ordering = '" . $row->ordering . "'
					 WHERE a.state_id = '" . $cid . "'";
		$query2 = "UPDATE `#__jshopping_states` AS a
					 SET a.ordering = '" . $number . "'
					 WHERE a.state_id = '" . $row->state_id . "'";
		$db->setQuery($query1);
		$db->execute();
		$db->setQuery($query2);
		$db->execute();
		
		$this->setRedirect("index.php?option=com_jshopping&controller=states");
	}
	
	public function remove() : void
	{
		$db = \JFactory::getDBO();
		$query = '';
		$text = '';
		$cid = JFactory::getApplication()->input->getVar("cid");
        JPluginHelper::importPlugin('jshoppingadmin');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveState', array(&$cid) );
        
		foreach ($cid as $key => $value) {
			$query = "DELETE FROM `#__jshopping_states`
					   WHERE `state_id` = '" . $db->escape($value) . "'";
			$db->setQuery($query);
			if ($db->execute())
				$text .= JText::_('COM_SMARTSHOP_STATES_DELETED')."<br>";
			else
				$text .= JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')."<br>";	
		}
        
        $dispatcher->triggerEvent( 'onAfterRemoveState', array(&$cid) );

		$this->setRedirect("index.php?option=com_jshopping&controller=states", $text);
	}
	
	public function publish() : void
	{
        $this->publishState(1);
    }
    
	public function unpublish() : void
	{
        $this->publishState(0);
    }
    
	public function publishState($flag) : void
	{
		$cid = JFactory::getApplication()->input->getVar("cid");
		$db = \JFactory::getDBO();
        JPluginHelper::importPlugin('jshoppingadmin');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforePublishState', array(&$cid, &$flag) );
		foreach ($cid as $key => $value) {
			$query = "UPDATE `#__jshopping_states` SET `state_publish` = '".$db->escape($flag)."' WHERE `state_id` = '" . $db->escape($value) . "'";
			$db->setQuery($query);
			$db->execute();
		}
                
        $dispatcher->triggerEvent('onAfterPublishState', array(&$cid, &$flag) );
		
		$this->setRedirect("index.php?option=com_jshopping&controller=states");
	}
    
	public function saveorder() : void
	{
        $cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar( 'order', array(), 'post', 'array' );
        
        foreach ($cid as $k=>$id){
            $state = JTable::getInstance('state', 'jshop');
            $state->load($id);
            if ($state->ordering!=$order[$k]){
                $state->ordering = $order[$k];
                $state->store();
            }        
        }
        
        $state = JTable::getInstance('state', 'jshop');
        $state->reorder();        
        $this->setRedirect("index.php?option=com_jshopping&controller=states");
    } 
}

?>