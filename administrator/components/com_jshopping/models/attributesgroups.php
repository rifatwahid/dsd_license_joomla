<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelAttributesGroups extends JModelLegacy{ 

    public function getList(){
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang(); 
        $query = "SELECT id, `".$lang->get("name")."` as name, ordering FROM `#__jshopping_attr_groups` order by ordering";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	public function deleteAttrGroupsById($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_attr_groups` WHERE `id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		return $db->execute();
	}
	
	public function getAtributesGroupsWithFirstFreeSelect($selected_value = 0){
		$groups = $this->getList();
        $groups0 = array();
        $groups0[] = JHTML::_('select.option', 0, "- - -", 'id', 'name');        
        return JHTML::_('select.genericlist', array_merge($groups0, $groups),'group','class="inputbox form-select"','id','name', $selected_value);		
	}
}