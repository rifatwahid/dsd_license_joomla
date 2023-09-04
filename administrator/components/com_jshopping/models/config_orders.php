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

class JshoppingModelConfig_orders extends JModelLegacy{
    	
	public function getSelect_Status(){
		$jshopConfig = JSFactory::getConfig();
		$_orders = JSFactory::getModel("orders");
        $order_status = $_orders->getAllOrderStatus();
        return JHTML::_('select.genericlist', $order_status,'default_status_order','class = "inputbox form-select" size = "1"','status_id','name', $jshopConfig->default_status_order);
	}
		
}
?>