<?php

class JshoppingModelStorage extends jshopBase
{    
	
	private function checkDeletePeriod($intdate,$period){
		$delete=false;
		switch ($period){
			case 1: 
				if ($intdate>7) $delete=true;break;
			case 2:
				if ($intdate>30) $delete=true;break;
			case 3:
				if ($intdate>180) $delete=true;break;
			case 4:
				if ($intdate>365) $delete=true;break;
		}
		return $delete;
	}
	
	private function getPlusDate($period){
		$delete=false;
		switch ($period){
			case 1: 
				return "-7 days";
			case 2:
				return "-1 month";
			case 3:
				return "-6 month";
			case 4:
				return "-1 year";
		}
		return $delete;
	}
	
	private function checkCheckPeriod(){
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$day_now = date("i");//$day_now=$jshopConfig->storage_delete_uploads_lastcheckday+1;
		if ($day_now==$jshopConfig->storage_delete_uploads_lastcheckday){
			return false;
		}else{
			$query = "UPDATE `#__jshopping_config` SET storage_delete_uploads_lastcheckday = '" . $db->escape($day_now) . "' WHERE id = '" . $db->escape($jshopConfig->load_id) . "'";
			$db->setQuery($query);
			$db->execute();			
			return true;
		}
	}
	
	private function getDeleteUploadsList() {	
		$jshopConfig = JSFactory::getConfig();
		$arr=array();
		if (($jshopConfig->storage_delete_uploads>0)AND($this->checkCheckPeriod())){
			$folder=$jshopConfig->files_upload_path."/";
			$dircontent = scandir($folder);
			$date_now = new DateTime("now");  
			$arr = array();
			foreach($dircontent as $filename) {
			if ($filename != '.' && $filename != '..') {
			  if (filemtime($folder.$filename) === false) return false;
			  $date = date("Y-m-d", filemtime($folder.$filename));//His
			  //$date = date("Y-m-09", filemtime($folder.$filename));//His
			  $interval = $date_now->diff(new DateTime($date));
			  $intdate=$interval->d+($interval->m*30)+($interval->y*365);
			  if ($this->checkDeletePeriod($intdate,$jshopConfig->storage_delete_uploads))
			  {
				$file['date']=$date;
				$file['interval']=$intdate;
				$file['file']=$folder.$filename;
				$arr[] = $file;		  
			  }	  
			}
			}
			if (!ksort($arr)) return false;
		}
		return $arr;	
	}

	private function deleteUploads($list){
		foreach ($list as $file){
			unlink($file['file']);
		}
	}
	
    public function checkDeleteUploads(){
		$list=$this->getDeleteUploadsList();$this->deleteUploads($list);
		$list=$this->getDeleteOffers();
		$list=$this->getDeleteDeliverynotes();$this->deleteUploads($list);
		if (isSmartEditorEnabled()) {
			$list=$this->getDeleteEditortemporaryfolder();$this->deleteUploads($list);
			$list=$this->getDeleteEditor_print_files();$this->deleteUploads($list);
		}
	}
	
	public function checkFilesForReorder(&$order){
		$jshopConfig = JSFactory::getConfig();
		$can_reorder=true;
		foreach ($order->items as $k=>$item){
			if (is_array($item->uploadData)) if (count($item->uploadData)>0){
				$can_reorder_item=true;
				foreach ($item->uploadData['files'] as $file){
					if (!file_exists($jshopConfig->files_upload_path."/".$file)) {						
						$can_reorder_item=false;
						$can_reorder=false;
					}
				}
				if ($can_reorder_item){
					$order->items[$k]->reorder=$can_reorder_item;
				}
				
			}			
		}
		$order->reorder=$can_reorder;
	}
	
