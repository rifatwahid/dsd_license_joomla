<?php 
use Joomla\CMS\Application\ApplicationHelper;
abstract class AbstractRegistrationService
{
    public function create(array $accountData): jshopUserBase
    {
        $params = JComponentHelper::getParams('com_users');
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $dispatcher = \JFactory::getApplication();

        $usergroup = JSFactory::getTable('usergroup', 'jshop');
        $defaultUsergroup = $usergroup->getDefaultUsergroup();

        $accountData['f_name'] = $accountData['f_name'] ?: $accountData['email'];
        $accountData['name'] = $accountData['f_name'] . ' ' . $accountData['l_name'];

        if ($accountData['birthday']) {
            $accountData['birthday'] = getJsDateDB($accountData['birthday'], $jshopConfig->field_birthday_format);
        }

        $accountData['lang'] = $jshopConfig->getLang();
        
        $dispatcher->triggerEvent('onBeforeRegister', [&$accountData, &$defaultUsergroup]);
        
        $shopUser = JSFactory::getTable('userShop', 'jshop');
        $shopUser->usergroup_id = $defaultUsergroup;
        $shopUser->password = $accountData['password'];
        $shopUser->password2 = $accountData['password2'];  
        
        if (empty($accountData['u_name'])) {
            $accountData['u_name'] = $accountData['email'];
            $shopUser->u_name = $accountData['u_name'];
        }

        if (empty($accountData['password'])) {
            $accountData['password'] = substr(md5('up' . time()), 0, 8);
        }

        $data = [
            'groups' => [
                $params->get('new_usertype', 2)
            ],
            'email' => $accountData['email'],
            'password' => $accountData['password'],
            'password2' => $accountData['password2'],
            'name' => "{$accountData['f_name']} {$accountData['l_name']}",
            'username' => $accountData['u_name']
        ];

        $useractivation = $params->get('useractivation');
        $sendpassword = $params->get('sendpassword', 1);

        if ((1 == $useractivation) || (2 == $useractivation)) {
            jimport('joomla.user.helper');
            $data['activation'] = ApplicationHelper::getHash(JUserHelper::genRandomPassword());
            $data['block'] = 1;
        }
        $joomlaUser = new JUser;
        $joomlaUser->bind($data);

        if (!$joomlaUser->save()) {
            saveToLog('error.log', 'Error registration-' . $joomlaUser->getError());
            throw new \Exception($joomlaUser->getError(), 500);
        }
        
        $shopUser->user_id = $joomlaUser->id;      
        $shopUser->number = $shopUser->getNewUserNumber();
        unset($shopUser->password);
        unset($shopUser->password2);
        $isSavedNewShopUser = $db->insertObject($shopUser->getTableName(), $shopUser, $shopUser->getKeyName());

        if (!$isSavedNewShopUser) {
            saveToLog('error.log', $db->getErrorMsg());
            throw new \Exception('Error insert in table ' . $shopUser->getTableName(), 500);
        }

        $accountData['user_id'] = $shopUser->user_id;
        $accountData['is_default'] = 1;
        $userAddressTable = JSFactory::getService('UserAddress')->createOrUpdateIfEmailExists($accountData);

        JSFactory::getModel('RegistrationFront')->sendRegisterMail($joomlaUser, $jshopConfig, $accountData, $sendpassword);
        $dispatcher->triggerEvent('onAfterRegister', array(&$joomlaUser, &$shopUser, &$accountData, &$useractivation, &$userAddressTable));

        return $shopUser;
    }

    abstract function validationCheck(array $accountData);
}