<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$row=$this->deliveryTimes; 
$edit=$this->edit; 
?>
<form action="index.php?option=com_jshopping&controller=deliverytimes" method="post" name="adminForm" id="adminForm">
	<?php print $this->tmp_html_start ?? ''?>
	<div class="jshops_edit deliverytimes_edit">
		<?php
		foreach($this->languages as $lang){
		$field="name_".$lang->language;
		?>
			<div class="form-group row align-items-center">
				<label for="<?php print $field;?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_TITLE')?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="text" class="inputbox form-control" id="<?php print $field;?>" name="<?php print $field;?>" value="<?php echo $row->$field;?>" />
				</div>
			</div>  
		<?php }?>
		<div class="form-group row align-items-center">
			<label for="days" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">	
				<?php echo JText::_('COM_SMARTSHOP_DAYS')?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" name="days"  id="days" value="<?php echo $row->days?>" />
			</div>
		</div>  
		<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>   
	</div>
	 
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo isset($row->id) ? $row->id : 0; ?>" />
	<?php print $this->tmp_html_end ?? ''?>
</form>