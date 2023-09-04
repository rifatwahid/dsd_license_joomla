<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop category-list">

	<h1 class="category-list__page-title"><?php echo JText::_('COM_SMARTSHOP_CATEGORIES'); ?></h1>

	<div class="row">
		<?php if ($this->categories) : ?>
			<?php foreach($this->categories as $k => $category) : ?>
				
				<?php include  templateOverrideBlock('blocks', 'category_info.php'); ?>
				
			<?php endforeach; ?>
		<?php endif; ?>
	</div> 

</div> 
