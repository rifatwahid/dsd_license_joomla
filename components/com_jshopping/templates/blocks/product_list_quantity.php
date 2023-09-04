<div class="mb-2 row product__quantity-row">
	<label class="col-6" for="quantity">
		<?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>
	</label>
	<div class="col-6">
		<?php if(!$product->quantity_select && strlen(trim($product->quantity_select)) == 0){ ?>
			<input type="number" name="quantity" id="quantity" value="<?php echo $product->productQuantity; ?>" />
		<?php }else{ ?>
			<?php print printSelectQuantity($product, $product->productQuantity, 'quantity', 1); ?>
		<?php } ?>
		<?php echo isset($product->_tmp_qty_unit) ? $product->_tmp_qty_unit : ''; ?>
	</div>
</div>