<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelShippingsBathProductEdit extends JshoppingModelBathProductEdit
{    
    public function resolveActionOfProduct(jshopProduct &$product, $choosedShippings, int $actionId)
    {
        if (array_key_exists($actionId, static::ACTIONS) && !empty($choosedShippings)) {
            $productId = $product->getProductId(false);
            $modelOfProductShippings = JSFactory::getModel('ProductShippings');
            $selectedShippings = array_filter($choosedShippings, function ($value) {
                return !empty($value);
            });
            $selectedShippingsIds = array_keys($selectedShippings);
            
            if (!empty($selectedShippings)) {
                $modelOfShippings = JModelLegacy::getInstance('shippings', 'JshoppingModel');
                switch($actionId) {
                    case static::CODES['ADD']:
                        $modSelectedShippings = ['prMethods' => $selectedShippings];
                        $modelOfProductShippings->deleteByShMethodsAndProductId($productId, $selectedShippingsIds);
                        $modelOfProductShippings->saveShippingByProdId($productId, $modSelectedShippings);
                        break;
                    case static::CODES['DELETE']:
                        $isPublished = false;
                        $modelOfProductShippings->switchPublishByShMethodsAndProductId($productId, $selectedShippingsIds, $isPublished);
                        break;
                    case static::CODES['REPLACE']:
                        $modSelectedShippings = ['spm_published' => $selectedShippings];
                        $modelOfShippings->saveShippings($product->product_id, $modSelectedShippings);
                        break;
                }
            }
        }
    }
}