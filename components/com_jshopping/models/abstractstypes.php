<?php 

defined('_JEXEC') or die('Restricted access');

class JshoppingModelAbstractsTypes extends jshopBase
{
    const TABLE_NAME = '#__jshopping_abstracts_types';

    public function getAll(string $index = 'id'): array
    {
        $query = 'SELECT * FROM ' . self::TABLE_NAME;
        $db = \JFactory::getDBO();
        $db->setQuery($query);

        return $db->loadObjectList($index) ?: [];
    }

}