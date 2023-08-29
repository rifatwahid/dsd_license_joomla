<?php
/**
* @version      4.3.0 24.07.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelAccess extends JModelLegacy{    

	public function getAccessGroupsLists($product_id,&$lists){
		$_table_product = JSFactory::getTable('product', 'jshop');
		$_table_product->load($product_id);
		$accessgroups = getAccessGroups();        
        $lists['access'] = JHTML::_('select.genericlist', $accessgroups, 'access','class = "inputbox form-select" size = "1"','id','title', $_table_product->access);
	}
	
	public function getAccessGroupsSelect($first_free_element=0,$access=''){
		$accessgroups = getAccessGroups();
		if ($first_free_element){
			$first = array();
			$first[] = JHTML::_('select.option', '-1',"- - -", 'id','title');
			$accessgroups=array_merge($first, $accessgroups);
		}
		if ($access!="")			
			return JHTML::_('select.genericlist', $accessgroups, 'access','class = "inputbox form-select" size = "1"','id','title', $access);
		else
			return JHTML::_('select.genericlist', $accessgroups, 'access','class = "inputbox form-select" size = "1"','id','title');
	}
	
	public function productEditList_getAccessList(&$list){
		$accessgroups = getAccessGroups();        
        $first = array();
        $first[] = JHTML::_('select.option', '-1',"- - -", 'id','title');
        $lists['access'] = JHTML::_('select.genericlist', array_merge($first, $accessgroups), 'access','class = "inputbox form-select"','id','title');
	}
}
?>