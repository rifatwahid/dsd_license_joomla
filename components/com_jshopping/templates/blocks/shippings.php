<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');
$jshopConfig = JSFactory::getConfig();
?>

<div class="form-check" id="table_shippings">
	<?php 
	$exist=0;$first=0;
	foreach($this->shipping_methods as $shipping) : 
		if ($shipping->sh_pr_method_id == $this->active_shipping){
			$exist=1;
		}
		if ($first==0) $first=$shipping->sh_pr_method_id;
	endforeach;
	if (!$exist) $this->active_shipping=$first;
	?>
	<?php foreach($this->shipping_methods as $shipping) : 

		$shippingFormActiveClass = ($shipping->sh_pr_method_id == $this->active_shipping) ? 'shipping_form_active' : '';
		$shippingMethodChecked = ($shipping->sh_pr_method_id == $this->active_shipping) ? 'checked' : '';
	?>

	<div class="shipping">
		<div class="mr-1">
			<input class="form-check-input" type ="radio" name ="sh_pr_method_id" id ="shipping_method_<?php echo $shipping->sh_pr_method_id; ?>" value="<?php echo $shipping->sh_pr_method_id; ?>" <?php echo $shippingMethodChecked; ?> onclick="shopQuickCheckout.showShipping(<?php echo $shipping->sh_pr_method_id; ?>)" />
		</div>

		<div class="mb-3 text-muted">
			<label class="form-check-label d-block text-body" for="shipping_method_<?php echo $shipping->sh_pr_method_id; ?>">
				<?php  if ($shipping->image) : ?>
					<span class="shipping_image">
						<img src="<?php echo getPatchProductImage($shipping->image, '', 1); ?>" alt="<?php echo htmlspecialchars($shipping->name); ?>" />
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
