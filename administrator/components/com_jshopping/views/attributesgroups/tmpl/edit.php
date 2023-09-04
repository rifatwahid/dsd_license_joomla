<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die();
$row = $this->row;
?>
<form action="index.php?option=com_jshopping&controller=attributesgroups" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<div class="jshops_edit attribute_groups_edit">
<?php 
foreach($this->languages as $lang){
    $field = "name_".$lang->language;
?>
	<div class="form-group row align-items-center">
		<label for="<?php print $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
			<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="<?php print $field?>" name="<?php print $field?>" value="<?php echo $row->$field;?>" />
		</div>
	</div>
<?php }?>
	<div class="form-group row align-items-center">
		<label for="hide_title" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
			<?php echo JText::_('COM_SMARTSHOP_HIDE_TITLE'); ?> 
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="hidden" name="hide_title" value="0" />
			<input type="checkbox" class="inputbox form-check-input"  name="hide_title" id="hide_title" value="1" <?php echo ($row->hide_title == 1) ? 'checked="checked"' : ''; ?> />
		</div>
	</div>
<?php $pkey = "etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>    

</div>
<div class="clr"></div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo isset($row->id) ? $row->id : 0; ?>" />
<input type="hidden" name="ordering" value="<?php echo $row->ordering?>" />
<?php print $this->tmp_html_end ?? '' ?>
</form>