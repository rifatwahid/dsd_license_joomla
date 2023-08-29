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

class JshoppingModelOrderstatus extends JModelLegacy
{
	const TABLE_NAME = '#__jshopping_order_status';
        
	public function deleteOrderstatus($cid){
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {					
			if($_dbdelete->deleteItems("#__jshopping_order_status","status_id",$value))
                $text .= JText::_('COM_SMARTSHOP_ORDER_STATUS_DELETED')."<br>";
            else
                $text .= JText::_('COM_SMARTSHOP_ORDER_STATUS_ERROR_DELETED')."<br>";
		}
		return $text;
	}
	
	public function publishPayments($cid,$flag){		
		$_dbpublish = JSFactory::getModel('dbpublish');
		foreach ($cid as $key => $value) {
			$_dbpublish->setFlag("#__jshopping_payment_method","payment_id",$value,"payment_publish",$flag);			
		}
	}
	
	public function getListOrderStatusNames(){
		$_orders = JSFactory::getModel("orders");
		$_list_order_status = $_orders->getAllOrderStatus();
		$list_order_status = array();
		foreach($_list_order_status as $v){
            $list_order_status[$v->status_id] = $v->name;
        }
		return $list_order_status;
	}
	
	public function getListOrderStatus()
	{
		$_orders = JSFactory::getModel('orders');
		$orderStatuses = $_orders->getAllOrderStatus();
		$result = [];

		foreach($orderStatuses as $orderStatus) {
            $result[$orderStatus->status_id] = $orderStatus;
		}
		
		return $result;
	}	

	public function switchToDisableAllCancellationStatuses()
	{
		$db = \JFactory::getDBO();
		$updateSql = 'UPDATE `#__jshopping_order_status` SET `is_allowed_status_for_cancellation` = 0;';
		$db->setQuery($updateSql);

		return $db->execute();
	}

	public function switchToEnabledCancellationStatuses(array $ids)
	{
		$result = true;

		if (!empty($ids)) {
			$db = \JFactory::getDBO();
			$explodedIds = implode(',', $ids);
			$updateSql = 'UPDATE `#__jshopping_order_status` SET `is_allowed_status_for_cancellation` = 1 WHERE `status_id` IN (' . $explodedIds . ');';

			$db->setQuery($updateSql);
			$result = $db->execute();
		}

		return $result;
	}

	public function switchAllDefinedColumnsTo(array $columnsNames, $switchTo)
	{
		if (!empty($columnsNames) && isset($switchTo)) {
			$db = \JFactory::getDBO();

			$preparedColumnsNames = array_reduce($columnsNames, function($carry, $columnName) use ($db) {
				if (!empty($columnName)) {
					$carry[] = $db->qn($columnName);
				}

				return $carry;
			});

			$dataSet = array_reduce($preparedColumnsNames, function ($carry, $columnName) use($switchTo, $db) {
				if (!empty($columnName)) {
					$carry[] = $columnName . ' = ' . $db->q($switchTo);
				}

				return $carry;
			});

			if (!empty($dataSet)) {
				$updateSql = 'UPDATE ' . $db->qn(static::TABLE_NAME) . ' SET ' . implode(', ', $dataSet) . ';';
				$db->setQuery($updateSql);
				return $db->execute();
			}
		}

		return false;
	}

	public function switchAllDefinedColumnsByIdTo(array $columnsNamesWithIds, $switchTo)
	{
		if (!empty($columnsNamesWithIds) && isset($switchTo)) {
			$db = \JFactory::getDBO();

			foreach ($columnsNamesWithIds as $columnName => $ids) {
				if (!empty($columnName) && !empty($ids)) {
					$ids = array_map(function ($id) use ($db) {
						return $db->q($id);
					}, $ids);

					$sqlUpdate = 'UPDATE ' . $db->qn(static::TABLE_NAME) . ' SET ' . $db->qn($columnName) . ' = ' . $db->q($switchTo) . ' WHERE `status_id` IN (' . implode(',', $ids) . ')';
					$db->setQuery($sqlUpdate);
					$db->execute();
				}
			}
		}
	}

	public function getAllWitchSupport(?string $columnName) 
	{
		$result = [];

		if (!empty($columnName)) {
			$db = \JFactory::getDBO();

			$sqlSelect = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE ' . $db->qn($columnName) . ' = 1';
			$db->setQuery($sqlSelect);
			$result = $db->loadObjectList('status_id') ?: [];

			if (!empty($result)) {
				$result = $this->addColumnTranslate($result);
			}
		}

		return $result;
	}

	protected function addColumnTranslate(array $listOfData)
	{
		if (!empty($listOfData)) {
			$lang = JSFactory::getLang();
			
			foreach ($listOfData as $data) {
				$name = $lang->get('name');
				$data->name = $data->$name;
			}
		}

		return $listOfData;
	}
}
