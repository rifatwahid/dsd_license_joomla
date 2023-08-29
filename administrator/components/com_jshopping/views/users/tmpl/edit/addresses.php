<?php
    use Joomla\CMS\Session\Session;
?>

<div class="col100">
    <fieldset class="adminform">
    
        <div class="front-user-addresses">

            <div class="user-addresses">
                <?php if (empty($this->userAddresses)) : ?>
                    <?php 
                        echo (new JLayoutFile('smartshop.helpers.alert', null, ['client' => 0]))->render([
                            'type' => 'warning',
                            'message' => JText::_('COM_SMARTSHOP_ADDRESSES_NOT_FOUND')
                        ]);
                    ?>
                <?php endif; ?>

                <a href="/administrator/index.php?option=com_jshopping&controller=users&task=addNewAddress&user_id=<?php echo $user->user_id; ?>" class="user-addresses__new mb-4 pt-3 pb-3 pl-5 ps-5">
                    + <?php echo JText::_('COM_SMARTSHOP_ADD_NEW_ADDRESS'); ?>
                </a>

                <?php if (!empty($this->userAddresses)) : ?>
                    <div class="user-addresses__list">
                        <?php foreach ($this->userAddresses as $userAddress) : ?>
                            <div class="user-address mb-4 pt-3 pb-3 pl-5 ps-5 <?php echo (!empty($userAddress->is_default) || !empty($userAddress->is_default_bill) ? 'user-address--default' : ''); ?>">
								<?php if ($userAddress->firma_name!=""){?>
									<p class="user-address__firma">
										<?php echo "$userAddress->firma_name"; ?>
									</p>
								<?php } ?>
                                <p class="user-address__name">
                                    <?php echo "$userAddress->l_name $userAddress->f_name"; ?>
                                </p>
                                <p class="user-address__address">
                                    <?php echo "{$userAddress->street} {$userAddress->street_nr}, {$userAddress->zip} {$userAddress->city}, {$userAddress->country}"?>
                                </p>

                                <?php if (empty($userAddress->is_default)) : ?>
                                    <a href="/administrator/index.php?option=com_jshopping&controller=users&task=setDefaultAddress&user_id=<?php echo $user->user_id; ?>&defaultId=<?php echo $userAddress->address_id; ?>&<?php echo Session::getFormToken(); ?>=1" class="user-address__as-default"><?php echo JText::_('COM_SMARTSHOP_SET_AS_DEFAULT'); ?></a> 
                                    <span class="user-address__separator">-</span>
                                <?php endif; ?>

                                <?php if (empty($userAddress->is_default_bill)) : ?>
                                    <a href="/administrator/index.php?option=com_jshopping&controller=users&task=setDefaultAddress&isBill=true&user_id=<?php echo $user->user_id; ?>&defaultId=<?php echo $userAddress->address_id; ?>&<?php echo Session::getFormToken(); ?>=1" class="user-address__as-default"><?php echo JText::_('COM_SMARTSHOP_SET_AS_DEFAULT_BILLING'); ?></a> 
                                    <span class="user-address__separator">-</span>
                                <?php endif; ?>

                                <a href="/administrator/index.php?option=com_jshopping&controller=users&task=editAddress&user_id=<?php echo $user->user_id; ?>&editId=<?php echo $userAddress->address_id; ?>" class="user-address__edit"><?php echo JText::_('COM_SMARTSHOP_EDIT'); ?></a> 
                                
                                <?php if (empty($userAddress->is_default) || !empty($userAddress->is_default_bill)) : ?>
                                    <span class="user-address__separator">-</span> 
                                    <a href="/administrator/index.php?option=com_jshopping&controller=users&task=deleteAddress&user_id=<?php echo $user->user_id; ?>&deleteId=<?php echo $userAddress->address_id; ?>&<?php echo Session::getFormToken(); ?>=1" class="user-address__delete"><?php echo JText::_('COM_SMARTSHOP_DELETE'); ?></a> 
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
                    </div>
                <?php endif;?>
            </div>

        </div>
    
    </fieldset>
</div>

<div class="clr"></div>
<?php
    $pkey = 'etemplatevar1';

    if (!empty($this->$pkey)) {
        echo $this->$pkey;
    }
?>