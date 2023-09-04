<!-- Type -->
<!-- <div class="form-group row align-items-center">
    <label for="attr_weight" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label col-form-label-sm">
        <?php echo JText::_('COM_SMARTSHOP_TYPE'); ?>
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <?php echo $this->lists['attr_product_packing_type']; ?>
    </div>
</div> -->

<!-- Weight -->
<div class="form-group row align-items-center">
    <label for="attr_weight" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT')?>
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <input type="text" id="attr_weight" class="form-control" style="width:100px;" value="<?php echo $row->product_weight; ?>"> <?php echo sprintUnitWeight(); ?>
    </div>
</div>

<!-- Expiration date -->
<div class="form-group row align-items-center">
    <label for="attr_expiration_date" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_EXPIRATION_DATE')?>
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
        <input type="date" id="attr_expiration_date" class="form-control" style="width:100px;" value="<?php echo $row->expiration_date; ?>">
    </div>
</div>    

<!-- Product code -->
<div class="form-group row align-items-center">
    <label for="attr_ean" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); ?>
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <input type="text" id="attr_ean" class="form-control" style="width:100px;" value="<?php echo $row->product_ean; ?>">
    </div>
</div> 

<?php if ($jshopConfig->stock) : ?>
    <!-- Qty -->
    <div class="form-group row align-items-center">
        <label for="attr_count" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_QUANTITY_PRODUCT'); ?>*
        </label>
        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
            <div id="block_enter_attr_qty">
                <input type="text" class="form-control" id="attr_count"  style="width:100px;" value="1">		
            </div>
            <div>         
                <input type="checkbox" class="form-check-input" id="attr_unlimited" value="1" onclick="shopProductCommon.toggleAttrQuantity(this.checked)"  /> <?php echo JText::_('COM_SMARTSHOP_UNLIMITED'); ?>
            </div> 
        </div>
    </div>  

    <!-- Low stock notify   -->
    <div class="form-group row align-items-center">
        <label for="low_stock_attr_notify_status" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_LOW_STOCK_ATTR_NOTIFY'); ?>
        </label>
        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">	
            <input type="checkbox" class="form-check-input" id="low_stock_attr_notify_status">
            <input type="number" class="form-control" id="low_stock_attr_notify_number" style="width:100px;" value="0">  
        </div>
    </div>    
<?php endif; ?>

<!-- Factory -->
<div class="form-group row align-items-center factory_dep_attr">
    <label for="attr_factory" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_FACTORY')?>
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <input type="text" class="form-control" id="attr_factory" style="width:100px;">
    </div>
</div>

<!-- Storage -->
<div class="form-group row align-items-center storage_dep_attr">
    <label for="attr_storage" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_STORAGE')?>
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <input type="text" class="form-control" id="attr_storage" style="width:100px;">
    </div>
</div>

<!-- Tax -->
<?php if (!$this->withouttax) : ?>
    <div class="form-group row align-items-center">
        <label for="attr_tax" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_TAX')?>
        </label>
        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
            <?php echo $lists['attr_tax']; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Manufacturer name -->
<div class="form-group row align-items-center">
    <label for="attr_manufacturer_name" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_NAME_MANUFACTURER')?>
    </label>
    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <?php echo $lists['attr_manufacturers']; ?>
    </div>
</div>

<!-- Production time -->
<div class="form-group row align-items-center">
    <label for="attr_production_time" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCTION_TIME')?>
    </label>

    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <input type="number" class="form-control" min="0" id="attr_production_time"/>
    </div>
</div>

<!-- Delivery time -->
<?php if ($jshopConfig->admin_show_delivery_time) : ?>	
    <div class="form-group row align-items-center">
        <label for="attr_delivery_time" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME')?>
        </label>

        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
            <?php echo $lists['attr_deliverytimes']; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Label -->
<?php if ($jshopConfig->admin_show_product_labels) : ?>	
    <div class="form-group row align-items-center">
        <label for="attr_label" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_LABEL')?>
        </label>

        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
            <?php echo $lists['attr_labels']; ?>
        </div>
    </div>
<?php endif; ?>

<!-- No return -->
<?php if (!$jshopConfig->no_return_all) : ?>	
    <div class="form-group row align-items-center">
        <label for="attr_no_return" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_NO_RETURN')?>
        </label>

        <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
            <input type="hidden" name="attr_no_return"  value="0" />
            <input type="checkbox" class="form-check-input" name="attr_no_return" id="attr_no_return" value="1" />
        </div>
    </div>
<?php endif; ?>

<!-- Quantity(,) -->
<div class="form-group row align-items-center">
    <label for="attr_quantity_select" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_QUANTITY_SELECT_LABLE')?>
    </label>

    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">	
        <label for="equal_steps" class="col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_EQUAL_STEPS_LABLE'); ?>
            <input type="hidden" name="attr_equal_steps"  value="0" />
            <input type="checkbox" class="form-check-input" name="attr_equal_steps" value="1"/>
        </label>

        <input type="text" class="form-control" id="attr_quantity_select" size="80" />
    </div>
</div>

<!-- Maximum number of items to be able to order -->	
<div class="form-group row align-items-center">
    <label for="attr_max_count_product" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_ERROR_MAX_COUNT_ORDER_ONE_PRODUCT')?>
    </label>

    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <input type="text" class="form-control" id="attr_max_count_product" />
    </div>
</div>

<!-- Minimum number of items to be able to order -->
<div class="form-group row align-items-center">
    <label for="attr_min_count_product" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_ERROR_MIN_COUNT_ORDER_ONE_PRODUCT')?>
    </label>

    <div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
        <input type="text" class="form-control" id="attr_min_count_product" />
    </div>
</div>