<?php defined('_JEXEC') or die('Restricted access') ?>
<div class="jshop_edit production_calendar_configurations">
	<form action="index.php?option=com_jshopping&controller=production_calendar" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
		<div class="jshops_edit striped-block production_calendar_config">
			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo  JText::_('COM_SMARTSHOP_PRODUCTION_TIME_DEFAULT');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="number" name="production_time" class="form-control" id="production_time" value="<?php echo $this->production_time ?>" min="0"/>
				</div>
			</div> 

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo  JText::_('COM_SMARTSHOP_PRODUCTION_CALENDAR_SHOW_IN_PRODUCT');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="show_in_product" value="0" />
					<input type="checkbox" name="show_in_product" class="form-check-input" id="show_in_product" value="1" <?php if ($this->show_in_product) echo 'checked="checked"' ?> />
				</div>
			</div> 

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo  JText::_('COM_SMARTSHOP_PRODUCTION_CALENDAR_SHOW_IN_PRODUCT_LIST');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="show_in_product_list" value="0" />
					<input type="checkbox" name="show_in_product_list" class="form-check-input" id="show_in_product_list" value="1" <?php if ($this->show_in_product_list) echo 'checked="checked"' ?> />
				</div>
			</div> 

			<div class="form-group row align-items-center">
				<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo  JText::_('COM_SMARTSHOP_PRODUCTION_CALENDAR_SHOW_IN_CART_CHECKOUT');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="show_in_cart_checkout" value="0" />
					<input type="checkbox" name="show_in_cart_checkout" class="form-check-input" id="show_in_cart_checkout" value="1" <?php if ($this->show_in_cart_checkout) echo 'checked="checked"' ?> />
				</div>
			</div> 
		</div>

		<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0)?>" />
	</form>
</div>