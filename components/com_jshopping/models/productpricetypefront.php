<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductPriceTypeFront extends jshopBase
{
    public const FORMULA_QTY_CODE = 0;
    public const QTY_DISCOUNT_CODE = 1;
    public const QTY_PLUS_FORMULA_CODE = 2;

    public function getPricePerConsignmentRowByQty(?array $addPrices, $qty)
    {
        $result = [];

        if (!empty($addPrices) && isset($qty)) {
			/*$_addPrice = [];
            foreach ($addPrices as $value) {
				$_addPrice[$value->product_quantity_start] = $value;
			}
			sort($_addPrice);*/
            usort($addPrices, function ($first, $second) {
                return (float)$first->product_quantity_start > (float)$second->product_quantity_start;
            });
			
            foreach ($addPrices as $value) {
                $isQtyMore = $qty >= $value->product_quantity_start;
                $isQtyLess = $qty <= $value->product_quantity_finish;

                if (($isQtyMore && $isQtyLess) || ($isQtyMore && $value->product_quantity_finish == 0)) {
                    $result = $value;
                }
            }
            usort($addPrices, function ($first, $second) {
                return (float)$first->product_quantity_start < (float)$second->product_quantity_start;
            });
        }

        return $result;
    }

    public function calcQtyFromSelectedQtyFormula($formulaCode, $quantityProduct, $calculatedPriceType)
    {
        $calcQuantity = null;

        switch ($formulaCode) {
            case static::FORMULA_QTY_CODE:
                if ($calculatedPriceType==1)
					$calcQuantity = $quantityProduct;
				else
					$calcQuantity = $calculatedPriceType;
                break;
            case static::QTY_DISCOUNT_CODE:
                $calcQuantity = $quantityProduct;
                break;
            case static::QTY_PLUS_FORMULA_CODE:
                $calcQuantity = $calculatedPriceType * $quantityProduct;
                break;
        }

        return $calcQuantity;
    }

    public function getCalcPriceType(?int $formulaCodeOfPriceType, ?array $freeAttrsValues) 
    {
        JModelLegacy::addIncludePath(JPATH_ROOT  . '/administrator/components/com_jshopping/models');

        $priceOfPriceType = 0;
        $activeFormula = null;
        $modelOfFreeAttrCalcPrice = JSFactory::getModel('freeattrcalcprice');
        $freeAttrCalcPriceParams = $modelOfFreeAttrCalcPrice->getParameters();
        $freeAttrCalcPriceFormuls = $freeAttrCalcPriceParams->priceTypes['formula'] ?: [];

        if (!empty($freeAttrCalcPriceFormuls[$formulaCodeOfPriceType])) {
            $activeFormula = $freeAttrCalcPriceFormuls[$formulaCodeOfPriceType];
        }
        
        if (!empty($activeFormula)) {
            $widthId = $freeAttrCalcPriceParams->freeAttrsParamsIds['width_id'] ?? 0;
            $heightId = $freeAttrCalcPriceParams->freeAttrsParamsIds['height_id'] ?? 0;
            $depthId = $freeAttrCalcPriceParams->freeAttrsParamsIds['depth_id'] ?? 0;

            $width = saveAsPrice($freeAttrsValues[$widthId] ?? 0);
            $height = saveAsPrice($freeAttrsValues[$heightId] ?? 0);
            $depth = saveAsPrice($freeAttrsValues[$depthId]  ?? 0);
            
            foreach ($freeAttrCalcPriceParams->freeAttrsParamsVarsIds as $varId => $freeAttrId) {
                if (!empty($freeAttrId)) {
                    $varName = 'var' . $varId;
                    $value = $freeAttrsValues[$freeAttrId];

                    ${$varName} = saveAsPrice($value);
                }
            }
            
            eval('$priceOfPriceType = ' . $activeFormula . ';');
        }

        return $priceOfPriceType;
    }

}
