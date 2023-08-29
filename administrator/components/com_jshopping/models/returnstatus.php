<?php
/**
* @version      4.7.1 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelReturnstatus extends JModelLegacy
{
	const TABLE_NAME = '#__jshopping_return_status';
        
	public function deleteReturnstatus($cid){
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {					
			if($_dbdelete->deleteItems("#__jshopping_return_status","status_id",$value))
                $text .= JText::_('COM_SMARTSHOP_RETURN_STATUS_DELETED')."<br>";
            else
                $text .= JText::_('COM_SMARTSHOP_RETURN_STATUS_ERROR_DELETED')."<br>";
		}
		return $text;
	} 
	public function getParams(){
		$db = \JFactory::getDBO();
		$query = 'SELECT `order_status_for_return` FROM `#__jshopping_config` WHERE `id` = 1';
		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}
	
	public function saveConfigurations($data)
	{
		$db = \JFactory::getDBO();
		
		$query = 'UPDATE  `#__jshopping_config` SET `order_status_for_return`="'.implode(',', $data).'"';
		$db->setQuery($query);		
		$db->execute();
	}
	
	
}
