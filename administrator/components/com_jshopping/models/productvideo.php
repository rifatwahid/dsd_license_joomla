<?php
/**
* @version      4.7.0 26.09.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

// TODO deprecated!!! Will be delete!!! Use media model!
class JshoppingModelProductvideo extends JModelLegacy
{
	public function deleteProductVideos($id){
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$product = JSFactory::getTable('product', 'jshop');
        $product->load($id);
		$images = $product->getVideos();
		if (count($videos)) {
			foreach ($videos as $video) {
				$query = "select count(*) as k from #__jshopping_products_videos where video_name='".$db->escape($video->video_name)."' and product_id!='".$db->escape($id)."'";                    
				$db->setQuery($query);
				if (!$db->loadResult()){
					@unlink($jshopConfig->video_product_path . "/" . $video->video_name);
					if ($video->video_preview){
						@unlink($jshopConfig->video_product_path . "/" . $video->video_preview);
					}
				}
			}
		}
		
		$query = "DELETE FROM `#__jshopping_products_videos` WHERE `product_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();
    }

	
	public function getVideosByVideoId($video_id){
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_products_videos` WHERE video_id = '" . $db->escape($video_id) . "'";
        $db->setQuery($query);        
        return $db->loadObject();
	}
	public function deleteVideosByVideoId($video_id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_products_videos` WHERE `video_id` = '" . $db->escape($video_id) . "'";
        $db->setQuery($query);
        $db->execute();
	}
	private function deleteVideoFiles($row){
		$jshopConfig = JSFactory::getConfig();
		$db = \JFactory::getDBO();
		$query = "select count(*) from #__jshopping_products_videos where video_name='".$db->escape($row->video_name)."' and product_id!='".$db->escape($row->product_id)."'";                    
        $db->setQuery($query);
        if (!$db->loadResult()){
            @unlink($jshopConfig->video_product_path . "/" . $row->video_name);
            if ($row->video_preview){
                @unlink($jshopConfig->video_product_path . "/" . $row->video_preview);
            }
        }
	}
	public function deleteVideoByVideoId($video_id){
        $jshopConfig = JSFactory::getConfig();
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_products_videos` WHERE video_id = '" . $db->escape($video_id) . "'";
        $db->setQuery($query);
        $row = $db->loadObject();
        
        $query = "select count(*) from #__jshopping_products_videos where video_name='".$db->escape($row->video_name)."' and product_id!='".$db->escape($row->product_id)."'";                    
        $db->setQuery($query);
        if (!$db->loadResult()){
            @unlink($jshopConfig->video_product_path . "/" . $row->video_name);
            if ($row->video_preview){
                @unlink($jshopConfig->video_product_path . "/" . $row->video_preview);
            }
        }

        $query = "DELETE FROM `#__jshopping_products_videos` WHERE `video_id` = '" . $db->escape($video_id) . "'";
        $db->setQuery($query);
        $db->execute();

	}
	
	public function deleteImageByImageId($video_id){		
        $db = \JFactory::getDBO();        
		$row = $this->getVideosByVideoId($video_id);
		$this->deleteVideoFiles($row);		
		$this->deleteVideosByVideoId($video_id);
	}
	
	public function getProductVideosList($_table_product){
		$edit = intval($_table_product->product_id);  
		if ($edit){
			$videos = $_table_product->getVideos();
		}else{
			$videos = array();
		}
		return $videos;
	}
	
	public function deleteFileByFileId($id,$type){
        $jshopConfig = JSFactory::getConfig();
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_products_files` WHERE `id` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
        $row = $db->loadObject();
        
        $delete_row = 0;
                
        if ($type=="demo"){
            if ($row->file==""){
                $query = "DELETE FROM `#__jshopping_products_files` WHERE `id` = '" . $db->escape($id) . "'";
                $db->setQuery($query);
                $db->execute();
                $delete_row = 1;
            }else{
                $query = "update `#__jshopping_products_files` set `demo`='' WHERE `id` = '" . $db->escape($id) . "'";
                $db->setQuery($query);
                $db->execute();
            }
            
            $query = "select count(*) as k from #__jshopping_products_files where demo='".$db->escape($row->demo)."'";
            $db->setQuery($query);
            if (!$db->loadResult()){
                @unlink($jshopConfig->demo_product_path."/".$row->demo);
            }
        }
        
        if ($type=="file"){
            if ($row->demo==""){
                $query = "DELETE FROM `#__jshopping_products_files` WHERE `id` = '" . $db->escape($id) . "'";
                $db->setQuery($query);
                $db->execute();
                $delete_row = 1;
            }else{
                $query = "update `#__jshopping_products_files` set `file`='' WHERE `id` = '" . $db->escape($id) . "'";
                $db->setQuery($query);
                $db->execute();
            }
            
            $query = "select count(*) as k from #__jshopping_products_files where file='".$db->escape($row->file)."'";
            $db->setQuery($query);
            if (!$db->loadResult()){
                @unlink($jshopConfig->files_product_path."/".$row->file);
            }
        }
        print $delete_row;
	}
}