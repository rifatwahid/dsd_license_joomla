<?php

class JshoppingModelUpload extends JModelLegacy
{

	public function getParams()
	{
		$db = \JFactory::getDBO();
		$query = 'SELECT * FROM `#__jshopping_upload` WHERE `id` = 1';
		$db->setQuery($query);

		return $db->loadObject();
	}
	
	public function saveParams($params)
	{
		$db = \JFactory::getDBO();

		$objWithDataForUpdate = new stdClass();
		$objWithDataForUpdate->id = 1;
		$objWithDataForUpdate->allow_files_types = $params['allow_files_types'];
		$objWithDataForUpdate->allow_files_size = $params['allow_files_size'];
		$objWithDataForUpdate->is_allow_product_page = (int) $db->escape($params['is_allow_product_page']);
		$objWithDataForUpdate->is_allow_cart_page = (int) $db->escape($params['is_allow_cart_page']);
		$objWithDataForUpdate->upload_design = (int) $db->escape($params['upload_design']);
		$objWithDataForUpdate->order_status_for_upload = implode(',', $params['order_status_for_upload']);
		
		return $db->updateObject('#__jshopping_upload', $objWithDataForUpdate, 'id');
	}
	
}