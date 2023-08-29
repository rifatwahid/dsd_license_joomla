<?php
/**
* @version      7.0.6 04.05.2023
* @author       
* @package      
* @copyright    Copyright (C) 2010 All rights reserved.
* @license      GNU/GPL
*/


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class JshoppingControllerLicense extends JControllerLegacy
{
    protected $canDo;

    public function __construct($config = array()) {
        parent::__construct($config);
        checkAccessController("license");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? ''); 		
        addSubmenu("other", $this->canDo);
		$this->registerTask('display', 'display'); 
        $this->registerTask('save', 'save');
    }

    function display($cachable = false, $urlparams = false) {		
        $password = JFactory::getApplication()->getUserState("com_jshopping.password", "");
        $enteredPassword = JFactory::getApplication()->input->getString('password');
        if ($enteredPassword == 'your_password' || $password == 'your_password') {
            JFactory::getApplication()->setUserState("com_jshopping.password", 'your_password');
            $view = $this->getView("license", "html");
			$view->set('canDo', $this->canDo ?? '');
            $view->setLayout("default");
            $view->display();
        } else {
            $view = $this->getView("license", "html");
			$view->set('canDo', $this->canDo ?? '');
            $view->setLayout("password");
            $view->display();
        }
    }

	function save() {
		//Load required files
		require_once(JPATH_COMPONENT_SITE . "/fonts/comfortaa.php");
		require_once(JPATH_COMPONENT_SITE . "/fonts/fredoka.php");

		$license_notifications_array = aplInstallLicense($_POST["ROOT_URL"], $_POST["CLIENT_EMAIL"], $_POST["LICENSE_CODE"]);
		if ($license_notifications_array['notification_case'] == "notification_license_ok") {
			$message = "License installation succeeded!";
			$msgType = 'message';
		} else {
			$message = "License installation failed because of this reason: " . $license_notifications_array['notification_text'];
			$msgType = 'error';
		}
		$this->setRedirect(JRoute::_('index.php?option=com_jshopping&controller=license', false), $message, $msgType);
	}
}

?>