<div class="cart-product__weight-data">
	<span class="cart-product__weight-text"><?php echo JText::_('COM_SMARTSHOP_WEIGHT'); ?></span>
	<span class="cart-product__weight-separator">: </span>
	<span class="cart-product__weight"><?php echo formatweight($product->preview_calculated_weight ?? $product->getWeight()); ?></span>
</div>