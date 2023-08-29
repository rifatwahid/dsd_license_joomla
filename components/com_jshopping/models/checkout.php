<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

include_once JPATH_SITE . '/components/com_jshopping/payments/payment.php';
include_once JPATH_SITE . '/components/com_jshopping/shippingform/shippingform.php';

class jshopCheckout extends jshopBase
{
    
    public function __construct()
    {
        JPluginHelper::importPlugin('jshoppingorder');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshopCheckout', [&$currentObj]);
    }
    
    public function sendOrderEmail($order_id, $manuallysend = 0)
    {
        loadLangsFiles();
        $manuallysend = 0;

        $mainframe = JFactory::getApplication();
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $order = JSFactory::getTable('order', 'jshop');
        $jshopConfig->user_field_title['0'] = '';
        $jshopConfig->user_field_client_type['0'] = '';
        $file_generete_pdf_order = $jshopConfig->file_generete_pdf_order;

        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields['address'];
        $count_filed_delivery = $jshopConfig->getEnableDeliveryFiledRegistration('address', 2);

        $order->load($order_id);

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
        $show_percent_tax = 0;        
        if (count($order->order_tax_list) > 1 || $jshopConfig->show_tax_in_product) {
            $show_percent_tax = 1;
        }        

        if ($jshopConfig->hide_tax) {
            $show_percent_tax = 0;
        }
        $hide_subtotal = 0;
        if (($jshopConfig->hide_tax || empty($order->order_tax_list)) && $order->order_discount == 0 && $jshopConfig->without_shipping && $order->order_payment == 0) {
            $hide_subtotal = 1;
        }
        
        if ($order->weight == 0 && $jshopConfig->hide_weight_in_cart_weight0) {
            $jshopConfig->show_weight_order = 0;
        }
        
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
		$paymentsysdata = $pm_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        
        $name = $lang->get('name');
        $description = $lang->get('description');
        $order->shipping_information = JSFactory::getModel('OrdersFront')->getOrderShippingsMethodsNames($order);
        $shippingForm = $shippingMethod->getShippingForm();
        if (!empty($shippingForm)) {
            $shippingForm->prepareParamsDispayMail($order, $shippingMethod);
        }
        $order->payment_name = $pm_method->$name;
        $order->payment_information = $order->payment_params;
		if (!empty($payment_system)) {
            $payment_system->prepareParamsDispayMail($order, $pm_method);
        }

        $order->payment_description = ($pm_method->show_descr_in_email) ? $pm_method->$description : '';
                
        $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL');
        if (($jshopConfig->show_tax_in_product || $jshopConfig->show_tax_product_in_cart) && !empty($order->order_tax_list)) {
            $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL_INKL_TAX');
        }
        
        $uri = JURI::getInstance();
        $liveurlhost = $uri->toString(['scheme','host', 'port']);
        $listVendors = [];
        if ($jshopConfig->admin_show_vendors) {
            $listVendors = $order->getVendors();
        }

        $vendors_send_message = $jshopConfig->vendor_order_message_type == 1;
        $vendor_send_order = $jshopConfig->vendor_order_message_type == 2;
        $vendor_send_order_admin = (($jshopConfig->vendor_order_message_type == 2 && $order->vendor_type == 0 && $order->vendor_id) || $jshopConfig->vendor_order_message_type == 3);
        if ($vendor_send_order_admin) {
            $vendor_send_order = 0;
        }

        $admin_send_order = ($jshopConfig->admin_not_send_email_order_vendor_order && $vendor_send_order_admin && !empty($listVendors)) ? 0 : 1;
        $order->shipping_information = JSFactory::getModel('OrdersFront')->getOrderShippingsMethodsNames($order);		
        
		$order->products_pdf=JSFactory::getModel("pdfhubfront")->getOrderPDFs($order);
		$isOrderHasBeenPaid = in_array($order->order_status, $jshopConfig->payment_status_enable_download_sale_file);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSendEmailsOrder', [&$order, &$listVendors, &$file_generete_pdf_order, &$admin_send_order]);
        
        $data = [
            'client' => 1,
            'show_customer_info' => 1,
            'show_weight_order' => 1,
            'show_total_info' => 1,
            'show_payment_shipping_info' => 1,
            'config_fields' => $config_fields,
            'count_filed_delivery' => $count_filed_delivery,
            'config' => $jshopConfig,
            'order' => $order,
            'products' => $order->products,
            'show_percent_tax' => $show_percent_tax,
            'hide_subtotal' => $hide_subtotal,
            'noimage' => $jshopConfig->noimage,
            'text_total' => $text_total,
            'liveurlhost' => $liveurlhost,
			'isOrderHasBeenPaid'=> $isOrderHasBeenPaid
        ];
		$dispatcher->triggerEvent('onBeforerenderOrderMailMsgTmpl_msgTextForClient', [&$order, &$listVendors, &$file_generete_pdf_order, &$admin_send_order,&$data]);
        $modelOfOrdersFront = JSFactory::getModel('OrdersFront');
        $msgTextForClient = $modelOfOrdersFront->renderOrderMailMsgTmpl($data);

        $data['order_email_descr'] = null;
        $data['order_email_descr_end'] = null;
		$dispatcher->triggerEvent('onBeforerenderOrderMailMsgTmpl_msgTextForAdmin', [&$order, &$listVendors, &$file_generete_pdf_order, &$admin_send_order,&$data]);
        $msgTextForAdmin = $modelOfOrdersFront->renderOrderMailMsgTmpl($data);
        
        //vendors messages or order
        if ($vendors_send_message || $vendor_send_order) {
            foreach ($listVendors as $k => $datavendor) {

                if ($vendors_send_message || $vendor_send_order) {
                    $show_weight_order = 0;
                    $show_total_info = 0;
                }

                if ($vendors_send_message) {
                    $show_customer_info = 0;
                    $show_payment_shipping_info = 0;
                }

                if ($vendor_send_order) {
                    $show_customer_info = 1;
                    $show_payment_shipping_info = 1;
                }
				
				$vendor_data = [
                    'client' => 0,
                    'show_customer_info' => $show_customer_info ?? null,
                    'show_weight_order' => $show_weight_order ?? null,
                    'show_total_info' => $show_total_info ?? null,
                    'show_payment_shipping_info' => $show_payment_shipping_info ?? null,
                    'config_fields' => $config_fields,
                    'count_filed_delivery' => $count_filed_delivery,
                    'order_email_descr' => null,
                    'order_email_descr_end' => null,
                    'config' => $jshopConfig,
                    'order' => $order,
                    'products' => $order->getVendorItems($datavendor->id),
                    'show_percent_tax' => $show_percent_tax,
                    'hide_subtotal' => $hide_subtotal,
                    'noimage' => $jshopConfig->noimage,
                    'text_total' => $text_total,
                    'liveurlhost' => $liveurlhost,
                    'show_customer_info' => $vendor_send_order
                ];
				$dispatcher->triggerEvent('onBeforerenderOrderMailMsgTmpl_msgForVendor', [&$order, &$listVendors, &$file_generete_pdf_order, &$admin_send_order,&$vendor_data]);
                $msgForVendor = $modelOfOrdersFront->renderOrderMailMsgTmpl($vendor_data, 'onBeforeCreateTemplateOrderPartMail');
                $listVendors[$k]->message = $msgForVendor;
            }
        }        

        $pdfsend = true;        
        $mailfrom = $mainframe->getCfg('mailfrom');
        $fromname = $mainframe->getCfg('fromname');
        $fio = $config_fields['f_name']['display'] ? $order->f_name . ' ':'';
        $fio .= $config_fields['l_name']['display'] ? $order->l_name :'';
        $subject = JText::sprintf('COM_SMARTSHOP_NEW_ORDER', $order->order_number, $fio);

        $modelOfOrdersStatusFront = JSFactory::getModel('OrdersStatusFront');
        $modelOfOrdersStatusFront->sendEmailWhenChangeOrderStatus($order, $subject, $msgTextForClient, $msgTextForAdmin);

        $isAccessSendOrderEmail = true;
        $dispatcher->triggerEvent('onBeforeSendEmailsOrderAndAfterGeneratePdf', [&$order, &$listVendors, &$file_generete_pdf_order, &$admin_send_order, &$isAccessSendOrderEmail]);

        if (!$isAccessSendOrderEmail) {
            return;
        }
        
        //send mail vendors
		$app = JFactory::getApplication();
		if (!$app->get('mailonline', 1)){
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SENDING_MAIL'),'error');
			return;
		}
        if ($vendors_send_message || $vendor_send_order) {
            foreach($listVendors as $k => $vendor) {
                $mailer = JFactory::getMailer();
                $mailer->setSender([$mailfrom, $fromname]);
                $mailer->addRecipient($vendor->email);
                $mailer->setSubject( JText::sprintf('COM_SMARTSHOP_NEW_ORDER_V', $order->order_number, ''));
                
				$dataForTemplate = array('emailSubject'=>JText::sprintf('COM_SMARTSHOP_NEW_ORDER_V', $order->order_number, ''), 'emailBod'=>$vendor->message);
				$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
               
                $mailer->setBody($bodyEmailText);			
                $mailer->isHTML(true);
                $dispatcher->triggerEvent('onBeforeSendOrderEmailVendor', [&$mailer, &$order, &$manuallysend, &$pdfsend, &$vendor, &$vendors_send_message, &$vendor_send_order]);
                $mailer->Send();
            }
        }

        //vendor send order
        if ($vendor_send_order_admin) {
            foreach($listVendors as $k => $vendor) {
                $mailer = JFactory::getMailer();
                $mailer->setSender(array($mailfrom, $fromname));
                $mailer->addRecipient($vendor->email);
                $fio = $config_fields['f_name']['display'] ? $order->f_name . ' ':'';
                $fio .= $config_fields['l_name']['display'] ? $order->l_name :'';

                $mailer->setSubject( JText::sprintf('COM_SMARTSHOP_NEW_ORDER', $order->order_number, $fio));
                $mailer->setBody($msgTextForAdmin);

                if ($jshopConfig->order_send_pdf_admin) {
                    $mailer->addAttachment($jshopConfig->pdf_orders_path . '/' . $order->pdf_file);
                }

                $mailer->isHTML(true);
                $dispatcher->triggerEvent('onBeforeSendOrderEmailVendorOrder', [&$mailer, &$order, &$manuallysend, &$pdfsend, &$vendor, &$vendors_send_message, &$vendor_send_order]);
                $mailer->Send();
            }
        }

        $dispatcher->triggerEvent('onAfterSendEmailsOrder', [&$order]);
    }
    
