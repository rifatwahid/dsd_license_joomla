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

class JshoppingModelDbpublish extends JModelLegacy{
    
   public function setFlag($table,$field_id_name,$id,$field_publish,$flag){
		$db = \JFactory::getDBO();
		$query = "UPDATE `".$table."` SET `".$field_publish."` = '".$db->escape($flag)."' WHERE `".$field_id_name."` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();
	}
	
}
?>