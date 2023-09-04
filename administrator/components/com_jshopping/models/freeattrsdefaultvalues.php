<?php 

class JshoppingModelFreeAttrsDefaultValues extends JModelLegacy
{
	const TABLE_NAME = '#__jshopping_free_attr_default_values';
	const ADDON_NAME = 'free_attributes_default_values';

	/**
	*	@param int $productId - product id	
	*	@param array $data - array width data to insert
	*
    *	$data['1'] <= id of free attr.
    *	$data['1']['defaultVal'] <= default value for free attr.
    *	$data['1']['isFixedVal'] <= is fixed value or not. if 1 - is set.
    *	$data['1']['showFreeAttrInput'] <= show or not input in front-end
    *	$data['1']['attr_activated'] <= activated free attr or not
    *
    *	@return array
    */
	public function setFreeAttrDataToTable($productId, array $data)
	{
		$this->deleteDataByProductId($productId);
		return $this->setFreeAttrDataToTableWithoutDelete($productId, $data);
	}

	public function setFreeAttrDataToTableWithoutDelete($productId, array $data)
	{
		$db = \JFactory::getDBO();
		$query = $db->getQuery(true);
		$columns = ['product_id', 'attr_id', 'default_value', 'min_value', 'max_value', 'is_fixed', 'attr_activated', 'showFreeAttrInput'];
		$arrayWithQueriesToInsert = $this->prepareArrayToSetFreeAttrInDb($productId, $data);

		if ( !empty($arrayWithQueriesToInsert) ) {
			$query
				->insert(static::TABLE_NAME)
				->columns($columns)
				->values($arrayWithQueriesToInsert);

			$db->setQuery($query);

			return $db->execute();			
		}
	}

	/**
	*	@param int $productId
	*	@return array
	*/
	public function getProductDefaultFreeAttributes($productId)
	{
        $db = \JFactory::getDBO();
        $query = 'SELECT * FROM `#__jshopping_free_attr_default_values` WHERE `product_id` = ' . $db->escape($productId);

        $db->setQuery($query);
        $arrWithResult = $db->loadAssocList();

        foreach($arrWithResult as $key => &$arr) {
        	unset($arr['id']);
        }

        return $arrWithResult;
    }

    /**
    *	@param int $productId - product identifier to which the free attributes will belong
    *	@param mixed $arrWithDataForTable - product id number OR array.
    */
    public function setDefaultFreeAttributesForProduct($productId, $arrWithDataForTable)
    {
        if ( !empty($arrWithDataForTable) && is_numeric($productId) ) {

            $db = \JFactory::getDBO();
            
            foreach($arrWithDataForTable as $key => $arrWithData) {
            	$queryBuilder = $db->getQuery(true);
            	
		    	$arrWithData = array_filter($arrWithData, function($val) {
		    		return $val !== '' && !is_null($val);
		    	});

            	$arrWithData['product_id'] = $productId;
            	$arrWithColumnsNamesToInsert = array_keys($arrWithData);
            	$strWithColumnsValuesToInsertSeparatedCommas = implode(',', array_values($arrWithData));

				$queryBuilder
            			->insert( $db->quoteName('#__jshopping_free_attr_default_values') )
            			->columns( $db->quoteName($arrWithColumnsNamesToInsert) )
            			->values($strWithColumnsValuesToInsertSeparatedCommas);   

            	$db->setQuery($queryBuilder);
            	$db->execute();
            }

        } 
        
        return false;
    }

	/**
	*	Prepare data for method `setFreeAttrDataToTable`
	*
	*	@param int $productId - product id
	*	@param array $data - array width data to insert
	*	
	*	@return array with queries
	*/
	protected function prepareArrayToSetFreeAttrInDb($productId, array $data)
	{
		$result = [];
		$db = \JFactory::getDBO();

		if ( !empty($data) ) {
			foreach($data as $key => $value) {
				$quote = $db->quote($productId) . ', ' . $db->quote($key) . ', ' . $db->quote($value['defaultVal']) . ', ' . $db->quote($value['minVal']) . ', ' . $db->quote($value['maxVal']) . ', ' . $db->quote($value['isFixedVal']) . ', ' . $db->quote($value['attr_activated']) . ', ' . $db->quote($value['showFreeAttrInput']);
		
				$result[] = $quote;					
			}			
		}

		unset($db);
		return $result;
	}

	/**
	*	@param int $productId 
	*	@return array
	*/
	public function getDataByProductId($productId, $sqlWhereCondition = '')
	{
		if ( !empty($productId) ) {
			$db = \JFactory::getDBO();
			$query = $db->getQuery(true);

			if ( !empty($sqlWhereCondition) ) {
				$sqlWhereCondition = ' AND ' . $sqlWhereCondition;
			}

			$query
				->select('*')
				->from($db->quoteName(self::TABLE_NAME))
				->where( 'product_id = ' .  $db->quote($productId) . $sqlWhereCondition);

			$db->setQuery($query);
			$queryResult = $db->loadAssocList();

			return $this->changeArrayKeyToAttrId($queryResult);			
		}

		return [];
		
	}

	protected function changeArrayKeyToAttrId(array $arr)
	{
		$result = [];

		foreach ($arr as $key => $value) {
			$result[$value['attr_id']] = $value;
		}

		return $result;
	}

	/**
	*	Delete row from const `TABLE_NAME` by product_id
	*	@param int $productId - product id to delete
	*	@param string $tableName - table name
	*
	*	@return array
	*/
	public function deleteDataByProductId($productId, $tableName = '')
	{
		if ( empty($tableName) ) {
			$tableName = static::TABLE_NAME;
		}

		$db = \JFactory::getDBO();
		$query = $db->getQuery(true);

		$query
			->delete($db->quoteName($tableName))
			->where($db->quoteName('product_id') . ' = ' . $db->quote($productId));
		$db->setQuery($query);
		
		return $db->execute();
	}

	/**
    *   Defining and return rows where attrs is activated and set default values.
    */
    public function definingTheAppropriateData($post)
    {
        if ( !empty($post['freeAttrDefVal']) ) {
            $intersectArr = $post['freeAttrDefVal'];
            // Define if is set default values.
            $result = [];
            foreach($intersectArr as $key => $arr) {
                if ( $arr['defaultVal'] !== '' || $arr['minVal'] !== ''  || $arr['maxVal'] !== '' ) {
                    $result[$key] = $arr;

                    if ( !empty($post['freeattribut']) && !empty($post['freeattribut'][$key]) ) {
                        $result[$key]['attr_activated'] = 1;                    
                    }
                } 
            }

            return $result;
        }     
    }	
    
}