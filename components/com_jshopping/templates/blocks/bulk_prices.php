<?php if ($this->product->_display_price && $this->product->is_show_bulk_prices == 1 && $this->product->product_is_add_price == 1 && count($this->add_prices_with_user_discount)>0) : ?>
	<div id="productBulkPrices">
		<span class="productBulkPrices__title">
			<?php echo JText::_('COM_SMARTSHOP_BULK_PRICES'); ?>
		</span>

		<div id="productBulkPrices__list">
			<?php 
				$this->product->product_add_prices = $this->add_prices_with_user_discount;
				include templateOverrideBlock('blocks', 'price_per_consigments_prices_list.php');
			?>
		</div>
	</div>
<?php endif; ?>