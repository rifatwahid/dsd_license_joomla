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

class JshoppingModelProducts extends JModelLegacy
{
    /**
    *   @return array
    */
    public function getProductsByOrderId($orderId, $select = '*', $resType = 'loadObjectList')
    {
        $result = [];

        if ( !empty($orderId) ) {
            $orderItemsModel = JSFactory::getModel('OrderItems');
            $ordersItems = $orderItemsModel->getByOrderId($orderId);

            if ( !empty($ordersItems) ) {

                $arrWithStringForQuerySelect = [];
                $db = \JFactory::getDBO();
                $query = $db->getQuery(true);

                foreach($ordersItems as $key => $orderItem) {
                    $arrWithStringForQuerySelect[] = 'product_id = ' . $db->escape($orderItem->product_id);
                }

                $query->select($select)
                    ->from('#__jshopping_products')
                    ->where($arrWithStringForQuerySelect, 'OR');

                $result = $db->setQuery($query)->$resType();
            }
        }

        return $result;
    }

    public function getProductsByIds($arrayWithIds)
    {
        $result = [];

        if ( !empty($arrayWithIds) && is_array($arrayWithIds) ) {
            $strWithIds = implode(',', $arrayWithIds);
            $db = \JFactory::getDBO();
            $querySelectProductsByIds = $db->getQuery(true);
            $querySelectProductsByIds->select('*')
                  ->from( $db->qn('#__jshopping_products') )
                  ->where( $db->qn('product_id') . ' IN (' . $strWithIds . ')' );

            $db->setQuery( $querySelectProductsByIds );
            $result = $db->loadObjectList('product_id');
        }

        return $result;
    }  
    
    public function _getAllProductsQueryForFilter($filter)
    {
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO();
        $where = "";
        if (isset($filter['without_product_id']) && $filter['without_product_id']){
            $where .= " AND pr.product_id <> '".$db->escape($filter['without_product_id'])."' ";    
        }
        if (isset($filter['category_id']) && $filter['category_id']){
            $category_id = $filter['category_id'];
            $where .= " AND pr_cat.category_id = '".$db->escape($filter['category_id'])."' ";    
        }
        if (isset($filter['text_search']) && $filter['text_search']){
            $text_search = $filter['text_search'];
            $word = addcslashes($db->escape($text_search), "_%");
            $where .=  "AND (LOWER(pr.`".$lang->get('name')."`) LIKE '%" . $word . "%' OR LOWER(pr.`".$lang->get('short_description')."`) LIKE '%" . $word . "%' OR LOWER(pr.`".$lang->get('description')."`) LIKE '%" . $word . "%' OR pr.product_ean LIKE '%" . $word . "%' OR pr.product_id LIKE '%" . $word . "%')";            
        }
        if (isset($filter['manufacturer_id']) && $filter['manufacturer_id']){
            $where .= " AND pr.product_manufacturer_id = '".$db->escape($filter['manufacturer_id'])."' ";    
        }
        if (isset($filter['label_id']) && $filter['label_id']){
            $where .= " AND pr.label_id = '".$db->escape($filter['label_id'])."' ";    
        }
        if (isset($filter['publish']) && $filter['publish']){
            if ($filter['publish']==1) $_publish = 1; else $_publish = 0;            
            $where .= " AND pr.product_publish = '".$db->escape($_publish)."' ";
        }    
        
        if (!empty($filter['except_categories_id'])) {
            $where .= ' AND pr_cat.category_id NOT IN(' . implode(',', $filter['except_categories_id']) . ')';
        }

		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfter_getAllProductsQueryForFilter', array(&$where,&$filter));
    return $where;
    }
    
    public function _allProductsOrder($order = null, $orderDir = null, $category_id = 0)
    {
        if ($order && $orderDir){
            $fields = array("product_id"=>"pr.product_id", "name"=>"name",'category'=>"namescats","manufacturer"=>"man_name","vendor"=>"v_f_name","ean"=>"ean","qty"=>"qty","price"=>"pr.product_price","hits"=>"pr.hits","date"=>"pr.product_date_added", "product_name_image"=>"pr.image");
            if ($category_id) $fields['ordering'] = "pr_cat.product_ordering";
            if (strtolower($orderDir)!="asc") $orderDir = "desc";
            if ($orderDir=="desc") $fields['qty'] ='pr.unlimited desc, qty';
            if (!$fields[$order]) return "";
            return "order by ".$fields[$order]." ".$orderDir;
        }else{
            return "";
        }
    }
    
    public function getAllProducts($filter, $limitstart = null, $limit = null, $order = null, $orderDir = null)
    {
        $currentObj = $this;
        $jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO(); 
        if ($limit > 0){
            $limit = " LIMIT ".$limitstart.", ".$limit;
        }else{
            $limit = "";
        }        
        if (isset($filter['category_id'])) 
            $category_id = $filter['category_id'];
        else 
            $category_id = '';
        
        $where = $this->_getAllProductsQueryForFilter($filter);
        
        $query_filed = ""; $query_join = "";
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayListProductsBeforeGetAllProducts', array(&$currentObj, &$query, &$query_filed, &$query_join));		
        
        if ($category_id) {
            $query = "SELECT pr.product_id, pr.product_publish, pr_cat.product_ordering, pr.`".$lang->get('name')."` as name, pr.`".$lang->get('short_description')."` as short_description, man.`".$lang->get('name')."` as man_name, pr.product_ean as ean, pr.product_quantity as qty, pr.image as image, pr.product_price, pr.currency_id, pr.hits, pr.unlimited, pr.product_date_added, pr.label_id $query_filed FROM `#__jshopping_products` AS pr
                      LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                      LEFT JOIN `#__jshopping_manufacturers` AS man ON pr.product_manufacturer_id=man.manufacturer_id
                      $query_join
                      WHERE pr.parent_id=0 ".$where." ".$this->_allProductsOrder($order, $orderDir, $category_id)." ".$limit;
        }else{
            $mysqlversion = getMysqlVersion();
            if ($mysqlversion < "4.1.0"){
                $spec_where = "cat.`".$lang->get('name')."` AS namescats";
            }else{
                $spec_where = "GROUP_CONCAT(cat.`".$lang->get('name')."` SEPARATOR '<br>') AS namescats";
            }
            
            $query = "SELECT pr.product_id, pr.product_publish, pr.`".$lang->get('name')."` as name, pr.`".$lang->get('short_description')."` as short_description, man.`".$lang->get('name')."` as man_name, ".$spec_where.", pr.product_ean as ean, pr.product_quantity as qty, pr.image as image, pr.product_price, pr.currency_id, pr.hits, pr.unlimited, pr.product_date_added, pr.label_id $query_filed FROM `#__jshopping_products` AS pr 
                      LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                      LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id=cat.category_id
                      LEFT JOIN `#__jshopping_manufacturers` AS man ON pr.product_manufacturer_id=man.manufacturer_id
                      $query_join
                      WHERE pr.parent_id=0 ".$where." GROUP BY pr.product_id ".$this->_allProductsOrder($order, $orderDir)." ".$limit;
        }
        
        $dispatcher->triggerEvent('onBeforeDisplayListProductsGetAllProducts', array(&$currentObj, &$query, &$filter, &$limitstart, &$limit, &$order, &$orderDir));
        $db->setQuery($query);
                $list = $db->loadObjectList();
		
		if(!empty($list)){
			foreach($list as $key=>$row){
				$list[$key]->short_description = str_replace('src="images/','src="/images/', $row->short_description);
			}
		}
		return $list;
    }
    
