<?php

class JshoppingModelConfigDisplayPricesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_config_display_prices';

    public function getList(): array
    {
        $list = $this->select(['*']);

        foreach($list as $k => $v) {
            $list[$k]->countries = unserialize($v->zones);
        }

        return $list;
    }
}