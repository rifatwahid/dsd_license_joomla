<?php

defined('_JEXEC') or die();

require_once __DIR__ . '/../FrontMambot.php';

class CheckoutExtrascouponMambot extends FrontMambot
{        
    protected static $instance;
    
    public function onBeforeDiscountSave(&$coupon, &$cart)
    {
        $db = \JFactory::getDBO();
        
        if (isset($coupon->only_for_guests) && $coupon->only_for_guests == 1 && JFactory::getUser()->id != 0) {
            $coupon = null;
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_RABATT_NON_SUPPORT'),'error');
            return 0;
        }
        
        $userShop = JSFactory::getUserShop();
        if ($coupon->for_user_group_id != 0 && $userShop->usergroup_id != $coupon->for_user_group_id) {
            $coupon = null;
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_COUPON'),'error');
            return 0;
        }
        
		$coupon_except_user_groups = ($coupon->except_user_group_id == '') ? [] : explode(',', $coupon->except_user_group_id);
		if (!empty($coupon_except_user_groups) && in_array($userShop->usergroup_id, $coupon_except_user_groups)) {
			$coupon = null;
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_COUPON'),'error');

            return 0;
        }
        
		if ($coupon->min_count_in_cart > 0 && count($cart->products) < $coupon->min_count_in_cart) {
			$coupon = null;
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_COUPON'),'error');

            return 0;
        }
        		
		$coupon_currencies = ($coupon->for_currencies == '') ? [] : explode(',', $coupon->for_currencies);
		$currency_id = JFactory::getSession()->get('js_id_currency');

		if (!empty($coupon_currencies) && !in_array($currency_id, $coupon_currencies)) {
			$coupon = null;
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_COUPON'),'error');

            return 0;
		}
		
        if (!empty($coupon->limited_use)) {
            if (!empty($coupon->once_for_each_user)) {
                $user = JFactory::getUser();

                if (!empty($user->id)) {
                    $modelOfCouponsUsersRestFront = JSFactory::getModel('CouponsUsersRestFront');
                    $adv_user = JSFactory::getUserShop();
                    $rest = $modelOfCouponsUsersRestFront->getDataByUserAndCouponIds($adv_user->user_id, $coupon->coupon_id)->rest ?? null;

                    if (isset($rest)) {
                        if ($rest == 0) {
                            $coupon = null;
                            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_RABATT_USED'),'error');
                            
                            return 0;
                        }
                    } else {
                        $count = $modelOfCouponsUsersRestFront->countCouponsByCouponId($coupon->coupon_id);

                        if ($count >= $coupon->limited_count) {
                            $coupon = null;
                            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_RABATT_USED'),'error');

                            return 0;
                        }
                    }
                } else {
                    $coupon = null;
                    \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_FOR_USE_COUPON_PLEASE_LOGIN'),'error');

                    return 0;
                }
            } else {
                $count = JSFactory::getModel('OrdersFront')->countCouponsByCouponId($coupon->coupon_id);

                if ($count >= $coupon->limited_count) {
                    $coupon = null;
                    \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_RABATT_USED'),'error');

                    return 0;
                }
            }
        }

        $this->updateExtraCouponData($cart, $coupon, true);
    }
    
    public function onAfterDiscountSave(&$coupon, &$cart)
    {
        $this->resaveDiscount($cart);
    }
    
    public function onBeforeDisplayCart(&$cart) 
    {
        $this->updateCart($cart);
    }
    
    public function onBeforeDisplaySmallCart(&$cart)
    {
        $this->updateCart($cart);
    }

    protected function updateCart(&$cart)
    {
        $coupon = null;

        if (!empty($cart->rabatt_id)) {
            $coupon = JTable::getInstance('coupon', 'jshop');
            $coupon->load($cart->rabatt_id);
        }

        $this->updateExtraCouponData($cart, $coupon);
        $this->resaveDiscount($cart);
        $this->checkAccessToCouponForRegistered($cart, $coupon);
    }
    
    public function onBeforeDisplayCheckoutStep3View(&$view)
    {
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();
        $couponId = $cart->rabatt_id;
        $paymentMethods = $view->payment_methods;
        
        $coupon = JTable::getInstance('coupon', 'jshop');
        $coupon->load($couponId);
        $isFreePayment = $coupon->free_payment;

        if ($isFreePayment == 1) {
            foreach($paymentMethods as $key => $value) {                
                if (!empty($paymentMethods[$key]->price_add_text)) {
                    $price = 0; 
                    $paymentMethods[$key]->price_add_text = '+' . formatprice($price);
                }
            }
        }
		
		$this->checkAccessToCouponForRegistered($cart, $coupon);
    }
    
