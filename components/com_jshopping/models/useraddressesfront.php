<?php

use Joomla\CMS\Factory;

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelUserAddressesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_users_addresses';
    protected $isReplaceCountryIdToName = true;

    public function isReplaceCountryIdToName(bool $isReplace): bool
    {
        $this->isReplaceCountryIdToName = $isReplace;
        return true;
    }

    public function isEmailExistsWhereNotEqualUserId(string $email, int $userId, bool $isEscapeGuests = true): bool
    {
        $db = \JFactory::getDBO();
        $sqlSelect = "SELECT `email` FROM {$db->qn(static::TABLE_NAME)} WHERE `email` = {$db->q($email)} AND `user_id` != {$userId}";

        if ($isEscapeGuests) {
            $config = JSFactory::getConfig();
            $sqlSelect .= ' AND `user_id` != ' . $db->escape($config->guest_user_id);
        }

        $db->setQuery($sqlSelect);
        $result = $db->loadResult();

        return (bool)$result;
    }

    public function deleteByUserId(int $userId): bool
    {
        $db = \JFactory::getDBO();
        $deleteSql = "DELETE FROM `{$db->escape(static::TABLE_NAME)}` WHERE `user_id` = {$userId}";
        $db->setQuery($deleteSql);
        $isDeleted = $db->execute();

        return $isDeleted;
    }

    public function isEmailExists(string $email, bool $isEscapeGuests = true): bool
    {
        $db = \JFactory::getDBO();
        $sqlSelect = "SELECT `email` FROM {$db->qn(static::TABLE_NAME)} WHERE `email` = {$db->q($email)}";
        
        if ($isEscapeGuests) {
            $config = JSFactory::getConfig();
            $sqlSelect .= ' AND `user_id` != ' . $db->escape($config->guest_user_id);
        }

        $db->setQuery($sqlSelect);
        $result = $db->loadResult();

        return (bool)$result;
    }

    public function getAddressIdByEmail(string $email): int
    {
        $db = \JFactory::getDBO();
        $sqlSelect = "SELECT `address_id` FROM {$db->qn(static::TABLE_NAME)} WHERE `email` = {$db->q($email)}";
        $db->setQuery($sqlSelect);
        $result = $db->loadResult();

        return (int)$result;
    }

    public function getById(int $addressId): ?object
    {
		$return = new stdClass();
        if (!empty($addressId)) {
            $db = \JFactory::getDBO();
            $sqlSelect = "SELECT * FROM {$db->qn(static::TABLE_NAME)} WHERE `address_id` = {$db->q($addressId)}";

            $db->setQuery($sqlSelect);
            return $db->loadObject();
        }
		return $return;
		
    }

    public function getAllByUserId(int $userId, ?int $offset = 0, ?int $limit = 0, ?array $searchLikeWordData = []): array
    {
        $db = \JFactory::getDBO();
        $sqlSelect = "SELECT UA.* FROM {$db->qn(static::TABLE_NAME)} as UA WHERE `user_id` = {$db->escape($userId)}";

        if ($this->isReplaceCountryIdToName) {
            $langTag = getCurrentLangTag();
            $langname = 'name_' . $langTag;

            $sqlSelect = "SELECT UA.*, C.`{$langname}` as `country` FROM {$db->qn(static::TABLE_NAME)} as UA 
            LEFT JOIN `#__jshopping_countries` as C on (UA.`country` = C.`country_id`)
            WHERE `user_id` = {$db->escape($userId)}";
        }

        if (!empty($searchLikeWordData)) {
            $likeQuery = $this->transformLikeDataToStr($searchLikeWordData);

            if (!empty($likeQuery)) {
                $sqlSelect .= " AND ({$likeQuery})";
            }
        }
        
        $sqlSelect .= ' ORDER BY `is_default` DESC';

        if (!empty($limit)) {
            $sqlSelect .= " LIMIT {$db->escape($limit)}";

            if (!empty($offset)) {
                $sqlSelect .= " OFFSET {$db->escape($offset)}";
            }
        } 

        $db->setQuery($sqlSelect);
        $list = $db->loadObjectList();
        $token = JSession::getFormToken();
        if(!empty($list)){
            foreach($list as $key=>$value){
                $list[$key]->set_default_link = html_entity_decode(SEFLink("index.php?option=com_jshopping&controller=user&task=setDefaultAddress&defaultId={$value->address_id}&{$token}=1", 1));
                $list[$key]->edit_link =SEFLink("index.php?option=com_jshopping&controller=user&task=editAddress&editId={$value->address_id}", 1);
                $list[$key]->edit_link_popup =html_entity_decode(SEFLink("index.php?option=com_jshopping&controller=user&task=editAddress&editId={$value->address_id}&isCloseTabAfterSave=1", 1));
                $list[$key]->delete_link =html_entity_decode(SEFLink("index.php?option=com_jshopping&controller=user&task=deleteAddress&deleteId={$value->address_id}&{$token}=1", 1));
            }
        }

        return $list ?: [];
    }

    public function getCountByUserId(int $userId, ?array $searchLikeWordData = []): int
    {
        $db = \JFactory::getDBO();
        $sqlCount = "SELECT count(*) FROM {$db->qn(static::TABLE_NAME)} WHERE `user_id` = {$db->escape($userId)}";

        if (!empty($searchLikeWordData)) {
            $likeQuery = $this->transformLikeDataToStr($searchLikeWordData);

            if (!empty($likeQuery)) {
                $sqlCount .= " AND ({$likeQuery})";
            }
        }

        $db->setQuery($sqlCount);
        $count = $db->loadResult();

        return $count ?: 0;
    }

    /*
    [
        'word' => "word%",
        'byColumns' => [
            'columnName1',
            'columnNameN',
        ]
    ]
    */
    protected function transformLikeDataToStr(?array $searchLikeWordData = []): string
    {
        $result = '';

        if (!empty($searchLikeWordData['word']) && !empty($searchLikeWordData['byColumns'])) {
            $resultTemp = [];
            $db = \JFactory::getDBO();

            foreach ($searchLikeWordData['byColumns'] as $columnName) {
                $resultTemp[] = " ({$db->qn($columnName)} LIKE {$db->q($searchLikeWordData['word'])}) ";
            }

            if (!empty($resultTemp)) {
                $result = implode(' OR ', $resultTemp);
            }
        }

        return $result;
    }

    public function setAsDefault(int $addressId, int $userId): bool
    {
        if (!empty($addressId) && !empty($userId)) {
            $db = \JFactory::getDBO();
            $sqlSetDefault = "UPDATE {$db->qn(static::TABLE_NAME)} SET `is_default` = 1 WHERE `address_id` = {$db->escape($addressId)}";
            $sqlDeleteDefault = "UPDATE {$db->qn(static::TABLE_NAME)} SET `is_default` = 0 WHERE `user_id` = {$db->escape($userId)}";

            $deleteStatus = $db->setQuery($sqlDeleteDefault)->execute();

            if ($deleteStatus) {
                $db->setQuery($sqlSetDefault);
                return $db->execute();
            }
        }

        return false;
    }

    public function getDataOfDefaultAddress(int $userId): ?object
    {
        $db = \JFactory::getDBO();
        $sqlSelect = "SELECT * FROM `#__jshopping_users_addresses` WHERE `user_id` = {$db->escape($userId)}";
        $db->setQuery($sqlSelect);
        $result = $db->loadObjectList();
		$return = null;
		
		if(!empty($result)){
			foreach($result as $key=>$val){
				if($val->is_default){
					$return = $result[$key];
					break;
				}
			}
			if(!$return && $result[0]->address_id){
				$sqlSelect = "UPDATE `#__jshopping_users_addresses` SET `is_default`=1 WHERE `user_id` = {$db->escape($userId)} AND `address_id` = {$db->escape($result[0]->address_id)}";
				$db->setQuery($sqlSelect);
				$db->execute();	
				
				$return = $result[0];
			}
		}
        return $return;
    }

    public function setAsBillDefault(int $addressId, int $userId): bool
    {
        if (!empty($addressId) && !empty($userId)) {
            $db = \JFactory::getDBO();
            $sqlSetDefault = "UPDATE {$db->qn(static::TABLE_NAME)} SET `is_default_bill` = 1 WHERE `address_id` = {$db->escape($addressId)}";
            $sqlDeleteDefault = "UPDATE {$db->qn(static::TABLE_NAME)} SET `is_default_bill` = 0 WHERE `user_id` = {$db->escape($userId)}";

            $deleteStatus = $db->setQuery($sqlDeleteDefault)->execute();

            if ($deleteStatus) {
                $db->setQuery($sqlSetDefault);
                return $db->execute();
            }
        }

        return false;
    }

    public function getDataOfDefaultBillAddress(int $userId): ?object
    {
        $db = \JFactory::getDBO();
        $sqlSelect = "SELECT * FROM `#__jshopping_users_addresses` WHERE `user_id` = {$db->escape($userId)} AND `is_default_bill` = 1";
        $db->setQuery($sqlSelect);
        $result = $db->loadObject() ?: null;

        return $result;
    }

    /**
     * @return array with errors.
     */
    public function checkVerifyFieldsById(?int $billingAddressId, ?int $shippingAddressId): array
    {
        $result = [];

        if (!empty($billingAddressId)) {
            $tableOfUserAddress = JSFactory::getTable('UserAddress');
            $tableOfUserAddress->load($billingAddressId);
            $result['billing'] = $tableOfUserAddress->check()['msgs'] ?: [];
        }

        if (!empty($shippingAddressId)) {
            $tableOfUserAddress = JSFactory::getTable('UserAddress');
            $tableOfUserAddress->load($shippingAddressId);
            $result['shipping'] = $tableOfUserAddress->check()['msgs'] ?: [];
        }

        return $result;
    }

    public function isCurrentUserOwnedAddressId($addressId): bool
    {
        $isOwner = false;
        $currentUser = JSFactory::getUser();

        if (!empty($currentUser) && $currentUser->user_id != -1) {
            $addressData = $this->getById($addressId);

            if (!empty($addressData->user_id)) {
                $isOwner = ($currentUser->user_id == $addressData->user_id);
            }
        }

        return $isOwner;
    }

    public function setDefaultAddressIfNotExistsByUserId(int $userId): bool
    {
        $isSuccess = true;
        $defaultData = $this->getDataOfDefaultAddress($userId);

        if (empty($defaultData)) {
            $isSuccess = $this->setDefaultAddressForFirstByUserId($userId);
        }

        return $isSuccess;
    }

    public function setDefaultAddressForFirstByUserId(int $userId): bool
    {
        $db = Factory::getDbo();
        $sql = 'UPDATE ' . $db->qn(static::TABLE_NAME) . ' 
            SET `is_default` = 1
            WHERE `address_id` = (
                SELECT `address_id` FROM (
                    SELECT `ad2`.`address_id` FROM ' . $db->qn(static::TABLE_NAME) . ' AS `ad2` WHERE `ad2`.`user_id` = ' . $db->escape($userId) . ' LIMIT 1
                ) AS `address`
            );';
        $db->setQuery($sql);
        return $db->execute();
    }
	
	public function reloadPaymentsOnAdressChanged(&$adv_user,&$tableOfUserAddress){
		$isAuthorizedUser = !empty($adv_user->user_id) && $adv_user->user_id != -1;        

        if (!$isAuthorizedUser) {			
			foreach ($adv_user as $key=>$value){
				if (isset($tableOfUserAddress->$key)){
					if ($tableOfUserAddress->$key!="")	$adv_user->$key=$tableOfUserAddress->$key;
				}
			}	
		}
	}
}