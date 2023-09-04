<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$row=$this->tax;
$additional_taxes=$this->additional_taxes;
?>
<form action="index.php?option=com_jshopping&controller=exttaxes&back_tax_id=<?php print $this->back_tax_id;?>" method="post"name="adminForm" id="adminForm">
	<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
	<div class="jshops_edit taxes_ext_edit">
		<div class="form-group row align-items-center">
			<label for="tax_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_TITLE');?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php print $this->lists['taxes'];?>
			</div>
		</div>  
		<div class="form-group row align-items-center">
			<label for="countries_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_COUNTRY')."*<br/><br/><span style='font-weight:normal'>". JText::_('COM_SMARTSHOP_MULTISELECT_INFO')."</span>"; ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $this->lists['countries'];?>
			</div>
		</div>  
		<div class="form-group row align-items-center">
			<label for="tax" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_TAX');?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<div class="input-group">
					<input type="text" class="inputbox form-control" id="tax" name="tax" value="<?php echo $row->tax;?>" /> %
				</div>
			</div>
		</div>  
		<div class="form-group row align-items-center">
			<label for="firma_tax" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
			   <?php 
				if ($this->config->ext_tax_rule_for==1) 
					echo  JText::_('COM_SMARTSHOP_USER_WITH_TAX_ID_TAX');
				else
					echo  JText::_('COM_SMARTSHOP_FIRMA_TAX');
				?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<div class="input-group">
				<input type="text" class="inputbox form-control" id="firma_tax" name="firma_tax" value="<?php echo $row->firma_tax;?>" /> %
			</div>
			</div>
		</div> 
		<?php foreach ($additional_taxes as $key=>$value){?>
		<div class="form-group row align-items-center">
			<label for="firma_tax" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
			   <?php echo  $value->name;?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<div class="input-group">
				<input type="text" class="inputbox form-control" id="additional_tax_<?php echo $value->id;?>" name="additional_tax_<?php echo $value->id;?>" value="<?php $addtaxname="additional_tax_".$value->id;echo $row->$addtaxname;?>" /> %
			</div>
			</div>
		</div>  
		<?php } ?>
		
	   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
	</div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo isset($row->id) ? $row->id : 0; ?>" />
	<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>