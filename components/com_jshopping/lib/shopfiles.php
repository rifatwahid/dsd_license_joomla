<?php 

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Filesystem\File;

class ShopFiles extends File
{
    public function getList(string $path, array $excludeFiles = [], bool $getWithFullPAth = false): array
    {
        $result = [];

        if (is_dir($path)) {
            $unixFiles = scandir($path);
            $tempResult = [];

            $path = rtrim($path, '/');
            $path = rtrim($path, '\\');

            foreach($unixFiles as $unixFile) {

                if ( !in_array($unixFile, $excludeFiles) ) {
                    $fullPathToFile = "{$path}/{$unixFile}";
                    $pathInfo = pathinfo($fullPathToFile);
                    
                    if (!empty($pathInfo['extension'])) {
                        $tempResult[] = $getWithFullPAth ? $fullPathToFile : $unixFile;
                    }
                }
            }

            $result = $tempResult;
        }

        return $result;
    }
}