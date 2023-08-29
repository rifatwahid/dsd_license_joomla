<?php
/**
* @version      3.12.0 10.11.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelConfig_media extends JModelLegacy{
    	
	public function getSelect_SelectResizeType(){
		$jshopConfig = JSFactory::getConfig();
        $config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');		
		$_options_array = JSFactory::getModel('options_array');  
		$resize_type = $_options_array->getResizeType();                
		return JHTML::_('select.genericlist', $resize_type, 'image_resize_type','class = "inputbox form-select" size = "1"','id','name', $jshopConfig->image_resize_type);
	}
	
}
?>