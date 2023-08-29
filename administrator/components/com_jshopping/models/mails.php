<?php
/**
* @version      2.6.0 25.11.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelMails extends JModelLegacy{ 

    public function getList() {
        $db = \JFactory::getDBO(); 
        $query = "SELECT * FROM `#__jshopping_config_display_prices`";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    } 
}

?>