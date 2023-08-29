<?php
/**
* @version      4.1.0 05.07.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelDbdelete extends JModelLegacy{
    
   public function deleteItems($table,$field_id_name,$id){
		$db = \JFactory::getDBO();		
		$query = "DELETE FROM `".$table."` WHERE `".$field_id_name."` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
		return $db->execute();
	}
   public function deleteField($table,$field){
		$db = \JFactory::getDBO();		
		$query = "ALTER TABLE `#__jshopping_products` DROP `".$field."`";
		$db->setQuery($query);
		$db->execute();
	}	
	
}
?>