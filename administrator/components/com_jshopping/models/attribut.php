<?php
/**
* @version      4.8.0 04.06.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class JshoppingModelAttribut extends JModelLegacy 
{
    
    public function getNameAttribut($attr_id) 
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT `".$lang->get("name")."` as name FROM `#__jshopping_attr` WHERE attr_id = '".$db->escape($attr_id)."'";
        $db->setQuery($query);
        return $db->loadResult();
    }

    public function copyAttributes(array $arrWithAttrsIdsForCopy) 
    {

        if ( !empty($arrWithAttrsIdsForCopy) ) {
            $attrTable = JSFactory::getTable('Attribut');
            $db = \JFactory::getDBO();

            foreach($arrWithAttrsIdsForCopy as $key => $attrIdForCopy) {
                $isAttrFindedInTable = $attrTable->load($attrIdForCopy);              

                if ( $isAttrFindedInTable ) {
                    $attrTable->attr_id = null;
                    $isAttrSuccessStored = $attrTable->store();

                    if ( $isAttrSuccessStored ) {
                        $attrValueAdminModel = JSFactory::getModel('AttributValue');
                        $attrValueAdminModel->copyAttrValues($attrIdForCopy, $attrTable->attr_id);

                        $columnName = 'attr_' . $attrTable->attr_id;

                        if ( !isTableColumnExists($columnName, '#__jshopping_products_attr') ) {
                            $query = 'ALTER TABLE `#__jshopping_products_attr` ADD `' . $columnName . '` INT DEFAULT 0';
                            $db->setQuery($query);
                            $db->execute();                            
                        }                        
                    }
                }
            }

        }

    }    
    
    public function getAllAttributes($result = 0, $categorys = null, $order = null, $orderDir = null)
    {
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO(); 
        $ordering = "A.attr_ordering asc";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT A.attr_id, A.`".$lang->get("name")."` as name, A.attr_type, A.attr_ordering, A.independent, A.allcats, A.cats, G.`".$lang->get("name")."` as groupname
                  FROM `#__jshopping_attr` as A left join `#__jshopping_attr_groups` as G on A.`group`=G.id
                  ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $list = $db->loadObjectList();
                
        if (is_array($categorys) && count($categorys)){
            foreach($list as $k=>$v){
                if (!$v->allcats){
                    if ($v->cats!=""){
                        $cats = unserialize($v->cats);
                    }else{
                        $cats = array();
                    }
                    $enable = 0;
                    foreach($categorys as $cid){
                        if (in_array($cid, $cats)) $enable = 1;
                    }
                    if (!$enable){
                        unset($list[$k]);
                    }
                }
            } 
        }
        
        if ($result==0){
            return $list;
        }
        if ($result==1){
            $attributes_format1 = array();
            foreach($list as $v){
                $attributes_format1[$v->attr_id] = $v;
            }
            return $attributes_format1;
        }
        if ($result==2){
            $attributes_format2 = array();
            $attributes_format2['independent']= array();
            $attributes_format2['dependent']= array();
            foreach($list as $v){
                if ($v->independent) $key_dependent = "independent"; else $key_dependent = "dependent";
                $attributes_format2[$key_dependent][$v->attr_id] = $v;
            }
            return $attributes_format2;
        }
    }

    public function getExtendedWeight($product_id, $attribs)
    {
        $weight = 0;

        foreach($attribs as $aid => $vid){
            $weight += $this->getExtendedWeightValId($product_id, $vid);
        }

        return $weight;
    }

    protected function getExtendedWeightValId($product_id, $attr_value_id)
    {
        $db = \JFactory::getDBO();
        $query = 'select weight from #__jshopping_products_attr2 where product_id=' . (int)$product_id . ' and attr_value_id=' . (int)$attr_value_id;
        $db->setQuery($query);

        return $db->loadResult();
    }
	
	public function deleteAttr2ByAttr2_id($attr_id)
    {
        $db = \JFactory::getDBO();
        $db->setQuery('DELETE FROM `#__jshopping_products_attr2` WHERE `attr_id` = '. (int) $attr_id);
        $db->execute();        
    }
	
	public function getNextOrdering()
    {
        $db = \JFactory::getDBO();
        $query = "SELECT MAX(attr_ordering) AS attr_ordering FROM `#__jshopping_attr`";
		$db->setQuery($query);
		$row = $db->loadObject();
		return ($row->attr_ordering + 1);
    }
	
	private function getTableColumns()
    {
        $db = \JFactory::getDBO();
        return $db->getTableColumns('#__jshopping_products_attr');
    }
    
	public function addAttr($attr_id)
    {
        $db = \JFactory::getDBO();
		$column = $this->getTableColumns();
        if (!isset($column['attr_'.$attr_id])) {
			$query="ALTER TABLE `#__jshopping_products_attr` ADD `attr_".$attr_id."` INT( 11 ) NOT NULL";
			$db->setQuery($query);
			$db->execute();
		}		
		return $attr_id;
    }
	
	public function getAttr($attr_id)
    {
        $db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_attr_values` where attr_id='".$db->escape($attr_id)."'";
		$db->setQuery($query);
		return $db->loadObjectList();		
    }
	
	public function deleteAttr($attr_id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_attr` WHERE `attr_id` = '".$db->escape($attr_id)."'";
		$db->setQuery($query);
		$db->execute();
	}
	
	public function deleteColumn($attr_id){
		$db = \JFactory::getDBO();
		$query="ALTER TABLE `#__jshopping_products_attr` DROP `attr_".$attr_id."`";
		$db->setQuery($query);
		$db->execute();                
	}
	
	public function orderingChange($order,$cid,$number){
		$_ordering = JSFactory::getModel('ordering');
		switch ($order) {
			case 'up':
				$_ordering->orderingMoveUp('#__jshopping_attr',"attr_id","attr_ordering",$number,$cid);
				break;
			case 'down':
				$_ordering->orderingMoveDown('#__jshopping_attr',"attr_id","attr_ordering",$number,$cid);
		}	
	}
	
	public function getAllAttributesWithValues($result = 0, $categorys = null, $order = null, $orderDir = null){
		$rows = $this->getAllAttributes(0, null, $filter_order ?? [], $filter_order_Dir ?? '');
		$_attributesvalue = JSFactory::getModel("attributValue");
		foreach ($rows as $key => $value){
            $rows[$key]->values = splitValuesArrayObject( $_attributesvalue->getAllValues($rows[$key]->attr_id), 'name');
            $rows[$key]->count_values = count($_attributesvalue->getAllValues($rows[$key]->attr_id));
        }        
		return $rows;
	}
	
	public function getAttributesTypesSelect($selected_value){
		$types[] = JHTML::_('select.option', '1','Select','attr_type_id','attr_type');
        $types[] = JHTML::_('select.option', '2','Radio','attr_type_id','attr_type');
		$types[] = JHTML::_('select.option', '3','Hidden','attr_type_id','attr_type');
		$types[] = JHTML::_('select.option', '4','Checkbox','attr_type_id','attr_type');
		
        $codeOfHiddenAttrType = JSFactory::getConfig()->attrs_types_code['hidden'] ?? '';
        $type_attribut = JHTML::_('select.genericlist', $types, 'attr_type','class = "inputbox form-select" onchange="shopHelper.showAlertMessage(' . $codeOfHiddenAttrType . ', this.value, \'' . JText::_('COM_SMARTSHOP_ATTRS_WITH_TYPE_HIDDEN_WILL_DELETED_FROM_PRODS') . '\')" size = "1"','attr_type_id','attr_type', $selected_value);
		
		return $type_attribut;
	}
	
	public function allcatsSelect($selected_value){
		$all = array();
        $all[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_ALL'), 'id','value');
        $all[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_SELECTED'), 'id','value');		
		return JHTML::_('select.radiolist', $all, 'allcats','onclick="PFShowHideSelectCats()"','id','value', $selected_value);
	}
	
	public function removeAttrs($attrs_ids){
		$jshopConfig = JSFactory::getConfig();
		$_attributvalue = JSFactory::getModel('attributvalue');
        $text = '';

        $modelOfProductAttrs2 = JSFactory::getModel('productAttrs2');
        $modelOfProductAttrs2->deleteByAttrsIds($attrs_ids);
        
        $modelOfProductAttrs = JSFactory::getModel('ProductAttrs');
        $modelOfProductAttrs->deleteAttrsWithProductByAttrsIds($attrs_ids);

		foreach ($attrs_ids as $key => $value) {
            $value = intval($value);
			$this->deleteAttr($value);
            if ( isTableColumnExists('attr_' . $value, '#__jshopping_products_attr') ) {
				$this->deleteColumn($value);	                
            }            
			$attr_values = $_attributvalue->getValuesByAttrId($value);
            foreach ($attr_values as $attr_val){
                if (!$_attributvalue->isExistsAttrValueWithSameImageByValueId($attr_val->value_id)) {
                    @unlink($jshopConfig->image_attributes_path."/".$attr_val->image);
                }
            }
			$attr_values = $_attributvalue->deleteValuesByAttrId($value);
            $text .= JText::_('COM_SMARTSHOP_ATTRIBUT_DELETED').": ".$key." ";
		}
		return $text;
	}
}
