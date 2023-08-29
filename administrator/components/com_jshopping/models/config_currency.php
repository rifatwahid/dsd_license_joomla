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

class JshoppingModelConfig_currency extends JModelLegacy{
    	
	public function getSelect_Currencies(){
		$jshopConfig = JSFactory::getConfig();
		$_currencies = JSFactory::getModel("currencies");
		$currencies = $_currencies->getAllCurrencies();
        return JHTML::_('select.genericlist', $currencies,'mainCurrency','class = "inputbox form-select" size = "1"','currency_id','currency_name',$jshopConfig->mainCurrency);
	}
	
	public function getSelect_FormatCurrency(){
		$jshopConfig = JSFactory::getConfig();
		$i = 0;
		foreach ($jshopConfig->format_currency as $key => $value) {
            $currenc[$i] = new stdClass();
			$currenc[$i]->id_cur = $key;
			$currenc[$i]->format = $value;
			$i++;
		}
        return JHTML::_('select.genericlist', $currenc,'currency_format','class = "inputbox form-select" size = "1"','id_cur','format',$jshopConfig->currency_format);
	}
}
?>