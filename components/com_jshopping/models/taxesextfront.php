<?php

class JshoppingModelTaxesExtFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_taxes_ext';

    public function getExt(?int $tax_id = 0)
    {
        $where = !empty($tax_id) ? ["`tax_id` = '{$tax_id}'"] : [];
        $extTaxes = $this->select(['*'], $where);

        if (!empty($extTaxes)) {
            foreach($extTaxes as $k => $extTax) {
                $extTaxes[$k]->countries = unserialize($extTax->zones);
            }
        }

        return $extTaxes;
    }
}
