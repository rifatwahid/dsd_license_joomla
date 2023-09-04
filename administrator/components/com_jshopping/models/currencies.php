<?php
/**
* @version      4.1.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelCurrencies extends JModelLegacy{ 

    public function getAllCurrencies($publish = 1, $order = null, $orderDir = null) {
        $db = \JFactory::getDBO(); 
        $query_where = ($publish)?("WHERE currency_publish = '1'"):("");
        $ordering = 'currency_ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT * FROM `#__jshopping_currencies` $query_where ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }      
	
	public function moveUpCurrenciesOrdering($currency_ordering){
		$db = \JFactory::getDBO();
        $query = "UPDATE `#__jshopping_currencies`
                    SET `currency_ordering` = currency_ordering + 1
                    WHERE `currency_ordering` > '" . $currency_ordering . "'";
        $db->setQuery($query);
        $db->execute();
	}
	
	public function orderingChange($order,$cid,$number){
		$_ordering = JSFactory::getModel('ordering');
		switch ($order) {
			case 'up':
				$_ordering->orderingMoveUp('#__jshopping_currencies',"currency_id","currency_ordering",$number,$cid);
				break;
			case 'down':
				$_ordering->orderingMoveDown('#__jshopping_currencies',"currency_id","currency_ordering",$number,$cid);
		}	
	}
	
	public function publishCurrencies($cid,$flag){		
		$_dbpublish = JSFactory::getModel('dbpublish');
		foreach ($cid as $key => $value) {
			$_dbpublish->setFlag("#__jshopping_currencies","currency_id",$value,"currency_publish",$flag);			
		}
	}
	
	public function deleteCurrencies($cid){
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {					
			if($_dbdelete->deleteItems("#__jshopping_currencies","currency_id",$value))
                $text .= JText::_('COM_SMARTSHOP_CURRENCY_DELETED')."<br>";
            else
                $text .= JText::_('COM_SMARTSHOP_CURRENCY_ERROR_DELETED')."<br>";
		}
		return $text;
	}
	
	public function getCurrencyLists($product_id,&$lists){
		$jshopConfig = JSFactory::getConfig();
		$_table_product = JSFactory::getTable('product', 'jshop');
		$_table_product->load($product_id);
		$current_currency = $_table_product->currency_id;
        if (!$current_currency) $current_currency = $jshopConfig->mainCurrency;       
        $currency_list = $this->getAllCurrencies();
        $lists['currency'] = JHTML::_('select.genericlist', $currency_list, 'currency_id','class = "inputbox small2 form-select"','currency_id','currency_code', $current_currency);
	}
	
	public function productEditList_getCurrencyList(&$list, bool $isAddPacifierOption = false){
		$jshopConfig = JSFactory::getConfig();
		$product = JSFactory::getTable('product', 'jshop'); 
		$current_currency = $product->currency_id;
        if (!$current_currency) $current_currency = $jshopConfig->mainCurrency;
        $currency_list = $this->getAllCurrencies();

		if ($isAddPacifierOption) {
			$current_currency = 0;
			array_unshift($currency_list, (object)[
				'currency_id' => '-1',
				'currency_code' => '- - -'
			]);
		}

        $list['currency'] = JHTML::_('select.genericlist', $currency_list, 'currency_id','class = "inputbox form-select"','currency_id','currency_code', $current_currency);
	}
        
}
?>