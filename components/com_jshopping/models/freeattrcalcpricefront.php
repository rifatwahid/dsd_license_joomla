<?php 

defined('_JEXEC') or die();

class JshoppingModelFreeAttrCalcPriceFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_free_attribute_calcule_price';

    public function isProductHasAnyFreeAttributesForCalculation(jshopProduct $product) 
    {
        $result = false;
        $clonedProduct = clone $product;
        $clonedProduct->getListFreeAttributes();
		$back_value = $back_value ?? [];

        if (empty($clonedProduct->freeattributes)) {
			$clonedProduct->getListFreeAttributes();
		}
		
        if (empty($clonedProduct->freeattributes)) {
            return $result;
        }

        JModelLegacy::addIncludePath(JPATH_ROOT  . '/administrator/components/com_jshopping/models');
        $modelFreeAttrCalcPrice = JSFactory::getModel('freeattrcalcprice');
        $freeAttrCalcPriceParams = $modelFreeAttrCalcPrice->getParameters();
        $freeAttrsId = $freeAttrCalcPriceParams->freeAttrsParamsIds;

        foreach ($clonedProduct->freeattributes as $freeattribut) {
            if (in_array($freeattribut->id, $freeAttrsId)) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    public function isIssetAtLeastOneIdInFormulaCalcBasicParams(array $ids): bool
	{
        JModelLegacy::addIncludePath(JPATH_ROOT  . '/administrator/components/com_jshopping/models');
        $modelFreeAttrCalcPrice = JSFactory::getModel('freeattrcalcprice');
		$formulaCalcBasicParams = $modelFreeAttrCalcPrice->getParameters();
        $freeAttrsParamsIds = $formulaCalcBasicParams->freeAttrsParamsIds;
        
		$isIssetIdsInFormulaCalcBasicParams = false;
        foreach($ids as $id) {
            if (in_array($id, $freeAttrsParamsIds)) {
                $isIssetIdsInFormulaCalcBasicParams = true;
                break;
            }
        }
            
		return $isIssetIdsInFormulaCalcBasicParams;
    }
    
    public function isFreeAttrsValsAgreeWithBasicParamsLimits(array $freeAttrsVals): bool
    {
        $error = true;
        $modelFreeAttrCalcPrice = JSFactory::getModel('freeattrcalcprice');
        $freeAttrCalcPriceBasicParams = $modelFreeAttrCalcPrice->getParameters();

        if (!empty($freeAttrCalcPriceBasicParams->freeAttrsParamsIds)) {
            foreach($freeAttrCalcPriceBasicParams->freeAttrsParamsIds as $freeAttrId) {
                if (array_key_exists($freeAttrId, $freeAttrsVals)) {
    
                    $freeAttrValFromUser = $freeAttrsVals[$freeAttrId];
                    $minValFromBasicParam = $freeAttrCalcPriceBasicParams->freeAttrsParamsMinVals[$freeAttrId];
                    $maxValFromBasicParam = $freeAttrCalcPriceBasicParams->freeAttrsParamsMaxVals[$freeAttrId];
                    $stepValFromBasicParam = $freeAttrCalcPriceBasicParams->freeAttrsParamsStepVals[$freeAttrId];
    
                    if (($minValFromBasicParam && $freeAttrValFromUser < $minValFromBasicParam) || ($maxValFromBasicParam && $freeAttrValFromUser > $maxValFromBasicParam)) {
                        return false;
                    }
                        
                    if ($stepValFromBasicParam) {
                        $stepResult = $stepValFromBasicParam > 0 ? $freeAttrValFromUser / $stepValFromBasicParam : $freeAttrValFromUser;
    
                        if (!$freeAttrValFromUser || (int)$stepResult != $stepResult) {
                            return false;
                        }
                            
                    }
                }
            }
        }
        
        return $error;
    }

    public function addErrorsAndReplaceFreeAttrsValsIfOutMinMaxQuota(jshopProduct &$product): void
    {
        $productDefFreeAttrVal = [];
        $product->getListFreeAttributes();
        JModelLegacy::addIncludePath( JPATH_ROOT  . '/administrator/components/com_jshopping/models');
        $paramsOfFreeCalcPrice = JSFactory::getModel('freeattrcalcprice')->getParameters();

       	$addon = JTable::getInstance('addon', 'jshop');
        $addon->loadAlias('free_attributes_default_values');
		
		if (!empty($addon) && $addon->id > 0) {
			$productDefFreeAttrVal = JSFactory::getModel('FreeAttrsDefaultValues')->getDataByProductId($product->product_id, 'attr_activated = 1');
        }

        $error_message = [];
        if (!empty($paramsOfFreeCalcPrice->freeAttrsParamsIds)) {
            foreach($paramsOfFreeCalcPrice->freeAttrsParamsIds as $paramName => $freeAttrId) {

                if ($freeAttrId == 0) {
                    continue;
                }

                $names = [
                    'width_id' => 'WIDTH',
                    'height_id' => 'HEIGHT',
                    'depth_id' => 'DEPTH',
                ];
                $name = $names[$paramName] ?: 'VARIABLE';

                $freeAttrValueFromUser = $product->free_attribute_active[$freeAttrId] ?? null;

                if (isset($freeAttrValueFromUser)) {
                    $defaultMinVal = $productDefFreeAttrVal[$freeAttrId]['min_value'] ?? $paramsOfFreeCalcPrice->freeAttrsParamsMinVals[$freeAttrId] ?? null;
                    $defaultMaxVal = $productDefFreeAttrVal[$freeAttrId]['max_value'] ?? $paramsOfFreeCalcPrice->freeAttrsParamsMaxVals[$freeAttrId] ?? null;
                    $defaultStep = $paramsOfFreeCalcPrice->freeAttrsParamsStepVals[$freeAttrId] ?? null;
                    
                    if (!empty($defaultMinVal) && ($defaultMinVal > $freeAttrValueFromUser)) {
                       // array_push($error_message, JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA') . ' ' . JText::_('COM_SMARTSHOP_FACP_' . $name . '_MINIMUM'));
                        array_push($error_message, '<b>'.$name.'</b></br>'.JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA_MIN'));
                        $product->free_attribute_active[$freeAttrId] = $defaultMinVal;
                    }
        
                    if (!empty($defaultMaxVal) && ($defaultMaxVal < $freeAttrValueFromUser)) {
                       // array_push($error_message, JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA') . ' ' . JText::_('COM_SMARTSHOP_FACP_' . $name . '_MAXIMUM'));
                        array_push($error_message, '<b>'.$name.'</b></br>'.JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA_MAX'));
						$product->free_attribute_active[$freeAttrId] = $defaultMaxVal;
                    }

                    if (!empty($defaultStep) && (($freeAttrValueFromUser % $defaultStep) != 0)) {
                        //array_push($error_message, JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA') . ' ' . JText::_('COM_SMARTSHOP_FACP_' . $name . '_STEP'));
						array_push($error_message, '<b>'.$name.'</b></br>'.JText::_('COM_SMARTSHOP_ERROR_ENTER_DATA_STEP'));
                    }
                }

            }
        }

        $product->error_message = '';
        if (!empty($error_message)) {
            $product->old_free_attribute_active = $product->free_attribute_active;
            $product->error_message = $error_message;
        }
    }

    public function getParams() 
    {
        $params = $this->select(['params'], [], '', false);

        if (!empty($params->params)) {
            return unserialize($params->params);
        }
    }  

    public function getDefaultValues($freeattributes): array
    {
        $defaultValues = [];
        
        JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_jshopping/models');
        $modelFreeAtrrCalcPriceParameters = JSFactory::getModel('FreeAttrCalcPrice')->getParameters();
        $freeAttrCalcPriceBasicDefaultParams = $modelFreeAtrrCalcPriceParameters->freeAttrsParamsDefVals ?: [];

        if (!empty($modelFreeAtrrCalcPriceParameters->freeAttrsParamsIds)) {
            $ids = $modelFreeAtrrCalcPriceParameters->freeAttrsParamsIds ?: [];

            $defaultValues = array_reduce($freeattributes, function ($carry, $freeAttr) use ($freeAttrCalcPriceBasicDefaultParams, $ids) {
                if (in_array($freeAttr->id, $ids) && isset($freeAttrCalcPriceBasicDefaultParams[$freeAttr->id])) {
                    $carry[$freeAttr->id] = $freeAttrCalcPriceBasicDefaultParams[$freeAttr->id];
                }

                return $carry;
            });
        }
        
        return $defaultValues ?: [];
    }
}
