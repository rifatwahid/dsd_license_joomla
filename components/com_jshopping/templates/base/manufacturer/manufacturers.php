<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop manufacturer-list">

	<h1 class="manufacturer-list__page-title"><?php echo JText::_('COM_SMARTSHOP_MANUFACTURERS'); ?></h1>

	<div class="row">
		<?php if ($this->rows) : ?>
			<?php foreach($this->rows as $k=>$row) : ?>				
				<?php include  templateOverrideBlock('blocks', 'manufacturers_info.php'); ?>				
			<?php endforeach; ?>
		<?php endif; ?>
	</div> 

	<?php if ($this->display_pagination) : ?>
		<div class="manufacturer-list__page-title">
			<?php echo $this->pagination; ?>
		</div>
	<?php endif;  ?>

</div> 