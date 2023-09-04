<?php

defined('_JEXEC') or die('Restricted access');

include_once JPATH_SITE . '/components/com_jshopping/lib/pdfgenerator.php';

class JorderDeliveryNotePDF extends PdfGenerator
{
    const PDF_TYPE = 'delivery_note';

	public function generate($order, $wishfulPdfFileName)
	{
		if ($order->order_id <= 0) {
            return false;
        }

        loadLangsFiles();

        if (!empty($order->user_id) && $order->user_id >= 1) {
            $user = JSFactory::getTable('userShop', 'jshop');
            $user->load($order->user_id);
        }
        
        $jshopConfig = JSFactory::getConfig();	
        $this->init($order);
		$this->vendorInfo = $order->getVendorInfo();
        $this->shop_id = $order->shop_id;

        $this->addPage();

        $userTitle = (is_numeric($order->d_title) && isset($jshopConfig->user_field_title[$order->d_title])) ? JText::_($jshopConfig->user_field_title[$order->d_title]): $order->d_title;
        $this->generateSubheader($order, $this->vendorInfo, [
            'title' => $userTitle,
            'jshopConfig' => $jshopConfig,
            'user' => $user,
        ]);

        $this->generateOrderTable($order, $this->vendorInfo, [
            'jshopConfig' => $jshopConfig,
        ]);

        $this->generateAdditionalInfo($order, $this->vendorInfo, [
            'jshopConfig' => $jshopConfig,
        ]);
	    
        $pathToFolderWithPdfs = $jshopConfig->pdf_orders_path . '/delivery/';

        if (!is_dir($pathToFolderWithPdfs)) {
            mkdir($pathToFolderWithPdfs, 0777);
        }

        $this->Output($pathToFolderWithPdfs . $wishfulPdfFileName, 'F');

        return $wishfulPdfFileName;
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
		$dispatcher->triggerEvent('onBeforeGeneratePdf_note_table', [&$order, $this, &$dataToTransfer]);

        $html = getContentOfFile($this->getPathTo('note_table'), $dataToTransfer);
        $this->writeHTML($html);
    }

    public function generateAdditionalInfo($order, $vendorinfo, $additionalData = [])
    {
        $dataToTransfer = [
            'pdf' => $this,
            'vendorInfo' => $vendorinfo,
            'order' => $order,
            'additionalData' => $additionalData,
        ];

        $html = getContentOfFile($this->getPathTo('additional_info'), $dataToTransfer);
        $this->writeHTML($html);
    }
}