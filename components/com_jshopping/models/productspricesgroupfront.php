<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsPricesGroupFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_prices_group';

    public function getAll(): array
    {
        $result = $this->select(['*']);
        return $result ?: [];
    }

    public function getByProductAndGroupIds($productId, $groupId)
    {
        if (!empty($productId)) {
            $result = $this->select(['*'], [
                'product_id = ' . $productId,
                'group_id = ' . $groupId
            ], '', false);
    
            return $result ?: null;
        }
    }

    public function getByProductsAndGroupIds($productsIds, $userGroupId): array
    {
        $result = [];

        if (!empty($productsIds)) {
            $ids = implode(',', $productsIds);

            $db = \JFactory::getDBO();
            $query = 'SELECT * FROM `' . static::TABLE_NAME . '` WHERE `product_id` IN (' . $ids . ') AND `group_id` = ' . $userGroupId;
            $db->setQuery($query);
            $result = $db->loadObjectList('product_id') ?: [];
        }

        return $result;
    }

    public function updateProdPricesToGroupPrices($products, $userGroupId)
    {
        if (!empty($products)) {
            $productsIds = getListSpecifiedAttrsFromArray($products, 'product_id');
            $productGroupPrices = $this->getByProductsAndGroupIds($productsIds, $userGroupId);

            if (!empty($productGroupPrices)) {
                foreach ($products as &$product) {
                    if (isset($productGroupPrices[$product->product_id])) {
                        $groupPrices = $productGroupPrices[$product->product_id];

                        $product->min_price = $groupPrices->price;
                        $product->product_price = $groupPrices->price;
                        $product->product_old_price = $groupPrices->old_price;
						$product->preview_total_price = $groupPrices->price;
                    }
                }
            }
        }

        return $products;
    }

    // public function updateProductPriceByUserGroupPrice(&$product,$front = true)
    // {
    //     $userShop = JSFactory::getUserShop();
    //     $groupPriceData = $this->getByProductAndGroupIds($product->product_id, (int)$userShop->usergroup_id);

	// 	if (!empty($groupPriceData)) {
	// 		$price = $groupPriceData->price;	

    //        // $product->isUsePriceFromUserGroup = true;
    //         $product->product_price_calculate = $price;
    //         if ($front) $product->product_price = $price; 
	// 		$product->product_is_add_price = $groupPriceData->product_is_add_price;
	// 	}
    // }

    public function updateProductPriceByUserGroupPrice(&$product,$front = true)
    {
        $userShop = JSFactory::getUserShop();
        $groupPriceData = $this->getByProductAndGroupIds($product->getProductId(), (int)$userShop->usergroup_id);

		if (!empty($groupPriceData)) {
           // $product->isUsePriceFromUserGroup = true;
            $product->product_price_calculate = $groupPriceData->price;
            $product->product_is_add_price = $groupPriceData->product_is_add_price;

            if (!empty($product->attribute_active_data->ext_data)) {
                $extProduct = $product->attribute_active_data->ext_data;
                $extProduct->product_price = $groupPriceData->price;
                $extProduct->product_old_price = $groupPriceData->old_price;
                $extProduct->product_is_add_price = $groupPriceData->product_is_add_price;
                $extProduct->add_price_unit_id = $groupPriceData->add_price_unit_id;
                $extProduct->product_is_add_price = $groupPriceData->product_is_add_price;
                $product->attribute_active_data->old_price = $groupPriceData->old_price;

                $product->attribute_active_data->price = $groupPriceData->price;
            }

            if ($front) {
                $product->product_price = $groupPriceData->price; 
            }
		}
    }

    public function recalcAllProductsPricesGroup()
    {
        $allProductsPricesGroups = $this->getAll();

        if (!empty($allProductsPricesGroups)) {
            JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
            $modelOfProductsFront = JSFactory::getModel('ProductsFront');
            $db = \JFactory::getDBO();
        
            foreach($allProductsPricesGroups as $priceGroup) {
                $calculatedPrice = $modelOfProductsFront->calculateProductDataByProductId($priceGroup->product_id, 1, $priceGroup->price)['calculatedPrice'];
                $sql = "UPDATE " . static::TABLE_NAME . " SET `total_calculated_price_without_tax` = {$calculatedPrice}  WHERE `id` = {$priceGroup->id};";
                $db->setQuery($sql)->execute();
            }
        }

        return true;
    }
}