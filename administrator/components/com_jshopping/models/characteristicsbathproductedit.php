<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelCharacteristicsBathProductEdit extends JshoppingModelBathProductEdit
{    
    public function resolveActionOfProduct(jshopProduct &$product, $choosedCharacteristics, int $actionId)
    {
        if (array_key_exists($actionId, static::ACTIONS) && !empty($choosedCharacteristics)) {
            $modelOfProductFields = JSFactory::getModel('productFields');
            $allCharacteristics = $modelOfProductFields->getList(1);

            array_walk($allCharacteristics, function ($field) use(&$product, $actionId, $choosedCharacteristics) {
                $dontActionCode = -1;
                $charactColmnName = 'extra_field_' . $field->id;
                $choosedCharacteristic = $choosedCharacteristics[$charactColmnName] ?? [];
                
                if (!in_array($dontActionCode, $choosedCharacteristic)) {

                    $currentCharactsOfProduct = explode(',', $product->get($charactColmnName) ?:'') ?: [];
                    $value = $this->getPreparedValueOFCharacts($field, $choosedCharacteristic) ?? null;
    
                    if (!is_null($value)) {
                        switch($actionId) {
                            case static::CODES['ADD']:
                                if ($field->multilist == 1) {
                                    $value = array_unique(array_merge($currentCharactsOfProduct, $value));
                                }
                                break;
                            case static::CODES['DELETE']:
                                $value = array_diff($currentCharactsOfProduct, $value);
                                break;
                            case static::CODES['REPLACE']:
                                // We don`t do anything and i`ll be replace automatically
                                break;
                        }

                        $value = implode(',', $value);
                        $product->set($charactColmnName, $value);
                    }
                }
            });
        }
    }

    protected function getPreparedValueOFCharacts(object $field, $characteristic): ?array
    {
        $result = null;

        if (!is_null($characteristic)) {
            switch($field->type) {
                case 0:
                    if (is_array($characteristic) and !empty($characteristic)) {
                        if ($field->multilist == 1 || ($field->multilist == 0 and !in_array(-1, $characteristic))) {
                            $result = $characteristic;
                        }
                    }
                break;
                case 1:
                    $characteristic = is_array($characteristic) ? reset($characteristic) : $characteristic;

                    if ($characteristic != '' && $characteristic != -1) {
                        $result = [
                            $characteristic
                        ];
                    }
                break;
            }
        }

        return $result;
    }
}