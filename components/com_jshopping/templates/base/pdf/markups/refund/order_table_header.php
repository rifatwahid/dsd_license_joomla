<!-- Product name -->
<td class="orderListHeader__column orderListHeader__prodInfo" bgcolor="#c8c8c8" colspan="3">
    <?php echo JText::_('COM_SMARTSHOP_NAME_PRODUCT'); ?>
</td>

<!-- Product code -->
<?php if (!empty($jshopConfig->show_product_code_in_order)) : $sizeOfLeftColspanFooter++; ?>
    <td class="orderListHeader__column orderListHeader__prodCode" bgcolor="#c8c8c8">
        <?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); ?>
    </td>
<?php endif; ?>

<!-- Product qty -->
<td class="orderListHeader__column orderListHeader__prodQty" bgcolor="#c8c8c8">
    <?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>
</td>
    
<!-- Product single price -->
<?php if (!empty($jshopConfig->single_item_price)) : $sizeOfLeftColspanFooter++; ?>
    <td class="orderListHeader__column orderListHeader__prodPrice" bgcolor="#c8c8c8">
        <?php echo JText::_('COM_SMARTSHOP_SINGLEPRICE'); ?>
    </td>
<?php endif; ?>

<!-- Total price -->
<td class="orderListHeader__column orderListHeader__prodTotal" bgcolor="#c8c8c8">
    <?php echo JText::_('COM_SMARTSHOP_TOTAL'); ?>
</td>