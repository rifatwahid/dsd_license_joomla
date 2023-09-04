<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsFreeAttrsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_free_attr';

    public function getFreeAttrsByProductId($productId, bool $isGetDefaultValues = true): array
    {
        $freeAttrs = [];

        if (!empty($productId)) {
            $lang = JSFactory::getLang();
            $db = \JFactory::getDBO(); 
            $query = "SELECT DISTINCT FA.id, FA.required, FA.`" . $lang->get("name") . "` as name, FA.`" . $lang->get("description") . "` as description, FA.type, FA.file_type, FA.show_unit, FA.unit_id, `FADV`.`default_value`, `FADV`.`min_value`, `FADV`.`max_value`, FADV.`is_fixed`, FADV.`showFreeAttrInput`
                FROM `" . static::TABLE_NAME . "` as `PFA`
                LEFT JOIN `#__jshopping_free_attr` as `FA` ON `FA`.`id` = `PFA`.`attr_id`
                LEFT JOIN `#__jshopping_free_attr_default_values` as `FADV` ON (`FADV`.`product_id` = " . $db->escape($productId) . " AND `FADV`.`attr_id` = `FA`.`id`)
                WHERE PFA.product_id = '" . $db->escape($productId) . "' 
                ORDER BY FA.ordering";

            $db->setQuery($query);
            $freeAttrs = $db->loadObjectList() ?: [];
	
			foreach($freeAttrs as $k=>$val){
				if(!$val->unit_id){
					$query = "SELECT `basic_price_unit_id`	FROM `#__jshopping_products` WHERE product_id = " . (int)$productId;
					$db->setQuery($query);
					$freeAttrs[$k]->unit_id = $db->loadResult();
				}
				if ($val->min_value != '' && $val->max_value != '') {
                    $val->min_max_value = JText::sprintf('COM_SMARTSHOP_FREE_ATTR_DEFAULT_VALUES_MIN_MAX', $val->min_value, $val->max_value, $val->units_measure ?? '');
                } elseif ($val->min_value != '') {
                    $val->min_max_value = JText::sprintf('COM_SMARTSHOP_FREE_ATTR_DEFAULT_VALUES_MIN', $val->min_value, $val->units_measure ?? '');
                } elseif ($val->max_value != '') {
                    $val->min_max_value = JText::sprintf('COM_SMARTSHOP_FREE_ATTR_DEFAULT_VALUES_MIX', $val->max_value, $val->units_measure ?? '');
                }
				$val->description = prepareText($val->description);

			}
            if ($isGetDefaultValues && !empty($freeAttrs)) {
                $freeAttrCalcPriceDefaultVals = JSFactory::getModel('FreeAttrCalcPriceFront')->getDefaultValues($freeAttrs);
                $productFreeAttrsDefaultVals = JSFactory::getModel('FreeAttrDefaultValuesFront')->getByProductId($productId);

                $freeAttrs = array_map(function ($freeAttr) use ($freeAttrCalcPriceDefaultVals, $productFreeAttrsDefaultVals) {

                    if (isset($freeAttrCalcPriceDefaultVals[$freeAttr->id]) || isset($productFreeAttrsDefaultVals[$freeAttr->id])) {
                        $freeAttr->defaultValue = $productFreeAttrsDefaultVals[$freeAttr->id] ?? $freeAttrCalcPriceDefaultVals[$freeAttr->id];
                    }

					if($freeAttr->show_unit){
						if($freeAttr->unit_id){
							$freeAttr->units_measure = JSFactory::getModel('units')->getUnitById($freeAttr->unit_id);
						}
					}else{
						$freeAttr->units_measure = '';
					}
					return $freeAttr;
                }, $freeAttrs, array($productId));
            }
        }

        return $freeAttrs;
    }
}
