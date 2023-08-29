<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelSearchFront extends jshopBase
{
    public function generateAdvQueryForResult(string $adv_query, string $searchWord, string $search_type, $date_to, $date_from): string
    {
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();

        if ($date_to && checkMyDate($date_to)) {
            $adv_query .= " AND prod.product_date_added <= '" . $db->escape($date_to) . "'";
        }

        if ($date_from && checkMyDate($date_from)) {
            $adv_query .= " AND prod.product_date_added >= '" . $db->escape($date_from) . "'";
        }
        
        $where_search = '';

        if ($search_type == 'exact') {
            $word = addcslashes($db->escape($searchWord), '_%');
            $tmp = [];

            foreach($jshopConfig->product_search_fields as $field){
                $tmp[] = "LOWER(" . getDBFieldNameFromConfig($field) . ") LIKE '%" . $word . "%'";
            }

            $where_search = implode(' OR ', $tmp);
        } else {        
            $words = explode(' ', $searchWord);
            $search_word = [];

            foreach ($words as $word) {
                $word = addcslashes($db->escape($word), '_%"');
                $tmp = [];

                foreach ($jshopConfig->product_search_fields as $field) {
                    $tmp[] = "LOWER(" . getDBFieldNameFromConfig($field) . ") LIKE '%" . $word . "%'";
                }

                $where_search_block = implode(' OR ', $tmp);
                $search_word[] = '(' . $where_search_block . ')';
            }
            if ($search_type == 'any') {
                $where_search = implode(' OR ', $search_word);
            } else {
                $where_search = implode(' AND ', $search_word);
            }
        }

        if ($where_search) {
            $adv_query .= " AND ($where_search)";
        }

        return $adv_query;
    }

    public function getProductsToLeftJoinCategory(array $columnsToGet = ['*'], string $afterJoin = '', string $afterWhere = '', string $resultView = 'loadObjectList', $offset = 0, $limit = 0, bool $isSearchPublishProd = true, bool $isSearchPublishCategory = true)
    {
        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $isSearchPublishProd = (int)$isSearchPublishProd;
            $isSearchPublishCategory = (int)$isSearchPublishCategory;

            $query = "SELECT {$stringOfSearchColumns} FROM `#__jshopping_products` AS prod
                LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id 
                LEFT JOIN `#__jshopping_products` AS pprod ON pprod.parent_id = prod.product_id {$afterJoin}
                WHERE prod.product_publish = '{$isSearchPublishProd}' AND cat.category_publish = '{$isSearchPublishCategory}' {$afterWhere}";
            $db->setQuery($query, $offset, $limit);

            return $db->$resultView();
        }
    }
    public function getBuildQueryListProduct($type, $restype, &$filters, &$adv_query, &$adv_from, &$adv_result)
    {
        $jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO();
        $originaladvres = $adv_result;

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

            if ($pcolumns['vendor_id'] && isset($filters['vendors']) && $type != 'vendor' && is_array($filters['vendors']) && !empty($filters['vendors'])) {
                $adv_query .= ' AND prod.vendor_id in (' . implode(',', $filters['vendors']) . ')';
            }

            return $adv_query;
        };
        $adv_query .= $generateAdvQuery();

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
                $adv_from2 .= ' LEFT JOIN `#__jshopping_currencies` AS cr ON (cr.currency_id) ';
//                $adv_from2 .= ' LEFT JOIN `#__jshopping_currencies` AS cr USING (currency_id) ';
                if (!empty($price_to)) {
                    $adv_query2 .= " AND (( prod.product_price*{$price_part} / cr.currency_value ) <= {$price_to} OR ( pprod.product_price*{$price_part} / cr.currency_value ) <= {$price_to})";
                }

                if (!empty($price_from)) {
                    $adv_query2 .= " AND (( prod.product_price*{$price_part} / cr.currency_value ) >= {$price_from} OR ( pprod.product_price*{$price_part} / cr.currency_value ) >= {$price_from})";
                }
            } else {
                if (!empty($price_to)) {
                    $adv_query2 .= " AND (prod.product_price*{$price_part} <= {$price_to} OR pprod.product_price*{$price_part} <= {$price_to})";
                }

                if (!empty($price_from)) {
                    $adv_query2 .= " AND (prod.product_price*{$price_part} >= {$price_from} OR pprod.product_price*{$price_part} >= {$price_from} )";
                }
            }
            $adv_query .= ' AND prod.usergroup_show_product<>" " AND (prod.usergroup_show_product="*" OR prod.usergroup_show_product='.$shopUser->usergroup_id.' OR prod.usergroup_show_product  LIKE "% '.$shopUser->usergroup_id.' %" OR prod.usergroup_show_product  LIKE "% '.$shopUser->usergroup_id.'" OR prod.usergroup_show_product  LIKE "'.$shopUser->usergroup_id.' %"  )';

            return $adv_query2;
        };
        $adv_query2 = $generateAdvQuery2();

        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBuildQueryListProductFilterPrice', [$filters, &$adv_query, &$adv_from, &$adv_query2, &$adv_from2]);

        $adv_query .= $adv_query2;
        $adv_from .= $adv_from2;
    }
}