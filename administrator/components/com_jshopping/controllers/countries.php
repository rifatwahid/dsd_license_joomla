<?php
/**
* @version      2.9.4 31.07.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerCountries extends JControllerLegacy{

	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("countries");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
		
    }

    function display($cachable = false, $urlparams = false){  	        		
        $mainframe = JFactory::getApplication();
		$context = "jshoping.list.admin.countries";
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
        $publish = $mainframe->getUserStateFromRequest( $context.'publish', 'publish', 0, 'int' );
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');        
		
		$_countries = JSFactory::getModel("countries");
		$total = $_countries->getCountAllCountries();        
       
        if ($publish == 0) {
            $total = $_countries->getCountAllCountries();
        } else {
            $total = $_countries->getCountPublishCountries($publish % 2);
        }
		
		jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);		
        $rows = $_countries->getAllCountries($publish, $pageNav->limitstart,$pageNav->limit, 0, $filter_order, $filter_order_Dir);
        
        $f_option = array();
        $f_option[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_ALL'), 'id', 'name');
        $f_option[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PUBLISH'), 'id', 'name');
        $f_option[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_UNPUBLISH'), 'id', 'name');
        
        $filter = JHTML::_('select.genericlist', $f_option, 'publish', 'class="form-select" onchange="document.adminForm.submit();"', 'id', 'name', $publish);
                
		$view=$this->getView("countries", 'html');
        $view->setLayout("list");		
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows); 
        $view->set('pageNav', $pageNav);       
        $view->set('filter', $filter);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayCountries', array(&$view));
		$view->displayList(); 
    }
    
   	function edit() {
		$country_id = JFactory::getApplication()->input->getInt("country_id");
		$_countries = JSFactory::getModel("countries");
		$country = JSFactory::getTable('country', 'jshop');
		$country->load($country_id);
		$first[] = JHTML::_('select.option', '0',JText::_('COM_SMARTSHOP_ORDERING_FIRST'),'ordering','name');
		$rows = array_merge($first, $_countries->getAllCountries(0));
		$lists['order_countries'] = JHTML::_('select.genericlist', $rows,'ordering','class="inputbox form-select" size="1"','ordering','name', $country->ordering);
        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;        
        
		$edit = ($country_id)?($edit = 1):($edit = 0);                
        
        JFilterOutput::objectHTMLSafe( $country, ENT_QUOTES);

		$view=$this->getView("countries", 'html');
        $view->setLayout("edit");		
		$view->set('canDo', $canDo ?? '');
        $view->set('country', $country); 
        $view->set('lists', $lists);       
        $view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('etemplatevar', '');
        $view->set('multilang', $multilang);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditCountries', array(&$view));
		$view->displayEdit();
	}
	
	function save() {
        
        $dispatcher = \JFactory::getApplication();
        
		$country_id = JFactory::getApplication()->input->getInt("country_id");		
	    $post = JFactory::getApplication()->input->post->getArray();
        
        $dispatcher->triggerEvent( 'onBeforeSaveCountry', array(&$post) );
        
		$country = JSFactory::getTable('country', 'jshop');
		if (!$country->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=countries");
			return 0;
		}
	
		if (!$country->country_publish){
			$country->country_publish = 0;
	    }    
		$this->_reorderCountry($country);
		if (!$country->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=countries");
			return 0;
		}
                
        $dispatcher->triggerEvent( 'onAfterSaveCountry', array(&$country) );
        
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=countries&task=edit&country_id=".$country->country_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=countries");
        }
	}
	
	function _reorderCountry(&$country) {
		$_countries = JSFactory::getModel('countries');
		$_countries->moveUpCountryOrdering($country->ordering);
		$country->ordering++;
	}
	
	function order(){
		$order = JFactory::getApplication()->input->getVar("order"); 
		$cid = JFactory::getApplication()->input->getInt("id");
		$number = JFactory::getApplication()->input->getInt("number");				
		$_countries = JSFactory::getModel('countries');
		$_countries->orderingChange($order,$cid,$number);		
		$this->setRedirect("index.php?option=com_jshopping&controller=countries");
	}
	
	function remove() {		
		$_countries = JSFactory::getModel('countries');		
		$cid = JFactory::getApplication()->input->getVar("cid");        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveCountry', array(&$cid) );		
		$text=$_countries->deleteCountry($cid,$number,$order);		 
        $dispatcher->triggerEvent( 'onAfterRemoveCountry', array(&$cid) );
		$this->setRedirect("index.php?option=com_jshopping&controller=countries", $text);
	}
	
    function publish(){
        $this->publishCountry(1);
    }
    
    function unpublish(){
        $this->publishCountry(0);
    }
    
	function publishCountry($flag) {
		$_countries = JSFactory::getModel('countries');		
		$cid = JFactory::getApplication()->input->getVar("cid");		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforePublishCountry', array(&$cid, &$flag) );	
        
		$_countries->publishCountry($cid,$flag);		                
		
        $dispatcher->triggerEvent( 'onAfterPublishCountry', array(&$cid, &$flag) );		
		$this->setRedirect("index.php?option=com_jshopping&controller=countries");
	}
    
    function saveorder(){
        $cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar( 'order', array(), 'post', 'array' );
        
        foreach ($cid as $k=>$id){
            $country = JSFactory::getTable('country', 'jshop');
            $country->load($id);
            if ($country->ordering!=$order[$k]){
                $country->ordering = $order[$k];
                $country->store();
            }        
        }
        
        //$country = JSFactory::getTable('country', 'jshop');
        //$country->reorder();        
                
        $this->setRedirect("index.php?option=com_jshopping&controller=countries");
    }
    
      
}

?>