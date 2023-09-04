<?php
/**
* @version      4.1.0 26.12.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelProductFieldValues extends JModelLegacy
{ 
	
    public function getList($field_id, $order = null, $orderDir = null, $filter = [])
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang(); 
        $ordering = 'ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $where = '';
		if ($filter['text_search']){
            $text_search = $filter['text_search'];
            $word = addcslashes($db->escape($text_search), "_%");
            $where =  " and (LOWER(`".$lang->get('name')."`) LIKE '%".$word."%' OR id LIKE '%".$word."%')";
        }
        $query = "SELECT id, `".$lang->get("name")."` as name, ordering, image FROM `#__jshopping_products_extra_field_values` where field_id='$field_id' ".$where." order by ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    
    public function getAllList($display = 0)
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang(); 
        $query = "SELECT id, `".$lang->get("name")."` as name, field_id, image FROM `#__jshopping_products_extra_field_values` order by ordering";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        if ($display==0){
            return $db->loadObjectList();
        }elseif($display==1){
            $rows = $db->loadObjectList();
            $list = array();
            foreach($rows as $k=>$row){
                $list[$row->id] = $row->name;
                unset($rows[$k]);    
            }
            return $list;
        }else{
            $rows = $db->loadObjectList();
            $list = array();
            foreach($rows as $k=>$row){
                $list[$row->field_id][$row->id] = $row->name;
                unset($rows[$k]);    
            }
            return $list;
        }
    }
	
    public function deleteProductfieldvalues($cid)
    {
        $text = [];
		$modelOfDbDelete = JSFactory::getModel('dbdelete');

        if (!empty($cid)) {
            foreach ($cid as $value) {		
                $value = intval($value);
                $isDeleted = $modelOfDbDelete->deleteItems('#__jshopping_products_extra_field_values', 'id', $value);
                
                if ($isDeleted) {
                    $text[] = JText::_('COM_SMARTSHOP_ITEM_DELETED');
                }
            }
        }
        
		return $text;
	}
}
