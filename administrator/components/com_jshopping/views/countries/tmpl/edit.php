<?php 
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php
	$row=$this->country;
	$lists=$this->lists;
	$edit=$this->edit;
?>

<form action="index.php?option=com_jshopping&controller=countries" method="post"name="adminForm" id="adminForm">
	<?php print $this->tmp_html_start ?? ''?>
	<div class="jshops_edit countries_edit">
		<div class="form-group row align-items-center">
			<label for="country_publish" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" name="country_publish" class="form-check-input" id="country_publish" value="1" <?php if ($row->country_publish) echo 'checked="checked"'?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="ordering" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_ORDERING'); ?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $lists['order_countries']?>
			</div>
		</div>
	   <?php 
	   foreach($this->languages as $lang){
	   $field="name_".$lang->language;
	   ?>
		<div class="form-group row align-items-center">
			<label for="name_<?php print $lang->language;?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="name_<?php print $lang->language;?>" name="name_<?php print $lang->language;?>" value="<?php echo $row->$field;?>" />
			</div>
		</div>
	   <?php }?>
		<div class="form-group row align-items-center">
			<label for="country_code" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CODE'); ?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="country_code" name="country_code" value="<?php echo $row->country_code;?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="country_code_2" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CODE'); ?> 2*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="country_code_2" name="country_code_2" value="<?php echo $row->country_code_2;?>" />
			</div>
		</div>
	   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
	</div>

	<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task')?>" />
	<input type="hidden" name="edit" value="<?php echo $edit;?>" />
	<?php if ($edit) {?>
	  <input type="hidden" name="country_id" value="<?php echo $row->country_id?>" />
	<?php }?>
	<?php print $this->tmp_html_end ?? ''?>
</form>