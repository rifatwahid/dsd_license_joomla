<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelAddonsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_addons';

    public function getByAlias(string $alias, array $columnsToGet = ['*'])
    {
        $result = null;

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
			foreach ($columnsToGet as $key=>$value){
				$columnsToGet[$key]='`'.$columnsToGet[$key].'`';
			}
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $sqlQuery = 'SELECT ' . $stringOfSearchColumns . ' FROM `' . self::TABLE_NAME . '` WHERE `alias` = \'' . $db->escape($alias) . '\'';

            $db->setQuery($sqlQuery);
            $result = $db->loadObject();
        }
        
        return $result;
    }
}