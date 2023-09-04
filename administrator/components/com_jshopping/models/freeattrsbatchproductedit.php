<?php 

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelFreeAttrsBatchProductEdit extends JshoppingModelBathProductEdit
{
    public function resolveActionOfProduct(jshopProduct &$product, $post, int $actionId)
    {
        $choosedFreeAttrs = $post['freeattribut'];

        if (array_key_exists($actionId, static::ACTIONS) && !empty($choosedFreeAttrs)) {
            $modelOfFreeAttr = JSFactory::getModel('FreeAttribut');
            $freeAttrsIds = array_keys($choosedFreeAttrs);

            switch($actionId) {
                case static::CODES['ADD']:
                    $this->addFreeAttrs($product->product_id, $freeAttrsIds, $post);
                    break;
                case static::CODES['DELETE']:
                    $modelOfFreeAttr->deleteProdFreeAttrsWithDefaultValuesByFreeAttrsAndProdId($product->product_id, $freeAttrsIds);
                    break;
                case static::CODES['REPLACE']:
                    $modelOfFreeAttr->deleteAllFreeAttrsWithDefaultValuesByProdId($product->product_id);
                    $this->addFreeAttrs($product->product_id, $freeAttrsIds, $post);
                    break;
            }
        }
    }

    protected function addFreeAttrs(int $productId, array $attrsId, array $post)
    {
        $modelOfProducts = JSFactory::getModel('products');
        $freeAttrDefValuesModel = JSFactory::getModel('FreeAttrsDefaultValues');
        $appropriateData = $freeAttrDefValuesModel->definingTheAppropriateData($post);

        $modelOfProducts->onlySaveFreeAttrs($productId, array_flip($attrsId));
        $freeAttrDefValuesModel->setFreeAttrDataToTableWithoutDelete($productId, $appropriateData); 
    }
}