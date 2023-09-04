<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelFilesBatchProductEdit extends JshoppingModelBathProductEdit
{   
    const ACTIONS = [
        0 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_ADD',
        2 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_REPLACE'
    ]; 

    public function resolveActionOfProduct(jshopProduct &$product, array $data, int $actionId)
    {

        if (array_key_exists($actionId, static::ACTIONS)) {   
            if ((!empty($data['demo_files']) || !empty($data['files'])) && $this->isExistsNotEmptyFiles($data['demo_files'], $data['files'])) {
                $modelOfProducts = JSFactory::getModel('products'); 
                $modelOfProductFile = JSFactory::getModel('Productfile'); 

                switch($actionId) {
                    case static::CODES['ADD']:
                        $modelOfProducts->handleSetFiles($product->product_id, $data);
                        break;
                    case static::CODES['REPLACE']:
                        $modelOfProductFile->deleteProductFilesFromDbByProductId($product->product_id);
                        $modelOfProducts->handleSetFiles($product->product_id, $data); 
                        break;
                }
            }
        }
    }

    protected function isExistsNotEmptyFiles(array $files = [], array $demoFiles = []): bool
    {
        $isExistsNotEmptyFiles = false;

        if (!empty($files)) {
            foreach ($files as $fileData) {
                if (!empty($fileData['source'])) {
                    return true;
                }
            }
        }

        if (!empty($demoFiles)) {
            foreach ($demoFiles as $fileData) {
                if (!empty($fileData['source'])) {
                    return true;
                }
            }
        }

        return $isExistsNotEmptyFiles;
    }
}