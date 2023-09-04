<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');
$jshopConfig = JSFactory::getConfig();
?>
<div class="form-check" id="table_shippings">
	<?php foreach($this->shipping_methods as $shipping) : 

		$shippingFormActiveClass = ($shipping->sh_pr_method_id == $this->active_shipping) ? 'shipping_form_active' : '';
		$shippingMethodChecked = ($shipping->sh_pr_method_id == $this->active_shipping) ? 'checked' : '';
	?>

	<div class="shipping d-flex">
		<div class="d-grid me-1">
			<input class="mb-2" type ="radio" name ="sh_pr_method_id" id ="shipping_method_<?php echo $shipping->sh_pr_method_id; ?>" value="<?php echo $shipping->sh_pr_method_id; ?>" <?php echo $shippingMethodChecked; ?> onclick="shopQuickCheckout.showShipping(<?php echo $shipping->sh_pr_method_id; ?>);" />
		</div>

		<div class="mb-1 text-muted">
			<label class="form-check-label d-block text-body" for="shipping_method_<?php echo $shipping->sh_pr_method_id; ?>">
				<?php  if ($shipping->image) : ?>
					<span class="shipping_image">
						<img src="<?php echo $jshopConfig->image_shippings_live_path.'/'.$shipping->image; ?>" alt="<?php echo htmlspecialchars($shipping->name); ?>" />
					</span>
				<?php endif; ?>

				<?php echo $shipping->name . '(' . formatprice($shipping->calculeprice) . ')'; ?>
			</label>

			<div id="shipping_form_<?php echo $shipping->sh_pr_method_id; ?>" class="shipping_form <?php echo $shippingFormActiveClass; ?>">
				<?php echo $shipping->form; ?>
			</div>

			<?php echo $shipping->description; ?>

			<?php if ($shipping->delivery) : ?>
				<p class="mb-1">
					<?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME') . ': ' . $shipping->delivery; ?>
				</p>
			<?php endif; ?>

			<?php if ($shipping->delivery_date_f) : ?>
				<p class="mb-1">
					<?php echo JText::_('COM_SMARTSHOP_DELIVERY_DATE') . ': ' . $shipping->delivery_date_f; ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>
