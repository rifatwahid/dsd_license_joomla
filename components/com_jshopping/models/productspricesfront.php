<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsPricesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_prices';

    public function getSqlFunctionsResultByProdId(int $productId)
    {
        return $this->select([
            'count(*) as countprices',
            'SUM(price) AS sum_price',
            'MIN(price) AS min_price',
            'MAX(price) AS max_price',
            'MIN(discount) AS min_discount',
            'MAX(discount) AS max_discount',
            'MIN(start_discount) AS min_start_discount',
            'MAX(start_discount) AS max_start_discount',
        ], ['product_id = \'' . $productId . '\''], '', false);
    }

    public function getAddPrices(int $product_id, int $usergroup = 0, int $usergroup_prices = 0)
	{        
        $where = [
            "`product_id` = '{$product_id}'",
            "`usergroup_prices` = '{$usergroup_prices}'",
            "`usergroup` = '{$usergroup}'"
        ];

        return $this->select(['*'], $where, ' ORDER BY product_quantity_start ASC');
    }
	
	public function getAddPricesFront(int $product_id, int $usergroup = 0, int $usergroup_prices = 0)
	{   
		$db = \JFactory::getDBO();
		$unitsOfMeasures = JSFactory::getAllUnits();
        $select = [
            '*',
            '`discount` as `true_discount`',
            '`start_discount` as `discount`'
        ];
        $where = [
            "`product_id` = '{$product_id}'",
            "`usergroup_prices` = '{$usergroup_prices}'",
            "`usergroup` = '{$usergroup}'"
        ];
		
        $prices = $this->select($select, $where, ' ORDER BY product_quantity_start DESC');
		if(!empty($prices)){
			foreach($prices as $k=>$price){
				$query = "SELECT `add_price_unit_id` FROM `#__jshopping_products_prices_group` WHERE `product_id`=".$product_id." AND `group_id`=".(int)JSFactory::getUserShop()->usergroup_id;
				$db->setQuery($query);
				$prices[$k]->unit_id = $db->loadResult();
				if($prices[$k]->unit_id) $prices[$k]->unit_name= $unitsOfMeasures[$prices[$k]->unit_id]->name;
				
			}
		}
		
        \JFactory::getApplication()->triggerEvent('onAfterGetAddPricesFront', [$product_id, &$prices]);
		
		return $prices;
	}
}