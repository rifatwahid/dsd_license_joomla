<?php 
    $configFields = JSFactory::getConfig()->getListFieldsRegister()['address'];
?>

<div class="front-user-addresses shop">

	<h1 class="hidden"><?php echo JText::_('COM_SMARTSHOP_ADDRESSES'); ?></h1>
    <div class="user-addresses">
	
        <a href="<?php echo SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress', 1); ?>" class="user-addresses__new mb-4 pt-3 pb-3 ps-5">
            + <?php echo JText::_('COM_SMARTSHOP_ADD_NEW_ADDRESS'); ?>
        </a>

        <?php if (!empty($this->userAddresses)) : ?>
            <div class="user-addresses__list">
                <?php foreach ($this->userAddresses as $userAddress) : ?>
					<div class="user-address mb-4 pt-3 pb-3 ps-5 <?php echo (!empty($userAddress->is_default) || !empty($userAddress->is_default_bill) ? 'user-address--default' : ''); ?>">
						
						<?php include templateOverrideBlock('blocks', 'addresses_adress.php'); ?>
						
						<?php if (empty($userAddress->is_default)) : ?>
							<a href="<?php echo SEFLink("index.php?option=com_jshopping&controller=user&task=setDefaultAddress&defaultId={$userAddress->address_id}&" . JSession::getFormToken() . '=1', 1); ?>" class="user-address__as-default"><?php echo JText::_('COM_SMARTSHOP_SET_AS_DEFAULT'); ?></a> 
							<span class="user-address__separator">-</span>
						<?php endif; ?>

						<?php if (empty($userAddress->is_default_bill)) : ?>
							<a href="<?php echo SEFLink("index.php?option=com_jshopping&controller=user&task=setDefaultAddress&isBill=true&defaultId={$userAddress->address_id}&" . JSession::getFormToken() . '=1', 1); ?>" class="user-address__as-default"><?php echo JText::_('COM_SMARTSHOP_SET_AS_DEFAULT_BILLING'); ?></a> 
							<span class="user-address__separator">-</span>
						<?php endif; ?>

						<a href="<?php echo SEFLink("index.php?option=com_jshopping&controller=user&task=editAddress&editId={$userAddress->address_id}", 1); ?>" class="user-address__edit"><?php echo JText::_('COM_SMARTSHOP_EDIT'); ?></a> 

						<?php if (empty($userAddress->is_default) || !empty($userAddress->is_default_bill)) : ?>
							<span class="user-address__separator">-</span> 
							<a href="<?php echo SEFLink("index.php?option=com_jshopping&controller=user&task=deleteAddress&deleteId={$userAddress->address_id}&" . JSession::getFormToken() . '=1', 1); ?>" class="user-address__delete"><?php echo JText::_('COM_SMARTSHOP_DELETE'); ?></a> 
						<?php endif;?>

						<?php if (!empty($userAddress->is_default) || !empty($userAddress->is_default_bill)) : ?>
							<div class="user-address__default">
								<p class="user-address__default-text">
									<?php 
										if (!empty($userAddress->is_default) && !empty($userAddress->is_default_bill)) {
											echo JText::_('COM_SMARTSHOP_DEFAULT');
										} elseif (!empty($userAddress->is_default)) {
											echo JText::_('COM_SMARTSHOP_ADDRESS_SHIPPING');
										} else {
											echo JText::_('COM_SMARTSHOP_ADDRESS_BILLING');
										}
									?>
								</p>
							</div>
						<?php endif; ?>
					</div>
                <?php endforeach;?>

                <?php echo JHtml::_('form.token'); ?>
            </div>
        <?php endif;?>
    </div>

</div>