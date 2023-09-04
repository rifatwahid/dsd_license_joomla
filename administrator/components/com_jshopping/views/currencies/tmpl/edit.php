<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$row=$this->currency;
$lists=$this->lists;
$edit=$this->edit;
?>

<form action="index.php?option=com_jshopping&controller=currencies" method="post"name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? '' ?>
<div class="jshops_edit currencies_edit">
	<div class="form-group row align-items-center">
		<label for="currency_publish" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="checkbox" name="currency_publish" class="form-check-input" id="currency_publish" value="1" <?php if ($row->currency_publish) echo 'checked="checked"'?> />
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="order_currencies" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_ORDERING'); ?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<?php echo $lists['order_currencies']?>
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="currency_name" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_TITLE');?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="currency_name" name="currency_name" value="<?php echo $row->currency_name;?>" />
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="currency_code" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		    <?php echo JText::_('COM_SMARTSHOP_CODE');?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="currency_code" name="currency_code" value="<?php echo $row->currency_code;?>" />
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="currency_code_iso" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_CODE')." (ISO)";?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="currency_code_iso" name="currency_code_iso" value="<?php echo $row->currency_code_iso;?>" />
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="currency_code_num" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_CODE')." (".JText::_('COM_SMARTSHOP_NUMERIC').")";?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="currency_code_num" name="currency_code_num" value="<?php echo $row->currency_code_num;?>" />
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="currency_value" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_VALUE_CURRENCY');?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="currency_value" name="currency_value" value="<?php echo $row->currency_value;?>" />
		</div>
	</div>  
   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>

</div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="edit" value="<?php echo $edit;?>" />
<?php if ($edit) {?>
  <input type="hidden" name="currency_id" value="<?php echo $row->currency_id?>" />
<?php }?>
<?php print $this->tmp_html_end ?? '' ?>
</form>