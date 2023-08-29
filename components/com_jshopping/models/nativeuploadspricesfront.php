<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelNativeUploadsPricesFront extends jshopBase
{
    public function getByProductId(int $productId): ?array
    {
        $tableOfNativeUploadsPrices = JSFactory::getTable('NativeUploadsPrices');
        $tableName = $tableOfNativeUploadsPrices->getTableName();

        $db = \JFactory::getDBO();
        $selectOfQuery = "SELECT * FROM {$db->qn($tableName)} WHERE `product_id` = {$db->escape($productId)}";

        return $db->setQuery($selectOfQuery)->loadObjectList();
    }

    public function getUploadPriceData(int $productId, int $qtyUpload): ?jshopNativeUploadsPrices
    {
        $tableOfNativeUploadsPrices = JSFactory::getTable('NativeUploadsPrices');
        $tableName = $tableOfNativeUploadsPrices->getTableName();

        $db = \JFactory::getDBO();
        $productId = $db->escape($productId);
        $qtyUpload = $db->escape($qtyUpload);

        $sqlSelect = "SELECT * FROM `{$tableName}` WHERE `product_id` = {$productId} AND (({$qtyUpload} >= `from_item` AND {$qtyUpload} <= `to_item`) OR ({$qtyUpload} >= `from_item` AND `to_item` = 0)) ORDER BY `id` DESC;";
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/tables/');
        $result = $db->setQuery($sqlSelect)->loadObject();

        if (!empty($result)) {
            JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/tables/');

            $tableOfNativeUploadsPrice = JSFactory::getTable('NativeUploadsPrices');
            $tableOfNativeUploadsPrice->bind($result);
            $result = $tableOfNativeUploadsPrice;

            $result->percent = (float) $result->percent;
            $result->price = (float) $result->price;
            $result->calculated_price = (float) $result->calculated_price;
        }

        return $result;
    }
}