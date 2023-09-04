<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsExtraFieldValuesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_extra_field_values';

    public function getAll(int $display = 0): array
    {
        $list = [];
        $lang = JSFactory::getLang(); 
        $rows = $this->select([
            'id',
            "`{$lang->get('name')}` as name",
            'field_id',
            'image'
        ], [], 'ORDER BY `ordering`');

        if (!empty($rows)) {
            if ($display == 0) {
                return $rows;
            } else {
                foreach($rows as $k => $row) {
                    if ($display == 1) {
                        $list[$row->id] = $row->name;
                    } else {
                        $list[$row->field_id][$row->id] = $row->name;
                    }
    
                    unset($rows[$k]);    
                }
            }
        }
        
        return $list;
    }

    public function getAllDetails(int $display = 0): array
    {
        $list = [];
        $lang = JSFactory::getLang();
        $rows = $this->select([
            'id',
            "`{$lang->get('name')}` as name",
            'field_id',
            'image'
        ], [], 'ORDER BY `ordering`');

        if (!empty($rows)) {
            if ($display == 0) {
                return $rows;
            } else {
                foreach($rows as $k => $row) {
                    if ($display == 1) {
                        $list[$row->id] = $row;
                    } else {
                        $list[$row->field_id][$row->id] = $row;
                    }

                    unset($rows[$k]);
                }
            }
        }

        return $list;
    }
}