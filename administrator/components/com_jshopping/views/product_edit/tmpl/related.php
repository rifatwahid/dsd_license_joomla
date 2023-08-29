<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$productRelatedStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_related_products) && !$this->isBatchEdit) ? 'display: none;' : '';
?>

<div id="product_related" class="tab-pane">
	 <div class="jshops_edit product_related">
		<?php if ($this->isPageWithAdditionalValues && !$this->isBatchEdit) : ?>
			<div class="form-group row align-items-center">
				<label for="is_use_additional_related_products" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_RELATED_PRODUCTS'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="is_use_additional_related_products" value="0" checked>
					<input type="checkbox" id="is_use_additional_related_products" class="form-check-input" name="is_use_additional_related_products" value="1" <?php if ($this->product->is_use_additional_related_products) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#product_related .productRelated__data');">
                </div>
            </div>
		<?php else : ?>
			<input type="hidden" name="is_use_additional_related_products" value="1" checked>
		<?php endif; ?>
	</div>

	<div class="productRelated__data" style="<?php echo $productRelatedStyle; ?>">
		<div class="col100">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_SMARTSHOP_PRODUCT_RELATED'); ?></legend>

				<div id="list_related">
					<?php
					foreach($this->related_products as $row_related) :
						$row_related->image = $row_related->image ?: $jshopConfig->noimage;
					?>      
						<div class="block_related" id="related_product_<?php echo $row_related->product_id; ?>">
							<div class="block_related_inner">
								<div class="name">
									<?php echo $row_related->name;?> (ID:&nbsp;<?php echo $row_related->product_id; ?>)
								</div>

								<div class="image">
									<a href="index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php echo $row_related->product_id; ?>">
										<img src="<?php echo getPatchProductImage($row_related->image, 'thumb', 1); ?>" width="90" border="0" />
									</a>
								</div>

								<div style="padding-top:5px;">
									<input type="button" class="btn btn-danger btn-small" value="<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>" onclick="shopProductRelated.delete(<?php echo $row_related->product_id; ?>)">
								</div>
								<input type="hidden" name="related_products[]" value="<?php echo $row_related->product_id; ?>"/>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</fieldset>
		</div>
		
		<div class="clr"></div>
		<?php $pkey='plugin_template_related'; if ($this->$pkey){ echo $this->$pkey;}?>
		<br/>
		
		<div class="col100">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?></legend>

				<div class="row">
					<div class="col-4">
						<div class="input-group">
							<input type="text" id="related_search" class="form-control" size="35" onkeypress="shopSearch.searchRelatedEnterKeyPress(event,0,'<?php echo $row->product_id; ?>');">

							<button type="button" class="btn btn-primary" value="<?php echo JText::_('COM_SMARTSHOP_SEARCH');?>" onclick="shopProductRelated.search(0, '<?php echo $row->product_id; ?>');">
								<span class="icon-search" aria-hidden="true"></span>
							</button>
						</div>
					</div>
				</div>

				<br/>
				<div id="list_for_select_related"></div>
			</fieldset>
		</div>
		<div class="clr"></div>
	</div>
</div>