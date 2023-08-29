<?php
/**
* @version      3.9.0 31.07.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerInfo extends JControllerLegacy{
	
	protected $canDo;

    function display($cachable = false, $urlparams = false){
        checkAccessController("info");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);        
        addSubmenu("info",$this->canDo);
        
        $jshopConfig = JSFactory::getConfig();        
        $data = JApplicationHelper::parseXMLInstallFile($jshopConfig->admin_path."jshopping.xml");
		if ($jshopConfig->display_updates_version){
		    $_info = JSFactory::getModel("info");
		    $update = $_info->getUpdateObj($data['version'], $jshopConfig);
        }else{
            $update = new stdClass();
        }
        $view=$this->getView("panel", 'html');
        $view->setLayout("cainfo");//#INSTALLATION$ info
		$view->set("canDo", $this->canDo);
		$view->set("data",$data);
        $view->set("update",$update);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayInfo', array(&$view));
        $view->displayInfo();
    }

}
?>