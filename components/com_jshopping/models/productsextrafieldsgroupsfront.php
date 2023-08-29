<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsExtraFieldsGroupsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_extra_field_groups';

    public function getList()
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang(); 
        $query = "SELECT id, `{$lang->get('name')}` as name, ordering FROM `#__jshopping_products_extra_field_groups` order by ordering";
        $db->setQuery($query);
        
        return $db->loadObjectList();
    }
}