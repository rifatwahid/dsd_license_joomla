<?php 

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelOrderPackages extends JModelLegacy
{
	public function savePackages($order_id,$post){
		$order_id = $order_id ?? 0;
		$shipping_packages_products=get_object_vars(json_decode($post['shipping_packages_products']));
		$package_provider=$post['package_provider'];
		$package_tracking=$post['package_tracking'];
		$package_status=$post['package_status'];		
		$this->clearOrderPackages($order_id);
		foreach ($shipping_packages_products as $key=>$package){
			$this->savePackage($order_id,$key,json_encode($package),$package_provider[$key-1],$package_tracking[$key-1],$package_status[$key-1]);
		}
	}
	
	public function clearOrderPackages($order_id=0){
		$db = \JFactory::getDBO();		
		$db->setQuery('DELETE FROM `#__jshopping_order_packages` WHERE `order_id` = '.(int)$order_id);
        $db->execute();
	}
	
	public function savePackage($order_id,$package_number,$products,$package_provider,$package_tracking,$package_status){
		$order_id = $order_id ?? 0;
		$db = \JFactory::getDBO();				
		$package = new stdClass();
		$package->order_id = $order_id;
		$package->package = $package_number;
		$package->products = $products;
		$package->package_provider = $package_provider;
		$package->package_tracking = $package_tracking;
		$package->package_status = $package_status;//print_r($package_tracking);		
		$db->insertObject('#__jshopping_order_packages', $package);                
	}
	
	public function loadPackages($order_id=0)
	{
		$result = [];

		if ( !empty($order_id) ) {
			$db = \JFactory::getDBO();
			$sqlSelectOrderItems = $db->getQuery(true);

			$sqlSelectOrderItems->select('*')
								->from('#__jshopping_order_packages')
								->where('order_id = ' . $db->escape($order_id));

			$result = $db->setQuery($sqlSelectOrderItems)->loadObjectList();
		}

		return $result;
	}

}