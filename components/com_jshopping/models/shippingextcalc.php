<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelShippingExtCalc extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_shipping_ext_calc';

    public function getByAliasName(string $aliasName, array $columnsToGet = ['*'])
    {
        $result = '';

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $sqlQuery = "SELECT {$stringOfSearchColumns} FROM `" . self::TABLE_NAME . "`  WHERE `alias` = {$db->q($aliasName)}";


            $db->setQuery($sqlQuery);
            $result = $db->loadObject();
        }
        
        return $result;
    }

    public function getAll($isGetPublished = 0)
    {
        $where = ($isGetPublished == 1) ? ["`published` = '1'"] : [];
        return $this->select(['*'], $where, ' ORDER BY `ordering`');
    }
}