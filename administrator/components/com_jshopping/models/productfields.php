<?php
/**
* @version      4.1.0 10.12.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelProductFields extends JModelLegacy
{ 
	
    public function getList($groupordering = 0, $order = null, $orderDir = null, $filter=array())
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $ordering = "F.ordering";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        if ($groupordering){
            $ordering = "G.ordering, ".$ordering;
        }        
        $where = '';
		$_where = array();		
		if (isset($filter['group']) && $filter['group']){
            $_where[] = " F.group = '".$db->escape($filter['group'])."' ";    
        }		
		if (isset($filter['text_search']) && $filter['text_search']){
            $text_search = $filter['text_search'];
            $word = addcslashes($db->escape($text_search), "_%");
            $_where[]=  "(LOWER(F.`".$lang->get('name')."`) LIKE '%" . $word . "%' OR LOWER(F.`".$lang->get('description')."`) LIKE '%" . $word . "%' OR F.id LIKE '%" . $word . "%')";            
        }		
		if (count($_where)>0){
			$where = " WHERE ".implode(" AND ",$_where);
		}
        $query = "SELECT F.id, F.`".$lang->get("name")."` as name, F.`".$lang->get("description")."` as description, F.allcats, F.type, F.cats, F.ordering, F.`group`, G.`".$lang->get("name")."` as groupname, multilist FROM `#__jshopping_products_extra_fields` as F left join `#__jshopping_products_extra_field_groups` as G on G.id=F.group ".$where." order by ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	public function deleteProductfields($cid)
    {
        $text = [];

        if (!empty($cid)) {
            $modelOfDbDelete = JSFactory::getModel('dbdelete');
        
            foreach ($cid as $value) {
                $isDeleted = $modelOfDbDelete->deleteItems('#__jshopping_products_extra_fields', 'id', $value);

                if($isDeleted) {
                    $text[] = JText::_('COM_SMARTSHOP_ITEM_DELETED');
                }

                $modelOfDbDelete->deleteItems('#__jshopping_products_extra_field_values', 'field_id', $value);
                $modelOfDbDelete->deleteField('#__jshopping_products_extra_field_values', 'extra_field_' . $value);            
            }
        }
        
		return $text;
	}
	
    public function addProductField($product_field)
    {
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$query = "ALTER TABLE `#__jshopping_products` ADD `extra_field_{$product_field}` {$jshopConfig->new_extra_field_type} NOT NULL";
        $db->setQuery($query);

        return $db->execute();
	}

    public function getPlacesOfHideCharactImages(): array
    {
        $result = [
            'product' => JText::_('COM_SMARTSHOP_HIDE_EXTRA_FIELDS_IMAGES_FOR_PRODUCT'),
            'cart' => JText::_('COM_SMARTSHOP_HIDE_EXTRA_FIELDS_IMAGES_FOR_CART'),
            'checkout' => JText::_('COM_SMARTSHOP_HIDE_EXTRA_FIELDS_IMAGES_FOR_CHECKOUT'),
            'mails' => JText::_('COM_SMARTSHOP_HIDE_EXTRA_FIELDS_IMAGES_FOR_MAILS'),
            'my_orders' => JText::_('COM_SMARTSHOP_HIDE_EXTRA_FIELDS_IMAGES_FOR_MY_ORDERS'),
        ];

        return $result;
    }
}
