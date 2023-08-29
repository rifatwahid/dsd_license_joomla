<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelJoomlaUsersFront extends jshopBase
{
    public const TABLE_NAME = '#__users';

    public function getByAllowSendEmail(bool $isAllowSendEmail = true): ?array
    {
        $db = \JFactory::getDBO();
        $query = "SELECT * FROM {$db->qn(static::TABLE_NAME)} WHERE `sendEmail` = {$isAllowSendEmail}";
        $db->setQuery($query);
        
        return $db->loadObjectList();
    }

    public function getJoomlaUserInfoByLogin(array $columnsToGet = ['*'], string $login): ?object
    {
        $result = '';

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);

            $query = "SELECT {$stringOfSearchColumns} FROM {$db->qn(static::TABLE_NAME)} WHERE `username` = {$db->escape($login)}";
            $db->setQuery($query);
        
            return $db->loadObject();
        }

        return $result;
    }

    public function getJoomlaUserInfoByEmail(array $columnsToGet = ['*'], string $email): ?object
    {
        $return = null;

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);

            $query = "SELECT {$stringOfSearchColumns} FROM {$db->qn(static::TABLE_NAME)} WHERE `email` = {$db->quote($email)}";
            $db->setQuery($query);
            $result = $db->loadObject();
        
            if (!empty($result)) {
                $return = $result;
            }
        }

        return $return;
    }

    public function getIdByEmailWhereNotEqualUserId(string $email, int $userId): int
    {
        $db = \JFactory::getDBO();
        $query = "SELECT `id` FROM {$db->qn(static::TABLE_NAME)} WHERE `email` = '{$db->escape($email)}' AND `id` != {$db->escape($userId)}";
        $db->setQuery($query);
        $id = intval($db->loadResult()) ?: 0;
        
		return $id;
    }

    public function getIdByUserNameWhereNotEqualUserId(string $userName, int $userId): int
    {
        $db = \JFactory::getDBO();
        $query = "SELECT `id` FROM {$db->qn(static::TABLE_NAME)} WHERE `username` = '{$db->escape($userName)}' AND `id` != {$db->escape($userId)}";
        $db->setQuery($query);
        $id = intval($db->loadResult()) ?: 0;
        
		return $id;
    }

    public function activate($token)
    {
        $config = JFactory::getConfig();
        $userParams = JComponentHelper::getParams('com_users');
        $db = \JFactory::getDBO();

        // Get the user id based on the token.
        $db->setQuery(
            'SELECT '.$db->quoteName('id').' FROM '.$db->quoteName('#__users') .
            ' WHERE '.$db->quoteName('activation').' = '.$db->Quote($token) .
            ' AND '.$db->quoteName('block').' = 1' .
            ' AND '.$db->quoteName('lastvisitDate').' = '.$db->Quote($db->getNullDate())
        );
        $userId = (int) $db->loadResult();
        
        // Check for a valid user id.
        if (!$userId) {
            $this->setError(JText::_('COM_USERS_ACTIVATION_TOKEN_NOT_FOUND'));
            return false;
        }

        // Load the users plugin group.
        JPluginHelper::importPlugin('user');

        // Activate the user.
        $user = JFactory::getUser($userId);
		$app = JFactory::getApplication();
			if (!$app->get('mailonline', 1)){
				\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SENDING_MAIL'),'error');
				return;
			}
        // Admin activation is on and user is verifying their email
        if (($userParams->get('useractivation') == 2) && !$user->getParam('activate', 0))
        {
            $uri = JURI::getInstance();

            // Compile the admin notification mail values.
            $data = $user->getProperties();
            $data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
            $user->set('activation', $data['activation']);
            $data['siteurl']    = JUri::base();
            $base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
            $data['activate'] = $base.JRoute::_('index.php?option=com_jshopping&controller=user&task=activate&token='.$data['activation'], false);
            $data['fromname'] = $config->get('fromname');
            $data['mailfrom'] = $config->get('mailfrom');
            $data['sitename'] = $config->get('sitename');
            $user->setParam('activate', 1);
            $emailSubject    = JText::sprintf(
                'COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT',
                $data['name'],
                $data['sitename']
            );

            $emailBody = JText::sprintf(
                'COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY',
                $data['sitename'],
                $data['name'],
                $data['email'],
                $data['username'],
                $data['siteurl'].'index.php?option=com_jshopping&controller=user&task=activate&token='.$data['activation']
            );

            // get all admin users
            $query = 'SELECT name, email, sendEmail FROM #__users WHERE sendEmail=1';

            $db->setQuery( $query );
            $rows = $db->loadObjectList();

            // Send mail to all superadministrators id
			
            foreach( $rows as $row )
            {				
				$dataForTemplate = array('emailSubject'=>$emailSubject, 'emailBod'=>$emailBody);
				$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
				
                $return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $bodyEmailText, true);

                // Check for an error.
                if ($return !== true) {
                    $this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
                    return false;
                }
            }
        }

        //Admin activation is on and admin is activating the account
        elseif (($userParams->get('useractivation') == 2) && $user->getParam('activate', 0))
        {
            $user->set('activation', '');
            $user->set('block', '0');

            $uri = JURI::getInstance();

            // Compile the user activated notification mail values.
            $data = $user->getProperties();
            $user->setParam('activate', 0);
            $data['fromname'] = $config->get('fromname');
            $data['mailfrom'] = $config->get('mailfrom');
            $data['sitename'] = $config->get('sitename');
            $data['siteurl']    = JUri::base();
            $emailSubject    = JText::sprintf(
                'COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT',
                $data['name'],
                $data['sitename']
            );

            $emailBody = JText::sprintf(
                'COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY',
                $data['name'],
                $data['siteurl'],
                $data['username']
            );

			$dataForTemplate = array('emailSubject'=>$emailSubject, 'emailBod'=>$emailBody);
			$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
            $return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $bodyEmailText, true);

            // Check for an error.
            if ($return !== true) {
                $this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
                return false;
            }
        }
        else
        {
            $user->set('activation', '');
            $user->set('block', '0');
        }

        // Store the user object.
        if (!$user->save()) {
            $this->setError(JText::sprintf('COM_USERS_REGISTRATION_ACTIVATION_SAVE_FAILED', $user->getError()));
            return false;
        }

        return $user;
    }
}