<a href="<?php echo $product->product_link; ?>" class="text-body cart-product__title-link">
	<h5 class="card-title"><?php echo $product->name; ?></h5>
</a>

<div class="cart-product__short-description">
	<?php if ($jshopConfig->product_list_show_short_description && !empty($product->getTexts()->short_description)) : ?>
		<div class="text-small text-muted short_description">
			<?php echo JHtml::_('content.prepare', $product->getTexts()->short_description); ?>
		</div>
	<?php endif; ?>
</div>

<?php if (!empty($product->delivery_time) && !empty($jshopConfig->delivery_times_on_product_listing)) : ?>
	<div class="text-small text-muted card-deliveryTime cart-product__delivery-time">
		<?php echo JText::_('COM_SMARTSHOP_STOCK_DELIVERY_TIME'); ?>: <?php echo $product->delivery_time; ?>
	</div>
<?php endif; ?>

<?php if ($this->production_time && $product->production_time > 0) : ?> 
	<div class="text-small text-muted cart-product__production-time"> 
		<?php print JText::_('COM_SMARTSHOP_PRODUCTION_TIME') ?> : <?php print $product->production_time.' '.JText::_('COM_SMARTSHOP_DAYS') ?> 
	</div> 
<?php endif; ?> 

<?php if ($product->manufacturer->name) : ?>
	<p class="card-text text-muted small cart-product__manufacturer-name"><?php echo $product->manufacturer->name; ?></p>
<?php endif; ?>
