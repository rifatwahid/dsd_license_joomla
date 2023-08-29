<?php 
/**
* @version      4.8.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

echo JText::_('COM_SMARTSHOP_PRODUCT') . ': ' . $this->product_name . '<br/>';
echo JText::_('COM_SMARTSHOP_REVIEW_USER_NAME') . ': ' . $this->user_name . '<br/>';
echo JText::_('COM_SMARTSHOP_REVIEW_USER_EMAIL') . ': ' . $this->user_email . '<br/>';
echo JText::_('COM_SMARTSHOP_REVIEW_MARK_PRODUCT') . ': ' . $this->mark . '<br/>';
echo JText::_('COM_SMARTSHOP_COMMENT') . ':<br/>';
echo nl2br($this->review);
?>