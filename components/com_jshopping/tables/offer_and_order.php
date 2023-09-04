<?php

include_once JPATH_SITE . '/components/com_jshopping/lib/shop_item_menu.php';

class jshopOffer_and_order extends JTableAvto 
{

    public function __construct(&$_db) 
    {
        $language =& JFactory::getLanguage();
        $language->load('addon_offer_and_order' , JPATH_ROOT, $language->getTag(), true);
        parent::__construct('#__jshopping_offer_and_order', 'order_id', $_db);
    }

	public function bind($src, $ignore = Array(), $update = 0)
	{
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment") && !$update){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC') && !$update){					
					$src[$key]=0;
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}
	
    public function getAllItems() 
    {
        if (!isset($this->items)) {
            $this->items = JSFactory::getModel('OfferAndOrderItemsFront')->getAllByOrderId($this->order_id, true);
        }

        return $this->items ?: [];
    }

    public function setInvoiceDate() 
    {
        if (datenull($this->invoice_date)) {
            $this->invoice_date = getJsDate();
            JSFactory::getModel('OrdersFront')->setInvoiceDateByOrderId($this->order_id, $this->invoice_date);
        }
    }
    
    public function getWeightItems() 
    {
        $items = $this->getAllItems();
        $weight = 0;
        foreach ($items as $row) {
            $weight += $row->product_quantity * $row->weight;
        }

        return $weight;
    }

    public function copyDeliveryData() 
    {
        $this->d_title = $this->title;
        $this->d_f_name = $this->f_name;
        $this->d_l_name = $this->l_name;
        $this->d_firma_name = $this->firma_name;
        $this->d_home = $this->home;
        $this->d_apartment = $this->apartment;
        $this->d_street = $this->street;
        $this->d_zip = $this->zip;
        $this->d_city = $this->city;
        $this->d_state = $this->state;
        $this->d_email = $this->email;
        $this->d_country = $this->country;
        $this->d_phone = $this->phone;
        $this->d_mobil_phone = $this->mobil_phone;
        $this->d_fax = $this->fax;
        $this->d_ext_field_1 = $this->ext_field_1;
        $this->d_ext_field_2 = $this->ext_field_2;
        $this->d_ext_field_3 = $this->ext_field_3;
    }

    public function getOrdersForUser(int $userId) 
    {
        return JSFactory::getModel('OfferAndOrderFront')->getOffersAndOrdersByUserId($userId);
    }

    /**
     * Next order id    
     */
    public function getLastOrderId() 
    {
        return JSFactory::getModel('OfferAndOrderFront')->getGeneratedNextOrderId();
    }

    public function formatOrderNumber($num) 
    {
        return outputDigit($num, 8);
    }

    /**
     * save name pdf from order
     */
    public function insertPDF() 
    {
        return JSFactory::getModel('OrdersFront')->setPdfFileByOrderId($this->pdf_file, $this->order_id);
    }

    public function getFilesStatDownloads() 
    {
        return empty($this->file_stat_downloads) ? [] : unserialize($this->file_stat_downloads);
    }

    public function setFilesStatDownloads($array) 
    {
        $this->file_stat_downloads = serialize($array);
    }

    public function getTaxExt() 
    {
        return empty($this->order_tax_ext) ? [] : unserialize($this->order_tax_ext);
    }

    public function setTaxExt($array) 
    {
        $this->order_tax_ext = serialize($array);
    }

    public function getPaymentParamsData() 
    {
        return empty($this->payment_params_data) ? [] : unserialize($this->payment_params_data);
    }

    public function setPaymentParamsData($array) 
    {
        $this->payment_params_data = serialize($array);
    }

    public function getLang() 
    {
        $lang = $this->lang;

        if (empty($lang)) {
            $lang = 'en-GB';
        }
            
        return $lang;
    }

    public function saveOrderItem($items) 
    {
        return JSFactory::getModel('OfferAndOrderItemsFront')->saveOrderItems($items, $this->order_id);
    }

    /**
     * get list vendors for order
     */
    public function getVendors() 
    {
        return JSFactory::getModel('OfferAndOrderFront')->getVendorsByOrderId($this->order_id);
    }

    public function getVendorItems($vendor_id) 
    {
        $items = $this->getAllItems();
        foreach ($items as $k => $v) {
            if ($v->vendor_id != $vendor_id) {
                unset($items[$k]);
            }
        }

        return $items;
    }

    public function getVendorInfo() 
    {
        $jshopConfig = JSFactory::getConfig();
        $vendor_id = $this->vendor_id;
                    
        if ($jshopConfig->vendor_order_message_type < 2 || $vendor_id == -1) {
            $vendor_id = 0;
        }

        $vendor = JTable::getInstance('vendor', 'jshop');
        $vendor->loadFull($vendor_id);
        $vendor->country_id = $vendor->country;
        $lang = JSFactory::getLang($this->getLang());
        $country = JTable::getInstance('country', 'jshop');
        $country->load($vendor->country_id);
        $field_country_name = $lang->get('name');
        $vendor->country = $country->$field_country_name;

        return $vendor;
    }

    public function getUrlMyOfferAndOrder() 
    {
        return JSFactory::getModel('OfferAndOrderItemsFront')->getUrlToMyOfferAndOrder();
    }

    public function getShippingMethodsCountry($country_id, $payment_id, $shippint_id, $publish = 1) 
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $query_where = ($publish) ? "AND sh_pr_method.published = '1'" : '';

        if ($payment_id && $jshopConfig->step_4_3 == 0) {
            $query_where.= " AND (sh_pr_method.payments='' OR FIND_IN_SET(" . $payment_id . ", sh_pr_method.payments) ) ";
        }

        $query = "SELECT *, sh_pr_method.`" . $lang->get("name") . "` as name, sh_pr_method.`" . $lang->get("description") . "` as description 
				FROM `#__jshopping_shipping_method_price` AS sh_pr_method 
                INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
                INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
                WHERE countries.country_id = '" . $db->escape($country_id) . "' AND  sh_pr_method.sh_pr_method_id = " . intval($shippint_id) . " $query_where
                ORDER BY sh_pr_method.ordering";
        extract(js_add_trigger(get_defined_vars(), 'query'));
        $db->setQuery($query);
        
        return $db->loadObject();
    }

}