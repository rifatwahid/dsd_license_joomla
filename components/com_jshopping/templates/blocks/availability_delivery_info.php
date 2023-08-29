<ul class="list-unstyled">
	<?php if ($this->config->stock && $this->config->product_show_qty_stock) : ?>
		<li id="available-text" class="text-small avaliable-text <?php echo (!sprintQtyInStock($this->product->qty_in_stock) <= 0) ? 'text-success' : 'text-danger' ?>">
		<?php 
				if (!sprintQtyInStock($this->product->qty_in_stock) <= 0) {
					echo JText::_('COM_SMARTSHOP_STOCK_AVAILABLE');
				} elseif (empty($this->config->hide_text_product_not_available)) {
					echo JText::_('COM_SMARTSHOP_STOCK_NOT_AVAILABLE');
				}
			?>
		</li>

		<?php if ($product->qty_in_stock!="INF") : ?>
			<li class="text-small text-muted <?php if (sprintQtyInStock($this->product->qty_in_stock) <= 0 || sprintQtyInStock($this->product->qty_in_stock) == INF) echo 'hidden'; ?>">
				<?php echo JText::_('COM_SMARTSHOP_STOCK_QUANTITY'); ?>: <span id="product_qty"><?php echo sprintQtyInStock($this->product->qty_in_stock); ?></span>
			</li>
		<?php endif ?>
	<?php endif ?>


	<?php if (!empty($this->product->delivery_time) && !$this->product->hide_delivery_time && !empty($jshopConfig->delivery_times_on_product_page)) : ?>
		<li class="text-small text-muted">
			<?php echo JText::_('COM_SMARTSHOP_STOCK_DELIVERY_TIME'); ?>: <?php echo $this->product->delivery_time; ?>
		</li>
	<?php endif; ?>

	<?php if ($this->production_time) : ?>
		<li class="text-small text-muted <?php if($this->product->production_time <= 0) print 'hidden'; ?>"  >
			<?php print JText::_('COM_SMARTSHOP_PRODUCTION_TIME') ?> : <span id="production_time"><?php print $this->product->production_time.'</span> '.JText::_('COM_SMARTSHOP_DAYS') ?>
		</li>
	<?php endif ?>
</ul>