<form action="<?php echo SEFLink('index.php?option=com_jshopping&controller=returns&task=add') ?>" id="updateCartForm" method="post" name="returnOrder" >
	<div class="row pb-4 pt-4"><div class="coll"><h5><?php print JText::_('COM_SMARTSHOP_SELECT_ITEMS_RETURN'); ?></h5></div></div>
	<div class="row">
		<div class="col-9">
		<?php $count = 0; 
			foreach($this->items as $key_id=>$prod) :  ?>
				<?php $count += $prod->product_quantity; if($prod->product_quantity <= 0) continue; ?>
				<?php include templateOverrideBlock('blocks', 'return_start_product.php'); ?>									
			<?php endforeach; ?>
			<?php if($count == 0){ ?>
				<p><?php print JText::_('COM_SMARTSHOP_RETURN_PRODUCTS_EMPTY'); ?></p>
				<p><a class="btn btn-outline-secondary" href="<?php echo SEFLink('index.php?option=com_jshopping&controller=user&task=order&order_id='.$this->order->order_id) ?>"><?php echo JText::_('COM_SMARTSHOP_BACK_TO_ORDER'); ?></a></p>
			<?php } ?>
		</div>
		<div class="col-3">
			<div class="d-end">
				<div><input type="button" class="btn btn-outline-secondary w-100" id="return_submit" value="<?php echo JText::_('COM_SMARTSHOP_CONTINUE'); ?>" /></div>
				<div class="row"><div class="coll pt-3"><?php print JText::_('COM_SMARTSHOP_PRODUCT_SELECT_FOR_RETURN'); ?>:</div></div>
				<div class="returns_product">
					<?php if($this->return_products){ ?>
						<?php foreach($this->return_products as $prod_id => $return_products){  ?>
							<?php include templateOverrideBlock('blocks', 'return_package.php'); ?>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="order_id" value="<?php print $this->order->order_id; ?>"/>
</form>