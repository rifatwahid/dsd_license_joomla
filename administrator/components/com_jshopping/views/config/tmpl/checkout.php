<?php 
/**
* @version      4.9.0 10.02.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig=JSFactory::getConfig();
$lists=$this->lists;
displaySubmenuConfigs('checkout',$this->canDo);
?>

<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php print $this->tmp_html_start ?? ''?>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="tab" value="7">

	<div class="striped-block jshops_edit checkout_tmpl_config">

		<div class="form-group row align-items-center">
			<label for="hide_shipping_step" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_HIDE_SHIPPING_STEP')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="hide_shipping_step" id="hide_shipping_step" value="1" <?php if ($jshopConfig->hide_shipping_step) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="hide_payment_step" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_HIDE_PAYMENT_STEP')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="hide_payment_step" id="hide_payment_step" value="1" <?php if ($jshopConfig->hide_payment_step) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="sorting_country_in_alphabet" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SORTING_COUNTRY_IN_ALPHABET')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="sorting_country_in_alphabet" id="sorting_country_in_alphabet" value="1" <?php if ($jshopConfig->sorting_country_in_alphabet) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="default_country" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_DEFAULT_COUNTRY');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo $lists['default_country']; ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="step_4_3" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SEQUENCE_STEP');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo $lists['step_4_3']; ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_weight_order" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_WEIGHT_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="show_weight_order" id="show_weight_order" value="1" <?php if ($jshopConfig->show_weight_order) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_product_code_in_cart" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_EAN_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="show_product_code_in_cart" id="show_product_code_in_cart" value="1" <?php if ($jshopConfig->show_product_code_in_cart) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="cart_basic_price_show" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_BASIC_PRICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="cart_basic_price_show" value="0" />
				<input type="checkbox" class="form-check-input" name="cart_basic_price_show" id="cart_basic_price_show" value="1" <?php if ($jshopConfig->cart_basic_price_show) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="use_decimal_qty" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_USE_DECIMAL_QTY')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="use_decimal_qty" value="0" />
				<input type="checkbox" class="form-check-input" name="use_decimal_qty" id="use_decimal_qty" value="1" <?php if ($jshopConfig->use_decimal_qty) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="display_agb" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_AGB')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="display_agb" value="0" />
				<input type="checkbox" class="form-check-input" name="display_agb" id="display_agb" value="1" <?php if ($jshopConfig->display_agb) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_return_policy_in_email_order" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_RETURN_POLICY_IN_EMAIL_ORDER')?> (<?php print  JText::_('COM_SMARTSHOP_URL')?>)
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="show_return_policy_in_email_order" id="show_return_policy_in_email_order" value="1" <?php if ($jshopConfig->show_return_policy_in_email_order) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="no_return_all" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_NORETURN_ALL_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="no_return_all" value="0" />
				<input type="checkbox" class="form-check-input" name="no_return_all" id="no_return_all" value="1" <?php if ($jshopConfig->no_return_all) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_create_account_block" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_CREATE_ACCOUNT_BLOCK')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="show_create_account_block" value="0" />
				<input type="checkbox" class="form-check-input" name="show_create_account_block" id="show_create_account_block" value="1" <?php if ($jshopConfig->show_create_account_block) echo 'checked="checked"';?> />
			</div>
		</div>		
		<div class="form-group row align-items-center">
			<label for="show_comment_box" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_COMMENT_BOX')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="show_comment_box" value="0" />
				<input type="checkbox" class="form-check-input" name="show_comment_box" id="show_comment_box" value="1" <?php if ($jshopConfig->show_comment_box) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_shipping_costs_in_cart" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_SHIPPING_COSTS_IN_CART')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="show_shipping_costs_in_cart" value="0" />
				<input type="checkbox" class="form-check-input" name="show_shipping_costs_in_cart" id="show_shipping_costs_in_cart" value="1" <?php if ($jshopConfig->show_shipping_costs_in_cart) echo 'checked="checked"';?> />
			</div>
		</div>
<?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
	
</div>
<div class="clr"></div>
<?php print $this->tmp_html_end ?? ''?>
</form>