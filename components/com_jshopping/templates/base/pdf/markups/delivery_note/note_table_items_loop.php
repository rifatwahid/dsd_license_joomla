<?php 
$orderProducts = ($order->products ?: $order->items);
foreach($orderProducts as $orderProduct) : ?>
    <tr class="orderListBody__row">
        <!-- Product info (name, manufacture, delivery time, uploads and etc.) -->
        <td colspan="3" class="orderListBody__prodInfo">
            <!-- Name -->
            <div class="orderListBody__prodName">
                <?php echo $orderProduct->product_name; ?>
            </div>

            <table class="orderListBody__prodAdditionalInfo">
                <tr>
                    <td>
                        <!-- Manufacture -->
                        <?php if (!empty(trim($orderProduct->manufacturer))) : ?>
                            <div class="orderListBody__prodManufact">
                                <?php echo JText::_('COM_SMARTSHOP_MANUFACTURER') . ': ' . $orderProduct->manufacturer; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Attrs -->
                        <div class="orderListBody__prodAttrs">
                            <?php echo trim($pdf->prepareProductAttrs($orderProduct)); ?>
                        </div>
                        
                        <!-- Delivery time -->
                        <?php if (!empty(trim($orderProduct->delivery_time))) : ?>
                            <div class="orderListBody__prodDeliveryTime">
                                <?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME') . ': ' . $orderProduct->delivery_time; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Uploads -->
                        <?php if (!empty($orderProduct->uploadData['files'])) : ?>
                            <div class="orderListBody__prodUploads">
                                <?php echo '<b>'.JText::_('COM_SMARTSHOP_UPLOADS'). ':</b><br>'; 
                                    for($i = 0; $i < count($orderProduct->uploadData['files']); $i++) {
                                        $uploadFileName = $orderProduct->uploadData['files'][$i] ?: '';
                                        $uploadQty = $orderProduct->uploadData['qty'][$i] ?: 0;
                                        $uploadDescription = $orderProduct->uploadData['descriptions'][$i] ?: '';
                                        $resultUploadTexts = trim($uploadFileName . ' - ' . $uploadQty);

                                        echo $resultUploadTexts;

                                        if (!empty($uploadDescription)) {
                                            echo $uploadDescription ;
                                        }
										
										echo '<br>';
                                    }
                                ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </td>

        <!-- Product code -->
        <?php if (!empty($jshopConfig->show_product_code_in_order)) : ?>
            <td class="orderListBody__prodCode">
                <?php echo $orderProduct->product_ean; ?>
            </td>
        <?php endif; ?>

        <!-- Product qty -->
        <td class="orderListBody__prodQty">
            <?php echo formatqty($orderProduct->product_quantity) . $orderProduct->_qty_unit; ?>
        </td>
    </tr>
<?php endforeach; ?>