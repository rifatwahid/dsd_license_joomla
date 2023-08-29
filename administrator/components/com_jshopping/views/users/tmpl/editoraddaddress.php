<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$config_fields = $this->config_fields;
$addressData = $this->address;
$user = $this->user;
$flashDataSavedPost = $this->flashDataSavedPost;

?>

<div id="qc_error" class="display--none"></div>

<div class="adminEditUserAddress">

	<form action="/administrator/index.php?option=com_jshopping&controller=users" method="post" name="adminForm" id="adminForm">

		<div class="jshops_edit editoraddaddress_edit">
		<?php if ($config_fields['title']['display']) : ?>
			<div class="form-group row">
				<label for="title" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?><?php if ($config_fields['title']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo $this->select_titles; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['f_name']['display']) : ?>
			<div class="form-group row">
				<label for="f_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_FIRST_NAME'); ?><?php if ($config_fields['f_name']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="f_name" id="f_name" placeholder="<?php echo JText::_('COM_SMARTSHOP_FIRST_NAME'); ?>" value="<?php echo $flashDataSavedPost['f_name'] ?? $addressData->f_name ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['m_name']['display']) : ?>
			<div class="form-group row">
				<label for="m_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_MIDDLE_NAME'); ?><?php if ($config_fields['m_name']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="m_name" id="m_name" placeholder="<?php echo JText::_('COM_SMARTSHOP_MIDDLE_NAME'); ?>" value="<?php echo $flashDataSavedPost['m_name'] ?? $addressData->m_name ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['l_name']['display']) : ?>
			<div class="form-group row">
				<label for="l_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_LAST_NAME'); ?><?php if ($config_fields['l_name']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="l_name" id="l_name" placeholder="<?php echo JText::_('COM_SMARTSHOP_LAST_NAME'); ?>" value="<?php echo $flashDataSavedPost['l_name'] ?? $addressData->l_name ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['firma_name']['display']) : ?>
			<div class="form-group row">
				<label for="firma_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_COMPANY_NAME'); ?><?php if ($config_fields['firma_name']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="firma_name" id="firma_name" placeholder="<?php echo JText::_('COM_SMARTSHOP_COMPANY_NAME'); ?>" value="<?php echo $flashDataSavedPost['firma_name'] ?? $addressData->firma_name ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['client_type']['display']) : ?>
			<div class="form-group row">
				<label for="client_type" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_CLIENT_TYPE'); ?><?php if ($config_fields['client_type']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo $this->select_client_types; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['firma_code']['display']) : ?>
			<div class="form-group row <?php if ($config_fields['client_type']['display'] && $addressData->client_type!="2") : ?>display--none<?php endif; ?>" id = 'tr_field_firma_code'>
				<label for="firma_code" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_COMPANY_CODE'); ?><?php if ($config_fields['firma_code']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>
				
				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="firma_code" id="firma_code" placeholder="<?php echo JText::_('COM_SMARTSHOP_COMPANY_CODE'); ?>" value="<?php echo $flashDataSavedPost['firma_code'] ?? $addressData->firma_code ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['tax_number']['display']) : ?>
			<div class="form-group row" <?php if ($config_fields['tax_number']['display'] && (!isset($addressData->client_type) || $addressData->client_type != 2)) : ?>style="display: none;"<?php endif; ?> id = 'tr_field_tax_number'>
				<label for="tax_number" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_VAT_NR'); ?><?php if ($config_fields['tax_number']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="tax_number" id="tax_number" placeholder="<?php echo JText::_('COM_SMARTSHOP_VAT_NR'); ?>" value="<?php echo $flashDataSavedPost['tax_number'] ?? $addressData->tax_number ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['email']['display']) : ?>
			<div class="form-group row">
				<label for="email" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?><?php if ($config_fields['email']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>
				
				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="email" id="email" placeholder="<?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?>" <?php if(!empty($addressData->is_default)) print 'readonly'; ?> readonly value="<?php echo $user['email'] ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['birthday']['display']) : ?>
			<div class="form-group row">
				<label for="birthday" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_BIRTHDAY'); ?><?php if ($config_fields['birthday']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>
				
				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo JHTML::_('calendar', $flashDataSavedPost['birthday'] ?? $addressData->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19']);?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['home']['display']) : ?>
			<div class="form-group row">
				<label for="home" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_HOME'); ?><?php if ($config_fields['home']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="home" id="home" placeholder="<?php echo JText::_('COM_SMARTSHOP_HOME'); ?>" value="<?php echo $flashDataSavedPost['home'] ?? $addressData->home ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['apartment']['display']) : ?>
			<div class="form-group row">
				<label for="apartment" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_APARTMENT'); ?><?php if ($config_fields['apartment']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>
				
				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="apartment" id="apartment" placeholder="<?php echo JText::_('COM_SMARTSHOP_APARTMENT'); ?>" value="<?php echo $flashDataSavedPost['apartment'] ?? $addressData->apartment ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['street']['display']) : ?>
			<div class="form-group row">
				<label for="street" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_STREET'); ?><?php if ($config_fields['street']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="street" id="street" placeholder="<?php echo JText::_('COM_SMARTSHOP_STREET'); ?>" value="<?php echo $flashDataSavedPost['street'] ?? $addressData->street ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['street_nr']['display']) : ?>
			<div class="form-group row">
				<label for="street_nr" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_STREET_NR'); ?><?php if ($config_fields['street_nr']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="street_nr" id="street_nr" placeholder="<?php echo JText::_('COM_SMARTSHOP_STREET_NR'); ?>" value="<?php echo $flashDataSavedPost['street_nr'] ?? $addressData->street_nr ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['zip']['display']) : ?>
			<div class="form-group row">
				<label for="zip" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ZIP'); ?><?php if ($config_fields['zip']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="zip" id="zip" placeholder="<?php echo JText::_('COM_SMARTSHOP_ZIP'); ?>" value="<?php echo $flashDataSavedPost['zip'] ?? $addressData->zip ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['city']['display']) : ?>
			<div class="form-group row">
				<label for="city" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_CITY'); ?><?php if ($config_fields['city']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="city" id="city" placeholder="<?php echo JText::_('COM_SMARTSHOP_CITY'); ?>" value="<?php echo $flashDataSavedPost['city'] ?? $addressData->city ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['state']['display']) : ?>
			<div class="form-group row">
				<label for="state" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_STATE'); ?><?php if ($config_fields['state']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="state" id="state" placeholder="<?php echo JText::_('COM_SMARTSHOP_STATE'); ?>" value="<?php echo $flashDataSavedPost['state'] ?? $addressData->state ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['country']['display']) : ?>
			<div class="form-group row">
				<label for="country" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_COUNTRY'); ?><?php if ($config_fields['country']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo $this->select_countries; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['phone']['display']) : ?>
			<div class="form-group row">
				<label for="phone" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_PHONE'); ?><?php if ($config_fields['phone']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="tel" name="phone" id="phone" placeholder="<?php echo JText::_('COM_SMARTSHOP_PHONE'); ?>" value="<?php echo $flashDataSavedPost['phone'] ?? $addressData->phone ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['mobil_phone']['display']) : ?>
			<div class="form-group row">
				<label for="mobil_phone" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_PHONE_MOBILE'); ?><?php if ($config_fields['mobil_phone']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="tel" name="mobil_phone" id="mobil_phone" placeholder="<?php echo JText::_('COM_SMARTSHOP_PHONE_MOBILE'); ?>" value="<?php echo $flashDataSavedPost['mobil_phone'] ?? $addressData->mobil_phone ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['fax']['display']) : ?>
			<div class="form-group row">
				<label for="fax" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_FAX'); ?><?php if ($config_fields['fax']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="tel" name="fax" id="fax" placeholder="<?php echo JText::_('COM_SMARTSHOP_FAX'); ?>" value="<?php echo $flashDataSavedPost['fax'] ?? $addressData->fax ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['ext_field_1']['display']) : ?>
			<div class="form-group row">
				<label for="ext_field_1" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_1'); ?><?php if ($config_fields['ext_field_1']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="ext_field_1" id="ext_field_1" placeholder="<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_1'); ?>" value="<?php echo $flashDataSavedPost['ext_field_1'] ?? $addressData->ext_field_1 ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['ext_field_2']['display']) : ?>
			<div class="form-group row">
				<label for="ext_field_2" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_2'); ?><?php if ($config_fields['ext_field_2']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="ext_field_2" id="ext_field_2" placeholder="<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_2'); ?>" value="<?php echo $flashDataSavedPost['ext_field_2'] ?? $addressData->ext_field_2 ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<?php if ($config_fields['ext_field_3']['display']) : ?>
			<div class="form-group row">
				<label for="ext_field_3" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_3'); ?><?php if ($config_fields['ext_field_3']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="ext_field_3" id="ext_field_3" placeholder="<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_3'); ?>" value="<?php echo $flashDataSavedPost['ext_field_3'] ?? $addressData->ext_field_3 ?? ''; ?>" class="input form-control" />
				</div>
			</div>
		<?php endif; ?>

		<div class="form-group row">
			<div class="col-sm-5 col-md-4 col-lg-3 text-danger">
				<span>* </span><?php echo JText::_('COM_SMARTSHOP_REQUIRED_FIELD'); ?>
			</div>
		</div>
		</div>

		<input type="hidden" name="task" value="">
		<?php echo JHtml::_('form.token'); ?>
        <input type="hidden" name="editId" value="<?php echo $addressData->address_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>">
	</form>
</div>