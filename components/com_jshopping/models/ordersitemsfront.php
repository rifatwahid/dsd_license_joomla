<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelOrdersItemsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_order_item';
	
	public function checkProductFromUserOrders(int $user_id,$product_id){		
		$db = \JFactory::getDBO();
		$_ordersfront = JSFactory::getModel('ordersfront');
		$user_orders=$_ordersfront->getAllOrdersByUserId($user_id);				
		foreach ($user_orders as $key=>$value){
			$orderproducts=$this->getAllByOrderId($value->order_id);
			foreach ($orderproducts as $k=>$v){
				if ($v->product_id==$product_id) return 1;
			}			
		}
		return 0;
	}
	
    public function getAllByOrderId($orderId): array
    {
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($orderId);
        $jsUri = JSFactory::getJSUri();
        $items = [];

		try {			
			$query = "SELECT oi.*, p.publish_editor_pdf
					  FROM `" . static::TABLE_NAME . "` AS oi
					  JOIN `#__jshopping_products` AS p
					  ON oi.product_id = p.product_id
					  WHERE oi.order_id = '" . $db->escape($orderId) . "'";
			$db->setQuery($query);
			$items = $db->loadObjectList();
		} catch (Exception $e) {			
			$query = "SELECT oi.*
					  FROM `" . static::TABLE_NAME . "` AS oi
					  JOIN `#__jshopping_products` AS p
					  ON oi.product_id = p.product_id
					  WHERE oi.order_id = '" . $db->escape($orderId) . "'";
			$db->setQuery($query);
			$items = $db->loadObjectList();
		}

        if (!empty($items)) {
            $modelOfOrderItemsNativeUploadsFiles = JSFactory::getModel('orderItemsNativeUploadsFiles');
        
            foreach($items as $k => $v) {
                $items[$k]->_qty_unit = '';
                $items[$k]->delivery_time = '';
                $items[$k]->uploadData = $modelOfOrderItemsNativeUploadsFiles->getDataByOrderAndItemId($items[$k]->order_id, $items[$k]->order_item_id);;
                $items[$k]->formatprice_quantity = formatprice($items[$k]->product_item_price * $items[$k]->product_quantity, $order->currency_code);
                $items[$k]->formatprice = formatprice($items[$k]->product_item_price, $order->currency_code);
                if (!empty($items[$k]->uploadData)) {
                    $items[$k]->uploadedFiles = sprintPreviewNativeUploadedFiles($items[$k]->uploadData);
                }
                $items[$k]->repeatorderlink = SEFLink('index.php?option=com_jshopping&controller=repeatorder&task=add&product_id=' . $items[$k]->product_id . '&order_id=' . $order->order_id . '&order_item_id=' . $items[$k]->order_item_id, 1);
                $items[$k]->files = unserialize($items[$k]->files);
                if (!empty($items[$k]->uploadData)) {
                    $items[$k]->uploadDataBlock = sprintPreviewNativeUploadedFiles($items[$k]->uploadData);
                }
                $items[$k]->urlToThumbImage = $jshopConfig->no_image_product_live_path;

                if (!empty($items[$k]->thumb_image)) {
                    $items[$k]->urlToThumbImage = $jsUri->isUrl($items[$k]->thumb_image) ? $items[$k]->thumb_image : "{$jshopConfig->image_product_live_path}/{$items[$k]->thumb_image}";
                }
                $product = JSFactory::getTable('product', 'jshop');
                $product->load($items[$k]->product_id);
                if ($product->product_id) {
                    $attr_id = unserialize($items[$k]->attributes) ?: [];
                    $product->setAttributeActive($attr_id);
                    $productWithSupportUpload = $product->getEssenceWithActiveUpload();

                    $items[$k]->is_allow_uploads = (isset($productWithSupportUpload->is_allow_uploads)) ? (bool)$productWithSupportUpload->is_allow_uploads : false;
					$items[$k]->max_allow_uploads = 1;
					if((isset($productWithSupportUpload->is_unlimited_uploads) && $productWithSupportUpload->is_unlimited_uploads)){
						$items[$k]->max_allow_uploads =  INF;
					}elseif(isset($productWithSupportUpload->max_allow_uploads)){
						$items[$k]->max_allow_uploads =  $productWithSupportUpload->max_allow_uploads;
					}
                    $items[$k]->is_required_upload = (isset($productWithSupportUpload->is_required_upload)) ? (bool)$productWithSupportUpload->is_required_upload : false;
                    $items[$k]->productMaxQty = $product->unlimited ? INF : (int)$product->getFullQty();
                    $items[$k]->is_unlimited_uploads = (isset($productWithSupportUpload->is_unlimited_uploads)) ? (bool)$productWithSupportUpload->is_unlimited_uploads : false;
                    $items[$k]->is_upload_independ_from_qty = (isset($productWithSupportUpload->is_upload_independ_from_qty)) ? (bool)$productWithSupportUpload->is_upload_independ_from_qty : false;

                    $items[$k]->js_template_for_native_uploaded = sprintJsTemplateForNativeUploadedOrderFiles($items[$k]->is_unlimited_uploads, $items[$k]->order_item_id);//(bool)$productWithSupportUpload->is_upload_independ_from_qty;


                }
                if($items[$k]->extra_fields && is_array(json_decode($items[$k]->extra_fields))){
                    $items[$k]->display_extra_fields = separateExtraFieldsWithUseHideImageCharactParams(json_decode($items[$k]->extra_fields), 'my_orders');
                }else{
                    $items[$k]->display_extra_fields = '';
                }

            }

            if ($jshopConfig->display_delivery_time_for_product_in_order_mail) {
                $deliverytimes = JSFactory::getAllDeliveryTime();

                foreach($items as $k => $v) {
                    if (isset($deliverytimes[$v->delivery_times_id])) {
                        $items[$k]->delivery_time = $deliverytimes[$v->delivery_times_id];
                    }
                }
            }
        }
         
        return $items;
    }

    public function saveOrderItems($items, $order_id) 
    {
        $dispatcher = \JFactory::getApplication();
        $orderItemsNativeUploadsFilesModel = JSFactory::getModel('orderItemsNativeUploadsFiles');

        if (!empty($items)) {
            foreach($items as $value) {
                $order_item = JSFactory::getTable('orderItem', 'jshop');
                $order_item->order_id = $order_id;
                $order_item->product_id = $value['product_id'];
                $order_item->product_ean = $value['ean'];
                $order_item->product_name = $value['product_name'];
                $order_item->product_quantity = $value['quantity'];
                $order_item->product_item_price = $value['price'];
                $order_item->product_tax = $value['tax'];
                $order_item->product_attributes = $attributes_value = '';
                $order_item->product_freeattributes = $free_attributes_value = '';
                $order_item->attributes = $value['attributes'];
                $order_item->files = $value['files'];
                $order_item->freeattributes = $value['freeattributes'];
                $order_item->weight = $value['weight'];
                $order_item->thumb_image = $value['thumb_image'];
                $order_item->delivery_times_id = $value['delivery_times_id'];
                $order_item->vendor_id = $value['vendor_id'];
                $order_item->manufacturer = $value['manufacturer'];
                $order_item->basicprice = $value['basicprice'];
                $order_item->basicpriceunit = $value['basicpriceunit'];
                $order_item->params = $value['params'];
                $order_item->total_price = $value['total_price'];
                
                if (isset($value['attributes_value'])) {
                    foreach ($value['attributes_value'] as $attr) {
                        if (!empty($attr->attr) && $attr->value != '') {
                            $attributes_value .= $attr->attr . ': ' . $attr->value . "\n";
                        }
                    }
                }
				
				$dispatcher->triggerEvent('onBeforeSetOrderItemProductAttributes', array(&$value, &$attributes_value));
                $order_item->product_attributes = $attributes_value;
                
                if (isset($value['free_attributes_value'])) {
					if($value['prod_id_of_additional_val']){
						$prod_id_of_additional_val = $value['prod_id_of_additional_val'];
						$db = JFactory::getDBO();
						$db->setQuery('SELECT `is_use_additional_free_attrs` FROM `#__jshopping_products` WHERE `product_id` =' . $value['prod_id_of_additional_val']);
						if(!$db->loadResult()){	
							$prod_id_of_additional_val = $value['product_id'];
						}
					}else{
						$prod_id_of_additional_val = $value['product_id'];
					}
					$value['free_attributes_value'] = excludeHiddenAttr($value['free_attributes_value'], $prod_id_of_additional_val);
					foreach ($value['free_attributes_value'] as $attr) {
                        $free_attributes_value .= $attr->attr . ': ' . $attr->value . "\n";
                    }
                }
                $order_item->product_freeattributes = $free_attributes_value;
                
                if (!empty($value['extra_fields'])) {
                    $order_item->extra_fields = json_encode($value['extra_fields']);
                }
    
                if ($value['facp_label_label'] && $value['facp_label_suffix']) {
                    $order_item->product_freeattributes .= $value['facp_label_label'] . ': ' . $value['facp_label_suffix'] . "\n";
                }
                
                $order_item->reorder = $value['reorder'];
                $order_item->reorder_num = $value['reorder_num'];
                $dispatcher->triggerEvent('onBeforeSaveOrderItem', [&$order_item, &$value]);
                
                $order_item->store();
    
                if (!empty($value['uploadData'])) {
                    $orderItemsNativeUploadsFilesModel->massInsert($order_id, $order_item->order_item_id, $value['uploadData']);
                }

                unset($order_item);
            }
        }

        return 1;
    }
}