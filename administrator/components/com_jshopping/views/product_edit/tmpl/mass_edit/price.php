<!-- Price -->
<script>
	var consigment_rows_number = 0; 
</script>
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE'); ?>
    </td>
    <td>
        <div class="row m-0">
            <div class="col-3 p-0">
                <?php echo $this->lists['price_mod_price'];?>
            </div>
            <div class="col-3 p-0">
                <input type="text" name="product_price" class="form-control ml-1 ms-1 mr-1 me-1" value=""/>
            </div>
            <div class="col-4 p-0">
                <?php echo $this->lists['currency']; ?>
            </div>
        </div>
    </td>
</tr>

<!-- Old price -->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_OLD_PRICE');?>
    </td>
    <td>
        <div class="row m-0" id="foldprice">
            <div class="col-3 p-0">
                <?php echo $this->lists['price_mod_old_price']; ?>
            </div>
            <div class="col-3 p-0">
                <input type="text" name = "product_old_price" class="form-control ml-1 ms-1" value = ""/>
            </div>
        </div>
        <input type="checkbox" name="use_old_val_price" class="form-check-input" value="1" onclick="shfoldprice(this.checked)"> <?php echo JText::_('COM_SMARTSHOP_USE_OLD_VALUE_PRICE'); ?>
    </td>
</tr>

<!-- Price per consignment -->
<?php include __DIR__ . '/price_per_consignment.php'; ?>

<!-- Price per consignment upload -->
<tr id="tr_add_price">
    <td>
        <label for="is_activated_price_per_consignment_upload">
            <?php echo JText::_('COM_SMARTSHOP_NATIVE_PROGRESS_UPLOADS_PRICES'); ?>
        </label>
    </td>
    <td>
        <input type="hidden" name="is_activated_price_per_consignment_upload" value="0">
        <input type="checkbox" class="nativeProgressUploadsAddPrices__checker form-check-input" name="is_activated_price_per_consignment_upload" id="is_activated_price_per_consignment_upload" value="1" onclick="shopHelper.showHideByChecked(this, '#nativeUploadPricesWrapper', 'block')"/>

        <div id="nativeUploadPricesWrapper" class="display--none">
            <?php include __DIR__ . '/../elements/native_progress_uploads/table_of_add_prices.php'; ?>
        </div>
    </td>
</tr>

<!-- Prices for User Group -->
<tr>
    <td colspan="2">
        <div class="usergoup_price_block"></div>
        <div style="margin-left: 18px;">
            <?php include __DIR__ . '/../elements/usergroup_prices/add_new.php' ?>
        </div>
    </td>
</tr>

<!-- Price type -->
<tr>
    <td for="product_price_type">
        <?php echo JText::_('COM_SMARTSHOP_PRICE_TYPE'); ?>
    </td>
    <td>
        <?php 
            echo $this->lists['priceType']; 
            include __DIR__ . '/price_type_qty.php';
        ?>
    </td>
</tr>

<?php if ($jshopConfig->admin_show_product_bay_price) { ?>
    <tr>
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_PRODUCT_BUY_PRICE'); ?>
        </td>
        <td>
            <input type="text" name="product_buy_price" value="" />
        </td>
    </tr>
<?php } ?>

<?php if ($jshopConfig->admin_show_product_basic_price) : ?>
    <!-- Basic price -->
    <tr>
        <td class="key">
            <br/><?php echo JText::_('COM_SMARTSHOP_BASIC_PRICE'); ?>
        </td>
    </tr>
    
    <!-- Price per unit of measure -->
    <tr>
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_WEIGHT_VOLUME_UNITS'); ?>
        </td>
        <td>
            <input type="text" name="weight_volume_units" class="form-control" />
        </td>
    </tr>

    <!-- Unit of measure -->
    <tr>
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE'); ?>
        </td>
        <td>
            <?php echo $lists['basic_price_units']; ?>
        </td>
    </tr>
<?php endif; ?>

<script type="text/javascript">
    <?php include __DIR__ . '/../default_scripts.php'; ?>
</script>