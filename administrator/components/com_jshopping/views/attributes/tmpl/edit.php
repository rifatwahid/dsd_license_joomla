<?php 
/**
* @version      4.8.0 10.02.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php?option=com_jshopping&controller=attributes" method="post" name="adminForm" id="adminForm" enctype = "multipart/form-data">
	<?php print $this->tmp_html_start ?? ''?>
	<div class="jshops_edit attributes_edit">
		<?php 
		foreach($this->languages as $lang){
		$name="name_".$lang->language;
		?>
			<div class="form-group row align-items-center">
				<label for="<?php print $name?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
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
				<label for="<?php print $description; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
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
			<label for="attr_type" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_TYPE_ATTRIBUT');?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $this->type_attribut;?>
			</div>
		</div>
		
		<div class="form-group row align-items-center">
			<label for="independent0" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_DEPENDENT');?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<div class="controls row">
					<label for="independent0" id="independent0-lbl" class="radio col-auto">
						<input type="radio" name="independent" id="independent0" value="0" <?php echo ($this->independentInputCheckedNumber == 0) ? 'checked="checked"' : ''; ?> class="inputbox" size="1">Yes
					</label>
					
					<label for="independent1" id="independent1-lbl" class="radio col-auto">
						<input type="radio" name="independent" id="independent1" value="1" <?php echo ($this->independentInputCheckedNumber == 1) ? 'checked="checked"' : ''; ?> class="inputbox" size="1">No
					</label>
				</div>

			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="group" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_GROUP');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $this->lists['group'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="allcats1" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_SHOW_FOR_CATEGORY');?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<div class="controls row">
					<label for="allcats1" id="allcats1-lbl" class="radio col-auto">
						<input type="radio" name="allcats" id="allcats1" value="1" <?php echo ($this->allcatsInputCheckedNumber == 1) ? 'checked="checked"' : ''; ?> onclick="shopCategory.toggle()">All
					</label>
					
					<label for="allcats0" id="allcats0-lbl" class="radio col-auto">
						<input type="radio" name="allcats" id="allcats0" value="0" <?php echo ($this->allcatsInputCheckedNumber == 0) ? 'checked="checked"' : ''; ?> onclick="shopCategory.toggle()">selected
					</label>
				</div>
			</div>
		</div>
		<div class="form-group row align-items-center" id="tr_categorys" <?php if ($this->attribut->allcats=="1") print "style='display:none;'";?>>
			<label for="categories" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_CATEGORIES');?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $this->lists['categories'];?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="<?php print $name?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_ERPNR'); ?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="ERPnr" name="ERPnr" value="<?php echo $this->attribut->ERPnr?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="<?php print $name?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_ERPSORT'); ?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="ERPsort" name="ERPsort" value="<?php echo $this->attribut->ERPsort?>" />
			</div>
		</div>		
		<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="attr_id" value="<?php echo $this->attribut->attr_id?>" />
	<input type="hidden" name="attr_ordering" value="<?php echo $this->attribut->attr_ordering?>" />
	<?php print $this->tmp_html_end ?? '' ?>
</form>