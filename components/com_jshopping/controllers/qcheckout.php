<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Language\Text;

require_once JPATH_ROOT . '/components/com_jshopping/payments/payment.php';
require_once JPATH_ROOT . '/components/com_jshopping/shippingform/shippingform.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/shMailer.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/offer_and_order/checkout_offer_and_order.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/extrascoupon/checkout_extrascoupon_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/extrascoupon/order_extrascoupon_mambot.php';

class JshoppingControllerQCheckout extends JshoppingControllerBase
{
    const ADDRESS_STEP_CODE = 2;
    const PAYMENT_METHOD_STEP_CODE = 3;
    const DELIVERY_METHOD_STEP_CODE = 4;
    const CONFIRM_STEP_CODE = 5;
    const PREPARE_PAYMENT_DATA_STEP_CODE = 6;
    const RETURN_TO_SHOP_STEP_CODE = 7;
    const FINISH_STEP_CODE = 10;

    public $cartName = 'cart';
    protected $isChangePayment = true;

    public function __construct($config = [])
    {        
        parent::__construct($config);
        JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_jshopping/models');
		setSeoMetaData();
    }

    public function display($cachable = false, $urlparams = false)
    {
        JPluginHelper::importPlugin('jshoppingcheckout');

        $doc = JFactory::getDocument();
        $shopUser = JSFactory::getUser();
        $dispatcher = \JFactory::getApplication();
        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();
        $checkout = JModelLegacy::getInstance('checkout', 'jshop');
        $checkout->checkStep(self::ADDRESS_STEP_CODE);
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $isUserAuthorized = (!empty($shopUser->user_id) && $shopUser->user_id != -1);
        $countOfUserAddresses = ($isUserAuthorized) ? $modelOfUserAddressesFront->getCountByUserId($shopUser->user_id) : 0;
        $dispatcher->triggerEvent('onLoadCheckoutStep2', []);
        $checkLogin = JFactory::getApplication()->input->getInt('check_login');
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $flashData = JSFactory::getFlashData();
        $flashOrderData = $flashData->get('guestOrderData');
		$session->clear('all_shipping_prices');
        if ($checkLogin) {
            $session->set('show_pay_without_reg', 1);
            checkUserLogin($ajax);
        }

		checkUserBlock();
		checkUserCreditLimit();
        appendPathWay(JText::_('COM_SMARTSHOP_CHECKOUT'));
        $seodata = JTable::getInstance('seo', 'jshop')->loadData('checkout-address');
		if(isset($seodata->title) && $seodata->title){
			$seoTitle = $seodata->title;
		}else{
			$seoTitle = JText::_('COM_SMARTSHOP_CHECKOUT');
		}
		setSeoMetaData($seoTitle);
        
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load($this->cartName);
        $cart->getSum();
        $cart->setDisplayData('checkout');
        $this->checkUploadsAndElseRedirect($cart);

        $modelOfUsersFront = JSFactory::getModel('UsersFront');
        //address
		$shipping_addr_id = $cart->getShippingAddressId();

		if($shipping_addr_id){
			$shAddress = $modelOfUserAddressesFront->getById($shipping_addr_id);
			//$shAddress->country = $shAddress->country ? JSFactory::getModel('countriesFront')->getById($shAddress->country)->name : JSFactory::getModel('countriesFront')->getById($jshopConfig->default_country)->name;
		}
        $configFields = $modelOfUsersFront->getListFields(2);
        
        if ($configFields['birthday']['display']) {
            if (method_exists('JHtmlBehavior', 'calendar')) {
                JHtmlBehavior::calendar();
            }
        }
        
        $shopUser->country = $shopUser->country ?: $jshopConfig->default_country;
        $shopUser->birthday = getDisplayDate($shopUser->birthday, $jshopConfig->field_birthday_format);

        $modelOfCountries = JSFactory::getModel('CountriesFront');

        $countriesSelectMarkup = $modelOfCountries->generateCountriesSelectMarkup($flashOrderData['country'] ??  $shopUser->country);
        $clientTitles = $modelOfUsersFront->generateClientTitlesSelectMarkup($flashOrderData['title'] ?? $shopUser->title);
        $markupOfClientTypes = $modelOfUsersFront->generateClientTypesSelectMarkup($flashOrderData['client_type'] ?? $shopUser->client_type);
		$markupOfDClientTypes = $modelOfUsersFront->generateClientTypesSelectDMarkup($flashOrderData['d_client_type'] ?? $shopUser->d_client_type ?? 0);

        filterHTMLSafe($shopUser, ENT_QUOTES);
        
        loadJSLanguageKeys();

        $view = $this->getView('quick_checkout', getDocumentType(), '', [
            'template_path' => viewOverride('quick_checkout', 'default.php')
        ]);
        $layout = getLayoutName('quick_checkout', 'default');
        $view->setLayout($layout);

		$configDFields = $modelOfUsersFront->getListFields(2);

        $dataOfDefaultBillAddress = new stdClass;
        if ($isUserAuthorized) {
            $dataOfDefaultBillAddress = $modelOfUserAddressesFront->getDataOfDefaultBillAddress($shopUser->user_id);
        }

        $view->set('dataOfDefaultBillAddress', $dataOfDefaultBillAddress);
        $view->set('select', $jshopConfig->user_field_title);
        $view->set('usersParams', JComponentHelper::getParams('com_users'));
        $view->set('currentUser', JFactory::getUser());
        $view->set('select_countries', $countriesSelectMarkup->selectCountries);
         $view->set('select_d_countries', $countriesSelectMarkup->selectDCountries);
        $view->set('select_titles', $clientTitles->selectTitles);
        $view->set('select_d_titles', $clientTitles->selectDTitles);
        $view->set('select_client_types', $markupOfClientTypes);
        $view->set('select_d_client_types', $markupOfDClientTypes);
        $view->set('live_path', JURI::base());
        $view->set('config_fields', $configFields);
        $view->set('config_dfields', $configDFields);
        $view->set('user', $shopUser);
        $view->set('sef', JFactory::getConfig()->get('sef'));
		if($shipping_addr_id){			
			$shAddress->country = $modelOfCountries->getById($shAddress->country)->name ?: $shAddress->country;
 			$view->set('d_address', $shAddress);
		}
		loadingStatesScripts();
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep2View', [&$view]);
        // end address
        
        if ($jshopConfig->step_4_3) {
            $this->deliveryMethodStep($dispatcher, $jshopConfig, $cart, $view, $shopUser);
            $this->paymentStep($dispatcher, $jshopConfig, $cart, $view, $shopUser);
        } else {
            $this->paymentStep($dispatcher, $jshopConfig, $cart, $view, $shopUser);
            $this->deliveryMethodStep($dispatcher, $jshopConfig, $cart, $view, $shopUser);
        }
        
        $delivery = JSFactory::getModel('DeliveryTimesFront')->getByCart($cart, $jshopConfig);
        $smallCartMarkup = JSFactory::getModel('cart', 'jshop')->renderSmallCart();

        if($ajax){
            JSFactory::getModel('cart', 'jshop')->viewSmallCart($view);
        }
        //preview finish
        $dispatcher->triggerEvent('onLoadCheckoutStep5', []);
        $sh_method = $pm_method = $delivery_info = null;

        $isNoReturn = 0;
        if ($jshopConfig->no_return_all) {
            $isNoReturn = 1;
        } else {
            $view->set('products_return', $cart->isExistsAnyProductWithNoReturn());
        }
        $cart->saveToSession();
        $requiredConfigFields = $jshopConfig->buildArrayWithFieldsRegisterForJS('address', 2);

        $dataToFrontJs = [
            'jshopConfig' => $jshopConfig,
            'live_path' => JURI::base(),
            'no_return' => $isNoReturn,
            'qc_ajax_link' => '/index.php?option=com_jshopping&controller=qcheckout&task=ajaxRefresh',
            'qc_ajax_reload_link' => '/index.php?option=com_jshopping&controller=qcheckout&task=reloadShippingPrice',
            'register_field_require' => $requiredConfigFields,
            'payment_type_check' => [],
            'payment_class' => ''
        ];

        if (!empty($view->payment_methods)) {
            foreach ($view->payment_methods as $paymentObj) {
                $dataToFrontJs['payment_type_check'][$paymentObj->payment_class] = $paymentObj->existentcheckform;

                if (isset($view->active_payment) && $paymentObj->payment_id == $view->active_payment) {
                    $dataToFrontJs['payment_class'] = $paymentObj->payment_class;
                }
            }
        }else{
	        if ($jshopConfig->without_payment) {
            $cart->setPaymentId(0);
            $cart->setPaymentParams('');
            $cart->setPaymentPrice(0);
            $view->assign('payment_step', 0);
        } else {
            $modelOfQCheckout = JSFactory::getModel('QCheckout');
            $post = [];
			$adv_user = JSFactory::getUserShop(false);
            $loadedPayment = JSFactory::getModel('PaymentsFront')->getPreparedPayments($adv_user, $jshopConfig, $cart);
            $paym = $loadedPayment['payments'];
            $firstPayment = &$paym['0'];
            $activePayment = $loadedPayment['active_payment'];
            
            if ($jshopConfig->hide_payment_step) {//echo "<pre>";print_r($firstPayment);die("dds");
                if (!$firstPayment->payment_class) {
                    $modelOfQCheckout->setSessionError(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'));
                    $firstPayment->payment_class = '';
                }
				$view->payment_methods=$firstPayment->payment_id;
				$this->setPayment($post, $cart, JSFactory::getModel('PaymentsFront')->getPaymentClassForPaymentsArray($paym, $activePayment), null, $adv_user);
				$view->assign('payment_step', 1);
                $view->assign('payment_methods', $paym);
                $view->assign('active_payment', $activePayment);
			}
		}
			
		}

        $ac_paym_method = $checkout->getActivePaymMethod($view->active_payment, $view->payment_methods);
		$captcha = $modelOfUsersFront->getCaptchaData();	

        $doc->addScriptOptions('qCheckout', $dataToFrontJs);
        $doc->addScriptOptions('isAgbEnabled', (bool)$jshopConfig->display_agb);

        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep5', [&$sh_method, &$pm_method, &$delivery_info, &$cart, &$view]);
        $view->set('no_return', $isNoReturn);
        $view->set('delivery_time', $delivery['delivery_time']);
        $view->set('delivery_date', $delivery['delivery_date']);
        $view->set('small_cart', $smallCartMarkup);
        $view->set('jshopConfig', $jshopConfig);
        $view->set('config', $jshopConfig);
        $view->set('action', SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=save', 0, 0, $jshopConfig->use_ssl));
        $view->set('qc_error', $session->get('qc_error'));
        $view->set('session', $session);
        $view->set('clientTypeId', $shopUser->client_type);
        $view->set('dclientTypeId', $shopUser->d_client_type ?? 0);
        $view->set('countOfUserAddresses', $countOfUserAddresses);
        $view->set('isUserAuthorized', $isUserAuthorized);
        $view->set('flashOrderData', $flashOrderData);
        $view->set('ac_paym_method', $ac_paym_method);
        $view->set('captcha', $captcha);
        $view->set('allowUserRegistration', JComponentHelper::getParams('com_users')->get('allowUserRegistration'));
        $clientTitlesOptions = $modelOfUsersFront->generateClientTitlesOptionsMarkup();
        $clientTypesOptions = $modelOfUsersFront->generateClientTypesOptionsMarkup($flashOrderData['title'] ?? $shopUser->title);
        if($captcha){
            $view->set('captchaHtml', $captcha->display('jshopping_captcha', 'jshopping_captcha', 'jshopping_captcha'));
        }
        $view->set('clientTitlesOptions', $clientTitlesOptions);
        $view->set('clientTypesOptions', $clientTypesOptions);
        $view->set('calendarField', (JHTML::_('calendar', $flashOrderData['birthday'] ?? $shopUser->birthday, 'birthday', 'birthday', $this->config->field_birthday_format ?? '', ['class' => 'input', 'size' => '25', 'maxlength' => '19'])));
        $view->set('onSubmitForm', 'return shopQuickCheckout.onSubmitForm(this, ' . (int)$isUserAuthorized . ');');
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep5View', [&$view]);
        CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep5View($view);
		
        $shopUser->country = $shopUser->country ?: $jshopConfig->default_country;
		$shopUser->country = $modelOfCountries->getById($shopUser->country)->name ?: $shopUser->country;
        $view->set('sprintpreviewnativeuploadedfiles_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintPreviewNativeUploadedFiles', 1));
        $view->set('allowUserRegistration', JComponentHelper::getParams('com_users')->get('allowUserRegistration'));
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('component', 'Default_quick_checkout');
        $view->set('dataToFrontJs', $dataToFrontJs);
        $view->set('jsConfigFields', $requiredConfigFields);
        $view->set('finishPage',  SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 1));

        $doc->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));

        if($ajax){
            print_r(json_encode(prepareView($view)));die;
        }

        $view->display();
    }

    protected function checkUploadsAndElseRedirect(&$cart)
    {
        $linkToCart = SEFLink('/index.php?option=com_jshopping&controller=cart&task=view', 0, 1, JSFactory::getConfig()->use_ssl);
        $uploadModel = JSFactory::getModel('upload');
        $uploadErrors = [];

        $cart->setDisplayData('checkout');
		//print_r($cart->products);die;
        foreach($cart->products as $val) {
           	if (isset($val['product_id_with_support_upload']) && !empty($val['product_id_with_support_upload'])) {
                $cleanedUploadData = $uploadModel->getCleanedArrWithUploadData($val['uploadData'] ?? []);
                $countFiles = (!empty($cleanedUploadData['files'])) ? count($cleanedUploadData['files']) : 0;
				$isProductPassedRequired = $uploadModel->isProductPassedRequired($val['product_id'], $countFiles, 'cart', $val);

                if (!$isProductPassedRequired && !empty($uploadModel->getErrors())) {
                    $uploadErrors = array_merge($uploadErrors, $uploadModel->getErrors());
                    $uploadModel->clearErrors();
                }
            }
        }

        if (!empty($uploadErrors)) {
            return redirectMsgsWithOneTypeStatus($uploadErrors, $linkToCart, 'error');
        }

        $isValidUploadedFiles = $uploadModel->cleanedAndIsValidProductsUploadedFiles($cart->products, true);

        if (!$isValidUploadedFiles) {
            return JFactory::getApplication()->redirect($linkToCart);
        }
    }

    private function paymentStep(&$dispatcher, jshopConfig &$jshopConfig, jshopCart &$cart, &$view, &$adv_user)
    {
        $dispatcher->triggerEvent('onLoadCheckoutStep3', []);
        
        if ($jshopConfig->without_payment) {
            $cart->setPaymentId(0);
            $cart->setPaymentParams('');
            $cart->setPaymentPrice(0);
            $view->set('payment_step', 0);
        } else {
            $modelOfQCheckout = JSFactory::getModel('QCheckout');
            $post = [];
            $loadedPayment = JSFactory::getModel('PaymentsFront')->getPreparedPayments($adv_user, $jshopConfig, $cart);
            $paym = $loadedPayment['payments'];
            $firstPayment = &$paym['0'];
            $activePayment = $loadedPayment['active_payment'];
            
            if ($jshopConfig->hide_payment_step) {
                if (!$firstPayment->payment_class) {
                    $modelOfQCheckout->setSessionError(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'));
                    $firstPayment->payment_class = '';
                }

                $this->setPayment($post, $cart, $firstPayment->payment_class, null, $adv_user);
                $view->set('payment_step', 0);
                $view->set('active_payment', $firstPayment->payment_id);
                $view->set('active_payment_name', $firstPayment->name);
                $view->set('active_payment_class', $firstPayment->payment_class);
            } else {
                $this->setPayment($post, $cart, JSFactory::getModel('PaymentsFront')->getPaymentClassForPaymentsArray($paym, $activePayment), null, $adv_user);

                $this->transformPaymentsDescrsTextsToModule($paym);

                $view->set('payment_step', 1);
                $view->set('payment_methods', $paym);
                $view->set('active_payment', $activePayment);
				//Product shipping
				$modelOfShippingExtCalc = JSFactory::getModel('ShippingExtCalc');
                $shippingExtCalcSmProduct = $modelOfShippingExtCalc->getByAliasName('sm_product');
                $params = [];

				if (!empty($shippingExtCalcSmProduct->params)) {
					$params = unserialize($shippingExtCalcSmProduct->params);
                }
                
				if (isset($params['payment_filter']) && $params['payment_filter'] == 1) {
                    $shopUser = JSFactory::getUser();
                    $countryId = $shopUser->country ?: $jshopConfig->default_country;
                    $modelOfShippingMethod = JSFactory::getModel('ShippingMethod');             
                    $productsIds = getListOfValuesByArrKey($cart->products, 'product_id');

                    $modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
                    $listOfProductShippings = $modelOfProductsShipping->getByProductsIds($productsIds);
                         
                    $productsdata = [];
                    foreach($listOfProductShippings as $k => $productShipping) {
                        $productsdata[$productShipping->product_id][] = $productShipping;
                    }
                    
                    $_listsh = $modelOfShippingMethod->getAdditionalDataByCountryId($countryId, ['sh_pr_method.sh_pr_method_id', 'sh_pr_method.payments']);
		
                    $listsh = $shfilter = [];
                    foreach($_listsh as $v) {
                        $listsh[$v->sh_pr_method_id] = $v->payments;  
                        $shfilter[] = $v->sh_pr_method_id;  
                    }
                                    
                    foreach($productsdata as $v) {
                        $filter = [];

                        foreach($v as $v2) {
                            $filter[$v2->sh_pr_method_id] = $v2->published;
                        }            
                        
                        foreach($shfilter as $k3 => $v3) {
                            if (isset($filter[$v3]) && $filter[$v3] == 0) {
                                unset($shfilter[$k3]);
                            }
                        }
                    }
                    
                    $enablshipping = [];
                    foreach ($shfilter as $id) {
                        $enablshipping[$id] = $listsh[$id];
                    }

                    $allpayments = $view->payment_methods;
                    foreach ($view->payment_methods as $k => $v) {
                        $en = 0;

                        foreach($enablshipping as $pids) {
                            if ($pids == '') {
                                $en = 1;
                            } else {
                                $tmp = explode(',', $pids);
                                if (in_array($v->payment_id, $tmp)) {
                                    $en = 1;
                                }
                            }
                        }

                        if (!$en) {
                            unset($view->payment_methods[$k]);
                        }
                    }
                    
                    if ($params['priority_shipping'] && empty($view->payment_methods)) {
                        $view->payment_methods = $allpayments;
                        
                        $shippingPayments = $modelOfShippingMethod->getByShippingId(1, ['payments']);

                        if (empty($shippingPayments->payments)) {
                            return 1;
                        } 

                        $payments = explode(',', $shippingPayments->payments);

                        foreach($view->payment_methods as $k => $v) {
                            if (!in_array($v->payment_id, $payments)) {
                                unset($view->payment_methods[$k]);
                            }
                        }
                    }
				}
                //////////////////
                $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep3View', [&$view]);
                CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep3View($view);
            }
        
        }
    }
    
    private function deliveryMethodStep(&$dispatcher, jshopConfig &$jshopConfig, jshopCart &$cart, &$view, &$adv_user)
    {
        $dispatcher->triggerEvent('onLoadCheckoutStep4', []);

        if ($jshopConfig->without_shipping) {
            $cart->setShippingId(0);
            $cart->setShippingPrice(0);
            $cart->setPackagePrice(0);
            $view->set('delivery_step', 0);
        } else {
            $modelOfQCheckout = JSFactory::getModel('QCheckout');
			$shippingAddressId = $cart->getShippingAddressId();
			if($shippingAddressId){
				$modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
				$billingAddressId = $modelOfUserAddressesFront->getDataOfDefaultAddress($adv_user->user_id)->address_id;
     			$modelOfUserAddressesFront->setAsDefault($shippingAddressId, $adv_user->user_id);
				$adv_user = JSFactory::getUserShop(false);
			}
			$id_country =  $adv_user->country;

            if (!isset($id_country) || !$id_country) {
                $id_country = $jshopConfig->default_country;
                $modelOfQCheckout->setSessionError(JText::_('COM_SMARTSHOP_REGWARN_COUNTRY'));
            }

			$firstShipping = new stdClass();
            $load_shippings = JSFactory::getModel('ShippingsFront')->getPreparedShippings($adv_user, $id_country, $jshopConfig, $cart);
            if($load_shippings){
				$shippings = $load_shippings['shippings'];
				$firstShipping = &$shippings[array_key_first($shippings)];
				$active_shipping = $load_shippings['active_shipping'];
			}
    
            // if (!$jshopConfig->hide_shipping_step) {
            //     $this->_setShipping($cart, $adv_user, $id_country, $active_shipping, $jshopConfig, null);
            // }
            
            if ($jshopConfig->hide_shipping_step || !$load_shippings) {
                if ((!isset($firstShipping->sh_pr_method_id) || !$firstShipping->sh_pr_method_id) && ($jshopConfig->hide_payment_step || $jshopConfig->without_payment)) {
                    $modelOfQCheckout->setSessionError(JText::_('COM_SMARTSHOP_ERROR_SHIPPING'));
                }

                $firstShipping->sh_pr_method_id = $firstShipping->sh_pr_method_id ?? '';
                $this->_setShipping($cart, $adv_user, $id_country, $active_shipping ?? 0, $jshopConfig, null, 0, $shippings ?? []);
                $view->set('delivery_step', 0);
                $view->set('active_shipping', $firstShipping->sh_pr_method_id);
                $view->set('active_shipping_name', $firstShipping->name ?? '');
                $view->set('active_sh_pr_method_id', $firstShipping->sh_pr_method_id);
            } else {
                transformDescrsTextsToModule($shippings);

                $view->set('delivery_step', 1);
                $view->set('shipping_methods', $shippings);
                $view->set('active_shipping', $active_shipping);

                $modelOfQCheckout->prepareViewProductShipping($view);
                $this->_setShipping($cart, $adv_user, $id_country, $view->active_shipping, $jshopConfig, null, 0, $shippings);

                $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep4View', [&$view]);
                CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep4View($view);

				if(!empty($shippings)){
					foreach($shippings as $key=>$val){
						if($val->calculeprice){
							$shipping_stand_price = $val->shipping_stand_price;
							$package_stand_price = $val->calculeprice - $val->shipping_stand_price;
						}else{
							$shipping_stand_price = $val->shipping_stand_price;
							$package_stand_price = $val->package_stand_price;
							
						}
						JFactory::getSession()->set('shipping_stand_price_'.$val->sh_pr_method_id, $shipping_stand_price);
						JFactory::getSession()->set('package_stand_price_'.$val->sh_pr_method_id, $package_stand_price);						
					}
				}
            }
			if(isset($billingAddressId) && $billingAddressId){
				$modelOfUserAddressesFront->setAsDefault($billingAddressId, $adv_user->user_id);
				$adv_user = JSFactory::getUserShop(false);
			}

        }
    }

    protected function checkDataFromShippingSection(array &$post, &$adv_user, jshopConfig &$jshopConfig, array &$data, jshopCart &$cart, &$dispatcher)
    {
        $currentObj = $this;
        $modelOfQCheckout = JSFactory::getModel('QCheckout');
        $params = [];
		if(isset($adv_user->d_country) && $adv_user->d_country){
			$id_country = $adv_user->d_country;
		}else{
			$id_country = (isset($adv_user->country) && $adv_user->country) ? $adv_user->country : $jshopConfig->default_country;
		}		

//        if (empty($post['sh_pr_method_id']) || empty($post['reload'])) {
            $shippings = JSFactory::getModel('ShippingsFront')->getPreparedShippings($adv_user, $id_country, $jshopConfig, $cart);

            if (empty($post['sh_pr_method_id'])) {
                $post['sh_pr_method_id'] = $jshopConfig->hide_shipping_step ? $shippings['shippings'][array_key_first($shippings['shippings'])]->sh_pr_method_id : $shippings['active_shipping'];
            }
//        }

        if (!empty($post['params'])) {
            foreach ($post['params'] as $param) {
                if (isset($param['name'])) {
                    $index = $modelOfQCheckout->parseParamsName($param['name']);
                    $params[$index['1']][$index['2']] = $param['value'];
                }
            }
        }
        
        if (empty($id_country)) {
            if (!in_array(JText::_('COM_SMARTSHOP_REGWARN_COUNTRY'), $data['error'])) {
                $data['error'][] = JText::_('COM_SMARTSHOP_REGWARN_COUNTRY');
            }
        }
        $data['reload'] = isset($post['reload']) ? $post['reload'] : 0;

        $setShipping = $this->_setShipping($cart, $adv_user, $id_country, $post['sh_pr_method_id'], $jshopConfig, $params, 1, $shippings['shippings']);
        if (!empty($setShipping) && isset($setShipping['error']) && $setShipping['error'] == 1) {
            if (!in_array($setShipping['msg'], $data['error'])) {
                $data['error'][] = $setShipping['msg'];
            }
        }
		if($shippings){
			foreach($shippings['shippings'] as $val){
				if($val->calculeprice){
					$shipping_stand_price = $val->shipping_stand_price;
					$package_stand_price = $val->calculeprice - $val->shipping_stand_price;
				}else{
					$shipping_stand_price = $val->shipping_stand_price;
					$package_stand_price = $val->package_stand_price;
					
				}
				JFactory::getSession()->set('shipping_stand_price_'.$val->sh_pr_method_id, $shipping_stand_price);
				JFactory::getSession()->set('package_stand_price_'.$val->sh_pr_method_id, $package_stand_price);
			}
		}
		if (!isset($post['reload']) || !$post['reload'] || $post['reload'] == 0) { 
			if($shippings){
				transformDescrsTextsToModule($shippings['shippings']);

				$this->shipping_methods = $shippings['shippings'];
				$this->active_shipping = $shippings['active_shipping'];
				
				$modelOfQCheckout->prepareViewProductShipping($this);
				$dispatcher->triggerEvent('onBeforeDisplayCheckoutStep4View', [&$currentObj]);
				CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep4View($this);

				ob_start();
				include  templateOverrideBlock('blocks', 'shippings.php');
				$data['shippings1'] = ob_get_contents();
				ob_end_clean(); 
			}
		}
        //get payments
		$d = $data;
		$this->checkRequiredFields($post, $d);				
        $d = $this->additionDataForAjax($d, $adv_user);
        
		// if (empty($d['error'])) {
        //     $this->ajaxLoadPayments($jshopConfig, $dispatcher, $adv_user, $cart, $post, $data);  
        // }
			          
        $delivery = JSFactory::getModel('DeliveryTimesFront')->getByCart($cart, $jshopConfig);
        $data['delivery_time'] = $delivery['delivery_time'];
        $data['delivery_date'] = $delivery['delivery_date'];        
    }
    
    private function ajaxLoadPayments(&$jshopConfig, &$dispatcher, &$adv_user, &$cart, &$post, &$data)
    {
        if (!$jshopConfig->without_payment) { //&& $this->isChangePayment
            $currentObj = $this;
            $dispatcher->triggerEvent('onLoadCheckoutStep3', []);

            if ($jshopConfig->hide_payment_step) {
                $payments = JSFactory::getModel('PaymentsFront')->getPreparedPayments($adv_user, $jshopConfig, $cart);
                
                if (!$payments['payments']['0']->payment_class) {
                    $data['error'][] = JText::_('COM_SMARTSHOP_ERROR_PAYMENT');
                }

                $setPayment = $this->setPayment($post, $cart, $payments['payments']['0']->payment_class, null, $adv_user, 1);
                $data['active_payment_class'] = $payments['payments']['0']->payment_class;
            } else {

				$setPayment = $this->setPayment($post, $cart, $post['payment_id'], null, $adv_user, 1);
				$payments = JSFactory::getModel('PaymentsFront')->getPreparedPayments($adv_user, $jshopConfig, $cart);
                $modelOfShippingExtCalc = JSFactory::getModel('ShippingExtCalc');
                $this->transformPaymentsDescrsTextsToModule($payments['payments']);
				
                $this->payment_methods = $payments['payments'];
                $this->active_payment = $payments['active_payment'];
                //Product shipping	
                $params = [];			
				$row = $modelOfShippingExtCalc->getByAliasName('sm_product');
				if ($row->params) {
					$params = unserialize($row->params);
                }
                
				if ($params['payment_filter'] == 1) {
                    
                    $cart = JModelLegacy::getInstance('cart', 'jshop');
                    $cart->load($this->cartName);
                    
                    $adv_user = JSFactory::getUser();
                    
                    $id_country = $adv_user->country ?: $jshopConfig->default_country;    
                            
                    $products = [];
                    foreach ($cart->products as $product) {
                        $products[] = $product['product_id'];
                    }
                    
                    $modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
                    $list = $modelOfProductsShipping->getByProductsIds($products);  
                    
                    $productsdata = [];
                    foreach ($list as $k => $v) {
                        $productsdata[$v->product_id][] = $v;
                    }
                    $shid_country = $adv_user->d_country ?: $adv_user->country ?: $jshopConfig->default_country;
                    $modelOfShippingMethod = JSFactory::getModel('ShippingMethod');
                    $_listsh = $modelOfShippingMethod->getAdditionalDataByCountryId($shid_country, ['sh_pr_method.sh_pr_method_id', 'sh_pr_method.payments']);
                    $listsh = [];

                    foreach($_listsh as $v) {
                        $listsh[$v->sh_pr_method_id] = $v->payments;    
                    }
                    
                    $shfilter = [];
                    foreach ($listsh as $k => $v) {
                        $shfilter[] = $k;
                    }
                    
                    foreach ($productsdata as $v) {
                        $filter = [];

                        foreach ($v as $v2) {
                            $filter[$v2->sh_pr_method_id] = $v2->published;
                        }            
                        
                        foreach ($shfilter as $k3 => $v3) {
                            if (isset($filter[$v3]) && $filter[$v3] == 0) {
                                unset($shfilter[$k3]);
                            }
                        }
                    }
                    
                    $enablshipping = [];
                    foreach ($shfilter as $id) {
                        $enablshipping[$id] = $listsh[$id];
                    }
                    $allpayments = $this->payment_methods;
                    foreach ($this->payment_methods as $k => $v) {
                        $en = 0;
                        foreach ($enablshipping as $pids) {
                            if ($pids == '') {
                                $en = 1;
                            } else {
                                $tmp = explode(',', $pids);

                                if (in_array($v->payment_id, $tmp)) {
                                    $en = 1;
                                }
                            }
                        }
                        
                        if (!$en) {
                            unset($this->payment_methods[$k]);
                        }
                    }
                    
                    if ($params['priority_shipping'] && empty($this->payment_methods)) {
                        $this->payment_methods = $allpayments;
                        $strpayment = $modelOfShippingMethod->getByShippingId(intval($params['priority_shipping']), ['payments'])->payments;
                        
                        if ($strpayment == '') {
                            return 1;
                        } else {
                            $payments = explode(',', $strpayment);

                            foreach($this->payment_methods as $k => $v) {
                                if (!in_array($v->payment_id, $payments)) {
                                    unset($this->payment_methods[$k]);
                                }
                            }
                        }
                    }
				}
                //////////////////
                $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep3View', [&$currentObj]);
                CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep3View($this);
                

				ob_start();
				include  templateOverrideBlock('blocks', 'payments.php');
				 $data['payments'] = ob_get_contents();
				ob_end_clean(); 
                $setPayment = $this->setPayment($post, $cart, JSFactory::getModel('PaymentsFront')->getPaymentClassForPaymentsArray($payments['payments'], $payments['active_payment']), null, $adv_user, 1);
            }
            
            if (!empty($setPayment) && $setPayment['error'] == 1) {
                if (!in_array($setPayment['msg'], $data['error'])) {
                    $data['error'][] = $setPayment['msg'];
                }
            }
        }
    }
    
    public function ajaxRefresh()
    {
        $jshopConfig = JSFactory::getConfig();
        $post = JFactory::getApplication()->input->post->getArray();
        $adv_user = JSFactory::getUser();
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $this->cartName = $cartName = (isset($post['cart_name']) && $post['cart_name']) ? $post['cart_name'] : 'cart';
        \JFactory::getApplication()->triggerEvent('onBeforeQCheckoutAjaxRefresh', [&$post, &$adv_user, &$cart, &$modelOfUserAddressesFront]);
		$session = JFactory::getSession();		
		$session->clear('all_shipping_prices');
		
		$cart->load($this->cartName);
        if (!empty($adv_user->user_id) && $adv_user->user_id >= 1) {
            if (!empty($post['billingAddress_id']) || !empty($post['shippingAddress_id'])) {
                $addressId = !empty($jshopConfig->tax_on_delivery_address) ? $post['billingAddress_id'] : $post['shippingAddress_id'];
				$shipping_addressId = !empty($jshopConfig->tax_on_delivery_address) ?  $post['shippingAddress_id'] : $post['billingAddress_id'] ;
				$cart->setShippingAddressId($shipping_addressId);
				
                if (isset($modelOfUserAddressesFront->getById($addressId)->address_id)) {
                    $modelOfUserAddressesFront->setAsDefault($addressId, $adv_user->user_id);
                    $adv_user = JSFactory::getUserShop(false);
                }
            }
        } elseif (!empty($post['country'])) {
            $adv_user->country = $post['country'];
			$adv_user->d_country = ($post['delivery_adress'] && $post['d_country']) ? $post['d_country'] : $post['country'];
			$adv_user->delivery_adress = $post['delivery_adress'];
            if($post['delivery_adress']){
                foreach($post as $k=>$val){
                    if(strpos($k, 'd_') !== false && strpos($k, 'd_') == 0){
                        $adv_user->$k = $val;
                    }
                }
            }
            $adv_user->store();
        }

		$cart->refreshCart();
        $data = [
            'error' => [],
            'post' => $post,
            'stype' => $post['type'],
			'jshopConfig' => $jshopConfig
        ];           
		
        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher = \JFactory::getApplication();

        $this->isChangePayment = (bool)$post['isChangedPayment'];
        $this->checkDataFromAdressSection($dispatcher, $data, $post, $cart, $jshopConfig, $adv_user);
        $this->checkDataFromShippingSection($post, $adv_user, $jshopConfig, $data, $cart, $dispatcher);

        $this->checkDataFromPaymentSection($post, $cart, $adv_user, $data, $jshopConfig, $dispatcher);
        $this->checkRequiredFields($post, $data);				

        $data = $this->additionDataForAjax($data, $adv_user);

        $data['previousSum'] = JFactory::getSession()->get('previousSum');
        JFactory::getSession()->set('previousSum', $data['currentFullSummWithoutFormatPrice']);
        $data['newViewSmallCart'] = JSFactory::getModel('cart', 'jshop')->renderSmallCart($this->cartName);

        \JFactory::getApplication()->triggerEvent('onAfterQCheckoutAjaxRefresh', [&$data]);
        echo json_encode($data);
        die;	
    }

    protected function checkRequiredFields(&$post, &$data)
    {
        if ( isset($post['password']) || isset($post['password2']) ) {

            if ( empty($post['password']) xor empty($post['password2']) ) {
                $data['error'][] = JText::_('COM_SMARTSHOP_QC_A_PASSWORD_FIELD');
            }

            if ( (!empty($post['password']) || !empty($post['password2'])) && empty($post['qcheckoutReadPrivacy']) ) {
                $data['error'][] = JText::_('COM_SMARTSHOP_QC_ACCOUNTCONFIRM_POLICY');
            }

            if ( !empty($post['password']) && !empty($post['password2']) ) {
                if ( $post['password'] != $post['password2'] ) {
                    $data['error'][] = JText::_('COM_SMARTSHOP_QC_ENTER_IDENT_PASSWD');
                }
            }              
        }

        if ( (isset($post['agb']) && $post['agb'] == 0) || (JSFactory::getConfig()->display_agb == 1 && !isset($post['agb'])) ) {
            $data['error'][] = JText::_('COM_SMARTSHOP_QC_CONFIRM_POLICY');
        }  
        
        if ((isset($post['no_return']) && $post['no_return'] == 0)) {
            $data['error'][] = JText::_('COM_SMARTSHOP_REGWARN_RETURN_POLICY');
        }    
    }

    protected function additionDataForAjax($data, &$adv_user)
    {
        if ( !empty($adv_user->arrWithCheckedErrors) ) {
            $data['error'] = array_merge($adv_user->arrWithCheckedErrors, $data['error']);
        }

        $this->getNewCartValues($data);

        if (!empty($data['error'])) {
            $data['error'] = implode('<br />', $data['error']);
        } else {
            unset($data['error']);
        }

        return $data;  
    }

	private function ajaxLoadShippings(&$jshopConfig, &$dispatcher, &$adv_user, &$data, &$cart, &$post = [])
    {
        if (!$jshopConfig->without_shipping) {
            $currentObj = $this;
            $dispatcher->triggerEvent('onLoadCheckoutStep4', []);
            if($post['shippingAddress_id']){
				$modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
				$modelOfUserAddressesFront->setAsDefault($post['shippingAddress_id'], $adv_user->user_id);
				$adv_user = JSFactory::getUserShop(false);
			}
			$id_country =  (isset($adv_user->d_country) && $adv_user->d_country) ? $adv_user->d_country : $adv_user->country;


            if (!$id_country) {
                $id_country = $jshopConfig->default_country;

                if (!in_array(JText::_('COM_SMARTSHOP_REGWARN_COUNTRY'), $data['error'])) {
                    $data['error'][] = JText::_('COM_SMARTSHOP_REGWARN_COUNTRY');
                }
            }

			if($post['sh_pr_method_id']){
                $cart->setShippingId($post['sh_pr_method_id']);
            }
            $shippings = JSFactory::getModel('ShippingsFront')->getPreparedShippings($adv_user, $id_country, $jshopConfig, $cart);
           //// print_r($cart->products);die;
            if ($jshopConfig->hide_shipping_step || !$shippings) {
				if(!empty($shippings)){
					$methodIdOfShippingPrice = $shippings['shippings'][array_key_first($shippings['shippings'])]->sh_pr_method_id;

					if (!$methodIdOfShippingPrice && ($jshopConfig->hide_payment_step || $jshopConfig->without_payment)) {
						$data['error'][] = JText::_('COM_SMARTSHOP_ERROR_SHIPPING');
					}

					$data['active_sh_pr_method_id'] = $methodIdOfShippingPrice;
				}
            } else {
                transformDescrsTextsToModule($shippings['shippings']);
                $this->shipping_methods = $shippings['shippings'];
                $this->active_shipping = $shippings['active_shipping'];

				
				
                JSFactory::getModel('QCheckout')->prepareViewProductShipping($this);
                $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep4View', [&$currentObj]);
                CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep4View($this);
				if(!empty($shippings['shippings'])){
					foreach($shippings['shippings'] as $key=>$val){
						if($val->calculeprice){
							$shipping_stand_price = $val->shipping_stand_price;
							$package_stand_price = $val->calculeprice - $val->shipping_stand_price;
						}else{
							$shipping_stand_price = $val->shipping_stand_price;
							$package_stand_price = $val->package_stand_price;
							
						}
						JFactory::getSession()->set('shipping_stand_price_'.$val->sh_pr_method_id, $shipping_stand_price);
						JFactory::getSession()->set('package_stand_price_'.$val->sh_pr_method_id, $package_stand_price);						
					}
				}
				ob_start();
				include  templateOverrideBlock('blocks', 'shippings.php');
				 $data['shippings'] = ob_get_contents();
				ob_end_clean(); 
                $methodIdOfShippingPrice = $shippings['active_shipping'];
            }

            $setShipping = $this->_setShipping($cart, $adv_user, $id_country, $methodIdOfShippingPrice, $jshopConfig, null, 1, $shippings['shippings']);
            if($post['billingAddress_id']){
				$modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
				$modelOfUserAddressesFront->setAsDefault($post['billingAddress_id'], $adv_user->user_id);
				$adv_user = JSFactory::getUserShop(false);
			}

            if (!empty($setShipping) && (isset($setShipping['error']) && $setShipping['error'] == 1)) {
                if (!in_array($setShipping['msg'], $data['error'])) {
                    $data['error'][] = $setShipping['msg'];
                }
            }
        }
    }    
    
    protected function checkDataFromAdressSection(&$dispatcher, &$data, &$post, &$cart, &$jshopConfig, &$adv_user)
    {
        $dispatcher->triggerEvent('onLoadCheckoutStep2save', []);
        $isAuthorizedUser = !empty($adv_user->user_id) && $adv_user->user_id != -1;
        //$this->saveAddress($cart, $adv_user, 1);

        if ($isAuthorizedUser) {
            if (empty($post['shippingAddress_id'])) {
                $data['error'][] = JText::_('COM_SMARTSHOP_ADDRESS_SELECT_SHIPPING_ADDRESS');
            } 
            
            if (empty($post['billingAddress_id'])) {
                $data['error'][] = JText::_('COM_SMARTSHOP_ADDRESS_SELECT_BILLING_ADDRESS');
            }
        } else {
            $tableOfUserAddress = JSFactory::getTable('UserAddress');
            $tableOfUserAddress->bind($post);
			JSFactory::getModel('UserAddressesFront')->reloadPaymentsOnAdressChanged($adv_user,$tableOfUserAddress);
            $checkoutAddressErrors = $tableOfUserAddress->check('address', 'checkout');

            if (!empty($checkoutAddressErrors['msgs'])) {
                $data['error'] = array_unique(array_merge($data['error'], $checkoutAddressErrors['msgs']));
            }
        }
        
        if ($jshopConfig->step_4_3) {
            $this->ajaxLoadShippings($jshopConfig, $dispatcher, $adv_user, $data, $cart, $post);
        }
			
        $this->additionDataForAjax($data, $adv_user);
        if ($post['isChangedPayment']) $this->ajaxLoadPayments($jshopConfig, $dispatcher, $adv_user, $cart, $post, $data);
    
        if (!$jshopConfig->step_4_3) {
            $this->ajaxLoadShippings($jshopConfig, $dispatcher, $adv_user, $data, $cart, $post);
        }
        
        $delivery = JSFactory::getModel('DeliveryTimesFront')->getByCart($cart, $jshopConfig);
        $data['delivery_time'] = $delivery['delivery_time'];
        $data['delivery_date'] = $delivery['delivery_date'];        
    }

    protected function checkDataFromPaymentSection(&$post, &$cart, &$adv_user, &$data, &$jshopConfig, &$dispatcher)
    {
        $params = [];

        if (!empty($post['params'])) {
            foreach ($post['params'] as $param) {
                if (isset($param['name'])) {
                    $index = JSFactory::getModel('QCheckout')->parseParamsName($param['name']);
                    $params[$index['1']][$index['2']] = $param['value'];
                }
            }
        }

        if (!empty($jshopConfig->hide_payment_step)) {
            $payments = JSFactory::getModel('PaymentsFront')->getPreparedPayments($adv_user, $jshopConfig, $cart);
            $post['payment_method'] = $payments['payments']['0']->payment_class;
        }

        $set_payment = $this->setPayment($post, $cart, $post['payment_method'], $params, $adv_user, 1);
        if (!empty($set_payment) && isset($set_payment['error']) && $set_payment['error'] == 1) {
            if (!in_array($set_payment['msg'], $data['error'])) {
                $data['error'][] = $set_payment['msg'];
            }
        }
        
        //get shippings
        $this->ajaxLoadShippings($jshopConfig, $dispatcher, $adv_user, $data, $cart, $post);
        
        $delivery = JSFactory::getModel('DeliveryTimesFront')->getByCart($cart, $jshopConfig);
        $data['delivery_time'] = $delivery['delivery_time'];
        $data['delivery_date'] = $delivery['delivery_date'];        
    }

    private function getNewCartValues(&$cart_data)
    {
        $jshopConfig = JSFactory::getConfig();
		$dispatcher = \JFactory::getApplication();
        $isIncludeShipping = 0;
        
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load($this->cartName);
        $cart->updateDiscountData();

        $cart_data['discount'] = [
            'price' => $cart->getDiscountShow(), 
            'formatprice' => formatprice($cart->getDiscountShow())
        ];

        $cart_data['price_product'] = formatprice($cart->price_product);
        $cart_data['free_discount'] = [
            'price' => $cart->getFreeDiscount(), 
            'formatprice' => formatprice($cart->getFreeDiscount())
        ];

        if (!$jshopConfig->without_shipping) {
			
            $cart_data['summ_delivery'] = formatprice($cart->getShippingPrice());
            $cartPackagePrice = $cart->getPackagePrice();

            if ($cartPackagePrice > 0 || $jshopConfig->display_null_package_price) {
                $cart_data['summ_package'] = formatprice($cartPackagePrice);
            }
			
            $isIncludeShipping = 1;
        }

        $cartPaymentPrice = $cart->getPaymentPrice();
        $cart_data['summ_payment'] = [
            'price' => $cartPaymentPrice, 
            'formatprice' => formatprice($cartPaymentPrice)
        ];

        $cart->setDisplayItem(1, 1);
        $fullsumm = $cart->getSum($isIncludeShipping, 1, 1);
        if(!$jshopConfig->hide_tax) $tax_list = $cart->getTaxExt($isIncludeShipping, 1, 1);

        $name = JSFactory::getLang()->get('name');
        $pm_method = JTable::getInstance('paymentMethod', 'jshop');
        $pm_method->load($cart->getPaymentId());
        $cart_data['payment_name'] = $pm_method->$name;
        
        $new_tax_list = [];
		$new_tax_list_name = [];
        if (!empty($tax_list)) {
            foreach ($tax_list as $percent => $tax) {
				if ($tax>0){
					if ((double)$percent==0) {
						$tmp=explode('_',substr($percent,15,strlen($percent)));
						$percent=$tmp[1];	
						$new_tax_list[formattax($percent)." "] = formatprice($tax);
						$new_tax_list_name[] = displayTotalCartTax().JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name." ";
					}else{
						$new_tax_list[formattax($percent)." "] = formatprice($tax);
						$new_tax_list_name[] = displayTotalCartTaxName();
					}
				}
            }
			
        }
		
		$dispatcher->triggerEvent('onNewCartValuesAfterTaxCalc', [&$tax_list,&$new_tax_list,&$new_tax_list_name]);		
		
        $cart_data['tax_list_name'] = $new_tax_list_name;
        if(!$jshopConfig->hide_tax) $cart_data['tax_list'] = $new_tax_list; else $cart_data['tax_list'] =[];
        $cart_data['fullsumm'] = formatprice($fullsumm);
        $cart_data['currentFullSummWithoutFormatPrice'] = $fullsumm;
        
        $sh_method = JTable::getInstance('shippingMethod', 'jshop');
        $sh_method->load($cart->getShippingPrId());
        $cart_data['shipping_name'] = $sh_method->$name;
    }    
            
    private function savePayment(&$jshopConfig, &$checkout, &$cart, &$adv_user)
    {
        $maxStep = self::DELIVERY_METHOD_STEP_CODE;

        if ($jshopConfig->without_payment) {
            $checkout->setMaxStep($maxStep);
        } else {
            $checkout->checkStep(self::PAYMENT_METHOD_STEP_CODE);
            $payment_method = JFactory::getApplication()->input->getVar('payment_method');
            $params = JFactory::getApplication()->input->getVar('params');
            $post = JFactory::getApplication()->input->post->getArray();

            if (!$this->setPayment($post, $cart, $payment_method, $params, $adv_user)) {
                return JFactory::getApplication()->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout', 0, 1, $jshopConfig->use_ssl));
            }
            
            if ($jshopConfig->step_4_3) {
                $maxStep = self::CONFIRM_STEP_CODE;
            }

            return $checkout->setMaxStep($maxStep);
        }
    }
    
    private function saveShipping(&$jshopConfig, &$checkout, &$adv_user, &$cart)
    {
        $maxStep = self::CONFIRM_STEP_CODE;

        if ($jshopConfig->without_shipping) {
            $checkout->setMaxStep($maxStep);
        } else {
            $checkout->checkStep(self::DELIVERY_METHOD_STEP_CODE);
            $id_country = $adv_user->country ?: $jshopConfig->default_country;

            $sh_pr_method_id = JFactory::getApplication()->input->getVar('sh_pr_method_id');
            $params = JFactory::getApplication()->input->getVar('params');
			if (!$this->_setShipping($cart, $adv_user, $id_country, $sh_pr_method_id, $jshopConfig, $params)) {
                return JFactory::getApplication()->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout', 0, 1, $jshopConfig->use_ssl));
            }

            if ($jshopConfig->step_4_3 && !$jshopConfig->without_payment) {            
                $maxStep = self::PAYMENT_METHOD_STEP_CODE;
            }  
               
            return $checkout->setMaxStep($maxStep);
        }
    }
    
    public function save()
    {
        $app = JFactory::getApplication();
        $post = $this->input->getArray();
        $flashData = JSFactory::getFlashData();
        $flashData->set('guestOrderData', $post);
        $shopUser = JSFactory::getUser();
        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $checkout = JModelLegacy::getInstance('checkout', 'jshop');
        $qcheckoutRegistrationService = JSFactory::getService('Registration', 'QCheckoutRegistration');
        $checkout->checkStep(self::ADDRESS_STEP_CODE, $post['cart_name']);

        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher->triggerEvent('onLoadCheckoutStep2save', [&$post]);
        
        $cart = JModelLegacy::getInstance('cart', 'jshop');

        if($post['cart_name'] && $post['cart_name'] != 'cart'){
            $cart->load($post['cart_name']);
            $session = JFactory::getSession();
            $original_cart = $session->get('cart');
            $session->set('original_cart', $original_cart);
            $session->set('cart', serialize($cart));
        }else{
            $cart->load('cart');
            $cart2 = JSFactory::getModel('cart', 'jshop');
            $cart2->load('original_cart');
            $cart2->getSum();
            $cart2->clear();
			$session = JFactory::getSession();
			$session->clear('all_shipping_prices');
        }
        $billingAddressId = $this->input->get('billingAddress_id', 0);
        $shippingAddressId = $this->input->get('shippingAddress_id', 0);

        if (!isUserAuthorized()) {
            try {
                $qcheckoutRegistrationService->validationCheck($post);
            } catch (\Exception $e) {
                redirectMsgsWithOneTypeStatus([$e->getMessage()], getCheckoutUrl(), 'error');
                die;
            }    
        }

        $userAddressIds = $this->saveAddressById($shippingAddressId, $billingAddressId, $post, $shopUser);
        $orderUserId = $shopUser->user_id;

        if ($jshopConfig->step_4_3) {
            $checkout->setMaxStep(self::DELIVERY_METHOD_STEP_CODE);
            $this->saveShipping($jshopConfig, $checkout, $shopUser, $cart);
            $this->savePayment($jshopConfig, $checkout, $cart, $shopUser);
        } else {
            $checkout->setMaxStep(self::PAYMENT_METHOD_STEP_CODE);
            $this->savePayment($jshopConfig, $checkout, $cart, $shopUser);
            $this->saveShipping($jshopConfig, $checkout, $shopUser, $cart);
        }
        
        //save agb
        $checkout->checkStep(self::CONFIRM_STEP_CODE);
        $checkagb = $this->input->get('agb');
        
        $dispatcher->triggerEvent('onLoadStep5save', [&$checkagb, &$shippingAddressId, &$billingAddressId, &$post, &$shopUser, &$orderUserId]);

        if ($jshopConfig->check_php_agb && $checkagb != 'on') {
            return redirectMsgsWithOneTypeStatus([JText::_('COM_SMARTSHOP_ERROR_AGB')], getCheckoutUrl(), 'error');
        }

        if (!$cart->checkListProductsQtyInStore()) {
            return $app->redirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1, 1));
        }
        
        if (!$session->get('checkcoupon')) {
            if (!$cart->checkCoupon()) {
                $cart->setRabatt(0, 0, 0);
                return redirectMsgsWithOneTypeStatus([JText::_('COM_SMARTSHOP_RABATT_NON_CORRECT')], 'index.php?option=com_jshopping&controller=cart&task=view', 'error');
            }
            $session->set('checkcoupon', 1);
        }
        
        $payment_method_id = $cart->getPaymentId();
        $pm_method = JTable::getInstance('paymentMethod', 'jshop');
        $pm_method->load($payment_method_id);

        if ($jshopConfig->without_payment) {
            $pm_method->payment_type = 1;
            $paymentSystemVerySimple = 1; 
        } else {
            $paymentsysdata = $pm_method->getPaymentSystemData();
            $payment_system = $paymentsysdata->paymentSystem;

            if ($paymentsysdata->paymentSystemVerySimple) {
                $paymentSystemVerySimple = 1;
            }

            if ($paymentsysdata->paymentSystemError || ($pm_method->payment_publish == 0)&&(!$jshopConfig->hide_payment_step)) {
                $cart->setPaymentParams('');
                if($post['ajax']){
                    $data['redirectLink'] = getCheckoutUrl();
                    $data['message'] = 'error';
                    $data['status'] = 0;
                    print_r(json_encode($data));die;
                }
                return redirectMsgsWithOneTypeStatus([JText::_('COM_SMARTSHOP_ERROR_PAYMENT')], getCheckoutUrl(), 'error');
            }
        }
        
        $cart->user_id = $orderUserId;

        $order = JTable::getInstance('order', 'jshop');
        $order->bindFromCart($cart, $pm_method, $paymentSystemVerySimple , $payment_system);    
        $order->storeCartData($cart, $billingAddressId, $shippingAddressId);

        $order_history = JTable::getInstance('orderHistory', 'jshop');
        $order_history->order_id = $order->order_id;
        $order_history->order_status_id = $order->order_status;
        $order_history->status_date_added = $order->order_date;
        $order_history->customer_notify = 1;
        $order_history->store();

        $dispatcher->triggerEvent('onEndCheckoutAfterSaveOrder', [$order, $jshopConfig, $pm_method, $order_history, $cart, $checkout]);
        
        if ($pm_method->payment_type == 1) {
            $product_stock_removed = 1;

            if ($jshopConfig->order_stock_removed_only_paid_status) {
                $product_stock_removed = (in_array($order->order_status, $jshopConfig->payment_status_enable_download_sale_file));
            }

            if ($product_stock_removed) {
                $order->changeProductQTYinStock('-');
            }

            if ($jshopConfig->send_order_email) {
                $checkout->sendOrderEmail($order->order_id);
            }
        }
        
        $dispatcher->triggerEvent('onEndCheckoutStep5', [&$order]);
        $session->set('jshop_send_end_form', 0);
        $flashData->deleteByKey('guestOrderData');

        $modelOfOrderItemsNativeUploadsFiles = JSFactory::getModel('OrderItemsNativeUploadsFiles');
        $modelOfOrderItemsNativeUploadsFiles->deleteUnusedFiles();
        
        $dispatcher->triggerEvent('onEndCheckoutBeforeRedirect', [$order, $jshopConfig, $pm_method, $order_history, $cart, $checkout]);

        if (!empty($post['email']) && !isUserAuthorized()) {
            JFactory::getSession()->set('qcheckoutRegistrationAddressData', [
                'post' => $post,
                'order' => (object)get_object_vars($order),
                'billingAddressId' => $userAddressIds['billingAddressId'],
                'shippingAddressId' => $userAddressIds['shippingAddressId'],
            ]);
        }

        if ($jshopConfig->without_payment) {
            $checkout->setMaxStep(self::FINISH_STEP_CODE);
            if($post['ajax']){
                $data['redirectLink'] = SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 0, 1, $jshopConfig->use_ssl);
                $data['status'] = 1;
                print_r(json_encode($data));die;
            }
            return $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 0, 1, $jshopConfig->use_ssl));
        }
        
        $pmconfigs = $pm_method->getConfigs();
        
        $task = 'step6';
        
        if (isset($pmconfigs['windowtype']) && $pmconfigs['windowtype'] == 2) {
            $task = 'step6iframe';
            $session->set('jsps_iframe_width', $pmconfigs['iframe_width']);
            $session->set('jsps_iframe_height', $pmconfigs['iframe_height']);
        }

        $checkout->setMaxStep(self::PREPARE_PAYMENT_DATA_STEP_CODE);
        if($post['ajax']){
            $data['redirectLink'] = SEFLink("index.php?option=com_jshopping&controller=qcheckout&task={$task}&ajax=1", 1);
            $data['status'] = 1;
            print_r(json_encode($data));die;
        }
        return $app->redirect(SEFLink("index.php?option=com_jshopping&controller=qcheckout&task={$task}", 1, 1, $jshopConfig->use_ssl));
    }

    protected function saveAddressById(&$shippingAddressId, &$billingAddressId, $post, &$shopUser)
    {
        $jshopConfig = JSFactory::getConfig();
        $modelOfQCheckout = JSFactory::getModel('QCheckout');
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');

        if (isUserAuthorized()) {
            $billingAddressData = $modelOfUserAddressesFront->getById($billingAddressId);
            $shippingAddressData = $modelOfUserAddressesFront->getById($shippingAddressId);

            if (empty($billingAddressData->address_id)) {
                return redirectMsgsWithOneTypeStatus([Text::_('COM_SMARTSHOP_ADDRESS_BILLING_ADDRESS_NOT_FOUND')], getCheckoutUrl(), 'error');
            }

            if (empty($shippingAddressData->address_id)) {
                return redirectMsgsWithOneTypeStatus([Text::_('COM_SMARTSHOP_ADDRESS_SHIPPING_ADDRESS_NOT_FOUND')], getCheckoutUrl(), 'error');
            }
        } else {
            $tableOfUserAddress = JSFactory::getTable('UserAddress');
            $orderUserId = $jshopConfig->guest_user_id;
            $post['is_default'] = 1;

            $isSavedAddress = $tableOfUserAddress->bindAndSave($post, $orderUserId);

            if (!$isSavedAddress) {
                return redirectMsgsWithOneTypeStatus($tableOfUserAddress->getErrors(), getCheckoutUrl(), 'error');
            }
            $billingAddressId = $tableOfUserAddress->address_id;

            if (!$post['delivery_adress']) {
                $shippingAddressId = $billingAddressId;
            } else {
                $preparedFields = $modelOfQCheckout->preparedShippingFields($post);
                $tableOfUserAddress = JSFactory::getTable('UserAddress');
                $isSavedAddress = $tableOfUserAddress->bindAndSave($preparedFields, $orderUserId);

                if (!$isSavedAddress) {
                    return redirectMsgsWithOneTypeStatus($tableOfUserAddress->getErrors(), getCheckoutUrl(), 'error');
                }
                $shippingAddressId = $tableOfUserAddress->address_id;
            }

            setNextUpdatePrices();
        }

        $fieldsErrorOfUserAddresses = $modelOfUserAddressesFront->checkVerifyFieldsById($billingAddressId, $shippingAddressId);

        if (!empty($fieldsErrorOfUserAddresses['shipping'])) {
            return redirectMsgsWithOneTypeStatus($fieldsErrorOfUserAddresses['shipping'], getCheckoutUrl(), 'error');
        }

        if (!empty($fieldsErrorOfUserAddresses['billing'])) {
            return redirectMsgsWithOneTypeStatus($fieldsErrorOfUserAddresses['billing'], getCheckoutUrl(), 'error');
        }

        return [
            'billingAddressId' => $billingAddressId,
            'shippingAddressId' => $shippingAddressId,
        ];
    }

    public function step6()
    {
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(6);
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        header('Cache-Control: no-cache, must-revalidate');
        $order_id = $session->get('jshop_end_order_id');
        $wmiframe = JFactory::getApplication()->input->getInt('wmiframe');
        $ajax = JFactory::getApplication()->input->getInt('ajax');

        $linkToControllerQCheckout = 'index.php?option=com_jshopping&controller=qcheckout';
        $sefLinkToControllerQCheckout = SEFLink($linkToControllerQCheckout, 0, 1, $jshopConfig->use_ssl);
        $sefLinkToFinishTaskControllerQCheckout = SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 0, 1, $jshopConfig->use_ssl);

        if (!$order_id) {
			if (version_compare(JVERSION, '3.999.999', 'le')) {
                \Joomla\CMS\Factory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_SESSION_FINISH'), 'warning');
			} else {
				\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_SESSION_FINISH'));
			}
            if($ajax){
                print_r($sefLinkToControllerQCheckout);die;
            }
            if (!$wmiframe) {
                return $this->setRedirect($sefLinkToControllerQCheckout);
            } 

            return $this->iframeRedirect($sefLinkToControllerQCheckout);
        }

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load($this->cartName);
        
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);

        // user click back in payment system 
        $jshop_send_end_form = $session->get('jshop_send_end_form');
        if ($jshop_send_end_form == 1){
            return $this->_cancelPayOrder($order_id);
        }

        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $payment_method_id = $order->payment_method_id;
        $pm_method->load($payment_method_id);
        
        $paymentsysdata = $pm_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        if ($paymentsysdata->paymentSystemVerySimple) {
            $paymentSystemVerySimple = 1;
        }

        if ($paymentsysdata->paymentSystemError) {
            $cart->setPaymentParams('');
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');


            if($ajax){
                print_r($sefLinkToControllerQCheckout);die;
            }

            if (!$wmiframe) {
                return $this->setRedirect($sefLinkToControllerQCheckout);
            }
            return $this->iframeRedirect($sefLinkToControllerQCheckout);
        }
        
        if ($pm_method->payment_type == 1 || $paymentSystemVerySimple) { 
            $checkout->setMaxStep(self::FINISH_STEP_CODE);

            if($ajax){
                print_r($sefLinkToFinishTaskControllerQCheckout);die;
            }
            if (!$wmiframe) {
                return $this->setRedirect($sefLinkToFinishTaskControllerQCheckout);
            }
            return $this->iframeRedirect($sefLinkToFinishTaskControllerQCheckout);
        }

        \JFactory::getApplication()->triggerEvent('onBeforeShowEndFormStep6', [&$order, &$cart, $pm_method]);
        $session->set('jshop_send_end_form', 1);
        $pmconfigs = $pm_method->getConfigs();
        $payment_system->showEndForm($pmconfigs, $order);
    }     

    public function step7()
    {
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $input = JFactory::getApplication()->input;
        $wmiframe = $input->getInt("wmiframe");
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $dispatcher = JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadStep7', array());
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        
        $str = "url: ".$_SERVER['REQUEST_URI']."\n";
        foreach($_POST as $k=>$v) $str .= $k."=".$v."\n";
        saveToLog("paymentdata.log", $str);
        
        $act = $input->getVar("act");
        $payment_method = $input->getVar("js_paymentclass");
        
        $pm_method->loadFromClass($payment_method);
        
        $paymentsysdata = $pm_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        if ($paymentsysdata->paymentSystemVerySimple){
            if ($input->getInt('no_lang')) JSFactory::loadLanguageFile();
            saveToLog("payment.log", "#001 - Error payment method file. PM ".$payment_method);
            $dispatcher->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'), 'warning');
            return 0;
        } 
        if ($paymentsysdata->paymentSystemError){
            if ($input->getInt('no_lang')) JSFactory::loadLanguageFile();
            saveToLog("payment.log", "#002 - Error payment. CLASS ".$payment_method);
            $dispatcher->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'), 'warning');
            return 0;
        }
        
        $pmconfigs = $pm_method->getConfigs();
        $urlParamsPS = $payment_system->getUrlParams($pmconfigs);
        
        $order_id = $urlParamsPS['order_id'];
        $hash = $urlParamsPS['hash'];
        $checkHash = $urlParamsPS['checkHash'];
        $checkReturnParams = $urlParamsPS['checkReturnParams'];
        
        $session->set('jshop_send_end_form', 0);
        
        if ($act == "cancel"){
            $this->_cancelPayOrder($order_id);
            return 0;
        }
        
        if ($act == "return" && !$checkReturnParams){
            $checkout->setMaxStep(10);
            if (!$wmiframe){
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 0, 1, $jshopConfig->use_ssl));
            }else{
                $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 0, 1, $jshopConfig->use_ssl));
            }
            return 1;
        }
        
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        
        if ($input->getInt('no_lang')){
            JSFactory::loadLanguageFile($order->getLang());
            $lang = JSFactory::getLang($order->getLang());
        }

        if ($checkHash && $order->order_hash != $hash){
            saveToLog("payment.log", "#003 - Error order hash. Order id ".$order_id);
            $dispatcher->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_ORDER_HASH'), 'warning');
            return 0;
        }
        
        if (!$order->payment_method_id){
            saveToLog("payment.log", "#004 - Error payment method id. Order id ".$order_id);
            $dispatcher->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'), 'warning');
            return 0;
        }

        if ($order->payment_method_id!=$pm_method->payment_id){
            saveToLog("payment.log", "#005 - Error payment method set url. Order id ".$order_id);
            $dispatcher->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'), 'warning');
            return 0;
        }

        $res = $payment_system->checkTransaction($pmconfigs, $order, $act);
        $rescode = $res[0];
        $restext = $res[1];
        $transaction = $res[2];
        $transactiondata = $res[3];
        
        $status = $payment_system->getStatusFromResCode($rescode, $pmconfigs);
        
        $order->transaction = $transaction;
        $order->store();
        $order->saveTransactionData($rescode, $status, $transactiondata);
        
        if ($restext != '') {
            saveToLog("payment.log", $restext);
        }        

        if ($status && !$order->order_created) {
            $product_stock_removed = 1;
            $order->order_created = 1;
            $order->order_status = $status;
            $dispatcher->triggerEvent('onStep7OrderCreated', array(&$order, &$res, &$checkout, &$pmconfigs));
            $order->store();
            if ($payment_system->checkSendOrderMail($rescode, $pmconfigs)) $checkout->sendOrderEmail($order->order_id);
            
            if ($jshopConfig->order_stock_removed_only_paid_status) {
                $product_stock_removed = (in_array($status, $jshopConfig->payment_status_enable_download_sale_file));
            }

            if ($product_stock_removed) {
                $order->changeProductQTYinStock("-");
            }

            $checkout->changeStatusOrder($order_id, $status, 0);
        }

        if ($status && $order->order_status != $status){
           $checkout->changeStatusOrder($order_id, $status, 1);
        }
        
        $dispatcher->triggerEvent('onStep7BefereNotify', array(&$order, &$checkout, &$pmconfigs));
        
        if ($act == "notify"){
            $payment_system->nofityFinish($pmconfigs, $order, $rescode);
            die();
        }
        
        $payment_system->finish($pmconfigs, $order, $rescode, $act);

        if (in_array($rescode, [0,3,4])) {
            $dispatcher->enqueueMessage($restext, 'warning');
            if (!$wmiframe){
                return $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5', 0, 1, $jshopConfig->use_ssl));
            }

            return $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5', 0, 1, $jshopConfig->use_ssl));
        } else {
            $checkout->setMaxStep(10);

            if (!$wmiframe){
                return $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 0, 1, $jshopConfig->use_ssl));
            }
            
            return $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 0, 1, $jshopConfig->use_ssl));
        }
    }

    public function finish()
    {
        JPluginHelper::importPlugin('content');
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(self::FINISH_STEP_CODE);
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $modelOfJsContent = JSFactory::getModel('contentFront', 'jshop');
        $order_id = $session->get('jshop_end_order_id');
        $ajax = JFactory::getApplication()->input->getInt('ajax');

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_SMARTSHOP_CHECKOUT_FINISH'));
        appendPathWay(JText::_('COM_SMARTSHOP_CHECKOUT_FINISH'));

        $text = $modelOfJsContent->getTextContentByContentName('order_success_page');        

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutFinish', [&$text, &$order_id]);
        $session->set('cart_offer_and_order', 0);

        if (trim(strip_tags($text)) == '') {
            $text = '';
        }

        JSFactory::getModel('ProductsFront')->noticeAdminIfALowAmountOfProducts($order_id);
        JSFactory::getModel('ProductAttrsFront')->noticeAdminIfALowAmountOfAttrs();
        loadJSLanguageKeys();

        $view = $this->getView('quick_checkout', getDocumentType(), '', [
            'template_path' => viewOverride('quick_checkout', 'finish.php')
        ]);
        
        $qcheckoutRegistrationAddressData = $session->get('qcheckoutRegistrationAddressData');
        if (!empty($qcheckoutRegistrationAddressData) && !isUserAuthorized()) {
            $isPasswordNotEmpty = (!empty($qcheckoutRegistrationAddressData['post']['password']) && !empty($qcheckoutRegistrationAddressData['post']['password2']));
            $isReadPrivacy = !empty($qcheckoutRegistrationAddressData['post']['qcheckoutReadPrivacy']);

            if ($isPasswordNotEmpty && $isReadPrivacy) {
                try {
                    $registeredUser = JSFactory::getService('Registration', 'QCheckoutRegistration')->create($qcheckoutRegistrationAddressData['post']);
                    if (!empty($registeredUser)) {
                        JSFactory::getService('Order')->changeUserIdByOrderId($registeredUser->user_id, $qcheckoutRegistrationAddressData['order']->order_id);
                    }
                } catch(\Exception $e) {
                    $dispatcher->enqueueMessage($e->getMessage(), 'error');
                }
            }

            $session->clear('qcheckoutRegistrationAddressData');
        }

        $layout = getLayoutName('quick_checkout', 'finish');
        $view->setLayout($layout);
        $view->set('component', 'Finish');
        $view->set('usersParams', JComponentHelper::getParams('com_users'));
        $dynamicFinishText = '';
        $dispatcher->triggerEvent('onAfterSetLayoutCheckoutFinish', [&$view, &$order_id, &$text, &$dynamicFinishText]);
        $view->set('dynamicFinishText', $dynamicFinishText);
        $view->set('text', $text);
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){
            print_R(json_encode(prepareView($view)));
        }else {
            $view->display();
        }

        if ($order_id) {
            $order = JSFactory::getTable('order', 'jshop');
            $order->load($order_id);
            $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
            $payment_method_id = $order->payment_method_id;
            $pm_method->load($payment_method_id);
            $paymentsysdata = $pm_method->getPaymentSystemData();
            $payment_system = $paymentsysdata->paymentSystem;

            if ($payment_system) {
                $pmconfigs = $pm_method->getConfigs();
                $payment_system->complete($pmconfigs, $order, $pm_method);
            }

            $dispatcher->triggerEvent('onAfterDisplayCheckoutFinish', [&$text, &$order, &$pm_method]);
        }

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load($this->cartName);
        $cart->getSum();
        $cart->clear();
        $checkout->deleteSession();
		$session->clear('all_shipping_prices');

        $original_cart = $session->get('original_cart');
        $temp_cart = unserialize($original_cart);
        $products = $temp_cart->products ?? [];

        $cartOfOneClickBy = JSFactory::getModel('cart', 'jshop');
        $cartOfOneClickBy->load('one_click_buy');
        $cartOfOneClickBy->clear();


        if( !empty($products)){
            $session->set('cart', $original_cart);
        }
        if($ajax){die;}

    }
        
    private function setPayment(&$post, &$tableOfCart, $payment_method, $params, $shopUser, $ajax = 0)
    {
        $modelOfQCheckout = JSFactory::getModel('QCheckout');
        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveCheckoutStep3save', [&$post]);
        $params_pm = $params[$payment_method] ?? [];
        $tableOfPaymentMethod = JTable::getInstance('paymentmethod', 'jshop');
        $tableOfPaymentMethod->class = $payment_method;
        $paymentMethodId = $tableOfPaymentMethod->getId();
        $tableOfPaymentMethod->load($paymentMethodId);
        $pmconfigs = $tableOfPaymentMethod->getConfigs();
        $paymentsysdata = $tableOfPaymentMethod->getPaymentSystemData();
        $paymentSystem = $paymentsysdata->paymentSystem;
        $ajax_return = 1;

        if ($paymentsysdata->paymentSystemError || !$paymentMethodId) {
            $tableOfCart->setPaymentParams('');
            $ajax_return =  [
                'error' => 1, 
                'msg' => JText::_('COM_SMARTSHOP_SELECT_PAYMENT')
            ];
        }

        if ($paymentSystem) {
            if (!$paymentSystem->checkPaymentInfo($params_pm, $pmconfigs)) {
                $tableOfCart->setPaymentParams('');
                $ajax_return = [
                    'error' => 1, 
                    'msg' => $paymentSystem->getErrorMessage()
                ];
            }
        }

        $tableOfPaymentMethod->setCart($tableOfCart);
        $tableOfCart->setPaymentId($paymentMethodId);
        $paymentPrice = $tableOfPaymentMethod->getPrice();
        $tableOfCart->setPaymentDatas($paymentPrice, $tableOfPaymentMethod);

        if (isset($params[$payment_method])) {
            $tableOfCart->setPaymentParams($params_pm);
        } else {
            $tableOfCart->setPaymentParams('');
        }
        
        $shopUser->saveTypePayment($paymentMethodId);
        
        $dispatcher->triggerEvent('onAfterSaveCheckoutStep3save', [&$shopUser, &$tableOfPaymentMethod, &$tableOfCart]);
        CheckoutExtrascouponMambot::getInstance()->onAfterSaveCheckoutStep3save($shopUser, $tableOfPaymentMethod, $tableOfCart);
        
        if ($ajax) {
            return $ajax_return;
        }

        if (isset($ajax_return['error']) && 1 == $ajax_return['error']) {
            return $modelOfQCheckout->setSessionError($ajax_return['msg']);
        }

        return 1;
    }
    
    private function _setShipping(&$cart, $adv_user, $id_country, $sh_pr_method_id, $jshopConfig, $params, $ajax = 0, $shippings = [])
    {
        $modelOfQCheckout = JSFactory::getModel('QCheckout');
        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveCheckoutStep4save', []);
        $sh_ids = explode('_', $sh_pr_method_id);
        $shipping_prices = 0;
        $package_prices = 0;
		$shipping_stand_price = 0;
		$package_stand_price = 0;
        
		foreach($sh_ids as $key=>$val){
			if(!$val) continue;
            $shipping_method_price = JTable::getInstance('shippingMethodPrice', 'jshop');
            $shipping_method_price->load($val);

            $sh_method = JTable::getInstance('shippingMethod', 'jshop');
            $sh_method->load($val);//shipping_method_id
            $params_sm = $params[$val] ?? [];

            $ajax_return = 1;
            $paymentId = $cart->getPaymentId();
            $shippingMethodModel = JSFactory::getModel('ShippingMethod');

            if (!$shipping_method_price->sh_pr_method_id || !$shipping_method_price->isCorrectMethodForCountry($id_country) || !$shippingMethodModel->isCorectShippingMethodForPayment($paymentId, $sh_method->sh_pr_method_id)){
                $ajax_return = [
                    'error' => 1,
                    'msg' => JText::_('COM_SMARTSHOP_SELECT_SHIPPING')
                ];
            }

            if (isset($params[$val])) {
                $cart->setShippingParams($params_sm);
            } else {
                if (!$cart->getShippingId() || $cart->getShippingId() == $val) {
                    $params_sm = $cart->getShippingParams();
                } elseif(!$cart->getShippingId() || $cart->getShippingId() != $val){
                    $cart->setShippingId($val);
                    $params_sm = $cart->getShippingParams();
                }
                else {
                    $cart->setShippingParams('');
                }
            }

            if ($shipping_method_price && !$shipping_method_price->check($params_sm, $sh_method)) {
                $ajax_return = [
                    'error' => 1,
                    'msg' => $shipping_method_price->getErrorMessage()
                ];
            }

            // Production Time
            JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models');
            $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel');

            $working_days = json_decode($_production_calendar->getParams()->working_days);
            $maxProductionTime = JSFactory::getService('ProductionCalendar')->getMaxProductionTime($cart->products);

            if ($jshopConfig->show_delivery_date) {
                $delivery_date = '';
                $deliverytimedays = JSFactory::getAllDeliveryTimeDays();
                $day = $deliverytimedays[$shipping_method_price->delivery_times_id];

                $productDeliveryDay = getMaxDayDeliveryOfProducts($cart->products);

                if ($day) {
                    $delivery_day = $day + $productDeliveryDay;

                    if (!is_null($working_days) && !empty($working_days)) {
                        $delivery_day = $_production_calendar->calculateDelivery($delivery_day + $maxProductionTime);
                    }

                    $delivery_date = getCalculateDeliveryDay($delivery_day);
                } else {
                    if ($jshopConfig->delivery_order_depends_delivery_product) {
                        $day = $cart->getDeliveryDaysProducts();

                        if ($day) {
                            $delivery_day = $day + $productDeliveryDay;
                            if (!is_null($working_days) && !empty($working_days)) {
                                $delivery_day = $_production_calendar->calculateDelivery($delivery_day + $maxProductionTime);
                            }

                            $delivery_date = getCalculateDeliveryDay($delivery_day);
                        }
                    }
                }

                $cart->setDeliveryDate($delivery_date);
            }
            //update payment price
            $payment_method_id = $cart->getPaymentId();
            if ($payment_method_id){
                $paym_method = JTable::getInstance('paymentmethod', 'jshop');
                $paym_method->load($payment_method_id);
                $cart->setDisplayItem(1, 1);
                $paym_method->setCart($cart);
                $price = $paym_method->getPrice();
                $cart->setPaymentDatas($price, $paym_method);
            }

            $adv_user->saveTypeShipping($sh_method->sh_pr_method_id);
            //Product shipping
            $params = [];
			
			$id_country = $jshopConfig->default_country;
			if(isset($adv_user->d_country) && $adv_user->d_country){
				$id_country = $adv_user->d_country;
			}else{
				$id_country = $adv_user->country;
			}			

            $tableOfShippingMethod = JTable::getInstance('shippingMethod', 'jshop');
            $idsOfCartProducts = getListOfValuesByArrKey($cart->products, 'product_id');
            $shippingsOfCartProducts = JSFactory::getModel('ProductsShipping')->getByProductsIds($idsOfCartProducts, ['*'], true);
            $allShippingsOfCartProducts = JSFactory::getModel('ProductsShipping')->getByProductsIds($idsOfCartProducts, ['*'], false);
            $allShippingsMethods = $tableOfShippingMethod->getAllShippingMethodsCountry($id_country, $paymentId, 1, $adv_user->usergroup_id, $adv_user->state);
            $listOfProductNoShippings = JSFactory::getModel('ProductsShipping')->getByProductsIdsNoInclude($idsOfCartProducts, ['*']);

            $idsOfShPrMethodOfCartProducts = getListSpecifiedAttrsFromArray($shippingsOfCartProducts, 'sh_pr_method_id');
            $allidsOfShPrMethodOfCartProducts = getListSpecifiedAttrsFromArray($allShippingsOfCartProducts, 'sh_pr_method_id');
            $iIdsOfShPrMethodsNoProducts = getListSpecifiedAttrsFromArray($listOfProductNoShippings, 'sh_pr_method_id') ?: [];
            $nonUniqueIdsOfShPrMethodOfCartProducts = (count($cart->products) >= 2 && $idsOfShPrMethodOfCartProducts != array_unique($idsOfShPrMethodOfCartProducts)) ? array_diff_assoc($idsOfShPrMethodOfCartProducts, array_unique($idsOfShPrMethodOfCartProducts)) : $idsOfShPrMethodOfCartProducts;

            $cartProductsShippingsMethods = array_reduce($allShippingsMethods, function ($carry, $shippingMethod) use ($idsOfShPrMethodOfCartProducts, $iIdsOfShPrMethodsNoProducts) {
                if (in_array($shippingMethod->sh_pr_method_id, $idsOfShPrMethodOfCartProducts) || (!in_array($shippingMethod->sh_pr_method_id, $idsOfShPrMethodOfCartProducts) && !in_array($shippingMethod->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))) {
                    $carry[$shippingMethod->sh_pr_method_id] = $shippingMethod;
                }

                return $carry;
            });

            $shippingCostOfAllCartProductsShippings = 0;
            if (!empty($cartProductsShippingsMethods)) {
                $filteredCartProductsShippingsMethods = array_filter($cartProductsShippingsMethods, function ($shippingMethod) use ($nonUniqueIdsOfShPrMethodOfCartProducts, &$shippingCostOfAllCartProductsShippings, $allidsOfShPrMethodOfCartProducts, $iIdsOfShPrMethodsNoProducts) {
                    $shippingCostOfAllCartProductsShippings += $shippingMethod->shipping_stand_price ?: 0;
                    if(in_array($shippingMethod->sh_pr_method_id, $nonUniqueIdsOfShPrMethodOfCartProducts) || (!in_array($shippingMethod->sh_pr_method_id, $nonUniqueIdsOfShPrMethodOfCartProducts) &&  !in_array($shippingMethod->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))){
                        return true;
                    }
                });
            }

            $shippingCost = $cartProductsShippingsMethods[$shipping_method_price->sh_pr_method_id]->shipping_stand_price ?: 0;
            if (empty($filteredCartProductsShippingsMethods) || count($sh_ids) > 1) {
                $shippingCost = $shippingCostOfAllCartProductsShippings;
            }
			if(!empty($shippings)){
				$shippingMethodId = $shipping_method_price->sh_pr_method_id;
				foreach($shippings as $val){
					if($val->sh_pr_method_id == $shippingMethodId){
						$prices['shipping'] = $val->calculeprice - $val->package;
						$prices['package'] = $val->package;
						break;
					}
				}
			}
			
			$shipping_stand_price = $shipping_method_price->shipping_stand_price;
			$package_stand_price = $shipping_method_price->package_stand_price;

            $shipping_method_price->shipping_stand_price = $shipping_stand_price;
			$shipping_method_price->package_stand_price = $package_stand_price;

			$prices = $shipping_method_price->calculateSum($cart);


            $shipping_prices += $prices['shipping'];
			$package_prices += $prices['package'];

			$prices['shipping'] = $shipping_prices;
			$prices['package'] = $package_prices;
            $cart->setShippingId($sh_pr_method_id);
            $cart->setShippingPrId($sh_pr_method_id);
            $cart->setShippingsDatas($prices, $shipping_method_price);
            /////////////////
            $dispatcher->triggerEvent('onAfterSaveCheckoutStep4', [&$adv_user, &$sh_method, &$shipping_method_price, &$cart]);
            CheckoutExtrascouponMambot::getInstance()->onAfterSaveCheckoutStep4($adv_user, $sh_method, $shipping_method_price, $cart);

        }

        if ($ajax) {
            return $ajax_return;
        }

        if (isset($ajax_return['error']) && $ajax_return['error'] == 1) {
            return $modelOfQCheckout->setSessionError($ajax_return['msg']);
        }
        //die;
        return 1;
    }

    protected function transformPaymentsDescrsTextsToModule(array &$payments)
    {
        foreach($payments as &$payment) {
            if (!empty($payment->payment_description)) {
                $payment->payment_description = JHtml::_('content.prepare', $payment->payment_description);
            }
        }
    }
    
	public function reloadShippingPrice(){		
        $jshopConfig = JSFactory::getConfig();
        $post = JFactory::getApplication()->input->post->getArray();
        $adv_user = JSFactory::getUser();
		$dispatcher = \JFactory::getApplication();
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load($this->cartName);
		$sh_pr_method_id = isset($post['shipping_id']) ? $post['shipping_id'] : null;
		$payment_method_id = isset($post['payment_id']) ? $post['payment_id'] : null;		
		$billing_id = isset($post['billing_id']) ? $post['billing_id'] : null;

		if((int)$billing_id > 0){
			$modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
			$addressId = $billing_id;
			
			if (isset($modelOfUserAddressesFront->getById($addressId)->address_id)) {
				$modelOfUserAddressesFront->setAsDefault($addressId, $adv_user->user_id);
				$adv_user = JSFactory::getUserShop(false);
			}
		}
		
		if (!isset($post['shipping_id'])) $sh_pr_method_id=$cart->getShippingId();
		$sh_pr_method_ids = explode('_', $sh_pr_method_id);
		$prices = [];
		$prices['shipping'] = 0;
		$prices['package'] = 0;
		foreach($sh_pr_method_ids as $_sh_pr_method_id){
			$modelOfQCheckout = JSFactory::getModel('QCheckout');
			
			$shipping_method_price = JTable::getInstance('shippingMethodPrice', 'jshop');
			$shipping_method_price->load($_sh_pr_method_id);
			$sh_method = JTable::getInstance('shippingMethod', 'jshop');
			$sh_method->load($_sh_pr_method_id);//shipping_method_id
			$params_sm = $params[$_sh_pr_method_id] ?? [];

			$ajax_return = 1;
			$paymentId = $cart->getPaymentId();
			$shippingMethodModel = JSFactory::getModel('ShippingMethod');
			

			if (isset($params[$_sh_pr_method_id])) {
				$cart->setShippingParams($params_sm);
			} else {
				if (!$cart->getShippingId() || $cart->getShippingId() == $_sh_pr_method_id) {
					$params_sm = $cart->getShippingParams();
				} elseif(!$cart->getShippingId() || $cart->getShippingId() != $_sh_pr_method_id){
					$cart->setShippingId($_sh_pr_method_id);
					$params_sm = $cart->getShippingParams();
				 }else {
					$cart->setShippingParams('');
				}
			}
			
		   
			if ($shipping_method_price && !$shipping_method_price->check($params_sm, $sh_method)) {
				$ajax_return = [
					'error' => 1, 
					'msg' => $shipping_method_price->getErrorMessage()
				];
			}
			/*if($adv_user->user_id > 0 && !isset($post['client_type']) && JFactory::getSession()->get('shipping_stand_price_'.$sh_pr_method_id) && JFactory::getSession()->get('shipping_stand_price_'.$sh_pr_method_id) != '0.00'){
				$prices['shipping'] = JFactory::getSession()->get('shipping_stand_price_'.$sh_pr_method_id);
				$prices['package'] = JFactory::getSession()->get('package_stand_price_'.$sh_pr_method_id);
			}else{*/
				$_prices = $shipping_method_price->calculateSum($cart);
				$prices['shipping'] += isset($_prices['shipping']) ? $_prices['shipping'] : 0;
				$prices['package'] += isset($_prices['package']) ? $_prices['package'] : 0;
				JFactory::getSession()->set('shipping_stand_price_'.$_sh_pr_method_id, $prices['shipping']);
				JFactory::getSession()->set('package_stand_price_'.$_sh_pr_method_id, $prices['package']);
			//}		
			$cart->setShippingId($_sh_pr_method_id);
			$cart->setShippingPrId($_sh_pr_method_id);
			$cart->setShippingsDatas($prices, $shipping_method_price);
		}
        //update payment price
        if (!isset($post['payment_id'])) $payment_method_id = $cart->getPaymentId();
		if ($payment_method_id){
            $paym_method = JTable::getInstance('paymentmethod', 'jshop');
            $paym_method->load($payment_method_id);
			if (isset($post['payment_id']))  $paym_method->loadFromClass($payment_method_id);
            $cart->setDisplayItem(1, 1);
            $paym_method->setCart($cart);
            $price = $paym_method->getPrice();
            $cart->setPaymentDatas($price, $paym_method);  
			$name = JSFactory::getLang()->get('name');
			if(isset($post['payment_id']) || isset($post['client_type'])  || isset($post['billing_id']) ){
				$cart_data['not_change_payment'] = 0;
				$cart_data['payment_name'] = $paym_method->$name;
				$cartPaymentPrice = $cart->getPaymentPrice();
				if ((isset($cartPackagePrice) && $cartPackagePrice > 0) || $jshopConfig->display_null_package_price) {
					$cart_data['summ_package'] = formatprice($cartPackagePrice);
				}
				$cart_data['summ_payment'] = [
					'price' => $cartPaymentPrice, 
					'formatprice' => formatprice($cartPaymentPrice)
				];  
			}else{
				$cart_data['not_change_payment'] = 1;
			}			
        }
		$dispatcher->triggerEvent('onAfterSaveCheckoutStep4', [&$adv_user, &$sh_method, &$shipping_method_price, &$cart]);
		if (!$jshopConfig->without_shipping) {			
            $cart_data['summ_delivery'] = formatprice($cart->getShippingPrice());
            $cartPackagePrice = $cart->getPackagePrice() ?? 0;

            if ($cartPackagePrice > 0 || $jshopConfig->display_null_package_price) {
                $cart_data['summ_package'] = formatprice($cartPackagePrice);
            }
			
            $isIncludeShipping = 1;			
			
			$cart->setDisplayItem(1, 1);
			$fullsumm = $cart->getSum($isIncludeShipping, 1, 1);
			$tax_list = $cart->getTaxExt($isIncludeShipping, 1, 1);

		   $new_tax_list = [];
			if (!empty($tax_list)) {
				foreach ($tax_list as $percent => $tax) {
					$new_tax_list[formattax($percent)] = formatprice($tax);
				}
			}
			
			$dispatcher->triggerEvent('onReloadShippingPriceAfterTaxList', [&$tax_list]);		
			
			$cart_data['tax_list_name'] = displayTotalCartTaxName();
			if(!$jshopConfig->hide_tax) $cart_data['tax_list'] = $new_tax_list;
			$cart_data['fullsumm'] = formatprice($fullsumm);
			$cart_data['currentFullSummWithoutFormatPrice'] = $fullsumm;
			$cart_data['jshopConfig'] = $jshopConfig;
			
			//$cart_data['newViewSmallCart'] = JSFactory::getModel('cart', 'jshop')->renderSmallCart();
        }


		//if((isset($post['client_type']) && $post['client_type']) || isset($post['billing_id']) ){
			$this->additionDataForAjax($cart_data, $adv_user);
			if ((isset($post['payment_id']))AND($post['payment_id']!=$cart->getPaymentId())) $this->ajaxLoadPayments($jshopConfig, $dispatcher, $adv_user, $cart, $post, $cart_data);
		   
			if (!$jshopConfig->step_4_3) {
				$this->ajaxLoadShippings($jshopConfig, $dispatcher, $adv_user, $cart_data, $cart, $post);
			}
			
			$cart_data['newViewSmallCart'] = JSFactory::getModel('cart', 'jshop')->renderSmallCart();
		//}
        echo json_encode($cart_data);
        die;
    }
    
    public function _cancelPayOrder($orderId = '')
    {
        $jshopConfig = JSFactory::getConfig();
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $wmiframe = JFactory::getApplication()->input->getInt('wmiframe');
        $session = JFactory::getSession();
        $linkToCheckout = SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5', 0, 1, $jshopConfig->use_ssl);

        $orderId = $orderId ?: $session->get('jshop_end_order_id');
        
        if (empty($orderId)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_SESSION_FINISH'),'error');

            if (empty($wmiframe)) {
                return $this->setRedirect($linkToCheckout);
            }

            return $this->iframeRedirect($linkToCheckout);
        }

        $checkout->cancelPayOrder($orderId);
        
        \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_PAYMENT_CANCELED'),'error');
        if (empty($wmiframe)) { 
            return $this->setRedirect($linkToCheckout);
        }
            
        return $this->iframeRedirect($linkToCheckout);
    }
}