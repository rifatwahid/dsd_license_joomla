<?php

defined('_JEXEC') or die();

require_once __DIR__ . '/../FrontMambot.php';

class ProductsFreeAttrsDefaultValuesMambot extends FrontMambot
{
    const CONTEXT = 'com_jshopping.plugins.products.free_attributes_default_values';
    protected $product;
    protected static $instance;
    
	public function onBeforeDisplayProductListView(&$view) 
    {
        $app = JFactory::getApplication();
		foreach ($view->rows as $key=>$product){
			$view->product = $product;
			$this->product = $product;
			$productId = ($product->isUseAdditionalFreeAttrs() && !empty($product->getAdditionalProductId())) ? $product->getAdditionalProductId() : $product->product_id;
			$defaultValues = $this->getProductFreeAttrsDefaultValues($productId);

			if ( !empty($defaultValues) ) {
				$deletedFreeAttrsFieldsArr = $this->deleteFixedFields($defaultValues, $view->product);
				
				$app->setUserState(static::CONTEXT . '.deletedFreeAttrs', $deletedFreeAttrsFieldsArr['defaultValuesOfFreeAttrs'] ?? '');
				
				if ( empty($view->product->free_attribute_active) ) {
					$view->product->free_attribute_active = $this->convertArray($deletedFreeAttrsFieldsArr['defaultValuesOfFreeAttrs'] ?? '');
				} else {
					$view->product->free_attribute_active += $this->convertArray($deletedFreeAttrsFieldsArr['defaultValuesOfFreeAttrs'] ?? '');
				}

				if(isset($deletedFreeAttrsFieldsArr['input_fields']) && $deletedFreeAttrsFieldsArr['input_fields'] && count($deletedFreeAttrsFieldsArr['input_fields']) > 0){
					foreach($deletedFreeAttrsFieldsArr['input_fields'] as $val){
						$view->_tmp_product_html_before_buttons .= $val;
					}
				}
			}
        }/**/
    }
	
    public function onBeforeDisplayProductView(&$view) 
    {
        $app = JFactory::getApplication();
        $this->product = $view->product;
        $productId = ($view->product->isUseAdditionalFreeAttrs() && !empty($view->product->getAdditionalProductId())) ? $view->product->getAdditionalProductId() : $view->product->product_id;
        $defaultValues = $this->getProductFreeAttrsDefaultValues($productId);

        if ( !empty($defaultValues) ) {
            $deletedFreeAttrsFieldsArr = $this->deleteFixedFields($defaultValues, $view->product);
           
            $app->setUserState(static::CONTEXT . '.deletedFreeAttrs', $deletedFreeAttrsFieldsArr['defaultValuesOfFreeAttrs'] ?? []);
            
            if ( empty($view->product->free_attribute_active) ) {
                $view->product->free_attribute_active = $this->convertArray($deletedFreeAttrsFieldsArr['defaultValuesOfFreeAttrs'] ?? []);
            } else {
                $view->product->free_attribute_active += $this->convertArray($deletedFreeAttrsFieldsArr['defaultValuesOfFreeAttrs'] ?? []);
            }

            if(isset($deletedFreeAttrsFieldsArr['input_fields']) && count($deletedFreeAttrsFieldsArr['input_fields']) > 0){
                foreach($deletedFreeAttrsFieldsArr['input_fields'] as $val){
                    $view->_tmp_product_html_before_buttons .= $val;
                }
            }
        }
        
    }

    public function onBeforeCalculatePriceProduct(&$quantity, &$enableCurrency, &$enableUserDiscount, &$enableParamsTax, &$product, &$cartProduct)
    {
        $productId = ($product->isUseAdditionalFreeAttrs() && !empty($product->getAdditionalProductId())) ? $product->getAdditionalProductId() : $product->product_id;
        $defaultValues = $this->getProductFreeAttrsDefaultValues($productId);
        $app = JFactory::getApplication();
        $app->setUserState(static::CONTEXT . '.deletedFreeAttrs', null);
        
        if ( !empty($defaultValues) ) {
                                    
            if ( empty($product->free_attribute_active) ) {
                $convertedArr = $this->convertArray($defaultValues);
                
                if ( !empty( $convertedArr ) ) {
                    $product->free_attribute_active = $convertedArr;
                }
                
            } else {
                $fixedConvertedArr = $this->convertArray($this->getFixedElements($defaultValues));
                
                if ( !empty($fixedConvertedArr) ) {
                    $product->free_attribute_active = $fixedConvertedArr + $product->free_attribute_active;
                }
            }
                
        }
                    
    }
    
