<?php
/**
* @version      4.7.0 05.11.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerCategory extends JshoppingControllerBase
{
    
    function __construct($config = [])
    {
        parent::__construct($config);
        $currentObj = $this;
        JPluginHelper::importPlugin('jshoppingproducts');
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerCategory', [&$currentObj]);
		setSeoMetaData();
    }
    
    function display($cachable = false, $urlparams = false)
    {
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $params = $mainframe->getParams();
        $document = JFactory::getDocument();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $categoryId = 0;
        
        $ordering = $jshopConfig->category_sorting==1 ? 'ordering' : 'name';
        $category = JSFactory::getTable('category', 'jshop');
        $category->load($categoryId);
        $categories = $category->getChildCategories($ordering, 'asc', 1);
        $category->getDescription();
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayMainCategory', [&$category, &$categories]);
        
		setSeoMetaData($category->meta_title ?? '');

        $category->transformDescrTextToModule();
        transformDescrsTextsToModule($categories);

        loadJSLanguageKeys();

        $view = $this->getView('category', getDocumentType(), '', [
            'template_path' => viewOverride('category', 'maincategory.php')
        ]);

        $layout = getLayoutName('category', 'maincategory');
        $view->setLayout($layout);
        $view->set('component', 'Maincategory');

        $view->set('category', $category);
        $view->set('image_category_path', $jshopConfig->image_category_live_path);
        $view->set('noimage', $jshopConfig->noimage);
        $view->set('categories', $categories);
        $view->set('count_category_to_row', 0);
        $view->set('params', $params);
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $dispatcher->triggerEvent('onBeforeDisplayCategoryView', [&$view]);
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){ print_r(json_encode(prepareView($view)));die; }
        $view->display();
    }

    function view()
    {
        $category_id = JFactory::getApplication()->input->getInt('category_id');
        $limitstart = JFactory::getApplication()->input->getInt('limitstart');
        $itemId = JFactory::getApplication()->input->getInt('Itemid');
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $document = JFactory::getDocument();

        JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models');
        $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel')->getParams();

        $category = JSFactory::getTable('category', 'jshop');
        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();
        $jshopConfig = JSFactory::getConfig();
        $session = JFactory::getSession();
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        $session->set('jshop_end_page_list_product', $_SERVER['REQUEST_URI']);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);

        $category->load($category_id);
        $category->getDescription();
        $dispatcher->triggerEvent('onAfterLoadCategory', [&$category, &$user]);

        $view_name = 'category';
        $pathToCategoryTmpl = $jshopConfig->template_path . $jshopConfig->template . '/' . $view_name;

		if (!$category->category_id || $category->category_publish == 0 || !in_array($category->access, $user->getAuthorisedViewLevels())) {
            throw new \Exception(JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'), 404);
            return;
        }

        if(!is_file($pathToCategoryTmpl . '/category_' . $category->category_template . '.php') || empty($category->category_template)) {
            $category->category_template = 'default';
        }
        loadJSLanguageKeys();

        $view = $this->getView('category', getDocumentType(), '', [
            'template_path' => viewOverride('category', 'category_' . $category->category_template.'.php')
        ]);

        $layout = getLayoutName('category', 'category_' . $category->category_template);
        $view->setLayout($layout);
        $view->set('component', 'Category_default');


        $jshopConfig->count_products_to_page = $category->products_page;

        $context = 'jshoping.list.front.product';
        $contextfilter = 'jshoping.list.front.product.cat.' . $category_id;
        $orderby = $mainframe->getUserStateFromRequest($context . 'orderby', 'orderby', $jshopConfig->product_sorting_direction, 'int');
        $order = $mainframe->getUserStateFromRequest($context . 'order', 'order', $jshopConfig->product_sorting, 'int');
        $limit = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', $category->products_page, 'int') ?: $category->products_page;

        $orderbyq = getQuerySortDirection($order, $orderby);
        $image_sort_dir = getImgSortDirection($order, $orderby);
        $field_order = $jshopConfig->sorting_products_field_select[$order];
        $filters = getBuildFilterListProduct($contextfilter, ['categorys']);
        
        if (getShopMainPageItemid() == $itemId) {
            appendExtendPathWay($category->getTreeChild(), 'category');
        }
        
        $orderfield = ($jshopConfig->category_sorting == 1) ? 'ordering' : 'name';
        $sub_categories = $category->getChildCategories($orderfield, 'asc', $publish = 1);
        $dispatcher->triggerEvent('onBeforeDisplayCategory', [&$category, &$sub_categories]);

		if(isset($category->meta_title) && $category->meta_title){
			$category->meta_title = $category->meta_title;  
		}else{
			$category->meta_title = $category->name;  			
		}		
		setSeoMetaData($category->meta_title);
		
        
        $total = $category->getCountProducts($filters);
        $action = xhtmlUrl($_SERVER['REQUEST_URI']);
		
		$dispatcher->triggerEvent('onBeforeFixLimitstartDisplayProductList', [&$limitstart, &$total, 'category']);
        if ($limitstart >= $total) {
            $limitstart = 0;
        }

        $products = [];
        $allProducts = $category->getProducts($filters, $field_order, $orderbyq, $limitstart, $limit);
        addLinkToProducts($allProducts, $category_id);
        $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, true, true);

        foreach($allProducts as $key=>$loopProduct) {
            $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
            $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
            $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
            if ($loopProductUserGroupPermissions->is_usergroup_show_product) {
                $products[] = $loopProduct;
            }
        }
        
        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();
        $pagenavdata = _buildPaginationDataObject($total, $limitstart, $limit);
        
        $sorting_sel = generateSortingHtmlSelectForProduct($order);
        $product_count_sel = generateProductHtmlCountSelectFilter($limit, $category->products_page);

        $reviewTable = JSFactory::getTable('review', 'jshop');
        $isAllowReview = $reviewTable->getAllowReview();
        
        if (!$category->category_ordertype) {
            $category->category_ordertype = 1;
        }
        
        $manufacuturers_sel = '';
        if ($jshopConfig->show_product_list_filters) {
            $activeManufactureVal = $filters['manufacturers']['0'] ?? 0;
            $manufacuturers_sel = generateManufacturerHtmlSelect($category->getManufacturers(), $activeManufactureVal);
        }

        $category->transformDescrTextToModule();

        $willBeUseFilter = willBeUseFilter($filters);
        $display_list_products = (!empty($products) || $willBeUseFilter);

        transformDescrsTextsToModule($products);
        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$products]);
        $view->set('config', $jshopConfig);
        $view->set('display_price', getDisplayPriceForListProduct());
        $view->set('template_block_list_product', 'list_products/list_products.php');
        $view->set('template_no_list_product', 'list_products/no_products.php');
        $view->set('template_block_pagination', 'list_products/block_pagination.php');
        $view->set('path_image_sorting_dir', $jshopConfig->live_path . 'images/' . $image_sort_dir);
        $view->set('filter_show', 1);
        $view->set('filter_show_category', 0);
        $view->set('filter_show_manufacturer', 1);
        $view->set('pagination', $pagenav);
		$view->set('pagination_obj', $pagination);
        $view->set('display_pagination', $pagenav != "");
        $view->set('rows', $products);
        $view->set('image_category_path', $jshopConfig->image_category_live_path);
        $view->set('noimage', $jshopConfig->noimage);
        $view->set('category', $category);
        $view->set('categories', $sub_categories);
        $view->set('count_category_to_row', 0);
        $view->set('allow_review', $isAllowReview);
        $view->set('product_count', $product_count_sel);
        $view->set('sorting', $sorting_sel);
        $view->set('action', $action);
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('orderby', $orderby);
        $view->set('manufacuturers_sel', $manufacuturers_sel);
        $view->set('filters', $filters);
        $view->set('willBeUseFilter', $willBeUseFilter);
        $view->set('display_list_products', $display_list_products);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
		$view->set('action', SEFLink('index.php?option=com_jshopping&controller=cart&task=add', 1));
		$view->set('enable_wishlist', $jshopConfig->enable_wishlist);
        $view->set('show_wishlist_button', $jshopConfig->show_wishlist_button);
        $view->set('production_time', $_production_calendar->show_in_product_list);
        $view->set('patchproductimage_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=patchProductImage', 1));
        $view->set('isUrl_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=isUrl', 1));
        $view->set('productusergrouppermissions_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=productUsergroupPermissions', 1));
        $view->set('price_format_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=getPriceFormat', 1));
        $view->set('prices_format_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=getPricesFormat', 1));
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $view->set('sef', JFactory::getConfig()->get('sef'));
        $view->set('pagenavdata', $pagenavdata);

        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $this->sendEEDataForLinkOrRedirect($view);
        $dispatcher->triggerEvent('onBeforeDisplayProductListView', [&$view]);
        $document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if($ajax){print_r(json_encode(prepareView($view)));die; }
        $view->display();
    }

    protected function sendEEDataForLinkOrRedirect(&$view)
    {
        if (file_exists(JPATH_SITE . '/expresseditor.php')){
            $editorsToCategoriesModel = JSFactory::getModel('EeEditorsToCategories', 'ExpresseditorModel');
            $editorsModel = JSFactory::getModel('Ee_Editors', 'ExpresseditorModel');
            $eeCategories = $editorsToCategoriesModel->getDataByCategoryId(JFactory::getApplication()->input->getInt('category_id'));
            
            if (!empty($eeCategories[0]) ) {
                $eeEditors = $editorsModel->getEditorDataJoinedWithEditorsTypes(intval($eeCategories[0]->editor_id), ' ORDER BY ed.editor_id ASC');
        
                if (!empty($eeEditors[0])) {
        
                    $editorsContentModel = JSFactory::getModel('EeEditorsContent', 'ExpresseditorModel');
                    $editorsContent = $editorsContentModel->getByEditorId($eeEditors[0]->editor_id);
                    $eeLink = 'index.php?option=com_expresseditor&controller=expresseditor&task=editor&xmlname=' . $eeEditors[0]->editor_id . '_' . $eeEditors[0]->source_xml . '&product=' . $eeEditors[0]->flash_name . '#editor';
        
                    if (!empty($editorsContent[0]) && $eeCategories[0]->enable == 0) {
                        $view->set('eeLink', JRoute::_($eeLink));
                        $view->set('editorsContent' , $editorsContent);
                        $view->set('eeCategories' , $eeCategories);
                    } else {
                        JFactory::getApplication()->redirect(JURI::base() . $eeLink);
                    }
                }
            }
        
        }
    }
}