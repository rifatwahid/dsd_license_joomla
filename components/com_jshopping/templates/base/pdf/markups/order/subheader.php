<?php 
    $jshopConfig = $additionalData['jshopConfig'];
    $configFields = $jshopConfig->getListFieldsRegister()['address'];
    $user = $additionalData['user'];
?>
<div class="subheader">
    <table class="subheader__table">
        <tr class="subheader__tableRow">
            <td class="subheader__leftSide">
                <?php if (!empty($implodeInfo) || !empty($vendorInfo->city)) : ?>
                    <p class="subheader__vendorAddress">
                        <?php echo "{$implodeInfo} {$vendorInfo->city}"; ?>
                    </p>
                <?php endif; ?>
            </td>
            <td class="subheader__rightSide">
                <p class="subheader__billText">
                    <?php echo JText::_('COM_SMARTSHOP_EMAIL_BILL'); ?>
                </p>
                <br>
            </td>
        </tr>

        <tr class="subheader__tableRow">
            <td class="subheader__leftSide">
                <div class="subheader__customer">
                    <?php if ($configFields['firma_name']['display'] && !empty($order->firma_name)) : ?>
                        <p class="subheader__customerItem subheader__customerFirmaName">
                            <?php echo $order->firma_name; ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['title']['display'] && !empty($additionalData['title'])) : ?>
                        <p class="subheader__customerItem subheader__customerTitle">
                            <?php echo $additionalData['title']; ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['f_name']['display'] && !empty($order->f_name) || $configFields['m_name']['display'] && !empty($order->m_name) || $configFields['l_name']['display'] && !empty($order->l_name)) : ?>
                        <p class="subheader__customerItem subheader__customerFIO">
                            <?php 
                                echo ($configFields['f_name']['display']) ? $order->f_name . ' ': '';
                                echo ($configFields['m_name']['display']) ? $order->m_name . ' ': '';
                                echo ($configFields['l_name']['display']) ? $order->l_name: '';
                            ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['street']['display'] && !empty($order->street) || $configFields['street_nr']['display'] && !empty($order->street_nr) || $configFields['home']['display'] && !empty($order->home) || $configFields['apartment']['display'] && !empty($order->apartment)) : ?>
                        <p class="subheader__customerItem subheader__customerHome">
                            <?php
                                echo ($configFields['street']['display']) ? $order->street . ' ': '';
                                echo ($configFields['street_nr']['display']) ? $order->street_nr . ' ': '';
                                echo ($configFields['home']['display']) ? $order->home . ' ': '';
                                echo ($configFields['apartment']['display']) ? $order->apartment: '';
                            ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['zip']['display'] && !empty($order->zip) || $configFields['city']['display'] && !empty($order->city)) : ?>
                        <p class="subheader__customerItem subheader__customerZipCity">
                            <?php
                                echo ($configFields['zip']['display']) ? $order->zip . ' ': '';
                                echo ($configFields['city']['display']) ? $order->city: '';
                            ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['country']['display'] && !empty($order->country)) : ?>
                        <p class="subheader__customerItem subheader__customerCountry">
                            <?php echo "{$order->country}"; ?>
                        </p>
                    <?php endif; ?>
					
					<?php if ($configFields['tax_number']['display'] && !empty($order->tax_number)) : ?>
                        <p class="subheader__customerItem subheader__customerTaxid">
                            <?php echo JText::_('COM_SMARTSHOP_VAT_NR') . ': ' . $order->tax_number; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
            
            <td class="subheader__rightSide">
                <div class="subheader__orderData">
                    <?php //if (!empty($thus->invoice_number)) : ?>
                        <p class="subheader__orderDataItem subheader__invoiceNumber">
                            <?php echo JText::_('COM_SMARTSHOP_ORDER_SHORT_NR') . ' ' . $order->invoice_number; ?>
                        </p>
                    <?php //endif; ?>
                    <?php if (!empty($order->order_number)) : ?>
                        <p class="subheader__orderDataItem subheader__orderNumber">
                            <?php echo JText::_('COM_SMARTSHOP_ORDER_NUMBER') . ' ' . $order->order_number; ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($order->order_date)) : ?>
                        <p class="subheader__orderDataItem subheader__orderDate">
                            <?php echo JText::_('COM_SMARTSHOP_ORDER_FROM') . ' ' . $order->order_date; ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($order->invoice_date) && !empty($jshopConfig->date_invoice_in_invoice)) : ?>
                        <p class="subheader__orderDataItem subheader__orderInvoice">
                            <?php echo JText::_('COM_SMARTSHOP_INVOICE_DATE') . ' ' . strftime($jshopConfig->store_date_format, strtotime($order->invoice_date)); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($jshopConfig->user_number_in_invoice) && !empty($order->user_id) && !empty($user->number)) : ?>
                        <p class="subheader__orderDataItem subheader__orderUserNumber">
                            <?php echo JText::_('COM_SMARTSHOP_USER_NUMBER') . ' ' . $user->number; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td></td>
            <td>
                <table class="subheader__numbers" border="1" cellpadding="2">
                    <tr class="subheader__numbersRow subheader__numbersRowLeft">
                        <td class="subheader__numbersTdText">
                            <?php echo JText::_('COM_SMARTSHOP_IDENTIFICATION_NUMBER'); ?>
                        </td>
                        <td class="subheader__numbersTdNumber">
                            <?php echo $vendorInfo->identification_number; ?>
                        </td>
                    </tr>
                    <tr class="subheader__numbersRow subheader__numbersRight">
                        <td class="subheader__numbersTdText">
                            <?php echo JText::_('COM_SMARTSHOP_TAX_NUMBER'); ?>
                        </td>
                        <td class="subheader__numbersTdNumber">
                            <?php echo $vendorInfo->tax_number; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>