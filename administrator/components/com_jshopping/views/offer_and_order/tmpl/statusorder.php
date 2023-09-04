<?php print JText::_('COM_SMARTSHOP_HI'); ?> <?php print $this->order->f_name;?> <?php print $this->order->l_name;?>,
<?php printf(JText::_('COM_SMARTSHOP_YOUR_ORDER_STATUS_CHANGE'), $this->order->order_number);?>

<?php print JText::_('COM_SMARTSHOP_NEW_STATUS_IS'); ?>: <?php print $this->order_status?> 
<?php if ($this->order_detail){?>
<?php print JText::_('COM_SMARTSHOP_ORDER_DETAILS'); ?>: <?php print $this->order_detail?>
<?php }?> 
 
<?php if ($this->comment!=""){?>
<?php print JText::_('COM_SMARTSHOP_COMMENT_YOUR_ORDER'); ?>: <?php print $this->comment;?>

<?php }?>
<?php print $this->vendorinfo->company_name?> 
<?php print $this->vendorinfo->adress?> 
<?php print $this->vendorinfo->zip?> <?php print $this->vendorinfo->city?> 
<?php print $this->vendorinfo->country?> 
<?php print JText::_('COM_SMARTSHOP_CONTACT_PHONE'); ?>: <?php print $this->vendorinfo->phone?> 
<?php print JText::_('COM_SMARTSHOP_CONTACT_FAX'); ?>: <?php print $this->vendorinfo->fax?>
