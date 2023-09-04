<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../FrontMambot.php';

class CheckoutMinMaxQuantityMambot extends FrontMambot 
{
    
	protected static $instance;
	
    public function onBeforeAddProductToCart($cartObject, &$productId, &$quantity, &$attrId, &$freeAttrs, &$updateQty)
    {
		$lang = JSFactory::getLang();
		$name = $lang->get('name');
		$product = JTable::getInstance('product', 'jshop');
        $product->load($productId);
        $product->setAttributeActive($attrId);
		$predefinedMaxCountOfProduct = $product->max_count_product;
		$predefinedMinCountOfProduct = $product->min_count_product;
        $serializedAttrs = serialize($attrId);
        $serializedfreeAttrs = serialize($freeAttrs);
		
		$cart = JModelLegacy::getInstance('cart', 'jshop');
		$cart->load($cartObject->type_cart);
		$productInCart = 0;
		foreach($cart->products as $value) {
            if ($value['product_id'] == $productId && $value['attributes'] == $serializedAttrs && $value['freeattributes'] == $serializedfreeAttrs) {
                $productInCart += $value['quantity'];
            }
		}
        
        if ($productInCart == 0 && $quantity < $predefinedMinCountOfProduct) {
            $quantity = $predefinedMinCountOfProduct;
            \Joomla\CMS\Factory::getApplication()->enqueueMessage(sprintf(JText::_('COM_SMARTSHOP_ERROR_MIN_COUNT_ORDER_ONE_PRODUCT'), $predefinedMinCountOfProduct), 'notice');
        }

		$saveQty = $quantity + $productInCart;

		if ($predefinedMaxCountOfProduct == 0 && $predefinedMinCountOfProduct == 0) {
			return;
        }

		if ($predefinedMaxCountOfProduct >= $saveQty && $saveQty >= $predefinedMinCountOfProduct) {
			return;            
        }
        
        if ($predefinedMaxCountOfProduct == 0 && $saveQty >= $predefinedMinCountOfProduct) {
			return;
        }
        
        $isExceededQuotaOfMaxProdCount = (!empty($predefinedMaxCountOfProduct) && $saveQty > $predefinedMaxCountOfProduct);
        $isExceededQuotaOfMinProdCount = (!empty($predefinedMinCountOfProduct) && $saveQty < $predefinedMinCountOfProduct);

		if ($isExceededQuotaOfMaxProdCount) {
			$cartObject->exceededQuotaOfProdCount[$productId]['max']['productId'] = $productId;
            $cartObject->exceededQuotaOfProdCount[$productId]['max']['predefined'] = $predefinedMaxCountOfProduct;
			if($predefinedMaxCountOfProduct - $productInCart > 0){
				$quantity = $predefinedMaxCountOfProduct - $productInCart;
				$cartObject->error_max = sprintf($product->$name . ' - ' . JText::_('COM_SMARTSHOP_ERROR_MAX_COUNT_ORDER_ONE_PRODUCT'), $predefinedMaxCountOfProduct);//exit;rorMessage = 'dddddd';//sprintf($product->$name . ' - ' . JText::_('COM_SMARTSHOP_ERROR_MAX_COUNT_ORDER_ONE_PRODUCT'), $predefinedMaxCountOfProduct);//exit;
				$cartObject->add_quantity = $quantity;
				return;
			}else{
				$quantity = 0;
                return \Joomla\CMS\Factory::getApplication()->enqueueMessage(sprintf($product->$name . ' - ' . JText::_('COM_SMARTSHOP_ERROR_MAX_COUNT_ORDER_ONE_PRODUCT'), $predefinedMaxCountOfProduct), 'notice');
			}
	    }
        
        if ($isExceededQuotaOfMinProdCount) {
            $cartObject->exceededQuotaOfProdCount[$productId]['min']['productId'] = $productId;
            $cartObject->exceededQuotaOfProdCount[$productId]['min']['predefined'] = $predefinedMaxCountOfProduct;
            $quantity = $predefinedMinCountOfProduct;
            return \Joomla\CMS\Factory::getApplication()->enqueueMessage(sprintf($product->$name . ' - ' . JText::_('COM_SMARTSHOP_ERROR_MIN_COUNT_ORDER_ONE_PRODUCT'), $predefinedMinCountOfProduct), 'notice');
        }
	}
	
    public function onBeforeRefreshProductInCart(&$quantity)
    {
		$cart = JModelLegacy::getInstance('cart', 'jshop');
		$product = JTable::getInstance('product', 'jshop');
        $cart->load();
		
        $redirect = 0;
        
		foreach($cart->products as $key => $data) {
            if (!isset($data['adtprodnum'])) {
                $_quantity = $quantity[$key];
                $product->load($data['product_id']);
                $max_count_product = $product->max_count_product;
                $min_count_product = $product->min_count_product;
                
                if ($max_count_product == 0 && $min_count_product == 0) {
                    continue;
                } elseif($max_count_product >= $_quantity && $_quantity >= $min_count_product) {
                    continue;
                } elseif($max_count_product == 0 && $_quantity >= $min_count_product) {
                    continue;
                }
                
                if ($max_count_product && $_quantity > $max_count_product) {
                    $quantity[$key] = $max_count_product;
                    \Joomla\CMS\Factory::getApplication()->enqueueMessage(sprintf($data['product_name'] . ' - ' . JText::_('COM_SMARTSHOP_ERROR_MAX_COUNT_ORDER_ONE_PRODUCT'), $max_count_product), 'notice');
                }
                
                if ($min_count_product && $_quantity < $min_count_product) {
                    $quantity[$key] = $min_count_product;
                    \Joomla\CMS\Factory::getApplication()->enqueueMessage(sprintf($data['product_name'] . ' - ' . JText::_('COM_SMARTSHOP_ERROR_MIN_COUNT_ORDER_ONE_PRODUCT'), $min_count_product), 'notice');
                }
            }
		}
		
	}
}
