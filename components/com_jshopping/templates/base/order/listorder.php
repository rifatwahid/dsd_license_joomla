<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
$sefLinkSearchOrders = SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 1);
?>

<div class="shop order-list">
	<div class="row-fluid row pb-2">
		<div class="col-sm-12 col-md-6 col-xl-6 col-12 ">
			<h1 class="order-list__page-title">
				<?php echo JText::_('COM_SMARTSHOP_MY_ORDERS'); ?>		
			</h1>
		</div>	
		<div class="col-sm-12 col-md-6 col-xl-6 col-12 ">
            <?php require_once templateOverrideBlock('blocks', 'listorder_search_form.php'); ?>		
		</div>	

		<?php if (!empty($this->orders)) : ?>
		<div class="col-12 list-group">
            <?php require_once templateOverrideBlock('blocks', 'listorder_data.php'); ?>			
		</div>
	<?php else : ?>
		<p><?php echo JText::_('COM_SMARTSHOP_NO_ORDERS'); ?></p>
	<?php endif; ?>

	</div>
</div>