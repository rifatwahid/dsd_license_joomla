<?php 

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelReturnPackages extends JModelLegacy
{
	public function savePackages($order_id,$post){
		$order_id = $order_id ?? 0;
		$this->clearReturnPackages($order_id);
		//print_R($post);die;
		foreach($post['return_package_id'] as $pack_id){
			
			$pid = $this->savePackage($order_id, $pack_id, $post['return_package_status'][$pack_id]);
			if($post['return_product_quantity'][$pack_id]){
				foreach($post['return_product_quantity'][$pack_id] as $pr_id=>$qty){ 
					$this->savePackageProduct($pid, $pr_id, $post['return_product_quantity'][$pack_id][$pr_id], $post['return_reason'][$pack_id][$pr_id], $post['customer_comment'][$pack_id][$pr_id], $post['admin_notice'][$pack_id][$pr_id] );
				}
			}
		}
		
	}
	
	public function clearReturnPackages($order_id=0){
		$db = \JFactory::getDBO();		
		$db->setQuery('DELETE FROM `#__jshopping_return_packages_products` WHERE package_id IN(
			SELECT pack.`id` FROM `#__jshopping_return_packages` as pack 
			WHERE pack.`order_id` = '.(int)$order_id.')');
        $db->execute();	
		$db->setQuery('DELETE FROM `#__jshopping_return_packages` WHERE `order_id` = '.(int)$order_id);
        $db->execute();
	}
	
	public function savePackage($order_id,$package_number,$package_status){
		$order_id = $order_id ?? 0;
		$db = \JFactory::getDBO();				
		$package = new stdClass();
		$package->order_id = $order_id;
		$package->package = $package_number;
		$package->package_status = $package_status;	
		
		$db->insertObject('#__jshopping_return_packages', $package); 
		return $db->insertid();
	}
	
	public function savePackageProduct($package_id, $product_id, $quantity, $return_status_id, $customer_comment, $admin_notice){
		$order_id = $order_id ?? 0;
		$db = \JFactory::getDBO();				
		$package = new stdClass();
		$package->package_id = $package_id;
		$package->product_id = $product_id;
		$package->quantity = $quantity;
		$package->return_status_id = $return_status_id;	
		$package->customer_comment = $customer_comment;	
		$package->admin_notice = $admin_notice;	
		
		$db->insertObject('#__jshopping_return_packages_products', $package);
	}
	
	public function loadPackages($order_id=0)
	{
		$_orders = JSFactory::getModel("orders");
		$result = [];

		if ( !empty($order_id) ) {
			$db = \JFactory::getDBO();
			$sqlSelectOrderItems = $db->getQuery(true);

			$sqlSelectOrderItems->select('*')
								->from('#__jshopping_return_packages')
								->where('order_id = ' . $db->escape($order_id));

			$result = $db->setQuery($sqlSelectOrderItems)->loadObjectList();
		}
		
		if(!empty($result)){
			$first = array(0 => JText::_('COM_SMARTSHOP_ORDEREDIT_NO_REASON'));
			$list = array_merge($first, $_orders->getAllReturnStatus());
			
			foreach($result as $k => $val){
				$val->products = $this->getPackProducts($val->id);
				$val->return_reason = JHTML::_('select.genericlist', $list,'return_package_status[]','class = "inputbox form-select" size = "1" id = "return_package_status"','status_id','name', $val->package_status);
			}
		}

		return $result;
	}
	
	function getPackProducts($pack_id){
		$db = \JFactory::getDBO();
		$sqlSelectOrderItems = $db->getQuery(true);
		$sqlSelectOrderItems->select('*')
							->from('#__jshopping_return_packages_products')
							->where('package_id = ' . $db->escape($pack_id));

		return $db->setQuery($sqlSelectOrderItems)->loadObjectList('product_id');
	}
}