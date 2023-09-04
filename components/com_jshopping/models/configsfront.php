<?php

class JshoppingModelConfigsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_config';

    public function getNextOrderNumber(int $configId)
    {
        return $this->select(['next_order_number'], ['id = ' . $configId], '', false)->next_order_number;
    }  
    
    public function updateNextOrderNumber(): bool
    {
        $db = \JFactory::getDBO();
        $query = 'update `#__jshopping_config` set next_order_number=next_order_number + 1';
        $db->setQuery($query);
        return (bool)$db->execute();
    }
	
	public function updateNextInvoiceNumber(int $num = 0): bool
    {
        $db = \JFactory::getDBO();
		if(!$num){
			$query = 'update `#__jshopping_config` set next_invoice_number=next_invoice_number + 1';
		}else{
			$num++;
			$query = 'update `#__jshopping_config` set next_invoice_number='.$num ;
		}
        $db->setQuery($query);
        return (bool)$db->execute();
    }
	
	
	public function updateNextRefundNumber(int $num = 0): bool
    {
        $db = \JFactory::getDBO();
		if(!$num){
			$query = 'update `#__jshopping_config` set next_refund_number=next_refund_number + 1';
		}else{
			$query = 'update `#__jshopping_config` set next_refund_number='.$num + 1;
		}
        $db->setQuery($query);
        return (bool)$db->execute();
    }
}