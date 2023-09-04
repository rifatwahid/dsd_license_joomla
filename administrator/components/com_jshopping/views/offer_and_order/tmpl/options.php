<?php
    defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php?option=com_jshopping&controller=offer_and_order" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
    <div class="jshops_edit striped-block offer_and_order_options_edit">
        <!-- Suffix -->
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo JText::_('COM_SMARTSHOP_SUFFIX'); ?>
            </label>
            <div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type='text' class="form-control" name='params[offer_and_order_suffix]' value='<?php echo $this->params['offer_and_order_suffix']; ?>'>   
            </div>
        </div>

        <!-- Validity days -->
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_VALIDITY'); ?>
            </label>
            <div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type='text' class="form-control" name='params[offer_and_order_validity]' value='<?php echo $this->params['offer_and_order_validity']; ?>'>   
            </div>
        </div>

        <!-- Payment -->
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo JText::_('COM_SMARTSHOP_PAYMENT'); ?>
            </label>
            <div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <?php echo $this->payments_select; ?>
            </div>
        </div>

        <!-- Shippings -->
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo JText::_('COM_SMARTSHOP_SHIPPINGS'); ?>
            </label>
            <div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <?php echo $this->shippings_select; ?>
            </div>
        </div>

        <!-- Enable\disable Invoice data -->
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo JText::_('COM_SMARTSHOP_INVOICE_DATE'); ?>
            </label>
            <div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="radio" class="form-check-input" name="params[offer_and_order_invoice_data]" value="1" <?php echo ($this->params['offer_and_order_invoice_data'] == 1) ? 'checked="checked"' : ''; ?> /><?php echo JText::_('JYES'); ?>
                <br/>
                <input type="radio" class="form-check-input" name="params[offer_and_order_invoice_data]" value="0" <?php echo ($this->params['offer_and_order_invoice_data'] == 0) ? 'checked="checked"' : ''; ?>/><?php echo JText::_('JNO'); ?>  
            </div>
        </div>

        <!-- Allow offer on product details page -->
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo JText::_('COM_SMARTSHOP_ALLOW_OFFER_ON_PRODUCT_DETAILS_PAGE'); ?>
            </label>
            <div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="checkbox" class="form-check-input" name="params[allow_offer_on_product_details_page]" value="1" <?php if ($this->params['allow_offer_on_product_details_page']) echo 'checked="checked"';?> />
            </div>
        </div>

        <!-- Allow offer in cart -->
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo JText::_('COM_SMARTSHOP_ALLOW_OFFER_IN_CART'); ?>
            </label>
            <div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="checkbox" class="form-check-input" name="params[allow_offer_in_cart]" value="1" <?php if ($this->params['allow_offer_in_cart']) echo 'checked="checked"';?> />
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="saveOfferOptions"/>
</form>