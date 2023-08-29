<?php 

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelRefund extends JModelLegacy
{
	public function save($order_id, $post){
		$order_id = $order_id ?? 0;
		$this->clearRefunds($order_id);
		foreach($post as $pack_id=>$val){
			if($val['product_id']){		
				$pid = $this->saveRefund($order_id, $pack_id, $post);
				if($post[$pack_id]){
					$this->saveRefundItem($pid, $pack_id, $order_id, $post[$pack_id] );
				}
			}
		}
		
	}
	
	public function clearRefunds($order_id){
		
		$db = \JFactory::getDBO();		
		$db->setQuery('DELETE FROM `#__jshopping_refunds` WHERE order_id='.(int)$order_id);
        $db->execute();	
		$db->setQuery('DELETE FROM `#__jshopping_refund_item` WHERE order_id='.(int)$order_id);
        $db->execute();
	}
	
	public function saveRefund($order_id,$refund_number, $post){
		$order_id = $order_id ?? 0;
		$post['order_tax'] = 0;
		$tax_ext = array();
		if (isset($post[$refund_number]['tax_percent'])){
			foreach($post[$refund_number]['tax_percent'] as $k=>$v){
				if ($post[$refund_number]['tax_percent'][$k]!="" || $post[$refund_number]['tax_percent'][$k]!=""){
					$tax_ext[number_format($post[$refund_number]['tax_percent'][$k],2)] = $post[$refund_number]['tax_value'][$k];
				}
			}
		}
		$post[$refund_number]['refund_tax_ext'] = serialize($tax_ext);
		$post[$refund_number]['refund_tax'] = number_format(array_sum($tax_ext),2);
		$db = \JFactory::getDBO();				
		$refund = new stdClass();
		$refund->order_id = $order_id;
		$refund->refund_total = $post[$refund_number]['total'];	
		$refund->refund_subtotal = $post[$refund_number]['subtotal'];	
		
		$refund->refund_tax = $post[$refund_number]['refund_tax'];
		$refund->refund_tax_ext = $post[$refund_number]['refund_tax_ext'];	
		
		$refund->refund_shipping = $post[$refund_number]['shipping'];
		$refund->refund_payment = $post[$refund_number]['payment'];
		$refund->refund_discount = $post[$refund_number]['discount'];
		$refund->refund_package = $post[$refund_number]['package'];
				
		$refund->shipping_tax = $post[$refund_number]['shipping_tax'];	
		$refund->payment_tax = $post[$refund_number]['payment_tax'];	
		$refund->payment_tax = $post[$refund_number]['payment_tax'];
				
		$refund->refund_date = strtotime($post[$refund_number]['refund_date']);	
		$refund->pdf_date = strtotime($post[$refund_number]['pdf_date']);	
		$refund->pdf_file = $post[$refund_number]['pdf_file'];	
        $jshopConfig = JSFactory::getConfig();
		
		$refund->refund_number = $post[$refund_number]['refund_number']; 
		/*}elseif($jshopConfig->next_refund_number){
			$refund->refund_number = $jshopConfig->next_refund_number;		
			$jshopConfig->updateNextRefundNumber($refund->refund_number + 1);
		}else{
			$refund->refund_number = count($this->getList($order_id)) + 1;
			$jshopConfig->updateNextRefundNumber($refund->refund_number + 1);	
			
		}	*/		
		
		$db->insertObject('#__jshopping_refunds', $refund); 
		$id = $db->insertid();		
		
		$db = \JFactory::getDBO();
		$query = "UPDATE `#__jshopping_refunds` SET `refund_date`='".$post[$refund_number]['refund_date']."', `pdf_date`='".$post[$refund_number]['pdf_date']."' WHERE `refund_id`=".$id;
		$db->setQuery($query);
		saveToLog("error.log", $query);
		saveToLog("error.log", "refund_date - ".$post[$refund_number]['refund_date']);
		saveToLog("error.log", "pdf_date - " . $post[$refund_number]['pdf_date']);
		$db->execute();
		
		return $id;
	}
	
	public function saveRefundItem($refund_id, $num, $order_id, $post){
		$order_id = $order_id ?? 0;
		$db = \JFactory::getDBO();
		foreach($post['product_id'] as $key=>$val){
			$item = new stdClass();
			$item->order_id = $order_id;
			$item->refund_id  = $refund_id;
			$item->product_id = $val;	
			$item->product_quantity = $post['product_quantity'][$key];	
			$item->product_item_price = $post['product_item_price'][$key];
			$item->product_tax  = $post['product_tax'][$key];
			$item->weight  = $post['weight'][$key];
			$item->order_item_id  = $post['item_id'][$key];
			
            $item->total_price = saveAsPrice((int)$post['product_quantity'][$key] * $post['product_item_price'][$key]);
			
			$db->insertObject('#__jshopping_refund_item', $item); 
		}
	}
	
	public function getList($order_id)
	{
		$_orders = JSFactory::getModel("orders");
		$result = [];

		if ( !empty($order_id) ) {
			$db = \JFactory::getDBO();
			$sqlSelectOrderItems = $db->getQuery(true);

			$sqlSelectOrderItems->select('*')
								->from('#__jshopping_refunds')
								->where('order_id = ' . $db->escape($order_id));

			$result = $db->setQuery($sqlSelectOrderItems)->loadObjectList();
		}
	    if(!empty($result)){
			
			foreach($result as $k => $val){
				//if($val->refund_tax_ext){
					$result[$k]->refund_tax_list = ($val->refund_tax_ext == '') ? [] : unserialize($val->refund_tax_ext); 
				//}
				$val->products = $this->getRefundProducts($val->refund_id);
			}
		}

		return $result;
	}
	
	function getRefundProducts($refund_id){
		$db = \JFactory::getDBO();
		$sqlSelectOrderItems = $db->getQuery(true);
		$sqlSelectOrderItems->select('*')
							->from('#__jshopping_refund_item')
							->where('refund_id = ' . $db->escape($refund_id));

		return $db->setQuery($sqlSelectOrderItems)->loadObjectList('order_item_id');
	}
}