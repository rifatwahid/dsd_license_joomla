<?php if (!$this->hide_buy && $this->product->isShowCartSection() && $jshopConfig->display_checkout_button) :  ?>
	<li class="d-grid" id="checkout_button__product">
		<button type="submit" class="btn btn-outline-primary d-grid btn-add-product-to-checkout" onclick="document.querySelector('#productForm').setAttribute('action', '<?php echo $this->toCheckout; ?>')">
			<?php echo JText::_('COM_SMARTSHOP_CHECKOUT'); ?>
		</button>
	</li>
<?php endif; ?>