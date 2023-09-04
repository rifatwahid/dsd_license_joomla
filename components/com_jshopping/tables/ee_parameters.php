<?php

class ExpresseditorModelee_parameters extends JModelLegacy{
    
    public function getDataFromName($param_name){
        $db = \JFactory::getDBO();
        $lang = JFactory::getLanguage();
        $query = 'SELECT * FROM #__ee_parameters '                
                . 'WHERE param_name="'.$db->escape($param_name).'"';
        $db->setQuery($query);
        return $db->loadObject();
    }
    
}

