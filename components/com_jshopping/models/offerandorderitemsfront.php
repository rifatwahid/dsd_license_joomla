<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelOfferAndOrderItemsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_offer_and_order_item';

    public function getAllByOrderId(int $orderId, $downloadAndDeliveryTime = false): array
    {
        $items = $this->select(['*'], ['order_id = \'' . $orderId . '\'']);

        if (!empty($items) && $downloadAndDeliveryTime) {
            $jshopConfig = JSFactory::getConfig();

            if ($jshopConfig->display_delivery_time_for_product_in_order_mail) {
                $deliverytimes = JSFactory::getAllDeliveryTime();

                foreach ($items as $k => $v) {
                    $items[$k]->delivery_time = $deliverytimes[$v->delivery_times_id];
                }
            }
        }
        
        return $items;
    }

    public function deleteByOrderId(int $orderId): bool
    {
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_offer_and_order_item` WHERE order_id = '" . $db->escape($orderId) . "'";
        $db->setQuery($query);

        return $db->execute();
    }

    public function getUrlToMyOfferAndOrder(): string
    {
        $url = 'index.php?option=com_jshopping&controller=offer_and_order&task=myoffer_and_order';
        $itemid = $this->getItemIdMenuOfferAndOrder();

        if ($itemid != 0) {
            $url = 'index.php?Itemid=' . $itemid;
        }

        return $url;
    }

    protected function getItemIdMenuOfferAndOrder(): int
    {
        $id = 0;
        $shim = shopItemMenu::getInstance();
        $list_js_url = $shim->getList();

        foreach ($list_js_url as $v) {
            if (((isset($v->data['view']) && $v->data['view'] == 'offer_and_order') || (isset($v->data['controller']) && $v->data['controller'] == 'offer_and_order')) && count($v->data) == 1) {
                $id = $v->id;
                break;
            }
        }

        return $id;
    }

    public function saveOrderItems(?array $items, int $orderId) 
    {
        JPluginHelper::importPlugin('jshoppingorder');
        $dispatcher = \JFactory::getApplication();
		$items = (array)$items;
        if (!empty($items)) {
            $this->deleteByOrderId($orderId);

            foreach ($items as $value) {
                $order_item = JTable::getInstance('offer_and_orderItem', 'jshop');
                $order_item->order_id = $orderId;
                $order_item->product_id = $value['product_id'];
                $order_item->product_ean = $value['ean'];
                $order_item->product_name = $value['product_name'];
                $order_item->product_quantity = $value['quantity'];
                $order_item->product_item_price = $value['price'];
				$order_item->product_item_one_time_cost = $value['one_time_cost'];
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
                $order_item->params = $value['params'];
				$order_item->product_id_for_order = $value['product_id_for_order'];

                if (isset($value['attributes_value'])) {
                    foreach ($value['attributes_value'] as $attr) {
                        if (!empty($attr->attr) && $attr->value != '') {
                            $attributes_value .= $attr->attr . ': ' . $attr->value . "\n";
                        }
                    }
                }
				
				if($value['product_attributes']){
					$attributes_value .= $value['product_attributes'];
				}
				
				if($value['product_freeattributes']){
					$free_attributes_value .= $value['product_freeattributes'];
				}

				$dispatcher->triggerEvent('onBeforeSetOrderItemProductAttributes', array(&$value, &$attributes_value));
                $order_item->product_attributes = $attributes_value;

                if (isset($value['free_attributes_value'])) {
                    foreach ($value['free_attributes_value'] as $attr) {
                        $free_attributes_value .= $attr->attr . ': ' . $attr->value . "\n";
                    }
                }
                $order_item->product_freeattributes = $free_attributes_value;

                if (!empty($value['extra_fields'])) {
                    $order_item->extra_fields = json_encode($value['extra_fields']);
                }
                $order_item->uploaded_files = serialize($value['uploadData'] ?: []);
                
                $dispatcher->triggerEvent('onBeforeSaveOfferAndOrderItem', [&$order_item, &$value]);

                $order_item->store();
            }
        }

        return 1;
    }
}