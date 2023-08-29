<?php
/**
* @version      4.8.1 09.01.2015
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopVendor extends JTableAvto
{

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_vendors', 'id', $_db);
        JPluginHelper::importPlugin('jshoppingproducts');
    }
    
    public function loadMain()
    {
        $id = JSFactory::getModel('VendorsFront')->getMainVendorId();
        $this->load($id);
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
	
    public function loadFull($id)
    {
        return ($id) ? $this->load($id) : $this->loadMain();
    }
    
    public function check()
    {
        jimport('joomla.mail.helper');
	    $jshopConfig = JSFactory::getConfig();

        $isRequiredFieldsFullFill = true;
        foreach ($jshopConfig->vendor as $fieldName => $fieldInfo) {
            $isRequiredFieldNotFill = $fieldInfo['required'] && empty(trim($this->$fieldName));

            if ($isRequiredFieldNotFill) {
                $isRequiredFieldsFullFill = false;
                throw new \Exception(JText::_($fieldInfo['errorName']));
            }
        }

        if (!$isRequiredFieldsFullFill) {
            return false;
        }

        if ($this->user_id) {
            $xid = JSFactory::getModel('VendorsFront')->getIdWhereNotEqualVendorId((int)$this->user_id, (int)$this->id);
            
            if ($xid) {
                $this->setError(JText::sprintf('COM_SMARTSHOP_ERROR_SET_VENDOR_TO_MANAGER', $this->user_id));
                return false;
            }
        }
        
	    return true;
	}
    
    public function getAllVendors(int $publish = 1, int $limitstart = 0, int $limit = 0): array
    {
        return JSFactory::getModel('VendorsFront')->getAll($publish, $limitstart, $limit);
    }
    
    public function getCountAllVendors(int $publish = 1): int
    {
        return JSFactory::getModel('VendorsFront')->getVendorsCount($publish);
    }
    
    public function getProducts($filters, $order = null, $orderby = null, $limitstart = 0, $limit = 0)
    {
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = $this->getBuildQueryListProductDefaultResult();
        $this->getBuildQueryListProduct('vendor', 'list', $filters, $adv_query, $adv_from, $adv_result);
        
        if ($this->main) {
            $query_vendor_id = "(prod.vendor_id = '{$this->id}' OR prod.vendor_id ='0')";
        } else {
            $query_vendor_id = "prod.vendor_id = '{$this->id}'";
        }
        $order_query = $this->getBuildQueryOrderListProduct($order, $orderby, $adv_from);

        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeQueryGetProductList', ['vendor', &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters]);
        
        $query = "SELECT $adv_result FROM `#__jshopping_products` AS prod
            LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
            LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id                  
            $adv_from
            WHERE {$query_vendor_id} AND prod.product_publish = '1' AND cat.category_publish='1' {$adv_query}
            GROUP BY prod.product_id {$order_query}";

       if ($limit) {
            $this->_db->setQuery($query, $limitstart, $limit);
       } else {
            $this->_db->setQuery($query);
       }

       $products = $this->_db->loadObjectList();
       $products = listProductUpdateData($products);

       return $products;
    }    
    
    public function getCountProducts($filters) 
    {
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = '';
        $this->getBuildQueryListProduct('vendor', 'count', $filters, $adv_query, $adv_from, $adv_result);
        $query_vendor_id = '';
        $currentObj = $this;
        
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeQueryCountProductList', ['vendor', &$adv_result, &$adv_from, &$adv_query, &$filters,&$currentObj,&$query_vendor_id]);
        
        $db = \JFactory::getDBO(); 
        $query = "SELECT COUNT(distinct prod.product_id) FROM `#__jshopping_products` as prod
            LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
            LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
            $adv_from
            WHERE {$query_vendor_id} AND prod.product_publish = '1' AND cat.category_publish='1' {$adv_query}";
        $db->setQuery($query);

        return $db->loadResult();
    }
}