    public function onBeforeDisplayCheckoutStep4View(&$view)
    {
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();
        $couponId = $cart->rabatt_id;
        $shippingMethods = $view->shipping_methods;
        
        $coupon = JTable::getInstance('coupon', 'jshop');
        $coupon->load($couponId);
        $isFreeShipping = $coupon->free_shipping;
        
        if ($isFreeShipping == 1) {
            $shippingMethods = array_map(function ($item) {
                $item->calculeprice = 0;
                return $item;
            }, $shippingMethods);
        }
		
		$this->checkAccessToCouponForRegistered($cart, $coupon);
    }
	
    public function onBeforeDisplayCheckoutStep5View(&$view)
    {
		$cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();
		
		$couponId = $cart->rabatt_id;
		$coupon = JTable::getInstance('coupon', 'jshop');
        $coupon->load($couponId);
		
		$this->checkAccessToCouponForRegistered($cart, $coupon);
	}
    
    public function onAfterSaveCheckoutStep3save(&$adv_user, &$paym_method, &$cart)
    {
        $this->storeFreePaymentAndShipping();        
    }
    
    public function onAfterSaveCheckoutStep4(&$adv_user, &$sh_method, &$shipping_method_price, &$cart)
    {
        $this->storeFreePaymentAndShipping();
    }
    
    public function onAfterLoadShopParams()
    {
        $this->storeFreePaymentAndShipping();
    }
    

    protected function storeFreePaymentAndShipping()
    {
        $cart = JModelLegacy::getInstance('cart', 'jshop');
        $cart->load();
        $couponId = $cart->rabatt_id;

        $price = 0;
        $emptyArray = [];
        $coupon = JTable::getInstance('coupon', 'jshop');
        $coupon->load($couponId);
        $free_shipping = $coupon->free_shipping;
        $free_payment = $coupon->free_payment;

        if ($free_shipping == 1) {
            $cart->setShippingPrice($price);
            $cart->setShippingTaxList($emptyArray);
            $cart->setPackagePrice($price);
        }

        if ($free_payment == 1) {
            $cart->setPaymentPrice($price);
            $cart->setPaymentTaxList($emptyArray);
            $cart->setPaymentPriceForTaxes($emptyArray);
        }
    }

    protected function updateCouponPriceForEachUser(&$coupon) 
    {
        if (isset($coupon->once_for_each_user) && $coupon->once_for_each_user) {
            $user = JFactory::getUser();

            if (!empty($user->id)) {
                $adv_user = JSFactory::getUserShop();
                $modelOfCouponsUsersRestFront = JSFactory::getModel('CouponsUsersRestFront');
                $rest = $modelOfCouponsUsersRestFront->getDataByUserAndCouponIds($adv_user->user_id, $coupon->coupon_id)->rest ?? null;

                if (isset($rest)) {
                    if ($rest == 0) {
                        $coupon = null;
                        \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_RABATT_USED'),'error');
                        return 0;
                    }

                    if (($rest > 0) && ($coupon->coupon_type == 1)) {
                        $coupon->coupon_value = $rest;
                    }
                }
            } else {
                $coupon = null;
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_FOR_USE_COUPON_PLEASE_LOGIN'),'error');
                return 0;
            }
        }
    }

    protected function checkAccessToCouponForRegistered(&$cart, &$coupon)
    {
        if (isset($coupon->only_for_guests) && $coupon->only_for_guests == 1 && JFactory::getUser()->id != 0){
            $coupon = null;
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_RABATT_NON_SUPPORT'),'error');
            $cart->rabatt_value = 0;
            $cart->rabatt_id = 0;
            $cart->rabatt_type = 0;
            
            $cart->updateDiscountData();
        }
    }

