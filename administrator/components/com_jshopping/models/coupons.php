<?php
/**
* @version      4.1.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelCoupons extends JModelLegacy{    

    public function getAllCoupons($limitstart, $limit, $order = null, $orderDir = null) {
        $db = \JFactory::getDBO(); 
        $queryorder = 'ORDER BY C.used, C.coupon_id desc';
        if ($order && $orderDir){
            $queryorder = "ORDER BY ".$order." ".$orderDir;
        }
        $query = "SELECT C.*, U.f_name, U.l_name  FROM `#__jshopping_coupons` as C left join `#__jshopping_users_addresses` as U on (C.`for_user_id` = U.`user_id` AND U.`is_default` = 1) {$queryorder}";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query, $limitstart, $limit);
        return $db->loadObjectList();
    }
    
    public function getCountCoupons(){
        $db = \JFactory::getDBO(); 
        $query = "SELECT count(*) FROM `#__jshopping_coupons`";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadResult();   
    }    
	
	public function publishСoupons($cid,$flag){		
		$_dbpublish = JSFactory::getModel('dbpublish');
		foreach ($cid as $key => $value) {
			$_dbpublish->setFlag("#__jshopping_coupons","coupon_id",$value,"coupon_publish",$flag);			
		}
	}
	
	public function deleteCoupons($cid){
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {					
			if($_dbdelete->deleteItems("#__jshopping_coupons","coupon_id",$value))
                $text .= JText::_('COM_SMARTSHOP_COUPON_DELETED')."<br>";
            else
                $text .= JText::_('COM_SMARTSHOP_COUPON_ERROR_DELETED')."<br>";
		}
		return $text;
	}
	
	public function getOrderCouponCode(&$order){
		if ($order->coupon_id){
            $coupon = JSFactory::getTable('coupon', 'jshop'); 
            $coupon->load($order->coupon_id);
            $order->coupon_code = $coupon->coupon_code;
        }
	}
}
?>