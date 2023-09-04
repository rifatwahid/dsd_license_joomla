<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

class JshoppingModelProductShippings extends JModelLegacy
{
    const TABLE_NAME = '#__jshopping_products_shipping';

    public function saveShippingByProdId(int $productId, array $shippings, $defaultPrice = -1, $defaultPricePack = -1): bool
    {
        $result = false;
        $db = Factory::getDbo();

        if (!empty($shippings['prMethods'])) {
            foreach ($shippings['prMethods'] as $shPrMethodId => $isPublished) {
                $price = $shippings['defaultPrices'][$shPrMethodId] ?? $defaultPrice;
                $pricePack = $shippings['defaultPricesPack'][$shPrMethodId] ?? $defaultPricePack;

                $query = 'INSERT INTO ' . $db->qn(self::TABLE_NAME) . '(`product_id`, `sh_pr_method_id`, `published`, `price`, `price_pack`) VALUES(
                    ' . $db->escape($productId) . ',
                    ' . $db->escape($shPrMethodId) . ', 
                    ' . $db->escape($isPublished) . ',
                    ' . $db->escape($price) . ', 
                    ' . $db->escape($pricePack) . ')';

                $db->setQuery($query);
                $db->execute();
            }

            $result = true;
        }
    
        return $result;
    }

    public function deleteAllByProductId(int $productId): bool
    {
        $db = Factory::getDbo();
        $query = 'DELETE FROM ' . $db->qn(self::TABLE_NAME) . ' WHERE `product_id` = ' . $db->escape($productId);
        $db->setQuery($query);
        $isSuccess = (bool)$db->execute();

        return $isSuccess;
    }

    public function switchPublishByShMethodsAndProductId(int $productId, array $shippingsId, bool $isPublish = false): bool
    {
        $db = Factory::getDbo();
        $query = 'UPDATE ' . $db->qn(self::TABLE_NAME) . ' 
            SET `published` = ' . (int)$isPublish . ' 
            WHERE `sh_pr_method_id` IN(' . implode(',', $shippingsId) . ') AND 
                `product_id` = ' . $db->escape($productId);
        $db->setQuery($query);
        $isSuccess = (bool)$db->execute();

        return $isSuccess;
    }

    public function deleteByShMethodsAndProductId(int $productId, array $shippingsId): bool
    {
        $db = Factory::getDbo();
        $query = 'DELETE FROM ' . $db->qn(self::TABLE_NAME) . ' WHERE 
            `sh_pr_method_id` IN(' . implode(',', $shippingsId) . ') AND 
            `product_id` = ' . $db->escape($productId);
        $db->setQuery($query);
        $isSuccess = (bool)$db->execute();

        return $isSuccess;
    }

    public function saveShippingProducts($sh_pr_method_id){
        $db = Factory::getDbo();
        $_products = JSFactory::getModel('products');
        $products = $_products->getAllProducts([]);
        $insert = '';
        foreach($products as $val){
            if(strlen($insert) > 0) $insert .= ',';
            $insert .= '('. $db->escape($val->product_id) . ', ' . $db->escape($sh_pr_method_id) . ', 1, -1, -1)';
        }
        if(strlen($insert) > 0){
            $query = 'INSERT INTO ' . $db->qn(self::TABLE_NAME) . '(`product_id`, `sh_pr_method_id`, `published`, `price`, `price_pack`) VALUES'.$insert;
            $db->setQuery($query);
            $db->execute();
        }
    }
}