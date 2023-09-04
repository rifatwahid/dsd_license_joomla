<?php 
    $pricesProduct = !empty($this->product->product_id) ? $this->product : $product;
	$qties = explode(',',$this->product->quantity_select); 
	$default_count_product = $qties[0] ?: $this->default_count_product;
    $calculatedProductPrice = $pricesProduct->getPriceCalculate($default_count_product);
    $isShowProductTax = $this->config->show_tax_in_product && !empty($pricesProduct->product_tax);
	$product_basic_price_unit_name = $pricesProduct->product_basic_price_unit_name ?? '';
?>

<div class="d-flex align-items-center justify-content-between my-4">
    <ul class="list-unstyled">

        <?php if ($pricesProduct->product_old_price > 0) : ?>
            <li class="text-danger font-weight-light">
                <del><?php echo precisionformatprice($pricesProduct->product_old_price); ?></del>
            </li>
        <?php endif; ?>

        <li class="h4">
            <?php if($this->config->single_item_price): ?>
                <span id="block_price">
                    <?php echo precisionformatprice($pricesProduct->product_price_calculate); ?>
                </span>
            <?php endif; ?>

            <?php if ($pricesProduct->product_basic_price_show) : ?>
                <span class="font-weight-light text-muted text-small">
                    (<?php echo precisionformatprice($pricesProduct->product_basic_price_calculate ?? 0) . ' / ' . $product_basic_price_unit_name; ?>)
                </span>
            <?php endif; ?>

            <?php if (!empty($calculatedProductPrice)) : ?>
                <div id="product-current-price">
                    <?php echo formatprice($calculatedProductPrice); ?>
                </div>
            <?php endif; ?>
        </li>

        <?php if ($isShowProductTax || $this->config->show_plus_shipping_in_product) : ?>
            <li class="text-muted font-weight-light">
                <?php 
                    if ($isShowProductTax) {
                        echo productTaxInfo($pricesProduct->product_tax);
                    }

                    if ($this->config->show_plus_shipping_in_product)  {
                        echo ' '.JText::sprintf('COM_SMARTSHOP_PLUS_SHIPPING', $this->shippinginfo);
                    }
                ?>
            </li>
        <?php endif; ?>

    </ul>
</div>

<?php
    unset($pricesProduct);
    unset($calculatedProductPrice);
    unset($isShowProductTax);
?>