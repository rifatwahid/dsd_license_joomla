<?php 

class JshoppingModelProductAttrs extends JModelLegacy
{
	const TABLE_NAME = '#__jshopping_products_attr';

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

	public function getLowStock($productsId = 0, $notifyStatus = 1, $getWithNames = 1)
	{
		$result = [];

		$db = \JFactory::getDBO();
		$querySelectItemsWhereAttrsLowStock = $db->getQuery(true);

		$querySelectItemsWhereAttrsLowStock->select('*')
											->from( $db->qn('#__jshopping_products_attr') )
											->where( $db->qn('count') . ' <= ' . $db->qn('low_stock_attr_notify_number') );

		if ( $notifyStatus != '' ) {
			$querySelectItemsWhereAttrsLowStock->where( $db->qn('low_stock_attr_notify_status') . ' = ' . $db->escape($notifyStatus) );
		}

		if ( !empty($productsId) ) {

			if ( is_array($productsId) ) {
				$querySelectItemsWhereAttrsLowStock->where( $db->qn('product_id') . ' IN (' . implode(',', $productsId) . ')' );
			} else {
				$querySelectItemsWhereAttrsLowStock->where( $db->qn('product_id') . ' = ' . $db->escape($productsId) );
			}

		}

		$querySelectItemsWhereAttrsLowStock->order( $db->qn('low_stock_attr_notify_number') );

		$db->setQuery($querySelectItemsWhereAttrsLowStock);

		$res = $db->loadObjectList();

		if ( !empty($res) ) {
			
			foreach($res as $key => $item) {
				$result[$item->product_id][] = $item;
			}

		}

		if ( $getWithNames == 1 ) {
			$this->getNamesForAttrs($result);
		}
	
		return $result;
	}

	protected function getNamesForAttrs(&$arrayWithAttrs = [])
	{
		if ( !empty($arrayWithAttrs) ) {
			$db = \JFactory::getDBO();
			$this->createObjAttrWithAttrValIds($arrayWithAttrs);

			foreach($arrayWithAttrs as $productId => $array) {
				foreach($array as $key => $obj) {
					if ( !empty($obj->attrValIds) ) {
						$res = [];
						$strWithValuesIds = implode(',', $obj->attrValIds);
						$query = $db->getQuery(true);

						$query->select('*')
							  ->from('#__jshopping_attr_values')
							  ->where( $db->qn('value_id') . ' IN (' . $strWithValuesIds . ')' );

						$db->setQuery($query);
						$res = $db->loadAssocList('value_id');

						if ( !empty($res) && !empty(reset($res)) ) {

							// Delete all elements except `name_`
							foreach( $res as $valueId => $arr ) {

								foreach($arr as $key => $val) {
									if ( preg_match('~^name_~', $key) != 1 ) {
										unset( $res[$valueId][$key] );
									}
								}

							}

							$obj->attrsNames = $res;
						}
					}
				}
			}

		}

	}

	protected function createObjAttrWithAttrValIds(&$arrayWithAttrs = []) 
	{
		if ( !empty($arrayWithAttrs) ) {

			// 1 lvl
			foreach($arrayWithAttrs as $productId => $array) {

				// 2 lvl
				foreach($array as $key => $objAttr) {

					// Obj attrs
					foreach($objAttr as $attrName => $attrValue) {

						if ( (preg_match('~^attr_[0-9]+$~', $attrName) == 1) && !empty($attrValue) ) {
							$arrayWithAttrs[$productId][$key]->attrValIds[] = $attrValue;
						}

					}
				}
			}
		}
	
	}
	
	public function setExtAttributeForProductID($product_id,$val){
		$db = \JFactory::getDBO();
		$query = "update #__jshopping_products_attr set ext_attribute_product_id=".$val." where product_id='".$product_id."'";
		$db->setQuery($query);
		$db->execute();
	}
	
