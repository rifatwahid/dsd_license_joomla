<?php
/**
* @version      4.7.1 22.10.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopConfig_fields extends JTableAvto
{
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_config_fields', 'id', $_db);
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
    
    public function getExistCode()
    {
        $query = "SELECT `coupon_id` FROM `#__jshopping_coupons`
                  WHERE `coupon_code` = '" . $this->_db->escape($this->coupon_code) . "' AND `coupon_id` <> '" . $this->_db->escape($this->coupon_id) . "'";
		extract(js_add_trigger(get_defined_vars(), 'query'));
        $this->_db->setQuery($query);
        $this->_db->execute();		
        return $this->_db->getNumRows();
    }
    
    public function getEnableCode($code)
    {
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();

        if(!$jshopConfig->use_rabatt_code) {
            $this->error = JText::_('COM_SMARTSHOP_RABATT_NON_SUPPORT');
            return 0;
        }

        $date = getJsDate('now', 'Y-m-d');
        $query = "SELECT * FROM `#__jshopping_coupons` WHERE coupon_code = '".$db->escape($code)."' AND coupon_publish = '1'";
		extract(js_add_trigger(get_defined_vars(), 'query'));
        $db->setQuery($query);
        $row = $db->loadObject();
        
        if(!isset($row->coupon_id) || ($row->coupon_expire_date < $date && $row->coupon_expire_date != '0000-00-00') || ($row->coupon_start_date > $date)) {
            $this->error = JText::_('COM_SMARTSHOP_RABATT_NON_CORRECT');
            return 0;
        }
        
        if($row->used) {
            $this->error = JText::_('COM_SMARTSHOP_RABATT_USED');
            return 0;
        }
        
        if ($row->for_user_id) {
            $user = JFactory::getUser();
            if (!$user->id) {
                $this->error = JText::_('COM_SMARTSHOP_FOR_USE_COUPON_PLEASE_LOGIN');
                return 0;
            }

            if ($row->for_user_id != $user->id) {
                $this->error = JText::_('COM_SMARTSHOP_RABATT_NON_CORRECT');
                return 0;    
            }
        }
        
        $this->load($row->coupon_id);
        return 1;                
    }

}