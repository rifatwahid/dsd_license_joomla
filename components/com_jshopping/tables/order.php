<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/extrascoupon/order_extrascoupon_mambot.php';

class jshopOrder extends JTableAvto 
{

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_orders', 'order_id', $_db);
        JPluginHelper::importPlugin('jshoppingcheckout');
        JPluginHelper::importPlugin('jshoppingorder');
    }
	
	public function bind($src, $ignore = Array())
	{
		if (isset($src['order_id']) AND $src['order_id']>0) $old_src=$this->getLatestValue($src['order_id']);		
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					if (isset($old_src[$key])) {$src[$key]=$old_src[$key];}else{$src[$key]="";} 
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					if (isset($old_src[$key])) {$src[$key]=$old_src[$key];}else{$src[$key]=0;}
				}
			}						
		}		
		return parent::bind($src, $ignore);
	}


	public function getLatestValue($id)
    {
		$db = \JFactory::getDBO();
        $dispatcher = \JFactory::getApplication();
        
        $query = "SELECT * FROM `#__jshopping_orders` WHERE order_id = '".$db->escape($id)."'";
        $db->setQuery($query);
        return get_object_vars($db->loadObject());
    }

    public function load($id = NULL, $reset = true)
    {
        $isParentLoaded = parent::load($id, $reset);

        if ($isParentLoaded && !empty($this->order_address_id)) {
            $this->loadDataFromOrderAddress();
        }
        return $isParentLoaded;
    }


    public function store($updateNulls = false)
    {
        $idOfOrderAddress = $this->createOrUpdateOrderAddress();
        
        if (!empty($idOfOrderAddress)) {
            $this->order_address_id = $idOfOrderAddress;
        }
        $isOrderStored = parent::store($updateNulls);
        
        return $isOrderStored;
    }

    /**
     * @return integer Id of created order address
     */
    protected function createOrUpdateOrderAddress(): int
    {
        $isStored = false;
        $tableOfOrderAddress = JSFactory::getTable('OrderAddress');

        if (!empty($this->order_address_id)) {
            $tableOfOrderAddress->load($this->order_address_id);
        }

        if (isset($this->email) || isset($this->u_name)) {
            $tableOfOrderAddress->bind((array)$this);
            $isStored = $tableOfOrderAddress->store();
        }

        return ($isStored) ? $tableOfOrderAddress->id : 0;
    }


    public function getAllItems()
    {
        if (!isset($this->items)) {
            $this->items = JSFactory::getModel('OrdersItemsFront')->getAllByOrderId($this->order_id);
        }

        return $this->items ?: [];
    }
    
    public function getWeightItems()
    {
        $currentObj = $this;
        $items = $this->getAllItems();
        $weight = 0;

        foreach($items as $row) {
            $weight += $row->product_quantity * $row->weight;
        }

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onGetWeightOrderProducts', [&$currentObj, &$weight]);

        return $weight;
    }

    public function getHistory() 
    {
        return JSFactory::getModel('OrdersHistoryFront')->getHistoryByOrderId($this->order_id);
    }

    public function getStatusTime()
    {
        return JSFactory::getModel('OrdersHistoryFront')->getMaxStatusDateAddedByOrderId($this->order_id);
    }

    public function getStatus() 
    {
        return JSFactory::getModel('OrdersStatusFront')->getStatusNameByStatusId($this->order_status);
    }

    public function copyDeliveryData()
    {
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $this->d_title = $this->title;
        $this->d_f_name = $this->f_name;
        $this->d_l_name = $this->l_name;
		$this->d_m_name = $this->m_name;
        $this->d_firma_name = $this->firma_name;
        $this->d_home = $this->home;
        $this->d_apartment = $this->apartment;
        $this->d_street = $this->street;
        $this->d_street_nr = $this->street_nr;
        $this->d_zip = $this->zip;
        $this->d_city = $this->city;
        $this->d_state = $this->state;
        $this->d_email = $this->email;
		$this->d_birthday = $this->birthday;
        $this->d_country = $this->country;
        $this->d_phone = $this->phone;
        $this->d_mobil_phone = $this->mobil_phone;
        $this->d_fax = $this->fax;
        $this->d_ext_field_1 = $this->ext_field_1;
        $this->d_ext_field_2 = $this->ext_field_2;
        $this->d_ext_field_3 = $this->ext_field_3;
		$dispatcher->triggerEvent('onAfterCopyDeliveryData', [&$currentObj]);
    }

    public function getOrdersForUser(int $idUser) 
    {
        return JSFactory::getModel('OrdersFront')->getAllOrdersByUserId($idUser);
    }

    /**
    * Next order id    
    */
    public function getLastOrderId() 
    {
        return JSFactory::getModel('OrdersFront')->genGeneratedNextOrderId();
    }

    public function formatOrderNumber($num)
    {
		$jshopConfig = JSFactory::getConfig();
        $number = outputDigit($num, $jshopConfig->ordernumberlength);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterFormatOrderNumber', [&$number, &$num]);

        return $number;
    }

    /**
    * save name pdf from order
    */
    public function insertPDF()
    {
        return JSFactory::getModel('OrdersFront')->setPdfFileByOrderId($this->pdf_file, $this->order_id);
    }
    
    public function setInvoiceDate()
    {
        if (datenull($this->invoice_date)) {
            $this->invoice_date = getJsDate();
            return JSFactory::getModel('OrdersFront')->setInvoiceDate($this->order_id, $this->invoice_date);
        }
        
    }
	
    public function getFilesStatDownloads($fileinfo = 0)
    {
        if (empty($this->file_stat_downloads)) {
            return [];
        }

        $rows = unserialize($this->file_stat_downloads);
        if ($fileinfo && !empty($rows)) {
            $_list = JSFactory::getModel('ProductsFilesFront')->getProductsFilesByFilesIds(array_keys($rows));
            $list = [];

            foreach($_list as $k => $v) {
                if (is_array($rows[$v->id])) {
                    $v->count_download = $rows[$v->id]['download'];
                    $v->time = $rows[$v->id]['time'];
                } else {
                    $v->count_download = $rows[$v->id];
                }

                $list[$v->id] = $v;
            }

            return $list;
        }else{

            foreach($rows as $k => $v) {
                if (!is_array($v)) {
                    $rows[$k] = [
                        'download' => $v, 
                        'time' => ''
                    ];
                }
            }

            return $rows;
        }
    }
    
    public function setFilesStatDownloads($array)
    {
        $this->file_stat_downloads = serialize($array);
    }
    
    public function getTaxExt()
    {
        return ($this->order_tax_ext == '') ? [] : unserialize($this->order_tax_ext);
    }
    
    public function setTaxExt($array)
    {
        $this->order_tax_ext = serialize($array);
    }
    
    public function setShippingTaxExt($array)
    {
        $this->shipping_tax_ext = serialize($array);
    }
    
    function getShippingTaxExt()
    {
        return ($this->shipping_tax_ext == '') ? [] : unserialize($this->shipping_tax_ext);
    }
    
    public function setPackageTaxExt($array)
    {
        $this->package_tax_ext = serialize($array);
    }
    
    public function getPackageTaxExt()
    {
        return empty($this->shipping_tax_ext == '') ? [] : unserialize($this->package_tax_ext);
    }

    public function setPaymentTaxExt($array)
    {
        $this->payment_tax_ext = serialize($array);
    }

    public function getNativeUploadedFiles($orderId, $orderItemId) 
    {
        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_jshopping/models');
        return JSFactory::getModel('orderItemsNativeUploadsFiles')->getDataByOrderAndItemId($orderId, $orderItemId);
    }
    
    public function getPaymentTaxExt()
    {
        return ($this->payment_tax_ext == '') ? [] : unserialize($this->payment_tax_ext);
    }
    
    public function getPaymentParamsData()
    {
        return ($this->payment_params_data == '') ? [] : unserialize($this->payment_params_data);
    }
    
    public function setPaymentParamsData($array)
    {
        $this->payment_params_data = serialize($array);
    }
    
    public function getLang()
    {
        $lang = $this->lang;

        if (empty($lang)) {
            $lang = "en-GB";
        }

        return $lang;
    }
	
    public function getListFieldCopyUserToOrder()
    {
        $dispatcher = \JFactory::getApplication();        
        $list = ['user_id','f_name','l_name','m_name','firma_name','client_type','firma_code','tax_number','email','birthday','home','apartment','street','street_nr','zip','city','state','country','phone','mobil_phone','fax','title','ext_field_1','ext_field_2','ext_field_3','d_f_name','d_l_name','d_m_name','d_firma_name','d_email','d_birthday','d_home','d_apartment','d_street','d_street_nr','d_zip','d_city','d_state','d_country','d_phone','d_mobil_phone','d_title','d_fax','d_ext_field_1','d_ext_field_2','d_ext_field_3'];
        $dispatcher->triggerEvent('onBeforeGetListFieldCopyUserToOrder', [&$list]);

        return $list;
    }
    
    public function saveOrderItem(array $items) 
    {
        return JSFactory::getModel('OrdersItemsFront')->saveOrderItems($items, $this->order_id);
    }
    
    /**
    * get or return product in Stock
    * @param $change ("-" - get, "+" - return) 
    */
    public function changeProductQTYinStock($change = "-")
    {
        $currentObj = $this;
        $db = \JFactory::getDBO();
        $dispatcher = \JFactory::getApplication();
        
        $query = "SELECT OI.*, P.unlimited FROM `#__jshopping_order_item` as OI left join `#__jshopping_products` as P on P.product_id=OI.product_id
                  WHERE order_id = '".$db->escape($this->order_id)."'";
        $db->setQuery($query);
        $items = $db->loadObjectList();
		$dispatcher->triggerEvent('onBeforechangeProductQTYinStock', array(&$items, &$currentObj, &$change));

        foreach($items as $item){
            
            if ($item->unlimited) continue;
            
            if ($item->attributes!=""){
                $attributes = unserialize($item->attributes);
            }else{
                $attributes = array();
            }            
            if (!is_array($attributes)) $attributes = array();
            
            $allattribs = JSFactory::getAllAttributes(1);
            $dependent_attr = array();
            foreach($attributes as $k=>$v){
                if ($allattribs[$k]->independent==0){
                    $dependent_attr[$k] = $v;
                }
            }
            
            if (count($dependent_attr)){
                $where="";
                foreach($dependent_attr as $k=>$v){
                    $where.=" and `attr_$k`='".intval($v)."'";
                }
                $query = "update `#__jshopping_products_attr` set `count`=`count`  ".$change." ".$item->product_quantity." where product_id='".intval($item->product_id)."' ".$where;
                $db->setQuery($query);
                $db->execute();
                
                $query="select sum(count) as qty from `#__jshopping_products_attr` where product_id='".intval($item->product_id)."' and `count`>0 ";
                $db->setQuery($query);
                $qty = $db->loadResult();
                
                $query = "UPDATE `#__jshopping_products` SET product_quantity = '".$qty."' WHERE product_id = '".intval($item->product_id)."'";
                $db->setQuery($query);
                $db->execute();
            }else{
                $query = "UPDATE `#__jshopping_products` SET product_quantity = product_quantity ".$change." ".$item->product_quantity." WHERE product_id = '".intval($item->product_id)."'";
                $db->setQuery($query);
                $db->execute();
            }
            $dispatcher->triggerEvent('onAfterchangeProductQTYinStock', array(&$item, &$change, &$currentObj));
        }
        
        $product_stock_removed = 0;

        if ($change == '-') {
            $product_stock_removed = 1;
        }

        $query = "update #__jshopping_orders set product_stock_removed=".$product_stock_removed." WHERE order_id = '".$db->escape($this->order_id)."'";
        $db->setQuery($query);
        $db->execute();
		$dispatcher->triggerEvent('onAfterchangeProductQTYinStockPSR', array(&$items, &$currentObj, &$change, &$product_stock_removed));
    }
    
    /**    
    * get list vendors for order
    */
    public function getVendors()
    {
        $db = \JFactory::getDBO();
        $query = "SELECT distinct V.* FROM `#__jshopping_order_item` as OI
                  left join `#__jshopping_vendors` as V on V.id = OI.vendor_id
                  WHERE order_id = '" . $db->escape($this->order_id) . "'";
        $db->setQuery($query);

        return $db->loadObjectList();
    }
    
    public function getVendorItems($vendor_id)
    {
        $items = $this->getAllItems();
        foreach($items as $k=>$v){
            if ($v->vendor_id!=$vendor_id){
                unset($items[$k]);
            }
        }

        return $items;
    }
    
    public function getVendorInfo()
    {
        $jshopConfig = JSFactory::getConfig();
        $vendor_id = $this->vendor_id;
        if ($vendor_id==-1) $vendor_id = 0;
        if ($jshopConfig->vendor_order_message_type<2) $vendor_id = 0;
        $vendor = JSFactory::getTable('vendor', 'jshop');
        $vendor->loadFull($vendor_id);
        $vendor->country_id = $vendor->country;
        $lang = JSFactory::getLang($this->getLang());
        $country = JSFactory::getTable('country', 'jshop');
        $country->load($vendor->country_id);
        $field_country_name = $lang->get("name");
        $vendor->country = $country->$field_country_name;

        return $vendor;
    }
    
    public function getVendorIdForItems()
    {
        $items = $this->getAllItems();
        $vendors = [];
        
        foreach($items as $v) {
            $vendors[] = $v->vendor_id;
        }

        $vendors = array_unique($vendors);
        if (empty($vendors)) {
            return 0;
        }elseif (count($vendors) > 1) {
            return -1;
        }

        return $vendors['0'];
    }
    
    public function getReturnPolicy()
    {
        $rows = '';
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterOrderGetReturnPolicy', [&$currentObj, &$rows]);

        return $rows;
    }
    
    public function saveTransactionData($rescode, $status_id, $data)
    {
        $row = JSFactory::getTable('PaymentTrx', 'jshop');
        $row->order_id = $this->order_id;
        $row->rescode = $rescode;
        $row->status_id = $status_id;
        $row->transaction = $this->transaction;
        $row->date = getJsDate();
        $row->store();

        if (is_array($data)) {
            foreach($data as $k => $v) {
                $rowdata = JSFactory::getTable('PaymentTrxData', 'jshop');
                $rowdata->id = 0;
                $rowdata->trx_id = $row->id;
                $rowdata->order_id = $this->order_id;
                $rowdata->key = $k;
                $rowdata->value = $v;
                $rowdata->store();
            }
        }
    }
    
    public function getListTransactions()
    {
        $db = \JFactory::getDBO();
        $query = "SELECT * FROM `#__jshopping_payment_trx` WHERE order_id = '".$db->escape($this->order_id)."' order by id desc";
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        foreach($rows as $k => $v) {
            $rows[$k]->data = $this->getTransactionData($v->id);
        }

        return $rows;
    }
    
    public function getTransactionData($trx_id)
    {
        $db = \JFactory::getDBO();
        $query = "SELECT * FROM `#__jshopping_payment_trx_data` WHERE trx_id = '".$db->escape($trx_id)."' order by id";
        $db->setQuery($query);  

        return $db->loadObjectList();
    }

    public function setShippingParamsData($array)
    {
        $this->shipping_params_data = serialize($array);
    }
    
    public function getShippingParamsData()
    {
        return ($this->shipping_params_data == '') ? [] : unserialize($this->shipping_params_data);
    }

    /**
     * @param $typeName - order, delivery_note
     */
    public function getOrderNumbWithSuffixFor($typeName)
    {
        $typeNameSuffix = $typeName . '_suffix';
        $jshopConfig = JSFactory::getConfig();

        preg_match('~-(?P<orderNumb>\d+)$~U', $this->order_number, $matches);
        $orderNumber = (isset($matches['orderNumb'])) ? $matches['orderNumb'] : $this->order_number;

        if (isset($jshopConfig->$typeNameSuffix)) {
            return $jshopConfig->$typeNameSuffix . $orderNumber;
        }

        return $orderNumber;
    }

    public function bindFromCart(jshopCart &$cart, jshopPaymentMethod &$paymentMethod, &$paymentSystemVerySimple = 1, &$paymentSystem = null)
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $orderNumber = $jshopConfig->getNextOrderNumber();
        
        $sh_mt_pr = JTable::getInstance('shippingMethodPrice', 'jshop');
        $sh_mt_pr->load((int)$cart->getShippingPrId());
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeBindFromCart', [&$currentObj, &$cart]);

        $this->order_date = $this->order_m_date = getJsDate();
        $this->order_tax = $cart->getTax(1, 1, 1);
        $this->setTaxExt($cart->getTaxExt(1, 1, 1));
        $this->order_subtotal = $cart->getPriceProducts();
        $this->order_shipping = $cart->getShippingPrice();
        $this->order_payment = $cart->getPaymentPrice();
        $this->order_discount = $cart->getDiscountShow();
        $this->shipping_tax = $cart->getShippingPriceTaxPercent();
        $this->setShippingTaxExt($cart->getShippingTaxList());
        $this->payment_tax = $cart->getPaymentTaxPercent();
        $this->setPaymentTaxExt($cart->getPaymentTaxList());
        $this->order_package = $cart->getPackagePrice();
        $this->setPackageTaxExt($cart->getPackageTaxList());
        $this->order_total = $cart->getSum(1, 1, 1);
        $this->currency_exchange = $jshopConfig->currency_value;
        $this->vendor_type = $cart->getVendorType();
        $this->vendor_id = $cart->getVendorId();
        if ($jshopConfig->without_payment) {
			$this->order_status = $jshopConfig->default_status_order;
		}else{		
			$this->order_status = $paymentMethod->payment_status;
		}
        $this->shipping_method_id = (int)$cart->getShippingId();
        $this->payment_method_id = $cart->getPaymentId();
        $this->delivery_times_id = $sh_mt_pr->delivery_times_id;
		$this->shippings = $cart->getShippingId();
        $this->user_id = $cart->user_id;

        if ($jshopConfig->delivery_order_depends_delivery_product) {
            $this->delivery_time = $cart->getDelivery();
        }

        if ($jshopConfig->show_delivery_date) {
            $this->delivery_date = $cart->getDeliveryDate();
        }

        $this->coupon_id = $cart->getCouponId();
        $pm_params = $cart->getPaymentParams();

        if (is_array($pm_params) && !$paymentSystemVerySimple) {
            $paymentSystem->setParams($pm_params);
            $payment_params_names = $paymentSystem->getDisplayNameParams();
            $this->payment_params = getTextNameArrayValue($payment_params_names, $pm_params);
            $this->setPaymentParamsData($pm_params);
        }
        
        $sh_params = $cart->getShippingParams();
        if (is_array($sh_params)) {
            $sh_method = JSFactory::getTable('shippingMethod', 'jshop');
            $sh_method->load($cart->getShippingId());
            $shippingForm = $sh_method->getShippingForm();

            if (!empty($shippingForm)) {
                $shipping_params_names = $shippingForm->getDisplayNameParams();            
                $this->shipping_params = getTextNameArrayValue($shipping_params_names, $sh_params);
            }

            $this->setShippingParamsData($sh_params);
        }        
       
        $this->ip_address = $_SERVER['REMOTE_ADDR'];
        $this->order_add_info = JFactory::getApplication()->input->getVar('order_add_info','');
        $this->currency_code = $jshopConfig->currency_code;
        $this->currency_code_iso = $jshopConfig->currency_code_iso;
        $this->order_number = $this->formatOrderNumber($orderNumber);
        $this->order_hash = md5(time() . $this->order_total . $this->user_id);
        $this->file_hash = md5(time() . $this->order_total . $this->user_id . 'hashfile');
        $this->display_price = $jshopConfig->display_price_front_current;
        $this->lang = $jshopConfig->getLang();

        if (isset($jshopConfig->order_suffix) && $jshopConfig->order_suffix != '') {
            $this->order_number = $jshopConfig->order_suffix . $this->order_number;
        }
                
        if ($this->order_total == 0) {
            $paymentMethod->payment_type = 1;
            $jshopConfig->without_payment = 1;
            $this->order_status = $jshopConfig->payment_status_paid;
        }
        
        $this->order_created = 0;
        if ($paymentMethod->payment_type == 1) {
            $this->order_created = 1; 
        }
    }

    public function storeCartData(jshopCart $cart, int $billingAddressId, int $shippingAddressId)
    {
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeCreateOrder', [&$currentObj, &$cart]);

        $tableOfOrderAddress = JSFactory::getTable('OrderAddress');
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $billing = $modelOfUserAddressesFront->getById($billingAddressId) ?: [];
        $shipping = ($shippingAddressId == $billingAddressId) ? $billing : ($modelOfUserAddressesFront->getById($shippingAddressId) ?: []);
        $tableOfOrderAddress->bindShippingAndBillingAddresses((array)$shipping, (array)$billing);
        $tableOfOrderAddress->store();
        $this->order_address_id = $tableOfOrderAddress->id;
        $dispatcher->triggerEvent('onBeforeStoreCartDataToOrder', [&$currentObj, &$cart]);
        $isStored = $this->store();

        if ($isStored) {
            $jshopConfig = JSFactory::getConfig();
            $jshopConfig->updateNextOrderNumber();

            $session = JFactory::getSession();

            $dispatcher->triggerEvent('onAfterCreateOrder', [&$currentObj]);
            OrderExtrascouponMambot::getInstance()->onAfterCreateOrder($this);

            if ($this->coupon_id) {
                $coupon = JTable::getInstance('coupon', 'jshop');
                $coupon->load($this->coupon_id);

                if ($coupon->finished_after_used) {
                    $shopUser = JSFactory::getUser();
                    $free_discount = $cart->getFreeDiscount();

                    if ($free_discount > 0) {
                        $coupon->coupon_value = $free_discount / $jshopConfig->currency_value;
                    } else {
                        $coupon->used = $shopUser->user_id;
                    }
                }
				$coupon->count_use++;
				
				$coupon->store();
            }

            $this->saveOrderItem($cart->products);
            $dispatcher->triggerEvent('onAfterCreateOrderFull', [&$currentObj]);
            $session->set('jshop_end_order_id', $this->order_id);

            return true;
        }

        return false;
    }

    /**
     * Loads the required fields from OrderAddress for Order
     */
    protected function loadDataFromOrderAddress()
    {
        if (!empty($this->order_address_id)) {

            $tableOfOrderAddress = JSFactory::getTable('OrderAddress');
            $isLoaded = $tableOfOrderAddress->load($this->order_address_id);

            if ($isLoaded && !empty($tableOfOrderAddress->id)) {
                $tableColumns = array_keys($tableOfOrderAddress->getTableFields(false, true));
                $excluded = [
                    'user_id',
					'shippings'
                ];

                foreach ($tableColumns as $key => $columnName) {
                    if (!in_array($columnName, $excluded)) {
                        $this->$columnName = $tableOfOrderAddress->$columnName;
                    }
                }

                return true;
            }
        }

        return false;
    }


    public function getTaxExtFormat()
    {
        $new_list = [];
        $list = ($this->order_tax_ext == '') ? [] : unserialize($this->order_tax_ext);
        if(!empty($list)){
            $i = 0;
            foreach($list as $percent => $value){
                $new_list[$i]['value']= formatprice($value, $this->currency_code);
                $new_list[$i]['percent']= formattax($percent);
                $i++;
            }
        }
        return $new_list;
    }

	public function setOneTimeCost($order_items){
		foreach($order_items as $key=>$value){
			$jshop_attr_id=unserialize($order_items[$key]->attributes);
			$product = JSFactory::getTable('product', 'jshop');
			$product->load($order_items[$key]->product_id,true,false);
			$order_items[$key]->one_time_cost = JSFactory::getTable('ProductAttribut2')->calcAttrsWithOneTimeCostPriceType($product->product_id, $jshop_attr_id, getPriceCalcParamsTax($product->product_price, $product->product_tax_id));						
		}
        return $order_items ?: [];
	}
	
    public function setRefundsDate()
    {
        if (datenull($this->invoice_date)) {
            $this->invoice_date = getJsDate();
            return JSFactory::getModel('OrdersFront')->setInvoiceDate($this->order_id, $this->invoice_date);
        }
        
    }
}