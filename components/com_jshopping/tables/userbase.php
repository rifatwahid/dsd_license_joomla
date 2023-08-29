<?php 

defined('_JEXEC') or die('Restricted access');

abstract class jshopUserBase extends JTableAvto
{
    public $user_id = null;
    public $usergroup_id = null;
    public $payment_id = null;
    public $shipping_id = null;
    public $u_name = null;
    public $number = null;
    public $email = null;
    public $lang = null;
    public $sender_address = null;

    public function saveTypePayment($id): bool
    {
        $result = false;
        $this->payment_id = $id;

        if (!empty($this->user_id) && isset($id)) {
            $modelOfUsersFront = JSFactory::getModel('UsersFront');
            $result = $modelOfUsersFront->updatePaymentIdByUserId($this->user_id, $id);
        }

        return $result;
    }
    
    public function saveTypeShipping($id): bool
    {
        $result = false;
        $this->shipping_id = $id;
        
        if (!empty($this->user_id) && isset($id)) {
            $modelOfUsersFront = JSFactory::getModel('UsersFront');
            $result = $modelOfUsersFront->updateShippingIdByUserId($this->user_id, $id);
        }

        return $result;
    }

    public function addUserToTableShop($user) 
    {
        $currentObj = $this;
		$this->u_name = $user->username;
		$this->email = $user->email;
		$this->user_id = $user->id;
        $number = $this->getNewUserNumber();
        $default_usergroup = JSFactory::getTable('usergroup', 'jshop')->getDefaultUsergroup();

        JSFactory::getModel('UsersFront')->addNewUser($default_usergroup, $user->username, $user->email, $user->id, $user->name, $number);
        \JFactory::getApplication()->triggerEvent('onAfterAddUserToTableShop', [&$currentObj]);
    }

    protected function prepareLoadUserDataAddress(): bool
    {
        $accessColumns = JSFactory::getTable('UserAddress')->getOnlyAddressColumns();
        $dataOfDefaultAddress = $this->getDataOfDefaultAddress();

        if (!empty($dataOfDefaultAddress->address_id)) {
            foreach($dataOfDefaultAddress as $key => $addressData) {
                if (array_key_exists($key, $accessColumns)) {
                    $this->$key = $addressData;
                }
            }

            return true;
        }

        return false;
    }
}