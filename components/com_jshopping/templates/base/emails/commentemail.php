<?php 

echo JText::_('COM_SMARTSHOP_PRODUCT') . ': ' . $this->product_name . '<br/>';
echo JText::_('COM_SMARTSHOP_REVIEW_USER_NAME') . ': ' . $this->user_name . '<br/>';
echo JText::_('COM_SMARTSHOP_REVIEW_USER_EMAIL') . ': ' . $this->user_email . '<br/>';
echo JText::_('COM_SMARTSHOP_REVIEW_MARK_PRODUCT') . ': ' . $this->mark . '<br/>';
echo JText::_('COM_SMARTSHOP_COMMENT') . ':<br/>';
echo nl2br($this->review);
?>