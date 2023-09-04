 <div class="mb-1 row">
	<div id="available-text" class="col avaliable-text <?php echo (!sprintQtyInStock($product->qty_in_stock) <= 0) ? 'text-success' : 'text-danger' ?> ">
		<?php if (!sprintQtyInStock($product->qty_in_stock) <= 0) : ?>
			<?php echo JText::_('COM_SMARTSHOP_STOCK_AVAILABLE'); ?>
		<?php elseif (empty($jshopConfig->hide_text_product_not_available)) : ?>
			<?php echo JText::_('COM_SMARTSHOP_STOCK_NOT_AVAILABLE'); ?>
		<?php endif; ?>
	</div>
	
	<?php if ($product->qty_in_stock!="INF") : ?>
		<div class="col-6 text-end text-muted <?php if (sprintQtyInStock(isset($product->qty_in_stock) ? $product->qty_in_stock : $product->qty_in_stock->qty ) <= 0) echo 'hidden'; ?> ">
			<?php echo JText::_('COM_SMARTSHOP_STOCK_QUANTITY'); ?>: <span id="product_qty"><?php print sprintQtyInStock(isset($product->qty_in_stock) ? $product->qty_in_stock : $product->qty_in_stock->qty); ?></span>
		</div>
	<?php endif ?>
</div>