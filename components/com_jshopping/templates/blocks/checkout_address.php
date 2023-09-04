<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<fieldset class="form-group">
	<div id="qc_address">
		<legend>
			<?php echo JText::_('COM_SMARTSHOP_ADDRESS'); ?>
		</legend>

		<?php 
			if ($this->isUserAuthorized) {
				include templateOverrideBlock('blocks', 'address_handling.php');
			} else {
				include templateOverrideBlock('blocks', 'address_fields.php');
			}
		?>
	</div>
</fieldset>