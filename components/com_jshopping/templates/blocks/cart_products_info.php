 <ul class="list-unstyled">

	<li class="font-weight-bold form-control border-0 ps-0">
		<a class="text-body" href="<?php echo $prod['href']; ?>">
			<?php echo $prod['product_name']; ?>
		</a>
	</li>

	<?php if ($this->config->show_product_code_in_cart && $prod['ean'] != "" ) : ?>
		<li class="text-muted small">
			<?php echo JText::_('COM_SMARTSHOP_PRODUCT_CODE'); ?>: <?php echo $prod['ean']; ?>
		</li>
	<?php endif; ?>

	<?php if ($this->config->show_product_manufacturer_in_cart && !empty($prod['manufacturer_info']['name'])) : ?>
		<li class="manufacturer_name">
			<?php echo JText::_('COM_SMARTSHOP_MANUFACTURER') ?>: <span><?php echo $prod['manufacturer_info']['name']; ?></span>
		</li>
	<?php endif; ?>

	<?php if ($prod['manufacturer'] != '') : ?>
		<li class="text-muted small">
			<?php echo JText::_('COM_SMARTSHOP_MANUFACTURER'); ?>: <?php echo $prod['manufacturer']; ?>
		</li>
	<?php endif; ?>

	<?php if(!empty($prod['editor_attr'])){ ?>
		<li class="list_attribute">
			<?php foreach($prod['editor_attr'] as $val){ ?>
				<p class="jshop_cart_attribute"><span class="name"><?php echo $val ?></span></p>
			<?php } ?>
		</li>
	<?php } ?>
	
	<?php if (!empty($prod['attributes_value']) || !empty($prod['free_attributes_value']) || !empty($prod['extra_fields']) || !empty($prod['_mirror_editor_data'])) : ?>
		<li class="text-muted small">
			<?php 
				echo sprintAtributeInCart($prod['attributes_value']); 
				echo sprintAtributeInCart($prod['_mirror_editor_data'] ?? []);
				echo sprintFreeAtributeInCart($prod['free_attributes_value'], $prod['product_id'], isset($prod['prod_id_of_additional_val']) ? $prod['prod_id_of_additional_val'] : 0);
				echo separateExtraFieldsWithUseHideImageCharactParams($prod['extra_fields'], 'cart');
			?>
		</li>
	<?php endif; ?>
	
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

	<?php if($prod['category_id'] && $prod['category_id'] != 1): ?>
		<li>
			<?php include templateOverrideBlock('blocks', 'cart_upload.php'); ?>
		</li>
	<?php endif; ?>
	
	<?php if ($prod['is_product_from_editor']) : ?>
		<li class="cart-item__back-to-editor">
			<a href="<?php echo $prod['href']; ?>" class="cart-item__back-to-editor--href">
				<?php echo JText::_('COM_SMARTSHOP_BACK_TO_DESIGNING_YOUR_PRODUCT'); ?>
			</a>
		</li>
	<?php endif; ?>
</ul>