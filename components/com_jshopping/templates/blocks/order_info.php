<?php  
	if(!is_array($prod->files)){
		$files = unserialize($prod->files);				
	}else{
		$files = $prod->files;		
	}
 ?>
<li class="list-group-item py-3">
	<div class="media">
		<?php if (empty($prod->thumb_image)) { $prod->thumb_image = $this->config->noimage; } ?>	
		<div class="media-body">
							
		 <div class="row">
			<div class="col-md-4 col-lg-3">
				<?php 
					$urlToThumbImage = $this->config->no_image_product_live_path;
					
					if (!empty($prod->thumb_image)) {
						$urlToThumbImage = getPatchProductImage($prod->thumb_image, '', 1);
					}
				?>
				<img class="mr-3 order-thumbnail" src="<?php echo $urlToThumbImage; ?>" alt="<?php echo $prod->product_name; ?>">
			</div>

			<div class="col-md-8 col-lg-9">

				<h5 class="mt-0 mb-1"><?php echo $prod->product_name; ?></h5>

				<ul class="list-unstyled">
					<?php if ($this->config->show_product_code_in_order && $prod->product_ean != "") : ?>
						<li class="text-muted small"><?php echo JText::_('COM_SMARTSHOP_PRODUCT_CODE'); ?>: <?php echo $prod->product_ean; ?></li>
					<?php endif; ?>

					<?php if ($prod->manufacturer !="") : ?>
						<li class="text-muted small"><?php echo JText::_('COM_SMARTSHOP_MANUFACTURER'); ?>: <?php echo $prod->manufacturer; ?></li>
					<?php endif; ?>					
					
					<?php if ($prod->product_attributes || $prod->product_freeattributes || $prod->extra_fields) : ?>
						<li class="text-muted small">
							<?php if ($prod->product_attributes) : ?>
								<div class="list_attribute"><?php echo nl2br($prod->product_attributes); ?></div>
							<?php endif; ?>
							<?php if (isset($prod->_mirror_editor_data) && $prod->_mirror_editor_data) : ?>
								<div class="list_attribute"><?php echo nl2br($prod->_mirror_editor_data); ?></div>
							<?php endif; ?>
							<?php if ($prod->product_freeattributes) : ?>
								<div class="list_free_attribute"><?php echo nl2br($prod->product_freeattributes); ?></div>
							<?php endif; ?>
							<?php if (!empty($prod->extra_fields) && is_array(json_decode($prod->extra_fields))) { ?>
								<div class="list_extra_field"><?php echo separateExtraFieldsWithUseHideImageCharactParams(json_decode($prod->extra_fields), 'my_orders'); ?></div>
							<?php }else{ ?>
								<div class="list_extra_field"><?php echo $prod->extra_fields; ?></div>
							<?php } ?>
						</li>
					<?php endif; ?>
					
					<?php if (!empty($files) && $this->isOrderHasBeenPaid) : ?>
						<li class="filelist">
							<?php foreach($files as $file) : ?>
								<?php if($file->file){ ?>
									<div class="file">
										<span class="descr">
											<?php print $file->file_descr?>
										</span>
										<a class="download" href="<?php print JURI::root()?>index.php?option=com_jshopping&controller=product&task=getfile&oid=<?php print $this->order->order_id?>&id=<?php print $file->id?>&hash=<?php print $this->order->file_hash;?>">
											<?php echo JText::_('COM_SMARTSHOP_DOWNLOAD');?>
										</a>
									</div>
								<?php } ?>
							<?php endforeach; ?>
						</li>
					<?php endif; ?>
					
					<?php if (($prod->publish_editor_pdf==1) && ($order->products_pdf[$prod->product_id]!="") && $this->isOrderHasBeenPaid) {?>
					<div class="file">
						<?php echo $order->products_pdf[$prod->product_id];?>
					</div>
					<?php } ?>

					
					<li><?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>: <?php echo precisionformatprice($prod->product_item_price, $order->currency_code); ?></li>
					<li>

						<?php if(!$isUpoad){ ?>
							<?php if (!empty($prod->uploadData)) { echo sprintPreviewNativeUploadedFiles($prod->uploadData); } ?>
						<?php }else{ ?>
							<?php include templateOverrideBlock('blocks', 'order_upload.php');//__DIR__ . '/cart_upload.php'; ?>
						<?php } ?>
					</li>
				</ul>

				<ul class="list-unstyled mt-4">
					<li><?php echo JText::_('COM_SMARTSHOP_COUNT'); ?>: <span class="float-md-right"><?php echo formatqty($prod->product_quantity);?></span></li>
					<li><?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>: <span class="float-md-right"><?php echo formatprice($prod->product_item_price * $prod->product_quantity, $order->currency_code); ?></span></li>
				</ul>

				<input type="hidden" name="quantity[<?php echo $key_id; ?>]" id="quantity[<?php echo $key_id; ?>]" value="<?php echo $prod->product_quantity; ?>"   />
			</div> 

		</div>
		</div> 
	</div> 
</li> 