    protected function getFixedElements(array $array)
    {
        $result = [];
        foreach($array as $key => $element) {
            
            if ($element['is_fixed'] == 1) {
                $result[$key] = $element;
            }
            
        }
        
        return $result;
    }
         
    protected function getProductFreeAttrsDefaultValues($productId)
    {
        $app = JFactory::getApplication();
        JModelLegacy::addIncludePath( JPATH_ROOT  . '/administrator/components/com_jshopping/models' );
        $modelFreeAttrsDefaultValues = JSFactory::getModel('FreeAttrsDefaultValues');
    
        return $modelFreeAttrsDefaultValues->getDataByProductId($productId, 'attr_activated = 1');        
    }


    /**
    *   Searching and delete activated, fixed freeattributes from product array and add default value.
    *
    *   @return array with the deleted product free attrs fields.
    */
    protected function deleteFixedFields($defaultValuesOfFreeAttrs, &$product)
    {
        $deletedFields = [];
        
        foreach ($defaultValuesOfFreeAttrs as $attrId => $arrWithFreeAttrDefaultInfo  ) {

            foreach ($product->freeattributes as $key => $freeAttr) {

                if ( $freeAttr->id == $attrId && 1 == $arrWithFreeAttrDefaultInfo['attr_activated'] ) {

                    if( $arrWithFreeAttrDefaultInfo['default_value'] !== '' ) {
                        $this->addDefaultValueToFreeAttrsField($freeAttr, $arrWithFreeAttrDefaultInfo);
                    }

                    if ( $arrWithFreeAttrDefaultInfo['is_fixed'] ) {
                        $deletedFields['productDefaultValues'][$freeAttr->id] = $product->freeattributes[$key];
                        $deletedFields['defaultValuesOfFreeAttrs'][$attrId] = $defaultValuesOfFreeAttrs[$attrId];
                        $deletedFields['productDefaultValues'][$freeAttr->id]->input_field = str_replace('/>', ' readonly />', $product->freeattributes[$key]->input_field);

                        if ( !isset($defaultValuesOfFreeAttrs[$attrId]['showFreeAttrInput']) || $defaultValuesOfFreeAttrs[$attrId]['showFreeAttrInput'] != 1 ) {
                            unset($product->freeattributes[$key]);
                            $deletedFields['input_fields'][$key] = '<input type="hidden" name="freeattribut['.$freeAttr->id.']" type="hidden" value="'.$defaultValuesOfFreeAttrs[$attrId]['default_value'].'">';//str_replace('/>', ' style="display:none;" />', $product->freeattributes[$key]->input_field);
                            
                        }                 
                    } 
                }
            }
        }  

        return $deletedFields;
    }

