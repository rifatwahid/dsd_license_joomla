<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig = JSFactory::getConfig();
$lists = $this->lists;
displaySubmenuConfigs('general',$this->canDo);
?>
<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php print $this->tmp_html_start ?? ''?>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="tab" value="1">
	
	<div class="striped-block jshops_edit general_config_tmpl">
		<div class="form-group row align-items-center">
			<label for="contact_email" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_EMAIL_ADMIN');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">
				<input type="text" class="form-control" name="contact_email" id="contact_email" class="inputbox" value="<?php echo $jshopConfig->contact_email;?>" />
			</div>
		</div>	
		<div class="form-group row align-items-center">
			<label for="defaultLanguage" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">			
				<?php echo  JText::_('COM_SMARTSHOP_DEFAULT_LANGUAGE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">
				<?php echo $lists['languages']; ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="template" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">			
				<?php echo  JText::_('COM_SMARTSHOP_TEMPLATE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">
				<?php echo $lists['template']; ?>
			</div>
		</div>	
		<div class="form-group row align-items-center">
			<label for="display_price_admin" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">	
				<?php echo  JText::_('COM_SMARTSHOP_DISPLAY_PRICE_ADMIN');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">			
				<?php echo $lists['display_price_admin']; ?>        
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="display_price_front" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">	
				<?php echo  JText::_('COM_SMARTSHOP_DISPLAY_PRICE_FRONT');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">	
				<?php echo $lists['display_price_front']; ?> 
				<a class="btn btn-small btn-primary" href="index.php?option=com_jshopping&controller=configdisplayprice"><?php print  JText::_('COM_SMARTSHOP_EXTENDED_CONFIG');?></a>        
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="single_item_price" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">	
				<?php echo  JText::_('COM_SMARTSHOP_SINGLE_ITEM_PRICE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">	
				<?php echo $lists['single_item_price']; ?> 
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="store_date_format" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">	
				 <?php echo  JText::_('COM_SMARTSHOP_STORE_DATE_FORMAT');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">	
				<input size="55" type="text" class="inputbox form-control" name="store_date_format" id="store_date_format" value="<?php echo $jshopConfig->store_date_format?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="cart_decimal_qty_precision" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_OC_CART_DECIMAL_QTY_PRECISION'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">
				<input type="text" class="form-control" name="cart_decimal_qty_precision" id="cart_decimal_qty_precision" value="<?php echo $jshopConfig->cart_decimal_qty_precision; ?>">
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="list_products_calc_basic_price_from_product_price" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_OC_LIST_PRODUCTS_CALC_BASIC_PRICE_FROM_PRODUCT_PRICE'); ?>		
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">
				<input type="hidden" name="list_products_calc_basic_price_from_product_price" value="0">
				<input type="checkbox" class="form-check-input" name="list_products_calc_basic_price_from_product_price" id="list_products_calc_basic_price_from_product_price" value="1" <?php if ($jshopConfig->list_products_calc_basic_price_from_product_price) echo 'checked="checked"';?>>
			</div>
		</div>	

		<div class="form-group row align-items-center">
			<label for="calc_basic_price_from_product_price" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_OC_CALC_BASIC_PRICE_FROM_PRODUCT_PRICE'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">
				<input type="hidden" name="calc_basic_price_from_product_price" value="0">
				<input type="checkbox" class="form-check-input" name="calc_basic_price_from_product_price" id="calc_basic_price_from_product_price" value="1" <?php if ($jshopConfig->calc_basic_price_from_product_price) echo 'checked="checked"';?>>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="savelog" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SAVE_INFO_TO_LOG'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="savelog" id="savelog" value="1" <?php if ($jshopConfig->savelog) echo 'checked="checked"';?> onclick="if (!this.checked) {document.querySelector('#savelogpaymentdata').checked = false;}" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="savelogpaymentdata" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SAVE_PAYMENTINFO_TO_LOG')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" class="form-check-input" name="savelogpaymentdata" id="savelogpaymentdata" value="1" <?php if ($jshopConfig->savelogpaymentdata) echo 'checked="checked"';?> onclick="if (!document.querySelector('#savelog').checked) {this.checked = false;}" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="securitykey" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SECURITYKEY')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="securitykey" id="securitykey" size="50" value="<?php print $jshopConfig->securitykey;?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="shop_mode" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOP_MODE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->lists['shop_mode'];?>
			</div>
		</div>

		<input type="hidden" name="create_alias_product_category_auto" value="1">
		<input type="hidden" name="tax_on_delivery_address" id="tax_on_delivery_address" value="1">
		<input type="hidden" name="display_tax_id_in_pdf" id="display_tax_id_in_pdf" value="1">
		<input type="hidden" name="rating_starparts" id="rating_starparts" value="2">
		<input type="hidden" name="product_price_precision" id="product_price_precision" value="2">
		<input type="hidden" name="product_file_upload_via_ftp" id="product_file_upload_via_ftp" value="0">
		<input type="hidden" name="product_file_upload_count" id="product_file_upload_count" value="1">
		<input type="hidden" name="product_image_upload_count" id="product_image_upload_count" value="10">
		<input type="hidden" name="product_video_upload_count" id="product_video_upload_count" value="3">
		<input type="hidden" name="show_insert_code_in_product_video" id="show_insert_code_in_product_video" value="1">
		<input type="hidden" name="max_number_download_sale_file" id="max_number_download_sale_file" value="3">
		<input type="hidden" name="max_day_download_sale_file" id="max_day_download_sale_file" value="365">
		<input type="hidden" name="display_user_groups_info" id="display_user_groups_info" value="1">
		<input type="hidden" name="display_user_group" id="display_user_group" value="1">
		<input type="hidden" name="user_discount_not_apply_prod_old_price" id="user_discount_not_apply_prod_old_price" value="1">
		<input type="hidden" name="load_jquery_lightbox" id="load_jquery_lightbox" value="1">
		<input type="hidden" name="load_javascript" id="load_javascript" value="1">
		<input type="hidden" name="load_css" id="load_css" value="1">

		<?php $k = 'product_price_precision'; ?>
		<?php if (in_array($k, $this->other_config)) : ?>
			<div class="form-group row align-items-center">
				<label for="<?php print $k?>" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">		
					<?php print JText::_('COM_SMARTSHOP_OC_'.strtoupper($k)) ?>
				</label>
				<div class="col-sm-8 col-md-9 col-xl-9 col-12 ">	
					<input type="text" class="form-control" name="<?php print $k?>" id="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				</div>
			</div>
		<?php endif ?>

		<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
	</div>
	<div class="clr"></div>
	<?php print $this->tmp_html_end ?? ''?>

</form>