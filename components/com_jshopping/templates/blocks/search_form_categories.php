 <label for="category_id" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
	<?php echo JText::_('COM_SMARTSHOP_CATEGORIES'); ?>
</label>

<div class="col-sm-7 col-md-8 col-lg-9">
	<?php echo $this->list_categories; ?>
	<input type = "checkbox" name = "include_subcat" id = "include_subcat" value="1" class="input" />

	<label for = "include_subcat">
		<?php echo JText::_('COM_SMARTSHOP_SEARCH_INCLUDE_SUBCATEGORIES'); ?>
	</label>
</div>