    protected function getProductsForExtraCoupon($cart, $coupon)
    {
        $products = [];
        
        $coupon_products = (!isset($coupon->for_product_id) || $coupon->for_product_id == '') ? [] : explode(',', $coupon->for_product_id);
        $coupon_except_products = (!isset($coupon->except_product_id) || $coupon->except_product_id == '') ? [] : explode(',', $coupon->except_product_id);
        $coupon_categories = (!isset($coupon->for_category_id ) || $coupon->for_category_id == '') ? [] : explode(',', $coupon->for_category_id);
        $coupon_except_categories = (!isset($coupon->except_category_id ) || $coupon->except_category_id == '') ? [] : explode(',', $coupon->except_category_id);
        $coupon_except_labels = (!isset($coupon->except_label_id ) || $coupon->except_label_id == '') ? [] : explode(',', $coupon->except_label_id);
		$coupon_except_vendors = (!isset($coupon->except_vendor_id ) || $coupon->except_vendor_id == '') ? [] : explode(',', $coupon->except_vendor_id);
		$coupon_fields = (!isset($coupon->for_product_fields ) || $coupon->for_product_fields == '') ? [] : explode(',', $coupon->for_product_fields);
        $except_manufacturer_ids = (!isset($coupon->except_manufacturer_id ) || $coupon->except_manufacturer_id == '') ? [] : preg_split('/\s*,\s*/', $coupon->except_manufacturer_id, null, PREG_SPLIT_NO_EMPTY);
        $coupon_product_name = $coupon->{'for_product_name_' . JSFactory::getLang()->lang} ?? '';
        
        foreach($cart->products as $k => $val) {
            $isProductAlreadyHasBeen = in_array($val['product_id'] . $val['attributes'], $products);

			if (!$isProductAlreadyHasBeen) {
                $product = JTable::getInstance('product', 'jshop');
                $product->load($val['product_id']);

                if (!empty($val['attributes'])) {
                    $attrs = unserialize($val['attributes']);
                    $product->setAttributeActive($attrs);
                }

				$product_fields = $product->getExtraFields();
                //$cats = $product->getCategories(1);
				if (!empty($product->product_id)) $cats = JSFactory::getModel('ProductsToCategoriesFront')->getProductCategory($product->product_id);//!!COUPON
                if (!empty($coupon_products) && (!in_array($val['product_id'], $coupon_products))) continue;                
                if (!empty($coupon_except_products) && (in_array($val['product_id'], $coupon_except_products))) continue;                
                if (!empty($coupon_categories) && (count(array_intersect($cats, $coupon_categories)) == 0)) continue;
                if (!empty($coupon_except_categories) && (count(array_intersect($cats, $coupon_except_categories)))) continue;
                if (isset($coupon->for_editor_id) && $coupon->for_editor_id && ((!isset($product->related_editor_id) || $product->related_editor_id != $coupon->for_editor_id) && (!isset($product->editor_id) || $product->editor_id != $coupon->for_editor_id))) continue;
                if (isset($coupon->for_label_id) && $coupon->for_label_id && (!isset($product->label_id) || $product->label_id != $coupon->for_label_id)) continue;
                if (!empty($coupon_except_labels) && (in_array($product->label_id, $coupon_except_labels))) continue;
                if (isset($coupon->for_manufacturer_id) && $coupon->for_manufacturer_id && (!isset($product->product_manufacturer_id) || $product->product_manufacturer_id != $coupon->for_manufacturer_id)) continue;
                if (is_array($except_manufacturer_ids) && !empty($except_manufacturer_ids) && in_array($product->product_manufacturer_id, $except_manufacturer_ids)) continue;
                if (isset($coupon->for_vendor_id) && $coupon->for_vendor_id && (!isset($product->vendor_id) || $product->vendor_id != $coupon->for_vendor_id)) continue;
				if (!empty($coupon_except_vendors) && (in_array($product->vendor_id, $coupon_except_vendors))) continue;
				
                if (!empty($coupon->for_product_ean) && ($product->product_ean != $coupon->for_product_ean)) continue;
                if (!empty($coupon_product_name)) {
                    if ($coupon->for_product_name_type == 0 && $coupon_product_name != $val['product_name']) {
                        //Exact phrase
                        continue;
                    } else if ($coupon->for_product_name_type == 1 && strpos($val['product_name'], $coupon_product_name) === false) {
                        //Contain
                        continue;
                    }
                }
                
                if (isset($coupon->not_use_for_product_with_old_price) && $coupon->not_use_for_product_with_old_price && $product->product_old_price > 0) {
                    continue;
                }
				//print_r($coupon);die;
				if (isset($coupon->not_use_for_product_with_old_price) && ($coupon->for_prod_price_from > 0 && $product->product_price < $coupon->for_prod_price_from)
					|| (isset($coupon->for_prod_price_to) && $coupon->for_prod_price_to > 0 && $product->product_price > $coupon->for_prod_price_to)
				) {
                    continue;
                }
				
				if (!empty($coupon_fields)) {
                    $check_coupon_fields = false;
                    
					foreach($product_fields as $pf_k => $pf_v) {
						if(in_array($pf_k,$coupon_fields)) {
							$check_coupon_fields = true;
							break;
						}
                    }
                    
					if(!$check_coupon_fields) {
                        continue;
                    }
				}

                $products[] = $val['product_id'] . $val['attributes'];
            }
        }

        return $products;
    }

