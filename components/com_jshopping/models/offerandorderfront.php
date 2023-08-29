<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelOfferAndOrderFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_offer_and_order';

    public function getGeneratedNextOrderId(): int
    {
        $countOfOrders = $this->select(['MAX(orders.order_id) AS max_order_id'], [], 'AS orders', false)->max_order_id ?: 0;
        return $countOfOrders + 1;
    }

    public function getVendorsByOrderId(int $orderId)
    {
        $db = \JFactory::getDBO();
        $query = "SELECT distinct V.* FROM `#__jshopping_offer_and_order_item` as OI
                  left join `#__jshopping_vendors` as V on V.id = OI.vendor_id
                  WHERE order_id = '" . $db->escape($orderId) . "'";
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function getOffersAndOrdersByUserId($userId) 
    {
        $db = \JFactory::getDBO();
		$lang = JSFactory::getLang();
		$text_search=$db->escape(JFactory::getApplication()->input->getVar('text_search'));
		$text_search_price=str_replace(',','.',$text_search);
		if (trim($text_search)!=""){
			$where[] = " and (
			orders.`f_name` like '%" . $text_search . "%'	or 
			orders.`l_name` like '%" . $text_search . "%' or 
			orders.`email` like '%" . $text_search . "%' or 
			orders.`firma_name` like '%" . $text_search . "%' or 
			orders.`d_f_name` like '%" . $text_search . "%' or 
			orders.`d_l_name` like '%" . $text_search . "%' or 
			orders.`d_firma_name` like '%" . $text_search . "%' or
			orders.order_add_info like '%".$text_search."%' or 			
			orders.`street` like '%".$text_search."%' or 
			orders.`home` like '%".$text_search."%' or 
			orders.`city` like '%".$text_search."%' or 
			orders.`zip` like '%".$text_search."%' or 
			orders.`phone` like '%".$text_search."%' or 
			orders.`mobil_phone` like '%".$text_search."%' or 
			orders.`fax` like '%".$text_search."%' or 
			orders.`d_street` like '%".$text_search."%' or 
			orders.`d_city` like '%".$text_search."%' or 
			orders.`d_state` like '%".$text_search."%' or 
			orders.`d_zip` like '%".$text_search."%' or  
			orders.`projectname` like '%" . $text_search . "%'  or
			orders.`order_number` like '%" . $text_search . "%' or
			orders.`order_total` like '%" . $text_search . "%' or
			orders.`order_total` like '%" . $text_search_price . "%' or
			orders.`currency_code` like '%" . $text_search . "%' or
			orders.`order_date` like '%" . $text_search . "%' or
			orders.`order_m_date` like '%" . $text_search . "%' or
			orders.`client_type_name` like '%" . $text_search . "%' or
			orders.`firma_code` like '%" . $text_search . "%' or
			orders.`tax_number` like '%" . $text_search . "%' or
			orders.`state` like '%" . $text_search . "%' or
			orders.`ext_field_1` like '%" . $text_search . "%' or
			orders.`ext_field_2` like '%" . $text_search . "%' or
			orders.`ext_field_3` like '%" . $text_search . "%' or
			orders.`d_email` like '%" . $text_search . "%' or
			orders.`d_home` like '%" . $text_search . "%' or
			orders.`d_apartment` like '%" . $text_search . "%' or
			orders.`d_mobil_phone` like '%" . $text_search . "%' or
			orders.`d_fax` like '%" . $text_search . "%' or
			orders.`d_ext_field_1` like '%" . $text_search . "%' or
			orders.`d_ext_field_2` like '%" . $text_search . "%' or
			orders.`d_ext_field_3` like '%" . $text_search . "%' or
			orders.`order_custom_info` like '%" . $text_search . "%' or
			orders.`display_price` like '%" . $text_search . "%' or
			orders.`delivery_time` like '%" . $text_search . "%' or
			order_item.`product_ean` like '%" . $text_search . "%' or
			order_item.`product_name` like '%" . $text_search . "%' or
			order_item.`product_item_price` like '%" . $text_search_price . "%' or
			order_item.`product_item_price` like '%" . $text_search . "%' or
			order_item.`product_attributes` like '%" . $text_search . "%' or
			order_item.`product_freeattributes` like '%" . $text_search . "%' or
			order_item.`files` like '%" . $text_search . "%' or
			order_item.`extra_fields` like '%" . $text_search . "%' or
			order_item.`manufacturer` like '%" . $text_search . "%' or
			P.`".$lang->get('name')."` like '%".$text_search."%' or 
			P.`".$lang->get('description')."` like '%".$text_search."%' or 			
            orders.`d_phone` like '%".$text_search."%')";
			$where = implode(' ', $where); 	
		}
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeRequestOfferAndOrder', [&$where]);
		
        $query = "SELECT orders.*, COUNT(order_item.order_id) AS count_products
                  FROM `#__jshopping_offer_and_order` AS orders                  
                  INNER JOIN `#__jshopping_offer_and_order_item` AS order_item ON order_item.order_id = orders.order_id
				  LEFT JOIN `#__jshopping_products` as P on P.product_id = order_item.product_id
                  WHERE orders.user_id = '" . $db->escape($userId) . "' {$where}
                  GROUP BY order_item.order_id 
                  ORDER BY orders.order_number DESC";
        $db->setQuery($query);

        $list = $db->loadObjectList();
        if(!empty($list)){
            foreach($list as $k=>$v){
                $list[$k]->order_link = SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=order&id=' . $v->order_id, 1);
            }
        }
        return $list;
    }

    public function setPdfFileByOrderId(string $pdfFile, int $orderId): bool
    {
        $db = \JFactory::getDBO();
        $query = "UPDATE `#__jshopping_offer_and_order` SET pdf_file = '" . $db->escape($pdfFile) . "' WHERE order_id = '" . $db->escape($orderId) . "'";
        $db->setQuery($query);

        return $db->execute();
    }
}