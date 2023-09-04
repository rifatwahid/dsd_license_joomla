<?php
/**
* @version      4.1.0 25.11.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelTaxes extends JModelLegacy{ 

    public function getAllTaxes($order = null, $orderDir = null) {
        $db = \JFactory::getDBO(); 
        $ordering = 'tax_name';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT * FROM `#__jshopping_taxes` ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public function getExtTaxes($tax_id = 0, $order = null, $orderDir = null) {
        $db = \JFactory::getDBO();
        $where = "";
        if ($tax_id) $where = " where ET.tax_id='".$tax_id."'";
        $ordering = 'ET.id';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT ET.*, T.tax_name FROM `#__jshopping_taxes_ext` as ET left join #__jshopping_taxes as T on T.tax_id=ET.tax_id ".$where." ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	public function getProductsCountByTaxId($id){
		$db = \JFactory::getDBO();
		$query2 = "SELECT pr.product_id
                       FROM `#__jshopping_products` AS pr
                       WHERE pr.product_tax_id = '" . $db->escape($id) . "'";
		$db->setQuery($query2);
		$res = $db->execute();
		return $db->getNumRows($res);
	}
	
	public function deleteTaxById($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_taxes` WHERE `tax_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		return $db->execute();
	}
	
	public function deleteTaxExtById($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_taxes_ext` WHERE `tax_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();            
	}
	
	public function getTaxesList(){
		$all_taxes = $this->getAllTaxes();
		$list_tax = array();
		foreach ($all_taxes as $tax){
			$list_tax[] = JHTML::_('select.option', $tax->tax_id, $tax->tax_name . ' (' . $tax->tax_value . '%)','tax_id','tax_name');
		}
		return $list_tax;
	}
	
	public function getTaxById($tax_id){
		$all_taxes = $this->getAllTaxes();
		$jshopConfig = JSFactory::getConfig();
		$tax_value = 0;
        if ($jshopConfig->tax){
            foreach($all_taxes as $tax){
                if ($tax->tax_id == $tax_id){
                    $tax_value = $tax->tax_value;
                    break; 
                }
            }
        }		
		return $tax_value;
	}
	
	public function getTaxesSelect($_table_product,&$lists, $name = 'tax', $selectName = 'product_tax_id', $id = false){
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->tax){			 
			$list_tax=$this->getTaxesList(); 
            $lists[$name] = JHTML::_('select.genericlist', $list_tax, $selectName,'class = "inputbox form-select" size = "1" onchange = "shopProductPrice.updateByTax('.$jshopConfig->display_price_admin.');"','tax_id','tax_name',$_table_product->product_tax_id, $id);
        }
	}
	
	public function withouttaxCheck(){
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->tax){			       
            return 0;
        }else{
            return 1;
        }
	}

	public function productEditList_getTaxList()
	{        
        $list = [
			JHTML::_('select.option', -1, '- - -', 'tax_id', 'tax_name')
		];

        foreach($this->getAllTaxes() as $tax) {
            $list[] = JHTML::_('select.option', $tax->tax_id, $tax->tax_name . ' (' . $tax->tax_value . '%)', 'tax_id', 'tax_name');
        }

		return JHTML::_('select.genericlist', $list,'product_tax_id', 'class = "inputbox form-select" size = "1"', 'tax_id', 'tax_name');
	}
      
}

?>