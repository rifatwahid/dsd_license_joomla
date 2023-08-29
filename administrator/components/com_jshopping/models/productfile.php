<?php
/**
* @version      4.7.0 26.09.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelProductfile extends JModelLegacy
{
	public function deleteProductFiles($id){
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$product = JSFactory::getTable('product', 'jshop');
        $product->load($id);
		$files = $product->getFiles();
		 if (count($files)){
			foreach($files as $file){
				$query = "select count(*) as k from #__jshopping_products_files where demo='".$db->escape($file->demo)."' and product_id!='".$db->escape($id)."'";
				$db->setQuery($query);
				if (!$db->loadResult()){
					@unlink($jshopConfig->demo_product_path."/".$file->demo);
				}
				
				$query = "select count(*) as k from #__jshopping_products_files where file='".$db->escape($file->file)."' and product_id!='".$db->escape($id)."'";
				$db->setQuery($query);
				if (!$db->loadResult()){
					@unlink($jshopConfig->files_product_path."/".$file->file);
				}            
			}
		}
		
		$query = "DELETE FROM `#__jshopping_products_files` WHERE `product_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();
	}

	
	private function getFilesByFileId($id){
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_products_files` WHERE `id` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
        return $db->loadObject();
	}
	
	public function deleteFileByFileId($id,$type){
		$jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $row = $this->getFilesByFileId($id);
        
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
	
	public function getProductFilesList($_table_product){
		$edit = intval($_table_product->product_id);
		if ($edit){
			$files = $_table_product->getFiles();
		}else{
			$files = array();
		}
		return $files;
	}

    public function deleteProductFilesFromDbByProductId(int $productId): bool
    {
        $isSuccess = true;

        if (!empty($productId)) {
            $db = Factory::getDbo();
            $queryDelete = "DELETE FROM `#__jshopping_products_files` WHERE `product_id` = '" . $db->escape($productId) . "'";
            $db->setQuery($queryDelete);

            $isSuccess = $db->execute();
        }

        return $isSuccess;
    }
}