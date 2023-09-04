<?php
/**
* @version      4.7.0 18.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopUserGust extends JObject
{
    
    public $user_id = null;
    public $usergroup_id = null;
    public $payment_id = null;
    public $shipping_id = null;
    
    public $title = null;
    public $u_name = null;
    public $f_name = null;
    public $l_name = null;
    public $m_name = null;
    public $firma_name = null;
    public $client_type = null;
    public $firma_code = null;
    public $tax_number = null;
    public $email = null;    
    public $birthday = null;
    public $home = null;
    public $apartment = null;
    public $street = null;
    public $street_nr = null;
    public $zip = null;
    public $city = null;
    public $state = null;
    public $country = null;
    public $phone = null;
    public $mobil_phone = null;
    public $fax = null;
    public $ext_field_1 = null;
    public $ext_field_2 = null;
    public $ext_field_3 = null;
    
    public $delivery_adress = null;
    public $d_title = null;
    public $d_f_name = null;
    public $d_l_name = null;
    public $d_m_name = null;
    public $d_firma_name = null;
    public $d_email = null;
    public $d_birthday = null;
    public $d_home = null;
    public $d_apartment = null;
    public $d_street = null;
    public $d_street_nr = null;
    public $d_city = null;
    public $d_zip = null;
    public $d_state = null;
    public $d_country = null;
    public $d_phone = null;
    public $d_mobil_phone = null;    
    public $d_fax = null;
    public $d_ext_field_1 = null;
    public $d_ext_field_2 = null;
    public $d_ext_field_3 = null;
    
    public $arrWithCheckedErrors = [];
    
    public function __construct()
    {
        $currentObj = $this;
        JPluginHelper::importPlugin('jshoppingcheckout');
        \JFactory::getApplication()->triggerEvent('onConstructJshopUserGust', [&$currentObj]);
    }
    
    public function load(): bool
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $objuser = $session->get('user_shop_guest');

        if (isset($objuser) && !empty($objuser)) {
            $tmp = unserialize($objuser);

            foreach($tmp as $k => $v) {
                $this->$k = $v;
            }
        }

        $this->user_id = -1;
        $this->usergroup_id = intval($jshopConfig->default_usergroup_id_guest ?? 0);
        \JFactory::getApplication()->triggerEvent('onLoadJshopUserGust', [&$currentObj]);

        return true;
    }
        
    public function bind($from, $ignore = []): bool
    {
        $fromArray = is_array($from);
        $fromObject = is_object($from);

        if (!$fromArray && !$fromObject) {
            return false;
        }
        
        foreach ($this->getProperties() as $k => $v) {
            if (!in_array($k, $ignore)) {
                if ($fromArray && isset($from[$k])) {
                    $this->$k = $from[$k];
                } else if ($fromObject && isset($from->$k)) {
                    $this->$k = $from->$k;
                }
            }
        }

        return true;
    }
    
    public function store()
    {
        $currentObj = $this;
        $this->user_id = -1;
        $session = JFactory::getSession();
        $session->set('user_shop_guest', serialize($this));
        \JFactory::getApplication()->triggerEvent('onAfterStoreJshopUserGust', [&$currentObj]);

        return true;
    }
    
    public function check($type)
    {
        jimport('joomla.mail.helper');
        
        $jshopConfig = JSFactory::getConfig();
        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields[$type];
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onBeforeCheckJshopUserGust', [&$currentObj, &$type, &$config_fields]);
        $checkerMsgs = JSFactory::getModel('UserFieldsCheckerFront')->check($this, $config_fields, $type);
        $this->arrWithCheckedErrors = $checkerMsgs['msgs'];
        $this->_error = $checkerMsgs['msg'];
        
        if ( !empty($this->arrWithCheckedErrors) ) {
            return false;
        }

        return true;
    }
    
    public function saveTypePayment($id)
    {
        $this->payment_id = $id;
        $this->store();

        return 1;
    }
    
    public function saveTypeShipping($id)
    {
        $this->shipping_id = $id;
        $this->store();

        return 1;
    }
    
    public function getError($i = null, $toString = true)
    {
        return $this->_error;
    }
}
