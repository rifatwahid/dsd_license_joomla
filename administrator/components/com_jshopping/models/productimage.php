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
class JshoppingModelProductimage extends JModelLegacy
{
	public function deleteProductImages($id){
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$product = JSFactory::getTable('product', 'jshop');
        $product->load($id);
		$images = $product->getImages();
		if (count($images)){
			foreach($images as $image){
				$query = "select count(*) as k from #__jshopping_products_images where image_name='".$db->escape($image->image_name)."' and product_id!='".$db->escape($id)."'";                    
				$db->setQuery($query);
				if (!$db->loadResult()){
					@unlink(getPatchProductImage($image->image_name,'thumb',2));
					@unlink(getPatchProductImage($image->image_name,'',2));
					@unlink(getPatchProductImage($image->image_name,'full',2));
				}
			}
		}
		$query = "DELETE FROM `#__jshopping_products_images` WHERE `product_id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		$db->execute();
	}
	
	private function getImagesByImageId($image_id){
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_products_images` WHERE image_id = '".$db->escape($image_id)."'";
        $db->setQuery($query);
        return $db->loadObject();
	}
	private function deleteImagesByImageId($image_id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_products_images` WHERE `image_id` = '".$db->escape($image_id)."'";
        $db->setQuery($query);
        $db->execute();
	}
	private function deleteImagesFiles($row){
		$db = \JFactory::getDBO();
		$query = "select count(*) as k from #__jshopping_products_images where image_name='".$db->escape($row->image_name)."' and product_id!='".$db->escape($row->product_id)."'";
        $db->setQuery($query);
        if (!$db->loadResult()){        
            @unlink(getPatchProductImage($row->image_name,'thumb',2));
            @unlink(getPatchProductImage($row->image_name,'',2));
            @unlink(getPatchProductImage($row->image_name,'full',2));
        }
	}
	
	public function deleteImageByImageId($image_id){
        $db = \JFactory::getDBO();		
		
        $row = $this->getImagesByImageId($image_id);
		$this->deleteImagesByImageId($image_id);
        
		$this->deleteImagesFiles($row);
        
        $product = JSFactory::getTable('product', 'jshop');
        $product->load($row->product_id);
        if ($product->image==$row->image_name){
            $product->image = '';
            $list_images = $product->getImages();
            if (count($list_images)){
                $product->image = $list_images[0]->image_name;
            } 
            $product->store();
        }
	}
	
	public function getProductImagesList($_table_product){
		$edit = intval($_table_product->product_id);  
		if ($edit){
			$images = $_table_product->getImages();
		}else{
			$images = array();
		}
		return $images;
	}
	
	public function productSave_setPostImage(&$product, &$post){
		if (isset($post['set_main_image'])) {
            $image= JSFactory::getTable('image', 'jshop');
            $image->load($post['set_main_image']);
            if ($image->image_id){
                $product->image = $image->image_name;
            }
        }
	}
}