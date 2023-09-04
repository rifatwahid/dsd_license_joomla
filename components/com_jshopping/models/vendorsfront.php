<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelVendorsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_vendors';

    public function getAll(int $publish = 1, $limitstart = 0, $limit = 0): array
    {
        $where = [
            1
        ];

        if ($publish) {
            $where[] = "`publish`='{$publish}'";
        }

        return $this->select(['*'], $where, ' ORDER BY shop_name', true, $limit, $limitstart);
    }

    public function getVendorsCount(int $isGetPublish = 1): int
    {
        $where = [
            1
        ];

        if ($isGetPublish) {
            $where[] = "`publish`='{$isGetPublish}'";
        }

        return $this->select(['COUNT(id) as count'], $where, '', false)->count ?: 0;
    }

    public function getMainVendorId(): int
    {
        $db = \JFactory::getDBO();
        $query = 'SELECT `id` FROM `#__jshopping_vendors` WHERE `main` = 1';
        extract(js_add_trigger(get_defined_vars(), 'query'));
        $db->setQuery($query);

        return $db->loadResult() ?: 0;
    }

    public function getIdWhereNotEqualVendorId(int $userId, int $vendorId): int
    {
        return (int)$this->select(['id'], [
            "`user_id` = '{$userId}'",
            "`id` != '{$vendorId}'",
        ], '', false)->id ?: 0;
    }
}
