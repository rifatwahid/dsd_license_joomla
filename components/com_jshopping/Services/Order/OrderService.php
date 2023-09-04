<?php 

class OrderService
{
    public function changeUserIdByOrderId(int $newUserId, int $orderId): ?jshopOrder
    {
        $result = null;
        
        if (!empty($newUserId) && !empty($orderId)) {
            $orderTable = JSFactory::getTable('Order');
            $orderTable->load($orderId);

            if (!empty($orderTable->order_id)) {
                $orderTable->user_id = $newUserId;
                $orderTable->store();
                
                $result = $orderTable;
            }
        }

        return $result;
    }
}