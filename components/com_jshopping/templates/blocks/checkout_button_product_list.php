<?php if ($productUsergroupPermissions->is_usergroup_show_price && $productUsergroupPermissions->is_usergroup_show_buy && $product->isShowCartSection() && $jshopConfig->display_checkout_button) : ?>
	<li class="mb-2 d-grid cart-product__checkout">	
		<button type="submit" class="btn btn-outline-primary d-grid" onclick="shopHelper.replaceFormActionText('form#productForm-<?php echo $product->product_id; ?>', '<?php echo $product->checkout_link; ?>')">
			<?php echo JText::_('COM_SMARTSHOP_CHECKOUT'); ?>
		</button>
    </li>
<?php endif; ?>