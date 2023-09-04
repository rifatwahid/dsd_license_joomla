<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="checkout-cart">
	<div class="cart-products">
		<ul class="list-group">
			<?php if (!empty($this->products)) {
				foreach ($this->products as $key_id => $prod) {
					include templateOverrideBlock('blocks', 'cartproduct.php');
				}
			} ?>
		</ul>
	</div>

	<div class="row my-4">

		<div class="col-md-6 col-lg-7">
			<?php if ($this->config->show_weight_order && formatweight($this->weight) > 0) : ?>
				<?php echo JText::_('COM_SMARTSHOP_WEIGHT'); ?>: <?php echo formatweight($this->weight); ?>
			<?php endif; ?>
		</div>

		<div class="col">
			<?php include templateOverrideBlock('blocks', 'quick_checkout_calculation.php'); ?>			
		</div>

	</div> 
</div> 
