<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class sm_standart_weight extends shippingextRoot
{
    
    public $version = 2;
    
    public function showShippingPriceForm($params, &$shipping_ext_row, &$template)
    {        
        include __DIR__ . '/shippingpriceform.php';
    }
    
    public function showConfigForm($config, &$shipping_ext, &$template)
    {
        include __DIR__ . '/configform.php';
    }
    
    public function getPrices($cart, $params, $prices, &$shipping_ext_row, &$shipping_method_price, $sh_pr_method_id = 0)
    {		
		JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_jshopping/models');
        $conditions = JSFactory::getModel('conditionsadmin');
		$modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
		$productsIds = array_reduce($cart->products, function($carry, $productCart){
            $carry[] = $productCart['product_id'];

            return $carry;
        });
		$shipping_method_price->shipping_products = $modelOfProductsShipping->getProductsByShippingId($shipping_method_price->sh_pr_method_id, $productsIds);
		
		$options =  $conditions->getOptions();
		$width_id = 0;
		$height_id = 0;
		$depth_id = 0;
		if(!empty($options)){
			$width_id = $options->width_id;
			$height_id = $options->height_id;
			$depth_id = $options->depth_id;
		}
		
		if($sh_pr_method_id){
        	$shipping_method_price->load($sh_pr_method_id);
		}
		//print_r($cart);die;
        $params = array($width_id, $height_id, $depth_id);
		$sides = $cart->getMinMedianMaxFreeattrValProducts($params);
		
        $weightSum = $cart->getWeightProducts();
		$weight = $weightSum;
		
		$width = $cart->getFreeattrValProducts($width_id, 1);
		$height = $cart->getFreeattrValProducts($height_id, 2);		
		$depth = $cart->getSumValProducts($depth_id);
		$price = $cart->price_product;		
		$max_side = $sides['max'];
		$median_side = $sides['median'];
		$min_side = $sides['min'];	
		$max = $sides['max'];
		$median = $sides['median'];
		$min = $sides['min'];			
			
        $shippingPrices = $shipping_method_price->getPrices('desc');
        
		foreach($shippingPrices as $shippingPrice) { 
			if($shippingPrice->formula){
				if($shippingPrice->rule_apply == 1){ 
					$sides = $cart->getMinMedianMaxFreeattrValProducts($params, $shipping_method_price->shipping_products);
	
					$weightSum = $cart->getWeightProducts($shipping_method_price->shipping_products);
					$weight = $weightSum;
					$width = $cart->getFreeattrValProducts($width_id, 1, $shipping_method_price->shipping_products);
					$height = $cart->getFreeattrValProducts($height_id, 2, $shipping_method_price->shipping_products);
					$depth = $cart->getSumValProducts($depth_id, $shipping_method_price->shipping_products);
					//change $shippings[$key]->shipping_products
					$price = $cart->getPriceProds($shipping_method_price->shipping_products); //price_product;
					
					$max_side = $sides['max'];
					$median_side = $sides['median'];
					$min_side = $sides['min'];	
					$max = $sides['max'];
					$median = $sides['median'];
					$min = $sides['min'];	
				}
				eval('$formula = ' . $shippingPrice->formula . ';');
					if ($formula) {
						$prices['shipping'] = $shippingPrice->shipping_price;
						$prices['package'] = $shippingPrice->shipping_package_price;
						break;
					}
				}
			}

        return $prices;
    }
}
