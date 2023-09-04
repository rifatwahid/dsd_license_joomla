<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop shop-logout">
  	<h1 class="shop-logout__page-title">
		<?php echo JText::_('COM_SMARTSHOP_LOGOUT'); ?>
	</h1>

	<a class="btn btn-outline-secondary" href="<?php echo SEFLink("index.php?option=com_jshopping&controller=user&task=logout"); ?>">
	  <?php echo JText::_('COM_SMARTSHOP_LOGOUT'); ?>
	</a>
</div>
