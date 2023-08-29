<?php

use Joomla\CMS\Factory;

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/free_attrs_default_values/products_free_attrs_default_values_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/exclude_attribute_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/exclude_buttons_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/min_max_quantity/products_min_max_quantity_mambot.php';

class JshoppingModelProductsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_products';

    public function noticeAdminIfALowAmountOfProducts($orderId): bool
    {
        JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_jshopping/models');
        
        $productsModel = JSFactory::getModel('products');
        $arrWithProductsModelsSelectedByOrderId = $productsModel->getProductsByOrderId($orderId);
        
        if ( !empty($arrWithProductsModelsSelectedByOrderId) ) {

            $bodyEmailText = JText::_('COM_SMARTSHOP_ADMIN_NOTICE_EMAIL_TEXT');
            $nameLang = 'name_' . JComponentHelper::getParams('com_languages')->get('site');
            $isPresentSmallAmountOfProd = false;

            foreach($arrWithProductsModelsSelectedByOrderId as $key => $productModelFromArray) {

                if ( 1 != $productModelFromArray->unlimited && 1 == $productModelFromArray->low_stock_notify_status && $productModelFromArray->product_quantity <= $productModelFromArray->low_stock_number ) {
                    $title = $productModelFromArray->$nameLang ?: $productModelFromArray->{'name_en-GB'};
                    $isPresentSmallAmountOfProd = true;

                    $bodyEmailText .= $productModelFromArray->{'name_en-GB'} . ' ' . sprintf(JText::_('COM_SMARTSHOP_NUMBER_ITEMS_LEFT'), (int)$productModelFromArray->product_quantity) . '<br>';
                    
                }

            }
			$dataForTemplate = array('emailSubject'=>JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_PROD_TITLE'), 'emailBod'=>$bodyEmailText);
			$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
		
            if ( $isPresentSmallAmountOfProd ) {
                $shMailer = new shMailer();
                return $shMailer->sendMailToAdmin(JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_PROD_TITLE'), $bodyEmailText);   
            }    
        }    
        
        return false;
    } 

    public function getByProdId(int $productId)
    {
        return $this->select(['*'], ["`product_id` = {$productId}"], '', false);
    }

    public function getAllIds(): array
    {
        $result = [];
        $ids = $this->select(['product_id']);

        if (!empty($ids)) {
            foreach($ids as $obj) {
                $result[] = $obj->product_id;
            }
        }

        return $result;
    }

    public function getProductsToLeftJoinCategory(array $columnsToGet = ['*'], string $afterJoin = '', string $afterWhere = '', string $resultView = 'loadObjectList', $offset = 0, $limit = 0, bool $isSearchPublishProd = true, bool $isSearchPublishCategory = true)
    {
        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $isSearchPublishProd = (int)$isSearchPublishProd;
            $isSearchPublishCategory = (int)$isSearchPublishCategory;

            $query = "SELECT {$stringOfSearchColumns} FROM `" . static::TABLE_NAME . "` AS prod
                LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id {$afterJoin}
                WHERE prod.product_publish = '{$isSearchPublishProd}' AND cat.category_publish = '{$isSearchPublishCategory}' {$afterWhere}";
            $db->setQuery($query, $offset, $limit);

            return $db->$resultView();
        }
    }

    public function getProductsToLeftJoinCategoryExtended(int $categoryId, string $advResult, string $advFrom = '', string $advQuery = '', string $orderQuery = '', int $limit = 0, int $limitstart = 0)
    {
        $db = \JFactory::getDBO();
        $query = "SELECT {$advResult} FROM `" . static::TABLE_NAME . "` AS prod
            LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
            {$advFrom}
            WHERE pr_cat.category_id = '{$db->escape($categoryId)}' AND prod.product_publish = '1' {$advQuery} {$orderQuery}";
	
        if (!empty($limit)) {
            $db->setQuery($query, $limitstart, $limit);
        } else {
            $db->setQuery($query);
        }

        return $db->loadObjectList();
    }

    public function getCount($filters = null)
    {
        $app = Factory::getApplication();
		$allProducts=$this->getAllProductsCount($filters);
		$rows = [];
		$total=0;
		$modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $allProducts = $modelOfProductsFront->buildProductDataOnFlyForCount($allProducts, false, true, false);
		$app->triggerEvent('onAfterbuildProductDataOnFly', [&$allProducts]);
        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
				$total++;
            }
        }
		return $total;
        /*$table = JSFactory::getTable('Product');
        $advQuery = ''; 
        $advFrom = ''; 
        $advResult = '';

        $currentObj = $this;
        $table->getBuildQueryListProduct('products', 'count', $filters, $advQuery, $advFrom, $advResult);
        \JFactory::getApplication()->triggerEvent('onBeforeQueryCountProductList', ['all_products', &$advResult, &$advFrom, &$advQuery, &$filters, &$currentObj]);
        
        $db = \JFactory::getDBO(); 
        $query = "SELECT COUNT(distinct prod.product_id) FROM `" . static::TABLE_NAME . "` as prod
            LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
            LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
            {$advFrom}
            WHERE prod.product_publish = '1' AND cat.category_publish='1' {$advQuery}";
        $db->setQuery($query);

        return $db->loadResult();
		*/
    }

    public function getLastProducts(?int $count = null, $categ = null, $filters = [])
    {
        $table = JSFactory::getTable('Product');
        $advQuery = ''; 
        $advFrom = ''; 
        $adv_result = $table->getBuildQueryListProductDefaultResult();
        $orderQuery = 'ORDER BY prod.product_id';
        
        $table->getBuildQueryListProductSimpleList('last', $categ, $filters, $advQuery, $advFrom, $adv_result);

        \JFactory::getApplication()->triggerEvent('onBeforeQueryGetProductList', ['last_products', &$adv_result, &$advFrom, &$advQuery, &$orderQuery, &$filters]);

        $limitStr = isset($count) ? ' LIMIT ' . $count : '';

        $db = \JFactory::getDBO();
        $query = "SELECT {$adv_result} FROM `#__jshopping_products` AS prod
            INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
            LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
            {$advFrom}
            WHERE prod.product_publish = '1' AND cat.category_publish='1' {$advQuery}
            GROUP BY prod.product_id {$orderQuery} DESC {$limitStr}";
        $db->setQuery($query);
        $products = $db->loadObjectList();

        return listProductUpdateData($products);
    }

    public function getRandProducts($count, $array_categories = null, $filters = [])
    {
        $table = JSFactory::getTable('Product');
        $advQuery = ''; 
        $advFrom = ''; 
        $advSelect = $table->getBuildQueryListProductDefaultResult();
        $orderQuery = '';

        $table->getBuildQueryListProductSimpleList('rand', $array_categories, $filters, $advQuery, $advFrom, $advSelect);
        \JFactory::getApplication()->triggerEvent( 'onBeforeQueryGetProductList', ['rand_products', &$advSelect, &$advFrom, &$advQuery, &$orderQuery, &$filters]);

        $totalrow = $this->getCount($advFrom, $advQuery);             
        $totalrow = $totalrow - $count;

        if ($totalrow < 0) {
            $totalrow = 0;
        }

        $limitstart = rand(0, $totalrow);
        
        $order = [
            'name asc',
            'name desc',
            'prod.product_price asc',
            'prod.product_price desc'
        ];
        $orderby = $order[rand(0,3)];
        
        $db = \JFactory::getDBO();
        $query = "SELECT {$advSelect} FROM `#__jshopping_products` AS prod
                  INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                  {$advFrom}
                  WHERE prod.product_publish = '1' AND cat.category_publish='1' {$advQuery}
                  GROUP BY prod.product_id order by {$orderby}";
        
        if ($count !== null) {
            $db->setQuery($query, $limitstart, $count);
        } else {
            $db->setQuery($query);
        }
        $products = $db->loadObjectList();

        return listProductUpdateData($products);        
    }

    public function getBestSellers(?int $count = null, $categories = null, $filters = [])
    {
        $table = JSFactory::getTable('Product');
        $advQuery = ''; 
        $advFrom = ''; 
        $order_query = '';
        $advSelect = $table->getBuildQueryListProductDefaultResult();

        $table->getBuildQueryListProductSimpleList('best', $categories, $filters, $advQuery, $advFrom, $advSelect);
        \JFactory::getApplication()->triggerEvent('onBeforeQueryGetProductList', ['bestseller_products', &$advSelect, &$advFrom, &$advQuery, &$order_query, &$filters]);

        $countStr = isset($count) ? ' LIMIT ' . $count : '';

        $db = \JFactory::getDBO();
        $query = "SELECT SUM(OI.product_quantity) as max_num, {$advSelect} FROM #__jshopping_order_item AS OI
                INNER JOIN `#__jshopping_products` AS prod   ON prod.product_id=OI.product_id
                INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
                {$advFrom}
                WHERE prod.product_publish = '1' AND cat.category_publish='1' {$advQuery}
                GROUP BY prod.product_id
                ORDER BY max_num desc {$countStr}";
        $db->setQuery($query);
        $products = $db->loadObjectList();

        return listProductUpdateData($products);
    }

    public function getProdsWithLabels(?int $label_id = null, ?int $count = null, $categories = null, $filters = [], $orderQuery = 'ORDER BY name')
    {
        $db = \JFactory::getDBO();

        $table = JSFactory::getTable('Product');
        $advQuery = ''; 
        $advFrom = ''; 
        $advSelect = $table->getBuildQueryListProductDefaultResult();
        $table->getBuildQueryListProductSimpleList('label', $categories, $filters, $advQuery, $advFrom, $advSelect);

        if ($label_id) {
            $advQuery .= " AND prod.label_id = '{$db->escape($label_id)}'";
        }

        \JFactory::getApplication()->triggerEvent('onBeforeQueryGetProductList', ['label_products', &$advSelect, &$advFrom, &$advQuery, &$orderQuery, &$filters]);
 
        $countStr = isset($count) ? ' LIMIT ' . $count : '';

        $db = \JFactory::getDBO();
        $query = "SELECT {$advSelect} FROM `#__jshopping_products` AS prod
            INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
            LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
            {$advFrom}
            WHERE prod.product_publish = '1' and prod.label_id != 0 AND cat.category_publish='1' {$advQuery}
            GROUP BY prod.product_id {$orderQuery} {$countStr}";
        $db->setQuery($query);
        $products = $db->loadObjectList();

        return listProductUpdateData($products);
    }

    public function getTopRatingProds(?int $count = null, $categories = null, $filters = [])
    {
        $table = JSFactory::getTable('Product');
        $advQuery = '';
        $advFrom = '';
        $advSelect = $table->getBuildQueryListProductDefaultResult();
        $order_query = '';

        $table->getBuildQueryListProductSimpleList('toprating', $categories, $filters, $advQuery, $advFrom, $advSelect);
        \JFactory::getApplication()->triggerEvent('onBeforeQueryGetProductList', ['top_rating_products', &$advSelect, &$advFrom, &$advQuery, &$order_query, &$filters]);
 
        $countStr = isset($count) ? ' LIMIT ' . $count : '';

        $db = \JFactory::getDBO();
        $query = "SELECT {$advSelect} FROM `#__jshopping_products` AS prod
            INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
            LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
            {$advFrom}
            WHERE prod.product_publish = '1' AND cat.category_publish='1' {$advQuery}
            GROUP BY prod.product_id ORDER BY prod.average_rating desc {$countStr}";
        $db->setQuery($query);
        $products = $db->loadObjectList();

        return listProductUpdateData($products);
    }

    public function getTopHitsProds(?int $count = null, $categories = null, $filters = [])
    {
        $table = JSFactory::getTable('Product');
        $advQuery = ''; 
        $advFrom = ''; 
        $advSelect = $table->getBuildQueryListProductDefaultResult();
        $order_query = '';

        $table->getBuildQueryListProductSimpleList('tophits', $categories, $filters, $advQuery, $advFrom, $advSelect);
        \JFactory::getApplication()->triggerEvent( 'onBeforeQueryGetProductList', ['top_hits_products', &$advSelect, &$advFrom, &$advQuery, &$order_query, &$filters]);
 
        $countStr = isset($count) ? ' LIMIT ' . $count : '';

        $db = \JFactory::getDBO();
        $query = "SELECT {$advSelect} FROM `#__jshopping_products` AS prod
            INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
            LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
            {$advFrom}
            WHERE prod.product_publish = '1' AND cat.category_publish='1' {$advQuery}
            GROUP BY prod.product_id ORDER BY prod.hits desc {$countStr}";
        $db->setQuery($query);
        $products = $db->loadObjectList();

        return listProductUpdateData($products);
    }

    public function getAllProducts(array $filters = [], $order = null, $orderby = null, $limitstart = 0, $limit = 0)
    {
        $table = JSFactory::getTable('Product');
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = $table->getBuildQueryListProductDefaultResult();

        $table->getBuildQueryListProduct('products', 'list', $filters, $adv_query, $adv_from, $adv_result);
        $order_query = $table->getBuildQueryOrderListProduct($order, $orderby, $adv_from);
        \JFactory::getApplication()->triggerEvent('onBeforeQueryGetProductList', ['all_products', &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters]);
		
		$userShop = \JSFactory::getUserShop();		
        $adv_query .= 'AND (prod.usergroup_show_product LIKE "% '.(int)$userShop->usergroup_id.' %" OR prod.usergroup_show_product LIKE "% '.(int)$userShop->usergroup_id.'" OR prod.usergroup_show_product LIKE "'.(int)$userShop->usergroup_id.' %" OR prod.usergroup_show_product LIKE "'.(int)$userShop->usergroup_id.'" OR prod.usergroup_show_product LIKE "*") '; 
		$adv_result.= ',prod.usergroup_show_product';
        
        $afterWhere = "{$adv_query} GROUP BY prod.product_id {$order_query}";
        $products = $this->getProductsToLeftJoinCategory([$adv_result], $adv_from, $afterWhere, 'loadObjectList', $limitstart, $limit);
        
		$group_id = (int)JSFactory::getUser()->usergroup_id;
        $products = JSFactory::getModel('ProductsPricesGroupFront')->updateProdPricesToGroupPrices($products, $group_id);

		return listProductUpdateData($products);
    } 
	
    public function getAllProductsCount(array $filters = [], $order = null, $orderby = null, $limitstart = 0, $limit = 0)
    {
        $table = JSFactory::getTable('Product');
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = $table->getBuildQueryListProductDefaultResult();

        $table->getBuildQueryListProduct('products', 'list', $filters, $adv_query, $adv_from, $adv_result);
        $order_query = $table->getBuildQueryOrderListProduct($order, $orderby, $adv_from);
        \JFactory::getApplication()->triggerEvent('onBeforeQueryGetProductList', ['all_products', &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters]);
		
		$userShop = \JSFactory::getUserShop();		
        $adv_query .= 'AND (prod.usergroup_show_product LIKE "% '.(int)$userShop->usergroup_id.' %" OR prod.usergroup_show_product LIKE "% '.(int)$userShop->usergroup_id.'" OR prod.usergroup_show_product LIKE "'.(int)$userShop->usergroup_id.' %" OR prod.usergroup_show_product LIKE "'.(int)$userShop->usergroup_id.'" OR prod.usergroup_show_product LIKE "*") '; 
		$adv_result.= ',prod.usergroup_show_product';
        
        $afterWhere = "{$adv_query} GROUP BY prod.product_id {$order_query}";
        $products = $this->getProductsToLeftJoinCategory([$adv_result], $adv_from, $afterWhere, 'loadObjectList', $limitstart, $limit);
        
		//$group_id = (int)JSFactory::getUser()->usergroup_id;
        //$products = JSFactory::getModel('ProductsPricesGroupFront')->updateProdPricesToGroupPrices($products, $group_id);
		return $products;
    } 
	

    public function getCustom($count, $categories = null, $filters = [], $order_query = 'ORDER BY name')
    {
        $table = JSFactory::getTable('Product');
        $db = \JFactory::getDBO();

        $advQuery = ''; 
        $advFrom = ''; 
        $advResult = $table->getBuildQueryListProductDefaultResult();

        $table->getBuildQueryListProductSimpleList('rand', $categories, $filters, $advQuery, $advFrom, $advResult);
        \JFactory::getApplication()->triggerEvent('onBeforeQueryGetProductList', ['rand_products', &$advResult, &$advFrom, &$advQuery, &$order_query, &$filters]);
        
        $query = "SELECT {$advResult} FROM `#__jshopping_products` AS prod
            INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
            LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
            {$advFrom}
            WHERE prod.product_publish = '1' AND cat.category_publish='1' {$advQuery}
            GROUP BY prod.product_id {$order_query} LIMIT {$count}";
        $db->setQuery($query);
        $products = $db->loadObjectList();
        $products = listProductUpdateData($products);
        return $products;
    }

    /*
    TODO: Not used.

    public function calcTotalPriceByProductId(int $productId, int $productQty = 1, bool $isUseMinQtyIfExists = true)
    {
        $calculatedPrice = $this->calculateProductDataByProductId($productId, $productQty, null, $isUseMinQtyIfExists)['calculatedPrice'];
        return $calculatedPrice;
    }
    */

    public function calculateProductDataByProductId(int $productId, int $productQty = 1, ?float $ownPrice = null, bool $isUseMinQtyIfExists = true, $front = true): array
    {
        $repository = JSFactory::getRepository();
        $product = JSFactory::getTable('product', 'jshop');
        $modelOfFreeAttrsFront = JSFactory::getModel('freeAttrsFront');
        $modelOfAttrsFront = JSFactory::getModel('AttrsFront');

        $product->load($productId,true,$front);

        if (isset($ownPrice)) {
            $product->product_price = $ownPrice;
        }

        $attributesDatas = $product->getAttributesDatas();

        $repoData = $repository->get($attributesDatas['attributeActive'], true);
        if (isset($repoData)) {
            $excludeAttrs = $repoData;
        } else {
            $excludeAttrs = $modelOfAttrsFront->excludeAttrs($attributesDatas['attributeActive']);
            $repository->set($attributesDatas['attributeActive'], $excludeAttrs, true);
        }
        unset($repoData);

        $product->setAttributeActive($excludeAttrs);
        $product->getTax();
        $modelOfFreeAttrsFront->addFreeAttrsToProd($product);
        $productQty = ($isUseMinQtyIfExists && !empty($product->min_count_product)) ? $product->min_count_product : $productQty;
        $product->getPrice($productQty, 1, 1, 1);
        $pricefloat = $product->total_price_without_tax;

        $prodAttr2Table = JSFactory::getTable('ProductAttribut2');
        $calculatedPrice = $prodAttr2Table->calcAttrsWithOneTimeCostPriceType($productId, $attributesDatas['attributeActive'], $pricefloat * $productQty,0);

        return [
            'product' => $product, 
            'calculatedPrice' => $calculatedPrice
        ];
    }

    public function buildProductDataOnFly(?array $products, bool $isUseMambots = false, $isUseUserGroupPermissions = false, $firstInput = true)
    {
        if (!empty($products)) {
            $jshopConfig = JSFactory::getConfig();
            $user = JSFactory::getUser();

            foreach ($products as $key => $loopProduct) {
                ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeLoadProduct($loopProduct->product_id, null, []);
                
                $product = JSFactory::getTable('product', 'jshop');
                $product->load($loopProduct->product_id);
                $product->preview_total_price = $loopProduct->preview_total_price;
                foreach ($products[$key] as  $k => $value) {
                    $product->$k = $products[$key]->$k;				
                }

                $attributesDatas = $isUseUserGroupPermissions ? $product->getAttributesDatas([], $user->usergroup_id) : $product->getAttributesDatas();
                                
                $attributeValues = $attributesDatas['attributeValues'];
                $allAttrValues = [];

                $attributes = $jshopConfig->productlist_allow_buying ? $product->getBuildSelectAttributes($attributeValues, $attributesDatas['attributeSelected'], true) : [];
                if (!empty($attributes)) {
                    $attrValueTable = JSFactory::getTable('AttributValue', 'jshop');
                    $allAttrValues = $attrValueTable->getAllAttributeValues(0,$product->product_id);   
                }
			
                $product->setAttributeActive($attributesDatas['attributeSelected']);
				JSFactory::getModel('ProductsPricesGroupFront')->updateProductPriceByUserGroupPrice($product,1);
                $product->attributes = $attributes;
                $product->allAttrValues = $allAttrValues;
                
                $product->extra_field = null;
                if ($jshopConfig->admin_show_product_extra_field) {
                    $product->extra_field = $product->getExtraFields();
                }
                //if($loopProduct->product_id == 52){print_r($product);die;}
                $product->getExtendsData($firstInput);
                $product->short_description = str_replace('src="images/','src="/images/', $product->short_description);
                
                $product->freeattributes = null;
                $product->freeattribrequire = 0;
                if ($jshopConfig->admin_show_freeattributes) {
                    $product->getListFreeAttributes();
                    $product->freeattribrequire = count($product->getRequireFreeAttribute());
                }
                if($jshopConfig->product_list_show_weight && ((float)$product->preview_calculated_weight > 0 || (float)$product->getWeight() > 0)) {
                    $product->weight = formatweight($product->preview_calculated_weight ?? $product->getWeight());
                }
				
				if ($jshopConfig->product_list_show_qty_stock) {
                    $products[$key]->qty_in_stock = getDataProductQtyInStock($product);
                    $product->qty_in_stock = getDataProductQtyInStock($product);
                }
                $product->getBasicPriceInfo();
                if ($isUseMambots) {
                    $view = new JViewLegacy();
                    $view->set('rows', [$product]);

                    ExcludeAttributeForAttribute::getInstance()->onBeforeDisplayProductListView($view);
		            ExcludeButtonsForAttribute::getInstance()->onBeforeDisplayProductListView($view);        
                    ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplayProductListView($view);
                    ProductsMinMaxQuantityMambot::getInstance()->onBeforeDisplayProductListView($view);

                    $product = $view->product;
                }
				$default_count_product = $products[$key]->default_count_product ?? 1;
				$products[$key]->default_count_product = corectDefaultCount($products[$key], $default_count_product);
			
				
				
                $products[$key] = $product;
            }
        }

        return $products;
    }
	
	    public function buildProductDataOnFlyForCount(?array $products, bool $isUseMambots = false, $isUseUserGroupPermissions = false, $firstInput = true)
    {
        if (!empty($products)) {
            $jshopConfig = JSFactory::getConfig();
            $user = JSFactory::getUser();

            foreach ($products as $key => $loopProduct) {
                ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeLoadProduct($loopProduct->product_id, null, []);
                
                $product = JSFactory::getTable('product', 'jshop');
                $product->load($loopProduct->product_id);
                $product->preview_total_price = $loopProduct->preview_total_price;
                foreach ($products[$key] as  $k => $value) {
                    $product->$k = $products[$key]->$k;				
                }

				if ($jshopConfig->product_list_show_qty_stock) {
                    $products[$key]->qty_in_stock = getDataProductQtyInStock($product);
                    $product->qty_in_stock = getDataProductQtyInStock($product);
                }

                if ($isUseMambots) {
                    $view = new JViewLegacy();
                    $view->set('rows', [$product]);

                    ExcludeAttributeForAttribute::getInstance()->onBeforeDisplayProductListView($view);
		            ExcludeButtonsForAttribute::getInstance()->onBeforeDisplayProductListView($view);        
                    ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplayProductListView($view);
                    ProductsMinMaxQuantityMambot::getInstance()->onBeforeDisplayProductListView($view);

                    $product = $view->product;
                }
				$products[$key]->default_count_product = corectDefaultCount($products[$key], $products[$key]->default_count_product ?? 1);
				
                $products[$key] = $product;
            }
        }

        return $products;
    }

    public function calcPreviewDataForAllProds(): bool
    {
        $allProdsIds = $this->getAllIds();

        if (!empty($allProdsIds)) {
            $db = \JFactory::getDBO();
            
            foreach ($allProdsIds as $productId) {
                $calculatedProductData = $this->calculateProductDataByProductId($productId);
                $query = 'UPDATE ' . static::TABLE_NAME . " SET `preview_total_price` = {$calculatedProductData['calculatedPrice']}, `preview_calculated_weight` = {$calculatedProductData['product']->getWeight()} WHERE `product_id` = {$productId}; ";

                $db->setQuery($query);
                $db->execute();
            }

            return true;
        }

        return false;
    }

    public function getProductWithDefaultAttrs(int $productId, $params = [])
    {
        static $productsCache = [];

        if (!isset($productsCache[$productId])) {
            $productTable = JSFactory::getTable('product', 'jshop');
            $productTable->load($productId);	
            $attributesDatas = $productTable->getAttributesDatas([], $params['usergroup_id'] ?? null);
            $productTable->setAttributeActive($attributesDatas['attributeActive']);
            $productsCache[$productId] = $productTable;
        }

        return $productsCache[$productId];
    }
}