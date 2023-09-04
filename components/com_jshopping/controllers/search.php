<?php
/**
* @version      4.9.0 10.08.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/free_attrs_default_values/products_free_attrs_default_values_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/exclude_attribute_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/exclude_buttons_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/min_max_quantity/products_min_max_quantity_mambot.php';

class JshoppingControllerSearch extends JshoppingControllerBase
{
    public const CONTEXT_DISPLAY = 'jshoping.search.front';
    public const CONTEXT_RESULT = 'jshoping.searclist.front.product';
    
    public function __construct($config = [])
    {
        parent::__construct($config);
        JPluginHelper::importPlugin('jshoppingproducts');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerSearch', [&$currentObj]);
		setSeoMetaData();
    }
    
    public function display($cachable = false, $urlparams = false)
    {
    	$jshopConfig = JSFactory::getConfig();
    	if (method_exists('JHtmlBehavior', 'calendar')) {
            JHtmlBehavior::calendar();
        }
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams();
        $Itemid = JFactory::getApplication()->input->getInt('Itemid');
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadSearchForm', []);
        $seo = JSFactory::getTable('seo', 'jshop');
        $seodata = $seo->loadData('search');
        $document = JFactory::getDocument();
        $change_cat_val = '';

        if (getThisURLMainPageShop()) {
            appendPathWay(JText::_('COM_SMARTSHOP_SEARCH'));
            
            if (isset($seodata->title) && empty($seodata->title)) {
                $seodata->title = JText::_('COM_SMARTSHOP_SEARCH');
            }         
        }
        
		setSeoMetaData($seodata->title ?? '');
		
        if ($jshopConfig->admin_show_product_extra_field) {
            $urlsearchcaracters = '/index.php?option=com_jshopping&controller=search&task=get_html_characteristics&ajax=1';
            $change_cat_val = "onchange='updateSearchCharacteristic(\"" . $urlsearchcaracters . "\",this.value);'";
        }

		$categories = buildTreeCategory(1);
        $categoriesSelectMarkup = generateCategoryListHtmlSelect($categories, $change_cat_val);
		
        $manufacturers = JSFactory::getTable('manufacturer', 'jshop')->getList();
        $manufacturersSelectMarkup = generateManufacturerHtmlSelect($manufacturers, 0, 'manufacturer_id', 'size = "1"');
        
        $document->addScriptDeclaration('var liveurl = "' . JURI::root() . '";');

        loadJSLanguageKeys();

        $view = $this->getView('search', getDocumentType(), '', [
            'template_path' => viewOverride('search', 'form.php')
        ]);
        $layout = getLayoutName('search', 'form');
        $view->setLayout($layout);

        $view = $this->getView('search', getDocumentType(), '', [
            'template_path' => viewOverride('search', 'form.php')
        ]);
        $view->set('component', 'Form_search');
		$view->set('list_categories', $categoriesSelectMarkup);
        $view->set('list_manufacturers', $manufacturersSelectMarkup);
        $view->set('config', $jshopConfig);
        $view->set('Itemid', $Itemid);
		$view->set('action', SEFLink('index.php?option=com_jshopping&controller=search&task=result'));
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $dispatcher->triggerEvent('onBeforeDisplaySearchFormView', [&$view]);
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print_r(json_encode(prepareView($view)));die;}
		$view->display();
    }
    
    public function result()
    {
        JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models'); 

        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $document = JFactory::getDocument();
        $session = JFactory::getSession();
        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $ajax = JFactory::getApplication()->input->getInt('ajax', 0);
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);
        $_production_calendar = JModelLegacy::getInstance('production_calendar', 'JshoppingModel')->getParams();
        $params = $mainframe->getParams();

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);
        
        $product = JSFactory::getTable('product', 'jshop');
        $seo = JSFactory::getTable('seo', 'jshop');
        $seodata = $seo->loadData('search-result');

        $post = JFactory::getApplication()->input->post->getArray();
		$get = JFactory::getApplication()->input->get->getArray();
		if (isset($get['search'])) $post=$get;
		
        $category_id = intval($post['category_id']);
        $manufacturer_id = intval($post['manufacturer_id']);
        $date_to = $post['date_to'] ?? null;
        $date_from = $post['date_from'] ?? null;
        $price_to = $post['price_to'] ?? null;
        $price_from = isset($post['price_from']) ? $post['price_from'] : null;
        $include_subcat = isset($post['include_subcat']) ? intval($post['include_subcat']) : 0;
        $search = trim($post['search']);
        $search_type = $post['search_type'] ?: 'any';

        if (getThisURLMainPageShop()){
            appendPathWay(JText::_('COM_SMARTSHOP_SEARCH'));

            if (!isset($seodata->title) || empty($seodata->title)) {
                $seodata->title = JText::_('COM_SMARTSHOP_SEARCH');
            }
        }
		setSeoMetaData($seodata->title ?? '');
        if (isset($post['setsearchdata']) && $post['setsearchdata'] == 1) {
            $session->set('jshop_end_form_data', $post);
        } else {
            if (!isset($post['search'])) $data = $session->get('jshop_end_form_data');

            if (!empty($data)) {
                $post = $data;

                $category_id = intval($post['category_id']);
                $manufacturer_id = intval($post['manufacturer_id']);
                $date_to = $post['date_to'] ?? null;
                $date_from = $post['date_from'] ?? null;
                $price_to = $post['price_to'] ?? null;
                $price_from = isset($post['price_from']) ? $post['price_from'] : null;
                $include_subcat = isset($post['include_subcat']) ? intval($post['include_subcat']) : 0;
                $search = trim($post['search']);
                $search_type = $post['search_type'] ?: 'any';
            }
        }

        $orderby = $mainframe->getUserStateFromRequest(self::CONTEXT_RESULT . 'orderby', 'orderby', $jshopConfig->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest(self::CONTEXT_RESULT . 'order', 'order', $jshopConfig->product_sorting, 'int');
        $order = ($order == 4) ? 1 : $order;
        $limit = $mainframe->getUserStateFromRequest(self::CONTEXT_RESULT . 'limit', 'limit', $jshopConfig->count_products_to_page, 'int');

        if (!$limit) {
            $limit = $jshopConfig->count_products_to_page;
        }

        $extra_fields = [];
        if ($jshopConfig->admin_show_product_extra_field) {
            if (isset($post['extra_fields'])) {
                $extra_fields = $post['extra_fields'];
            }
                
            $extra_fields = filterAllowValue($extra_fields, 'array_int_k_v+');
        }
        
        $categorys = [];
        if ($category_id) {
            if ($include_subcat) { 
                $_category = JSFactory::getTable('category', 'jshop');
                $all_categories = $_category->getAllCategories();
                $cat_search[] = $category_id;
                searchChildCategories($category_id, $all_categories, $cat_search);

                foreach ($cat_search as $value) {
                    $categorys[] = $value;
                }
            } else {
                $categorys[] = $category_id;
            }
        }

        $orderbyq = getQuerySortDirection($order, $orderby);
        $image_sort_dir = getImgSortDirection($order, $orderby);
        
        $filters = [];
        $filters['categorys'] = $categorys;
        if ($manufacturer_id) {
            $filters['manufacturers'][] = $manufacturer_id;
        }

        $filters['price_from'] = $price_from;
        $filters['price_to'] = $price_to;

        if ($jshopConfig->admin_show_product_extra_field) {
            $filters['extra_fields'] = $extra_fields;
        }

        $modelOfSearchFront = JSFactory::getModel('SearchFront');
        $adv_query = ''; 
        $adv_from = ''; 
        $adv_result = $product->getBuildQueryListProductDefaultResult();
        $modelOfSearchFront->getBuildQueryListProduct('search', 'list', $filters, $adv_query, $adv_from, $adv_result);

        $adv_query = $modelOfSearchFront->generateAdvQueryForResult($adv_query, $search, $search_type, $date_to, $date_from);

        $orderbyf = $jshopConfig->sorting_products_field_s_select[$order];
        $order_query = $product->getBuildQueryOrderListProduct($orderbyf, $orderbyq, $adv_from);

        $dispatcher->triggerEvent('onBeforeQueryGetProductList', ['search', &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters]);

        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $total = $modelOfSearchFront->getProductsToLeftJoinCategory(['count(distinct prod.product_id)'], $adv_from, $adv_query, 'loadResult');
        $href_search = SEFLink('index.php?option=com_jshopping&controller=search');

        loadJSLanguageKeys();
        if (empty($total)) {

            $view = $this->getView('search', getDocumentType(), '', [
                'template_path' => viewOverride('search', 'noresult.php')
            ]);
            $layout = getLayoutName('search', 'noresult');
            $view->setLayout($layout);

            $view->set('component', 'Noresult_search');
            $view->set('search', $search);
            $view->set('href_search', $href_search);
            $view->set('sef', JFactory::getConfig()->get('sef'));
            $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
            if($ajax){ print_r(json_encode(prepareView($view)));die;}
            $view->display();

            return 0;
        }
        
		$dispatcher->triggerEvent('onBeforeFixLimitstartDisplayProductList', [&$limitstart, &$total, 'search']);
        if ($limitstart >= $total) {
            $limitstart = 0;
        }

        $allProducts = $modelOfSearchFront->getProductsToLeftJoinCategory([$adv_result], $adv_from, $adv_query . ' GROUP BY prod.product_id ' . $order_query, 'loadObjectList', $limitstart, $limit);
        $allProducts = JSFactory::getTable('product', 'jshop')->addObjectFunction($allProducts);

        $allProducts = listProductUpdateData($allProducts);
        addLinkToProducts($allProducts, 0, 1);
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true);

        $rows = [];

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;

            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }

        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();
        $pagenavdata = _buildPaginationDataObject($total, $limitstart, $limit);
        
        $sorting_sel = generateSortingHtmlSelectForProduct($order, 2);
        $product_count_sel = generateProductHtmlCountSelectFilter($limit, $jshopConfig->count_products_to_page);
        $allowReview = JSFactory::getTable('review', 'jshop')->getAllowReview();
        
        $action = xhtmlUrl($_SERVER['REQUEST_URI']);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        $display_price = getDisplayPriceForListProduct();
        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');

        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        transformDescrsTextsToModule($rows);

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('search', 'products.php')
        ]);
		$layout = getLayoutName('search', 'products');
        $view->setLayout($layout);

        $view->set('component', 'Products_search');
        $view->set('search', $search);
        $view->set('total', $total);
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', 'list_products/list_products.php');
        $view->set('template_block_pagination', 'list_products/block_pagination.php');
        $view->set('path_image_sorting_dir', $jshopConfig->live_path . 'images/' . $image_sort_dir);
        $view->set('filter_show', 0);
        $view->set('filter_show_category', 0);
        $view->set('filter_show_manufacturer', 0);
        $view->set('pagination', $pagenav);
		$view->set('pagination_obj', $pagination);
        $view->set('display_pagination', $pagenav != '');
        $view->set('product_count', $product_count_sel);
        $view->set('sorting', $sorting_sel);
        $view->set('href_search', $href_search);
        $view->set('action', $action);
        $view->set('orderby', $orderby);
        $view->set('rows', $rows);
        $view->set('allow_review', $allowReview);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('enable_wishlist', $jshopConfig->enable_wishlist);		
        $view->set('production_time', $_production_calendar->show_in_product_list); 
        $view->set('show_wishlist_button', $jshopConfig->show_wishlist_button);
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('header', $header);
        $view->set('display_price', $display_price);
        $view->set('prefix', $prefix);
        $view->set('categorys_sel', []);
        $view->set('manufacuturers_sel', []);
        $view->set('willBeUseFilter', false);
        $view->set('allow_review', $allow_review);
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('pagenavdata', $pagenavdata);
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));

        ExcludeAttributeForAttribute::getInstance()->onBeforeDisplayProductListView($view);
		ExcludeButtonsForAttribute::getInstance()->onBeforeDisplayProductListView($view);        
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplayProductListView($view);
        ProductsMinMaxQuantityMambot::getInstance()->onBeforeDisplayProductListView($view);

        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);

        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){ print_r(json_encode(prepareView($view)));die; }
        $view->display();
    }
    
    public function get_html_characteristics()
    {
        $jshopConfig = JSFactory::getConfig();
        $category_id = JFactory::getApplication()->input->getInt('category_id');

        if ($jshopConfig->admin_show_product_extra_field) {
            $dispatcher = \JFactory::getApplication();
            $characteristic_fields = JSFactory::getAllProductExtraField();
            $characteristic_fieldvalues = JSFactory::getAllProductExtraFieldValueDetail();
            $characteristic_displayfields = JSFactory::getDisplayFilterExtraFieldForCategory($category_id);
            
            $view->characteristic_fields = $characteristic_fields;
            $view->characteristic_fieldvalues = $characteristic_fieldvalues;
            $view->characteristic_displayfields = $characteristic_displayfields;
            $dispatcher->triggerEvent('onBeforeDisplaySearchHtmlCharacteristics', [&$view]);
			
			$html = renderTemplate([
			templateOverrideBlock('blocks', 'characteristics.php', 1)
				], 'characteristics', [
					'characteristic_fields' => $view->characteristic_fields,
					'characteristic_fieldvalues' => $view->characteristic_fieldvalues,
					'characteristic_displayfields' => $view->characteristic_displayfields
				]);
			print $html;
        }

        die();
    }
}