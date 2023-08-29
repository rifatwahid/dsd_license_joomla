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

class JshoppingModelProductLabels extends JModelLegacy{    

    function getList($order = null, $orderDir = null){
        $db = \JFactory::getDBO();
        $ordering = "name";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
		$lang = JSFactory::getLang();
        $query = "SELECT id, image, `".$lang->get("name")."` as name FROM `#__jshopping_product_labels` ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	function getLabelsList(){
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->admin_show_product_labels) {            
            $alllabels = $this->getList();
            $first = array();
            $first[] = JHTML::_('select.option', '0', " - ".JText::_('COM_SMARTSHOP_LABEL')." - ", 'id','name');
            $lists['labels'] = JHTML::_('select.genericlist', array_merge($first, $alllabels), 'label_id','class="form-select" style="width: 100px;" onchange="document.adminForm.submit();"','id','name', $label_id ?? 0);
			return $lists['labels'];
        }
	}
	
	public function getlabelsLists($product_id,&$lists, $name = 'labels', $selectName = 'label_id', $id = false){
		$jshopConfig = JSFactory::getConfig();
		$_table_product = JSFactory::getTable('product', 'jshop');
		$_table_product->load($product_id);
		if ($jshopConfig->admin_show_product_labels){            
			$alllabels = $this->getList();
            $first = array();
            $first[] = JHTML::_('select.option', '0',JText::_('COM_SMARTSHOP_SELECT'), 'id','name');        
            $lists[$name] = JHTML::_('select.genericlist', array_merge($first, $alllabels), $selectName,'class = "inputbox form-select" size = "1"','id','name',$_table_product->label_id, $id);
        }
	}
	
	public function productEditList_getLabelsList(&$lists){
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->admin_show_product_labels) {            
            $alllabels = $this->getList();
            $first = array();
            $first[] = JHTML::_('select.option', '-1',"- - -", 'id','name');
            $first[] = JHTML::_('select.option', '0',JText::_('COM_SMARTSHOP_SELECT'), 'id','name');
            $lists['labels'] = JHTML::_('select.genericlist', array_merge($first, $alllabels), 'label_id','class = "inputbox form-select"','id','name');
        }
	}
}
?>