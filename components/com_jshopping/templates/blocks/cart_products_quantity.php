 
<div class="form-group flex-fill mb-0">
	<label for="quantity[<?php echo $key_id ?>]" class="sr-only">
		<?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>
	</label>

	<?php if(!$prod['quantity_select'] && strlen(trim($prod['quantity_select'])) == 0){ ?>
		<input type="number" name="quantity[<?php echo $key_id; ?>]" id="quantity[<?php echo $key_id; ?>]" value="<?php echo $prod['quantity']; ?>" onfocusout="uploadImage.updateQuantityWhenChangeProductQuantity('<?php echo $key_id; ?>', this);"  class="form-control text-center" />
	<?php }else{ ?>
		<?php print printSelectQuantityCart($prod['product_id'], $prod['quantity_select'], $prod['quantity'], "quantity[$key_id]", $key_id, (unserialize($prod['attributes']) ?: [])); ?>
	<?php } ?>
</div>