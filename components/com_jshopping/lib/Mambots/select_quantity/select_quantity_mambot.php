<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../FrontMambot.php';

class CheckoutSelectQuantityMambot extends FrontMambot 
{
    
	protected static $instance;
	
    public function onBeforeAddProductToCart($cartObject, &$productId, &$quantity, &$attrId, &$freeAttrs, &$updateQty)
    {
		$product = JTable::getInstance('product', 'jshop');
        $product->load($productId);
        if($product->quantity_select && strlen(trim($product->quantity_select)) > 0){
			$updateQty = 0;
		}
	}
	
    
}
