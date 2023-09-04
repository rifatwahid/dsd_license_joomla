<?php foreach ($this->product->product_add_prices as $k => $productAddprice) : 
	if(isset($productAddprice->unit_name) && $productAddprice->unit_name){
		$unitName = $productAddprice->unit_name;
	}else{
		$unitName = $this->product->product_add_price_unit;
	}
    $unitId = $productAddprice->unit_id ?: $this->product->add_price_unit_id;
?>
    <div class="productBulkPrice">
        <span class="productBulkPriceQty">

            <span class="productBulkPriceQtyFrom">
                <?php if ($productAddprice->product_quantity_finish == 0) : ?>
                    <span class="productBulkPriceQtyFrom__from-text">
                        <?php echo JText::_('COM_SMARTSHOP_BULK_FROM') . ' '; ?>
                    </span>
                <?php endif; ?>

                <span class="productBulkPriceQtyFrom__start">
					<?php echo getUnitNumberFormat($unitId, $productAddprice->product_quantity_start); ?>
                </span>
                <span class="productBulkPriceQtyFrom__unit">
                    <?php echo $unitName; ?>
                </span>
            </span>
            
            <?php if ($productAddprice->product_quantity_finish > 0) : ?>
                <span class="productBulkPriceQtyTo">
                    <span class="productBulkPriceQtyTo__delimeter">
                        -
                    </span>
                    <span class="productBulkPriceQtyTo__finish">
                        <?php echo getUnitNumberFormat($unitId, $productAddprice->product_quantity_finish); ?>
                    </span>

                    <span class="productBulkPriceQtyTo__unit">
                        <?php echo $unitName; ?>
                    </span>
                </span>
            <?php endif; ?>
        </span>

        <span class="float-end productBulkPrice" id="pricelist_from_<?php echo $productAddprice->idForElement ?? 0; ?>">
            <i class="productBulkPrice__price">
                <?php echo precisionformatprice($productAddprice->price_wp ?? $productAddprice->price) . ' ' . ($productAddprice->ext_price ?? 1); ?>
            </i>
            <i class="productBulkPrice__delimeter">
                /
            </i>
            <i class="productBulkPrice__unit">
                <?php echo $unitName; ?>
            </i>
        </span>
    </div>
<?php endforeach; ?>