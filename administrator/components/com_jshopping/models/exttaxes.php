<?php
/**
* @version      4.1.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelExttaxes extends JModelLegacy{    
	
	public function deleteExttaxes($cid){
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {					
			if($_dbdelete->deleteItems("#__jshopping_taxes_ext","id",$value))
                $text .= JText::_('COM_SMARTSHOP_EXTTAXES_DELETED')."<br>";
            else
                $text .= JText::_('COM_SMARTSHOP_EXTTAXES_ERROR_DELETED')."<br>";
		}
		return $text;
	}
	
	public function deleteExtAditionaltaxes($cid){	
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {	
			$taxext = JSFactory::getTable('taxExt', 'jshop');
			$taxext->deleteColumn("additional_tax_".$value);
			
			if($_dbdelete->deleteItems("#__jshopping_taxes_ext_additional_taxes","id",$value))
                $text .= JText::_('COM_SMARTSHOP_EXTTAXES_DELETED')."<br>";
            else
                $text .= JText::_('COM_SMARTSHOP_EXTTAXES_ERROR_DELETED')."<br>";
		}
		return $text;
	}

	
}
?>