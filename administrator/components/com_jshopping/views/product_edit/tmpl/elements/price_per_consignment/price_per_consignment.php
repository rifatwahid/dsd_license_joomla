<?php
    $productIsAddPriceLabelClass = (isset($productIsAddPriceLabelClass) && $productIsAddPriceLabelClass) ? $productIsAddPriceLabelClass : 'col-sm-3 col-md-2 col-xl-2 col-12 col-form-label';
    $productIsAddPriceCheckboxRowClass = (isset($productIsAddPriceCheckboxRowClass) && $productIsAddPriceCheckboxRowClass) ? $productIsAddPriceCheckboxRowClass : 'col-sm-9 col-md-10 col-xl-10 col-12';
    $classNameOfLabelRowAttrAddprice = (isset($classNameOfLabelRowAttrAddprice) && $classNameOfLabelRowAttrAddprice) ? $classNameOfLabelRowAttrAddprice : 'col-sm-3 col-md-2 col-xl-2 col-12 col-form-label';
    $classNameOfTableRowAttrAddprice = (isset($classNameOfTableRowAttrAddprice) && $classNameOfTableRowAttrAddprice) ? $classNameOfTableRowAttrAddprice : 'col-sm-9 col-md-10 col-xl-10 col-12 table-responsive';
?>

<div class="form-group row align-items-center">
    <label for="<?php echo $consignmentPrefix; ?>product_is_add_price" class="<?php echo $productIsAddPriceLabelClass; ?>">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?>
    </label>
    <div class="<?php echo $productIsAddPriceCheckboxRowClass; ?>">
        <input type="checkbox" name="<?php echo $consignmentPrefix; ?>product_is_add_price" id="<?php echo $consignmentPrefix; ?>product_is_add_price" class="form-check-input" value="1" <?php if (isset($row->product_is_add_price) && $row->product_is_add_price) echo 'checked="checked"';?>  onclick="showHideAddPrice()" />
    </div>
</div>

<div id="tr_add_price" class="form-group row align-items-center">
    <label class="<?php echo $classNameOfLabelRowAttrAddprice; ?>">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE'); ?>
    </label>

    <div class="<?php echo $classNameOfTableRowAttrAddprice; ?>">
        <table id="<?php echo $consignmentPrefix; ?>table_add_price" class="table table-striped">
            <thead>
                <tr>
                    <th  width="20%">
                        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_START'); ?>    
                    </th>
                    <th width="20%">
                        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_FINISH'); ?>    
                    </th>
                    <th width="22%">
                        <?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>
                    </th>
                    <th width="22%">
                        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE'); ?>
                    </th>          
                    <?php $pkey='plugin_consignment_attr_title'; if (isset($row->$pkey) && $row->$pkey){ echo $row->$pkey; } ?>
                    <th width="16%">
                        <?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>    
                    </th>
                </tr>
            </thead>                
            <tbody>
                <?php require __DIR__ . '/consignment_rows.php'; ?>              
            </tbody>
        </table>

        <table class="table table-striped">
            <tr>
                <td>
                    <?php 
                        $add_price_units = !empty($consignmentPrefix) ? $attr_price_per_consignment_basic_price_unit_id : $lists['add_price_units'];
                        echo $add_price_units . ' - ' . JText::_('COM_SMARTSHOP_UNIT_MEASURE');
                    ?>
                </td>
                <td align="right" width="100">
                    <input class="btn button btn-primary" type="button" name="<?php echo $consignmentPrefix; ?>add_new_price" onclick="shopProductPrice.add('<?php echo $consignmentPrefix; ?>');<?php $pkey='plugin_consignment_attr_button'; if ($row->$pkey){ echo $row->$pkey; }?>" value="<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD'); ?>" />
                </td>
            </tr>
        </table>
			
    </div>
</div>