<?php
/**
* @version      4.1.0 23.09.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelDelivery extends JModelLegacy{    
    
	public function setDeliveryNotes(&$rows){		
		$jshopConfig = JSFactory::getConfig();
		if (count($rows) > 0){
            $jshopConfig = JSFactory::getConfig();
            JSFactory::loadExtLanguageFile('jshop_delivery');
          
            foreach($rows as $row){
                if ($row->pdf_file && file_exists($jshopConfig->pdf_orders_path."/delivery/".$row->pdf_file)){
                    if(!isset($row->_ext_order_info)) $row->_ext_order_info = '';
					$row->_ext_order_info .= "<a title = '". JText::_('COM_SMARTSHOP_DN_LIEFERSCHEIN') ."' href = \"javascript:void window.open('".$jshopConfig->pdf_orders_live_path."/delivery/".$row->pdf_file ."', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no');\"><i class='fas fa-dolly'></i></a>";
                }
            }
        }
	}
	
    
}
?>