	public function deleteAttributes($product_id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_products_attr` WHERE `product_id` = " . (int)$product_id;
		$db->setQuery($query);
		$db->execute();
	}
	
	public function getProductsAttributes($product_id){
		$db = \JFactory::getDBO();		 
		$query = "SELECT * FROM `#__jshopping_products_attr` WHERE `product_id` = " . (int)$product_id;
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	
	public function getProductAttr($product_id, $product_attr_id, $isUpdateFromSubProduct = false)
	{
		if (!empty($product_attr_id)) {  
			$subProduct = JSFactory::getTable('product', 'jshop');          
            $product_attr = JSFactory::getTable('productAttribut', 'jshop');
			$product_attr->load($product_attr_id);

			if (!$product_attr->ext_attribute_product_id) {
                $subProduct->parent_id = $product_attr->product_id;
				$subProduct->store();
				
                $product_attr->ext_attribute_product_id = $subProduct->product_id;
                $product_attr->store();
				$new=1;
			} else {
				$subProduct->load($product_attr->ext_attribute_product_id);
				$new=0;
			}

			$product_id = $subProduct->product_id;
			if ($isUpdateFromSubProduct) {
				$product_attr->product_id = $subProduct->parent_id;
				$product_attr->price = $subProduct->product_price;
				$product_attr->price = $subProduct->min_price;
				$product_attr->count = $subProduct->product_quantity;
				$product_attr->unlimited = $subProduct->unlimited;
				$product_attr->low_stock_attr_notify_status = $subProduct->low_stock_notify_status;
				$product_attr->low_stock_attr_notify_number = $subProduct->low_stock_number;
				$product_attr->ean = $subProduct->product_ean;
				$product_attr->weight = $subProduct->product_weight;
				$product_attr->expiration_date = $subProduct->expiration_date;
				$product_attr->production_time = $subProduct->production_time;
				$product_attr->weight_volume_units = $subProduct->weight_volume_units;
				$product_attr->old_price = $subProduct->product_old_price;
				$product_attr->add_price_unit_id = $subProduct->add_price_unit_id;

				$product_attr->product_price_type = $subProduct->product_price_type;
				$product_attr->qtydiscount = $subProduct->qtydiscount;
				$product_attr->product_packing_type = $subProduct->product_packing_type;
				$product_attr->factory = $subProduct->factory;
				$product_attr->storage = $subProduct->storage;
				$product_attr->product_tax_id = $subProduct->product_tax_id;
				$product_attr->product_manufacturer_id = $subProduct->product_manufacturer_id;
				$product_attr->delivery_times_id = $subProduct->delivery_times_id;
				$product_attr->label_id = $subProduct->label_id;
				$product_attr->quantity_select = $subProduct->quantity_select;
				$product_attr->max_count_product = $subProduct->max_count_product;
				$product_attr->min_count_product = $subProduct->min_count_product;
				$product_attr->basic_price_unit_id = $subProduct->basic_price_unit_id;
				$product_attr->equal_steps = $subProduct->equal_steps;
				$product_attr->buy_price = $subProduct->product_buy_price;

				$product_attr->store();
			} else {
				$subProduct->parent_id = $product_attr->product_id;
				if ($new) {
				$subProduct->product_price = $product_attr->price;
				$subProduct->min_price = $product_attr->price;
				$subProduct->product_quantity = $product_attr->count;
				$subProduct->unlimited = $product_attr->unlimited;
				$subProduct->low_stock_notify_status = $product_attr->low_stock_attr_notify_status;
				$subProduct->low_stock_number = $product_attr->low_stock_attr_notify_number;
				$subProduct->product_ean = $product_attr->ean;
				$subProduct->product_weight = $product_attr->weight;
				$subProduct->expiration_date = $product_attr->expiration_date;
				$subProduct->production_time = $product_attr->production_time;
				$subProduct->weight_volume_units = $product_attr->weight_volume_units;
				$subProduct->product_old_price = $product_attr->old_price;
				$subProduct->add_price_unit_id = $product_attr->add_price_unit_id;

				$subProduct->product_price_type = $product_attr->product_price_type;
				$subProduct->qtydiscount = $product_attr->qtydiscount;
				$subProduct->product_packing_type = $product_attr->product_packing_type;
				$subProduct->factory = $product_attr->factory;
				$subProduct->storage = $product_attr->storage;
				$subProduct->product_tax_id = $product_attr->product_tax_id;
				$subProduct->product_manufacturer_id = $product_attr->product_manufacturer_id;
				$subProduct->delivery_times_id = $product_attr->delivery_times_id;
				$subProduct->label_id = $product_attr->label_id;
				$subProduct->quantity_select = $product_attr->quantity_select;
				$subProduct->max_count_product = $product_attr->max_count_product;
				$subProduct->min_count_product = $product_attr->min_count_product;
				$subProduct->min_count_product = $product_attr->min_count_product;
				$subProduct->basic_price_unit_id = $product_attr->basic_price_unit_id;
				$subProduct->equal_steps = $product_attr->equal_steps;
				$subProduct->product_buy_price = $product_attr->buy_price;
				$subProduct->is_use_additional_media = $product_attr->is_use_additional_media;
				}
				$subProduct->store();	
			}     			
		}       
		 
		return $product_id;
	}

	public function getByAttrsIdsAndValuesIds($attrId, array $attrsValuesIds = []): array
	{
		$result = [];

		if (!empty($attrId) && !empty($attrsValuesIds)) {
			$db = \JFactory::getDBO();

			$ids = implode(', ', $attrsValuesIds);
			$columnAttrName = 'attr_' . $attrId;
			$query = 'SELECT * FROM ' . $db->qn(static::TABLE_NAME) . ' WHERE ' . $db->qn($columnAttrName) . ' IN(' . $ids . ')';

			try {
				$db->setQuery($query);
				$result = $db->loadObjectList();
			} catch (\Exception $e) {}
		}

		return $result;
	}

	public function deleteByProductsAttrsIds(array $attrsIds = []): bool
	{
		$result = true;

		if (!empty($attrsIds)) {
			$ids = implode(', ', $attrsIds);

			$db = \JFactory::getDBO();
			$query = 'DELETE FROM ' . $db->qn(static::TABLE_NAME) . ' WHERE `product_attr_id` IN(' . $ids . ')';

			try {
				$db->setQuery($query);
				$result = $db->execute();
			} catch (\Exception $e) {
				$result = false;
			}
		}

		return $result;
	}

	public function deleteAttrsWithProductByAttrsIdsAndValuesIds($attrId, array $attrsValuesIds): bool
	{
		$result = true;

		if (!empty($attrId) && !empty($attrsValuesIds)) {
			$attrsData = $this->getByAttrsIdsAndValuesIds($attrId, $attrsValuesIds);

			$expProductsIds = [];
			$productsAttrsIds = [];
			if (!empty($attrsData)) {
				$modelOfProducts = JSFactory::getModel('Products');

				foreach ($attrsData as $attrData) {
					if (!empty($attrData->product_attr_id)) {
						$productsAttrsIds[] = $attrData->product_attr_id;
					}

					if (!empty($attrData->ext_attribute_product_id)) {
						$expProductsIds[] = $attrData->ext_attribute_product_id;
					}
				}

				$this->deleteByProductsAttrsIds($productsAttrsIds);
				$modelOfProducts->deleteProductsFromTablesByIds($expProductsIds);
			}
		}

		return $result;
	}

	public function getByAttrsIds(array $attrsIds = []): array
	{
		$result = [];

		if (!empty($attrsIds)) {
			$db = \JFactory::getDBO();
			$sqlWhere = [];

			foreach ($attrsIds as $attrId) {
				if (!empty($attrId)) {
					$sqlWhere[] = '`attr_' . $db->escape($attrId) . '` != 0';
				}
			}

			$sqlWhereExploded = implode(' OR ', $sqlWhere);
			$query = 'SELECT * FROM ' . $db->qn(static::TABLE_NAME) . ' WHERE ' . $sqlWhereExploded;

			try {
				$db->setQuery($query);
				$result = $db->loadObjectList();
			} catch (\Exception $e) {}
		}

		return $result;
	}

	public function deleteAttrsWithProductByAttrsIds(array $attrsIds = []): bool
	{
		$result = true;

		if (!empty($attrsIds)) {
			$attrsData = $this->getByAttrsIds($attrsIds);

			$expProductsIds = [];
			$productsAttrsIds = [];
			if (!empty($attrsData)) {

				foreach ($attrsData as $attrData) {
					if (!empty($attrData->product_attr_id)) {
						$productsAttrsIds[] = $attrData->product_attr_id;
					}

					if (!empty($attrData->ext_attribute_product_id)) {
						$expProductsIds[] = $attrData->ext_attribute_product_id;
					}
				}

				$this->deleteByProductsAttrsIds($productsAttrsIds);
				JSFactory::getModel('Products')->deleteProductsFromTablesByIds($expProductsIds);
			}
		}

		return $result;
	}
}