    public function changeStatusOrder($order_id, $status, $sendmessage = 1)
    {
        $mainframe = JFactory::getApplication();
        
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $restext = '';

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeChangeOrderStatus', [&$order_id, &$status, &$sendmessage, &$restext]);
            
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $order->order_status = $status;
        $order->order_m_date = getJsDate();
        $order->store();
        
        $vendorinfo = $order->getVendorInfo();

        $order_status = JSFactory::getTable('orderStatus', 'jshop');
        $order_status->load($status);
        
        $product_stock_removed = (!in_array($status, $jshopConfig->payment_status_return_product_in_stock));
        if ($jshopConfig->order_stock_removed_only_paid_status) {
            $product_stock_removed = (in_array($status, $jshopConfig->payment_status_enable_download_sale_file));
        }
        
        if ($order->order_created && !$product_stock_removed && $order->product_stock_removed == 1) {
            $order->changeProductQTYinStock('+');            
        }
        
        if ($order->order_created && $product_stock_removed && $order->product_stock_removed == 0) {
            $order->changeProductQTYinStock('-');            
        }
        
        $order_history = JSFactory::getTable('orderHistory', 'jshop');
        $order_history->order_id = $order->order_id;
        $order_history->order_status_id = $status;
        $order_history->status_date_added = getJsDate();
        $order_history->customer_notify = 1;
        $order_history->comments = $restext;
        $order_history->store();
        
        $name = $lang->get('name');
        
        $uri = JURI::getInstance();
        $liveurlhost = $uri->toString(['scheme','host', 'port']);
        $order_details_url = $liveurlhost . SEFLink('index.php?option=com_jshopping&controller=user&task=order&order_id=' . $order_id, 1);

        if ($order->user_id == -1) {
            $order_details_url = '';
        }
        
		$email_texts = unserialize($order_status->email_text);
		$email_text = '';
		if($email_texts['text_'.$lang->lang]){$email_text = $email_texts['text_'.$lang->lang];}
        $message = $this->getMessageChangeStatusOrder($order, $order_status->$name, $vendorinfo, $order_details_url, '', $email_texts);
        $listVendors = ($jshopConfig->admin_show_vendors) ? $order->getVendors() : [];
        
        $vendors_send_message = ($jshopConfig->vendor_order_message_type == 1 || ($order->vendor_type == 1 && $jshopConfig->vendor_order_message_type == 2));
        $vendor_send_order = ($jshopConfig->vendor_order_message_type==2 && $order->vendor_type == 0 && $order->vendor_id);
        if ($jshopConfig->vendor_order_message_type == 3) {
            $vendor_send_order = 1;
        }

        $admin_send_order = 1;
        if ($jshopConfig->admin_not_send_email_order_vendor_order && $vendor_send_order && !empty($listVendors)) {
            $admin_send_order = 0;
        }
         
        $mailfrom = $mainframe->getCfg('mailfrom');
        $fromname = $mainframe->getCfg('fromname');
        
        if ($sendmessage) {
            require_once JPATH_ROOT . '/components/com_jshopping/lib/shMailer.php';
            
            $shMailer = new shMailer();
            //message client
            $subject = JText::sprintf('COM_SMARTSHOP_ORDER_STATUS_CHANGE_SUBJECT', $order->order_number) ?? JText::_('COM_SMARTSHOP_ORDER_STATUS_CHANGE_SUBJECT');
            $shMailer->sendMsg([$mailfrom, $fromname], $order->email, $subject, $message, [
                'name' => 'onBeforeSendMailChangeOrderStatusClient',
                'data' => [$order_id, $status, $sendmessage, $order]
            ]);
            
            //message admin
            if ($admin_send_order) {
                $subject = JText::_('COM_SMARTSHOP_ORDER_STATUS_CHANGE_TITLE');
                $shMailer->sendMsg([$mailfrom, $fromname], explode(',', $jshopConfig->contact_email), $subject, $message, [
                    'name' => 'onBeforeSendMailChangeOrderStatusAdmin',
                    'data' => [$order_id, $status, $sendmessage, $order]
                ]);
            }
            
            //message vendors
            if ($vendors_send_message || $vendor_send_order) {
                foreach($listVendors as $datavendor) {
                    $subject = JText::_('COM_SMARTSHOP_ORDER_STATUS_CHANGE_TITLE');
                    $shMailer->sendMsg([$mailfrom, $fromname], $datavendor->email, $subject, $message, [
                        'name' => 'onBeforeSendMailChangeOrderStatusVendor',
                        'data' => [$order_id, $status, $sendmessage, $order]
                    ]);
                }
            }
        }

        $dispatcher->triggerEvent('onAfterChangeOrderStatus', [&$order_id, &$status, &$sendmessage]);
        return 1;
    }
    
