<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');

$flashOrderData = $this->flashOrderData;
?>
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
        <?php } elseif($k == 'client_type'){ ?>
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
                    <?php echo JHTML::_('calendar', $flashOrderData['birthday'] ?? $this->user->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19']);?>
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
		
		<?php }elseif ($k == 'phone') { ?>
             <div class="form-group row">
                <label for="<?php echo $k; ?>" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_'.$k); ?><?php if ($config_fields[$k]['require']) : ?>
                        <span>*</span><?php endif; ?>
                </label>

                <div class="col-sm-7 col-md-8 col-lg-9">
                    <input type="text" name="<?php echo $k; ?>" id="<?php echo $k; ?>" placeholder="+000000000000" value="<?php echo $flashOrderData[$k] ?? $this->user->$k; ?>" class="input" />
                    <div class="<?php echo $k; ?>_error text-danger"></div>
                </div>
            </div>

		<?php }else{ ?>
            <div class="form-group row">
                <label for="<?php echo $k; ?>" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_'.$k); ?><?php if ($config_fields[$k]['require']) : ?>
                        <span>*</span><?php endif; ?>
                </label>

                <div class="col-sm-7 col-md-8 col-lg-9">
                    <input type="text" name="<?php echo $k; ?>" id="<?php echo $k; ?>" placeholder="<?php echo JText::_('COM_SMARTSHOP_'.$k); ?>" value="<?php echo $flashOrderData[$k] ?? $this->user->$k; ?>" class="input" />
                    <div class="<?php echo $k; ?>_error text-danger"></div>
                </div>
            </div>
        <?php } ?>
    <?php endforeach; ?>
<?php endif; ?>


<div class="form-group row">
	<label for="email" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
		<?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?><?php if (isset($config_fields['email']['require']) && $config_fields['email']['require']) : ?>
		<span>*</span><?php endif; ?>
	</label>

	<div class="col-sm-7 col-md-8 col-lg-9">
		<input type="text" name="email" id="email" placeholder="<?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?>" value="<?php echo $flashOrderData['email'] ?? $this->user->email; ?>" class="input" />
		<div class="email_error text-danger"></div>
	</div>
</div>


    <div class="form-group row pb-3 pt-3">
        <div class="col-sm-5 col-md-4 col-lg-3">
            <?php echo JText::_('COM_SMARTSHOP_DELIVERY_ADDRESS_DIFFERENT'); ?>
        </div>
        <div class="col-sm-7 col-md-8 col-lg-9">
            <div class="form-check d-inline-block">
                <input class="form-check-input" type="radio" name="delivery_adress" id="delivery_adress_1" value="0" <?php if (!$this->user->delivery_adress) {?> checked = "checked" <?php } ?> onclick = "shopHelper.hide(document.querySelector('#shop-delivery-address'))">
                <label class="form-check-label" for="delivery_adress_1"><?php echo JText::_('COM_SMARTSHOP_NO'); ?></label>
            </div>
            <div class="form-check d-inline-block">
                <input class="form-check-input" type="radio" name="delivery_adress" id="delivery_adress_2" value="1" <?php if ($this->user->delivery_adress) {?> checked = "checked" <?php } ?> onclick = "shopHelper.show(document.querySelector('#shop-delivery-address'))">
                <label class="form-check-label" for="delivery_adress_2"><?php echo JText::_('COM_SMARTSHOP_YES'); ?></label>
            </div>
        </div>
    </div>

    <div id = "shop-delivery-address" style = "<?php if (!$this->user->delivery_adress){ ?>display:none<?php } ?>" >

        <?php if(!empty($config_dfields)): ?>
    <?php foreach($config_dfields as $k=>$val): ?>
        <?php if($k == 'title'){ ?>
            <div class="form-group row">
                <label for="d_title" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_TITLE'); ?><?php if ($config_dfields['title']['require']) : ?>
                        <span>*</span><?php endif; ?>
                </label>

                <div class="col-sm-7 col-md-8 col-lg-9">
                    <?php echo $this->select_d_titles; ?>
                    <div class="d_title_error text-danger"></div>
                </div>
            </div>
        <?php } elseif($k == 'client_type'){ ?>
            <div class="form-group row">
                <label for="d_client_type" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_CLIENT_TYPE'); ?><?php if ($config_dfields['client_type']['require']) : ?>
                        <span>*</span><?php endif; ?>
                </label>

                <div class="col-sm-7 col-md-8 col-lg-9">
                    <?php echo $this->select_d_client_types; ?>
                    <div class="d_client_type_error text-danger"></div>
                </div>
            </div>
        <?php } elseif($k == 'birthday'){ ?>
            <div class="form-group row">
                <label for="birthday" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_BIRTHDAY'); ?><?php if ($config_dfields['birthday']['require']) : ?>
                        <span>*</span><?php endif; ?>
                </label>

                <div class="col-sm-7 col-md-8 col-lg-9">
                    <?php echo JHTML::_('calendar', $flashOrderData['birthday'] ?? $this->user->d_birthday, 'd_birthday', 'd_birthday', $this->config->field_birthday_format, ['class' => 'input', 'size' => '25', 'maxlength' => '19']);?>
                </div>
            </div>
        <?php }elseif($k == 'country'){ ?>
            <div class="form-group row">
                <label for="d_country" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_COUNTRY'); ?><?php if ($config_dfields['country']['require']) : ?>
                        <span>*</span><?php endif; ?>
                </label>

                <div class="col-sm-7 col-md-8 col-lg-9">
                    <?php echo $this->select_d_countries; ?>
                    <div class="d_country_error text-danger"></div>
                </div>
            </div>
        <?php }elseif ($k == 'phone') { ?>
                    <div class="form-group row">
                        <label for="d_<?php echo $k; ?>" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                            <?php echo JText::_('COM_SMARTSHOP_'.$k); ?><?php if ($config_dfields[$k]['require']) : ?>
                                <span>*</span><?php endif; ?>
                        </label>

                        <div class="col-sm-7 col-md-8 col-lg-9">
                            <input type="text" name="d_<?php echo $k; ?>" id="d_<?php echo $k; ?>" placeholder="+000000000000" value="<?php echo $flashOrderData[$k] ?? $this->user->$k; ?>" class="input" />
                            <div class="d_<?php echo $k; ?>_error text-danger"></div>
                        </div>
                    </div>
        <?php }else{ ?>
            <div class="form-group row">
                <label for="d_<?php echo $k; ?>" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_'.$k); ?><?php if ($config_dfields[$k]['require']) : ?>
                        <span>*</span><?php endif; ?>
                </label>

                <div class="col-sm-7 col-md-8 col-lg-9">
                    <input type="text" name="d_<?php echo $k; ?>" id="d_<?php echo $k; ?>" placeholder="<?php echo JText::_('COM_SMARTSHOP_'.$k); ?>" value="<?php echo $flashOrderData[$k] ?? $this->user->$k; ?>" class="input" />
                    <div class="d_<?php echo $k; ?>_error text-danger"></div>
                </div>
            </div>
        <?php } ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<div class="form-group row">
	<div class="col-sm-5 col-md-4 col-lg-3 text-danger">
		<span>* </span><?php echo JText::_('COM_SMARTSHOP_REQUIRED_FIELD'); ?>
	</div>
</div>

<?php if ( $this->allowUserRegistration && $this->jshopConfig->show_create_account_block && $this->currentUser->guest ) : ?>
	<h4 class="mt-4 pb-2 font-weight-normal"><?php echo JText::_('COM_SMARTSHOP_CREATE_ACCOUNT'); ?></h4>
	<p><?php echo JText::_('COM_SMARTSHOP_CREATE_ACCOUNT_TEXT'); ?></p>

	<div id="qcheckout__create-account">
		<div class="form-group row">
			<label for="password" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PASSWORD'); ?>
			</label>

			<div class="col-sm-7 col-md-8 col-lg-9">
				<input type="password" name="password" id="password" autocomplete="new-password" placeholder="<?php echo JText::_('COM_SMARTSHOP_PASSWORD'); ?>" class="input" />
				<div class="password_error text-danger"></div>
			</div>
		</div>

		<div class="form-group row">
			<label for="password2" class="col-sm-5 col-md-4 col-lg-3 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PASSWORD_AGAIN'); ?>
			</label>

			<div class="col-sm-7 col-md-8 col-lg-9">
				<input type="password" name="password2" id="password2" autocomplete="new-password" placeholder="<?php echo JText::_('COM_SMARTSHOP_PASSWORD_AGAIN'); ?>" class="input" />
				<div class="password2_error text-danger"></div>
			</div>
		</div>
		
		<?php if($this->captcha){ ?>
			<div class="form-group row">
				<label for="captcha" class="col-sm-5 col-md-4 col-lg-3">
					<?php echo JText::_('COM_SMARTSHOP_CAPTCHA_LABEL'); ?>
				</label>

				<div class="col-sm-7 col-md-8 col-lg-9">
					<?php echo $this->captcha->display('jshopping_captcha', 'jshopping_captcha', 'jshopping_captcha'); ?>
				</div>
			</div>
		<?php } ?>
		
		<div class="form-group row">
			<label for="qcheckoutReadPrivacy" class="col-sm-5 col-md-4 col-lg-3">
				<a href="/index.php?option=com_jshopping&controller=content&task=view&page=privacy_statement&tmpl=component" target="_blank"><?php echo JText::_('COM_SMARTSHOP_PRIVACY_POLICY'); ?></a>
			</label>

			<div class="col-sm-7 col-md-8 col-lg-9">
				<input type="checkbox" name="qcheckoutReadPrivacy" id="qcheckoutReadPrivacy" class="input" />

				<label for="qcheckoutReadPrivacy">
					<?php echo JText::_('COM_SMARTSHOP_PRIVACY_POLICY_CREATE_ACCOUNT'); ?>
				</label>
			</div>
		</div>
	</div>
<?php endif; ?>