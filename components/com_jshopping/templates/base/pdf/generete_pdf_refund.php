<?php

defined('_JEXEC') or die('Restricted access');

include_once JPATH_SITE . '/components/com_jshopping/lib/pdfgenerator.php';

class JrefundPDF extends PdfGenerator
{  
    const PDF_TYPE = 'refund';
    
    public function generate($order, $refund, $pdfOrderName) 
    {
        loadLangsFiles();
        JPluginHelper::importPlugin('jshoppingorder');
        $dispatcher = \JFactory::getApplication();
        $pdfOrderName = $refund->refund_number . '_' . md5(uniqid(rand(0,100))) . '.pdf';

        $dispatcher->triggerEvent('onBeforeCreatePdfOrder', [&$order, $this, &$pdfOrderName]);

        $jshopConfig = JSFactory::getConfig();
        $this->init($order);
        $this->vendorInfo = $order->getVendorInfo();

        if (!empty($order->user_id) && $order->user_id >= 1) {
            $user = JSFactory::getTable('userShop', 'jshop');
            $user->load($order->user_id);
        }

        $this->addPage();

        $userTitle = (is_numeric($order->title) && isset($jshopConfig->user_field_title[$order->title])) ? JText::_($jshopConfig->user_field_title[$order->title]): $order->title;
        $this->generateSubheader($order, $refund, $this->vendorInfo, [
            'title' => $userTitle,
            'jshopConfig' => $jshopConfig,
            'user' => $user,
        ]);

        $this->generateOrderTable($order, $refund, $this->vendorInfo, [
            'jshopConfig' => $jshopConfig,
        ]);

        $this->generateOrderInfo($order, $this->vendorInfo, [
            'jshopConfig' => $jshopConfig,
        ]);

        $this->generateAdditionalInfo($order, $this->vendorInfo, [
            'jshopConfig' => $jshopConfig,
        ]);
       // print_r($pdfOrderName);die;
        $dispatcher->triggerEvent('onBeforeCreatePdfOrderEnd', [&$order, $this, &$pdfOrderName]);
		if (!file_exists($jshopConfig->pdf_orders_path . '/refunds/'. $order->order_id .'/')) {
			mkdir($jshopConfig->pdf_orders_path . '/refunds/'. $order->order_id .'/', 0777, true);
		}
        $this->Output($jshopConfig->pdf_orders_path . '/refunds/'. $order->order_id .'/'. $pdfOrderName, 'F');
        
        return $pdfOrderName;
    }

    public function generateSubheader($order, $refund, $vendorinfo, $additionalData = [])
    {
        $jshopConfig = JSFactory::getConfig();
        if($order->user_id > 0){
			$userShop = JSFactory::getTable('userShop', 'jshop');
            $userShop->load($order->user_id);
		}else{		
			$userShop = JSFactory::getUserShop();	
		}
	    $implodeInfo = '';
        if (!empty($vendorinfo->company_name) || !empty($vendorinfo->adress) || !empty($vendorinfo->zip)) {
            $implodeInfo = implode(', ', [
                $vendorinfo->company_name,
                $vendorinfo->adress,
                $vendorinfo->zip
            ]);
        }		
		
        $dataToTransfer = [
            'pdf' => $this,
            'vendorInfo' => $vendorinfo,
            'order' => $order,
            'implodeInfo' => $implodeInfo,
            'additionalData' => $additionalData,
            'user' => $userShop,
            'refund' => $refund
        ];

        $html = getContentOfFile($this->getPathTo('subheader'), $dataToTransfer);
        $this->writeHTML($html);
    }
    
    public function generateOrderTable($order, $refund, $vendorinfo, $additionalData = [])
    {
        $isHideSubtotal = (bool)(($this->jshopConfig->hide_tax || empty($refund->refund_tax_list)) && $refund->refund_discount == 0 && $refund->refund_payment == 0 && $this->jshopConfig->without_shipping);
        $isShowPercentTax = ((count($refund->refund_tax_list) > 1 || !empty($this->jshopConfig->show_tax_in_product)) && !$this->jshopConfig->hide_tax) ? 1 : 0;
//print_r($order->products);die;
        $dataToTransfer = [
            'pdf' => $this,
            'vendorInfo' => $vendorinfo,
            'order' => $order,
            'refund' => $refund,
            'additionalData' => $additionalData,
            'isHideSubtotal' => $isHideSubtotal,
            'isShowPercentTax' => $isShowPercentTax
        ];

		JPluginHelper::importPlugin('jshoppingorder');
		$dispatcher = \JFactory::getApplication();        
		$dispatcher->triggerEvent('onBeforeGeneratePdf_order_table', [&$order, $this, &$dataToTransfer]);
        $html = getContentOfFile($this->getPathTo('order_table'), $dataToTransfer);
        $this->writeHTML($html);
    }

