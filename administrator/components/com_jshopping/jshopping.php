<?php
/**
* @version      4.8.0 20.09.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/lib/constants.php';

if (Joomla\CMS\Version::MAJOR_VERSION == 4) {
    $db = \JFactory::getDBO();
    $db->setQuery("set @@sql_mode = ''");
    $db->execute();
}

// Access check: is this user allowed to access the backend of this component?
if (!JFactory::getUser()->authorise('core.manage', 'com_jshopping'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}
require_once(JPATH_COMPONENT_SITE . '/lib/jtableauto.php');
JTable::addIncludePath(JPATH_COMPONENT_SITE.'/tables');
require_once(JPATH_COMPONENT_SITE."/lib/factory.php");
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/functions.php');

$ajax = JFactory::getApplication()->input->get('ajax');
$adminlang = JFactory::getLanguage();
if (!JFactory::getApplication()->input->get("js_nolang")){
    JSFactory::loadAdminLanguageFile();
}

$db = \JFactory::getDBO();
$jshopConfig = JSFactory::getConfig();
$jshopConfig->setLang($jshopConfig->getFrontLang());

if ($jshopConfig->adminLanguage!=$adminlang->getTag()){
	$config = new jshopConfig($db);
	$config->id = $jshopConfig->load_id;
	$config->adminLanguage = $adminlang->getTag();
	if (!$config->store()) {
		\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'');
		return 0;
	}
}
if (!$ajax){
    installNewLanguages();
}else{
    header('Content-Type: text/html;charset=UTF-8');
}

JPluginHelper::importPlugin('jshopping');
JPluginHelper::importPlugin('jshoppingadmin');
JPluginHelper::importPlugin('jshoppingmenu');
$dispatcher = \JFactory::getApplication();
$dispatcher->triggerEvent('onAfterLoadShopParamsAdmin', array());

//if (version_compare(JVERSION, '3.999.999', 'le')) JHtml::_('behavior.framework');
JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
if (!version_compare(JVERSION, '3.999.999', 'le')) $document->addScript('/media/vendor/jquery/js/jquery.js');
if ($jshopConfig->shop_mode) {
	$document->addScript($jshopConfig->live_admin_path.'js/src/deprecated.js');
	$document->addScript($jshopConfig->live_admin_path.'js/src/index.js', [], [
		'type' => 'module'
	]);
} else {
	$document->addScript($jshopConfig->live_admin_path.'js/dist/index.min.js');
}

if (!isJoomla4()) {
	$document->addStyleSheet($jshopConfig->live_admin_path . 'css/bootstrap_4_grid.css');
	$document->addStyleSheet($jshopConfig->live_admin_path . 'css/bootstrap_4_utilities.css');
}
$document->addStyleSheet($jshopConfig->live_admin_path.'css/style.css');
$document->addStyleSheet($jshopConfig->live_admin_path.'lib/fontawesome/css/all.css');
$controller = JFactory::getApplication()->input->get('controller');
if (!$controller) $controller = "panel";
$dispatcher->triggerEvent('onAfterGetControllerAdmin', array(&$controller));

if (file_exists(JPATH_COMPONENT.'/controllers/'.$controller.'.php'))
    require_once( JPATH_COMPONENT.'/controllers/'.$controller.'.php' );
else
	throw new \Exception(JText::_('Access Forbidden'), 403);

$classname = 'JshoppingController'.$controller;
$controller = new $classname();
$controller->execute(JFactory::getApplication()->input->getVar('task'));
$controller->redirect();
