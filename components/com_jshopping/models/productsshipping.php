<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsShipping extends jshopBase
{
    const TABLE_NAME = '#__jshopping_products_shipping';

    public function getAll($isPublished = null)
    {
        $db = \JFactory::getDBO();
        $sqlSelect = 'SELECT * FROM ' . $db->qn(static::TABLE_NAME);

        if ($isPublished !== null) {
            $sqlSelect .= 'WHERE `published` = ' . (int)$isPublished;
        }

        $db->setQuery($sqlSelect);
        $result = $db->loadObjectList() ?: [];

        return $result;
    }

    public function getByProductsIds(array $productsIds, array $columnsToGet = ['*'], $isGetOnlyPublished = false, $isGroupByProductId = false)
    {
        $result = [];

        if (!empty($columnsToGet) && !empty($productsIds)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $stringOfProductsIds = implode(', ', $productsIds);
			if (!empty($stringOfProductsIds)){
				$sqlQuery = 'SELECT ' . $stringOfSearchColumns . ' FROM `#__jshopping_products_shipping` WHERE `product_id` IN (' . $stringOfProductsIds . ')';

				if ($isGetOnlyPublished) {
					$sqlQuery .= ' AND `published` = 1';
				}
				
				if($isGroupByProductId){
				   // $sqlQuery .= ' GROUP BY `product_id` ';
				}
				
				$db->setQuery($sqlQuery);
				$result = $db->loadObjectList();
				}
        }
        return $result;
    }

    public function isAtLeastOneEnabledForProduct($productId): bool
    {
        $result = false;

        if (!empty($productId)) {

            try {
                $db = \JFactory::getDBO();
                $sql = "SELECT `id` FROM `#__jshopping_products_shipping` WHERE `product_id` = {$db->escape($productId)} AND `published` = 1";
                $db->setQuery($sql);
                $result = !empty($db->loadResult());
            } catch(\Exception $e) {}
        }

        return $result;
    }

    public function getByShippingAndProductsIds($shippingId, array $productsIds)
    {
        $result = [];

        if (!empty($shippingId) && !empty($productsIds)) {
            $db = JFactory::getDBO();
            $productsIds = implode(',', $productsIds);
			if (!empty($productsIds)){
				$query = "SELECT * FROM `#__jshopping_products_shipping` WHERE `sh_pr_method_id` = {$db->q($shippingId)} AND `product_id` IN ({$productsIds})";
				$db->setQuery($query);
				$result = $db->loadObjectList();
			}
			if (empty($result)) $result=[];
        }

        return $result;
    }
	

    public function getByProductsIdsNoInclude(array $productsIds, array $columnsToGet = ['*'])
    {
        $result = [];

        if (!empty($columnsToGet) && !empty($productsIds)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $stringOfProductsIds = implode(', ', $productsIds);
			if (!empty($stringOfProductsIds)){
				$sqlQuery = 'SELECT ' . $stringOfSearchColumns . ' FROM `#__jshopping_products_shipping` WHERE `product_id` IN (' . $stringOfProductsIds . ')';

				$sqlQuery .= ' AND `published` = 0';

				$db->setQuery($sqlQuery);
				$result = $db->loadObjectList();
				}
        }
        
        return $result;
    }

	public function getProductsByShippingId($sh_pr_method_id, $productsIds)
    {
        $result = [];
        if (!empty($productsIds)) {
            $db = \JFactory::getDBO();
            $stringOfProductsIds = implode(', ', $productsIds);
			if (!empty($stringOfProductsIds)){
				$sqlQuery = 'SELECT `product_id` FROM `#__jshopping_products_shipping` WHERE `sh_pr_method_id`='.$sh_pr_method_id.' AND `published` = 1 AND `product_id` IN ('. $stringOfProductsIds .')';
				$db->setQuery($sqlQuery);
				$result = $db->loadColumn();
				}
        }
        
        return $result;
    }
}