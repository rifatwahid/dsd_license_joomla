<div id="tr_add_price_upload_singleprice" class="form-group row align-items-center">
	<label for="is_activated_price_per_consignment_upload_disable_quantity" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
		<?php echo JText::_('COM_SMARTSHOP_NATIVE_PROGRESS_UPLOADS_PRICES_INDEPENDENTLY_FROM_THE_QUANTITY'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">		
		<input type="checkbox" <?php if ($this->product->is_activated_price_per_consignment_upload_disable_quantity) echo 'checked="checked"';?> name="is_activated_price_per_consignment_upload_disable_quantity" class="form-check-input"  value="1">		
	</div>
</div>