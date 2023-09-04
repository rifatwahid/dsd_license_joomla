<?php 
    $jshopConfig = $this->jshopConfig;
?>

<form name="adminForm" id="adminForm" method="post" action="index.php?option=com_jshopping&controller=manufacturers">
	<div class="jshops_edit striped-block manufacturer_options">
		<div class="form-group row align-items-center">
			<label for="product_list_show_manufacturer" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
				<?php echo JText::_('COM_SMARTSHOP_DISPLAY_MANUFACTURER_ON_PRODUCT_LIST'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="params[product_list_show_manufacturer]" value="0" checked>
				<input type="checkbox" id="product_list_show_manufacturer" class="form-check-input" name="params[product_list_show_manufacturer]" value="1" <?php if ($jshopConfig->product_list_show_manufacturer) echo 'checked="checked"';?> />
			</div>
		</div>     

		<!-- show manufacturer logo (product details page) -->
		<div class="form-group row align-items-center">
			<label for="product_show_manufacturer_logo" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
				<?php echo JText::_('COM_SMARTSHOP_DISPLAY_MANUFACTURER_LOGO_ON_PRODUCT_PAGE'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="params[product_show_manufacturer_logo]" value="0" checked>
				<input type="checkbox" id="product_show_manufacturer_logo" class="form-check-input" name="params[product_show_manufacturer_logo]" value="1" <?php if ($jshopConfig->product_show_manufacturer_logo) echo 'checked="checked"';?> />
			</div>
		</div>

		<!-- show manufacturer (product details page) -->
		<div class="form-group row align-items-center">
			<label for="product_show_manufacturer" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
				<?php echo JText::_('COM_SMARTSHOP_DISPLAY_MANUFACTURER_ON_PRODUCT_DETAIL_PAGE'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="params[product_show_manufacturer]" value="0" checked>
				<input type="checkbox" id="product_show_manufacturer" class="form-check-input" name="params[product_show_manufacturer]" value="1" <?php if ($jshopConfig->product_show_manufacturer) echo 'checked="checked"';?> />
			</div>
		</div>

		<!-- show manufacturer (in cart) -->
		<div class="form-group row align-items-center">
			<label for="show_product_manufacturer_in_cart" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
                <?php echo  JText::_('COM_SMARTSHOP_DISPLAY_MANUFACTURER_IN_CART')?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="params[show_product_manufacturer_in_cart]" value="0" />
				<input type="checkbox" id="show_product_manufacturer_in_cart" class="form-check-input" name="params[show_product_manufacturer_in_cart]" value="1" <?php if ($jshopConfig->show_product_manufacturer_in_cart) echo 'checked="checked"';?> />
			</div>
		</div>

        <input type="hidden" name="task" value=""/>
	</div>
</form>