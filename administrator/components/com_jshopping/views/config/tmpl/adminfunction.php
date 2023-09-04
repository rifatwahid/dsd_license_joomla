<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$jshopConfig=$this->jshopConfig;
$lists=$this->lists;
displaySubmenuConfigs('adminfunction',$this->canDo);
?>

<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php print $this->tmp_html_start ?? ''?>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="tab" value="8">

    <legend><?php echo  JText::_('COM_SMARTSHOP_GENERAL');?></legend>
	<div class="striped-block jshops_edit adminfunction_general">
		<div class="form-group row align-items-center wishlist">
			<label for="enable_f_wishlist" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			  <?php echo  JText::_('COM_SMARTSHOP_ENABLE_WISHLIST');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" name="enable_wishlist" class="inputbox form-check-input" id="enable_f_wishlist" value="1" <?php if ($jshopConfig->enable_wishlist) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center shop_user_guest">
			<label for="shop_user_guest" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			  <?php echo  JText::_('COM_SMARTSHOP_PURCHASE_WITHOUT_REGISTERING')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['shop_register_type'];?>
			</div>
		</div>
		<div class="form-group row align-items-center user_as_catalog">
			<label for="user_as_catalog" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			  <?php echo  JText::_('COM_SMARTSHOP_USER_AS_CATALOG')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="user_as_catalog" id="user_as_catalog" value="1" <?php if ($jshopConfig->user_as_catalog) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center without_shipping">
			<label for="without_shipping" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			  <?php echo  JText::_('COM_SMARTSHOP_SHIPPINGS')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="without_shipping" id="without_shipping" value="1" <?php if (!$jshopConfig->without_shipping) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center without_payment">
			<label for="without_payment" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			  <?php echo  JText::_('COM_SMARTSHOP_PAYMENTS')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="without_payment" id="without_payment" value="1" <?php if (!$jshopConfig->without_payment) echo 'checked="checked"';?> />
		   </div>
		</div>
		<div class="form-group row align-items-center tax_configuration_general">
			<label for="tax" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_TAX')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="tax" value="0"/>
				<input type="checkbox" class="form-check-input" name="tax" id="tax" value="1" <?php if ($jshopConfig->tax) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center stock_general_config">
			<label for="stock" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_STOCK')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="stock" value="0"/>
				<input type="checkbox" class="form-check-input" name="stock" id="stock" value="1" <?php if ($jshopConfig->stock) echo 'checked = "checked"';?> />
			</div>
		</div>		
		<div class="form-group row align-items-center display_checkout_button">
			<label for="display_checkout_button" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">	
				 <?php echo  JText::_('COM_SMARTSHOP_DISPLAY_CHECKOUT_BUTTON');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">	
				<input type="hidden" name="display_checkout_button" value="0"/>
				<input type="checkbox" class="form-check-input" name="display_checkout_button" id="display_checkout_button" value="1" <?php if ($jshopConfig->display_checkout_button) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center display_preloader">
			<label for="display_preloader" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				 <?php echo  JText::_('COM_SMARTSHOP_DISPLAY_PRELOAD');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">
				<input type="hidden" name="display_preloader" value="0"/>
				<input type="checkbox" class="form-check-input" name="display_preloader" id="display_preloader" value="1" <?php if ($jshopConfig->display_preloader) echo 'checked="checked"';?> />
			</div>
		</div>
	</div>

	<div class="striped-block jshops_edit admin_show_attributes">
		<legend><?php echo  JText::_('COM_SMARTSHOP_PRODUCTS') ?></legend>
		<div class="form-group row align-items-center">
			<label for="admin_show_attributes" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_ATTRIBUTES')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_attributes" id="admin_show_attributes" value="1" <?php if ($jshopConfig->admin_show_attributes) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_freeattributes">
			<label for="admin_show_freeattributes" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_FREE_ATTRIBUTES')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_freeattributes" id="admin_show_freeattributes" value="1" <?php if ($jshopConfig->admin_show_freeattributes) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_delivery_time">
			<label for="admin_show_delivery_time" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_DELIVERY_TIME')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_delivery_time" id="admin_show_delivery_time" value="1" <?php if ($jshopConfig->admin_show_delivery_time) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_product_video">
			<label for="admin_show_product_video" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_PRODUCT_VIDEOS')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_product_video" id="admin_show_product_video" value="1" <?php if ($jshopConfig->admin_show_product_video) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_product_related">
			<label for="admin_show_product_related" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_PRODUCT_RELATED')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_product_related" id="admin_show_product_related" value="1" <?php if ($jshopConfig->admin_show_product_related) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_product_demo_files">
			<label for="admin_show_product_demo_files" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_DEMO_FILE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_product_demo_files" id="admin_show_product_demo_files" value="1" <?php if ($jshopConfig->admin_show_product_demo_files) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_product_sale_files">
			<label for="admin_show_product_sale_files" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
			    <?php echo  JText::_('COM_SMARTSHOP_SALE_FILE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_product_sale_files" id="admin_show_product_sale_files" value="1" <?php if ($jshopConfig->admin_show_product_sale_files) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_product_labels">
			<label for="admin_show_product_labels" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_LABEL');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_product_labels" id="admin_show_product_labels" value="1" <?php if ($jshopConfig->admin_show_product_labels) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_product_bay_price">
			<label for="admin_show_product_bay_price" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_PRODUCT_BUY_PRICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_product_bay_price" id="admin_show_product_bay_price" value="1" <?php if ($jshopConfig->admin_show_product_bay_price) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_product_basic_price">
			<label for="admin_show_product_basic_price" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_BASIC_PRICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_product_basic_price" id="admin_show_product_basic_price" value="1" <?php if ($jshopConfig->admin_show_product_basic_price) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center admin_show_product_extra_field">
			<label for="admin_show_product_extra_field" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_EXTRA_FIELDS')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="admin_show_product_extra_field" id="admin_show_product_extra_field" value="1" <?php if ($jshopConfig->admin_show_product_extra_field) echo 'checked="checked"';?> />
			</div>
		</div>
<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
</div>
<?php print $this->tmp_html_end ?? ''?>
</form>