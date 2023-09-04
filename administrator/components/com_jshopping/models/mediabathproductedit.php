<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelMediaBathProductEdit extends JshoppingModelBathProductEdit
{   
    const ACTIONS = [
        0 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_ADD',
        2 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_REPLACE'
    ]; 

    public function resolveActionOfProduct(jshopProduct &$product, array $data, int $actionId)
    { 
		$db = \JFactory::getDBO();
        if (array_key_exists($actionId, static::ACTIONS) && isset($data['media']) && !empty($data['media'])) {   
            $modelOfProducts = JSFactory::getModel('products'); 
            $modelOfProductMedia = JSFactory::getModel('ProductMedia');

            switch($actionId) {
                case static::CODES['ADD']:
                    $modelOfProducts->setMedia($product->product_id, [], $data);
                    break;
                case static::CODES['REPLACE']:
                    $product->product_thumb_image = $product->product_name_image = $product->product_full_image = $product->image = '';
                    $modelOfProductMedia->deleteByProductId($product->product_id, false);
                    $modelOfProducts->setMedia($product->product_id, [], $data);
                    break;
            }
			$modelOfProductsMedia = JSFactory::getModel('ProductsMediaFront');
        
			$mainMedia = reset($modelOfProductsMedia->getByProductId($product->product_id, true));
            $preview = $mainMedia->media_preview ?: '';

            $product->image = $preview;
        }
    }
}