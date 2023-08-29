<?php 

defined('_JEXEC') or die('Restricted access');

class jshopImportExportFront extends jshopBase
{

    public function getDataByLatestStartLessCurrentTime()
    {
        $db = \JFactory::getDBO();
        $currentUnixTime = time();
        $query = "SELECT * FROM `#__jshopping_import_export` where `steptime` > 0 and (endstart + steptime < $currentUnixTime) ORDER BY `id`";
        $db->setQuery($query);
        
        return $db->loadObjectList();
    }

}