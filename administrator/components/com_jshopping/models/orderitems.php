<?php 

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelOrderItems extends JModelLegacy
{
	/**
	*	@return array
	*/
	public function getByOrderId($orderId)
	{
		$result = [];

		if ( !empty($orderId) ) {
			$db = \JFactory::getDBO();
			$sqlSelectOrderItems = $db->getQuery(true);

			$sqlSelectOrderItems->select('*')
								->from('#__jshopping_order_item')
								->where('order_id = ' . $db->escape($orderId));

			$result = $db->setQuery($sqlSelectOrderItems)->loadObjectList();
		}

		return $result;
	}

}