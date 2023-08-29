<?php

defined('_JEXEC') or die();

require_once __DIR__ . '/../BackMambot.php';

class AdminFreeAttrsDefaultValuesMambot extends BackMambot
{
    protected static $instance;

    public function getPreparedInputsAndCheckboxesTemplateForFreeAttrsRows($listOfFreeAttrs, $productId = null)
    {
        $freeAttrsDefaultValuesModel = JSFactory::getModel('FreeAttrsDefaultValues');
        $savedFreeAttr = $productId ? $freeAttrsDefaultValuesModel->getDataByProductId($productId): [];

        $result = [];
        foreach($listOfFreeAttrs as $key => $objWithAttr ) {
            $checkedStatusForFixedValue = !empty($savedFreeAttr[$objWithAttr->id]['is_fixed']) ? 'checked' : '';
            $checkedStatusForShowfreeAttrDefInputs = !empty($savedFreeAttr[$objWithAttr->id]['showFreeAttrInput']) ? 'checked' : '';
            $inputDefaultValue = $savedFreeAttr[$objWithAttr->id]['default_value'] ?? '';
            $inputMinValue = $savedFreeAttr[$objWithAttr->id]['min_value'] ?? '';
            $inputMaxValue = $savedFreeAttr[$objWithAttr->id]['max_value'] ?? '';

            $result[$objWithAttr->id] = renderTemplate([
                JPATH_ROOT . '/administrator/components/com_jshopping/views/free_attrs_default_values'
            ], 'add_inputs_for_rows', [
                'inputDefaultValue' => $inputDefaultValue,
                'inputMinValue' => $inputMinValue,
                'inputMaxValue' => $inputMaxValue,
                'objWithAttr' => $objWithAttr,
                'checkedStatusForFixedValue' => $checkedStatusForFixedValue,
                'checkedStatusForShowfreeAttrDefInputs' => $checkedStatusForShowfreeAttrDefInputs
            ]);

        }

        return $result;        
    }

    /**
    *   $post['freeAttrDefVal']['1'] <= id of free attr.
    *   $post['freeAttrDefVal']['1']['defaultVal'] <= default value for free attr.
    *   $post['freeAttrDefVal']['1']['isFixedVal'] <= is fixed value or not. if 1 - is set.
    *   $post['freeAttrDefVal']['1']['showFreeAttrInput'] <= show or not input in front-end
    */
    public function onBeforeDisplaySaveProduct(&$post, &$product) 
    {
        $app = JFactory::getApplication();
        $app->setUserState('com_jshopping.plugins.free_attributes_default_values.post', $post);
    }

    public function onAfterSaveProduct(&$product)
    {
        $app = JFactory::getApplication();
        $freeAttrsDefaultValuesModel = JSFactory::getModel('FreeAttrsDefaultValues');
        $post = $app->getUserState('com_jshopping.plugins.free_attributes_default_values.post');  
        $post['product_id'] = $product->product_id;
        
        if ( !empty($post['product_id']) && !empty($post['freeAttrDefVal']) ) {

            $appropriateData = $freeAttrsDefaultValuesModel->definingTheAppropriateData($post);
            
            $freeAttrDefValuesModel = JSFactory::getModel('FreeAttrsDefaultValues');
            $freeAttrDefValuesModel->setFreeAttrDataToTable($post['product_id'], $appropriateData);             
        }


        $app->setUserState('com_jshopping.plugins.free_attributes_default_values.post', null);
    }

    /**
    *   When copying a product - we copy and default values for free attributes
    *
    *   @param $cid - array with products ids to copy
    *   @param $key - key array in foreach
    *   @param $value - the id product you want to copy
    *   @param $product - copied product object
    */    
    public function onCopyProductEach(&$cid, &$key, &$value, &$product)
    {
        $freeAttrsDefaultValuesModel = JSFactory::getModel('FreeAttrsDefaultValues');
        $defaultFreeAttrsToCopy = $freeAttrsDefaultValuesModel->getProductDefaultFreeAttributes($value);
        $freeAttrsDefaultValuesModel->setDefaultFreeAttributesForProduct($product->product_id, $defaultFreeAttrsToCopy);
    }  

    /**
    *   After removing the product - delete the data from `#__jshopping_free_attr_default_values`
    *   @param array $cid - the products ids of which user deleted
    */
    public function onAfterRemoveProduct(&$cid)
    {
        $freeAttrsDefaultValuesModel = JSFactory::getModel('FreeAttrsDefaultValues');

        foreach ($cid as $key => $deletedProductId) {
            $freeAttrsDefaultValuesModel->deleteDataByProductId($deletedProductId);
        }
        
    }      

}