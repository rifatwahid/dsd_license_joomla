<?php
/**
* @version      4.9.0 18.12.2014
* @author
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/free_attrs_default_values/checkout_free_attrs_default_values_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/free_attrs_default_values/products_free_attrs_default_values_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/offer_and_order/checkout_offer_and_order.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/exclude_attribute_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/exclude_buttons_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/min_max_quantity/checkout_min_max_quantity_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/select_quantity/select_quantity_mambot.php';

class jshopCart
{
    public $type_cart = 'cart'; // cart/wishlist
    public $products = [];
    public $count_product = 0;
    public $price_product = 0;
    public $summ = 0;
    public $rabatt_id = 0;
    public $rabatt_value = 0;
    public $rabatt_type = 0;
    public $rabatt_summ = 0;
	public $total_tax_rate = 0;
    public $tax_source = '';

    public function __construct()
    {
        JPluginHelper::importPlugin('jshoppingcheckout');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshopCartBeforeAllPlugins', [&$currentObj]);
		\JFactory::getApplication()->triggerEvent('onConstructJshopCart', [&$currentObj]);
		\JFactory::getApplication()->triggerEvent('onConstructJshopCartAfterAllPlugins', [&$currentObj]);
    }

    public function load($type_cart = 'cart')
    {
        $jshopConfig = JSFactory::getConfig();
        $this->type_cart = $type_cart;
        $currentObj = $this;

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeCartLoad', [&$currentObj]);

        $session = JFactory::getSession();
        $objcart = $session->get($this->type_cart);

        if (isset($objcart) && $objcart != '') {
            $temp_cart = unserialize($objcart);			
            $this->products = $temp_cart->products;
            $this->rabatt_id = $temp_cart->rabatt_id;
            $this->rabatt_value = $temp_cart->rabatt_value;
            $this->rabatt_type = $temp_cart->rabatt_type;
            $this->rabatt_summ = $temp_cart->rabatt_summ;
        }

        if (JFactory::getUser()->id && $this->type_cart == 'wishlist') {
            $_tempcart = JSFactory::getModel('tempcart', 'jshop');
            if (isset($_COOKIE['jshopping_temp_cart'])) $products = $_tempcart->getTempCart($_COOKIE['jshopping_temp_cart'], $this->type_cart);

            if (!empty($products)) {
                $this->products = $products;
                $this->saveToSession();
            }
        }
        $this->getEditorAttributesToProducts();

        $this->loadPriceAndCountProducts();
        if ($jshopConfig->use_extend_tax_rule&&(!isset($currentObj->skip)||($currentObj->skip!=1))) {
            $this->updateTaxForProducts();
            $this->saveToSession();
        }

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterCartLoad', [&$currentObj]);
		CheckoutOfferAndOrder::getInstance()->onAfterCartLoad($this);
    }

    public function loadPriceAndCountProducts()
    {
        $jshopConfig = JSFactory::getConfig();
        $currentObj = $this;
        $this->price_product = 0;
        $this->price_product_brutto = 0;
        $this->count_product = 0;

        if (!empty($this->products)) {
            foreach($this->products as $prod) {

                if (!empty($prod['one_time_cost'])) {
                    $this->price_product += $prod['one_time_cost'];
                } else {
                    $this->price_product += $prod['price'] * $prod['quantity'];
                }

                if ($jshopConfig->display_price_front_current == 1) {
					$taxes=$prod['tax'];
					foreach ($prod as $key=>$value){
						if (substr($key,0,14)=="additional_tax") $taxes+=(double)$value;
					}
					\JFactory::getApplication()->triggerEvent('onAfterLoadPriceAndCountProductsBeforePriceBrutto', [&$currentObj,$prod]);
					if (!isset($currentObj->skip)||($currentObj->skip!=1)) $this->price_product_brutto += ($prod['price'] * (1 + $taxes / 100)) * $prod['quantity'];
                } else {
                    $this->price_product_brutto += $prod['price'] * $prod['quantity'];
                }

                $this->count_product += $prod['quantity'];
            }
        }

        \JFactory::getApplication()->triggerEvent('onAfterLoadPriceAndCountProducts', [&$currentObj]);
    }

    public function getPriceProducts()
    {
        return $this->price_product;
    }

    public function getPriceBruttoProducts()
    {
        return $this->price_product_brutto;
    }

    public function getCountProduct()
    {
        return $this->count_product;
    }

    public function updateTaxForProducts()
    {
		$currentObj = $this;
        if (!empty($this->products)) {
            $taxes = JSFactory::getAllTaxes(false);
            foreach ($this->products as $k => $prod) {
				\JFactory::getApplication()->triggerEvent('onUpdateTaxForProductOnProd', [&$currentObj,$taxes,$k]);
				if (!isset($currentObj->skip)||($currentObj->skip!=1)) $this->products[$k]['tax'] = $taxes[$prod['tax_id']];
            }
        
			$_taxextadditional = JSFactory::getTable('taxextadditional', 'jshop');
			$additional_taxes=$_taxextadditional->getAllAdditionalTaxes();
			foreach ($additional_taxes as $key=>$value){
				$addtaxname="additional_tax_".$value->id;
				$this->products[$k][$addtaxname]=$taxes[$addtaxname][$prod['tax_id']];
			}
		}
    }

    /**
    * get cart summ price
    * @param mixed $incShiping - include price shipping
    * @param mixed $incRabatt - include discount
    * @param mixed $incPayment - include price payment
    */
    public function getSum($incShiping = 0, $incRabatt = 0, $incPayment = 0) 
    {
        $jshopConfig = JSFactory::getConfig();

        $this->summ = $this->price_product;
		//Modify tax and total price calculation task 5467
		if ((!$jshopConfig->hide_tax && $jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 1)||(!$jshopConfig->hide_tax && $jshopConfig->display_price_admin == 1 && $jshopConfig->display_price_front_current == 1)) {//$jshopConfig->display_price_front_current == 1
        //if ($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 1) {//$jshopConfig->display_price_front_current == 1
            $this->summ = $this->summ + $this->getTax($incShiping, $incRabatt, $incPayment);
        }

        if ($incShiping) {
            $this->summ = $this->summ + $this->getShippingPrice();
            $this->summ = $this->summ + $this->getPackagePrice();
        }

        if ($incPayment) {
            $price_payment = $this->getPaymentPrice();
            $this->summ = $this->summ + $price_payment;
        }

        if ($incRabatt) {
            $this->summ = $this->summ - $this->getDiscountShow();
            if ($this->summ < 0) {
                $this->summ = 0;
            }
        }
        $currentObj = $this;

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterCartGetSum', [&$currentObj, &$incShiping, &$incRabatt, &$incPayment]);

        return $currentObj->summ;
    }

    public function getDiscountShow()
    {
        $summForCalculeDiscount = $this->getSummForCalculeDiscount();

        if ($this->rabatt_summ > $summForCalculeDiscount) {
            return getRoundPriceProduct($summForCalculeDiscount);
        }

        return getRoundPriceProduct($this->rabatt_summ);
    }

    public function getFreeDiscount()
    {
        $summForCalculeDiscount = $this->getSummForCalculeDiscount();

        if ($this->rabatt_summ > $summForCalculeDiscount) {
            return $this->rabatt_summ - $summForCalculeDiscount;
        }

        return 0;
    }

    public function getTax($incShiping = 0, $incRabatt = 0, $incPayment = 0)
    {
        $taxes = $this->getTaxExt($incShiping, $incRabatt, $incPayment);
        $tax_summ = array_sum($taxes);

        return getRoundPriceProduct($tax_summ);
    }

    public function getTaxExt($incShiping = 0, $incRabatt = 0, $incPayment = 0)
    {
		$dispatcher = \JFactory::getApplication();        
        $jshopConfig = JSFactory::getConfig();
        $tax_summ = [];
		
        foreach($this->products as $key => $value) {
			if (!isset($tax_summ[$value['tax']])) {
				$tax_summ[$value['tax']] = 0;
			}
			
			if (!empty($value['one_time_cost'])) {
				//Modify tax and total price calculation task 5467
				$price_netto=1;
				if (($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 0)) $price_netto=0;
				if (($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 1)) $price_netto=1;
				if (($jshopConfig->display_price_admin == 1 && $jshopConfig->display_price_front_current == 0)) $price_netto=0;
				$currentObj = $this;
				$dispatcher->triggerEvent('onCartGetTaxExtBeforeTaxSum', [&$currentObj, &$tax_summ, &$incShiping, &$incRabatt, $incPayment, &$key, &$value,$jshopConfig,$price_netto]);
				if (!isset($currentObj->skip)||($currentObj->skip!=1)){
					$tax_summ[$value['tax']] += getPriceTaxValue($value['one_time_cost'], $value['tax'], $price_netto);//$jshopConfig->display_price_front_current
					foreach ($value as $k=>$v){
						if (substr($k,0,14)=="additional_tax") $tax_summ[$k."_".$v]+= getPriceTaxValue($value['one_time_cost'], (double)$v, $price_netto);
					}
				}
				//$tax_summ[$value['tax']] += getPriceTaxValue($value['one_time_cost'], $value['tax'], ($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 1));//$jshopConfig->display_price_front_current
				//Modify tax and total price calculation task 5467
			} else {
				$dispatcher->triggerEvent('onCartGetTaxExtBeforeTaxSum2', [&$currentObj, &$tax_summ, &$incShiping, &$incRabatt, $incPayment, &$key, &$value,$jshopConfig,$price_netto,$prod]);
				if (!isset($currentObj->skip)||($currentObj->skip!=1)){
					$tax_summ[$value['tax']] += $value['quantity'] * getPriceTaxValue($value['price'], $value['tax'], $jshopConfig->display_price_front_current);
					foreach ($value as $k=>$v){
						if (substr($k,0,14)=="additional_tax") $tax_summ[$k."_".$v]+= $value['quantity'] * getPriceTaxValue($value['price'], (double)$v, $jshopConfig->display_price_front_current);
					}
				}
			}
        }

        if ($incShiping) {
            $this->addTaxValueToCalc($this->getShippingTaxList(), $tax_summ);
            $this->addTaxValueToCalc($this->getPackageTaxList(), $tax_summ);
        }

        if ($incPayment) {
            $this->addTaxValueToCalc($this->getPaymentTaxList(), $tax_summ);
        }

        
		if ((!$jshopConfig->hide_tax && $incRabatt && $jshopConfig->calcule_tax_after_discount && $this->rabatt_summ > 0)&&(!(!isset($currentObj->skip)||($currentObj->skip!=1)))) {
            $tax_summ = $this->getTaxExtCalcAfterDiscount($incShiping, $incPayment);
        }
						
		
        $dispatcher->triggerEvent('onAfterCartGetTaxExt', [&$currentObj, &$tax_summ, &$incShiping, &$incRabatt, $incPayment]);

        return $tax_summ;
    }

    public function getTaxExtCalcAfterDiscount($incShiping = 0, $incPayment = 0)
    {
		$dispatcher = \JFactory::getApplication();
		$currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $summ = [];

        foreach($this->products as $key => $value) {
			$dispatcher->triggerEvent('onCartGetTaxExtCalcAfterDiscountBeforeTaxSum', [&$currentObj, &$summ,&$key, &$value]);
				if (!isset($currentObj->skip)||($currentObj->skip!=1)){				
					
				if (!isset($summ[$value['tax']])) {
					$summ[$value['tax']] = 0;
				}
				
				if (!empty($value['one_time_cost'])) {
					//Modify tax and total price calculation task 5467
					$price_netto=1;
					if (($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 0)) $price_netto=0;
					if (($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 1)) $price_netto=1;
					if (($jshopConfig->display_price_admin == 1 && $jshopConfig->display_price_front_current == 0)) $price_netto=0;
					$summ[$value['tax']] += $value['one_time_cost'];//$jshopConfig->display_price_front_current
					foreach ($value as $k=>$v){
						if (substr($k,0,14)=="additional_tax") $summ[$k."_".$v]+= ($value['one_time_cost']);
					}
					//$summ[$value['tax']] += getPriceTaxValue($value['one_time_cost'], $value['tax'], ($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 1));//$jshopConfig->display_price_front_current
					//Modify tax and total price calculation task 5467
				} else {
					$summ[$value['tax']] += $value['quantity'] * $value['price'];
					foreach ($value as $k=>$v){
						if (substr($k,0,14)=="additional_tax") $summ[$k."_".$v]+= $value['quantity'] * ($value['price']);
					}
				}			
			}
        }

        if ($jshopConfig->discount_use_full_sum) {
            if ($incShiping) {// && $this->display_item_shipping
                $this->addTaxValueToCalc($this->getShippingPriceForTaxes(), $summ);
                $this->addTaxValueToCalc($this->getPackagePriceForTaxes(), $summ);
            }

            if ($incPayment) {// && $this->display_item_payment
                $this->addTaxValueToCalc($this->getPaymentPriceForTaxes(), $summ);
            }
        }

        $allsum = array_sum($summ);
        $discountsum = $this->getDiscountShow();

        $calc_taxes = [];
		
        foreach ($summ as $tax => $val) {
            $percent = $allsum > 0 ? $val / $allsum :  $val;
            $pwd = $val - ($discountsum * $percent);

            if ($pwd < 0) {
                $pwd = 0;
            }

		if (substr($tax,0,14)=="additional_tax") $tax=(float)substr($tax,17,strlen($tax));

            $calc_taxes[$tax] = $pwd * $tax / (100 + $tax);
            if ($jshopConfig->display_price_front_current == 1) {
                $calc_taxes[$tax] = $pwd * $tax / 100;
            }
        }

        if (!$jshopConfig->discount_use_full_sum) {
            if ($incShiping) { // && $this->display_item_shipping
                $this->addTaxValueToCalc($this->getShippingTaxList(), $calc_taxes);
                $this->addTaxValueToCalc($this->getPackageTaxList(), $calc_taxes);
            }

            if ($incPayment) {// && $this->display_item_payment
                $this->addTaxValueToCalc($this->getPaymentTaxList(), $calc_taxes);
            }
        }

        return $calc_taxes;
    }

    protected function addTaxValueToCalc(array $taxListWithValues, array &$valuesToModify) 
    {
		$dispatcher = \JFactory::getApplication();
		$currentObj = $this;
        foreach ($taxListWithValues as $tax => $value) {
            if (isset($valuesToModify[$tax]) && $tax != 0 && $value != 0) {
				$dispatcher->triggerEvent('onCartAddTaxValueToCalsBeforeModify', [&$currentObj, &$tax, &$value,&$valuesToModify]);
				if (!isset($currentObj->skip)||($currentObj->skip!=1)){	
					$valuesToModify[$tax] += $value;
				}
            }
        }
    }

    public function setDisplayFreeAttributes()
    {
        $jshopConfig = JSFactory::getConfig();

        if (!empty($this->products)) {
            if ($jshopConfig->admin_show_freeattributes) {
                $tableOfFreeAttr = JSFactory::getTable('freeattribut', 'jshop');
                $namesOfFreeAttrs = $tableOfFreeAttr->getAllNames();
            }

            foreach ($this->products as $k => $prod) {
                $this->products[$k]['free_attributes_value'] = [];

                if ($jshopConfig->admin_show_freeattributes) {
                    $freeattributes = unserialize($prod['freeattributes']);

                    if (!is_array($freeattributes)) {
                        $freeattributes = [];
                    }

                    $free_attributes_value = [];
                    foreach($freeattributes as $id => $text) {
                        $obj = new stdClass();
                        $obj->attr_id = $id;
                        $obj->attr = $namesOfFreeAttrs[$id];
                        $obj->value = $text;
                        $free_attributes_value[] = $obj;
                    }

                    $this->products[$k]['free_attributes_value'] = $free_attributes_value;
                } 
            }
        }
    }

    public function setDisplayItem($shipping = 0, $payment = 0)
    {
        $this->display_item_shipping = $shipping;
        $this->display_item_payment = $payment;
    }

    public function setShippingsDatas($prices, $shipping_method_price)
    {
        $this->setShippingPrice($prices['shipping']);
        $this->setShippingTaxId($shipping_method_price->shipping_tax_id);
        $this->setShippingTaxList($shipping_method_price->calculateShippingTaxList($prices['shipping'], $this));
        $this->setShippingPriceForTaxes($shipping_method_price->getShipingPriceForTaxes($prices['shipping'], $this));
        $this->setPackagePrice($prices['package']);
        $this->setPackageTaxId($shipping_method_price->package_tax_id);
        $this->setPackageTaxList($shipping_method_price->calculatePackageTaxList($prices['package'], $this));
        $this->setPackagePriceForTaxes($shipping_method_price->getPackegePriceForTaxes($prices['package'], $this));
    }

    public function setShippingId($val)
    {
        $session = JFactory::getSession();
        $session->set('sh_pr_method_id', $val);
    }

    public function getShippingId() 
    {
        $session = JFactory::getSession();
        return $session->get('sh_pr_method_id');
    }

    public function setShippingPrId($val)
    {
        $session = JFactory::getSession();
        $session->set('sh_pr_method_id', $val);
    }

    public function getShippingPrId() 
    {
        $session = JFactory::getSession();
        return $session->get('sh_pr_method_id');
    }

    public function setShippingPrice($price)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_shipping', $price);
    }

    public function getShippingPrice() 
    {
        $session = JFactory::getSession();
        $price = $session->get('jshop_price_shipping');
        return getRoundPriceProduct(floatval($price));
    }

    public function setPackagePrice($price)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_package', $price);
    }

    public function getPackagePrice() 
    {
        $session = JFactory::getSession();
        $price = $session->get('jshop_price_package');
        return getRoundPriceProduct(floatval($price));
    }

    //deprecated
    public function setShippingPriceTax($price)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_shipping_tax', $price);
    }

    public function getShippingPriceTax() 
    {
        $session = JFactory::getSession();
        $price = $session->get('jshop_price_shipping_tax');

        return floatval($price);
    }

    //deprecated
    public function setShippingPriceTaxPercent($price)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_shipping_tax_percent', $price);
    }

    public function getShippingPriceTaxPercent()
    {
        $stl = $this->getShippingTaxList();

        if (is_array($stl) && count($stl) == 1) {
            $tmp = array_keys($stl);
            return $tmp['0'];
        }

        return 0;
    }

    public function setShippingTaxId($id)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_shipping_tax_id', $id);
    }

    public function getShippingTaxId()
    {
        $session = JFactory::getSession();
        return $session->get('jshop_price_shipping_tax_id');
    }

    public function setPackageTaxId($id)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_package_tax_id', $id);
    }

    public function getPackageTaxId()
    {
        $session = JFactory::getSession();
        return $session->get('jshop_price_package_tax_id');
    }

    public function setShippingTaxList($list)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_shipping_tax_list', $list);
    }

    public function getShippingTaxList()
    {
        $session = JFactory::getSession();
        return (array)$session->get('jshop_price_shipping_tax_list');
    }

    public function setPackageTaxList($list)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_package_tax_list', $list);
    }

    public function getPackageTaxList()
    {
        $session = JFactory::getSession();
        return (array)$session->get('jshop_price_package_tax_list');
    }

    public function setShippingPriceForTaxes($list)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_shipping_for_tax_list', $list);
    }

    public function getShippingPriceForTaxes()
    {
        $session = JFactory::getSession();
        return $session->get('jshop_price_shipping_for_tax_list');
    }

    public function setPackagePriceForTaxes($list)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_package_for_tax_list', $list);
    }

    public function getPackagePriceForTaxes()
    {
        $session = JFactory::getSession();
        return $session->get('jshop_price_package_for_tax_list');
    }

    public function getShippingNettoPrice()
    {
        $jshopConfig = JSFactory::getConfig();
        $shippingPrice = $this->getShippingPrice();

        if ($jshopConfig->display_price_front_current == 1) {
            return $shippingPrice;
        }

        $shippingTaxList = $this->getShippingTaxList();

        foreach($shippingTaxList as $value) {
            $shippingPrice -= $value;
        }

        return $shippingPrice;
        
    }

    public function getShippingBruttoPrice()
    {
        return $this->addTaxToPrice($this->getShippingPrice(), $this->getShippingTaxList());
    }

    public function getPackageBruttoPrice()
    {
        return $this->addTaxToPrice($this->getPackagePrice(), $this->getPackageTaxList());
    }

    public function getPaymentBruttoPrice()
    {
        return $this->addTaxToPrice($this->getPaymentPrice(), $this->getPaymentTaxList());
    }

    protected function addTaxToPrice($price, array $taxList)
    {
        $jshopConfig = JSFactory::getConfig();

        if ($jshopConfig->display_price_front_current == 1) {

            foreach($taxList as $value) {
                $price += $value;
            }

        }

        return $price;
    }

    public function setShippingParams($val)
    {
        $session = JFactory::getSession();
        $session->set('shipping_params', $val);
    }

    public function getShippingParams()
    {
        $session = JFactory::getSession();
        $val = $session->get('shipping_params');

        return $val;
    }

    public function setPaymentId($val)
    {
        $session = JFactory::getSession();
        $session->set('payment_method_id', $val);
    }

    public function getPaymentId()
    {
        $session = JFactory::getSession();
        return intval($session->get('payment_method_id'));
    }

    public function setPaymentPrice($val)
    {
        $session = JFactory::getSession();
        $session->set('jshop_payment_price', $val);
    }

    public function getPaymentPrice()
    {
        $session = JFactory::getSession();
        $price = $session->get('jshop_payment_price');

        return getRoundPriceProduct(floatval($price));
    }

    public function setPaymentDatas($price, $payment)
    {
        $this->setPaymentPrice($price);
        $this->setPaymentTaxList($payment->calculateTaxList($price));
        $this->setPaymentPriceForTaxes($payment->getPriceForTaxes($price));
    }

    public function setPaymentTaxList($list)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_payment_tax_list', $list);
    }

    public function getPaymentTaxList()
    {
        $session = JFactory::getSession();
        return (array)$session->get('jshop_price_payment_tax_list');
    }

    public function setPaymentPriceForTaxes($list)
    {
        $session = JFactory::getSession();
        $session->set('jshop_price_payment_for_tax_list', $list);
    }

    public function getPaymentPriceForTaxes()
    {
        $session = JFactory::getSession();
        return $session->get('jshop_price_payment_for_tax_list');
    }

    //deprecated
    public function setPaymentTax($val)
    {
        $session = JFactory::getSession();
        $session->set('jshop_payment_tax', $val);
    }

    public function getPaymentTax()
    {
        $session = JFactory::getSession();
        $price = $session->get('jshop_payment_tax');

        return $price;
    }

    //deprecated
    public function setPaymentTaxPercent($val)
    {
        $session = JFactory::getSession();
        $session->set('jshop_payment_tax_percent', $val);
    }

    public function getPaymentTaxPercent()
    {
        $ptl = $this->getPaymentTaxList();

        if (is_array($ptl) && count($ptl) == 1) {
            $tmp = array_keys($ptl);
            return $tmp['0'];
        }

        return 0;
    }

    public function setPaymentParams($val)
    {
        $session = JFactory::getSession();
        $session->set('pm_params', $val);
    }

    public function getPaymentParams()
    {
        $session = JFactory::getSession();
        $val = $session->get('pm_params');

        return $val;
    }

    public function getCouponId()
    {
        return $this->rabatt_id;
    }

    public function setDeliveryDate($date)
    {
        $session = JFactory::getSession();
        $session->set('jshop_delivery_date', $date);
    }

    public function getDeliveryDate()
    {
        $session = JFactory::getSession();

        return $session->get('jshop_delivery_date');
    }
	
	public function setShippingAddressId($id)
    {
        $session = JFactory::getSession();
        $session->set('jshop_shipping_address_id', $id);
    }

    public function getShippingAddressId()
    {
       $session = JFactory::getSession();

       return $session->get('jshop_shipping_address_id');
   }

    protected function setNativeUploadPriceData(object &$product, $uploadQty)
    {
        $amountOfUploads = $uploadQty ?: 0;
        if (!empty($amountOfUploads)) {			
            $uploadPrice = JSFactory::getModel('NativeUploadsPricesFront')->getUploadPriceData($product->product_id, $amountOfUploads);
            $product->setNativeUploadPrice($uploadPrice, $amountOfUploads);			
        }
    }

    public function updateCartProductPrice() 
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        
        foreach($this->products as $key => $value) {
			if(!$this->products[$key]['product_id']){unset($this->products[$key]); continue;}
            $product = JSFactory::getTable('product', 'jshop');
            $product->load($this->products[$key]['product_id']);
            if(!$product->product_id) {unset($this->products[$key]); continue;}
            $attr_id = unserialize($value['attributes']);
			$_attr_id = unserialize($value['_attributes']);
            $freeattributes = unserialize($value['freeattributes']);
            if($attr_id){ $product->setAttributeActive($attr_id); }
			if($_attr_id){ $product->setAttributeActive($_attr_id); }
            $product->setFreeAttributeActive($freeattributes);
            $uploadQty = !empty($this->products[$key]['uploadData']['files']) ? countOfNonEmptyValues($this->products[$key]['uploadData']['files']) : 0;
            $this->setNativeUploadPriceData($product, $uploadQty);
            $this->products[$key]['price'] = $product->getPrice($this->products[$key]['quantity'], 1,JSFactory::getModel('TaxesFront')->getAddTax(1), 1, $this->products[$key]);
			$this->products[$key]['ean'] = $product->product_ean;

			if ($jshopConfig->cart_basic_price_show) {
                $this->products[$key]['basicprice'] = $product->getBasicPrice();
            }
			
            $this->addOneTimeCostToProductTotalPrice($key);			
			
			$nativeUploadPrice = $product->getNativeUploadPrice();	
			if ((!empty($nativeUploadPrice))&&($product->is_activated_price_per_consignment_upload_disable_quantity)) {											
				$this->products[$key]['one_time_cost'] = $nativeUploadPrice->modifyPrice($this->products[$key]['one_time_cost'], $uploadQty);            
				$this->products[$key]['total_price'] = $nativeUploadPrice->modifyPrice($this->products[$key]['total_price'], $uploadQty);            
			}
		
        }

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterUpdateCartProductPrice', [&$currentObj]);
        $this->loadPriceAndCountProducts();
        $this->reloadRabatValue();
        $this->saveToSession();
    }

    public function add($product_id, $quantity, $attr_id, $freeattributes, $additional_fields = [], $usetriggers = 1, &$errors = [], $displayErrorMessage = 1, $uploadDataArr = [])
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
		$_attr_id = $attr_id;
        if ($quantity <= 0) {
            $errors['100'] = 'Error quantity';

			if ($displayErrorMessage) {
                \Joomla\CMS\Factory::getApplication()->enqueueMessage($errors['100'], 'notice');
            }

            return 0;
        }
        $isUpdateQty = 1;
        if ($usetriggers) {
            $dispatcher = \JFactory::getApplication();
            ExcludeAttributeForAttribute::getInstance()->onBeforeAddProductToCart($this, $product_id, $quantity, $attr_id, $freeattributes, $isUpdateQty, $errors, $displayErrorMessage, $additional_fields, $usetriggers, $uploadDataArr);
			$buttons=ExcludeButtonsForAttribute::getInstance()->onBeforeAddProductToCart($this, $product_id, $quantity, $attr_id, $freeattributes, $isUpdateQty, $errors, $displayErrorMessage, $additional_fields, $usetriggers, $uploadDataArr);
            $dispatcher->triggerEvent('onBeforeAddProductToCart', [&$currentObj, &$product_id, &$quantity, &$attr_id, &$freeattributes, &$isUpdateQty, &$errors, &$displayErrorMessage, &$additional_fields, &$usetriggers, &$uploadDataArr]);			
            CheckoutFreeAttrsDefaultValuesMambot::getInstance()->onBeforeAddProductToCart($this, $product_id, $quantity, $attr_id, $freeattributes, $isUpdateQty, $errors, $displayErrorMessage, $additional_fields, $usetriggers, $uploadDataArr);
            CheckoutMinMaxQuantityMambot::getInstance()->onBeforeAddProductToCart($this, $product_id, $quantity, $attr_id, $freeattributes, $isUpdateQty, $errors, $displayErrorMessage, $additional_fields, $usetriggers, $uploadDataArr);
            CheckoutSelectQuantityMambot::getInstance()->onBeforeAddProductToCart($this, $product_id, $quantity, $attr_id, $freeattributes, $isUpdateQty, $errors, $displayErrorMessage, $additional_fields, $usetriggers, $uploadDataArr);
       }

		$buttons = serialize($buttons);
        $attr_serialize = serialize($attr_id);
		if($_attr_id){
			$separatedAttrs = JSFactory::getModel('AttrsFront')->separateAttrsByTypes($_attr_id)->independs;
			foreach($separatedAttrs as $k => $val){
				if(!isset($attr_id[$k]) || $attr_id[$k] <= 0){
					unset($_attr_id[$k]);
				}
			}
		}
        $_attr_serialize = serialize($_attr_id);
        $free_attr_serialize = serialize($freeattributes);

        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);

        if (!empty($uploadDataArr) && !empty($uploadDataArr['files']) ) {
            $uploadModel = JSFactory::getModel('upload');

            if (count($uploadDataArr['files']) != 1 && !empty($uploadDataArr['files']['0'])) {
                $isSuccessValidateUploadFiles = $uploadModel->isValidateUploadFiles($uploadDataArr, $quantity, false, $product->is_upload_independ_from_qty);

                if (!$isSuccessValidateUploadFiles) {
                    return 0;
                }
            }

            $uploadDataArr = $uploadModel->getCleanedArrWithUploadData($uploadDataArr);
        }

        //check attributes
        $required_attr = $product->getRequireAttribute();
		$_attr = JSFactory::getTable('attribut', 'jshop');
		$count_required = count($required_attr);
		foreach($attr_id as $key=>$val){
			if($val == -2){
				$attr_exclude++;
				unset($attr_id[$key]);
			}
		}
		foreach($required_attr as $attr){
			if(($_attr->getTypeAttribut($attr) == 3)||($_attr->getTypeAttribut($attr) == 4)){
				$count_required--;
			}
		}
		if($attr_exclude){
			$count_required -= $attr_exclude; 
		}
        if ( ($count_required > count($attr_id)) || in_array(0, $attr_id)){
            $errors['101'] = JText::_('COM_SMARTSHOP_SELECT_PRODUCT_OPTIONS');
            if ($displayErrorMessage){
                \Joomla\CMS\Factory::getApplication()->enqueueMessage($errors['101'], 'notice');
            }
            return 0;
        }

        //check free attributes
        if ($jshopConfig->admin_show_freeattributes) {
            $allfreeattributes = $product->getListFreeAttributes();
			$smarteditor_product = JFactory::getApplication()->input->getInt('smarteditor_product');
            $error = 0;

            foreach($allfreeattributes as $k => $v) {
                if ($v->required && ((trim($freeattributes[$v->id]) == '') || trim($freeattributes[$v->id]) == 'file|')) {
					if (!($smarteditor_product == 1 && $v->type ==2)) {
                        $error = 1;
                        
                        $errors['102_' . $v->id] = JText::sprintf('COM_SMARTSHOP_PLEASE_ENTER_X', $v->name);
                        
						if ($displayErrorMessage) {
                            \Joomla\CMS\Factory::getApplication()->enqueueMessage($errors['102_' . $v->id], 'notice');
						}
					}
                }
            }

            if ($error) {
                return 0;
            }
        }

		$product->setAttributeActive($attr_id);
        $product->setFreeAttributeActive($freeattributes);
		$product->setButtonsActive($buttons);
        $qtyInStock = $product->getQtyInStock();
        $pidCheckQtyValue = $product->getPIDCheckQtyValue();

        if ( ProductsFreeAttrsDefaultValuesMambot::getInstance()->isProductWidthHeightOutOfQuotaMinMaxVal($product) ) {
            foreach($product->error_message as $errorMsg) {
                \JFactory::getApplication()->enqueueMessage($errorMsg,'error');
            }

            return 0;
        }        
        $isNewProduct = true;
        $dataToVerify = [
            'product_id' => $product_id,
			'buttons' => $buttons,
            'attr_serialize' => $attr_serialize,
			'_attr_serialize' => $_attr_serialize,
            'free_attr_serialize' => $free_attr_serialize,
            'uploadDataArr' => $uploadDataArr,
            'attr_id' => $attr_id,
            'additional_fields' => $additional_fields
        ];

		if($quantity == 0) return false;
		
        if ($isUpdateQty) {
            $status = $this->updateProductQty($quantity, $product, $dataToVerify, $isNewProduct, $errors, $pidCheckQtyValue, $qtyInStock, $displayErrorMessage, $usetriggers);

            if ($status === 0) {
                return 0;
            }
        }
	
        if ($isNewProduct) {
            $status = $this->addNewProduct($quantity, $product, $dataToVerify, $isNewProduct, $errors, $pidCheckQtyValue, $qtyInStock, $displayErrorMessage, $usetriggers);

            if ($status === 0) {
                return 0;
            }
        }

        $this->loadPriceAndCountProducts();
		
        $this->reloadRabatValue();		
        $this->saveToSession();

        if ($usetriggers) {
            $dispatcher->triggerEvent('onAfterAddProductToCart', [&$currentObj, &$product_id, &$quantity, &$attr_id, &$freeattributes, &$errors, &$displayErrorMessage]);
			$this->updateCartProductPrice();       
        }
       
        return 1;
    }

    protected function updateProductQty(&$quantity, jshopProduct &$product, array $dataToVerify, bool &$isNewProduct, &$errors, &$pidCheckQtyValue, &$qtyInStock, &$displayErrorMessage, &$usetriggers)
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
		$productattrsfront = JSFactory::getModel('attrsfront');
		$_all_quantity = 0;
		$count_attr_expiration_cart = 0;
		if($product->product_packing_type == 1 && $product->attribute_active_data->expiration_date && $product->attribute_active_data->expiration_date != 0){
			$attribute_active = $product->attribute_active;
			if(!empty($attribute_active)){
				foreach ($this->products as $key => $value) {
					if ($value['product_id'] == $dataToVerify['product_id'] &&  $value['freeattributes'] == $dataToVerify['free_attr_serialize']  && $value['uploadData'] == $dataToVerify['uploadDataArr'] && $value['buttons'] == $dataToVerify['buttons']) {				
						$attrs = unserialize($value['attributes']);		
						$attrs = $productattrsfront->separateAttrsByTypes($attrs);
						foreach($attrs->depends as $k => $v){						
							if(array_key_exists($k, $attribute_active)){
								$count_attr_expiration_cart += $value['quantity'] ;
							}
						}
					}
				}
			}
			$count_attr_expiration_stock = $productattrsfront->countAttrExpirationData($product->product_id, $product->attribute_active);
		}
        foreach ($this->products as $key => $value) {
            if ($value['product_id'] == $dataToVerify['product_id'] && $value['attributes'] == $dataToVerify['attr_serialize'] && $value['freeattributes'] == $dataToVerify['free_attr_serialize']  && $value['uploadData'] == $dataToVerify['uploadDataArr'] && $value['buttons'] == $dataToVerify['buttons']) {
				$product_in_cart = $this->products[$key]['quantity'];
                $save_quantity = $product_in_cart + $quantity;
                if (isset($this->exceededQuotaOfProdCount[$value['product_id']])) {
                    $exceededQuotaData = $this->exceededQuotaOfProdCount[$value['product_id']];
                    $save_quantity = $exceededQuotaData['max']['predefined'] ?? $exceededQuotaData['min']['predefined'] ?? $quantity;
                }

                $sum_quantity = $save_quantity;
                foreach ($this->products as $key2 => $value2) {
                    if ($key == $key2) {
                        continue;
                    }

                    if ($value2['pid_check_qty_value'] == $pidCheckQtyValue) {
                        $sum_quantity += $value2['quantity'];
                        $product_in_cart += $value2['quantity'];
                    }
                }

				$productattrsfront = JSFactory::getModel('attrsfront');
				$attUnlimited = 0;
				if(!empty($product->attribute_active)){
					foreach($product->attribute_active as $key3=>$val){	
						$attrs = $productattrsfront->separateAttrsByTypes(array($key3=>$val));
						if(count($attrs->depends) > 0){
							$count_attr = $productattrsfront->countAttrDataVal($product->product_id, array($key3=>$val));
							if($count_attr == INF) { 
								$attUnlimited = 1;break;
							}elseif($count_attr > 0){
								break;
							}
						}
					}
				}
				$count_attr_expiration_date = $productattrsfront->countAttrExpirationData($product->product_id, $product->attribute_active);
				
                if (!$product->unlimited && $attUnlimited != 1 && $jshopConfig->controler_buy_qty && ($sum_quantity > $qtyInStock)) {
                    $balans = $qtyInStock - $product_in_cart;

                    if ($balans < 0) {
                        $balans = 0;
                    }
					
					if($product->product_packing_type == 1 && $product->attribute_active_data->expiration_date && $product->attribute_active_data->expiration_date != 0){
						if($this->type_cart != 'wishlist' && $count_attr_expiration_stock != INF && $count_attr_expiration_cart + $quantity > $count_attr_expiration_stock){
							 $balans = $count_attr_expiration_stock - $count_attr_expiration_cart;
							if ($balans < 0) {
								$balans = 0;
							}
							$errors['105'] = JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT_IN_CART', $count_attr_expiration_cart, $count_attr_expiration_stock);

							if ($displayErrorMessage) {
								//JError::raiseWarning(105, $errors['105']);
								//throw new Exception($errors['105'],105);	
								\JFactory::getApplication()->enqueueMessage($errors['105'],'notice');
							}

							return 0;
						}else{
							if($count_attr_expiration_stock == INF){
								$balans = $quantity;
							}else{
								$balans = $count_attr_expiration_stock - $count_attr_expiration_cart;
							}
							
							$attrs = [];
							$_quantity = $quantity;
							if($balans > 0 && $product->product_packing_type == 1 && $product->attribute_active_data->expiration_date && $product->attribute_active_data->expiration_date != 0){
								if(!empty($product->attribute_active)){
									foreach($product->attribute_active as $key3=>$val){							
										$attrs1 = $productattrsfront->separateAttrsByTypes(array($key3=>$val));		
										
										if(count($attrs1->depends) > 0){ 
											$attrs = $productattrsfront->getAttrsVals($product->product_id, $key3);
											foreach($attrs as $k=>$attr){ 
												if($_quantity > 0 && in_array($val, $attrs)){ 
													$count_attr = $productattrsfront->countAttrExpirationDataVal($product->product_id, array($key3=>$attr));
													$count_attr_in_cart = $this->getAttrsCountAttrIdsInCart($product, $key3, $attr);
													$new_qty = 0;
													
													if($count_attr - $count_attr_in_cart >= $_quantity || $count_attr == INF){
														$new_qty = $_quantity;
														$product->attribute_active[$key3] = (int)$attr;																
													}else{
														if($count_attr - $count_attr_in_cart > 0){
															$new_qty = $count_attr - $count_attr_in_cart;
															$product->attribute_active[$key3] = (int)$attr;
														}else{
															continue;
														}
													}
													if($new_qty > 0){ 
														$this->add($product->product_id, $new_qty, $product->attribute_active, $product->free_attribute_active, $dataToVerify['additional_fields'], $usetriggers, $errors, $displayErrorMessage, $uploadDataArr);
														$_quantity -= $new_qty;
													}												
												}
											}
										}
										
									}
									$isNewProduct = false;
									return 1;
								}
							}
						}
					}else{		
						if($this->type_cart != 'wishlist'){
							$errors['105'] = JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT_IN_CART', $this->products[$key]['quantity'], $balans);

							if ($displayErrorMessage) {
								//JError::raiseWarning(105, $errors['105']);
								//throw new Exception($errors['105'],105);	
								\JFactory::getApplication()->enqueueMessage($errors['105'],'notice');
							}
							return 0;
						}
					}
                }

			    $this->products[$key]['quantity'] = $save_quantity;
                $this->products[$key]['price'] = $product->getPrice($this->products[$key]['quantity'], 1, 1, 1, $this->products[$key]);
                $this->products[$key]['is_upload_independ_from_qty'] = $product->is_upload_independ_from_qty;

                if ($jshopConfig->cart_basic_price_show) {
                    $this->products[$key]['basicprice'] = $product->getBasicPrice();
                }

                $this->addOneTimeCostToProductTotalPrice($key);

                if ($usetriggers) {
                    $dispatcher->triggerEvent('onBeforeSaveUpdateProductToCart', [&$currentObj, &$product, $key, &$errors, &$displayErrorMessage, &$product_in_cart, &$quantity]);
                }
                $isNewProduct = false;
                break;
            }
        }
    }

    protected function addNewProduct(&$quantity, jshopProduct &$product, array $dataToVerify, bool &$isNewProduct, &$errors, &$pidCheckQtyValue, &$qtyInStock, &$displayErrorMessage, &$usetriggers)
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();

        $product_id = $dataToVerify['product_id'];
		$buttons = $dataToVerify['buttons'];
        $attr_serialize = $dataToVerify['attr_serialize'];
		$_attr_serialize = $dataToVerify['_attr_serialize'];
        $free_attr_serialize = $dataToVerify['free_attr_serialize'];
        $uploadDataArr = $dataToVerify['uploadDataArr'];
        $attr_id = $dataToVerify['attr_id'];
		$_attr_id = unserialize($dataToVerify['_attr_serialize']);
        $additional_fields = $dataToVerify['additional_fields'];

        $product_in_cart = 0;
        foreach ($this->products as $value2) {
            if ($value2['pid_check_qty_value'] == $pidCheckQtyValue) {
                $product_in_cart += $value2['quantity'];
            }
        }
		$product->setAttributeActive(unserialize($_attr_serialize));
		$productattrsfront = JSFactory::getModel('attrsfront');
		$attUnlimited = 0;
		if(!empty($product->attribute_active)){
			foreach($product->attribute_active as $key=>$val){	
				$attrs = $productattrsfront->separateAttrsByTypes(array($key=>$val));
				if(count($attrs->depends) > 0){
					$count_attr = $productattrsfront->countAttrDataVal($product->product_id, array($key=>$val));
					if($count_attr == INF) {$attUnlimited = 1;break;}
				}
			}
		}
		
        $sum_quantity = $product_in_cart + $quantity;
		if (($jshopConfig->stock)AND(!$product->unlimited && $attUnlimited != 1 && $jshopConfig->controler_buy_qty && ($sum_quantity > $qtyInStock))) {
            $balans = $qtyInStock - $product_in_cart;
			$new_qty = $sum_quantity - $qtyInStock;
            if ($balans < 0) {
                $balans = 0;
            }			
		
			if($product->product_packing_type == 1 && $product->attribute_active_data->expiration_date && $product->attribute_active_data->expiration_date != 0){
				$count_attr_expiration_date = $productattrsfront->countAttrExpirationData($product->product_id, $product->attribute_active);
              
				if($this->type_cart != 'wishlist' && $sum_quantity > $count_attr_expiration_date && $count_attr_expiration_date != INF){
					$balans = $count_attr_expiration_date - $product_in_cart;
					if ($balans < 0) {
						$balans = 0;
					}
					$errors['108'] = JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT', $balans);

					if ($displayErrorMessage) {
						$Itemid = getShopCategoryPageItemid($product->getCategory());
						if (version_compare(JVERSION, '3.999.999', 'le')) {
							\Joomla\CMS\Factory::getApplication()->enqueueMessage($errors['108'], 'error');
							$app = JFactory::getApplication();
							$app->redirect(SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=' . $product->getCategory() . '&product_id=' . $product->product_id, 0, 0, null, $Itemid));
						} else {
							\JFactory::getApplication()->enqueueMessage($errors['108'],'error');
							$app = JFactory::getApplication();
							$app->redirect(SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=' . $product->getCategory() . '&product_id=' . $product->product_id, 0, 0, null, $Itemid));
          				}
						
					}

					return 0;
				}
			}
			
			$fl = 0;
			$attrs = [];
			if($balans > 0 && $product->product_packing_type == 1 && $product->attribute_active_data->expiration_date && $product->attribute_active_data->expiration_date != 0){
				if(!empty($product->attribute_active)){
					foreach($product->attribute_active as $key=>$val){	
						$attrs = $productattrsfront->separateAttrsByTypes(array($key=>$val));										
						if(count($attrs->depends) > 0){					
							$attrs = $productattrsfront->getAttrsValsByProdAndAttrIdsList($product->product_id, $key);						
							foreach($attrs as $k=>$value){
								if($value->count > 0 && $value->val_id == $val && $attrs[$k+1]){
									$fl = 1;
									$quantity = $balans;
									$product->attribute_active[$key] = (int)$attrs[$k+1]->val_id;
									
									$this->add($product_id, $new_qty, $product->attribute_active, $product->free_attribute_active, [], $usetriggers, $errors, $displayErrorMessage, $uploadDataArr);
								}
							}
						}
					}
				}
			}
			if($this->type_cart != 'wishlist' && $fl == 0 && $balans > 0){
				$errors['108'] = JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT', $balans);

				if ($displayErrorMessage) {
					if (version_compare(JVERSION, '3.999.999', 'le')) {             
						JError::raiseWarning(108, $errors['108']);
					}else{
						//throw new Exception($errors['108'],108);
						
						$app = JFactory::getApplication();
						\JFactory::getApplication()->enqueueMessage($errors['108'],'error');
						$Itemid = getShopCategoryPageItemid($product->getCategory());
						$app->redirect(SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=' . $product->getCategory() . '&product_id=' . $product->product_id, 0, 0, null, $Itemid));
          
					}		
				}

				return 0;
			}
        }

        $product->getDescription();
        $productWithSupportUpload = $product->getEssenceWithActiveUpload();
        $temp_product['quantity'] = $quantity;
        $temp_product['product_id'] = $product_id;
        $temp_product['category_id'] = $product->getCategory(false);
        if(!$jshopConfig->hide_tax) $temp_product['tax'] = $product->getTax();
        if(!$jshopConfig->hide_tax) $temp_product['tax_id'] = $product->product_tax_id;
		$temp_product['storage'] = $product->storage;
        $temp_product['product_name'] = $product->name;
        $temp_product['thumb_image'] = $product->getUrlOfMainImage();
        $temp_product['delivery_times_id'] = $product->getDeliveryTimeId();
        $temp_product['ean'] = $product->getEan();
        $temp_product['attributes'] = $attr_serialize;
		$temp_product['_attributes'] = $_attr_serialize;
        $temp_product['attributes_value'] = array();
        $temp_product['_attributes_value'] = array();
        $temp_product['extra_fields'] = array();
        $temp_product['weight'] = $product->getWeight();
        $temp_product['vendor_id'] = fixRealVendorId($product->vendor_id);
        $temp_product['files'] = serialize($product->getSaleFiles());
        $temp_product['freeattributes'] = $free_attr_serialize;
        $temp_product['is_allow_uploads'] = (bool)$productWithSupportUpload->is_allow_uploads;
        $temp_product['max_allow_uploads'] = ($productWithSupportUpload->is_unlimited_uploads) ? INF : $productWithSupportUpload->max_allow_uploads;
        $temp_product['is_required_upload'] = (bool)$productWithSupportUpload->is_required_upload;
        $temp_product['productMaxQty'] = $product->unlimited ? INF : (int)$product->getFullQty();
        $temp_product['is_unlimited_uploads'] = (bool)$productWithSupportUpload->is_unlimited_uploads;
        $temp_product['is_upload_independ_from_qty'] = (bool)$productWithSupportUpload->is_upload_independ_from_qty;
        $temp_product['buttons'] = $buttons;
        $temp_product['production_time'] = $product->attribute_active_data->production_time ?: $product->production_time;
        $temp_product['is_use_additional_shippings'] = $product->attribute_active_data->ext_data->is_use_additional_shippings ?: 0;
        $temp_product['is_product_from_editor'] = $product->isFromEditor();
        
        if (!empty($product->attribute_active_data->ext_data->product_id)) {
            $temp_product['prod_id_of_additional_val'] = $product->attribute_active_data->ext_data->product_id;
        }

        if (!empty($productWithSupportUpload->is_allow_uploads)) {
            $temp_product['product_id_with_support_upload'] = $productWithSupportUpload->is_allow_uploads;
        }

        if (!empty($uploadDataArr)) {
            $temp_product['uploadData'] = $uploadDataArr;

            if (!empty($uploadDataArr['files'])) {
                $uploadQty = !empty($uploadDataArr['files']) ? countOfNonEmptyValues($uploadDataArr['files']) : 0;
                $this->setNativeUploadPriceData($product, $uploadQty);
            }
        }

        $temp_product['manufacturer'] = '';
        if ($jshopConfig->show_product_manufacturer_in_cart) {
            $manufacturer_info = $product->getManufacturerInfo();
            $temp_product['manufacturer'] = $manufacturer_info->name;
        }

        $temp_product['pid_check_qty_value'] = $pidCheckQtyValue;
        $i = 0;	

        $activeAttrValuesIds = [];
        if (is_array($attr_id) && !empty($attr_id)) {
            foreach($attr_id as $key => $value) {
                $value = is_array($value) ? $value : [$value];

                foreach ($value as $k => $v) {
                    $attr = JSFactory::getTable('attribut', 'jshop');
                    $attr_v = JSFactory::getTable('attributvalue', 'jshop');
                    $attrType = $attr->getTypeAttribut($key);
                    $isHiddeAttr = ($attrType == 3); 

                    $temp_product['attributes_value'][$i] = new stdClass();
                    $temp_product['attributes_value'][$i]->attr_id = $key;
                    $temp_product['attributes_value'][$i]->value_id = $v;
                    $temp_product['attributes_value'][$i]->attr = $isHiddeAttr ? '' : $attr->getName($key);
                    $temp_product['attributes_value'][$i]->value = $isHiddeAttr ? '' : $attr_v->getName($v);
                    $temp_product['attributes_value'][$i]->attr_type = $attrType;
                    $activeAttrValuesIds[] = $value;					
                    $i++;
                }
            }			
        }

		if (is_array($_attr_id) && !empty($_attr_id)) {
            foreach($_attr_id as $key => $value) {
                $value = is_array($value) ? $value : [$value];

                foreach ($value as $k => $v) {
                    $attr = JSFactory::getTable('attribut', 'jshop');
                    $attr_v = JSFactory::getTable('attributvalue', 'jshop');
                    $attrType = $attr->getTypeAttribut($key);
                    $isHiddeAttr = ($attrType == 3); 

                    $temp_product['_attributes_value'][$i] = new stdClass();
                    $temp_product['_attributes_value'][$i]->attr_id = $key;
                    $temp_product['_attributes_value'][$i]->value_id = $v;
                    $temp_product['_attributes_value'][$i]->attr = $isHiddeAttr ? '' : $attr->getName($key);
                    $temp_product['_attributes_value'][$i]->value = $isHiddeAttr ? '' : $attr_v->getName($v);
                    $temp_product['_attributes_value'][$i]->attr_type = $attrType;
                    					
                    $i++;
                }
            }			
        }
		
        if ($jshopConfig->admin_show_product_extra_field && !empty($jshopConfig->getCartDisplayExtraFields())) {
            $extra_field = $product->getExtraFields(2);
            $temp_product['extra_fields'] = $extra_field;
        }

        foreach($additional_fields as $k => $v) {
            if ($k != 'after_price_calc') {
                $temp_product[$k] = $v;
            }
        }

        if ($usetriggers) {
            ExcludeAttributeForAttribute::getInstance()->onBeforeSaveNewProductToCartBPC($this, $temp_product, $product, $errors, $displayErrorMessage);
            $dispatcher->triggerEvent('onBeforeSaveNewProductToCartBPC', [&$currentObj, &$temp_product, &$product, &$errors, &$displayErrorMessage]);
        }
		
        $temp_product['price'] = $product->getPrice($quantity, 1, 1, 1, $temp_product);
		
		$temp_product['oneTimePrice'] = $temp_product['price'] - $pr;

		
		//$temp_product['is_activated_price_per_consignment_upload_disable_quantity']=$product->is_activated_price_per_consignment_upload_disable_quantity;						
		//$temp_product['getNativeUploadPrice']=$product->getNativeUploadPrice();
        /**/
		
		
		
		
        if ($jshopConfig->cart_basic_price_show) {
            $temp_product['basicprice'] = $product->getBasicPrice();
            $temp_product['basicpriceunit'] = $product->getBasicPriceUnit();
        }

        $this->addOneTimeCostToProductTotalPrice(null, $product, $temp_product);
		/*NEW FUNCTION*/
		$nativeUploadPrice = $product->getNativeUploadPrice();		
		if ((!empty($nativeUploadPrice))&&($product->is_activated_price_per_consignment_upload_disable_quantity)) {			
            $amountOfUploads = $product->getNativeAmountOfUploads();			
            $temp_product['oneTimePrice'] = $nativeUploadPrice->modifyPrice($temp_product['oneTimePrice'], $amountOfUploads);            
			$temp_product['one_time_cost'] = $nativeUploadPrice->modifyPrice($temp_product['one_time_cost'], $amountOfUploads);            
			$temp_product['total_price'] = $nativeUploadPrice->modifyPrice($temp_product['total_price'], $amountOfUploads);            
        }		

        if (is_array($additional_fields['after_price_calc']) && !empty($additional_fields['after_price_calc'])) {
            foreach($additional_fields['after_price_calc'] as $k => $v) {
                $temp_product[$k] = $v;
            }
        }
        $pr = JSFactory::getTable('product', 'jshop');
        $pr->load($temp_product['product_id']);
		//$pr->is_activated_price_per_consignment_upload_disable_quantity=$temp_product->is_activated_price_per_consignment_upload_disable_quantity;						
		//$pr->getNativeUploadPrice=$product->getNativeUploadPrice();
        $pr->setAttributeActive($attr_id);
		$pr->setButtonsActive($buttons);
        $pr->setFreeAttributeActive($freeattributes);
        $temp_product['aprice'] = $pr->getPrice($temp_product['quantity'], 1, 1, 1, $temp_product['product_id']);

        $hide_unchecked_attr = 1;
        if ($hide_unchecked_attr) {
            if ($temp_product['attributes']){
                $attr_id = unserialize($temp_product['attributes']);

                if (is_array($attr_id) && !empty($attr_id)) {
                    $attributes = self::_getAllCheckBoxAttribute();
                    if (!empty($attributes)) {
                        $i = 0;
                        foreach ($attr_id as $k => $v) {
                            if (isset($attributes[$k]) && ($attributes[$k] == $v)) {
								if ($temp_product['attributes_value'][$i]->attr_type!=3)
									unset($temp_product['attributes_value'][$i]);
                            }
                            $i++;
                        }
                    }
                }
            }
        }


        if ($usetriggers) {
            $this->beforeSaveNewProductToCart($this, $temp_product, $product);
        }
		$dispatcher->triggerEvent('onBeforeSaveNewProductToCart', [&$currentObj, &$temp_product, &$product, &$errors, &$displayErrorMessage, &$additional_fields]);
		CheckoutOfferAndOrder::getInstance()->onBeforeSaveNewProductToCart($this, $temp_product, $product, $errors, $displayErrorMessage);
        $this->products[] = $temp_product;
    }
	
	function _getAllCheckBoxAttribute(){
		$db = \JFactory::getDBO();
		$query = 'SELECT av.`value_id`, av.`attr_id` FROM `#__jshopping_attr_values` AS av
				INNER JOIN `#__jshopping_attr` AS a ON a.`attr_id`=av.`attr_id`
				WHERE a.`attr_type` = 3
				ORDER BY a.`attr_id`, av.`value_ordering`';
		$db->setQuery($query);
		$tmp = $db->loadObjectList();
        $attr_values = [];
        
		if (!empty($tmp)) {
			foreach ($tmp as $v) {
				if (!(isset($attr_values[$v->attr_id]) && $attr_values[$v->attr_id])) {
                    $attr_values[$v->attr_id] = $v->value_id;
                }
			}
        }
        
		unset($tmp);
		return $attr_values;
	}

	public function beforeSaveNewProductToCart(&$cart, &$temp_product, &$product) {

        $app = JFactory::getApplication();
        $modelOfFreeAttrCalcPriceFront = JSFactory::getModel('FreeAttrCalcPriceFront');
		$product->free_attribute_active = is_array($product->free_attribute_active) ? $product->free_attribute_active : [];
        $isIssetAtLeastOneNonEmptyFreeAttrCalcPriceParam = $modelOfFreeAttrCalcPriceFront->isIssetAtLeastOneIdInFormulaCalcBasicParams(array_keys($product->free_attribute_active));

        if ($isIssetAtLeastOneNonEmptyFreeAttrCalcPriceParam) {
            $isFreeAttrsValsAgreeWithBasicParamsLimits = $modelOfFreeAttrCalcPriceFront->isFreeAttrsValsAgreeWithBasicParamsLimits($product->free_attribute_active);
            
            if (!$isFreeAttrsValsAgreeWithBasicParamsLimits) {
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA'),'error');
				
				$Itemid = getShopCategoryPageItemid($product->getCategory());
                $app->redirect(SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=' . $product->getCategory() . '&product_id=' . $product->product_id, 0, 0, null, $Itemid));
            }

            $qty = JSFactory::getModel('ProductPriceTypeFront')->getCalcPriceType($product->product_price_type, $product->free_attribute_active);

            if ($qty <= 0) {
                $qty = '';
                return 0;
            }

            $temp_product['facp_label_label'] = '';
            $temp_product['facp_label_suffix'] = $qty . '';
        }
            
        
	}

/*******************************************************/
	public function refreshCart()
	{		
		$quantity=array();
		foreach ($this->products as $key=>$value){
			$quantity[$key]=$value['quantity'];
		}		
		$this->refresh($quantity);
	}

    public function refresh($quantity)
    {
        $jshopConfig = JSFactory::getConfig();
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeRefreshProductInCart', [&$quantity, &$currentObj]);
        CheckoutMinMaxQuantityMambot::getInstance()->onBeforeRefreshProductInCart($quantity, $this);

        if (is_array($quantity) && !empty($quantity)) {
            $lang = JSFactory::getLang();
            $name = $lang->get('name');
            foreach($quantity as $key => $value) {
                $value = intval($value);

                if ($jshopConfig->use_decimal_qty) {
                    $value = floatval(str_replace(',', '.', $value));
                    $value = round($value, $jshopConfig->cart_decimal_qty_precision);
                }
                
                $value = ($value <= 0) ? 1 : $value;

                $product = JSFactory::getTable('product', 'jshop');
                $product->load($this->products[$key]['product_id']);
                $attr = unserialize($this->products[$key]['attributes']) ?: [];
                $productFreeAttrs = unserialize($this->products[$key]['freeattributes']);
                $product->setAttributeActive($attr);
                $product->setFreeAttributeActive($productFreeAttrs);
                $qtyInStock = $product->getQtyInStock();
                $checkqty = $value;
				$dispatcher->triggerEvent('onRefreshProductInCartForeach', [&$currentObj, &$quantity, &$key, &$product, &$attr, &$productFreeAttrs, &$qtyInStock, &$checkqty, &$value]);

                foreach($this->products as $key2 => $value2) {
                    if ($key2 != $key && $value2['pid_check_qty_value'] == $this->products[$key]['pid_check_qty_value']) {
                        $checkqty += $value2['quantity'];
                    }
                }

                if ($jshopConfig->stock && (!$product->unlimited && $jshopConfig->controler_buy_qty && ($checkqty > $qtyInStock))) {
						if (version_compare(JVERSION, '3.999.999', 'le')) {
							\Joomla\CMS\Factory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT_BASKET'	, $product->$name, $qtyInStock), 'error');
						} else {
							\JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT_BASKET'	, $product->$name, $qtyInStock),'error');
						}                   
				    continue;
                }

                $uploadQty = !empty($this->products[$key]['uploadData']['files']) ? countOfNonEmptyValues($this->products[$key]['uploadData']['files']) : 0;
                $this->setNativeUploadPriceData($product, $uploadQty);

                $this->products[$key]['price'] = $product->getPrice($value, 1, 1, 1, $this->products[$key]); 
                
				if ($jshopConfig->cart_basic_price_show) {
                    $this->products[$key]['basicprice'] = $product->getBasicPrice();
                }
                $this->products[$key]['quantity'] = $value;
                $this->products[$key]['is_upload_independ_from_qty'] = $product->is_upload_independ_from_qty;
                

                $this->addOneTimeCostToProductTotalPrice($key);
                unset($product);
            }
        }

        $this->loadPriceAndCountProducts();
        $this->reloadRabatValue();
        $this->saveToSession();
        $dispatcher->triggerEvent('onAfterRefreshProductInCart', [&$quantity, &$currentObj]);
		$this->updateCartProductPrice();
        return 1;
    }

    protected function addOneTimeCostToProductTotalPrice($key = 0, &$product = null, &$temp_product = null)
    {
        $prodAttr2Table = JSFactory::getTable('ProductAttribut2');
        $activeAttrValuesIds = [];
        $prodAttrs = isset($key) ? $this->products[$key]['attributes_value'] : $temp_product['attributes_value'];
        if (!empty($prodAttrs)) {
            foreach ($prodAttrs as $prodKey => $attrObj) {
                $activeAttrValuesIds[] = $attrObj->value_id;
            }
        }        

        if (isset($key)) {
            $totalPriceWithOneTimeCost  = $prodAttr2Table->calcAttrsWithOneTimeCostPriceType($this->products[$key]['product_id'], $activeAttrValuesIds, $this->products[$key]['price'] * $this->products[$key]['quantity']);
            $this->products[$key]['one_time_cost'] = $totalPriceWithOneTimeCost;
            $this->products[$key]['total_price'] = $totalPriceWithOneTimeCost;
        } else {
            $jshopConfig = JSFactory::getConfig();

            if ($jshopConfig->cart_basic_price_show){
                $temp_product['basicprice'] = $product->getBasicPrice();
                $temp_product['basicpriceunit'] = $product->getBasicPriceUnit();
            }

            $totalPriceWithOneTimeCost = $prodAttr2Table->calcAttrsWithOneTimeCostPriceType($temp_product['product_id'], $activeAttrValuesIds, $temp_product['price'] * $temp_product['quantity']);
            $temp_product['one_time_cost'] = $totalPriceWithOneTimeCost;
            $temp_product['total_price'] = $totalPriceWithOneTimeCost;         
        }			
    }

    public function checkListProductsQtyInStore()
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onBeforeCheckListProductsQtyInStore', [&$currentObj]);
        $lang = JSFactory::getLang();
        $name = $lang->get('name');
        $check = 1;

        foreach($this->products as $key => $value) {
			if ($value['pid_check_qty_value'] == 'nocheck') {
                continue;
            }

            $productTable = JSFactory::getTable('product', 'jshop');
            $productTable->load($this->products[$key]['product_id']);
            $attr = unserialize($this->products[$key]['attributes']);
            $productTable->setAttributeActive($attr);
            $qtyInStock = $productTable->getQtyInStock();
            $checkqty = $value['quantity'];
			$dispatcher->triggerEvent('onCheckListProductsQtyInStoreForeach', [&$currentObj, &$key, &$productTable, &$attr, &$qtyInStock, &$checkqty]);

            foreach($this->products as $key2 => $value2) {
                if ($key2 != $key && $value2['pid_check_qty_value'] == $this->products[$key]['pid_check_qty_value']) {
                    $checkqty += $value2['quantity'];
                }
            }

            if (!$productTable->unlimited && $jshopConfig->controler_buy_qty && ($checkqty > $qtyInStock)) {
                $check = 0;
                \JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_ERROR_EXIST_QTY_PRODUCT_BASKET', $productTable->$name, $qtyInStock),'error');
                continue;
            }
        }

        $dispatcher->triggerEvent('onAfterCheckListProductsQtyInStore', [&$currentObj]);
        return $check;
    }

    public function checkCoupon()
    {
        if (!$this->getCouponId()) {
            return 1;
        }

        $currentObj = $this;
        $coupon = JSFactory::getTable('coupon', 'jshop');
        $coupon->load($this->getCouponId());
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeCheckCouponStep5save', [&$currentObj, &$coupon]);

        if (!$coupon->coupon_publish || $coupon->used || ($coupon->type == 1 && $coupon->coupon_value < $this->rabatt_value)) {
            return 0;
        }

        return 1;
    }

    public function getWeightProducts($products = [])
    {
        $weight_sum = 0;

        foreach ($this->products as $prod) {
			if(empty($products) || (!empty($products) && in_array($prod['product_id'], $products))){
				$weight_sum += $prod['weight'] * $prod['quantity'];
			}
        }

        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onGetWeightCartProducts', [&$currentObj, &$weight_sum]);
        return $weight_sum;
    }

	public function getMinMedianMaxFreeattrValProducts($freeAttrsIds, $products = []): array
    {
        $result = ['max' => 0, 'median' => 0, 'min' => 0];
		$freeAttrs = array_reduce($this->products, function ($carry, $cartProduct) use($freeAttrsIds) {
			if(empty($products) || (!empty($products) && in_array($cartProduct['product_id'], $products))){
				$carry = $carry ?: [];
				$freeAttrsOfCartProd = unserialize($cartProduct['freeattributes']) ?: [];
				
				if (!empty($freeAttrsOfCartProd)) {
					$result = array_filter($freeAttrsOfCartProd, function ($key) use($freeAttrsIds) {
						return in_array($key, $freeAttrsIds);
					}, ARRAY_FILTER_USE_KEY);
					
					$carry = array_merge($carry, $result);
					
					if(count($carry) < 3) {
						for($i = count($carry); $i < 3; $i++){
							$carry[] = 0;
						}
					}
				} elseif (!empty($cartProduct['xml'])) {
					$carry[] = getFromEditorXmlProductWidth($cartProduct['xml']);
					$carry[] = getFromEditorXmlProductHeight($cartProduct['xml']);
				}

				return $carry;
			}
        }) ?: [];
		
        if (!empty($freeAttrs)) {
            $result = [
                'max' => max($freeAttrs), 
                'median' => getMedianaValue($freeAttrs), 
                'min' => min($freeAttrs)
            ];
        }


        return $result;
    }
	
	public function getFreeattrValProducts($id, $type = 1, $products = [])
    {
        $val = 0;
        foreach ($this->products as $prod) {
			
			if(empty($products) || (!empty($products) && in_array($prod['product_id'], $products))){
				$fattrs = unserialize($prod['freeattributes']);
				if(isset($fattrs[$id]) && !empty($fattrs) && $fattrs[$id]){
					if($val < $fattrs[$id]) $val = $fattrs[$id];
				}elseif(isset($prod['xml']) && $prod['xml']){
					if($type == 1){
						$width = getFromEditorXmlProductWidth($prod['xml']);
						if($val < $width) $val = $width;
					}elseif($type == 2){
						$height = getFromEditorXmlProductHeight($prod['xml']);
						if($val < $height) $val = $height;
						
					}
				}
			}
        }
        return $val;
    }
	
    public function getSumValProducts($id, $products = [])
    {
        $val_sum = 0;
        foreach ($this->products as $prod) {
			if(empty($products) || (!empty($products) && in_array($prod['product_id'], $products))){
				$fattrs = unserialize($prod['freeattributes']);
				if(!empty($fattrs) && isset($fattrs[$id]) && $fattrs[$id]){
					$val_sum += $fattrs[$id] * $prod['quantity'];
				}
			}
        }
        return $val_sum;
    }
	
    public function getPriceProds($products = [])
    {
        $val_sum = 0;
			
		if(empty($products)){
			$val_sum = $this->price_product;
		}else{
			foreach ($this->products as $prod) {
				if((!empty($products) && in_array($prod['product_id'], $products))){
					$val_sum += $prod['price'];				
				}
				
			}
		}
		
        return $val_sum;
    }

    public function setRabatt($id, $type, $value) 
    {
        $this->rabatt_id = $id;
        $this->rabatt_type = $type;
        $this->rabatt_value = $value;
        $this->reloadRabatValue();
        $this->saveToSession();
    }

    public function getSummForCalculePlusPayment()
    {
        $sum = $this->getPriceBruttoProducts();

        if ($this->display_item_shipping) {
            $sum += $this->getShippingBruttoPrice();
            $sum += $this->getPackageBruttoPrice();
        }

        return $sum;
    }

    public function getSummForCalculeDiscount()
    {
        $jshopConfig = JSFactory::getConfig();
        $sum = $this->getPriceProducts();

        if ($jshopConfig->discount_use_full_sum && $jshopConfig->display_price_front_current == 1) {
            $sum = $this->getPriceBruttoProducts();
        }

        if ($jshopConfig->discount_use_full_sum) {
            if ($this->display_item_shipping) {
                $sum += $this->getShippingBruttoPrice();
                $sum += $this->getPackageBruttoPrice();
            }

            if ($this->display_item_payment) {
                $sum += $this->getPaymentBruttoPrice();
            }
        }

        return $sum;
    }

    public function reloadRabatValue()
    {
        $jshopConfig = JSFactory::getConfig();

        if ($this->rabatt_type == 1) {
            $this->rabatt_summ = $this->rabatt_value * $jshopConfig->currency_value; //value
        } else {
            $this->rabatt_summ = $this->getSummForCalculeDiscount() > 0 ? $this->rabatt_value / 100 * $this->getSummForCalculeDiscount() : $this->rabatt_value / 100; //percent
        }

        $this->rabatt_summ = round($this->rabatt_summ, 2);
    }

    public function updateDiscountData()
    {
        $this->reloadRabatValue();
        $this->saveToSession();
    }

    public function addLinkToProducts($show_delete = 0, $type = 'cart') 
    { 
		$currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $pieceOfUrlWithControllerName = 'index.php?option=com_jshopping&controller=' . $type;

		foreach($this->products as $key => $value) {		            
            $category_id = $this->products[$key]['category_id'];
            $Itemid = getShopCategoryPageItemid($category_id);
			$productItemid = getShopProductPageItemid($value['product_id']);
			if($productItemid){
				$Itemid = $productItemid;
			}	
			$this->products[$key]['href'] = SEFLink("index.php?option=com_jshopping&controller=product&task=view&category_id={$category_id}&product_id={$this->products[$key]['product_id']}", 0, 0, null, $Itemid);
          
			if ($show_delete) {
                $this->products[$key]['href_delete'] = SEFLink($pieceOfUrlWithControllerName . '&task=delete&number_id=' . $key);
            }

            if ($type == 'wishlist') {
                $this->products[$key]['remove_to_cart'] = SEFLink($pieceOfUrlWithControllerName . '&task=remove_to_cart&number_id=' . $key);
            }
        }
        $dispatcher->triggerEvent('onAfterAddLinkToProductsCart', [&$currentObj, &$show_delete, &$type]);
    }

    /**
    * get vendor type
    * return (1 - multi vendors, 0 - single vendor)
    */
    public function getVendorType(){
		$vendors = [];
        $currentObj = $this;
		$dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onAfterGetVendorType', [&$currentObj,&$vendors]);
        if (count($vendors) > 1) {
            return 1;
        }
        return 0;
    }

    /**
    * get id vendor
    * reutnr (-1) - if type == multivendors
    */
    public function getVendorId()
    {
        $vendors = [];
        $currentObj = $this;
		$dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onAfterGetVendorId', [&$currentObj,&$vendors]);
        if (empty($vendors)) {
            return 0;
        }elseif (count($vendors) > 1) {
            return -1;
        }
        return $vendors['0'];
    }

    public function getDelivery()
    {
        $deliverytimes = JSFactory::getAllDeliveryTime();
        $deliverytimesdays = JSFactory::getAllDeliveryTimeDays();
        $min_id = 0;
        $max_id = 0;
        $min_days = 0;
        $max_days = 0;

        foreach($this->products as $prod) {
            if ($prod['delivery_times_id']) {
                if ($min_days == 0) {
                    $min_days = $deliverytimesdays[$prod['delivery_times_id']];
                    $min_id = $prod['delivery_times_id'];
                }

                if ($deliverytimesdays[$prod['delivery_times_id']] < $min_days) {
                    $min_days = $deliverytimesdays[$prod['delivery_times_id']];
                    $min_id = $prod['delivery_times_id'];
                }

                if ($deliverytimesdays[$prod['delivery_times_id']] > $max_days) {
                    $max_days = $deliverytimesdays[$prod['delivery_times_id']];
                    $max_id = $prod['delivery_times_id'];
                }
            }
        }

        $delivery = $deliverytimes[$min_id] . ' - ' . $deliverytimes[$max_id];

        if ($min_id == $max_id) {
            $delivery = $deliverytimes[$min_id];
        }

        return $delivery;
    }

    public function getDeliveryDaysProducts()
    {
        $deliverytimesdays = JSFactory::getAllDeliveryTimeDays();
        $day = 0;

        foreach($this->products as $prod) {
            if (isset($prod['delivery_times_id'])) {
                if ($deliverytimesdays[$prod['delivery_times_id']] > $day) {
                    $day = $deliverytimesdays[$prod['delivery_times_id']];
                }
            }
        }

        return $day;
    }

    public function getReturnPolicy()
    {
        return [];
    }

    public function clear()
    {
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeClearCart', [&$currentObj]);
        $session = JFactory::getSession();
        $this->products = [];
        $this->rabatt = 0;
        $this->rabatt_value = 0;
        $this->rabatt_type = 0;
        $this->rabatt_summ = 0;
        $this->summ = 0;
        $this->count_product = 0;
        $this->price_product = 0;
        $session->set($this->type_cart, '');
        $session->set('pm_method', '');
        $session->set('pm_params', '');
        $session->set('payment_method_id', '');
        $session->set('shipping_method_id', '');
        $session->set('jshop_price_shipping', '');
		$session->set('checkcoupon', 0);
    }

    public function delete($number_id)
    {
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDeleteProductInCart', [&$number_id, &$currentObj]);

        unset($this->products[$number_id]);
        $this->loadPriceAndCountProducts();
        $this->reloadRabatValue();
        $this->saveToSession();

        $dispatcher->triggerEvent('onAfterDeleteProductInCart', [&$number_id, &$currentObj]);
		$this->updateCartProductPrice();
    }

    public function saveToSession()
    {
        $currentObj = $this;
        $session = JFactory::getSession();
		\JFactory::getApplication()->triggerEvent('onBeforeSaveToSessionCart', [&$currentObj]);
        $session->set($this->type_cart, serialize($this));		
		\JFactory::getApplication()->triggerEvent('onAfterSetToSessionCart', [&$currentObj]);
        $_tempcart = JSFactory::getModel('tempcart', 'jshop');
        $_tempcart->insertTempCart($this);
        \JFactory::getApplication()->triggerEvent('onAfterSaveToSessionCart', [&$currentObj]);
    }

	public function getPricesArray()
    {
		foreach ($this->products as $key => $value) {
			$product = JSFactory::getTable('product', 'jshop');
			$product->load($value['product_id']);
			$product->product_quantity = $value['quantity'];
			$product->getPricesArray();
			$this->products[$key]['prices_variables'] = $product->prices_variables;
		}
	}

    public function loadProductsFromArray($products)
    {
        foreach($products as $v) {
            $this->products[] = $v;
        }
    }
    
    public function removeUploadFile($productArrayKeyInCart, $uploadArrayKeyInCart)
    {
        $this->load();
        $uploadDataOfProduct = &$this->products[$productArrayKeyInCart]['uploadData'];

        if (!empty($uploadDataOfProduct)) {
            $namesOfArrays = ['qty', 'previews', 'files', 'descriptions'];

            foreach($namesOfArrays as $name) {
                if (isset($uploadDataOfProduct[$name][$uploadArrayKeyInCart])) {
                    unset($uploadDataOfProduct[$name][$uploadArrayKeyInCart]);
                }
            }

            $this->updateCartProductPrice();
            
            return true;
        }

        return false;
    }

    public function updateDataOfUploadsFiles($updateData)
    {
        if (!empty($updateData)) {

            $this->load();
            
            if (!empty($this->products)) {

                foreach($updateData as $dataObj) {
                    $productKeyToUpdate = $dataObj->productArrayKey;
                    $uploadKeyToUpdate = $dataObj->uploadNumber;
                    
                    if (isset($this->products[$productKeyToUpdate])) {
                        $product = &$this->products[$productKeyToUpdate];
                        $uploadDataOfProductInCart = &$product['uploadData'];

                        $uploadDataOfProductInCart['qty'][$uploadKeyToUpdate] = isset($dataObj->qty) ? $dataObj->qty : 1;
                        $uploadDataOfProductInCart['previews'][$uploadKeyToUpdate] = isset($dataObj->imagePreviewName) ? $dataObj->imagePreviewName : 'noimage.gif';
                        $uploadDataOfProductInCart['files'][$uploadKeyToUpdate] = isset($dataObj->fileName) ? $dataObj->fileName : 'noimage.gif';
                        $uploadDataOfProductInCart['descriptions'][$uploadKeyToUpdate] = isset($dataObj->descriptionText) ? $dataObj->descriptionText : '';

                        if (!empty($dataObj->totalCountOfProducts)) {
                            $this->products[$productKeyToUpdate]['quantity'] = (int) $dataObj->totalCountOfProducts;
                        }
                    }
                }

                $this->saveToSession();
                $this->loadPriceAndCountProducts();
                $this->reloadRabatValue();

                return true;
            }
        }

        return false;
    }

    public function renderSmallCart(string $cartName = 'cart')
    { 
        $jshopConfig = JSFactory::getConfig();
        $doc = JFactory::getDocument();


        JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models');
        $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel')->getParams();
        
        $cart = $this;
        $cart->load($cartName);
		$cart->refreshCart();
        $cart->addLinkToProducts(0);
        $cart->setDisplayFreeAttributes();
        
        $cart->setDisplayItem(1, 1);
        $cart->updateDiscountData();
        $cart->setDisplayData('checkout');
        
        $weight_product = $cart->getWeightProducts();
        if (empty($weight_product) && $jshopConfig->hide_weight_in_cart_weight0) {
            $jshopConfig->show_weight_order = 0;
        }
        
        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplaySmallCart', [&$cart]);
        CheckoutExtrascouponMambot::getInstance()->onBeforeDisplaySmallCart($cart);

        $view = new JViewLegacy([
            'template_path' => getPathToShopOrRewriteTmpls('quick_checkout_cart', 'default_quick_checkout_cart')
        ]);
		
        $layout = getLayoutName('quick_checkout_cart', 'default');
        $view->setLayout($layout);

        $view->set('config', $jshopConfig);
        $view->set('products', $cart->products);
		$view->set('summ', $cart->getPriceProducts());
        $view->set('image_product_path', $jshopConfig->image_product_live_path);
        $view->set('no_image', $jshopConfig->noimage);
        $view->set('discount', $cart->getDiscountShow());
        $view->set('free_discount', $cart->getFreeDiscount());
        $view->set('deliverytimes', JSFactory::getAllDeliveryTime());
        $view->set('summ_payment', $cart->getPaymentPrice());
        $view->set('production_time', $_production_calendar->show_in_cart_checkout );
        $isIncludeShipping = 0;
        
        if (!$jshopConfig->without_shipping) {
            $view->set('summ_delivery', $cart->getShippingPrice());

            if ($cart->getPackagePrice() > 0 || $jshopConfig->display_null_package_price) {
                $view->set('summ_package', $cart->getPackagePrice());
            }

            $view->set('summ_payment', $cart->getPaymentPrice());
            $isIncludeShipping = 1;
        }

        $fullsumm = $cart->getSum($isIncludeShipping, 1, 1);
        if(!$jshopConfig->hide_tax) $tax_list = $cart->getTaxExt($isIncludeShipping, 1, 1); else $tax_list = [];

        $name = JSFactory::getLang()->get('name');
        $pm_method = JTable::getInstance('paymentMethod', 'jshop');
        $payment_method_id = $cart->getPaymentId();
        $pm_method->load($payment_method_id);
        $view->set('payment_name', $pm_method->$name);
        
        $show_percent_tax = 0;
        if (count($tax_list) > 1 || $jshopConfig->show_tax_in_product) {
            $show_percent_tax = 1;
        }

        if ($jshopConfig->hide_tax) {
            $show_percent_tax = 0;
        }

        $hide_subtotal = 0;
        if (($jshopConfig->hide_tax || empty($tax_list)) && !$cart->rabatt_summ && $jshopConfig->without_shipping && $cart->getPaymentPrice() == 0) {
            $hide_subtotal = 1;
        }
        
        $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL');
        if (($jshopConfig->show_tax_in_product || $jshopConfig->show_tax_product_in_cart) && !empty($tax_list)) {
            $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL_INKL_TAX');
        }

        $view->set('tax_list', $tax_list);
        $view->set('fullsumm', $fullsumm);
        $view->set('show_percent_tax', $show_percent_tax);
        $view->set('hide_subtotal', $hide_subtotal);
        $view->set('text_total', $text_total);
        $view->set('weight', $weight_product);
        $view->set('formatweight', formatweight($weight_product));
		$this->viewLabelSuffixInCart($view);
		$view->set('formattax_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=formattax', 1));
		$view->set('tax_info_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=productTaxInfo', 1));
		$view->set('sprintbasicprice_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintBasicPrice', 1));

        $dispatcher->triggerEvent('onBeforeDisplayCheckoutCartView', [&$view]);

        $view->set('component', 'Default_quick_checkout_cart');
        $doc->addScriptDeclaration('const dataJsonCart='.json_encode(prepareView($view)));

        return $view->loadTemplate();
    }


    public function viewSmallCart(&$view)
    {
        $jshopConfig = JSFactory::getConfig();
        $doc = JFactory::getDocument();


        JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models');
        $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel')->getParams();

        $cart = $this;
        $cart->load();
		$cart->refreshCart();
        $cart->addLinkToProducts(0);
        $cart->setDisplayFreeAttributes();

        $cart->setDisplayItem(1, 1);
        $cart->updateDiscountData();
        $cart->setDisplayData('checkout');

        $weight_product = $cart->getWeightProducts();
        if (empty($weight_product) && $jshopConfig->hide_weight_in_cart_weight0) {
            $jshopConfig->show_weight_order = 0;
        }

        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplaySmallCart', [&$cart]);
        CheckoutExtrascouponMambot::getInstance()->onBeforeDisplaySmallCart($cart);

        $view->set('config', $jshopConfig);
        $view->set('products', $cart->products);
		$view->set('summ', $cart->getPriceProducts());
        $view->set('image_product_path', $jshopConfig->image_product_live_path);
        $view->set('no_image', $jshopConfig->noimage);
        $view->set('discount', $cart->getDiscountShow());
        $view->set('free_discount', $cart->getFreeDiscount());
        $view->set('deliverytimes', JSFactory::getAllDeliveryTime());
        $view->set('summ_payment', $cart->getPaymentPrice());
        $view->set('production_time', $_production_calendar->show_in_cart_checkout );
        $isIncludeShipping = 0;

        if (!$jshopConfig->without_shipping) {
            $view->set('summ_delivery', $cart->getShippingPrice());

            if ($cart->getPackagePrice() > 0 || $jshopConfig->display_null_package_price) {
                $view->set('summ_package', $cart->getPackagePrice());
            }

            $view->set('summ_payment', $cart->getPaymentPrice());
            $isIncludeShipping = 1;
        }

        $fullsumm = $cart->getSum($isIncludeShipping, 1, 1);
        $tax_list = $cart->getTaxExt($isIncludeShipping, 1, 1);
		
		$dispatcher->triggerEvent('onBeforeDisplayCheckoutCartViewSet', [&$tax_list]);		
		
        $name = JSFactory::getLang()->get('name');
        $pm_method = JTable::getInstance('paymentMethod', 'jshop');
        $payment_method_id = $cart->getPaymentId();
        $pm_method->load($payment_method_id);
        $view->set('payment_name', $pm_method->$name);

        $show_percent_tax = 0;
        if (count($tax_list) > 1 || $jshopConfig->show_tax_in_product) {
            $show_percent_tax = 1;
        }

        if ($jshopConfig->hide_tax) {
            $show_percent_tax = 0;
        }

        $hide_subtotal = 0;
        if (($jshopConfig->hide_tax || empty($tax_list)) && !$cart->rabatt_summ && $jshopConfig->without_shipping && $cart->getPaymentPrice() == 0) {
            $hide_subtotal = 1;
        }

        $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL');
        if (($jshopConfig->show_tax_in_product || $jshopConfig->show_tax_product_in_cart) && !empty($tax_list)) {
            $text_total = JText::_('COM_SMARTSHOP_ENDTOTAL_INKL_TAX');
        }

        $view->set('tax_list', $tax_list);
        $view->set('fullsumm', $fullsumm);
        $view->set('show_percent_tax', $show_percent_tax);
        $view->set('hide_subtotal', $hide_subtotal);
        $view->set('text_total', $text_total);
        $view->set('weight', $weight_product);
        $view->set('formatweight', formatweight($weight_product));
		$this->viewLabelSuffixInCart($view);
		$view->set('formattax_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=formattax', 1));
		$view->set('tax_info_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=productTaxInfo', 1));
		$view->set('sprintbasicprice_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintBasicPrice', 1));

        $dispatcher->triggerEvent('onBeforeDisplayCheckoutCartView', [&$view]);

    }

    protected function viewLabelSuffixInCart(&$view) 
    {
       if (!empty($view->products)) {
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

    public function countOfProductsByProdId(int $productId): int
    {
        $cart = $this;
        $cart->load();
        
        $count = 0;

        if (!empty($cart->products)) {
            foreach ($cart->products as $product) {
                if ($product['product_id'] == $productId && !$product['atrprodnum'] && !$product['adtprodnum']) {
                    $count += $product['quantity'];
                }
            }
        }
        
        return $count;
    }
    
    public function isExistsAnyProductWithNoReturn(): bool
    {
        if (!empty($this->products)) {
            foreach($this->products as $product) {
                $tableOfProductOption = JSFactory::getTable('productOption', 'jshop');
                $isReturn = $tableOfProductOption->getProductOptions($product['product_id'])['no_return'] ?? 0;
    
                if (!empty($isReturn)) {
                    return true;
                }
            }
        }

        return false;
    }
		
	public function setDisplayButtons(){
        if (count($this->products)){            
            foreach ($this->products as $k=>$prod){
               $this->products[$k]['buttons']=unserialize($prod['buttons']);
            }
        }
    }
	
	protected function getAttrsCountAttrIdsInCart($product, $attr_key, $attr_value){
		
		if (!empty($product)) {
			$quantity = 0;
			foreach($this->products as $value) {
                if ($value['product_id'] == $product->product_id && !empty($value['attributes_value'])) {
					foreach($value['attributes_value'] as $attr){
						if($attr->attr_id == $attr_key && $attr->value_id == $attr_value)
							$quantity += $value['quantity'];
					}
				}
            }
			return $quantity;
        }
	}
	
    public function getEditorAttributesToProducts() 
    {        
		$jshopConfig = &JSFactory::getConfig();
		$jshopConfig->loadCurrencyValue();
		$db = & JFactory::getDBO();
		$lang = &JFactory::getLanguage();
		
        foreach($this->products as $key => $value) {
			if(!$value['product_id']) continue;
			$this->products[$key]['editor_attr'] = [];
			$db->setQuery('SELECT `short_description_' . $lang->getTag() . '` as short_description FROM #__jshopping_products where product_id=' . $value['product_id'] . ' AND editor_id>0');
			$res = $db->loadObjectList();  
			
			$prices = (isset($res[0]->short_description)) ? explode('<br>', $res[0]->short_description) : [];
			foreach ($prices as $single_price) {
				if (strpos($single_price, '::') > 0) {
					$sum = substr($single_price, 0, strpos($single_price, '::'));
					$this->products[$key]['editor_attr'][] = substr($single_price, strpos($single_price, '::') + 2, strlen($single_price));
				}
			}			
        }
    }
	public function setQuantitySelect(){
		if (!empty($this->products)) {
			
			foreach($this->products as $k=>$value) {
				$pr = JTable::getInstance('product', 'jshop'); 
				$pr->load($value['product_id']);
				$this->products[$k]['quantity_select'] = $pr->quantity_select;
			}
        }

	}

	public function setDisplayData($type = ''){
        $jshopConfig = JSFactory::getConfig();
        $jsUri = JSFactory::getJSUri();
		if (!empty($this->products)) {
			foreach($this->products as $k=>$prod) {
                $this->products[$k]['attributes_display'] = getAtributeInCart($prod['attributes_value']);
                $this->products[$k]['_mirror_editor_display'] = sprintAtributeInCart($prod['_mirror_editor_data'] ?? []);
                $this->products[$k]['free_attributes_display'] = sprintFreeAtributeInCart($prod['free_attributes_value'], $prod['product_id'], isset($prod['prod_id_of_additional_val']) ? $prod['prod_id_of_additional_val'] : 0);
                if (!empty($prod['extra_fields'])) $this->products[$k]['extra_fields_display'] = separateExtraFieldsWithUseHideImageCharactParams($prod['extra_fields'], $type);
                $isMultiUpload = $prod['max_allow_uploads'] >= 2 || $prod['max_allow_uploads'] == 'INF' || $prod['is_unlimited_uploads'];

                $this->products[$k]['sprintjstempfiles'] = sprintJsTemplateForNativeUploadedFiles($isMultiUpload);

                $this->products[$k]['sprintPreviewNativeUploadedFiles'] = sprintPreviewNativeUploadedFiles($prod['uploadData'] ?? []);

                if (!empty($this->products[$k]['thumb_image'])) {
                    $this->products[$k]['image'] = $jsUri->isUrl($this->products[$k]['thumb_image']) ? $this->products[$k]['thumb_image'] : "{$jshopConfig->image_product_live_path}/{$this->products[$k]['thumb_image']}";
                }else{
                    $this->products[$k]['image'] = $jshopConfig->no_image_product_live_path;
                }
                $this->products[$k]['sprintPreviewNativeUploadedFiles'] = sprintPreviewNativeUploadedFiles($prod['uploadData'] ?? []);
			}
        }
    }

}
