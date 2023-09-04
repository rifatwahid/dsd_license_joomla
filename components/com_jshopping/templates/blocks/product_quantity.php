<label class="col" for="quantity">
	<?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>
</label>
<div class="col-4">
	<?php if(!$this->product->quantity_select && strlen(trim($this->product->quantity_select)) == 0){ ?>
		<input type="number" name="quantity" id="quantity" oninput="shopProductFreeAttributes.setData();uploadImage.updateQuantityWhenChangeProductQuantity(0, this);" onkeyup="shopProductFreeAttributes.setData();uploadImage.updateQuantityWhenChangeProductQuantity(0, this);" value="<?php echo $this->default_count_product; ?>" />
	<?php }else{ ?>
		<?php print printSelectQuantity($this->product, $this->default_count_product); ?>
	<?php } ?>
	<?php echo $this->_tmp_qty_unit ?? ''; ?>
</div>