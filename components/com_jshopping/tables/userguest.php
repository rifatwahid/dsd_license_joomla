<?php
/**
* @version      4.7.0 18.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopUserGust extends jshopUserBase
{   
    public $arrWithCheckedErrors = [];
    
    public function __construct()
    {
        $currentObj = $this;
        JPluginHelper::importPlugin('jshoppingcheckout');
        \JFactory::getApplication()->triggerEvent('onConstructJshopUserGust', [&$currentObj]);
    }
    
    public function load()
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $objuser = $session->get('user_shop_guest');

        if (!empty($objuser)) {
            $tmp = unserialize($objuser);

            foreach ($tmp as $k => $v) {
                $this->$k = $v;
            }
        }

        $this->user_id = $jshopConfig->guest_user_id;
        $this->usergroup_id = intval($jshopConfig->default_usergroup_id_guest);
        \JFactory::getApplication()->triggerEvent('onLoadJshopUserGust', [&$currentObj]);

        return true;
    }
        
    public function bind($from, $ignore = [])
    {
        $fromArray = is_array($from);
        $fromObject = is_object($from);

        if (!$fromArray && !$fromObject) {
            return false;
        }
        
        foreach ($this->getProperties() as $k => $v) {
            if (!in_array($k, $ignore )) {
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
        $jshopConfig = JSFactory::getConfig();
        $this->user_id = $jshopConfig->guest_user_id;
        $session = JFactory::getSession();
        $session->set('user_shop_guest', serialize($this));
        \JFactory::getApplication()->triggerEvent('onAfterStoreJshopUserGust', [&$currentObj]);

        return true;
    }
    
    public function check($type)
    {
        jimport('joomla.mail.helper');

        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields[$type];
        
        \JFactory::getApplication()->triggerEvent('onBeforeCheckJshopUserGust', [&$currentObj, &$type, &$config_fields]);
        $checkerMsgs = JSFactory::getModel('UserFieldsCheckerFront')->check($this, $config_fields, $type);
        $this->arrWithCheckedErrors = $checkerMsgs['msgs'];
        $this->_error = $checkerMsgs['msg'];
        
        if ( !empty($this->arrWithCheckedErrors) ) {
            return false;
        }

        return true;
    }

    public function getError($i = null, $toString = true)
    {
        return $this->_error;
    }
}
