<?php if (!empty($this->attributes)) : ?>
	<?php foreach ($this->attributes as $attribut) : ?>
		<?php if ($attribut->attr_type == 3 || ($attribut->expiration == 1 && $product->product_packing_type == 1)) : ?>											
			<div class="display--none">
				<?php echo $attribut->selects; ?>
			</div>												
		<?php else : ?>
			<?php if ($attribut->grshow && !$attribut->hide_title) : ?>
				<h5 class="mb-3"><?php echo $attribut->groupname; ?></h5>
			<?php endif; ?>

			<div class="mb-2 jshop_prod_attributes <?php if( !$attribut->selects ){ print 'display--none'; } ?>">
				<label class="d-grid">
					<span class="h6"><?php echo $attribut->attr_name; ?></span>
					<?php if (!empty($attribut->attr_description)) : ?>
						<p class="text-muted text-small mt-1"><?php echo $attribut->attr_description; ?></p>
					<?php endif; ?>

					<span id='block_attr_sel_<?php echo $attribut->attr_id; ?>'>
						<?php echo $attribut->selects; ?>
					</span>
				</label>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>