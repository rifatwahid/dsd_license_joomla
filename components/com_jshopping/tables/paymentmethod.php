<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopPaymentMethod extends JTableAvto 
{
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_payment_method', 'payment_id', $_db);
        JPluginHelper::importPlugin('jshoppingcheckout');
    }
   
    public function bind($src, $ignore = Array())
	{
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					$src[$key]=0;
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}
	
    public function loadFromClass($class)
    {
        $id = $this->getId($class);
        return $this->load($id);
    }

    public function getAllPaymentMethods(int $publish = 1, int $shipping_id = 0, $usergroup = '')
    {
        return JSFactory::getModel('PaymentsFront')->getAllMethods($publish, $shipping_id, $usergroup);
    }

    /**
    * get id payment for payment_class
    */
    public function getId($paymentClass = '')
    {
        $paymentClass = $paymentClass ?: $this->class ?: '';
        return JSFactory::getModel('PaymentsFront')->getIdByPaymentClass($paymentClass);
    }
    
    public function setCart(&$cart)
    {
        $this->_cart = $cart;
    }
    
    public function getCart()
    {
        return $this->_cart;
    }
    
    public function getPrice()
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $cart = $this->getCart();

        if ($this->price_type == 2) {
            $price = $cart->getSummForCalculePlusPayment() * $this->price / 100;
        }else{
            $price = $this->price * $jshopConfig->currency_value; 
        }

        if ($jshopConfig->display_price_front_current || $this->price_type != 2) {
            $price = getPriceCalcParamsTax($price, $this->tax_id, $cart->products);
        }

        JPluginHelper::importPlugin('jshoppingcheckout');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterGetPricePaymant', [&$currentObj, &$price]);        
        return $price;
    }
    
    public function getTax()
    {        
        $taxes = JSFactory::getAllTaxes();        
        return $taxes[$this->tax_id];
    }
    
    public function calculateTax()
    {
        $jshopConfig = JSFactory::getConfig();
        $price = $this->getPrice();
        $pricetax = getPriceTaxValue($price, $this->getTax(), $jshopConfig->display_price_front_current);

        return $pricetax;
    }
    
    public function getPriceForTaxes($price)
    {
        $prices = [];

        if ($this->tax_id == -1) {
            $cart = $this->getCart();
            $prodtaxes = getPriceTaxRatioForProducts($cart->products);

            foreach($prodtaxes as $k => $v) {
                $prices[$k] = $price * $v;
            }
        }else{
            $prices[$this->getTax()] = $price;
        }

        return $prices;
    }
    
    public function calculateTaxList($price)
    {
        $cart = $this->getCart();
        $jshopConfig = JSFactory::getConfig();
        $taxes = [];

        if ($this->tax_id == -1) {
            $prodtaxes = getPriceTaxRatioForProducts($cart->products);
            $prices = [];

            foreach($prodtaxes as $k => $v) {
                $prices[] = [
                    'tax' => $k, 
                    'price' => $price * $v
                ];
            }

            $isDisplayPriceFrontCurrentDisabled = $jshopConfig->display_price_front_current == 0;
            foreach($prices as $v) {
                $taxes[$v['tax']] = ($isDisplayPriceFrontCurrentDisabled) ? ($v['price'] * $v['tax'] / (100 + $v['tax'])) : ($v['price'] * $v['tax'] / 100);
            }  
        } else {
            $taxes[$this->getTax()] = $this->calculateTax();
        }

        return $taxes;
    }
    
    /**
    * static
    * get config payment for classname
    */
    public function getConfigsForClassName($classname) 
    {
        return JSFactory::getModel('PaymentsFront')->getParamsByPaymentClass($classname);
    }
    
    /**
    * get config    
    */
    public function getConfigs()
    {
        $parseString = new parseString($this->payment_params);
        $params = $parseString->parseStringToParams();
        return $params;
    }
    
    public function check()
    {
        if (empty($this->payment_class)) {
            $this->setError('Alias Empty');
            return 0;
        }

        return 1;
    }
	
    public function getPaymentSystemData($script = '')
    {
        $jshopConfig = JSFactory::getConfig();
        if (empty($script)) {
            $script = $this->payment_class;

            if (!empty($this->scriptname)) {
                $script = $this->scriptname;
            }

        } else {
            $script = str_replace(['.', '/'], '', $script);
        }
        $data = new stdClass();

        $pathToPaymentFile = $jshopConfig->path . 'payments/' . $script . '/' . $script . '.php';
        $data->paymentSystemError = 0;
        
        if (!file_exists($pathToPaymentFile)) {
            $data->paymentSystemVerySimple = 1;
            $data->paymentSystem = null;
        } else {
            include_once($pathToPaymentFile);
            $data->paymentSystemVerySimple = 0;

            if (!class_exists($script)) {
                $data->paymentSystemError = 1;
                $data->paymentSystem = null;
            } else {
                $data->paymentSystem = new $script();
                $data->paymentSystem->setPmMethod($this);
            }
        }

        return $data;
    }
    
    public function loadPaymentForm($payment_system, $params, $pmconfig)
    {
        return JSFactory::getModel('PaymentsFront')->renderPaymentForm($payment_system, $params, $pmconfig);
    }

}