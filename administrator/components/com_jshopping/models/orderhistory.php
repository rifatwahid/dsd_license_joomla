<?php
/**
* @version      4.7.1 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelOrderhistory extends JModelLegacy{
        
	public function addHistory($order_id,$order_status,$notify,$comments){
		$order_history = JSFactory::getTable('orderHistory', 'jshop');
        $order_history->order_id = $order_id;
        $order_history->order_status_id = $order_status;
        $order_history->status_date_added = getJsDate();
        $order_history->customer_notify = $notify;
        $order_history->comments = $comments;
        $order_history->store();
	}
}
?>