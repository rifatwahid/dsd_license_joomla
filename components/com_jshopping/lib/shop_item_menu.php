<?php
/**
* @version      4.7.0 05.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class shopItemMenu
{
    static $instance = null;
    public $list = null;
    public $list_category = null;
    public $list_manufacturer = null;
    public $list_content = null;
    public $cart = null;
    public $wishlist = null;
    public $search = null;
    public $user = null;
    public $vendor = null;
    public $shop = null;
    public $manufacturer = null;
    public $products = null;
    public $checkout = null;
    public $login = null;
    public $logout = null;
    public $editaccount = null;
    public $orders = null;
    public $register = null;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new shopItemMenu();
            self::$instance->init();
        }

        return self::$instance;
    }
    
    public function init()
    {
        $list = $this->getList();
        $this->list_category = [];
        $this->list_manufacturer = [];
        $this->list_content = [];
        $this->list_product = [];
        $this->cart = 0;
        $this->wishlist = 0;
        $this->search = 0;
        $this->user = 0;
        $this->vendor = 0;
        $this->shop = 0;
        $this->manufacturer = 0;
        $this->products = 0;
        $this->checkout = 0;
        $this->login = 0;
        $this->logout = 0;
        $this->editaccount = 0;
        $this->orders = 0;
        $this->register = 0;

        foreach($list as $k => $v) {
            $data = $v->data;

            if ((!isset($data['controller']) || !$data['controller']) && (isset($data['view']) && $data['view'])) {
                $data['controller'] = $data['view'];
                unset($data['view']);
                unset($data['layout']);
            }

            if (count($data) == 3 && $data['controller'] == 'category' && $data['task'] == 'view' && $data['category_id']) {
                $this->list_category[$data['category_id']] = $v->id;
            }

            if (count($data) == 3 && $data['controller'] == 'manufacturer' && $data['task'] == 'view' && $data['manufacturer_id']) {
                $this->list_manufacturer[$data['manufacturer_id']] = $v->id;
            }

            if (count($data)==3 && $data['controller'] == 'content' && $data['task'] == 'view' && $data['page']) {
                $this->list_content[$data['page']] = $v->id;
            }

            if (count($data)==3 && $data['controller'] == 'product' && $data['product_id']) {
				$this->list_product[$data['product_id']] = $v->id;
            }

            if (count($data) == 2 && $data['controller'] == 'user' && $data['task'] == 'login') {
                $this->login = $v->id;
            }

            if (count($data) == 2 && $data['controller'] == 'user' && $data['task'] == 'logout') {
                $this->logout = $v->id;
            }

            if (count($data) == 2 && $data['controller'] == 'user' && $data['task'] == 'editaccount') {
                $this->editaccount = $v->id;
            }

            if (count($data) == 2 && $data['controller'] == 'user' && $data['task'] == 'orders') {
                $this->orders = $v->id;
            }

            if (count($data) == 2 && $data['controller'] == 'user' && $data['task'] == 'register') {
                $this->register = $v->id;
            }

            if ($data['controller'] == 'cart') {
                $this->cart = $v->id;
            }

            if ($data['controller'] == 'wishlist') {
                $this->wishlist = $v->id;
            }

            if ($data['controller'] == 'search') {
                $this->search = $v->id;
            }

            if ($data['controller'] == 'category' && count($data) == 1) {
                $this->shop = $v->id;
            }

            if ($data['controller'] == 'manufacturer' && count($data) == 1) {
                $this->manufacturer = $v->id;
            }

            if ($data['controller'] == 'products' && count($data) == 1) {
                $this->products = $v->id;
            }

            if ($data['controller'] == 'user' && count($data) == 1) {
                $this->user = $v->id;
            }

            if ($data['controller'] == 'vendor' && count($data) == 1) {
                $this->vendor = $v->id;
            }

            if ($data['controller'] == 'qcheckout') {
                $this->checkout = $v->id;
            }            
        }
    }
    
    public function getList()
    {
        if (!is_array($this->list)) {
			$dispatcher = \JFactory::getApplication();
            $jshopConfig = JSFactory::getConfig();
            $current_lang = $jshopConfig->getLang();
            $user = JFactory::getUser();
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $db = \JFactory::getDBO();
			$where = '';
			$dispatcher->triggerEvent('onBeforeGetListMenu', array(&$where));
            $query = "select id, link from #__menu where `type`='component' and published=1 and link like '%option=com_jshopping%' and client_id=0 and (language='*' or language='{$current_lang}') and access IN ({$groups}) ".$where;
            $db->setQuery($query);
            $this->list = $db->loadObjectList();

            foreach($this->list as $k => $item) {
                $data = [];
                $item->link = str_replace('index.php?option=com_jshopping&', '', $item->link);
                $tmp = explode('&', $item->link);

                foreach($tmp as $k2 => $v2){
                    $tmp2 = explode('=', $v2);

                    if ($tmp2[1] != '') {
                        $data[$tmp2['0']] = $tmp2['1'];
                    }
                }

                $this->list[$k]->data = $data;
            }
        }

        return $this->list;
    }
    
    public function getListCategory()
    {
        return $this->list_category;
    }
    
    public function getListManufacturer()
    {
        return $this->list_manufacturer;
    }
    
    public function getListContent()
    {
        return $this->list_content;
    }
    
    public function getListProduct()
    {
        return $this->list_product;
    }
    
    public function getCart()
    {
        return $this->cart;
    }
    
    public function getWishlist()
    {
        return $this->wishlist;
    }
    
    public function getSearch()
    {
        return $this->search;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getLogin()
    {
        return $this->login;
    }
    
    public function getLogout()
    {
        return $this->logout;
    }
    
    public function getEditaccount()
    {
        return $this->editaccount;
    }
    
    public function getOrders()
    {
        return $this->orders;
    }
    
    public function getRegister()
    {
        return $this->register;
    }

    public function getVendor()
    {
        return $this->vendor;
    }
    
    public function getShop()
    {
        return $this->shop;
    }
    
    public function getManufacturer()
    {
        return $this->manufacturer;
    }
    
    public function getProducts()
    {
        return $this->products;
    }
    
    public function getCheckout()
    {
        return $this->checkout;
    }
}
