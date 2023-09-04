<?php
/**
* @version      4.5.0 31.05.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelUsers extends JModelLegacy
{    

	public function getAllUsers($limitstart, $limit, $text_search = '', $order = null, $orderDir = null) 
	{
        $db = \JFactory::getDBO();        
		$jshopConfig = JSFactory::getConfig();
		$current_fields = $jshopConfig->getListFieldsRegister();		
		$where = [];
		
        if (!empty($text_search)) {
			$where[] = 'and (';
			$search = $db->escape($text_search);
			$el = 0;
			$excluded = [
				'password',
				'password_2',
				'd_title',
				'privacy_statement'
			];

			foreach ($current_fields['register'] as $fieldName => $field) {

				if ( !in_array($fieldName, $excluded) && $field['display'] == 1) {
					if ($el > 0) {
						$where[] = 'or';
					}

					$where[] = "UA.{$fieldName} like '%{$search}%'";
					$el++;
				}

			}

			$where[] = "or U.u_name like '%{$search}%' or 
						UA.f_name like '%{$search}%' or 
						UA.l_name like '%{$search}%' or 
						UA.email like '%{$search}%' or 
						UA.firma_name like '%{$search}%' or 
						U.number = '{$search}')";
		}

        $queryorder = '';		
        if ($order && $orderDir) {
            $queryorder = "order by {$order} {$orderDir}";
		}
			
		$where = implode(' ', $where);
		
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeGetAllUsers', array(&$where));
		
        $query = "SELECT U.number, U.u_name, UA.f_name, UA.l_name, UA.email, U.user_id, U.block, UG.usergroup_name FROM `#__jshopping_users` AS U
                INNER JOIN `#__users` AS UM ON U.user_id = UM.id
                left join `#__jshopping_usergroups` as UG on UG.usergroup_id=U.usergroup_id
				left join `#__jshopping_users_addresses` AS UA ON (U.`user_id` = UA.`user_id` AND UA.`is_default` = 1)
				where 1 {$where} {$queryorder}";
        extract(js_add_trigger(get_defined_vars(), 'before'));
		$db->setQuery($query, $limitstart, $limit);
		
        return $db->loadObjectList();
    }

	public function getCountAllUsers($text_search = '') 
	{
        $db = \JFactory::getDBO(); 
		
		$jshopConfig = JSFactory::getConfig();
		$current_fields = $jshopConfig->getListFieldsRegister();
		$where = [];

        if (!empty($text_search)) {
			$where[] = 'and (';
			$search = $db->escape($text_search);
			$el = 0;
			$excluded = [
				'password',
				'password_2',
				'd_title',
				'privacy_statement'
			];

			foreach ($current_fields['register'] as $fieldName => $field) {

				if ( !in_array($fieldName, $excluded) && $field['display'] == 1) {
					if ($el > 0) {
						$where[] = 'or';
					}

					$where[] = "UA.{$fieldName} like '%{$search}%'";
					$el++;
				}

			}			
			
			$where[] = "or U.u_name like '%{$search}%' or 
						UA.f_name like '%{$search}%' or
						UA.l_name like '%{$search}%' or
						UA.email like '%{$search}%' or 
						UA.firma_name like '%{$search}%' or 
						U.number = '{$search}')";
		}
	
		$where = implode(' ', $where);
		
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeGetAllUsers', array(&$where));
		
        $query = "SELECT COUNT(U.user_id) FROM `#__jshopping_users` AS U
                INNER JOIN `#__users` AS UM ON U.user_id = UM.id
				left join `#__jshopping_users_addresses` AS UA ON (U.`user_id` = UA.`user_id` AND UA.`is_default` = 1)
				where 1 {$where}";
        extract(js_add_trigger(get_defined_vars(), 'before'));
		$db->setQuery($query);

        return $db->loadResult();
    }

    public function getUsers(){
        $db = \JFactory::getDBO();
        $query = "SELECT U.`user_id`, concat(UA.`f_name`, ' ', UA.`l_name`) as `name`
                  FROM `#__jshopping_users` as U 
				  INNER JOIN `#__users` AS UM ON U.`user_id` = UM.`id`
				  left join `#__jshopping_users_addresses` AS UA ON (U.`user_id` = UA.`user_id` AND UA.`is_default` = 1)
                  ORDER BY UA.`f_name`, UA.`l_name`";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	public function getUserById($id){
		$db = \JFactory::getDBO();
		$query = 'SELECT * FROM `#__jshopping_users` WHERE `user_id`='.$id;
        $db->setQuery($query);
        return $db->loadAssoc();
	}
	
	public function getJoomlaUserById($id){
		$db = \JFactory::getDBO();
		$query = 'SELECT * FROM `#__users` WHERE `id`='.$id;
        $db->setQuery($query);
        return $db->loadAssoc();
	}
	
	public function setUserBlockById($value,$flag){
		$db = \JFactory::getDBO();
		$query = "UPDATE `#__jshopping_users` SET `block` = '".$db->escape($flag)."' WHERE `user_id` = '" . $db->escape($value) . "'";
		$db->setQuery($query);
		$db->execute();
	}
}
