<?php

defined('_JEXEC') or die('Restricted access');

class jshopOrderItemNativeUploadsFiles extends JTableAvto 
{
    const TABLE_COLUMNS_NAMES = [
        'order_id',
        'order_item_id', 
        'qty', 
        'file', 
        'preview',
        'description'
    ];

    function __construct(&$_db)
    {
        parent::__construct('#__jshopping_order_items_native_uploads_files', 'id', $_db);
        JPluginHelper::importPlugin('jshoppingcheckout');
        JPluginHelper::importPlugin('jshoppingorder');
    }
	
	public function bind($src, $ignore = Array())
	{
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					$src[$key]=0;
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}
}