<?php
/**
* @version      4.9.0 31.05.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerManufacturer extends JshoppingControllerBase
{
    
    public function __construct($config = [])
    {
        parent::__construct($config);
        JPluginHelper::importPlugin('jshoppingproducts');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerManufacturer', [&$currentObj]);
		setSeoMetaData();
    }
	
    public function display($cachable = false, $urlparams = false)
    {
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams();
		$jshopConfig = JSFactory::getConfig();
        $document = JFactory::getDocument();
        $manufacturer = JSFactory::getTable('manufacturer', 'jshop');
        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $ajax = JFactory::getApplication()->input->getInt('ajax', 0);
        $ordering = ($jshopConfig->manufacturer_sorting == 1) ? 'ordering' : 'name';
		
		$manufacturers_page = $jshopConfig->count_manufacturer_to_page;
		$context = 'jshoping.alllist.front.product';
		$limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $manufacturers_page, 'int') ?: $manufacturers_page;
        $total = $manufacturer->getCountManufacturers();
        
		if ($limitstart >= $total) {
            $limitstart = 0;
        }

		jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();
        $pagenavdata = _buildPaginationDataObject($total, $limitstart, $limit);

        $rows = $manufacturer->getAllManufacturers(1, $ordering, 'asc', $limitstart, $limit);
        transformDescrsTextsToModule($rows);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayListManufacturers', [&$rows]);

        $seo = JSFactory::getTable('seo', 'jshop');
        $seodata = $seo->loadData('manufacturers');
        
		setSeoMetaData($seodata->title ?? '');
        loadJSLanguageKeys();

        $view = $this->getView('manufacturer', getDocumentType(), '', [
            'template_path' => viewOverride('manufacturer', 'manufacturers.php')
        ]);
        $layout = getLayoutName('manufacturer', 'manufacturers');
        $view->setLayout($layout);
        $view->set('component', 'Manufacturers');

		$view->set('rows', $rows);
		$view->set('image_manufs_live_path', $jshopConfig->image_manufs_live_path);
        $view->set('noimage', $jshopConfig->noimage);
        $view->set('count_manufacturer_to_row', 0);
        $view->set('count_manufacturer_to_page', $jshopConfig->count_manufacturer_to_page);
        $view->set('params', $params);
        $view->set('pagination', $pagenav);
        $view->set('pagination_obj', $pagination);
        $view->set('display_pagination', $pagenav != '');        
		$view->set('manufacturer', $manufacturer);
		$view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $dispatcher->triggerEvent('onBeforeDisplayManufacturerView', [&$view]);
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('pagenavdata', $pagenavdata);
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){ print_r(json_encode(prepareView($view)));die; }
		$view->display();
	}	
	
    public function view()
    {
	    $mainframe = JFactory::getApplication();
		$jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax', 0);
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $session->set("jshop_end_page_buy_product", $_SERVER['REQUEST_URI']);
        $session->set("jshop_end_page_list_product", $_SERVER['REQUEST_URI']);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);
        
        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $manufacturer_id = JFactory::getApplication()->input->getInt('manufacturer_id');
        $itemId = JFactory::getApplication()->input->getInt('Itemid');

		$manufacturer = JSFactory::getTable('manufacturer', 'jshop');		
		$manufacturer->load($manufacturer_id);
		$manufacturer->getDescription();

        $dispatcher->triggerEvent('onBeforeDisplayManufacturer', [&$manufacturer]);
        
        if ($manufacturer->manufacturer_publish == 0) {
            throw new \Exception(JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'), 404);
            return;
        }

        if (getShopManufacturerPageItemid() == $itemId) {
            appendPathWay($manufacturer->name);
        }
        
        if (empty($manufacturer->meta_title)) {
            $manufacturer->meta_title = $manufacturer->name;
        }
        
		setSeoMetaData($manufacturer->meta_title);
        
		$action = xhtmlUrl($_SERVER['REQUEST_URI']);
        
        if (!$manufacturer->products_page){
		    $manufacturer->products_page = $jshopConfig->count_products_to_page; 
        }
				
		$context = 'jshoping.manufacturlist.front.product';
        $contextfilter = 'jshoping.list.front.product.manf.' . $manufacturer_id;
        $orderby = $mainframe->getUserStateFromRequest($context . 'orderby', 'orderby', $jshopConfig->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest($context . 'order', 'order', $jshopConfig->product_sorting, 'int');
        $limit = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', $manufacturer->products_page, 'int') ?: $manufacturer->products_page;

        if ($order == 4) {
            $order = 1;
        }

        $orderbyq = getQuerySortDirection($order, $orderby);
        $image_sort_dir = getImgSortDirection($order, $orderby);
        $field_order = $jshopConfig->sorting_products_field_s_select[$order];
        $filters = getBuildFilterListProduct($contextfilter, ['manufacturers']);

        $total = $manufacturer->getCountProducts($filters);
       
        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();
        $pagenavdata = _buildPaginationDataObject($total, $limitstart, $limit);
        
        $dispatcher->triggerEvent('onBeforeFixLimitstartDisplayProductList', [&$limitstart, &$total, 'manufacturer']);
        
        if ($limitstart >= $total) {
            $limitstart = 0;
        }

		$rows = $manufacturer->getProducts($filters, $field_order, $orderbyq, $limitstart, $limit);
		addLinkToProducts($rows, 0, 1);		
	
        $sorting_sel = generateSortingHtmlSelectForProduct($order);
        $product_count_sel = generateProductHtmlCountSelectFilter($limit, $manufacturer->products_page);

        $reviewTable = JSFactory::getTable('review', 'jshop');
        $isAllowReview = $reviewTable->getAllowReview();
        
        if ($jshopConfig->show_product_list_filters) {
            $filter_categorys = $manufacturer->getCategorys();
            $first_category = [];
            $first_category[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_ALL'), 'id', 'name');
            $active_category = 0;

            if (isset($filters['categorys']['0'])){
                $active_category = $filters['categorys']['0'];
            }

            $categorys_sel = JHTML::_('select.genericlist', array_merge($first_category, $filter_categorys), 'categorys[]', 'class = "inputbox form-select" onchange = "submitListProductFilters()"', 'id', 'name', $active_category);
        }else{
            $categorys_sel = '';
        }

        $manufacturer->transformDescrTextToModule();
        
        $willBeUseFilter = willBeUseFilter($filters);
        $display_list_products = (!empty($rows) || $willBeUseFilter);

        transformDescrsTextsToModule($rows);
        $rows = $modelOfProductsFront->buildProductDataOnFly($rows, true, true);
        foreach($rows as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $rows[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $rows[$key]->permissions = $loopProductUserGroupPermissions;
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $products[] = $loopProduct;
            }

        }
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$rows]);

        loadJSLanguageKeys();

        $view = $this->getView('manufacturer', getDocumentType(), '', [
            'template_path' => viewOverride('manufacturer', 'products.php')
        ]);
        $view->set('component', 'Products_manufacturer');

        $layout = getLayoutName('manufacturer', 'products');
        $view->setLayout($layout);
        $view->set('config', $jshopConfig);
        $view->set('template_block_list_product', 'list_products/list_products.php');
        $view->set('template_no_list_product', 'list_products/no_products.php');
        $view->set('template_block_pagination', 'list_products/block_pagination.php');
        $view->set('path_image_sorting_dir', $jshopConfig->live_path . 'images/' . $image_sort_dir);
        $view->set('filter_show', 1);
        $view->set('filter_show_category', 1);
        $view->set('filter_show_manufacturer', 0);
        $view->set('pagination', $pagenav);
		$view->set('pagination_obj', $pagination);
        $view->set('display_pagination', $pagenav != '');
		$view->set('rows', $rows);
		$view->set('manufacturer', $manufacturer);
        $view->set('action', $action);
        $view->set('allow_review', $isAllowReview);
		$view->set('orderby', $orderby);		
		$view->set('product_count', $product_count_sel);
        $view->set('sorting', $sorting_sel);
        $view->set('categorys_sel', $categorys_sel);
        $view->set('filters', $filters);
        $view->set('willBeUseFilter', $willBeUseFilter);
        $view->set('display_list_products', $display_list_products);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('pagenavdata', $pagenavdata);
		$view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){ print_r(json_encode(prepareView($view)));die; }
		$view->display();
	}	
}