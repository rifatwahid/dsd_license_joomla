<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelCustomizeBathProductEdit extends JshoppingModelBathProductEdit
{    
    public function resolveActionOfProduct(jshopProduct &$product, array $data, int $actionId)
    {
        if (array_key_exists($actionId, static::ACTIONS) && $this->isExistsAtLeastOneCustomizeActivatedProp($data)) {   
            switch($actionId) {
                case static::CODES['ADD']:
                    $this->bindActivatedCustomizeProperties($product, $data, 1);
                    break;
                case static::CODES['DELETE']:
                    $this->bindActivatedCustomizeProperties($product, $data, 0);
                    break;
                case static::CODES['REPLACE']:
                    $this->resetAllCustomizeProperties($product, $data);
                    $this->bindActivatedCustomizeProperties($product, $data, 1);
                    break;
            }
        }
    }

    protected function resetAllCustomizeProperties(jshopProduct &$product, $data)
    {
        $propertiesNames = $this->getCustomizePropertiesNames();

        foreach($propertiesNames as $propertyName) {
            $product->set($propertyName, 0);
        }
    }

    protected function bindActivatedCustomizeProperties(jshopProduct &$product, array $data, int $value = 1)
    {
        $propertiesNames = $this->getCustomizePropertiesNames();

        foreach($propertiesNames as $propertyName) {
            if (isset($data[$propertyName]) && !empty($data[$propertyName])) {
                if ($propertyName == 'max_allow_uploads') {
                    if ($data['max_allow_uploads'] != '') {
                        $product->set($propertyName, $data['max_allow_uploads']);
                    }
                } else {
                    $product->set($propertyName, $value);
                }
            }
        }
    }

    protected function isExistsAtLeastOneCustomizeActivatedProp(array $data): bool
    {
        $isExists = false;
        $propertiesNames = $this->getCustomizePropertiesNames();

        if (!empty($data)) {
            foreach ($propertiesNames as $name) {
                if (isset($data[$name]) && !empty($data[$name])) {
                    $isExists = true;
                    break;
                }
            }
        }

        return $isExists;
    }

    protected function getCustomizePropertiesNames()
    {
        return [
            'is_allow_uploads',
            'max_allow_uploads',
            'is_unlimited_uploads',
            'is_upload_independ_from_qty',
            'is_required_upload',
            'product_show_cart',
            'is_show_bulk_prices',
            'one_click_buy',
        ];
    }
}