<?php
/**
* @version      4.7.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopTempCart 
{
    
    public const SAVEDAYS = 30;
    
    public function __construct()
    {
        $currentObj = $this;
        JPluginHelper::importPlugin('jshoppingcheckout');
        \JFactory::getApplication()->triggerEvent('onConstructJshopTempCart', [&$currentObj]);
    }
    
    public function insertTempCart($cart) 
    {
        //not save if type == cart
        if ($cart->type_cart != 'wishlist') {
            return 0;
        }
        
        $patch = !empty(JURI::base(true)) ? JURI::base(true) : '/';
        setcookie('jshopping_temp_cart', session_id(), time() + 3600 * 24 * self::SAVEDAYS, $patch);        

        $this->deleteByCookieIdAndTypeCart(session_id(), $cart->type_cart);
		
        $this->insertProducts($cart->products, $cart->type_cart);
        
        return 1;
    }

    public function deleteByCookieIdAndTypeCart($cookieId, string $typeCart): bool
    {
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_cart_temp` WHERE `id_cookie` = '" . $cookieId . "' AND `type_cart`='" . $typeCart . "'";
        $db->setQuery($query);

        return (bool)$db->execute();
    }

    public function insertProducts(array $products, string $typeOfCart): bool
    {
		$user = JFactory::getUser();
		$db = \JFactory::getDBO();
		if($typeOfCart == 'wishlist'){
			$query = "DELETE FROM `#__jshopping_cart_temp` WHERE `user_id` = '" . $user->id . "' ";       
			$db->setQuery($query);
			$db->execute();
			$query = "INSERT INTO `#__jshopping_cart_temp` SET `cart` = '" . $db->escape(serialize($products)) . "', `type_cart` = '" . $typeOfCart . "', `user_id` = '" . $user->id . "' ";
        }else{
			 $query = "INSERT INTO `#__jshopping_cart_temp` SET `id_cookie` = '" . session_id() . "', `cart` = '" . $db->escape(serialize($products)) . "', `type_cart` = '" . $typeOfCart . "' ";
		}
        $db->setQuery($query);

        return (bool)$db->execute();
    }
        
    public function getTempCart($id_cookie, $type_cart = 'cart') 
    {
        $user = JFactory::getUser();        
        $db = \JFactory::getDBO();
		if($type_cart == 'wishlist'){		
			$query = "SELECT `cart` FROM `#__jshopping_cart_temp` WHERE `type_cart`='" . $type_cart . "' AND `user_id`='".$user->id."' LIMIT 0,1";
        }else{
			$query = "SELECT `cart` FROM `#__jshopping_cart_temp` WHERE `id_cookie` = '" . $db->escape($id_cookie) . "' AND `type_cart`='" . $type_cart . "' LIMIT 0,1";
        }
		$db->setQuery($query);
        $cart = $db->loadResult();
        $result = ($cart != '') ? (unserialize($cart)) : [];

        return $result;
    }
}