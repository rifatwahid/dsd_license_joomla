<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopOrderItem extends JTableAvto 
{

    public $order_item_id = null;
    public $order_id = null;
    public $product_id = null;
    public $product_ean = null;
    public $product_name = null;
    public $product_quantity = null;
    public $product_item_price = null;
    public $product_tax = null;
    public $product_attributes = null;
    public $files = null;
    public $weight = null;
    public $thumb_image = null;
    public $vendor_id = null;

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_order_item', 'order_item_id', $_db);
    }
	
	public function bind($src, $ignore = Array())
	{
		if (isset($src['order_item_id']) AND $src['order_item_id']>0) $old_src=$this->getLatestValue($src['order_item_id']);		
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
        
        $query = "SELECT * FROM `#__jshopping_order_item` WHERE order_item_id = '".$db->escape($id)."'";
        $db->setQuery($query);
        return get_object_vars($db->loadObject());
    }
}