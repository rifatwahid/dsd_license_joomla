<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

$attr_id=$this->attr_id; 
?>
<form action="index.php?option=com_jshopping&controller=attributesvalues&attr_id=<?php echo $attr_id?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php echo $this->tmp_html_start ?? ''; ?>

	<div class="jshops_edit attributes_values_edit">
		<?php foreach($this->languages as $lang) : $field = "name_{$lang->language}"; ?>
			<div class="form-group row align-items-center">
				<label for="<?php print $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_NAME_ATTRIBUT_VALUE'); ?> <?php echo ($this->multilang) ? "({$lang->lang})" : ''; ?>* 
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="text" class="inputbox form-control" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo $this->attributValue->$field; ?>" />
				</div>
			</div>
		<?php endforeach; ?>					
		<div class="form-group row align-items-center">
			<label for="image_label_button" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_IMAGE_ATTRIBUT_VALUE')?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo LayoutHelper::render('fields.media', [
					'name' => 'image',
					'id' => 'image',
					'folder' => 'img_attributes',
					'type' => 'smartshopimgs',
					'preview' => 'tooltip',
					'value' => $this->attributValue->image
				]); ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="<?php print $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_ERPNR'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="ERPnr" name="ERPnr" value="<?php echo $this->attributValue->ERPnr; ?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="<?php print $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_ERPSORT'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="ERPsort" name="ERPsort" value="<?php echo $this->attributValue->ERPsort; ?>" />
			</div>
		</div>			
		<?php 
			$pkey = 'etemplatevar';
			if ($this->$pkey) {
				echo $this->$pkey;
			}
		?>
	</div>
	<div class="clr"></div>

	<input type="hidden" name="old_image" value="<?php echo $this->attributValue->image; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="value_id" value="<?php echo $this->attributValue->value_id; ?>" />
	<input type="hidden" name="value_ordering" value="<?php echo $this->attributValue->value_ordering; ?>" />
	<input type="hidden" name="attr_id" value="<?php echo $attr_id; ?>" />
	<?php echo $this->tmp_html_end ?? ''; ?>
</form>