    public function getMessageChangeStatusOrder($order, $newstatus, $vendorinfo, $order_details_url, $comments = '', $message = '')
    {	
        $modelOfOrdersFront = JSFactory::getModel('OrdersFront');
        if(is_array($message)) $message = reset($message);
		$messege = $modelOfOrdersFront->replaceShortCodes($message, $order, $comments, $newstatus, $vendorinfo, $order_details_url);
		if (is_string($messege)) {
			$messege=trim($messege);
			$st=strlen($messege);
		} else $st=count($message);
		if(!empty($messege) && ($st > 0)){
			return $messege;
		}else{
			return $modelOfOrdersFront->renderStatusOrderMailMsgTmpl([
				'order' => $order,
				'order_status' => $newstatus,
				'vendorinfo' => $vendorinfo,
				'order_detail' => $order_details_url,
				'comment' => $comments
			]);
		}
    }
    
    public function cancelPayOrder($order_id)
    {
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $pm_method->load($order->payment_method_id);
        $pmconfigs = $pm_method->getConfigs();
        $status = $pmconfigs['transaction_cancel_status'];

        if (!$status) {
            $status = $pmconfigs['transaction_failed_status'];
        }

        $sendmessage = ($order->order_created) ? $sendmessage = 1 : 0;

        $this->changeStatusOrder($order_id, $status, $sendmessage);
        \JFactory::getApplication()->triggerEvent('onAfterCancelPayOrderJshopCheckout', [&$order_id, $status, $sendmessage]);
    }
    
