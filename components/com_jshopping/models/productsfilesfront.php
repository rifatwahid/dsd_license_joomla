<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsFilesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_files';

    public function getProductsFilesByFilesIds(?array $filesIds): array
    {
        $lang = JSFactory::getLang();
        $result = [];

        if (!empty($filesIds)) {
            $db = \JFactory::getDBO();
            $sql = 'SELECT *,`' . $lang->get('demo_descr') . '` as demo_descr ,`' . $lang->get('file_descr') . '` as file_descr FROM ' . static::TABLE_NAME . ' WHERE id IN (' . implode(',', $filesIds) . ')';
            $db->setQuery($sql);
            $result = $db->loadObjectList() ?: [];
        }

        return $result;
    }

    public function getFilesByProductId(?int $productId): array
    {
        $lang = JSFactory::getLang();
        $result = [];

        if (!empty($productId)) {
            $db = \JFactory::getDBO();
            $sql = 'SELECT *,`' . $lang->get('demo_descr') . '` as demo_descr ,`' . $lang->get('file_descr') . '` as file_descr FROM ' . static::TABLE_NAME . ' WHERE `product_id` = ' . $productId . ' ORDER BY `ordering`';
            $db->setQuery($sql);
            $result = $db->loadObjectList() ?: [];
        }

        return $result;
    }

    public function getDemoFilesByProductId(?int $productId): array
    {
        $lang = JSFactory::getLang();
        $result = [];

        if (!empty($productId)) {
            $db = \JFactory::getDBO();
            $sql = 'SELECT *,`' . $lang->get('demo_descr') . '` as demo_descr ,`' . $lang->get('file_descr') . '` as file_descr FROM ' . static::TABLE_NAME . ' WHERE `product_id` = ' . $productId . ' AND `demo` != \'\' ORDER BY `ordering`';
            $db->setQuery($sql);
            $result = $db->loadObjectList() ?: [];
        }

        return $result;
    }

    public function getSalesFilesByProductId(?int $productId): array
    {
        $lang = JSFactory::getLang();
        $result = [];

        if (!empty($productId)) {
            $db = \JFactory::getDBO();
            $sql = 'SELECT *,`' . $lang->get('demo_descr') . '` as demo_descr ,`' . $lang->get('file_descr') . '` as file_descr FROM ' . static::TABLE_NAME . ' WHERE product_id = ' . $productId . ' AND `file`<>"" ORDER BY `ordering`';
            $db->setQuery($sql);
            $result = $db->loadObjectList() ?: [];
        }

        return $result;
    }

}