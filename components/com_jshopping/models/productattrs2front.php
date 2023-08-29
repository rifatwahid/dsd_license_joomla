<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductAttrs2Front extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_attr2';

    public function getByproductId(int $productId): array
    {
        return $this->select(['*'], ['product_id = \'' . $productId . '\'']);
    }

    public function getByProductAndAttrAndAttrValIds(int $productId, int $attrId, int $attrValId)
    {
        $where = [
            'product_id = \'' . $productId . '\'',
            'attr_id = \'' . $attrId . '\'',
            'attr_value_id = \'' . $attrValId . '\'',
        ];

        return $this->select(['*'], $where, '', false);
    }

    public function getIndependAttrs($productId, int $attrId = 0, string $orderBy = 'sorting')
    {
		$orderBy = 'gorder';
        if (!empty($productId)) {
			$lang = JSFactory::getLang();
            $db = \JFactory::getDBO();
			$orderring = JSFactory::getModel('AttrsFront')->orderAttrIndependent();
            $query = "SELECT AP.*,A.`" . $lang->get('name') . "` as name, G.ordering as gorder FROM `" . static::TABLE_NAME . "`  as AP
			LEFT JOIN `#__jshopping_attr` as A ON AP.`attr_id`=A.`attr_id`
			LEFT JOIN `#__jshopping_attr_groups` as G on A.`group` = G.id
			WHERE AP.product_id = '" . $productId. "' AND AP.`attr_id` > " . $attrId . " ORDER BY `".$orderBy."`, AP.sorting,".$orderring;
            \JFactory::getApplication()->triggerEvent('onAfterQueryAttrs2GetAllByProductIdWhereAttrIdMoreOf', [&$query]);
            $db->setQuery($query);

            $list = $db->loadObjectList();			
			foreach($list as $k=>$val){ 
				if($val->expiration_date && !empty($val->expiration_date) && strtotime($val->expiration_date) > 0 && ($val->expiration_date < date('Y-m-d'))) {
                    unset($list[$k]);
                }
			}
			
			return $list;
        }

        return [];
    }

    public function deleteByProdId(int $productId)
    {
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `" . static::TABLE_NAME . "` WHERE `product_id` = '" . $db->escape($productId) . "'";
        $db->setQuery($query);

        return $db->execute();    
    }

    public function deleteByIds(array $ids)
    {
        if (!empty($ids)) {
            $db = \JFactory::getDBO();
            $delSql = 'DELETE FROM `' . static::TABLE_NAME . '` WHERE `id` IN (' . implode(', ', $ids) . ')';

            return $db->setQuery($delSql)->execute();
        }

        return false;
    }

    public function deleteForEachProdButNotFirstsAttrs(int $attrId)
    {
        if (!empty($attrId)) {
            $db = \JFactory::getDBO();

            $sqlSelect = "SELECT `id`, `product_id`, `attr_id` FROM `" . static::TABLE_NAME . "` WHERE `attr_id` = {$attrId} AND `id` IN (
                SELECT `id` FROM `" . static::TABLE_NAME . "` GROUP BY `product_id`, `attr_id`
            );";

            $items = $db->setQuery($sqlSelect)->loadAssocList();

            if (!empty($items)) {
                $groupByItems = groupByArrayKeyVal($items, 'product_id');
                $idsOfRecords = getSubElementsButNotFirst($groupByItems, 'id');
            }
    
            if (!empty($idsOfRecords)) {
                return $this->deleteByIds($idsOfRecords);
            }
        }

        return false;
    }
}
