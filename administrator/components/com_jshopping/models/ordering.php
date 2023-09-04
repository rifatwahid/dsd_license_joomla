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

class JshoppingModelOrdering extends JModelLegacy{
    
   private function getNextOrderingNumber($table,$field_id_name,$ordering_field,$number){
		$db = \JFactory::getDBO();
		$query = "SELECT a.".$field_id_name.", a.".$ordering_field."
					   FROM `".$table."` AS a
					   WHERE a.".$ordering_field." < '" . $number . "'
					   ORDER BY a.".$ordering_field." DESC
					   LIMIT 1";
		$db->setQuery($query);		
		return $db->loadObject();		
	}
	
	private function getPrvisionOrderingNumber($table,$field_id_name,$ordering_field,$number){
		$db = \JFactory::getDBO();
		$query = "SELECT a.".$field_id_name.", a.".$ordering_field."
					   FROM `".$table."` AS a
					   WHERE a.".$ordering_field." > '" . $number . "'
					   ORDER BY a.".$ordering_field." ASC
					   LIMIT 1";
		$db->setQuery($query);		
		return $db->loadObject();		
	}
	
	public function orderingMoveUp($table,$field_id_name,$ordering_field,$number,$cid){
		$db = \JFactory::getDBO();
		$row=$this->getNextOrderingNumber($table,$field_id_name,$ordering_field,$number);
		
		$query1 = "UPDATE `".$table."` AS a
					 SET a.".$ordering_field." = '" . $row->$ordering_field . "'
					 WHERE a.".$field_id_name." = '" . $cid . "'";
		$query2 = "UPDATE `".$table."` AS a
					 SET a.".$ordering_field." = '" . $number . "'
					 WHERE a.".$field_id_name." = '" . $row->$field_id_name . "'";		
		$db->setQuery($query1);
		$db->execute();
		$db->setQuery($query2);
		$db->execute();	
	}
	
	public function orderingMoveDown($table,$field_id_name,$ordering_field,$number,$cid){
		$db = \JFactory::getDBO();
		$row=$this->getPrvisionOrderingNumber($table,$field_id_name,$ordering_field,$number);		
		$query1 = "UPDATE `".$table."` AS a
					 SET a.".$ordering_field." = '" . $row->$ordering_field . "'
					 WHERE a.".$field_id_name." = '" . $cid . "'";
		$query2 = "UPDATE `".$table."` AS a
					 SET a.".$ordering_field." = '" . $number . "'
					 WHERE a.".$field_id_name." = '" . $row->$field_id_name . "'";
		$db->setQuery($query1);
		$db->execute();
		$db->setQuery($query2);
		$db->execute();	
	}
      
}

?>