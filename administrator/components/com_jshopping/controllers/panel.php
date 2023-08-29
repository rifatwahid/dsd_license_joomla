<?php
/**
* @version      3.6.1 10.08.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerPanel extends JControllerLegacy{
	
	protected $canDo;
	
    function display($cachable = false, $urlparams = false){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        checkAccessController("panel");
        addSubmenu("",$this->canDo);
		$view=$this->getView("panel", 'html');
        $view->setLayout("cahomenew");//#INSTALLATION$ home
		$view->set("canDo", $this->canDo);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayHomePanel', array(&$view));
		$view->displayHome(); 
    }
}
?>		