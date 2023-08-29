<?php 
    $jshopConfig = $this->jshopConfig;
?>

<form name="adminForm" id="adminForm" class="deliveryTimeOptionsForm" method="post" action="index.php?option=com_jshopping&controller=deliverytimes">
	<div class="jshops_edit striped-block deliverytimes_options_edit">
		<div class="form-group row align-items-center">
			<label for="show_delivery_time_checkout" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_DELIVERY_TIME')?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="show_delivery_time_checkout" value="0" />
				<input type="checkbox" id="show_delivery_time_checkout" class="form-check-input" name="show_delivery_time_checkout" value="1" <?php if ($jshopConfig->show_delivery_time_checkout) echo 'checked="checked"';?> />
			</div>
		</div> 
		<div class="form-group row align-items-center">
			<label for="show_delivery_time_step5" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_DELIVERY_TIME')." (". JText::_('COM_SMARTSHOP_PRODUCT').")"?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="params[show_delivery_time_step5]" value="0" />
				<input type="checkbox" id="show_delivery_time_step5" class="form-check-input" name="params[show_delivery_time_step5]" value="1" <?php if ($jshopConfig->show_delivery_time_step5) echo 'checked="checked"';?> />
			</div>
		</div> 
		<div class="form-group row align-items-center">
			<label for="display_delivery_time_for_product_in_order_mail" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_OC_display_delivery_time_for_product_in_order_mail')?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="display_delivery_time_for_product_in_order_mail" value="0" />
				<input type="checkbox" id="display_delivery_time_for_product_in_order_mail" class="form-check-input" name="display_delivery_time_for_product_in_order_mail" value="1" <?php if ($jshopConfig->display_delivery_time_for_product_in_order_mail) echo 'checked="checked"';?> />
			</div>
		</div> 
		<div class="form-group row align-items-center">
			<label for="show_delivery_date" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_DELIVERY_DATE')?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="show_delivery_date" value="0" />
				<input type="checkbox" id="show_delivery_date" class="form-check-input" name="show_delivery_date" value="1" <?php if ($jshopConfig->show_delivery_date) echo 'checked="checked"';?> />
			</div>
		</div>  

				<!-- show delivery time on product page -->
		<div class="form-group row align-items-center">
			<label for="delivery_times_on_product_page" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_SHOW_DELIVERY_TIME_ON_PRODUCT_PAGE'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="params[delivery_times_on_product_page]" value="0">
				<input type="checkbox" id="delivery_times_on_product_page" class="form-check-input" name="params[delivery_times_on_product_page]" value="1" <?php echo (!empty($jshopConfig->delivery_times_on_product_page)) ? 'checked' : ''; ?>>
			</div>
		</div>  

				<!-- show delivery time on product listing -->
		<div class="form-group row align-items-center">
			<label for="delivery_times_on_product_listing" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_SHOW_DELIVERY_TIME_ON_PRODUCT_LISTING'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="params[delivery_times_on_product_listing]" value="0">
				<input type="checkbox" id="delivery_times_on_product_listing" class="form-check-input" name="params[delivery_times_on_product_listing]" value="1" <?php echo (!empty($jshopConfig->delivery_times_on_product_listing)) ? 'checked' : ''; ?>>
			</div>
		</div>  

	</div>
	<input type="hidden" name="task" value=""/>
</form>