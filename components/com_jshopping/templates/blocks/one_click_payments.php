<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<?php foreach($this->payment_methods as $payment) : ?>
<div class="form-check d-flex">

	<div class="me-1">
		<input class="mb-2" type ="radio" name ="payment_method" id ="payment_method_<?php echo $payment->payment_id; ?>" onclick="shopQuickCheckout.showPayment('<?php echo $payment->payment_class; ?>')" value="<?php echo $payment->payment_class; ?>" <?php if ($this->active_payment == $payment->payment_id){?>checked<?php } ?> />
	</div>

	<div class="mb-3 text-muted">
		<label class="form-check-label d-block text-body" for ="payment_method_<?php echo $payment->payment_id; ?>">
			<?php if ($payment->image) : ?>
				<span class="payment_image">
				<img src="<?php echo $payment->image; ?>" alt="<?php echo htmlspecialchars($payment->name); ?>" />
				</span>
			<?php endif; ?>

			<?php echo $payment->name; ?><?php if ($payment->price_add_text != '') : ?> (<?php echo $payment->price_add_text; ?>)<?php endif; ?>
		</label>

		<?php echo $payment->payment_description; ?>
		<?php echo $payment->form; ?>
	</div>

</div>
<?php endforeach; ?>