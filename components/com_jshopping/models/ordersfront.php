<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelOrdersFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_orders';

    public function getAll(): array
    {
        $db = \JFactory::getDBO();
        $sql = "SELECT * FROM {$db->qn(self::TABLE_NAME)}";
        $db->setQuery($sql);

        return $db->loadObjectList() ?: [];
    }

    public function setOrderAddressIdById(int $primaryKey, int $orderAddressId): bool
    {
        $db = \JFactory::getDBO();
        $sql = "UPDATE {$db->qn(self::TABLE_NAME)} SET `order_address_id` = {$db->escape($orderAddressId)} WHERE `order_id` = {$primaryKey}";
        $db->setQuery($sql);

        return $db->execute();
    }

    public function getOrderShippingsMethodsNames($order): string
    {
        $shipping_params_data = unserialize($order->shipping_params_data);
		$shipping_ids = isset($shipping_params_data['shipping_id']) ? explode(',', $shipping_params_data['shipping_id']) : [];
		$lang = JSFactory::getLang();
		$name = $lang->get('name');
		$shippingMethod = JSFactory::getTable('shippingMethodPrice', 'jshop');
		$shippingMethod->load($order->shipping_method_id);
		if(isset($order->shippings) && $order->shippings && count(explode('_', $order->shippings)) > 1) return JText::_('COM_SMARTSHOP_COMPLEX_SHIPPING');
		if(!empty($shipping_ids) && !empty($shipping_ids['0'])) {
            $shipping_information = '';
            
			foreach($shipping_ids as $id) {
				$shippingMethod = JSFactory::getTable('shippingMethodPrice', 'jshop');
                $shippingMethod->load($id);
                
				if (strlen($shipping_information) > 0) {
                    $shipping_information .= ', ';
                }

                $shipping_information .= $shippingMethod->$name;
                unset($shippingMethod);
			}
			
			return $shipping_information;
        }
        
        $shippingMethod = JSFactory::getTable('shippingMethodPrice', 'jshop');
        $shippingMethod->load($order->shipping_method_id);

        return (string)$shippingMethod->$name;
   }

   public function renderOrderMailMsgTmpl(array $dataToInsert, string $triggerName = 'onBeforeCreateTemplateOrderMail'): string
   {
        include_once(JPATH_COMPONENT_SITE . '/views/qcheckout/view.html.php');

        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $view = new JshoppingViewQcheckout([
            "template_path"=>viewOverride('emails',"orderemail.php")
        ]);
		
        $view->setLayout('orderemail');

        if (!empty($dataToInsert)) {
            foreach($dataToInsert as $keyName => $value) {
                $view->set($keyName, $value);
            }
        }

        if (!empty($triggerName)) {
            $dispatcher->triggerEvent($triggerName, [&$view]);
        }
        
		$dataForTemplate = array('emailSubject'=>'', 'emailBod'=>$view->loadTemplate());
		return renderTemplateEmail('default', $dataForTemplate, 'emails');
   }

   public function renderStatusOrderMailMsgTmpl(array $dataToInsert, string $triggerName = 'onBeforeCreateMailOrderStatusView'): string
   {
        include_once(JPATH_COMPONENT_SITE . '/views/qcheckout/view.html.php');
        
        $jshopConfig = JSFactory::getConfig();
        $view_name = "emails";
        $view_config = array("template_path"=>viewOverride($view_name,"statusorder.php"));

		$view = new JshoppingViewQcheckout($view_config);

        $view->setLayout('statusorder');
        
        if (!empty($dataToInsert)) {
            foreach($dataToInsert as $keyName => $value) {
                $view->set($keyName, $value);
            }
        }

        if (!empty($triggerName)) {
            \JFactory::getApplication()->triggerEvent($triggerName, [&$view]);
        }
        
        //return $view->loadTemplate();
		
		$dataForTemplate = array('emailSubject'=>'', 'emailBod'=>$view->loadTemplate());
		return renderTemplateEmail('default', $dataForTemplate, 'emails');
   }

   public function setInvoiceDateByOrderId(int $orderId, $invoiceDate = null): bool
    {
       /* $db = \JFactory::getDBO();

        if (!isset($invoiceDate)) {
            $invoiceDate = getJsDate();
        }

        $query = "UPDATE `#__jshopping_orders` SET invoice_date = '" . $db->escape($invoiceDate) . "' WHERE order_id = '" . $db->escape($orderId) . "'";
        $db->setQuery($query);
        
        return $db->execute();*/
    }

    public function getAllOrdersByUserId(int $isUser)
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang(); 
		$text_search=$db->escape(JFactory::getApplication()->input->getVar('text_search'));
		$text_search_price=str_replace(',','.',$text_search);
		$where = '';
		if (trim($text_search)!=""){
            $_where[] = "and (orders.`order_number` like '%{$text_search}%' or 
                OA.`f_name` like '%{$text_search}%' or 
                OA.`l_name` like '%{$text_search}%' or 
                OA.`email` like '%{$text_search}%' or 
                OA.`firma_name` like '%{$text_search}%' or 
                OA.`firma_code` like '%{$text_search}%' or 
                OA.`tax_number` like '%{$text_search}%' or 
                OA.`street` like '%{$text_search}%' or 
                OA.`home` like '%{$text_search}%' or 
                OA.`apartment` like '%{$text_search}%' or 
                OA.`zip` like '%{$text_search}%' or 
                OA.`city` like '%{$text_search}%' or 
                OA.`state` like '%{$text_search}%' or 
                OA.`country` like '%{$text_search}%' or 
                OA.`phone` like '%{$text_search}%' or 
                OA.`mobil_phone` like '%{$text_search}%' or 
                OA.`fax` like '%{$text_search}%' or 
                OA.`ext_field_1` like '%{$text_search}%' or 
                OA.`ext_field_2` like '%{$text_search}%' or 
                OA.`ext_field_3` like '%{$text_search}%' or 
                OA.`birthday` like '%{$text_search}%' or 
                OA.`street_nr` like '%{$text_search}%' or 
                OA.`d_f_name` like '%{$text_search}%' or 
                OA.`d_l_name` like '%{$text_search}%' or 
                OA.`d_firma_name` like '%{$text_search}%' or 
                OA.`d_email` like '%{$text_search}%' or 
                OA.`d_street` like '%{$text_search}%' or 
                OA.`d_home` like '%{$text_search}%' or 
                OA.`d_apartment` like '%{$text_search}%' or 
                OA.`d_zip` like '%{$text_search}%' or 
                OA.`d_city` like '%{$text_search}%' or 
                OA.`d_state` like '%{$text_search}%' or 
                OA.`d_country` like '%{$text_search}%' or 
                OA.`d_phone` like '%{$text_search}%' or 
                OA.`d_firma_code` like '%{$text_search}%' or 
                OA.`d_tax_number` like '%{$text_search}%' or 
                OA.`d_mobil_phone` like '%{$text_search}%' or 
                OA.`d_fax` like '%{$text_search}%' or 
                OA.`d_ext_field_1` like '%{$text_search}%' or 
                OA.`d_ext_field_2` like '%{$text_search}%' or 
                OA.`d_ext_field_3` like '%{$text_search}%' or 
                OA.`d_m_name` like '%{$text_search}%' or 
                OA.`d_birthday` like '%{$text_search}%' or 
                OA.`d_street_nr` like '%{$text_search}%' or 
                order_item.`product_ean` like '%{$text_search}%' or 
                order_item.`product_name` like '%{$text_search}%' or 
                order_item.`product_attributes` like '%{$text_search}%' or 
                order_item.`product_freeattributes` like '%{$text_search}%' or 
                order_item.`files` like '%{$text_search}%' or 
                order_item.`weight` like '%{$text_search}%' or 
                order_item.`manufacturer` like '%{$text_search}%' or 
                orders.`order_date` like '%{$text_search}%' or 
                orders.`order_m_date` like '%{$text_search}%' or 
                orders.`order_total` like '%{$text_search}%' or 
                orders.`order_total` like '%{$text_search_price}%' or 
                orders.`order_subtotal` like '%{$text_search}%' or 
                orders.`order_subtotal` like '%{$text_search_price}%' or 
                orders.`order_add_info` like '%{$text_search}%' or 
                orders.`order_custom_info` like '%{$text_search}%' or 
                orders.`delivery_time` like '%{$text_search}%' or 
                orders.order_add_info like '%{$text_search}%')";
				$where = implode(' ', $_where); 
		}
		
        $query = "SELECT orders.*, order_status.`" . $lang->get('name') . "` as status_name, COUNT(order_item.order_id) AS count_products
                  FROM `#__jshopping_orders` AS orders
                  INNER JOIN `#__jshopping_order_status` AS order_status ON orders.order_status = order_status.status_id
                  INNER JOIN `#__jshopping_order_item` AS order_item ON order_item.order_id = orders.order_id
				  LEFT JOIN `#__jshopping_order_addresses` as OA on orders.order_address_id = OA.id
                  WHERE orders.user_id = '" . $db->escape($isUser) . "' and orders.order_created='1' {$where}
                  GROUP BY order_item.order_id 
                  ORDER BY orders.order_date DESC";
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function genGeneratedNextOrderId() 
    {
        $db = \JFactory::getDBO(); 
        $query = 'SELECT MAX(orders.order_id) AS max_order_id FROM `#__jshopping_orders` AS orders';
        $db->setQuery($query);

        return $db->loadResult() + 1;
    }

    public function setPdfFileByOrderId(string $pdfFile, int $orderId): bool
    {
        $db = \JFactory::getDBO();
		
		$query = "SELECT `pdf_file` FROM `#__jshopping_orders` WHERE order_id = '" . $db->escape($orderId) . "'";
		$db->setQuery($query);
        $file = $db->loadResult();
		
		if(!$file){
			if (!isset($invoiceDate)) {
				$invoiceDate = getJsDate();
			}
			$query = "UPDATE `#__jshopping_orders` SET invoice_date='" . $db->escape($invoiceDate) . "' WHERE order_id = '" . $db->escape($orderId) . "'";
			$db->setQuery($query);
			$db->execute();
		}
        $query = "UPDATE `#__jshopping_orders` SET pdf_file = '" . $db->escape($pdfFile) . "' WHERE order_id = '" . $db->escape($orderId) . "'";
        $db->setQuery($query);

        return $db->execute();
    }

    public function setInvoiceDate(int $orderId, $invoiceDate = null)
    {
        /*$db = \JFactory::getDBO();

        if (!isset($invoiceDate)) {
            $invoiceDate = getJsDate();
        }

        $query = "UPDATE `#__jshopping_orders` SET invoice_date='" . $db->escape($invoiceDate) . "' WHERE order_id = '" . $db->escape($orderId) . "'";
        $db->setQuery($query);

        return $db->execute();*/
    }

    public function countCouponsByCouponId(int $couponId)
    {
        $couponCount = $this->select(['COUNT(`coupon_id`) as count'], [
            '`coupon_id` = ' . $couponId
        ], '', false)->count;

        return $couponCount ?: 0;
    }
    
    public function replaceShortCodes(&$message, $order, $comments, $new_status, $vendorinfo, $order_details_url)
    {
		$jshopConfig = JSFactory::getConfig();
        if (!empty($message)) {
            $configFields = JSFactory::getConfig()->getListFieldsRegister()['address'];
            $orderDetailUrl = !empty($order_details_url) ? "<a href='{$order_details_url}'>{$order_details_url}</a>" : '';
            $replace = [
                '{title}',
                '{first_name}',
                '{middle_name}',
                '{last_name}',
                '{order_number}',
                '{order_status}',
                '{comment}',
                '{company}',
                '{address}',
                '{zip}',
                '{city}',
                '{country}',
                '{phone}',
                '{fax}',
                '{order_detail_url}',				
				'{firma_code}',
				'{client_type}',
				'{apartment}',
				'{home}',
				'{street_nr}',
				'{street}',
				'{state}',
				'{mobil_phone}',
				'{tax_number}',
				'{birthday}',
				'{ext_field_1}',
				'{ext_field_2}',
				'{ext_field_3}',
				'{delivery_title}',
				'{delivery_first_name}',
				'{delivery_middle_name}',
				'{delivery_last_name}',
				'{delivery_company}',				
				'{delivery_firma_code}',
				'{delivery_client_type}',
				'{delivery_address}',
				'{delivery_apartment}',
				'{delivery_home}',
				'{delivery_street_nr}',
				'{delivery_street}',
				'{delivery_zip}',
				'{delivery_city}',
				'{delivery_state}',
				'{delivery_country}',
				'{delivery_phone}',
				'{delivery_mobil_phone}',
				'{delivery_fax}',
				'{delivery_tax_number}',
				'{delivery_birthday}',
				'{delivery_ext_field_1}',
				'{delivery_ext_field_2}',
				'{delivery_ext_field_3}'
            ];
      
			$to = [
                $configFields['title']['display'] ? $order->title: '',
                $configFields['f_name']['display'] ? $order->f_name: '',
                $configFields['m_name']['display'] ? $order->m_name: '',
                $configFields['l_name']['display'] ? $order->l_name: '',
                $order->order_number,
                $new_status,
                $comments ? $comments : $order->order_add_info,
                $configFields['firma_name']['display'] ? $order->firma_name: '',
                ($configFields['state']['display'] ? $order->state :'') . ' ' . ($configFields['zip']['display'] ? $order->zip :'') . ' ' . ($configFields['street']['display'] ? $order->street :''),
                $configFields['zip']['display'] ? $order->zip: '',
                $configFields['city']['display'] ? $order->city: '',
                $configFields['country']['display'] ? $order->country: '',
                $configFields['phone']['display'] ? $order->phone: '',
                $configFields['fax']['display'] ? $order->fax: '',
                $orderDetailUrl,
				$configFields['firma_code']['display'] ? $order->firma_code: '',
				$configFields['client_type']['display'] ? JText::_($jshopConfig->user_field_client_type[$order->client_type]): '',
				$configFields['apartment']['display'] ? $order->apartment: '',
				$configFields['home']['display'] ? $order->home: '',
				$configFields['street_nr']['display'] ? $order->street_nr: '',
				$configFields['street']['display'] ? $order->street: '',
				$configFields['state']['display'] ? $order->state: '',
				$configFields['mobil_phone']['display'] ? $order->mobil_phone: '',
				$configFields['tax_number']['display'] ? $order->tax_number: '',
				$configFields['birthday']['display'] ? $order->birthday: '',
				$configFields['ext_field_1']['display'] ? $order->ext_field_1: '',
				$configFields['ext_field_2']['display'] ? $order->ext_field_2: '',
				$configFields['ext_field_3']['display'] ? $order->ext_field_3: '',
				
				$configFields['title']['display'] ? $order->d_title: '',
                $configFields['f_name']['display'] ? $order->d_f_name: '',
                $configFields['m_name']['display'] ? $order->d_m_name: '',
                $configFields['l_name']['display'] ? $order->d_l_name: '',               
				$configFields['firma_name']['display'] ? $order->d_firma_name: '',
				$configFields['firma_code']['display'] ? $order->d_firma_code: '',
				$configFields['client_type']['display'] ? JText::_($jshopConfig->user_field_client_type[$order->d_client_type]): '',
				($configFields['state']['display'] ? $order->d_state : '') . ' ' . ($configFields['zip']['display'] ? $order->d_zip : '') . ' ' . ($configFields['street']['display'] ? $order->d_street : ''),
				$configFields['apartment']['display'] ? $order->d_apartment: '',
				$configFields['home']['display'] ? $order->d_home: '',
				$configFields['street_nr']['display'] ? $order->d_street_nr: '',
				$configFields['street']['display'] ? $order->d_street: '',
				$configFields['zip']['display'] ? $order->d_zip: '',
				$configFields['city']['display'] ? $order->d_city: '',
				$configFields['state']['display'] ? $order->d_state: '',
				$configFields['country']['display'] ? $order->d_country: '',
				$configFields['phone']['display'] ? $order->d_phone: '',
				$configFields['mobil_phone']['display'] ? $order->d_mobil_phone: '',
				$configFields['fax']['display'] ? $order->d_fax: '',
				$configFields['tax_number']['display'] ? $order->d_tax_number: '',
				$configFields['birthday']['display'] ? $order->d_birthday: '',
				$configFields['ext_field_1']['display'] ? $order->d_ext_field_1: '',
				$configFields['ext_field_2']['display'] ? $order->d_ext_field_2: '',
				$configFields['ext_field_3']['display'] ? $order->d_ext_field_3: ''
            ];

			$message = str_replace($replace, $to, $message);
        }

		return $message;
    }
	
    public function prepareToPdf(&$order)
    {
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $jshopConfig->user_field_title['0'] = '';
        $jshopConfig->user_field_client_type['0'] = '';

        $status = JSFactory::getTable('orderStatus', 'jshop');
        $status->load($order->order_status);
        $name = $lang->get('name');
        $order->status = $status->$name;
        $order->order_date = strftime($jshopConfig->store_date_format, strtotime($order->order_date));
        $order->products = $order->getAllItems();
        $order->weight = $order->getWeightItems();
        if ($jshopConfig->show_delivery_time_checkout) {
            $deliverytimes = JSFactory::getAllDeliveryTime();

            $order->order_delivery_time = '';
            if (isset($deliverytimes[$order->delivery_times_id])) {
                $order->order_delivery_time = $deliverytimes[$order->delivery_times_id];
            }

            if (empty($order->order_delivery_time)) {
                $order->order_delivery_time = $order->delivery_time;
            }
        }

        $order->order_tax_list = $order->getTaxExt();
        $country = JSFactory::getTable('country', 'jshop');
        $country->load($order->country);
        $field_country_name = $lang->get('name');
        $order->country_id = $order->country;
        $order->country = $country->$field_country_name;        
        
        $d_country = JSFactory::getTable('country', 'jshop');
        $d_country->load($order->d_country);
        $field_country_name = $lang->get('name');
        $order->d_country_id = $order->d_country;
        $order->d_country = $d_country->$field_country_name;

        $order->delivery_date_f = '';
        if ($jshopConfig->show_delivery_date && !datenull($order->delivery_date)) {
            $order->delivery_date_f = formatdate($order->delivery_date);
        }
        
        $order->title = JText::_($jshopConfig->user_field_title[$order->title]);
        $order->d_title = JText::_($jshopConfig->user_field_title[$order->d_title]);
        $order->birthday = getDisplayDate($order->birthday, $jshopConfig->field_birthday_format);
        $order->d_birthday = getDisplayDate($order->d_birthday, $jshopConfig->field_birthday_format);
        $order->client_type_name = JText::_($jshopConfig->user_field_client_type[$order->client_type]);
        
        $shippingMethod = JSFactory::getTable('shippingMethod', 'jshop');
        $shippingMethod->load($order->shipping_method_id);
        
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $pm_method->load($order->payment_method_id);
        
        $name = $lang->get('name');
        $description = $lang->get('description');
        $order->shipping_information = $shippingMethod->$name;
        $order->payment_name = $pm_method->$name;
        $order->payment_information = $order->payment_params;
        $order->payment_description = ($pm_method->show_descr_in_email) ? $pm_method->$description : '';
        $order->shipping_information = JSFactory::getModel('OrdersFront')->getOrderShippingsMethodsNames($order);;	
    }
	
	public function setInvoiceNumber($order_id, $invoice_number){
		$db = \JFactory::getDBO();
        $query = 'update `#__jshopping_orders` set `invoice_number`='.$db->quote($invoice_number).' WHERE `order_id`='.$db->quote($order_id);
        $db->setQuery($query);
        return (bool)$db->execute();
	}
	
	public function setRefundNumber($refund_id, $refund_number){
		$db = \JFactory::getDBO();
		
        $query = 'update `#__jshopping_refunds` set `refund_number`='.$db->quote($refund_number).', `refund_date`="'.date('Y-m-d').'" WHERE `refund_id`='.$refund_id;
     
	    $db->setQuery($query);
        return (bool)$db->execute();
	}
	
	public function setRefundFile($refund_id, $file){
		$db = \JFactory::getDBO();
		
        $query = 'update `#__jshopping_refunds` set `pdf_file`='.$db->quote($file).' WHERE `refund_id`='.$refund_id;
     
	    $db->setQuery($query);
        return (bool)$db->execute();
	}
}