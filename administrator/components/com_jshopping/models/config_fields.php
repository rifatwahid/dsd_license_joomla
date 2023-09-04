<?php
/**
* @version      3.12.0 10.11.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelConfig_fields extends JModelLegacy{

    function getAllFields(){
        $db = \JFactory::getDBO();

        $query = "SELECT * FROM `#__jshopping_config_fields` ORDER BY `sorting`";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    function getFieldData($id){
        $db = \JFactory::getDBO();

        $query = "SELECT * FROM `#__jshopping_config_fields` WHERE `id`=".$id;
        $db->setQuery($query);
        return $db->loadObject();
    }
    function saveOrder($ids){
        $db = \JFactory::getDBO();
        if(!empty($ids)){
            foreach($ids as $id => $order){
                if($id && $order){
                    $query = "UPDATE `#__jshopping_config_fields` SET `sorting`=".(int)$order." WHERE `id`=".$id;
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }
    }

}
?>