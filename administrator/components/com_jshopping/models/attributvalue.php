<?php
/**
* @version      4.7.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

use Joomla\CMS\Factory;

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class JshoppingModelAttributValue extends JModelLegacy 
{
    const TABLE_NAME = '#__jshopping_attr_values';
    
    public function getNameValue($value_id) 
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT `".$lang->get("name")."` as name FROM `#__jshopping_attr_values` WHERE value_id = '".$db->escape($value_id)."'";
        $db->setQuery($query);        
        return $db->loadResult();
    }

    public function getAllValues($attr_id, $order = null, $orderDir = null) 
    {
        $db = \JFactory::getDBO(); 
        $lang = JSFactory::getLang();
        $ordering = 'value_ordering, value_id';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT value_id, image, `".$lang->get("name")."` as name, attr_id, value_ordering FROM `#__jshopping_attr_values` where attr_id='".$attr_id."' ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    /**
    * get All Atribute value
    * @param $resulttype (0 - ObjectList, 1 - array {id->name}, 2 - array(id->object) )
    * 
    * @param mixed $resulttype
    */
    public function getAllAttributeValues($resulttype=0)
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT value_id, image, `".$lang->get("name")."` as name, attr_id, value_ordering FROM `#__jshopping_attr_values` ORDER BY value_ordering, value_id";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $attribs = $db->loadObjectList();

        if ($resulttype==2){
            $rows = array();
            foreach($attribs as $k=>$v){
                $rows[$v->value_id] = $v;    
            }
            return $rows;
        }elseif ($resulttype==1){
            $rows = array();
            foreach($attribs as $k=>$v){
                $rows[$v->value_id] = $v->name;    
            }
            return $rows;
        }else{
            return $attribs;
        }        
    }

    public function getValues($attr_id) 
    {
        $db = \JFactory::getDBO(); 
        $lang = JSFactory::getLang();
        $query = "SELECT * FROM `#__jshopping_attr_values` WHERE `attr_id` = " . $db->quote($attr_id);
        $db->setQuery($query);

        return $db->loadObjectList();
    } 

    /**
    *   @return object
    */
    public function getAttrValueByValId($valueId)
    {
        $db = \JFactory::getDBO(); 
        $lang = JSFactory::getLang();
        $query = "SELECT * FROM `#__jshopping_attr_values` WHERE `value_id` = " . $db->quote($valueId);

        $db->setQuery($query);

        return $db->loadObject();        
    }     

    /**
    *   @return array with objects
    */
    public function copyAttrValues($attrIdToCopy, $newAttrIdForCopyValues) 
    {
        $values = $this->getValues($attrIdToCopy);

        if ( !empty($values) ) {
            $db = \JFactory::getDBO();

            foreach($values as $key => $objWithAttrValues) {
                $objWithAttrValues->value_id = 0;
                $objWithAttrValues->attr_id = $newAttrIdForCopyValues;

                $db->insertObject('#__jshopping_attr_values', $objWithAttrValues, 'value_id');                
            }

        }
    }

    /**
    *   @return boolean
    */
    public function copyAttrValue($attrIdforWhichWantToCopy, $valueIdForCopy) 
    {
        $objWithAttrValue = $this->getAttrValueByValId($valueIdForCopy);

        if ( !empty($objWithAttrValue) ) {
            $db = \JFactory::getDBO();

            $objWithAttrValue->value_id = 0;
            $objWithAttrValue->attr_id = $attrIdforWhichWantToCopy;

            return $db->insertObject('#__jshopping_attr_values', $objWithAttrValue, 'value_id');
        }
    } 

    public function deleteValAttrsForSortTable($productId)
    {
        if ( !empty($productId) ) {
            $db = \JFactory::getDBO();
            $db->setQuery('DELETE FROM `#__jshopping_sort_val_attrs` WHERE `product_id` = ' . $db->quote($productId));

            return $db->execute();            
        }   
    }

    public function writeAttrsToSortTable($productId, $arrArrWithAttrs, $isDependentAttrs = 1)
    {

        if ( !empty($productId) && !empty($arrArrWithAttrs)) {
            $db = \JFactory::getDBO();
            $arrWithSqls = [];
            $arrWithDataToInsert = [];            

            if ( $isDependentAttrs == 1 ) {
                $arrWithAttrValuesIds = [];
                $sortNumb = 1;

                foreach($arrArrWithAttrs as  $key => $arr) {
                    foreach($arr as $key1 => $val) {
                        if ( $val != 0 ) {
                            $arrWithAttrValuesIds[$sortNumb] = $val; 
                            $sortNumb++;                   
                        }
                    }
                }

                foreach($arrWithAttrValuesIds as $sortingNumb => $attrValId) {
                    $arrWithDataToInsert[] = $db->escape($attrValId) . ', ' . $db->escape($productId) . ', ' . $db->escape($sortingNumb);
                } 
     
            } else {
                foreach($arrArrWithAttrs as $sortingNumb => $attrValId) {
                    $arrWithDataToInsert[] = $db->escape($attrValId) . ', ' . $db->escape($productId) . ', ' . $db->escape($sortingNumb + 1);                 
                }
            }

            $prodAttrs = JSFactory::getModel('ProductAttrs');       
            $arrayWithProdAttrsValIds = $prodAttrs::getValues($productId);            

            if ( !empty($arrayWithProdAttrsValIds) ) {
                foreach ( $arrayWithProdAttrsValIds as $arr => $obj ) {
                    foreach($obj as $key => $attrValId) {
                        if ( $key != 'product_id' && $attrValId > 0 && !in_array($attrValId, $arrWithAttrValuesIds)) {
                            $arrWithDataToInsert[] = $db->escape($attrValId) . ', ' . $db->escape($productId) . ', ' . $db->escape( count($arrWithDataToInsert) + 1 );
                            $arrWithAttrValuesIds[] = $attrValId;
                        }
                    }
                }                 
            }           

            if ( !empty($arrWithDataToInsert) ) {
                $tableName = '#__jshopping_sort_val_attrs';
                $columnsNames = [
                    'attr_val_id',
                    'product_id',
                    'frontend_sorting'
                ];

                $query = $db->getQuery(true);
                $query->insert( $db->qn($tableName) )
                      ->columns( $db->qn($columnsNames) )
                      ->values($arrWithDataToInsert);

                $db->setQuery($query);
                
                return $db->execute();
            }

        }

        return false;
    }          
	
	public function getNextOrdering($attr_id){
        $db = \JFactory::getDBO();
        $query = "SELECT MAX(value_ordering) AS value_ordering FROM `#__jshopping_attr_values` where attr_id='".$db->escape($attr_id)."'";
		$db->setQuery($query);
		$row = $db->loadObject();
		$ordering=$row->value_ordering + 1;
		return $ordering;
    }
	
	public function getValuesByAttrId($attr_id){
        $db = \JFactory::getDBO();
		$query = "select * from `#__jshopping_attr_values` where `attr_id` = '".$db->escape($attr_id)."' ";
		$db->setQuery($query);
		return $db->loadObjectList();
    }
	
	public function deleteValuesByAttrId($attr_id){
        $db = \JFactory::getDBO();
		$query = "delete from `#__jshopping_attr_values` where `attr_id` = '".$db->escape($attr_id)."' ";
        $db->setQuery($query);
        $db->execute();
    }
	
	public function getImage($id){
		$db = \JFactory::getDBO();
		$query = "SELECT image FROM `#__jshopping_attr_values` WHERE value_id = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$image = $db->loadResult();
		return $image;
	}
	
	public function deleteAttrValue($id): bool
    {
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_attr_values` WHERE `value_id` = '{$db->escape($id)}'";
        $db->setQuery($query);
        
		return $db->execute();		
	}
		
	public function orderingChange($order,$cid,$number){
		$_ordering = JSFactory::getModel('ordering');
		switch ($order) {
			case 'up':
				$_ordering->orderingMoveUp('#__jshopping_attr_values',"value_id","value_ordering",$number,$cid);
				break;
			case 'down':
				$_ordering->orderingMoveDown('#__jshopping_attr_values',"value_id","value_ordering",$number,$cid);
		}	
	}
    
    public function isExistsAttrValueWithSameImageByValueId(int $valueId): bool
    {
        $db = Factory::getDbo();
        $sql = 'SELECT COUNT(`value_id`) FROM ' . $db->qn(static::TABLE_NAME) . ' WHERE `image` LIKE (
            SELECT `image` FROM ' . $db->qn(static::TABLE_NAME) . ' AS `attr_value` WHERE `attr_value`.`value_id` = ' . $db->escape($valueId) . '
        ) AND `value_id` != ' . $db->escape($valueId);
        $db->setQuery($sql);
        $count = $db->loadResult() ?: 0;
        $isExists = ($count >= 1);

        return $isExists;
    }
    
    public function copyToSortTable(int $product_id, int $copy_product_id)
    {
        $db = Factory::getDbo();
		
        $query = 'DELETE FROM `#__jshopping_sort_val_attrs` WHERE `product_id`=' . $db->escape($product_id);
        $db->setQuery($query);
        $db->execute();
		
        $sql = 'SELECT * FROM `#__jshopping_sort_val_attrs` WHERE `product_id`=' . $db->escape($copy_product_id);
        $db->setQuery($sql);
        $list = $db->loadObjectList();
        
		$insert = '';
		if($list){
			foreach($list as $k => $val){
				if(strlen($insert) > 0) $insert .= ',';
				$insert .= "('".$product_id."', ".$val->attr_val_id.", ".$val->frontend_sorting.")";
			}
		
			$query = "INSERT INTO `#__jshopping_sort_val_attrs` (`product_id`, `attr_val_id`, `frontend_sorting`) VALUES ".$insert;
			$db->setQuery($query);
			$db->execute();
		}
		
	}
}