    protected function saveExtraCouponData(&$coupon, $products, &$cart)
    {
        $extracoupon = [];
        $extracoupon['products'] = $products;
        $extracoupon['type'] = 1;
        $extracoupon['value'] = 0;

        if (isset($coupon->coupon_id) && $coupon->coupon_id) {
            if ($coupon->min_sum_for_use > 0) {
                $summ = 0;

                foreach($cart->products as $prod) {
                    $summ += $prod['price'] * $prod['quantity'];
                }

                if ($summ < $coupon->min_sum_for_use) {
                    \JFactory::getApplication()->enqueueMessage(sprintf( JText::_('COM_SMARTSHOP_MIN_SUM_FOR_USE_ERROR'), formatprice($coupon->min_sum_for_use), formatprice($summ)),'error');
                    $coupon = null;
                    $cart->setRabatt(0, 1, 0);
                }
            }

            $extracoupon['type'] = $coupon->coupon_type;
            $extracoupon['value'] = $coupon->coupon_value;
        }

        $session = JFactory::getSession();
        $session->set('extracouponData', $extracoupon);
    }

    protected function getExtraCouponData()
    {
        $session = JFactory::getSession();
        return $session->get("extracouponData");
    }
    
    protected function updateExtraCouponData(&$cart, &$coupon, $isShowErrors = false)
    {
        $this->updateCouponPriceForEachUser($coupon);
        $products = $this->getProductsForExtraCoupon($cart, $coupon);
        $emptyArray = [];
        
        if (!empty($products)) {
            $this->saveExtraCouponData($coupon, $products, $cart);
        } else {
            $this->saveExtraCouponData($coupon, $emptyArray, $cart);

            if ($isShowErrors) {
                $coupon = null;
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_COUPON'),'error');
                return 0;
            }
        }
    }
    
    protected function getPriceProducts($cart, $extracoupon)
    {
        $startPrice = 0;

        $productPrice = array_reduce($cart->products, function ($price, $product) use ($extracoupon) {
            $id = $product['product_id'] . $product['attributes'];

            if (in_array($id, $extracoupon['products'])) {
                $price += $product['price'] * $product['quantity'];
            }

            return $price;
        }, $startPrice);

        return $productPrice;
    }
    
    protected function getPriceBruttoProducts($cart, $extracoupon)
    {
        $jshopConfig = JSFactory::getConfig();
        $price = 0;

        foreach($cart->products as $prod) {
            $id = $prod['product_id'] . $prod['attributes'];

            if (in_array($id, $extracoupon['products'])) {
                if ($jshopConfig->display_price_front_current == 1) {
                    $price += ($prod['price'] * (1 + $prod['tax'] / 100)) * $prod['quantity'];
                } else {
                    $price += $prod['price'] * $prod['quantity'];
                }
            }
        }

        return $price;
    }
    
    protected function getSummForCalculeDiscount($cart, $extracoupon)
    {
        $jshopConfig = JSFactory::getConfig();
        $sum = $this->getPriceProducts($cart, $extracoupon);

        if ($jshopConfig->discount_use_full_sum && $jshopConfig->display_price_front_current == 1) {
            $sum = $this->getPriceBruttoProducts($cart, $extracoupon);
        }

        if ($jshopConfig->discount_use_full_sum) {
            if ($cart->display_item_shipping) {
                $sum += $cart->getShippingBruttoPrice();
                $sum += $cart->getPackageBruttoPrice();
            }

            if ($cart->display_item_payment) {
                $sum += $cart->getPaymentBruttoPrice();
            }
        }

        return $sum;
    }
    
    protected function resaveDiscount(&$cart)
    {
        $jshopConfig = JSFactory::getConfig();
        $extracoupon = $this->getExtraCouponData();
        $cart->rabatt_value = 0;
        $cart->rabatt_type = 0;
        
        if (is_array($extracoupon['products']) && count($extracoupon['products']) > 0) {
            $cart->rabatt_type = 1;

            if ($extracoupon['type'] == 1) {
                $cart->rabatt_value = $extracoupon['value'];
            } else {
                $price = $this->getSummForCalculeDiscount($cart, $extracoupon);
                $cart->rabatt_value = ($price * $extracoupon['value'] / 100) / $jshopConfig->currency_value;
            }
        }
        
        $cart->updateDiscountData();
    }

}