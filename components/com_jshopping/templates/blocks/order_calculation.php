 <ul class="list-group">
	<?php if (!$this->hide_subtotal) : ?>
		<li class="list-group-item"><?php echo JText::_('COM_SMARTSHOP_SUBTOTAL'); ?>: <span class="float-end"><?php echo formatprice($order->order_subtotal, $order->currency_code); ?></span></li>
	<?php endif; ?>

	<?php if ($order->order_discount > 0) : ?>
		<li class="list-group-item"><?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>: <span class="float-end"><?php echo formatprice(-$order->order_discount, $order->currency_code); ?></span></li>
	<?php endif; ?>

	<?php if (!$this->config->without_shipping || $order->order_shipping > 0) : ?>
		<li class="list-group-item"><?php echo JText::_('COM_SMARTSHOP_SHIPPING_COSTS'); ?>: <span class="float-end"><?php echo formatprice($order->order_shipping, $order->currency_code); ?></span> </li>
	<?php endif; ?>

	<?php if (!$this->config->without_shipping && ($order->order_package>0 || $this->config->display_null_package_price)) : ?>
		<li class="list-group-item"><?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE'); ?>: <span class="float-end"><?php echo formatprice($order->order_package, $order->currency_code); ?></span> </li>
	<?php endif; ?>
	
	<?php if ($order->payment_name) : ?>
		<li class="list-group-item summ_payment">
			<?php echo $order->payment_name; ?>: <span class="float-end"><?php echo formatprice($order->order_payment, $order->currency_code); ?></span>
		</li>
	<?php endif; ?>

	<?php if (!$this->config->hide_tax) : ?>
		<?php foreach($order->order_tax_list as $percent=>$value) {
			if ($value>0) {?>
			<li class="list-group-item">
			<?php if ((double)$percent==0) {
					$tmp=explode('_',substr($percent,15,strlen($percent)));
					echo displayTotalCartTax().JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name." ";
					//print $tmp[1]."%";
					$percent=$tmp[1];
				}else {
					echo displayTotalCartTaxName($order->display_price);
				} ?> 
			<?php if ($this->show_percent_tax) { echo formattax($percent) . '%'; } ?>: <span class="float-end"><?php echo formatprice($value, $order->currency_code); ?></span></li>
		<?php }}; ?>
		<?php echo $this->_tmp_ext_html_user_ordershow_after_total_tax ?? ''; ?> 
	<?php endif; ?>

	<li class="list-group-item"><?php echo JText::_('COM_SMARTSHOP_ORDER_TOTAL'); ?>: <span class="float-end"><?php echo formatprice($order->order_total, $order->currency_code); ?></span></li>
</ul>