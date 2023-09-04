<?php
/**
* @version      4.8.0 04.06.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class JshoppingModelDependent_attributes extends JModelLegacy 
{
   public function getDependentAttributesList($_table_product,&$lists){
		$_products = JSFactory::getModel('products');
		$_attribut = JSFactory::getModel('attribut');
		$_attributvalue = JSFactory::getModel('attributvalue');
		$_categories = JSFactory::getModel('categories');
		$first = array();
		$first[] = JHTML::_('select.option', '0',JText::_('COM_SMARTSHOP_SELECT'), 'value_id','name');
		$categories_select_list=$_categories->getProductCategoriesList($_table_product);
		$list_all_attributes = $_attribut->getAllAttributes(2, $categories_select_list);
		$all_attributes = $list_all_attributes['dependent'];
		//$lists['attribs'] = array();
		//$lists['ind_attribs'] = array();
		$lists['ind_attribs_gr'] = array();
		foreach($lists['ind_attribs'] as $v){
			$lists['ind_attribs_gr'][$v->attr_id][] = $v;
		}
		foreach ($lists['attribs'] as $key => $attribs){
			$lists['attribs'][$key]->count = floatval($attribs->count);
		}
		foreach ($all_attributes as $key => $value){
            $values_for_attribut = $_attributvalue->getAllValues($value->attr_id);
            $all_attributes[$key]->values_select = JHTML::_('select.genericlist', array_merge($first, $values_for_attribut),'value_id['.$value->attr_id.']','class = "inputbox form-select" size = "5" multiple="multiple" id = "value_id_'.$value->attr_id.'"','value_id','name');
            $all_attributes[$key]->values = $values_for_attribut;
        }        
        $lists['all_attributes'] = $all_attributes;
		$_products->addHiddenType($lists['all_attributes']);
   }
   
   public function getDependentAttributesSelect($selected_value){
		$dependent[] = JHTML::_('select.option', '0',JText::_('COM_SMARTSHOP_YES'),'id','name');
		$dependent[] = JHTML::_('select.option', '1',JText::_('COM_SMARTSHOP_NO'),'id','name');
		$dependent_attribut = JHTML::_('select.radiolist', $dependent, 'independent','class = "inputbox" size = "1"','id','name', $selected_value);
   }
}
