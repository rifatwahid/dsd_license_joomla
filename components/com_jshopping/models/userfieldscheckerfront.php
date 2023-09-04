<?php

class JshoppingModelUserFieldsCheckerFront extends jshopBase
{
    public function check(object $addressData, array $config_fields, string $type, ?string $type2 = null): array
    {
        $post = JFactory::getApplication()->input->post->getArray();
        if($post['delivery_adress'] == 1) {
            $shippingAddressCheckData = $this->getCheckerRules($addressData, $config_fields, $type2);
        }else{
            $shippingAddressCheckData = $this->getCheckerRules($addressData, $config_fields);
        }

        $msgs = [];
        $msg = '';

        foreach($shippingAddressCheckData as $k=>$additionalData) {

            if (($additionalData['itemCode'] == 7 || $additionalData['itemCode'] == 8 || $additionalData['itemCode'] == 31 || $additionalData['itemCode'] == 32) && ($addressData->client_type != 2 || !$config_fields['client_type']['display'])) {
                continue;
            }

            if ($additionalData['isRequire'] && $additionalData['isFieldEmpty']) {
                if($k != 'email' && $k != 'd_email' && $type2 != 'newuseraddress'){
					$msg = $additionalData['errorMsg'];
					$msgs[] = $additionalData['errorMsg'];
				}
            }
        }

        $this->checkUserName($type, $type2, $config_fields, $addressData, $msg, $msgs);
        $this->checkUserPassword($type, $type2, $config_fields, $addressData, $msg, $msgs);
		if ($type2 != 'newuseraddress') {
			$this->checkUserEmail($type, $type2, $config_fields, $addressData, $msg, $msgs);
        }
		
        return [
            'msg' => $msg,
            'msgs' => $msgs
        ];
    }

    public function checkUserName(string $type, ?string $type2 = null, array $config_fields, object $addressData, &$msg, &$msgs)
    {
        if ($type == 'register' || $type2 == 'edituser') {
            if ($config_fields['u_name']['require'] && trim($addressData->u_name) == '') {
                $errorTranslatedTxt = addslashes(JText::_('COM_SMARTSHOP_REGWARN_UNAME'));

                $msg = $errorTranslatedTxt;
                $msgs[] = $errorTranslatedTxt;
            }

			if (!empty($addressData->u_name)) {
                $isIncorrectUserName = (preg_match("#[<>\"'%;()&]#i", $addressData->u_name) || strlen(utf8_decode($addressData->u_name )) < 2);

				if ($isIncorrectUserName) {
                    $errorTranslatedTxt = sprintf(addslashes(JText::_('COM_SMARTSHOP_VALID_AZ09')), addslashes(JText::_('COM_SMARTSHOP_USERNAME')), 2);

                    $msg = $errorTranslatedTxt;
                    $msgs[] = $errorTranslatedTxt;
                }
                
				$xid = JSFactory::getModel('UsersFront')->getIdByUserNameWhereNotEqualUserId($addressData->u_name, (int)$addressData->user_id);

				if(!empty($xid) && $xid != intval($addressData->user_id)) {
                    $errorTranslatedTxt = addslashes(JText::_('COM_SMARTSHOP_REGWARN_INUSE'));

                    $msg = $errorTranslatedTxt;
                    $msgs[] = $errorTranslatedTxt;
				}
			}
        }
    }

    public function checkUserPassword(string $type, ?string $type2 = null, array $config_fields, object $addressData, &$msg, &$msgs)
    {
        if ($type == 'editaccount' || $type == 'register') {
            if ($config_fields['password']['require'] && trim($addressData->password) == '') {
                $errorTranslatedTxt = addslashes(JText::_('COM_SMARTSHOP_REGWARN_PASSWORD'));

                $msg = $errorTranslatedTxt;
                $msgs[] = $errorTranslatedTxt;
            }

            if ((!empty($addressData->password) || !empty($addressData->password2)) && $config_fields['password_2']['display'] && $addressData->password != $addressData->password2) {
                $errorTranslatedTxt = JText::_('COM_SMARTSHOP_REGWARN_PASSWORD_NOT_MATCH');

                $msg = $errorTranslatedTxt;
                $msgs[] = $errorTranslatedTxt;
            }
        }
        
        if ($type2 == 'edituser') {
            if (!empty($addressData->password) && $addressData->password != $addressData->password2) {
                $errorTranslatedTxt = JText::_('COM_SMARTSHOP_REGWARN_PASSWORD_NOT_MATCH');

                $msg = $errorTranslatedTxt;
                $msgs[] = $errorTranslatedTxt;
            }
        }
    }

