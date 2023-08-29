<?php
/**
* @version      4.1.0 20.11.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

class jshopCategory extends JTableAvto
{
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_categories', 'category_id', $_db);
        \JPluginHelper::importPlugin('jshoppingproducts');
    }

	public function bind($src, $ignore = Array())
	{
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					$src[$key]=0;
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}
    
    public function getSubCategories($parentId, string $order = 'id', string $ordering = 'asc', int $publish = 0): array
    {
        return JSFactory::getModel('categoriesFront')->getSubCategories($parentId, $order, $ordering, $publish);
    }

    public function getName() 
    {
        $name = JSFactory::getLang()->get('name');
        return $this->$name;
    }
    
    public function getDescription()
    {
        if (!$this->category_id) {
            $this->getDescriptionMainPage();
            return 1; 
        }
        
        $lang = JSFactory::getLang();
        $name = $lang->get('name');        
        $description = $lang->get('description');
        $short_description = $lang->get('short_description');      
        
        $this->name = $this->$name;
        $this->description = $this->$description;
        $this->short_description = $this->$short_description;        
    }    

    public function getTreeChild() 
    {
        $category_parent_id = $this->category_parent_id;
        $i = 0;
        $list_category = [];
        $list_category[$i] = new stdClass();
        $list_category[$i]->category_id = $this->category_id;
        $list_category[$i]->name = $this->name;
        $i++;

        while($category_parent_id) {
            $category = JSFactory::getTable('category', 'jshop');
            $category->load($category_parent_id);
            $list_category[$i] = new stdClass();
            $list_category[$i]->category_id = $category->category_id;
            $list_category[$i]->name = $category->getName();
            $category_parent_id = $category->category_parent_id;
            unset($category);
            $i++;
        }

        $list_category = array_reverse($list_category);
        return $list_category;
    }

    public function getAllCategories(int $publish = 1, int $access = 1): array
    {
        return JSFactory::getModel('CategoriesFront')->getAllCategories($publish, $access);
    }

    public function getChildCategories(string $order = 'id', string $ordering='asc', int $publish = 1)
    {
        return $this->getSubCategories($this->category_id, $order, $ordering, $publish);
    }

    public function getSisterCategories($order, string $ordering = 'asc', int $publish = 1) 
    {
        return $this->getSubCategories($this->category_parent_id, $order, $ordering, $publish);
    }

    public function getTreeParentCategories(int $publish = 1, int $access = 1)
    {
        return JSFactory::getModel('CategoriesFront')->getTreeParentCategories($this->category_id, $publish, $access);
    }

    public function getProducts($filters, $order = null, $orderby = null, $limitstart = 0, $limit = 0) 
    {   
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = $this->getBuildQueryListProductDefaultResult();

        $this->getBuildQueryListProduct('category', 'list', $filters, $adv_query, $adv_from, $adv_result);
        $order_query = $this->getBuildQueryOrderListProduct($order, $orderby, $adv_from);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeQueryGetProductList', ['category', &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters]);

        $products = JSFactory::getModel('ProductsFront')->getProductsToLeftJoinCategoryExtended($this->category_id, $adv_result, $adv_from, $adv_query, $order_query, $limit, (int)$limitstart);
        
        $group_id = (int)JSFactory::getUserShop()->usergroup_id;
        $modelOfProductsPricesGroupFront = JSFactory::getModel('ProductsPricesGroupFront');
		foreach ($products as $key => $value) {
            $group_prices = $modelOfProductsPricesGroupFront->getByProductAndGroupIds($value->product_id, $group_id);
            
			if (!empty($group_prices) && isset($group_prices->price)) {
				$products[$key]->min_price = $group_prices->price;
				$products[$key]->product_price = $group_prices->price;
				$products[$key]->product_old_price = $group_prices->old_price;
			}
		}
        $products = listProductUpdateData($products);

        return $products;
    }

    public function getCountProducts($filters)
    {
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = 'count(*)';

        $this->getBuildQueryListProduct('category', 'count', $filters, $adv_query, $adv_from, $adv_result);

        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeQueryCountProductList', ['category', &$adv_result, &$adv_from, &$adv_query, &$filters, &$currentObj]);

        $query = "SELECT $adv_result FROM `#__jshopping_products_to_categories` AS pr_cat
                  INNER JOIN `#__jshopping_products` AS prod ON pr_cat.product_id = prod.product_id
                  $adv_from 
                  WHERE pr_cat.category_id = '" . $this->_db->escape($this->category_id) . "' AND prod.product_publish = '1' " . $adv_query;
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    }
    
    public function getDescriptionMainPage()
    {}
    
    /**
    * get List Manufacturer for this category
    */
    public function getManufacturers()
    {
        return JSFactory::getModel('ManufacturersFront')->getAllManufacturersByCategoryId($this->category_id);
    }
    
    public function transformDescrTextToModule()
    {
        if (!empty($this->description)) {
            $this->description = JHtml::_('content.prepare', $this->description);
        }

        if (!empty($this->short_description)) {
            $this->short_description = JHtml::_('content.prepare', $this->short_description);
        }
        
    }

}