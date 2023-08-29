<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$productDetailsTableStyle = ($this->isPageWithAdditionalValues && (empty($this->product->is_use_additional_details) && empty($is_use_additional_details))) ? 'display: none;' : '';
?>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		let lowStockNotifyStatusEl = document.querySelector('#low_stock_notify_status');
		let unlimitedQtyStatusEl = document.querySelector('#unlimitedQuantityStatus');

		if (lowStockNotifyStatusEl) {
			lowStockNotifyStatusEl.addEventListener('click', function () {
				let isDisabled = this.checked ? false: true;
				let lowStockNumberEl = document.querySelector('#low_stock_number');
				if (lowStockNumberEl) {
					lowStockNumberEl.disabled = isDisabled;
				}
			});
		}

		if (unlimitedQtyStatusEl) {
			unlimitedQtyStatusEl.addEventListener('click', function () {
				let lowStockNotifyRowEl = document.querySelector('.lowStockNotifyRow');

				if (lowStockNotifyRowEl) {
					if (this.checked) {
						lowStockNotifyRowEl.classList.add('hidden');
					} else {
						lowStockNotifyRowEl.classList.remove('hidden');
					}
				}
			});
		}
	});
</script>

<div id="main-page" class="tab-pane">
	<div class="jshops_edit product_edit_info">
		<?php if ($this->isPageWithAdditionalValues) : ?>
			<div class="form-group row align-items-center">
				<label for="is_use_additional_details" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_DETAILS'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="is_use_additional_details" value="0" checked>
					<input type="checkbox" id="is_use_additional_details" class="form-check-input" name="is_use_additional_details" value="1" <?php if ($this->product->is_use_additional_details || $is_use_additional_details) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#main-page .admintable');">
				</div>
			</div>
		<?php else : ?>
			<input type="hidden" name="is_use_additional_details" value="1" checked>
		<?php endif; ?>
	</div>

	<div class="admintable jshops_edit product_info_tmpl" style="<?php echo $productDetailsTableStyle; ?>">
		<?php if ((!isset($this->product->parent_id) || $this->product->parent_id == 0) && (!isset($parent_id) || $parent_id == 0)) : ?>
			<div class="form-group row align-items-center">
				<label for="product_publish" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="checkbox" name="product_publish" class="form-check-input" id="product_publish" value="1" <?php if ($row->product_publish) { echo 'checked="checked"'; } ?> />
				</div>
			</div>

			<div class="form-group row align-items-center display--none">
				<label for="access" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ACCESS'); ?>*
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['access']; ?>
				</div>
			</div>
			<div class="form-group row align-items-center">
				<label for="product_packing_type" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_TYPE'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php echo $this->lists['product_packing_type']; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="form-group row align-items-center">
			<label for="product_weight" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" name="product_weight" id="product_weight" class="form-control" value="<?php echo $row->product_weight; ?>" /> <?php echo sprintUnitWeight(); ?>
			</div>
		</div>  

		<div class="form-group row align-items-center">
			<label for="expiration_date" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PRODUCT_EXPIRATION_DATE'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="date" name="expiration_date" class="form-control"  id="expiration_date" value="<?php echo $row->expiration_date; ?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="product_ean" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" name="product_ean" id="product_ean" class="form-control" value="<?php echo $row->product_ean; ?>" onkeyup="shopProductCommon.updateEan()"; />
			</div>
		</div>

		<div class="form-group row align-items-center <?php if (!$jshopConfig->stock) {echo 'display--none';} ?>">
			<label for="product_quantity" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_QUANTITY_PRODUCT'); ?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<div id="block_enter_prod_qty" style="padding-bottom:2px;<?php if ($row->unlimited) print "display:none;";?>">
					<input type="text" name="product_quantity" class="form-control" id="product_quantity" value="<?php echo $row->product_quantity; ?>" <?php if ($this->product_with_attribute){?>readonly="readonly"<?php }?> />
					<?php if ($this->product_with_attribute) { echo JHTML::tooltip(JText::_('COM_SMARTSHOP_INFO_PLEASE_EDIT_AMOUNT_FOR_ATTRIBUTE')); } ?>
				</div>
				<div>         
					<input type="checkbox" name="unlimited" class="form-check-input" id="unlimitedQuantityStatus" value="1" onclick="shopProductCommon.toggleQuantity(this.checked)" <?php if ($row->unlimited) { echo "checked"; } ?> /> <?php echo JText::_('COM_SMARTSHOP_UNLIMITED'); ?>
				</div>         
			</div>
		</div>

		<div class="form-group row align-items-center lowStockNotifyRow <?php if (!$jshopConfig->stock || $row->unlimited) {echo 'hidden'; } ?>">
			<label for="low_stock_notify_status" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_LOW_STOCK_NOTIFY'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<div id="block_enter_low_stock_notify" style="padding-bottom:2px;">
					<input type="hidden" name="low_stock_notify_status" value="0" />
					<input type="checkbox" name="low_stock_notify_status" value="1" class="form-check-input" id="low_stock_notify_status" <?php if ( $row->low_stock_notify_status == 1 ) { echo 'checked'; } ?> />
					<input type="text" name="low_stock_number" class="form-control" id="low_stock_number" <?php if ( $row->low_stock_notify_status != 1 ) { echo 'disabled'; } ?> value="<?php echo $row->low_stock_number?>" />
				</div>         
			</div>
		</div>			

		<div class="form-group row align-items-center">
			<label for="factory" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_FACTORY'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<div style="padding-bottom:2px;">
					<input type="text" name="factory" class="form-control" id="factory" value="<?php echo $row->factory?>" />
				</div>         
			</div>
		</div>
		

		<div class="form-group row align-items-center">
			<label for="storage" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_STORAGE'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<div style="padding-bottom:2px;">
					<input type="text" name="storage" class="form-control" id="storage" value="<?php echo $row->storage?>" />
				</div>         
			</div>
		</div>
		
		<?php if ($jshopConfig->use_different_templates_cat_prod && (!isset($this->product->parent_id) || $this->product->parent_id == 0) && (!isset($parent_id) || $parent_id == 0)) : ?>
			<div class="form-group row align-items-center">
				<label for="product_template" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_PRODUCT'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $lists['templates']; ?>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if (!$this->withouttax) : ?>
			<div class="form-group row align-items-center">
				<label for="product_tax_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_TAX'); ?>*
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $lists['tax']; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="form-group row align-items-center">
			<label for="product_manufacturer_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_NAME_MANUFACTURER'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $lists['manufacturers']; ?>
			</div>
		</div>

		<?php if ($this->product->parent_id == 0 && (!isset($parent_id) || $parent_id == 0)) : ?>
			<div class="form-group row align-items-center">
				<label for="category_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_CATEGORIES'); ?>*
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $lists['categories']; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($jshopConfig->admin_show_vendors && isset($this->display_vendor_select) && $this->display_vendor_select) : ?>
			<div class="form-group row align-items-center">
				<label for="vendor_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_VENDOR'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $lists['vendors']; ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group row align-items-center">
			<label for="production_time" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PRODUCTION_TIME'); ?> 
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="number" min="0" name="production_time" class="form-control" value="<?php echo ($row->production_time) ? $row->production_time : $this->production_time; ?>" />
			</div>
		</div>
		
		<?php if ($jshopConfig->admin_show_delivery_time) : ?>			
			<div class="form-group row align-items-center">
				<label for="delivery_times_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $lists['deliverytimes']; ?>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if ($jshopConfig->admin_show_product_labels) : ?>
			<div class="form-group row align-items-center">
				<label for="label_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_LABEL'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $lists['labels']; ?>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if (!$jshopConfig->no_return_all) : ?>  
			<div class="form-group row align-items-center">
				<label for="no_return" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_NO_RETURN');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="options[no_return]"  value="0" />
					<input type="checkbox" name="options[no_return]" value="1" class="form-check-input" <?php if ($row->product_options['no_return']) {echo 'checked = "checked"';} ?> />
				</div>
			</div>
		<?php endif; ?>

		<div class="form-group row align-items-center">
			<label for="quantity_select" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_QUANTITY_SELECT_LABLE'); ?>
			</label>
            <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <label for="equal_steps" class="col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_EQUAL_STEPS_LABLE'); ?>
                    <input type="hidden" name="equal_steps"  value="0" />
                    <input type="checkbox" name="equal_steps" value="1" class="form-check-input" <?php if ($row->equal_steps) {echo 'checked = "checked"';} ?> />
                </label>
                <input name ="quantity_select" class="form-control" step="1" type="text" id="quantity_select" value = "<?php if($row->equal_steps){ echo (int)$row->quantity_select; }else{echo $row->quantity_select;}?>" size="80" />
            </div>
		</div>
		<div class="form-group row align-items-center">
			<label for="max_count_product" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_ERROR_MAX_COUNT_ORDER_ONE_PRODUCT'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" id="max_count_product" class="form-control" name="max_count_product" value="<?php echo $row->max_count_product ?: 0; ?>"/>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="min_count_product" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_ERROR_MIN_COUNT_ORDER_ONE_PRODUCT'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" name="min_count_product" class="form-control" id="min_count_product" value="<?php echo $row->min_count_product ?: 0; ?>"/>
			</div>
		</div>
		
		<div class="form-group row align-items-center">
			<label for="min_count_product" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PUBLISH_EDITOR_PDF'); ?>
			</label>
		
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<div class = "rows">
				<span class="col-md-3 col-lg-3"><?php echo JText::_('COM_SMARTSHOP_NO'); ?>
					<input type="radio" name="publish_editor_pdf" class="form-check-input" id="publish_editor_pdf" value="0" checked="checked" <?php echo $row->publish_editor_pdf!=1 ? "checked='checked'": ""; ?>/></span>  
				<span class="col-md-3 col-lg-3"><?php echo JText::_('COM_SMARTSHOP_YES'); ?> 
					<input type="radio" name="publish_editor_pdf" class="form-check-input" value="1" <?php echo $row->publish_editor_pdf!=0 ? "checked='checked'": ""; ?>/>
				</span>
			</div>
		</div>
		
		</div>
		<?php $pkey='plugin_template_info'; if ($this->$pkey){ print $this->$pkey;}?>
		
	</div>
	<div class="clr"></div>
</div>
