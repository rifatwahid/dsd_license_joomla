<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/extrascoupon/checkout_extrascoupon_mambot.php';

class JshoppingControllerCart extends JshoppingControllerBase
{
    
    public function __construct($config = [])
    {
        parent::__construct($config);
        $currentObj = $this;
        JPluginHelper::importPlugin('jshoppingcheckout');
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerCart', [&$currentObj]);
		setSeoMetaData();
    }
    
    public function display($cachable = false, $urlparams = false)
    {
        $this->view();
    }

    public function add()
    {
        header('Cache-Control: no-cache, must-revalidate');
        $jshopConfig = JSFactory::getConfig(); 
        if ($jshopConfig->user_as_catalog || !getDisplayPriceShop()) {
            return 0;
        }
		//print_r($_POST);die;
        $session = JFactory::getSession();
        $isAjaxRequest = JFactory::getApplication()->input->getInt('ajax');
		$isAjaxRequest_mod_cart = JFactory::getApplication()->input->getInt('ajax_mod_cart');
        $prodIdToAddInCart = JFactory::getApplication()->input->getInt('product_id');
        $categoryId = JFactory::getApplication()->input->getInt('category_id');
        $freeattribut = is_array(JFactory::getApplication()->input->getVar('freeattribut')) ? JFactory::getApplication()->input->getVar('freeattribut') : [];
        $uploadDataArr = JFactory::getApplication()->input->getVar('nativeProgressUpload');
        $quantity = JFactory::getApplication()->input->getInt('quantity', 1);
        $jshop_attr_id = is_array(JFactory::getApplication()->input->getVar('jshop_attr_id')) ? JFactory::getApplication()->input->getVar('jshop_attr_id') : [];
        $to = JFactory::getApplication()->input->getVar('to', 'cart');

        //$to = ($to != 'cart' && $to != 'wishlist') ? 'cart' : $to;
        $to = ($to != 'cart' && $to != 'wishlist' && $to != 'one_click_buy') ? 'cart' : $to;

        $Itemid = getShopCategoryPageItemid($categoryId);
        $urlToProductPage = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=' . $categoryId . '&product_id=' . $prodIdToAddInCart, 0, 0, null, $Itemid);
		
		if($to == 'wishlist'){			
			$_SERVER['REQUEST_URI'] .=(strstr($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') .'product_id=' . $prodIdToAddInCart;
			$_SERVER['REQUEST_URI'] .= (strstr($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') .'category_id=' . $categoryId;
			$_SERVER['REQUEST_URI'] .= (strstr($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') .'freeattribut=' . serialize($freeattribut);			
			$_SERVER['REQUEST_URI'] .=  (strstr($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') .'quantity=' . $quantity;
			$_SERVER['REQUEST_URI'] .=  (strstr($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') .'jshop_attr_id=' . serialize($jshop_attr_id);
			$_SERVER['REQUEST_URI'] .= (strstr($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') .'to=wishlist';
			
			checkUserLogin();
			
			if(is_array(JFactory::getApplication()->input->getVar('freeattribut'))){
				$freeattribut = JFactory::getApplication()->input->getVar('freeattribut');			
			}elseif(strlen(JFactory::getApplication()->input->getVar('freeattribut')) > 0){
				$freeattribut = unserialize(JFactory::getApplication()->input->getVar('freeattribut'));
			}else{
				$freeattribut = [];
			}
			
			if(is_array(JFactory::getApplication()->input->getVar('jshop_attr_id'))){
				$jshop_attr_id =  JFactory::getApplication()->input->getVar('jshop_attr_id');			
			}elseif(strlen(JFactory::getApplication()->input->getVar('jshop_attr_id')) > 0){
				$jshop_attr_id = unserialize(JFactory::getApplication()->input->getVar('jshop_attr_id'));
			}else{
				$jshop_attr_id = [];
			}			
		}
		
        $additional_fields = [];
        $usetriggers = 1;
        $errors = [];
        $displayErrorMessage = 1;

        if ($jshopConfig->use_decimal_qty) {
            $quantity = floatval(str_replace(',', '.', $quantity));
            $quantity = round($quantity, $jshopConfig->cart_decimal_qty_precision);
        }
 	
        foreach($jshop_attr_id as $k => $v) {
            $val = is_array($v) ? $v : intval($v);
            $jshop_attr_id[intval($k)] = $val;
        }

        $back_value = [
            'pid' => $prodIdToAddInCart, 
            'attr' => $jshop_attr_id, 
            'freeattr' => $freeattribut,
            'qty' => $quantity
        ];
        $session->set('product_back_value', $back_value);
        $productTable = JSFactory::getTable('product');
        $productTable->load($prodIdToAddInCart);
		$attributesDatas = $productTable->getAttributesDatas($jshop_attr_id, JSFactory::getUser()->usergroup_id);
		$jshop_attr_id=$attributesDatas['attributeActive'];		
		foreach ($jshop_attr_id as $key=>$val){
			if ($val<=0){unset($jshop_attr_id[$key]);}
		}
        $productTable->setAttributeActive($jshop_attr_id);
        $productTable->getExtendsData();

        $uploadModel = JSFactory::getModel('Upload');
        $uploadGlobalParams = $uploadModel->getParams();
        $productWithSupportUpload = $productTable->getEssenceWithActiveUpload();

        if (!empty($uploadGlobalParams->is_allow_product_page) && !empty($productWithSupportUpload->product_id) && !$productWithSupportUpload->isFromEditor()) {
            $cleanedUploadData = $uploadModel->getCleanedArrWithUploadData($uploadDataArr);
            $count = 0;
			
			if(!empty($cleanedUploadData)) $count = count($cleanedUploadData['files']);			
            $isProductPassedRequired = $uploadModel->isProductPassedRequired($productWithSupportUpload->product_id, (int)$count);			
            
            if (!$isProductPassedRequired && !empty($uploadModel->getErrors())) {
                return redirectMsgsWithOneTypeStatus($uploadModel->getErrors(), $urlToProductPage, 'error');
            }

            $isValidUploadedFiles = $uploadModel->isValidateUploadFiles($uploadDataArr, $quantity, true, $productWithSupportUpload->is_upload_independ_from_qty);

            if (!$isValidUploadedFiles) {
                return raiseWarningRedirect('', $urlToProductPage);
            }
        }

        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load($to);

		$dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onBeforePreAddProductToCart', [&$cart, &$prodIdToAddInCart, &$quantity, &$jshop_attr_id, &$freeattribut, &$additional_fields, &$usetriggers, &$errors, &$displayErrorMessage, &$uploadDataArr]);			
           
		
        if (!$cart->add($prodIdToAddInCart, $quantity, $jshop_attr_id, $freeattribut, $additional_fields, $usetriggers, $errors, $displayErrorMessage, $uploadDataArr)) {
			if ($isAjaxRequest_mod_cart){
				echo "$isAjaxRequest_mod_cart";
				die();
			}
            if ($isAjaxRequest) {
                echo getMessageJson();
                die();
            }

            $this->setRedirect($urlToProductPage);
            return 0;
        }

        $session->set('product_back_value', []);
		if ($isAjaxRequest_mod_cart){
			$moduleName = 'mod_smartshop_cart';
			$modul = JModuleHelper::getModule($moduleName);

			if (!empty($modul)) {
				$module = reset($modul);
				$moduleID = $module->id;
				$updatedModuleContent = JModuleHelper::renderModule($modul);
				$response = array(
					'html' => $updatedModuleContent
				);
				header('Content-Type: application/json');
				echo json_encode($response);
			}
			die();
		}
		
        if ($isAjaxRequest) {
            echo json_encode($cart, JSON_PARTIAL_OUTPUT_ON_ERROR);
            die();
        }
		
		if($cart->error_max){
			\JFactory::getApplication()->enqueueMessage($cart->error_max);
		}

        if ($jshopConfig->not_redirect_in_cart_after_buy) {
            $message = JText::_('COM_SMARTSHOP_ADDED_TO_CART');

            if ($to == 'wishlist'){
                $message = JText::_('COM_SMARTSHOP_ADDED_TO_WISHLIST');
            }

            $this->setRedirect($_SERVER['HTTP_REFERER'], $message);
            return 1;
        }

        if ($to == 'wishlist') {
            $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1, 1));
       		return 1;
        }

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 0, 1));
    }

    public function view()
    {
        JText::script('COM_SMARTSHOP_NATIVE_UPLOAD_REMAINING_QUANTITY');
        JText::script('COM_SMARTSHOP_NATIVE_UPLOAD_EXIST_ZERO_QUANTITY_IN_ROW');

	    $jshopConfig = JSFactory::getConfig();
        if ($jshopConfig->user_as_catalog) {
            return 0;
        }
		$user = JFactory::getUser();		

        if ($user->id){
            $adv_user = JSFactory::getUserShop();
        }else{
            $adv_user = JSFactory::getUserShopGuest();    
        }       
		if (isset($state_val) && $state_val && $adv_user->user_id == -1) {
			if ($d_id=='country'){
				$adv_user->state = $state_val;
			}
		}
		$country_id = $adv_user->country;
		if ((int)$country_id == 0){
			$country_id = $jshopConfig->default_country;
		}

		if($adv_user->user_id == -1){
            $adv_user->country = $country_id;
            $adv_user->d_country = $country_id;
            $adv_user->store();
        }

        JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models');
        $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel')->getParams();

        $document = JFactory::getDocument();
        $uploadCommonSettings = JSFactory::getModel('upload')->getParams();
        $session = JFactory::getSession();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $cart = JSFactory::getModel('cart', 'jshop');
        $isIncludeShippingInCartCost = (bool)$jshopConfig->show_shipping_costs_in_cart;
        $isHideSubtotal = 0;
        $isShowPercentTax = 0;
		$cart->load();		
		$cart->getPricesArray();		
		$cart->addLinkToProducts(1);
        $cart->setDisplayFreeAttributes();
		$cart->setQuantitySelect();
		
		$keys = array_keys($cart->products);
		$firstKey = reset($keys);		
		if (!isset($cart->products[$firstKey]['product_id'])) {
			$cart->products = array();
		}	
		
		if($cart->products)$load_shippings = JSFactory::getModel('ShippingsFront')->getPreparedShippingsCart($adv_user, $country_id, $jshopConfig, $cart);
		
		if(isset($load_shippings) && $load_shippings){
			$shippings = $load_shippings['shippings'];
			$firstShipping = &$shippings['0'];
			$active_shipping = $load_shippings['active_shipping'];

			$this->_setShipping($cart, $adv_user, $country_id, $active_shipping, $jshopConfig, null, 0, $shippings);
		}
		//print_r($load_shippings);die;
        $linkToAjaxUploadFiles = '/index.php?option=com_jshopping&controller=upload&task=ajaxUploadFile';
		$document->addScriptOptions('link_to_ajax_upload_files', $linkToAjaxUploadFiles);

        $shopurl = SEFLink('index.php?option=com_jshopping&controller=category', 1);
        if ($jshopConfig->cart_back_to_shop == 'product') {
            $endpagebuyproduct = xhtmlUrl($session->get('jshop_end_page_buy_product'));
        }elseif ($jshopConfig->cart_back_to_shop == 'list') {
            $endpagebuyproduct =  xhtmlUrl($session->get('jshop_end_page_list_product'));
        }

        if (isset($endpagebuyproduct) && $endpagebuyproduct) {
            $shopurl = $endpagebuyproduct;
        }
        
        $productWeight = $cart->getWeightProducts();
        if ($productWeight == 0 && $jshopConfig->hide_weight_in_cart_weight0) {
            $jshopConfig->show_weight_order = 0;
        }
		
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayCart', [&$cart]);
        CheckoutExtrascouponMambot::getInstance()->onBeforeDisplayCart($cart);
                
        $taxList = $cart->getTaxExt($isIncludeShippingInCartCost, 1);
		
        if ((!empty($taxList) || $jshopConfig->show_tax_in_product) && !$jshopConfig->hide_tax) {
            $isShowPercentTax = 1;
        }

        if (($jshopConfig->hide_tax || empty($taxList)) && !$cart->rabatt_summ) {
            $isHideSubtotal = 1;
        }       
        
        loadJSLanguageKeys();
        $cart->setDisplayData('cart');
		
		$tmp_fields = $jshopConfig->getListFieldsRegister();
		$config_address_fields = $tmp_fields['address'];
		$config_register_fields = $tmp_fields['register'];

        $view = $this->getView('cart', getDocumentType(), '', [
            'template_path' => viewOverride('cart', 'cart.php')
        ]);
        $uploadData = array_map(function($product) use($uploadCommonSettings) {
            $arr = [
                'upload_common_settings' => $uploadCommonSettings,
                'isSupportUpload' => $product['is_allow_uploads'],
                'isMultiUpload' => $product['is_unlimited_uploads'],
                'productMaxQty' => (string)$product['productMaxQty'],
                'maxFilesUploads' => (string)$product['max_allow_uploads'],
                'is_required_upload' => $product['is_required_upload'],
                'is_upload_independ_from_qty' => $product['is_upload_independ_from_qty']
            ];
            return $arr;
        }, $cart->products);
		$modelOfCountries = JSFactory::getModel('CountriesFront');


        $countriesSelectMarkup = $modelOfCountries->generateCountriesSelectMarkup($adv_user->country ?: $jshopConfig->default_country);
        $document = JFactory::getDocument();        
        $document->addScriptOptions('urlGetShippingPrice', SEFLink('index.php?option=com_jshopping&controller=cart&task=setShippingPriceAjax', 1, 1) );
   
        $document->addScriptOptions('uploadData', $uploadData);
		$summ_delivery = formatprice($cart->getShippingPrice() + $cart->getPackagePrice());
        $layout = getLayoutName('cart', 'cart');
        $view->setLayout($layout);
        $view->set('component', 'Cart');

        $view->set('config', $jshopConfig);
		$view->set('products', $cart->products);
		$view->set('summ', $cart->getPriceProducts());
		$view->set('image_product_path', $jshopConfig->image_product_live_path);
		$view->set('image_path', $jshopConfig->live_path);
        $view->set('no_image', $jshopConfig->noimage);
		$view->set('href_shop', $shopurl);
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('discount', $cart->getDiscountShow());
		$view->set('free_discount', $cart->getFreeDiscount());
		$view->set('use_rabatt', $jshopConfig->use_rabatt_code);
		$view->set('tax_list', $taxList);
        $view->set('fullsumm', $cart->getSum($isIncludeShippingInCartCost, 1));
        $view->set('show_percent_tax', $isShowPercentTax);
        $view->set('hide_subtotal', $isHideSubtotal);
        $view->set('weight', $productWeight);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('projectname', $session->get('projectname'));
        $view->set('offer_and_order_admin_user_id', $session->get('offer_and_order_admin_user_id'));
        $view->set('upload_common_settings', $uploadCommonSettings);
        $view->set('uploadparams', $uploadCommonSettings);
        $view->set('link_to_ajax_upload_files', $linkToAjaxUploadFiles);
        $view->set('summ_delivery', $summ_delivery);
        $view->set('select_countries', $countriesSelectMarkup->selectCountriesCart);
        $view->set('user', $adv_user);
		$view->set('config_address_fields', $config_address_fields['state']['display']);
        $view->set('config_register_fields', $config_register_fields['state']['display']);
		loadingStatesScripts();
		$this->viewLabelSuffixInCart($view);
		$session->set('display_link_offer_and_order_guest', 0);
        $view->set('deliverytimes', JSFactory::getAllDeliveryTime());
        $view->set('production_time', $_production_calendar->show_in_cart_checkout);
        $view->set('refresh_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=refresh'));
        $view->set('price_format_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=getPriceFormat', 1));
        $view->set('tax_info_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=productTaxInfo', 1));
        $view->set('sprintbasicprice_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintBasicPrice', 1));
        $view->set('create_offer_cart_link', SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=create_offer_cart', 1));
        $view->set('create_order_cart_link', SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=create_order_cart', 1));
        $view->set('isUrl_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=isUrl', 1));
        $view->set('sereliaze_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=unsereliaze_data', 1));
        $view->set('sprintpreviewnativeuploadedfiles_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintPreviewNativeUploadedFiles', 1));
        $view->set('sprintjstemplatefornativeuploadedfiles_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintJsTemplateForNativeUploadedFiles', 1));
        $view->set('formattax_link', SEFLink('index.php?option=coshow_buttonsm_jshopping&controller=functions&task=formattax', 1));
        $view->set('create_offer_link', SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=create_offer', 1));
        $view->set('discountsave_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=discountsave', 1));
        $view->set('printselectquantitycart_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantityCart', 1));
        $view->set('sef', JFactory::getConfig()->get('sef'));
       
        $dispatcher->triggerEvent('onBeforeDisplayCartView', [&$view]);

        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        
        if ($ajax) {
			print json_encode(prepareView($view));die;
            die();
        }
        $view->display();
    }
	
	private function viewLabelSuffixInCart(&$view) 
    {
       if (!empty($view->products)) {
            foreach ($view->products as $key => $prod) {
                if (isset($prod['facp_label_label']) && isset($prod['facp_label_suffix']) && !empty($prod['facp_label_label'])) {
                    $object = new stdClass();
                    $object->attr_id = $key;
                    $object->attr = $prod['facp_label_label'];
                    $object->value = $prod['facp_label_suffix'];
					$view->products[$key]['free_attributes_value'][] = $object;
                }
            }
        }
    }

    public function delete()
    {
        header('Cache-Control: no-cache, must-revalidate');
        $isAjaxRequest = JFactory::getApplication()->input->getInt('ajax');
        $isRAjaxRequest = JFactory::getApplication()->input->getInt('rajax');
        $number_id = JFactory::getApplication()->input->getInt('number_id');
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        $cart->delete($number_id);

        if ($isAjaxRequest) {
            echo getOkMessageJson($cart);
            die();
        }
        if ($isRAjaxRequest) {
            echo '1';
            die();
        }

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 0, 1));
    }

    public function refresh()
    { 
        $isAjaxRequest = JFactory::getApplication()->input->getInt('ajax');
        $isRAjaxRequest = JFactory::getApplication()->input->getInt('rajax');
        $quantitys = JFactory::getApplication()->input->getVar('quantity');
        $updatedForCartUploads = JFactory::getApplication()->input->getVar('updatedForCartUploads');
        $cart = JSFactory::getModel('cart', 'jshop');
	
		if(empty($quantitys)){
			$body = file_get_contents('php://input');
			$data = json_decode($body , true);
			foreach($data as $k=>$value){				
				if(isset($value['name']) && $value['name'] == 'updatedForCartUploads'){
					$_updatedForCartUploads = json_decode($value['value']);
				}elseif($k === 'updatedForCartUploads'){
					$_updatedForCartUploads = json_decode($value);
				}elseif(isset($value['name']) && strpos($value['name'], 'quantity') !== false){
					preg_match('/(?<=\[).*?(?=\])/', $value['name'], $keys); 
					$quantitys[$keys[0]] = $value['value'];
				}elseif(strpos($k, 'quantity') !== false){
					preg_match('/(?<=\[).*?(?=\])/', $k, $keys); 
					$quantitys[$keys[0]] = $value;
				}elseif(isset($value) && is_array($value) && $value['fileName'] != null){
					$_updatedForCartUploads[$k] = $value;
					break;
				}		
			}	
			if(!empty($_updatedForCartUploads)){
				foreach ($_updatedForCartUploads as $key => $value)
				{
					foreach($value as $k => $val){ //print_r($_updatedForCartUploads);die;
						$_updatedForCartUploads[$key]->$k = $val;
					}
				}
			}
		}	
		
		if (!empty($_updatedForCartUploads)) {
            $this->updateUploadsFiles($_updatedForCartUploads);
        }

        $cart->load();		
        $cart->refresh($quantitys);
        if ($isAjaxRequest) {
            echo getOkMessageJson($cart);
            die();
        }
        if ($isRAjaxRequest) {
            print_r('1');
            die;
        }

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 0, 1));
    }

    public function discountsave()
    {
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadDiscountSave', []);
        
        $isAjaxRequest = JFactory::getApplication()->input->getInt('ajax');
        $isRAjaxRequest = JFactory::getApplication()->input->getInt('rajax');
        $coupon = JSFactory::getTable('coupon', 'jshop');
        $code = JFactory::getApplication()->input->getVar('rabatt');

        if ($coupon->getEnableCode($code)) {
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load();

            $dispatcher->triggerEvent('onBeforeDiscountSave', [&$coupon, &$cart]);
            CheckoutExtrascouponMambot::getInstance()->onBeforeDiscountSave($coupon, $cart);
            $cart->setRabatt($coupon->coupon_id, $coupon->coupon_type, $coupon->coupon_value);
            $dispatcher->triggerEvent('onAfterDiscountSave', [&$coupon, &$cart]);
            CheckoutExtrascouponMambot::getInstance()->onAfterDiscountSave($coupon, $cart);

            if ($isAjaxRequest) {
                echo getOkMessageJson($cart);
                die();
            }
            if($isRAjaxRequest){
               echo '1';die;
            }
        } else {
            \JFactory::getApplication()->enqueueMessage($coupon->error,'error');

            if ($isAjaxRequest) {
                echo getMessageJson();
                die();
            }
        }

        $this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 0, 1));
    }

    public function removeUploadFile($productArrayKeyInCart = null, $uploadArrayKeyInCart = null)
    {
        $isAjax = JFactory::getApplication()->input->getBool('isAjax');
        $productArrayKeyInCart = (isset($productArrayKeyInCart)) ? $productArrayKeyInCart : JFactory::getApplication()->input->getInt('productArrayKey');
        $uploadArrayKeyInCart = (isset($uploadArrayKeyInCart)) ? $uploadArrayKeyInCart : JFactory::getApplication()->input->getInt('uploadArrayKey');
        $result = false;

        if (isset($productArrayKeyInCart) && isset($uploadArrayKeyInCart)) {
            $cartModel = JSFactory::getModel('cart', 'jshop');
            $result = $cartModel->removeUploadFile($productArrayKeyInCart, $uploadArrayKeyInCart);
        }

        if ($isAjax) {
            echo json_encode([
                'result' => $result
            ]);
            die;
        }

        return $result;
    }

    public function updateUploadsFiles($dataForUpdate = [])
    {
        $updatedDataForUploadsInCart = json_decode(JFactory::getApplication()->input->getVar('updatedForCartUploads'));
        $answer = [
            'isUpdate' => false
        ];

        if (!empty($dataForUpdate)) {
            $updatedDataForUploadsInCart = $dataForUpdate;
        }
		

        if (!empty($updatedDataForUploadsInCart)) {
            $cartModel = JSFactory::getModel('cart', 'jshop');
            $answer['isUpdate'] = $cartModel->updateDataOfUploadsFiles($updatedDataForUploadsInCart);
        }

        return $answer['isUpdate'];
    }

    function setShippingPriceAjax(){
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        $country_id = (string)JFactory::getApplication()->input->getVar("country_id");
        $d_id = JFactory::getApplication()->input->getVar("id");
        $key = JFactory::getApplication()->input->getVar("key");
        $state_val = JFactory::getApplication()->input->getVar("state_val");

        if ($user->id){
            $adv_user = JSFactory::getUserShop();
        }else{
            $adv_user = JSFactory::getUserShopGuest();
        }
        if ($state_val && $adv_user == -1) {
            if ($d_id=='country'){
                $adv_user->state = $state_val;
            }
        }
        if ($country_id===''){
            $country_id = $jshopConfig->default_country;
        }

        if($adv_user->user_id == -1){
            $adv_user->country = $country_id;
            $adv_user->d_country = $country_id;
            $adv_user->state = $state_val;
            $adv_user->d_state = $state_val;
            $adv_user->store();
        }

        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();

        $load_shippings = JSFactory::getModel('ShippingsFront')->getPreparedShippingsCart($adv_user, $country_id, $jshopConfig, $cart);

        $shippings = $load_shippings['shippings'];
        $firstShipping = &$shippings['0'];
        $active_shipping = $load_shippings['active_shipping'];

        $this->_setShipping($cart, $adv_user, $id_country ?? 0, $active_shipping, $jshopConfig, null, 0, $shippings);

        if (!$jshopConfig->without_shipping) {

            $summ_delivery = formatprice($cart->getShippingPrice() + $cart->getPackagePrice());
        }

         $taxList = $cart->getTaxExt(1, 1);
        $new_tax_list = [];
        if (!empty($taxList)) {
            foreach ($taxList as $percent => $tax) {
				if ((double)$percent==0) {
				$tmp=explode('_',substr($percent,15,strlen($percent)));				
					//$new_tax_list[JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name] = formatprice($tax);
					$new_tax_list[$percent] = formatprice($tax);
					$data['tax_list_name'][] = JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name;
				} else {
					$new_tax_list[formattax($percent)] = formatprice($tax);
					$data['tax_list_name'][] = displayTotalCartTaxName();
				}
            }
        }
        $data['summ'] = formatprice($cart->getPriceProducts());
        $data['discount'] = formatprice($cart->getDiscountShow());
        $data['free_discount'] = formatprice($cart->getFreeDiscount());
        $data['fullsumm'] = formatprice($cart->getSum(1, 1));
        $data['tax_list_name'] = displayTotalCartTaxName();
        $data['tax_list'] = $new_tax_list;
        $data['summ_delivery'] = $summ_delivery;
        $data['jshopConfig'] = $jshopConfig;
		$data['smallCartMarkup'] = JSFactory::getModel('cart', 'jshop')->renderSmallCart(); 
        echo json_encode($data);
        die;
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
            $params_sm = (isset($params[$val]) && $params[$val]) ? $params[$val] : [];

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

            $id_country = (isset($adv_user->d_country) && $adv_user->d_country) ?: $adv_user->country ?: $jshopConfig->default_country;
            $id_state = (isset($adv_user->d_state) && $adv_user->d_state) ?: $adv_user->state ?: 0;

            $tableOfShippingMethod = JTable::getInstance('shippingMethod', 'jshop');
            $idsOfCartProducts = getListOfValuesByArrKey($cart->products, 'product_id');
            $shippingsOfCartProducts = JSFactory::getModel('ProductsShipping')->getByProductsIds($idsOfCartProducts, ['*'], true);
            $allShippingsOfCartProducts = JSFactory::getModel('ProductsShipping')->getByProductsIds($idsOfCartProducts, ['*'], false);

            $allShippingsMethods = $tableOfShippingMethod->getAllShippingMethodsCountry($id_country, $paymentId, 1, $adv_user->usergroup_id, $id_state);

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
						$package = isset($val->package) ? $val->package : 0;
						$prices['shipping'] = $val->calculeprice - $package;
						$prices['package'] = $package;
						break;
					}
				}
			}
			$shipping_stand_price = $shipping_method_price->shipping_stand_price;
			$package_stand_price = $shipping_method_price->package_stand_price;
			
			$shipping_method_price->shipping_stand_price = $shipping_stand_price;
			$shipping_method_price->package_stand_price = $package_stand_price;
			//if(!$prices){
				$prices = $shipping_method_price->calculateSum($cart);
			//}
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

       
        return 1;
    }
}