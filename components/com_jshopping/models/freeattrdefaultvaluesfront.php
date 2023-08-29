<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelFreeAttrDefaultValuesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_free_attr_default_values';

    public function getDefaultValueByProductAndAttrIds(int $productId, int $attrId)
    {
        $db = \JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->qn('default_value'))
            ->from($db->qn('#__jshopping_free_attr_default_values'))
            ->where($db->qn('attr_id') . '=' . $attrId, 'AND')
            ->where($db->qn('product_id') . '=' . $productId);
        $db->setQuery($query);

        return printFreeAttrQtyByUnit($db->loadResult(), $attrId, $productId);
    }

    public function getByProductId($productId): array
    {
        $defaultValues = [];

        if (!empty($productId)) {
            JModelLegacy::addIncludePath( JPATH_ROOT  . '/administrator/components/com_jshopping/models' );
            $modelFreeAttrsDefaultValues = JSFactory::getModel('FreeAttrsDefaultValues');
            $defaultValuesInfo = $modelFreeAttrsDefaultValues->getDataByProductId($productId, 'attr_activated = 1') ?: [];

            if (!empty($defaultValuesInfo)) {
                $defaultValues = array_reduce($defaultValuesInfo, function ($carry, $defaultValueInfo) {
                    if ($defaultValueInfo['default_value'] !== '') {
                        $defaultValue = is_numeric($defaultValueInfo['default_value']) ? printFreeAttrQtyByUnit($defaultValueInfo['default_value'], $defaultValueInfo['attr_id'], $defaultValueInfo['product_id']) : $defaultValueInfo['default_value'];
                        $carry[$defaultValueInfo['attr_id']] = $defaultValue;
                    }

                    return $carry;
                }) ?: [];
            }
        }

        return $defaultValues;
    }
}