<?php
/**
* @version      5.9.0 22.02.2023
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
//echo "<pre>";print_r($this->languages);
$row=$this->row;
?>
<form action="index.php?option=com_jshopping&controller=exttaxes&back_tax_id=<?php print $this->back_tax_id;?>" method="post"name="adminForm" id="adminForm">
	<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
	<div class="jshops_edit taxes_ext_edit">
		<?php foreach ($this->languages as $key=>$value){?>
		<div class="form-group row align-items-center">
			<label for="tax_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_TITLE')." ".$value->title." ";//lang_code?> 
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<div class="input-group">
				<input type="text" class="inputbox form-control" id="tax_title_<?php echo $value->lang_code;?>" name="<?php echo $value->lang_code;?>" value="<?php $field=$value->lang_code;echo isset($row->$field) ? $row->$field : "";?>" />
			</div>
			</div>
		</div>  
		<?php } ?>
	   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
	</div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo isset($row->id) ? $row->id : 0; ?>" />
	<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?></form>