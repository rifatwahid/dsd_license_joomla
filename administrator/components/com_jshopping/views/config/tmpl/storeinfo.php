<?php 
/**
* @version      4.9.0 09.01.2015
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$jshopConfig=JSFactory::getConfig();
JFilterOutput::objectHTMLSafe($jshopConfig, ENT_QUOTES);
$vendor=$this->vendor;
$lists=$this->lists;
displaySubmenuConfigs('storeinfo',$this->canDo);
?>
<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start ?? ''?>
<input type="hidden" name="task" value="">
<input type="hidden" name="tab" value="5">
<input type="hidden" name="vendor_id" value="<?php print $vendor->id;?>">

    <legend><?php echo JText::_('COM_SMARTSHOP_STORE_INFO') ?></legend>
	<div class="striped-block jshops_edit ">
		<div class="form-group row align-items-center">
			<label for="shop_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_STORE_NAME');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="shop_name" id="shop_name" value="<?php echo $vendor->shop_name?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="company_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_STORE_COMPANY');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="company_name" id="company_name" value="<?php echo $vendor->company_name?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="url" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_STORE_URL');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="url" id="url" value="<?php echo $vendor->url?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="logo" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_LOGO');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="logo" id="logo" value="<?php echo $vendor->logo?>" />
			</div>
		</div>   
		<div class="form-group row align-items-center">
			<label for="adress" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_STORE_ADRESS');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="adress" id="adress" value="<?php echo $vendor->adress?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="city" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_STORE_CITY');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="city" id="city" value="<?php echo $vendor->city?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="zip" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_STORE_ZIP');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="zip" id="zip"  value="<?php echo $vendor->zip?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="state" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_STORE_STATE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="state" value="<?php echo $vendor->state?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="countries" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_STORE_COUNTRY');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo $lists['countries'];?>
			</div>
		</div> 
	</div>

    <legend><?php echo JText::_('COM_SMARTSHOP_CONTACT_INFO') ?></legend>
	<div class="striped-block jshops_edit ">
		<div class="form-group row align-items-center">
			<label for="f_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CONTACT_FIRSTNAME');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="f_name" id="f_name" value="<?php echo $vendor->f_name?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="l_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CONTACT_LASTNAME');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="l_name" id="l_name" value="<?php echo $vendor->l_name?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="middlename" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CONTACT_MIDDLENAME');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="middlename" id="middlename" value="<?php echo $vendor->middlename?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="phone" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CONTACT_PHONE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="phone" id="phone" value="<?php echo $vendor->phone?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="fax" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CONTACT_FAX');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="fax" id="fax" value="<?php echo $vendor->fax?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="email" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_EMAIL');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="email" id="email" value="<?php echo $vendor->email?>" />
			</div>
		</div>
	</div>

    <legend><?php echo JText::_('COM_SMARTSHOP_BANK') ?></legend>
	<div class="striped-block jshops_edit ">
		<div class="form-group row align-items-center">
			<label for="benef_bank_info" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_BENEF_BANK_NAME');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="benef_bank_info" id="benef_bank_info" value="<?php echo $vendor->benef_bank_info?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="benef_bic" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_BENEF_BIC');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="benef_bic" id="benef_bic" value="<?php echo $vendor->benef_bic?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="benef_conto" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_BENEF_CONTO');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="benef_conto" id="benef_conto" value="<?php echo $vendor->benef_conto?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="benef_payee" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_BENEF_PAYEE');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="benef_payee" id="benef_payee" value="<?php echo $vendor->benef_payee?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="benef_iban" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_BENEF_IBAN');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="benef_iban" id="benef_iban" value="<?php echo $vendor->benef_iban?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="benef_bic_bic" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_BIC_BIC');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type = "text" class = "inputbox form-control" name = "benef_bic_bic" id = "benef_bic_bic" value = "<?php echo $vendor->benef_bic_bic?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="benef_swift" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_BENEF_SWIFT');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="benef_swift" id="benef_swift" value="<?php echo $vendor->benef_swift?>" />
			</div>
		</div>
	</div>

    <legend><?php echo JText::_('COM_SMARTSHOP_INTERM_BANK') ?></legend>
	<div class="striped-block jshops_edit ">
		<div class="form-group row align-items-center">
			<label for="interm_name" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_INTERM_NAME');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="interm_name" id="interm_name" value="<?php echo $vendor->interm_name?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="interm_swift" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_INTERM_SWIFT');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="interm_swift" id="interm_swift" value="<?php echo $vendor->interm_swift?>" />
			</div>
		</div>
	</div>

	<div class="striped-block jshops_edit ">
		<div class="form-group row align-items-center">
			<label for="identification_number" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_IDENTIFICATION_NUMBER');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="identification_number" id="identification_number" value="<?php echo $vendor->identification_number?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="tax_number" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_TAX_NUMBER');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input size="55" type="text" class="inputbox form-control" name="tax_number" id="tax_number" value="<?php echo $vendor->tax_number?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="additional_information" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_INFORMATION');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<textarea rows="5" cols="55" class="form-control" name="additional_information" id="additional_information"><?php echo $vendor->additional_information?></textarea>
			</div>
		</div>
	</div>

<?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
<?php print $this->tmp_html_end ?? ''?>
</form>