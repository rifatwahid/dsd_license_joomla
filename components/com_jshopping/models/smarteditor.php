<?php
/**
 * @version      7.4.0
 * @author       
 * @package      Smarteditor
 * @copyright    Copyright (C) 2010. All rights reserved.
 * @license      GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

class jshopSmarteditor 
{
    public function db_table_exist($name) 
    {
        $db = \JFactory::getDBO();
        $table_name = str_replace('#__', $db->getPrefix(), $name);
        $query = "SHOW TABLES LIKE '" . $table_name . "'";
        $db->setQuery($query);
        $list = $db->loadObjectList();
        
        return (count($list) > 0);
    }

    public function copyProductToNew($product_id, $params = [], $price = 0) 
    {
        $jshopConfig = JSFactory::getConfig();
        $xmlid = $params['xmlid'];
        $db = \JFactory::getDBO();
        $_lang = JTable::getInstance('language', 'jshop');
        $languages = $_lang->getAllLanguages(1);
        $tables = array('attr', 'attr2', 'prices', 'min_price', 'prices_group', 'editor_id', 'relations','relations2', 'videos', 'files', 'free_attr', 'sna','aantal_table','prices_width_height');

        $product = JTable::getInstance('product', 'jshop');
        $product->load($product_id);
        $product->product_id = null;
        foreach ($languages as $lang) {
            $name_alias = 'alias_' . $lang->language;
            $product->$name_alias = '';
        }
        $product->product_date_added = date('Y-m-d H:i:s');
        $product->date_modify = date('Y-m-d H:i:s');
        $product->average_rating = 0;
        $product->reviews_count = 0;
        $product->hits = 0;
		$product->product_created_type = 1;
        if ($price > 0) {
            $product->product_price = $price / $jshopConfig->currency_value;
        }
        $product->store();

        $new_product_id = $product->product_id;
		
		
		
		
		//NEW		
		$db->setQuery("UPDATE `#__jshopping_products` SET xml='".$params['params']->xml_id."' WHERE product_id=".$new_product_id);$db->execute();
		$db->setQuery("UPDATE `#__jshopping_products` SET pr_id=".(int)$params['params']->pr_id." WHERE product_id=".$new_product_id);$db->execute();
		$db->setQuery("UPDATE `#__jshopping_products` SET editor_id='".$params['params']->editor_id."' WHERE product_id=".$new_product_id);$db->execute();
		$db->setQuery("UPDATE `#__jshopping_products` SET product_type=".(int)$params['params']->product_type." WHERE product_id=".$new_product_id);$db->execute();
		//NEW
		
		$db->setQuery("UPDATE `#__jshopping_products` SET jsproduct_with_editor_for_image='".$_GET['xmlname']."' WHERE product_id=".$new_product_id);$db->execute();
		$db->setQuery("UPDATE `#__jshopping_products` SET parrams='".json_encode($params['params'])."' WHERE product_id=".$new_product_id);$db->execute();
		//Konfigurator in HTML 5 task 2375
		$db->setQuery("UPDATE `#__jshopping_products` SET created_in_editor=NOW() WHERE product_id=".$new_product_id);
        $db->execute();
		//////////////////////////////////

        $array = array();
		$wh="";
        if(isset($params['params']->sna)){
            foreach (get_object_vars($params['params']->sna) as $key=>$v){
                $wh.=" prod_table.sna_id=".$key." AND prod_table.id=".$v." AND ";
            }
        }
		
        foreach ($tables as $table) {
            if ($this->db_table_exist("#__jshopping_products_" . $table)) {
				if ($table=='sna'){
					$query = "SELECT * FROM `#__jshopping_products_" . $table . "` AS prod_table WHERE ".$wh." prod_table.product_id = '" . $db->escape($product_id) . "'";
					$db->setQuery($query);
					$array[$table] = $db->loadAssocList();				
					
				}ELSE{   
					$query = "SELECT * FROM `#__jshopping_products_" . $table . "` AS prod_table WHERE prod_table.product_id = '" . $db->escape($product_id) . "'";
					$db->setQuery($query);
					$array[$table] = $db->loadAssocList();
				}
            }
        }
		/*
		foreach ($array['attr'] as $key=>$val){
			$array['attr'][$key]['template_id']="";				
			
		}
		*/
		
		foreach ($array['attr2'] as $key=>$val){
			if ($val['attr_id']==9999){
				unset($array['attr2'][$key]);				
			}
		}

		foreach ($array as $table_xname => $value2) {
            if (count($value2)) {
                foreach ($value2 as $key3 => $value3) {
                    $db->setQuery($this->copyProductBuildQuery($table_xname, $value3, $new_product_id));
                    $db->execute();                
				
					if ($table_xname=='sna'){	
						$fl=$value3['sna_id'];
						if ($params['params']->sna->$fl<>""){
						$params['params']->sna->$fl=$db->insertid(); 					
						}					
					}				
				}
            }
        }
       // $query = "update #__jshopping_products_attr set ext_attribute_product_id=0 where product_id='" . $new_product_id . "'";
       // $db->setQuery($query);
        //$db->execute();

        $query = 'INSERT INTO #__jshopping_products_to_categories (`product_id`, `category_id`) VALUES (' . $new_product_id . ', 1)';
        $db->setQuery($query);
        $db->execute();
        if ($xmlid) {
            include_once(JPATH_ROOT . "/components/com_expresseditor/SimpleImage.php");
            include_once(JPATH_ROOT . "/components/com_expresseditor/engine/jshopping/helper.php");


            $imagename = EngineHelper::getProductImageBaseURI() .$xmlid . '.jpg';
            $productn = JTable::getInstance('product', 'jshop');

            $productn->load($new_product_id);

            $productn->set('image',$imagename);
            $productn->set('product_thumb_image',$imagename);
            $productn->set('product_name_image',$imagename);
            $productn->set('product_full_image','full_' . $imagename);
            $productn->set('xml',$xmlid);
            $productn->store();

            $image = JTable::getInstance('productsMedia', 'jshop');
            $image->set("product_id", 'thumb_' . $imagename);
            $image->set("media_src", $imagename);
            $image->set("media_preview", $imagename);
            $image->set("media_src_abstract_type", 5);
            $image->set("media_preview_abstract_type", 5);
            $image->set("media_abstract_type", 1);
            $image->set("is_main", 1);
            $image->store();
        }

        return $product->product_id;
    }

    public function copyProductBuildQuery($table, $array, $product_id) 
    {
        $db = \JFactory::getDBO();
        $query = "INSERT INTO `#__jshopping_products_" . $table . "` SET ";
        $array_keys = array('image_id', 'price_id', 'review_id', 'video_id', 'product_attr_id', 'value_id', 'id');
        foreach ($array as $key => $value) {
            if (in_array($key, $array_keys))
                continue;
            if ($key == 'product_id')
                $value = $product_id;
            if ($key == 'old_sna_id')
                $value = $array['id'];
            $query .= "`" . $key . "` = '" . $db->escape($value) . "', ";
        }

        return $query = substr($query, 0, strlen($query) - 2);
    }

    public function getFileEditorFreeAttrId()
    {
        $db = \JFactory::getDBO();
        $query = "SELECT  id  FROM `#__jshopping_free_attr` WHERE type_for_editor = '3'";
		$db->setQuery($query);

        return $db->loadResult();
    }
}