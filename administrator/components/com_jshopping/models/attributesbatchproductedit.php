<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelAttributesBatchProductEdit extends JshoppingModelBathProductEdit
{   
    const ACTIONS = [
        0 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_ADD',
        2 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_REPLACE'
    ];

    public function resolveActionOfProduct(jshopProduct &$product, $data, int $actionId)
    {
        if (array_key_exists($actionId, static::ACTIONS)) {
            $modelOfProducts = JSFactory::getModel('products'); 
            $modelOfAttributeValue = JSFactory::getModel('AttributValue');

            switch($actionId) {
                case static::CODES['ADD']:
                    $modelOfProducts->saveAttributes($product, $product->product_id, $data, true);
                    $modelOfAttributeValue->writeAttrsToSortTable($product->product_id, $data['attrib_id']);
                    break;
                case static::CODES['REPLACE']:
                    $modelOfProducts->saveAttributes($product, $product->product_id, $data);
                    $modelOfAttributeValue->deleteValAttrsForSortTable($product->product_id);
                    $modelOfAttributeValue->writeAttrsToSortTable($product->product_id, $data['attrib_id']);
                    break;
            }
        }
    }
}