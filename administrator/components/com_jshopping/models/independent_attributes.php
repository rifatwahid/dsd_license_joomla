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

class JshoppingModelIndependent_attributes extends JModelLegacy 
{
   public function getIndependentAttributesList($_table_product,&$lists){
	   $_attribut = JSFactory::getModel('attribut');
	   $_attributvalue = JSFactory::getModel('attributvalue');
	   $_categories = JSFactory::getModel('categories');
	   $_pricemodification = JSFactory::getModel('pricemodification');
	   $first = array();
       $first[] = JHTML::_('select.option', '0',JText::_('COM_SMARTSHOP_SELECT'), 'value_id','name');
	   $categories_select_list=$_categories->getProductCategoriesList($_table_product);
	   $list_all_attributes = $_attribut->getAllAttributes(2, $categories_select_list);
	   $all_independent_attributes = $list_all_attributes['independent']; 
	   $price_modification=$_pricemodification->getModificationArray(); 
	   foreach ($all_independent_attributes as $key => $value){
            $values_for_attribut = $_attributvalue->getAllValues($value->attr_id);            
            $all_independent_attributes[$key]->values_select = JHTML::_('select.genericlist', array_merge($first, $values_for_attribut),'attr_ind_id_tmp_'.$value->attr_id.'','class = "inputbox middle2 form-select" ','value_id','name');
            $all_independent_attributes[$key]->values = $values_for_attribut;
            $all_independent_attributes[$key]->price_modification_select = JHTML::_('select.genericlist', $price_modification,'attr_price_mod_tmp_'.$value->attr_id.'','class = "inputbox small3 form-select" ','id','name');
            $hidden_attribute = ($value->attr_type == 3) ? 1 : '';

            $all_independent_attributes[$key]->submit_button = '<input type = "button" class="btn" onclick = "shopProductAttribute.addSecondValue('.$value->attr_id.', '. $hidden_attribute .');" value = "'.JText::_('COM_SMARTSHOP_ADD_ATTRIBUT').'" />';
        }        
        $lists['all_independent_attributes'] = $all_independent_attributes;
        $lists['dep_attr_button_add'] = '<input type="button" class="btn btn-primary" onclick="shopProductAttribute.addValue()" value="'.JText::_('COM_SMARTSHOP_ADD').'" />';
   }
   
   public function getIndependentAttributesView($lists,&$view){
	   $_prices = JSFactory::getModel("prices");
       $_pricemodification = JSFactory::getModel('pricemodification');
       $price_modification=$_pricemodification->getModificationArray();
	    if (isset($lists['all_independent_attributes']) && count($lists['all_independent_attributes'])) {
            if(isset($view->ind_attr_td_header)) $view->ind_attr_td_header .= '<th width="220">' . JText::_('COM_SMARTSHOP_PRICE_TYPE') . '</th>';
			else $view->ind_attr_td_header = '<th width="220">' . JText::_('COM_SMARTSHOP_PRICE_TYPE') . '</th>';
            $view->ind_attr_td_header .= '<th>' . JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT') . '</th>';
            foreach ($lists['all_independent_attributes'] as $ind_attr) {
                if (isset($lists['ind_attribs_gr'][$ind_attr->attr_id]) && is_array($lists['ind_attribs_gr'][$ind_attr->attr_id])) {
                    foreach ($lists['ind_attribs_gr'][$ind_attr->attr_id] as $k=>$ind_attr_val) {
                        $lists['ind_attribs_gr'][$ind_attr->attr_id][$k]->price_mod_select = JHTML::_('select.genericlist', $price_modification,'attrib_ind_price_mod[]','class = "inputbox small3 form-select" ','id','name',$ind_attr_val->price_mod);
                        if(isset($view->ind_attr_td_row[$ind_attr_val->attr_value_id])) $view->ind_attr_td_row[$ind_attr_val->attr_value_id] .= '<td>' . $_prices->getProductAttrPriceTypeSelect($ind_attr_val->price_type, 'attrib_ind_price_type[]', 'inputbox', '', true) . '</td>';
                        else $view->ind_attr_td_row[$ind_attr_val->attr_value_id] = '<td>' . $_prices->getProductAttrPriceTypeSelect($ind_attr_val->price_type, 'attrib_ind_price_type[]', 'inputbox', '', true) . '</td>';
						$view->ind_attr_td_row[$ind_attr_val->attr_value_id] .= '<td><input class="small3" type="text" name="attrib_ind_weight[]" value="' . $ind_attr_val->weight . '"></td>';
                    }
                }
                
                if(isset($view->ind_attr_td_footer[$ind_attr->attr_id])) $view->ind_attr_td_footer[$ind_attr->attr_id] .= '<td width="220">'.$_prices->getProductAttrPriceTypeSelect(0, 'attrib_ind_price_type_tmp_'.$ind_attr->attr_id, 'inputbox', 'attrib_ind_price_type_tmp_'.$ind_attr->attr_id, true).'</td>';
				else $view->ind_attr_td_footer[$ind_attr->attr_id] = '<td width="220">'.$_prices->getProductAttrPriceTypeSelect(0, 'attrib_ind_price_type_tmp_'.$ind_attr->attr_id, 'inputbox', 'attrib_ind_price_type_tmp_'.$ind_attr->attr_id, true).'</td>';
                $view->ind_attr_td_footer[$ind_attr->attr_id] .= '<td width="120"><input type="text" class="small3 " id="attr_ind_weight_tmp_' . $ind_attr->attr_id . '" value="0"></td>';
            }
        }
   }
}
