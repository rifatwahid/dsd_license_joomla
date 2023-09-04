<?php
/**
* @version      4.7.0 27.09.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\Event\Event;

require_once("fonts/comfortaa.php");
require_once("fonts/fredoka.php");
$license_notifications_array=aplVerifyLicense();

if ($license_notifications_array['notification_case']=="notification_license_ok") //'notification_license_ok' case returned - operation succeeded
    {
	jimport('joomla.application.component.model');
	jimport( 'joomla.html.html' );
	Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT_SITE . '/helpers/html/');
	Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_ROOT . '/components/com_jshopping/helpers/html/');


	JTable::addIncludePath(JPATH_COMPONENT . '/tables');
	JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');

	if (Joomla\CMS\Version::MAJOR_VERSION == 4) {
		$db = \JFactory::getDBO();
		$db->setQuery("set @@sql_mode = ''");
		$db->execute();
	}

	require_once JPATH_COMPONENT_SITE . '/lib/constants.php';
	require_once JPATH_COMPONENT_SITE . '/lib/jtableauto.php';
	require_once JPATH_COMPONENT_SITE . '/lib/factory.php';
	require_once JPATH_COMPONENT_SITE . '/controllers/base.php';

	$controller = getJsFrontRequestController();
	require __DIR__ . '/loadparams.php';

	if (file_exists(JPATH_COMPONENT . "/controllers/{$controller}.php")) {
		require_once(JPATH_COMPONENT . "/controllers/{$controller}.php");
	} else {
		throw new Exception(JText::_('Access Forbidden'),403);
	}

	$_storage=JSFactory::getModel('storage');
	$_storage->checkDeleteUploads();
	$classname = 'JshoppingController' . $controller;
	$jshopConfig = JSFactory::getConfig();
	$document = JFactory::getDocument();
	$document->addStyleSheet($jshopConfig->live_path.'lib/fontawesome/css/all.css');
	$dispatcher = Factory::getApplication()->getDispatcher();
	$event = new Event('onBeforeSmartshop');
	$dispatcher->dispatch('onBeforeSmartshop', $event);
	$controller = new $classname();
	$controller->execute(JFactory::getApplication()->input->get('task'));
	$controller->redirect();
}
