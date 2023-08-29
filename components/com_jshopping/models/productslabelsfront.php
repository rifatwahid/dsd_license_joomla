<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsLabelsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_product_labels';

    public function getAll()
    {
        $lang = JSFactory::getLang();
		$db = \JFactory::getDBO();
		$query = "SELECT id, `image_{$lang->lang}` as image, `{$lang->get('name')}` as name, `image` as img FROM `#__jshopping_product_labels` ORDER BY name";
		$db->setQuery($query);
        
        return $db->loadObjectList('id');
    }
}
