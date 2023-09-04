<?php
/**
* @version      4.6.1 10.08.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerOther extends JControllerLegacy{
	
	protected $canDo;    

    public function display($cachable = false, $urlparams = false){
		$_search = JSFactory::getModel('search');    
		$text_search=JFactory::getApplication()->input->getVar('text_search');		
		
		$rows=$_search->getSearchResults();
		$rows=$_search->scanLinks($text_search,$rows);		
		$_search->getResultInCurrentLanguage($rows);
		
        checkAccessController("other");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');        
        addSubmenu("other",$this->canDo);
        $view=$this->getView("panel", 'html');
        $view->setLayout("options");
        $view->set("canDo", $this->canDo);
		$view->set("rows", $rows);
		$view->set("text_search", $text_search);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayOptionsPanel', array(&$view));
        $view->displayOptions();
    }

    
}
?>		