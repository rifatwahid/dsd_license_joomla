<?php 
	Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT_SITE . '/helpers/html/');
    use Joomla\CMS\HTML\HTMLHelper;
	use Joomla\CMS\Language\Text;
	
	$d_address = $this->d_address ?? $this->user;
	$configFields = JSFactory::getConfig()->getListFieldsRegister()['address'];
	$country = (int)$this->user->country > 0 ? JSFactory::getModel('countriesFront')->getById($this->user->country)->name : $this->user->country;

	$addresses = [
		'street' => [
			($configFields['street']['display']) ? trim($this->user->street): '',
			($configFields['street_nr']['display']) ? trim($this->user->street_nr): ''
		],
		'city' => [
			($configFields['zip']['display']) ? trim($this->user->zip): '',
			($configFields['city']['display']) ? trim($this->user->city): ''
		],
		'country' => [
			($configFields['country']['display']) ? trim($country): ''
		]
	];
	
	$_addresses = [
		'street' => [
			($configFields['street']['display']) ? trim($d_address->street): '',
			($configFields['street_nr']['display']) ? trim($d_address->street_nr): ''
		],
		'city' => [
			($configFields['zip']['display']) ? trim($d_address->zip): '',
			($configFields['city']['display']) ? trim($d_address->city): ''
		],
		'country' => [
			($configFields['country']['display']) ? trim($d_address->country): ''
		]
	];

	$addressesBilling = [
		'street' => [
			($configFields['street']['display']) ? trim($this->dataOfDefaultBillAddress->street ?? $this->user->street): '',
			($configFields['street_nr']['display']) ? trim($this->dataOfDefaultBillAddress->street_nr ?? $this->user->street_nr): ''
		],
		'city' => [
			($configFields['zip']['display']) ? trim($this->dataOfDefaultBillAddress->zip ?? $this->user->zip): '',
			($configFields['city']['display']) ? trim($this->dataOfDefaultBillAddress->city ?? $this->user->city): ''
		],
		'country' => [
			($configFields['country']['display']) ? trim($this->dataOfDefaultBillAddress->country ?? $country): ''
		]
	];
?>

