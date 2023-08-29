<?php 

class UserAddressService
{
    /**
     * @return jshopUserAddress
     */
    public function createOrUpdateIfEmailExists(array $accountData): jshopUserAddress
    {
        $userAddressTable = JSFactory::getTable('UserAddress');
        $modelOfUserAddress = JSFactory::getModel('UserAddressesFront');
        $addressId = $modelOfUserAddress->getAddressIdByEmail($accountData['email']);

        if (!empty($addressId)) {
            $userAddressTable->load($addressId);
            $userAddressTable->bind($accountData);
        } else {
            $userAddressTable->bind($accountData);
            $userAddressTable->is_default = 1;
        }
        $userAddressTable->store();

        return $userAddressTable;
    }
}