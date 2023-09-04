<?php
	defined('_JEXEC') or die('Restricted access');

	use Joomla\CMS\Language\Text;

	$productUserGroupPermissionTableStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_usergroup_permission)) ? 'display: none;' : '';
?>

<div id="usergroup_permissions" class="tab-pane"> 
	<?php if ($this->isPageWithAdditionalValues) : ?>
		<div class="jshops_edit usergroup_permissions">
			<div class="form-group row align-items-center">
				<label for="is_use_additional_customize" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">			
					<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_USERGROUP_PERMISSION'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="is_use_additional_usergroup_permission" value="0" checked>
					<input type="checkbox" name="is_use_additional_usergroup_permission" class="form-check-input" value="1" <?php if ($this->product->is_use_additional_usergroup_permission) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#usergroup_permissions .admintable');">
				</div>
			</div>
		</div>
	<?php else : ?>
		<input type="hidden" name="is_use_additional_usergroup_permission" value="1" checked>
	<?php endif; ?>

	<div class="admintable form-horizontal" style="<?php echo $productUserGroupPermissionTableStyle; ?>">
		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_USERGROUP_SHOW_PRODUCT'); ?>
			</div>

			<div class="control-label">
				<?php echo $this->lists['usergroup_show_product']; ?>
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_USERGROUP_SHOW_PRICE'); ?>
			</div>
			<div class="control-label">
				<?php echo $this->lists['usergroup_show_price']; ?>
			</div>
		</div>

		<div class="control-group">
			<div class="control-label name">
				<?php echo Text::_('COM_SMARTSHOP_USERGROUP_SHOW_BUY'); ?>
			</div>
			<div class="control-label">
				<?php echo $this->lists['usergroup_show_buy']; ?>
			</div>
		</div>
	</div>
</div>