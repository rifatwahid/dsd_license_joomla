<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopShippingMethodPrice extends JTableAvto 
{
	
	public $total_tax_rate = 0;
    public $tax_source = '';
	
    public function __construct(&$_db)
    {
		$currentObj=$this;
		$dispatcher = \JFactory::getApplication();          
        $dispatcher->triggerEvent('onShippingMethodPriceConstructorBefore', [&$currentObj]);
				
        parent::__construct('#__jshopping_shipping_method_price', 'sh_pr_method_id', $_db);
		
		$currentObj=$this;
		$dispatcher->triggerEvent('onShippingMethodPriceConstructorAfter', [&$currentObj]);
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
    
    public function getPricesWeight($sh_pr_method_id, $id_country, &$cart)
    {
        return JSFactory::getModel('ShippingMethodPrice')->getPricesWeight($sh_pr_method_id, $id_country, $cart);
    }

    public function getPrices(string $orderDir = 'asc') 
    {
        return JSFactory::getModel('ShippingMethodPriceWeightFront')->getPricesByShippingPriceMethodId((int)$this->sh_pr_method_id, $orderDir);
    }

    public function getCountries() 
    {
        return JSFactory::getModel('ShippingMethodPriceCountriesFront')->getAll((int)$this->sh_pr_method_id);
    }

    public function getStates()
    {
        return JSFactory::getModel('ShippingMethodPriceStatesFront')->getAll((int)$this->sh_pr_method_id);
    }

    public function getTax()
    {        
		$currentObj=$this;
		$dispatcher = \JFactory::getApplication();          
        $dispatcher->triggerEvent('onShippingMethodPriceBeforeGetTax', [&$currentObj]);
        $taxes = JSFactory::getAllTaxes();        
        return $taxes[$this->shipping_tax_id];
    }
    
    public function getTaxPackage()
    {
		$currentObj=$this;
		$dispatcher = \JFactory::getApplication();          
        $dispatcher->triggerEvent('onShippingMethodPriceBeforeGetTaxPackage', [&$currentObj]);
        $taxes = JSFactory::getAllTaxes();
        return $taxes[$this->package_tax_id] ?? 0;
    }
    
    public function getGlobalConfigPriceNull($cart)
    {
        $jshopConfig = JSFactory::getConfig();
        return ($cart->getSum() >= ($jshopConfig->summ_null_shipping * $jshopConfig->currency_value) && $jshopConfig->summ_null_shipping > 0);
    }

    public function calculateSum(&$cart)
    {

        if ($this->getGlobalConfigPriceNull($cart)) {
            return 0;
        }
		
		$session = JFactory::getSession();		
		$_prices = $session->get('all_shipping_prices'); 
		
		if($_prices['shipping_price_' . $this->sh_pr_method_id]){
			return $_prices['shipping_price_' . $this->sh_pr_method_id];
		}

        $jshopConfig = JSFactory::getConfig();
        $price = $this->shipping_stand_price;
        $package = $this->package_stand_price;
        $prices = [
            'shipping' => $price,
            'package' => $package
        ];
        // TODO delete
		$shippingMethodId = $this->sh_pr_method_id;
        $extensions = JSFactory::getShippingExtList($shippingMethodId);

        foreach($extensions as $extension) {
            $this->load($extension->id);
            $prices = $extension->exec->getPrices($cart, $this->getParams(), $prices, $extension, $this, $shippingMethodId);
        }

        $this->load($shippingMethodId);
        // TODO delete END

        $prices['shipping'] = getPriceCalcParamsTax($prices['shipping'] * $jshopConfig->currency_value, $this->shipping_tax_id, $cart->products);
        $prices['package'] = getPriceCalcParamsTax($prices['package'] * $jshopConfig->currency_value, $this->package_tax_id, $cart->products);

        return $prices;
    }

    public function calculateTax($sum)
    {
        $jshopConfig = JSFactory::getConfig();
        return getPriceTaxValue($sum, $this->getTax(), $jshopConfig->display_price_front_current);
    }

    public function calculateTaxPackage($sum)
    {
        $jshopConfig = JSFactory::getConfig();
        return getPriceTaxValue($sum, $this->getTaxPackage(), $jshopConfig->display_price_front_current);
    }
    
    public function getShipingPriceForTaxes($price, $cart)
    {
        $prices = [];

        if ($this->shipping_tax_id == -1) {
            $prodtaxes = getPriceTaxRatioForProducts($cart->products);

            foreach($prodtaxes as $k => $v) {
                $prices[$k] = $price * $v;
            }
        }else{
            $prices[$this->getTax()] = $price;
        }

        return $prices;
    }
    
    public function getPackegePriceForTaxes($price, $cart)
    {
        $prices = [];

        if ($this->package_tax_id == -1) {
            $prodtaxes = getPriceTaxRatioForProducts($cart->products);

            foreach($prodtaxes as $k => $v) {
                $prices[$k] = $price * $v;
            }
        }else{
            $prices[$this->getTaxPackage()] = $price;
        }

        return $prices;
    }

    public function calculateShippingTaxList($price, $cart)
    {
        $jshopConfig = JSFactory::getConfig();
        $taxes = [];
		$currentObj=$this;
		
        if ($this->shipping_tax_id == -1) {
            $prodtaxes = getPriceTaxRatioForProducts($cart->products);
            $prices = [];

            foreach($prodtaxes as $k => $v) {
				
				$dispatcher = \JFactory::getApplication();          
				$dispatcher->triggerEvent('onShippingMethodPriceBeforeCalculateShippingTaxList', [&$currentObj, &$prices]);
				if (!isset($currentObj->skip)||($currentObj->skip!=1)){
					$prices[] = [
						'tax' => $k, 
						'price' => $price * $v
					];
				}
            }

            foreach($prices as $v) {
                if ($jshopConfig->display_price_front_current == 0) {
                    $taxes[$v['tax']] = $v['price'] * $v['tax'] / (100 + $v['tax']);
                } else {
                    $taxes[$v['tax']] = $v['price'] * $v['tax'] / 100;
                }
            } 
        } else {
            $taxes[$this->getTax()] = $this->calculateTax($price);
        }

        return $taxes;
    }
    
    public function calculatePackageTaxList($price, $cart)
    {
        $jshopConfig = JSFactory::getConfig();
        $taxes = [];

        if ($this->package_tax_id == -1) {
            $prodtaxes = getPriceTaxRatioForProducts($cart->products);
            $prices = [];

            foreach($prodtaxes as $k => $v) {
                $prices[] = [
                    'tax' => $k, 
                    'price' => $price * $v
                ];
            }

            foreach($prices as $v) {
                if ($jshopConfig->display_price_front_current == 0) {
                    $taxes[$v['tax']] = $v['price'] * $v['tax'] / (100 + $v['tax']);
                } else {
                    $taxes[$v['tax']] = $v['price'] * $v['tax'] / 100;
                }
            } 
        } else {
            $taxes[$this->getTaxPackage()] = $this->calculateTaxPackage($price);
        }

        return $taxes;
    }
    
    public function isCorrectMethodForCountry($id_country) 
    {
        return JSFactory::getModel('ShippingMethodPriceCountriesFront')->isCorrectMethodForCountry($id_country, $this->sh_pr_method_id);
    }
    
    public function setParams($params)
    {
        $this->params = serialize($params);
    }
    
    public function getParams()
    {
        return empty($this->params) ? [] : unserialize($this->params);
    }
	
	public function loadFromAlias($alias)
    {
        $id = JSFactory::getModel('ShippingMethodPrice')->getByAlias($alias)->sh_pr_method_id ?? 0;
        return $this->load($id);
    }
    
    public function getAllShippingMethods($publish = 1) 
    {
        return JSFactory::getModel('ShippingMethodPrice')->getAll($publish);
    }

    public function getAllShippingMethodsCountry($country_id, $payment_id, $publish = 1,$usergroup = '', $state = 0)
    {
        return JSFactory::getModel('ShippingMethodPrice')->getAllShippingMethodsCountry($country_id, $payment_id, $publish, $usergroup, $state);
    }
    
    public function getPayments()
    {
		extract(js_add_trigger(get_defined_vars()));
        return empty($this->payments) ? [] : explode(',', $this->payments);
    }
    
    public function setPayments($payments)
    {
        $payments = (array)$payments;
        foreach($payments as $v) {
            if ($v == 0) {
                $payments = [];
                break;
            }
        }
		extract(js_add_trigger(get_defined_vars()));
        $this->payments = implode(',', $payments);
    }
    
    public function getShippingForm($alias = null)
    {
        if (is_null($alias)) {
            $alias = $this->alias;
        }

        return JSFactory::getModel('ShippingMethod')->getShippingForm($alias);
    }
    
    public function loadShippingForm($shipping_id, $shippinginfo, $params)
    {
        return JSFactory::getModel('ShippingMethod')->renderShippingForm($shipping_id, $shippinginfo, $params);
    }

    public function getShippingPriceId($shipping_id, $country_id, $publish = 1)
    {
        return JSFactory::getModel('ShippingMethod')->getShippingPriceId($shipping_id, $country_id, $publish);
    }

}
