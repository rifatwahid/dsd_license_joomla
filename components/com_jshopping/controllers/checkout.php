<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

include_once JPATH_ROOT . '/components/com_jshopping/payments/payment.php';
include_once JPATH_ROOT . '/components/com_jshopping/shippingform/shippingform.php';
include_once JPATH_ROOT . '/components/com_jshopping/lib/shMailer.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/offer_and_order/checkout_offer_and_order.php';

/**
 * DEPRECATED!!!!!
 * Try NOT to use this controller!!!
 */
class JshoppingControllerCheckout extends JshoppingControllerBase
{
    
    public function __construct($config = array())
    {
        parent::__construct($config);
		$document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'components/com_jshopping/css/offer_and_order.css');


        JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_jshopping/models');
        JPluginHelper::importPlugin('jshoppingcheckout');
        JPluginHelper::importPlugin('jshoppingorder');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerCheckout', array(&$currentObj));
		setSeoMetaData();
    }
    
    public function display($cachable = false, $urlparams = false)
    {
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
        $this->step2();
    }
    
    public function step2()
    {	$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(2);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadCheckoutStep2', array());
		
        $session = JFactory::getSession();
        $user = JFactory::getUser();
        $jshopConfig = JSFactory::getConfig();
        $country = JSFactory::getTable('country', 'jshop');
        
        $checkLogin = JFactory::getApplication()->input->getInt('check_login');
        if ($checkLogin){
            $session->set("show_pay_without_reg", 1);
            checkUserLogin();
        }

        appendPathWay(JText::_('COM_SMARTSHOP_CHECKOUT_ADDRESS'));
        $seo = JSFactory::getTable("seo", "jshop");
        $seodata = $seo->loadData("checkout-address");
        if ($seodata->title==""){
            $seodata->title = JText::_('COM_SMARTSHOP_CHECKOUT_ADDRESS');
        }        
		setSeoMetaData($seodata->title);
        
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();		
        $cart->getSum();

        $adv_user = JSFactory::getUser();
        
        $adv_user->birthday = getDisplayDate($adv_user->birthday, $jshopConfig->field_birthday_format);
        $adv_user->d_birthday = getDisplayDate($adv_user->d_birthday, $jshopConfig->field_birthday_format);
        
        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields['address'];
        $count_filed_delivery = $jshopConfig->getEnableDeliveryFiledRegistration('address');

        $checkout_navigator = $this->_showCheckoutNavigation(2);
        $small_cart = $this->_showSmallCart(2);

        $view_name = "quick_checkout";
        $view_config = array("template_path"=>viewOverride('quick_checkout','adress.php'));
        $view = $this->getView($view_name, getDocumentType(), '', $view_config);
        $view->setLayout("adress");
        $view->set('select', $jshopConfig->user_field_title);
        
        if (!$adv_user->country) $adv_user->country = $jshopConfig->default_country;
        if (!$adv_user->d_country) $adv_user->d_country = $jshopConfig->default_country;

        $option_country[] = JHTML::_('select.option',  '0', JText::_('COM_SMARTSHOP_REG_SELECT'), 'country_id', 'name' );
        $option_countryes = array_merge($option_country, $country->getAllCountries());
        $select_countries = JHTML::_('select.genericlist', $option_countryes, 'country', 'class = "inputbox form-select" size = "1"','country_id', 'name', $adv_user->country );
        $select_d_countries = JHTML::_('select.genericlist', $option_countryes, 'd_country', 'class = "inputbox form-select" size = "1"','country_id', 'name', $adv_user->d_country);

        foreach($jshopConfig->user_field_title as $key => $value) {
            $option_title[] = JHTML::_('select.option', $key, JText::_($value), 'title_id', 'title_name');
        }
        $select_titles = JHTML::_('select.genericlist', $option_title, 'title', 'class = "inputbox form-select"','title_id', 'title_name', $adv_user->title);            
        $select_d_titles = JHTML::_('select.genericlist', $option_title, 'd_title', 'class = "inputbox form-select"','title_id', 'title_name', $adv_user->d_title);
        
        $client_types = array();
        foreach ($jshopConfig->user_field_client_type as $key => $value) {
            $client_types[] = JHTML::_('select.option', $key, JText::_($value), 'id', 'name' );
        }
        $select_client_types = JHTML::_('select.genericlist', $client_types,'client_type','class = "inputbox form-select" onchange="shopHelper.toggleFirm(this.value)"','id','name', $adv_user->client_type);
        $select_d_client_types = JHTML::_('select.genericlist', $client_types,'d_client_type','class = "inputbox form-select" onchange="shopHelper.toggleFirm(this.value)"','id','name', $adv_user->client_type);

        filterHTMLSafe( $adv_user, ENT_QUOTES);

		if ($config_fields['birthday']['display'] || $config_fields['d_birthday']['display']){
            if (method_exists('JHtmlBehavior', 'calendar')) {
                JHtmlBehavior::calendar();
            }
        }
        $view->set('config', $jshopConfig);
        $view->set('select_countries', $select_countries);
        $view->set('select_d_countries', $select_d_countries);
        $view->set('select_titles', $select_titles);
        $view->set('select_d_titles', $select_d_titles);
        $view->set('select_client_types', $select_client_types);
        $view->set('live_path', JURI::base());
        $view->set('config_fields', $config_fields);
        $view->set('count_filed_delivery', $count_filed_delivery);
        $view->set('user', $adv_user);
        $view->set('delivery_adress', $adv_user->delivery_adress);
        $view->set('checkout_navigator', $checkout_navigator);
        $view->set('small_cart', $small_cart);
        $view->set('action', SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step2save', 0, 0, $jshopConfig->use_ssl));
		loadingStatesScripts();
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep2View', array(&$view));
        $view->display();
    }
    
    public function step2save()
    {
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(2);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadCheckoutStep2save', array());

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        
        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();
        $post = JFactory::getApplication()->input->post->getArray();
        if (!count($post)){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_DATA'),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step2',0,1, $jshopConfig->use_ssl));
            return 0;
        }
        if ($post['birthday']) $post['birthday'] = getJsDateDB($post['birthday'], $jshopConfig->field_birthday_format);
        if ($post['d_birthday']) $post['d_birthday'] = getJsDateDB($post['d_birthday'], $jshopConfig->field_birthday_format);
        unset($post['user_id']);
        unset($post['usergroup_id']);
        $post['lang'] = $jshopConfig->getLang();
        $user = JFactory::getUser();
        $adv_user = JSFactory::getUser();
        
        $adv_user->bind($post);
        if (!$adv_user->check("address")){
            \JFactory::getApplication()->enqueueMessage($adv_user->getError(),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step2',0,1, $jshopConfig->use_ssl));
            return 0;
        }
        $dispatcher->triggerEvent('onBeforeSaveCheckoutStep2', array(&$adv_user, &$user, &$cart));
        
        if (!$adv_user->store()){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_REGWARN_ERROR_DATABASE'),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step2',0,1, $jshopConfig->use_ssl));
            return 0;
        }

        if($user->id && !$jshopConfig->not_update_user_joomla){
            $user = clone(JFactory::getUser());
			if ($adv_user->email){
				$user->email = $adv_user->email;
			}
			if ($adv_user->f_name || $adv_user->l_name){
				$user->name = $adv_user->f_name." ".$adv_user->l_name;
			}
			if ($adv_user->f_name || $adv_user->l_name || $adv_user->email){
				$user->save();
			}
        }
        
        setNextUpdatePrices();
        
		$cart->setShippingId(0);
		$cart->setShippingPrId(0);
		$cart->setShippingPrice(0);
		$cart->setPaymentId(0);
		$cart->setPaymentParams("");
		$cart->setPaymentPrice(0);
			
        $dispatcher->triggerEvent('onAfterSaveCheckoutStep2', array(&$adv_user, &$user, &$cart));
        
        if ($jshopConfig->without_shipping && $jshopConfig->without_payment) {
            $checkout->setMaxStep(5);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1, $jshopConfig->use_ssl));
            return 0; 
        }
        
        if ($jshopConfig->without_payment){
            $checkout->setMaxStep(4);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4',0,1,$jshopConfig->use_ssl));
            return 0;
        }

		if ($jshopConfig->step_4_3){
            if ($jshopConfig->without_shipping){
                $checkout->setMaxStep(3);
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3',0,1,$jshopConfig->use_ssl));
                return 0;
            }
            $checkout->setMaxStep(4);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4',0,1,$jshopConfig->use_ssl));
        }else{
			$checkout->setMaxStep(3);
			$this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3',0,1,$jshopConfig->use_ssl));
		}
    }
    
    public function step3()
    {
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
        $checkout = JSFactory::getModel('checkout', 'jshop');
    	$checkout->checkStep(3);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadCheckoutStep3', array() );
    	
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        
        $user = JFactory::getUser();
        $adv_user = JSFactory::getUser();
        
        appendPathWay(JText::_('COM_SMARTSHOP_CHECKOUT_PAYMENT'));
        $seo = JSFactory::getTable("seo", "jshop");
        $seodata = $seo->loadData("checkout-payment");
        if ($seodata->title==""){
            $seodata->title = JText::_('COM_SMARTSHOP_CHECKOUT_PAYMENT');
        }
		
		setSeoMetaData($seodata->title);
        
        $checkout_navigator = $this->_showCheckoutNavigation(3);
        if ($jshopConfig->show_cart_all_step_checkout){
            $small_cart = $this->_showSmallCart(3);
        }else{
            $small_cart = '';
        }
        
        if ($jshopConfig->without_payment){
            $checkout->setMaxStep(4);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4',0,1,$jshopConfig->use_ssl));
            return 0;
        }

        $paymentmethod = JSFactory::getTable('paymentmethod', 'jshop');
		$shipping_id = $cart->getShippingId();
        $all_payment_methods = $paymentmethod->getAllPaymentMethods(1, $shipping_id,$adv_user->usergroup_id);
        $i = 0;
        $paym = array();
        foreach($all_payment_methods as $pm){
            $paym[$i] = new stdClass();
            if ($pm->scriptname!=''){
                $scriptname = $pm->scriptname;    
            }else{
                $scriptname = $pm->payment_class;   
            }
            $paymentmethod->load($pm->payment_id); 
            $paymentsysdata = $paymentmethod->getPaymentSystemData($scriptname);
            if ($paymentsysdata->paymentSystem){
                $paym[$i]->existentcheckform = 1;
				$paym[$i]->payment_system = $paymentsysdata->paymentSystem;
            }else{
                $paym[$i]->existentcheckform = 0;
            }
            
            $paym[$i]->name = $pm->name;
            $paym[$i]->payment_id = $pm->payment_id;
            $paym[$i]->payment_class = $pm->payment_class;
            $paym[$i]->scriptname = $pm->scriptname;
            $paym[$i]->payment_description = $pm->description;
            $paym[$i]->price_type = $pm->price_type;
            $paym[$i]->image = $pm->image;
            $paym[$i]->price_add_text = '';
            if ($pm->price_type==2){
                $paym[$i]->calculeprice = $pm->price;
                if ($paym[$i]->calculeprice!=0){
                    if ($paym[$i]->calculeprice>0){
                        $paym[$i]->price_add_text = '+'.$paym[$i]->calculeprice.'%';
                    }else{
                        $paym[$i]->price_add_text = $paym[$i]->calculeprice.'%';
                    }
                }
            }else{
                $paym[$i]->calculeprice = getPriceCalcParamsTax($pm->price * $jshopConfig->currency_value, $pm->tax_id, $cart->products);
                if ($paym[$i]->calculeprice!=0){
                    if ($paym[$i]->calculeprice>0){
                        $paym[$i]->price_add_text = '+'.formatprice($paym[$i]->calculeprice);
                    }else{
                        $paym[$i]->price_add_text = formatprice($paym[$i]->calculeprice);
                    }
                }
            }
            
            $s_payment_method_id = $cart->getPaymentId();
            if ($s_payment_method_id == $pm->payment_id){
                $params = $cart->getPaymentParams();
            }else{
                $params = array();
            }

            $parseString = new parseString($pm->payment_params);
            $pmconfig = $parseString->parseStringToParams();

            if ($paym[$i]->existentcheckform){
                $paym[$i]->form = $paymentmethod->loadPaymentForm($paym[$i]->payment_system, $params, $pmconfig);
            }else{
                $paym[$i]->form = "";
            }
            
            $i++;
        }
        
        $s_payment_method_id = $cart->getPaymentId();
        $active_payment = intval($s_payment_method_id);

        if (!$active_payment){
            $list_payment_id = array();
            foreach($paym as $v){
                $list_payment_id[] = $v->payment_id;
            }
            if (in_array($adv_user->payment_id, $list_payment_id)) $active_payment = $adv_user->payment_id;
        }
        
        if (!$active_payment){
            if (isset($paym[0])){
                $active_payment = $paym[0]->payment_id;
            }
        }
        
        if ($jshopConfig->hide_payment_step){
            $first_payment = $paym[0]->payment_class;
            if (!$first_payment){
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
                return 0;
            }
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3save&payment_method='.$first_payment,0,1,$jshopConfig->use_ssl));
            return 0;
        }
        
        $view_name = "checkout";
        $view_config = array("template_path"=>viewOverride($view_name,'payments.php'));
        $view = $this->getView($view_name, getDocumentType(), '', $view_config);
        $view->setLayout("payments");
        $view->set('payment_methods', $paym);
        $view->set('active_payment', $active_payment);
        $view->set('checkout_navigator', $checkout_navigator);
        $view->set('small_cart', $small_cart);
        $view->set('action', SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3save', 0, 0, $jshopConfig->use_ssl));
		//Product shipping
		$lang = JSFactory::getLang();
        $db = \JFactory::getDBO();
        
        $query = "select * from #__jshopping_shipping_ext_calc where alias='sm_product'";
        $db->setQuery($query);
        $row = $db->loadObject();
        if ($row->params){
            $params = unserialize($row->params);
        }else{
            $params = array();
        }
        if ($params['payment_filter']==1){
        
        
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();
        
        $user = JFactory::getUser();
        if ($user->id){
            $adv_user = JSFactory::getUserShop();
        }else{
            $adv_user = JSFactory::getUserShopGuest();    
        }
        
        $shippingmethod = JTable::getInstance('shippingMethod', 'jshop');                    
        if ($adv_user->delivery_adress){
            $id_country = $adv_user->d_country;
        }else{
            $id_country = $adv_user->country;
        }
        if (!$id_country) $id_country = $jshopConfig->default_country;
                
        $products = array();
        foreach($cart->products as $v){
            $products[] = $v['product_id'];
        }
        
        $query = "select * from #__jshopping_products_shipping where product_id in (".implode(",", $products).")";
        $db->setQuery($query);
        $list = $db->loadObjectList();        
        
        $productsdata = array();
        foreach($list as $k=>$v){
            $productsdata[$v->product_id][] = $v;
        }
        
        $query = "SELECT sh_pr_method.sh_pr_method_id, sh_pr_method.payments FROM `#__jshopping_shipping_method_price` 
				  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
                  INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
                  WHERE countries.country_id = '".$db->escape($id_country)."' AND sh_pr_method.published = '1'";
        $db->setQuery($query);
        $_listsh = $db->loadObjectList();
        $listsh = array();
        foreach($_listsh as $v){
            $listsh[$v->sh_pr_method_id] = $v->payments;    
        }
        
        $shfilter = array();
        foreach($listsh as $k=>$v){
            $shfilter[] = $k;
        }
        
        foreach($productsdata as $pid=>$v){
            $filter = array();
            foreach($v as $k2=>$v2){
                $filter[$v2->sh_pr_method_id] = $v2->published;
            }            
            
            foreach($shfilter as $k3=>$v3){
                if (isset($filter[$v3]) && $filter[$v3]==0){
                    unset($shfilter[$k3]);
                }
            }
        }
        
        $enablshipping = array();
        foreach($shfilter as $id){
            $enablshipping[$id] = $listsh[$id];
        }
        $allpayments = $view->payment_methods;
        foreach($view->payment_methods as $k=>$v){
            $en = 0;
            foreach($enablshipping as $pids){
                if ($pids==""){
                    $en = 1;
                }else{
                    $tmp = explode(",",$pids);
                    if (in_array($v->payment_id, $tmp)) $en = 1;
                }
            }
            if (!$en){
                unset($view->payment_methods[$k]);
            }
        }
        
        if ($params['priority_shipping'] && count($view->payment_methods)==0){
            $view->payment_methods = $allpayments;
            
            $query = "SELECT payments FROM `#__jshopping_shipping_method_price` WHERE `sh_pr_method_id` = '" . intval($params['priority_shipping']) . "' ";
            $db->setQuery($query);
            $strpayment = $db->loadResult();
            if ($strpayment==""){
                return 1;
            }else{
                $payments = explode(",", $strpayment);
                foreach($view->payment_methods as $k=>$v){
                    if (!in_array($v->payment_id, $payments)) unset($view->payment_methods[$k]);
                }
            }
        }
		}
		//////////////////
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep3View', array(&$view));
        $view->display();    
    }
    
    public function step3save()
    {
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(3);
        
        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();
        $post = JFactory::getApplication()->input->post->getArray();

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeSaveCheckoutStep3save', array(&$post) );
        
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        
        $user = JFactory::getUser();
        $adv_user = JSFactory::getUser();
        
        $payment_method = JFactory::getApplication()->input->getVar('payment_method'); //class payment method
        $params = JFactory::getApplication()->input->getVar('params');
        if (isset($params[$payment_method])){
            $params_pm = $params[$payment_method];
        }else{
            $params_pm = '';
        }
        
        $paym_method = JSFactory::getTable('paymentmethod', 'jshop');
        $paym_method->class = $payment_method;
        $payment_method_id = $paym_method->getId();
        $paym_method->load($payment_method_id);
        $pmconfigs = $paym_method->getConfigs();
        $paymentsysdata = $paym_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        if ($paymentsysdata->paymentSystemError || $paym_method->payment_publish==0){
            $cart->setPaymentParams('');
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3',0,1,$jshopConfig->use_ssl));
            return 0;
        }
        if ($payment_system){
            if (!$payment_system->checkPaymentInfo($params_pm, $pmconfigs)){
                $cart->setPaymentParams('');
                \JFactory::getApplication()->enqueueMessage($payment_system->getErrorMessage(),'error');
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3',0,1,$jshopConfig->use_ssl));
                return 0;
            }            
        }
        
        $paym_method->setCart($cart);
        $cart->setPaymentId($payment_method_id);
        $price = $paym_method->getPrice();
        $cart->setPaymentDatas($price, $paym_method);
        
        if (isset($params[$payment_method])) {
            $cart->setPaymentParams($params_pm);
        } else {
            $cart->setPaymentParams('');
        }
        
        $adv_user->saveTypePayment($payment_method_id);
        
        $dispatcher->triggerEvent( 'onAfterSaveCheckoutStep3save', array(&$adv_user, &$paym_method, &$cart) );
        
        if ($jshopConfig->without_shipping) {
            $checkout->setMaxStep(5);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            return 0; 
        }
        
		if ($jshopConfig->step_4_3){
            $checkout->setMaxStep(5);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
        }else{
			$checkout->setMaxStep(4);
			$this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4',0,1,$jshopConfig->use_ssl));
		}
    }
    
    public function step4()
    {
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(4);
        
        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadCheckoutStep4', array() );

        appendPathWay(JText::_('COM_SMARTSHOP_CHECKOUT_SHIPPING'));
        $seo = JSFactory::getTable("seo", "jshop");
        $seodata = $seo->loadData("checkout-shipping");
        if ($seodata->title==""){
            $seodata->title = JText::_('COM_SMARTSHOP_CHECKOUT_SHIPPING');
        }
        
		setSeoMetaData($seodata->title);
        
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        
        $user = JFactory::getUser();
        $adv_user = JSFactory::getUser();

        $checkout_navigator = $this->_showCheckoutNavigation(4);
        if ($jshopConfig->show_cart_all_step_checkout){
            $small_cart = $this->_showSmallCart(4);
        }else{
            $small_cart = '';
        }
        
        if ($jshopConfig->without_shipping){
        	$checkout->setMaxStep(5);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            return 0; 
        }
        
        $shippingmethod = JSFactory::getTable('shippingMethod', 'jshop');
        $shippingmethodprice = JSFactory::getTable('shippingMethodPrice', 'jshop');
        
        if ($adv_user->delivery_adress){
            $id_country = $adv_user->d_country;
        }else{
            $id_country = $adv_user->country;
        }
        if (!$id_country) $id_country = $jshopConfig->default_country;
        
        if (!$id_country){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_REGWARN_COUNTRY'),'error');
        }
        
        if ($jshopConfig->show_delivery_time_checkout){
            $deliverytimes = JSFactory::getAllDeliveryTime();
            $deliverytimes[0] = '';
        }
        if ($jshopConfig->show_delivery_date){
            $deliverytimedays = JSFactory::getAllDeliveryTimeDays();
        }
        $sh_pr_method_id = $cart->getShippingPrId();
        $active_shipping = intval($sh_pr_method_id);
        $payment_id = $cart->getPaymentId();
        $shippings = $shippingmethod->getAllShippingMethodsCountry($id_country, $payment_id,1,$adv_user->usergroup_id);
        foreach($shippings as $key=>$value){
            $shippingmethodprice->load($value->sh_pr_method_id);

            $prices = $shippingmethodprice->calculateSum($cart);
            $shippings[$key]->calculeprice = $prices['shipping']+$prices['package'];
            $shippings[$key]->delivery = '';
            $shippings[$key]->delivery_date_f = '';
            if ($jshopConfig->show_delivery_time_checkout){
                $shippings[$key]->delivery = $deliverytimes[$value->delivery_times_id];
            }
            if ($jshopConfig->show_delivery_date){
                $day = $deliverytimedays[$value->delivery_times_id];
                if ($day){
                    $shippings[$key]->delivery_date = getCalculateDeliveryDay($day);
                    $shippings[$key]->delivery_date_f = formatdate($shippings[$key]->delivery_date);
                }
            }
            
            if ($value->sh_pr_method_id==$active_shipping){
                $params = $cart->getShippingParams();
            }else{
                $params = array();
            }
            
            $shippings[$key]->form = $shippingmethod->loadShippingForm($value->shipping_id, $value, $params);
        }

        if (!$active_shipping){
            foreach($shippings as $v){
                if ($v->shipping_id == $adv_user->shipping_id){
                    $active_shipping = $v->sh_pr_method_id;
                    break;
                }
            }
        }
        if (!$active_shipping){
            if (isset($shippings[0])){
                $active_shipping = $shippings[0]->sh_pr_method_id;
            }
        }
        
        if ($jshopConfig->hide_shipping_step){
            $first_shipping = $shippings[0]->sh_pr_method_id;
            if (!$first_shipping){
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SHIPPING'),'error');
                return 0;
            }
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4save&sh_pr_method_id='.$first_shipping,0,1,$jshopConfig->use_ssl));
            return 0;
        }
		
		$view_name = "checkout";
		//$template_path = $jshopConfig->template_path.$jshopConfig->template."/".$view_name;
        $view_config = array("template_path"=>viewOverride('checkout','shippings.php'));
        $view = $this->getView($view_name, getDocumentType(), '', $view_config);
        $view->setLayout("shippings");
        $view->set('shipping_methods', $shippings);
        $view->set('active_shipping', $active_shipping);
        $view->set('config', $jshopConfig);        
        $view->set('checkout_navigator', $checkout_navigator);
        $view->set('small_cart', $small_cart);
        $view->set('action', SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4save',0,0,$jshopConfig->use_ssl));
		
        //Product shipping
		$db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();
        
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();
        
        $query = "select * from #__jshopping_shipping_ext_calc where alias='sm_product'";
        $db->setQuery($query);
        $row = $db->loadObject();
        if ($row->params){
            $params = unserialize($row->params);
        }else{
            $params = array();
        }
        
        $priority_shipping = $params['priority_shipping'];
        
        $user = JFactory::getUser();
        if ($user->id){
            $adv_user = JSFactory::getUserShop();
        }else{
            $adv_user = JSFactory::getUserShopGuest();    
        }
        
        $shippingmethod = JTable::getInstance('shippingMethod', 'jshop');                    
        if ($adv_user->delivery_adress){
            $id_country = $adv_user->d_country;
        }else{
            $id_country = $adv_user->country;
        }
        if (!$id_country) $id_country = $jshopConfig->default_country;
        
        $payment_id = $cart->getPaymentId();
        
        
                
        $products = array();
        foreach($cart->products as $v){
            $products[] = $v['product_id'];
        }
     
    	$query = "select * from #__jshopping_shipping_method_price where product_id in (".implode(",", $products).")";
        $db->setQuery($query);
        $list = $db->loadObjectList();        
       
	
		$db = \JFactory::getDBO();
        $productsdata = array();
        foreach($list as $k=>$v){
			
			/*	$query = "SELECT `shipping_method_id` FROM `#__jshopping_shipping_method_price` WHERE `sh_pr_method_id` = ".$v->sh_pr_method_id;
				$db->setQuery($query);
				$v->shipping_method_id = $db->loadResult(); */
				$productsdata[$v->product_id][] = $v->sh_pr_method_id;
			
        }        
         
        $shfilter = array();
        foreach($view->shipping_methods as $k=>$v){
            $shfilter[] = $v->sh_pr_method_id;
        }
        $shfilter_data = $shfilter;
        foreach($productsdata as $pid=>$v){
            $filter = array();
            foreach($v as $k2=>$v2){
                $filter[$v2->sh_pr_method_id] = $v2->published;
				if($v2->published == 1){			
					$product_shipping_id[$pid][$v2->shipping_method_id] = $v2->shipping_method_id;
				}
            }
			
            
            foreach($shfilter as $k3=>$v3){
                if (isset($filter[$v3]) && $filter[$v3]==0){
                    unset($shfilter[$k3]);
                }
            }
        }
		
        $copy_shfilter = array();
        $copy_shfilter_ids = array();
        if (count($shfilter)==0){
			if($params['multiplying_qty']){
				foreach($product_shipping_id as $pr_id=>$ids){
					foreach($ids as $id){
						$shipping_ids[$id] = $id; 
					}
				}
				$shippings = $shippingmethod->getAllShippingMethodsCountry($id_country, $payment_id,1,$adv_user->usergroup_id);
				
				$default_sh_pr_method_id = 0;
				if ($priority_shipping){ 
					foreach($shippings as $key=>$value){                        
						if ($value->shipping_id==$priority_shipping){
							$default_sh_pr_method_id = $value->sh_pr_method_id;
							$default_product_id = $value->sh_pr_method_id;
							break;
						}
					}
					if ($default_sh_pr_method_id){
						$shfilter[] = $default_sh_pr_method_id;
						
					}else{						
						foreach($shippings as $key=>$value){ 
							if (in_array($value->shipping_id,$shipping_ids)){
								$default_sh_pr_method_id = $value->sh_pr_method_id;
								$shfilter[] = $default_sh_pr_method_id;														
								break;
							}
						}
						
					}
					foreach($product_shipping_id as $pr_id=>$ids){
						if(in_array($priority_shipping, $ids)){//print_r($ids)
							if(!in_array($priority_shipping, $copy_shfilter)){
								$copy_shfilter[] = $priority_shipping;
							}
															
						}else{
							if(count(array_diff($ids, $copy_shfilter)) == count($ids)){
								foreach($ids as $id){
									$copy_shfilter[] = $id;
									break;
								}
							}
						}
					}
					if(count($copy_shfilter) > 0){
						foreach($shippings as $key=>$value){ 
							if (in_array($value->shipping_id,$copy_shfilter)){
								$copy_shfilter_ids[] = $value->sh_pr_method_id;								
							}
						}
					}
					if (count($shfilter)==0){						
						$shippingmethodprice = JTable::getInstance('shippingMethodPrice', 'jshop');
						
						$lang = JSFactory::getLang();
						$query = "SELECT *, sh_pr_method.`".$lang->get("name")."` as name, `".$lang->get("description")."` as description FROM `#__jshopping_shipping_method_price` AS sh_pr_method 
								  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
								  INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
								  WHERE countries.country_id = '".$db->escape($id_country)."' AND sh_pr_method.published = '1'
								  ORDER BY sh_pr_method.ordering";
						$db->setQuery($query);
						$tmplist = $db->loadObjectList();
						
						foreach($tmplist as $k=>$value){
							if ($value->shipping_id==$priority_shipping){
								$shfilter[] = $value->sh_pr_method_id;
								$shippingmethodprice->load($value->sh_pr_method_id);						  
							}
						}
					}
				}else{
					foreach($shippings as $key=>$value){                        
							$priority_shipping = $value->shipping_id;
							$default_sh_pr_method_id = $value->sh_pr_method_id;
							$default_product_id = $value->sh_pr_method_id;
							break;
					}
					if ($default_sh_pr_method_id){
						$shfilter[] = $default_sh_pr_method_id;
					}else{						
						foreach($shippings as $key=>$value){ 
							if (in_array($value->shipping_id,$shipping_ids)){
								$default_sh_pr_method_id = $value->sh_pr_method_id;
								$shfilter[] = $default_sh_pr_method_id;														
								break;
							}
						}
					}
					foreach($product_shipping_id as $pr_id=>$ids){
						if(in_array($priority_shipping, $ids)){
							if(!in_array($priority_shipping, $copy_shfilter)){
								$copy_shfilter[] = $priority_shipping;
							}								
						}else{
							if(count(array_diff($ids, $copy_shfilter)) == count($ids)){
								foreach($ids as $id){
									$copy_shfilter[] = $id;
									break;
								}
							}
						}
					}
					if(count($copy_shfilter) > 0){
						foreach($shippings as $key=>$value){ 
							if (in_array($value->shipping_id,$copy_shfilter)){
								$copy_shfilter_ids[] = $value->sh_pr_method_id;								
							}
						}
					}
				
					if (count($shfilter)==0){
						
						$shippingmethodprice = JTable::getInstance('shippingMethodPrice', 'jshop');
						
						$lang = JSFactory::getLang();
						$query = "SELECT *, sh_pr_method.`".$lang->get("name")."` as name, `".$lang->get("description")."` as description FROM `#__jshopping_shipping_method_price` AS sh_pr_method 
								  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
								  INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
								  WHERE countries.country_id = '".$db->escape($id_country)."' AND sh_pr_method.published = '1'
								  ORDER BY sh_pr_method.ordering";
						$db->setQuery($query);
						$tmplist = $db->loadObjectList();
						
						foreach($tmplist as $k=>$value){
							if ($value->shipping_id==$priority_shipping){
								$shfilter[] = $value->sh_pr_method_id;
								$shippingmethodprice->load($value->sh_pr_method_id);							  
							}
						}
					}
				}
			}elseif ($priority_shipping){
                    
                $shippings = $shippingmethod->getAllShippingMethodsCountry($id_country, $payment_id,1,$adv_user->usergroup_id);
                $default_sh_pr_method_id = 0;
                foreach($shippings as $key=>$value){                        
                    if ($value->shipping_id==$priority_shipping){
                        $default_sh_pr_method_id = $value->sh_pr_method_id;
                        break;
                    }
                }

                if ($default_sh_pr_method_id){
                    $shfilter[] = $default_sh_pr_method_id;
                }
                
                if (count($shfilter)==0){
                    
                    $shippingmethodprice = JTable::getInstance('shippingMethodPrice', 'jshop');
                    
                    $lang = JSFactory::getLang();
                    $query = "SELECT *, sh_pr_method.`".$lang->get("name")."` as name, `".$lang->get("description")."` as description FROM `#__jshopping_shipping_method_price` AS sh_pr_method 
                              INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
                              INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
                              WHERE countries.country_id = '".$db->escape($id_country)."' AND sh_pr_method.published = '1'
                              ORDER BY sh_pr_method.ordering";
                    $db->setQuery($query);
                    $tmplist = $db->loadObjectList();
                    
                    foreach($tmplist as $k=>$value){
                        if ($value->shipping_id==$priority_shipping){
                            $shfilter[] = $value->sh_pr_method_id;
                            $shippingmethodprice->load($value->sh_pr_method_id);
                        }
                    }
                }
            }
        }
      
		$shipping_methods = $view->shipping_methods;
		//print_r($copy_shfilter_ids);die;
      //print_r($shippings);die; print_r($view->shipping_methods);die;
        foreach($view->shipping_methods as $k=>$v){
            if (!in_array($v->sh_pr_method_id, $shfilter)){
                unset($view->shipping_methods[$k]);
            }
        }
		$names = '';
		$shipping_stand_price = 0;
		$calculeprice = 0;
        if(count($copy_shfilter_ids) > 0 && count($copy_shfilter_ids) != count($shfilter)){
			foreach($shipping_methods as $k=>$v){
				if (!in_array($v->sh_pr_method_id, $shfilter) && in_array($v->sh_pr_method_id, $copy_shfilter_ids)){
					$name .= ', '.$v->name;
					$shipping_stand_price += $v->shipping_stand_price;
					$calculeprice += $v->calculeprice;
				}
			}	
			foreach($view->shipping_methods as $k=>$v){	
				$v->name .= $name;
				$v->shipping_stand_price += $shipping_stand_price;
				$v->calculeprice += $calculeprice;
			}		
		}
		
        if (!in_array($view->active_shipping, $shfilter) && $shfilter[0]){
            $view->active_shipping = $shfilter[0];
        }elseif(!in_array($view->active_shipping, $shfilter) && !$shfilter[0]){
			foreach($shfilter as $val){
				$view->active_shipping = $val;
				break;
			}
		}
		///////////////////
        
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep4View', array(&$view));
        $view->display();
    }
    
    public function step4save()
    {
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
        $checkout = JSFactory::getModel('checkout', 'jshop');
    	$checkout->checkStep(4);
        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveCheckoutStep4save', array());

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        
        $user = JFactory::getUser();
        $adv_user = JSFactory::getUser();
        
        if ($adv_user->delivery_adress){
            $id_country = $adv_user->d_country;
        }else{
            $id_country = $adv_user->country;
        }
        if (!$id_country) $id_country = $jshopConfig->default_country;
        
        $sh_pr_method_id = JFactory::getApplication()->input->getInt('sh_pr_method_id');
                
        $shipping_method_price = JSFactory::getTable('shippingMethodPrice', 'jshop');
        $shipping_method_price->load($sh_pr_method_id);
        
        $sh_method = JSFactory::getTable('shippingMethod', 'jshop');
        $sh_method->load($shipping_method_price->shipping_method_id);
        
        if (!$shipping_method_price->sh_pr_method_id){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SHIPPING'),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4',0,1,$jshopConfig->use_ssl));
            return 0;
        }
        
        if (!$shipping_method_price->isCorrectMethodForCountry($id_country)){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SHIPPING'),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4',0,1,$jshopConfig->use_ssl));
            return 0;
        }
        
        if (!$sh_method->shipping_id){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SHIPPING'),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4',0,1,$jshopConfig->use_ssl));
            return 0;
        }
        
        $allparams = JFactory::getApplication()->input->getVar('params');
        $params = $allparams[$sh_method->shipping_id];
        
        if (isset($params)){
            $cart->setShippingParams($params);
        }else{
            $cart->setShippingParams('');
        }
        
        $shippingForm = $sh_method->getShippingForm();
        
        if ($shippingForm && !$shippingForm->check($params, $sh_method)){
            \JFactory::getApplication()->enqueueMessage($shippingForm->getErrorMessage(),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step4',0,1,$jshopConfig->use_ssl));
            return 0;
        }
        
        $prices = $shipping_method_price->calculateSum($cart);
        $cart->setShippingId($sh_method->shipping_id);
        $cart->setShippingPrId($sh_pr_method_id);
        $cart->setShippingsDatas($prices, $shipping_method_price);
        
        if ($jshopConfig->show_delivery_date){
            $delivery_date = '';
            $deliverytimedays = JSFactory::getAllDeliveryTimeDays();
            $day = $deliverytimedays[$shipping_method_price->delivery_times_id];
            if ($day){
                $delivery_date = getCalculateDeliveryDay($day);
            }else{
                if ($jshopConfig->delivery_order_depends_delivery_product){
                    $day = $cart->getDeliveryDaysProducts();
                    if ($day){
                        $delivery_date = getCalculateDeliveryDay($day);                    
                    }
                }
            }
            $cart->setDeliveryDate($delivery_date);
        }

        //update payment price
        $payment_method_id = $cart->getPaymentId();
        if ($payment_method_id){
            $paym_method = JSFactory::getTable('paymentmethod', 'jshop');
            $paym_method->load($payment_method_id);
            $cart->setDisplayItem(1, 1);
            $paym_method->setCart($cart);
            $price = $paym_method->getPrice();
            $cart->setPaymentDatas($price, $paym_method);
        }

        $adv_user->saveTypeShipping($sh_method->shipping_id);
        //Product shipping
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$prices = $shipping_method_price->calculateSum($cart);
		$products = array();
        foreach($cart->products as $v){
            $products[] = $v['product_id'];
        }
		
     
	
		$query = "select * from #__jshopping_products_shipping where product_id in (".implode(",", $products).")";
        $db->setQuery($query);
        $list = $db->loadObjectList();        
       
		$db = \JFactory::getDBO();
        $productsdata = array();
        foreach($list as $k=>$v){			
				$query = "SELECT `shipping_method_id` FROM `#__jshopping_shipping_method_price` WHERE `sh_pr_method_id` = ".$v->sh_pr_method_id;
				$db->setQuery($query);
				$v->shipping_method_id = $db->loadResult(); 
				$productsdata[$v->product_id][] = $v;			
        }        
         
        $shfilter = array();
		$shfilter[] = $shipping_method_price->sh_pr_method_id;
        $shfilter_data = $shfilter;
        foreach($productsdata as $pid=>$v){
            $filter = array();
            foreach($v as $k2=>$v2){
                $filter[$v2->sh_pr_method_id] = $v2->published;
				if($v2->published == 1){			
					$product_shipping_id[$pid][$v2->shipping_method_id] = $v2->shipping_method_id;
				}
            }
			
            
            foreach($shfilter as $k3=>$v3){
                if (isset($filter[$v3]) && $filter[$v3]==0){
                    unset($shfilter[$k3]);
                }
            }
        }
		$query = "select * from #__jshopping_shipping_ext_calc where alias='sm_product'";
        $db->setQuery($query);
        $row = $db->loadObject();
        if ($row->params){
            $params = unserialize($row->params);
        }else{
            $params = array();
        }
        if ($adv_user->delivery_adress){
			$id_country = $adv_user->d_country;
		}else{
			$id_country = $adv_user->country;
		}
		if (!$id_country) $id_country = $jshopConfig->default_country;
        $priority_shipping = $params['priority_shipping'];
        $shippingmethod = JTable::getInstance('shippingMethod', 'jshop');  
		$shippings = $shippingmethod->getAllShippingMethodsCountry($id_country, $payment_id,1,$adv_user->usergroup_id);
		
        $copy_shfilter = array();
        $copy_shfilter_ids = array();
        if (count($shfilter)==0){
			if($params['multiplying_qty']){
				foreach($product_shipping_id as $pr_id=>$ids){
					foreach($ids as $id){
						$shipping_ids[$id] = $id; 
					}
				}
				
				
				
				$default_sh_pr_method_id = 0;
				if ($priority_shipping){ 
					foreach($shippings as $key=>$value){                        
						if ($value->shipping_id==$priority_shipping){
							$default_sh_pr_method_id = $value->sh_pr_method_id;
							$default_product_id = $value->sh_pr_method_id;
							break;
						}
					}
					if ($default_sh_pr_method_id){
						$shfilter[] = $default_sh_pr_method_id;
						
					}else{						
						foreach($shippings as $key=>$value){ 
							if (in_array($value->shipping_id,$shipping_ids)){
								$default_sh_pr_method_id = $value->sh_pr_method_id;
								$shfilter[] = $default_sh_pr_method_id;														
								break;
							}
						}
						
					}
					foreach($product_shipping_id as $pr_id=>$ids){
						if(in_array($priority_shipping, $ids)){//print_r($ids)
							if(!in_array($priority_shipping, $copy_shfilter)){
								$copy_shfilter[] = $priority_shipping;
							}
															
						}else{
							if(count(array_diff($ids, $copy_shfilter)) == count($ids)){
								foreach($ids as $id){
									$copy_shfilter[] = $id;
									break;
								}
							}
						}
					}
					if(count($copy_shfilter) > 0){
						foreach($shippings as $key=>$value){ 
							if (in_array($value->shipping_id,$copy_shfilter)){
								$copy_shfilter_ids[] = $value->sh_pr_method_id;								
							}
						}
					}
					if (count($shfilter)==0){						
						$shippingmethodprice = JTable::getInstance('shippingMethodPrice', 'jshop');
						
						$lang = JSFactory::getLang();
						$query = "SELECT *, sh_pr_method.`".$lang->get("name")."` as name, `".$lang->get("description")."` as description FROM `#__jshopping_shipping_method_price` AS sh_pr_method 
								  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
								  INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
								  WHERE countries.country_id = '".$db->escape($id_country)."' AND sh_pr_method.published = '1'
								  ORDER BY sh_pr_method.ordering";
						$db->setQuery($query);
						$tmplist = $db->loadObjectList();
						
						foreach($tmplist as $k=>$value){
							if ($value->shipping_id==$priority_shipping){
								$shfilter[] = $value->sh_pr_method_id;
								$shippingmethodprice->load($value->sh_pr_method_id);							  
							}
						}
					}
				}else{
					foreach($shippings as $key=>$value){                        
							$priority_shipping = $value->shipping_id;
							$default_sh_pr_method_id = $value->sh_pr_method_id;
							$default_product_id = $value->sh_pr_method_id;
							break;
					}
					if ($default_sh_pr_method_id){
						$shfilter[] = $default_sh_pr_method_id;
					}else{						
						foreach($shippings as $key=>$value){ 
							if (in_array($value->shipping_id,$shipping_ids)){
								$default_sh_pr_method_id = $value->sh_pr_method_id;
								$shfilter[] = $default_sh_pr_method_id;														
								break;
							}
						}
					}
					foreach($product_shipping_id as $pr_id=>$ids){
						if(in_array($priority_shipping, $ids)){
							if(!in_array($priority_shipping, $copy_shfilter)){
								$copy_shfilter[] = $priority_shipping;
							}								
						}else{
							if(count(array_diff($ids, $copy_shfilter)) == count($ids)){
								foreach($ids as $id){
									$copy_shfilter[] = $id;
									break;
								}
							}
						}
					}
					if(count($copy_shfilter) > 0){
						foreach($shippings as $key=>$value){ 
							if (in_array($value->shipping_id,$copy_shfilter)){
								$copy_shfilter_ids[] = $value->sh_pr_method_id;								
							}
						}
					}
				
					if (count($shfilter)==0){
						
						$shippingmethodprice = JTable::getInstance('shippingMethodPrice', 'jshop');
						
						$lang = JSFactory::getLang();
						$query = "SELECT *, sh_pr_method.`".$lang->get("name")."` as name, `".$lang->get("description")."` as description FROM `#__jshopping_shipping_method_price` AS sh_pr_method 
								  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
								  INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
								  WHERE countries.country_id = '".$db->escape($id_country)."' AND sh_pr_method.published = '1'
								  ORDER BY sh_pr_method.ordering";
						$db->setQuery($query);
						$tmplist = $db->loadObjectList();
						
						foreach($tmplist as $k=>$value){
							if ($value->shipping_id==$priority_shipping){
								$shfilter[] = $value->sh_pr_method_id;
								$shippingmethodprice->load($value->sh_pr_method_id);							  
							}
						}
					}
				}
			}
        }
		$shipping_methods = $shippings;
		$shipping_methods1 = $shippings;
		
        foreach($shipping_methods as $k=>$v){
            if (!in_array($v->sh_pr_method_id, $shfilter)){
                unset($shipping_methods[$k]);
            }
        }
		$names = '';
		$shipping_stand_price = 0;
		$calculeprice = 0;		
        if(count($copy_shfilter_ids) > 0 && count($copy_shfilter_ids) != count($shfilter)){
			foreach($shipping_methods1 as $k=>$v){ 
				if (!in_array($v->sh_pr_method_id, $shfilter)  && in_array($v->sh_pr_method_id, $copy_shfilter_ids)){
					$shippingmethodpr = JTable::getInstance('shippingMethodPrice', 'jshop');
					$shippingmethodpr->load($v->sh_pr_method_id);
					$prices = ($shippingmethodpr->calculateSum($cart));//die;
					$name .= ', '.$v->name;
					$shipping_stand_price += $prices['shipping'];
					$calculeprice += $v->calculeprice;
				}
			}		
			foreach($shipping_methods as $k=>$v){	
					$shippingmethodpr = JTable::getInstance('shippingMethodPrice', 'jshop');
					$shippingmethodpr->load($v->sh_pr_method_id);
					$prices = ($shippingmethodpr->calculateSum($cart));
				$v->name .= $name;
				$v->shipping_stand_price = $prices['shipping'] + $shipping_stand_price;
				$v->calculeprice += $calculeprice;
				if($shipping_method_price->sh_pr_method_id == $v->sh_pr_method_id){ 
					$shipping_method_price->shipping_stand_price = $v->shipping_stand_price;		
				}
			}			
			$shparams = array();
			
			foreach($copy_shfilter as $k=>$v){	
				if(strlen($shparams['shipping_id'])){$shparams['shipping_id'] .= ',';}
				$shparams['shipping_id'] .= $v;
			}
			$cart->setShippingParams($shparams);	
		}
		
		$prices = array('shipping'=>$shipping_method_price->shipping_stand_price);
        $cart->setShippingId($sh_method->shipping_id);
        $cart->setShippingPrId($shipping_method_price->sh_pr_method_id);
        $cart->setShippingsDatas($prices, $shipping_method_price);
		/////////////////	
        $dispatcher->triggerEvent('onAfterSaveCheckoutStep4', array(&$adv_user, &$sh_method, &$shipping_method_price, &$cart));   
		if ($jshopConfig->step_4_3 && !$jshopConfig->without_payment){            
            $checkout->setMaxStep(3);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3',0,1,$jshopConfig->use_ssl));
        }else{		
			$checkout->setMaxStep(5);
			$this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
		}
    }
    
    public function step5()
    {
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(5);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadCheckoutStep5', array() );

        appendPathWay(JText::_('COM_SMARTSHOP_CHECKOUT_PREVIEW'));
        $seo = JSFactory::getTable("seo", "jshop");
        $seodata = $seo->loadData("checkout-preview");
        if ($seodata->title==""){
            $seodata->title = JText::_('COM_SMARTSHOP_CHECKOUT_PREVIEW');
        }
        
		setSeoMetaData($seodata->title);

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();

        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig(); 
        $user = JFactory::getUser();
        $adv_user = JSFactory::getUser();

        $sh_method = JSFactory::getTable('shippingMethod', 'jshop');
        $shipping_method_id = $cart->getShippingId();
        $sh_method->load($shipping_method_id);
        
        $sh_mt_pr = JSFactory::getTable('shippingMethodPrice', 'jshop');
        $sh_mt_pr->load($cart->getShippingPrId());
        if ($jshopConfig->show_delivery_time_checkout){
            $deliverytimes = JSFactory::getAllDeliveryTime();
            $deliverytimes[0] = '';
            $delivery_time = $deliverytimes[$sh_mt_pr->delivery_times_id];
            if (!$delivery_time && $jshopConfig->delivery_order_depends_delivery_product){
                $delivery_time = $cart->getDelivery();
            }
        }else{
            $delivery_time = '';
        }
        if ($jshopConfig->show_delivery_date){
            $delivery_date = $cart->getDeliveryDate();
            if ($delivery_date){
                $delivery_date = formatdate($cart->getDeliveryDate());
            }
        }else{
            $delivery_date = '';
        }
        
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $payment_method_id = $cart->getPaymentId();
		$pm_method->load($payment_method_id); 

        $lang = JSFactory::getLang();
        $field_country_name = $lang->get("name");
        
        $invoice_info = array();
        $country = JSFactory::getTable('country', 'jshop');
        $country->load($adv_user->country);
        $invoice_info['f_name'] = $adv_user->f_name;
        $invoice_info['l_name'] = $adv_user->l_name;
        $invoice_info['firma_name'] = $adv_user->firma_name;
        $invoice_info['street'] = $adv_user->street;
        $invoice_info['street_nr'] = $adv_user->street_nr;
        $invoice_info['zip'] = $adv_user->zip;
        $invoice_info['state'] = $adv_user->state;
        $invoice_info['city'] = $adv_user->city;
        $invoice_info['country'] = $country->$field_country_name;
        $invoice_info['home'] = $adv_user->home;
        $invoice_info['apartment'] = $adv_user->apartment;
        
		if ($adv_user->delivery_adress){
            $country = JSFactory::getTable('country', 'jshop');
            $country->load($adv_user->d_country);
			$delivery_info['f_name'] = $adv_user->d_f_name;
            $delivery_info['l_name'] = $adv_user->d_l_name;
			$delivery_info['firma_name'] = $adv_user->d_firma_name;
			$delivery_info['street'] = $adv_user->d_street;
            $delivery_info['street_nr'] = $adv_user->d_street_nr;
			$delivery_info['zip'] = $adv_user->d_zip;
			$delivery_info['state'] = $adv_user->d_state;
            $delivery_info['city'] = $adv_user->d_city;
			$delivery_info['country'] = $country->$field_country_name;
            $delivery_info['home'] = $adv_user->d_home;
            $delivery_info['apartment'] = $adv_user->d_apartment;
		} else {
            $delivery_info = $invoice_info;
		}
        
        $no_return = 0;
        if ($jshopConfig->no_return_all){
            $no_return = 1;
        }
        
        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields['address'];
        $count_filed_delivery = $jshopConfig->getEnableDeliveryFiledRegistration('address');
        
        $checkout_navigator = $this->_showCheckoutNavigation(5);
        $small_cart = $this->_showSmallCart(5);

		$view_name = "checkout";
        $view_config = array("template_path"=>viewOverride($view_name,'previewfinish.php'));
        $view = $this->getView($view_name, getDocumentType(), '', $view_config);
        $view->setLayout("previewfinish");
		$sum = (!$jshopConfig->without_shipping) ? $cart->getSum(1, 1, 1) : $cart->getSum(0, 1, 1);
        $session->set('previousSum', $sum);
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep5', array(&$sh_method, &$pm_method, &$delivery_info, &$cart, &$view));
        $lang = JSFactory::getLang();
        $name = $lang->get("name");
        $sh_method->name = $sh_method->$name;
        $view->set('no_return', $no_return);
		$view->set('sh_method', $sh_method );
		$view->set('payment_name', $pm_method->$name);
        $view->set('delivery_info', $delivery_info);
		$view->set('invoice_info', $invoice_info);
        $view->set('action', SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5save',0,0, $jshopConfig->use_ssl));       
        $view->set('config', $jshopConfig);
        $view->set('delivery_time', $delivery_time);
        $view->set('delivery_date', $delivery_date);
        $view->set('checkout_navigator', $checkout_navigator);
        $view->set('small_cart', $small_cart);
		$view->set('count_filed_delivery', $count_filed_delivery);
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutStep5View', array(&$view));
    	$view->display();
    }

    public function step5save()
    {
		$jshopConfig = JSFactory::getConfig();
		$app = JFactory::getApplication();
        $app->redirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout',1,1,$jshopConfig->use_ssl));
		$session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $mainframe = JFactory::getApplication();
        $checkout->checkStep(5);
		$checkagb = JFactory::getApplication()->input->getVar('agb');
        $dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onLoadStep5save', array(&$checkagb));
        
        $lang = JSFactory::getLang();
        $user = JFactory::getUser();
        $adv_user = JSFactory::getUser();
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        $cart->setDisplayItem(1, 1);
        $cart->setDisplayFreeAttributes();
		
		if ($jshopConfig->check_php_agb && $checkagb!='on'){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_AGB'),'error');
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            return 0;
        }

        if (!$cart->checkListProductsQtyInStore()){
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view',1,1));
            return 0;
        }
		if (!$session->get('checkcoupon')){
            if (!$cart->checkCoupon()){
                $cart->setRabatt(0,0,0);
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_RABATT_NON_CORRECT'));
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view',1,1));
                return 0;
            }
            $session->set('checkcoupon', 1);
        }

        $orderNumber = $jshopConfig->getNextOrderNumber();
        $jshopConfig->updateNextOrderNumber();

        $payment_method_id = $cart->getPaymentId();
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $pm_method->load($payment_method_id);
		$payment_method = $pm_method->payment_class;

        if ($jshopConfig->without_payment){
            $pm_method->payment_type = 1;
            $paymentSystemVerySimple = 1; 
        }else{
            $paymentsysdata = $pm_method->getPaymentSystemData();
            $payment_system = $paymentsysdata->paymentSystem;
            if ($paymentsysdata->paymentSystemVerySimple){
                $paymentSystemVerySimple = 1;
            }
            if ($paymentsysdata->paymentSystemError){
                $cart->setPaymentParams("");
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3',0,1,$jshopConfig->use_ssl));
                return 0;
            }
        }

        $order = JSFactory::getTable('order', 'jshop');
        $arr_property = $order->getListFieldCopyUserToOrder();
        foreach($adv_user as $key => $value){
            if (in_array($key, $arr_property)){
                $order->$key = $value;
            }
        }

        $sh_mt_pr = JSFactory::getTable('shippingMethodPrice', 'jshop');
        $sh_mt_pr->load($cart->getShippingPrId());

        $order->order_date = $order->order_m_date = getJsDate();
        $order->order_tax = $cart->getTax(1, 1, 1);
        $order->setTaxExt($cart->getTaxExt(1, 1, 1));
        $order->order_subtotal = $cart->getPriceProducts();
        $order->order_shipping = $cart->getShippingPrice();
        $order->order_payment = $cart->getPaymentPrice();
        $order->order_discount = $cart->getDiscountShow();
        $order->shipping_tax = $cart->getShippingPriceTaxPercent();
        $order->setShippingTaxExt($cart->getShippingTaxList());
        $order->payment_tax = $cart->getPaymentTaxPercent();
        $order->setPaymentTaxExt($cart->getPaymentTaxList());
        $order->order_package = $cart->getPackagePrice();
        $order->setPackageTaxExt($cart->getPackageTaxList());
        $order->order_total = $cart->getSum(1, 1, 1);
        $order->currency_exchange = $jshopConfig->currency_value;
        $order->vendor_type = $cart->getVendorType();
        $order->vendor_id = $cart->getVendorId();
        $order->order_status = $pm_method->payment_status;
        $order->shipping_method_id = $cart->getShippingId();
        $order->payment_method_id = $cart->getPaymentId();
        $order->delivery_times_id = $sh_mt_pr->delivery_times_id;
        if ($jshopConfig->delivery_order_depends_delivery_product){
            $order->delivery_time = $cart->getDelivery();
        }
        if ($jshopConfig->show_delivery_date){
            $order->delivery_date = $cart->getDeliveryDate();
        }
        $order->coupon_id = $cart->getCouponId();

        $pm_params = $cart->getPaymentParams();

        if (is_array($pm_params) && !$paymentSystemVerySimple){
            $payment_system->setParams($pm_params);
            $payment_params_names = $payment_system->getDisplayNameParams();
			$pm_params_data = $payment_system->getPaymentParamsData($pm_params);
			$order->payment_params = getTextNameArrayValue($payment_params_names, $pm_params_data);						
            $order->setPaymentParamsData($pm_params);
        }
        
        $sh_params = $cart->getShippingParams();
        if (is_array($sh_params)){
            $sh_method = JSFactory::getTable('shippingMethod', 'jshop');
            $sh_method->load($cart->getShippingId());
            $shippingForm = $sh_method->getShippingForm();
            if ($shippingForm){
				$shippingForm->setParams($sh_params);
                $shipping_params_names = $shippingForm->getDisplayNameParams();            
                $order->shipping_params = getTextNameArrayValue($shipping_params_names, $sh_params);
            }
            $order->setShippingParamsData($sh_params);
        }
        
        $name = $lang->get("name");
        $order->ip_address = $_SERVER['REMOTE_ADDR'];
        $order->order_add_info = JFactory::getApplication()->input->getVar('order_add_info','');
        $order->currency_code = $jshopConfig->currency_code;
        $order->currency_code_iso = $jshopConfig->currency_code_iso;
        $order->order_number = $order->formatOrderNumber($orderNumber);
        $order->order_hash = md5(time().$order->order_total.$order->user_id);
        $order->file_hash = md5(time().$order->order_total.$order->user_id."hashfile");
        $order->display_price = $jshopConfig->display_price_front_current;
        $order->lang = $jshopConfig->getLang();
        
        if ($order->client_type){
            $order->client_type_name = $jshopConfig->user_field_client_type[$order->client_type];
        }else{
            $order->client_type_name = "";
        }
		
		if ($order->order_total==0){
            $pm_method->payment_type = 1;
            $jshopConfig->without_payment = 1;
            $order->order_status = $jshopConfig->payment_status_paid;
        }
        
        if ($pm_method->payment_type == 1){
            $order->order_created = 1; 
        }else {
            $order->order_created = 0;
        }
        
        if (!$adv_user->delivery_adress) $order->copyDeliveryData();
        
        $dispatcher->triggerEvent('onBeforeCreateOrder', array(&$order));

        $order->store();

        $dispatcher->triggerEvent('onAfterCreateOrder', array(&$order));

        if ($cart->getCouponId()){
            $coupon = JSFactory::getTable('coupon', 'jshop');
            $coupon->load($cart->getCouponId());
            if ($coupon->finished_after_used){
                $free_discount = $cart->getFreeDiscount();
                if ($free_discount > 0){
                    $coupon->coupon_value = $free_discount / $jshopConfig->currency_value;
                }else{
                    $coupon->used = $adv_user->user_id;
                }
            }
			$coupon->count_use++;
			$coupon->store();
        }

        $order->saveOrderItem($cart->products);

		$dispatcher->triggerEvent('onAfterCreateOrderFull', array(&$order));
		
        $session->set("jshop_end_order_id", $order->order_id);

        $order_history = JSFactory::getTable('orderHistory', 'jshop');
        $order_history->order_id = $order->order_id;
        $order_history->order_status_id = $order->order_status;
        $order_history->status_date_added = $order->order_date;
        $order_history->customer_notify = 1;
        $order_history->store();
        
        if ($pm_method->payment_type == 1){
            if ($jshopConfig->order_stock_removed_only_paid_status){
                $product_stock_removed = (in_array($order->order_status, $jshopConfig->payment_status_enable_download_sale_file));
            }else{
                $product_stock_removed = 1;
            }
            if ($product_stock_removed){
                $order->changeProductQTYinStock("-");
            }

            $checkout->sendOrderEmail($order->order_id);
        }
        
        $dispatcher->triggerEvent('onEndCheckoutStep5', array(&$order) );

        $session->set("jshop_send_end_form", 0);
        
        if ($jshopConfig->without_payment){
            $checkout->setMaxStep(10);
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish',0,1,$jshopConfig->use_ssl));
            return 0;
        }
        
        $pmconfigs = $pm_method->getConfigs();
        
        $task = "step6";
        if (isset($pmconfigs['windowtype']) && $pmconfigs['windowtype']==2){
            $task = "step6iframe";
            $session->set("jsps_iframe_width", $pmconfigs['iframe_width']);
            $session->set("jsps_iframe_height", $pmconfigs['iframe_height']);
        }
        $checkout->setMaxStep(6);
        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task='.$task,0,1,$jshopConfig->use_ssl));
    }

    public function step6iframe()
    {
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(6);
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $width = $session->get("jsps_iframe_width");
        $height = $session->get("jsps_iframe_height");
        if (!$width) $width = 600;
        if (!$height) $height = 600;
        \JFactory::getApplication()->triggerEvent('onBeforeStep6Iframe', array(&$width, &$height));
        ?><iframe width="<?php print $width?>" height="<?php print $height?>" frameborder="0" src="<?php print SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step6&wmiframe=1',0,1,$jshopConfig->use_ssl)?>"></iframe><?php
    }

    public function step6()
    {
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(6);
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        header("Cache-Control: no-cache, must-revalidate");
        $order_id = $session->get('jshop_end_order_id');
        $wmiframe = JFactory::getApplication()->input->getInt("wmiframe");

        if (!$order_id){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_SESSION_FINISH'),'error');
            if (!$wmiframe){
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            }else{
                $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            }
        }
        
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);

        // user click back in payment system 
        $jshop_send_end_form = $session->get('jshop_send_end_form');
        if ($jshop_send_end_form == 1){
            $this->_cancelPayOrder($order_id);
            return 0;
        }

        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $payment_method_id = $order->payment_method_id;
        $pm_method->load($payment_method_id);
        $payment_method = $pm_method->payment_class; 
        
		$paymentsysdata = $pm_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        if ($paymentsysdata->paymentSystemVerySimple){
            $paymentSystemVerySimple = 1;
        }
        if ($paymentsysdata->paymentSystemError){
            $cart->setPaymentParams("");
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
            if (!$wmiframe){
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3',0,1,$jshopConfig->use_ssl));
            }else{
                $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step3',0,1,$jshopConfig->use_ssl));
            }
            return 0;
        }
		
        if ($pm_method->payment_type == 1 || $paymentSystemVerySimple) { 
            $checkout->setMaxStep(10);
            if (!$wmiframe){
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish',0,1,$jshopConfig->use_ssl));
            }else{
                $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish',0,1,$jshopConfig->use_ssl));
            }
            return 0;
        }

        \JFactory::getApplication()->triggerEvent('onBeforeShowEndFormStep6', array(&$order, &$cart, &$pm_method));
        $session->set('jshop_send_end_form', 1);
        $pmconfigs = $pm_method->getConfigs();
        $payment_system->showEndForm($pmconfigs, $order);
    }

    /**
     * DEPRECATED!!!
     * 
     * Use Step7 of qcheckout controller!!!
     */
    public function step7()
    {
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $wmiframe = JFactory::getApplication()->input->getInt("wmiframe");
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadStep7', array());
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        
        $str = "url: ".$_SERVER['REQUEST_URI']."\n";
        foreach($_POST as $k=>$v) $str .= $k."=".$v."\n";
        saveToLog("paymentdata.log", $str);
        
        $act = JFactory::getApplication()->input->getVar("act");
        $payment_method = JFactory::getApplication()->input->getVar("js_paymentclass");
        
        $pm_method->loadFromClass($payment_method);
        
        $paymentsysdata = $pm_method->getPaymentSystemData();
        $payment_system = $paymentsysdata->paymentSystem;
        if ($paymentsysdata->paymentSystemVerySimple){
            if (JFactory::getApplication()->input->getInt('no_lang')) JSFactory::loadLanguageFile();
            saveToLog("payment.log", "#001 - Error payment method file. PM ".$payment_method);
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
            return 0;
        } 
        if ($paymentsysdata->paymentSystemError){
            if (JFactory::getApplication()->input->getInt('no_lang')) JSFactory::loadLanguageFile();
            saveToLog("payment.log", "#002 - Error payment. CLASS ".$payment_method);
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
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
        
        if (JFactory::getApplication()->input->getInt('no_lang')){
            JSFactory::loadLanguageFile($order->getLang());
            $lang = JSFactory::getLang($order->getLang());
        }

        if ($checkHash && $order->order_hash != $hash){
            saveToLog("payment.log", "#003 - Error order hash. Order id ".$order_id);
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_ORDER_HASH'),'error');
            return 0;
        }
        
        if (!$order->payment_method_id){
            saveToLog("payment.log", "#004 - Error payment method id. Order id ".$order_id);
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
            return 0;
        }

        if ($order->payment_method_id!=$pm_method->payment_id){
            saveToLog("payment.log", "#005 - Error payment method set url. Order id ".$order_id);
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
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
        
        if ($restext!=''){
            saveToLog("payment.log", $restext);
        }        

        if ($status && !$order->order_created){
            $order->order_created = 1;
            $order->order_status = $status;
            $dispatcher->triggerEvent('onStep7OrderCreated', array(&$order, &$res, &$checkout, &$pmconfigs));
            $order->store();
            $checkout->sendOrderEmail($order->order_id);
            
            if ($jshopConfig->order_stock_removed_only_paid_status){
                $product_stock_removed = (in_array($status, $jshopConfig->payment_status_enable_download_sale_file));
            }else{
                $product_stock_removed = 1;
            }
            if ($product_stock_removed){
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

        if (in_array($rescode, array(0,3,4))){
            \JFactory::getApplication()->enqueueMessage($restext,'error');
            if (!$wmiframe){
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            }else{
                $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            }
            return 0;
        }else{
            $checkout->setMaxStep(10);
            if (!$wmiframe){
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish',0,1,$jshopConfig->use_ssl));
            }else{
                $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish',0,1,$jshopConfig->use_ssl));
            }
            return 1;
        }
    }

    public function finish()
    {
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $checkout->checkStep(10);
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $modelOfJsContent = JSFactory::getModel('contentFront', 'jshop');
        $order_id = $session->get('jshop_end_order_id');

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_SMARTSHOP_CHECKOUT_FINISH'));
        appendPathWay(JText::_('COM_SMARTSHOP_CHECKOUT_FINISH'));
/*
        $statictext = JSFactory::getTable("statictext","jshop");
        $rowstatictext = $statictext->loadData("order_finish_descr");
        $text = $rowstatictext->text;
*/
        $text = $modelOfJsContent->getTextContentByContentName('order_success_page');        

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutFinish', array(&$text, &$order_id));
        $session->set('cart_offer_and_order', 0);


        if (trim(strip_tags($text)) == '') {
            $text = '';
        }

        $this->noticeAdminWithALowAmountOfProducts($order_id);
        $this->noticeAdminWithALowAmountOfAttrs($order_id);

        $view_name = 'quick_checkout';
        $view_config = array("template_path"=>viewOverride($view_name,'finish.php'));
        $view = $this->getView($view_name, getDocumentType(), '', $view_config);
		$view->set('usersParams', JComponentHelper::getParams('com_users'));
        $view->set('currentUser', JFactory::getUser());        
        $view->setLayout("finish");
        $view->set('text', $text);
        $view->display();

        if ($order_id){
            $order = JSFactory::getTable('order', 'jshop');
            $order->load($order_id);
            $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
            $payment_method_id = $order->payment_method_id;
            $pm_method->load($payment_method_id);
            $paymentsysdata = $pm_method->getPaymentSystemData();
            $payment_system = $paymentsysdata->paymentSystem;
            if ($payment_system){
                $pmconfigs = $pm_method->getConfigs();
                $payment_system->complete($pmconfigs, $order, $pm_method);
            }
            $dispatcher->triggerEvent('onAfterDisplayCheckoutFinish', array(&$text, &$order, &$pm_method));
        }

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        $cart->getSum();
        $cart->clear();
        $checkout->deleteSession();
    }

    protected function noticeAdminWithALowAmountOfProducts($orderId) 
    {
        $productsModel = JSFactory::getModel('products');
        $arrWithProductsModelsSelectedByOrderId = $productsModel->getProductsByOrderId($orderId);
        
        if ( !empty($arrWithProductsModelsSelectedByOrderId) ) {

            $bodyEmailText = JText::_('COM_SMARTSHOP_ADMIN_NOTICE_EMAIL_TEXT');
            $nameLang = 'name_' . JComponentHelper::getParams('com_languages')->get('site');
            $isPresentSmallAmountOfProd = false;

            foreach($arrWithProductsModelsSelectedByOrderId as $key => $productModelFromArray) {

                if ( 1 != $productModelFromArray->unlimited && 1 == $productModelFromArray->low_stock_notify_status && $productModelFromArray->product_quantity <= $productModelFromArray->low_stock_number ) {
                    $title = $productModelFromArray->$nameLang ?: $productModelFromArray->{'name_en-GB'};
                    $isPresentSmallAmountOfProd = true;

                    $bodyEmailText .= $productModelFromArray->{'name_en-GB'} . ' ' . sprintf(JText::_('COM_SMARTSHOP_NUMBER_ITEMS_LEFT'), (int)$productModelFromArray->product_quantity) . '<br>';
                    
                }

            }

			$dataForTemplate = array('emailSubject'=>JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_PROD_TITLE'), 'emailBod'=>$bodyEmailText);
			$body = renderTemplateEmail('default', $dataForTemplate, 'emails');
	
            if ( $isPresentSmallAmountOfProd ) {
                $shMailer = new shMailer();
                $shMailer->sendMailToAdmin(JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_PROD_TITLE'), $body);   
            }    
        }        
    } 

    protected function noticeAdminWithALowAmountOfAttrs($orderId) 
    {
        $productsModel = JSFactory::getModel('products');
        /*
        $arrWithProdsModelsFromOrder = $productsModel->getProductsByOrderId($orderId);
        $arrWithProdsModelsFromOrder = getChangedArrKeyOnObjVal($arrWithProdsModelsFromOrder, 'product_id');
        $prodsIdsFromselectedOrder = getListSpecifiedAttrsFromArray($arrWithProdsModelsFromOrder, 'product_id');
        $productAttrsWithLowStock = JSFactory::getModel('ProductAttrs')->getLowStock($prodsIdsFromselectedOrder);
        */
        $productAttrsWithLowStock = JSFactory::getModel('ProductAttrs')->getLowStock();
        $arrWithProdsIdsForSelect = array_keys($productAttrsWithLowStock);
        $prodsModelsWithLowAttrs = $productsModel->getProductsByIds($arrWithProdsIdsForSelect);

        if ( !empty($productAttrsWithLowStock) && !empty($prodsModelsWithLowAttrs) ) {

            $dataForTemplate = compact('productAttrsWithLowStock', 'prodsModelsWithLowAttrs');
            $templateName = 'mailnoticelowamountofattrs';
            $pathesToTemplate = [
                JPATH_ROOT . '/templates/' . JFactory::getApplication()->getTemplate() . '/html/com_jshopping/checkout',
                JPATH_COMPONENT_SITE . '/templates/base/checkout'
            ];

            $bodyEmailText = renderTemplate($pathesToTemplate, $templateName, $dataForTemplate, 'emails');
			$dataForTemplate = array('emailSubject'=>JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_ATTRS_TITLE'), 'emailBod'=>$bodyEmailText);
			$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
            $shMailer = new shMailer();
            $shMailer->sendMailToAdmin(JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_ATTRS_TITLE'), $bodyEmailText);                          
        }        
    }      

    public function _showSmallCart($step = 0)
    {
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        
        $cart = JSFactory::getModel('cart', 'jshop');		
        $cart->load();
		$cart->getPricesArray();		
        $cart->addLinkToProducts(0);
        $cart->setDisplayFreeAttributes();
        
        if ($step == 5){
            $cart->setDisplayItem(1, 1);
        }elseif ($step == 4 && !$jshopConfig->step_4_3) {
            $cart->setDisplayItem(0, 1);
        }elseif ($step == 3 && $jshopConfig->step_4_3){
            $cart->setDisplayItem(1, 0);
		}else{
            $cart->setDisplayItem(0, 0);
        }
        $cart->updateDiscountData();

        $weight_product = $cart->getWeightProducts();
        if ($weight_product==0 && $jshopConfig->hide_weight_in_cart_weight0){
            $jshopConfig->show_weight_order = 0;
        }
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeDisplaySmallCart', array(&$cart) );
                
        $view_name = "cart";
        $view_config = array("template_path"=>viewOverride($view_name,'checkout.php'));
        $view = $this->getView($view_name, getDocumentType(), '', $view_config);
        $view->setLayout("checkout");
        $view->set('step', $step);
        $view->set('config', $jshopConfig);
        $view->set('products', $cart->products);
        $view->set('summ', $cart->getPriceProducts());
        $view->set('image_product_path', $jshopConfig->image_product_live_path);
        $view->set('no_image', $jshopConfig->noimage);
        $view->set('discount', $cart->getDiscountShow());
        $view->set('free_discount', $cart->getFreeDiscount());
        $deliverytimes = JSFactory::getAllDeliveryTime();
        $view->set('deliverytimes', $deliverytimes);
        
		$lang = JSFactory::getLang();
        $name = $lang->get("name");
        $payment_method_id = $cart->getPaymentId();
        if ($payment_method_id){
            $pm_method = JSFactory::getTable('paymentMethod', 'jshop');            
            $pm_method->load($payment_method_id);
            $payment_name = $pm_method->$name;
        }else{
            $payment_name = '';
        }
        $view->set('payment_name', $payment_name);
		
        if ($step == 5){
            if (!$jshopConfig->without_shipping){
                $view->set('summ_delivery', $cart->getShippingPrice());
                if ($cart->getPackagePrice()>0 || $jshopConfig->display_null_package_price){
                    $view->set('summ_package', $cart->getPackagePrice());
                }
				$view->set('summ_payment', $cart->getPaymentPrice());
                $fullsumm = $cart->getSum(1,1,1);
                $tax_list = $cart->getTaxExt(1,1,1);
            }else{
				$view->set('summ_payment', $cart->getPaymentPrice());
                $fullsumm = $cart->getSum(0,1,1);
                $tax_list = $cart->getTaxExt(0,1,1);
            }
        }elseif($step == 4 && !$jshopConfig->step_4_3){
            $view->set('summ_payment', $cart->getPaymentPrice());
            $fullsumm = $cart->getSum(0,1,1);
            $tax_list = $cart->getTaxExt(0,1,1);
        }elseif($step == 3 && $jshopConfig->step_4_3){
			$view->set('summ_delivery', $cart->getShippingPrice());
            if ($cart->getPackagePrice()>0 || $jshopConfig->display_null_package_price){
                $view->set('summ_package', $cart->getPackagePrice());
            }
			$fullsumm = $cart->getSum(1,1,0);
            $tax_list = $cart->getTaxExt(1,1,0);
		}
		else{
            $fullsumm = $cart->getSum(0, 1, 0);
            $tax_list = $cart->getTaxExt(0, 1, 0);
        }
        
        $show_percent_tax = 0;
        if (count($tax_list)>1 || $jshopConfig->show_tax_in_product) $show_percent_tax = 1;
        if ($jshopConfig->hide_tax) $show_percent_tax = 0;
        $hide_subtotal = 0;
        if ($step == 5){
            if (($jshopConfig->hide_tax || count($tax_list)==0) && !$cart->rabatt_summ && $jshopConfig->without_shipping && $cart->getPaymentPrice()==0) $hide_subtotal = 1;
        }elseif ($step == 4 && !$jshopConfig->step_4_3) {
            if (($jshopConfig->hide_tax || count($tax_list)==0) && !$cart->rabatt_summ && $cart->getPaymentPrice()==0) $hide_subtotal = 1;
        }elseif ($step == 3 && $jshopConfig->step_4_3){
            if (($jshopConfig->hide_tax || count($tax_list)==0) && !$cart->rabatt_summ && $jshopConfig->without_shipping) $hide_subtotal = 1;
        }else{
            if (($jshopConfig->hide_tax || count($tax_list)==0) && !$cart->rabatt_summ) $hide_subtotal = 1;
        }
        
        $text_total = JText::_('COM_SMARTSHOP_PRICE_TOTAL');
        if ($step == 5){
            $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL');
            if (($jshopConfig->show_tax_in_product || $jshopConfig->show_tax_product_in_cart) && (count($tax_list)>0)){
                $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL_INKL_TAX');
            }
        }

        $view->set('tax_list', $tax_list);
        $view->set('fullsumm', $fullsumm);
        $view->set('show_percent_tax', $show_percent_tax);
        $view->set('hide_subtotal', $hide_subtotal);
        $view->set('text_total', $text_total);
        $view->set('weight', $weight_product);
		$this->viewLabelSuffixInCart($view);
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutCartView', array(&$view));
    return $view->loadTemplate();
    }
	private function viewLabelSuffixInCart(&$view) 
    {
       if (count($view->products) > 0) {
            foreach ($view->products as $key => $prod) {
                if (isset($prod['facp_label_label']) && isset($prod['facp_label_suffix']) && !empty($prod['facp_label_label'])) {
                    $object = new stdClass();
                    $object->attr = $prod['facp_label_label'];
                    $object->value = $prod['facp_label_suffix'];
                    $view->products[$key]['free_attributes_value'][] = $object;
                }
            }
        }
    }
    
    public function _showCheckoutNavigation($step)
    {
        $jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->step_4_3){
			$array_navigation_steps = array('2'=>JText::_('COM_SMARTSHOP_STEP_ORDER_2'), '4'=>JText::_('COM_SMARTSHOP_STEP_ORDER_4'), '3'=>JText::_('COM_SMARTSHOP_STEP_ORDER_3'), '5'=>JText::_('COM_SMARTSHOP_STEP_ORDER_5'));
        }else{
			$array_navigation_steps = array('2' => JText::_('COM_SMARTSHOP_STEP_ORDER_2'), '3' => JText::_('COM_SMARTSHOP_STEP_ORDER_3'), '4' => JText::_('COM_SMARTSHOP_STEP_ORDER_4'), '5' => JText::_('COM_SMARTSHOP_STEP_ORDER_5'));
		}
        $output = array();
        $cssclass = array();
        if ($jshopConfig->without_shipping || $jshopConfig->hide_shipping_step) unset($array_navigation_steps[4]);
        if ($jshopConfig->without_payment || $jshopConfig->hide_payment_step) unset($array_navigation_steps[3]);

        foreach($array_navigation_steps as $key=>$value){
            if ($key < $step && !($jshopConfig->step_4_3 && $key==3 && $step==4) || ($jshopConfig->step_4_3 && $key==4 && $step==3)){
                $output[$key] = '<span class="not_active_step"><a href="'.SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step'.$key,0,0,$jshopConfig->use_ssl).'">'.$value.'</a></span>';
                $cssclass[$key] = "prev";
            }else{
                if ($key == $step){
                    $output[$key] = '<span id="active_step"  class="active_step">'.$value.'</span>';
                    $cssclass[$key] = "active";
                }else{
                    $output[$key] = '<span class="not_active_step">'.$value.'</span>';
                    $cssclass[$key] = "next";
                }
            }
        }

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayCheckoutNavigator', array(&$output, &$array_navigation_steps, &$step));

        $view_name = "quick_checkout";
        $view_config = array("template_path"=>viewOverride($view_name,'menu.php'));
        $view = $this->getView($view_name, getDocumentType(), '', $view_config);
        $view->setLayout("menu");
        $view->set('steps', $output);
		$view->set('step', $step);
        $view->set('cssclass', $cssclass);
        $view->set('array_navigation_steps', $array_navigation_steps);
        $dispatcher->triggerEvent('onAfterDisplayCheckoutNavigator', array(&$view));
    return $view->loadTemplate();
    }

    public function _cancelPayOrder($order_id="")
    {
        $jshopConfig = JSFactory::getConfig();
        $checkout = JSFactory::getModel('checkout', 'jshop');
        $wmiframe = JFactory::getApplication()->input->getInt("wmiframe");
        $session = JFactory::getSession();
        if (!$order_id) $order_id = $session->get('jshop_end_order_id');
        if (!$order_id){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_SESSION_FINISH'),'error');
            if (!$wmiframe){
                $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            }else{
                $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1,$jshopConfig->use_ssl));
            }
            return 0;
        }

        $checkout->cancelPayOrder($order_id);
        
        \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_PAYMENT_CANCELED'),'error');
        if (!$wmiframe){ 
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1, $jshopConfig->use_ssl));
        }else{
            $this->iframeRedirect(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step5',0,1, $jshopConfig->use_ssl));
        }
        return 0;
    }
    
    public function iframeRedirect($url)
    {
        echo "<script>parent.location.href='$url';</script>\n";
        $mainframe = JFactory::getApplication();
        $mainframe->close();
    }
    
}
