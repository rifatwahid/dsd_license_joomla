<?php 

require_once __DIR__ . '/DbHelper.php';

class UninstallerHelper
{
    public static function dropAllShopTables()
    {
        $shopTables = DbHelper::getAllShopTables();
        return DbHelper::dropTables($shopTables);
    }
}