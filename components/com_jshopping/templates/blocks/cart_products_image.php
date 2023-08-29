<a href="<?php echo $prod['href']; ?>">
	<?php
		$urlToThumbImage = getPatchProductImage($prod['thumb_image'], '', 1) ?: $this->config->no_image_product_live_path;
	?>
	
	<img class="img-fluid img-cart" src="<?php echo $urlToThumbImage; ?>" alt="<?php echo htmlspecialchars($prod['product_name']); ?>">
</a>