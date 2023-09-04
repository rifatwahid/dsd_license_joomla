<?php

class JshoppingModelModulesFront extends jshopBase
{
    public function getByModuleName(array $columnsToGet = ['*'], string $moduleName)
    {
        $result = null;

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $sqlQuery = "SELECT {$stringOfSearchColumns}  FROM `#__modules` WHERE `module` = {$db->quote($moduleName)}";

            $db->setQuery($sqlQuery);
            $result = $db->loadObject();
        }
        
        return $result;
    }
}
