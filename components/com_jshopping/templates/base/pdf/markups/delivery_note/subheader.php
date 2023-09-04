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
                    <?php echo JText::_('COM_SMARTSHOP_DN_LIEFERSCHEIN'); ?>
                </p>
                <br>
            </td>
        </tr>

        <tr class="subheader__tableRow">
            <td class="subheader__leftSide">
                <div class="subheader__customer">
                    <?php if ($configFields['firma_name']['display'] && !empty($order->d_firma_name)) : ?>
                        <p class="subheader__customerItem subheader__customerFirmaName">
                            <?php echo $order->d_firma_name; ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['title']['display'] && !empty($additionalData['title'])) : ?>
                        <p class="subheader__customerItem subheader__customerTitle">
                            <?php echo $additionalData['title']; ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['f_name']['display'] && !empty($order->d_f_name) || $configFields['l_name']['display'] && !empty($order->d_l_name) || $configFields['m_name']['display'] && !empty($order->d_m_name)) : ?>
                        <p class="subheader__customerItem subheader__customerFIO">
                            <?php 
                                echo ($configFields['f_name']['display']) ? $order->d_f_name . ' ': '';
                                echo ($configFields['m_name']['display']) ? $order->d_m_name . ' ': '';
                                echo ($configFields['l_name']['display']) ? $order->d_l_name: '';
                            ?>
                        </p>
                    <?php endif; ?>
                     
                    <?php if ($configFields['street']['display'] && !empty($order->d_street) || $configFields['street_nr']['display'] && !empty($order->d_street_nr) || $configFields['home']['display'] && !empty($order->d_home) || $configFields['apartment']['display'] && !empty($order->d_apartment)) : ?>
                        <p class="subheader__customerItem subheader__customerHome">
                            <?php                                
                                echo ($configFields['street']['display']) ? $order->d_street . ' ': '';
                                echo ($configFields['street_nr']['display']) ? $order->d_street_nr . ' ': '';
                                echo ($configFields['home']['display']) ? $order->d_home . ' ': '';
                                echo ($configFields['apartment']['display']) ? $order->d_apartment: '';
                            ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['zip']['display'] && !empty($order->d_zip) || $configFields['city']['display'] && !empty($order->d_city)) : ?>
                        <p class="subheader__customerItem subheader__customerZipCity">
                            <?php 
                                echo ($configFields['zip']['display']) ? $order->d_zip . ' ': '';
                                echo ($configFields['city']['display']) ? $order->d_city: '';
                            ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($configFields['country']['display'] && !empty($order->d_country)) : ?>
                        <p class="subheader__customerItem subheader__customerCountry">
                            <?php echo "{$order->d_country}"; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </td>
            
            <td class="subheader__rightSide">
                <div class="subheader__orderData">
                    <?php if (!empty($order->order_number)) : ?>
                        <p class="subheader__orderDataItem subheader__orderNumber">
                            <?php echo JText::_('COM_SMARTSHOP_ORDER_NUMBER') . ' ' . $order->getOrderNumbWithSuffixFor('delivery_note'); ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($order->order_date)) : ?>
                        <p class="subheader__orderDataItem subheader__orderDate">
                            <?php echo JText::_('COM_SMARTSHOP_ORDER_FROM') . ' ' . $order->order_date; ?>
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