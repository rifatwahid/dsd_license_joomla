<?php

class JshoppingModelManufacturersFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_manufacturers';

    public function getAllManufacturersByCategoryId(int $categoryId): object
    {
        $db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $adv_query = '';
        $groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
        $adv_query .=' AND prod.access IN (' . $groups . ')';

        if ($jshopConfig->hide_product_not_avaible_stock){
            $adv_query .= ' AND prod.product_quantity > 0';
        }

        $order = ($jshopConfig->manufacturer_sorting == 2) ? 'name' : 'man.ordering';

        $queryToSelectCategoryManufacturers = "SELECT distinct man.manufacturer_id as id, man.`" . $lang->get('name') . "` as name 
                FROM `#__jshopping_products` AS prod
                LEFT JOIN `#__jshopping_products_to_categories` AS categ USING (product_id)
                LEFT JOIN `" . self::TABLE_NAME . "` as man on prod.product_manufacturer_id = man.manufacturer_id 
                WHERE categ.category_id = '" . $db->escape($categoryId) . "' 
                    AND prod.product_publish = '1' 
                    AND prod.product_manufacturer_id != 0 " . $adv_query . " order by " . $order;

        $db->setQuery($queryToSelectCategoryManufacturers);

        return $db->loadObjectList();
    }

    public function getCount(int $publish = 0) 
    {
        $select = ['COUNT(`manufacturer_id`) as count'];
        $where = $publish ? ["manufacturer_publish = '1'"] : [];
        return $this->select($select, $where, '', false);
    }
    
    public function getAllManufacturers(int $publish = 0, string $order = 'ordering', string $dir = 'asc', int $limitstart = 0, int $limit = 0): array
    {
		$lang = JSFactory::getLang();
        $shopConfig = JSFactory::getConfig();
        $orderby = 'ordering'; 

        if ($order == 'id') { $orderby = 'manufacturer_id'; }
        if ($order == 'name') { $orderby = 'name'; }

        $select = ['manufacturer_id', 'manufacturer_url', 'manufacturer_logo', 'manufacturer_publish', '`' . $lang->get('name') . '` as name', '`' . $lang->get('description') . '` as description', '`' . $lang->get('short_description') . '` as short_description'];
        $where = $publish ? ["manufacturer_publish = 1"] : [];
        $listOfManufacturers = $this->select($select, $where, 'ORDER BY ' . $orderby . ' ' . $dir, true, $limit, $limitstart);
        
		foreach($listOfManufacturers as $key => $value) {
            $listOfManufacturers[$key]->manufacturer_logo = $listOfManufacturers[$key]->manufacturer_logo ?: $shopConfig->path_to_category_no_manuf;
            $listOfManufacturers[$key]->link = SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view&manufacturer_id=' . $listOfManufacturers[$key]->manufacturer_id);
        }		

		return $listOfManufacturers;
	}
}