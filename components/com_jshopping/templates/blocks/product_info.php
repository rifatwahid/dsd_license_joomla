<h1><?php echo $this->product->name; ?></h1>

<!-- Ratings -->
<?php if ($this->allow_review && $this->product->reviews_count > 0) {
	include templateOverrideBlock('blocks', 'ratingandhits.php');
} ?>

<?php if($this->config->template == 'base' && $this->product->product_template == 'default'){ ?>
	<!-- Old Price, Price, Tax Info, Shipping Info -->
	<div id="product-details__prices">
		<?php if ($productUsergroupPermissions->is_usergroup_show_price) {
			include templateOverrideBlock('blocks', 'prices.php');
		}?>
	</div>
<?php } ?>
			
<!-- Manufacturer -->
<?php if ($this->config->product_show_manufacturer && !empty($this->product->manufacturer_info->name)) : ?>
	<div class="manufacturer_name">
		<?php echo JText::_('COM_SMARTSHOP_MANUFACTURER') ?>: <span><?php echo $this->product->manufacturer_info->name; ?></span>
	</div>
<?php endif; ?>

<?php if ($this->config->product_show_manufacturer_logo && $this->product->manufacturer_info->manufacturer_logo != "") : ?>
	<div class="manufacturer_logo">
		<a href="<?php print SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view&manufacturer_id=' . $this->product->product_manufacturer_id, 2); ?>">
			<img src="<?php print $this->config->image_manufs_live_path . "/" . $this->product->manufacturer_info->manufacturer_logo ?>" alt="<?php print htmlspecialchars($this->product->manufacturer_info->name); ?>" title="<?php print htmlspecialchars($this->product->manufacturer_info->name); ?>" />
		</a>
	</div>
<?php endif; ?>

<div id="product-details__short-description">
	<?php if ($this->config->product_show_short_description && !empty($this->product->getTexts()->short_description)) : ?>
		<p class="mb-4 text-muted">
			<?php echo JHtml::_('content.prepare', $this->product->getTexts()->short_description); ?>
		</p>
	<?php endif; ?>
</div>