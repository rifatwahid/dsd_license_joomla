<div class="form-group row align-items-center eafa_tr" id="eafa_tr">
<label for="name_en-GB" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm"><?php echo JText::_('COM_SMARTSHOP_EXCLUDE_BUTTON'); ?></label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
		<?php echo JHTML::_('select.genericlist', $buttons, 'eafa_btn_ids[]', 'class="inputbox form-select" size= "10" multiple="multiple"  ', 'attr_id', 'name', $attrs_ids); ?>
	</div>
</div>