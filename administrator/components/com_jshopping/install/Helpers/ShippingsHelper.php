<?php 

class ShippingsHelper
{
	const TABLE_NAME = 'jshopping_shipping_method_price';
	
	public static function getListOfColumnsNames()
	{
		$db = \JFactory::getDBO();
		
		$sqlToGetAllAttrColumnsFromProdAttr = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` 
			WHERE `TABLE_SCHEMA` = '" . JFactory::getConfig()->get('db') . "' 
				AND `TABLE_NAME` = '" . $db->getPrefix() . "jshopping_shipping_method'";

		$db->setQuery($sqlToGetAllAttrColumnsFromProdAttr);
		return $db->loadAssocList('', 'COLUMN_NAME') ?: [];
	}

	public static function getValues($productId = 0)
	{
		$listOfAllAttrColumns = static::getListOfColumnsNames();

		if ( !empty($listOfAllAttrColumns) ) {
			
			$db = \JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select( $db->qn($listOfAllAttrColumns) )
				  ->from( $db->qn('#__jshopping_shipping_method') );

			$db->setQuery($query);

			return $db->loadObjectList() ?: [];
		}

		return [];
	}
	
	public static function addTableField(){
		$db = \JFactory::getDBO();			
		$listOfAllAttrColumns = static::getListOfColumnsNames();
		$arrWithProductsAttrsValuesIds = static::getValues();
		
		foreach($listOfAllAttrColumns as $column){
			if($column && $column != 'shipping_id' && $column != 'params' && !DbHelper::isTableColumnExists($db->getPrefix() . 'jshopping_shipping_method_price', $column)){
				
				$query = "SELECT `DATA_TYPE` FROM `INFORMATION_SCHEMA`.`COLUMNS`
					WHERE 
						`TABLE_SCHEMA` = '" . JFactory::getConfig()->get('db') . "' 
						AND `TABLE_NAME` = '" . $db->getPrefix() . "jshopping_shipping_method'
						AND `COLUMN_NAME` = '" . $column."'";
				$db->setQuery($query);
				$type = $db->loadResult();
				if($type == 'varchar') $tp = 'VARCHAR(255)';
				elseif($type == 'tinyint') $tp = 'tinyint(1)';
				elseif($type == 'int') $tp = 'int(11)';
				else $tp = $type;
				DbHelper::addColumnToTableIfNotExist($db->getPrefix() . 'jshopping_shipping_method_price', $column, $tp);
				
				$query = "SELECT `shipping_id`,`".$column."` FROM `#__jshopping_shipping_method` WHERE 1";
				$db->setQuery($query);
				
				$valsList = $db->loadObjectList();
				
				if(!empty($valsList)){
					foreach($valsList as $val){
						$query = "UPDATE `#__jshopping_shipping_method_price` SET `".$column."`='".$val->$column."'  WHERE `shipping_method_id`=".$val->shipping_id;
						$db->setQuery($query);
						$db->execute();
					}
				}
				
			}
		}
	}
}