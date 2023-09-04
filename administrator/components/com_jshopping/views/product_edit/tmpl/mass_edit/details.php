<!-- Publish -->
<tr>
    <td class="key" style="width:180px;">
        <?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
    </td>
    <td>
        <?php echo $this->lists['product_publish'];?>
    </td>
</tr>

<!-- Type -->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_TYPE');?>
    </td>
    <td>
        <?php echo $this->lists['product_packing_type']; ?>
    </td>
</tr>

<!-- Weight -->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT');?>
    </td>
    <td>
        <input type="text" name="product_weight" class="form-control" value="" /> <?php echo sprintUnitWeight(); ?>
    </td>
</tr>

<!--- Expiration date --->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_EXPIRATION_DATE');?>
    </td>
    <td>
        <input type="date" name="expiration_date" class="form-control" id="expiration_date" />
    </td>
</tr>

<!--- Product code --->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT');?>
    </td>
    <td>
        <input type="text" name="product_ean" class="form-control" id="product_ean"/>
    </td>
</tr>

<!-- Amount of product in stock -->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_QUANTITY_PRODUCT');?>
    </td>
    <td>
        <div id="block_enter_prod_qty" style="padding-bottom:2px;">
            <input type="text" name="product_quantity" class="form-control" id="product_quantity" value="" />
        </div>
        <div>         
            <input type="checkbox" class="form-check-input" name="unlimited" id="unlimitedQuantityStatus" value="1" onclick="shopProductCommon.toggleQuantity(this.checked)" /> <?php print JText::_('COM_SMARTSHOP_UNLIMITED');?>
        </div>         
    </td>
</tr>

<!-- Low stock notification -->
<tr class="lowStockNotifyRow">
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_LOW_STOCK_NOTIFY');?>
    </td>
    <td>
        <div id="block_enter_low_stock_notify" style="padding-bottom:2px;">
            <input type="hidden" name="low_stock_notify_status" value="0" />
            <input type="checkbox" name="low_stock_notify_status" value="1" class="form-check-input" id="low_stock_notify_status" />
            <input type="text" name="low_stock_number" id="low_stock_number" class="form-control" disabled/>
        </div>         
    </td>
</tr>

<!--- Factory --->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_FACTORY');?>
    </td>
    <td>
        <input type="text" name="factory" class="form-control" id="factory" />
    </td>
</tr>

<!--- Storage --->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_STORAGE');?>
    </td>
    <td>
        <input type="text" name="storage" class="form-control" id="storage" />
    </td>
</tr>

<!-- Template -->
<?php if ($jshopConfig->use_different_templates_cat_prod) { ?>
    <tr>
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_TEMPLATE_PRODUCT');?>
        </td>
        <td>
            <?php echo $lists['templates'];?>
        </td>
    </tr>
<?php } ?>
    
<!-- Tax -->
<?php if (!$this->withouttax){?>
    <tr>     
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_TAX');?>
        </td>
        <td>
            <?php echo $lists['tax'];?>
        </td>
    </tr>
<?php }?>

<!-- Manufacturer -->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_NAME_MANUFACTURER');?>
    </td>
    <td>
        <?php echo $lists['manufacturers'];?>
    </td>
</tr>

<!-- Categories -->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_CATEGORIES');?>
    </td>
    <td>
        <?php echo $lists['categories'];?>
    </td>
</tr>

<!--- Production time --->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCTION_TIME');?>
    </td>
    <td>
        <input type="number" min="0" class="form-control" name="production_time" />
    </td>
</tr>

<!-- Vendor -->
<?php if ($jshopConfig->admin_show_vendors && $this->display_vendor_select) { ?>
    <tr>
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_VENDOR');?>
        </td>
        <td>
            <?php echo $lists['vendors'];?>
        </td>
    </tr>
<?php }?>

<!-- Delivery time -->
<?php if ($jshopConfig->admin_show_delivery_time) : ?>
    <tr>
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME');?>
        </td>
        <td>
            <?php echo $lists['deliverytimes'];?>
        </td>
    </tr>
<?php endif; ?>

<!-- Label -->
<?php if ($jshopConfig->admin_show_product_labels) : ?>
    <tr>
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_LABEL');?>
        </td>
        <td>
            <?php echo $this->lists['labels'];?>
        </td>
    </tr>
<?php endif; ?>

<!--- NO RETUN --->
<?php if (!$jshopConfig->no_return_all) : ?>  
    <tr>
        <td class="key">
            <?php echo JText::_('COM_SMARTSHOP_NO_RETURN');?>
        </td>
        <td>
            <input type="hidden" name="options[no_return]"  value="0" />
            <input type="checkbox" name="options[no_return]" class="form-check-input" value="1" />
        </td>
    </tr>
<?php endif; ?>

<!--- Quantity --->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_QUANTITY_SELECT_LABLE'); ?>
    </td>
    <td>
        <label for="equal_steps" class="col-form-label">
            <?php echo JText::_('COM_SMARTSHOP_EQUAL_STEPS_LABLE'); ?>
            <input type="hidden" name="equal_steps"  value="0" />
            <input type="checkbox" name="equal_steps" class="form-check-input" value="1"/>
        </label>
        <input name="quantity_select" class="form-control" step="1" type="text" id="quantity_select" size="80" />
    </td>
</tr>

<!--- Max number of items --->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_ERROR_MAX_COUNT_ORDER_ONE_PRODUCT'); ?>
    </td>
    <td>
        <input type="text" class="form-control" id="max_count_product" name="max_count_product"/>
    </td>
</tr>

<!--- Min number of items --->
<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_ERROR_MIN_COUNT_ORDER_ONE_PRODUCT'); ?>
    </td>
    <td>
        <input type="text" class="form-control" name="min_count_product" id="min_count_product" />
    </td>
</tr>

<script>
    document.addEventListener('DOMContentLoaded', function () {
		let lowStockNotifyStatusEl = document.querySelector('#low_stock_notify_status');
		let unlimitedQtyStatusEl = document.querySelector('#unlimitedQuantityStatus');

		if (lowStockNotifyStatusEl) {
			lowStockNotifyStatusEl.addEventListener('click', function () {
				let isDisabled = this.checked ? false: true;
				let lowStockNumberEl = document.querySelector('#low_stock_number');
				if (lowStockNumberEl) {
					lowStockNumberEl.disabled = isDisabled;
				}
			});
		}

		if (unlimitedQtyStatusEl) {
			unlimitedQtyStatusEl.addEventListener('click', function () {
				let lowStockNotifyRowEl = document.querySelector('.lowStockNotifyRow');

				if (lowStockNotifyRowEl) {
					if (this.checked) {
						lowStockNotifyRowEl.classList.add('hidden');
					} else {
						lowStockNotifyRowEl.classList.remove('hidden');
					}
				}
			});
		}
	});
</script>