 <?php if (!empty($order->reorder)) : ?>
	<a href="<?php echo SEFLink('index.php?option=com_jshopping&controller=repeatorder&order_id=' . $order->order_id, 1); ?> " class="btn btn-outline-secondary"><?php echo JText::_('COM_SMARTSHOP_REPEAT_ORDER'); ?></a>
<?php endif; ?> 

<?php if ($this->isReturn) : ?>
	<a href="<?php echo SEFLink('index.php?option=com_jshopping&controller=returns&task=start&order_id=' . $order->order_id, 1); ?> " class="btn btn-outline-secondary"><?php echo JText::_('COM_SMARTSHOP_START_RETURN'); ?></a>
<?php endif; ?>

<?php if (!empty($order->pdf_file)) : ?>
	<a href="<?php echo $urlToInvoice; ?>" class="btn btn-outline-secondary float-sm-right"><?php echo JText::_('COM_SMARTSHOP_ORDER_DOWNLOAD_BILL'); ?></a>
<?php endif; ?>

<?php if (!empty($this->refunds)) : ?>
<div class="text"><?php echo JText::_('COM_SMARTSHOP_ORDER_DOWNLOAD_REFUND'); ?>:</div>
	<?php foreach($this->refunds as $file) : ?>
		<?php if ($file->pdf_file != ""){?>
						<a href="<?php echo $urlToRefund . $file->pdf_file; ?>" class="btn btn-outline-secondary float-sm-right"><i class="fas fa-print"></i></a>
		<?php } ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if (isset($order->_ext_order_info)) echo $order->_ext_order_info;?>