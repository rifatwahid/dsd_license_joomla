<?php 
$language = JFactory::getLanguage();
$language->load('com_jshopping', JPATH_SITE, $this->language, true);
$configFields = JSFactory::getConfig()->getListFieldsRegister()['address'];
?>

<?php 
    echo JText::_('COM_SMARTSHOP_HI');
    echo $configFields['f_name']['display'] ? ' ' . $this->order->f_name . ' ': '';
    echo $configFields['l_name']['display'] ? $this->order->l_name . ' ': '';
?>,<br/><br/>

<?php JText::printf('COM_SMARTSHOP_YOUR_ORDER_STATUS_CHANGE', $this->order->order_number); ?><br/>

<?php echo JText::_('COM_SMARTSHOP_NEW_STATUS_IS'); ?>: <?php echo $this->order_status; ?><br/><br/>

<?php if ($this->order_detail) : ?>
	<?php echo JText::_('COM_SMARTSHOP_ORDER_DETAILS'); ?>: <?php echo $this->order_detail; ?><br/>
<?php endif; ?>
 
<?php echo $configFields['company_name']['display'] ? $this->vendorinfo->company_name: ''; ?> <br/>
<?php echo $configFields['adress']['display'] ? $this->vendorinfo->adress: ''; ?> <br/>
<?php echo $configFields['zip']['display'] ? $this->vendorinfo->zip: ''; ?> <?php echo $configFields['city']['display'] ? $this->vendorinfo->city: ''; ?> <br/>
<?php echo $configFields['country']['display'] ? $this->vendorinfo->country: ''; ?><br/>
<?php echo $this->comment; ?><br/>

<?php if (!empty($this->vendorinfo->phone) && $configFields['phone']['display']) : ?> 
    <?php echo JText::_('COM_SMARTSHOP_CONTACT_PHONE') ?>: <?php echo $this->vendorinfo->phone; ?> <br/>
<?php endif; ?>

<?php if (!empty($this->vendorinfo->fax) && $configFields['fax']['display']) : ?> 
    <?php echo JText::_('COM_SMARTSHOP_CONTACT_FAX') ?>: <?php echo $this->vendorinfo->fax; ?>
<?php endif; ?>