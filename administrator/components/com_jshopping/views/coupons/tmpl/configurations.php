<?php 
	defined('_JEXEC') or die('Restricted access');
	$jshopConfig = JSFactory::getConfig();
?>
<form action="index.php?option=com_jshopping&controller=coupons" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<div class="striped-block jshops_edit coupons_config">
		<div class="form-group row align-items-center">
			<label for="use_rabatt_code" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label">	
				<?php echo  JText::_('COM_SMARTSHOP_USE_RABATT_CODE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" name="use_rabatt_code" id="use_rabatt_code"  class="form-check-input" value="1" <?php if ($jshopConfig->use_rabatt_code) echo 'checked="checked"';?> />
			</div>
		</div> 
		<?php $k='user_discount_not_apply_prod_old_price';?>
		<?php if (in_array($k, $this->other_config_checkbox)) : ?>
			<div class="form-group row align-items-center">
				<label for="<?php print $k?>" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label">	
					<?php echo  JText::_('COM_SMARTSHOP_OC_'.strtoupper($k)); ?>
				</label>
				<div class="col-sm-8 col-md-9 col-xl-9 col-12">
					<input type="hidden" name="<?php print $k?>" value="0">
					<input type="checkbox" id="<?php print $k?>"  class="form-check-input" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
				</div>
			</div>
		<?php endif ?>
		<div class="form-group row align-items-center">
			<label for="discount_use_full_sum" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label">	
				<?php echo  JText::_('COM_SMARTSHOP_DISCOUNT_USE_FULL_SUM')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="checkbox" id="discount_use_full_sum" name="discount_use_full_sum" class="form-check-input" value="1" <?php if ($jshopConfig->discount_use_full_sum) echo 'checked="checked"';?> />
			</div>
		</div>
	</div>
	<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0)?>" />
</form>