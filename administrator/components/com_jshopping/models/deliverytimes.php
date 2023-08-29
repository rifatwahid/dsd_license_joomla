<?php
/**
* @version      4.1.0 23.09.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelDeliveryTimes extends JModelLegacy{    

    public function getDeliveryTimes($order = null, $orderDir = null){
        $db = \JFactory::getDBO();    
        $lang = JSFactory::getLang();    
        
        $ordering = "name";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT id, `".$lang->get('name')."` as name FROM `#__jshopping_delivery_times` ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public function getCountDeliveryTimes() {
        $db = \JFactory::getDBO();         
        $query = "SELECT count(id) FROM `#__jshopping_delivery_times`";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadResult();
    }
	
	private function deleteItem($id){
		$db = \JFactory::getDBO();         
		$query = "DELETE FROM `#__jshopping_delivery_times` WHERE `id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		return $db->execute();
	}
	
	public function deleteItems($cid){
		$text = array();	
		foreach ($cid as $key => $value) {
			if ($this->deleteItem($value))
				$text[] = JText::_('COM_SMARTSHOP_DELIVERY_TIME_DELETED')."<br>";
			else
				$text[] = JText::_('COM_SMARTSHOP_DELIVERY_TIME_DELETED_ERROR_DELETED')."<br>";
		}
		return $text;		
	}
	
	public function getDeliveryTimesList($delivery_times_id,&$lists, $name = 'deliverytimes', $selectName = 'delivery_times_id', $id = false){		
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->admin_show_delivery_time) {            
			$all_delivery_times = $this->getDeliveryTimes();                
			$all_delivery_times0 = array();
			$all_delivery_times0[0] = new stdClass();
			$all_delivery_times0[0]->id = '0';
			$all_delivery_times0[0]->name = JText::_('COM_SMARTSHOP_NONE');        
			$lists[$name]=JHTML::_('select.genericlist', array_merge($all_delivery_times0, $all_delivery_times), $selectName,'class = "inputbox form-select" size = "1"','id','name',$delivery_times_id, $id); 
		}
	}
	
	public function getDeliveryTimesListForEditList(&$lists){		
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->admin_show_delivery_time) {            
			$all_delivery_times = $this->getDeliveryTimes();                
			$all_delivery_times0 = array();
			$all_delivery_times0[-1] = new stdClass();
            $all_delivery_times0[-1]->id = '-1';
            $all_delivery_times0[-1]->name = "- - -";
			$all_delivery_times0[0] = new stdClass();
			$all_delivery_times0[0]->id = '0';
			$all_delivery_times0[0]->name = JText::_('COM_SMARTSHOP_NONE');        
			$lists['deliverytimes']=JHTML::_('select.genericlist', array_merge($all_delivery_times0, $all_delivery_times),'delivery_times_id','class = "inputbox form-select" size = "1"','id','name'); 
		}
	}
	
	public function getOrderDeliveryTime(&$order){
		$jshopConfig = JSFactory::getConfig();
		$order->delivery_time_name = '';
        $order->delivery_date_f = '';
        if ($jshopConfig->show_delivery_time_checkout){
            $deliverytimes = JSFactory::getAllDeliveryTime();
            $order->delivery_time_name = $deliverytimes[$order->delivery_times_id];
            if ($order->delivery_time_name==""){
                $order->delivery_time_name = $order->delivery_time;
            }
        }
	}
	
	public function getOrderDeliveryDate(&$order){
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->show_delivery_date && !datenull($order->delivery_date)){
            $order->delivery_date_f = formatdate($order->delivery_date);
        }
	}
    
}
?>