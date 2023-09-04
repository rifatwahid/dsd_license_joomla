<?php
	defined('_JEXEC') or die('Restricted access');

	use Joomla\CMS\Language\Text;

	$allowUploadCheckedStatus = ($row->is_allow_uploads == 1) ? 'checked' : '';
	$unlimitedUploadsCheckedStatus = ($row->is_unlimited_uploads == 1) ? 'checked' : '';
	$independUploadFromQtyCheckedStatus = (!$this->product->product_id || $row->is_upload_independ_from_qty == 1) ? 'checked' : '';
	$requiredUploadStatus = ($row->is_required_upload == 1) ? 'checked' : '';
	$isShowBulkPricesStatus = ($row->is_show_bulk_prices == 1 || empty($this->edit)) ? 'checked' : '';
	$productCustomizeTableStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_customize)) ? 'display: none;' : '';
?>
<div id="customize" class="tab-pane"> 

	<div class="jshops_edit customize_edit">
		<?php if ($this->isPageWithAdditionalValues) : ?>
			<div class="form-group row align-items-center">
				<label for="is_use_additional_customize" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">			
					<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_CUSTOMIZE'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="is_use_additional_customize" value="0" checked>
					<input type="checkbox" name="is_use_additional_customize" class="form-check-input" id="is_use_additional_customize" value="1" <?php if ($this->product->is_use_additional_customize) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#customize .admintable');">
				</div>
			</div>
		<?php endif; ?>
	</div>

	<div class = "admintable form-horizontal" style="<?php echo $productCustomizeTableStyle; ?>">
		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_USE_SINGLE_FILE_UPLOAD'); ?>
			</div>

			<div class="control-label">
				<input type="hidden" name="is_allow_uploads" value="0" checked />
			   	<input type="checkbox" name="is_allow_uploads" class="form-check-input" value="1" <?php echo $allowUploadCheckedStatus; ?> />
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_PRODUCT_MAX_UPLOADS'); ?>
			</div>

			<div class="control-label">
			   	<input type="number" name="max_allow_uploads" class="form-control" min="1" value="<?php echo $row->max_allow_uploads; ?>"/>
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_PRODUCT_UNLIMITED_UPLOADS'); ?>
			</div>

			<div class="control-label">
				<input type="hidden" name="is_unlimited_uploads" value="0" checked/>
			   	<input type="checkbox" name="is_unlimited_uploads" class="form-check-input" value="1" <?php echo $unlimitedUploadsCheckedStatus; ?>/>
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_UPLOAD_INDEPEND_FROM_QTY'); ?>
			</div>

			<div class="control-label">
				<input type="hidden" name="is_upload_independ_from_qty" value="0" checked/>
			   	<input type="checkbox" name="is_upload_independ_from_qty" class="form-check-input" value="1" <?php echo $independUploadFromQtyCheckedStatus; ?>/>
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_UPLOAD_REQUIRED'); ?>
			</div>

			<div class="control-label">
				<input type="hidden" name="is_required_upload" value="0" checked/>
			   	<input type="checkbox" name="is_required_upload" class="form-check-input" value="1" <?php echo $requiredUploadStatus; ?>/>
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_SHOW_CART');?>
			</div>

			<div class="control-label">
				<input type="checkbox" name="product_show_cart" class="form-check-input" id="product_show_cart" value="1" <?php if ($row->product_show_cart) echo 'checked="checked"'?> />
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_SHOW_BULK_PRICES'); ?>
			</div>

			<div class="control-label">
				<input type="hidden" name="is_show_bulk_prices" value="0" checked/>
			   	<input type="checkbox" name="is_show_bulk_prices" class="form-check-input" value="1" <?php echo $isShowBulkPricesStatus; ?>/>
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_ONE_CLICK_BUY'); ?>
			</div>

			<div class="control-label">
				<input type="hidden" name="one_click_buy" value="0" checked/>
			   	<input type="checkbox" name="one_click_buy" class="form-check-input" value="1" <?php if ($row->one_click_buy) echo 'checked="checked"'?>/>
			</div>
		</div>

	</div>

</div>