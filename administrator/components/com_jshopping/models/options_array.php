<?php
/**
* @version      2.9.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelOptions_array extends JModelLegacy{ 

    public function getDisplayPriceOptions(){
		$displayprice = array();
        $displayprice[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_YES'), 'id', 'value');
        $displayprice[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_NO'), 'id', 'value');
		return $displayprice;
	}
	
	public function getCategorySortingOptions(){
		$catsort = array();
        $catsort[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_SORT_MANUAL'), 'id','value');
        $catsort[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_SORT_ALPH'), 'id','value');
		return $catsort;
	}
	
	public function getSortingAZ(){
		$sortd = array();
        $sortd[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_A_Z'), 'id','value');
        $sortd[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_Z_A'), 'id','value');
		return $sortd;
	}
	
	public function getSortingAttributesInProductDependent(){
		$opt = array();
        $opt[] = JHTML::_('select.option', 'V.value_ordering', JText::_('COM_SMARTSHOP_SORT_MANUAL'), 'id','value');
        $opt[] = JHTML::_('select.option', 'value_name', JText::_('COM_SMARTSHOP_SORT_ALPH'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.price', JText::_('COM_SMARTSHOP_SORT_PRICE'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.ean', JText::_('COM_SMARTSHOP_EAN_PRODUCT'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.count', JText::_('COM_SMARTSHOP_QUANTITY_PRODUCT'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.product_attr_id', JText::_('COM_SMARTSHOP_SPECIFIED_IN_PRODUCT'), 'id','value');
		return $opt;
	}
	
	public function getSortingAttributesInProductIndependent(){
		$opt = array();
        $opt[] = JHTML::_('select.option', 'V.value_ordering', JText::_('COM_SMARTSHOP_SORT_MANUAL'), 'id','value');
        $opt[] = JHTML::_('select.option', 'value_name', JText::_('COM_SMARTSHOP_SORT_ALPH'), 'id','value');
        $opt[] = JHTML::_('select.option', 'addprice', JText::_('COM_SMARTSHOP_SORT_PRICE'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.id', JText::_('COM_SMARTSHOP_SPECIFIED_IN_PRODUCT'), 'id','value');
		return $opt;
	}	
	
	public function getSortingProduct(){
		$jshopConfig = JSFactory::getConfig();
		$select = array();
		foreach ($jshopConfig->sorting_products_name_select as $key => $value) {
            $select[] = JHTML::_('select.option', $key, JText::_($value), 'id', 'value');            
        }
		return $select;
	}
	
	public function getPaymentShippingSteps(){
		$option = array();
        $option[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_STEP_3_4'), 'id', 'name');
        $option[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_STEP_4_3'), 'id', 'name');
		return $option;
	}
	
	public function getShopRegisterType(){
		$shop_register_type = array();
        $shop_register_type[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_REGISTRATION_REQUIRED'), 'id', 'name' );
        $shop_register_type[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_MEYBY_SKIP_REGISTRATION'), 'id', 'name' );
        $shop_register_type[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_WITHOUT_REGISTRATION'), 'id', 'name' );
		return $shop_register_type;
	}
	
	public function getResizeType(){
		$resize_type = array();
        $resize_type[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_CUT'), 'id', 'name' );
        $resize_type[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_FILL'), 'id', 'name' );
        $resize_type[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_STRETCH'), 'id', 'name' );		
		return $resize_type;
	}	
	
	public function getShopModeTypes(){
		$opt = array();
        $opt[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_NORMAL'), 'id', 'name');
        $opt[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_DEVELOPER'), 'id', 'name');		
		return $opt;
	}	
	
	public function getTaxRules(){
		$tax_rule_for = array();
        $tax_rule_for[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_FIRMA_CLIENT'), 'id', 'name' );
        $tax_rule_for[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_VAT_NUMBER'), 'id', 'name' );
		return $tax_rule_for;
	}
	
	public function getDisplayPriceTypes(){
		$display_price_list = array();
        $display_price_list[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_PRODUCT_BRUTTO_PRICE'), 'id', 'name');
        $display_price_list[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PRODUCT_NETTO_PRICE'), 'id', 'name');
		return $display_price_list;
	}
	
	public function getProductlistAllowBuying(){
		$display_button = array();
        $display_button[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_NO'), 'id', 'value');
        $display_button[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_YES').' '.JText::_('COM_SMARTSHOP_BUTTON_ONLY'), 'id', 'value');
        $display_button[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_YES').' '.JText::_('COM_SMARTSHOP_WITH_QUANTITY'), 'id', 'value');
		return $display_button;
	}
	
	//ORDERS
	public function getNotFinished_Options(){
		$nf_option = array();
        $nf_option[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_HIDE'), 'id', 'name');
        $nf_option[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_SHOW'), 'id', 'name');
		return $nf_option;
	}
}
?>