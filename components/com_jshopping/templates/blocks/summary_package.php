	<?php if (empty($prod['thumb_image'])) { $prod['thumb_image'] = $this->config->noimage; } ?>	
	<div class="row pb-4">
		<div class="col-md-2 col-lg-2">
			<?php 
				$urlToThumbImage = $this->config->no_image_product_live_path;
				
				if (!empty($prod['thumb_image'])) {
					$urlToThumbImage = getPatchProductImage($prod['thumb_image'], '', 1);
				}
			?>
			<img class="mr-3 order-thumbnail" id="img_<?php print $prod['product_id']; ?>" src="<?php echo $urlToThumbImage; ?>" alt="<?php echo $prod['product_name']; ?>">
		</div>

		<div class="col-md-4 col-lg-4" id="info_<?php print $prod['product_id']; ?>">
			<div class="row mt-0 mb-1"><?php echo $prod['product_name']; ?></div>
			<div class="row mt-0 mb-1"><?php print JText::_('COM_SMARTSHOP_QTY'); ?>: <?php print $prod['count_product']; ?></div>
			<div class="row mt-0 mb-1"><?php print JText::_('COM_SMARTSHOP_REASON'); ?>: <?php print $prod['return_status'] ?? JText::_('COM_SMARTSHOP_NO_REASON'); ?></div>
			<?php if($prod['customer_comment']){ ?>
				<div class="row mt-0 mb-1"><?php print JText::_('COM_SMARTSHOP_COMMENT'); ?>: </div>
				<div class="row mt-0 mb-1"><?php print $prod['customer_comment']; ?></div>
			<?php } ?>
		</div>
	</div>