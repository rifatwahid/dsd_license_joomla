<?php
	displaySubmenuOptions("",$this->canDo);
	
	$statusList = $this->statusList;
	$params = $this->params;
	
?>

<form action="index.php?option=com_jshopping&controller=returnstatus&task=saveReturnOptions" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<div class="striped-block jshops_edit">

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_ALLOWED_STATUS_FOR_RETURN'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="order_status_for_return[0]" value="0" checked>
                <?php foreach($statusList as  $k=>$v){ ?>
					<div align="left">
						<input type="checkbox" class="form-check-input" name="order_status_for_return[<?php print $v->status_id?>]" value="<?php print $v->status_id?>" <?php if (in_array($v->status_id, $params)) echo "checked"; ?>> <?php print $v->name?>
					</div>
				<?php } ?>
			</div>
		</div>
		
    </div>

	<input type="hidden" name="task" value=""/>
</form>
