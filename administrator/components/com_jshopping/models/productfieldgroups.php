<?php
/**
* @version      3.3.0 10.12.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelProductFieldGroups extends JModelLegacy{ 

    public function getList(){
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang(); 
        $query = "SELECT id, `".$lang->get("name")."` as name, ordering FROM `#__jshopping_products_extra_field_groups` order by ordering";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	public function deleteProductfieldgroups($cid){
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {					
			if($_dbdelete->deleteItems("#__jshopping_products_extra_field_groups","id",$value))
                $text .= JText::_('COM_SMARTSHOP_ITEM_DELETED')."<br>";         
		}
		return $text;
	}
}
?>