    public function getCountAllProducts($filter)
    {
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO();                
        if (isset($filter['category_id'])) 
            $category_id = $filter['category_id'];
        else
            $category_id = '';
                
        $where = $this->_getAllProductsQueryForFilter($filter);
        $query = "SELECT count(DISTINCT pr.product_id) FROM `#__jshopping_products` AS pr
                    LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                    LEFT JOIN `#__jshopping_manufacturers` AS man ON pr.product_manufacturer_id=man.manufacturer_id
                    WHERE pr.parent_id=0 ".$where;
        
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayListProductsGetCountAllProducts', array(&$currentObj, &$query, &$filter));
        $db->setQuery($query);        
        return $db->loadResult();
    }
    
    public function productInCategory($product_id, $category_id) 
    {
        $db = \JFactory::getDBO();
        $query = "SELECT prod_cat.category_id FROM `#__jshopping_products_to_categories` AS prod_cat
                   WHERE prod_cat.product_id = '".$db->escape($product_id)."' AND prod_cat.category_id = '".$db->escape($category_id)."'";
        $db->setQuery($query);
        $res = $db->execute();
        return $db->getNumRows($res);
    }
    
    public function getMaxOrderingInCategory($category_id) 
    {
        $db = \JFactory::getDBO();
        $query = "SELECT MAX(product_ordering) as k FROM `#__jshopping_products_to_categories` WHERE category_id = '".$db->escape($category_id)."'";
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    public function setCategoryToProduct($product_id, $categories = array())
    {
        $db = \JFactory::getDBO();
        foreach($categories as $cat_id){
            if (!$this->productInCategory($product_id, $cat_id)){
                $ordering = $this->getMaxOrderingInCategory($cat_id)+1;
                $query = "INSERT INTO `#__jshopping_products_to_categories` SET `product_id` = '".$db->escape($product_id)."', `category_id` = '".$db->escape($cat_id)."', `product_ordering` = '".$db->escape($ordering)."'";
                $db->setQuery($query);
                $db->execute();
            }
        }

        //delete other cat for product        
        $query = "select `category_id` from `#__jshopping_products_to_categories` where `product_id` = '".$db->escape($product_id)."'";
        $db->setQuery($query);
        $listcat = $db->loadObjectList();
        foreach($listcat as $val){
            if (!in_array($val->category_id, $categories)){
                $query = "delete from `#__jshopping_products_to_categories` where `product_id` = '".$db->escape($product_id)."' and `category_id` = '".$db->escape($val->category_id)."'";
                $db->setQuery($query);
                $db->execute();
            }
        }
                    
    }
    
    public function getRelatedProducts($product_id)
    {
        $edit = intval($product_id);
        $result = [];

		if ($edit) {
			$db = \JFactory::getDBO();
			$lang = JSFactory::getLang();
			$query = "SELECT relation.product_related_id AS product_id, prod.`".$lang->get('name')."` as name, prod.image as image
					FROM `#__jshopping_products_relations` AS relation
					LEFT JOIN `#__jshopping_products` AS prod ON prod.product_id=relation.product_related_id                
					WHERE relation.product_id = '".$db->escape($product_id)."' order by relation.id";
			extract(js_add_trigger(get_defined_vars(), "before"));
            $db->setQuery($query);
            
			$result = $db->loadObjectList();
		}
        
        return $result;
    }
    
    public function saveAditionalPrice($product_id, $product_add_discount, $quantity_start, $quantity_finish, $product_add_price, $start_discount,$usergroup=0,$usergroup_prices=0)
    {
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_products_prices` WHERE `product_id` = ".$db->q($product_id)." AND `usergroup_prices` = ".$db->q($usergroup_prices)." AND `usergroup` = ".$db->q($usergroup);
        $db->setQuery($query);
        $db->execute();
        
        $counter = 0;
        if (isset($product_add_discount) && is_array($product_add_discount) && count($product_add_discount)){
            foreach ($product_add_discount as $key => $value) {
                
                if (empty($quantity_start[$key]) && empty($quantity_finish[$key])) {
                    continue;
                }
                
                $query = "INSERT INTO `#__jshopping_products_prices` SET 
                            `product_id` = '" . $db->escape($product_id) . "',
                            `discount` = '" . $db->escape(saveAsPrice($value)) . "',
                            `product_quantity_start` = '" . floatval(str_replace(',','.', $quantity_start[$key])) . "',
                            `product_quantity_finish` = '" . floatval(str_replace(',','.', $quantity_finish[$key])) . "',
                            `price` = '" . $db->escape(saveAsPrice($product_add_price[$key])) . "',
                            `start_discount` = '" . $db->escape(saveAsPrice($start_discount[$key])) . "',
							`usergroup_prices` = '" . $db->escape($usergroup_prices) . "',
							`usergroup` = '" . $db->escape((int)($usergroup)) . "'";                            							
                $db->setQuery($query);
                $db->execute();				
                $counter++;
            }
        }
        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);
        $product->product_is_add_price = ($counter>0) ? (1) : (0);
        $product->store();
    }
    
    public function saveFreeAttributes($product_id, $attribs)
    {
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_products_free_attr` WHERE `product_id` = '".$db->escape($product_id)."'";
        $db->setQuery($query);
        $db->execute();
        
        $this->onlySaveFreeAttrs($product_id, $attribs);
    }

    public function onlySaveFreeAttrs($product_id, $attribs)
    {
        if (is_array($attribs)){
            $db = \JFactory::getDBO();

            foreach($attribs as $attr_id=>$v){
                $query = "insert into `#__jshopping_products_free_attr` set `product_id` = '".$db->escape($product_id)."', attr_id='".$db->escape($attr_id)."'";
                $db->setQuery($query);
                $db->execute();
            }
        }
    }
    
    public function saveProductOptions($product_id, $options)
    {
        $db = \JFactory::getDBO(); 
        foreach($options as $key=>$value){
            if (isset($value)) {
                $query = "DELETE FROM `#__jshopping_products_option` WHERE `product_id` = '".$db->escape($product_id)."' AND `key`='".$db->escape($key)."'";
                $db->setQuery($query);
                $db->execute();
                
                $query = "insert into `#__jshopping_products_option` set `product_id` = '".$db->escape($product_id)."', `key`='".$db->escape($key)."', `value`='".$db->escape($value)."'";
                $db->setQuery($query);
                $db->execute();     
            }            
        }
    }
    
    public function getMinimalPrice($price, $attrib_prices, $attrib_ind_price_data, $is_add_price, $add_discounts)
    {
        $oneTimePriceTypeArrKey = 100500;
        $minprice = $price;

        if (is_array($attrib_prices)){            
            $minprice = min($attrib_prices);            
        }
        
        if (is_array($attrib_ind_price_data[0])){
            $attr_ind_id = array_unique($attrib_ind_price_data[0]);
            $startprice = $minprice ? $minprice : 0;
            foreach($attr_ind_id as $attr_id){
                $tmpprice = array();
                foreach($attrib_ind_price_data[0] as $k=>$tmp_attr_id){

                    $attrTypePriceId = $attrib_ind_price_data[3][$k];

                    if ($tmp_attr_id == $attr_id && $attrTypePriceId != $oneTimePriceTypeArrKey) {
                        if ($attrib_ind_price_data[1][$k]=="+"){
                            $tmpprice[] = $startprice + $attrib_ind_price_data[2][$k];
                        }elseif ($attrib_ind_price_data[1][$k]=="-"){
                            $tmpprice[] = $startprice - $attrib_ind_price_data[2][$k];
                        }elseif ($attrib_ind_price_data[1][$k]=="*"){
                            $tmpprice[] = $startprice * $attrib_ind_price_data[2][$k];
                        }elseif ($attrib_ind_price_data[1][$k]=="/" && $attrib_ind_price_data[2][$k] > 0){
                            $tmpprice[] = $startprice / $attrib_ind_price_data[2][$k];
                        }elseif ($attrib_ind_price_data[1][$k]=="%"){
                            $tmpprice[] = $startprice * $attrib_ind_price_data[2][$k] / 100;
                        }elseif ($attrib_ind_price_data[1][$k]=="="){
                            $tmpprice[] = $attrib_ind_price_data[2][$k];
                        }
                    }
                }

                if (!empty($tmpprice)) {
                    $startprice = min($tmpprice);
                }
            }
            
            $minprice = $startprice;
        }
        
        extract(js_add_trigger(get_defined_vars(), "before"));
        return $minprice;
    }
    
    public function copyProductBuildQuery($table, $array, $product_id)
    {
        $db = \JFactory::getDBO();
        $query = "INSERT INTO `#__jshopping_products_".$table."` SET ";
        $array_keys = array('image_id', 'price_id', 'review_id', 'video_id', 'product_attr_id', 'value_id', 'id');
        foreach ($array as $key=>$value){
            if (in_array($key, $array_keys)) continue;
            if ($key=='product_id') $value = $product_id;
            $query .= "`".$key."` = '".$db->escape($value)."', ";
        }
        extract(js_add_trigger(get_defined_vars(), "before"));
        return $query = substr($query, 0, strlen($query) - 2);
    }
    
    public function setMediaAttr($productId, $uploadMediaFiles, $media) 
    {
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $modelOfProductsMedia = JSFactory::getModel('ProductsMediaFront');
		
        $media = $media ?: [];

        if (!empty($media)) {
            $uploadedMedia = $modelOfProductsMedia->handleUploadMedia($media);
            $_media = $modelOfProductsMedia->bindAndStoreMedia($productId, $uploadedMedia);
			if(!empty($_media) && $_media[0]['id']) {
				$modelOfProductsMedia->setMain($productId, $_media[0]['id']);
				if($_media[0]['id']){
					$_product = JSFactory::getTable('product', 'jshop');
                    $_product->load($productId);  
                    $_product->is_use_additional_media = 1; 
                    $_product->store();  
				}
			}
        }
        $modelOfProductsMedia->setTitles([]);
        $modelOfProductsMedia->setOrdering([]);
    }
    
    public function setMedia($productId, $uploadMediaFiles, $post) 
    {
		$uploadMediaFiles = $uploadMediaFiles ?? [];
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $modelOfProductsMedia = JSFactory::getModel('ProductsMediaFront');
        $media = $post['media'] ?: [];

        if (!empty($media)) {
            $uploadedMedia = $modelOfProductsMedia->handleUploadMedia($media);
            $modelOfProductsMedia->bindAndStoreMedia($productId, $uploadedMedia);
        }

        $modelOfProductsMedia->setMain($productId, $post['set_main_image'] ?: 0);
        $modelOfProductsMedia->setTitles($post['old_image_descr'] ?: []);
        $modelOfProductsMedia->setOrdering($post['old_image_ordering'] ?: []);
    }

    public function handleSetFiles($productId, $post)
    {
        $shopConfig = JSFactory::getConfig();

        for($i = 0; $i < $shopConfig->product_file_upload_count; $i++) {
            $loopDataOfSale = $post['files'][$i]['source'] ? $post['files'][$i]: [];
            $loopDataOfDemo = $post['demo_files'][$i]['source'] ? $post['demo_files'][$i]: [];

            if (!empty($loopDataOfSale) || !empty($loopDataOfDemo)) {
                $salePath = $loopDataOfSale['source'] ?: '';
                $salePath = clearPathOfImage($salePath);
                $saleDescr = $loopDataOfSale['descr'] ?: [];
                $saleSort = $loopDataOfSale['sort'] ?: 0;

                $demoPath = $loopDataOfDemo['source'] ?: '';
                $demoPath = clearPathOfImage($demoPath);
                $demoDescr = $loopDataOfDemo['descr'] ?: [];

                if (!empty($salePath) || !empty($demoPath)) {
                    $this->addToProductFiles($productId, $demoPath, $demoDescr, $salePath, $saleDescr, $saleSort, $post);
                }
            }
        }

        $this->productUpdateDescriptionFiles($post['product_demo_descr'], $post['product_file_descr'], $post['product_file_sort']);
    }
    
    /**
     * Don`t use!!!
     * 
     * @deprecated
     */
    public function uploadFiles($product, $product_id, $post)
    {
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
		
        $_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);
		$i=0;
		foreach($this->languages as $lang) {
			if (!isset($post['product_demo_descr_' . $lang->language]) || !$jshopConfig->admin_show_product_demo_files) $post['product_demo_descr_'  . $lang->language] = '';
			if (!isset($post['product_file_descr_' . $lang->language]) || !$jshopConfig->admin_show_product_sale_files) $post['product_file_descr_' . $lang->language] = '';
        }
        if (!isset($post['product_file_sort'])) $post['product_file_sort'] = '';
		
        for($i=0; $i<$jshopConfig->product_file_upload_count; $i++){
            $file_demo = "";
            $file_sale = "";
            if ($jshopConfig->product_file_upload_via_ftp!=1){
                if($jshopConfig->admin_show_product_demo_files ) {
                    $upload = new UploadFile($_FILES['product_demo_file_' . $i]);
                    $upload->setDir($jshopConfig->demo_product_path);
                    $upload->setFileNameMd5(0);
                    $upload->setFilterName(1);
                    if ($upload->upload()) {
                        $file_demo = $upload->getName();
                        @chmod($jshopConfig->demo_product_path . "/" . $file_demo, 0777);
                    } else {
                        if ($upload->getError() != 4) {
                            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_UPLOADING_FILE_DEMO'),'error');
                            saveToLog("error.log", "SaveProduct - Error upload demo. code: " . $upload->getError());
                        }
                    }
                    unset($upload);
                }
                if($jshopConfig->admin_show_product_sale_files ) {
                    $upload = new UploadFile($_FILES['product_file_'.$i]);
                    $upload->setDir($jshopConfig->files_product_path);
                    $upload->setFileNameMd5(0);
                    $upload->setFilterName(1);
                    if ($upload->upload()){
                        $file_sale = $upload->getName();
                        @chmod($jshopConfig->files_product_path."/".$file_sale, 0777);
                    }else{
                        if ($upload->getError() != 4){
                            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_UPLOADING_FILE_SALE'),'error');
                            saveToLog("error.log", "SaveProduct - Error upload file sale. code: ".$upload->getError());
                        }
                    }
                    unset($upload);
                }
            }

            if ($jshopConfig->admin_show_product_demo_files && !$file_demo && isset($post['product_demo_file_name_'.$i]) && $post['product_demo_file_name_'.$i]){
                $file_demo = $post['product_demo_file_name_'.$i];
            }
            if ($jshopConfig->admin_show_product_sale_files && !$file_sale && isset($post['product_file_name_'.$i]) && $post['product_file_name_'.$i]){
                $file_sale = $post['product_file_name_'.$i];
            }

			$product_demo_descr = [];
			$product_file_descr = [];

			foreach($languages as $lang) {
				$product_demo_descr[$lang->language] = $post['demo_descr_'  . $lang->language.'_'.$i];
				$product_file_descr[$lang->language] = $post['file_descr_' . $lang->language.'_'.$i];
			}
		
            if ($file_demo!="" || $file_sale!=""){
                $this->addToProductFiles($product_id, $file_demo, $product_demo_descr, $file_sale, $product_file_descr, $post['product_file_sort_'.$i], $post);
            }
        }
        //Update description files
        $this->productUpdateDescriptionFiles($post['product_demo_descr'], $post['product_file_descr'], $post['product_file_sort']);
    }
    
    public function addToProductFiles($product_id, $file_demo, $demo_descr, $file_sale, $file_descr, $sort, $post)
    {
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
		$ins = '';
		if($jshopConfig->admin_show_product_demo_files) {
            foreach ($demo_descr as $key => $val) {
                if ($ins != '') {
                    $ins .= ', ';
                }
                $ins .= "`demo_descr_$key`='" . $db->escape($val) . "'";
            }
        }
        if($jshopConfig->admin_show_product_sale_files) {
            foreach ($file_descr as $key => $val) {
                if ($ins != '') {
                    $ins .= ', ';
                }
                $ins .= "`file_descr_$key`='" . $db->escape($val) . "'";
            }
        }
		if($ins != ''){
			$ins = ', '.$ins;
		} 
        $query = "INSERT INTO `#__jshopping_products_files` SET `product_id` = '".$db->escape($product_id)."', `demo` = '".$db->escape($file_demo)."',  `file` = '".$db->escape($file_sale)."' ".$ins." ,`ordering`='".$db->escape($sort)."'";
		   
	   $db->setQuery($query);
        $db->execute();
    }
    
    public function productUpdateDescriptionFiles($demo_descr, $file_descr, $ordering)
    {
        $db = \JFactory::getDBO();
        if (is_array($demo_descr)){
            foreach($demo_descr as $lang=>$values){
				foreach($values as $file_id=>$value){
					$query = "update `#__jshopping_products_files`SET 
								`demo_descr_$lang` = '".$db->escape($demo_descr[$lang][$file_id])."', 
								`ordering` = '".$db->escape($ordering[$file_id])."'
								where id='".$db->escape($file_id)."'";			
					$db->setQuery($query);
					$db->execute();   		
				}
            }
        }
        if (is_array($file_descr)){
            foreach($file_descr as $lang=>$values){
				foreach($values as $file_id=>$value){
					$query = "update `#__jshopping_products_files`SET  
								`file_descr_$lang` = '".$db->escape($file_descr[$lang][$file_id])."'
								where id='".$db->escape($file_id)."'";
					$db->setQuery($query);
					$db->execute();
				}
            }
        }
    }
    
    public function saveAttributes($product, $product_id, $post, bool $isAddMode = false)
    {
        $dispatcher = \JFactory::getApplication();
        $modelOfProdAttrs = JSFactory::getModel('productattrs');
        $productAttribut = JSFactory::getTable('productAttribut', 'jshop');
        $modelOfProduct = JSFactory::getModel('products');
        $modelOfNativeUploadsPricesAdmin = JSFactory::getModel('NativeUploadsPricesAdmin');
        $productAttribut->set("product_id", $product_id);
        $_prices = JSFactory::getModel("prices");
        
        $list_exist_attr = $product->getAttributes(false);
        
        if (isset($post['product_attr_id']) && !empty($post['product_attr_id'])){
            $list_saved_attr = $post['product_attr_id'];
        }else{
            $list_saved_attr = array();
        }     
        
        if (!$isAddMode) {
            foreach($list_exist_attr as $v){
                if (!in_array($v->product_attr_id, $list_saved_attr)){
                    $productAttribut->deleteAttribute($v->product_attr_id);
                }
            }
        }

		if($post['product_packing_type'] == 1){
			function date_sort($a, $b) {
				return strtotime($a) - strtotime($b);
			}
			$post['attr_expiration_date'] = $post['attr_expiration_date'] ?: [];
			uasort($post['attr_expiration_date'], "date_sort");
			$i = 0;
			foreach($post['attr_expiration_date'] as $k=>$date){
				$post['product_attr_sorting'][$k] = $i;
				$i++;
			}
		}
        if (is_array($post['attrib_price'])){
            foreach($post['attrib_price'] as $k=>$v){

                /* Price */
                $a_price = saveAsPrice($post['attrib_price'][$k]);
                $a_old_price = saveAsPrice($post['attrib_old_price'][$k]);
                $a_product_price_type = $post['attr_product_price_type'][$k]; //product_price_type
                $a_qtydiscount = $post['attr_qtydiscount'][$k]; //qtydiscount
                $a_weight_volume_units = $post['attr_weight_volume_units'][$k];
                $a_basic_price_unit_id = $post['attr_basic_price_unit_id'][$k];
                $a_add_price_unit_id = $post['attr_add_price_unit_id'][$k];

                /* Details */
                $a_product_packing_type = $post['attr_product_packing_type'][$k]; //product_packing_type
                $a_weight = $post['attr_weight'][$k];
                $a_expiration_date = $post['attr_expiration_date'][$k];
                $a_ean = $post['attr_ean'][$k];
                $a_count = $post['attr_count'][$k];
                $a_low_stock_attr_notify_status = $post['low_stock_attr_notify_status'][$k] ?: 0;
                $a_low_stock_attr_notify_number = $post['low_stock_attr_notify_number'][$k] ?: 0;
                $a_factory = $post['attr_factory'][$k]; //factory
                $a_storage = $post['attr_storage'][$k]; //storage
                $a_product_tax_id = $post['attr_product_tax_id'][$k]; //product_tax_id
                $a_product_manufacturer_id = $post['attr_product_manufacturer_id'][$k]; //product_manufacturer_id
                $a_attr_production_time = $post['attr_production_time'][$k] ?: 0;
                $a_delivery_times_id = $post['attr_delivery_times_id'][$k]; //delivery_times_id
                $a_labels = $post['attr_labels'][$k]; //label_id
                $a_no_return = $post['attr_no_return'][$k];
                $a_quantity_select = $post['attr_quantity_select'][$k]; //quantity_select
                $a_max_count_product = $post['attr_max_count_product'][$k]; //max_count_product
                $a_min_count_product = $post['attr_min_count_product'][$k]; //min_count_product
                $attr_equal_steps = $post['attr__equal_steps'][$k];
                $a_buy_price = saveAsPrice($post['attrib_buy_price'][$k]);
                
                if ($post['product_attr_id'][$k]){
                    $productAttribut->load($post['product_attr_id'][$k]);
                }else{
                    $productAttribut->set("product_attr_id", 0);
                    $productAttribut->set("ext_attribute_product_id", 0);
                }				            
				if (!empty($post['attr_unlimited'][$k])) {
					$a_count = 1;
					$a_unlimited = 1;
					$productAttribut->set('unlimited', 1);
				}else{					
					$productAttribut->set('unlimited', 0);
				}				
				
                $productAttribut->set("price", $a_price);
                $productAttribut->set("old_price", $a_old_price);
                $productAttribut->set("buy_price", $a_buy_price);
                $productAttribut->set("count", $a_count);
                $productAttribut->set("ean", $a_ean);
                $productAttribut->set("weight_volume_units", $a_weight_volume_units);
                $productAttribut->set("weight", $a_weight);
				$productAttribut->set("expiration_date", $a_expiration_date);
				$productAttribut->set("production_time", $a_attr_production_time);
                $productAttribut->set("sorting", $post['product_attr_sorting'][$k]);
                $productAttribut->set("low_stock_attr_notify_status", $a_low_stock_attr_notify_status);
                $productAttribut->set("low_stock_attr_notify_number", $a_low_stock_attr_notify_number);
                $productAttribut->set("add_price_unit_id", $a_add_price_unit_id);

                $productAttribut->set('product_price_type', $a_product_price_type);
                $productAttribut->set('qtydiscount', $a_qtydiscount);
                $productAttribut->set('product_packing_type', $a_product_packing_type);
                $productAttribut->set('factory', $a_factory);
                $productAttribut->set('storage', $a_storage);
                $productAttribut->set('product_tax_id', $a_product_tax_id);
                $productAttribut->set('product_manufacturer_id', $a_product_manufacturer_id);
                $productAttribut->set('delivery_times_id', $a_delivery_times_id);
                $productAttribut->set('label_id', $a_labels);
                $productAttribut->set('quantity_select', $a_quantity_select);
                $productAttribut->set('max_count_product', $a_max_count_product);
                $productAttribut->set('min_count_product', $a_min_count_product);
                $productAttribut->set('basic_price_unit_id', $a_basic_price_unit_id);
                $productAttribut->set('equal_steps', $attr_equal_steps);

                foreach($post['attrib_id'] as $field_id=>$val){
                    $productAttribut->set("attr_".intval($field_id), $val[$k]);
                }
                $dispatcher->triggerEvent('onBeforeProductAttributStore', array(&$productAttribut, &$product, &$product_id, &$post, &$k));

                if ($productAttribut->check()){
                    $isAttrStored = $productAttribut->store();

					//$this->saveProductExtOption($product_id, $productAttribut->product_attr_id, $post);
                    if ($isAttrStored) {
                        $subProductId = $modelOfProdAttrs->getProductAttr($product_id, $productAttribut->product_attr_id, (bool)$post['parent_id']);

						$this->setMediaAttr($subProductId, [], $post['attr_media'][$k]['media']);
						
                        if (!empty($subProductId)) {
                            $modelOfProduct->saveProductOptions($subProductId, [
                                'no_return' => $a_no_return
                            ]);

                            if (!empty($post['attr__consignment_product_is_add_price'][$k])) {
                                $modelOfProduct->saveAditionalPrice($subProductId, $post['attr__consignment_product_add_discount'][$k], $post['attr__consignment_quantity_start'][$k], $post['attr__consignment_quantity_finish'][$k], $post['attr__consignment_product_add_price'][$k], $post['attr__consignment_start_discount'][$k]);                                    
                            }

                            if (!empty($post['attr__is_activated_price_per_consignment_upload'][$k]) && !empty($post['attr__nativeProgressUploads']['prices']['updates'][$k])) {
                                $preparedPricePerConsigmentUploadData = $this->preparePricePerConsigmentUploadData($post['attr__nativeProgressUploads']['prices']['updates'][$k]);
                                $modelOfNativeUploadsPricesAdmin->savePricesData($preparedPricePerConsigmentUploadData, $subProductId);

                                $subProductTable = JSFactory::getTable('product', 'jshop');
                                $subProductTable->load($subProductId);
                                $subProductTable->is_activated_price_per_consignment_upload = true;
                                $subProductTable->store();
                            }
                            if (!empty($post['attrDependUsergroup'][$k])){// && !empty($post['attrDependUsergroup'][$k]['add_usergroup_price'])) {
								
								if(!empty($post['attrDependUsergroup'][$k]) && is_array($post['attrDependUsergroup'][$k])){
									
									foreach($post['attrDependUsergroup'][$k] as $attrData){
										$attrData['add_usergroup_price'] = 1;
										$_prices->addUsergroupPrices($subProductId, $attrData);
									}
								}else{
									$_prices->addUsergroupPrices($subProductId, $post['attrDependUsergroup'][$k]);
								}
                            }
                        }
                    } else {
                        $dispatcher->enqueueMessage(JText::_('COM_SMARTSHOP_FAILED_TO_SAVE_ATTRS'), 'warning');

                        if (!empty($productAttribut->getErrors())) {
                            $dispatcher->enqueueMessage(implode("\n", $productAttribut->getErrors()), 'warning');
                        }
                    }
                }
				
            }
        }   
        
        if (!empty($post['is_use_additional_details']) && !empty($post['product_attr_id']) && !is_array($post['product_attr_id'])) {
            $productAttribut->load($post['product_attr_id']);

            $productAttribut->set('price', $post['product_price']);
            $productAttribut->set('old_price', $post['product_old_price']);
            $productAttribut->set('count', $post['product_quantity']);
            $productAttribut->set('unlimited', $post['unlimited']);
            $productAttribut->set('ean', $post['product_ean']);
            $productAttribut->set('weight', $post['product_weight']);
            $productAttribut->set('weight_volume_units', $post['weight_volume_units']);
            $productAttribut->set('expiration_date', $post['expiration_date']);
            $productAttribut->set('production_time', $post['production_time']);
            $productAttribut->set('low_stock_attr_notify_status', $post['low_stock_notify_status']);
            $productAttribut->set('low_stock_attr_notify_number', $post['low_stock_notify_number']);

            if ($productAttribut->check()){
                $isAttrStored = $productAttribut->store();
            }
        }
        
        $productAttribut2 = JSFactory::getTable('productAttribut2', 'jshop');
        if (!$isAddMode) {
            $productAttribut2->set("product_id", $product_id);
            $productAttribut2->deleteAttributeForProduct();
        }
        
        if (is_array($post['attrib_ind_id'])){
            foreach($post['attrib_ind_id'] as $k=>$v){
                $a_id = intval($post['attrib_ind_id'][$k]);
                $a_value_id = intval($post['attrib_ind_value_id'][$k]);
                $a_price = saveAsPrice($post['attrib_ind_price'][$k]);
                $a_mod_price = $post['attrib_ind_price_mod'][$k];
                $price_type = $post['attrib_ind_price_type'][$k];
                $weight = saveAsPrice($post['attrib_ind_weight'][$k]);
				if ($post['attrib_ind_expiration_date'][$k]<>"") {$expiration_date = $post['attrib_ind_expiration_date'][$k];}else{$expiration_date="";}
                $sorting = $post['product_independ_attr_sorting'][$k];
                
                $productAttribut2->set("id", 0);
                $productAttribut2->set("product_id", $product_id);
                $productAttribut2->set("attr_id", $a_id);
                $productAttribut2->set("attr_value_id", $a_value_id);
                $productAttribut2->set("price_mod", $a_mod_price);
                $productAttribut2->set("addprice", $a_price);
                $productAttribut2->set("sorting", $sorting);
                $productAttribut2->set("price_type", $price_type);
                $productAttribut2->set("weight", $weight);
				$productAttribut2->set("expiration_date", $expiration_date);				
                $dispatcher->triggerEvent('onBeforeProductAttribut2Store', array(&$productAttribut2, &$product, &$product_id, &$post, &$k));
                if ($productAttribut2->check()){
                    $productAttribut2->store();
                }
            }
        }
        extract(js_add_trigger(get_defined_vars(), "after"));    
    }

    protected function preparePricePerConsigmentUploadData($data) 
    {
        $result = [];
        foreach ($data as $key1 => $values) {
            foreach ($values as $key2 => $value) {
                $result[$key2][$key1] = $value;
            }
        }

        return $result;
    }
    
    public function saveRelationProducts($product, $product_id, $post)
    {
        $db = \JFactory::getDBO();
        
        if ($post['edit']) {
            $query = "DELETE FROM `#__jshopping_products_relations` WHERE `product_id` = '".$db->escape($product_id)."'";
            $db->setQuery($query);
            $db->execute();
        }
        
        $post['related_products'] = array_unique($post['related_products']);
        foreach($post['related_products'] as $key => $value){
            if ($value!=0){
                $query = "INSERT INTO `#__jshopping_products_relations` SET `product_id` = '" . $db->escape($product_id) . "', `product_related_id` = '" . $db->escape($value) . "'";
                $db->setQuery($query);
                $db->execute();
            }
        }
    }

    public function getModPrice($price, $newprice, $mod)
    {
        $result = 0;
        switch($mod){
            case '=':
            $result = $newprice;
            break;
            case '+':
            $result = $price + $newprice;
            break;
            case '-':
            $result = $price - $newprice;
            break;
            case '*':
            $result = $price * $newprice;
            break;
            case '/':
            $result = $newprice > 0 ? $price / $newprice : $price;
            break;
            case '%':
            $result = $price * $newprice / 100;
            break;
        }
    return $result;
    }
    
    public function updatePriceAndQtyDependAttr($product_id, $post)
    {
        $db = \JFactory::getDBO();
        $_adv_query = array();
        if ($post['product_price']!=""){
            $price = saveAsPrice($post['product_price']);
            if ($post['mod_price']=='%') 
                $_adv_query[] = " `price`=`price` * '".$price."' / 100 ";
            elseif($post['mod_price']=='=') 
                $_adv_query[] = " `price`= '".$price."' ";
            else 
                $_adv_query[] = " `price`=`price` ".$post['mod_price']." '".$price."' ";
        }
        
        if ($post['product_old_price']!=""){
            $price = saveAsPrice($post['product_old_price']);
            if ($post['mod_old_price']=='%') 
                $_adv_query[] = " `old_price`=`old_price` * '".$price."' / 100 ";
            elseif($post['mod_old_price']=='=') 
                $_adv_query[] = " `old_price`= '".$price."' ";
            else 
                $_adv_query[] = " `old_price`=`old_price` ".$post['mod_old_price']." '".$price."' ";
        }

        if ($post['product_quantity']!=""){
            $_adv_query[] = " `count`= '".$db->escape($post['product_quantity'])."' ";
        }
        
        if (count($_adv_query)>0){
            $adv_query = implode(" , ", $_adv_query);
            $query = "update `#__jshopping_products_attr` SET ".$adv_query." where product_id='".$db->escape($product_id)."'";
            $db->setQuery($query);
            $db->execute();
        }
    }
    
    public function saveProductExtOption($product_id, $product_attr_id, $post){
		$productAttr = JSFactory::getModel('productAttrs', 'jshop');
        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);
        $product_attr = $productAttr->getProductAttr($product_id, $product_attr_id);
		
        if($product_attrs){
			if($attr->ext_attribute_product_id){
				$_product = JSFactory::getTable('product', 'jshop');
				$_product->load($attr->ext_attribute_product_id);  
				$_product->product_price = $attr->price; 
				$_product->store();                    
            }
        }
    }
    
    public function saveProductExtOptions($product_id) {
        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);
        $product_attrs=$product->getAttributes();
        if($product_attrs){
            foreach ($product_attrs as $attr){
                if($attr->ext_attribute_product_id){
                    $_product = JSFactory::getTable('product', 'jshop');
                    $_product->load($attr->ext_attribute_product_id);  
                    $_product->product_price = $attr->price; 
                    $_product->store();                    
                }
            }
        }
    }
    
    public function setProductAttr($cid, $key, $productIdFrom, $product) {
        $db = \JFactory::getDBO();
        
        if (isset($product->product_id) && $product->product_id){
            $query = "DELETE FROM `#__jshopping_products_attr` WHERE `product_id` = " . (int)$product->product_id;
            $db->setQuery($query);
            $db->execute();
            //product attributes 
            $query = "SELECT * FROM `#__jshopping_products_attr` WHERE `product_id` = " . (int)$productIdFrom;
            $db->setQuery($query);
            $attributes = $db->loadAssocList();
            
            if (count($attributes)){
                foreach ($attributes as $key => $value){
                    $extAttributeProduct = $this->createExtAttributeProduct($value['ext_attribute_product_id'], $product->product_id);
                    if ($extAttributeProduct){
                        $value['ext_attribute_product_id'] = $extAttributeProduct;
                    } else {
                        $value['ext_attribute_product_id'] = 0;
                    }

                    $db->setQuery($this->copyProductBuildQuery('attr', $value, $product->product_id));
                    $db->execute();
                }
            }
        }
    }
        
    private function createExtAttributeProduct($extProdIdFrom, $newCopyProductId){
        if ($extProdIdFrom > 0){
            $db = \JFactory::getDBO();

            $extAttributeProductFrom = JTable::getInstance('product', 'jshop');
            $extAttributeProductFrom->load($extProdIdFrom);
            
            $extAttributeProduct = JTable::getInstance('product', 'jshop');
            $extAttributeProduct->product_id = null;
            $extAttributeProduct->parent_id = $newCopyProductId;
            $extAttributeProduct->product_is_add_price = $extAttributeProductFrom->product_is_add_price;
            $extAttributeProduct->add_price_unit_id = $extAttributeProductFrom->add_price_unit_id;
            $extAttributeProduct->min_price = $extAttributeProductFrom->min_price;
            $extAttributeProduct->different_prices = $extAttributeProductFrom->different_prices;
            $extAttributeProduct->product_price = $extAttributeProductFrom->product_price;
            $extAttributeProduct->product_buy_price = $extAttributeProductFrom->product_buy_price;
            $extAttributeProduct->store();
            
            $query = "SELECT * FROM `#__jshopping_products_prices` WHERE `product_id` = " . (int)$extProdIdFrom;
            $db->setQuery($query);
            $prices = $db->loadAssocList();

            if (count($prices)){
                foreach ($prices as $value){
                    $db->setQuery($this->copyProductBuildQuery('prices', $value, $extAttributeProduct->product_id));
                    $db->execute();
                }
            }

            return $extAttributeProduct->product_id;
        }
        
        return 0;
    }

    public function addHiddenType(&$attributes)
    {
        foreach($attributes as $key => $attribute) {
            if ($attribute->attr_type == 3) {
                $attributes[$key]->hidden = 1;
            }
        }
    }
	
	public function updatePublish($product_id,$flag){
		$db = \JFactory::getDBO();
		$query = "UPDATE `#__jshopping_products` SET `product_publish` = '" . $db->escape($flag) . "' WHERE `product_id` = '" . $db->escape($product_id) . "'";
		$db->setQuery($query);
		$db->execute();
	}
	
	public function getProductAdditionalInfoById($id,$tables){
		$db = \JFactory::getDBO();
		$array = array();
        foreach($tables as $table){
			$query = "SELECT * FROM `#__jshopping_products_".$table."` AS prod_table WHERE prod_table.product_id = '" . $db->escape($id) . "'";
			$db->setQuery($query);
			$array[] = $db->loadAssocList();
		}
		return $array;
	}
	
	public function copyProductAdditionalInfo($tables,$array,$product_id){
		$db = \JFactory::getDBO();
		$i = 0;
		foreach($array as $key2=>$value2){
			if (count($value2)){
				foreach($value2 as $key3=>$value3){
					$db->setQuery($this->copyProductBuildQuery($tables[$i], $value3, $product_id));
					$db->execute();
				}
			}
			$i++;                
		}
	}
	
	private function productOrderingGetMaxOrdering($category_id){
		$db = \JFactory::getDBO();
		$query = "SELECT *
                       FROM `#__jshopping_products_to_categories` 
					   WHERE category_id='".$category_id."'
					   ORDER BY product_ordering DESC
					   LIMIT 1";
		$db->setQuery($query);
        $row = $db->loadObject();
		return $row->product_ordering;
	}
	
	private function productResetOrderingIfZero($category_id){
		$next_ordering=$this->productOrderingGetMaxOrdering($category_id)+1;		
		$db = \JFactory::getDBO();
		$query = "SELECT *
                       FROM `#__jshopping_products_to_categories`
					   WHERE category_id='".$category_id."' AND
					   product_ordering=0
					   ORDER BY product_ordering ASC";
		$db->setQuery($query);
        $rows = $db->loadObjectList();		
		foreach ($rows as $row){
			$query = "UPDATE `#__jshopping_products_to_categories` AS a
                     SET a.product_ordering = '" . $next_ordering++ . "'
                     WHERE a.product_id = '" . $row->product_id . "' AND a.category_id = '" . $category_id . "'";				 
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	private function productExchangeOrderinginCategory($category_id,$product_id,$number,$row){
		$db = \JFactory::getDBO();
		$query1 = "UPDATE `#__jshopping_products_to_categories` AS a
                     SET a.product_ordering = '" . $row->product_ordering . "'
                     WHERE a.product_id = '" . $product_id . "' AND a.category_id = '" . $category_id . "'";
        $query2 = "UPDATE `#__jshopping_products_to_categories` AS a
                     SET a.product_ordering = '" . $number . "'
                     WHERE a.product_id = '" . $row->product_id . "' AND a.category_id = '" . $category_id . "'";				 
        $db->setQuery($query1);
        $db->execute();
        $db->setQuery($query2);
        $db->execute();
	}
	
	public function productOrderUp($category_id,$product_id,$number){
		$this->productResetOrderingIfZero($category_id);
		$db = \JFactory::getDBO();
		$query = "SELECT a.*
                       FROM `#__jshopping_products_to_categories` AS a
                       WHERE a.product_ordering < '" . $number . "' AND a.category_id = '" . $category_id . "'
                       ORDER BY a.product_ordering DESC
                       LIMIT 1";
		$db->setQuery($query);
        $row = $db->loadObject();
		$this->productExchangeOrderinginCategory($category_id,$product_id,$number,$row);		
	}
	
	public function productOrderDown($category_id,$product_id,$number){
		$this->productResetOrderingIfZero($category_id);
		$db = \JFactory::getDBO();
		$query = "SELECT a.*
                       FROM `#__jshopping_products_to_categories` AS a
                       WHERE a.product_ordering > '" . $number . "' AND a.category_id = '" . $category_id . "'
                       ORDER BY a.product_ordering ASC
                       LIMIT 1";
		$db->setQuery($query);
        $row = $db->loadObject();
		$this->productExchangeOrderinginCategory($category_id,$product_id,$number,$row);		
	}
	
	public function productSetOrdering($category_id,$product_id,$ordering){
		$this->productResetOrderingIfZero($category_id);
		$db = \JFactory::getDBO();
		$query = "UPDATE `#__jshopping_products_to_categories`
                     SET product_ordering = '".intval($ordering)."'
                     WHERE product_id = '".intval($product_id)."' AND category_id = '".intval($category_id)."'";		
		$db->setQuery($query);
		$db->execute();        
	}
	
	public function deleteProductFromTables($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_products` WHERE `product_id` = '".$db->escape($id)."' or `parent_id` = '".$db->escape($id)."' ";
		$db->setQuery($query);
		$db->execute();

		$query = "DELETE FROM `#__jshopping_products_attr` WHERE `product_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();
		
		$query = "DELETE FROM `#__jshopping_products_attr2` WHERE `product_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();
		
		$query = "DELETE FROM `#__jshopping_products_prices` WHERE `product_id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		$db->execute();
		
		$query = "DELETE FROM `#__jshopping_products_relations` WHERE `product_id` = '" . $db->escape($id) . "' OR `product_related_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();

		$query = "DELETE FROM `#__jshopping_products_to_categories` WHERE `product_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		$db->execute();

        $query = "DELETE FROM `#__jshopping_products_media` WHERE `product_id` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
        $db->execute();

        $query = "DELETE FROM `#__jshopping_products_prices_group` WHERE `product_id` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
        $db->execute();

        $query = "DELETE FROM `#__jshopping_sort_val_attrs` WHERE `product_id` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
        $db->execute();

        $query = "DELETE FROM `#__jshopping_products_free_attr` WHERE `product_id` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
        $db->execute();

        $query = "DELETE FROM `#__jshopping_products_images` WHERE `product_id` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
        $db->execute();

        $query = "DELETE FROM `#__jshopping_products_videos` WHERE `product_id` = '" . $db->escape($id) . "'";
        $db->setQuery($query);
        $db->execute();
    }
    
    public function deleteProductsFromTablesByIds(array $ids)
    {
        if (!empty($ids)) {
            $idsImploded = implode(', ', $ids);

            $db = \JFactory::getDBO();
            $query = "DELETE FROM `#__jshopping_products` WHERE `product_id` IN({$idsImploded}) OR `parent_id` IN({$idsImploded})";
            $db->setQuery($query);
            $db->execute();

            $query = "DELETE FROM `#__jshopping_products_attr` WHERE `product_id` IN({$idsImploded})";
            $db->setQuery($query);
            $db->execute();
            
            $query = "DELETE FROM `#__jshopping_products_attr2` WHERE `product_id` IN({$idsImploded})";
            $db->setQuery($query);
            $db->execute();
            
            $query = "DELETE FROM `#__jshopping_products_prices` WHERE `product_id` IN({$idsImploded})";
            $db->setQuery($query);
            $db->execute();
            
            $query = "DELETE FROM `#__jshopping_products_relations` WHERE `product_id` IN({$idsImploded}) OR `product_related_id` IN({$idsImploded})";
            $db->setQuery($query);
            $db->execute();

            $query = "DELETE FROM `#__jshopping_products_to_categories` WHERE `product_id` IN({$idsImploded})";
            $db->setQuery($query);
            $db->execute();
        }
	}
	
	public function copyProductPrices($product, $prices)
    {
        if (!empty($prices)) {
            $db = \JFactory::getDBO();
            
            foreach ($prices as $value) {
                $db->setQuery($this->copyProductBuildQuery('prices', $value, $product->product_id));
                $db->execute();
            }
        }

        return $product->product_id;
	}
	
	public function setDefaultFields(&$product)
    {
        $isEdit = (bool)$product->product_id;  
        $product->product_quantity = $isEdit ? floatval($product->product_quantity) : 1;     

        if (!$isEdit) {
            $product->product_quantity = 1;
            $product->product_publish = 1;
            $product->product_show_cart = 1;
			$product->product_quantity = floatval($product->product_quantity);  
        }	
	}

    public function getAllProductsRows($filter, $limitstart, $limit, $filter_order, $filter_order_Dir)
    {    
		$rows = $this->getAllProducts($filter, $limitstart, $limit, $filter_order, $filter_order_Dir);	
		
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayListProductsBeforeGetAllProductsRows', [&$filter, &$rows]);		
        
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');        

		return $rows;
	}
	
    public function productSave_setPostValues(&$post)
    {
		$post['different_prices'] = 0;
        if (isset($post['product_is_add_price']) && $post['product_is_add_price']) $post['different_prices'] = 1;

        if (!isset($post['max_allow_uploads']) || (isset($post['max_allow_uploads']) && $post['max_allow_uploads'] <= 0)) {
            $post['max_allow_uploads'] = 1;
        }

        if (!isset($post['product_publish'])) $post['product_publish'] = 0;
        if (!isset($post['product_show_cart'])) $post['product_show_cart'] = 0;
        if (!isset($post['product_is_add_price'])) $post['product_is_add_price'] = 0;
        if (!isset($post['unlimited'])) $post['unlimited'] = 0;        
        $post['product_price'] = saveAsPrice($post['product_price']);
        $post['product_old_price'] = saveAsPrice($post['product_old_price']);		
        if (isset($post['product_buy_price']))
            $post['product_buy_price'] = saveAsPrice($post['product_buy_price']);
        else 
            $post['product_buy_price'] = null;
        $post['product_weight'] = saveAsPrice($post['product_weight']);
        if(!isset($post['related_products'])) $post['related_products'] = array();
        if (!$post['product_id']) $post['product_date_added'] = getJsDate();
        if (!isset($post['attrib_price'])) $post['attrib_price'] = null;
        if (!isset($post['attrib_ind_id'])) $post['attrib_ind_id'] = null;
        if (!isset($post['attrib_ind_price'])) $post['attrib_ind_price'] = null;
        if (!isset($post['attrib_ind_price_mod'])) $post['attrib_ind_price_mod'] = null;
        if (!isset($post['freeattribut'])) $post['freeattribut'] = null;
        if (!isset($post['is_allow_uploads'])) $post['is_allow_uploads'] = 0;
        $post['date_modify'] = getJsDate();
        $post['edit'] = intval($post['product_id']);
        if (!isset($post['product_add_discount'])) $post['product_add_discount'] = 0;

        $post['min_price'] = $this->getMinimalPrice($post['product_price'], $post['attrib_price'], array($post['attrib_ind_id'], $post['attrib_ind_price_mod'], $post['attrib_ind_price'], $post['attrib_ind_price_type']), $post['product_is_add_price'], $post['product_add_discount']);
        
        if (isset($post['attr_count']) && is_array($post['attr_count'])){
            $qty = 0;
            foreach($post['attr_count'] as $key => $_qty) {
                $post['attr_count'][$key] = saveAsPrice($_qty);
                if ($_qty > 0) $qty += $post['attr_count'][$key];
            }
            $post['product_quantity'] = $qty;
        }
        
        if ($post['unlimited']){
            $post['product_quantity'] = 1;
        }
        
        $post['product_quantity'] = saveAsPrice($post['product_quantity']);
        
        if (isset($post['productfields']) && is_array($post['productfields'])){
            foreach($post['productfields'] as $productfield=>$val){
                if (is_array($val)){
                    $post[$productfield] = implode(',', $val);
                }
            }
        }
		
		$post['expiration_date'] = $post['expiration_date'] ?: 0;
		
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterproductSave_setPostValues', array(&$post));
	}
	
    public function productSave_checkSetPrice($product)
    {
		$jshopConfig = JSFactory::getConfig();
		if ($product->product_price==0 && !$jshopConfig->user_as_catalog && $product->parent_id==0){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_YOU_NOT_SET_PRICE'), 'error');
        }
	}
	
    public function getProductPackingTypeSelect($product_packing_type, $selectName = 'product_packing_type', $id = false)
    {
		$list = [
            JText::_('COM_SMARTSHOP_DEFAULT'), 
            JText::_('COM_SMARTSHOP_SELL_OFF'), 
            JText::_('COM_SMARTSHOP_BUNDLE')
        ]; 
        $lists = JHTML::_('select.genericlist', $list, $selectName, 'class = "inputbox form-select" size = "1"', $selectName, $selectName, $product_packing_type, $id);

        return $lists;
	}

    public function getProductPackingTypeSelect__editList($product_packing_type = -1, $selectName = 'product_packing_type', $id = false)
    {
		$list = [
            -1 => JHTML::_('select.option', '- - -', '-1', $id, $selectName),
            0 => JText::_('COM_SMARTSHOP_DEFAULT'), 
            1 => JText::_('COM_SMARTSHOP_SELL_OFF'), 
            2 => JText::_('COM_SMARTSHOP_BUNDLE')
        ]; 
        $lists = JHTML::_('select.genericlist', $list, $selectName, 'class = "inputbox form-select" size = "1"', $selectName, $selectName, $product_packing_type, $id);

        return $lists;
	}

    public function addRelatedProducts(int $productId, array $relatedProdsIds): bool
    {
        $isSuccess = true;

        if (!empty($productId) && !empty($relatedProdsIds)) {
            $relatedProdsIds = array_unique($relatedProdsIds);
            $isSuccess = $this->deleteRelatedProductsByProdAndRelatedIds($productId, $relatedProdsIds);

            if ($isSuccess) {
                $db = Factory::getDbo();

                foreach($relatedProdsIds as $key => $relatedProdId) {
                    $query = "INSERT INTO `#__jshopping_products_relations` SET `product_id` = '" . $db->escape($productId) . "', `product_related_id` = '" . $db->escape($relatedProdId) . "'";
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }

        return $isSuccess;
    }

    public function deleteRelatedProductsByProdAndRelatedIds(int $productId, array $relatedProdsIds): bool
    {
        $isSuccess = true;

        if (!empty($productId) && !empty($relatedProdsIds)) {
            $db = Factory::getDbo();
            $queryDel = 'DELETE FROM `#__jshopping_products_relations` WHERE `product_id` = ' . $db->escape($productId) . ' AND `product_related_id` IN(' . implode(',',  $relatedProdsIds) . ')';
            $db->setQuery($queryDel);

            $isSuccess = $db->execute();
        }

        return $isSuccess;
    }
}