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

class JshoppingModelConfig_general extends JModelLegacy{
    	
	public function getSelect_DisplayPriceAdmin(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel("options_array");		
		$display_price_list = $_options_array->getDisplayPriceTypes();
        return JHTML::_('select.genericlist', $display_price_list, 'display_price_admin', 'class="form-select"', 'id', 'name', $jshopConfig->display_price_admin);
	}
	
	public function getSelect_DisplayPriceFront(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel("options_array");		
		$display_price_list = $_options_array->getDisplayPriceTypes();
        return JHTML::_('select.genericlist', $display_price_list, 'display_price_front', 'class="form-select"', 'id', 'name', $jshopConfig->display_price_front);
	}
	
	public function getSelect_SingleItemPrice(){
		$jshopConfig = JSFactory::getConfig();
		$display_single_item_price = array();
        $display_single_item_price[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_YES'), 'id', 'name');
        $display_single_item_price[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_NO'), 'id', 'name');
        return JHTML::_('select.genericlist', $display_single_item_price, 'single_item_price', 'class="form-select"', 'id', 'name', $jshopConfig->single_item_price);
	}
}
?>