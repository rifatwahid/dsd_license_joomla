<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelConfigStaticTextsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_config_statictext';

    public function getByAlias(string $alias)
    {
        $lang = JSFactory::getLang();
        $select = [
            '*',
            "`{$lang->get('text')}` as text"
        ];

        return $this->select($select, ["alias = '{$alias}'"], '', false);
    }

    public function getByIds(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $separatedIds = implode(', ', $ids);
        $lang = JSFactory::getLang();
        $select = [
            '*',
            "`{$lang->get('text')}` as text"
        ];

        return $this->select($select, ["id  IN ({$separatedIds})"], '');
    }
}
