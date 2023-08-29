<!-- Price -->
<div class="form-group row align-items-center">
    <label for="attr_price" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>*
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <input type="text" id="attr_price" class="form-control" style="width:100px;">
    </div>
</div>  

<!-- Old price -->
<div class="form-group row align-items-center">
    <label for="attr_old_price" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_OLD_PRICE'); ?>				
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
        <input type="text" id="attr_old_price" class="form-control" style="width:100px;">
    </div>
</div>

<?php 
    require __DIR__ . '/price_per_consignment.php'; 
    require __DIR__ . '/add_price_per_upload.php';
    require __DIR__ . '/add_depend_usergroup_prices.php';
?>

<!-- Price type -->
<div class="form-group row align-items-center">
    <label for="attr_price_type" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_PRICE_TYPE'); ?>				
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
        <?php echo $this->attrProductAttrPriceTypeSelect; ?>

        <div class="col-sm-9 col-md-10 col-xl-10 col-12">
            <div class = "rows">
                <span class="col-md-3 col-lg-3"><?php echo JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT0'); ?>
                    <input type="radio" name="add_attr_qtydiscount" class="qtydiscount qtydiscount_0" value="0" />
                </span>  

                <span class = "col-md-3 col-lg-3"><?php echo JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT'); ?> 
                    <input type="radio" name="add_attr_qtydiscount" class="qtydiscount qtydiscount_1" value="1" />
                </span>

                <span class = "col-md-3 col-lg-3"><?php echo JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT2'); ?>
                    <input type="radio" name="add_attr_qtydiscount" class="qtydiscount qtydiscount_2" value="2" checked/>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Bye price -->
<?php if ($jshopConfig->admin_show_product_bay_price) : ?>
    <div class="form-group row align-items-center">
        <label for="attr_buy_price" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_PRODUCT_BUY_PRICE'); ?>
        </label>
        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
            <input type="text" id="attr_buy_price" class="form-control" style="width:100px;">
        </div>
    </div>    
<?php endif; ?>

<?php if ($jshopConfig->admin_show_product_basic_price) : ?>
    <!-- Weight volume units -->
    <div class="form-group row align-items-center">
        <label for="attr_weight_volume_units" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_WEIGHT_VOLUME_UNITS'); ?>
        </label>
        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
            <input type="text" id="attr_weight_volume_units" class="form-control" style="width:100px;" value="0.0000">
        </div>
    </div>    

    <!-- Unit measure -->
    <div class="form-group row align-items-center">
        <label for="attr_basic_price_unit_id" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE'); ?>
        </label>
        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
            <?php echo $lists['attr_basic_price_unit_id'];?>
        </div>
    </div>  
<?php endif; ?>