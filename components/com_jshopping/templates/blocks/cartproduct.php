<?php 
	$urlToThumbImage = !empty($prod['thumb_image']) ? getPatchProductImage($prod['thumb_image'], '', 1): $this->config->no_image_product_live_path;
?>

<li class=" list-group-item cart-products__item">
	<div class="row">
		<div class="col-sm-3">
			<img class="img-fluid img-cart" src="<?php echo $urlToThumbImage; ?>" alt="<?php echo htmlspecialchars($prod['product_name']); ?>">
		</div>

		<div class="col">
			<div class="media-body row">
				<div class="col-md-6 col-lg-7">
					<ul class="list-unstyled">
						<li class="font-weight-bold form-control border-0 ps-0">
							<?php echo $prod['product_name']; ?>

							<?php if (!empty($prod['uploadData'])) {
								echo sprintPreviewNativeUploadedFiles($prod['uploadData']); 
							} ?>
						</li>

						<?php if ($this->config->show_product_code_in_cart && $prod['ean'] != '') : ?>
							<li class="text-muted small">
								<?php echo JText::_('COM_SMARTSHOP_PRODUCT_CODE'); ?>: <?php echo $prod['ean']; ?>
							</li>
						<?php endif; ?>

						<?php if ($prod['manufacturer'] != '') : ?>
							<li class="text-muted small">
								<?php echo JText::_('COM_SMARTSHOP_MANUFACTURER'); ?>: <?php echo $prod['manufacturer']; ?>
							</li>
						<?php endif; ?>

						<?php if(!empty($prod['editor_attr'])){ ?>
							<div class="list_attribute">
								<?php foreach($prod['editor_attr'] as $val){ ?>
									<p class="jshop_cart_attribute"><span class="name"><?php echo $val ?></span></p>
								<?php } ?>
							</div>
						<?php } ?>
						
						<?php 
							echo sprintAtributeInCart($prod['attributes_value']); 
							echo sprintAtributeInCart($prod['_mirror_editor_data'] ?? '');
							echo sprintFreeAtributeInCart($prod['free_attributes_value'],$prod['product_id'],isset($prod['prod_id_of_additional_val']) ? $prod['prod_id_of_additional_val'] : 0);
							echo separateExtraFieldsWithUseHideImageCharactParams($prod['extra_fields'], 'checkout');
						?>

						<?php if ($this->config->show_delivery_time_step5 && $prod['delivery_times_id']) : ?>
							<li class="text-muted small">
								<?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME'); ?>: <?php echo $this->deliverytimes[$prod['delivery_times_id']]; ?>
							</li>
						<?php endif; ?>

						<?php if ($this->production_time && $prod['production_time'] > 0) : ?>
							<li class="text-small text-muted">
								<?php print JText::_('COM_SMARTSHOP_PRODUCTION_TIME') ?> : <?php print $prod['production_time'].' '.JText::_('COM_SMARTSHOP_DAYS') ?>
							</li>
						<?php endif; ?>
						
					</ul>
				</div>

				<div class="col-md-3 col-lg-2 md-text-center">
					<span class="d-md-none"><?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>:</span> <?php echo $prod['quantity']; ?>
				</div>

				<div class="col-md-3 border-0 text-md-end smartshop_cart_price_tax_cell">
					<span class="d-block">
						<?php echo formatprice($prod['price1'] ?? $prod['total_price']); ?>
					</span>

					<?php if ($this->config->show_tax_product_in_cart) : ?>
						<?php if ($prod['tax']>0) {?>
						<span class="d-block mt-1"><?php echo productTaxInfo($prod['tax']); ?></span>
						<?php } ?>
						<?php foreach ($prod as $key=>$value){
							if ((substr($key,0,14)=="additional_tax")&&($value>0)) {
							?>
							<span class="d-block mt-1"><?php echo productAdditionalTaxInfo($key,$value); ?></span>
						<?php }} ?>
						
						<?php print $this->_tmp_ext_html_after_show_product_tax[$key_id] ?? ''; ?>
					<?php endif; ?>

					<?php if($this->config->single_item_price): ?>	
						<span class="small text-muted">
							<?php echo precisionformatprice($prod['price1'] ?? $prod['price']) ?>
						</span>

						<?php if ($this->config->show_tax_product_in_cart ) : ?>
							<?php if ($prod['tax']>0) {?>
							<span class="small text-muted d-block mt-1"><?php echo productTaxInfo($prod['tax']); ?></span>
							<?php } ?>
							<?php foreach ($prod as $key=>$value){
								if ((substr($key,0,14)=="additional_tax")&&($value>0)) {
								?>
								<span class="small text-muted d-block mt-1"><?php echo productAdditionalTaxInfo($key,$value); ?></span>
							<?php }} ?>
							<?php print $this->_tmp_ext_html_after_show_single_product_tax[$key_id] ?? ''; ?>
						<?php endif; ?>

						<?php if ($this->config->cart_basic_price_show && $prod['basicprice'] > 0) : ?>
							<span class="small text-muted d-block mt-1"><?php echo sprintBasicPrice($prod); ?></span>
						<?php endif; ?>
					<?php endif; ?>
				</div>

			</div>
		</div>
	<div>
</li>