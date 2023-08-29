<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelOrdersHistoryFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_order_history';

    public function getHistoryByOrderId(int $orderId): array
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT history.*, status.*, status.`" . $lang->get('name') . "` as status_name  FROM `" . static::TABLE_NAME . "` AS history
                  INNER JOIN `#__jshopping_order_status` AS status ON history.order_status_id = status.status_id
                  WHERE history.order_id = '" . $db->escape($orderId) . "'
                  ORDER BY history.status_date_added";
        $db->setQuery($query);

        $list = $db->loadObjectList() ?: [];

        if(!empty($list)){
            foreach($list as $k=>$history){
                $list[$k]->formatdate = formatdate($history->status_date_added, 0);
            }
        }
        return $list;
    }

    public function getMaxStatusDateAddedByOrderId(int $orderId)
    {
        $maxDate = $this->select(['max(status_date_added) as maxDate'], ['order_id = \'' . $orderId . '\''], '', false)->maxDate ?: '';
        return strtotime($maxDate);
    }
}