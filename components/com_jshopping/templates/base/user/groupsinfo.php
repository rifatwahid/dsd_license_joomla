<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop shop-groups shop">
	<h1 class="shop-groups__page-title">
		<?php echo JText::_('COM_SMARTSHOP_GROUPS'); ?>
	</h1>

	<div class="row">
		<div class="col-sm-auto">
			<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?>

			<?php foreach($this->rows as $row) : ?>
				<div>
					<?php echo $row->name; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="col-sm-auto">
			<?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>

			<?php foreach($this->rows as $row) : ?>
				<div>
					<?php echo $row->usergroup_discount; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div> <!-- .row -->

</div> <!-- .shop-groups -->