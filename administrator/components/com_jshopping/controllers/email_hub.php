<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerEmail_hub extends JControllerLegacy{
	
	protected $canDo;

    function display($cachable = false, $urlparams = false){
        checkAccessController("info");        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);
        addSubmenu("info",$this->canDo);        
        $jshopConfig = JSFactory::getConfig();                
        $view=$this->getView("email_hub", 'html');
        $view->setLayout("email_hub");
		$view->set("canDo", $this->canDo);
		$view->set("data",$data);
        $view->display();
    }

}
?>