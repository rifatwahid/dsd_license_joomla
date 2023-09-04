<?php

class JshoppingModelExtensionsFront extends jshopBase
{
    public const TABLE_NAME = '#__extensions';

    public function getByElementAndFolderNames(array $columnsToGet = ['*'], string $elementName, string $folderName)
    {
        $result = null;

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $sqlQuery = 'SELECT ' . $stringOfSearchColumns . ' FROM `' . self::TABLE_NAME . '` WHERE `element` = \'' . $db->escape($elementName) . '\' AND `folder` = \'' . $db->escape($folderName) . '\'';

            $db->setQuery($sqlQuery);
            $result = $db->loadObject();
        }
        
        return $result;
    }
}