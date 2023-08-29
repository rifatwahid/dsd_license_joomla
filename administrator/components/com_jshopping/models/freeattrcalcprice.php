<?php

class JshoppingModelFreeAttrCalcPrice extends JModelLegacy
{
	const ADDON_NAME = 'addon_free_attribute_calcule_price';

	/**
	*	Get addon params.
	*/
	public function getAddonParameters()
	{
		$query = "select `params` from #__jshopping_free_attribute_calcule_price ";
        $this->_db->setQuery($query);
        $params = $this->_db->loadResult();
		 if ($params!=""){
            $res = unserialize($params);
			if(isset($res->id) && $res->id){
				return unserialize($res->params);
			}else{
				return $res;
				
			}
        }else{
            return array();
        }
       
	}

	/**
	*	Get addon parameter from `variables` element array without width_id, height_id, depth_id.
	*/
	public function getParametersVariables()
	{
		static $paramsCache = null;

		if (!empty($paramsCache)) {
			return $paramsCache;
		}

		$addonParams = $this->getAddonParameters()['variables'];

		if (!empty($addonParams)) {
			unset($addonParams['width_id']);
			unset($addonParams['height_id']);
			unset($addonParams['depth_id']);
		}

		$paramsCache = $addonParams;
		return $addonParams;
	}
	
	public function saveParams($params)
	{
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_free_attribute_calcule_price` WHERE 1";
        $db->setQuery($query);
		$ps = $db->loadObject();
		if(!empty($ps)){
			$query = "UPDATE `#__jshopping_free_attribute_calcule_price` SET `params` = ".$db->quote(serialize($params));
			$db->setQuery($query);
			$db->execute();
		}else{
			$query = "INSERT INTO `#__jshopping_free_attribute_calcule_price` SET `name`='free_attribute', `params` = ".$db->quote(serialize($params));
			$db->setQuery($query);
			$db->execute();			
		}
       
	}

	public function getParameters()
	{
		static $paramsCache = null;

		if (!empty($paramsCache)) {
			return $paramsCache;
		}

		$params = $this->getAddonParameters();
		$result = new stdClass();
		$addonParams = (object)$params;
		
        if (!empty($addonParams)) {
			$count_variables = (count($this->getParametersVariables()) >= 2) ? count($this->getParametersVariables()) : 1;
			
			$widthId = $addonParams->variables['width_id'];
			$heightId = $addonParams->variables['height_id'];
			$depthId = $addonParams->variables['depth_id'];
                
			$defaultValsOfFormulaCalcParams = [
				$widthId => $addonParams->width_def,
				$heightId => $addonParams->height_def,
				$depthId => $addonParams->depth_def
			];
			
			$freeAttrsParamsIds = [
				'width_id' => $widthId,
				'height_id' => $heightId,
				'depth_id' => $depthId
			];

			$freeAttrsParamsMin = [
				$widthId => $addonParams->width_min,
				$heightId => $addonParams->height_min,
				$depthId => $addonParams->depth_min
			];

			$freeAttrsParamsMax = [
				$widthId => $addonParams->width_max,
				$heightId => $addonParams->height_max,
				$depthId => $addonParams->depth_max
			];

			$freeAttrsParamsStep = [
				$widthId => $addonParams->width_step,
				$heightId => $addonParams->height_step,
				$depthId => $addonParams->depth_step
			];

			$freeAttrsParamsVarsIds = [];

            for ($i = 1; $i <= $count_variables; $i++) {
				$freeAttrId = $addonParams->variables['var_' . $i];

				$freeAttrsParamsIds['var_' . $i] = $freeAttrId;
				$defaultValsOfFormulaCalcParams[$freeAttrId] = $addonParams->{'var_' . $i . '_def'};

				$freeAttrsParamsMin[$freeAttrId] = $addonParams->{'var_' . $i . '_min'};
				$freeAttrsParamsMax[$freeAttrId] = $addonParams->{'var_' . $i . '_max'};
				$freeAttrsParamsStep[$freeAttrId] = $addonParams->{'var_' . $i . '_step'};

				$freeAttrsParamsVarsIds[$i] = $freeAttrId;
            }
			        
			$result->freeAttrsParamsIds = $freeAttrsParamsIds;
			$result->freeAttrsParamsVarsIds = $freeAttrsParamsVarsIds;
			$result->freeAttrsParamsDefVals = $defaultValsOfFormulaCalcParams;
			
			$result->freeAttrsParamsMinVals = $freeAttrsParamsMin;
			$result->freeAttrsParamsMaxVals = $freeAttrsParamsMax;
			$result->freeAttrsParamsStepVals = $freeAttrsParamsStep;

			$result->priceTypes['formula'] = $addonParams->pricetypes_formula;
			$result->priceTypes['names'] = $addonParams->pricetypes_formula_name;

            $result->countVariables = $count_variables;
		}
		
		$paramsCache = $result;
		return $result;
	}

	public function isIssetAtLeastOneNonEmptyParam() 
    {
        $modelFreeAtrrCalcPriceParameters = $this->getParameters();
        $freeAttrCalcPriceBasicDefaultParams = $modelFreeAtrrCalcPriceParameters->freeAttrsParamsDefVals ?: [];
        $countOfParamsOnFormulaCalc = $modelFreeAtrrCalcPriceParameters->countVariables ?: 0;

        $width_def = $freeAttrCalcPriceBasicDefaultParams['1'] ?? '';
        $height_def = $freeAttrCalcPriceBasicDefaultParams['2'] ?? '';
        $depth_def = $freeAttrCalcPriceBasicDefaultParams['3'] ?? '';

        $isIssetAtLeastOneNonEmptyParam = false;
        if (!empty($width_def) || !empty($height_def) || !empty($depth_def)) {
            $isIssetAtLeastOneNonEmptyParam = true;
        }
        
        if (!$isIssetAtLeastOneNonEmptyParam) {
            for ($i = 1; $i <= $countOfParamsOnFormulaCalc; $i++) {
                if (!empty($freeAttrCalcPriceBasicDefaultParams[$i])) {
                    $isIssetAtLeastOneNonEmptyParam = true;
                    break;
                }
            }
        }

        return $isIssetAtLeastOneNonEmptyParam;
	}
	
	public function replaceInputValuesToDefault(&$freeattributes, $productId)
	{
        $modelFreeAtrrCalcPriceParameters = $this->getParameters();
        $freeAttrCalcPriceBasicDefaultParams = $modelFreeAtrrCalcPriceParameters->freeAttrsParamsDefVals ?: [];

        if (!empty($modelFreeAtrrCalcPriceParameters->freeAttrsParamsIds)) {
            $ids = $modelFreeAtrrCalcPriceParameters->freeAttrsParamsIds ?: [];
            
            foreach ($freeattributes as &$freeAttr) {
            
                if (in_array($freeAttr->id, $ids)) {
					
					$value = '';
                    if (isset($freeAttrCalcPriceBasicDefaultParams[$freeAttr->id])) {
                        $value =  printFreeAttrQtyByUnit($freeAttrCalcPriceBasicDefaultParams[$freeAttr->id], $freeAttr->id, $productId);//$freeAttrCalcPriceBasicDefaultParams[$freeAttr->id];
                    }

                    $freeAttr->input_field = '<input type="text" class="inputbox freeattr" size="40" name="freeattribut[' . $freeAttr->id . ']" id="freeattribut_' . $freeAttr->id . '" value="' . $value . '" onfocusout="shopProductFreeAttributes.onKeyup(true);free_attributte_recalcule();" />';
                }
            }
        }
	}

}