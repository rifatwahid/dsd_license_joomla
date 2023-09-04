<?php
/**
 * @version      7.4.0
 * @author       
 * @package      Smarteditor
 * @copyright    Copyright (C) 2010. All rights reserved.
 * @license      GNU/GPL
 */
defined('_JEXEC') or die();

class jshopSmarteditorQuickOrder {

    protected $post;
    protected $xmlid;
    protected $aftereditor;

    public function setData($post, $xmlid, $aftereditor){
        $this->post = $post;
        $this->xmlid = $xmlid;
        $this->aftereditor = $aftereditor;
    }

    public function save(){
        $jshopConfig = JSFactory::getConfig();
        $smarteditor = JModelLegacy::getInstance('smarteditor', 'jshop');

        $attr_file_id  = $smarteditor->getFileEditorFreeAttrId();
		$params = $this->post['parrams'];
		$params['xmlname'] = 'smarteditor|'.$params['ftpName'];
        $user_data = $this->post['user'];

        if ($this->aftereditor){
            $product_id = $smarteditor->copyProductToNew($params['product_id'], array('params'=>$params, 'xmlid'=>$this->xmlid));
        }else{
            $product_id = $params['product_id'];
        }

        $params['freeattribut'][$attr_file_id] = $params['xmlname'];

        $temp_product = $this->getCartProduct($product_id, $params, $user_data);
        $products = array($temp_product);
        
        $adv_user = JSFactory::getUser();
        $orderNumber = $jshopConfig->getNextOrderNumber();
        $jshopConfig->updateNextOrderNumber();

        $order = JSFactory::getTable('order', 'jshop');
        $arr_property = $order->getListFieldCopyUserToOrder();
        foreach($adv_user as $key => $value){
            if (in_array($key, $arr_property)){
                $order->$key = $value;
            }
        }
        foreach($user_data as $k => $v){
            if (in_array($k, $arr_property)){
                $order->$k = $v;
            }
        }


        $order->order_date = $order->order_m_date = getJsDate();
        $order->order_subtotal = $this->getSubTotalPrice($products);
        $order->order_total = $this->getTotalPrice($products);
        //$order->order_tax = $cart->getTax(1, 1, 1);
        //$order->setTaxExt($cart->getTaxExt(1, 1, 1));
        $order->currency_exchange = $jshopConfig->currency_value;
        $order->order_status = $jshopConfig->default_status_order;
 
        $order->ip_address = $_SERVER['REMOTE_ADDR'];
        $order->order_add_info = JFactory::getApplication()->input->getVar('order_add_info','');
        $order->currency_code = $jshopConfig->currency_code;
        $order->currency_code_iso = $jshopConfig->currency_code_iso;
        $order->order_number = $order->formatOrderNumber($orderNumber);
        $order->order_hash = md5(time().$order->order_total.$order->user_id);
        $order->file_hash = md5(time().$order->order_total.$order->user_id."hashfile");
        $order->display_price = $jshopConfig->display_price_front_current;
        $order->lang = $jshopConfig->getLang();
        $order->order_created = 1;
        $order->store();

        $order->saveOrderItem($products);

        return $order;
    }

    function getSubTotalPrice($products){
        $total = 0;
        foreach($products as $prod){
            $total += $prod['price'] * $prod['quantity'];
        }
        return $total;
    }

    function getTotalPrice($products){
        $jshopConfig = JSFactory::getConfig();
        $total = 0;
        foreach($products as $prod){
            if ($jshopConfig->display_price_front_current==1){
                $total += ($prod['price'] * (1 + $prod['tax'] / 100)) * $prod['quantity'];
            }else{
                $total += $prod['price'] * $prod['quantity'];
            }
        }
        return $total;
    }

    function getCartProduct($product_id, $params, $user_data){
        $jshopConfig = JSFactory::getConfig();
        $quantity = $params['quantity'];
        if (!$quantity){
            $quantity = 1;
        }
        $attr_id = (array)$params['jshop_attr_id'];
        $freeattributes = (array)$params['freeattribut'];
        $attr_serialize = serialize($attr_id);
        $free_attr_serialize = serialize($freeattributes);

        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);
        $product->setAttributeActive($attr_id);
        $product->setFreeAttributeActive($freeattributes);
        $product->getDescription();
        $pidCheckQtyValue = $product->getPIDCheckQtyValue();

        $temp_product = array();
        $temp_product['quantity'] = $quantity;
        $temp_product['product_id'] = $product_id;
        $temp_product['category_id'] = $product->getCategory();
        $temp_product['tax'] = $product->getTax();
        $temp_product['tax_id'] = $product->product_tax_id;
        $temp_product['product_name'] = $product->name;
        $temp_product['design_name'] = $user_data['design_name'];
        $temp_product['thumb_image'] = getPatchProductImage($product->getData('image'), 'thumb');
        $temp_product['delivery_times_id'] = $product->getDeliveryTimeId();
        $temp_product['ean'] = $product->getEan();
        $temp_product['attributes'] = $attr_serialize;
        $temp_product['attributes_value'] = array();
        $temp_product['extra_fields'] = array();
        $temp_product['weight'] = $product->getWeight();
        $temp_product['vendor_id'] = fixRealVendorId($product->vendor_id);
        $temp_product['files'] = serialize($product->getSaleFiles());
        $temp_product['freeattributes'] = $free_attr_serialize;
        $temp_product['free_attributes_value'] = $this->getFreeAttributesValue($freeattributes);
        if ($jshopConfig->show_manufacturer_in_cart || $jshopConfig->show_product_manufacturer_in_cart) {
            $manufacturer_info = $product->getManufacturerInfo();
            $temp_product['manufacturer'] = $manufacturer_info->name;
        }else{
            $temp_product['manufacturer'] = '';
        }
        $temp_product['pid_check_qty_value'] = $pidCheckQtyValue;
        $i = 0;
        if (is_array($attr_id) && count($attr_id)){
            foreach($attr_id as $key=>$value){
                $attr = JSFactory::getTable('attribut', 'jshop');
                $attr_v = JSFactory::getTable('attributvalue', 'jshop');
                $temp_product['attributes_value'][$i] = new stdClass();
                $temp_product['attributes_value'][$i]->attr_id = $key;
                $temp_product['attributes_value'][$i]->value_id = $value;
                $temp_product['attributes_value'][$i]->attr = $attr->getName($key);
                $temp_product['attributes_value'][$i]->value = $attr_v->getName($value);
                $i++;
            }
        }

        if ($jshopConfig->admin_show_product_extra_field && count($jshopConfig->getCartDisplayExtraFields())>0){
            $extra_field = $product->getExtraFields(2);
            $temp_product['extra_fields'] = $extra_field;
        }

        $temp_product['price'] = $product->getPrice($quantity, 1, 1, 1, $temp_product);

        return $temp_product;
    }

    function getFreeAttributesValue($freeattributes){
        $_freeattributes = JSFactory::getTable('freeattribut', 'jshop');
        $namesfreeattributes = $_freeattributes->getAllNames();
        if (!is_array($freeattributes)){
            $freeattributes = array();
        }
        $free_attributes_value = array();
        foreach($freeattributes as $id=>$text){
            $obj = new stdClass();
            $obj->attr = $namesfreeattributes[$id];
            $obj->value = $text;
            $free_attributes_value[] = $obj;
        }
        return $free_attributes_value;
    }


}