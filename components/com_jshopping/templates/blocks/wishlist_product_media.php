<?php $urlToWishlistProductImg = $prod['thumb_image'] ?: $this->no_image; ?>

 <a href="<?php echo $prod['href']?>">
	<img class="card-img-top" src="<?php echo $urlToWishlistProductImg; ?>" alt="<?php echo htmlspecialchars($prod['product_name']);?>">
</a>