<label class="d-block" for="quantity"><?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?></label>
<div class="input-group pb-3">
	<?php if(!$this->product->quantity_select && strlen(trim($this->product->quantity_select)) == 0){ ?>
		<input class="form-control text-center" type="number" name="quantity" id="quantity" min="<?php echo $this->product->min_count_product; ?>" oninput="shopProductFreeAttributes.setData();uploadImage.updateQuantityWhenChangeProductQuantity(0, this);" onkeyup="shopProductFreeAttributes.setData();uploadImage.updateQuantityWhenChangeProductQuantity(0, this);" value="<?php echo $this->default_count_product; ?>" />
		<?php echo isset($this->_tmp_qty_unit) ? $this->_tmp_qty_unit : ''; ?>

		<div class="input-group-append">
			<button class="btn quantity-minus btn-outline-primary " type="button" onClick="var epattr = parseInt(document.querySelector('#quantity').getAttribute('min'));if (typeof epattr !== typeof undefined && epattr !== false && epattr !== 0 && epattr >= document.querySelector('#quantity').value){document.querySelectorAll('#quantity')[epattr].value--;}else{document.querySelector('#quantity').value--;} shopProductFreeAttributes.setData(); ">-</button>
			<button class="btn quantity-plus btn-outline-primary " type="button" onClick="document.querySelector('#quantity').value++; shopProductFreeAttributes.setData(); ">+</button>
		</div>
	<?php }else{ ?>
		<?php print printSelectQuantity($this->product, $this->default_count_product); ?>
		<?php if (isset($this->_tmp_qty_unit)) echo $this->_tmp_qty_unit;?>
	<?php } ?>
</div>
<div id="ep_pu_container">
	<?php if($this->product->min_count_product){ ?>
		<?php echo JText::_('COM_SMARTSHOP_MIN_QTY_FOR_USE').': '.$this->product->min_count_product; ?>
	<?php } ?>
</div>