<?php 

include __DIR__ . '/pdf_config.php';
include __DIR__ . '/tcpdf/tcpdf.php';

abstract class PdfGenerator extends TCPDF
{
    const CONTENT_MARGIN_LEFT = 20;
    const CONTENT_MARGIN_RIGHT = 20;
    const FOOTER_BOTTOM_OFFSET = 10;
    const HEADER_TOP_OFFSET = 10;

    public $imgHeaderName = 'header.jpg';
    public $imgFooterName = 'footer.jpg';
    public $urlToHeaderImg = '';
    public $pathToHeaderImg = '';
    public $pathToFooterImg = '';
    public $urlToFooterImg = '';
    
    public $dataForCssStyles = [];
    
    public function init($order)
    {
        $this->jshopConfig = JSFactory::getConfig();
        $this->prepareOrderData($order);

        if (!empty($order->shop_id)) {
            $multiShopHeaderImgName = "header-{$order->shop_id}.jpg";
            $multiShopFooterImgName = "footer-{$order->shop_id}.jpg";
            $pathToMultiShopHeaderImg = "{$this->jshopConfig->path}images/{$multiShopHeaderImgName}";
            $pathToMultiShopFooterImg = "{$this->jshopConfig->path}images/{$multiShopFooterImgName}";

            $this->imgHeaderName = file_exists($pathToMultiShopHeaderImg) ? $multiShopHeaderImgName : null;
            $this->imgFooterName = file_exists($pathToMultiShopFooterImg) ? $multiShopFooterImgName : null;
        }

        $this->imgHeaderName = $this->imgHeaderName ?: $this->jshopConfig->pdf_header_file_name;
        $this->imgFooterName = $this->imgFooterName ?: $this->jshopConfig->pdf_footer_file_name;

        $this->urlToHeaderImg = $this->jshopConfig->live_path . 'images/' . $this->imgHeaderName;
        $this->pathToHeaderImg = $this->jshopConfig->path . 'images/' . $this->imgHeaderName;
        $this->urlToFooterImg = $this->jshopConfig->live_path . 'images/' . $this->imgFooterName;
        $this->pathToFooterImg = $this->jshopConfig->path . 'images/' . $this->imgFooterName;

        $this->preparePdfSettings();
        $this->setOrderPaymentAndShippingInfo($order);
    }

    /**
     * Set pdf`s header
     */
    public function Header()
    {
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onBeforeAddTitleHead', [$this->vendorInfo, &$currentObj]);
        \JFactory::getApplication()->triggerEvent('onBeforeAddTitleHeadTo' . static::PDF_TYPE, [$this->vendorInfo, &$currentObj]);

        $dataToTransfer = [
            'pdf' => $this,
            'isHeaderImgExists' => $this->isHeaderImgExists(),
            'urlToImg' => $this->urlToHeaderImg,
            'imgWidth' => $this->jshopConfig->pdf_header_width,
            'imgHeight' => $this->jshopConfig->pdf_header_height,
            'imgUnit' => $this->getUnit(),
            'vendorInfo' => $this->vendorInfo
        ];
        
        $html = getContentOfFile($this->getPathTo('header'), $dataToTransfer);

        if ($this->isHeaderImgExists()) {
            $this->SetMargins(0, 0, -1, true);
        }

        $this->writeHTML($html);
        $this->SetMargins(static::CONTENT_MARGIN_LEFT, $this->getY(), static::CONTENT_MARGIN_RIGHT, true);

        return $html;
    }

    /**
     * Set pdf`s footer
     */
    public function Footer() 
    {
        if ($this->isFooterImgExists()) {
            $savedPageBreakTrigger = $this->getPageBreakTrigger();
            $this->setPageBreakTrigger(0);
            $this->Image($this->pathToFooterImg, 0, $this->getFooterStartYPoint(), $this->jshopConfig->pdf_footer_width, $this->jshopConfig->pdf_footer_height);
            $this->setPageBreakTrigger($savedPageBreakTrigger['bMargin']);
        }
    }

    public function writeHTML($html, $ln = false, $fill = false, $reseth = false, $cell = false, $align = '') 
    {
        $html = $this->deleteSpaceBetweenTags($html);
        $html = $this->includeCssStylesToHtml($html, static::PDF_TYPE);

        return parent::writeHTML($html, $ln, $fill, $reseth, $cell, $align);
    }

    public function writeHTMLCell($w, $h, $x, $y, $html = '', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '', $autopadding = true)
    {
        $html = $this->deleteSpaceBetweenTags($html);
        $html = $this->includeCssStylesToHtml($html, static::PDF_TYPE);

        return parent::writeHTMLCell($w, $h, $x, $y, $html, $border, $ln, $fill, $reseth, $align, $autopadding);
    }

