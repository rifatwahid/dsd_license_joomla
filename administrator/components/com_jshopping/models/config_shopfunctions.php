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

class JshoppingModelConfig_shopfunctions extends JModelLegacy{
    	
	public function getSelect_ShopRegisterType(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');  
		$shop_register_type = $_options_array->getShopRegisterType();                
		return JHTML::_('select.genericlist', $shop_register_type, 'shop_user_guest','class = "inputbox form-select" size = "1"','id','name', $jshopConfig->shop_user_guest);
	}
	
}
?>