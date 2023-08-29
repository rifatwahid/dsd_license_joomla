<?php 
    $jshopConfig = $additionalData['jshopConfig'];
    $sizeOfLeftColspanFooter = 3; // varies depending on the quantity of table headers.
    $sizeOfRightColspanFooter = 2; // varies depending on the quantity of table headers.
?>

<table class="orderList"  border="1" cellpadding="3">
    <!--- Titles --->
    <tr class="orderListHeader__row orderList__header orderListHeader" nobr="true">
        <?php include templateOverride('pdf/markups/' . $pdf::PDF_TYPE, 'order_table_header.php'); ?>
    </tr>
    <!--- Titles END --->
    
    <!--- Order products list --->
    <tbody class="orderList__body orderListBody">
        <?php include templateOverride('pdf/markups/' . $pdf::PDF_TYPE, 'order_table_items_loop.php'); ?>
    </tbody>
    <!--- Order products list END --->

    <tfoot class="orderList__footer orderListFooter">
        <!-- Subtotal -->
        <?php if (!$isHideSubtotal) : ?>
            <tr class="orderListFooter__row orderListFooter__subtotal" bgcolor="#c8c8c8">
                <td colspan="<?php echo $sizeOfLeftColspanFooter; ?>" class="orderListFooter__columnText">
                    <?php echo JText::_('COM_SMARTSHOP_SUBTOTAL'); ?><?php if ($order->order_discount != 0) : ?> (<?php echo JText::_('COM_SMARTSHOP_BEFORE_DISCOUNT'); ?>)<?php endif; ?>
                </td>
                
                <td colspan="<?php echo $sizeOfRightColspanFooter; ?>" class="orderListFooter__columnPrice">
                    <?php echo formatprice($refund->refund_subtotal, $order->currency_code, 0, -1) . $order->_pdf_ext_subtotal; ?>
                </td>
            </tr>
        <?php endif; ?>

        <!-- Discount -->
        <?php if ($refund->refund_discount != 0) : ?>
            <tr class="orderListFooter__row orderListFooter__discount" bgcolor="#c8c8c8">
                <td colspan="<?php echo $sizeOfLeftColspanFooter; ?>" class="orderListFooter__columnText">
                    <?php echo JText::_('COM_SMARTSHOP_RABATT_VALUE') . $order->_pdf_ext_discount_text; ?>
                </td>

                <td colspan="<?php echo $sizeOfRightColspanFooter; ?>" class="orderListFooter__columnPrice">
                    - <?php echo formatprice($refund->refund_discount, $order->currency_code, 0, -1) . $order->_pdf_ext_discount; ?>
                </td>
            </tr>
            <tr class="orderListFooter__row orderListFooter__total" bgcolor="#c8c8c8">
                <td colspan="<?php echo $sizeOfLeftColspanFooter; ?>" class="orderListFooter__columnText">
                    <?php echo JText::_('COM_SMARTSHOP_SUBTOTAL_AFTER_DISCOUNT'); ?>
                </td>

                <td colspan="<?php echo $sizeOfRightColspanFooter; ?>" class="orderListFooter__columnPrice">
                    <?php echo formatprice(($refund->refund_subtotal-$refund->refund_discount), $order->currency_code, 0, -1) . $order->_pdf_ext_subtotal; ?>
                </td>
            </tr>			
        <?php endif; ?>

        <!-- Shipping price -->
        <?php if (!$jshopConfig->without_shipping) : ?>
            <tr class="orderListFooter__row orderListFooter__shippingPrice" bgcolor="#c8c8c8">
                <td colspan="<?php echo $sizeOfLeftColspanFooter; ?>" class="orderListFooter__columnText">
                    <?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICE'); ?>
                </td>

                <td colspan="<?php echo $sizeOfRightColspanFooter; ?>" class="orderListFooter__columnPrice">
                    <?php echo formatprice($refund->refund_shipping, $order->currency_code, 0, -1) . $order->_pdf_ext_shipping; ?>
                </td>
            </tr>
        <?php endif; ?>

        <!-- Package price -->
        <?php if (!$jshopConfig->without_shipping && ($refund->refund_package != 0 || !empty($jshopConfig->display_null_package_price))) : ?>
            <tr class="orderListFooter__row orderListFooter__packagePrice" bgcolor="#c8c8c8">
                <td colspan="<?php echo $sizeOfLeftColspanFooter; ?>" class="orderListFooter__columnText">
                    <?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE'); ?>
                </td>

                <td colspan="<?php echo $sizeOfRightColspanFooter; ?>" class="orderListFooter__columnPrice">
                    <?php echo formatprice($refund->refund_package, $order->currency_code, 0, -1) . $order->_pdf_ext_shipping_package; ?>
                </td>
            </tr>
        <?php endif; ?>

        <!-- Order payment -->
        <?php if (!empty($refund->refund_payment)) : ?>
            <tr class="orderListFooter__row orderListFooter__orderPayment" bgcolor="#c8c8c8">
                <td colspan="<?php echo $sizeOfLeftColspanFooter; ?>" class="orderListFooter__columnText">
                    <?php echo $order->payment_name; ?>
                </td>

                <td colspan="<?php echo $sizeOfRightColspanFooter; ?>" class="orderListFooter__columnPrice" bgcolor="#c8c8c8">
                    <?php echo formatprice($refund->refund_payment, $order->currency_code, 0, -1) . $order->_pdf_ext_payment; ?>
                </td>
            </tr>
        <?php endif; ?>

        <!-- Tax list -->
        <?php if (!$jshopConfig->hide_tax) : 
                foreach ($refund->refund_tax_list as $percent => $value) :
            ?>
                <tr class="orderListFooter__row orderListFooter__tax" bgcolor="#c8c8c8">
                    <td colspan="<?php echo $sizeOfLeftColspanFooter; ?>" class="orderListFooter__columnText">
                        <?php 
                            $text = displayTotalCartTaxName($refund->refund_price);

                            if ($isShowPercentTax) {
                                $text = $text . ' ' . formattax($percent) . '%';
                            }

                            echo $text;
                        ?>
                    </td>

                    <td colspan="<?php echo $sizeOfRightColspanFooter; ?>" class="orderListFooter__columnPrice">
                        <?php echo formatprice($value, $order->currency_code, 0, -1) . $order->_pdf_ext_tax[$percent]; ?>
                    </td>
                </tr>
				
        <?php endforeach; ?>
		<?php print $order->_tmp_ext_html_after_show_product_tax_total_price; ?>
		<?php endif; ?>

        <!-- Total -->
        <tr class="orderListFooter__row orderListFooter__total" bgcolor="#c8c8c8">
            <td colspan="<?php echo $sizeOfLeftColspanFooter; ?>" class="orderListFooter__columnText">
                <?php 
                    $totalText = ($jshopConfig->show_tax_in_product || $jshopConfig->show_tax_product_in_cart) && (count($refund->refund_tax_list)>0) ? JText::_('COM_SMARTSHOP_ENDTOTAL_INKL_TAX') : JText::_('COM_SMARTSHOP_ENDTOTAL');
                    echo $totalText;
                ?>
            </td>

            <td colspan="<?php echo $sizeOfRightColspanFooter; ?>" class="orderListFooter__columnPrice">
                <?php echo formatprice($refund->refund_total, $order->currency_code, 0, -1) . $order->_pdf_ext_total; ?>
            </td>
        </tr>
    </tfoot>
</table>
<br>
<br>