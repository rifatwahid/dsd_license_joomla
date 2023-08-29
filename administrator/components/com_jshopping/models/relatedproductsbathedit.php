<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelRelatedProductsBathEdit extends JshoppingModelBathProductEdit
{  
    const ACTIONS = [
        0 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_ADD',
        2 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_REPLACE'
    ];

    public function resolveActionOfProduct(jshopProduct &$product, array $data, int $actionId)
    {
        if (array_key_exists($actionId, static::ACTIONS) && isset($data['related_products']) && !empty($data['related_products'])) {   
            $modelOfProducts = JSFactory::getModel('products'); 

            switch($actionId) {
                case static::CODES['ADD']:
                    $modelOfProducts->addRelatedProducts($product->product_id, $data['related_products']);
                    break;
                case static::CODES['REPLACE']:
                    $data['edit'] = true;
                    $modelOfProducts->saveRelationProducts($product, $product->product_id, $data);
                    break;
            }
        }
    }
}

