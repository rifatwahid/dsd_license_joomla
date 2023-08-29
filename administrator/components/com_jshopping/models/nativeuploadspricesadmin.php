<?php

defined('_JEXEC') or die( 'Restricted access');

class JshoppingModelNativeUploadsPricesAdmin extends JModelLegacy 
{
    public function savePricesData(?array $data, int $productId)
    {
        if (!empty($data) && !empty($productId)) {
            $peeledData = $this->clearSaveData($data);

            if (!empty($peeledData)) {

                foreach ($peeledData as $data) {
                    $tableOfNativeUploadsPrices = JSFactory::getTable('NativeUploadsPrices');
                    $data['product_id'] = $productId;
                    $tableOfNativeUploadsPrices->bind($data);
                    $tableOfNativeUploadsPrices->store();
                    unset($tableOfNativeUploadsPrices);
                }
            }
        }
    }

    protected function clearSaveData(array $data)
    {
        if (!empty($data)) {
            $result = array_reduce($data, function ($carry, $uploadPriceData) {
                $uploadPriceData = is_object($uploadPriceData) ? (array)$uploadPriceData : $uploadPriceData;
                
                if (isIssetAndNotEmptyString($uploadPriceData['from_item']) && isIssetAndNotEmptyString($uploadPriceData['to_item']) && isIssetAndNotEmptyString($uploadPriceData['calculated_price'])) {
                    $uploadPriceData['percent'] = $uploadPriceData['percent'] ?: 0;
                    $uploadPriceData['price'] = $uploadPriceData['price'] ?: 0;
                    $uploadPriceData['calculated_price'] = $uploadPriceData['calculated_price'] ?: 0;

                    $carry[] = $uploadPriceData;
                }

                return $carry;
            }, []);

            return $result;
        }

        return $data;
    }

    public function getByProductId(int $productId): array
    {
        $tableOfNativeUploadsPrices = JSFactory::getTable('NativeUploadsPrices');
        $tableName = $tableOfNativeUploadsPrices->getTableName();

        $db = \JFactory::getDBO();
        $sqlSelect = "SELECT * FROM `{$tableName}` WHERE `product_id` = {$db->q($productId)}";
        $db->setQuery($sqlSelect);

        return $db->loadObjectList() ?: [];
    }

    public function deleteByProductId(int $productId)
    {
        $tableOfNativeUploadsPrices = JSFactory::getTable('NativeUploadsPrices');
        $tableName = $tableOfNativeUploadsPrices->getTableName();

        $db = \JFactory::getDBO();
        $sqlDelete = "DELETE FROM `{$tableName}` WHERE `product_id` = {$db->q($productId)}";
        
        return $db->setQuery($sqlDelete)->execute();
    }

    public function duplicatePrices($fromProductId, $toProductId)
    {
        $isSuccess = false;

        if (!empty($fromProductId) && !empty($toProductId)) {
            $prices = $this->getByProductId($fromProductId);
            $isSuccess = true;

            if (!empty($prices)) {
                $separetedPrices = deletePropertiesFromObjectList($prices, ['id', 'product_id']);

                if (!empty($separetedPrices)) {
                    $this->savePricesData($separetedPrices, $toProductId);
                }
            }
        }

        return $isSuccess;
    }
}