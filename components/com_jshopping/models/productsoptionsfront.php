<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsOptionsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_option';

    public function getValByProductIdAndKey(int $productId, int $key)
    {
        $columns = ['`value`'];
        $where = [
            "`product_id` = {$productId}",
            "`key` = {$key}",
        ];

        return $this->select($columns, $where, '', false)->value ?? null;
    }

    public function getValsByProductId(int $productId)
    {
        $db = \JFactory::getDBO();
        $query = "SELECT `key`, `value` FROM `" . static::TABLE_NAME . "` WHERE product_id = '{$db->escape($productId)}'";
        $db->setQuery($query);
        $list = $db->loadObjectList();
        $rows = [];

        foreach($list as $v) {
            $rows[$v->key] = $v->value;
        }

        return $rows;
    }

    public function getList(array $productIds, int $key, int $setforallproducts = 1)
    {
        if (empty($productIds)) {
            return [];
        }
        
        $db = \JFactory::getDBO();
        $ids = implode(',', $productIds);
        $query = "SELECT `product_id`, `value` FROM `#__jshopping_products_option` WHERE product_id IN ({$db->escape($ids)}) AND `key`='{$db->escape($key)}' ";
        $db->setQuery($query);
        $list = $db->loadObjectList('product_id');         
        $rows = [];

        foreach($productIds as $pid) {
            if (isset($list[$pid])) {
                $rows[$pid] = $list[$pid]->value;
            } else {
                if ($setforallproducts) {
                    $rows[$pid] = '';
                }
            }
        }

        return $rows;
    }  
}
