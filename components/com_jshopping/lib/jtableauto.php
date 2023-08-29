<?php
/**
* @version      4.7.0 05.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Table\Table;

abstract class JTableAvto extends Table
{
    public function store($updateNulls = false)
    {
        $defaultTableColumns = array_keys($this->getTableFields()) ?: [];

        if (!empty($defaultTableColumns)) {
            $deletedProperies = [];
            $excluded = [
                '_tbl',
                '_tbl_key',
                '_tbl_keys',
                '_db',
                '_trackAssets',
                '_rules',
                '_locked',
                '_autoincrement',
                '_observers',
                '_columnAlias',
                '_jsonEncode',
                '_errors'
            ];
            $excluded[] = $this->getKeyName();

            foreach($this as $objProportyName => $propertyValue) {
                if (!in_array($objProportyName, $excluded) && !in_array($objProportyName, $defaultTableColumns)) {
                    $deletedProperies[$objProportyName] = $propertyValue;
                    unset($this->$objProportyName);
                }
            }

            $isStored = parent::store($updateNulls);

            if (!empty($deletedProperies)) {
                foreach ($deletedProperies as $objProportyName => $propertyValue) {
                    $this->$objProportyName = $propertyValue;
                }
            }

            return $isStored;
        } 

        return parent::store($updateNulls);
    }

    public function getTableFields($isReload = false, bool $isEscapePrimaryKey = false): array
    {
        static $cache = null;
        $tableName = $this->getTableName();

		if (empty($cache[$tableName]) || $isReload)
		{			
            $fields = $this->_db->getTableColumns($tableName, false);
            $keyName = $this->getkeyName();

			if (empty($fields))
			{
				throw new \UnexpectedValueException(sprintf('No columns found for %s table', $tableName));
            }
            
            if ($isEscapePrimaryKey && !empty($keyName) && isset($fields[$keyName])) {
                unset($fields[$keyName]);
            }

            $cache[$tableName] = $fields;
		}

		return $cache[$tableName] ?: [];
    }

    public function setErrors(array $errors)
    {
        if (!empty($errors)) {
            foreach ($errors as $error) {
                array_push($this->_errors, $error);
            }

            return true;
        }

        return false;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function clearErrors()
    {
        $this->_errors = [];
    }

    public function getLastInsertId(): int
    {
        if (isset($this->_db)) {
            $lastInsertId = $this->_db->insertid();
        }

        return $lastInsertId ?: 0;
    }

    public function getTableColumns(array $excluded = [])
    {
        $columns = JFactory::getDbo()->getTableColumns($this->getTableName());
        $newColumns = [];
        
        if (!empty($excluded)) {
            foreach ($columns as $key => $type) {
                if (!in_array($key, $excluded)) {
                    $newColumns[$key] = $type;
                }
            }
        } else {
            $newColumns = $columns;
        }

        return $newColumns;
    }

    public function getBuildQueryListProductDefaultResult($adfields = [])
    {
        $adquery = '';
        $lang = JSFactory::getLang();
        
		if (!empty($adfields)) {
            $adquery = ',' . implode(', ', $adfields);
        }

        return "prod.min_count_product,prod.unlimited,prod.product_show_cart,prod.low_stock_number,prod.low_stock_notify_status, prod.product_id, pr_cat.category_id, prod.`{$lang->get('name')}` as name, prod.`{$lang->get('short_description')}` as short_description, prod.product_ean, prod.image, prod.product_price, prod.currency_id, prod.product_tax_id as tax_id, prod.product_old_price, prod.product_weight, prod.average_rating, prod.reviews_count, prod.hits, prod.weight_volume_units, prod.basic_price_unit_id, prod.label_id, prod.product_manufacturer_id, prod.min_price, prod.product_quantity, prod.different_prices{$adquery}, prod.preview_total_price, prod.preview_calculated_weight, prod.production_time"; 
    }
    
    public function getBuildQueryListProduct($type, $restype, &$filters, &$adv_query, &$adv_from, &$adv_result)
    {
        $jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO();
        $originaladvres = $adv_result;
		$_user = JSFactory::getUser();
        
        $generateAdvResult = function () use ($jshopConfig) {
            $adv_result = 'prod.preview_total_price, prod.preview_calculated_weight ';

            if ($jshopConfig->delivery_times_on_product_listing) {            
                $adv_result .= ', prod.delivery_times_id';
            }        
    
            if ($jshopConfig->admin_show_product_extra_field) {
                $adv_result .= getQueryListProductsExtraFields();
            }
            $db = & JFactory::getDBO();
            $pcolumns = $db->getTableColumns('#__jshopping_products');
            if ($jshopConfig->product_list_show_vendor && $pcolumns['vendor_id']) {
                $adv_result .= ', prod.vendor_id';
            } 
        };
        $adv_result .= $generateAdvResult();

        $generateAdvQuery = function () use ($filters, $type, $jshopConfig) {
            $user = JFactory::getUser();
            $shopUser = JSFactory::getUser();
		
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $adv_query = '';

            if ($type == 'category') {
                $adv_query .= " AND prod.access IN ({$groups})";
            } else {
                $adv_query .= " AND prod.access IN ({$groups}) AND cat.access IN ({$groups})";
            }
            
            if (($jshopConfig->stock)AND($jshopConfig->hide_product_not_avaible_stock)){
                $adv_query .= ' AND prod.product_quantity > 0';
            }
    
            if (isset($filters['categorys']) && $type != 'category' && is_array($filters['categorys']) && !empty($filters['categorys'])) {
                $adv_query .= ' AND cat.category_id in (' . implode(',', $filters['categorys']) . ')';
            }
    
            if (isset($filters['manufacturers']) && $type != 'manufacturer' && is_array($filters['manufacturers']) && !empty($filters['manufacturers'])) {
                $adv_query .= ' AND prod.product_manufacturer_id in (' . implode(',', $filters['manufacturers']) . ')';
            }    
    
            if (isset($filters['labels']) && is_array($filters['labels']) && !empty($filters['labels'])) {
                $adv_query .= ' AND prod.label_id in (' . implode(',', $filters['labels']) . ')';
            }
            $db = & JFactory::getDBO();
            $pcolumns = $db->getTableColumns('#__jshopping_products');

            if (isset($pcolumns['vendor_id']) && $pcolumns['vendor_id'] && isset($filters['vendors']) && $type != 'vendor' && is_array($filters['vendors']) && !empty($filters['vendors'])) {
                $adv_query .= ' AND prod.vendor_id in (' . implode(',', $filters['vendors']) . ')';
            }  

            if (isset($filters['products_list']) && is_array($filters['products_list']) && !empty($filters['products_list'])) {
                $adv_query .= ' AND prod.product_id in (' . implode(',', $filters['products_list']) . ')';
            }
			
            return $adv_query;
        };
        $adv_query .= $generateAdvQuery();
		if ($_user->usergroup_id) {
			$adv_query .= ' AND (prod.usergroup_show_product = "*" OR prod.usergroup_show_product = "'.$_user->usergroup_id.'" OR prod.usergroup_show_product LIKE "'.$_user->usergroup_id.',%" 
			OR prod.usergroup_show_product LIKE "% '.$_user->usergroup_id.'" OR prod.usergroup_show_product LIKE "% '.$_user->usergroup_id.',%") ';
		}
		
        if (isset($filters['extra_fields']) && is_array($filters['extra_fields'])) {
            foreach($filters['extra_fields'] as $fieldId => $vals) {
                if (is_array($vals) && !empty($vals)) {
                    $tmp = [];

                    foreach($vals as $val_id) {
                        $tmp[] = " find_in_set('{$val_id}', prod.`extra_field_{$fieldId}`) ";
                    }

                    $mchfilterlogic = 'OR';
                    if ($jshopConfig->mchfilterlogic_and[$fieldId]) {
                        $mchfilterlogic = 'AND';
                    }

                    $_tmp_adv_query = implode(" {$mchfilterlogic} ", $tmp);
                    $adv_query .= " AND ({$_tmp_adv_query})";
                } elseif(is_string($vals) && $vals != '') {
                    $adv_query .= " AND prod.`extra_field_{$fieldId}` = '{$db->escape($vals)}'";
                }
            }
        }
        
        $this->getBuildQueryListProductFilterPrice($filters, $adv_query, $adv_from);
        
        if ($jshopConfig->product_list_show_qty_stock) {
            $adv_result .= ', prod.unlimited';
        }
        
        if ($restype == 'count') {
            $adv_result = $originaladvres;
        }    
    }
    
    public function getBuildQueryListProductFilterPrice($filters, &$adv_query, &$adv_from)
    {
        $adv_from2 = '';
        $price_from = 0;
        $price_to = 0;
        $price_part = 1;

        if (isset($filters['price_from'])) {
            $price_from = getCorrectedPriceForQueryFilter($filters['price_from']);
        }

        if (isset($filters['price_to'])) {
            $price_to = getCorrectedPriceForQueryFilter($filters['price_to']);
        }     

        if (!$price_from && !$price_to) {
            return 0;
        }
        
        $jshopConfig = JSFactory::getConfig();
        $userShop = JSFactory::getUserShop();
        $multyCurrency = count(JSFactory::getAllCurrency());

        if ($userShop->percent_discount) {
            $price_part = 1 - $userShop->percent_discount / 100;
        }

        $generateAdvQuery2 = function () use ($multyCurrency, $price_part, $price_to, $price_from, &$adv_from2) {
            $adv_query2 = '';
			$shopUser = JSFactory::getUser();
			
            if ($multyCurrency > 1) {
                $adv_from2 .= ' LEFT JOIN `#__jshopping_currencies` AS cr USING (currency_id) ';
                if (!empty($price_to)) {
                    $adv_query2 .= " AND ( prod.product_price*{$price_part} / cr.currency_value ) <= {$price_to}";
                } 
                
                if (!empty($price_from)) {
                    $adv_query2 .= " AND ( prod.product_price*{$price_part} / cr.currency_value ) >= {$price_from}";
                }
            } else {
                if (!empty($price_to)) {
                    $adv_query2 .= " AND prod.product_price*{$price_part} <= {$price_to}";
                }
    
                if (!empty($price_from)) {
                    $adv_query2 .= " AND prod.product_price*{$price_part} >= {$price_from}";
                }
            }
			$adv_query .= ' AND prod.usergroup_show_product<>" " AND (prod.usergroup_show_product="*" OR prod.usergroup_show_product='.$shopUser->usergroup_id.' OR prod.usergroup_show_product  LIKE "% '.$shopUser->usergroup_id.' %" OR prod.usergroup_show_product  LIKE "% '.$shopUser->usergroup_id.'" OR prod.usergroup_show_product  LIKE "'.$shopUser->usergroup_id.' %"  )';

            return $adv_query2;
        };
        $adv_query2 = $generateAdvQuery2();
        
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBuildQueryListProductFilterPrice', [&$filters, &$adv_query, &$adv_from, &$adv_query2, &$adv_from2]);
        
        $adv_query .= $adv_query2;
        $adv_from .= $adv_from2;
    }
    
    public function getBuildQueryOrderListProduct($order, $orderby, &$adv_from)
    {
        $order_query = '';
        if (!$order) {
            return $order_query;
        }

        $order_original = $order;
        $jshopConfig = JSFactory::getConfig();
        $multyCurrency = count(JSFactory::getAllCurrency());

        if ($multyCurrency > 1 && $order == 'prod.product_price') {
            if (strpos($adv_from, 'jshopping_currencies') === false) {
                $adv_from .= ' LEFT JOIN `#__jshopping_currencies` AS cr USING (currency_id) ';
            }

            $order = 'prod.product_price/cr.currency_value';
            if ($jshopConfig->product_list_show_min_price) {
                $order = 'prod.min_price/cr.currency_value';
            }
        }

        if ($order == 'prod.product_price' && $jshopConfig->product_list_show_min_price) {
            $order = 'prod.min_price';
        }

        $order_query = " ORDER BY {$order}";
        if ($orderby) {
            $order_query .= " {$orderby}";
        }
        
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBuildQueryOrderListProduct', [&$order, &$orderby, &$adv_from, &$order_query, &$order_original]);
        
        return $order_query;
    }
    
    public function getBuildQueryListProductSimpleList($type, $array_categories, &$filters, &$adv_query, &$adv_from, &$adv_result)
    {
        $user = JFactory::getUser();
		$shopUser = JSFactory::getUser();
        $jshopConfig = JSFactory::getConfig();
        $generateAdvResult = function () use ($jshopConfig) {
            $adv_result = ',prod.preview_total_price, prod.preview_calculated_weight ';

            if ($jshopConfig->delivery_times_on_product_listing) {
                $adv_result .= ', prod.delivery_times_id';
            }
    
            if ($jshopConfig->admin_show_product_extra_field){
                $adv_result .= getQueryListProductsExtraFields();
            }
            $db = & JFactory::getDBO();
            $pcolumns = $db->getTableColumns('#__jshopping_products');

            if (isset($pcolumns['vendor_id']) && $pcolumns['vendor_id'] && $jshopConfig->product_list_show_vendor) {
                $adv_result .= ', prod.vendor_id';
            }
    
            if ($jshopConfig->product_list_show_qty_stock) {
                $adv_result .= ', prod.unlimited';
            }
			
            return $adv_result;
        };
        $adv_result .= $generateAdvResult();
              
        $generateAdvQuery = function () use ($array_categories, $jshopConfig, $filters, $user, $shopUser) {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $adv_query = '';

            if (is_array($array_categories) && !empty($array_categories)) {
                $adv_query .= ' AND pr_cat.category_id IN (' . implode(',', $array_categories) . ')';
            }        
            $adv_query .= " AND prod.access IN ({$groups}) AND cat.access IN ({$groups})";
            
            if ($jshopConfig->hide_product_not_avaible_stock) {
                $adv_query .= ' AND prod.product_quantity > 0';
            }

            if (isset($filters['categorys']) && is_array($filters['categorys']) && !empty($filters['categorys'])) {
                $adv_query .= ' AND cat.category_id in (' . implode(',', $filters['categorys']) . ')';
            }

            if (isset($filters['manufacturers']) && is_array($filters['manufacturers']) && !empty($filters['manufacturers'])) {
                $adv_query .= ' AND prod.product_manufacturer_id in (' . implode(',', $filters['manufacturers']) . ')';
            }  

            if (isset($filters['labels']) && is_array($filters['labels']) && !empty($filters['labels'])) {
                $adv_query .= ' AND prod.label_id in (' . implode(',', $filters['labels']) . ')';
            }
            $db = & JFactory::getDBO();
            $pcolumns = $db->getTableColumns('#__jshopping_products');

            if (isset($pcolumns['vendor_id']) && $pcolumns['vendor_id'] && isset($filters['vendors']) && is_array($filters['vendors']) && !empty($filters['vendors'])) {
                $adv_query .= ' AND prod.vendor_id in (' . implode(',', $filters['vendors']) . ')';
            }  

            if (isset($filters['products_list']) && is_array($filters['products_list']) && !empty($filters['products_list'])) {
                $adv_query .= ' AND prod.product_id in (' . implode(',', $filters['products_list']) . ')';
            }  
			
			if ($jshopConfig->is_enabled_usergroup_check_for_get_build_query_list_product_simple_list) {
                $adv_query .= ' AND prod.usergroup_show_product<>" " AND (prod.usergroup_show_product="*" OR prod.usergroup_show_product='.$shopUser->usergroup_id.' OR prod.usergroup_show_product  LIKE "% '.$shopUser->usergroup_id.' %" OR prod.usergroup_show_product  LIKE "% '.$shopUser->usergroup_id.'" OR prod.usergroup_show_product  LIKE "'.$shopUser->usergroup_id.' %"  )';
            }
	
            return $adv_query;
        };
        $adv_query .= $generateAdvQuery();
        
        if (isset($filters['extra_fields']) && is_array($filters['extra_fields'])) {
            $db = \JFactory::getDBO();
            
            foreach($filters['extra_fields'] as $f_id => $vals) {
                if (is_array($vals) && !empty($vals)) {
                    $tmp = [];
                    
                    foreach($vals as $val_id) {
                        $tmp[] = " find_in_set('{$val_id}', prod.`extra_field_{$f_id}`) ";
                    }

                    $mchfilterlogic = 'OR';
                    if ($jshopConfig->mchfilterlogic_and[$f_id]) {
                        $mchfilterlogic = 'AND';
                    }

                    $_tmp_adv_query = implode(" {$mchfilterlogic} ", $tmp);
                    $adv_query .= " AND ({$_tmp_adv_query})";
                }elseif(is_string($vals) && $vals != '') {
                    $adv_query .= " AND prod.`extra_field_{$f_id}`='{$db->escape($vals)}'";
                }
            }
        }        
        $this->getBuildQueryListProductFilterPrice($filters, $adv_query, $adv_from);
    }
}
