 <p class="h4 text-secondary"><?php echo JText::_('COM_SMARTSHOP_GUEST_NO_ACCOUNT_TITLE'); ?></p>

<?php if ($cart->count_product && $this->config->shop_user_guest && $this->show_pay_without_reg) : ?>
	<p class="text-secondary">
		<?php echo JText::_('COM_SMARTSHOP_GUEST_NO_ACCOUNT_TO_CHECKOUT_TEXT'); ?>
	</p>

	<a class="btn btn-outline-secondary d-grid col-md-6 float-end" href="<?php echo SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step2', 1, 0, $this->config->use_ssl); ?>">
		<?php echo JText::_('COM_SMARTSHOP_TO_CHECKOUT'); ?>
	</a>
<?php else : ?>
	<p class="text-secondary">
		<?php echo JText::_('COM_SMARTSHOP_GUEST_NO_ACCOUNT_TO_REGISTRATION_TEXT'); ?>
	</p>

	<a class="btn btn-outline-secondary d-grid col-md-6 float-end" href="<?php echo $this->href_register; ?>">
		<?php echo JText::_('COM_SMARTSHOP_REGISTER'); ?>
	</a>
<?php endif; ?>