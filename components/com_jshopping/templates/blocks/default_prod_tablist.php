<section>
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<button class="nav-link active" id="ndescription" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">
				<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?>
			</button>
		</li>

		<li class="nav-item">
			<button class="nav-link" id="nadditional-information" data-bs-toggle="tab" data-bs-target="#additional-information" type="button" role="tab" aria-controls="additional-information" aria-selected="false">
				<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_INFORMATIONS'); ?>
			</button>
		</li>
	</ul>

	<div class="tab-content py-4">
		<div id="description" role="tabpanel" class="tab-pane fade active show" aria-labelledby="ndescription">
			<div id="description__text">
				<?php echo JHtml::_('content.prepare', $this->product->getTexts()->description); ?>
			</div>

			<div id="description__readmore">
				<?php if (!empty($this->product->product_url)) : ?>
					<a target="_blank" href="<?php echo $this->product->product_url; ?>">
						<?php echo JText::_('COM_SMARTSHOP_READ_MORE'); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div id="additional-information" role="tabpanel" class="tab-pane fade" aria-labelledby="nadditional-information">
			<ul class="list-unstyled">
				<!-- EAN -->
				<li id="product_code">
					<?php 
						if ($this->config->show_product_code && !empty($this->product->getEan())) {
							include templateOverrideBlock('blocks', 'code.php');
						}
					?>
				</li>

				<!-- Manufacturer -->
				<?php if ($this->config->product_show_manufacturer && !empty($this->product->manufacturer_info->name)) : ?>
						<li class="manufacturer_name">
							<?php echo JText::_('COM_SMARTSHOP_MANUFACTURER') ?>: <span><?php echo $this->product->manufacturer_info->name; ?></span>
						</li>
					<?php endif; ?>

					<?php if ($this->config->product_show_manufacturer_logo && $this->product->manufacturer_info->manufacturer_logo != "") { ?>
						<li class="manufacturer_logo">
							<a href="<?php print SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view&manufacturer_id=' . $this->product->product_manufacturer_id, 2); ?>">
								<img src="<?php print $this->config->image_manufs_live_path . "/" . $this->product->manufacturer_info->manufacturer_logo ?>" alt="<?php print htmlspecialchars($this->product->manufacturer_info->name); ?>" title="<?php print htmlspecialchars($this->product->manufacturer_info->name); ?>" />
							</a>
						</li>
					<?php } ?>

				<!-- Vendor Information -->
				<?php if ($this->product->vendor_info) : ?>
					<li><?php echo JText::_('COM_SMARTSHOP_VENDOR'); ?>: <?php echo $this->product->vendor_info->shop_name; ?><li>

					<?php if ($this->config->product_show_vendor_detail) : ?>
						<li>
							<a href="<?php echo $this->product->vendor_info->urlinfo; ?>">
								<?php echo JText::_('COM_SMARTSHOP_VENDOR_INFO'); ?>
							</a>
						</li>

						<li>  
							<a href="<?php echo $this->product->vendor_info->urllistproducts; ?>">
								<?php echo JText::_('COM_SMARTSHOP_VENDOR_PRODUCTS'); ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endif; ?>

				<!-- Weight + Basic Price -->
				<?php if ($this->config->product_show_weight && $this->product->getWeight() > 0) : ?>
					<li id="product-weight">
						<span class="product-weight__text"><?php echo JText::_('COM_SMARTSHOP_WEIGHT'); ?></span>: <span class="product-weight__weight"><?php echo formatweight($this->product->getWeight()); ?></span>
					</li>
				<?php endif; ?>

				<div id="product-details__extra-fields">
					<?php include templateOverrideBlock('blocks', 'extra_fields.php'); ?>
				</div>
			</ul>
		</div>
	</div>
</section>
