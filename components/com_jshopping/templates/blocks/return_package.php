
	<div class="row pt-3" class="return_product" id="return_<?php print $prod_id; ?>">
		<div class="col-sm-3">
			<?php 
				$urlToThumbImage = $this->config->no_image_product_live_path;
				
				if (!empty($this->items[$prod_id]->thumb_image)) {
					$urlToThumbImage = getPatchProductImage($this->items[$prod_id]->thumb_image, '', 1);
				}
			?>
			<img class="mr-3 order-thumbnail" id="img_<?php print $this->items[$prod_id]->order_item_id; ?>" src="<?php echo $urlToThumbImage; ?>" alt="<?php echo $this->items[$prod_id]->product_name; ?>">
		</div>
		<div class="col">
			<span><?php echo $this->items[$prod_id]->product_name; ?></span>
			<div class="row">
				<div class="col pe-0"><?php print JText::_('COM_SMARTSHOP_QTY'); ?>: <span class="col ps-0 count_block"><?php print $return_products['quantity']; ?></span></div>
			</div>
		</div>
		<input type="hidden" id="products_count_<?php print $prod_id; ?>" name="products_count[<?php print $prod_id; ?>]" value="<?php print $return_products['quantity']; ?>" />
	</div>