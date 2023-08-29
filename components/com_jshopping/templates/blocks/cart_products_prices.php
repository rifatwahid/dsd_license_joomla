 <span class="d-grid">
	<?php echo formatprice( $prod['price1'] ?? $prod['total_price'] ) ?>
</span>

<?php if ($this->config->show_tax_product_in_cart) : ?>
	<?php if ($prod['tax']>0){?>
	<span class="d-grid mt-1">
		<?php echo productTaxInfo($prod['tax']); ?>
	</span>
	<?php } ?>
	<?php foreach ($prod as $key=>$value){
			if ((substr($key,0,14)=="additional_tax")&&($value>0)) {?>
				<span class="small text-muted d-grid mt-1">
					<?php echo productAdditionalTaxInfo($key,$value); ?>
				</span>
	<?php }} ?>
	<?php print $this->_tmp_ext_html_after_show_product_tax[$key_id] ?? ''; ?>
<?php endif; ?>									

<span class="small text-muted <?php if(!$this->config->single_item_price){ ?> hidden<?php } ?>">
	<?php echo precisionformatprice( $prod['price'] ?? $prod['aprice'] ) ?>
</span>

<?php if ($this->config->show_tax_product_in_cart && $this->config->single_item_price) : ?>
	<?php if ($prod['tax']>0){?>
	<span class="small text-muted d-grid mt-1">
		<?php echo productTaxInfo($prod['tax']); ?>
	</span>
	<?php } ?>
	<?php foreach ($prod as $key=>$value){
			if ((substr($key,0,14)=="additional_tax")&&($value>0)) {?>
				<span class="small text-muted d-grid mt-1">
					<?php echo productAdditionalTaxInfo($key,$value); ?>
				</span>
	<?php }} ?>
	<?php print $this->_tmp_ext_html_after_show_product_tax_single_item_price[$key_id] ?? ''; ?>
<?php endif; ?>

<?php if ($this->config->cart_basic_price_show && $prod['basicprice'] > 0 && $this->config->single_item_price) : ?>
	<span class="small text-muted d-grid mt-1">
		<?php echo sprintBasicPrice($prod); ?>
	</span>
<?php endif; ?>