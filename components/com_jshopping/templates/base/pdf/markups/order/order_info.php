<?php 
    $jshopConfig = $additionalData['jshopConfig'];
?>

<div class="orderInfo">
    <!-- Delivery time -->
    <?php if ($isShowDeliveryTime) : ?>
        <div class="orderInfoItem orderInfoDeliveryTime">
            <span class="orderInfoItem__text orderInfoDeliveryTime__text">
                <?php echo JText::_('COM_SMARTSHOP_ORDER_DELIVERY_TIME'); ?> : 
            </span>

            <span class="orderInfoItem__value orderInfoDeliveryTime__value">
                <?php echo $deliveryTime; ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Delivery date -->
    <?php if ($isShowDeliveryDate) : ?>
        <div class="orderInfoItem orderInfoDeliveryDate">
            <span class="orderInfoItem__text orderInfoDeliveryDate__text">
                <?php echo JText::_('COM_SMARTSHOP_DELIVERY_DATE'); ?> :
            </span>

            <span class="orderInfoItem__value orderInfoDeliveryDate__value">
                <?php echo formatdate($order->delivery_date); ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Weight of products -->
    <?php if ($isShowWeightOfProducts) : ?>
        <div class="orderInfoItem orderInfoWeightOfProducts">
            <span class="orderInfoItem__text orderInfoWeightOfProducts__text">
                <?php echo JText::_('COM_SMARTSHOP_WEIGHT_PRODUCTS'); ?> :
            </span>

            <span class="orderInfoItem__value orderInfoWeightOfProducts__value">
                <?php echo formatweight($order->weight); ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Payment info -->
    <?php if ($isShowPaymentInfo && (!empty($order->payment_name) || !empty($paymentDescription))) : ?>
        <div class="orderInfoItem orderInfoPaymentInfo">
            <div class="orderInfoItem__text orderInfoPaymentInfo__text">
                <?php echo JText::_('COM_SMARTSHOP_PAYMENT_INFORMATION'); ?> :
            </div>

            <?php if (!empty($order->payment_name)) : ?>
                <div class="orderInfoItem__value orderInfoPaymentInfo__value">
                    <?php echo $order->payment_name; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($paymentDescription)) : ?>
                <div class="orderInfoItem__value orderInfoPaymentInfo__value">
                    <?php echo $paymentDescription; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Shipping info -->
    <?php if ($isShowShippingInfo && !empty(trim($order->shipping_information))) : ?>
        <div class="orderInfoItem orderInfoShippingInfo">
            <div class="orderInfoItem__text orderInfoShippingInfo__text">
                <?php echo JText::_('COM_SMARTSHOP_SHIPPING_INFORMATION'); ?> :
            </div>

            <div class="orderInfoItem__value orderInfoShippingInfo__value">
                <?php echo $order->shipping_information; ?>
            </div>
        </div>
    <?php endif; ?>
</div>