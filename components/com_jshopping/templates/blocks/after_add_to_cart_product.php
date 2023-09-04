<?php if (!$this->hide_buy && $this->product->isShowCartSection()) : ?>		
	<div class="tmpProductHtmlAfterAddToCart__wrapper">	
		<?php echo $this->_tmp_product_html_after_add_to_cart ?? ''; ?>	
	</div>
<?php endif; ?>