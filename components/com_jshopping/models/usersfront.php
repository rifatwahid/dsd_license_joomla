<?php 

defined('_JEXEC') or die('Restricted access');

class JshoppingModelUsersFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_users';

    public function getAll(): array
    {
        $db = \JFactory::getDBO();
        $querySelectAll = "SELECT * FROM {$db->qn(static::TABLE_NAME)}";
        $db->setQuery($querySelectAll);

        return $db->loadObjectList() ?: [];
    }

    public function generateClientTypesSelectMarkup(?int $selectedClientType = 0)
    {
        $jshopConfig = JSFactory::getConfig();

        $clientTypes = [];

        if (!empty($jshopConfig->user_field_client_type)) {
            foreach ($jshopConfig->user_field_client_type as $key => $value) {
                $clientTypes[] = JHTML::_('select.option', $key, JText::_($value), 'id', 'name');
            }
        }
        
        return JHTML::_('select.genericlist', $clientTypes, 'client_type','class = "inputbox form-select" onchange="shopHelper.toggleFirm(this.value)"', 'id', 'name', $selectedClientType);
    }

    public function generateClientTypesSelectDMarkup(?int $selectedClientType = 0)
    {
        $jshopConfig = JSFactory::getConfig();

        $clientTypes = [];

        if (!empty($jshopConfig->user_field_client_type)) {
            foreach ($jshopConfig->user_field_client_type as $key => $value) {
                $clientTypes[] = JHTML::_('select.option', $key, JText::_($value), 'id', 'name');
            }
        }

        return JHTML::_('select.genericlist', $clientTypes, 'd_client_type','class = "inputbox form-select" onchange="shopHelper.toggleDFirm(this.value)"', 'id', 'name', $selectedClientType);
    }

    public function generateClientTitlesSelectMarkup(?int $selectedClientTitle = 0, ?int $selectedClientDTitle = 0, string $attrIdClientTitle = 'title', string $attrDIdClientTitle = 'd_title', string $attrForClientTitle = '', string $attrForDClientTitle = '')
    {
        $jshopConfig = JSFactory::getConfig();
        $result = new stdClass();
        $result->selectTitles = '';
        $result->selectDTitles = '';

        if (!empty($jshopConfig->user_field_title)) {
            foreach ($jshopConfig->user_field_title as $key => $value) {
                $option_title[] = JHTML::_('select.option', $key, JText::_($value), 'title_id', 'title_name');
            }
    
            $selectTitles = JHTML::_('select.genericlist', $option_title, $attrIdClientTitle, 'class = "inputbox form-select" ' . $attrForClientTitle,'title_id', 'title_name', $selectedClientTitle);            
            $selectDTitles = JHTML::_('select.genericlist', $option_title, $attrDIdClientTitle, 'class = "inputbox form-select" ' . $attrForDClientTitle,'title_id', 'title_name', $selectedClientDTitle);

            $result->selectTitles = $selectTitles;
            $result->selectDTitles = $selectDTitles;
        }

        return $result;
    }

    public function generateClientTypesOptionsMarkup()
    {
        $jshopConfig = JSFactory::getConfig();
        $clientTypes = '';

        if (!empty($jshopConfig->user_field_client_type)) {
            foreach ($jshopConfig->user_field_client_type as $key => $value) {
                $clientTypes .= '<option value="'.$key.'">'.JText::_($value).'</option>';
            }
        }
        return $clientTypes;
   }

    public function generateClientTitlesOptionsMarkup()
    {
        $jshopConfig = JSFactory::getConfig();
        $option_title = '';

        if (!empty($jshopConfig->user_field_title)) {
            foreach ($jshopConfig->user_field_title as $key => $value) {
               $option_title .= '<option value="'.$key.'">'.JText::_($value).'</option>';
            }
        }

        return $option_title;
    }

    public function checkUserLoginOrEmailExist(?string $userName, ?string $email)
    {
        $msgs = [];
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeUserCheck_user_exist_ajax', [&$msgs, &$userName, &$email]);

        if (!empty($userName)) {
            $userInfoByUserName = $this->getJoomlaUserInfoByEmail(['id'], $userName);

            if (!empty($userInfoByUserName->id)) {
                $msgs[] = JText::sprintf('COM_SMARTSHOP_USER_EXIST', $userName);
            }
        }

        if (!empty($email)) {
            $userInfoByEmail = $this->getJoomlaUserInfoByEmail(['id'], $email);

            if (!empty($userInfoByEmail->id)) {
                $msgs[] = JText::sprintf('COM_SMARTSHOP_USER_EXIST_EMAIL', $email);
            }
        }

        $dispatcher->triggerEvent('onAfterUserCheck_user_exist_ajax', [&$msgs, &$userName, &$email]);

        /*if (empty($msgs)) {
			if($this->getPluginSettings()){	
				$language = JFactory::getLanguage();		
				$language->load('plg_captcha_'.$this->getPluginSettings(), JPATH_ROOT.'/administrator');
				
				$captcha = new JCaptcha($this->getPluginSettings(), array());
				try{
					if (!$captcha->checkAnswer(null)){ 
						return true;
					}
				}catch(\RuntimeException $e){ 
					$msgs[] = $e->getMessage();
				}			
			}
		}*/
		
		if (empty($msgs)) {
		
			return true;
		}

        return $msgs;
    }

    public function isExistUserId(int $userId): bool
    {
        $userData = $this->select(['user_id'], ["`user_id` = '{$userId}'"], '', false);
        return !empty($userData->user_id);
    }

    public function isExistEmail(string $email): bool
    {
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        return $modelOfUserAddressesFront->isEmailExists($email, true);
    }

    public function addNewUser(int $userGroupId, string $name, string $email, int $userId, string $fName, int $number): bool
    {
		$name = $name ?? '';
		$fName = $fName ?? '';
        $isUserAddedSuccess = false;
        $db = \JFactory::getDBO();
        $columnsNames = [
            'usergroup_id',
            'u_name',
            'user_id',
            'number',
			'payment_id',
			'shipping_id',
			'sender_address',
			'credit_limit',
			'open_amount',
			'lang'
        ];

        $columnsVals = [
            $userGroupId,
            $db->quote($name),
            $userId,
            $number,
			0,
			0,
			0,
			0,
			0,
            $db->quote('')
        ];

        $isInserted = $this->insert($columnsNames, $columnsVals);

        if ($isInserted) {
            $tableOfUserAddress = JSFactory::getTable('UserAddress');
            $tableOfUserAddress->bind([
                'f_name' => $fName,
                'email'=> $email,
                'is_default' => 1,
                'user_id' => $userId
            ]);
            $isUserAddedSuccess = $tableOfUserAddress->store();
        }

        return $isUserAddedSuccess;
    }

    public function getCountryId(int $userId): int
    {
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        $defaultData = $modelOfUserAddressesFront->getDataOfDefaultAddress($userId);

        return $defaultData->country ?: 0;
    }


    public function getByAllowSendEmail(bool $isAllowSendEmail = true): ?array
    {
        $modelOfJoomlaUserFront = JSFactory::getModel('JoomlaUsersFront');
        return $modelOfJoomlaUserFront->getByAllowSendEmail($isAllowSendEmail);
    }

    public function getJoomlaUserInfoByLogin(array $columnsToGet = ['*'], string $login = ''): ?object
    {
        $modelOfJoomlaUserFront = JSFactory::getModel('JoomlaUsersFront');
        return $modelOfJoomlaUserFront->getJoomlaUserInfoByLogin($columnsToGet, $login);
    }

    public function getJoomlaUserInfoByEmail(array $columnsToGet = ['*'], string $email = ''): ?object
    {
        $modelOfJoomlaUserFront = JSFactory::getModel('JoomlaUsersFront');
        return $modelOfJoomlaUserFront->getJoomlaUserInfoByEmail($columnsToGet, $email);
    }

    public function getIdByEmailWhereNotEqualUserId(string $email, int $userId): int
    {
        $modelOfJoomlaUserFront = JSFactory::getModel('JoomlaUsersFront');
        return $modelOfJoomlaUserFront->getIdByEmailWhereNotEqualUserId($email, $userId);
    }

    public function getIdByUserNameWhereNotEqualUserId(string $userName, int $userId): int
    {
        $modelOfJoomlaUserFront = JSFactory::getModel('JoomlaUsersFront');
        return $modelOfJoomlaUserFront->getIdByUserNameWhereNotEqualUserId($userName, $userId);
    }

    public function activate($token)
    {
        $modelOfJoomlaUserFront = JSFactory::getModel('JoomlaUsersFront');
        return $modelOfJoomlaUserFront->activate($token);
    }
	
	public function getCaptchaData(){	
		if($this->getCapthaPermission()) {	
			if($this->getPluginSettings()){	
				return new JCaptcha($this->getPluginSettings(), array());
			}
		}
		return false;
	}
	
	public function getCapthaPermission(){
		$user = JSFactory::getUser();
        $jshopConfig = JSFactory::getConfig();
	
		if ( JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 1 && $jshopConfig->show_create_account_block && JFactory::getUser()->guest ) {
			if($user->user_id == -1) { return true;	}
		}
		
		return false;
	}
	
	public function getPluginSettings(){
		$plugin = JComponentHelper::getParams('com_users')->get('captcha', JFactory::getConfig()->get('captcha'));
		if (!$plugin){
			return JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha','recaptcha'));	
		}else{
			return $plugin;
		}		
		return false;
	}
	
	public function checkClientType(&$userShop)
	{
		$post = JFactory::getApplication()->input->post->getArray();
		if (($userShop->user_id<=0)&&(isset($post['client_type']))&&($post['client_type']>0)) {$userShop->client_type=$post['client_type'];}
	}
	
	public function checkUserShopEmailExist(string $email)
    {
		$db = \JFactory::getDBO();        
		$query = "SELECT * FROM `#__jshopping_users` AS U
                INNER JOIN `#__users` AS UM ON U.user_id = UM.id
				where UM.email='".$email."'";
		$db->setQuery($query);		
		$result = $db->loadResult();
		return (bool)$result;
	}
	
	public function getJoomlaUserById($id){
		$db = \JFactory::getDBO();
		$query = 'SELECT * FROM `#__users` WHERE `id`='.$id;
        $db->setQuery($query);
        return $db->loadAssoc();
	}

    public function updatePaymentIdByUserId(int $userId, int $paymentId): bool
    {
        $db = \JFactory::getDBO();
        $query = "UPDATE {$db->qn(static::TABLE_NAME)} SET `payment_id` = " . $db->escape($paymentId) . ' WHERE `user_id` = ' . $db->escape($userId);
        $db->setQuery($query);

        return $db->execute();
    }

    public function updateShippingIdByUserId(int $userId, $shippingId): bool
    {
        $db = \JFactory::getDBO();
        $query = "UPDATE {$db->qn(static::TABLE_NAME)} SET `shipping_id` = " . $db->escape($shippingId) . ' WHERE `user_id` = ' . $db->escape($userId);
        $db->setQuery($query);

        return $db->execute();
    }

    public function getListFields($type){
        $db = \JFactory::getDBO();
        $query = "SELECT `name`, `require` FROM `#__jshopping_config_fields` 
            WHERE `display` = " . $db->escape($type) . " OR `display`=3 ORDER BY `sorting`";
        $db->setQuery($query);

        $list = $db->loadObjectList();

        $_fields = [];
        if(!empty($list)){
            foreach($list as $k=>$field){
                $_fields[$field->name]['display'] = 1;
                $_fields[$field->name]['require'] = 0;
                if($field->require == $type || $field->require == 3){
                    $_fields[$field->name]['require'] = 1;
                }

            }
        }

        return $_fields;
    }
}
