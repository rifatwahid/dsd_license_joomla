<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');

$config_fields = $this->config_fields;
$config_dfields = $this->config_dfields;
?>

<div class="shop shop-checkout" id="shop-qcheckout">
    <?php if($this->config->display_preloader){ ?>
        <div id="dsd-spinner_loading_block">
            <div id="spinner_loading" class="loading">
                <i class="fas fa-spinner fa-spin fa-4x"></i>
            </div>
        </div>
    <?php } ?>
	<h1 class="hidden"><?php echo JText::_('COM_SMARTSHOP_CHECKOUT'); ?></h1>
    <div id="qc_error" class="<?php echo (empty($this->qc_error)) ? 'display--none' : ''; ?>">
        <?php echo (!empty($this->qc_error)) ? $this->qc_error : ''; ?>
    </div>

    <?php if (!empty($this->qc_error)) {
        $this->session->clear('qc_error');
    }?>

    <form action="<?php echo $this->action ?>" method="post" id="payment_form" name="quickCheckout" onsubmit="<?php echo $this->onSubmitForm; ?>">

        <?php require_once templateOverrideBlock('blocks', 'checkout_address.php'); ?>

        <?php if ($this->jshopConfig->step_4_3) : ?>
            <?php if ($this->delivery_step) : ?>
                <fieldset class="form-group">
                    <legend>
                        <?php echo JText::_('COM_SMARTSHOP_CHECKOUT_SHIPMENT'); ?>
                    </legend>

                    <div id="qc_shippings_methods">
                        <?php require_once templateOverrideBlock('blocks', 'shippings.php'); ?>
                    </div>
                </fieldset>
            <?php elseif (!$this->delivery_step && isset($this->active_sh_pr_method_id)) : ?>
                <input type="hidden" name="sh_pr_method_id" value="<?php echo $this->active_sh_pr_method_id; ?>" id="qc_sh_pr_method_id" />
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($this->jshopConfig->hide_payment_step){?>
			<div style="display:none">
				<?php require_once templateOverrideBlock('blocks', 'payments.php'); ?>
			</div>
		<?php }?>
        <?php if (($this->payment_step)&&((!$this->jshopConfig->hide_payment_step))) : ?>
            <fieldset class="form-group">
                <legend>
                    <?php echo JText::_('COM_SMARTSHOP_CHECKOUT_PAYMENT'); ?>
                </legend>

                <div id="qc_payments_methods">
                    <?php require_once templateOverrideBlock('blocks', 'payments.php'); ?>
                </div>
            </fieldset>
        <?php elseif (!$this->payment_step && isset($this->active_payment_class)) : ?>
            <input type="radio" style="display:none;" name="payment_method" value="<?php echo $this->active_payment_class; ?>" id="qc_payment_method_class" checked/>
        <?php endif; ?>

		<?php if (!$this->jshopConfig->step_4_3) : ?>
			<?php if ($this->delivery_step) : ?>
				<fieldset class="form-group">
					<legend>
						<?php echo JText::_('COM_SMARTSHOP_CHECKOUT_SHIPMENT'); ?>
					</legend>

					<div id="qc_shippings_methods">
						<?php require_once templateOverrideBlock('blocks', 'shippings.php'); ?>
					</div>
				</fieldset>
			<?php elseif (!$this->delivery_step && isset($this->active_sh_pr_method_id)) : ?>
				<input type="hidden" name="sh_pr_method_id" value="<?php echo $this->active_sh_pr_method_id; ?>" id="qc_sh_pr_method_id" />
			<?php endif; ?>
		<?php endif; ?>

        <h4 class="pb-2 font-weight-normal">
            <?php echo JText::_('COM_SMARTSHOP_CHECK_ORDER'); ?>
        </h4>

        <?php echo $this->small_cart; ?>
        <?php require_once templateOverrideBlock('blocks', 'previewfinish.php'); ?>
    </form>
</div> <!-- shop-checkout -->

<?php if ($this->ac_paym_method->payment_class){ ?>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            shopQuickCheckout.showPayment('<?php print $this->ac_paym_method->payment_class; ?>');
        });

    </script>
<?php } ?>
