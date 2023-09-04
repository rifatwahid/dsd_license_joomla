<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopOrderHistory extends JTableAvto {
    
    var $order_history_id = null;
    var $order_id = null;
    var $order_status_id = null;
    var $status_date_added = null;
    var $customer_notify = null;
    var $comments = null;

    function __construct( &$_db ){
        parent::__construct( '#__jshopping_order_history', 'order_history_id', $_db );
    }

	public function bind($src, $ignore = Array())
	{
		if (isset($src['order_history_id']) AND $src['order_history_id']>0) $old_src=$this->getLatestValue($src['order_history_id']);		
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
        
        $query = "SELECT * FROM `#__jshopping_order_history` WHERE order_history_id = '".$db->escape($id)."'";
        $db->setQuery($query);
        return get_object_vars($db->loadObject());
    }
}
?>