<?php
defined('_JEXEC') or die();

require_once __DIR__ . '/../FrontMambot.php';
require_once __DIR__ . '/products_free_attrs_default_values_mambot.php';

class CheckoutFreeAttrsDefaultValuesMambot extends FrontMambot
{
    protected static $instance;

    /**
    *   Parse from `UserState` deleted free attrs and set them in `$freeattributes` array.
    */
    public function onBeforeAddProductToCart(&$cart, &$product_id, &$quantity, &$attrsIds, &$freeattributes, &$updateqty, &$errors, &$displayErrorMessage, &$additional_fields, &$usetriggers)
    {
        $productId = getAdditionalProdIdIfAdditionalFreeAttrsActivated($product_id, $attrsIds) ?: $product_id;
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->changeFixedFreeAttrsValToDefaulVal($productId, $freeattributes);
        $arrWithDeletedFreeAttrs = $this->getDefaultValuesFromAddon($productId);

        if ( !empty($arrWithDeletedFreeAttrs) ) {
            $freeattributes = $this->convertArray($arrWithDeletedFreeAttrs, $freeattributes) + $freeattributes;
        }
    }
    
    protected function getDefaultValuesFromAddon($productId)
    {
        JModelLegacy::addIncludePath( JPATH_ROOT  . '/administrator/components/com_jshopping/models' );
        $modelFreeAttrsDefaultValues = JSFactory::getModel('FreeAttrsDefaultValues');
    
        return $modelFreeAttrsDefaultValues->getDataByProductId($productId, 'attr_activated = 1');        
    }

    /**
    *   Converts array to a format:
    *   [
    *       3 => 'defaultValue'. Where `3` - is free attr id.
    *       4 => 'defaultValue'.
    *   ];
    *
    *   @return array 
    */
    protected function convertArray($arrWithFreeAttrs, $freeattributes)
    {
        $result = [];

        foreach($arrWithFreeAttrs as $freeAttrId => $freeAttr) {
            if ( isset($freeAttr['default_value']) && !$freeattributes[$freeAttrId]) {
                $result[$freeAttrId] = $freeAttr['default_value'];
            }
        }

        return $result;
    }

}
