<?php if (empty($prod->thumb_image)) { $prod->thumb_image = $this->config->noimage; } ?>	
<div class="row pb-4">
	<div class="col-md-1 col-lg-1">
		<input type="checkbox" name="prods[]"  id="prods_<?php print $prod->order_item_id; ?>" class="return_prods" value="<?php print $prod->order_item_id; ?>" <?php if($prod->show == 1){ print 'checked="true"'; } ?>/>
	</div>
	<div class="col-md-2 col-lg-2">
		<?php 
			$urlToThumbImage = $this->config->no_image_product_live_path;
			
			if (!empty($prod->thumb_image)) {
				$urlToThumbImage = getPatchProductImage($prod->thumb_image, '', 1);
			}
		?>
		<img class="mr-3 order-thumbnail" id="img_<?php print $prod->order_item_id; ?>" src="<?php echo $urlToThumbImage; ?>" alt="<?php echo $prod->product_name; ?>">
	</div>

	<div class="col-md-3 col-lg-3" id="info_<?php print $prod->order_item_id; ?>">
		<span class="mt-0 mb-1 pr_name"><?php echo $prod->product_name; ?></span>
		<div class="row"><div class="col pe-0"><?php print JText::_('COM_SMARTSHOP_QTY'); ?></div><div class="col ps-0 count_block"><input type="number" name="qty[<?php print $prod->order_item_id; ?>]" value="<?php print $this->return_products[$prod->order_item_id]['quantity'] ? (int)$this->return_products[$prod->order_item_id]['quantity'] : 1; ?>" min="1" max="<?php print $prod->product_quantity; ?>" class="" oninput="shopReturn.changeReturnsCount(this, <?php print $prod->order_item_id; ?>);" onfocusout="shopReturn.changeReturnsCount(this, <?php print $prod->order_item_id; ?>);" /></div></div>
	</div> 
	
	<div class="col-md-6 col-lg-6">
		<div class="row <?php if($prod->show != 1){ ?>d-none<?php } ?>" id="return_reason_<?php print $prod->order_item_id; ?>">
			<span class="coll"><?php print JText::_('COM_SMARTSHOP_RETURN_PRODUCT_REASON'); ?></span>
			<span><?php print JHTML::_('select.genericlist', $this->return_status_list,'reason['.$prod->order_item_id.']','class = "inputbox form-select return_reason" size = "1" ','status_id','name', (int)$this->return_products[$prod->order_item_id]['return_status_id']);?></span>
		</div>
		<div class="row <?php if($prod->show != 1){ ?>d-none<?php } ?> pt-2" id="return_comments_<?php print $prod->order_item_id; ?>">
			<span class="coll"><?php print JText::_('COM_SMARTSHOP_COMMENTS_OPTIONAL'); ?>:</span>
			<span><textarea name="comments[<?php print $prod->order_item_id; ?>]" id="comment_<?php print $prod->order_item_id; ?>" class="w-100"><?php print $this->return_products[$prod->order_item_id]['customer_comment']; ?></textarea></span>
			<span class="coll pt-2"><?php print JText::_('COM_SMARTSHOP_COMMENTS_OPTIONAL_AFTER'); ?></span>
		</div>
	</div>
</div>		