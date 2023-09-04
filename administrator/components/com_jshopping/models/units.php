<?php
/**
* @version      2.2.6 23.09.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelUnits extends JModelLegacy{    

    public function getUnits(){
        $db = \JFactory::getDBO();    
        $lang = JSFactory::getLang();     
        $query = "SELECT id, `".$lang->get('name')."` as name FROM `#__jshopping_unit` ORDER BY name";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	public function deleteUnitById($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_unit` WHERE `id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		return $db->execute();
	}
	
	public function getProductUnitsList($basic_price_unit_id, $selectName = 'basic_price_unit_id', $id = false){		
		$allunits = $this->getUnits();		
		return JHTML::_('select.genericlist', $allunits, $selectName,'class = "inputbox form-select"','id','name',$basic_price_unit_id, $id);
	}
	
	public function getAddPriceUnitsList($product_id){
		$jshopConfig = JSFactory::getConfig();
		$_table_product = JSFactory::getTable('product', 'jshop');
		$_table_product->load($product_id);
		if (!$_table_product->add_price_unit_id) $_table_product->add_price_unit_id = $jshopConfig->product_add_price_default_unit;  
		return $this->generateAddPriceUnitsList($_table_product->add_price_unit_id);
	}

	public function generateAddPriceUnitsList($default = 0, bool $isAddPacifierOption = false)
	{
		$allunits = $this->getUnits();

		if ($isAddPacifierOption) {
			array_unshift($allunits, (object)[
				'id' => '-1',
				'name' => '- - -'
			]);
		}

		return JHTML::_('select.genericlist', $allunits, 'add_price_unit_id','class = "inputbox middle form-select"','id','name', $default);
	}
	
	public function getUsergroupsAddPriceUnitsList($product_id = null, $name = 'add_usergroups_prices_add_price_unit_id'){
		$jshopConfig = JSFactory::getConfig();
		$defaultValue = $jshopConfig->product_add_price_default_unit;

		if (!empty($product_id)) {
			$_table_product = JSFactory::getTable('product', 'jshop');
			$_table_product->load($product_id);
			if (!empty($_table_product->add_price_unit_id)) {
				$defaultValue = $_table_product->add_price_unit_id;
			}
		}

		$allunits = $this->getUnits();
		return JHTML::_('select.genericlist', $allunits, $name,'class = "inputbox middle form-select"','id','name', $defaultValue);
	}
	
	public function getUsergroupsPricesAddPriceUnitsList($product_id){
		$jshopConfig = JSFactory::getConfig();
		$_table_product = JSFactory::getTable('product', 'jshop');
		$_table_product->load($product_id);
		$_usergroups = JSFactory::getModel("usergroups");
		$allunits = $this->getUnits();		
		$usergroups_prices = $_usergroups->getAllUsergroupsPrices($product_id);		
		foreach ($usergroups_prices as $key=>$value){	
			if (!$value->add_price_unit_id) $value->add_price_unit_id = $jshopConfig->product_add_price_default_unit; 
			if(!$value->usergroup_id) $value->usergroup_id = 0;
			$add_usergroups_prices_add_price_units_list[$value->usergroup_id] = JHTML::_('select.genericlist', $allunits, 'add_usergroups_prices_add_price_unit_id_list['.$value->usergroup_id.']','class = "inputbox middle form-select"','id','name',$value->add_price_unit_id);
		}
		return $add_usergroups_prices_add_price_units_list ?? [];
	}
	
	public function getUnitsByProductIdLists($product_id,&$lists){
		$jshopConfig = JSFactory::getConfig();
		$_table_product = JSFactory::getTable('product', 'jshop');
		$_table_product->load($product_id);
		if ($jshopConfig->admin_show_product_basic_price){            
			$lists['basic_price_units']=$this->getProductUnitsList($_table_product->basic_price_unit_id, 'basic_price_unit_id');		
			$lists['attr_basic_price_unit_id']=$this->getProductUnitsList($_table_product->basic_price_unit_id, '', 'attr_basic_price_unit_id');	
			$lists['attr_price_per_consignment_basic_price_unit_id']=$this->getProductUnitsList($_table_product->basic_price_unit_id, '', 'attr_price_per_consignment_basic_price_unit_id');		
        }        
		$lists['add_price_units']=$this->getAddPriceUnitsList($product_id);		
		$lists['add_usergroups_prices_add_price_units']=$this->getUsergroupsAddPriceUnitsList($product_id);
		$lists['add_usergroups_prices_add_price_units_add']=$this->getUsergroupsAddPriceUnitsList($product_id, 'add_usergroups_prices_add_price_unit_id[100500]');
		$lists['attr_add_usergroups_prices_add_price_units']=$this->getUsergroupsAddPriceUnitsList($product_id, 'attr_add_usergroups_prices_add_price_unit_id');
		$lists['add_usergroups_prices_add_price_units_list']=$this->getUsergroupsPricesAddPriceUnitsList($product_id);
	}
	
	public function getUnitsLists(&$lists, bool $isAddPacifierOption = false){
		$jshopConfig = JSFactory::getConfig();
		$allunits = $this->getUnits();

		if ($isAddPacifierOption) {
			array_unshift($allunits, (object)[
				'id' => '-1',
				'name' => '- - -',
			]);
		}

        if ($jshopConfig->admin_show_product_basic_price){
            $lists['basic_price_units'] = JHTML::_('select.genericlist', $allunits, 'basic_price_unit_id','class = "inputbox form-select"','id','name');
        }
	}
    
	public function getFreeAttrUnitsList($unit_id){		
		$allunits = $this->getUnits();
        $f_option = array();
        $f_option[] = JHTML::_('select.option', 0, " - - - ", 'id', 'name');	
		return JHTML::_('select.genericlist', array_merge($f_option, $allunits), 'unit_id','class = "inputbox form-select"','id','name',$unit_id);
	}
	
	public function getUnitById($id){
        $db = \JFactory::getDBO();    
        $lang = JSFactory::getLang();  
        $query = "SELECT `".$lang->get('name')."` as name FROM `#__jshopping_unit` WHERE `id`=".$id;
		$db->setQuery($query);
		return $db->loadResult();
	}
}
?>