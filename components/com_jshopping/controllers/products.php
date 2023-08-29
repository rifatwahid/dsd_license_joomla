<?php
/**
* @version      4.9.0 05.11.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/


use Joomla\CMS\Pagination\PaginationObject;

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/free_attrs_default_values/products_free_attrs_default_values_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/exclude_attribute_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/exclude_buttons_for_attribute.php';
require_once JPATH_ROOT . '/administrator/components/com_jshopping/models/freeattrcalcprice.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/min_max_quantity/products_min_max_quantity_mambot.php';


class JshoppingControllerProducts extends JshoppingControllerBase
{
    protected const PRODUCT_CUSTOM_CONTEXT_FILTER = 'jshoping.list.front.product.custom';
    protected const PRODUCT_LAST_CONTEXT_FILTER = 'jshoping.list.front.product.last';
    protected const PRODUCT_RANDOM_CONTEXT_FILTER = 'jshoping.list.front.product.random';
    protected const PRODUCT_BESTSELLER_CONTEXT_FILTER = 'jshoping.list.front.product.bestseller';
    protected const PRODUCT_LABEL_CONTEXT_FILTER = 'jshoping.list.front.product.label';
    protected const PRODUCT_TOPRATING_CONTEXT_FILTER = 'jshoping.list.front.product.toprating';
    protected const PRODUCT_TOPHITS_CONTEXT_FILTER = 'jshoping.list.front.product.tophits';

    protected $tmplsNames = [
        'templateBlockListProduct' => 'list_products/list_products.php',
        'templateBlockPagination' => 'list_products/block_pagination.php'
    ];

    public function __construct($config = [])
    {
        parent::__construct($config);
        JPluginHelper::importPlugin('jshoppingproducts');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerProducts', [&$currentObj]);
		setSeoMetaData();
    }
	
    public function display($cachable = false, $urlparams = false)
    {
        $contextfilter = 'jshoping.list.front.product.fulllist';

        JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models'); 
        $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel')->getParams(); 

	    $mainframe = JFactory::getApplication();
		$jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        $limitstart = JFactory::getApplication()->input->getInt('limitstart');
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);
        $ajax = JFactory::getApplication()->input->getInt('ajax');

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);
        
        $product = JSFactory::getTable('product', 'jshop');
        $params = $mainframe->getParams();

        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');

        $this->setSeoMetaData('all-products', $params);
        
		$action = xhtmlUrl($_SERVER['REQUEST_URI']);
		$products_page = $jshopConfig->count_products_to_page;

		$context = 'jshoping.alllist.front.product';
        $orderby = $mainframe->getUserStateFromRequest($context . 'orderby', 'orderby', $jshopConfig->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest($context . 'order', 'order', $jshopConfig->product_sorting, 'int');
        $limit = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', $products_page, 'int');

        if (!$limit) {
            $limit = $products_page;
        }
        
        if ($order == 4) {
            $order = 1;
        }

        $orderbyq = getQuerySortDirection($order, $orderby);
        $image_sort_dir = getImgSortDirection($order, $orderby);
        $field_order = $jshopConfig->sorting_products_field_s_select[$order];
        $filters = getBuildFilterListProduct($contextfilter, []);
        $total = $product->getCountAllProducts($filters);

       
        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();
        $pagenavdata = _buildPaginationDataObject($total, $limitstart, $limit);
       
        
		$dispatcher->triggerEvent('onBeforeFixLimitstartDisplayProductList', [&$limitstart, &$total, 'products']);
        if ($limitstart >= $total) {
            $limitstart = 0;
        }

		$rows = [];
		$allProducts = $product->getAllProducts($filters, $field_order, $orderbyq, $limitstart, $limit);
		addLinkToProducts($allProducts, 0, 1);
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true, false);
		$mainframe->triggerEvent('onAfterbuildProductDataOnFly', [&$allProducts]);
        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;			
			$allProducts[$key]->totalAjaxPrice = $loopProduct->getPriceCalculate($loopProduct->productQuantity);
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }
		
        $sorting_sel = generateSortingHtmlSelectForProduct($order);
        $product_count_sel = generateProductHtmlCountSelectFilter($limit, $products_page);
        
        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        
        $manufacuturers_sel = '';
        $categorys_sel = '';
        if ($jshopConfig->show_product_list_filters) {
            $first_el = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_ALL'), 'manufacturer_id', 'name' );
            $listmanufacturers = JSFactory::getTable('manufacturer', 'jshop')->getList();
            array_unshift($listmanufacturers, $first_el);
            $manufacuturers_sel = generateManufacturerHtmlSelect($listmanufacturers, $filters['manufacturers'][0] ?? '');
            
            $first_el = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_ALL'), 'category_id', 'name');
            $categories = buildTreeCategory(1);
            array_unshift($categories, $first_el);
            $categorys_sel = JHTML::_('select.genericlist', $categories, 'categorys[]', 'class = "inputbox form-select" onchange = "submitListProductFilters()"', 'category_id', 'name', $filters['categorys']['0'] ?? 0);
        }

        $willBeUseFilter = willBeUseFilter($filters);
        $display_list_products = (!empty($rows) || $willBeUseFilter);
        $display_price = getDisplayPriceForListProduct();
        transformDescrsTextsToModule($rows);
        $dispatcher->triggerEvent( 'onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('products', 'products.php')
        ]);
        $layout = getLayoutName('products', 'products');
        $view->setLayout($layout);
        $view->set('component', 'Products');
        $view->set('config', $jshopConfig);
		$view->set('display_price', $display_price);
        $view->set('template_block_list_product', $this->tmplsNames['templateBlockListProduct']);
        $view->set('template_no_list_product', $this->tmplsNames['templateNoListProduct'] ?? '');
        $view->set('template_block_pagination', $this->tmplsNames['templateBlockPagination']);
        $view->set('path_image_sorting_dir', $jshopConfig->live_path . 'images/' . $image_sort_dir);
        $view->set('filter_show', 1);
        $view->set('display_price', $display_price);
        $view->set('filter_show_category', 1);
        $view->set('filter_show_manufacturer', 1);
        $view->set('pagination', $pagenav);
        $view->set('pagination_obj', $pagination);
        $view->set('display_pagination', $pagenav != '');
        $view->set('pagenavdata', $pagenavdata);
        $view->set('header', $header);
        $view->set('prefix', $prefix);
		$view->set('rows', $rows);
		//$view->set('rows2', $rows2);
        $view->set('action', $action);
        $view->set('allow_review', $allow_review);
		$view->set('orderby', $orderby);		
		$view->set('product_count', $product_count_sel);
        $view->set('sorting', $sorting_sel);
        $view->set('categorys_sel', $categorys_sel);
        $view->set('manufacuturers_sel', $manufacuturers_sel);
        $view->set('filters', $filters);
        $view->set('willBeUseFilter', $willBeUseFilter);
        $view->set('display_list_products', $display_list_products);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));		
		$view->set('action', SEFLink('index.php?option=com_jshopping&controller=cart&task=add', 1));
        $view->set('enable_wishlist', $jshopConfig->enable_wishlist);		
        $view->set('production_time', $_production_calendar->show_in_product_list); 
        $view->set('show_wishlist_button', $jshopConfig->show_wishlist_button);
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));

        ExcludeAttributeForAttribute::getInstance()->onBeforeDisplayProductListView($view);
		ExcludeButtonsForAttribute::getInstance()->onBeforeDisplayProductListView($view);        
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplayProductListView($view);
        ProductsMinMaxQuantityMambot::getInstance()->onBeforeDisplayProductListView($view);
		
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $view->set('title', JFactory::getDocument()->getTitle());
        $view->set('sef', JFactory::getConfig()->get('sef'));
		$document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print json_encode(prepareView($view));die;}
		$view->display();
	}
    
    public function tophits()
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $document = JFactory::getDocument();
        $session = JFactory::getSession();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeLoadProductList', []);
        
        $product = JSFactory::getTable('product', 'jshop');
        $params = $mainframe->getParams();
        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');

        $this->setSeoMetaData('tophitsproducts', $params);
        
        $rows = [];
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $filtersInfo = $this->buildFilters(self::PRODUCT_TOPHITS_CONTEXT_FILTER);
        $allProducts = $product->getTopHitsProducts($filtersInfo->count, null, $filtersInfo->filters);
        addLinkToProducts($allProducts, 0, 1);		
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true, false);

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }
        
        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        $display_list_products = !empty($rows);
        $jshopConfig->show_sort_product = 0;
        $jshopConfig->show_product_list_filters = 0;
        
        transformDescrsTextsToModule($rows);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('products', 'products.php')
        ]);
        $layout = getLayoutName('products', 'products');
        $view->setLayout($layout);
        $view->set('component', 'Products');
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', $this->tmplsNames['templateBlockListProduct']);
        $view->set('template_block_pagination', $this->tmplsNames['templateBlockPagination']);
        $view->set('header', $header);
        $view->set('prefix', $prefix);
        $view->set('rows', $rows);
        $view->set('allow_review', $allow_review);
        $view->set('display_list_products', $display_list_products);
        $view->set('display_pagination', 0);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $view->set('title', JFactory::getDocument()->getTitle());
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));

        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print_r(json_encode(prepareView($view)));die;}
        $view->display();
    }

    public function toprating()
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $document = JFactory::getDocument();
        $session = JFactory::getSession();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);
        
        $product = JSFactory::getTable('product', 'jshop');
        $params = $mainframe->getParams();
        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');

        $this->setSeoMetaData('topratingproducts', $params);

        $rows = [];
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $filtersInfo = $this->buildFilters(self::PRODUCT_TOPRATING_CONTEXT_FILTER);
        $allProducts = $product->getTopRatingProducts($filtersInfo->count, null, $filtersInfo->filters);
        addLinkToProducts($allProducts, 0, 1);		
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true, false);

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }
        
        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        $display_list_products = !empty($rows);
        $jshopConfig->show_sort_product = 0;
        $jshopConfig->show_product_list_filters = 0;

        transformDescrsTextsToModule($rows);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('products', 'products.php')
        ]);
        $layout = getLayoutName('products', 'products');
        $view->setLayout($layout);
        $view->set('component', 'Products');
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', $this->tmplsNames['templateBlockListProduct']);
        $view->set('template_block_pagination', $this->tmplsNames['templateBlockPagination']);
        $view->set('header', $header);
        $view->set('prefix', $prefix);
        $view->set('rows', $rows);
        $view->set('allow_review', $allow_review);
        $view->set('display_list_products', $display_list_products);
        $view->set('display_pagination', 0);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $view->set('title', JFactory::getDocument()->getTitle());
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print_r(json_encode(prepareView($view)));die;}
        $view->display();
    }

    public function label()
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);
        
        $product = JSFactory::getTable('product', 'jshop');
        $params = $mainframe->getParams();
        $document = JFactory::getDocument();
        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');

        $this->setSeoMetaData('labelproducts', $params);

        $label_id = JFactory::getApplication()->input->getInt('label_id');
        $rows = [];
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $filtersInfo = $this->buildFilters(self::PRODUCT_LABEL_CONTEXT_FILTER);
        $allProducts = $product->getProductLabel($label_id, $filtersInfo->count, null, $filtersInfo->filters);
        addLinkToProducts($allProducts, 0, 1);		
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true, false);

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }
        
        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        $display_list_products = !empty($rows);
        $jshopConfig->show_sort_product = 0;
        $jshopConfig->show_product_list_filters = 0;
        
        transformDescrsTextsToModule($rows);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('products', 'products.php')
        ]);
        $layout = getLayoutName('products', 'products');
        $view->setLayout($layout);
        $view->set('component', 'Products');
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', $this->tmplsNames['templateBlockListProduct']);
        $view->set('template_block_pagination', $this->tmplsNames['templateBlockPagination']);
        $view->set('header', $header);
        $view->set('prefix', $prefix);
        $view->set('rows', $rows);
        $view->set('allow_review', $allow_review);
        $view->set('display_list_products', $display_list_products);
        $view->set('display_pagination', 0);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));

        $view->set('title', JFactory::getDocument()->getTitle());
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print_r(json_encode(prepareView($view)));die;}
        $view->display();
    }
    
    public function bestseller()
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);
     
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);
        
        $product = JSFactory::getTable('product', 'jshop');
        $params = $mainframe->getParams();
        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');

        $this->setSeoMetaData('bestsellerproducts', $params);

        $rows = [];
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $filtersInfo = $this->buildFilters(self::PRODUCT_BESTSELLER_CONTEXT_FILTER);
        $allProducts = $product->getBestSellers($filtersInfo->count, null, $filtersInfo->filters);
        addLinkToProducts($allProducts, 0, 1);		
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true, false);

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }
        
        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        $display_list_products = count($rows)>0;
        $jshopConfig->show_sort_product = 0;
        $jshopConfig->show_product_list_filters = 0;

        transformDescrsTextsToModule($rows);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('products', 'products.php')
        ]);
        $layout = getLayoutName('products', 'products');
        $view->setLayout($layout);
        $view->set('component', 'Products');
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', $this->tmplsNames['templateBlockListProduct']);
        $view->set('template_block_pagination', $this->tmplsNames['templateBlockPagination']);
        $view->set('header', $header);
        $view->set('prefix', $prefix);
        $view->set('rows', $rows);
        $view->set('allow_review', $allow_review);
        $view->set('display_list_products', $display_list_products);
        $view->set('display_pagination', 0);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $view->set('title', JFactory::getDocument()->getTitle());
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        $view->set('title', JFactory::getDocument()->getTitle());
        if($ajax){print_r(json_encode(prepareView($view)));die;}
        $view->display();
    }
    
    public function random()
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);
        
        $product = JSFactory::getTable('product', 'jshop');
        $params = $mainframe->getParams();
        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');

        $this->setSeoMetaData('randomproducts', $params);

        $rows = [];
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $filtersInfo = $this->buildFilters(self::PRODUCT_RANDOM_CONTEXT_FILTER);
        $allProducts = $product->getRandProducts($filtersInfo->count, null, $filtersInfo->filters);
        addLinkToProducts($allProducts, 0, 1);		
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true, false);

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }
        
        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        $display_list_products = !empty($rows);
        $jshopConfig->show_sort_product = 0;
        $jshopConfig->show_product_list_filters = 0;

        transformDescrsTextsToModule($rows);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('products', 'products.php')
        ]);
        $layout = getLayoutName('products', 'products');
        $view->setLayout($layout);
        $view->set('component', 'Products');
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', $this->tmplsNames['templateBlockListProduct']);
        $view->set('template_block_pagination', $this->tmplsNames['templateBlockPagination']);
        $view->set('header', $header);
        $view->set('prefix', $prefix);
        $view->set('rows', $rows);
        $view->set('allow_review', $allow_review);
        $view->set('display_list_products', $display_list_products);
        $view->set('display_pagination', 0);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $view->set('title', JFactory::getDocument()->getTitle());
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print_r(json_encode(prepareView($view)));die;}

        $view->display();
    }
    
    public function last()
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);

        $product = JSFactory::getTable('product', 'jshop');
        $params = $mainframe->getParams();
        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');
        
        $this->setSeoMetaData('lastproducts', $params);

        $rows = [];
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $filtersInfo = $this->buildFilters(self::PRODUCT_LAST_CONTEXT_FILTER);
        $allProducts = $product->getLastProducts($filtersInfo->count, null, $filtersInfo->filters);
        addLinkToProducts($allProducts, 0, 1);		
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true, false);

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }
        
        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        $display_list_products = !empty($rows);
        $jshopConfig->show_sort_product = 0;
        $jshopConfig->show_product_list_filters = 0;
        
        transformDescrsTextsToModule($rows);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('products', 'products.php')
        ]);
        $layout = getLayoutName('products', 'products');
        $view->setLayout($layout);
        $view->set('component', 'Products');
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', $this->tmplsNames['templateBlockListProduct']);
        $view->set('template_block_pagination', $this->tmplsNames['templateBlockPagination']);
        $view->set('header', $header);
        $view->set('prefix', $prefix);
        $view->set('rows', $rows);
        $view->set('allow_review', $allow_review);
        $view->set('display_list_products', $display_list_products);
        $view->set('display_pagination', 0);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view',1));
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $view->set('title', JFactory::getDocument()->getTitle());
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print_r(json_encode(prepareView($view)));die;}

        $view->display();
    }
	
    public function custom()
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);
        $document = JFactory::getDocument();

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);
        
        $product = JSFactory::getTable('product', 'jshop');
        $params = $mainframe->getParams();
        $header = getPageHeaderOfParams($params);
        $prefix = $params->get('pageclass_sfx');
		
        $rows = [];
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $filtersInfo = $this->buildFilters(self::PRODUCT_CUSTOM_CONTEXT_FILTER);
        $allProducts = $product->getCustom($filtersInfo->count, null, $filtersInfo->filters);
        addLinkToProducts($allProducts, 0, 1);		
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true, false);

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $rows[] = $loopProduct;
            }
        }
        
        $_review = JSFactory::getTable('review', 'jshop');
        $allow_review = $_review->getAllowReview();
        $display_list_products = !empty($rows);
        $jshopConfig->show_sort_product = 0;
        $jshopConfig->show_product_list_filters = 0;

        transformDescrsTextsToModule($rows);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('products', getDocumentType(), '', [
            'template_path' => viewOverride('products', 'products.php')
        ]);
        $layout = getLayoutName('products', 'products');
        $view->setLayout($layout);
        $view->set('component', 'Products');
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', $this->tmplsNames['templateBlockListProduct']);
        $view->set('template_block_pagination', $this->tmplsNames['templateBlockPagination']);
        $view->set('header', $header);
        $view->set('prefix', $prefix);
        $view->set('rows', $rows);
        $view->set('allow_review', $allow_review);
        $view->set('display_list_products', $display_list_products);
        $view->set('display_pagination', 0);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));
        $view->set('title', JFactory::getDocument()->getTitle());
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print_r(json_encode(prepareView($view)));die;}
        $view->display();
    }

    protected function setSeoMetaData(string $seoAlias, $params)
    {
        $seo = JSFactory::getTable('seo', 'jshop');
		$seodata = new StdClass();
        $seodata = $seo->loadData($seoAlias);
        setMetaData($seodata->title ?? '', $seodata->keyword ?? '', $seodata->description ?? '', $params);
		
    }

    protected function buildFilters(string $contextFilter)
    {
        $jshopConfig = JSFactory::getConfig();

        $filters = getBuildFilterListProduct($contextFilter, []);
        $count = $jshopConfig->count_products_to_page;
        
		if (isset($filters['number_of_products']) && ($filters['number_of_products'] > 0)) {
            $count = $filters['number_of_products'];
        }

        $result = new stdClass();
        $result->count = $count;
        $result->filters = $filters;
        
        return $result;
    }
	
}