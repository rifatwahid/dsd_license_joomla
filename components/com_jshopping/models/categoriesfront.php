<?php

class JshoppingModelCategoriesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_categories';

    public function getAllCategories(int $publish = 1, int $access = 1): array
    {
        $db = \JFactory::getDBO();
        $user = JFactory::getUser();
        $where = [];
        $add_where = '';

        if ($publish) {
            $where[] = "category_publish = '1'";
        }

        if ($access) {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $where[] = ' access IN (' . $groups . ')';
        }

        if (!empty($where)) {
            $add_where = ' where ' . implode(' and ', $where);
        }

        $query = 'SELECT `category_id`, `category_parent_id` FROM `#__jshopping_categories` ' . $add_where . ' ORDER BY ordering';
        $db->setQuery($query);

        return $db->loadObjectList() ?: [];
    }

    public function getSubCategories($parentId, string $order = 'id', string $ordering = 'asc', int $publish = 0): array
    {
        $shopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $user = JFactory::getUser();
        $add_where = $publish ? (" AND category_publish = '1' ") : '';
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $add_where .=' AND access IN (' . $groups . ')';
        if ($order == 'id') $orderby = 'category_id';
        if ($order == 'name') $orderby = '`' . $lang->get('name') . '`';
        if ($order == 'ordering') $orderby = 'ordering';
        if (!$orderby) $orderby = 'ordering';

        $db = \JFactory::getDBO();
        $query = "SELECT `".$lang->get('name')."` as name,`".$lang->get('description')."` as description,`".$lang->get('short_description')."` as short_description, category_id, category_publish, ordering, category_image 
                FROM `#__jshopping_categories`
                WHERE category_parent_id = '" . $db->escape($parentId) . "' " . $add_where . "
                ORDER BY " . $orderby . " " . $ordering;
                $db->setQuery($query);
        $categories = $db->loadObjectList();

        foreach($categories as $key => $value) {
			$Itemid = getShopCategoryPageItemid($categories[$key]->category_id);
			$categories[$key]->category_image = $categories[$key]->category_image ?: JUri::base() . $shopConfig->path_to_category_no_img;
            $categories[$key]->category_link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id=' . $categories[$key]->category_id, 1, 0, null, $Itemid);
        }  
        return $categories;
    }

    public function getTreeParentCategories(int $categoryParentId, int $publish = 1, int $access = 1): array
    {
        $user = JFactory::getUser();
        $cats_tree = []; 
        $where = [];

        if ($publish){
            $where[] = "category_publish = '1'";
        }

        if ($access) {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $where[] =' access IN (' . $groups . ')';
        }

        $add_where = '';
        if (!empty($where)) {
            $add_where = 'AND ' . implode(' AND ', $where);
        }

        $db = \JFactory::getDBO();
        while($categoryParentId) {
            $cats_tree[] = $categoryParentId;
            $query = "SELECT category_parent_id FROM `#__jshopping_categories` WHERE category_id = '" . $db->escape($categoryParentId) . "' " . $add_where;
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            $categoryParentId = $rows['0']->category_parent_id;
        }

        return array_reverse($cats_tree);
    }
}