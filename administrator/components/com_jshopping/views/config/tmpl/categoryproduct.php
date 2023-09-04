<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig=JSFactory::getConfig();
displaySubmenuConfigs('catprod',$this->canDo);
?>
<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start ?? ''?>
<input type="hidden" name="task" value="">
<input type="hidden" name="tab" value="6">

    <legend><?php echo  JText::_('COM_SMARTSHOP_LIST_PRODUCTS')." / ". JText::_('COM_SMARTSHOP_PRODUCT') ?></legend>
	<div class="striped-block jshops_edit categoryproduct_config">
		<div class="form-group row align-items-center">
			<label for="not_redirect_in_cart_after_buy" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			  <?php echo  JText::_('COM_SMARTSHOP_NOT_REDIRECT_IN_CART_AFTER_BUY')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="not_redirect_in_cart_after_buy" id="not_redirect_in_cart_after_buy" value="1" <?php if ($jshopConfig->not_redirect_in_cart_after_buy) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="hide_product_not_avaible_stock" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_HIDE_PRODUCT_NOT_AVAIBLE_STOCK')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" id="hide_product_not_avaible_stock" name="hide_product_not_avaible_stock" value="1" <?php if ($jshopConfig->hide_product_not_avaible_stock) echo 'checked="checked"';?> />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="hide_buy_not_avaible_stock" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_HIDE_BUY_PRODUCT_NOT_AVAIBLE_STOCK')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="hide_buy_not_avaible_stock" id="hide_buy_not_avaible_stock" value="1" <?php if ($jshopConfig->hide_buy_not_avaible_stock) echo 'checked="checked"';?> />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="hide_text_product_not_available" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_HIDE_HIDE_TEXT_PRODUCT_NOT_AVAILABLE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="hide_text_product_not_available" id="hide_text_product_not_available" value="1" <?php if ($jshopConfig->hide_text_product_not_available) echo 'checked="checked"';?> />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="main_unit_weight" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_DISPLAY_WEIGHT_AS')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['units'];?>
			</div>
		</div>	
	</div>
	<div class="clr"></div>


    <legend><?php echo  JText::_('COM_SMARTSHOP_LIST_PRODUCTS');?></legend>
	<div class="striped-block jshops_edit category_product_config_list ">
		<div class="form-group row align-items-center">
			<label for="count_products_to_page" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_COUNT_PRODUCTS_PAGE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="count_products_to_page" class="inputbox" id="count_products_to_page" value="<?php echo $jshopConfig->count_products_to_page;?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="count_manufacturer_to_page" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_COUNT_MANUFACTURER_PAGE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="count_manufacturer_to_page" id="count_manufacturer_to_page" class="inputbox" value="<?php echo $jshopConfig->count_manufacturer_to_page;?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="category_sorting" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_ORDERING_CATEGORY');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['category_sorting'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="manufacturer_sorting" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_MANUFACTURER_SORTING');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['manufacturer_sorting'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="product_sorting" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_PRODUCT_SORTING');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['product_sorting'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="product_sorting_direction" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_PRODUCT_SORTING_DIRECTION');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['product_sorting_direction'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="product_list_show_short_description" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_SHORT_DESCRIPTION_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="product_list_show_short_description" value="0">
				<input type="checkbox" class="form-check-input" name="product_list_show_short_description" id="product_list_show_short_description" value="1" <?php if ($jshopConfig->product_list_show_short_description) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="product_list_show_weight" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_WEIGHT_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="product_list_show_weight" id="product_list_show_weight" value="1" <?php if ($jshopConfig->product_list_show_weight) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="product_list_show_product_code" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_EAN_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="product_list_show_product_code" id="product_list_show_product_code" value="1" <?php if ($jshopConfig->product_list_show_product_code) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="product_list_show_qty_stock" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_QTY_IN_STOCK')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="product_list_show_qty_stock" value="0" />
				<input type="checkbox" class="form-check-input" name="product_list_show_qty_stock" id="product_list_show_qty_stock" value="1" <?php if ($jshopConfig->product_list_show_qty_stock) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="displayprice_for_list_product" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_PRICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['displayprice_for_list_product'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_plus_shipping_in_product_list" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_PLUS_SHIPPING')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="show_plus_shipping_in_product_list" value="0" />
				<input type="checkbox" class="form-check-input" name="show_plus_shipping_in_product_list" id="show_plus_shipping_in_product_list" value="1" <?php if ($jshopConfig->show_plus_shipping_in_product_list) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_base_price_for_product_list" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_DEFAULT_PRICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="show_base_price_for_product_list" value="0" />
				<input type="checkbox" class="form-check-input" name="show_base_price_for_product_list" id="show_base_price_for_product_list" value="1" <?php if ($jshopConfig->show_base_price_for_product_list) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_wishlist_button" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_PRODUCTS_LIST_SHOW_WISHLIST_BUTTON')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="show_wishlist_button" id="show_wishlist_button" value="1" <?php if ($jshopConfig->show_wishlist_button) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="productlist_allow_buying" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_PRODUCTS_LIST_ALLOW_BUYING')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['productlist_allow_buying'];?>
			</div>
		</div>
<?php $pkey="etemplatevarlistproductlist";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>  
</div>
    
	<div class="clr"></div>
    <legend><?php echo  JText::_('COM_SMARTSHOP_PRODUCT');?></legend>
	<div class="striped-block jshops_edit categoryproduct_config_product">
		<div class="form-group row align-items-center">
			<label for="product_show_short_description" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_SHORT_DESCRIPTION_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="product_show_short_description" value="0">
				<input type="checkbox" class="form-check-input" name="product_show_short_description" id="product_show_short_description" value="1" <?php if ($jshopConfig->product_show_short_description) echo 'checked="checked"';?> />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="product_show_weight" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_WEIGHT_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="product_show_weight" id="product_show_weight" value="1" <?php if ($jshopConfig->product_show_weight) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="attr_display_addprice" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_PRODUCT_ATTRIBUT_ADD_PRICE_DISPLAY')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="attr_display_addprice" id="attr_display_addprice" value="1" <?php if ($jshopConfig->attr_display_addprice) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="attribut_dep_sorting_in_product" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_PRODUCT_ATTRIBUT_SORTING')." (". JText::_('COM_SMARTSHOP_DEPENDENT').")"?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['attribut_dep_sorting_in_product'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="attribut_nodep_sorting_in_product" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_PRODUCT_ATTRIBUT_SORTING')." (". JText::_('COM_SMARTSHOP_INDEPENDENT').")"?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['attribut_nodep_sorting_in_product'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_product_code" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_EAN_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="show_product_code" id="show_product_code" value="1" <?php if ($jshopConfig->show_product_code) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="product_show_qty_stock" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_QTY_IN_STOCK')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="product_show_qty_stock" value="0" />
				<input type="checkbox" class="form-check-input" name="product_show_qty_stock" id="product_show_qty_stock" value="1" <?php if ($jshopConfig->product_show_qty_stock) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_plus_shipping_in_product" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_PLUS_SHIPPING')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="show_plus_shipping_in_product" id="show_plus_shipping_in_product" value="1" <?php if ($jshopConfig->show_plus_shipping_in_product) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="product_list_show_price_default" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_DEFAULT_PRICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="product_list_show_price_default" id="product_list_show_price_default" value="1" <?php if ($jshopConfig->product_list_show_price_default) echo 'checked="checked"';?> />
			</div>
		</div>

<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>  
</div>
<div class="clr"></div>
<?php print $this->tmp_html_end ?? ''?>
</form>