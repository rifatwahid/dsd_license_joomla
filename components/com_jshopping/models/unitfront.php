<?php

class JshoppingModelUnitFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_unit';

    public function getAll()
    {
        $lang = JSFactory::getLang();
        $select = [
            '*',
            "`{$lang->get('name')}` as name"
        ];

        $units = $this->select($select, [], 'ORDER BY id');
        $result = [];

        foreach($units as $row) {
            $result[$row->id] = $row;
       }

       return $result;
    }
}
