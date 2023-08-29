<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelCurrenciesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_currencies';

    public function getAllCurrencies(int $publish = 1): array
	{        
        $select = ['currency_id', 'currency_name', 'currency_code', 'currency_code_iso', 'currency_value'];
        $where = $publish ? ["currency_publish = '1'"] : [];

        return $this->select($select, $where, 'ORDER BY currency_ordering');
	}
}