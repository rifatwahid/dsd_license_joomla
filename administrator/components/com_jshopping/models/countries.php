<?php
/**
* @version      4.1.0 05.07.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelCountries extends JModelLegacy{
    
    /**
    * get list country
    * 
    * @param int $publish (0-all, 1-publish, 2-unpublish)
    * @param int $limitstart
    * @param int $limit
    * @param int $orderConfig use order config
    * @return array
    */
    public function getAllCountries($publish = 1, $limitstart = null, $limit = null, $orderConfig = 1, $order = null, $orderDir = null){
        $db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();
                
        if ($publish == 0) {
            $where = " ";
        } else {
            if ($publish == 1) {
                $where = (" WHERE country_publish = '1' ");
            } else {
                if ($publish == 2) {
                    $where = (" WHERE country_publish = '0' ");
                }
            }
        }
        $ordering = "ordering";
        if ($orderConfig && $jshopConfig->sorting_country_in_alphabet) $ordering = "name";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $lang = JSFactory::getLang();
        $query = "SELECT country_id, country_publish, ordering, country_code, country_code_2, `".$lang->get("name")."` as name FROM `#__jshopping_countries` ".$where." ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query, $limitstart, $limit);
        return $db->loadObjectList();
    }

    /**
    * get count country
    * @return int
    */
    public function getCountAllCountries() {
        $db = \JFactory::getDBO(); 
        $query = "SELECT COUNT(country_id) FROM `#__jshopping_countries`";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    /**
    * get count county
    * @param int $publish
    * @return int
    */
    public function getCountPublishCountries($publish = 1) {
        $db = \JFactory::getDBO(); 
        $query = "SELECT COUNT(country_id) FROM `#__jshopping_countries` WHERE country_publish = '".intval($publish)."'";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadResult();
    }
	
	public function orderingChange($order,$cid,$number){
		$_ordering = JSFactory::getModel('ordering');
		switch ($order) {
			case 'up':
				$_ordering->orderingMoveUp('#__jshopping_countries',"country_id","ordering",$number,$cid);
				break;
			case 'down':
				$_ordering->orderingMoveDown('#__jshopping_countries',"country_id","ordering",$number,$cid);
		}		
	}
	
	public function deleteCountry($cid,$number,$order){
		$db = \JFactory::getDBO();
		$query = '';
		$text = '';
		foreach ($cid as $key=>$value) {
			$query = "DELETE FROM `#__jshopping_countries`
					   WHERE `country_id` = '" . $db->escape($value) . "'";
			$db->setQuery($query);
			if ($db->execute())
				$text .= JText::_('COM_SMARTSHOP_COUNTRY_DELETED')."<br>";
			else
				$text .= JText::_('COM_SMARTSHOP_COUNTRY_ERROR_DELETED')."<br>";	
		}
		return $text;
	}
	
	public function publishCountry($cid,$flag){		
		$_dbpublish = JSFactory::getModel('dbpublish');
		foreach ($cid as $key => $value) {
			$_dbpublish->setFlag("#__jshopping_countries","country_id",$value,"country_publish",$flag);			
		}
	}
	
	public function moveUpCountryOrdering($country_ordering){
		$db = \JFactory::getDBO();
        $query = "UPDATE `#__jshopping_countries` SET `ordering` = ordering + 1 WHERE `ordering` > '".$country_ordering."'";
        $db->setQuery($query);
        $db->execute();
	}
	
	public function getOrderCountry(&$order){
		$lang = JSFactory::getLang();
		$country = JSFactory::getTable('country', 'jshop');
        $country->load($order->country);
        $field_country_name = $lang->get("name");
        $order->country = $country->$field_country_name;
	}
	
	public function getOrderDeliveryCountry(&$order){
		$lang = JSFactory::getLang();
		$d_country = JSFactory::getTable('country', 'jshop');
        $d_country->load($order->d_country);
        $field_country_name = $lang->get("name");
        $order->d_country = $d_country->$field_country_name;
	}
      
}

?>