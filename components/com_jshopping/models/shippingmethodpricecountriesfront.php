<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelShippingMethodPriceCountriesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_shipping_method_price_countries';

    public function isCorrectMethodForCountry($id_country, $shippingPriceMethodId) 
    {
        $where = [
            "`country_id` = '{$id_country}'",
            "`sh_pr_method_id` = '{$shippingPriceMethodId}'",
        ];
        $shippingMethodCountryId = $this->select(['`sh_method_country_id`'], $where, '', false);

        return !empty($shippingMethodCountryId->sh_method_country_id) ? 1 : 0;
    }

    public function getAll(int $shPrMethodId)
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT sh_country.country_id, countries.`".$lang->get('name')."` as name
                  FROM `" . static::TABLE_NAME . "` AS sh_country
                  INNER JOIN `#__jshopping_countries` AS countries ON countries.country_id = sh_country.country_id
                  WHERE sh_country.sh_pr_method_id = '" . $db->escape($shPrMethodId) . "'";
        $db->setQuery($query);       
         
        return $db->loadObjectList();
    }
}