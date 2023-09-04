<?php 
/**
* @version      4.9.0 22.10.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$row=$this->return_status; 
$edit=$this->edit;  
?>
<form action="index.php?option=com_jshopping&controller=returnstatus" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php print $this->tmp_html_start ?? ''?>
	<div class="jshops_edit returnstatus_edit">
	   <?php
	   foreach($this->languages as $lang){
	   $field="name_".$lang->language;
	   ?>
		<div class="form-group row align-items-center">
			<label for="<?php print $field;?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="<?php print $field;?>" name="<?php print $field;?>" value="<?php echo $row->$field;?>" />
			</div>
		</div>  
	   <?php }?>
		
	<?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
	</div>
	 
	<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task')?>" />
	<input type="hidden" name="edit" value="<?php echo $edit;?>" />
	<?php if ($edit) {?>
	  <input type="hidden" name="status_id" value="<?php echo $row->status_id?>" />
	<?php }?>
	<?php print $this->tmp_html_end ?? ''?>
</form>