<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');

$config_fields = $this->config_fields;
$config_dfields = $this->config_dfields;

$jsUri = JSFactory::getJSUri();
$urlToThumbImage = getPatchProductImage($prod['thumb_image'], '', 1) ?: $this->config->no_image_product_live_path;
$prod = $this->prod;

?>

<head>
    <?php echo JFactory::getDocument()->loadRenderer('head')->render('head'); ?>
    <link href="/templates/shaper_helixultimate/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>

<body id="ajax_cart_content">

    <h5 class="d-none"><?php echo JText::_('COM_SMARTSHOP_BUY_NOW'); ?>: <?php print $this->cart->products[0]['product_name']; ?></h5>

    <div class="shop shop-checkout one_click_checkout" id="shop-qcheckout" style="position:relative;">
        <h1 class="hidden"><?php echo JText::_('COM_SMARTSHOP_CHECKOUT'); ?></h1>
        <div id="qc_error" class="<?php echo (empty($this->qc_error)) ? 'display--none' : ''; ?>">
            <?php echo (!empty($this->qc_error)) ? $this->qc_error : ''; ?>
        </div>

        <?php if (!empty($this->qc_error)) {
            $this->session->clear('qc_error');
        }?>

        <form action="<?php echo $this->action ?>" method="post" id="payment_form" name="quickCheckout" onsubmit="<?php echo $this->onSubmitForm; ?>" onchange="shopQuickCheckout._refreshData();">
            <ul class="list-group cart-items">

                <li class="list-group-item"  onclick="shopOneClickCheckout.openNav('delivery_step')" >
                    <div class="row" id="delivery_block">

                        <div class="col-sm-3">
                            <img class="img-fluid img-cart" width="80" src="<?php print $urlToThumbImage; ?>" alt="<?php echo $prod->name; ?>">
                        </div>

                        <div class="col" >
                            <?php echo JText::_('COM_SMARTSHOP_CHECKOUT_SHIPMENT'); ?>:
                            <span class="shipping_name"><?php print $this->active_shipping_name; ?>
                        </div>
                    </div>

                </li>
                <li class="list-group-item billingAddress" id="billingAddress__changeAddress" onclick="shopOneClickCheckout.openNav('checkout_address_step');shopUserAddressesPopup.setAddressTypeToHandler('billing');">
                    <div id="qc_address"><div class="row">
                        <div class="col-sm-3">
                            <?php echo JText::_('COM_SMARTSHOP_BILL_ADDRESS'); ?>
                        </div>
                        <div class="col" id="">
                            <?php if ($this->user->firma_name!=""){?>
                                <p class="billingAddress__firma">
                                    <?php echo $this->user->firma_name; ?>
                                </p>
                            <?php } ?>
                            <p class="billingAddress__name">
                                <?php echo $this->user->f_name . ' ' . $this->user->l_name; ?>
                            </p>

                            <p class="billingAddress__addresses">
                                <span class="billingAddress__street">
                                    <?php echo $this->user->street; ?>
                                </span>

                                <span class="billingAddress__street_nr">
                                    <?php echo $this->user->street_nr; ?>
                                </span>

                                <?php if (isThereAtLeastOneNotEmpty($addresses['street']) && (isThereAtLeastOneNotEmpty($addresses['city'])  || isThereAtLeastOneNotEmpty($addresses['country']))) : ?>
                                    <span class="address-comma">,</span>
                                <?php endif; ?>

                                <span class="billingAddress__zip">
                                    <?php echo $this->user->zip; ?>
                                </span>

                                <span class="billingAddress__city">
                                    <?php echo $this->user->city; ?>
                                </span>

                                <?php if (isThereAtLeastOneNotEmpty($addresses['country']) && (isThereAtLeastOneNotEmpty($addresses['city'])  || isThereAtLeastOneNotEmpty($addresses['street']))) : ?>
                                    <span class="address-comma">,</span>
                                <?php endif; ?>

                                <span class="billingAddress__country">
                                    <?php echo $this->user->country; ?>
                                </span>
                            </p>
                        </div>

                            <input type="hidden" name="billingAddress_id" value="<?php echo $this->user->address_id; ?>">
                        </div>
                </li>
                <li class="list-group-item shippingAddress" id="shippingAddress__changeAddress" onclick="shopOneClickCheckout.openNav('checkout_address_step');shopUserAddressesPopup.setAddressTypeToHandler('shipping');">
                    <div id="qc_address"><div class="row">
                        <div class="col-sm-3">
                            <?php echo JText::_('COM_SMARTSHOP_SHIPPING_ADDRESS'); ?>
                        </div>
                        <div class="col" id="">
                            <p class="shippingAddress__name">
                                <?php echo $this->user->firma_name . ' ' . $this->user->f_name . ' ' . $this->user->l_name; ?>
                            </p>

                            <p class="shippingAddress__addresses">

                                <span class="shippingAddress__street">
                                    <?php echo $this->user->street; ?>
                                </span>

                                <span class="shippingAddress__street_nr">
                                    <?php echo $this->user->street_nr; ?>
                                </span>

                                <?php if (isThereAtLeastOneNotEmpty($addresses['street']) && (isThereAtLeastOneNotEmpty($addresses['city'])  || isThereAtLeastOneNotEmpty($addresses['country']))) : ?>
                                    <span class="address-comma">,</span>
                                <?php endif; ?>

                                <span class="shippingAddress__zip">
                                    <?php echo $this->user->zip; ?>
                                </span>

                                <span class="shippingAddress__city">
                                    <?php echo $this->user->city; ?>
                                </span>

                                <?php if (isThereAtLeastOneNotEmpty($addresses['country']) && (isThereAtLeastOneNotEmpty($addresses['city'])  || isThereAtLeastOneNotEmpty($addresses['street']))) : ?>
                                    <span class="address-comma">,</span>
                                <?php endif; ?>

                                <span class="shippingAddress__country">
                                    <?php echo $this->user->country; ?>
                                </span>
                            </p>
                            <input type="hidden" name="shippingAddress_id" value="<?php echo $this->user->address_id; ?>">

                        </div>
                        </div>


                </li>
                <li class="list-group-item" onclick="shopOneClickCheckout.openNav('payment_step')">
                    <div class="row">
                        <div class="col-sm-3">
                            <?php echo JText::_('COM_SMARTSHOP_CHECKOUT_PAYMENT'); ?>
                        </div>
                        <div class="col" id="payment_name">
                            <?php echo $this->active_payment_name; ?>
                        </div>
                </li>
                <li class="list-group-item" onclick="shopOneClickCheckout.openNav('total_step')">
                    <div class="row">
                        <div class="col-sm-3">
                            <?php echo JText::_('COM_SMARTSHOP_ORDER_TOTAL'); ?>
                        </div>
                        <div class="col" id="fullsumm">
                            <?php echo formatprice($this->fullsumm); ?>
                        </div>
                </li>

            </ul>
            <div class="overlay" id="payment_step" >
                <?php if ($this->payment_step) : ?>
                    <fieldset class="form-group">

                        <div class="_back mb-3 pt-3 pb-3 ps-1 btn btn-link ps-0" onClick="shopOneClickCheckout.closeNav('payment_step');">< <?php echo JText::_('COM_SMARTSHOP_CHECKOUT_PAYMENT'); ?></div>

                        <div id="qc_payments_methods">
                            <?php require_once templateOverrideBlock('blocks', 'one_click_payments.php'); ?>
                        </div>
                    </fieldset>
                <?php elseif (!$this->payment_step && isset($this->active_payment_class)) : ?>
                    <input type="radio" style="display:none;" name="payment_method" value="<?php echo $this->active_payment_class; ?>" id="qc_payment_method_class" checked/>
                <?php endif; ?>
            </div>
            <div class="overlay" id="delivery_step">
                <?php if (!$this->jshopConfig->step_4_3) : ?>
                    <?php if ($this->delivery_step) : ?>
                        <fieldset class="form-group">

                            <div class="_back mb-3 pt-3 pb-3 ps-1 btn btn-link ps-0" onClick="shopOneClickCheckout.closeNav('delivery_step');">< <?php echo JText::_('COM_SMARTSHOP_CHECKOUT_SHIPMENT'); ?></div>

                            <div id="qc_shippings_methods">
                                <?php require_once templateOverrideBlock('blocks', 'one_click_shippings.php'); ?>
                            </div>
                        </fieldset>
                    <?php elseif (!$this->delivery_step && isset($this->active_sh_pr_method_id)) : ?>
                        <input type="hidden" name="sh_pr_method_id" value="<?php echo $this->active_sh_pr_method_id; ?>" id="qc_sh_pr_method_id" />
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="overlay" id="total_step">
                <div onClick="shopOneClickCheckout.closeNav('total_step');" class="_back mb-3 pt-3 pb-3 ps-1 btn btn-link ps-0">< <?php echo JText::_('COM_SMARTSHOP_ORDER_TOTAL'); ?></div>
                <ul class="list-group">
                    <?php if (!$this->hide_subtotal) : ?>
                        <li class="list-group-item price_products">
                            <?php echo JText::_('COM_SMARTSHOP_SUBTOTAL'); ?>: <span class="float-end"><?php echo formatprice($this->summ); ?></span>
                        </li>
                    <?php endif; ?>

                    <?php if ($this->discount > 0) : ?>
                        <li class="list-group-item">
                            <?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>: <span class="float-end"><?php echo formatprice(-$this->discount);?></span>
                        </li>
                    <?php endif; ?>

                    <?php if ($this->free_discount > 0) : ?>
                        <li class="list-group-item">
                            <?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>: <span class="float-end"><?php echo formatprice($this->free_discount); ?></span>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($this->summ_delivery)) : ?>
                        <li class="list-group-item summ_delivery">
                            <?php echo JText::_('COM_SMARTSHOP_SHIPPING_COSTS'); ?>: <span class="float-end"><?php echo formatprice($this->summ_delivery); ?></span>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($this->summ_package)) : ?>
                        <li class="list-group-item summ_package">
                            <?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE'); ?>: <span class="float-end"><?php echo formatprice($this->summ_package); ?></span>
                        </li>
                    <?php endif; ?>


                    <li class="list-group-item summ_payment">
                        <span id="active_payment_name"><?php echo $this->active_payment_name; ?></span>: <span class="float-end summ_pay"><?php echo formatprice($this->summ_payment); ?></span>
                    </li>


                    <?php foreach($this->tax_list as $percent=>$value) : ?>
                        <li class="list-group-item tax_list_value">
                            <?php echo displayTotalCartTaxName(); ?> <?php if ($this->show_percent_tax) echo formattax($percent) . '%'; ?>: <span class="float-end"><?php echo formatprice($value); ?></span>
                        </li>
                    <?php endforeach; ?>
                    <?php print $this->_tmp_ext_html_after_show_total_tax; ?>

                    <li class="list-group-item fullsumm">
                        <?php echo JText::_('COM_SMARTSHOP_ORDER_TOTAL'); ?>: <span class="float-end"><?php echo formatprice($this->fullsumm); ?></span>
                    </li>

                </ul>
            </div>

            <?php require_once templateOverrideBlock('blocks', 'previewfinish.php'); ?>
            <input type="hidden" name="cart_name" value="one_click_buy"/>
        </form>
        <?php print $this->popup_address; ?>
    </div>

    <script>
        <?php if ($this->ac_paym_method->payment_class) : ?>
            document.addEventListener('DOMContentLoaded', () => {
                shopQuickCheckout.showPayment('<?php print $this->ac_paym_method->payment_class; ?>');
            });
        <?php endif; ?>

        
    </script>
</body>