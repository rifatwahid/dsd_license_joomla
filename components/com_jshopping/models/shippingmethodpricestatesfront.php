<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelShippingMethodPriceStatesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_shipping_method_price_states';

    public function getAll(int $shPrMethodId)
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT sh_state.state_id, states.`".$lang->get('name')."` as name
                  FROM `" . static::TABLE_NAME . "` AS sh_state
                  INNER JOIN `#__jshopping_states` AS states ON states.state_id = sh_state.state_id
                  WHERE sh_state.sh_pr_method_id = '" . $db->escape($shPrMethodId) . "'";
        $db->setQuery($query);       
         
        return $db->loadObjectList();
    }
}