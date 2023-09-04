<?php 

class ExtensionHelper
{
    public static function installToJoomla($data, $installExist = 0)
    {
        $exid = static::getExtensionId($data['element'], $data['folder']);

        if ($exid && !$installExist) {
            return -1;
        }

        $extension = JSFactory::getTable('extension', 'JTable');
        if ($exid) {
            $extension->load($exid);
        }

        $extension->bind($data);

        if ($extension->check()) {
            $extension->store();
            return 1;
        }

        return 0;
    }

    public static function getExtensionId($elementName, $folderName)
    {
        $db = \JFactory::getDBO();
        $db->setQuery("SELECT `extension_id` FROM `#__extensions` WHERE element='" . $db->escape($elementName) . "' AND folder='" . $db->escape($folderName) . "'");

        return (int)$db->loadResult();
    }

    public static function changeExtensionVersion($extensionId, $newVersion)
    {
        $result = false;

        if (!empty($extensionId) && !empty($newVersion)) {
            $db = \JFactory::getDBO();
            $sqlSelectExt = 'SELECT * FROM `#__extensions` WHERE `extension_id` = ' . $extensionId;
            $db->setQuery($sqlSelectExt);
            $extensionData = $db->loadObject();

            if (!empty($extensionData->manifest_cache)) {
                $manifest = json_decode($extensionData->manifest_cache);

                if (!empty($manifest->version)) {
                    $manifest->version = $newVersion;
                    $sqlUpdate = 'UPDATE `#__extensions` SET `manifest_cache` = ' . $db->quote(json_encode($manifest)) . ' WHERE `extension_id` = ' . $extensionId;
                    $db->setQuery($sqlUpdate);
                    $result = $db->execute();
                }
            }
        }

        return $result;
    }

    public static function renameSqlAdminFiles($prefix = 'old__')
    {
        $result = false;
        $pathToSqlFolder = JPATH_ROOT . '/administrator/components/com_jshopping/sql/updates/mysql/';
        $listOfSqlFiles = scandir($pathToSqlFolder);

        if (!empty($listOfSqlFiles)) {
            $ignoreNames = [
                '.',
                '..'
            ];

            foreach ($listOfSqlFiles as $fileName) {
                if (!in_array($fileName, $ignoreNames)) {
                    $pathToCurrentIterationFile = $pathToSqlFolder . $fileName;
                    $pathToNewFile = $pathToSqlFolder . $prefix . $fileName;

                    rename($pathToCurrentIterationFile, $pathToNewFile);
                }
            }

            $result = true;
        }

        return $result;
    }
}