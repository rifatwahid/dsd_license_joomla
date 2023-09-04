<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../FrontMambot.php';

class CheckoutOfferAndOrder extends FrontMambot 
{
    protected static $instance;

    public function onBeforeSaveNewProductToCart(&$cart, &$temp_product, &$product) 
    {
        $session = JFactory::getSession();
        $product_offer_and_order_price = $session->get('product_offer_and_order_price');
		$product_offer_image = $session->get("product_offer_and_order_image");
        $product_id_for_order = $session->get("product_offer_and_order_id_for_order");
		
        if (isset($product_offer_and_order_price) && $product_offer_and_order_price !== '') {
            if ((!isset($temp_product['price']))||($temp_product['price']==0)) $temp_product['price'] = $product_offer_and_order_price;
            if ((!isset($temp_product['oneTimePrice']))||($temp_product['oneTimePrice']==0)) $temp_product['oneTimePrice'] = $product_offer_and_order_price;
            if ((!isset($temp_product['one_time_cost']))||($temp_product['one_time_cost']==0)) $temp_product['one_time_cost'] = $product_offer_and_order_price * $temp_product['quantity'];
            if ((!isset($temp_product['total_price']))||($temp_product['total_price']==0)) $temp_product['total_price'] = $product_offer_and_order_price * $temp_product['quantity'];
            $temp_product['product_offer_and_order_price'] = $product_offer_and_order_price;
			if($product_offer_image){
				$temp_product['thumb_image'] = $product_offer_image;
			}
			if($product_id_for_order){
				$temp_product['product_id_for_order'] = $product_id_for_order;
			}
        }
    }

    public function onAfterCartLoad(&$cart) 
    {
        $fixprice = 0;
        
        foreach ($cart->products as $k => $v) {
            if (isset($v['product_offer_and_order_price'])) {
                $cart->products[$k]['price'] = $v['product_offer_and_order_price'];
                $fixprice = 1;
            }
        }

        if ($fixprice) {
            $cart->saveToSession();
            $cart->loadPriceAndCountProducts();
        }
    }

}
