<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop search-results">

	<h1 class="search-results__page-title">
		<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>
	</h1>
	<p>
		<?php echo JText::_('COM_SMARTSHOP_SEARCH_RESULTS');?> "<?php echo $this->search; ?>"
	</p>

	<div class="row">
		<?php if (!empty($this->rows)) {
			include  templateOverrideBlock('blocks', 'list_products.php');
		} ?>


	</div>

	<?php if ($this->display_pagination) : ?>
		<div class="search-results__pagination">
			<?php include templateOverrideBlock('blocks', 'block_pagination.php'); ?>
		</div>
	<?php endif; ?>
</div>
