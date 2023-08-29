<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class = "shop search-form">

	<h1 class="search-form__page-title">
		<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>
	</h1>

	<form action="<?php echo $this->action; ?>" name="form_ad_search" method="post" onsubmit="return shopSearch.changeSorting('form_ad_search');" id="search-form" class="form-horizontal">
		<input type="hidden" name="setsearchdata" value="1">

		<div class="mb-2 row">
			<?php include templateOverrideBlock('blocks', 'search_form_term.php'); ?>	
		</div>

		<div class="mb-2 row">
			<?php include templateOverrideBlock('blocks', 'search_form_type.php'); ?>			
		</div>

		<div class="mb-2 row">		
			<?php include templateOverrideBlock('blocks', 'search_form_categories.php'); ?>			
		</div>

		<div class="mb-2 row">
			<?php include templateOverrideBlock('blocks', 'search_form_manufacturers.php'); ?>			
		</div>

		<?php include templateOverrideBlock('blocks', 'search_form_price.php'); ?>	
		

		<div class="mb-2 row">
			<div class="col-sm-7 col-md-8 col-lg-9 offset-sm-5 offset-md-4 offset-lg-3">
				<button type="submit" class="btn btn-outline-primary d-grid col-md-6 float-end"><?php echo JText::_('COM_SMARTSHOP_SEARCHING'); ?></button>
			</div>
		</div>

	</form>
</div>