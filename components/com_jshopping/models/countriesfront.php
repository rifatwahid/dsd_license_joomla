<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelCountriesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_countries';

    public function generateCountriesSelectMarkup(int $selectedCountry = 0, int $selectedDCountry = 0)
    {
        $tableOfCountry = JTable::getInstance('country', 'jshop');
        $listOfCountries = $tableOfCountry->getAllCountries();

        $result = new stdClass();
        $result->selectCountries = '';
        $result->selectDCountries = '';

        if (!empty($listOfCountries)) {
            $option_country[] = JHTML::_('select.option',  '0', JText::_('COM_SMARTSHOP_REG_SELECT'), 'country_id', 'name' );
            $option_countries = $listOfCountries;//array_merge($option_country, $listOfCountries);

            $result->selectCountries = JHTML::_('select.genericlist', $option_countries, 'country', 'class = "inputbox form-select" size = "1"','country_id', 'name', $selectedCountry);
            $result->selectDCountries = JHTML::_('select.genericlist', $option_countries, 'd_country', 'class = "inputbox form-select" size = "1"','country_id', 'name', $selectedDCountry);
            $result->selectCountriesCart = JHTML::_('select.genericlist', $option_countries, 'country', ' class = "inputbox country_cart form-select" size = "1"','country_id', 'name', $selectedCountry);
        }

        return $result;
    }

    public function getAllCountries(int $publish = 1): array
    {
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $ordering = ($jshopConfig->sorting_country_in_alphabet) ? 'name' : 'ordering';

        $select = ['country_id', '`' . $lang->get('name') . '` as name '];
        $where = ($publish) ? ["country_publish = '1'"] : [];
        
        return $this->select($select, $where, 'ORDER BY ' . $ordering);
    }

    public function getById(int $countryId): ?object
    {
        $countryData = $this->select(['*'], ["country_id = {$countryId}"], '', false) ?: new stdClass;

        if (isset($countryData->country_id)) {
            $langTag = JFactory::getLanguage()->getTag();
            $propertyName = 'name_' . $langTag;

            $countryData->name = $countryData->$propertyName;
        }

        return $countryData;
    }
}