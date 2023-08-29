<div class="col-sm-5 col-md-4 col-lg-3">
	<?php echo JText::_('COM_SMARTSHOP_SEARCH_TYPE'); ?>
</div>

<div class="col-sm-7 col-md-8 col-lg-9">
	<div class="form-check form-check-inline">
		<input class="form-check-input d-inline" type="radio" name="search_type" id="search_type_any" value="any" checked="checked">

		<label class="form-check-label" for="search_type_any">
			<?php echo JText::_('COM_SMARTSHOP_SEARCH_ANY'); ?>
		</label>
	</div>

	<div class="form-check form-check-inline">
		<input class="form-check-input d-inline" type="radio" name="search_type" id="search_type_all" value="all">

		<label class="form-check-label" for="search_type_all">
			<?php echo JText::_('COM_SMARTSHOP_SEARCH_ALL'); ?>
		</label>
	</div>

	<div class="form-check form-check-inline">
		<input class="form-check-input d-inline" type="radio" name="search_type" id="search_type_exact" value="exact">

		<label class="form-check-label" for="search_type_exact">
			<?php echo JText::_('COM_SMARTSHOP_SEARCH_EXACT'); ?>
		</label>
	</div>
</div>