<?php 
/**
* @version      4.9.0 22.10.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$row=$this->order_status; 
$edit=$this->edit; 
$message=$this->message; 
?>
<form action="index.php?option=com_jshopping&controller=orderstatus" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php print $this->tmp_html_start ?? ''?>
	<div class="jshops_edit orderstatus_edit">
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
		<div class="form-group row align-items-center">
			<label for="<?php print 'text_'.$lang->language; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
				<?php echo JText::_('COM_SMARTSHOP_EMAIL_MESSAGE'); ?> <?php if ($multilang) print "(".$lang->lang.")";?>
				<div style = "font-size: 10px;"><?php echo JText::_('COM_SMARTSHOP_ALLOWED_VARS'); ?>: {title}, {first_name}, {middle_name}, {last_name}, {order_number}, {order_status}, {order_detail_url}, {comment}, {company}, {firma_code}, {client_type}, {address}, {home}, {apartment}, {street_nr}, {street}, {zip}, {city}, {country}, {phone}, {mobil_phone}, {fax}, {state}, {tax_number}, {birthday}, {ext_field_1}, {ext_field_2}, {ext_field_3},
				{delivery_title}, {delivery_first_name}, {delivery_middle_name}, {delivery_last_name}, {delivery_company}, {delivery_firma_code}, {delivery_client_type}, {delivery_home}, {delivery_apartment}, {delivery_street_nr}, {delivery_street}, {delivery_zip}, {delivery_city}, {delivery_country}, {delivery_phone}, {delivery_mobil_phone}, {delivery_fax}, {delivery_state}, {delivery_tax_number}, {delivery_birthday}, {delivery_ext_field_1}, {delivery_ext_field_2}, {delivery_ext_field_3}</div>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<?php 
				$editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));
				print $editor->display('text_'.$lang->language, JHtml::_('content.prepare', $message[$lang->language]), '100%', '350', '75', '20'); ?>
			</div>
		</div>  
	   <?php }?>
		<div class="form-group row align-items-center">
			<label for="status_code" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CODE');?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="status_code" name="status_code" value="<?php echo $row->status_code;?>" />
			</div>
		</div>  
		<div class="form-group row align-items-center">
			<label for="color" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_COLOR');?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="color" class="inputbox form-control-color" id="color" name="color" value="<?php echo $row->color;?>" />
			</div>
		</div>  
	   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
	</div>
	 
	<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task')?>" />
	<input type="hidden" name="edit" value="<?php echo $edit;?>" />
	<?php if ($edit) {?>
	  <input type="hidden" name="status_id" value="<?php echo $row->status_id?>" />
	<?php }?>
	<?php print $this->tmp_html_end ?? ''?>
</form>