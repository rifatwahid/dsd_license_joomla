<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelUsergroupPermissionBathProductEdit extends JshoppingModelBathProductEdit
{    
    public function resolveActionOfProduct(jshopProduct &$product, array $data, int $actionId)
    {
        if (array_key_exists($actionId, static::ACTIONS) && !empty($this->isExistsAtLeastOneActivatedProp($data))) {
            $usergroupPermissions = [
                'usergroup_show_product' => $data['usergroup_show_product'] ?? [],
                'usergroup_show_price' => $data['usergroup_show_price'] ?? [],
                'usergroup_show_buy' => $data['usergroup_show_buy'] ?? [],
            ];

            foreach ($usergroupPermissions as $propertyName => $permissionData) {
                if (!empty($permissionData)) {
                    $currentProductPermission = ($product->{$propertyName} != '') ? explode(' , ', $product->{$propertyName}): [];

                    switch($actionId) {
                        case static::CODES['ADD']:
                            $value = array_unique(array_merge($currentProductPermission, $permissionData));
                            break;
                        case static::CODES['DELETE']:
                            $value = array_diff($currentProductPermission, $permissionData);
                            break;
                        case static::CODES['REPLACE']:
                            $value = $permissionData;
                            break;
                    }

                    $value = implode(' , ', $value);
                    $product->set($propertyName, $value);
                }
            }
        }
    }

    protected function isExistsAtLeastOneActivatedProp(array $data): bool
    {
        return (!empty($data['usergroup_show_product']) || !empty($data['usergroup_show_price']) || !empty($data['usergroup_show_buy']));
    }
}