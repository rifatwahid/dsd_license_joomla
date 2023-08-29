<?php

class JshoppingModelProductsToCategoriesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_to_categories';

    public function getByProductId(int $productId): array
    {
        return $this->select(['*'], ['product_id = \'' . $productId . '\'']);
    }

    public function getFirstProductCategory(int $productId): ?int
    {
        $db = \JFactory::getDBO();

        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $query = "SELECT pr_cat.category_id FROM `#__jshopping_products_to_categories` AS pr_cat
                LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                WHERE pr_cat.product_id = '{$db->escape($productId)}' AND cat.category_publish = '1' AND cat.access IN ({$groups}) LIMIT 0, 1";
        $db->setQuery($query);

        return $db->loadResult();
    }
	
	public function getProductCategory(int $productId)
    {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $query = "SELECT pr_cat.category_id FROM `#__jshopping_products_to_categories` AS pr_cat
                LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                WHERE pr_cat.product_id = '{$db->escape($productId)}' AND cat.category_publish = '1' AND cat.access IN ({$groups})";
        $db->setQuery($query);
        return $db->loadColumn();
    }
}
