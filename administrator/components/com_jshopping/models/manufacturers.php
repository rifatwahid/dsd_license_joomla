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

class JshoppingModelManufacturers extends JModelLegacy{

    public function getAllManufacturers($publish=0, $order=null, $orderDir=null){
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang(); 
        $query_where = ($publish)?(" WHERE manufacturer_publish = '1'"):("");  
        $queryorder = '';        
        if ($order && $orderDir){
            $queryorder = "order by ".$order." ".$orderDir;
        }
        $query = "SELECT manufacturer_id, manufacturer_url, manufacturer_logo, manufacturer_publish, ordering, `".$lang->get('name')."` as name FROM `#__jshopping_manufacturers` $query_where ".$queryorder;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public function getList(){
        $jshopConfig = JSFactory::getConfig();
        if ($jshopConfig->manufacturer_sorting==2){
            $morder = 'name';
        }else{
            $morder = 'ordering';
        }
		return $this->getAllManufacturers(0, $morder, 'asc');
    } 
	
	public function getManufacturers(){
		$manuf1 = array();
        $manuf1[0] = new stdClass();
        $manuf1[0]->manufacturer_id = '0';
        $manuf1[0]->name = JText::_('COM_SMARTSHOP_NONE');
        $manufs = $this->getList();
        $manufs = array_merge($manuf1, $manufs);
		return $manufs;
	}
	
	public function getManufacturerList(){
		$manuf1 = array();
        $manuf1[0] = new stdClass();
        $manuf1[0]->manufacturer_id = '0';
        $manuf1[0]->name = " - ".JText::_('COM_SMARTSHOP_NAME_MANUFACTURER')." - ";		
		
        $manufs = $this->getList();
        $manufs = array_merge($manuf1, $manufs);
		
		$lists = JHTML::_('select.genericlist', $manufs, 'manufacturer_id','class="form-select" onchange="document.adminForm.submit();"', 'manufacturer_id', 'name', $manufacturer_id ?? 0);
		return $lists;
    }
	
	public function getManufacturerListForEditList(){
		$manuf1 = array();
		$manuf1[-1] = new stdClass();
        $manuf1[-1]->manufacturer_id = '-1';
        $manuf1[-1]->name = "- - -";
        $manuf1[0] = new stdClass();
        $manuf1[0]->manufacturer_id = '0';
        $manuf1[0]->name = " - ".JText::_('COM_SMARTSHOP_NONE')." - ";		
		
        $manufs = $this->getList();
        $manufs = array_merge($manuf1, $manufs);
		
		$lists = JHTML::_('select.genericlist', $manufs,'product_manufacturer_id','class = "inputbox form-select" size = "1"','manufacturer_id','name');
		return $lists;
    }
	
	public function setCountToPage($count_products_to_page){
		$db = \JFactory::getDBO();
		$query = "update `#__jshopping_manufacturers` set `products_page`='".$count_products_to_page."';";
		$db->setQuery($query);
		$db->execute();
	}
 
	public function publishManufacturers($cid,$flag){		
		$_dbpublish = JSFactory::getModel('dbpublish');
		foreach ($cid as $key => $value) {
			$_dbpublish->setFlag("#__jshopping_manufacturers","manufacturer_id",$value,"manufacturer_publish",$flag);			
		}
	}
	
	public function getManufacturersSelect($_table_product,&$lists, $name = 'manufacturers', $selectList = 'product_manufacturer_id', $id = false){		
		$manufs=$this->getManufacturers();
        $lists[$name] = JHTML::_('select.genericlist', $manufs, $selectList,'class = "inputbox form-select" size = "1"','manufacturer_id','name',$_table_product->product_manufacturer_id, $id);
	}
	

}
?>