<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopManufacturer extends JTableAvto
{

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_manufacturers', 'manufacturer_id', $_db);
        JPluginHelper::importPlugin('jshoppingproducts');
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

    public function getCountManufacturers(int $publish = 0) 
    {
        return JSFactory::getModel('ManufacturersFront')->getCount($publish)->count;
	}

    public function getAllManufacturers(int $publish = 0, string $order = 'ordering', string $dir = 'asc', int $limitstart = 0, int $limit = 0): array 
    {
        return JSFactory::getModel('ManufacturersFront')->getAllManufacturers($publish, $order, $dir, $limitstart, $limit);
	}
    
    public function getList(): array
    {
        $jshopConfig = JSFactory::getConfig();
        $morder = ($jshopConfig->manufacturer_sorting == 2) ? 'name' : 'ordering';

        return $this->getAllManufacturers(1, $morder, 'asc');
    }
	
    public function getName() 
    {
        $name = JSFactory::getLang()->get('name');
        return $this->$name;
    }
    
    public function getDescription()
    {
        if (!$this->manufacturer_id) {            
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
	
    public function getProducts($filters, $order = null, $orderby = null, int $limitstart = 0, int $limit = 0): array
    {
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = $this->getBuildQueryListProductDefaultResult();

        $this->getBuildQueryListProduct('manufacturer', 'list', $filters, $adv_query, $adv_from, $adv_result);
        $order_query = $this->getBuildQueryOrderListProduct($order, $orderby, $adv_from);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeQueryGetProductList', ['manufacturer', &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters]);
        
        $query = "SELECT $adv_result FROM `#__jshopping_products` AS prod
                  LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id                  
                  $adv_from
                  WHERE prod.product_manufacturer_id = '" . $this->manufacturer_id . "' AND prod.product_publish = '1' AND cat.category_publish = '1' " . $adv_query . " GROUP BY prod.product_id ".$order_query;

        if ($limit) {
            $this->_db->setQuery($query, $limitstart, $limit);
        } else {
            $this->_db->setQuery($query);
        }

        $products = $this->_db->loadObjectList();
        $products = listProductUpdateData($products);
        return $products;
    }    
	
    public function getCountProducts($filters = '') 
    {
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = '';

        $this->getBuildQueryListProduct('manufacturer', 'count', $filters, $adv_query, $adv_from, $adv_result);

        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeQueryCountProductList',  ['manufacturer', &$adv_result, &$adv_from, &$adv_query, &$filters, &$currentObj]);
        
		$db = \JFactory::getDBO(); 
		$query = "SELECT COUNT(distinct prod.product_id) FROM `#__jshopping_products` as prod
                  LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                  $adv_from
                  WHERE prod.product_manufacturer_id = '".$this->manufacturer_id."' AND prod.product_publish = '1' AND cat.category_publish='1' ".$adv_query;
        $db->setQuery($query);
        
		return $db->loadResult();
	}
    
    /**
    * get List category
    */
    public function getCategorys()
    {
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        $lang = JSFactory::getLang();
        $adv_query = "";
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $adv_query .=' AND prod.access IN ('.$groups.') AND cat.access IN ('.$groups.')';
        if ($jshopConfig->hide_product_not_avaible_stock){
            $adv_query .= " AND prod.product_quantity > 0";
        }
        $query = "SELECT distinct cat.category_id as id, cat.`".$lang->get('name')."` as name FROM `#__jshopping_products` AS prod
                  LEFT JOIN `#__jshopping_products_to_categories` AS categ USING (product_id)
                  LEFT JOIN `#__jshopping_categories` as cat on cat.category_id=categ.category_id
                  WHERE prod.product_publish = '1' AND prod.product_manufacturer_id='".$this->_db->escape($this->manufacturer_id)."' AND cat.category_publish='1' ".$adv_query." order by name";
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectList();

        return $list;
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