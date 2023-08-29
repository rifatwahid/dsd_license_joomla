<?php

require_once JPATH_COMPONENT_SITE . '/Services/Registration/AbstractRegistrationService.php';

class QCheckoutRegistrationService extends AbstractRegistrationService
{
    public function validationCheck(array $accountData)
    {
        $isPasswordNotEmpty = (!empty($accountData['password']) && !empty($accountData['password2']));
        $isReadPrivacy = !empty($accountData['qcheckoutReadPrivacy']);
        
        if ($isPasswordNotEmpty && !$isReadPrivacy) {
            throw new \Exception(JText::_('QUICK_CHECKOUT_ACCEPT_PRIVACY_POLICY'), 500);
        }

        if ($isPasswordNotEmpty && !empty($accountData['email'])) {                
            $isEmailAlreadyExists = JSFactory::getModel('UsersFront')->checkUserShopEmailExist($accountData['email']);

            if (!$isEmailAlreadyExists) { 
                $jshopConfig = JSFactory::getConfig();
                $params = JComponentHelper::getParams('com_users');
                $lang = JFactory::getLanguage();
                $lang->load('com_users');
                
                if (0 == $params->get('allowUserRegistration')) {
                    throw new \Exception(JText::_('QUICK_CHECKOUT_ACCESS_FORBIDDEN'), 500);
                }

                if ( empty($accountData['password']) && empty($accountData['password2']) ) {
                    $randomString = generateRandomString();
                    $accountData['password'] = $randomString;
                    $accountData['password2'] = $randomString;                 
                } elseif ($accountData['password'] != $accountData['password2']) {                        
                    throw new \Exception(JText::_('QUICK_CHECKOUT_PASSWORD_DO_NOT_MATCH'), 500);
                }
                
                $modelOfUsersFront = JSFactory::getModel('UsersFront');
                if($modelOfUsersFront->getCapthaPermission()) {							
                    if($modelOfUsersFront->getPluginSettings()){	
                        $language = JFactory::getLanguage();		
                        $language->load('plg_captcha_'.$modelOfUsersFront->getPluginSettings(), JPATH_ROOT.'/administrator');
                        
                        $captcha = new JCaptcha($modelOfUsersFront->getPluginSettings(), []);
                        try{
                            if (!$captcha->checkAnswer(null)){ 
                                
                            }
                        }catch(\RuntimeException $e){ 
                            throw new \Exception($e->getMessage(), 500);
                        }			
                    }
                }

                $savedAccountData = $accountData;
                $accountData['f_name'] = $accountData['f_name'] ?: $accountData['email'];
                $accountData['name'] = $accountData['f_name'] . ' ' . $accountData['l_name'];

                if ($accountData['birthday']) {
                    $accountData['birthday'] = getJsDateDB($accountData['birthday'], $jshopConfig->field_birthday_format);
                }

                $accountData['lang'] = $jshopConfig->getLang();

                $userAddressTable = JSFactory::getTable('UserAddress');
                $userAddressTable->bind($accountData);
                $userAddressTable->check();

                if (!empty($userAddressTable->getErrors())) {
                    $session = JFactory::getSession();
                    $session->set('registrationdata', $savedAccountData);

                    throw new \Exception(implode("\n", $userAddressTable->getErrors()), 500);
                }                                        
            } else {
                throw new \Exception(JText::_('QUICK_CHECKOUT_ACCOUNT_WITH_THIS_EMAIL_EXISTS'), 500);
            }
        }
    }
}