    public function checkUserEmail(string $type, ?string $type2 = null, array $config_fields, object $addressData, &$msg, &$msgs)
    {
        if (!empty($addressData->email)) {
            $modelOfUsersFront = JSFactory::getModel('UsersFront');
            $xid = $modelOfUsersFront->getIdByEmailWhereNotEqualUserId($addressData->email, (int)$addressData->user_id);

            if ($type2 != 'newuseraddress') {
                $isEmailAlreadyExists = (!empty($xid) && $xid != intval($addressData->id));
                $errorTranslatedTxt = addslashes(JText::_('COM_SMARTSHOP_REGWARN_EMAIL_INUSE'));
            } else {
                $isEmailAlreadyExists = '';
                $errorTranslatedTxt = '';
			}

			if($isEmailAlreadyExists) {
                $msg = $errorTranslatedTxt;
                $msgs[] = $errorTranslatedTxt;
			}
		}
    }

    public function getCheckerRules(object $addressData, array $config_fields, $type = '' )
    {
        $post = JFactory::getApplication()->input->post->getArray();
        if($type == 'checkout') {
            return [
                'title' => [
                    'itemCode' => 1,
                    'isRequire' => $config_fields['title']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_TITLE')),
                    'isFieldEmpty' => !intval($addressData->title)
                ],
                'f_name' => [
                    'itemCode' => 2,
                    'isRequire' => $config_fields['f_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_NAME')),
                    'isFieldEmpty' => (trim($addressData->f_name) == '')
                ],
                'l_name' => [
                    'itemCode' => 3,
                    'isRequire' => $config_fields['l_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_LNAME')),
                    'isFieldEmpty' => (trim($addressData->l_name) == '')
                ],
                'm_name' => [
                    'itemCode' => 4,
                    'isRequire' => $config_fields['m_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_MNAME')),
                    'isFieldEmpty' => (trim($addressData->m_name) == '')
                ],
                'firma_name' => [
                    'itemCode' => 5,
                    'isRequire' => $config_fields['firma_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FIRMA_NAME')),
                    'isFieldEmpty' => (trim($addressData->firma_name) == '')
                ],
                'client_type' => [
                    'itemCode' => 6,
                    'isRequire' => $config_fields['client_type']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_CLIENT_TYPE')),
                    'isFieldEmpty' => (trim($addressData->client_type) == 0)
                ],
                'firma_code' => [
                    'itemCode' => 7,
                    'isRequire' => $config_fields['firma_code']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FIRMA_CODE')),
                    'isFieldEmpty' => (trim($addressData->firma_code) == '')
                ],
                'tax_number' => [
                    'itemCode' => 8,
                    'isRequire' => $config_fields['tax_number']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_TAX_NUMBER')),
                    'isFieldEmpty' => (trim($addressData->tax_number) == '')
                ],
                'email' => [
                    'itemCode' => 9,
                    'isRequire' => ($config_fields['email']['require'] || !empty($addressData->email)),
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_MAIL')),
                    'isFieldEmpty' => (trim($addressData->email) == '' || !JMailHelper::isEmailAddress($addressData->email))
                ],
                'birthday' => [
                    'itemCode' => 10,
                    'isRequire' => $config_fields['birthday']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_BIRTHDAY')),
                    'isFieldEmpty' => (trim($addressData->birthday) == '')
                ],
                'home' => [
                    'itemCode' => 11,
                    'isRequire' => $config_fields['home']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_HOME')),
                    'isFieldEmpty' => (trim($addressData->home) == '')
                ],
                'apartment' => [
                    'itemCode' => 12,
                    'isRequire' => $config_fields['apartment']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_APARTMENT')),
                    'isFieldEmpty' => (trim($addressData->apartment) == '')
                ],
                'street' => [
                    'itemCode' => 13,
                    'isRequire' => $config_fields['street']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STREET')),
                    'isFieldEmpty' => (trim($addressData->street) == '')
                ],
                'street_nr' => [
                    'itemCode' => 14,
                    'isRequire' => $config_fields['street_nr']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STREET_NR')),
                    'isFieldEmpty' => (trim($addressData->street_nr) == '')
                ],
                'zip' => [
                    'itemCode' => 15,
                    'isRequire' => $config_fields['zip']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_ZIP')),
                    'isFieldEmpty' => (trim($addressData->zip) == '')
                ],
                'city' => [
                    'itemCode' => 16,
                    'isRequire' => $config_fields['city']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_CITY')),
                    'isFieldEmpty' => (trim($addressData->city) == '')
                ],
                'state' => [
                    'itemCode' => 17,
                    'isRequire' => $config_fields['state']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STATE')), //region
                    'isFieldEmpty' => (trim($addressData->state) == '')
                ],
                'country' => [
                    'itemCode' => 18,
                    'isRequire' => $config_fields['country']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_COUNTRY')),
                    'isFieldEmpty' => !intval($addressData->country)
                ],
                'phone' => [
                    'itemCode' => 19,
                    'isRequire' => $config_fields['phone']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_PHONE')),
                    'isFieldEmpty' => (trim($addressData->phone) == '')
                ],
                'mobil_phone' => [
                    'itemCode' => 20,
                    'isRequire' => $config_fields['mobil_phone']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_MOBIL_PHONE')),
                    'isFieldEmpty' => (trim($addressData->mobil_phone) == '')
                ],
                'fax' => [
                    'itemCode' => 21,
                    'isRequire' => $config_fields['fax']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FAX')),
                    'isFieldEmpty' => (trim($addressData->fax) == '')
                ],
                'ext_field_1' => [
                    'itemCode' => 22,
                    'isRequire' => $config_fields['ext_field_1']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_1')),
                    'isFieldEmpty' => (trim($addressData->ext_field_1) == '')
                ],
                'ext_field_2' => [
                    'itemCode' => 23,
                    'isRequire' => $config_fields['ext_field_2']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_2')),
                    'isFieldEmpty' => (trim($addressData->ext_field_2) == '')
                ],
                'ext_field_3' => [
                    'itemCode' => 24,
                    'isRequire' => $config_fields['ext_field_3']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_3')),
                    'isFieldEmpty' => (trim($addressData->ext_field_3) == '')
                ],
                'd_title' => [
                    'itemCode' => 25,
                    'isRequire' => $config_fields['title']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_TITLE_DELIVERY')),
                    'isFieldEmpty' => !intval($post['d_title'])
                ],
                'd_f_name' => [
                    'itemCode' => 26,
                    'isRequire' => $config_fields['f_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_NAME_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_f_name']) == '')
                ],
                'd_l_name' => [
                    'itemCode' => 27,
                    'isRequire' => $config_fields['l_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_LNAME_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_l_name']) == '')
                ],
                'd_m_name' => [
                    'itemCode' => 4,
                    'isRequire' => $config_fields['m_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_MNAME_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_m_name']) == '')
                ],
                'd_firma_name' => [
                    'itemCode' => 28,
                    'isRequire' => $config_fields['firma_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FIRMA_NAME_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_firma_name']) == '')
                ],
                'd_client_type' => [
                    'itemCode' => 29,
                    'isRequire' => $config_fields['client_type']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_CLIENT_TYPE_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_client_type']) == 0)
                ],
                'd_firma_code' => [
                    'itemCode' => 30,
                    'isRequire' => $config_fields['firma_code']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FIRMA_CODE_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_firma_code']) == '')
                ],
                'd_tax_number' => [
                    'itemCode' => 31,
                    'isRequire' => $config_fields['tax_number']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_TAX_NUMBER_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_tax_number']) == '')
                ],
                'd_birthday' => [
                    'itemCode' => 32,
                    'isRequire' => $config_fields['birthday']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_BIRTHDAY_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_birthday']) == '')
                ],
                'd_home' => [
                    'itemCode' => 33,
                    'isRequire' => $config_fields['home']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_HOME_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_home']) == '')
                ],
                'd_apartment' => [
                    'itemCode' => 34,
                    'isRequire' => $config_fields['apartment']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_APARTMENT_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_apartment']) == '')
                ],
                'd_street' => [
                    'itemCode' => 35,
                    'isRequire' => $config_fields['street']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STREET_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_street']) == '')
                ],
                'd_street_nr' => [
                    'itemCode' => 36,
                    'isRequire' => $config_fields['street_nr']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STREET_NR_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_street_nr']) == '')
                ],
                'd_zip' => [
                    'itemCode' => 37,
                    'isRequire' => $config_fields['zip']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_ZIP_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_zip']) == '')
                ],
                'd_city' => [
                    'itemCode' => 38,
                    'isRequire' => $config_fields['city']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_CITY_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_city']) == '')
                ],
                'd_state' => [
                    'itemCode' => 39,
                    'isRequire' => $config_fields['state']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STATE_DELIVERY')), //region
                    'isFieldEmpty' => (trim($post['d_state']) == '')
                ],
                'd_country' => [
                    'itemCode' => 40,
                    'isRequire' => $config_fields['country']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_COUNTRY_DELIVERY')),
                    'isFieldEmpty' => !intval($post['d_country'])
                ],
                'd_phone' => [
                    'itemCode' => 41,
                    'isRequire' => $config_fields['phone']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_PHONE_DELIVERY')),
                    'isFieldEmpty' => (trim($post['phone']) == '')
                ],
                'd_mobil_phone' => [
                    'itemCode' => 42,
                    'isRequire' => $config_fields['mobil_phone']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_MOBIL_PHONE_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_mobil_phone']) == '')
                ],
                'd_fax' => [
                    'itemCode' => 43,
                    'isRequire' => $config_fields['fax']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FAX_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_fax']) == '')
                ],
                'd_ext_field_1' => [
                    'itemCode' => 44,
                    'isRequire' => $config_fields['ext_field_1']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_1_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_ext_field_1']) == '')
                ],
                'd_ext_field_2' => [
                    'itemCode' => 45,
                    'isRequire' => $config_fields['ext_field_2']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_2_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_ext_field_2']) == '')
                ],
                'd_ext_field_3' => [
                    'itemCode' => 46,
                    'isRequire' => $config_fields['ext_field_3']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_3_DELIVERY')),
                    'isFieldEmpty' => (trim($post['d_ext_field_3']) == '')
                ],
            ];
        }else{
            return [
                'title' => [
                    'itemCode' => 1,
                    'isRequire' => $config_fields['title']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_TITLE')),
                    'isFieldEmpty' => !intval($addressData->title)
                ],
                'f_name' => [
                    'itemCode' => 2,
                    'isRequire' => $config_fields['f_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_NAME')),
                    'isFieldEmpty' => (trim($addressData->f_name) == '')
                ],
                'l_name' => [
                    'itemCode' => 3,
                    'isRequire' => $config_fields['l_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_LNAME')),
                    'isFieldEmpty' => (trim($addressData->l_name) == '')
                ],
                'm_name' => [
                    'itemCode' => 4,
                    'isRequire' => $config_fields['m_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_MNAME')),
                    'isFieldEmpty' => (trim($addressData->m_name) == '')
                ],
                'firma_name' => [
                    'itemCode' => 5,
                    'isRequire' => $config_fields['firma_name']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FIRMA_NAME')),
                    'isFieldEmpty' => (trim($addressData->firma_name) == '')
                ],
                'client_type' => [
                    'itemCode' => 6,
                    'isRequire' => $config_fields['client_type']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_CLIENT_TYPE')),
                    'isFieldEmpty' => (trim($addressData->client_type) == 0)
                ],
                'firma_code' => [
                    'itemCode' => 7,
                    'isRequire' => $config_fields['firma_code']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FIRMA_CODE')),
                    'isFieldEmpty' => (trim($addressData->firma_code) == '')
                ],
                'tax_number' => [
                    'itemCode' => 8,
                    'isRequire' => $config_fields['tax_number']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_TAX_NUMBER')),
                    'isFieldEmpty' => (trim($addressData->tax_number) == '')
                ],
                'email' => [
                    'itemCode' => 9,
                    'isRequire' => ($config_fields['email']['require'] || !empty($addressData->email)),
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_MAIL')),
                    'isFieldEmpty' => (trim($addressData->email) == '' || !JMailHelper::isEmailAddress($addressData->email))
                ],
                'birthday' => [
                    'itemCode' => 10,
                    'isRequire' => $config_fields['birthday']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_BIRTHDAY')),
                    'isFieldEmpty' => (trim($addressData->birthday) == '')
                ],
                'home' => [
                    'itemCode' => 11,
                    'isRequire' => $config_fields['home']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_HOME')),
                    'isFieldEmpty' => (trim($addressData->home) == '')
                ],
                'apartment' => [
                    'itemCode' => 12,
                    'isRequire' => $config_fields['apartment']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_APARTMENT')),
                    'isFieldEmpty' => (trim($addressData->apartment) == '')
                ],
                'street' => [
                    'itemCode' => 13,
                    'isRequire' => $config_fields['street']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STREET')),
                    'isFieldEmpty' => (trim($addressData->street) == '')
                ],
                'street_nr' => [
                    'itemCode' => 14,
                    'isRequire' => $config_fields['street_nr']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STREET_NR')),
                    'isFieldEmpty' => (trim($addressData->street_nr) == '')
                ],
                'zip' => [
                    'itemCode' => 15,
                    'isRequire' => $config_fields['zip']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_ZIP')),
                    'isFieldEmpty' => (trim($addressData->zip) == '')
                ],
                'city' => [
                    'itemCode' => 16,
                    'isRequire' => $config_fields['city']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_CITY')),
                    'isFieldEmpty' => (trim($addressData->city) == '')
                ],
                'state' => [
                    'itemCode' => 17,
                    'isRequire' => $config_fields['state']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_STATE')), //region
                    'isFieldEmpty' => (trim($addressData->state) == '')
                ],
                'country' => [
                    'itemCode' => 18,
                    'isRequire' => $config_fields['country']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_COUNTRY')),
                    'isFieldEmpty' => !intval($addressData->country)
                ],
                'phone' => [
                    'itemCode' => 19,
                    'isRequire' => $config_fields['phone']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_PHONE')),
                    'isFieldEmpty' => (trim($addressData->phone) == '')
                ],
                'mobil_phone' => [
                    'itemCode' => 20,
                    'isRequire' => $config_fields['mobil_phone']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_MOBIL_PHONE')),
                    'isFieldEmpty' => (trim($addressData->mobil_phone) == '')
                ],
                'fax' => [
                    'itemCode' => 21,
                    'isRequire' => $config_fields['fax']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_FAX')),
                    'isFieldEmpty' => (trim($addressData->fax) == '')
                ],
                'ext_field_1' => [
                    'itemCode' => 22,
                    'isRequire' => $config_fields['ext_field_1']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_1')),
                    'isFieldEmpty' => (trim($addressData->ext_field_1) == '')
                ],
                'ext_field_2' => [
                    'itemCode' => 23,
                    'isRequire' => $config_fields['ext_field_2']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_2')),
                    'isFieldEmpty' => (trim($addressData->ext_field_2) == '')
                ],
                'ext_field_3' => [
                    'itemCode' => 24,
                    'isRequire' => $config_fields['ext_field_3']['require'],
                    'errorMsg' => addslashes(JText::_('COM_SMARTSHOP_REGWARN_EXT_FIELD_3')),
                    'isFieldEmpty' => (trim($addressData->ext_field_3) == '')
                ]
            ];
        }
    }
}