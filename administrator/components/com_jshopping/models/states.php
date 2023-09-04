<?php
/**
* @version      3.5.2 20.03.2012
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelStates extends JModelLegacy
{
    function getAllCountries() : array
    {
        $db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();
        $where = " WHERE country_publish = '1' ";
        $ordering = "ordering";
        if ($jshopConfig->sorting_country_in_alphabet) $ordering = "name";
        $lang = JSFactory::getLang();
        $query = "SELECT country_id,   `".$lang->get("name")."` as name FROM `#__jshopping_countries` ".$where." ORDER BY ".$ordering;
        $db->setQuery($query);
        return $db->loadObjectList();
    }  

    function getCountryById($country_id)
    {
        if ($country_id != 0) {
            $db = \JFactory::getDBO();
            $jshopConfig = JSFactory::getConfig(); 
            $lang = JSFactory::getLang();             
            $query = "SELECT  `".$lang->get("name")."` as name FROM `#__jshopping_countries` WHERE  country_id=".$country_id;
            $db->setQuery($query);
            return $db->loadResult();           
        } else {
            return JText::_('COM_SMARTSHOP_ALL');
        }
        
    }  
    function getAllStates($publish = 1, $limitstart = null, $limit = null, $orderConfig = 1, $country_id=0) : array
    {
        $db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();
                
        if ($publish == 0) {
            $where = " ";
        } else {
            if ($publish == 1) {
                $where = (" WHERE S.state_publish = '1' ");
            } else {
                if ($publish == 2) {
                    $where = (" WHERE S.state_publish = '0' ");
                }
            }
        }
        if ($country_id==0 ) $where .="";
        else 
        {
            if (trim($where)==""){
                $where = " WHERE S.country_id = ".$country_id;  
            } else {
                $where .= " AND S.country_id = ".$country_id;  
            }
            
        }
        
        $ordering = "S.ordering";
        if ($orderConfig && $jshopConfig->sorting_country_in_alphabet) $ordering = "name";
        $lang = JSFactory::getLang();
        $query = "SELECT S.state_id, S.country_id, S.state_publish, S.ordering, S.`".$lang->get("name")."` as name, C.`".$lang->get("name")."` as country
                  FROM `#__jshopping_states` as S 
                  left join #__jshopping_countries as C on C.country_id=S.country_id
                  ".$where." ORDER BY ".$ordering;
        $db->setQuery($query, $limitstart, $limit);
        return $db->loadObjectList();
    }

    function getStatesByCountries($publish = 1, $countries) : array
    {
        if(count($countries) != 1) return [];
        $db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();

        if ($publish == 0) {
            $where = " ";
        } else {
            if ($publish == 1) {
                $where = (" WHERE S.state_publish = '1' ");
            } else {
                if ($publish == 2) {
                    $where = (" WHERE S.state_publish = '0' ");
                }
            }
        }

            if (trim($where)==""){
                $where = " WHERE S.country_id = ".$countries[0]->country_id;
            } else {
                $where .= " AND S.country_id = ".$countries[0]->country_id;
            }


        $ordering = "S.ordering";
        if ($jshopConfig->sorting_country_in_alphabet) $ordering = "name";
        $lang = JSFactory::getLang();
        $query = "SELECT S.state_id, S.country_id, S.state_publish, S.ordering, S.`".$lang->get("name")."` as name, C.`".$lang->get("name")."` as country
                  FROM `#__jshopping_states` as S 
                  left join #__jshopping_countries as C on C.country_id=S.country_id
                  ".$where." ORDER BY ".$ordering;
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function getCountAllStates($country_id) : int
    {
        $db = \JFactory::getDBO(); 
        if ($country_id==0) 
            $where="";
        else 
            $where=" WHERE country_id = ".$country_id;
        $query = "SELECT COUNT(state_id) FROM `#__jshopping_states` ".$where;
        $db->setQuery($query);
        return (int)$db->loadResult();
    }

    function getCountPublishStates($publish, $country_id) : int
    {
		$publish = $publish ?: 1;
        $db = \JFactory::getDBO(); 
        if ($country_id==0) $where="";
        else $where=" AND country_id = ".$country_id;         
        $query = "SELECT COUNT(state_id) FROM `#__jshopping_states` WHERE state_publish = '".intval($publish)."'".$where;
        $db->setQuery($query);
        return (int)$db->loadResult();
    }
}

?>