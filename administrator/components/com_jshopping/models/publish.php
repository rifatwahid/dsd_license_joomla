<?php
/**
* @version      4.7.0 28.10.2019
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelPublish extends JModelLegacy
{
	public function getPublishSelectWithFirstFreeElement($select_name){
		$published = array();
        $published[] = JHTML::_('select.option', '-1', "- - -", 'value', 'name');		
        $published[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_UNPUBLISH'), 'value', 'name');
        $published[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PUBLISH'), 'value', 'name');       
        return JHTML::_('select.genericlist', $published, $select_name, 'class="form-select"', 'value', 'name');
	}
	
	public function getProductsListFilterPublish(){
		$published = array();
        $published[] = JHTML::_('select.option', 0, " - ".JText::_('COM_SMARTSHOP_SHOW')." - ", 'id', 'name');
        $published[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PUBLISH'), 'id', 'name');
        $published[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_UNPUBLISH'), 'id', 'name');
        $select = JHTML::_('select.genericlist', $published, 'publish', 'class="form-select" style="width: 100px;" onchange="document.adminForm.submit();"', 'id', 'name', $publish ?? '');
		return $select;
	}
}