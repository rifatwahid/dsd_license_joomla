<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelDeliveryTimesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_delivery_times';

    public function getByCart(jshopCart $cart, ?jshopConfig $jshopConfig): array
    {
        if (empty($jshopConfig)) {
            $jshopConfig = JSFactory::getConfig();
        }

        $sh_mt_pr = JTable::getInstance('shippingMethodPrice', 'jshop');
        $sh_mt_pr->load($cart->getShippingPrId());
        $delivery_time = '';
        $delivery_date = '';

        if ($jshopConfig->show_delivery_time_checkout && $cart->getShippingPrId()) {
            $deliverytimes = JSFactory::getAllDeliveryTime();
            $delivery_time = $deliverytimes[$sh_mt_pr->delivery_times_id];

            if (!$delivery_time && $jshopConfig->delivery_order_depends_delivery_product) {
                $delivery_time = $cart->getDelivery();
            }
        }

        if ($jshopConfig->show_delivery_date) {
            $delivery_date = $cart->getDeliveryDate();

            if ($delivery_date){
                $delivery_date = formatdate($cart->getDeliveryDate());
            }
        }
        
        return [
            'delivery_time' => $delivery_time, 
            'delivery_date' => $delivery_date
        ];
    }

    public function getAllTimes(): array
    {
        $lang = JSFactory::getLang();
        $select = ['id', '`' . $lang->get('name') . '` as name', 'days'];

        return $this->select($select, [], 'ORDER BY name');
    }
}