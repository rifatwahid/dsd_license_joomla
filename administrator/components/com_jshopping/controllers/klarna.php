<?php
/**
* @version      3.5.1 25.06.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerKlarna extends JControllerLegacy{

	protected $canDo;    
	
    function __construct( $config = array() ){
        parent::__construct( $config );
        checkAccessController("orders");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);        
        addSubmenu("orders",$this->canDo);
    }

    function display(){
        $this->setRedirect("index.php?option=com_jshopping&controller=orders", '');
    }
	
	function actklinv(){
		$order_id = JFactory::getApplication()->input->getInt("order_id");
		$order = JTable::getInstance('order', 'jshop');
		$order->load($order_id);

		include_once(JPATH_COMPONENT_SITE."/payments/payment.php");
		include_once(JPATH_COMPONENT_SITE."/payments/pm_klarna/pm_klarna.php");
		$pm_klarna = new pm_klarna();        

		$params = unserialize($order->payment_params_data);

		$configKlarna = $pm_klarna->getKlarnaConfig();
		$klarna = new Klarna();
		$klarna->setConfig($configKlarna);

		try {
			$invURL = $klarna->activateInvoice($order->transaction);
			$order->klarna_invoice_url = $invURL;
			$order->store();
			$this->setRedirect("index.php?option=com_jshopping&controller=orders");
		}
		catch(Exception $e) {
			$error = utf8_decode($e->getMessage() . " (#" . $e->getCode() . ")");
			$this->setRedirect("index.php?option=com_jshopping&controller=orders", $error);
		}
	}
	
	function emailklinv() {
		$order_id = JFactory::getApplication()->input->getInt("order_id");
		$order = JTable::getInstance('order', 'jshop');
		$order->load($order_id);

		include_once(JPATH_COMPONENT_SITE."/payments/payment.php");
		include_once(JPATH_COMPONENT_SITE."/payments/pm_klarna/pm_klarna.php");
		$pm_klarna = new pm_klarna();        

		$params = unserialize($order->payment_params_data);

		$configKlarna = $pm_klarna->getKlarnaConfig();
		$klarna = new Klarna();
		$klarna->setConfig($configKlarna);

		try {
			$result = $klarna->emailInvoice($order->transaction);
			$this->setRedirect("index.php?option=com_jshopping&controller=orders", "Klarna order ".$result.": email sended.");
		}
		catch(Exception $e) {
			$error = utf8_decode($e->getMessage() . " (#" . $e->getCode() . ")");
			$this->setRedirect("index.php?option=com_jshopping&controller=orders", $error);
		}
	}
}
?>