<td>
    <input type="text" class="addonFreeAttrDefVal__textInput form-control" placeholder="<?php echo JText::_('COM_SMARTSHOP_FREE_ATTR_DEFAULT_VAL_DEFAULT_VAL'); ?>" value="<?php echo $this->inputDefaultValue; ?>" name="freeAttrDefVal[<?php echo $this->objWithAttr->id; ?>][defaultVal]">
</td>
<td>
    <input type="text" class="addonFreeAttrMinVal__textInput form-control" placeholder="<?php echo JText::_('COM_SMARTSHOP_FREE_ATTR_MIN_VAL'); ?>" value="<?php echo $this->inputMinValue; ?>" name="freeAttrDefVal[<?php echo $this->objWithAttr->id; ?>][minVal]">
</td>
<td>
    <input type="text" class="addonFreeAttrMaxVal__textInput form-control" placeholder="<?php echo JText::_('COM_SMARTSHOP_FREE_ATTR_MAX_VAL'); ?>" value="<?php echo $this->inputMaxValue; ?>" name="freeAttrDefVal[<?php echo $this->objWithAttr->id; ?>][maxVal]">
</td>

<td class="text-center">
    <input type="checkbox" class="addonFreeAttrDefVal__checkbox form-check-input" <?php echo $this->checkedStatusForFixedValue; ?> value="1" name="freeAttrDefVal[<?php echo $this->objWithAttr->id; ?>][isFixedVal]">
</td>

<td class="addonFreeAttrDefValShowFixed text-center">
	<input type="hidden" class="addonFreeAttrDefValShowFixed__hidden" name="freeAttrDefVal[<?php echo $this->objWithAttr->id; ?>][showFreeAttrInput]" value="0">
	<input type="checkbox" class="addonFreeAttrDefValShowFixed__checkbox form-check-input" name="freeAttrDefVal[<?php echo $this->objWithAttr->id; ?>][showFreeAttrInput]" value="1" <?php echo $this->checkedStatusForShowfreeAttrDefInputs;?>>
</td>