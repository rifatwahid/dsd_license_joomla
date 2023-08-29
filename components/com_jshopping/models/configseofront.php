<?php

class JshoppingModelConfigSeoFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_config_seo';

    public function getByAlias(string $alias)
    {
        $lang = JSFactory::getLang();
        $select = [
            '`id`',
            '`alias`',
            "`{$lang->get('title')}` as title",
            "`{$lang->get('keyword')}` as keyword",
            "`{$lang->get('description')}` as description",
        ];

        return $this->select($select, ["`alias` = '{".addcslashes($alias,"'")."}'"], '', false);
    }
}
