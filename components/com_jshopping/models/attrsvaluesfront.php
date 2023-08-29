<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelAttrsValuesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_attr_values';

    public function getByValueId(string $attrValId, array $columnsToGet = ['*'])
    {
        $result = null;

        if (!empty($columnsToGet)) {
            $stringOfSearchColumns = implode(', ', $columnsToGet);

            $select = [$stringOfSearchColumns];
            $where = ['`value_id` = \'' . $attrValId . '\''];
            $result = $this->select($select, $where)['0'] ?: null;
        }
        
        return $result;
    }

    /**
    * get All Atribute value
    * @param $resulttype (0 - ObjectList, 1 - array {id->name}, 2 - array(id->object) )
    * 
    * @param mixed $resulttype
    */
    public function getAllAttributeValues($resulttype = 0, $where = null): array
    {
        $lang = JSFactory::getLang();
        $select = ['value_id', 'image', '`' . $lang->get('name') . '` as name', 'attr_id', 'value_ordering'];				
        $orderBy = 'ORDER BY value_ordering, value_id';
		
		if (!empty($where)){
			$db = JFactory::getDbo();
            $stringOfSearchColumns = implode(', ', $select);
            $sqlWhere = '';

            if (is_array($where) && !empty($where)) {
                $sqlWhere = ' WHERE ';
                $sqlWhere .= implode(' AND ', $where);
            }elseif(strlen($where) > 0){
				$sqlWhere = ' WHERE '.$where;
			}
			$sqlQuery = "SELECT {$stringOfSearchColumns} FROM " . static::TABLE_NAME . " {$sqlWhere} {$orderBy}";
            $db->setQuery($sqlQuery);
			$attribs = $db->loadObjectList();
		}else{
			$attribs = $this->select($select, [], $orderBy);
		}
        if ($resulttype == 2 || $resulttype == 1) {
            $rows = [];

            foreach($attribs as $v) {
                $value = ($resulttype == 2) ? $v : $v->name;
                $rows[$v->value_id] = $value;    
            }

            return $rows;
        }
        
        return $attribs;
    }

    public function getAllValues(int $attr_id): array
    {
        $lang = JSFactory::getLang();
        $select = ['value_id', 'image', '`' . $lang->get('name') . '`', 'value_ordering', 'attr_id'];
        $where = ['`attr_id` = \'' . $attr_id . '\''];
        $afterWhere = 'ORDER BY `value_ordering`, `value_id`';

        return $this->select($select, $where, $afterWhere);
    } 
	   
	public function getAllAttributeValuesByProductID($resulttype = 0, $product_id = 0): array
    {	
		$db = \JFactory::getDBO();
        $lang = JSFactory::getLang();        
        $query = "
		SELECT *
		FROM `#__jshopping_products_attr`
		WHERE product_id=".(int)$product_id;
        $db->setQuery($query);
		$products=$db->loadObjectList();		
		$attrs="";
		foreach ($products as $id=>$product)
		foreach ($product as  $key=>$value){
			if (strpos($key,"ttr_")==1){
				$attr=substr($key,5,strlen($key)-5);
				if (is_numeric($attr))
				{
					if ($attrs=="") $attrs=" "; else $attrs.=" OR ";
					$attrs.=" (attr_id=".$attr." && value_id=".$value.") ";
				}
			}
		}
		return $this->getAllAttributeValues($resulttype,$attrs);
	}
}