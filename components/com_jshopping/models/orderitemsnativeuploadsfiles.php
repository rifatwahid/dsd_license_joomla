<?php 

defined('_JEXEC') or die('Restricted access');

class JshoppingModelOrderItemsNativeUploadsFiles extends jshopBase 
{
    const TABLE_NAME = '#__jshopping_order_items_native_uploads_files';

    public function getAll(array $columnsNames = ['*']): array
    {
        $columnsNames = $columnsNames ?: ['*'];
        $db = \JFactory::getDBO();
        $columns = implode(', ', $columnsNames);
        $query = "SELECT {$columns} FROM {$db->qn(static::TABLE_NAME)}";

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result ?: [];
    }

    public function getListOfUploadedFiles(bool $isGetWithFullPath = true): array
    {
        $result = [
            'files' => [],
            'previews' => []
        ];
        $uploadedFilesNames = $this->getAll(['file', 'preview']);
        
        if (!empty($uploadedFilesNames)) {
            foreach ($uploadedFilesNames as $data) {
                if (!empty($data->file)) {
                    $result['files'][] = $isGetWithFullPath ? PATH_TO_UPLOADED_FILES . $data->file : $data->file;
                }

                if (!empty($data->preview)) {
                    $result['previews'][] = $isGetWithFullPath ? PATH_TO_UPLOADED_FILES . $data->preview : $data->preview;
                }
            }
        }

        return $result;
    }

    public function deleteUnusedFiles(array $excludedFiles = ['noimage.gif']): bool
    {
        $isSuccess = false;
        $shopFiles = new ShopFiles();
        $filesFromUploadFolder = $shopFiles->getList(PATH_TO_UPLOADED_FILES, $excludedFiles);

        if (!empty($filesFromUploadFolder)) {
            $modelOfOrderItemsNativeUploadsFiles = JSFactory::getModel('OrderItemsNativeUploadsFiles');
            $ordersFiles = $modelOfOrderItemsNativeUploadsFiles->getListOfUploadedFiles(false);
            $ordersFiles = array_unique(array_merge($ordersFiles['files'], $ordersFiles['previews']));

            if (!empty($ordersFiles)) {
                $differenceFiles = array_diff($filesFromUploadFolder, $ordersFiles) ?: [];

                if (!empty($differenceFiles)) {
                    foreach ($differenceFiles as $file) {
                        $pathToFile = PATH_TO_UPLOADED_FILES . '/' . $file;
                        $shopFiles->delete($pathToFile);
                    }

                    $isSuccess = true;
                }
            }
        }

        return $isSuccess;
    }

    public function massInsert($orderId, $orderItemId, $uploadDataArr)
    {
        $isSuccessInsert = false;
        
        if (!empty($uploadDataArr['files']) && !empty($orderId)) {
            $db = \JFactory::getDBO();
            $countOfFilesNames = count($uploadDataArr['files']);
            $orderItemNativeUploadsFilesTable = JSFactory::getTable('orderItemNativeUploadsFiles');
            $valuesForInsert = [];

            for($i = 0; $i <= $countOfFilesNames; $i++) {
                if (!empty($uploadDataArr['previews'][$i])) {
                    $tempArr = [$orderId, $orderItemId];
                    $tempArr[] = $db->escape($uploadDataArr['qty'][$i]);
                    $tempArr[] = $db->q($uploadDataArr['files'][$i]);
                    $tempArr[] = $db->q($uploadDataArr['previews'][$i]);
                    $tempArr[] = $db->q($uploadDataArr['descriptions'][$i]);

                    $valuesForInsert[] = implode(', ', $tempArr);
                }
            }

            if (!empty($valuesForInsert)) {
                $query = $db->getQuery(true);

                $query->insert($db->qn($orderItemNativeUploadsFilesTable->getTableName()));
                $query->columns($orderItemNativeUploadsFilesTable::TABLE_COLUMNS_NAMES);
                $query->values($valuesForInsert);
                $db->setQuery($query);
                $isSuccessInsert = $db->execute();
            }

        }

        return $isSuccessInsert;
    }

    public function getDataBy($columnName, $columnValue)
    {
        $db = \JFactory::getDBO();
        $orderItemNativeUploadsFilesTable = JSFactory::getTable('orderItemNativeUploadsFiles');
        $querySelectData = 'SELECT * FROM ' . $db->qn($orderItemNativeUploadsFilesTable->getTableName()) . ' WHERE ' . $db->qn($columnName) . ' = ' . $columnValue;

        $db->setQuery($querySelectData);
        $selectedData = $db->loadObjectList();
        $result = $this->prepareNativeUploadDbResult($selectedData);

        return $result;
    }

    public function getDataByOrderAndItemId($orderId, $orderItemId)
    {
        $result = [];
        $db = \JFactory::getDBO();
        $orderItemNativeUploadsFilesTable = JSFactory::getTable('orderItemNativeUploadsFiles');
        $querySelectData = 'SELECT * FROM ' . $db->qn($orderItemNativeUploadsFilesTable->getTableName()) . ' WHERE `order_item_id` = ' . $orderItemId . ' AND `order_id` = ' . $orderId;

        $db->setQuery($querySelectData);
        $selectedData = $db->loadObjectList();
        $result = $this->prepareNativeUploadDbResult($selectedData);

        return $result;
    }

    protected function prepareNativeUploadDbResult($selectedData) {
        $result = [];

        if (!empty($selectedData)) {
            $i = 0;
            foreach($selectedData as $key => $data) {
                $result['additional'][$i]['id'] = $data->id;
                $result['additional'][$i]['order_id'] = $data->order_id;
                $result['additional'][$i]['order_item_id'] = $data->order_item_id;

                $result['qty'][] = $data->qty;
                $result['files'][] = $data->file;
                $result['previews'][] = $data->preview;
                $result['descriptions'][] = $data->description;

                $i++;
            }
        }

        return $result;
    }

    function deleteFilesFromOrder($order_id) {
        $db = \JFactory::getDBO();
        $query = "DELETE  FROM {$db->qn(static::TABLE_NAME)} WHERE `order_id`=".$order_id;

        $db->setQuery($query);
        $db->execute();
    }

    function insertOrderUploadFiles($data) {
       if(!empty($data['nativeProgressUpload']['uploads'])){
            foreach($data['nativeProgressUpload']['uploads'] as $order_item_id => $value){
                $this->massInsert($data['order_id'], $order_item_id, $value);
            }
       }
    }
}