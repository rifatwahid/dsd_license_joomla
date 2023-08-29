<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$config_fields = $this->config_fields;
?>

<div class="shop shop-edit-account shop">
	<h1 class="shop-edit-account__page-title">
		<?php echo JText::_('COM_SMARTSHOP_EDIT_ACCOUNT'); ?>
	</h1>

	<form action="<?php echo $this->action; ?>" method="post" name="loginForm" onsubmit="return shopUser.validateAccount(this.name)" class="form-horizontal shop-edit-account__form" id="edit-account-form" enctype="multipart/form-data">

		<!-- Block -->
		<?php if ($config_fields['title']['display']) : ?>
			<div class="form-group row">
				<label for="title" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?>
					<?php if ($config_fields['title']['require']) : ?><span>*</span><?php endif; ?>
				</label>
				
				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo $this->select_titles; ?>
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['f_name']['display']) : ?>
			<div class="form-group row">
				<label for="f_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_FIRST_NAME'); ?>
					<?php if ($config_fields['f_name']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="f_name" id="f_name" placeholder="<?php echo JText::_('COM_SMARTSHOP_FIRST_NAME'); ?>" value="<?php echo $this->user->f_name; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['m_name']['display']) : ?>
			<div class="form-group row">
				<label for="m_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_MIDDLE_NAME'); ?>
					<?php if ($config_fields['m_name']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="m_name" id="m_name" placeholder="<?php echo JText::_('COM_SMARTSHOP_MIDDLE_NAME'); ?>" value="<?php echo $this->user->m_name; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['l_name']['display']) : ?>
			<div class="form-group row">
				<label for="l_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_LAST_NAME'); ?>
					<?php if ($config_fields['l_name']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="l_name" id="l_name" placeholder="<?php echo JText::_('COM_SMARTSHOP_LAST_NAME'); ?>" value="<?php echo $this->user->l_name; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['firma_name']['display']) : ?>
			<div class="form-group row">
				<label for="firma_name" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_COMPANY_NAME'); ?>
					<?php if ($config_fields['firma_name']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="firma_name" id="firma_name" placeholder="<?php echo JText::_('COM_SMARTSHOP_COMPANY_NAME'); ?>" value="<?php echo $this->user->firma_name; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['client_type']['display']) : ?>
			<div class="form-group row">
				<label for="client_type" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_CLIENT_TYPE'); ?>
					<?php if ($config_fields['client_type']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo $this->select_client_types; ?>
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['firma_code']['display']) : ?>
			<div class="form-group row <?php if ($config_fields['client_type']['display'] && $this->user->client_type!="2") : ?>display--none<?php endif; ?>" id='tr_field_firma_code'>
				<label for="firma_code" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_COMPANY_CODE'); ?>
					<?php if ($config_fields['firma_code']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="firma_code" id="firma_code" placeholder="<?php echo JText::_('COM_SMARTSHOP_COMPANY_CODE'); ?>" value = "<?php echo $this->user->firma_code; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['tax_number']['display']) : ?>
			<div class="form-group row" <?php if ($config_fields['tax_number']['display'] && $this->clientTypeId != 2) : ?>style="display: none;"<?php endif; ?> id = 'tr_field_tax_number'>
				<label for="tax_number" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_VAT_NR'); ?>
					<?php if ($config_fields['tax_number']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="tax_number" id="tax_number" placeholder="<?php echo JText::_('COM_SMARTSHOP_VAT_NR'); ?>" value="<?php echo $this->user->tax_number; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<div class="form-group row">
			<label for="email" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?>
				<?php if ($config_fields['email']['require']) : ?><span>*</span><?php endif; ?>
			</label>

			<div class="col-sm-7 col-md-8 col-lg-9">
				<input type="text" name="email" id="email" placeholder="<?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?>" value="<?php echo $this->user->email ?>" class="input" />
			</div>
		</div>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['birthday']['display']) : ?>
			<div class="form-group row">
				<label for="birthday" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_BIRTHDAY'); ?>
					<?php if ($config_fields['birthday']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo JHTML::_('calendar', $this->user->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, ['class'=>'input', 'size'=>'25', 'maxlength'=>'19']);?>
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['home']['display']) : ?>
			<div class="form-group row">
				<label for="home" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_HOME'); ?>
					<?php if ($config_fields['home']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="home" id="home" placeholder="<?php echo JText::_('COM_SMARTSHOP_HOME'); ?>" value = "<?php echo $this->user->home; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['apartment']['display']) : ?>
			<div class="form-group row">
				<label for="apartment" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_APARTMENT'); ?>
					<?php if ($config_fields['apartment']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="apartment" id="apartment" placeholder="<?php echo JText::_('COM_SMARTSHOP_APARTMENT'); ?>" value="<?php echo $this->user->apartment ?>" class = "input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->
		
		<!-- Block -->
		<?php if ($config_fields['street']['display']) : ?>
			<div class="form-group row">
				<label for="street" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_STREET'); ?><?php if ($config_fields['street']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="street" id="street" placeholder="<?php echo JText::_('COM_SMARTSHOP_STREET'); ?>" value="<?php echo $this->user->street ?>" class = "input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['street_nr']['display']) : ?>
			<div class="form-group row">
				<label for="street_nr" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_STREET_NR'); ?><?php if ($config_fields['street_nr']['require']) : ?>
					<span>*</span><?php endif; ?>
				</label>
				
				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="street_nr" id="street_nr" placeholder="<?php echo JText::_('COM_SMARTSHOP_STREET_NR'); ?>" value="<?php print $this->user->street_nr?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['zip']['display']) : ?>
			<div class="form-group row">
				<label for="zip" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ZIP'); ?>
					<?php if ($config_fields['zip']['require']) : ?><span>*</span><?php endif; ?>
				</label>
				
				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="zip" id="zip" placeholder="<?php echo JText::_('COM_SMARTSHOP_ZIP'); ?>" value="<?php print $this->user->zip ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['city']['display']) : ?>
			<div class="form-group row">
				<label for="city" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_CITY'); ?>
					<?php if ($config_fields['city']['require']) : ?><span>*</span><?php endif; ?>
				</label>
				
				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="city" id="city" placeholder="<?php echo JText::_('COM_SMARTSHOP_CITY'); ?>" value="<?php echo $this->user->city ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['country']['display']) : ?>
			<div class="form-group row">
				<label for="country" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_COUNTRY'); ?>
					<?php if ($config_fields['country']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo $this->select_countries; ?>
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['state']['display']) : ?>
			<div class="form-group row">
				<label for="state" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_STATE'); ?>
					<?php if ($config_fields['state']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="state" id="state" placeholder="<?php echo JText::_('COM_SMARTSHOP_STATE'); ?>" value="<?php print $this->user->state ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->
		
		<!-- Block -->
		<?php if ($config_fields['phone']['display']) : ?>
			<div class="form-group row">
				<label for="phone" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_PHONE'); ?>
					<?php if ($config_fields['phone']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="tel" name="phone" id="phone" placeholder="<?php echo JText::_('COM_SMARTSHOP_PHONE'); ?>" value="<?php echo $this->user->phone; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['mobil_phone']['display']) : ?>
			<div class="form-group row">
				<label for="mobil_phone" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_PHONE_MOBILE'); ?>
					<?php if ($config_fields['mobil_phone']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="tel" name="mobil_phone" id="mobil_phone" placeholder="<?php echo JText::_('COM_SMARTSHOP_PHONE_MOBILE'); ?>" value="<?php echo $this->user->mobil_phone; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['fax']['display']) : ?>
			<div class="form-group row">
				<label for="fax" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_FAX'); ?>
					<?php if ($config_fields['fax']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="tel" name="fax" id="fax" placeholder="<?php echo JText::_('COM_SMARTSHOP_FAX'); ?>" value="<?php print $this->user->fax ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['ext_field_1']['display']) : ?>
			<div class="form-group row">
				<label for="ext_field_1" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_1'); ?>
					<?php if ($config_fields['ext_field_1']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="ext_field_1" id="ext_field_1" placeholder="<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_1'); ?>" value="<?php echo $this->user->ext_field_1; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['ext_field_2']['display']) : ?>
			<div class="form-group row">
				<label for="ext_field_2" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_2'); ?>
					<?php if ($config_fields['ext_field_2']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="ext_field_2" id="ext_field_2" placeholder="<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_2'); ?>" value="<?php print $this->user->ext_field_2 ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<?php if ($config_fields['ext_field_3']['display']) : ?>
			<div class="form-group row">
				<label for="ext_field_3" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_3'); ?>
					<?php if ($config_fields['ext_field_3']['require']) : ?><span>*</span><?php endif; ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="text" name="ext_field_3" id="ext_field_3" placeholder="<?php echo JText::_('COM_SMARTSHOP_ADDITIONAL_FIELD_3'); ?>" value="<?php echo $this->user->ext_field_3; ?>" class="input" />
				</div>
			</div>
		<?php endif; ?>
		<!-- Block END -->

		<!-- Block -->
		<div class="form-group row">
			<label for="password" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PASSWORD'); ?>
				<?php if ($config_fields['password']['require']) : ?><span>*</span><?php endif; ?>
			</label>

			<div class="col-sm-7 col-md-8 col-lg-9">
				<input type="password" name="password" id="password" placeholder="<?php echo JText::_('COM_SMARTSHOP_PASSWORD'); ?>" class="input" />
			</div>
		</div>
		<!-- Block END -->

		<!-- Block -->
		<div class="form-group row">
			<label for="password_2" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PASSWORD_AGAIN'); ?>
				<?php if ($config_fields['password_2']['require']) : ?><span>*</span><?php endif; ?>
			</label>

			<div class="col-sm-7 col-md-8 col-lg-9">
				<input type="password" name="password_2" id="password_2" placeholder="<?php echo JText::_('COM_SMARTSHOP_PASSWORD_AGAIN'); ?>" class="input" />
			</div>
		</div>
		<!-- Block END -->

		<?php if ($config_fields['privacy_statement']['display']) : ?>
			<div class="form-group row">
				<label for="privacy_statement" class="col-sm-5 col-md-4 col-lg-3">
					<a class="privacy_statement" href="/index.php?option=com_jshopping&controller=content&task=view&page=privacy_statement&tmpl=component" target="_blank"><?php echo JText::_('COM_SMARTSHOP_PRIVACY_POLICY'); ?>
						<?php if ($config_fields['privacy_statement']['require']) : ?><span>*</span><?php endif; ?>
					</a>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<input type="checkbox" name="privacy_statement" id="privacy_statement" value="1" class="input" /> <?php echo JText::_('COM_SMARTSHOP_PRIVACY_POLICY_NOTICE'); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="form-group row">
			<div class="col-sm-5 col-md-4 col-lg-3 text-danger">
				<span>*</span><?php echo JText::_('COM_SMARTSHOP_REQUIRED_FIELD'); ?>
			</div>

			<div class="col-sm-7 col-md-8 col-lg-9">
				<button type="submit" name="next" class="btn btn-outline-primary d-grid col-md-6 float-end">
					<?php echo JText::_('COM_SMARTSHOP_SAVE'); ?>
				</button>
			</div>
		</div>

	</form>
</div> <!-- .shop-edit-account -->