    protected function deleteSpaceBetweenTags($html)
    {
        if (!empty($html)) {
            $html = preg_replace('~<!--.+-->~', '', $html);
            $html = preg_replace('~>\s+<~', '><', $html);
            $html = preg_replace('~>\s+~', '>', $html);
            $html = preg_replace('~\s+<~', '<', $html);
        }

        return $html;
    }

    protected function includeCssStylesToHtml($html, $type)
    {
        if (!empty($type)) {
            $stylesContent = getContentOfFile($this->getPathTo('styles'), $this->dataForCssStyles);
            $html = $stylesContent . $html;
        }

        return $html;
    }

    protected function preparePdfSettings()
    {
        $this->SetAutoPageBreak(true, 40);
        $this->deleteTagsStyles();
        $this->SetMargins(static::CONTENT_MARGIN_LEFT, $this->jshopConfig->pdf_header_height, static::CONTENT_MARGIN_RIGHT, true);

        $this->dataForCssStyles['fontData'] = $this->getDefinedFont();

        $bottomMargin = $this->getPageHeight() - $this->getFooterStartYPoint();
        if ($this->isHeaderImgExists()) {
            $bottomMargin += static::FOOTER_BOTTOM_OFFSET;
        }

        $this->setPageBreakTrigger($bottomMargin);
    }

    protected function deleteTagsStyles()
    {
        $clearParamsData = [
            ['h' => 0, 'n' => 0], 
            ['h' => 0, 'n' => 0]
        ];

        $tagsToDelMargins = [
            'p' => $clearParamsData,
            'div' => $clearParamsData,
            'img' => $clearParamsData,
            'span' => $clearParamsData,
            'i' => $clearParamsData,
            'table' => $clearParamsData,
            'tr' => $clearParamsData,
            'td' => $clearParamsData,
        ];

        $this->setHtmlVSpace($tagsToDelMargins);
    }
    
    public function getPathTo(string $toFile): string
    {
        $fileName = "{$toFile}.php";
        $userFileName = "user_{$toFile}.php";

        $pathToUserFile = templateOverride('pdf/markups/' . static::PDF_TYPE, $userFileName);
        $pathToFile = templateOverride('pdf/markups/' . static::PDF_TYPE, $fileName);

        if (file_exists($pathToUserFile)) {
            return $pathToUserFile;
        }

        return $pathToFile;
    }

    public function setPageBreakTrigger($margin)
    {
        $this->PageBreakTrigger = $this->getPageHeight() - $margin;
        $this->bMargin = $margin;
    }

    public function getPageBreakTrigger()
    {
        return [
            'pageBreakTrigger' => $this->PageBreakTrigger,
            'bMargin' => $this->bMargin
        ];
    }

    public function getPageHeight($pagenum = '')
    {
        return $this->h;
    }

    public function getUnit()
    {
        return $this->pdfunit;
    }

    public function getFooterStartYPoint()
    {
        $result = $this->getPageHeight();

        if (!empty($this->jshopConfig->pdf_footer_height)) {
            $result -= $this->jshopConfig->pdf_footer_height;
        }

        return $result;
    }

    public function isHeaderImgExists(): bool
    {
        return (!empty($this->imgHeaderName) && file_exists($this->pathToHeaderImg));
    }

    public function isFooterImgExists(): bool
    {
        return (!empty($this->imgFooterName) && file_exists($this->pathToFooterImg));
    }

    protected function getDefinedFont()
    {
        // Latin + jap
        $result = [
            'font' => 'mplusrounded1clight',
            'fontI' => 'mplusrounded1clight',
            'fontB' => 'mplusrounded1cb',
        ];

        /* Freesans
            $result = [
                'name' => 'freesans',
                'i' => 'freesansi',
                'b' => 'freesansb',
            ];
        */

        return $result;
    }

    public function prepareProductAttrs($orderProduct, $wrapTag = 'p')
    {
        $attrs = !empty(trim($orderProduct->product_attributes)) ? sprintAtributeInOrder($orderProduct->product_attributes, 'pdf') . "\n" : '';
        $attrs .= !empty(trim($orderProduct->product_freeattributes)) ? sprintFreeAtributeInOrder($orderProduct->product_freeattributes, 'pdf') . "\n" : '';
        $attrs .= !empty(trim($orderProduct->extra_fields)) ? separatePdfExtraFieldsWithUseCharactParams(json_decode($orderProduct->extra_fields)): '';
        if ($orderProduct->product_id) {
            $attrs .= sprintEditorFiledsInOrder($orderProduct, 'pdf');
        }
        $attrs .= (isset($orderProduct->_ext_attribute) && !empty(trim($orderProduct->_ext_attribute))) ? $orderProduct->_ext_attribute . "\n" : '';

        if (!empty($attrs) && !empty($wrapTag)) {
            $attrs = array_reduce(explode("\n", $attrs), function ($acc, $attr) use($wrapTag) {
                if (!empty($attr)) {
                    $acc[] = "<{$wrapTag}>{$attr}</{$wrapTag}>";
                }
        
                return $acc;
            });

            $attrs = implode('', $attrs);
        }

        return $attrs;
    }

