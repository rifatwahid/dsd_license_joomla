<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

$productFreeAttrsTableStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_free_attrs) && !$this->isBatchEdit) ? 'display: none;' : '';
?>

<div id="product_freeattribute" class="tab-pane"> 

	<div class="col100">
		<div class="jshops_edit freeattribute_edit">
			<?php if ($this->isPageWithAdditionalValues && !$this->isBatchEdit) : ?>
				<div class="form-group row align-items-center">
					<label for="is_use_additional_free_attrs" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_FREE_ATTRS'); ?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="hidden" name="is_use_additional_free_attrs" value="0" checked>
						<input type="checkbox" id="is_use_additional_free_attrs" class="form-check-input" name="is_use_additional_free_attrs" value="1" <?php if ($this->product->is_use_additional_free_attrs) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#product_freeattribute .admintable');">
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="table-responsive">
		<table class="admintable" width="90%" style="<?php echo $productFreeAttrsTableStyle; ?>">

			<?php if ( !empty($this->listfreeattributes) ) : ?>
				<thead class="freeAttrTHeadEditProd">
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="freeAttrTHeadEditProd__defaultValFixedVal">
							<b><?php echo JText::_('COM_SMARTSHOP_FREE_ATTR_DEFAULT_VAL_FIXED_VAL'); ?></b>
						</td>
						<td class="freeAttrTHeadEditProd__showFixedFreeAttr">
							<b><?php echo JText::_('COM_SMARTSHOP_FREE_ATTR_DEFAULT_SHOW_FIXED_FREEATTR') ?></b>
						</td>
					</tr>
				</thead>
			<?php endif; ?>

		   	<?php foreach($this->listfreeattributes as $freeattrib) : ?>
				<tr>
					<td class="key">
						<?php echo $freeattrib->name; ?>
					</td>

				   	<td>
						<input type="checkbox" name="freeattribut[<?php print $freeattrib->id?>]" class="form-check-input" value="1" <?php if (isset($freeattrib->pactive) && $freeattrib->pactive) echo 'checked="checked"'?> />
				   	</td>

					<?php 
					  	if ( !empty($this->arrWithHtmlRowsOfDefaultValues[$freeattrib->id]) ) {
							echo $this->arrWithHtmlRowsOfDefaultValues[$freeattrib->id];
					  	}
					?>				   	
				</tr>

		   	<?php endforeach; 
		   		$pkey = 'plugin_template_freeattribute'; 

		   		if ( $this->$pkey ) { 
		   			echo $this->$pkey;
		   		}
		   	?>

		</table>
   	</div>
   	</div>

   <div class="clr"></div>
</div>