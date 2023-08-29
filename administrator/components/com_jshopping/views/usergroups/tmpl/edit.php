<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$usergroup=$this->usergroup;
?>
<form action="index.php?option=com_jshopping&controller=usergroups" method="post" name="adminForm" id="adminForm">
	<?php print $this->tmp_html_start ?? ''?>
	<div class="jshops_edit usergroups_title_edit">
		<?php
		foreach($this->languages as $lang){
		$name = "name_".$lang->language;
		?>
			<div class="form-group row align-items-center">
				<label for="<?php print $name?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_TITLE');?> <?php if ($this->multilang) print "(".$lang->lang.")";?>* 
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type = "text" class = "inputbox form-control" id = "<?php print $name?>" name = "<?php print $name?>" value = "<?php echo $usergroup->$name?>" />
				</div>
			</div>
		<?php } ?>	
		<div class="form-group row align-items-center">
			<label for="usergroup_is_default" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_USERGROUP_IS_DEFAULT');?>              
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="usergroup_is_default" name="usergroup_is_default" <?php if ($usergroup->usergroup_is_default) echo 'checked="checked"';?> value="1" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="usergroup_discount" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_USERGROUP_DISCOUNT');?>*    
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<div class="input-group">
					<input class="inputbox form-control" type="text" id="usergroup_discount" name="usergroup_discount" value="<?php echo $usergroup->usergroup_discount;?>" /> %
				</div>
			</div>
		</div>
		<?php
		foreach($this->languages as $lang){
		$name = "description_".$lang->language;
		?>
			<div class="form-group row align-items-center">
				<label for="<?php print 'description'.$lang->id; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_DESCRIPTION');?> <?php if ($this->multilang) print "(".$lang->lang.")";?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php 
					if (version_compare(JVERSION, '3.999.999', 'le')) {
						$editor = JFactory::getEditor();
					} else {
						$editor=\JEditor::getInstance(\JFactory::getConfig()->get('editor'));
					}

					print $editor->display('description'.$lang->id, $usergroup->$name, '100%', '350', '75', '20');
					?>
				</div>
			</div>
		<?php }?>
		<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>

	</div>

	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="usergroup_id" value="<?php echo $usergroup->usergroup_id;?>" />
	<?php print $this->tmp_html_end ?? ''?>
</form>