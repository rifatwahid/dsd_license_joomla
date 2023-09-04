 <a href="<?php echo $prod['href']; ?>" class="text-body">
	<h5 class="card-title"><?php echo $prod['product_name']; ?></h5>
</a>

<?php if ($prod['manufacturer']!='') : ?>
	<p class="card-text text-muted small"><?php echo $prod['manufacturer']; ?></p>
<?php endif; ?>

<?php if ($prod['attributes_value'] || $prod['free_attributes_value'] || $prod['extra_fields']) : ?>
	<div class="text-muted small mb-2">
		<?php 
			echo sprintAtributeInCart($prod['attributes_value']);
			echo sprintFreeAtributeInCart($prod['free_attributes_value'], $prod['product_id'], isset($prod['prod_id_of_additional_val']) ? $prod['prod_id_of_additional_val'] : 0);
			echo sprintFreeExtraFiledsInCart($prod['extra_fields']);
		?>
	</div>
<?php endif; ?>
<span class="d-grid text-muted small mb-2"><?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>: <?php echo $prod['quantity'] ?? ''?><?php echo $prod['_qty_unit'] ?? ''; ?></span>

<a href="<?php echo $prod['href'] ?>" class="text-body">
	<?php echo precisionformatprice($prod['price']); ?>
</a>
							