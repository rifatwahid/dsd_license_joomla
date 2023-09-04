<?php

defined('_JEXEC') or die('Restricted access');

include_once JPATH_SITE . '/components/com_jshopping/lib/pdfgenerator.php';

class JofferAndOrderPDF extends PdfGenerator
{
    const PDF_TYPE = 'offer_and_order';

    public function generate($order)
    {
        loadLangsFiles();
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $user = JSFactory::getTable('userShop', 'jshop');
        $this->init($order);
        $this->vendorInfo = $order->getVendorInfo();

        if (!empty($order->user_id) && $order->user_id >= 1) {
            $user->load($order->user_id);
        }

        $this->addPage();

        $dispatcher->triggerEvent('onBeforeCreatePdfOfferAndOrder', [&$order, $this->vendorInfo]);

        $userTitle = (is_numeric($order->title) && isset($jshopConfig->user_field_title[$order->title])) ? JText::_($jshopConfig->user_field_title[$order->title]): $order->title;
        $this->generateSubheader($order, $this->vendorInfo, [
            'title' => $userTitle,
            'jshopConfig' => $jshopConfig,
            'user' => $user,
        ]);

        $this->generateOrderTable($order, $this->vendorInfo, [
            'jshopConfig' => $jshopConfig,
        ]);

        $this->generateOrderInfo($order, $this->vendorInfo, [
            'jshopConfig' => $jshopConfig,
        ]);

        $currentObj = $this;
        $nameOfPdf = 'offer_and_order_' . $order->order_id . '_' . substr(md5(uniqid(rand(0,100))), 0, 10) . '.pdf';
        $dispatcher->triggerEvent('onBeforeCreatePdfOfferAndOrderEnd', [&$order, &$currentObj, &$nameOfPdf]);
        $this->Output($jshopConfig->pdf_orders_path . '/' . $nameOfPdf ,'F');
        return $nameOfPdf;
    }

    public function generateSubheader($order, $vendorinfo, $additionalData = [])
    {
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
            'additionalData' => $additionalData
        ];

        $html = getContentOfFile($this->getPathTo('subheader'), $dataToTransfer);
        $this->writeHTML($html);
    }
    
    public function generateOrderTable($order, $vendorinfo, $additionalData = [])
    {
        $isHideSubtotal = (bool)(($this->jshopConfig->hide_tax || empty($order->order_tax_list)) && $order->order_discount == 0 && $order->order_payment == 0 && $this->jshopConfig->without_shipping);
        $isShowPercentTax = ((count($order->order_tax_list) > 1 || !empty($this->jshopConfig->show_tax_in_product)) && !$this->jshopConfig->hide_tax) ? 1 : 0;

        $dataToTransfer = [
            'pdf' => $this,
            'vendorInfo' => $vendorinfo,
            'order' => $order,
            'additionalData' => $additionalData,
            'isHideSubtotal' => $isHideSubtotal,
            'isShowPercentTax' => $isShowPercentTax
        ];
		JPluginHelper::importPlugin('jshoppingorder');
		$dispatcher = \JFactory::getApplication();        
		$dispatcher->triggerEvent('onBeforeGeneratePdf_offer_table', [&$order, $this, &$dataToTransfer]);

        $html = getContentOfFile($this->getPathTo('offer_table'), $dataToTransfer);
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

        $html = getContentOfFile($this->getPathTo('offer_info'), $dataToTransfer);
        $this->writeHTML($html);
    }
}