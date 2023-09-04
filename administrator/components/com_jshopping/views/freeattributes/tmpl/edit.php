<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php?option=com_jshopping&controller=freeattributes" method="post" name="adminForm" id="adminForm">
	<?php print $this->tmp_html_start ?? ''?>
	<div class="jshops_edit freeattributes_edit">
		<?php 
		foreach($this->languages as $lang){
		$name="name_".$lang->language;
		?>
		<div class="form-group row align-items-center">
			<label for="<?php print $name?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>* 
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="<?php print $name?>" name="<?php print $name?>" value="<?php echo $this->attribut->$name?>" />
			</div>
		</div>
		<?php } ?>
		<?php 
		foreach($this->languages as $lang){
		$description="description_".$lang->language;
		?>
		<div class="form-group row align-items-center">
			<label for="<?php print $description?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
			  <?php $editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));  
				print $editor->display($description,  $this->attribut->$description , '100%', '350', '75', '20' ) ;
			  ?>
			</div>
		</div>
		<?php } ?>	
		<div class="form-group row align-items-center">
			<label for="required" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_REQUIRED');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="required" name="required" value="1" <?php if ($this->attribut->required) print "checked";?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="unitList" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php print $this->unitList; ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_unit" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_SHOW_UNIT');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="show_unit" value="0"  />
				<input type="checkbox" class="form-check-input" id="show_unit" name="show_unit" value="1" <?php if ($this->attribut->show_unit) print "checked";?> />
			</div>
		</div>
		<?php if (isset($this->type) && $this->type){print $this->type;}?>
		<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
	</div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="id" value="<?php echo $this->attribut->id?>" />
	<?php print $this->tmp_html_end ?? ''?>
</form>