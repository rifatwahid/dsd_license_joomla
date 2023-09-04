<?php 
$orderProducts = ($order->products ?: $order->items);
foreach($orderProducts as $orderProduct) : ?>
	<?php if(!isset($refund->products[$orderProduct->order_item_id])) continue; ?>
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
                                <?php echo JText::_('COM_SMARTSHOP_UPLOADS').':<br>'; 
                                    for($i = 0; $i < count($orderProduct->uploadData['files']); $i++) {
                                        $uploadFileName = $orderProduct->uploadData['files'][$i] ?: '';
                                        $uploadQty = $orderProduct->uploadData['qty'][$i] ?: 0;
                                        $uploadDescription = $orderProduct->uploadData['descriptions'][$i] ?: '';
                                        $resultUploadTexts = trim($uploadFileName . ' - ' . $uploadQty);

                                        echo $resultUploadTexts ;

                                        if (!empty($uploadDescription)) {
                                            echo $uploadDescription ;
                                        }
										
										echo '<br/>';
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
            <?php echo formatqty($refund->products[$orderProduct->order_item_id]->product_quantity) . $orderProduct->_qty_unit; ?>
        </td>
        
        <!-- Product single price -->
        <?php if (!empty($jshopConfig->single_item_price)) : ?>
            <td class="orderListBody__prodPrices orderListBodyProdPrices">
                <!-- Item price -->
                <div class="orderListBodyProdPrices__itemPrice">
                    <?php echo precisionformatprice($refund->products[$orderProduct->order_item_id]->product_item_price, $order->currency_code, 0, -1); ?>
                </div>

                <!-- Ext price -->
                <?php if (!empty($orderProduct->_ext_price)) : ?>
                    <div class="orderListBodyProdPrices__extPrice">
                        <?php echo $orderProduct->_ext_price; ?>
                    </div>
                <?php endif; ?>

                <!-- Tax price -->
                <?php if ($jshopConfig->show_tax_product_in_cart) : ?>
                    <div class="orderListBodyProdPrices__taxPrice">
                        <?php echo productTaxInfo($refund->products[$orderProduct->order_item_id]->product_tax, $order->display_price); ?>
                    </div>
					<?php print $html->_tmp_ext_html_after_show_product_tax_single_price[$orderProduct->order_item_id]; ?>
                <?php endif; ?>
				
                <!-- Basic price -->
                <?php if ($jshopConfig->cart_basic_price_show && !empty($refund->products[$orderProduct->order_item_id]->basicprice)) : ?>
                    <div class="orderListBodyProdPrices__basicPrice">
                        <?php echo JText::_('COM_SMARTSHOP_BASIC_PRICE') . ': ' . sprintBasicPrice($refund->products[$orderProduct->order_item_id]); ?>
                    </div>					
                <?php endif; ?>
            </td>
        <?php endif; ?>
        
        <!-- Total prices -->
        <td class="orderListBody__prodTotalPrice orderListBodyProdTotalPrice">
            <!-- Total price -->
            <div class="orderListBodyProdTotalPrice__totalPrice">
                <?php echo formatprice($refund->products[$orderProduct->order_item_id]->total_price+$orderProduct->one_time_cost, $order->currency_code, 0, -1); ?>
            </div>

            <!-- Ext total price -->
            <?php if (!empty($orderProduct->_ext_price_total)) : ?>
                <div class="orderListBodyProdTotalPrice__extTotalPrice">
                    <?php echo $orderProduct->_ext_price_total; ?>
                </div>
            <?php endif; ?>

            <!-- Tax total price -->
            <?php if ($jshopConfig->show_tax_product_in_cart) : ?>
                <div class="orderListBodyProdTotalPrice__taxTotalPrice">
                    <?php echo productTaxInfo($refund->products[$orderProduct->order_item_id]->product_tax, $order->display_price); ?>
                </div>
				<?php print $html->_tmp_ext_html_after_show_product_tax_price[$orderProduct->order_item_id]; ?>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>