<?php if ($product->label_id) : ?>
		<div class="product_label">
		<?php if ($product->_label_image) : ?>
			<img src="<?php echo getPatchProductImage($product->_label_image, '', 1); ?>" alt="<?php echo htmlspecialchars($product->_label_name); ?>" />
		<?php else : ?>
			<span class="label_name"><?php echo $product->_label_name; ?></span>
		<?php endif; ?>
	</div>
<?php endif; ?>

<a href="<?php echo $product->product_link; ?>" class="cart-product__img-link">
	<img class="card-img-top" src="<?php echo getPatchProductImage($image, '', 1); ?>" alt="<?php echo htmlspecialchars($product->name); ?>">
</a>