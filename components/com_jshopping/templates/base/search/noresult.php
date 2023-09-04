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

	<p class="search-results__no-result">
		<?php echo JText::_('COM_SMARTSHOP_SEARCH_RESULTS_NONE');?> "<?php echo $this->search;?>"
	</p>
</div>