<?php
/**
* @version      4.7.0 05.11.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelCategories extends JModelLegacy{
    
    public function getAllList($display=0){
        $db = \JFactory::getDBO();        
        $lang = JSFactory::getLang();
        if (isset($order) && $order=="id") $orderby = "`category_id`";
        if (isset($order) && $order=="name") $orderby = "`".$lang->get('name')."`";
        if (isset($order) && $order=="ordering") $orderby = "ordering";
        if (isset($orderby) && !$orderby) $orderby = "ordering";
        $query = "SELECT `".$lang->get('name')."` as name, category_id FROM `#__jshopping_categories` ORDER BY ordering";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);        
        $list = $db->loadObjectList();
        if ($display==1){
            $rows = array();
            foreach($list as $k=>$v){
                $rows[$v->category_id] = $v->name;    
            }
            unset($list);
            $list = $rows;
        }
        return $list;
    }
    
    public function getSubCategories($parentId, $order = 'id', $ordering = 'asc') {
        $db = \JFactory::getDBO();        
        $lang = JSFactory::getLang();
        if ($order=="id") $orderby = "`category_id`";
        if ($order=="name") $orderby = "`".$lang->get('name')."`";
        if ($order=="ordering") $orderby = "ordering";
        if (!$orderby) $orderby = "ordering";
        $query = "SELECT `".$lang->get('name')."` as name,`".$lang->get('short_description')."` as short_description, category_id, category_publish, ordering, category_image FROM `#__jshopping_categories`
                   WHERE category_parent_id = '".$db->escape($parentId)."'
                   ORDER BY ".$orderby." ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);        
        return $db->loadObjectList();
    }
    
    public function getAllCatCountSubCat() {
        $db = \JFactory::getDBO();        
        $query = "SELECT C.category_id, count(C.category_id) as k FROM `#__jshopping_categories` as C
                   inner join  `#__jshopping_categories` as SC on C.category_id=SC.category_parent_id
                   group by C.category_id";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $list = $db->loadObjectList();
        $rows = array();
        foreach($list as $row){
            $rows[$row->category_id] = $row->k;
        }        
        return $rows;
    }
    
    public function getAllCatCountProducts(){
        $db = \JFactory::getDBO();    
        $query = "SELECT category_id, count(product_id) as k FROM `#__jshopping_products_to_categories` group by category_id";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $list = $db->loadObjectList();
        $rows = array();
        foreach($list as $row){
            $rows[$row->category_id] = $row->k;
        }        
        return $rows;
    }
    
    public function deleteCategory($category_id){
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_categories` WHERE `category_id` = '" . $db->escape($category_id) . "'";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $db->execute();
    }
    
    public function getTreeAllCategories($filter = array(), $order = null, $orderDir = null) {
        $db = \JFactory::getDBO();
        $user = JFactory::getUser();
        $lang = JSFactory::getLang();

        $query = "SELECT ordering, category_id, category_parent_id, `".$lang->get('name')."` as name, `".$lang->get('short_description')."` as short_description, `".$lang->get('description')."` as description, category_publish, category_image FROM `#__jshopping_categories`
                  ORDER BY category_parent_id, ". $this->_allCategoriesOrder($order, $orderDir);
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $all_cats = $db->loadObjectList();

        $categories = array();
        if (count($all_cats)){
            foreach($all_cats as $key=>$category){
                $category->isPrev = 0; $category->isNext = 0;
                if (isset($all_cats[$key-1]) && $category->category_parent_id == $all_cats[$key-1]->category_parent_id){
                    $category->isPrev = 1;
                }
                if (isset($all_cats[$key+1]) && $category->category_parent_id == $all_cats[$key+1]->category_parent_id){
                    $category->isNext = 1;
                }
                
                if (!$category->category_parent_id){
                    recurseTree($category, 0, $all_cats, $categories, 0);
                }
            }
        }

        if (count($categories)){
			if (isset($filter['text_search']) && !empty($filter['text_search'])){
                $originalCategories = $categories;
                $filter['text_search'] = strtolower($filter['text_search']);

                foreach ($categories as $key => $category){
                    if (strpos(strtolower($category->name), $filter['text_search']) === false && strpos(strtolower($category->short_description), $filter['text_search']) === false && strpos(strtolower($category->description), $filter['text_search']) === false){
                        unset($categories[$key]);
                    }
                }

                if (count($categories)){
                    foreach ($categories as $key => $category){
                        $categories[$key]->name = "<span class = 'jshop_green'>".$categories[$key]->name."</span>"; 
                        $category_parent_id = $category->category_parent_id;
                        $i = 0;
                        while ($category_parent_id || $i < 1000) {
                            foreach ($originalCategories as $originalKey => $originalCategory){
                                if ($originalCategory->category_id == $category_parent_id){
                                    $categories[$originalKey] = $originalCategory;
                                    $category_parent_id = $originalCategory->category_parent_id;
                                    break;
                                }
                            }
                            $i++;
                        }
                    }
                    
                    ksort($categories);
                }
            }
		
            foreach($categories as $key=>$category){
                $category->space = ''; 
                for ($i = 0; $i < $category->level; $i++){
                    $category->space .= '<span class = "gi">|—</span>';
                }
            }
        }
		
        return $categories;
    }
   
    private function _allCategoriesOrder($order = null, $orderDir = null){
        $lang = JSFactory::getLang();
        if ($order && $orderDir){
            $fields = array("name" => "`".$lang->get('name')."`", "id" => "`category_id`", "description" => "`".$lang->get('description')."`", "ordering" => "`ordering`");
            if (strtolower($orderDir) != "asc") $orderDir = "desc";
            if (!$fields[$order]) return "`ordering` ".$orderDir;
            extract(js_add_trigger(get_defined_vars(), "before"));
            return $fields[$order]." ".$orderDir;
        }else{
            return "`ordering` asc";
        }
    }
    
    public function uploadImage($post){
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        
        $upload = new UploadFile($_FILES['category_image']);
        $upload->setAllowFile(array('jpeg','jpg','gif','png'));
        $upload->setDir($jshopConfig->image_category_path);
        $upload->setFileNameMd5(0);
        $upload->setFilterName(1);
        if ($upload->upload()){
            $name = $upload->getName();
            if ($post['old_image'] && $name!=$post['old_image']){
                @unlink($jshopConfig->image_category_path."/".$post['old_image']);
            }
            @chmod($jshopConfig->image_category_path."/".$name, 0777);
            
            if ($post['size_im_category'] < 3){
                if($post['size_im_category'] == 1){
                    $category_width_image = $jshopConfig->image_category_width; 
                    $category_height_image = $jshopConfig->image_category_height;
                }else{
                    $category_width_image = JFactory::getApplication()->input->getInt('category_width_image'); 
                    $category_height_image = JFactory::getApplication()->input->getInt('category_height_image');
                }

                $path_full = $jshopConfig->image_category_path."/".$name;
                $path_thumb = $jshopConfig->image_category_path."/".$name;
                if ($category_width_image || $category_height_image){
                    if (!ImageLib::resizeImageMagic($path_full, $category_width_image, $category_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color)) {
                        \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_CREATE_THUMBAIL'),'error');
                        saveToLog("error.log", "SaveCategory - Error create thumbail");
                    }
                }
                @chmod($jshopConfig->image_category_path."/".$name, 0777);
            }
            $category_image = $name;
            $dispatcher->triggerEvent('onAfterSaveCategoryImage', array($post, $category_image, $path_full, $path_thumb));
        }else{
            $category_image = '';
            if ($upload->getError() != 4){
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_UPLOADING_IMAGE'),'error');
                saveToLog("error.log", "SaveCategory - Error upload image. code: ".$upload->getError());
            }
        }
        return $category_image;
    }
	
	public function getCategoriesByProductID($product_id){
		$db = \JFactory::getDBO();
		$query = "select * from #__jshopping_products_to_categories where product_id='".$product->product_id."'";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	public function getNextProductsToCategoryOrdering($category_id){
		$db = \JFactory::getDBO();
		$query = "select max(product_ordering) as k from #__jshopping_products_to_categories where category_id='".$category_id."' ";
		$db->setQuery($query);
		return ($db->loadResult() + 1);
	}
	public function setOrderingForProductsToCategories($ordering,$category_id,$product_id){
		$db = \JFactory::getDBO();
		$query = "update #__jshopping_products_to_categories set product_ordering='".$ordering."' where category_id='".$category_id."' and product_id='".$product_id."' ";
		$db->setQuery($query);
		$db->execute();	
	}
	public function orderingUp($category_parent_id,$ordering){
		$db = \JFactory::getDBO();
        $query = "UPDATE `#__jshopping_categories` SET `ordering` = ordering + 1
                    WHERE `category_parent_id` = '" . $category_parent_id . "' AND `ordering` > '" . $ordering . "'";
        $db->setQuery($query);
        $db->execute();
	}
	public function setPublishFlag($flag,$id){
		$db = \JFactory::getDBO();
		$query = "UPDATE `#__jshopping_categories` SET `category_publish` = '" . $db->escape($flag) . "' WHERE `category_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();
	}
	public function getMaxOrderingByParentId($category_parent_id){
		$db = \JFactory::getDBO();
		$query = "select max(ordering) FROM `#__jshopping_categories` WHERE `category_parent_id`=".$category_parent_id;
		$db->setQuery($query);
		return $db->loadResult() + 1;
	}
	public function updateOrderingByCategoryId($category_id,$ordering){
		$db = \JFactory::getDBO();
		$query = "UPDATE `#__jshopping_categories` SET `ordering`=".$ordering." WHERE `category_id`=".$category_id;
		$db->setQuery($query);
		$db->execute();
	}
	public function getProductsToCategoriesByCategoryId($id){
		$db = \JFactory::getDBO();
		$query = "select * from #__jshopping_products_to_categories where category_id='".$id."'";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	public function addProductToCategory($product_id,$category_id,$product_ordering){
		$db = \JFactory::getDBO();
		$query = "INSERT INTO #__jshopping_products_to_categories (`product_id`, `category_id`, `product_ordering`) VALUES (".$product_id.", ".$category_id.", ".$product_ordering.") ";
		$db->setQuery($query);
		$db->execute();
	}
	public function setCountToPage($count_products_to_page){
		$db = \JFactory::getDBO();
		$query = "update `#__jshopping_categories` set `products_page`='".$count_products_to_page."';";
		$db->setQuery($query);
		$db->execute();
	}
	public function getSelectedArray($categories_select){		
		$categorys_id = array();
		if (is_array($categories_select)){
			foreach($categories_select as $tmp){
				$categorys_id[] = $tmp->category_id;
			}        
		}
		return $categorys_id;
	}
	
	public function getProductCategoriesList($_table_product){
		$edit = intval($_table_product->product_id);        
		if ($edit){
			$categories_select = $_table_product->getCategories();
            $categories_select_list = array();
            foreach($categories_select as $v){
                $categories_select_list[] = $v->category_id;
            }
		}else{
			$category_id = JFactory::getApplication()->input->getInt('category_id');
			$categories_select = null;
            if ($category_id) {
                $categories_select = $category_id;
            }            
            $categories_select_list = array();
		}
		return $categories_select_list;
	}
	
	public function getProductCategoriesSelected($_table_product){
		$edit = intval($_table_product->product_id);        
		if ($edit){
			$categories_select = $_table_product->getCategories();            
		}else{
			$category_id = JFactory::getApplication()->input->getInt('category_id');
			$categories_select = null;
            if ($category_id) {
                $categories_select = $category_id;
            }                        
		}
		return $categories_select;
	}	
	
	public function getProductCategoriesSelect($_table_product,$product_id,&$lists){
		$jshopConfig = JSFactory::getConfig();
		$categories = buildTreeCategory(0,1,0);		
        if (count($categories)==0) {
            \Joomla\CMS\Factory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_PLEASE_ADD_CATEGORY'), 'notice');
        }
		$category_select_onclick = "";
        if ($jshopConfig->admin_show_product_extra_field) $category_select_onclick = 'onclick="shopProductCommon.reloadExtraField(\''.$product_id.'\')"';
		$lists['categories'] = JHTML::_('select.genericlist', $categories, 'category_id[]', 'class="inputbox form-select" size="10" multiple = "multiple" '.$category_select_onclick, 'category_id', 'name',$this->getProductCategoriesSelected($_table_product));		
	}
	public function productEditList_getCategoriesList(&$lists){
		$jshopConfig = JSFactory::getConfig();
		$categories = buildTreeCategory(0,1,0);		        
		$category_select_onclick = "";
        if ($jshopConfig->admin_show_product_extra_field) $category_select_onclick = 'onclick="shopProductCommon.reloadExtraField(\'0\')"';
		$lists['categories'] = JHTML::_('select.genericlist', $categories, 'category_id[]', 'class="inputbox form-select" size="10" multiple = "multiple" '.$category_select_onclick, 'category_id', 'name',$this->getProductCategoriesSelected($_table_product));		
	}

	public function getTreeCategory($publish = 1, $is_select = 1, $access = 1){
		$categories = buildTreeCategory($publish,$is_select,$access);  
		return $categories;
	}
	
	public function getTreeCategoryWithFirstFreeElement($publish = 1, $is_select = 1, $access = 1){
		$parentTop = new stdClass();
        $parentTop->category_id = -1;
        $parentTop->name = '- - -';
        $categories = buildTreeCategory($publish,$is_select,$access);
        array_unshift($categories, $parentTop);
		return $categories;
	}
	
	public function getTreeCategoryWithFirstSetElement($first_element_name,$publish = 1, $is_select = 1, $access = 1){
		$parentTop = new stdClass();
        $parentTop->category_id = 0;
        $parentTop->name = $first_element_name;
        $categories = buildTreeCategory($publish,$is_select,$access);
        array_unshift($categories, $parentTop);
		return $categories;
	}
	
	public function getCategoriesTreeWithParentSelectedSelect($parentid=""){
		$categories = $this->getTreeCategoryWithFirstFreeElement(0,1,0);        
		return JHTML::_('select.genericlist', $categories,'category_parent_id','class="inputbox form-select" size="1" onchange = "changeCategory()"','category_id','name', $parentid);
	}
	
	public function getCategoriesTreeWithFirstFreeSelect($category_select_onclick="",$categories_selected=""){
		$categories = $this->getTreeCategoryWithFirstFreeElement(0,1,0);        
		return JHTML::_('select.genericlist', $categories, 'category_id[]', 'class="inputbox form-select" size="10" multiple = "multiple" '.$category_select_onclick, 'category_id', 'name',$categories_selected);
	}
	
	public function getCategoriesTreeSelect($category_select_onclick="",$categories_selected=""){
		$categories = buildTreeCategory($publish ?? '',$is_select ?? '',$access ?? '');
		return JHTML::_('select.genericlist', $categories, 'category_id[]', 'class="inputbox form-select" size="10" multiple = "multiple" '.$category_select_onclick, 'category_id', 'name',$categories_selected);
	}
	
    public function getTotalCountOfSameCategoryImage(string $imageName)
    {
        $db = \JFactory::getDBO();
        $query = 'SELECT COUNT(*) FROM `#__jshopping_categories` WHERE `category_image` = ' . $db->q($imageName);
        $db->setQuery($query);

        return $db->loadResult() ?: 0;
    }
}
?>