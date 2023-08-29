<ul class="list-unstyled">
	<?php if (!$this->config->without_payment) : ?>
		<li class="mb-3"><span class="font-weight-bold d-block"><?php echo JText::_('COM_SMARTSHOP_PAYMENT'); ?>:</span><?php echo $order->payment_name; ?></li>
	<?php endif; ?>

	<?php if (!$this->config->without_shipping) : ?>
		<li class="mb-3"><span class="font-weight-bold d-block"><?php echo JText::_('COM_SMARTSHOP_SHIPPING'); ?>:</span><?php echo nl2br($order->shipping_info); ?></li>
	<?php endif; ?>

	<?php if ($this->config->show_weight_order) : ?>
		<li class="mb-3"><span class="font-weight-bold d-block"><?php echo JText::_('COM_SMARTSHOP_WEIGHT'); ?>:</span><?php echo formatweight($this->order->weight); ?></li>
	<?php endif; ?>

	<?php if ($order->order_add_info) : ?>
		<li class="mb-3"><span class="font-weight-bold d-block"><?php echo JText::_('COM_SMARTSHOP_COMMENT'); ?>:</span><?php echo $order->order_add_info; ?></li>
	<?php endif; ?>
</ul>			

<ul class="list-unstyled">
	<span class="font-weight-bold d-block"><?php echo JText::_('COM_SMARTSHOP_ORDER_HISTORY'); ?>:</span>

	<?php foreach($order->history as $history) : ?>
		<li><?php echo formatdate($history->status_date_added, 0); ?> - <?php echo $history->status_name; ?></li>
	<?php endforeach; ?>
</ul>