    protected function prepareOrderData($order)
    {
        $countriesFront = JSFactory::getModel('CountriesFront');
        $language =& JFactory::getLanguage();
        $language->load('addon_offer_and_order' , JPATH_ROOT, $language->getTag(), true);

        if (empty($order->order_tax_list) && !empty($order->order_tax_ext) ) {
            $order->order_tax_list = unserialize($order->order_tax_ext);
        }
        
        $order->order_date = date('d.m.Y', strtotime($order->order_date));
		$order->invoice_date = !$order->pdf_file ? date('d.m.Y', strtotime(getJsDate())) : date('d.m.Y', strtotime($order->invoice_date));
        
        if (!empty($order->country) && is_numeric($order->country)) {
            $countryData = $countriesFront->getById((int)$order->country);
            $order->country_id = $order->country;
            $order->country = $countryData->name ?: '';
        }

        if (!empty($order->d_country) && is_numeric($order->d_country)) {
            $countryData = $countriesFront->getById((int)$order->d_country);
            $order->d_country_id = $order->d_country;
            $order->d_country = $countryData->name ?: '';
        }
    }

    /**
     * @deprecated Don`t use!!!!!!!
     */
    public function addNewPage()
    {
        $this->addPage();
        $this->addTitleHead();
    }

    /**
     * @deprecated Don`t use!!!!!!!
     */
    public function addTitleHead()
    {
		$jshopConfig = JSFactory::getConfig();
        $vendorinfo = $this->_vendorinfo ?: $this->vendorInfo;
        $pdfcolors = [[0,0,0], [200,200,200], [155,155,155]];
        $imgHeaderName = $this->img_header ?: $this->imgHeaderName;
        $imgFooterName = $this->img_footer ?: $this->imgFooterName;

        $this->Image($jshopConfig->path.'images/'.$imgHeaderName,1,1,$jshopConfig->pdf_header_width,$jshopConfig->pdf_header_height);
        $this->Image($jshopConfig->path.'images/'.$imgFooterName,1,265,$jshopConfig->pdf_footer_width,$jshopConfig->pdf_footer_height);
        $this->SetFont('freesans','',8);
        $this->SetXY(115,12);
        $this->SetTextColor($pdfcolors[2][0], $pdfcolors[2][1], $pdfcolors[2][2]);
        $_vendor_info = [
            $vendorinfo->adress,
            $vendorinfo->zip . ' ' . $vendorinfo->city
        ];
        if ($vendorinfo->phone) $_vendor_info[] = JText::_('COM_SMARTSHOP_CONTACT_PHONE').": ".$vendorinfo->phone;
        if ($vendorinfo->fax) $_vendor_info[] = JText::_('COM_SMARTSHOP_CONTACT_FAX') . ": ".$vendorinfo->fax;
        if ($vendorinfo->email) $_vendor_info[] = JText::_('COM_SMARTSHOP_EMAIL').": ".$vendorinfo->email;
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onBeforeAddTitleHead', [&$vendorinfo, &$currentObj, &$_vendor_info, &$currentObj]);
        $str_vendor_info = implode("\n", $_vendor_info);
        $this->MultiCell(80, 3, $str_vendor_info, 0, 'R');
        $this->SetTextColor($pdfcolors[0][0], $pdfcolors[0][1], $pdfcolors[0][2]);
	}

    protected function setOrderPaymentAndShippingInfo(&$order)
    {
        $lang = JSFactory::getLang();
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $shippingMethod = JSFactory::getTable('shippingMethod', 'jshop');

        $pm_method->load($order->payment_method_id);
        $name = $lang->get('name');
        $description = $lang->get('description');
        $shippingMethod->load($order->shipping_method_id);

        $order->shipping_information = $shippingMethod->$name;
        $order->payment_name = $pm_method->$name;
        $order->payment_information = $order->payment_params;
        $order->payment_description = ($pm_method->show_descr_in_email) ? $pm_method->$description : '';
        $order->shipping_information = JSFactory::getModel('OrdersFront')->getOrderShippingsMethodsNames($order);
    }
}
