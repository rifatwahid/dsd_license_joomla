<?php
/**
* @version      4.8.0 20.11.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerLanguages extends JControllerLegacy{
    
	protected $canDo;    
	
    function __construct( $config = array() ){
        parent::__construct( $config );
        checkAccessController("languages");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);        
        addSubmenu("other",$this->canDo);
    }

    function display($cachable = false, $urlparams = false){  	        		
        $_languages = JSFactory::getModel("languages");
        $rows = $_languages->getAllLanguages(0);
        $jshopConfig = JSFactory::getConfig();        
                
		$view=$this->getView("languages_list", 'html');		
        $view->set('rows', $rows);
        $view->set('default_front', $jshopConfig->getFrontLang());
        $view->set('defaultLanguage', $jshopConfig->defaultLanguage);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayLanguage', array(&$view));
		$view->display(); 
        
    }
    
    function publish(){
        $this->publishLanguage(1);
    }
    
    function unpublish(){
        $this->publishLanguage(0);
    }

    function publishLanguage($flag) {        
        $cid = JFactory::getApplication()->input->getVar("cid");
		$_languages = JSFactory::getModel('languages');
        $_languages->publishLanguages($cid,$flag);
        $this->setRedirect("index.php?option=com_jshopping&controller=languages");
    }
        
}
?>