    public function setMaxStep($step)
    {
        $session = JFactory::getSession();
        $jhop_max_step = $session->get('jhop_max_step');

        if (!isset($jhop_max_step)) {
            $session->set('jhop_max_step', 2);
        }

        $jhop_max_step = $session->get('jhop_max_step');
        $session->set('jhop_max_step', $step);
        \JFactory::getApplication()->triggerEvent('onAfterSetMaxStepJshopCheckout', [&$step]);
    }
    
    public function checkStep($step, $to = 'cart')
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();        
        $sefLinkToCartView = SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1, 1);
        $session = JFactory::getSession();

        if (empty($session->get('jhop_max_step'))) {
            $session->set('jhop_max_step', 2);
        }

        if ($step >= 11) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_STEP'),'error');
            $mainframe->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout', 1, 1, $jshopConfig->use_ssl));
            die;
        }
        
        if ($step < 10) {
            if (!$jshopConfig->shop_user_guest) {
                checkUserLogin();
            }

            if(!$to) $to = 'cart';
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load($to);
            $priceOfCartProducts = $cart->getPriceProducts();
    
            if ($cart->getCountProduct() == 0) {
                $mainframe->redirect($sefLinkToCartView);
                die;
            }
    
            if ($jshopConfig->min_price_order && ($priceOfCartProducts < ($jshopConfig->min_price_order * $jshopConfig->currency_value) )) {
                \Joomla\CMS\Factory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_ERROR_MIN_SUM_ORDER', formatprice($jshopConfig->min_price_order * $jshopConfig->currency_value)), 'notice');
                $mainframe->redirect($sefLinkToCartView);
                die;
            }
            
            if ($jshopConfig->max_price_order && ($priceOfCartProducts > ($jshopConfig->max_price_order * $jshopConfig->currency_value) )) {
                \Joomla\CMS\Factory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_ERROR_MAX_SUM_ORDER', formatprice($jshopConfig->max_price_order * $jshopConfig->currency_value)), 'notice');
                $mainframe->redirect($sefLinkToCartView);
                die;
            }
        }
    }
    
    public function deleteSession()
    {
        $currentObj = $this;
        $session = JFactory::getSession();        
        $session->set('check_params', null);
        $session->set('cart', null);
        $session->set('jhop_max_step', null);        
        $session->set('jshop_price_shipping_tax_percent', null);
        $session->set('jshop_price_shipping', null);
        $session->set('jshop_price_shipping_tax', null);
        $session->set('pm_params', null);
        $session->set('payment_method_id', null);
        $session->set('jshop_payment_price', null);
        $session->set('shipping_method_id', null);
        $session->set('sh_pr_method_id', null);
        $session->set('jshop_price_shipping_tax_percent', null);                
        $session->set('jshop_end_order_id', null);
        $session->set('jshop_send_end_form', null);
        $session->set('show_pay_without_reg', 0);
        $session->set('checkcoupon', 0);
        \JFactory::getApplication()->triggerEvent('onAfterDeleteDataOrder', [&$currentObj]);
    }
    
    public function getActivePaymMethod($active_payment, $payment_methods) 
    {
        if (!empty($payment_methods)) {
            foreach ($payment_methods as $pm) {
                if($pm->payment_id == $active_payment) {
                    return $pm;
                }
            }
        }

    }

    public function uploadAfterPurchaseMessage($order_id){

        require_once JPATH_ROOT . '/components/com_jshopping/lib/shMailer.php';

        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();

        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
		$app = JFactory::getApplication();
		if (!$app->get('mailonline', 1)){
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SENDING_MAIL'),'error');
			return;
		}
        $mailfrom = $mainframe->getCfg('mailfrom');
        $fromname = $mainframe->getCfg('fromname');

        $mailer = JFactory::getMailer();
        $mailer->setSender([$mailfrom, $fromname]);
        $mailer->addRecipient(explode(',', $jshopConfig->contact_email));
        $mailer->setSubject( JText::sprintf('COM_SMARTSHOP_ORDER_UPLOAD_AFTER_PURCHASE_SUBJECT', $order->order_number, ''));
        $message = JText::sprintf('COM_SMARTSHOP_ORDER_UPLOAD_AFTER_PURCHASE_MESSAGE', $order->order_number);

        $dataForTemplate = array('emailSubject'=>JText::sprintf('COM_SMARTSHOP_ORDER_UPLOAD_AFTER_PURCHASE_SUBJECT', $order->order_number, ''), 'emailBod'=>$message);
        $bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');

        $mailer->setBody($bodyEmailText);
        $mailer->isHTML(true);
        $mailer->Send();

        return true;
    }

}