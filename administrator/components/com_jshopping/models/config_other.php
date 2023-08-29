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

class JshoppingModelConfig_other extends JModelLegacy{
    	
	public function getSelect_TaxRuleFor(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel("options_array");		
		$tax_rule_for = $_options_array->getTaxRules();
        return JHTML::_('select.genericlist', $tax_rule_for, 'ext_tax_rule_for','class = "inputbox form-select" size = "1"','id','name', $jshopConfig->ext_tax_rule_for);
	}
	
	public function getSelect_ShopMode(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel("options_array");		
		$opt=$_options_array->getShopModeTypes();  
        return JHTML::_('select.genericlist', $opt, 'shop_mode','class = "inputbox form-select"','id','name', $jshopConfig->shop_mode);
	}
}
?>