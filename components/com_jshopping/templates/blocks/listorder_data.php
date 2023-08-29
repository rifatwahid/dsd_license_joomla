<div class="list-group-item d-none d-sm-block">
	<div class="row">

		<div class="col-sm-3">
			<?php echo JText::_('COM_SMARTSHOP_ORDER_NUMBER'); ?>
		</div>

		<div class="col-sm-3 text-center">
			<?php echo JText::_('COM_SMARTSHOP_ORDER_DATE'); ?>
		</div>

		<div class="col-sm-3 text-center">
			<?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS'); ?>
		</div>

		<div class="col-sm-3 text-end">
			<?php echo JText::_('COM_SMARTSHOP_ORDER_AMOUNT'); ?>
		</div>

	</div>
</div>

<?php foreach ($this->orders as $order) : ?>
	<a href = "<?php echo $order->order_href; ?>" class="list-group-item list-group-item-action">
		<div class="row">

			<div class="col-sm-3">
				<span class="d-sm-none"><?php echo JText::_('COM_SMARTSHOP_ORDER_NUMBER'); ?>:</span>
				<?php echo $order->order_number; ?>
			</div>

			<div class="col-sm-3 text-sm-center">
				<span class="d-sm-none"><?php echo JText::_('COM_SMARTSHOP_ORDER_DATE'); ?>:</span>
				<?php echo formatdate($order->order_date, 0); ?>
			</div>

			<div class="col-sm-3 text-sm-center">
				<span class="d-sm-none"><?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS'); ?>:</span>
				<?php echo $order->status_name; ?>
			</div>

			<div class="col-sm-3 text-sm-end">
				<span class="d-sm-none"><?php echo JText::_('COM_SMARTSHOP_ORDER_AMOUNT'); ?>:</span>
				<?php echo formatprice($order->order_total, $order->currency_code); ?>
			</div>
		</div>
	</a>
<?php endforeach; ?>