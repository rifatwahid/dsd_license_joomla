<?php 
/**
* @version      4.9.0 10.02.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig=JSFactory::getConfig();
displaySubmenuConfigs('pdf',$this->canDo);

JHtml::_('behavior.formvalidator');
?>

<form class="form-validate" action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<input type="hidden" name="task" value="">
	<input type="hidden" name="tab" value="12">

    <legend><?php echo  JText::_('COM_SMARTSHOP_CONFIGURATION_PDF') ?></legend>
	<div class="striped-block jshops_edit pdf_config ">

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_GENERATE_INVOICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_generate_invoice[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_generate_invoice', 'is_generate_invoice_name', array_keys($this->invoicesAndDeliveryNoteData['generateInvoice'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SEND_INVOICE_TO_CUSTOMER')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_send_invoice_to_customer[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_send_invoice_to_customer', 'is_send_invoice_to_customer_name', array_keys($this->invoicesAndDeliveryNoteData['sendInvoiceToCustomer'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SEND_INVOICE_TO_ADMIN')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_send_invoice_to_admin[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_send_invoice_to_admin', 'is_send_invoice_to_admin_name', array_keys($this->invoicesAndDeliveryNoteData['sendInvoiceToAdmin'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_ADDITIONAL_ADMIN_INVOICE_EMAIL')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="email" class="validate-email form-control" name="additional_admin_invoice_email" value="<?php echo $jshopConfig->additional_admin_invoice_email; ?>">
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_GENERATE_DELIVERY_NOTE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_generate_delivery_note[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_generate_delivery_note', 'is_generate_delivery_note_name', array_keys($this->invoicesAndDeliveryNoteData['generateDeliveryNote'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SEND_DELIVERY_NOTE_TO_CUSTOMER')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_send_delivery_note_to_customer[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_send_delivery_note_to_customer', 'is_send_delivery_note_to_customer_name', array_keys($this->invoicesAndDeliveryNoteData['sendDeliveryNoteToCustomer'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SEND_DELIVERY_NOTE_TO_ADMIN')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_send_delivery_note_to_admin[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_send_delivery_note_to_admin', 'is_send_delivery_note_to_admin_name', array_keys($this->invoicesAndDeliveryNoteData['sendDeliveryNoteToAdmin'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_ADDITIONAL_ADMIN_DELIVERY_NOTE_EMAIL')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="email" class="validate-email form-control" name="additional_admin_delivery_note_email" value="<?php echo $jshopConfig->additional_admin_delivery_note_email; ?>">
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_GENERATE_REFUND_NOTE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_generate_refund_note[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_generate_refund_note', 'is_generate_refund_note_name', array_keys($this->invoicesAndDeliveryNoteData['generateRefundNote'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SEND_REFUND_NOTE_TO_CUSTOMER')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_send_refund_note_to_customer[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_send_refund_note_to_customer', 'is_send_refund_note_to_customer_name', array_keys($this->invoicesAndDeliveryNoteData['sendRefundNoteToCustomer'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SEND_REFUND_NOTE_TO_ADMIN')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('select.genericlist', $this->invoicesAndDeliveryNoteData['allStatuses'], 'is_send_refund_note_to_admin[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'is_send_refund_note_to_admin', 'is_send_refund_note_to_admin_name', array_keys($this->invoicesAndDeliveryNoteData['sendRefundNoteToAdmin'])); ?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_ADDITIONAL_ADMIN_REFUND_EMAIL')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="email" class="validate-email form-control" name="additional_admin_refund_email" value="<?php echo $jshopConfig->additional_admin_refund_email; ?>">
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="date_invoice_in_invoice" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_INVOICE_DATE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="date_invoice_in_invoice" value="0" />
				<input type="checkbox" class="form-check-input" name="date_invoice_in_invoice" id="date_invoice_in_invoice" value="1" <?php if ($jshopConfig->date_invoice_in_invoice) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="weight_in_invoice" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_WEIGHT_IN_INVOICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="weight_in_invoice" value="0" />
				<input type="checkbox" class="form-check-input" name="weight_in_invoice" id="weight_in_invoice" value="1" <?php if ($jshopConfig->weight_in_invoice) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="shipping_in_invoice" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_SHIPPING_IN_INVOICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="shipping_in_invoice" id="shipping_in_invoice" value="0" />
				<input type="checkbox" class="form-check-input" name="shipping_in_invoice" value="1" <?php if ($jshopConfig->shipping_in_invoice) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="payment_in_invoice" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_PAYMENT_IN_INVOICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="payment_in_invoice" value="0" />
				<input type="checkbox" class="form-check-input" name="payment_in_invoice" id="payment_in_invoice" value="1" <?php if ($jshopConfig->payment_in_invoice) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="user_number_in_invoice" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_USER_NUMBER_IN_INVOICE')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="user_number_in_invoice" value="0" />
				<input type="checkbox" class="form-check-input" name="user_number_in_invoice" id="user_number_in_invoice" value="1" <?php if ($jshopConfig->user_number_in_invoice) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="show_return_policy_text_in_pdf" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_SHOW_RETURN_POLICY_IN_PDF')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="show_return_policy_text_in_pdf" value="0" />
				<input type="checkbox" class="form-check-input" name="show_return_policy_text_in_pdf" id="show_return_policy_text_in_pdf" value="1" <?php if ($jshopConfig->show_return_policy_text_in_pdf) echo 'checked="checked"';?> />
			</div>
		</div>
    </div>


    <legend><?php echo JText::_('COM_SMARTSHOP_PDF_CONFIG') ?></legend>
	<div class="striped-block jshops_edit pdf_config_image">
		<div class="form-group row align-items-center">
			<label for="header_img_but" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_PDF_HEADER')?>
				<small class="d-block form-text">
					<?php echo JText::_('COM_SMARTSHOP_PDF_ONLYJPG'); ?>
				</small>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input class='btn btn-primary' id="header_img_but" type="button" onClick="document.querySelector('input[name=header]').click();"  value="<?php print JText::_('COM_SMARTSHOP_SELECT_FILE')?>">
				<label id="header_label" ><?php print JText::_('COM_SMARTSHOP_NONE_SELECTED')?></label>
				<input size="55" type="file" name="header" id="header" value="" class="product_image"   hidden onchange="document.querySelector('#header_label').innerHTML=this.files[0].name;"/>
				<?php if ($this->header_img<>""){?><img class="pdf_hub_img_preview" id="pdfHeaderPreview" src='<?php echo $this->header_img;?>'> <a href="index.php?option=com_jshopping&controller=config&task=pdf&remove=header" class="pdf_hub_img_preview_delete">X</a><?php } ?>							
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="pdf_header_width" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_IMAGE_WIDTH') ?>
				<small class="d-block form-text">
					<?php echo JText::_('COM_SMARTSHOP_PDF_INMM'); ?>
				</small>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input size="55" type="text" class="inputbox form-control" id="pdf_header_width" name="pdf_parameters[pdf_header_width]" value="<?php echo $jshopConfig->pdf_header_width?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="pdf_header_height" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_IMAGE_HEIGHT') ?>
				<small class="d-block form-text">
					<?php echo JText::_('COM_SMARTSHOP_PDF_INMM'); ?>
				</small>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input size="55" type="text" class="inputbox form-control" id="pdf_header_height" name="pdf_parameters[pdf_header_height]" value="<?php echo $jshopConfig->pdf_header_height?>" />
			</div>
		</div>
					
		<div class="form-group row align-items-center">
			<label for="pdf_hub_img_but" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_PDF_FOOTER')?>
				<small class="d-block form-text">
					<?php echo JText::_('COM_SMARTSHOP_PDF_ONLYJPG'); ?>
				</small>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input class='btn btn-primary' id="pdf_hub_img_but" type="button" onClick="document.querySelector('#footer').click();"  value="<?php print JText::_('COM_SMARTSHOP_SELECT_FILE')?>">
				<label id="footer_label" ><?php print JText::_('COM_SMARTSHOP_NONE_SELECTED')?></label>
				<input size="55" type="file" name="footer" id="footer" value="" class="product_image"   hidden onchange="document.querySelector('#footer_label').innerHTML=this.files[0].name;"/>
				<?php if ($this->footer_img<>""){?><img class="pdf_hub_img_preview" src='<?php echo $this->footer_img;?>'> <a href="index.php?option=com_jshopping&controller=config&task=pdf&remove=footer" class="pdf_hub_img_preview_delete">X</a><?php } ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="pdf_footer_width" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_IMAGE_WIDTH')?>
				<small class="d-block form-text">
					<?php echo JText::_('COM_SMARTSHOP_PDF_INMM'); ?>
				</small>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" id="pdf_footer_width" name="pdf_parameters[pdf_footer_width]" value="<?php echo $jshopConfig->pdf_footer_width?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="pdf_footer_height" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_IMAGE_HEIGHT')?>
				<small class="d-block form-text">
					<?php echo JText::_('COM_SMARTSHOP_PDF_INMM'); ?>
				</small>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input size="55" type="text" class="inputbox form-control" id="pdf_footer_height" name="pdf_parameters[pdf_footer_height]" value="<?php echo $jshopConfig->pdf_footer_height?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">	</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print JText::_('COM_SMARTSHOP_PDF_PREVIEW_INFO1');?>
				<a class="btn" target="_blank" href="index.php?option=com_jshopping&controller=config&task=preview_pdf&config_id=<?php echo $jshopConfig->id?>"><?php echo JText::_('COM_SMARTSHOP_PDF_PREVIEW')?></a>
			</div>
		</div>
    </div>
        
</form>