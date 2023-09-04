<?php 

class ProductAttrs
{
	const TABLE_NAME = 'jshopping_products_attr';

	/**
	*	@return array
	*/
	public static function getListOfColumnsNames()
	{
		$db = \JFactory::getDBO();
		
		$sqlToGetAllAttrColumnsFromProdAttr = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` 
			WHERE `TABLE_SCHEMA` = '" . JFactory::getConfig()->get('db') . "' 
				AND `TABLE_NAME` = '" . $db->getPrefix() . static::TABLE_NAME . "' 
				AND `COLUMN_NAME` LIKE 'attr_%' 
				AND DATA_TYPE = 'int'";

		$db->setQuery($sqlToGetAllAttrColumnsFromProdAttr);

		return $db->loadAssocList('', 'COLUMN_NAME') ?: [];
	}

	/**
	*	@return array
	*/
	public static function getValues($productId = 0)
	{
		$listOfAllAttrColumns = static::getListOfColumnsNames();

		if ( !empty($listOfAllAttrColumns) ) {
			array_unshift($listOfAllAttrColumns, 'product_id');

			$db = \JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select( $db->qn($listOfAllAttrColumns) )
				  ->from( $db->qn('#__' . static::TABLE_NAME) );

			if ( $productId != 0 ) {
				$query->where($db->qn('product_id') . ' = ' . $db->escape($productId));
			}

			$db->setQuery($query);

			return $db->loadObjectList() ?: [];
		}

		return [];
	}

	public static function addSortValForProdsAttrValues()
    {
        $arrWithProductsAttrsValuesIds = ProductAttrs::getValues();

        $db = \JFactory::getDBO();
        
        $arrWithData = [];
        $columnsNames = [
            'product_id',
            'attr_val_id',
            'frontend_sorting'
        ];

        if ( !empty($arrWithProductsAttrsValuesIds) ) {
            foreach ($arrWithProductsAttrsValuesIds as $key => $objWithProdAttrValuesId) {
                foreach($objWithProdAttrValuesId as $columnName => $attrValueId) {
                    if ( $columnName != 'product_id' && 1 <= $attrValueId ) {
                        $arrWithData[] = $db->q($objWithProdAttrValuesId->product_id) . ', ' . $db->q($attrValueId)  . ', 1';
                    }
                }
			}
			
			if (!empty($arrWithData)) {
				$query = $db->getQuery(true);
				$query->insert($db->qn('#__jshopping_sort_val_attrs'));
				$query->columns($columnsNames);
				$query->values($arrWithData);
				$db->setQuery($query);

				return $db->execute(); 
			}
        } 

        return false;      
    }

    public static function clearSortValAttrsTable()
    {
        $db = \JFactory::getDBO();
        $queryClearTable = 'delete from `#__jshopping_sort_val_attrs`';
        $db->setQuery($queryClearTable);

        return $db->execute($queryClearTable);
    }
	
}