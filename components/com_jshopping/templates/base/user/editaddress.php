<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$config_fields = $this->config_fields;
$addressData = $this->address;
$flashSavedData = $this->flashSavedData;
$isClientTypeEqualsTwo = ($this->clientType == 2);

?>

<div id="qc_error" class="display--none"></div>

<div class="editUserAddress shop">
	<h1 class="editUserAddress__page-title"><?php echo JText::_('COM_SMARTSHOP_EDIT_ADDRESS'); ?></h1>

	<form action="<?php echo $this->action; ?>" id="editUserAddressForm" method="post" name="loginForm" onsubmit="return shopUser.validateAddress(this.name)" class="form-horizontal" enctype="multipart/form-data">
        <?php if(!empty($config_fields)): ?>
            <?php foreach($config_fields as $k=>$val): ?>
                <?php if($k == 'title'){ ?>
                    <div class="form-group row">
                        <label for="title" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                            <?php echo JText::_('COM_SMARTSHOP_TITLE'); ?><?php if ($config_fields['title']['require']) : ?>
                                <span>*</span><?php endif; ?>
                        </label>

                        <div class="col-sm-7 col-md-8 col-lg-9">
                            <?php echo $this->select_titles; ?>
                            <div class="title_error text-danger"></div>
                        </div>
                    </div>
                <?php }elseif($k == 'client_type'){ ?>
                    <div class="form-group row">
                        <label for="client_type" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                            <?php echo JText::_('COM_SMARTSHOP_CLIENT_TYPE'); ?><?php if ($config_fields['client_type']['require']) : ?>
                                <span>*</span><?php endif; ?>
                        </label>

                        <div class="col-sm-7 col-md-8 col-lg-9">
                            <?php echo $this->select_client_types; ?>
                            <div class="client_type_error text-danger"></div>
                        </div>
                    </div>
                <?php } elseif($k == 'birthday'){ ?>
                    <div class="form-group row">
                        <label for="birthday" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                            <?php echo JText::_('COM_SMARTSHOP_BIRTHDAY'); ?><?php if ($config_fields['birthday']['require']) : ?>
                                <span>*</span><?php endif; ?>
                        </label>

                        <div class="col-sm-7 col-md-8 col-lg-9">
                            <?php echo JHTML::_('calendar', $flashSavedData['birthday'] ?? $addressData->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19']);?>
                            <div class="birthday_error text-danger"></div>
                        </div>
                    </div>
                <?php }elseif($k == 'country'){ ?>
                    <div class="form-group row">
                        <label for="country" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                            <?php echo JText::_('COM_SMARTSHOP_COUNTRY'); ?><?php if ($config_fields['country']['require']) : ?>
                                <span>*</span><?php endif; ?>
                        </label>

                        <div class="col-sm-7 col-md-8 col-lg-9">
                            <?php echo $this->select_countries; ?>
                            <div class="country_error text-danger"></div>
                        </div>
                    </div>
                <?php }elseif ($k == 'privacy_statement') { ?>
					<div class="form-group row">
						<label for="privacy_statement" class="col-sm-5 col-md-4 col-lg-3">
							<a class="privacy_statement" href="/index.php?option=com_jshopping&controller=content&task=view&page=privacy_statement&tmpl=component" target="_blank"><?php echo JText::_('COM_SMARTSHOP_PRIVACY_POLICY'); ?>
								<?php if ($config_fields['privacy_statement']['require']) : ?><span>*</span><?php endif; ?>
							</a>
						</label>

						<div class="col-sm-7 col-md-8 col-lg-9">
							<label>
								<input type="checkbox" name="privacy_statement" id="privacy_statement" value="1" class="input" /> <?php echo JText::_('COM_SMARTSHOP_PRIVACY_POLICY_NOTICE'); ?>
							</label>
							<div class="privacy_statement_error text-danger"></div>
						</div>
					</div>
			
				<?php }else{ ?>
                    <div class="form-group row">
                        <label for="<?php echo $k; ?>" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                            <?php echo JText::_('COM_SMARTSHOP_'.strtoupper($k)); ?><?php if ($val['require']) : ?>
                                <span>*</span><?php endif; ?>
                        </label>

                        <div class="col-sm-7 col-md-8 col-lg-9">
                            <input type="text" name="<?php echo $k; ?>" id="<?php echo $k; ?>" placeholder="<?php echo JText::_('COM_SMARTSHOP_'.strtoupper($k)); ?>" value="<?php echo $flashSavedData[$k] ?? $addressData->$k; ?>" class="input" />
                            <div class="<?php echo $k; ?>_error text-danger"></div>
                        </div>
                    </div>
                <?php } ?>
            <?php endforeach; ?>
        <?php endif; ?>

		<?php echo $this->_tmpl_editaccount_html ?? ''; ?>

		<div class="form-group row">
			<div class="col-sm-5 col-md-4 col-lg-3 text-danger">
				<span>* </span><?php echo JText::_('COM_SMARTSHOP_REQUIRED_FIELD'); ?>
			</div>

			<div class="col-sm-7 col-md-8 col-lg-9">
				<button type="submit" name="next" class="btn btn-outline-primary d-grid col-md-6 float-end"><?php echo JText::_('COM_SMARTSHOP_SAVE'); ?></button>
			</div>
		</div>

		<input type="hidden" name="editId" value="<?php echo $addressData->address_id; ?>">
		<input type="hidden" name="isCloseTabAfterSave" value="<?php echo $this->isCloseTabAfterSave; ?>">
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>