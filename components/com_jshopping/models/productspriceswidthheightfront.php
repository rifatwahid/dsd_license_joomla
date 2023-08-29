<?php

class JshoppingModelProductsPricesWidthHeightFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_prices_width_height';

    public function getPricePer($productId, $width, $height) 
    {
        $db_width = $this->getMinWidthPer($productId, $width);
        $db_height = $this->getMinHeightPer($productId, $height);

        if (empty($db_width) || empty($db_height)) {
            return false;
        }

        return $this->getDbPricePer($productId, $db_width, $db_height);
    }

    public function getMinWidthPer($productId, $width) 
    {
        if (isset($productId)) {
            $db = \JFactory::getDBO();
            $query = "SELECT min(width) FROM `#__jshopping_products_prices_width_height` WHERE `product_id` = " . intval($productId) . " and `width` >= " . floatval($width);
            $db->setQuery($query);

            return $db->loadResult();
        }

        return false;
    }

    public function getMinHeightPer($productId, $height) 
    {
        if (isset($productId)) {
            $db = \JFactory::getDBO();
            $query = "SELECT min(height) FROM `#__jshopping_products_prices_width_height` WHERE `product_id` = " . intval($productId) . " and `height` >= " . floatval($height);
            $db->setQuery($query);

            return $db->loadResult();
        }

        return false;
    }

    public function getDbPricePer($productId, $width, $height) 
    {
        if (isset($productId)) {
            $db = \JFactory::getDBO();
            $query = "SELECT `price` FROM `#__jshopping_products_prices_width_height` WHERE `product_id` = " . intval($productId) . " AND `width` = " . floatval($width) . " AND `height` =  " . floatval($height);
            $db->setQuery($query);

            return $db->loadResult();
        }
    }
}
