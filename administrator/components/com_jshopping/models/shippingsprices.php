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

class JshoppingModelShippingsprices extends JModelLegacy{ 
	public function getShippingsPriceByCountries($lang){
		$db = \JFactory::getDBO();
		$query = "select MPC.sh_pr_method_id, C.`".$lang."` as name from #__jshopping_shipping_method_price_countries as MPC 
                  left join #__jshopping_countries as C on C.country_id=MPC.country_id order by MPC.sh_pr_method_id, C.ordering";
        $db->setQuery($query);
        $list = $db->loadObjectList();        
		return $list;
	}
	public function deletePricesByShippingMethodId($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_shipping_method_price`
					  WHERE `sh_pr_method_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		return $db->execute();
	}
	public function deletePriceWeightByShippingMethodId($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_shipping_method_price_weight`
						  WHERE `sh_pr_method_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();
	}
	public function deletePriceCountriesByShippingMethodId($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_shipping_method_price_countries`
						  WHERE `sh_pr_method_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();
	}
	public function orderingChange($order,$cid,$number){
		$_ordering = JSFactory::getModel('ordering');
		switch ($order) {
			case 'up':
				$_ordering->orderingMoveUp('#__jshopping_shipping_method_price',"sh_pr_method_id","ordering",$number,$cid);
				break;
			case 'down':
				$_ordering->orderingMoveDown('#__jshopping_shipping_method_price',"sh_pr_method_id","ordering",$number,$cid);
		}	
	}

	public function publishUnpublish(array $ids, bool $isPublish): bool
	{
		$result = true;
		$db = \JFactory::getDBO();
		$preparedIds = implode(', ', $ids);

		if (!empty($preparedIds)) {
			$query = 'UPDATE `#__jshopping_shipping_method_price` SET `published` = ' . $db->escape((int)$isPublish) . ' WHERE `sh_pr_method_id` IN(' . $preparedIds . ')';
			$db->setQuery($query);
			$result = $db->execute();
		}

		return $result;
	}
    public function getShippingsPriceByStates($method_id, $lang){
        $db = \JFactory::getDBO();
        $query = "select s.`".$lang."` from #__jshopping_shipping_method_price_states as ps 
                  left join #__jshopping_states as s on s.state_id=ps.state_id
                  WHERE ps.sh_pr_method_id = ". $method_id ."
                   order by ps.sh_pr_method_id, s.ordering";
        $db->setQuery($query);
        $list = $db->loadColumn();

        return $list;
    }
}