	private function getDeleteOffers() {	
		$jshopConfig = JSFactory::getConfig();
		$arr=array();		
		if (($jshopConfig->storage_delete_offers>0)AND($this->checkCheckPeriod())){
			$db = \JFactory::getDBO();
			$date = date("Y-m-d H:i:s",strtotime($this->getPlusDate($jshopConfig->storage_delete_offers)));
			$query = "SELECT * FROM `#__jshopping_offer_and_order` WHERE `valid_to`<'".$date."'";
			$db->setQuery($query);
			$offers = $db->loadObjectList();
			foreach ($offers as $offer){
				$query = "DELETE FROM `#__jshopping_offer_and_order` WHERE `order_id` = " . $offer->order_id;
				$db->setQuery($query);
				$db->execute();
		
				$query = "SELECT * FROM `#__jshopping_offer_and_order_item` WHERE `order_id`=".$offer->order_id;
				$db->setQuery($query);
				$offer_items = $db->loadObjectList();
				
				$query = "DELETE FROM `#__jshopping_offer_and_order_item` WHERE `order_id` = " . $offer->order_id;
				$db->setQuery($query);
				$db->execute();
				
				foreach ($offer_items as $offer_item){
					if (file_exists($jshopConfig->pdf_orders_path."/".$offer->pdf_file)AND(trim($offer->pdf_file)<>"")){
						unlink($jshopConfig->pdf_orders_path."/".$offer->pdf_file);
					}
				}
			}
		}		
	}
	
	private function getDeleteDeliverynotes() {	
		$jshopConfig = JSFactory::getConfig();
		$arr=array();
		if (($jshopConfig->storage_delete_deliverynotes>0)AND($this->checkCheckPeriod())){
			$folder=$jshopConfig->pdf_orders_path."/delivery/";
			$dircontent = scandir($folder);
			$date_now = new DateTime("now");  
			$arr = array();
			foreach($dircontent as $filename) {
			if ($filename != '.' && $filename != '..') {
			  if (filemtime($folder.$filename) === false) return false;
			  $date = date("Y-m-d", filemtime($folder.$filename));//His
			  //$date = date("Y-m-09", filemtime($folder.$filename));//His
			  $interval = $date_now->diff(new DateTime($date));
			  $intdate=$interval->d+($interval->m*30)+($interval->y*365);
			  if ($this->checkDeletePeriod($intdate,$jshopConfig->storage_delete_deliverynotes))
			  {
				$file['date']=$date;
				$file['interval']=$intdate;
				$file['file']=$folder.$filename;
				$arr[] = $file;		  
			  }	  
			}
			}
			if (!ksort($arr)) return false;
		}
		return $arr;	
	}
	
	private function getDeleteEditortemporaryfolder(){
		$jshopConfig = JSFactory::getConfig();
		$arr=array();
		if (($jshopConfig->storage_delete_editor_temporary_folder>0)AND($this->checkCheckPeriod())){
			$folder=JPATH_ROOT."/components/com_expresseditor/html5/upload/temp/";
			$dircontent = scandir($folder);
			$date_now = new DateTime("now");  
			$arr = array();
			foreach($dircontent as $filename) {
			if ($filename != '.' && $filename != '..') {
			  if (filemtime($folder.$filename) === false) return false;
			  $date = date("Y-m-d", filemtime($folder.$filename));			  
			  $interval = $date_now->diff(new DateTime($date));
			  $intdate=$interval->d+($interval->m*30)+($interval->y*365);
			  if ($this->checkDeletePeriod($intdate,$jshopConfig->storage_delete_editor_temporary_folder))
			  {
				$file['date']=$date;
				$file['interval']=$intdate;
				$file['file']=$folder.$filename;
				$arr[] = $file;		  
			  }	  
			}
			}
			if (!ksort($arr)) return false;
		}
		return $arr;
	}
	
	private function getDeleteEditor_print_files(){
		$jshopConfig = JSFactory::getConfig();
		$arr=array();
		if (($jshopConfig->storage_delete_editor_print_files>0)AND($this->checkCheckPeriod())){
			$folder=JPATH_ROOT."/products/";
			$dircontent = scandir($folder);			
			$date_now = new DateTime("now");  
			$arr = array();
			foreach($dircontent as $filename) {
			if ($filename != '.' && $filename != '..') {				
			  if (filemtime($folder.$filename) === false) return false;
			  $date = date("Y-m-d", filemtime($folder.$filename));			  
			  $interval = $date_now->diff(new DateTime($date));
			  $intdate=$interval->d+($interval->m*30)+($interval->y*365);
			  if ($this->checkDeletePeriod($intdate,$jshopConfig->storage_delete_editor_print_files))
			  {
				$file['date']=$date;
				$file['interval']=$intdate;
				$file['file']=$folder.$filename;
				$arr[] = $file;		  
			  }	  
			}
			}
			if (!ksort($arr)) return false;
		}
		return $arr;
	}
	
	
}
