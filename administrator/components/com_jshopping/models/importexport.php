<?php
/**
* @version      2.3.0 27.09.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelImportExport extends JModelLegacy{
    
    public function getList() {
        $db = \JFactory::getDBO();                
        $query = "SELECT * FROM `#__jshopping_import_export` ORDER BY name";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);        
        return $db->loadObjectList();
    }
        
    
}

?>