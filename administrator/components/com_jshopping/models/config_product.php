<?php
/**
* @version      3.12.0 10.11.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelConfig_product extends JModelLegacy{
    
	public function getSelect_DisplayPriceForListProduct(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');    		
		$displayprice=$_options_array->getDisplayPriceOptions();
        return JHTML::_('select.genericlist', $displayprice, 'displayprice_for_list_product','class="form-select"','id','value', $jshopConfig->displayprice_for_list_product);
	}
		
	public function getSelect_CategorySorting(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');    		
		$catsort=$_options_array->getCategorySortingOptions();
        return JHTML::_('select.genericlist', $catsort, 'category_sorting','class="form-select"','id','value', $jshopConfig->category_sorting);
	}
	
	public function getSelect_ManufacturerSorting(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');    		
		$catsort=$_options_array->getCategorySortingOptions();
        return JHTML::_('select.genericlist', $catsort, 'manufacturer_sorting','class="form-select"','id','value', $jshopConfig->manufacturer_sorting);
	}	
	
	public function getSelect_ProductSortingDirection(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');    		
		$sortd=$_options_array->getSortingAZ();
		return JHTML::_('select.genericlist', $sortd, 'product_sorting_direction','class="form-select"','id','value', $jshopConfig->product_sorting_direction);
	}
	
	public function getSelect_SortAttributesOfProductDependent(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');    		
		$opt=$_options_array->getSortingAttributesInProductDependent();
		return JHTML::_('select.genericlist', $opt, 'attribut_dep_sorting_in_product','class="form-select"','id','value', $jshopConfig->attribut_dep_sorting_in_product);
	}
	
	public function getSelect_SortAttributesOfProductIndependent(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');    		
		$opt=$_options_array->getSortingAttributesInProductIndependent();
		return JHTML::_('select.genericlist', $opt, 'attribut_nodep_sorting_in_product','class="form-select"','id','value', $jshopConfig->attribut_nodep_sorting_in_product);
	}
	
	public function getSelect_ProductSorting(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel('options_array');  
		$select=$_options_array->getSortingProduct();
		return JHTML::_('select.genericlist',$select, "product_sorting", 'class="form-select"', 'id','value', $jshopConfig->product_sorting);
	}
	
	public function getSelect_ProductListDisplayExtraField(){
		$jshopConfig = JSFactory::getConfig();	
		$_productfields = JSFactory::getModel("productFields");
        $rows = $_productfields->getList();		
		return JHTML::_('select.genericlist', $rows, "product_list_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getProductListDisplayExtraFields() );
	}
	
	public function getSelect_FilterDisplayExtraFields(){
		$jshopConfig = JSFactory::getConfig();	
		$_productfields = JSFactory::getModel("productFields");
        $rows = $_productfields->getList();		
		return JHTML::_('select.genericlist', $rows, "filter_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getFilterDisplayExtraFields() );
	}
	
	public function getSelect_ProductHideExtraFields(){
		$jshopConfig = JSFactory::getConfig();	
		$_productfields = JSFactory::getModel("productFields");
        $rows = $_productfields->getList();		
		return JHTML::_('select.genericlist', $rows, "product_hide_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getProductHideExtraFields() );
	}
	
	public function getSelect_CartDisplayExtraFields(){
		$jshopConfig = JSFactory::getConfig();	
		$_productfields = JSFactory::getModel("productFields");
        $rows = $_productfields->getList();		
		return JHTML::_('select.genericlist', $rows, "cart_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getCartDisplayExtraFields() );
	}

	public function getSelect_PdfDisplayExtraFields()
	{
		$jshopConfig = JSFactory::getConfig();	
		$_productfields = JSFactory::getModel("productFields");
        $rows = $_productfields->getList();		
		return JHTML::_('select.genericlist', $rows, "pdf_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getPdfDisplayExtraFields() );
	}

	public function getSelect_MailDisplayExtraFields()
	{
		$jshopConfig = JSFactory::getConfig();	
		$_productfields = JSFactory::getModel("productFields");
        $rows = $_productfields->getList();		
		return JHTML::_('select.genericlist', $rows, "mail_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getMailDisplayExtraFields() );
	}

	public function getSelect_HideExtraFieldsImages()
	{
		$jshopConfig = JSFactory::getConfig();	
		$_productfields = JSFactory::getModel("productFields");
        $rows = $_productfields->getList();		
		return JHTML::_('select.genericlist', $rows, "hide_extra_fields_images[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getHideExtraFieldsImages() );
	}
	
	public function getSelect_Units(){
		$jshopConfig = JSFactory::getConfig();	
		$_units = JSFactory::getModel("units");
        $list_units = $_units->getUnits();
		return JHTML::_('select.genericlist',$list_units, "main_unit_weight", 'class="form-select"', 'id','name', $jshopConfig->main_unit_weight);        
	}
	
	public function getSelect_ProductlistAllowBuying(){
		$jshopConfig = JSFactory::getConfig();	
		$_options_array = JSFactory::getModel('options_array');    		
		$productlist_allow_buying=$_options_array->getProductlistAllowBuying();
        return JHTML::_('select.genericlist', $productlist_allow_buying, 'productlist_allow_buying','class="form-select" size="1" ','id','value', $jshopConfig->productlist_allow_buying);        
	}
}
?>