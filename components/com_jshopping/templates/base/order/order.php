<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
$jsUri = JSFactory::getJSUri();
$order = $this->order;
$img_path = $this->config->image_product_live_path;
$urlToInvoice = $this->config->pdf_orders_live_path . '/' . $order->pdf_file;
$urlToRefund = $this->config->pdf_orders_live_path . '/refunds/' . $order->order_id .'/';
$isUpoad = $this->isUpoad;
?>

<div class="shop order-details">

	<h1 class="order-details__page-title"><?php echo JText::_('COM_SMARTSHOP_ORDER'); ?> <?php echo $order->order_number; ?></h1>

	<div class="row my-4">
		<div class="col-sm">
			<ul class="list-unstyled">
				<li><span class="font-weight-bold"><?php echo JText::_('COM_SMARTSHOP_ORDER_DATE'); ?>:</span> <?php echo formatdate($order->order_date, 0); ?></li>
				<li><span class="font-weight-bold"><?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS'); ?>:</span> <?php echo $order->status_name; ?></li>
			</ul>
		</div>

		<div class="col-sm">
			<?php require_once templateOverrideBlock('blocks', 'order_ext.php'); ?>			
		</div>
	</div> 

	<div class="row">
		<?php require_once templateOverrideBlock('blocks', 'order_address.php'); ?>
		<?php require_once templateOverrideBlock('blocks', 'order_shipping_address.php'); ?>	
	</div>
	<?php print $this->_tmp_html_after_address_block ?? '';?>
	<form action="<?php echo SEFLink('index.php?option=com_jshopping&controller=user&task=save_order_upload') ?>" id="updateCartForm" method="post" name="updateCart">

		<?php require_once templateOverrideBlock('blocks', 'order_save_upload.php'); ?>
		
		<ul class="list-group my-4">
			<?php $countprod = count($order->items);
			foreach($order->items as $key_id=>$prod) : ?>
				<?php include templateOverrideBlock('blocks', 'order_info.php'); ?>						
			<?php endforeach; ?>
		</ul> 
		<input type="hidden" name="order_id" value="<?php print $order->order_id; ?>"/>
	</form>
	<div class="row">

		<div class="col order-md-2">
			<?php require_once templateOverrideBlock('blocks', 'order_calculation.php'); ?>			
		</div> 

		<div class="col-md-7 col-lg-8 order-md-1">
			<?php require_once templateOverrideBlock('blocks', 'order_data.php'); ?>

			<?php if ($this->allow_cancel && !$this->isDisabledCancelOrder) : ?>
				<a href="<?php echo SEFLink('index.php?option=com_jshopping&controller=user&task=cancelorder&order_id=' . $order->order_id); ?>" class="text-danger"><?php echo JText::_('COM_SMARTSHOP_ORDER_CANCEL'); ?></a>
			<?php endif; ?>

		</div> 

	</div> 


</div> 