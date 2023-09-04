<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../FrontMambot.php';

class OrderExtrascouponMambot extends FrontMambot
{
	protected static $instance;

	public function onAfterCreateOrder(&$order) 
	{
		if ($order->coupon_id) {
			$db = \JFactory::getDBO();

			$coupon = JTable::getInstance('coupon', 'jshop');
			$coupon->load($order->coupon_id);
			
			if ($coupon->once_for_each_user) {	
				$rest = 0;			
				$modelOfCouponsUsersRestFront = JSFactory::getModel('CouponsUsersRestFront');
				$obj = $modelOfCouponsUsersRestFront->getDataByUserAndCouponIds($order->user_id, $order->coupon_id);

				if ($coupon->coupon_type == 1) { // value
					$cart = JModelLegacy::getInstance('cart', 'jshop');
					$cart->load();
					$rest = $cart->getFreeDiscount();
				}
				
				$query = "INSERT INTO `#__jshopping_coupons_users_rest` (`user_id`, `coupon_id`, `rest`) VALUES ('{$order->user_id}', '{$order->coupon_id}', '{$rest}');";
				
				if (is_object($obj) && ($obj->user_id)) {
					$query = "UPDATE `#__jshopping_coupons_users_rest` SET `rest`='{$rest}' WHERE `user_id`='{$obj->user_id}' AND `coupon_id`='{$obj->coupon_id}'";
				}
				
				$db->setQuery($query);
				$db->execute();
			}
			
			if ($coupon->limited_use) {
				$query = "SELECT COUNT(`coupon_id`) FROM `#__jshopping_orders` WHERE `coupon_id` = '{$order->coupon_id}'";

				if ($coupon->once_for_each_user) {
					$query = "SELECT COUNT(`coupon_id`) FROM `#__jshopping_coupons_users_rest` WHERE `coupon_id` = '{$order->coupon_id}' AND `rest` = 0";
				}

				$db->setQuery($query);
				$count = $db->loadResult();
				if ($count >= $coupon->limited_count) {
					$query = "UPDATE `#__jshopping_coupons` SET `used` = '{$order->user_id}' WHERE `coupon_id` = '{$order->coupon_id}'";
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
	}
}
