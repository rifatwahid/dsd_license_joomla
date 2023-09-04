<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelRegistrationFront extends jshopBase
{
    public function sendRegisterMail(&$user, &$config, &$post, &$sendpassword)
    {
        $dispatcher = \JFactory::getApplication();		
		$mainframe = JFactory::getApplication();
		$mailfrom = $mainframe->getCfg('mailfrom');
        $fromname = $mainframe->getCfg('fromname');
        $sitename = $mainframe->getCfg('sitename');
        $params = JComponentHelper::getParams('com_users');
        $useractivation = $params->get('useractivation');
        $data = $user->getProperties();		
        $data['fromname'] = $fromname;
        $data['mailfrom'] = $mailfrom;
        $data['sitename'] = $sitename;
        $data['siteurl'] = JUri::base();

        $emailSubject = JText::sprintf(
            'COM_USERS_EMAIL_ACCOUNT_DETAILS',
            $data['name'],
            $data['sitename']
        );

        $uri = JURI::getInstance();
        $base = $uri->toString([
            'scheme', 
            'user', 
            'pass', 
            'host', 
            'port'
        ]);

        $data['activate'] = $base . JRoute::_('index.php?option=com_jshopping&controller=user&task=activate&token=' . $data['activation'], false);
        $passwordClear = '';
        $siteUrl1 = $data['siteurl'] . 'index.php?option=com_jshopping&controller=user&task=activate&token=' . $data['activation'];
        $siteUrl2 = $data['siteurl'];
        $userName = $data['username'];

        if ($useractivation == 2) {
            if ($sendpassword) {
                $title = 'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY'; 
                $passwordClear = $data['password_clear'];
            } else {
                $title = 'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW';
            }
        } else if ($useractivation == 1) {
            if ($sendpassword) {
                $title = 'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY';
                $passwordClear = $data['password_clear'];
            } else {
                $title = 'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW';
            }
        } else {
            if ($sendpassword) {
                $title = 'COM_USERS_EMAIL_REGISTERED_BODY';
                $passwordClear = $data['password_clear'];
            } else {
                $title = 'COM_USERS_EMAIL_REGISTERED_BODY_NOPW';
                $userName = '';
                $siteUrl1 = '';
            }
        }

        $emailBody = JText::sprintf(
            $title,
            $data['name'],
            $data['sitename'],
            $siteUrl1,
            $siteUrl2,
            $userName,
            $passwordClear
        );
		
		$email_str_find     = array("{NAME}", "{SITENAME}", "{ACTIVATE}", "{SITEURL}", "{USERNAME}", "{PASSWORD}");
        $email_str_replace  = array($data['name'], $data['sitename'], $siteUrl1, $siteUrl2, $userName, $passwordClear);
        $emailBody    = str_replace( $email_str_find, $email_str_replace, $emailBody );
        $emailSubject = str_replace( $email_str_find, $email_str_replace, $emailSubject );

        $dispatcher->triggerEvent('onBeforeRegisterSendMailClient', [&$post, &$data, &$emailSubject, &$emailBody]);
        
		$dataForTemplate = array('emailSubject'=>$emailSubject, 'emailBod'=>$emailBody);
		$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
		
		$app = JFactory::getApplication();
		if (!$app->get('mailonline', 1)){
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SENDING_MAIL'),'error');
			return;
		}
        $mailer = JFactory::getMailer();
        $mailer->setSender([$data['mailfrom'], $data['fromname']]);
        $mailer->addRecipient($data['email']);
        $mailer->setSubject($emailSubject);
        $mailer->setBody($bodyEmailText);
        $mailer->isHTML(true);

        $dispatcher->triggerEvent('onBeforeRegisterMailerSendMailClient', [&$mailer, &$post, &$data, &$emailSubject, &$emailBody]);
        $mailer->Send();
        
        if (( 2 > $params->get('useractivation')) && (1 == $params->get('mail_to_admin'))) {
            $emailSubject = JText::sprintf(
                'COM_USERS_EMAIL_ACCOUNT_DETAILS',
                $data['name'],
                $data['sitename']
            );
			
			$emailSubject    = str_replace( $email_str_find, $email_str_replace, $emailSubject );

            $emailBodyAdmin = JText::sprintf(
                'COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY',
                $data['name'],
                $data['username'],
                $data['siteurl']
            );
			
			$emailBodyAdmin    = str_replace( $email_str_find, $email_str_replace, $emailBodyAdmin );
            
            $rows = JSFactory::getModel('UsersFront')->getByAllowSendEmail();            
            $mode = true;
            foreach ($rows as $rowadm) {
                $dispatcher->triggerEvent('onBeforeRegisterSendMailAdmin', [&$post, &$data, &$emailSubject, &$emailBodyAdmin, &$rowadm, &$mode]);
				
				$dataForTemplate = array('emailSubject'=>$emailSubject, 'emailBod'=>$emailBodyAdmin);
				$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
                JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $rowadm->email, $emailSubject, $bodyEmailText, $mode);
            }
        } 
    }

    public function registerNewAccount(array &$post, &$config, $urlToRedirectErrors = 'index.php?option=com_jshopping&controller=user&task=register')
    {
        $params = JComponentHelper::getParams('com_users');
        $jshopConfig = $config;
        $db = \JFactory::getDBO();
        $dispatcher = \JFactory::getApplication();
        $ajax = JFactory::getApplication()->input->getInt('ajax');

        $usergroup = JSFactory::getTable('usergroup', 'jshop');
        $defaultUsergroup = $usergroup->getDefaultUsergroup();
        $post['f_name'] = $post['f_name'] ?: $post['email'];
        $post['name'] = $post['f_name'] . ' ' . $post['l_name'];

        if ($post['birthday']) {
            $post['birthday'] = getJsDateDB($post['birthday'], $jshopConfig->field_birthday_format);
        }

        $post['lang'] = $jshopConfig->getLang();
        
		$this->checkCaptcha($post, $defaultUsergroup);
        $dispatcher->triggerEvent('onBeforeRegister', [&$post, &$defaultUsergroup]);
        
        $shopUser = JSFactory::getTable('userShop', 'jshop');
        $shopUser->usergroup_id = $defaultUsergroup;
        $shopUser->password = $post['password'];
        $shopUser->password2 = $post['password2'];  
        
        $userAddressTable = JSFactory::getTable('UserAddress');
        $userAddressTable->bind($post);
        $userAddressTable->check();

        if (!empty($userAddressTable->getErrors())) {
            $session = JFactory::getSession();
            $registrationdata = JFactory::getApplication()->input->post->getArray();
            $session->set('registrationdata', $registrationdata);
            if($ajax) {
                $data['status'] = 0;
                $data['message'] = $userAddressTable->getErrors();
                $data['redirect'] = '';
                print_r(json_encode($data));
                die;
            }
            return redirectMsgsWithOneTypeStatus($userAddressTable->getErrors(), $urlToRedirectErrors . '&lrd=1', 'error');
        }

        if (empty($post['u_name'])) {
            $post['u_name'] = $post['email'];
            $shopUser->u_name = $post['u_name'];
        }

        if (empty($post['password'])) {
            $post['password'] = substr(md5('up' . time()), 0, 8);
        }

        $data = [
            'groups' => [
                $params->get('new_usertype', 2)
            ],
            'email' => $post['email'],
            'password' => $post['password'],
            'password2' => $post['password2'],
            'name' => "{$post['f_name']} {$post['l_name']}",
            'username' => $post['u_name']
        ];

        $useractivation = $params->get('useractivation');
        $sendpassword = $params->get('sendpassword', 1);

        if ((1 == $useractivation) || (2 == $useractivation)) {
            jimport('joomla.user.helper');
            $data['activation'] = JApplicationHelper::getHash(JUserHelper::genRandomPassword());
            $data['block'] = 1;
        }
        $joomlaUser = new JUser;
        $joomlaUser->bind($data);

        if (!$joomlaUser->save()) {
            saveToLog('error.log', 'Error registration-' . $joomlaUser->getError());

            if($ajax){
                $data['status'] = 0;
                $data['message'] = $joomlaUser->getError();
                $data['redirect'] = '';
                print_r(json_encode($data));die;
            }
            return raiseWarningRedirect($joomlaUser->getError(), $urlToRedirectErrors);
        }
        
        $shopUser->user_id = $joomlaUser->id;      
        $shopUser->number = $shopUser->getNewUserNumber();
        unset($shopUser->password);
        unset($shopUser->password2);

        if (!$db->insertObject($shopUser->getTableName(), $shopUser, $shopUser->getKeyName())) {
            saveToLog('error.log', $db->getErrorMsg());
            if($post['ajax']){
                $data['status'] = 0;
                $data['message'] = 'Error insert in table ' . $shopUser->getTableName();
                $data['redirect'] = '';
                print_r(json_encode($data));die;
            }
            return raiseWarningRedirect('Error insert in table ' . $shopUser->getTableName(), $urlToRedirectErrors); 
        }

        $userAddressTable->user_id = $shopUser->user_id;
        $userAddressTable->is_default = 1;
        $userAddressTable->store();

        $this->sendRegisterMail($joomlaUser, $config, $post, $sendpassword);
        $dispatcher->triggerEvent('onAfterRegister', array(&$joomlaUser, &$shopUser, &$post, &$useractivation, &$userAddressTable));

        return $shopUser;
    }
	
	public function checkCaptcha(&$post, &$default_usergroup) {
		$plugin = JComponentHelper::getParams('com_users')->get('captcha', \JFactory::getConfig()->get('captcha'));
		
		if ($plugin === 0 || $plugin === '0' || $plugin === '' || $plugin === null) return;
		if (\JFactory::getApplication()->input->getVar('controller') != 'user') return;
		
		$jshopConfig = \JSFactory::getConfig();
		$app = \JFactory::getApplication();
		$language = \JFactory::getLanguage();		
        $language->load('plg_captcha_'.$plugin, JPATH_ROOT.'/administrator');
		
		$captcha = new JCaptcha($plugin, array());
		try{
			if (!$captcha->checkAnswer(null)){
				$error = $captcha->getError();
				
				if ($error instanceof Exception) {
					\JFactory::getApplication()->enqueueMessage($error, 'error');
				} else {
					\JFactory::getApplication()->enqueueMessage($error, 'error');
				}
				$this->savePostData($post);
				$app->redirect(SEFLink("index.php?option=com_jshopping&controller=user&task=register&lrd=1",1,1, $jshopConfig->use_ssl));
			}
		}catch(\RuntimeException $e){
			\JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			//\JSError::raiseWarning('', $e->getMessage());
			$this->savePostData($post);
			$app->redirect(SEFLink("index.php?option=com_jshopping&controller=user&task=register&lrd=1",1,1, $jshopConfig->use_ssl));
		}
	}
	
	private function savePostData($data){
		$session = \JFactory::getSession();            
        $session->set('registrationdata', $data);
	}

}