<?php if (!empty($this->countOfUserAddresses)) : ?>
    
    <fieldset class="form-group billingAddress mb-4">
        <legend class="billingAddress__title">
            <?php echo JText::_('COM_SMARTSHOP_BILL_ADDRESS'); ?>
        </legend>
		
		<?php if ($configFields['firma_name']['display']) : ?>
			<p class="billingAddress__firma">
				<?php 
					echo ($configFields['firma_name']['display']) ? ($this->dataOfDefaultBillAddress->firma_name ?? $this->user->firma_name) . ' ': '';
				?>
			</p>
		<?php endif; ?>

		<?php if ($configFields['f_name']['display'] || $configFields['l_name']['display']) : ?>
			<p class="billingAddress__name">
				<?php 
					echo ($configFields['f_name']['display']) ? ($this->dataOfDefaultBillAddress->f_name ?? $this->user->f_name) . ' ': '';
					echo ($configFields['l_name']['display']) ? ($this->dataOfDefaultBillAddress->l_name ?? $this->user->l_name): '';
				?>
			</p>
		<?php endif; ?>

        <p class="billingAddress__addresses">
			<?php if ($configFields['street']['display']) : ?>
				<span class="billingAddress__street">
					<?php echo $this->dataOfDefaultBillAddress->street ?? $this->user->street; ?>
				</span> 
			<?php endif; ?>
			
			<?php if ($configFields['street_nr']['display']) : ?>
				<span class="billingAddress__street_nr">
					<?php echo $this->dataOfDefaultBillAddress->street_nr ?? $this->user->street_nr; ?>
				</span>
			<?php endif; ?>
			
			<?php if (isThereAtLeastOneNotEmpty($addressesBilling['street']) && (isThereAtLeastOneNotEmpty($addressesBilling['city'])  || isThereAtLeastOneNotEmpty($addressesBilling['country']))) : ?>
				<span class="address-comma">,</span> 
			<?php endif; ?>
			
			<?php if ($configFields['zip']['display']) : ?>
				<span class="billingAddress__zip">
					<?php echo $this->dataOfDefaultBillAddress->zip ?? $this->user->zip; ?>
				</span> 
			<?php endif; ?>
			
			<?php if ($configFields['city']['display']) : ?>
				<span class="billingAddress__city">
					<?php echo $this->dataOfDefaultBillAddress->city ?? $this->user->city; ?>
				</span>
			<?php endif; ?>

			<?php if (isThereAtLeastOneNotEmpty($addressesBilling['country']) && (isThereAtLeastOneNotEmpty($addressesBilling['city'])  || isThereAtLeastOneNotEmpty($addressesBilling['street']))) : ?>
				<span class="address-comma">,</span> 
			<?php endif; ?>
			
			<?php if ($configFields['country']['display']) : ?>
				<span class="billingAddress__country">
					<?php echo $this->dataOfDefaultBillAddress->country ?? $country; ?>
				</span>
			<?php endif; ?>
        </p>

        <!-- Modal btn -->
        <?php echo HTMLHelper::_('smartshopmodal.renderButton', 'billingAddress', 'userAddressesPopup', 'onclick="shopUserAddressesPopup.setAddressTypeToHandler(\'billing\')";', JText::_('COM_SMARTSHOP_CHANGE_ADDRESS')); ?>
        
        <input type="hidden" name="billingAddress_id" value="<?php echo $this->dataOfDefaultBillAddress->address_id ?? $this->user->address_id; ?>">
    </fieldset>

    <fieldset class="form-group shippingAddress mb-4">
        <legend class="shippingAddress__title">
            <?php echo JText::_('COM_SMARTSHOP_SHIPPING_ADDRESS'); ?>
        </legend>
		
		<?php if ($configFields['firma_name']['display']) : ?>
			<p class="shippingAddress__firma">
				<?php 
					echo ($configFields['firma_name']['display']) ? $d_address->firma_name . ' ': '';
				?>
			</p>
		<?php endif; ?>

		<?php if ($configFields['f_name']['display'] || $configFields['l_name']['display']) : ?>
			<p class="shippingAddress__name">
				<?php 					
					echo ($configFields['f_name']['display']) ? $d_address->f_name . ' ': '';
					echo ($configFields['l_name']['display']) ? $d_address->l_name: '';
				?>
			</p>
		<?php endif; ?>

        <p class="shippingAddress__addresses">

			<?php if ($configFields['street']['display']) : ?>
				<span class="shippingAddress__street">
					<?php echo $d_address->street; ?>
				</span>
			<?php endif; ?>

			<?php if ($configFields['street_nr']['display']) : ?>
				<span class="shippingAddress__street_nr">
					<?php echo $d_address->street_nr; ?>
				</span>
			<?php endif; ?>

			<?php if (isset($d_addresses) && isThereAtLeastOneNotEmpty($d_addresses['street']) && (isThereAtLeastOneNotEmpty($d_addresses['city'])  || isThereAtLeastOneNotEmpty($d_addresses['country']))) : ?>
				<span class="address-comma">,</span> 
			<?php endif; ?>

			<?php if ($configFields['zip']['display']) : ?>
				<span class="shippingAddress__zip">
					<?php echo $d_address->zip; ?>
				</span> 
			<?php endif; ?>

			<?php if ($configFields['city']['display']) : ?>
				<span class="shippingAddress__city">
					<?php echo $d_address->city; ?>
				</span>
			<?php endif; ?>

			<?php if (isset($d_addresses) && isThereAtLeastOneNotEmpty($d_addresses['country']) && (isThereAtLeastOneNotEmpty($d_addresses['city'])  || isThereAtLeastOneNotEmpty($d_addresses['street']))) : ?>
				<span class="address-comma">,</span> 
			<?php endif; ?>

			<?php if ($configFields['country']['display']) : ?>
				<span class="shippingAddress__country">
					<?php echo  $d_address->country; ?>
				</span>
			<?php endif; ?>
        </p>

        <!-- Modal btn -->
        <?php echo HTMLHelper::_('smartshopmodal.renderButton', 'shippingAddress', 'userAddressesPopup', 'onclick="shopUserAddressesPopup.setAddressTypeToHandler(\'shipping\');"', JText::_('COM_SMARTSHOP_CHANGE_ADDRESS')); ?>

        <input type="hidden" name="shippingAddress_id" value="<?php echo $d_address->address_id; ?>">
    </fieldset>

    <!-- Modal -->
    <?php echo HTMLHelper::_('smartshopmodal.renderWindow', 'userAddressesPopup', JText::_('COM_SMARTSHOP_SELECT_ADDRESS'), '<iframe src="/index.php?option=com_jshopping&controller=user&task=addressPopup" id="selectAddressPopup" frameborder="0"></iframe>'); ?>
<?php else : ?>
    <?php 
        echo (new JLayoutFile('smartshop.helpers.alert', null, ['client' => 0]))->render([
            'type' => 'error',
            'message' => sprintf(Text::_('COM_SMARTSHOP_PLEASE_ADD_YOUR_ADDRESS__LINK'), SEFLink('/index.php?option=com_jshopping&controller=user&task=addNewAddress')),
            'isCloseable' => false
        ]);
    ?>
<?php endif;?>