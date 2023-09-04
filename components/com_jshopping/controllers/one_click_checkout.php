<?php

use Joomla\CMS\Application\SiteApplication;

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerone_click_checkout extends JControllerLegacy{

    const ADDRESS_STEP_CODE = 2;
    const PAYMENT_METHOD_STEP_CODE = 3;
    const DELIVERY_METHOD_STEP_CODE = 4;
    const CONFIRM_STEP_CODE = 5;
    const PREPARE_PAYMENT_DATA_STEP_CODE = 6;
    const RETURN_TO_SHOP_STEP_CODE = 7;
    const FINISH_STEP_CODE = 10;

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
        JHTML::_('bootstrap.loadCss');
        JHTML::_('bootstrap.framework');

        $ajax = JFactory::getApplication()->input->getVar("ajax");
        $doc = JFactory::getDocument();
        $shopUser = JSFactory::getUser();
        $dispatcher = \JFactory::getApplication();
        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();
        $checkout = JModelLegacy::getInstance('checkout', 'jshop');
        //$checkout->checkStep(self::ADDRESS_STEP_CODE, 'one_click_buy');

        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $isUserAuthorized = (!empty($shopUser->user_id) && $shopUser->user_id != -1);
        $countOfUserAddresses = ($isUserAuthorized) ? $modelOfUserAddressesFront->getCountByUserId($shopUser->user_id) : 0;
        $dispatcher->triggerEvent('onLoadCheckoutStep2', []);
        $checkLogin = JFactory::getApplication()->input->getInt('check_login');
        $flashData = JSFactory::getFlashData();
        $flashOrderData = $flashData->get('guestOrderData');

        if ($checkLogin) {
            $session->set('show_pay_without_reg', 1);
            checkUserLogin($ajax);
        }
        checkUserBlock();
        checkUserCreditLimit();
        appendPathWay(JText::_('COM_SMARTSHOP_CHECKOUT'));
        $seo = JTable::getInstance('seo', 'jshop');
        $seodata = $seo->loadData('checkout-address');
        $seodata->title = $seodata->title ?: JText::_('COM_SMARTSHOP_CHECKOUT');

        setSeoMetaData($seodata->title);

        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load('one_click_buy');
        $cart->getSum();
        // $cart->setDisplayData();
        $this->checkUploadsAndElseRedirect($cart);

        //address
        $configFields = $jshopConfig->getListFieldsRegister()['address'];

        if ($configFields['birthday']['display']) {
            if (method_exists('JHtmlBehavior', 'calendar')) {
                JHtmlBehavior::calendar();
            }
        }

        $shopUser->country = $shopUser->country ?: $jshopConfig->default_country;
        $shopUser->birthday = getDisplayDate($shopUser->birthday, $jshopConfig->field_birthday_format);

        $modelOfUsersFront = JSFactory::getModel('UsersFront');
        $modelOfCountries = JSFactory::getModel('CountriesFront');

        $countriesSelectMarkup = $modelOfCountries->generateCountriesSelectMarkup($flashOrderData['country'] ??  $shopUser->country);
        $clientTitles = $modelOfUsersFront->generateClientTitlesSelectMarkup($flashOrderData['title'] ?? $shopUser->title);
        $markupOfClientTypes = $modelOfUsersFront->generateClientTypesSelectMarkup($flashOrderData['client_type'] ?? $shopUser->client_type);
        $markupOfDClientTypes = $modelOfUsersFront->generateClientTypesSelectDMarkup($flashOrderData['d_client_type'] ?? $shopUser->d_client_type ?? 0);

        filterHTMLSafe($shopUser, ENT_QUOTES);

        loadJSLanguageKeys();

        $view = $this->getView('one_click_checkout', getDocumentType(), '', [
            'template_path' => viewOverride('one_click_checkout', 'default.php')
        ]);
        $layout = getLayoutName('one_click_checkout', 'default');
        $view->setLayout($layout);
        $view->cartName = 'one_click_buy';

        $configDFields = $jshopConfig->getListFieldsRegister()['address'];
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
        $view->set('cart', $cart);
        $view->set('prod', $cart->products[0]);
        // print_r($cart->products[0]);die;
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
        $requiredConfigFields = $jshopConfig->buildArrayWithFieldsRegisterForJS('address');
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $shopUser = JSFactory::getUserShop();
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');

        $jshopConfig = JSFactory::getConfig();

        // $this->input->set('tmpl', 'component');
        $context = 'smartshop.front.user.addressPopup';
        $searchText = $this->input->get('search_text', '');
        $addrType = $this->input->get('addrType', '');
        $isSearchReset = (bool)$this->input->get('search_text_reset', false);
        $searchLikeWord = [];

        if ($isSearchReset) {
            $app->setUserState('smartshop.popupSearch', '');
        }

        if (!empty($searchText)) {
            $app->setUserState('smartshop.popupSearch', $searchText);
        } else {
            $searchText = $app->getUserState('smartshop.popupSearch', '');
        }

        $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

        if (!empty($searchText)) {
            $searchLikeWord = [
                'word' => "{$searchText}%",
                'byColumns' => [
                    'l_name',
                    'f_name',
                    'street',
                    'street_nr',
                    'zip',
                    'city'
                ]
            ];
        }
        if ($shopUser->user_id) {
            $allUserAddresses = $modelOfUserAddressesFront->getAllByUserId($shopUser->user_id, $limitstart, $limit, $searchLikeWord);
        }
        $view->set('popup_address', $this->addressData(1));
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

        $doc->addScriptDeclaration('
            document.addEventListener("DOMContentLoaded", function () {
                shopUserAddressesPopup.setUserAddresses(' . json_encode($allUserAddresses) . ');
            });
        ');
        //shopUserAddressesPopup.setUserAddresses(' . json_encode($allUserAddresses) . ');
        if (!empty($view->payment_methods)) {
            foreach ($view->payment_methods as $paymentObj) {
                $dataToFrontJs['payment_type_check'][$paymentObj->payment_class] = $paymentObj->existentcheckform;

                if (isset($view->active_payment) && $paymentObj->payment_id == $view->active_payment) {
                    $dataToFrontJs['payment_class'] = $paymentObj->payment_class;
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
        $view->set('calendarField', (JHTML::_('calendar', $flashOrderData['birthday'] ?? $shopUser->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19'])));
        $view->set('onSubmitForm', 'return shopQuickCheckout.onSubmitForm(this, ' . (int)$isUserAuthorized . ');');
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep5View', [&$view]);
        CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep5View($view);

        $shopUser->country = $shopUser->country ?: $jshopConfig->default_country;
        $shopUser->country = $modelOfCountries->getById($shopUser->country)->name ?: $shopUser->country;
        $view->set('sprintpreviewnativeuploadedfiles_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintPreviewNativeUploadedFiles', 1));
        $view->set('allowUserRegistration', JComponentHelper::getParams('com_users')->get('allowUserRegistration'));

        if(!empty($view->shipping_methods)){
            foreach($view->shipping_methods as $k=>$v){
                if($view->active_shipping == $v->sh_pr_method_id){
                    $view->set('active_shipping_name', $v->name);
                    break;
                }
            }
        }
        if(!empty($view->payment_methods)){
            foreach($view->payment_methods as $k=>$v){
                if($view->active_payment == $v->payment_id){
                    $view->set('active_payment_name', $v->name);
                    break;
                }
            }
        }
        $view->set('component', 'default_one_click_checkout');
        $view->set('summ', $cart->getPriceProducts());
        $view->set('discount', $cart->getDiscountShow());
        $view->set('summ_payment', $cart->getPaymentPrice());
        $isIncludeShipping = 0;
        if (!$jshopConfig->without_shipping) {
            $view->set('summ_delivery', $cart->getShippingPrice());

            if ($cart->getPackagePrice() > 0 || $jshopConfig->display_null_package_price) {
                $view->set('summ_package', $cart->getPackagePrice());
            }

            $isIncludeShipping = 1;
        }
        $view->set('fullsumm', $cart->getSum(1, 1, 1));
        $view->set('tax_list', $cart->getTaxExt($isIncludeShipping, 1, 1));
        $_cart = prepareView($view->cart);

        $view->set('cart', $_cart);
        $view->set('dataJsonPopup', json_encode(prepareView($view)));
        $doc->addScriptDeclaration('const dataJsonPopup='.json_encode(prepareView($view)));
        $view->display();
        die;
    }

    function close(){
        $ajax = 1;

        $additional_fields = [];
        $usetriggers = 1;
        $errors = [];
        $displayErrorMessage = 1;

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load('one_click_buy');
        foreach($cart->products as $number_id=>$prod) {
            $attr = unserialize($prod['attributes']);
            $freeattribut = unserialize($prod['freeattributes']);
            $uploadDataArr = $prod['uploadData'];

            $cart1 = JSFactory::getModel('cart', 'jshop');
            $cart1->load('cart');
            $cart1->add($prod['product_id'], $prod['quantity'], $attr, $freeattribut, $additional_fields, $usetriggers, $errors, $displayErrorMessage, $uploadDataArr);
        }
        $cart->clear();
        die();

    }

    function error(){
        $message = JFactory::getApplication()->input->getVar('message');
        $category_id = JFactory::getApplication()->input->getVar('category_id');
        $product_id = JFactory::getApplication()->input->getVar('product_id');
        \Joomla\CMS\Factory::getApplication()->enqueueMessage($message, 'notice');

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$category_id.'&product_id='.$product_id,1,1));
    }

    protected function checkUploadsAndElseRedirect(&$cart)
    {
        $linkToCart = SEFLink('/index.php?option=com_jshopping&controller=cart&task=view', 0, 1, JSFactory::getConfig()->use_ssl);
        $uploadModel = JSFactory::getModel('upload');
        $uploadErrors = [];

        $cart->setDisplayData('checkout');
        foreach($cart->products as ['uploadData' => $productUploads, 'product_id_with_support_upload' => $product_id_with_support_upload]) {
            if (!empty($product_id_with_support_upload)) {
                $cleanedUploadData = $uploadModel->getCleanedArrWithUploadData($productUploads);
                $count = 0;
                if(!empty($cleanedUploadData)) $count = count(count($cleanedUploadData));
                $isProductPassedRequired = $uploadModel->isProductPassedRequired($product_id_with_support_upload, $count, 'cart');

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

    private function paymentStep(SiteApplication &$dispatcher, jshopConfig &$jshopConfig, jshopCart &$cart, &$view, &$adv_user)
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

                if ($params['payment_filter'] == 1) {
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

    private function deliveryMethodStep(SiteApplication &$dispatcher, jshopConfig &$jshopConfig, jshopCart &$cart, &$view, &$adv_user)
    {
        $dispatcher->triggerEvent('onLoadCheckoutStep4', []);

        if ($jshopConfig->without_shipping) {
            $cart->setShippingId(0);
            $cart->setShippingPrice(0);
            $cart->setPackagePrice(0);
            $view->set('delivery_step', 0);
        } else {
            $modelOfQCheckout = JSFactory::getModel('QCheckout');
            $id_country = $adv_user->country;

            if (!$id_country) {
                $id_country = $jshopConfig->default_country;
                $modelOfQCheckout->setSessionError(JText::_('COM_SMARTSHOP_REGWARN_COUNTRY'));
            }

            $load_shippings = JSFactory::getModel('ShippingsFront')->getPreparedShippings($adv_user, $id_country, $jshopConfig, $cart);
            $shippings = $load_shippings['shippings'];
            $firstShipping = &$shippings['0'];
            $active_shipping = $load_shippings['active_shipping'];

            // if (!$jshopConfig->hide_shipping_step) {
            //     $this->_setShipping($cart, $adv_user, $id_country, $active_shipping, $jshopConfig, null);
            // }

            if ($jshopConfig->hide_shipping_step) {
                if (!$firstShipping->sh_pr_method_id && ($jshopConfig->hide_payment_step || $jshopConfig->without_payment)) {
                    $modelOfQCheckout->setSessionError(JText::_('COM_SMARTSHOP_ERROR_SHIPPING'));
                }

                $firstShipping->sh_pr_method_id = $firstShipping->sh_pr_method_id ?: '';

                $this->_setShipping($cart, $adv_user, $id_country, $active_shipping, $jshopConfig, null);
                $view->set('delivery_step', 0);
                $view->set('active_shipping', $firstShipping->sh_pr_method_id);
                $view->set('active_shipping_name', $firstShipping->name);
                $view->set('active_sh_pr_method_id', $firstShipping->sh_pr_method_id);
            } else {
                transformDescrsTextsToModule($shippings);

                $view->set('delivery_step', 1);
                $view->set('shipping_methods', $shippings);
                $view->set('active_shipping', $active_shipping);

                $modelOfQCheckout->prepareViewProductShipping($view);
                $this->_setShipping($cart, $adv_user, $id_country, $view->active_shipping, $jshopConfig, null);

                $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep4View', [&$view]);
                CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep4View($view);
            }
        }
    }

    protected function checkDataFromShippingSection(array &$post, &$adv_user, jshopConfig &$jshopConfig, array &$data, jshopCart &$cart, SiteApplication &$dispatcher)
    {
        $modelOfQCheckout = JSFactory::getModel('QCheckout');
        $params = [];
        $id_country = $adv_user->country ?: $jshopConfig->default_country;

        if (empty($post['sh_pr_method_id']) || empty($post['reload'])) {
            $shippings = JSFactory::getModel('ShippingsFront')->getPreparedShippings($adv_user, $id_country, $jshopConfig, $cart);

            if (empty($post['sh_pr_method_id'])) {
                $post['sh_pr_method_id'] = $jshopConfig->hide_shipping_step ? $shippings['shippings']['0']->sh_pr_method_id : $shippings['active_shipping'];
            }
        }

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
        $data['reload'] = $post['reload'];

        $setShipping = $this->_setShipping($cart, $adv_user, $id_country, $post['sh_pr_method_id'], $jshopConfig, $params, 1);
        if (!empty($setShipping) && $setShipping['error'] == 1) {
            if (!in_array($setShipping['msg'], $data['error'])) {
                $data['error'][] = $setShipping['msg'];
            }
        }

        if (!$post['reload'] || $post['reload'] == 0) {
            transformDescrsTextsToModule($shippings['shippings']);

            $this->shipping_methods = $shippings['shippings'];
            $this->active_shipping = $shippings['active_shipping'];

            $currentObj = $this;
            $modelOfQCheckout->prepareViewProductShipping($this);
            $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep4View', [&$currentObj]);
            CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep4View($this);

            ob_start();
            include  templateOverrideBlock('blocks', 'shippings.php');
            $data['shippings1'] = ob_get_contents();
            ob_end_clean();
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
        if (!$jshopConfig->without_payment && $this->isChangePayment) {
            $dispatcher->triggerEvent('onLoadCheckoutStep3', []);

            if ($jshopConfig->hide_payment_step) {
                $payments = JSFactory::getModel('PaymentsFront')->getPreparedPayments($adv_user, $jshopConfig, $cart);

                if (!$payments['payments']['0']->payment_class) {
                    $data['error'][] = JText::_('COM_SMARTSHOP_ERROR_PAYMENT');
                }

                $setPayment = $this->setPayment($post, $cart, $payments['payments']['0']->payment_class, null, $adv_user, 1);
                $data['active_payment_class'] = $payments['payments']['0']->payment_class;
            } else {
                $setPayment = $this->setPayment($post, $cart, $post['payment_method'], null, $adv_user, 1);
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
                    $cart->load('one_click_buy');

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

                    $modelOfShippingMethod = JSFactory::getModel('ShippingMethod');
                    $_listsh = $modelOfShippingMethod->getAdditionalDataByCountryId($id_country, ['sh_pr_method.sh_pr_method_id', 'sh_pr_method.payments']);
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
                $currentObj = $this;
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
        \JFactory::getApplication()->triggerEvent('onBeforeQCheckoutAjaxRefresh', [&$post, &$adv_user, &$cart, &$modelOfUserAddressesFront]);

        if (!empty($adv_user->user_id) && $adv_user->user_id >= 1) {
            if (!empty($post['billingAddress_id']) || !empty($post['shippingAddress_id'])) {
                $addressId = !empty($jshopConfig->tax_on_delivery_address) ? $post['shippingAddress_id'] : $post['billingAddress_id'];

                if (isset($modelOfUserAddressesFront->getById($addressId)->address_id)) {
                    $modelOfUserAddressesFront->setAsDefault($addressId, $adv_user->user_id);
                    $adv_user = JSFactory::getUserShop(false);
                }
            }
        } elseif (!empty($post['country'])) {
            $adv_user->country = $post['country'];
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

        $cart->load('one_click_buy');
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
        $data['newViewSmallCart'] = JSFactory::getModel('cart', 'jshop')->renderSmallCart();

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
            $dispatcher->triggerEvent('onLoadCheckoutStep4', []);
            $id_country = $adv_user->country;

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

            if ($jshopConfig->hide_shipping_step) {
                $methodIdOfShippingPrice = $shippings['shippings']['0']->sh_pr_method_id;

                if (!$methodIdOfShippingPrice && ($jshopConfig->hide_payment_step || $jshopConfig->without_payment)) {
                    $data['error'][] = JText::_('COM_SMARTSHOP_ERROR_SHIPPING');
                }

                $data['active_sh_pr_method_id'] = $methodIdOfShippingPrice;
            } else {
                transformDescrsTextsToModule($shippings['shippings']);
                $this->shipping_methods = $shippings['shippings'];
                $this->active_shipping = $shippings['active_shipping'];


                $currentObj = $this;
                JSFactory::getModel('QCheckout')->prepareViewProductShipping($this);
                $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep4View', [&$currentObj]);
                CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCheckoutStep4View($this);
                ob_start();
                include  templateOverrideBlock('blocks', 'shippings.php');
                $data['shippings'] = ob_get_contents();
                ob_end_clean();
                $methodIdOfShippingPrice = $shippings['active_shipping'];
            }

            $setShipping = $this->_setShipping($cart, $adv_user, $id_country, $methodIdOfShippingPrice, $jshopConfig, null, 1);

            if (!empty($setShipping) && $setShipping['error'] == 1) {
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
        $this->ajaxLoadPayments($jshopConfig, $dispatcher, $adv_user, $cart, $post, $data);

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
        if (!empty($set_payment) && $set_payment['error'] == 1) {
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
        $isIncludeShipping = 0;

        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load('one_click_buy');
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
        $tax_list = $cart->getTaxExt($isIncludeShipping, 1, 1);

        $name = JSFactory::getLang()->get('name');
        $pm_method = JTable::getInstance('paymentMethod', 'jshop');
        $pm_method->load($cart->getPaymentId());
        $cart_data['payment_name'] = $pm_method->$name;

        $new_tax_list = [];
        if (!empty($tax_list)) {
            foreach ($tax_list as $percent => $tax) {
                $new_tax_list[formattax($percent)] = formatprice($tax);
            }
        }
        $cart_data['tax_list_name'] = displayTotalCartTaxName();
        $cart_data['tax_list'] = $new_tax_list;
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

            $sh_pr_method_id = JFactory::getApplication()->input->getInt('sh_pr_method_id');
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
        $checkout->checkStep(self::ADDRESS_STEP_CODE);

        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher->triggerEvent('onLoadCheckoutStep2save', []);

        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load('one_click_buy');
        $billingAddressId = $this->input->get('billingAddress_id', 0);
        $shippingAddressId = $this->input->get('shippingAddress_id', 0);

        /* new address functionality */
        $this->saveAddressById($shippingAddressId, $billingAddressId, $post, $shopUser);
        /* new address functionality END */

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

        $dispatcher->triggerEvent('onLoadStep5save', [&$checkagb]);

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

            if ($paymentsysdata->paymentSystemError || $pm_method->payment_publish == 0) {
                $cart->setPaymentParams('');
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
        if ($jshopConfig->without_payment) {
            $checkout->setMaxStep(self::FINISH_STEP_CODE);
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
            $registeredUserData = $modelOfQCheckout->registerNewAccount($post);
            $orderUserId = $registeredUserData->user_id ?? $jshopConfig->guest_user_id;
            $shopUser->user_id = $orderUserId ?: $shopUser->user_id;

            $post['is_default'] = 1;

            if ($orderUserId === $jshopConfig->guest_user_id) {
                $isSavedAddress = $tableOfUserAddress->bindAndSave($post, $orderUserId);

                if (!$isSavedAddress) {
                    return redirectMsgsWithOneTypeStatus($tableOfUserAddress->getErrors(), getCheckoutUrl(), 'error');
                }
                $billingAddressId = $tableOfUserAddress->address_id;

                if(!$post['delivery_adress']){
                    $shippingAddressId = $billingAddressId;
                }else{
                    $preparedFields = $modelOfQCheckout->preparedShippingFields($post);
                    $tableOfUserAddress = JSFactory::getTable('UserAddress');
                    $isSavedAddress = $tableOfUserAddress->bindAndSave($preparedFields, $orderUserId);

                    if (!$isSavedAddress) {
                        return redirectMsgsWithOneTypeStatus($tableOfUserAddress->getErrors(), getCheckoutUrl(), 'error');
                    }
                    $shippingAddressId = $tableOfUserAddress->address_id;
                }
            } else {

                $billingAddressId = $modelOfUserAddressesFront->getDataOfDefaultAddress($orderUserId)->address_id;
                if($post['delivery_adress']){
                    $preparedFields = $modelOfQCheckout->preparedShippingFields($post);
                    $tableOfUserAddress = JSFactory::getTable('UserAddress');
                    $isSavedAddress = $tableOfUserAddress->bindAndSave($preparedFields, $orderUserId);

                    if (!$isSavedAddress) {
                        return redirectMsgsWithOneTypeStatus($tableOfUserAddress->getErrors(), getCheckoutUrl(), 'error');
                    }
                    $shippingAddressId = $tableOfUserAddress->address_id;
                }
            }

            if(!$post['delivery_adress']){
                $shippingAddressId = $billingAddressId;
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

        $linkToControllerQCheckout = 'index.php?option=com_jshopping&controller=qcheckout';
        $sefLinkToControllerQCheckout = SEFLink($linkToControllerQCheckout, 0, 1, $jshopConfig->use_ssl);
        $sefLinkToFinishTaskControllerQCheckout = SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 0, 1, $jshopConfig->use_ssl);

        if (!$order_id) {            
			if (version_compare(JVERSION, '3.999.999', 'le')) {
                \Joomla\CMS\Factory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_SESSION_FINISH'), 'warning');
			} else {
				\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_SESSION_FINISH'));
			}

            if (!$wmiframe) {
                return $this->setRedirect($sefLinkToControllerQCheckout);
            }

            return $this->iframeRedirect($sefLinkToControllerQCheckout);
        }

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load('one_click_buy');

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

            if (!$wmiframe) {
                return $this->setRedirect($sefLinkToControllerQCheckout);
            }

            return $this->iframeRedirect($sefLinkToControllerQCheckout);
        }

        if ($pm_method->payment_type == 1 || $paymentSystemVerySimple) {
            $checkout->setMaxStep(self::FINISH_STEP_CODE);

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

    public function finish()
    {
        JPluginHelper::importPlugin('content');
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(self::FINISH_STEP_CODE);
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $modelOfJsContent = JSFactory::getModel('contentFront', 'jshop');
        $order_id = $session->get('jshop_end_order_id');

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

        $layout = getLayoutName('quick_checkout', 'finish');
        $view->setLayout($layout);
        $view->set('component', 'Finish');
        $view->set('usersParams', JComponentHelper::getParams('com_users'));
        $dynamicFinishText = '';
        $dispatcher->triggerEvent('onAfterSetLayoutCheckoutFinish', [&$view, &$order_id, &$text, &$dynamicFinishText]);
        $view->set('dynamicFinishText', $dynamicFinishText);
        $view->set('text', $text);
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        $view->display();

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
        $cart->load('one_click_buy');
        $cart->getSum();
        $cart->clear();
        $checkout->deleteSession();
    }

    private function setPayment(&$post, &$tableOfCart, $payment_method, $params, $shopUser, $ajax = 0)
    {
        $modelOfQCheckout = JSFactory::getModel('QCheckout');
        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveCheckoutStep3save', [&$post]);
        $params_pm = $params[$payment_method];
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

    private function _setShipping(&$cart, $adv_user, $id_country, $sh_pr_method_id, $jshopConfig, $params, $ajax = 0)
    {
        $modelOfQCheckout = JSFactory::getModel('QCheckout');
        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveCheckoutStep4save', []);

        $shipping_method_price = JTable::getInstance('shippingMethodPrice', 'jshop');
        $shipping_method_price->load($sh_pr_method_id);
        $sh_method = JTable::getInstance('shippingMethod', 'jshop');
        $sh_method->load($sh_pr_method_id);//shipping_method_id
        $params_sm = $params[$sh_pr_method_id];

        $ajax_return = 1;
        $paymentId = $cart->getPaymentId();
        $shippingMethodModel = JSFactory::getModel('ShippingMethod');

        /*if (!$shipping_method_price->sh_pr_method_id || !$shipping_method_price->isCorrectMethodForCountry($id_country) || !$shippingMethodModel->isCorectShippingMethodForPayment($paymentId, $sh_method->sh_pr_method_id)){
            $ajax_return = [
                'error' => 1,
                'msg' => JText::_('COM_SMARTSHOP_SELECT_SHIPPING')
            ];
        }*/

        if (isset($params[$sh_pr_method_id])) {
            $cart->setShippingParams($params_sm);
        } else {
            if (!$cart->getShippingId() || $cart->getShippingId() == $sh_pr_method_id) {
                $params_sm = $cart->getShippingParams();
            } else {
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

        $adv_user->saveTypeShipping($sh_method->shipping_id);
        //Product shipping
        $params = [];

        $id_country = $adv_user->country ?: $jshopConfig->default_country;
        $tableOfShippingMethod = JTable::getInstance('shippingMethod', 'jshop');
        $idsOfCartProducts = getListOfValuesByArrKey($cart->products, 'product_id');
        $shippingsOfCartProducts = JSFactory::getModel('ProductsShipping')->getByProductsIds($idsOfCartProducts, ['*'], true);
        $allShippingsOfCartProducts = JSFactory::getModel('ProductsShipping')->getByProductsIds($idsOfCartProducts, ['*'], false);
        $allShippingsMethods = $tableOfShippingMethod->getAllShippingMethodsCountry($id_country, $paymentId, 1, $adv_user->usergroup_id);
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
        $filteredCartProductsShippingsMethods = array_filter($cartProductsShippingsMethods, function ($shippingMethod) use ($nonUniqueIdsOfShPrMethodOfCartProducts, &$shippingCostOfAllCartProductsShippings, $allidsOfShPrMethodOfCartProducts, $iIdsOfShPrMethodsNoProducts) {
            $shippingCostOfAllCartProductsShippings += $shippingMethod->shipping_stand_price ?: 0;
            if(in_array($shippingMethod->sh_pr_method_id, $nonUniqueIdsOfShPrMethodOfCartProducts) || (!in_array($shippingMethod->sh_pr_method_id, $nonUniqueIdsOfShPrMethodOfCartProducts) &&  !in_array($shippingMethod->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))){
                return true;
            }
        });

        $shippingCost = $cartProductsShippingsMethods[$shipping_method_price->sh_pr_method_id]->shipping_stand_price ?: 0;
        if (empty($filteredCartProductsShippingsMethods)) {
            $shippingCost = $shippingCostOfAllCartProductsShippings;
        }

        $shipping_method_price->shipping_stand_price = $shippingCost;
        $prices = $shipping_method_price->calculateSum($cart);

        $cart->setShippingId($shipping_method_price->sh_pr_method_id);
        $cart->setShippingPrId($shipping_method_price->sh_pr_method_id);
        $cart->setShippingsDatas($prices, $shipping_method_price);
        /////////////////
        $dispatcher->triggerEvent('onAfterSaveCheckoutStep4', [&$adv_user, &$sh_method, &$shipping_method_price, &$cart]);
        CheckoutExtrascouponMambot::getInstance()->onAfterSaveCheckoutStep4($adv_user, $sh_method, $shipping_method_price, $cart);

        if ($ajax) {
            return $ajax_return;
        }

        if (isset($ajax_return['error']) && $ajax_return['error'] == 1) {
            return $modelOfQCheckout->setSessionError($ajax_return['msg']);
        }

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
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load('one_click_buy');
        //$cart->refreshCart();
        $sh_pr_method_id = $post['shipping_id'];

        if (!isset($post['shipping_id'])) $sh_pr_method_id=$cart->getShippingId();

        $modelOfQCheckout = JSFactory::getModel('QCheckout');

        $shipping_method_price = JTable::getInstance('shippingMethodPrice', 'jshop');
        $shipping_method_price->load($sh_pr_method_id);
        $sh_method = JTable::getInstance('shippingMethod', 'jshop');
        $sh_method->load($sh_pr_method_id);//shipping_method_id
        $params_sm = $params[$sh_pr_method_id];

        $ajax_return = 1;
        $paymentId = $cart->getPaymentId();
        $shippingMethodModel = JSFactory::getModel('ShippingMethod');


        if (isset($params[$sh_pr_method_id])) {
            $cart->setShippingParams($params_sm);
        } else {
            if (!$cart->getShippingId() || $cart->getShippingId() == $sh_pr_method_id) {
                $params_sm = $cart->getShippingParams();
            } else {
                $cart->setShippingParams('');
            }
        }


        if ($shipping_method_price && !$shipping_method_price->check($params_sm, $sh_method)) {
            $ajax_return = [
                'error' => 1,
                'msg' => $shipping_method_price->getErrorMessage()
            ];
        }
        $prices = $shipping_method_price->calculateSum($cart);
        $cart->setShippingId($sh_pr_method_id);
        $cart->setShippingPrId($sh_pr_method_id);
        $cart->setShippingsDatas($prices, $shipping_method_price);

        // Production Time
        JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models');
        $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel');

        $working_days = json_decode($_production_calendar->getParams()->working_days);
        $maxProductionTime = max(
            array_map(function($prod) {
                return $prod['production_time'];
            }, $cart->products)
        );

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
        if (!$jshopConfig->without_shipping) {
            $cart_data['summ_delivery'] = formatprice($cart->getShippingPrice());
            $cartPackagePrice = $cart->getPackagePrice();

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
            $cart_data['tax_list_name'] = displayTotalCartTaxName();
            $cart_data['tax_list'] = $new_tax_list;
            $cart_data['fullsumm'] = formatprice($fullsumm);
            $cart_data['currentFullSummWithoutFormatPrice'] = $fullsumm;

            $cart_data['newViewSmallCart'] = JSFactory::getModel('cart', 'jshop')->renderSmallCart();
        }
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

    public function addressData($temp = 0)
    {
        checkUserLogin();

        JHTML::_('bootstrap.loadCss');
        JHTML::_('bootstrap.framework');

        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $shopUser = JSFactory::getUserShop();
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');

        $jshopConfig = JSFactory::getConfig();

        $context = 'smartshop.front.user.addressPopup';
        $searchText = $this->input->get('search_text', '');
        $addrType = $this->input->get('addrType', '');
        $isSearchReset = (bool)$this->input->get('search_text_reset', false);
        $getUserAddresses = $this->input->get('getUserAddresses', 0);
        $ajax = $this->input->get('ajax', 0);

//print_r($ajax);die;
        if ($ajax) {
            $temp = 1;
        }
        $searchLikeWord = [];

        if ($isSearchReset) {
            $app->setUserState('smartshop.popupSearch', '');
        }

        if (!empty($searchText)) {
            $app->setUserState('smartshop.popupSearch', $searchText);
        } else {
            $searchText = $app->getUserState('smartshop.popupSearch', '');
        }

        $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

        if (!empty($searchText)) {
            $searchLikeWord = [
                'word' => "{$searchText}%",
                'byColumns' => [
                    'l_name',
                    'f_name',
                    'street',
                    'street_nr',
                    'zip',
                    'city'
                ]
            ];
        }
        if ($shopUser->user_id) {

            $allUserAddresses = $modelOfUserAddressesFront->getAllByUserId($shopUser->user_id, $limitstart, $limit, $searchLikeWord);
            $countOfAddresses = $modelOfUserAddressesFront->getCountByUserId($shopUser->user_id, $searchLikeWord);
            $pagination = new JPagination($countOfAddresses, $limitstart, $limit);
            $addrTypeScr = '';


            if ($getUserAddresses) {
                print  json_encode($allUserAddresses);
                die;
            }

            if (!empty($addrType)) {
                $addrTypeScr = 'parent.shopUserAddressesPopup.setAddressTypeToHandler("' . $addrType . '");';
            }
        }


        if($temp == 1 && !$ajax) {
            //return $view->loadTemplate();die;
            return renderTemplate([
                templateOverrideBlock('blocks', 'one_click_checkout_address.php', 1)
            ], 'one_click_checkout_address', [
                'addresses' => $allUserAddresses,
                'pagination' => $pagination,
                'limitstart' => $limitstart,
                'searchText' => $searchText,
                'addrType' => $addrType,
                'pagesLinks' => $pagination->getPagesLinks(),
                'limitBox' => $pagination->getLimitBox(),
                'addressPopup' => "/index.php?option=com_jshopping&controller=one_click_checkout&task=addressData&limitstart=" . $limitstart,
                'layoutSearch' => JLayouthelper::render('smartshop.helpers.search', ['searchText' => $searchText]),
                'addNewAddressLink' => SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress&isCloseTabAfterSave=1', 1)
            ]) ?: '';

        }elseif($ajax){
            print renderTemplate([
                templateOverrideBlock('blocks', 'one_click_checkout_address.php', 1)
            ], 'one_click_checkout_address', [
                'addresses' => $allUserAddresses,
                'pagination' => $pagination,
                'limitstart' => $limitstart,
                'searchText' => $searchText,
                'addrType' => $addrType,
                'pagesLinks' => $pagination->getPagesLinks(),
                'limitBox' => $pagination->getLimitBox(),
                'addressPopup' => "/index.php?option=com_jshopping&controller=one_click_checkout&task=addressData&limitstart=".$limitstart,
                'layoutSearch' => JLayouthelper::render('smartshop.helpers.search', ['searchText' => $searchText]),
                'addNewAddressLink' => SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress&isCloseTabAfterSave=1', 1),
                'display' => 1
            ]) ?: '';
            die();

        }else{
            $this->input->set('tmpl', 'component');
            $view = $this->getView('one_click_checkout', getDocumentType(), '', [
                'template_path' => viewOverride('one_click_checkout', 'one_click_checkout_address.php')
            ]);

            $layout = getLayoutName('one_click_checkout', 'one_click_checkout_address');
            $view->setLayout($layout);
            $view->set('addresses', $allUserAddresses);
            $view->set('pagination', $pagination);
            $view->set('limitstart', $limitstart);
            $view->set('searchText', $searchText);
            $view->set('addrType', $addrType);

            $view->set('pagesLinks', $pagination->getPagesLinks());
            $view->set('limitBox', $pagination->getLimitBox());
            $view->set('addressPopup', "/index.php?option=com_jshopping&controller=one_click_checkout&task=addressData&limitstart=".$limitstart);
            $view->set('layoutSearch', JLayouthelper::render('smartshop.helpers.search', ['searchText' => $searchText]) );
            $view->set('addNewAddressLink', SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress&isCloseTabAfterSave=1', 1) );
            $view->display();
        }

    }
}
?>