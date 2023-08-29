<?php
	displaySubmenuOptions("",$this->canDo);
	
	$statusList = $this->statusList;
	$order_status_for_upload = explode(',', $this->params->order_status_for_upload);
	$checkedStatusForProductPage = ($this->params->is_allow_product_page == 1) ? 'checked' : '';
	$checkedStatusForCartPage = ($this->params->is_allow_cart_page == 1) ? 'checked' : '';

	$checkedStatusForFirstUploadDesign = ($this->params->upload_design == 0) ? 'checked' : '';
	$checkedStatusForSecondUploadDesign = ($this->params->upload_design == 1) ? 'checked' : '';
?>

<form action="index.php?option=com_jshopping&controller=upload" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<div class="striped-block jshops_edit upload edit">

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_FILE_TYPE'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="allow_files_types" value="<?php echo $this->params->allow_files_types; ?>">
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_FILE_SIZE'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="allow_files_size" value="<?php echo $this->params->allow_files_size; ?>">
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_UPLOAD_ON_PRODUCT_PAGE'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="is_allow_product_page" value="0" checked>
				<input type="checkbox" class="form-check-input" name="is_allow_product_page" value="1" <?php echo $checkedStatusForProductPage; ?>>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_UPLOAD_ON_CART_PAGE'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="is_allow_cart_page" value="0" checked>
				<input type="checkbox" class="form-check-input" name="is_allow_cart_page" value="1" <?php echo $checkedStatusForCartPage; ?>>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_ALLOWED_STATUS_FOR_UPLOADS_AFTER_PURCHASE'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="order_status_for_upload[0]" value="0" checked>
                <?php foreach($statusList as  $k=>$v){ ?>
					<div align="left">
						<input type="checkbox" class="form-check-input" name="order_status_for_upload[<?php print $v->status_id?>]" value="<?php print $v->status_id?>" <?php if (in_array($v->status_id, $order_status_for_upload)) echo "checked"; ?>> <?php print $v->name?>
					</div>
				<?php } ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_SINGLE_UPLOAD_DESIGN'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="radio" class="form-check-label" name="upload_design" id="upload_design_0" value="0" <?php echo $checkedStatusForFirstUploadDesign; ?>><?php echo JText::_('COM_SMARTSHOP_SIMPLE'); ?>
				<input type="radio" class="form-check-label" name="upload_design" id="upload_design_1" value="1" <?php echo $checkedStatusForSecondUploadDesign; ?>><?php echo JText::_('COM_SMARTSHOP_COMPLEX'); ?>
			</div>
		</div>
    </div>

	<input type="hidden" name="task" value=""/>
</form>
