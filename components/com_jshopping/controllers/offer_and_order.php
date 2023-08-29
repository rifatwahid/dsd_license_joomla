<?php

/**
 * @version      1.7.0 07.05.2019
 * @author       MAXXmarketing GmbH
 * @package      addon offer_and_order
 * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
 * @license      GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class JshoppingControllerOffer_and_order extends JshoppingControllerBase 
{
    public function __construct()
    {
        $language =& JFactory::getLanguage();
        $language->load('addon_offer_and_order' , JPATH_ROOT, $language->getTag(), true);
		setSeoMetaData();
        parent::__construct();
    }

    public function display($cachable = false, $urlparams = false) 
    {
        $this->myoffer_and_order();
    }

    public function create_offer() 
    {
    	$session = JFactory::getSession();
        $ajax = JFactory::getApplication()->input->getInt('ajax', 0);
        $projectname = JFactory::getApplication()->input->getVar('projectname', '');

        if (!empty($projectname)) {
            $session->set('projectname', $projectname);
        }

        checkUserLogin($ajax);
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();
        $this->cart_to_order($order, $cart, 2);
        $order->store();
        if($ajax){
            $data['redirect'] = SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=created&id=' . $order->order_id,1);
            $data['status'] = 1;
            print_r(json_encode($data));die;

        }
        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=created&id=' . $order->order_id, 1, 1));
    }

    public function createOfferFromProduct()
    {
        $dataRepository = [
            'ajax' => JFactory::getApplication()->input->getInt('ajax'),
            'product_id' => JFactory::getApplication()->input->getInt('product_id'),
            'category_id' => JFactory::getApplication()->input->getInt('category_id'),
            'jshop_attr_id' => is_array(JFactory::getApplication()->input->getVar('jshop_attr_id')) ? JFactory::getApplication()->input->getVar('jshop_attr_id') : [],
            'freeattribut' => is_array(JFactory::getApplication()->input->getVar('freeattribut')) ? JFactory::getApplication()->input->getVar('freeattribut') : [],
            'requestQuantity' => JFactory::getApplication()->input->getInt('quantity', 1),
            'requestQuantityVar' => JFactory::getApplication()->input->getVar('quantity',1),
            'uploadDataArr' => JFactory::getApplication()->input->getVar('nativeProgressUpload')
        ];
        $session = JFactory::getSession();
        $additional_fields = [];
        $usetriggers = 1;
        $errors = [];
        $displayErrorMessage = 1;
        $quantity = $dataRepository['requestQuantity'];
        $projectname = JFactory::getApplication()->input->getVar('projectname', '');

        if ( !JFactory::getUser()->id ) {
            $session->set('dataRepository', $dataRepository);            
        } elseif ( empty($dataRepository['product_id']) ) {
            $dataRepository = $session->get('dataRepository');
        }
        
        $_SERVER['REQUEST_URI'] .= (strstr($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') . 'projectname=' . $projectname;
        checkUserLogin();

        header("Cache-Control: no-cache, must-revalidate");
        $jshopConfig = JSFactory::getConfig(); 
        if ($jshopConfig->user_as_catalog || !getDisplayPriceShop()) {
            return 0;
        }

        if ($jshopConfig->use_decimal_qty) {
            $quantity = floatval(str_replace(',', '.', $dataRepository['requestQuantityVar']));
            $quantity = round($quantity, $jshopConfig->cart_decimal_qty_precision);
        }

        foreach($dataRepository['jshop_attr_id'] as $k => $v) {
            $dataRepository['jshop_attr_id'][intval($k)] = intval($v);
        }
		
		$productTable = JSFactory::getTable('product');
        $productTable->load($dataRepository['product_id']);		
		$attributesDatas = $productTable->getAttributesDatas($dataRepository['jshop_attr_id'], JSFactory::getUser()->usergroup_id);
		$dataRepository['jshop_attr_id']=$attributesDatas['attributeActive'];	
		foreach ($dataRepository['jshop_attr_id'] as $key=>$val){
			if ($val<=0){unset($dataRepository['jshop_attr_id'][$key]);}
		}
        
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load('cart');  

        if (!$cart->add($dataRepository['product_id'], $quantity, $dataRepository['jshop_attr_id'], $dataRepository['freeattribut'], $additional_fields, $usetriggers, $errors, $displayErrorMessage, $dataRepository['uploadDataArr'])) {
            if ($dataRepository['ajax']){
                echo getMessageJson();
                die();
            }

            $back_value = [
                'pid' => $dataRepository['product_id'], 
                'attr' => $dataRepository['jshop_attr_id'], 
                'freeattr' => $dataRepository['freeattribut'],
                'qty' => $quantity
            ];
            $session->set('product_back_value', $back_value);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=' . $dataRepository['category_id'] . '&product_id=' . $dataRepository['product_id'], 1, 1));

            return 0;
        }

        $cartObjFromSession = unserialize($session->get('cart'));
        $cloneCartObjFromSession = unserialize($session->get('cart'));

        $lastElement = $cloneCartObjFromSession->products[count($cloneCartObjFromSession->products) - 1];
        if ( $lastElement['quantity'] == $dataRepository['requestQuantity'] ) {
            array_pop($cloneCartObjFromSession->products);
        } else {
            $quantityOfProduct = $lastElement['quantity'] - $dataRepository['requestQuantity'];
            $quantityOfProduct = ( $quantityOfProduct >= 2 ) ? $quantityOfProduct : 1;

            $cloneCartObjFromSession->products[count($cloneCartObjFromSession->products) - 1]['quantity'] = $quantityOfProduct;
        }
        $quantity = [];
        foreach($cloneCartObjFromSession->products as $key => $_product){
            $quantity[$key] = $_product['quantity'];
        }
        $cloneCartObjFromSession->refresh($quantity);

        if (!empty($cartObjFromSession->products) && count($cartObjFromSession->products) > 1) {
            do {
                array_shift($cartObjFromSession->products);
            } while( count($cartObjFromSession->products) > 1 );        
        }

        $cartObjFromSession->products['0']['quantity'] = $dataRepository['requestQuantity'];
        $cartObjFromSession->loadPriceAndCountProducts();
        $cartObjFromSession->reloadRabatValue();  

        $order = JTable::getInstance('offer_and_order', 'jshop');
        $this->cart_to_order($order, $cartObjFromSession, 2);
        $order->store();
        if($dataRepository['ajax']){
            $data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=created&id=' . $order->order_id,1);
            $data['status'] = 1;
            print_r(json_encode($data));die;
        }
        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=created&id=' . $order->order_id, 1, 1));        
    }    
    
    public function create_order() 
    {
        checkUserLogin();
        $order = JTable::getInstance('order', 'jshop');
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();

        $this->cart_to_order($order, $cart, 1);

        $jshopConfig = JSFactory::getConfig();
        $orderNumber = $jshopConfig->getNextOrderNumber();
        $jshopConfig->updateNextOrderNumber();
        if (!$order->order_number) {
            $order->order_number = $order->formatOrderNumber($orderNumber);
        }

        $order->store();

        $order_history = JSFactory::getTable('orderHistory', 'jshop');
        $order_history->order_id = $order->order_id;
        $order_history->order_status_id = $order->order_status;
        $order_history->status_date_added = getJsDate();
        $order_history->customer_notify = 1;
        $order_history->comments = '';
        $order_history->store();

        return $order;
    }

    public function cart_to_order(&$order, &$cart, $type = 0) 
    {
        $jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $user = JFactory::getUser();
        $config = JSFactory::getConfig();

        $session = JFactory::getSession();
        $session->set('display_link_offer_and_order_guest', 1);

        $projectname = JFactory::getApplication()->input->getVar('projectname', '');
        $guest = JFactory::getApplication()->input->getInt('guest');

        if (!empty($projectname)) {
            $session->set('projectname', $projectname);
        }

        if (!$guest) {
            checkUserLogin();
        }

        if ($user->id) {
            $adv_user = JSFactory::getUserShop();
        } else {
            $adv_user = JSFactory::getUserShopGuest();
        }

        $cart->setDisplayItem(1, 1);
        $cart->setDisplayFreeAttributes();
        $this->addShipping($cart);
        $this->addPayment($cart);

        $arr_property = [
            'user_id', 
            'f_name', 
            'm_name',
            'l_name', 
            'firma_name', 
            'client_type', 
            'firma_code', 
            'tax_number', 
            'email', 
            'home', 
            'apartment', 
            'street', 
            'street_nr',
            'zip', 
            'city', 
            'state', 
            'country', 
            'phone', 
            'mobil_phone', 
            'fax', 
            'title', 
            'ext_field_1', 
            'ext_field_2', 
            'ext_field_3', 
        ];

        foreach ($adv_user as $key => $value) {
            if (in_array($key, $arr_property)) {
                $modifyKey = 'd_' . $key;
                $order->$key = $value;

                if (property_exists($order, $modifyKey)) {
                    $order->$modifyKey = $value;
                }
            }
        }

        $orderNumber = $order->getLastOrderId();
        $order->offer_status = $type;
        $order->order_date = date('Y-m-d H:i:s');
        $order->order_m_date = date('Y-m-d H:i:s');
        $order->order_tax = $cart->getTax(1, 1, 1);
        $order->setTaxExt($cart->getTaxExt(1, 1, 1));
        $order->order_subtotal = $cart->getPriceProducts();
        $order->order_discount = $cart->getDiscountShow();
        $order->order_total = $cart->getSum(1, 1, 1);
        $order->currency_exchange = $jshopConfig->currency_value;
        $order->vendor_type = $cart->getVendorType();
        $order->vendor_id = $cart->getVendorId();
        $order->order_payment = $cart->getPaymentPrice();
        $order->order_shipping = $cart->getShippingPrice() + $cart->getPackagePrice();;
        $order->shipping_method_id = $cart->getShippingId();
        $order->payment_method_id = $cart->getPaymentId();
        $order->order_status = $jshopConfig->default_status_order;
        $order->delivery_times_id = $cart->delivery_times_id;
        $order->order_created = 1;

        if ($jshopConfig->delivery_order_depends_delivery_product) {
            $order->delivery_time = $cart->getDelivery();
        }

        $order->ip_address = $_SERVER['REMOTE_ADDR'];
        $order->order_add_info = JFactory::getApplication()->input->getVar('order_add_info', '');
        $order->projectname = $session->get('projectname');

        $session->set('projectname', '');

        $order->currency_code = $jshopConfig->currency_code;
        $order->currency_code_iso = $jshopConfig->currency_code_iso;

        if (!$order->order_number) {
            $order->order_number = $order->formatOrderNumber($orderNumber);
        }
            
        if (!empty($jshopConfig->offer_and_order_suffix)) {
            $order->order_number = $jshopConfig->offer_and_order_suffix . $order->order_number;
        }

        $order->order_hash = md5(time() . $order->order_total . $order->user_id);
        $order->file_hash = md5(time() . $order->order_total . $order->user_id . 'hashfile');
        $order->display_price = $jshopConfig->display_price_front_current;
        $order->lang = $jshopConfig->cur_lang;
        $order->client_type_name = '';

        if ($order->client_type) {
            $order->client_type_name = $jshopConfig->user_field_client_type[$order->client_type];
        }

        if (!$adv_user->delivery_adress) {
            $order->copyDeliveryData();
        }
            
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveOfferAndOrder', [&$order]);
		
        $order->store();
        $session->set('offer_and_order_id', '');

        $order->saveOrderItem($cart->products);

        $order->products = $order->getAllItems();
        if ($type == 2) {
            $validity = $config->offer_and_order_validity;
            if (!$validity) {
                $validity = 10;
            }
            $order->valid_to = date('Y-m-d', strtotime('+' . $validity . ' day'));
            $order->show_invoice_date = $config->offer_and_order_invoice_data;
        }

        $order->order_tax_list = $order->getTaxExt();

        $country = JTable::getInstance('country', 'jshop');
        $country->load($order->country);
        $order->country = $country->country_id;

        $d_country = JTable::getInstance('country', 'jshop');
        $d_country->load($order->d_country);
        $order->d_country = $d_country->country_id;
        
        $order->setInvoiceDate();

        if ($type <= 2) {
            include_once templateOverride("pdf", "generete_pdf_offer_and_order.php");//JPATH_COMPONENT_SITE . '/lib/generete_pdf_offer_and_order.php';
            $order->pdf_file = generateOfferAndOrderPdf($order, $jshopConfig);
            $order->insertPDF();
        } else {
            $file_generete_pdf_order = $jshopConfig->file_generete_pdf_order;
            include_once($file_generete_pdf_order);
            $order->pdf_file = generatePdf($order, $jshopConfig);
            $order->insertPDF();
            unset($order->liferadresse);
            unset($order->liferadresse2);
        }
    }

    public function created() 
    {
        $post = $this->input->getArray();
        $doc = JFactory::getDocument();
        $jshopConfig = JSFactory::getConfig();
        $ajax = $post['ajax'] ?? null;

        $id = JFactory::getApplication()->input->getInt('id');
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $order->load($id);

        $url = $jshopConfig->pdf_orders_live_path . '/' . $order->pdf_file;

        loadJsFilesLightBoxAddonOfferAndOrder();

        loadJSLanguageKeys();

        $view = $this->getView('offer_and_order', getDocumentType(), '', [
            'template_path' => viewOverride('offer_and_order', 'created_offer_and_order.php')
        ]);
        $layout = getLayoutName('offer_and_order', 'created');
        $view->setLayout($layout);

        $view->set('component', ucfirst('Created_offer_and_order'));
        $view->set('url', $url);
        $view->set('order', $order);
        $view->set('config', $jshopConfig);
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $doc->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));

        if ($ajax) {
            print json_encode(prepareView($view));die;
            die();
        }
        $view->display();
    }

    public function order() 
    {
        $id = JFactory::getApplication()->input->getInt('id');
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $order->load($id);

        $additional_fields = [];
        $usetriggers = 0;
        $errors = [];
        $displayErrorMessage = 1;

        $date = date('Y-m-d');
        if ($date > $order->valid_to) {
            \Joomla\CMS\Factory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_DATE_FINISHED'), 'notice');
            return 0;
        }

        $session = JFactory::getSession();
        $session->set('offer_and_order_id', $id);
        $session->set('projectname', $order->projectname);

        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $orderListProducts = $order->getAllItems();
        foreach ($orderListProducts as $pkey => $or_prod) {
            $orderProductInfo[$pkey]['product_id'] = $or_prod->product_id;
            $orderProductInfo[$pkey]['product_quantity'] = $or_prod->product_quantity;
            $orderProductInfo[$pkey]['attributes'] = $or_prod->attributes;
            $orderProductInfo[$pkey]['freeattributes'] = $or_prod->freeattributes;
            $orderProductInfo[$pkey]['charakteristik'] = $or_prod->extra_fields;
            $orderProductInfo[$pkey]['weight'] = $or_prod->weight;
            $orderProductInfo[$pkey]['thumb_image'] = $or_prod->thumb_image;
            $orderProductInfo[$pkey]['product_offer_and_order_price'] = $or_prod->product_item_price;
            $orderProductInfo[$pkey]['product_id_for_order'] = $or_prod->product_id_for_order;
        }

        foreach ($orderProductInfo as $product) {
            $product_id = $product['product_id'];
            $product_quantity = intval($product['product_quantity']);
            $attr_id = unserialize($product['attributes']);
			foreach($attr_id as $id=>$val){
				if($val == -1){
					unset($attr_id[$id]);
					$usetriggers = 1;
				}
			}
            $freeattributes = unserialize($product['freeattributes']);

            $session->set('product_offer_and_order_price', $product['product_offer_and_order_price']);
            $session->set('product_offer_and_order_image', $product['thumb_image']);
            $session->set('product_offer_and_order_id_for_order', $product['product_id_for_order']);
            $cart->add($product_id, $product_quantity, $attr_id, $freeattributes, $additional_fields, $usetriggers, $errors, $displayErrorMessage, [], []);
            $session->set('product_offer_and_order_price', '');
            $session->set('product_offer_and_order_image', '');
            $session->set('product_offer_and_order_id_for_order', '');
        }

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 0, 1));
    }

    public function myoffer_and_order() 
    {
        checkUserLogin();

        $user = JFactory::getUser();
        $jshopConfig = JSFactory::getConfig();
        $document = JFactory::getDocument();
		$ajax = JFactory::getApplication()->input->getInt('ajax');

        $order = JTable::getInstance('offer_and_order', 'jshop');
        $list = $order->getOrdersForUser($user->id);

        loadJsFilesLightBoxAddonOfferAndOrder();

        $language =& JFactory::getLanguage();
        $language->load('addon_offer_and_order' , JPATH_ROOT, $language->getTag(), true);
        loadJSLanguageKeys();

        $view = $this->getView('offer_and_order', getDocumentType(), '', [
            'template_path' => viewOverride('offer_and_order', 'myoffer_and_order.php')
        ]);
        $layout = getLayoutName('offer_and_order', 'myoffer_and_order');
        $view->setLayout($layout);

        $view->set('component', 'Myoffer_and_order');
        $view->set('rows', $list);
        $view->set('config', $jshopConfig);
        $view->set('text_search', JFactory::getApplication()->input->getVar('text_search'));
        $view->set('sefLinkSearchOffers', SEFLink('index.php?option=com_jshopping&controller=offer_and_order', 1));
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
		//print_r($list);die;
		if($ajax){ print json_encode(prepareView($view));die; }
        $view->display();
    }

    private function logout() 
    {
        $session = JFactory::getSession();
        $session->set('user_shop_guest', null);
        $session->set('cart', null);
    }

    private function login($user_id, $admin_user_id, $hide_pd5_password) 
    {
        $user = JFactory::getUser();
        $user->load($user_id);

        $app = JFactory::getApplication();
        $input = $app->input;

        // Populate the data array:
        $data = [
            'return' => base64_decode(base64_encode(JUri::base())),
            'username' => $user->username,
            'password' => '',
            'aca_name' => '',
            'secretkey' => ''
        ];

        // Set the return URL if empty.
        if (empty($data['return'])) {
            $data['return'] = 'index.php?option=com_users&view=profile';
        }
        // Set the return URL in the user state to allow modification by plugins
        $app->setUserState('users.login.form.return', $data['return']);

        // Get the log in options.
        $options = [
            'remember' => $input->getBool('remember', false),
            'return' => $data['return']
        ];

        // Get the log in credentials.
        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
            'secretkey' => $data['secretkey']
        ];

        if (true !== $app->login($credentials, $options)) {
            // Login failed !
            // Clear user name, password and secret key before sending the login form back to the user.
            $data['remember'] = (int) $options['remember'];
            $data['username'] = '';
            $data['password'] = '';
            $data['secretkey'] = '';
            $app->setUserState('users.login.form.data', $data);
            $app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
        }
        // Success
        if ($options['remember'] == true) {
            $app->setUserState('rememberLogin', true);
        }

        $this->save_outsite_params($admin_user_id, $hide_pd5_password);
        $app->setUserState('users.login.form.data', []);
        $app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
    }

    public function relogin() 
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $method = $input->getMethod();
        $user_id = $input->$method->getInt('user_id');
        $admin_user_id = $input->$method->getInt('admin_user_id');
        $hide_pd5_password = $input->$method->get('password', '', 'RAW');
        $admin_user = JFactory::getUser();
        $admin_user->load($admin_user_id);

        if ($admin_user->hide_pd5_password == $hide_pd5_password) {
            $this->logout();
            $this->login($user_id, $admin_user_id, $hide_pd5_password);
        }
    }

    private function save_outsite_params($admin_user_id, $hide_pd5_password) 
    {
        $session = JFactory::getSession();
        $session->set('offer_and_order_admin_user_id', $admin_user_id);
        $session->set('offer_and_order_hide_pd5_password', $hide_pd5_password);
    }

    public function create_offer_cart() 
    {
        $this->create_offer();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $app = JFactory::getApplication('site');
        $app->redirect(JRoute::_(JURI::base() . 'administrator/index.php?option=com_jshopping&controller=offer_and_order&task=display&user_id=' . intval($user_id), 0));
    }

    public function getPaymentDefault() 
    {
        $config = JSFactory::getConfig();
        return intval($config->offer_and_order_payment);
    }

    public function getShippingDefault($cart) {
        $config = JSFactory::getConfig();
		$shipping_id = $cart->getShippingId() ?: intval($config->offer_and_order_shipping);
		return $shipping_id;
    }

    public function getPaymentParams() 
    {
        return '';
    }

    public function getShippingParams() 
    {
        return '';
    }

    public function addPayment(&$cart) 
    {
        $payment_method_id = $this->getPaymentDefault();

        if ($payment_method_id) {
            $paym_method = JSFactory::getTable('paymentmethod', 'jshop');
            $paym_method->load($payment_method_id);
            $cart->setPaymentId($payment_method_id);
            $cart->setDisplayItem(1, 1);
            $paym_method->setCart($cart);
            $price = $paym_method->getPrice();
            $cart->setPaymentDatas($price, $paym_method);
            $cart->setPaymentPrice($price);
        }
    }

    public function addShipping(&$cart) 
    {
        $shipping_method_id = $this->getShippingDefault($cart);
		$shipping_method_ids = explode('_', $shipping_method_id);
		$shipping_price = 0;
		$package_price = 0;
		foreach($shipping_method_ids as $shipping_method_id){
			if ($shipping_method_id) {
				$cart->setShippingId($shipping_method_id);
				$adv_user = JSFactory::getUser();
				$jshopConfig = JSFactory::getConfig();
				$shippingmethodprice = JSFactory::getTable('shippingMethodPrice', 'jshop');
				$id_country = $adv_user->country;

				if (isset($adv_user->delivery_adress) && $adv_user->delivery_adress) {
					$id_country = $adv_user->d_country;
				} 

				if (!$id_country) {
					$id_country = $jshopConfig->default_country;
				}
					
				if (!$id_country) {
					JError::raiseWarning('', JText::_('COM_SMARTSHOP_REGWARN_COUNTRY'));
				}

				$payment_method_id = $this->getPaymentDefault();
				$cart->setPaymentId($payment_method_id);

				$offer_and_order = JTable::getInstance('offer_and_order', 'jshop');
				$shipping = $offer_and_order->getShippingMethodsCountry($id_country, $payment_method_id, $shipping_method_id);
				if(isset($shipping->sh_pr_method_id)){
					$shippingmethodprice->load($shipping->sh_pr_method_id);					
					$prices = $shippingmethodprice->calculateSum($cart);
				}
				$shipping_prices += $prices['shipping'] ?? 0;
				$package_prices += $prices['package'] ?? 0;

				$prices['shipping'] = $shipping_prices;
				$prices['package'] = $package_prices;
				$cart->shipping_method_id = $shipping_method_id;
				$cart->setShippingPrice($prices['shipping']);
				$cart->setPackagePrice($prices['package']);

				$cart->delivery_times_id = $shippingmethodprice->delivery_times_id;
			}
		}
    }

    public function create_order_cart() 
    {
        checkUserLogin();
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();
        $this->cart_to_order($order, $cart, 1);
        $order->store();
        $app = JFactory::getApplication('site');
        $app->redirect(JRoute::_(JURI::base() . 'administrator/index.php?option=com_jshopping&controller=offer_and_order&task=create_new_order&order_id=' . intval($order->order_id), 0));
    }

    public function send_offer_email()
    {
        $ajax = JFactory::getApplication()->input->getInt('ajax', 0);
        $user_id = JFactory::getApplication()->input->getVar('user_id');
        $order_id = JFactory::getApplication()->input->getVar('order_id');
        $email = JFactory::getApplication()->input->getString('email', '');
        $user_shop = JSFactory::getTable('userShop', 'jshop');
        $user_shop->load($user_id);
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $order->load($order_id);
        $order->status_email = intval($order->status_email) + 1;
        $order->store();
        $config = JFactory::getConfig();
        $data = $user_shop->getProperties();
        $data['fromname'] = $config->get('fromname');
        $data['mailfrom'] = $config->get('mailfrom');
        $data['sitename'] = $config->get('sitename');
        $data['siteurl'] = JUri::base();
        $emailSubject = sprintf(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SEND_EMAIL_HEAD'), $user_shop->f_name, $user_shop->l_name, $data['sitename'], $data['name']);
        $emailBody = sprintf(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SEND_EMAIL_BODY'), $user_shop->f_name, $user_shop->l_name, $data['sitename'], $data['username']);
        $filename = $order->pdf_file;
		$app = JFactory::getApplication();
		if (!$app->get('mailonline', 1)){
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SENDING_MAIL'),'error');
			if ($ajax == 0) {
				$this->setRedirect('index.php?option=com_jshopping&controller=offer_and_order&task=offer_email_sent');
			} else {
				while(ob_get_level())ob_end_clean();
				echo json_encode(0);
				exit;
			}
		}

		
			$mailer = JFactory::getMailer();
			$mailer->setSender(array($data['mailfrom'], $data['fromname']));
			$mailer->addRecipient($email != '' ? $email : $data['email']);
			$mailer->setSubject($emailSubject);		
			$dataForTemplate = array('emailSubject'=>$emailSubject, 'emailBod'=>$emailBody);
			$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
			$mailer->setBody($bodyEmailText);
			if (!empty($filename)) {
				$path = JPATH_SITE . '/components/com_jshopping/files/pdf_orders/' . $filename;
				if (file_exists($path)) {
					$mailer->addAttachment($path);
				}
			}
			$mailer->isHTML(true);
			$mailer->Send();
		

        if ($ajax == 0) {
            $this->setRedirect('index.php?option=com_jshopping&controller=offer_and_order&task=offer_email_sent');
        } else {
            while(ob_get_level())ob_end_clean();
            echo json_encode((int)(!$mailer->isError()));
            exit;
        }
    }
    
    public function offer_email_sent()
    {
        $jshopConfig = JSFactory::getConfig();
        $document = JFactory::getDocument();
        $id = JFactory::getApplication()->input->getInt('id');
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $order->load($id);

        loadJSLanguageKeys();

        $view = $this->getView('offer_and_order', getDocumentType(), '', [
            'template_path' => viewOverride('offer_and_order', 'offersent.php')
        ]);
        $layout = getLayoutName('offer_and_order', 'offersent');
        $view->setLayout($layout);

        $view->set('component', 'Offersent');
        $view->set('order', $order);
        $view->set('config', $jshopConfig);
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));

        $view->display();
    }
}