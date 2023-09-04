<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelProductsExtraFieldsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products_extra_fields';

    public function getList(int $groupordering = 1)
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $ordering = 'F.ordering';

        if ($groupordering){
            $ordering = 'G.ordering, F.ordering';
        } 

        $query = "SELECT F.id, F.`{$lang->get('name')}` as name, F.`{$lang->get('description')}` as description, F.allcats, F.type, F.cats, F.ordering, F.`group`, G.`{$lang->get('name')}` as groupname, multilist 
            FROM `#__jshopping_products_extra_fields` as F 
            left join `#__jshopping_products_extra_field_groups` as G on G.id = F.group order by {$ordering}";
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $list = [];        
        foreach($rows as $v) {
            $list[$v->id] = $v;
            $list[$v->id]->cats = ($v->allcats) ? [] : unserialize($v->cats);
        }

        return $list;
    }
}