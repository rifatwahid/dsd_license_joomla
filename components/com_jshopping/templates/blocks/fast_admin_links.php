<?php if ( !empty($this->offer_and_order_admin_user_id) ) : ?>
	<div class="btns">
		<a class="link_to_checkout button" href="<?php echo SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=create_offer_cart', 1); ?>">
			<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_OAO_CREATE_OFFER'); ?>
		</a>

		<a class="link_to_checkout button" href="<?php echo SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=create_order_cart', 1); ?>">
			<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_OAO_CREATE_ORDER'); ?>
		</a>
	</div>
<?php endif; ?>
