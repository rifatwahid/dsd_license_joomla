<?php 
defined('_JEXEC') or die('Restricted access');

$lists = $this->lists;
$jshopConfig = $this->config;
?>

<form action="index.php?option=com_jshopping&controller=taxes" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<?php echo $this->tmp_html_start ?? ''; ?>

    <div class="striped-block jshops_edit">
		<div class="form-group row align-items-center">
			<label for="tax_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label">	
				<?php echo JText::_('COM_SMARTSHOP_EXTENDED_TAX_RULE_FOR'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo $lists['tax_rule_for']; ?>
			</div>
		</div> 

		<div class="form-group row align-items-center">
			<label for="tax_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">	
				<?php $k='tax_on_delivery_address';?>
				<?php echo JText::_('COM_SMARTSHOP_OC_TAX_ON_DELIVERY_ADDRESS'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php if (!empty($this->other_config_checkbox) && in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php echo $k; ?>" value="0">
					<input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" value="1" <?php if ($jshopConfig->$k==1) echo 'checked'?>>
				<?php }elseif (isset($this->other_config_select[$k])){?>
					<?php 
					$option = array();
					foreach($this->other_config_select[$k] as $k2=>$v2){
						$option_name = $v2;
						if (defined(JText::_('COM_SMARTSHOP_OC_'.$k.'_'.$v2))){
							$option_name = constant(JText::_('COM_SMARTSHOP_OC_'.$k."_".$v2));
						}
						$option[] = JHTML::_('select.option', $k2, $option_name, 'id', 'name');
					}
					print JHTML::_('select.genericlist', $option, $k, 'class = "inputbox form-select"', 'id', 'name', $jshopConfig->$k);
					?>
				<?php }else{?>
					<input type="text" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>
				
				<?php if (defined(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO"))) echo JHTML::tooltip(constant(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO")));?>	
			</div>
		</div> 

		<div class="form-group row align-items-center">
			<label for="tax_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php $k='display_tax_id_in_pdf';?>
				<?php print JText::_('COM_SMARTSHOP_OC_DISPLAY_TAX_ID_IN_PDF');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php if (!empty($this->other_config_checkbox) && in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php echo $k; ?>" value="0">
					<input type="checkbox" name="<?php echo $k; ?>" class="form-check-input" value="1" <?php if ($jshopConfig->$k==1) echo 'checked'?>>
				<?php }elseif (isset($this->other_config_select[$k])){?>
					<?php 
					$option = array();
					foreach($this->other_config_select[$k] as $k2=>$v2){
						$option_name = $v2;
						if (defined(JText::_('COM_SMARTSHOP_OC_'.$k.'_'.$v2))){
							$option_name = constant(JText::_('COM_SMARTSHOP_OC_'.$k."_".$v2));
						}
						$option[] = JHTML::_('select.option', $k2, $option_name, 'id', 'name');
					}
					echo JHTML::_('select.genericlist', $option, $k, 'class = "inputbox form-select"', 'id', 'name', $jshopConfig->$k);
					?>
				<?php }else{?>
					<input type="text" name="<?php echo $k; ?>" value="<?php echo $jshopConfig->$k; ?>">
				<?php }?>
				
				<?php if (defined(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO"))) echo JHTML::tooltip(constant(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO")));?>	
			</div>
		</div> 

		<div class="form-group row align-items-center">
			<label for="tax_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CALCULE_TAX_AFTER_DISCOUNT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" name="calcule_tax_after_discount" class="form-check-input" value="1" <?php if ($jshopConfig->calcule_tax_after_discount) echo 'checked="checked"';?> />
			</div>
		</div> 

		<?php if ($jshopConfig->tax) : ?>
			<!--FROM CHECKOUT -->
			<div class="form-group row align-items-center">
				<label for="tax_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_HIDE_TAX')?>
				</label>
				<div class="col-sm-8 col-md-9 col-xl-9 col-12">
					<input type="checkbox" name="hide_tax" class="form-check-input" value="1" <?php if ($jshopConfig->hide_tax) echo 'checked="checked"';?> />
				</div>
			</div> 

			<div class="form-group row align-items-center">
				<label for="tax_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_SHOW_TAX')?>
				</label>
				<div class="col-sm-8 col-md-9 col-xl-9 col-12">
					<input type="checkbox" name="show_tax_in_product" class="form-check-input" value="1" <?php if ($jshopConfig->show_tax_in_product) echo 'checked="checked"';?> />
				</div>
			</div> 

			<div class="form-group row align-items-center">
				<label for="tax_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label">
					<?php echo  JText::_('COM_SMARTSHOP_SHOW_TAX_IN_CART')?>
				</label>
				<div class="col-sm-8 col-md-9 col-xl-9 col-12">
					<input type="checkbox" name="show_tax_product_in_cart" class="form-check-input" value="1" <?php if ($jshopConfig->show_tax_product_in_cart) echo 'checked="checked"';?> />
				</div>
			</div> 
		<?php endif; ?>

		<!-- B2B MSG TAX IN BILL -->
		<div class="form-group row align-items-start">
			<label for="tax_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_SHOW_EU_B2B_TAX_MSG_IN_BILL'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="is_show_eu_b2b_tax_msg_in_bill" class="form-check-input" value="0" />
				<input type="checkbox" name="is_show_eu_b2b_tax_msg_in_bill" onclick="shopHelper.showHideByChecked(this, '.b2bcountriesblock', 'block')" class="form-check-input" value="1" <?php if ($jshopConfig->is_show_eu_b2b_tax_msg_in_bill) echo 'checked="checked"'; ?> />

				<div class="b2bcountriesblock" <?php if (empty($jshopConfig->is_show_eu_b2b_tax_msg_in_bill)) { echo 'style="display: none;"'; } ?>>
					<!-- Countries select -->
					<div class="form-group row align-items-center">
						<label for="shipping_countries_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">	
							<?php echo JText::_('COM_SMARTSHOP_COUNTRIES') . '*' . "<br/><br/><span style='font-weight:normal'>" . JText::_('COM_SMARTSHOP_MULTISELECT_INFO') . '</span>'; ?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12 mb-3">
							<?php echo $lists['countries']; ?>
						</div>
					</div>

					<!-- Applies to -->
					<div class="form-group row align-items-center">
						<label for="shipping_countries_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">	
							<?php echo JText::_('COM_SMARTSHOP_APPLIES_TO'); ?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<?php echo $lists['applies_to']; ?>
						</div>
					</div>
				</div>
			</div>
		</div> 
    </div>

    <input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0); ?>" />
</form>
