 <div class="cart-product__code-data">
	<span class="cart-product__code-text"><?php echo JText::_('COM_SMARTSHOP_PRODUCT_CODE'); ?></span>
	<span class="cart-product__code-separator">:</span>
	<span class="cart-product__code"><?php if (method_exists($product,"getEan")) { echo $product->getEan();} ?></span>
</div>