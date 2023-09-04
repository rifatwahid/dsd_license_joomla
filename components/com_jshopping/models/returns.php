<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelReturns extends jshopBase
{
    public $products = [];
    public $order_id = 0;
   
    public function getAllReturnStatus($order = null, $orderDir = null) {
        $db = \JFactory::getDBO(); 
        $lang = JSFactory::getLang();
        $ordering = "status_id";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT *, `".$lang->get('name')."` as name FROM `#__jshopping_return_status` ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList('status_id');
    }
	
	public function load()
    {
        $jshopConfig = JSFactory::getConfig();
        $currentObj = $this;

        $session = JFactory::getSession();
        $objreturns = $session->get('returns');

        if (isset($objreturns) && $objreturns != '' && !empty($objreturns)) {
            $temp_return = unserialize($objreturns);
            $this->products = $temp_return->products;
            $this->order_id = $temp_return->order_id;
        }

    }
	
	public function add($orderId, $products_count, $reason_products, $comments){
		$session = JFactory::getSession();
		$session->set('returns', []);
  
		if(!empty($products_count)){
			foreach($products_count as $product_id=>$quantity){				
				$temp_product['quantity'] = $quantity;
				$temp_product['product_id'] = $product_id;
				$temp_product['order_id'] = $orderId;
				$temp_product['count_product'] = $products_count[$product_id];
				$temp_product['return_status_id'] = $reason_products[$product_id];
				$temp_product['customer_comment'] = $comments[$product_id];
				
				$this->products[$product_id] = $temp_product;
			}
		}
		$this->order_id = $orderId;
		$this->saveToSession();
		return true;
	}
	
	public function saveToSession()
    {
        $currentObj = $this;
        $session = JFactory::getSession();
        $session->set('returns', serialize($this));
        $_tempcart = JSFactory::getModel('tempcart', 'jshop');		
    }
	
	public function loadProductData($order_products){
		$statuses = $this->getAllReturnStatus();
		foreach($order_products as $val){
			if($this->products[$val->order_item_id]){
				$this->products[$val->order_item_id]['product_name'] = $val->product_name;
				$this->products[$val->order_item_id]['thumb_image'] = $val->thumb_image;
				$this->products[$val->order_item_id]['return_status'] = isset($statuses[$this->products[$val->order_item_id]['return_status_id']]) ? $statuses[$this->products[$val->order_item_id]['return_status_id']]->name : '';
			}
		}
		
		return $this->products;
	}
	
	public function savePackage($order_id){
		$order_id = $order_id ?? 0;
		$db = \JFactory::getDBO();				
		$package = new stdClass();
		$package_number = $this->getPackageNumber($order_id);
		$package->order_id = $order_id;
		$package->package = $package_number;
		
		$db->insertObject('#__jshopping_return_packages', $package); 
		return $db->insertid();
	}
	
	public function savePackageProduct($package_id, $products){
		$order_id = $order_id ?? 0;
		$db = \JFactory::getDBO();
		foreach($products as $prod){
			$package = new stdClass();
			$package->package_id = $package_id;
			$package->product_id = $prod['product_id'];
			$package->quantity = $prod['quantity'];
			$package->return_status_id = $prod['return_status_id'];	
			$package->customer_comment = $prod['customer_comment'];	
			
			$db->insertObject('#__jshopping_return_packages_products', $package);
		}
	}
	
	public function getPackageNumber($order_id){
		$db = \JFactory::getDBO(); 
       
	    $query = "SELECT max(`package`) FROM `#__jshopping_return_packages` WHERE `order_id`=".$order_id;
        $db->setQuery($query);
        $id = (int)$db->loadResult();
		$id++;
		
		return $id;
	}
	
	public function saveReturns(){
		$pkg_id = $this->savePackage($this->order_id);
		$this->savePackageProduct($pkg_id, $this->products);
		return true;
	}
	
	public function clear(){
		$session = JFactory::getSession();		
        $session->set('returns', '');
	}
	
	public function returnItemsQty($items, $return_products){		
		$db = \JFactory::getDBO();
		$_items = [];
		foreach($items as $k=>$prod){
			$_items[$prod->order_item_id] = $prod;
			$sqlSelectOrderItems = $db->getQuery(true);
			$sqlSelectOrderItems->select('SUM(quantity)')
				->from('#__jshopping_return_packages_products')
				->where('product_id = ' . $db->escape($prod->order_item_id));
			$_items[$prod->order_item_id]->product_quantity -= $db->setQuery($sqlSelectOrderItems)->loadResult();
			if($items && isset($return_products[$prod->order_item_id])){
				$_items[$prod->order_item_id]->show = 1;
			}else{
				$_items[$prod->order_item_id]->show = 0;
			}
							
		}
		

		return $_items;
	
	}
}