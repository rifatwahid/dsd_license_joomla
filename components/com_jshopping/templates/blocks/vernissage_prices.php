<?php 

$pricesProduct = !empty($this->product->product_id) ? $this->product : $product;
$productUsergroupPermissions = $this->product->getUsergroupPermissions();
if ($productUsergroupPermissions->is_usergroup_show_price) {
	$qties = explode(',',$this->product->quantity_select); 
	$default_count_product = $qties[0] ?: $this->default_count_product; ?>
	<!-- OLD PRICE -->
	<?php if ($this->product->product_old_price > 0) : ?>
		<div class="form-group row mb-2">
			<label class="col">
				<?php echo JText::_('COM_SMARTSHOP_OLD_PRICE'); ?>
			</label>

			<div class="col-4 text-end">
				<del><?php echo precisionformatprice($this->product->product_old_price)?></del>
			</div>
		</div>
	<?php endif; ?>

	<!-- SINGLE PRICE -->
	<?php if($this->config->single_item_price): ?>						
		<div class="form-group row mb-2">
			<label class="col">
				<?php echo JText::_('COM_SMARTSHOP_SINGLEPRICE'); ?>
			</label>

			<div class="col-4 text-end">
				<span id="block_price">
					<?php echo precisionformatprice($this->product->product_price_calculate); ?>
				</span>
			</div>
		</div>
	<?php endif; ?>

	<!-- BASIC PRICE -->
	<?php if ($pricesProduct->product_basic_price_show) : ?>
		<div class="form-group row mb-2">
			<label class="col">
				<?php echo JText::_('COM_SMARTSHOP_BASIC_PRICE'); ?>
			</label>

			<div class="col-4 text-end">
				(<?php echo precisionformatprice(isset($this->product->product_basic_price_calculate) ? $this->product->product_basic_price_calculate : 0) ?> / <?php echo isset($this->product->product_basic_price_unit_name) ? $this->product->product_basic_price_unit_name : ''; ?>)
			</div>
		</div>
	<?php endif; ?>
	<?php print isset($this->product->_tmp_ext_html_after_show_product_before_tax_overide) ? $this->product->_tmp_ext_html_after_show_product_before_tax_overide : ''; ?>
	<!-- TOTAL PRICE -->
	<?php if ( $this->product->getPriceCalculate($default_count_product) ) : ?>
		<div class="form-group row mb-2">
			<label class="col">
				<?php echo JText::_('COM_SMARTSHOP_SNA_TOTAL_PRICE'); ?>
			</label>

			<div class="col-4 text-end" id="product-current-price">
				<?php echo formatprice($this->product->getPriceCalculate($default_count_product)); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ($this->config->show_tax_in_product && $this->product->product_tax > 0 || $this->config->show_plus_shipping_in_product) : ?>
		<div class="text-end p-0 mt-n2">
			<?php if ($this->config->show_tax_in_product && $this->product->product_tax > 0) : ?>
				<?php echo productTaxInfo($this->product->product_tax); ?>
				<?php print $this->product->_tmp_ext_html_after_show_product_after_tax; ?>	
			<?php endif; ?>

			<?php if ($this->config->show_plus_shipping_in_product) : ?>
				<?php echo ' '.JText::sprintf('COM_SMARTSHOP_PLUS_SHIPPING', $this->shippinginfo); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php } ?>