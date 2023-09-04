<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopUserShop extends jshopUserBase
{
    const TABLE_NAME = '#__jshopping_users';

    public $arrWithCheckedErrors = [];

    public function __construct(&$_db)
    {
        parent::__construct(static::TABLE_NAME, 'user_id', $_db);
        JPluginHelper::importPlugin('jshoppingcheckout');
    }

	public function bind($src, $ignore = Array())
	{
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					$src[$key]=0;
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}
	
    public function load($keys = null, $reset = true)
    {
        parent::load($keys, $reset);
        $this->prepareLoadUserDataAddress();
		$user_site = new JUser($keys);
		$this->email=$user_site->email;
    }

    public function store($updateNulls = false)
    {
        $currentObj = $this;
        $tmp = $this->percent_discount;
        unset($this->percent_discount);
        $this->prepareStoreUserDataAddress();
        \JFactory::getApplication()->triggerEvent('onBeforeStoreTableShop', [&$currentObj]);
        $res = parent::store($updateNulls);
        $this->prepareLoadUserDataAddress();
        $this->percent_discount = $tmp;

        return $res;
    }

    public function isUserInShop(int $id): bool
    {
		return JSFactory::getModel('UsersFront')->isExistUserId($id);
    }
    
    public function isEmailExists(string $userEmail): bool
    {
        return JSFactory::getModel('UsersFront')->isExistEmail($userEmail);
    }
    
    public function check($type = null)
    {
        $userAddressTable = JSFactory::getTable('UserAddress');
        $userAddressTable->bind($this);

        $checkerMsgs = $userAddressTable->check($type);
        $this->_error = $checkerMsgs['msg'];
        $this->arrWithCheckedErrors = $checkerMsgs['msgs'];

        return !empty($this->arrWithCheckedErrors) ? false : true;
    }
    
    public function getCountryId(int $id_user): int
    {
        return JSFactory::getModel('UsersFront')->getCountryId($id_user);
    }
    
    public function getDiscount()
    {
        return JSFactory::getModel('UserGroupsFront')->getUserDiscount($this->user_id);
    }
    
    public function getError($i = null, $toString = true)
    {
        return $this->_error;
    }
    
    public function setError($error)
    {
        $this->_error = $error;
    }
    
    public function activate($token)
    {
        return JSFactory::getModel('UsersFront')->activate($token);
    }
    
    public function getNewUserNumber()
    {
        $currentObj = $this;
        $number = $this->user_id;
        \JFactory::getApplication()->triggerEvent('onBeforeGetNewUserNumber', [&$currentObj, &$number]);

        return $number;
    }

    public function getDataOfDefaultAddress(): object
    {
        if (!empty($this->user_id)) {
            JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
            return JSFactory::getModel('UserAddressesFront')->getDataOfDefaultAddress($this->user_id) ?: new stdClass();
        }

        return new stdClass();
    }

    protected function prepareStoreUserDataAddress()
    {
        $accessColumns = JSFactory::getTable('UserAddress')->getOnlyAddressColumns();
        $properties = [];

        if (!empty($accessColumns)) {
            foreach($accessColumns as $columnName => $type) {
                if (property_exists($this, $columnName)) {                    
                    $properties[$columnName] = $this->$columnName;
                }
            }

            if (!empty($properties)) {
                $tableOfUserAddress = JSFactory::getTable('UserAddress');

                if (!empty($this->user_id)) {
                    $data = JSFactory::getModel('UserAddressesFront')->getDataOfDefaultAddress($this->user_id);

                    if (!empty($data->address_id)) {
                        $tableOfUserAddress->load($data->address_id);
                    }
                }

                $tableOfUserAddress->bind($properties);
                
                return $tableOfUserAddress->store();
            }
        }

        return true;
    }
}