    public function generateOrderInfo($order, $vendorinfo, $additionalData = [])
    {
        $deliverytimes = JSFactory::getAllDeliveryTime();
        $deliveryTime = $deliverytimes[$order->delivery_times_id] ?: $order->delivery_time;

        $isShowDeliveryTime = $this->jshopConfig->show_delivery_time_checkout && ($order->delivery_times_id || $order->delivery_time);
        $isShowDeliveryDate = $this->jshopConfig->show_delivery_date && !datenull($order->delivery_date);
        $isShowWeightOfProducts = ($order->weight==0 && $this->jshopConfig->hide_weight_in_cart_weight0) ? 0 : $this->jshopConfig->weight_in_invoice;
        $isShowPaymentInfo = (!$this->jshopConfig->without_payment && $this->jshopConfig->payment_in_invoice);
        $paymentDescription = trim(trim($order->payment_information) . '<br>' . $order->payment_description);
        $isShowShippingInfo = (!$this->jshopConfig->without_shipping && $this->jshopConfig->shipping_in_invoice);
       
        $dataToTransfer = [
            'pdf' => $this,
            'vendorInfo' => $vendorinfo,
            'order' => $order,
            'additionalData' => $additionalData,
            'deliveryTime' => $deliveryTime,
            'isShowDeliveryTime' => $isShowDeliveryTime,
            'isShowDeliveryDate' => $isShowDeliveryDate,
            'isShowWeightOfProducts' => $isShowWeightOfProducts,
            'isShowPaymentInfo' => $isShowPaymentInfo,
            'paymentDescription' => $paymentDescription,
            'isShowShippingInfo' => $isShowShippingInfo
        ];
		JPluginHelper::importPlugin('jshoppingorder');
		$dispatcher = \JFactory::getApplication();        
		$dispatcher->triggerEvent('onBeforeGenerateOrderInfogetContentOfFile', [&$order, $this, &$dataToTransfer]);
		
        $html = getContentOfFile($this->getPathTo('order_info'), $dataToTransfer);
        $this->writeHTML($html);
    }

    public function generateAdditionalInfo($order, $vendorinfo, $additionalData = [])
    {
        $isShowBankInfo = 1;
        $orderDescription = '';
        $isShowEuB2BTagMsg = false;
        $shopConfig = $additionalData['jshopConfig'];
        $isBankSectionNotEmpty = (!empty($vendorinfo->benef_bank_info) || !empty($vendorinfo->benef_bic) || !empty($vendorinfo->benef_conto) || !empty($vendorinfo->benef_payee) || !empty($vendorinfo->benef_iban) || !empty($vendorinfo->benef_swift));
        $isIntermSectionNotEmpty = (!empty($vendorinfo->interm_name) || !empty($vendorinfo->interm_swift));

        if (!empty($shopConfig->is_show_eu_b2b_tax_msg_in_bill)) {
            $euCountriesId = ($shopConfig->eu_countries_to_show_b2b_msg) ? explode(',', $shopConfig->eu_countries_to_show_b2b_msg): [];

            if (!empty($euCountriesId)) {
                $isAppliesToBillingAddress = ($shopConfig->b2b_applies_to_options['0']['id'] == $shopConfig->eu_countries_selected_applies_to);
                $prefix = $isAppliesToBillingAddress ? '': 'd_';
                $isFillTaxId = !empty($order->{$prefix . 'tax_number'});

                if ($isFillTaxId) {
                    $countryId = (!empty($order->{$prefix . 'country'}) && is_numeric($order->{$prefix . 'country'})) ? $order->{$prefix . 'country'}: $order->{$prefix . 'country_id'};;

                    if (in_array($countryId, $euCountriesId) && !empty($order->{$prefix . 'tax_number'})) {
                        $isShowEuB2BTagMsg = true;
                    }
                }
            }
        }

        $returnPolicyText = '';
        if ($this->jshopConfig->show_return_policy_text_in_pdf) {

            $list = $order->getReturnPolicy();
            $listtext = [];

            if (!empty($list)) {
                foreach($list as $v) {
                    $listtext[] = $v->text;
                }
            }
            
            $returnPolicyText = strip_tags(implode("\n\n", $listtext)) ?: '';
        }

        if (!empty($order->payment_method_id)) {
            $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
            $pm_method->load($order->payment_method_id);
            $isShowBankInfo = $pm_method->show_bank_in_order;
            $orderDescription = $pm_method->order_description;
        }
        
        $dataToTransfer = [
            'pdf' => $this,
            'vendorInfo' => $vendorinfo,
            'order' => $order,
            'additionalData' => $additionalData,
            'isShowBankInfo' => $isShowBankInfo,
            'orderDescription' => $orderDescription,
            'isBankSectionNotEmpty' => $isBankSectionNotEmpty,
            'isIntermSectionNotEmpty' => $isIntermSectionNotEmpty,
            'returnPolicyText' => $returnPolicyText,
            'isShowEuB2BTagMsg' => $isShowEuB2BTagMsg
        ];
		JPluginHelper::importPlugin('jshoppingorder');
		$dispatcher = \JFactory::getApplication();        
		$dispatcher->triggerEvent('onBeforeGenerateAdditionalInfogetContentOfFile', [&$order, $this, &$dataToTransfer]);
		
        $html = getContentOfFile($this->getPathTo('additional_info'), $dataToTransfer);
        $this->writeHTML($html);
    }
}