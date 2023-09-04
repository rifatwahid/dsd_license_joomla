<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopUserAddress extends JTableAvto 
{
    const TABLE_NAME = '#__jshopping_users_addresses';

    public function __construct(&$db)
    {
        parent::__construct(static::TABLE_NAME, 'address_id', $db);
    }

	public function bind($src, $ignore = Array())
	{
		$fields = (parent::getTableFields()) ?: [];		
		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key])||$src[$key]=="")&&($value->Extra=="auto_increment")){
				unset($fields[$key]);
			}
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')&&(strtoupper(substr($value->Type,0,4))!='DATE')){					
					$src[$key]=0;
				}
				if ((strtoupper(substr($value->Type,0,4))=='DATE')){					
					$src[$key]="1970-01-02 00:00:00";
				}
			}						
		}
		
		if ($src['birthday']!='..')	return parent::bind($src, $ignore);
	}

    public function bindAndSave(array $dataToBind, int $userId): bool
    {
        if (!empty($dataToBind)) {
            $jshopConfig = JSFactory::getConfig();
            $filteredBindData = $dataToBind;

            if (!empty($filteredBindData['birthday'])) {
                $filteredBindData['birthday'] = getJsDateDB($filteredBindData['birthday'], $jshopConfig->field_birthday_format);
            }
            if (!empty($filteredBindData['d_birthday'])) {
                $filteredBindData['d_birthday'] = getJsDateDB($filteredBindData['d_birthday'], $jshopConfig->field_birthday_format);
            }
    
            if (!empty($userId)) {
                $filteredBindData['user_id'] = $userId;
            }

            $this->bind($filteredBindData);
            $checkerMsgs = $this->check()['msgs'] ?: [];

            if (!empty($checkerMsgs)) {
                $this->setErrors($checkerMsgs);
                return false;
            }

            $isStored = $this->store();

            if (!$isStored) {
                $this->setErrors([
                    JText::_('COM_SMARTSHOP_FAILED_TO_SAVE_DATA')
                ]);
                return false;
            }

            return true;
        }

        $this->setErrors([
            JText::_('COM_SMARTSHOP_FAILED_TO_SAVE_DATA')
        ]);
        return false;
    }

    public function check(string $type = 'address', string $type2 = 'newuseraddress')
    {
        jimport('joomla.mail.helper');

        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();

        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields[$type] ?: [];

        if (isset($config_fields['privacy_statement'])) {
            unset($config_fields['privacy_statement']);
        }

        $dispatcher->triggerEvent('onBeforeCheckJshopUserShop', [$this, &$type, &$config_fields, &$type2]);
        $checkerMsgs = JSFactory::getModel('UserFieldsCheckerFront')->check($this, $config_fields, $type, $type2);

        if (!empty($checkerMsgs['msgs'])) {
            $this->setErrors($checkerMsgs['msgs']);
        }

        return $checkerMsgs;
    }

    public function setAsDefault(): bool
    {
        if (!empty($this->address_id) && empty($this->is_default) && !empty($this->user_id)) {
            $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
            return $modelOfUserAddressesFront->setAsDefault($this->address_id, $this->user_id);
        }

        return false;
    }

    public function setAsBillDefault(): bool
    {
        if (!empty($this->address_id) && empty($this->is_default_bill) && !empty($this->user_id)) {
            $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
            return $modelOfUserAddressesFront->setAsBillDefault($this->address_id, $this->user_id);
        }

        return false;
    }

    public function countByUserId(int $userId): int
    {
        $count = 0;

        if (!empty($userId)) {
            $db = \JFactory::getDBO();
            $select = "SELECT count(`user_id`) FROM `{$this->getTableName()}` WHERE `user_id` = {$db->escape($userId)}";
            $db->setQuery($select);
            $count = $db->loadResult() ?: 0;
        }

        return $count;
    }

    public function getOnlyAddressColumns()
    {
        $columns = JFactory::getDbo()->getTableColumns(static::TABLE_NAME);
        $newColumns = [];
        $excluded = [
            'is_default'
        ];

        foreach ($columns as $key => $type) {
            if (!in_array($key, $excluded)) {
                $newColumns[$key] = $type;
            }
        }

        return $newColumns;
    }
}