    /**
    *   Add default values.
    */
    protected function addDefaultValueToFreeAttrsField(&$productFreeAttrInfo, $infoArr)
    {
        $defaultValue = is_numeric($infoArr['default_value']) ? printFreeAttrQtyByUnit($infoArr['default_value'], $infoArr['attr_id'], $infoArr['product_id']) : $infoArr['default_value'];

        // A switch is used to add support for more types when needed.
        switch($productFreeAttrInfo->type) {
            case '0': 
                // Set default value to input.
                if ( $infoArr['default_value'] !== '' ) {
                    $productFreeAttrInfo->input_field = isset($productFreeAttrInfo->input_field) ? preg_replace('~value=(\'|\").*(\'|\")~U', 'value="' . $defaultValue . '"', $productFreeAttrInfo->input_field) : '';
                }
            break;
        }
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
    protected function convertArray($arrWithFreeAttrs)
    {
        $result = [];

        if (!empty($arrWithFreeAttrs)) {
            foreach($arrWithFreeAttrs as $freeAttrId => $freeAttr) {
                if ( isset($freeAttr['default_value']) ) {
                    $result[$freeAttrId] = $freeAttr['default_value'];
                }
            }
        }

        return $result;
    }  
      
    public function onCalculatePriceProduct($quantity, $enableCurrency, $enableUserDiscount, $enableParamsTax, &$product) 
    {
        $productId = ($product->isUseAdditionalFreeAttrs() && !empty($product->getAdditionalProductId())) ? $product->getAdditionalProductId() : $product->product_id;
        $this->changeFixedFreeAttrsValToDefaulVal($productId, $product->free_attribute_active);
        $this->isProductWidthHeightOutOfQuotaMinMaxVal($product);   
    }

    public function changeFixedFreeAttrsValToDefaulVal($productId, &$freeAttrsValuesFromUser)
    {
        $arrWithProductFreeAttrs = $this->getProductFreeAttrsDefaultValues($productId);

        if ( !empty($arrWithProductFreeAttrs) ) {
            foreach($arrWithProductFreeAttrs as $key => $productFreeAttr) {
                $freeAttrId = $productFreeAttr['attr_id'];
                $isFixedFreeAttr = (bool)$productFreeAttr['is_fixed'];
                $defaultFreeAttrValue = $productFreeAttr['default_value'];

                if ( $isFixedFreeAttr && isset( $freeAttrsValuesFromUser[$freeAttrId] ) ) {
                    $freeAttrsValuesFromUser[$freeAttrId] = $defaultFreeAttrValue;
                }
            }
        }
    }

    public function isProductWidthHeightOutOfQuotaMinMaxVal($product) 
    {
		$_freeAttribut = JSFactory::getTable('freeAttribut', 'jshop');
        if (empty($product->freeattributes)){
			$product->getListFreeAttributes();
			$back_value = $back_value ?? [];
		}
        $arrWithErrorsMsgs = [];
        $productId = ($product->isUseAdditionalFreeAttrs() && !empty($product->getAdditionalProductId())) ? $product->getAdditionalProductId() : $product->product_id;
        $arrWithProductFreeAttrs = $this->getProductFreeAttrsDefaultValues($productId);
        $freeattr = JFactory::getApplication()->input->getVar('freeattrdef');

        $freeAttrsValFromUser = !empty($freeattr) ? $freeattr : $product->free_attribute_active;

        if (!empty($arrWithProductFreeAttrs)) {
            foreach($arrWithProductFreeAttrs as $key => $productFreeAttr) {

                $loopFreeAttrValFromUser = $freeAttrsValFromUser[$key]; 
                $defaultMinValueOfFreeAttr = $productFreeAttr['min_value'];
                $defaultMaxValueOfFreeAttr = $productFreeAttr['max_value'];

                $isUserFreeAttrValLessQuota = !empty($defaultMinValueOfFreeAttr) && ($defaultMinValueOfFreeAttr > $loopFreeAttrValFromUser);
                $isUserFreeAttrValMoreQuota = !empty($defaultMaxValueOfFreeAttr) && ($defaultMaxValueOfFreeAttr < $loopFreeAttrValFromUser);

				$fname = $_freeAttribut->getName($productFreeAttr['attr_id']);
				
                if ( $isUserFreeAttrValLessQuota ) {
                    $arrWithErrorsMsgs['1'] = '<b>'.$fname.'</b></br>'.JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA_MIN');
                    $freeAttrsValFromUser[$key] = $defaultMinValueOfFreeAttr;
                }

                if ( $isUserFreeAttrValMoreQuota ) {
                    $arrWithErrorsMsgs['2'] = '<b>'.$fname.'</b></br>'.JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA_MAX');
                    $freeAttrsValFromUser[$key] = $defaultMaxValueOfFreeAttr;
                }
                    
            }       
        }
                
        if (!empty($arrWithErrorsMsgs)) {
            $arrWithErrorsMsgs = array_values($arrWithErrorsMsgs);
            $product->old_free_attribute_active = $freeAttrsValFromUser;
            $product->error_message =  !empty($product->error_message) ? array_merge($product->error_message, $arrWithErrorsMsgs) : $arrWithErrorsMsgs;

            return true;
        }

        return false;
    }


    public function onBeforeDisplayAjaxAttrib(&$rows, &$product) 
    {
        if (!empty($product->error_message)) {
            $system_message = '<ul>';

            foreach ($product->error_message as $key => $value) {
                $system_message .="<li>" . $value . "</li>";
            }

            $system_message .='</ul>';
        } else {
            $system_message = '';
        }
        
        $old_free_attribute_active = json_encode($product->old_free_attribute_active ?? []);

        if (empty($old_free_attribute_active)) {
            $old_free_attribute_active = '';
        }

        $rows[] = '"jshop_facp_system_message":"' . $system_message . '"';
        $rows[] = '"jshop_facp_old_free_attribute_active":' . $old_free_attribute_active . '';
    }

    public function onBeforeLoadProduct() 
    {
    }

}
