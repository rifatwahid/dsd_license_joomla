 <div class="mb-2 row">
	<label for="price_from" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
		<?php echo JText::_('COM_SMARTSHOP_SEARCH_PRICE_START'); ?> (<?php echo $this->config->currency_code; ?>)
	</label>

	<div class="col-sm-7 col-md-8 col-lg-9">
		<input type="text" name="price_from" id="price_from" placeholder="<?php echo JText::_('COM_SMARTSHOP_SEARCH_PRICE_START'); ?>" class="input" />
	</div>
</div>

<div class="mb-2 row">
	<label for="price_to" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
		<?php echo JText::_('COM_SMARTSHOP_SEARCH_PRICE_END'); ?> (<?php echo $this->config->currency_code; ?>)
	</label>

	<div class="col-sm-7 col-md-8 col-lg-9">
		<input type="text" name="price_to" id="price_to" placeholder="<?php echo JText::_('COM_SMARTSHOP_SEARCH_PRICE_END'); ?>" class="input" />
	</div>
</div>