<?php

defined('_JEXEC') or die('Restricted access');

$jshopConfig = $this->config;
$lists = $this->lists;
$dispatcher = \JFactory::getApplication();
?>

<div class="jshop_edit product_edit_list">
	<form action="index.php?option=com_jshopping&controller=products" method="post" name="adminForm" id="adminForm" id="item-form">
		<ul class="nav nav-tabs">    
			<li class="active">
				<a href="#main-page" data-toggle="tab">
					<?php echo JText::_('COM_SMARTSHOP_INFO_PRODUCT'); ?>
				</a>
			</li>

			<?php if ($jshopConfig->admin_show_product_extra_field) : ?>
				<li>
					<a href="#product_extra_fields" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_EXTRA_FIELDS'); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if (empty($this->product->parent_id)) {
				$dispatcher->triggerEvent('onDisplayProductEditListTabsTab', [&$lists]);
			} ?>
		</ul>

		<div id="editdata-document" class="tab-content">
			<div id="main-page" class="tab-pane active">
				<div class="col100">
					<table class="admintable" width="90%">
						<tr>
							<td class="key" style="width:180px;">
								<?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
							</td>
							<td>
								<?php echo $this->lists['product_publish'];?>
							</td>
						</tr>

						<tr>
							<td class="key" style="width:180px;">
								<?php echo JText::_('COM_SMARTSHOP_ACCESS');?>
							</td>
							<td>
								<?php echo $this->lists['access'];?>
							</td>
						</tr>    

						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE');?>
							</td>
							<td>
								<?php echo $this->lists['price_mod_price'];?>
								<input type="text" name="product_price" value="" />
								<?php echo $this->lists['currency'];?>
							</td>
						</tr>

						<tr>
							<td class="key">
								<?php echo  JText::_('COM_SMARTSHOP_OLD_PRICE');?>
							</td>
							<td>
								<span id='foldprice'><?php echo $this->lists['price_mod_old_price'];?>
								<input type = "text" name = "product_old_price" value = "" />
								<span style="width:5px;"></span>
								</span>
								<input type="checkbox" name="use_old_val_price" value="1" onclick="shfoldprice(this.checked)"> <?php echo JText::_('COM_SMARTSHOP_USE_OLD_VALUE_PRICE'); ?>
							</td>
						</tr>

						<?php if ($jshopConfig->admin_show_product_bay_price) { ?>
							<tr>
								<td class="key">
									<?php echo JText::_('COM_SMARTSHOP_PRODUCT_BUY_PRICE');?>
								</td>
								<td>
									<input type="text" name="product_buy_price" value="" />
								</td>
							</tr>
						<?php } ?>

						<!-- Type -->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_TYPE');?>
							</td>
							<td>
								<?php echo $this->lists['product_packing_type']; ?>
							</td>
						</tr>
					
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT');?>
							</td>
							<td>
								<input type="text" name="product_weight" value="" /> <?php echo sprintUnitWeight(); ?>
							</td>
						</tr>

						<!--- Expiration date --->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_PRODUCT_EXPIRATION_DATE');?>
							</td>
							<td>
								<input type="date" name="expiration_date" id="expiration_date" />
							</td>
						</tr>

						<!--- Product code --->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT');?>
							</td>
							<td>
								<input type="text" name="product_ean" id="product_ean"/>
							</td>
						</tr>

						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_QUANTITY_PRODUCT');?>
							</td>
							<td>
								<div id="block_enter_prod_qty" style="padding-bottom:2px;">
									<input type="text" name="product_quantity" id="product_quantity" value="" />
								</div>
								<div>         
									<input type="checkbox" name="unlimited" value="1" onclick="shopProductCommon.toggleQuantity(this.checked)" /> <?php print JText::_('COM_SMARTSHOP_UNLIMITED');?>
								</div>         
							</td>
						</tr>

						<!--- Factory --->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_FACTORY');?>
							</td>
							<td>
								<input type="text" name="factory" id="factory" />
							</td>
						</tr>

						<!--- Storage --->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_STORAGE');?>
							</td>
							<td>
								<input type="text" name="storage" id="storage" />
							</td>
						</tr>

						<?php if ($jshopConfig->use_different_templates_cat_prod) { ?>
							<tr>
								<td class="key">
									<?php echo  JText::_('COM_SMARTSHOP_TEMPLATE_PRODUCT');?>
								</td>
								<td>
									<?php echo $lists['templates'];?>
								</td>
							</tr>
						<?php } ?>
					
						<?php if (!$this->withouttax){?>
							<tr>     
								<td class="key">
									<?php echo JText::_('COM_SMARTSHOP_NAME_TAX');?>*
								</td>
								<td>
									<?php echo $lists['tax'];?>
								</td>
							</tr>
						<?php }?>

						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_NAME_MANUFACTURER');?>
							</td>
							<td>
								<?php echo $lists['manufacturers'];?>
							</td>
						</tr>

						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_CATEGORIES');?>*
							</td>
							<td>
								<?php echo $lists['categories'];?>
							</td>
						</tr>

						<!--- Production time --->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_PRODUCTION_TIME');?>
							</td>
							<td>
								<input type="number" min="0" name="production_time" />
							</td>
						</tr>

						<?php if ($jshopConfig->admin_show_vendors && $this->display_vendor_select) { ?>
							<tr>
								<td class="key">
									<?php echo JText::_('COM_SMARTSHOP_VENDOR');?>
								</td>
								<td>
									<?php echo $lists['vendors'];?>
								</td>
							</tr>
						<?php }?>
					
						<?php if ($jshopConfig->admin_show_delivery_time) : ?>
							<tr>
								<td class="key">
									<?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME');?>
								</td>
								<td>
									<?php echo $lists['deliverytimes'];?>
								</td>
							</tr>
						<?php endif; ?>
					
						<?php if ($jshopConfig->admin_show_product_labels) : ?>
							<tr>
								<td class="key">
									<?php echo JText::_('COM_SMARTSHOP_LABEL');?>
								</td>
								<td>
									<?php echo $this->lists['labels'];?>
								</td>
							</tr>
						<?php endif; ?>

						<!--- NO RETUN --->
						<?php if (!$jshopConfig->no_return_all) : ?>  
							<tr>
								<td class="key">
									<?php echo JText::_('COM_SMARTSHOP_NO_RETURN');?>
								</td>
								<td>
									<input type="hidden" name="options[no_return]"  value="0" />
									<input type="checkbox" name="options[no_return]" value="1" />
								</td>
							</tr>
						<?php endif; ?>

						<!--- Quantity --->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_QUANTITY_SELECT_LABLE'); ?>
							</td>
							<td>
								<label for="equal_steps" class="col-form-label col-form-label-sm">
									<?php echo JText::_('COM_SMARTSHOP_EQUAL_STEPS_LABLE'); ?>
									<input type="hidden" name="equal_steps"  value="0" />
									<input type="checkbox" name="equal_steps" value="1"/>
								</label>
								<input name="quantity_select" step="1" type="text" id="quantity_select" size="80" />
							</td>
						</tr>

						<!--- Max number of items --->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_ERROR_MAX_COUNT_ORDER_ONE_PRODUCT'); ?>
							</td>
							<td>
								<input type="text" id="max_count_product" name="max_count_product"/>
							</td>
						</tr>

						<!--- Min number of items --->
						<tr>
							<td class="key">
								<?php echo JText::_('COM_SMARTSHOP_ERROR_MIN_COUNT_ORDER_ONE_PRODUCT'); ?>
							</td>
							<td>
								<input type="text" name="min_count_product" id="min_count_product" />
							</td>
						</tr>
					
						<?php if ($jshopConfig->admin_show_product_basic_price) : ?>
							<tr>
								<td class="key">
									<br/><?php echo JText::_('COM_SMARTSHOP_BASIC_PRICE'); ?>
								</td>
							</tr>

							<tr>
								<td class="key">
									<?php echo JText::_('COM_SMARTSHOP_WEIGHT_VOLUME_UNITS');?>
								</td>
								<td>
									<input type="text" name="weight_volume_units" />
								</td>
							</tr>

							<tr>
								<td class="key">
									<?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE');?>
								</td>
								<td>
									<?php echo $lists['basic_price_units'];?>
								</td>
							</tr>
						<?php endif; ?>

						<?php 
							$pkey='etemplatevar'; 
							if ($this->$pkey) { 
								echo $this->$pkey;
							}
						?>
					</table>
				</div>
			</div>

			<?php if ($jshopConfig->admin_show_product_extra_field) : ?>
				<div id="product_extra_fields" class="tab-pane">
					<?php include __DIR__  . '/extrafields_inner.php'; ?>
				</div>
			<?php endif; 
			
				$dispatcher->triggerEvent('onDisplayProductEditListTabsEndTab', [&$lists]);
			?>
		</div>

		<input type="hidden" name="task">
		<?php foreach($this->cid as $cid) : ?>
			<input type="hidden" name="cid[]" value="<?php echo $cid; ?>">
		<?php endforeach; ?>
	</form>
</div>

<script>
	function shfoldprice(checked) {
		let foldPriceEl = document.querySelector('#foldprice');

		if (foldPriceEl) {
			if (checked) {
				foldPriceEl.style.display = 'none';
			} else {
				foldPriceEl.style.display = 'block';
			}
		}
	}
</script>