<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class JshoppingModelOffer_and_order extends JModelLegacy 
{

    public function _getWhereForFilters($filters) 
    {
        $db = \JFactory::getDBO();
		$lang = JSFactory::getLang();
		$dispatcher = \JFactory::getApplication();
        $where = "";
        if (isset($filters['status_id']) && $filters['status_id']) {
            $where .= " and O.order_status = '" . $db->escape($filters['status_id']) . "'";
        }
        if ($filters['text_search']) {
            $search = $db->escape($filters['text_search']);
            $where .= " and (
			O.`f_name` like '%" . $search . "%'	or 
			O.`l_name` like '%" . $search . "%' or 
			O.`email` like '%" . $search . "%' or 
			O.`firma_name` like '%" . $search . "%' or 
			O.`d_f_name` like '%" . $search . "%' or 
			O.`d_l_name` like '%" . $search . "%' or 
			O.`d_firma_name` like '%" . $search . "%' or
			O.order_add_info like '%".$search."%' or 			
			O.`street` like '%".$search."%' or 
			O.`home` like '%".$search."%' or 
			O.`city` like '%".$search."%' or 
			O.`zip` like '%".$search."%' or 
			O.`phone` like '%".$search."%' or 
			O.`mobil_phone` like '%".$search."%' or 
			O.`fax` like '%".$search."%' or 
			O.`d_street` like '%".$search."%' or 
			O.`d_city` like '%".$search."%' or 
			O.`d_state` like '%".$search."%' or 
			O.`d_zip` like '%".$search."%' or  
			O.`projectname` like '%" . $search . "%'  or
			O.`order_number` like '%" . $search . "%' or
			P.`".$lang->get('name')."` like '%".$search."%' or 
			P.`".$lang->get('description')."` like '%".$search."%' or 			
            O.`d_phone` like '%".$search."%')";
        }
        if ($filters['year'] != 0)
            $year = $filters['year'];
        else
            $year = "%";
        if ($filters['month'] != 0)
            $month = $filters['month'];
        else
            $month = "%";
        if ($filters['day'] != 0)
            $day = $filters['day'];
        else
            $day = "%";
        $where .= " and O.order_date like '" . $year . "-" . $month . "-" . $day . " %'";
        if (isset($filters['vendor_id']) && $filters['vendor_id']) {
            $where .= " and OI.vendor_id='" . $db->escape($filters['vendor_id']) . "'";
        }

        $dispatcher->triggerEvent('onBeforeReturnWhereForFilter', array(&$filters, &$where));
        return $where;
    }

    public function getCountAllOrders($filters) 
    {
        $db = \JFactory::getDBO();
		$lang = JSFactory::getLang();
        $where = $this->_getWhereForFilters($filters);
        if (isset($filters['vendor_id']) && $filters['vendor_id']) {
            $query = "SELECT COUNT(distinct O.order_id) FROM `#__jshopping_offer_and_order` as O
                  left join `#__jshopping_offer_and_order_item` as OI on OI.order_id=O.order_id
				  left join `#__jshopping_products` as P on P.product_id = OI.product_id
                  where 1 $where ORDER BY O.order_id DESC";
        } else {
            $query = "SELECT COUNT(distinct O.order_id) FROM `#__jshopping_offer_and_order` as O
                  left join `#__jshopping_offer_and_order_item` as OI on OI.order_id=O.order_id
				  left join `#__jshopping_products` as P on P.product_id = OI.product_id where 1 " . $where;
        }
	    $db->setQuery($query);

        return $db->loadResult();
    }

    public function getAllOrders($limitstart, $limit, $filters, $filter_order, $filter_order_Dir, $user_id = 0) 
    {
        $db = \JFactory::getDBO();
		$lang = JSFactory::getLang();
        $where = $this->_getWhereForFilters($filters);
        $where .= " and O.`offer_status` = 2 ";
        if (!empty($user_id)) {
            $where .= " and O.user_id=" . intval($user_id) . " ";
        }
        
        $order = $filter_order . " " . $filter_order_Dir;

		$query = "SELECT distinct O.* FROM `#__jshopping_offer_and_order` as O
			  left join `#__jshopping_offer_and_order_item` as OI on OI.order_id=O.order_id
			  left join `#__jshopping_products` as P on P.product_id = OI.product_id
			  where 1 $where ORDER BY " . $order;
   
        $db->setQuery($query, $limitstart, $limit);

        return $db->loadObjectList();
    }

    public function getMinYear() 
    {
        $db = \JFactory::getDBO();
        $query = "SELECT min(order_date) FROM `#__jshopping_offer_and_order`";
        $db->setQuery($query);
        $res = substr($db->loadResult(), 0, 4);
        if (intval($res) == 0)
            $res = "2010";

        return $res;
    }

    public function saveOrderItem($order_id, $post, $old_items) 
    {
        $db = \JFactory::getDBO();
        if (!is_array($post['product_name']))
            $post['product_name'] = array();

        $edit_order_items = array();
        foreach ($post['product_name'] as $k => $v) {
            $order_item_id = intval($post['order_item_id'][$k]);
            $edit_order_items[] = $order_item_id;
            $order_item = JTable::getInstance('offer_and_orderItem', 'jshop');
            $order_item->order_item_id = $order_item_id;
            $order_item->order_id = $order_id;
            $order_item->product_id = $post['product_id'][$k];
            $order_item->product_ean = $post['product_ean'][$k];
            $order_item->product_name = $post['product_name'][$k];
            $order_item->product_quantity = $post['product_quantity'][$k];
            $order_item->product_item_price = $post['product_item_price'][$k];
            $order_item->product_tax = $post['product_tax'][$k];
            $order_item->product_attributes = $post['product_attributes'][$k];
            $order_item->product_freeattributes = $post['product_freeattributes'][$k];
            $order_item->weight = $post['weight'][$k];
            $order_item->delivery_times_id = $post['delivery_times_id'][$k];
            $order_item->vendor_id = $post['vendor_id'][$k];
            $order_item->thumb_image = $post['thumb_image'][$k];
            $order_item->files = serialize(array());
            $order_item->store();
            unset($order_item);
        }

        foreach ($old_items as $k => $v) {
            if (!in_array($v->order_item_id, $edit_order_items)) {
                $order_item = JTable::getInstance('offer_and_orderItem', 'jshop');
                $order_item->delete($v->order_item_id);
            }
        }

        return 1;
    }

    public function createOrderItem(&$offer_item, &$order) {
        $order_id = $order->order_id;
        $files = unserialize($offer_item->uploaded_files);
        $order_item = JTable::getInstance('orderItem', 'jshop');
        $arr_onliy_offerItem = array('order_item_id', 'order_id', 'uploaded_files');
        
        foreach ($offer_item as $key => $value) {
            if (!in_array($key, $arr_onliy_offerItem)) {
                $order_item->$key = $value;
            }
        }
        
        $order_item->order_id = $order_id;
        $order_item->store();

        $this->saveUploadFiles($order_id, $offer_item->order_item_id, $files);
    }

    private function saveUploadFiles(int $order_id, int $item_id, array $files) : void
    {
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $model = JSFactory::getModel('orderItemsNativeUploadsFiles');
        $model->massInsert($order_id, $item_id, $files);
    }


    public function saveOfferItemToOrderItem(&$offer, &$order) 
    {
        $offer->getAllItems();
        foreach ($offer->items as $offer_item) {
            $this->createOrderItem($offer_item, $order);
            $order_item = JTable::getInstance('offer_and_orderItem', 'jshop');
            $order_item->delete($offer_item->order_item_id);
        }

        return 1;
    }

    public function loadtaxorder($data_order, $products)
    {
        $jshopConfig = JSFactory::getConfig();
        $jshopConfig->display_price_front_current = $data_order['display_price'];
        $display_price_front_current = $data_order['display_price'];
        $taxes = array();
        $total = 0;
        $AllTaxes = JSFactory::getAllTaxes();
        $id_country = $data_order['d_country'];
        if (!$id_country){
            $id_country = $data_order['country'];
        }
        if (!$id_country){
            $id_country = $jshopConfig->default_country;
        }
        
        // tax product
        foreach($products as $key=>$product){
			$product = (array)$product;
            $tax = (string)floatval($product['product_tax']);
			$price = $product['product_item_price'] * $product['product_quantity'];
            $SumTax = (isset($taxes[$tax])) ? $taxes[$tax] : 0;
            $taxes[$tax] =  $SumTax + getPriceTaxValue($price, $tax, $display_price_front_current);
            $total += $price;
        }
        $cproducts = $this->getCartProductsFromOrderProducts($products);
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models/');
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->products = [];
        foreach($cproducts as $v){
            $cart->products[] = $v;   
        }
        $cart->loadPriceAndCountProducts();

        // payment
        if ($data_order['order_payment']!=0){
            $price = $data_order['order_payment'];
            $payment_method_id = $data_order['payment_method_id'];
            $paym_method = JSFactory::getTable('paymentmethod', 'jshop');
            $paym_method->load($payment_method_id);
            $paym_method->setCart($cart);
            $payment_taxes = $paym_method->calculateTaxList($price);   
			
            foreach($payment_taxes as $k=>$v){
                $k = (string)floatval($k);
                $SumTax = (isset($taxes[$k]))?$taxes[$k]:0;
                $taxes[$k] = $SumTax + $v;
            }
            $total += $price;
        }
        
        //shipping
        // $sh_pr_method_id = $this->getShippingPriceId($data_order['shipping_method_id'], $id_country);
        $sh_pr_method_id = $data_order['shipping_method_id'];
        
        $shipping_method_price = JSFactory::getTable('shippingMethodPrice', 'jshop');
        $shipping_method_price->load($sh_pr_method_id);
        
        // tax shipping
        if ($data_order['order_shipping']>0){
            $price = $data_order['order_shipping'];            
            $shipping_taxes = $shipping_method_price->calculateShippingTaxList($price, $cart);
            foreach($shipping_taxes as $k=>$v){
                $k = (string)floatval($k);
                $SumTax = (isset($taxes[$k]))?$taxes[$k]:0;
                $taxes[$k] = $SumTax + $v;
            }
            $total += $price;
        }
        // tax package
        if ($data_order['order_package']>0){
            $price = $data_order['order_package'];
            $shipping_taxes = $shipping_method_price->calculatePackageTaxList($price, $cart);
            foreach($shipping_taxes as $k=>$v){
                $k = (string)floatval($k);
                $SumTax = (isset($taxes[$k]))?$taxes[$k]:0;
                $taxes[$k] = $SumTax + $v;
            }
            $total += $price;
        }
        
        $taxes_array = array();
        foreach($taxes as $tax=>$value){
            if ($tax>0){
                $taxes_array[] = array('tax'=>$tax, 'value'=>$value);
            }
        }
		
        if ($data_order['order_discount'] > 0 && $jshopConfig->calcule_tax_after_discount){
            $discountPercent = ($total > 0) ? $data_order['order_discount'] / $total : $data_order['order_discount'];
            foreach($taxes_array as $k=>$v){
                $taxes_array[$k]['value'] = $v['value'] * (1 - $discountPercent);
            }
        }

        extract(js_add_trigger(get_defined_vars(), "before"));
        return $taxes_array;
    }

    /* public function getShippingPriceId($shipping_id, $country_id, $publish = 1)
    {
        $db = \JFactory::getDBO(); 
        $query_where = ($publish) ? ("AND sh_method.published = '1'") : ("");
        $query = "SELECT sh_pr_method.sh_pr_method_id FROM `#__jshopping_shipping_method` AS sh_method
                  INNER JOIN `#__jshopping_shipping_method_price` AS sh_pr_method ON sh_method.shipping_id = sh_pr_method.shipping_method_id
                  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
                  INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
                  WHERE countries.country_id = '".$db->escape($country_id)."' and sh_method.shipping_id=".intval($shipping_id)."  $query_where";
        extract(js_add_trigger(get_defined_vars(), "query"));
        $db->setQuery($query);
        return (int)$db->loadResult();
    }*/

    public function getCartProductsFromOrderProducts($items)
    {
        $products = array();
        foreach($items as $k=>$v){
			$v = (array)$v;
            $prod = array();
            $prod['product_id'] = $v['product_id'];
            $prod['quantity'] = $v['product_quantity'];
            $prod['tax'] = $v['product_tax'];
            $prod['product_name'] = $v['product_name'];
            $prod['thumb_image'] = $v['thumb_image'];
            $prod['ean'] = $v['product_ean'];
            $prod['weight'] = $v['weight'];
            $prod['delivery_times_id'] = $v['delivery_times_id'];
            $prod['vendor_id'] = $v['vendor_id'];
            $prod['price'] = $v['product_item_price'];
            $products[] = $prod;
        }
        extract(js_add_trigger(get_defined_vars(), "before"));
        return $products;
    }
	
	public function deleteOffer_and_order($cid){
        $jshopConfig = JSFactory::getConfig();
		$_dbdelete = JSFactory::getModel('dbdelete');
		$db = \JFactory::getDBO();
        
		$tmp = array();
		if (count($cid))
		foreach ($cid as $key => $value) {
			$query = "SELECT `pdf_file` FROM `#__jshopping_offer_and_order` WHERE `order_id`=".$value;
			$db->setQuery($query);
			$pdf = $db->loadResult();
			 $url = $jshopConfig->pdf_orders_path . '/' . $pdf;
			
			if($_dbdelete->deleteItems("#__jshopping_offer_and_order","order_id",$value)){
                $_dbdelete->deleteItems("#__jshopping_offer_and_order_item","order_id",$value);
				$tmp[] = $value;
				if(file_exists($url)){
					unlink($url);
				}
			}
		}
		return $text;
	}  	  	

}