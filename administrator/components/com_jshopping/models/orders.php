<?php
/**
* @version      4.7.0 13.06.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelOrders extends JModelLegacy
{    

    public function getCountAllOrders($filters) 
    {
        $db = \JFactory::getDBO();
        $where = [];

        if (!empty($filters['status_id'])) {
            $where[] = "and O.order_status = '{$db->escape($filters['status_id'])}'";
        }

        if(!empty($filters['user_id'])) {
            $where[] = "and O.user_id = '".$db->escape($filters['user_id'])."'";
        }

        if(!empty($filters['coupon_id'])) {
            $where[] = "and O.coupon_id = '".$db->escape($filters['coupon_id'])."'";
        }

        if (!empty($filters['text_search'])) {
            $search = $db->escape($filters['text_search']);
            $where[] = "and (O.`order_number` like '%{$search}%' or 
                OA.`f_name` like '%{$search}%' or 
                OA.`l_name` like '%{$search}%' or 
                OA.`email` like '%{$search}%' or 
                OA.`firma_name` like '%{$search}%' or 
                OA.`d_f_name` like '%{$search}%' or 
                OA.`d_l_name` like '%{$search}%' or 
                OA.`d_firma_name` like '%{$search}%' or 
                O.order_add_info like '%{$search}%')";
        }

        if (!$filters['notfinished']) {
            $where[] = "and O.order_created='1'";
        }

        $year = $day = $month = '%';
        if ($filters['year'] != 0) {
            $year = $filters['year'];
        }

        if ($filters['month'] != 0) {
            $month = $filters['month'];
        }

        if ($filters['day'] != 0) {
            $day = $filters['day'];
        }

        $where[] = "and O.order_date like '{$year}-{$month}-{$day} %'";  
        $where = implode(' ', $where);  

        $query = "SELECT COUNT(O.order_id) FROM `#__jshopping_orders` as O
            left join `#__jshopping_order_addresses` as OA on O.order_address_id = OA.id
            where 1 {$where}";
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeQueryGetCountAllOrders', [&$query, &$filters, &$where]);
        $db->setQuery($query);

        return $db->loadResult();
    }

    public function getAllOrders($limitstart, $limit, $filters, $filter_order, $filter_order_Dir)
    {
        $db = \JFactory::getDBO(); 
		$lang = JSFactory::getLang();
        $dispatcher = \JFactory::getApplication();
        $where = [];

        if (!empty($filters['status_id'])) {
            $where[] = "and O.order_status = '{$db->escape($filters['status_id'])}'";
        }

        if(!empty($filters['user_id'])) {
            $where[] = "and O.user_id = '{$db->escape($filters['user_id'])}'";
        }
		
        if(!empty($filters['coupon_id'])) {
            $where[] = "and O.coupon_id = '{$db->escape($filters['coupon_id'])}'";
        }

        if (!empty($filters['text_search'])) {
            $search = $db->escape($filters['text_search']);
            $where[] = "and (
                O.`order_number` like '%".$search."%' or 
                OA.`f_name` like '%".$search."%' or 
                OA.`l_name` like '%".$search."%' or 
                OA.`email` like '%".$search."%' or 
                OA.`firma_name` like '%".$search."%' or 
                OA.`d_f_name` like '%".$search."%' or 
                OA.`d_l_name` like '%".$search."%' or 
                OA.`d_firma_name` like '%".$search."%' or 
                O.order_add_info like '%".$search."%' or 			
                OA.`street` like '%".$search."%' or 
                OA.`street_nr` like '%".$search."%' or 
                OA.`city` like '%".$search."%' or 
                OA.`zip` like '%".$search."%' or 
                OA.`phone` like '%".$search."%' or 
                OA.`mobil_phone` like '%".$search."%' or 
                OA.`fax` like '%".$search."%' or 
                OA.`email` like '%".$search."%' or 
                OA.`d_street` like '%".$search."%' or 
                OA.`d_city` like '%".$search."%' or 
                OA.`d_state` like '%".$search."%' or 
                OA.`d_zip` like '%".$search."%' or 
                P.`".$lang->get('name')."` like '%".$search."%' or 
                P.`".$lang->get('description')."` like '%".$search."%' or 			
                OA.`d_phone` like '%".$search."%')";
        }

        if (!$filters['notfinished']) {
            $where[] = "and O.order_created='1'";
        }

        $year = $month = $day = '%';
        if ($filters['year'] != 0) {
            $year = $filters['year'];
        }

        if ($filters['month'] != 0) {
            $month = $filters['month'];
        }

        if ($filters['day'] != 0) {
            $day = $filters['day'];
        }
        
        $where[] = "and O.order_date like '{$year}-{$month}-{$day} %'";
		switch($filter_order){
			case 'payment_name':
				$filter_order=' PM.`' . $lang->get('name') . '`';
				break;
			case 'shipping_name':
				$filter_order=' SM.`' . $lang->get('name') . '`';
				break;
			default:
				$filter_order = $filter_order;
		}
        $order = "{$filter_order} {$filter_order_Dir}";

        $columnsToSelect = [
            'O.*',
            'concat(OA.f_name, " ", OA.l_name) as name',
            'OA.`f_name`',
            'OA.`l_name`',
            'OA.`firma_name`',
            'OA.`d_f_name`',
            'OA.`d_l_name`',
            'OA.`d_firma_name`',
            'OA.`street`',
            'OA.`street_nr`',
            'OA.`city`',
            'OA.`zip`',
            'OA.`phone`',
            'OA.`mobil_phone`',
            'OA.`fax`',
            'OA.`email`',
            'OA.`d_street`',
            'OA.`d_city`',
            'OA.`d_state`',
            'OA.`d_zip`',
            'OA.`d_phone`',
            'PM.`' . $lang->get('name') . '` as `payment_name`',
            'SM.`' . $lang->get('name') . '` as `shipping_name`',
        ];
        
        $join = [
            'left join `#__jshopping_order_item` as OI on OI.order_id = O.order_id',
			'left join `#__jshopping_products` as P on P.product_id = OI.product_id',
            'left join `#__jshopping_order_addresses` as OA on O.order_address_id = OA.id',
            'left join `#__jshopping_payment_method` as PM on O.payment_method_id = PM.payment_id',
            'left join `#__jshopping_shipping_method` as SM on O.shipping_method_id = SM.shipping_id'
        ];

        $dispatcher->triggerEvent('onBeforePrepareQueryForGetAllOrders', [&$join, &$filters, &$filter_order, &$filter_order_Dir, &$where, &$order, &$columnsToSelect]);
        
        $where = implode(' ', $where);
		$query = "SELECT distinct " . implode(', ', $columnsToSelect) . " FROM `#__jshopping_orders` as O
			" . implode(' ', $join) . "
            where 1 {$where} ORDER BY {$order}";
        
        $dispatcher->triggerEvent('onBeforeQueryGetAllOrders', [&$query, &$filters, &$filter_order, &$filter_order_Dir, &$where, &$order]);
        $db->setQuery($query, $limitstart, $limit);

        return $db->loadObjectList();
    }

    public function getAllOrderStatus($order = null, $orderDir = null) {
        $db = \JFactory::getDBO(); 
        $lang = JSFactory::getLang();
        $ordering = "status_id";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT *, `".$lang->get('name')."` as name, `color` FROM `#__jshopping_order_status` ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    } 
	
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
        return $db->loadObjectList();
    }
    
    public function getMinYear(){
        $db = \JFactory::getDBO();
        $query = "SELECT min(order_date) FROM `#__jshopping_orders`";
        $db->setQuery($query);
        $res = substr($db->loadResult(),0, 4);
        if (intval($res)==0) $res = "2010";
        extract(js_add_trigger(get_defined_vars(), "before"));
        return $res;
    }
    
    public function saveOrderItem($order_id, $post, $old_items){
        $db = \JFactory::getDBO();
        if (!isset($post['product_name'])) $post['product_name'] = array();

        $edit_order_items = array();
        foreach($post['product_name'] as $k=>$v){
            $order_item_id = intval($post['order_item_id'][$k]);
            $edit_order_items[] = $order_item_id;
            $order_item = JSFactory::getTable('orderItem', 'jshop');
            $order_item->order_item_id = $order_item_id;
            $order_item->order_id = $order_id;
            $order_item->product_id = $post['product_id'][$k];
            $order_item->product_ean = $post['product_ean'][$k];
            $order_item->product_name = $post['product_name'][$k];
            $order_item->product_quantity = saveAsPrice($post['product_quantity'][$k]);
            $order_item->product_item_price = $post['product_item_price'][$k];
            $order_item->product_tax = $post['product_tax'][$k];
            $order_item->product_attributes = $post['product_attributes'][$k];
            $order_item->product_freeattributes = $post['product_freeattributes'][$k];
            $order_item->weight = $post['weight'][$k];
            $order_item->total_price = saveAsPrice((int)$post['product_quantity'][$k] * $post['product_item_price'][$k]);
            if (isset($post['delivery_times_id'][$k])){
                $order_item->delivery_times_id = $post['delivery_times_id'][$k];
            }else{
                $order_item->delivery_times_id = 0;
            }
            $order_item->vendor_id = $post['vendor_id'][$k];
            $order_item->thumb_image = $post['thumb_image'][$k];
            $order_item->files = serialize(array());
			$dispatcher = \JFactory::getApplication();
			$dispatcher->triggerEvent('onBeforeOrderItemStore', array(&$k,&$order_item,&$post));
            $order_item->store();
            unset($order_item);
        }

        foreach($old_items as $k=>$v){
            if (!in_array($v->order_item_id, $edit_order_items)){
                $order_item = JSFactory::getTable('orderItem', 'jshop');
                $order_item->delete($v->order_item_id);                
            }
        }
        extract(js_add_trigger(get_defined_vars(), "before"));
        return 1;
    }

    public function loadtaxorder($data_order, $products){
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
            $tax = (string)floatval($product['product_tax']);
            $price = $product['product_item_price'] * $product['product_quantity'] + $product['one_time_cost'];/////!!!!!;
            $SumTax = (isset($taxes[$tax]))?$taxes[$tax]:0;
            $taxes[$tax] =  $SumTax + getPriceTaxValue($price, $tax, $display_price_front_current);
            $total += $price;
        }
        
        $cproducts = $this->getCartProductsFromOrderProducts($products);
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models/');
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->products = [];
        $cart->loadProductsFromArray($cproducts);
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
        $shipping_method = JSFactory::getTable('shippingMethod', 'jshop');
        //$sh_pr_method_id = $shipping_method->getShippingPriceId($data_order['shipping_method_id'], $id_country);
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

    public function getCartProductsFromOrderProducts($items){
        $products = array();
        foreach($items as $k=>$v){
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

    public function deleteOrders($cid)
    {
		$_dbdelete = JSFactory::getModel('dbdelete');
        $tmp = [];
        
		if (!empty($cid)) {
            foreach ($cid as $id) {					
                if($_dbdelete->deleteItems("#__jshopping_orders","order_id", $id)) {
                    $_dbdelete->deleteItems("#__jshopping_order_item","order_id", $id);
                    $_dbdelete->deleteItems("#__jshopping_order_history","order_id", $id);
                    $tmp[] = $id;
                }
            }
        }
		
		return $tmp;
	}    
	
	public function getSelect_changestatus($status_id){
		$_list_status0[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_ALL_ORDERS'), 'status_id', 'name');
        $_list_status = $this->getAllOrderStatus();
        $_list_status = array_merge($_list_status0, $_list_status);
		return JHTML::_('select.genericlist', $_list_status,'status_id','class="form-select" style = "width: 170px;" ','status_id','name', $status_id );
	}
	
	public function buildOrdersList(&$rows, &$payments_list = array()){
		JPluginHelper::importPlugin('jshoppingadmin');
		$dispatcher = \JFactory::getApplication();		
		$total = 0;
        JModelLegacy::addIncludePath(JPATH_COMPONENT_SITE . '/models');
        $_orderitemsnativeuploadsfiles = JSFactory::getModel('orderItemsNativeUploadsFiles');
		foreach($rows as $k=>$row){            
            $display_info_order = 1;			
			
			$dispatcher->triggerEvent('onBeforeBuildOrdersListRowsAdmin', array(&$rows,&$k,&$display_info_only_my_order,&$display_info_order));	
			
            $rows[$k]->display_info_order = $display_info_order;
            
            $blocked = 0;
            if (orderBlocked($row) || !$display_info_order) $blocked = 1;
            $rows[$k]->blocked = $blocked;
			
			$rows[$k]->payment_name = $payments_list[$row->payment_method_id] ?? '';
            $rows[$k]->shipping_name = $shippings_list[$row->shipping_method_id] ?? '';
			$orderNativeUploadedFiles = $_orderitemsnativeuploadsfiles->getDataBy('order_id', $rows[$k]->order_id) ?: [];
            $nativeUploadedFilesInfo = [];

            if (!empty($orderNativeUploadedFiles['files'])) {
                $countOfuploadedFiles = count($orderNativeUploadedFiles['files']);

                for($i = 0; $i < $countOfuploadedFiles; $i++) {
                    $uploadednativeFileName = $orderNativeUploadedFiles['files'][$i];
                    $nativeUploadedFilesInfo['urls'][$i] = (JUri::root() . 'components/com_jshopping/files/files_upload/' . $uploadednativeFileName) ?: '';
                    $nativeUploadedFilesInfo['filesNames'][$i] = $uploadednativeFileName ?: '';
                }
            }

            $rows[$k]->uploads_files = $nativeUploadedFilesInfo;
			$rows[$k]->refunds = JSFactory::getModel("refund")->getList($row->order_id);
			if(!empty($rows[$k]->refunds)){
				foreach($rows[$k]->refunds as $val){
					if($val->pdf_file){
						$rows[$k]->refund_pdfs[] = $val->pdf_file;
					}
				}
			}
            $total += $row->currency_exchange > 0 ? $row->order_total / $row->currency_exchange : $row->order_total;
        }
		return $total;
	}
	
	public function getOrder($order_id){
        $jshopConfig = JSFactory::getConfig();
		$_countries = JSFactory::getModel("countries");
		$_coupons = JSFactory::getModel("coupons");
		
		$lang = JSFactory::getLang();        
		$name = $lang->get("name");
		
		$order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        
		$orderstatus = JSFactory::getTable('orderStatus', 'jshop');
        $orderstatus->load($order->order_status);
            
        $order->status_name = $orderstatus->$name;
		
        $shipping_method =JSFactory::getTable('shippingMethodPrice', 'jshop');
        $shipping_method->load($order->shipping_method_id);

        $order->shipping_info = $shipping_method->$name;
        		
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $pm_method->load($order->payment_method_id);
        $order->payment_name = $pm_method->$name;
		
		$_countries->getOrderCountry($order);
		$_countries->getOrderDeliveryCountry($order);
        
        $order->title = JText::_($jshopConfig->user_field_title[$order->title]);
        $order->d_title = JText::_($jshopConfig->user_field_title[$order->d_title]);
		
		$order->birthday = getDisplayDate($order->birthday, $jshopConfig->field_birthday_format);
        $order->d_birthday = getDisplayDate($order->d_birthday, $jshopConfig->field_birthday_format);
		
		$jshopConfig->user_field_client_type[0]="";
        $order->client_type_name = JText::_($jshopConfig->user_field_client_type[$order->client_type]);
        
		$_coupons->getOrderCouponCode($order);
		
		
		return $order;
	}
	
	public function stockUpdate(&$order){
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->order_stock_removed_only_paid_status){
            $product_stock_removed = (in_array($order->order_status, $jshopConfig->payment_status_enable_download_sale_file));
        }else{
            $product_stock_removed = (!in_array($order->order_status, $jshopConfig->payment_status_return_product_in_stock));
        }
        
        if (!$product_stock_removed && $order->product_stock_removed==1){
            $order->changeProductQTYinStock("+");            
        }
        
        if ($product_stock_removed && $order->product_stock_removed==0){
            $order->changeProductQTYinStock("-");            
        }
	}
    
}
