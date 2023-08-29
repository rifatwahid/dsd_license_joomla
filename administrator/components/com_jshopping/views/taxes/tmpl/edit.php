<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$row=$this->tax;
$edit=$this->edit;
?>

<form action="index.php?option=com_jshopping&controller=taxes" method="post"name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<div class="jshops_edit taxes_edit">
	<div class="form-group row align-items-center">
		<label for="tax_name" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">	
			<?php echo  JText::_('COM_SMARTSHOP_TITLE');?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="tax_name" name="tax_name" value="<?php echo $row->tax_name;?>" />
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="tax_value" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">	
			<?php echo  JText::_('COM_SMARTSHOP_VALUE');?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<div class="input-group">
				<input type="text" class="inputbox form-control" id="tax_value" name="tax_value" value="<?php echo $row->tax_value;?>" /> % 
			</div>
		</div>
	</div>  

   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
</div>

<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task')?>" />
<input type="hidden" name="edit" value="<?php echo $edit;?>" />
<?php if ($edit) {?>
<input type="hidden" name="tax_id" value="<?php echo $row->tax_id?>" />
<?php }?>
<?php print $this->tmp_html_end ?? ''?>
</form>
<script type="text/javascript">
Joomla.submitbutton=function(task){
     if (task == 'save' || task == 'apply'){
         var taxValue=shopHelper.getValue('tax_value');
         if (isNaN(taxValue)){
           alert ('<?php echo  JText::_('COM_SMARTSHOP_WRITE_TAX_NO_VALID');?>');
           return 0;
         } else if (taxValue < 0 || taxValue >= 100){
           alert ('<?php echo  JText::_('COM_SMARTSHOP_WRITE_TAX_BIG_LESS');?>');
           return 0;
         }
     }
     Joomla.submitform(task);
 }
</script>