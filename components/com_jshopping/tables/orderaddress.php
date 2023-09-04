<?php

defined('_JEXEC') or die('Restricted access');

class jshopOrderAddress extends JTableAvto
{
    const TABLE_NAME = '#__jshopping_order_addresses';

    public $excludedCols = [
        'id'
    ];

    public function __construct(&$_db)
    {
        parent::__construct(static::TABLE_NAME, 'id', $_db);
    }

	public function bind($src, $ignore = Array())
	{
		if (isset($src['id']) AND $src['id']>0) $old_src=$this->getLatestValue($src['id']);		
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					if (isset($old_src[$key])) {$src[$key]=$old_src[$key];}else{$src[$key]="";}
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					if (isset($old_src[$key])) {$src[$key]=$old_src[$key];}else{$src[$key]=0;}
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}
	
	public function getLatestValue($id)
    {
		$db = \JFactory::getDBO();
        $dispatcher = \JFactory::getApplication();
        
        $query = "SELECT * FROM `#__jshopping_order_addresses` WHERE id = '".$db->escape($id)."'";
        $db->setQuery($query);
        return get_object_vars($db->loadObject());
    }
	
    public function bindShippingAndBillingAddresses(array $shippingDataAddress, array $billingDataAddress): bool
    {
        if (!empty($shippingDataAddress) || !empty($billingDataAddress)) {
            $tableColumns = $this->getTableColumns($this->excludedCols);
            $addressesData = [
                'shipping' => $shippingDataAddress ?: [],
                'billing' => $billingDataAddress ?: []
            ];
            $excluded = [
                'd_user_id'
            ];

            foreach ($addressesData as $type => $addresses) {
                if (!empty($addresses)) {
                    foreach ($addresses as $columnName => $addressInfo) {
                        if ( array_key_exists($columnName, $tableColumns)) {    

                            $colName = ($type == 'billing') ? $columnName : ('d_' . $columnName);

                            if (!in_array($colName, $excluded)) {
                                $this->$colName = $addressInfo;
                            }

                        }
                    }
                }
            }

            return true;
        }

        return false;
    }
}