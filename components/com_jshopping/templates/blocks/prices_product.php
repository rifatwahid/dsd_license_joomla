<?php 
    $pricesProduct = !empty($product->product_id) ? $product : $this->product;
?>

<a href="<?php echo $pricesProduct->product_link; ?>" class="text-body">
    <span class="cart-product__price">
        <?php echo precisionformatprice($this->totalAjaxPrice ?? $pricesProduct->product_price_calculate ?? $pricesProduct->product_price); ?>
    </span>
    
    <?php if ($pricesProduct->product_old_price > 0) : ?>
        <s class="cart-product__price-old">(<?php echo precisionformatprice($pricesProduct->product_old_price); ?>)</s>
    <?php endif; ?>
    <?php 
	if(isset($pricesProduct->product_basic_price_calculate)){	
		$basic_price = $pricesProduct->product_basic_price_calculate;
	}else{
		$basic_price = 0;
	}	?>
    <?php if ($this->show_base_price) :  ?>
        <span class="font-weight-light text-muted text-small basic_price <?php if(!$this->config->single_item_price){ ?> hidden<?php } ?>">
            (<?php echo precisionformatprice($basic_price) ?><?php if(isset($pricesProduct->product_basic_price_unit_name)){ ?> / <?php echo $pricesProduct->product_basic_price_unit_name; ?><?php } ?>)
        </span>
    <?php endif; ?>
    
</a>

<?php if ($this->config->show_plus_shipping_in_product_list) : ?>
    <div class="cart-product__plus-shipping-data">
        <span class="cart-product__plus-shipping">
            <?php echo ' '.JText::sprintf('COM_SMARTSHOP_PLUS_SHIPPING', $this->shippinginfo); ?>
        </span>
    </div>
<?php endif; ?>

<?php 
    unset($pricesProduct);
?>