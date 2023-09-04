<?php
defined('_JEXEC') or die('Restricted access');

$rows = $this->rows;
$products_shipping = $this->products_shipping;
$shippingAttrsTableStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_shippings)) ? 'display: none;' : '';
?>

<div id='tshipping' class='tab-pane'>

	<div class="jshops_edit product_shipping">
		<?php if ($this->isPageWithAdditionalValues) : ?>
			<div class="form-group row align-items-center">
				<label for="is_use_additional_shippings" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">			
					<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_SHIPPINGS'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="is_use_additional_shippings" value="0" checked>
					<input type="checkbox" name="is_use_additional_shippings"  id="is_use_additional_shippings" class="m-0 form-check-input" value="1" <?php if ($this->product->is_use_additional_shippings) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#tshipping .additionalShippingContainer');">
				</div>
			</div>
	<?php endif; ?>

		<div class="table-responsive">
			<table class="adminlist table table-striped additionalShippingContainer" style="<?php echo $shippingAttrsTableStyle; ?>">
				<thead>
					<tr>
						<th width="80">
							<?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
						</th>
						<th width="15%">
							<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?>
						</th>
						<th>
							<?php echo JText::_('COM_SMARTSHOP_COUNTRIES'); ?>
						</th>
					</tr>
				</thead>

				<?php foreach($rows as $k => $v) : ?>
					<tr>
						<td>
							<input type="hidden" name="spm_published[<?php echo $v->sh_pr_method_id; ?>]" value="0">
							<input type="checkbox" class="form-check-input" name="spm_published[<?php echo $v->sh_pr_method_id; ?>]" value="1" <?php if ($products_shipping[$v->sh_pr_method_id]->published || !$this->product->product_id) echo "checked";?>>
						</td>

						<td>
							<?php echo $v->name; ?>
						</td>

						<td>
							<?php echo $v->countries; ?>
                            <?php if($v->states){
                                echo '('. $v->states . ')';
                            }?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>

	<div class="clr"></div>
</div>
