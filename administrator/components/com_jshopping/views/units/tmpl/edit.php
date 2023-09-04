<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$row=$this->units; 
$edit=$this->edit; 
?>
<form  method="post" name="adminForm" id="adminForm">
<?php echo $this->tmp_html_start ?? ''?>
<div class="jshop_edit units">
   <?php
   foreach($this->languages as $lang){
   $field="name_".$lang->language;
   ?>
		<div class="form-group row align-items-center">
			<label for="<?php echo $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo  JText::_('COM_SMARTSHOP_TITLE');?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="<?php print $field;?>" name="<?php print $field;?>" value="<?php echo $row->$field;?>" />
			</div>
		</div>
   <?php }?>
		<div class="form-group row align-items-center">
			<label for="qty" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo  JText::_('COM_SMARTSHOP_BASIC_QTY');?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="qty" name="qty" value="<?php echo $row->qty;?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="unit_number_format" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo  JText::_("COM_SMARTSHOP_NUMBER_FORMAT");?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php print $this->list; ?>
			</div>
		</div>
   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>   
</table>
</fieldset>
</div>
<div class="clr"></div>
 
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo isset($row->id) ? $row->id : 0; ?>" />
<?php print $this->tmp_html_end ?? '' ?>
</form>
</div>