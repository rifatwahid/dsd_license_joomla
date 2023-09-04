<?php
/**
* @version      3.12.0 10.11.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelConfig_storage extends JModelLegacy{
    	
	public function getDeleteUploadsStatuses(){
		$statuses=array("never","after 1 week","after 1 month","after 6 months","after 1 year");
		return $statuses;
	}
	
	public function getDeleteUploadsStatuses_select(){
		$jshopConfig = JSFactory::getConfig();
		$upload_statuses=$this->getDeleteUploadsStatuses();		
        return JHTML::_('select.genericlist', $upload_statuses,'storage_delete_uploads','class = "inputbox form-select" size = "1" onchange="shopConfig.storageDeleteUploadsAlert(this.id,`'.JText::_('COM_SMARTSHOP_DELETE_UPLOADS_ALERT_MESSAGE').'`)"','status_id','name', $jshopConfig->storage_delete_uploads);
	}
	
	public function getSelect_VendorOrderMessageType(){
		$jshopConfig = JSFactory::getConfig();
		$_options_array = JSFactory::getModel("options_array");
		$vendor_order_message_type=$_options_array->getVendorOrderMessageType();
        return JHTML::_('select.genericlist', $vendor_order_message_type, 'vendor_order_message_type','class = "inputbox form-select" size = "1"','id','name', $jshopConfig->vendor_order_message_type);
	}
	
	public function getDeleteOffersStatuses_select(){
		$jshopConfig = JSFactory::getConfig();
		$upload_statuses=$this->getDeleteUploadsStatuses();		
        return JHTML::_('select.genericlist', $upload_statuses,'storage_delete_offers','class = "inputbox form-select" size = "1" onchange="shopConfig.storageDeleteUploadsAlert(this.id,`'.JText::_('COM_SMARTSHOP_DELETE_UPLOADS_ALERT_MESSAGE').'`)"','status_id','name', $jshopConfig->storage_delete_offers);
	}
	
	public function getDeleteDeliverynotesStatuses_select(){
		$jshopConfig = JSFactory::getConfig();
		$upload_statuses=$this->getDeleteUploadsStatuses();		
        return JHTML::_('select.genericlist', $upload_statuses,'storage_delete_deliverynotes','class = "inputbox form-select" size = "1" onchange="shopConfig.storageDeleteUploadsAlert(this.id,`'.JText::_('COM_SMARTSHOP_DELETE_UPLOADS_ALERT_MESSAGE').'`)"','status_id','name', $jshopConfig->storage_delete_deliverynotes);
	}
	
	public function getDeleteEditorTemporaryFolderStatuses_select(){
		$jshopConfig = JSFactory::getConfig();
		$upload_statuses=$this->getDeleteUploadsStatuses();		
        return JHTML::_('select.genericlist', $upload_statuses,'storage_delete_editor_temporary_folder','class = "inputbox form-select" size = "1" onchange="shopConfig.storageDeleteUploadsAlert(this.id,`'.JText::_('COM_SMARTSHOP_DELETE_UPLOADS_ALERT_MESSAGE').'`)"','status_id','name', $jshopConfig->storage_delete_editor_temporary_folder);
	}

	public function getDeleteEditorPrintFilesStatuses_select(){
		$jshopConfig = JSFactory::getConfig();
		$upload_statuses=$this->getDeleteUploadsStatuses();		
        return JHTML::_('select.genericlist', $upload_statuses,'storage_delete_editor_print_files','class = "inputbox form-select" size = "1" onchange="shopConfig.storageDeleteUploadsAlert(this.id,`'.JText::_('COM_SMARTSHOP_DELETE_UPLOADS_ALERT_MESSAGE').'`)"','status_id','name', $jshopConfig->storage_delete_editor_print_files);
	}

}
?>