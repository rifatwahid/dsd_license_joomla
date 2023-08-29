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

class JshoppingModelConfig_checkout extends JModelLegacy{
    	
	public function getSelect_Step43(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');  
		$option=$_options_array->getPaymentShippingSteps();
		return JHTML::_('select.genericlist', $option, 'step_4_3','class = "inputbox form-select"','id','name', $jshopConfig->step_4_3);
	}
	
	public function getSelect_DefaultCountry(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');  
		$option=$_options_array->getPaymentShippingSteps();
		
		$_countries = JSFactory::getModel("countries");
        $countries = $_countries->getAllCountries(0);    
        $first = JHTML::_('select.option', 0,JText::_('COM_SMARTSHOP_SELECT'),'country_id','name' );
        array_unshift($countries,$first);
		return JHTML::_('select.genericlist', $countries, 'default_country','class = "inputbox form-select" size = "1"','country_id','name', $jshopConfig->default_country);
	}
	
	public function getSelect_Status(){
		$jshopConfig = JSFactory::getConfig();
		$_orders = JSFactory::getModel("orders");
        $order_status = $_orders->getAllOrderStatus();
		return JHTML::_('select.genericlist', $order_status,'default_status_order','class = "inputbox form-select" size = "1"','status_id','name', $jshopConfig->default_status_order);
	}
	
}
?>