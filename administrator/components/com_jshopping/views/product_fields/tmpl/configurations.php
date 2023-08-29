<?php 
defined('_JEXEC') or die('Restricted access');
$jshopConfig = $this->config;
?>

<form action="index.php?option=com_jshopping&controller=productfields" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<?php echo $this->tmp_html_start ?? ''; ?>

    <div class="jshops_edit striped-block product_fields_configurations">
		<?php if ($jshopConfig->admin_show_product_extra_field) : ?>
			<div class="form-group row align-items-center">
				<label for="product_list_display_extra_fields" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo JText::_('COM_SMARTSHOP_SHOW_EXTRA_FIELDS'); ?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['product_list_display_extra_fields']; ?>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label for="filter_display_extra_fields" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_SHOW_EXTRA_FIELDS_FILTER'); ?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['filter_display_extra_fields']; ?>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label for="cart_display_extra_fields" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_SHOW_EXTRA_FIELDS_CART'); ?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['cart_display_extra_fields']; ?>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label for="pdf_display_extra_fields" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo JText::_('COM_SMARTSHOP_SHOW_EXTRA_FIELDS_IN_PDF'); ?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['pdf_display_extra_fields']; ?>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label for="mail_display_extra_fields" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo JText::_('COM_SMARTSHOP_SHOW_EXTRA_FIELDS_IN_MAIL'); ?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['mail_display_extra_fields']; ?>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label for="product_hide_extra_fields" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo JText::_('COM_SMARTSHOP_HIDE_EXTRA_FIELDS'); ?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['product_hide_extra_fields'];?>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label for="hide_extra_fields_images" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo JText::_('COM_SMARTSHOP_HIDE_EXTRA_FIELDS_IMAGES'); ?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['hide_extra_fields_images']; ?>
				</div>
			</div>
		<?php endif; ?>

		<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0); ?>" />
    </div>
</form>