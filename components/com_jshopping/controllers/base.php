<?php
/**
* @version      4.14.0 10.05.2016
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

class JshoppingControllerBase extends JControllerLegacy
{
	public function getView($name = '', $type = '', $prefix = '', $config = [])
	{
		$jshopConfig = JSFactory::getConfig();

		if (empty($type)){
			$type = getDocumentType();
		}

		if (empty($config)) {
			$config = [
				'template_path' => $jshopConfig->template_path . $jshopConfig->template . '/' . $name
			];
		}

		return parent::getView($name, $type, $prefix, $config);
	}
	
	public function getViewAddon($name ='', $type = '', $prefix = '', $viewName = 'addons')
	{	
		return $this->getView($viewName, $type, $prefix, [
			'template_path' => JSFactory::getConfig()->template_path . 'addons/' . $name
		]);
	}
	
}