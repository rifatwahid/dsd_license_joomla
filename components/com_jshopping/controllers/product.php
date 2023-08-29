<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die('Restricted access');
jimport('joomla.application.component.controller');

use Joomla\CMS\Language\Text;

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/free_attrs_default_values/products_free_attrs_default_values_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/exclude_attribute_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/exclude_buttons_for_attribute.php';
require_once JPATH_ROOT . '/administrator/components/com_jshopping/models/freeattrcalcprice.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/min_max_quantity/products_min_max_quantity_mambot.php';

class JshoppingControllerProduct extends JshoppingControllerBase
{
    
    public function __construct($config = [])
    {
        parent::__construct($config);
        JPluginHelper::importPlugin('jshoppingproducts');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerProduct', [&$currentObj]);
		setSeoMetaData();
    }

    public function display($cachable = false, $urlparams = false)
    {
        Text::script('COM_SMARTSHOP_NATIVE_UPLOAD_EXIST_ZERO_QUANTITY_IN_ROW');
        Text::script('COM_SMARTSHOP_NATIVE_UPLOAD_REMAINING_QUANTITY');
		Text::script('COM_SMARTSHOP_REVIW_YOU_CAN_UPLOAD_ONLY');
        Text::script('COM_SMARTSHOP_REVIW_YOU_CAN_UPLOAD_FILES');
        Text::script('COM_SMARTSHOP_UPLOAD_FILES_REMAINING_QTY_CANNOT_BENEGATIVE');
		Text::script('COM_SMARTSHOP_NOTE');
        JLoader::import( 'production_calendar', JPATH_ADMINISTRATOR . '/components/com_jshopping/models');
        $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel')->getParams();
		
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $tmpl = JFactory::getApplication()->input->getVar('tmpl');
        $product_id = JFactory::getApplication()->input->getInt('product_id');
        $category_id = JFactory::getApplication()->input->getInt('category_id');
        $attr = JFactory::getApplication()->input->getVar('attr');
        $itemId = JFactory::getApplication()->input->getInt('Itemid');
        
		checkUsergroupProductBlock($product_id, $category_id);
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        JSFactory::loadJsFilesLightBox();
        $session = JFactory::getSession();
        $document = JFactory::getDocument();

        if ($tmpl != 'component') {
            $session->set('jshop_end_page_buy_product', $_SERVER['REQUEST_URI']);
        }

        $back_value = $session->get('product_back_value');

        if (!isset($back_value['pid']) || $back_value['pid'] != $product_id) {
            $back_value = [
                'pid' => null, 
                'attr' => null, 
                'qty' => null
            ];
        }

        if (!is_array($back_value['attr'])) {
            $back_value['attr'] = [];
        }

        if (empty($back_value['attr']) && is_array($attr)) {
            $back_value['attr'] = $attr;
        }

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadProduct', [&$product_id, &$category_id, &$back_value]);
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeLoadProduct($product_id, $category_id, $back_value);
        $dispatcher->triggerEvent('onBeforeLoadProductList', []);

        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);

		$linkToAjaxUploadFiles = '/index.php?option=com_jshopping&controller=upload&task=ajaxUploadFile&product_id=' . $product->product_id;
        $listOfCategories = $product->getCategories(1);
      
		if ($category_id == 0 && !empty($listOfCategories)) {
			$category_id = $listOfCategories['0'];
		}
		
        if (!getDisplayPriceForProduct($product->product_id, $product->product_price)) {
            $jshopConfig->attr_display_addprice = 0;
        }

        $attributesDatas = $product->getAttributesDatas($back_value['attr'], JSFactory::getUser()->usergroup_id);
		
        $product->setAttributeActive($attributesDatas['attributeActive']);
        $attributeValues = $attributesDatas['attributeValues'];
        $allAttrValues = [];
        $attributes = $product->getBuildSelectAttributes($attributeValues, $attributesDatas['attributeSelected'], true);
		
        if (!empty($attributes)) {
            $attrValueTable = JSFactory::getTable('AttributValue', 'jshop');
            $allAttrValues = $attrValueTable->getAllAttributeValues(0,$product_id);
        }

		$buttons=JSFactory::getModel('buttons');//echo "<pre>";print_r($attributesDatas['attributeActive']);print_r($attributeValues);die();
		//$buttons->excludeButtonsForAttribute($attributeValues, $attributesDatas['attributeActive']);		
		$buttons=JSFactory::getModel('buttons');
		$show_buttons=$buttons->excludeButtonsForAttribute($attributeValues, $attributesDatas['attributeActive']);
		
        $session->set('product_back_value', []);
        $product->getExtendsData();
		if(isset($product->attribute_active_data->production_time) && $product->attribute_active_data->production_time){
			$product->production_time = $product->attribute_active_data->production_time;
		}

        $category = JSFactory::getTable('category', 'jshop');
        $category->load($category_id);
        $category->name = $category->getName();        
		$dispatcher->triggerEvent('onBeforeCheckProductPublish', [&$product, &$category, &$category_id, &$listOfCategories]);
        if ($category->category_publish == 0 || $product->product_publish == 0 || !in_array($product->access, $user->getAuthorisedViewLevels()) || !in_array($category_id, $listOfCategories)) {
            throw new \Exception(JText::_('Access COM_SMARTSHOP_PAGE_NOT_FOUND'), 404);
            return;
        }
        
        if (getShopMainPageItemid() == $itemId) {
            appendExtendPathway($category->getTreeChild(), 'product');
        }

        appendPathWay($product->name);
        if (!isset($product->meta_title) || $product->meta_title == '') {
            $product->meta_title = $product->name;
        }
        
		setSeoMetaData($product->meta_title);
        
        $product->hit();
        
        $product->product_basic_price_unit_qty = 1;
        $product->product_basic_price_show = 0;
        if ($jshopConfig->admin_show_product_basic_price && $jshopConfig->product_list_show_price_default) {
            $product->getBasicPriceInfo();
        }
		if ($jshopConfig->admin_show_product_basic_price && $jshopConfig->product_list_show_price_default) {
			$product->product_basic_price_show = 1;
		}
        
        $pathToProductTemplateFile = "{$jshopConfig->template_path}{$jshopConfig->template}/product/product_{$product->product_template}.php";
        if(!is_file($pathToProductTemplateFile) || empty($product->product_template)) {
            $product->product_template = 'default';
		}
        loadJSLanguageKeys();

        $view = $this->getView('product', getDocumentType(), '', [
            'template_path' => viewOverride('product', 'product_' . $product->product_template.'.php')
        ]);
        $layout = getLayoutName('product', 'product_' . $product->product_template);
        $view->setLayout($layout);

        $reviewTable = JSFactory::getTable('review', 'jshop');
        $allow_review = $reviewTable->getAllowReview(null,$product->product_id);
        $select_review = '';

        if (!empty($allow_review)) {
            $reviewModel = JSFactory::getModel('reviewFront', 'jshop');
            $select_review = $reviewModel->generateRatingMarkup();
            $text_review = '';
        } else {
            $text_review = $reviewTable->getText();
        }

        $product->manufacturer_info = new stdClass();
        $product->manufacturer_info->manufacturer_logo = '';
        $product->manufacturer_info->name = '';
         
        if ($jshopConfig->product_show_manufacturer_logo || $jshopConfig->product_show_manufacturer) {
            $manufactureProductInfo = $product->getManufacturerInfo();

            if (isset($manufactureProductInfo)) {
                $product->manufacturer_info = $manufactureProductInfo;
            }
        }        
        $product->vendor_info = null;		                
        $product->extra_field = null;
        if ($jshopConfig->admin_show_product_extra_field) {
            $product->extra_field = $product->getExtraFields();
           // foreach(extra_field)
        }
        
        $product->freeattributes = null;
        $product->freeattribrequire = 0;
        if ($jshopConfig->admin_show_freeattributes) {
            $product->getListFreeAttributes();
            $product->freeattribrequire = count($product->getRequireFreeAttribute());
        }

        if ($jshopConfig->product_show_qty_stock) {
		  $expirationQty = JSFactory::getModel('attrsfront')->countAttrDataValProduct($product->product_id, $product->attribute_active);
		  if($product->product_packing_type == 1 && $expirationQty){
            $product->qty_in_stock = $expirationQty;
		  }else{
			  $product->qty_in_stock = getDataProductQtyInStock($product);			  
		  }
        }
		
        
        if (!$jshopConfig->admin_show_product_labels) {
            $product->label_id = null;
        }

        if ($product->label_id) {
            $image = getNameImageLabel($product->label_id);

            if (!empty($image)) {
                $product->_label_image = getPatchProductImage($image, '', 1);
            }

            $product->_label_name = getNameImageLabel($product->label_id, 2);
        }
        
        $hide_buy = 0;
        
        $available = '';
        if ( ($product->getQty() <= 0) && $product->product_quantity > 0) {
            $available = JText::_('COM_SMARTSHOP_PRODUCT_NOT_AVAILABLE_THIS_OPTION');
        } elseif ($product->product_quantity <= 0) {
            $available = JText::_('COM_SMARTSHOP_PRODUCT_NOT_AVAILABLE');
        }

        $product->_display_price = getDisplayPriceForProduct($product->product_id, $product->getPriceCalculate());
        if (!$product->_display_price) {
            $product->product_old_price = 0;
            $product->product_price_default = 0;
            $product->product_basic_price_show = 0;
            $product->product_is_add_price = 0;
            $product->product_tax = 0;
            $jshopConfig->show_plus_shipping_in_product = 0;
        }
        
		$usergroup_show_action = getUsergroupShowAction($product->product_id);
        if (!$product->_display_price || $jshopConfig->user_as_catalog || ($jshopConfig->hide_buy_not_avaible_stock && $product->product_quantity <= 0)) {
            $hide_buy = 1;
        }

        $default_count_product = 1;
        if (!empty($back_value['qty'])) {
            $default_count_product = $back_value['qty'];
        }
                
        $product->hide_delivery_time = 0;
        if (!$product->getDeliveryTimeId()) {
            $product->hide_delivery_time = 1;
        }
        
        $product->button_back_js_click = "history.go(-1);";
        if ($session->get('jshop_end_page_list_product') && $jshopConfig->product_button_back_use_end_list) {
            $product->button_back_js_click = "location.href='" . $session->get('jshop_end_page_list_product') . "';";
        }
		
        $displaybuttons = '';
        if ($jshopConfig->hide_buy_not_avaible_stock && $product->getQty() <= 0) {
            $displaybuttons = 'display:none;';
        }

        $media = $product->getMedia();
        $product_demofiles = $product->getDemoFiles();
		
        $uploadCommonSettings = JSFactory::getModel('upload')->getParams();
        $productWithUpload = $product->getEssenceWithActiveUpload();

        $isMultiUploadProduct = (isset($productWithUpload->max_allow_uploads) && $productWithUpload->max_allow_uploads >= 2) || (isset($productWithUpload->is_unlimited_uploads) && $productWithUpload->is_unlimited_uploads);
        $maxFilesUploads = 1;
		if(isset($productWithUpload->is_unlimited_uploads) && $productWithUpload->is_unlimited_uploads){
			$maxFilesUploads =  INF;
		}elseif(isset($productWithUpload->max_allow_uploads)){
			$maxFilesUploads =  $productWithUpload->max_allow_uploads;
		}
        $productMaxQty = $product->unlimited ? INF : (int)$product->getFullQty();

        $dispatcher->triggerEvent('onBeforeDisplayProductList', [&$product->product_related]);
        $array = [];
        $dispatcher->triggerEvent('onBeforeDisplayProduct', [&$product, &$view, &$media, &$array, &$product_demofiles]);

        $router = $mainframe->getRouter();
        $uri = $router->build('index.php?option=com_jshopping&controller=product&task=ajax_attrib_select_and_price&product_id='.$product_id."&ajax=1");
        $urlupdateprice = $uri->toString();

        $addPricesWithUserDiscount = $product->getAddPriceWithDiscounts();

        if ($product->product_is_add_price && !empty($product->product_add_prices)) {
            foreach($product->product_add_prices as $k => &$add_price) {
                $add_price->idForElement = str_replace('.', '_', $add_price->product_quantity_start);
                $addPricesWithUserDiscount[$k]->idForElement = $add_price->idForElement;
                if(!$add_price->unit_id){
                    $product->product_add_prices[$k]->unitNumberFormatStar = getUnitNumberFormat($product->add_price_unit_id, $add_price->product_quantity_start);
                }else{
                    $product->product_add_prices[$k]->unitNumberFormatStar = getUnitNumberFormat($add_price->unit_id, $add_price->product_quantity_start);
                }
                if(!$add_price->unit_id){
                    $product->product_add_prices[$k]->unitNumberFormatFinish = getUnitNumberFormat($product->add_price_unit_id, $add_price->product_quantity_finish);
                }else{
                    $product->product_add_prices[$k]->unitNumberFormatFinish = getUnitNumberFormat($add_price->unit_id, $add_price->product_quantity_finish);
                }
            }
        }

        if ($allow_review) {
            $context = 'jshoping.list.front.product.review';
            $limit = $mainframe->getUserStateFromRequest($context.'limit', 'limit', 20, 'int');
            $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
            $total =  $product->getReviewsCount();
            $view->set('reviews', $product->getReviews($limitstart, $limit));
            jimport('joomla.html.pagination');
            $pagination = new JPagination($total, $limitstart, $limit);
            $pagenav = $pagination->getPagesLinks();
            $view->set('pagination', $pagenav);
			$view->set('pagination_obj', $pagination);
            $view->set('display_pagination', $pagenav != '');
        }

        $product->transformDescrTextToModule();

        if (!empty($product->product_related)) {
            $modelOfProductsFront = JSFactory::getModel('ProductsFront');
            foreach($product->product_related as $key=>$loopProduct) {
                $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
                $product->product_related[$key]->isShowCartSection = $loopProduct->isShowCartSection();
                $product->product_related[$key]->permissions = $loopProductUserGroupPermissions;
                
            }
        }

        $cartOfOneClickBy = JSFactory::getModel('cart', 'jshop');
        $cartOfOneClickBy->load('one_click_buy');
        $cartOfOneClickBy->clear();

        $view->set('config', $jshopConfig);
        $view->set('jshopConfig', $jshopConfig);
        $view->set('display_price', getDisplayPriceForListProduct());
        $view->set('component', 'Product_default');
        $view->set('image_path', $jshopConfig->live_path . '/images');
        $view->set('noimage', $jshopConfig->noimage);
        $view->set('image_product_path', $jshopConfig->image_product_live_path);
        $view->set('video_product_path', $jshopConfig->video_product_live_path);
        $view->set('video_image_preview_path', $jshopConfig->video_product_live_path);
        $view->set('product', $product);
        $view->set('category_id', $category_id);
        $view->set('images', $media);
        $view->set('demofiles', $product_demofiles);
        $view->set('attributes', $attributes);
        $view->set('all_attr_values', $allAttrValues);
        $view->set('related_prod', $product->product_related);
        $view->set('path_to_image', $jshopConfig->live_path . 'images/');
        $view->set('live_path', JURI::root());
        $view->set('enable_wishlist', $jshopConfig->enable_wishlist);
        $view->set('action', SEFLink('index.php?option=com_jshopping&controller=cart&task=add', 1));
        $view->set('urlupdateprice', $urlupdateprice);
        $view->set('allow_review', $allow_review);
		$view->set('allow_reviews_uploads', $jshopConfig->allow_reviews_uploads);
		$view->set('review_max_uploads', $jshopConfig->review_max_uploads);
        $view->set('select_review', $select_review);
        $view->set('text_review', $text_review);
        $view->set('stars_count', $jshopConfig->max_mark);
        $view->set('parts_count', $jshopConfig->rating_starparts);
        $view->set('user', $user);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('hide_buy', $hide_buy);
        $view->set('available', $available);
        $view->set('default_count_product', $default_count_product);
        $view->set('folder_list_products', 'list_products');
        $view->set('back_value', $back_value);
		$view->set('displaybuttons', $displaybuttons);
		$view->set('upload_common_settings', $uploadCommonSettings);
        $view->set('link_to_ajax_upload_files', $linkToAjaxUploadFiles);
        $view->set('isSupportUpload', !empty($productWithUpload->product_id) && $uploadCommonSettings->is_allow_product_page);
        $view->set('isMultiUpload', $isMultiUploadProduct);
        $view->set('productMaxQty', $productMaxQty);
        $view->set('maxFilesUploads', $maxFilesUploads);
        $view->set('production_time', $_production_calendar->show_in_product);
        $view->set('smartLink', getSmartLinkForProductPage($product));
		$view->set('show_buttons', $show_buttons);
		$view->set('add_prices_with_user_discount', $addPricesWithUserDiscount);
		$view->set('usergroup_show_action', $usergroup_show_action);
		
		if(isset($view->_tmp_product_html_before_buttons)) $view->_tmp_product_html_before_buttons .= '<div id="jshop_facp_block" style="display: none"><span id="jshop_facp_label"></span> <span id="jshop_facp_result"></span><span id="jshop_facp_suffix"></span></div>';
		else $view->_tmp_product_html_before_buttons = '<div id="jshop_facp_block" style="display: none"><span id="jshop_facp_label"></span> <span id="jshop_facp_result"></span><span id="jshop_facp_suffix"></span></div>';

        $modelFreeAtrrCalcPrice = JSFactory::getModel('freeattrcalcprice');
        $modelFreeAtrrCalcPrice->replaceInputValuesToDefault($view->product->freeattributes, $view->product->product_id);
        $isIssetAtLeastOneNonEmptyParam = $modelFreeAtrrCalcPrice->isIssetAtLeastOneNonEmptyParam();

        $this->addJsVariablesToProdPage($view, $product, $attributes, $allAttrValues, $urlupdateprice, $isIssetAtLeastOneNonEmptyParam, $productWithUpload);
        ExcludeAttributeForAttribute::getInstance()->onBeforeDisplayProductView($view);
		ExcludeButtonsForAttribute::getInstance()->onBeforeDisplayProductView($view);
        $dispatcher->triggerEvent('onBeforeDisplayProductView', [&$view]);
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplayProductView($view);
        ProductsMinMaxQuantityMambot::getInstance()->onBeforeDisplayProductView($view);
        $view->set('tax_info_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=productTaxInfo', 1));
        $view->set('calculatedProductPrice', $product->getPriceCalculate($default_count_product));
        $view->set('default_count_product', $default_count_product);
        $view->set('productUsergroupPermissions', $product->getUsergroupPermissions());
        $view->set('isShowCartSection', $product->isShowCartSection());
        $view->set('sprintQtyInStock', sprintQtyInStock($product->qty_in_stock ?? 0));
        $view->set('show_wishlist_button', $jshopConfig->show_wishlist_button);
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('manufacturer_link', SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view&manufacturer_id=' . $product->product_manufacturer_id, 2));
        $view->set('weight', formatweight($product->getWeight()));
        $view->set('reviewsave', SEFLink('index.php?option=com_jshopping&controller=product&task=reviewsave'));
        $view->set('file_exists_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=file_exists_link'));
        $view->set('request_uri', jsFilterUrl($_SERVER['REQUEST_URI']));
        $view->set('page_type', 'product');
        $view->set('show_base_price', $jshopConfig->show_base_price_for_product_list);
        $view->set('printselectquantitycart_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantityCart', 1));
        $view->set('toCheckout', SEFLink('index.php?option=com_jshopping&controller=product&task=toCheckout&productId=' . $product->product_id, 1));
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1, 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));
        $view->set('href_add', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&ajax=1', 1, 1));
        $view->set('href_view', SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=display', 1, 1));
        $view->set('href_close', SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=close', 1, 1));
        $view->set('href_address_data', SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=addressData&temp=0', 1, 1));
        $view->set('href_refresh', SEFLink('index.php?option=com_jshopping&controller=cart&task=refresh&ajax=1', 1, 1));
        $view->set('href_discount', SEFLink('index.php?option=com_jshopping&controller=cart&task=discountsave&ajax=1', 1, 1));
        $view->set('href_product', SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=', 1, 1));
        $view->set('href_error_attr', SEFLink('index.php?option=com_jshopping&controller=cart_popup&task=error&category_id=_cid_&product_id=_pid_&message=_msg_', 1, 1));
        $view->set('confirm_remove', JText::_('COM_SMARTSHOP_CONFIRM_REMOVE'));
        $view->set('base_url', JURI::base());
        $view->set('sprintjstempfiles', sprintJsTemplateForNativeUploadedFiles($isMultiUploadProduct));
        $view->set('sprintjstemplatefornativeuploadedfiles_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintJsTemplateForNativeUploadedFiles', 1));
		$document->addCustomTag('<script type="text/javascript">
                var href_add = "'. SEFLink('index.php?option=com_jshopping&controller=cart&task=add&ajax=1', 1, 1) .'";
                var href_view = "'. SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=display', 1, 1) .'";
                var href_close = "'. SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=close', 1, 1) .'";
                var href_address_data = "'. SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=addressData&temp=0', 1, 1) .'";
                var href_refresh = "'. SEFLink('index.php?option=com_jshopping&controller=cart&task=refresh&ajax=1', 1, 1) .'";
                var href_discount = "'. SEFLink('index.php?option=com_jshopping&controller=cart&task=discountsave&ajax=1', 1, 1) .'";
                var href_product = "' . SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=', 1, 1) . '";
                var href_error_attr = "'.SEFLink('index.php?option=com_jshopping&controller=cart_popup&task=error&category_id=_cid_&product_id=_pid_&message=_msg_', 1, 1) . '";
                var confirm_remove = "' . JText::_('COM_SMARTSHOP_CONFIRM_REMOVE') . '";        
                var base_url = "' . JURI::base() . '";
                var active_el = "";
            </script>');
        $context = 'smartshop.front.user.addressPopup';
        $searchText = $this->input->get('search_text', '');
        $addrType = $this->input->get('addrType', '');
        $isSearchReset = (bool)$this->input->get('search_text_reset', false);
        $searchLikeWord = [];
        $app = JFactory::getApplication();
        $modelOfUserAddressesFront = JSFactory::getModel('UserAddressesFront');
        if ($isSearchReset) {
            $app->setUserState('smartshop.popupSearch', '');
        }

        if (!empty($searchText)) {
            $app->setUserState('smartshop.popupSearch', $searchText);
        } else {
            $searchText = $app->getUserState('smartshop.popupSearch', '');
        }
        $shopUser = JSFactory::getUser();
        $limit = $app->getUserStateFromRequest($context . 'limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

        if (!empty($searchText)) {
            $searchLikeWord = [
                'word' => "{$searchText}%",
                'byColumns' => [
                    'l_name',
                    'f_name',
                    'street',
                    'street_nr',
                    'zip',
                    'city'
                ]
            ];
        }
        $allUserAddresses = $modelOfUserAddressesFront->getAllByUserId($shopUser->user_id, $limitstart, $limit, $searchLikeWord);


        $document->addScriptDeclaration('
            document.addEventListener("DOMContentLoaded", function () {
                 shopUserAddressesPopup.setUserAddresses(' . json_encode($allUserAddresses) . ');
                 
                 parent.document.addEventListener("visibilitychange", function(e) {
                    var isIframeDisplayes = false;

                    try {
                        isIframeDisplayes = document.querySelector("#checkout_address_step").style.display == "block";
                    } catch (error) {}

                    if (!document.hidden && isIframeDisplayes) {
                       shopOneClickCheckout.reloadAddressData(); 
                    }
                });
                
            });
        ');

        $requiredConfigFields = $jshopConfig->buildArrayWithFieldsRegisterForJS('address', 2);


        $dataToFrontJs = [
            'jshopConfig' => $jshopConfig,
            'live_path' => JURI::base(),
            'qc_ajax_link' => '/index.php?option=com_jshopping&controller=one_click_checkout&task=ajaxRefresh',
            'qc_ajax_reload_link' => '/index.php?option=com_jshopping&controller=qcheckout&task=reloadShippingPrice',
            'register_field_require' => $requiredConfigFields,
            'payment_type_check' => [],
            'payment_class' => ''
        ];
        $document->addScriptOptions('qCheckout', $dataToFrontJs);
        $document->addScriptOptions('isAgbEnabled', (bool)$jshopConfig->display_agb);
        $view->set('sef', JFactory::getConfig()->get('sef'));
		$document->addScriptDeclaration('const dataJson='.json_encode(prepareView($view)));
        if ($ajax) {
            print json_encode(prepareView($view));die;
        }
        $view->display();
        $dispatcher->triggerEvent('onAfterDisplayProduct', [&$product]);


    }

    protected function addJsVariablesToProdPage(&$view, &$product, $attributes, $all_attr_values, $urlupdateprice, $check_params, $productWithUpload)
    {		
        $document = JFactory::getDocument();
        $config = JSFactory::getConfig();
        $document->addScriptOptions('link_to_ajax_upload_files', '/index.php?option=com_jshopping&controller=upload&task=ajaxUploadFile&product_id=' . $product->product_id);
        $document->addScriptOptions('config', [
            'image_attributes_live_path' => $config->image_attributes_live_path,
            'image_product_live_path' => $config->image_product_live_path,
            'live_path' => $config->live_path . 'images',
            'urlupdateprice' => $urlupdateprice
        ]);

        $document->addScriptDeclaration($view->_tmp_product_ext_js ?? '');

        if (!empty($attributes)) {
            $attrIterator = 0;

            foreach ($attributes as $attribut) {
                $document->addScriptDeclaration('
                window.addEventListener("load", () => {
                    shopProduct.setAttributeValue("'. $attribut->attr_id .'", "'. $attribut->firstval .'");
                    shopProduct.setAttributeList("'. $attrIterator++ .'", "'. $attribut->attr_id .'")
                });
                ');
            }
        }

        if (!empty($all_attr_values)) {
            foreach($all_attr_values as $attrval) {
                if (!empty($attrval->image)) {
                    $document->addScriptDeclaration('
                    window.addEventListener("load", () => {
                        shopProduct.setAttributeImage("'. $attrval->value_id .'", "'. $attrval->image .'")
                    });
                    ');
                }
            }
        }
        
        $document->addScriptOptions('reviewLangs', [
            'review_user_name' => JText::_('COM_SMARTSHOP_REVIEW_NAME'),
            'review_user_email' => JText::_('COM_SMARTSHOP_REVIEW_EMAIL'),
            'review_review' => JText::_('COM_SMARTSHOP_REVIEW_COMMENT')
        ]);

        $document->addScriptOptions('uploadData', [
            'upload_common_settings' => $view->upload_common_settings,
            'isSupportUpload' => $view->isSupportUpload,
            'isMultiUpload' => $view->isMultiUpload,
            'productMaxQty' => (string)$view->productMaxQty,
            'maxFilesUploads' => (string)$view->maxFilesUploads,
            'is_required_upload' => $productWithUpload->is_required_upload ?? false,
            'is_upload_independ_from_qty' => $productWithUpload->is_upload_independ_from_qty ?? false
        ]);

        if ($check_params) {
            $view->_tmp_product_html_end .= '<script>free_attributte_recalcule();</script>';
        }
    }
    
    public function getfile()
    {
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        $order = JSFactory::getTable('order', 'jshop');

        $fileId = JFactory::getApplication()->input->getInt('id'); 
        $orderId = JFactory::getApplication()->input->getInt('oid');
        $hash = JFactory::getApplication()->input->getVar('hash');
        $rl = JFactory::getApplication()->input->getInt('rl');
        
        $order->load($orderId);

        $checkAccessToFile = function () use ($order, $hash, $jshopConfig, $rl, $orderId, $fileId, $user) {
            $doc = JFactory::getDocument();

            if ($order->file_hash != $hash) {
                throw new \Exception('Error download file', 500);
                return 0;
            }
            
            if (!in_array($order->order_status, $jshopConfig->payment_status_enable_download_sale_file)) {
                //JError::raiseWarning(500, JText::_('COM_SMARTSHOP_FOR_DOWNLOAD_ORDER_MUST_BE_PAID'));
				throw new Exception(JText::_('COM_SMARTSHOP_FOR_DOWNLOAD_ORDER_MUST_BE_PAID'),500);	
                return 0;
            }
    
            if ($rl == 1) {
                //fix for IE
                $newDownloadUrl = JURI::root() . "index.php?option=com_jshopping&controller=product&task=getfile&oid={$orderId}&id={$fileId}&hash={$hash}"; 
                $doc->addScriptDeclaration("location.href='{$newDownloadUrl}';");
                die();
            }
            
            if ($jshopConfig->user_registered_download_sale_file && !empty($order->user_id) && $order->user_id != $user->id) {
                checkUserLogin();
            }
            
            $secondsOnOneDay = 86400;
            if ($jshopConfig->max_day_download_sale_file && (time() > ($order->getStatusTime()+($secondsOnOneDay*$jshopConfig->max_day_download_sale_file))) ) {
                //JError::raiseWarning(500, JText::_('COM_SMARTSHOP_TIME_DOWNLOADS_FILE_RESTRICTED'));
				throw new Exception(JText::_('COM_SMARTSHOP_TIME_DOWNLOADS_FILE_RESTRICTED'),500);	
                return 0; 
            }
        };
        
        if ($checkAccessToFile() === 0) {
            return 0;
        }
        
        $orderItems = $order->getAllItems();
		$filesIds = [];
        
        foreach($orderItems as $orderItem) {
            foreach($orderItem->files as $orderFile) {
                $filesIds[] = $orderFile->id;
            }
        }
        
        if (!in_array($fileId, $filesIds)) {
            throw new \Exception('Error download file', 500);
            return 0;
        }
        
        $stat_download = $order->getFilesStatDownloads();
        
        if ($jshopConfig->max_number_download_sale_file > 0 && $stat_download[$fileId]['download'] >= $jshopConfig->max_number_download_sale_file) {
            //JError::raiseWarning(500, JText::_('COM_SMARTSHOP_NUMBER_DOWNLOADS_FILE_RESTRICTED'));
			throw new Exception(JText::_('COM_SMARTSHOP_NUMBER_DOWNLOADS_FILE_RESTRICTED'),500);	
            return 0;
        }
        
        $file = JSFactory::getTable('productFiles', 'jshop');
        $file->load($fileId);

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterLoadProductFile', [&$file, &$order]);
        $downloadFile = $file->file;

        if (empty($downloadFile)) {
            \JFactory::getApplication()->enqueueMessage('Error download file','error');
            return 0;
        }

        $pathToDownloadFile = getPatchProductImage($downloadFile, '', 4);
        if (!file_exists($pathToDownloadFile)) {
            \JFactory::getApplication()->enqueueMessage('Error. File not exist','error');
            return 0;
        }
        
        $stat_download[$fileId]['download'] = intval($stat_download[$fileId]['download']) + 1;
        $stat_download[$fileId]['time'] = getJsDate();
        
        $order->setFilesStatDownloads($stat_download);
        $order->store();

        $readDownloadFile = function () use ($pathToDownloadFile) {
            ob_end_clean();
            @set_time_limit(0);
            $fp = fopen($pathToDownloadFile, 'rb');
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . (string)(filesize($pathToDownloadFile)));
            header('Content-Disposition: attachment; filename="' . basename($pathToDownloadFile) . '"');
            header('Content-Transfer-Encoding: binary');

            while((!feof($fp)) && (connection_status() == 0) ) {
                echo (fread($fp, 1024*8));
                flush();
            }
            fclose($fp);
        };
        
        $readDownloadFile();
        die();
    }
    
    public function upload_img(){	
		$jshopConfig = JSFactory::getConfig();		
		require_once $jshopConfig->path . 'lib/uploadfile.class.php';
		$upload = new UploadFile($_FILES['file']);
        $upload->setAllowFile(array('jpeg','jpg','gif','png'));
        $upload->setDir($jshopConfig->files_product_review_path);
        $upload->setFileNameMd5(0);
        $upload->setFilterName(1);
		if ($upload->upload()){
			$name = $upload->getName();
            @chmod($jshopConfig->files_product_review_path."/".$name, 0777);


		require_once ($jshopConfig->path.'lib/image.lib.php');
		$url = $jshopConfig->files_product_review_path."/".$name;
		$url_parts = pathinfo($url);
		//filenames		
		$name_image = $url_parts['basename'];
		$name_thumb = "thumb_".$name_image;
		$name_full = "full_".$name_image;
		//file path
		
		$path_image = $jshopConfig->files_product_review_path .'/'.$name_image;
		$path_thumb = $jshopConfig->files_product_review_path .'/'.$name_thumb;
		$path_full = $jshopConfig->files_product_review_path .'/'.$name_full;
		//resize thumb
		$review_product_width = $jshopConfig->review_product_width;
		$review_product_height = $jshopConfig->review_product_height;            
		if (!ImageLib::resizeImageMagic($path_image, $review_product_width, $review_product_height, $jshopConfig->image_cut,$jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_CREATE_THUMBAIL')." ".$name_thumb,'error');
			saveToLog("error.log", "Resize Product Image - Error create thumbail ".$name_thumb);
			$error = 1;
		}
		//resize image
		$review_product_full_width = $jshopConfig->review_product_full_width; 
		$review_product_full_height = $jshopConfig->review_product_full_height;            
		if (!ImageLib::resizeImageMagic($path_image, $review_product_full_width, $review_product_full_height, $jshopConfig->image_cut,$jshopConfig->image_fill, $path_full, $jshopConfig->image_quality, $jshopConfig->image_fill_color)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_CREATE_THUMBAIL')." ".$name_image,'error');
			saveToLog("error.log", "Resize Product Image - Error create image ".$name_image);
			$error = 1;

		} 

			echo $name;die();
            
        }else{
            if ($upload->getError() != 4){
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_UPLOADING_IMAGE'),'error');
                saveToLog("error.log", "SaveManufacturer - Error upload image. code: ".$upload->getError());
            }
        }
		
		die();
	}
    public function reviewsave(){
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser(); 
        $post = JFactory::getApplication()->input->post->getArray();
        $backlink = JFactory::getApplication()->input->getVar('back_link');
        $product_id = JFactory::getApplication()->input->getInt('product_id');        
		
		if (JFactory::getApplication()->input->getInt('saved')){
			if ($jshopConfig->display_reviews_without_confirm) {
				$this->setRedirect($backlink, JText::_('COM_SMARTSHOP_YOUR_REVIEW_SAVE_DISPLAY'));
			} else {
				$this->setRedirect($backlink, JText::_('COM_SMARTSHOP_YOUR_REVIEW_SAVE'));
			}
		}else{
			
        $mainframe->input->checkToken() or jexit('Invalid Token');
        $dispatcher =\JFactory::getApplication();
        
        $review = JSFactory::getTable('review', 'jshop');
        
        if ($review->getAllowReview() <= 0) {
            \JFactory::getApplication()->enqueueMessage(jshopReview::getText(),'error');
            $this->setRedirect($backlink);
            return 0;
        }
				
        $review->bind($post);
        $review->time = getJsDate();
        $review->user_id = $user->id;
        $review->ip = $_SERVER['REMOTE_ADDR'];
        if ($jshopConfig->display_reviews_without_confirm) {
            $review->publish = 1;    
        }

        $dispatcher->triggerEvent('onBeforeSaveReview', [&$review]);
        if (!$review->check()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ENTER_CORRECT_INFO_REVIEW'),'error');
            $this->setRedirect($backlink);
            return 0;
        }
        $review->store();

        $dispatcher->triggerEvent('onAfterSaveReview', [&$review]);

        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);
        $product->loadAverageRating();
        $product->loadReviewsCount();
        $product->store();

        $lang = JSFactory::getLang();
        $name = $lang->get('name');

        $view_name = 'emails';
        $view = $this->getView($view_name, getDocumentType(), '', [
            'template_path' => viewOverride($view_name,"commentemail.php")
        ]);
        $view->setLayout('commentemail');
        $view->set('product_name', $product->$name);
        $view->set('user_name', $review->user_name);
        $view->set('user_email', $review->user_email);
        $view->set('mark', $review->mark);
        $view->set('review', $review->review);
        $message = $view->loadTemplate();
		
		$dataForTemplate = array('emailSubject'=>JText::_('COM_SMARTSHOP_NEW_COMMENT'), 'emailBod'=>$message);
		$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
		
		$send_email_toadmin = 0;
		if ($jshopConfig->sendmail_reviews_admin_email) {			
			if ($jshopConfig->sendmail_reviews_admin_email_all_reviews) {
				$send_email_toadmin = 1;
            }
            
			if (!$jshopConfig->display_reviews_without_confirm && $jshopConfig->sendmail_reviews_admin_email_require_confirmation) {
				$send_email_toadmin = 1;
            }
            
			if (!$user->id && $jshopConfig->sendmail_reviews_admin_email_from_guests) {
				$send_email_toadmin = 1;
			}
		}
		
		$app = JFactory::getApplication();
		if ($send_email_toadmin && $app->get('mailonline', 1)) {
			
			$mailer = JFactory::getMailer();
			$mailer->setSender([
                $mainframe->getCfg('mailfrom'),
                $mainframe->getCfg('fromname')
            ]);
			$mailer->addRecipient(explode(',', $jshopConfig->contact_email));
			$mailer->setSubject(JText::_('COM_SMARTSHOP_NEW_COMMENT'));
			$mailer->setBody($bodyEmailText);
			$mailer->isHTML(true);
			$mailer->Send();
		}
		
			if ($jshopConfig->display_reviews_without_confirm) {			
				$this->setRedirect($backlink, JText::_('COM_SMARTSHOP_YOUR_REVIEW_SAVE_DISPLAY'));
			} else {			
				$this->setRedirect($backlink, JText::_('COM_SMARTSHOP_YOUR_REVIEW_SAVE'));
			}	
		}
    }

    public function reactData(){

    }
    /**
    * get attributes html selects, price for select attribute 
    */
   public function ajax_attrib_select_and_price()
    {     
        $jshopConfig = $this->jshopConfig = JSFactory::getConfig();
        $pathToFolderWithProdTmpls = $jshopConfig->template_path . '/blocks';
        $pathToTmplProductImgThumb = $pathToFolderWithProdTmpls . '/media_product_block.php';
        $ajaxResponse = [];
        $productQty = $this->input->get('qty', 0);
        $quantity = $this->input->get('quantity', 0);
        $change_attr = $this->input->getInt('change_attr', null);
        $product_id = $this->input->getInt('product_id', null);
        $amountOfUploads = $this->input->getInt('amountOfUploads', 0);
        $if_react = $this->input->getInt('if_react', 0);
		
        $ajaxPageFrom = $this->input->getVar('fromPage', 'product_page');
        $attribs = is_array(JFactory::getApplication()->input->getVar('attr')) ? JFactory::getApplication()->input->getVar('attr') : [];
        $freeattr = is_array(JFactory::getApplication()->input->getVar('freeattr')) ? JFactory::getApplication()->input->getVar('freeattr') : [];
        if(!$productQty) $productQty = $quantity;
        $productQty = str_replace(',', '.', $productQty) * 1;
        $isProductQtyWasForbiddenFloat = false;
        if (!$jshopConfig->use_decimal_qty && is_float($productQty)) {
            $productQty = (int)$productQty;
            $isProductQtyWasForbiddenFloat = true;
        }

        if ($productQty <= 0) {
            $productQty = 1;
        }

        $productQtyFromRequest = $productQty;
        $dispatcher =\JFactory::getApplication();        
        $dispatcher->triggerEvent('onBeforeLoadDisplayAjaxAttrib', [&$product_id, &$change_attr, &$productQty, &$attribs, &$freeattr]);
        
        $product = JSFactory::getTable('product', 'jshop'); 
        $product->load($product_id);

        $dispatcher->triggerEvent('onBeforeLoadDisplayAjaxAttrib2', [&$product]);

        $attributesDatas = $product->getAttributesDatas($attribs, JSFactory::getUser()->usergroup_id);
        $product->setAttributeActive($attributesDatas['attributeActive']);
        $attributeValues = $attributesDatas['attributeValues'];

        if (!empty($product->quantity_select) && $jshopConfig->use_extend_attribute_data && $this->isChangedAttrIsDependent()) {
            $productQty = (float)reset(explode(',', $product->quantity_select));
        }

        $productQty = corectDefaultCount($product, $productQty);
        $minimumQty = $product->min_count_product ?: 1;
        $maximumQty = $product->max_count_product ?: PHP_INT_MAX;
        $productQty = ($productQty < $minimumQty) ? $minimumQty : $productQty;
        $productQty = ($productQty > $maximumQty) ? $maximumQty : $productQty;
		$product->qty = $productQty;
		$product->productQty=$productQty;
		if (!empty($product->quantity_select)) $product->quantity_select=$productQty;
        if (!empty($product->label_id)) {
            $image = getNameImageLabel($product->label_id);
        
            if (!empty($image)) {
                $product->_label_image = getPatchProductImage($image, '', 1);
            }
        
            $product->_label_name = getNameImageLabel($product->label_id, 2);
        }

        $modelFreeAtrrCalcPrice = JSFactory::getModel('freeattrcalcprice');
        $paramsOfFreeAttrCalcPrice = (object)$modelFreeAtrrCalcPrice->getAddonParameters();
        $formulaCalcParams = $modelFreeAtrrCalcPrice->getParameters();
        
        $this->updateAjaxExtDataFreeAttrs($product, $formulaCalcParams, $ajaxResponse, $pathToFolderWithProdTmpls, $freeattr, $change_attr);

        $product->setFreeAttributeActive($freeattr);

        if (($ajaxPageFrom == 'product_list' && $jshopConfig->productlist_allow_buying) || $ajaxPageFrom != 'product_list') {
            $attributes = $product->getBuildSelectAttributes($attributeValues, $attributesDatas['attributeSelected']);
		}else{
			$attributes = [];
		}
            $product->setAttributeActive($attributesDatas['attributeSelected']);
        
	
        $productId = $product_id;
        if (isset($product->isTransferedDependParamsToMainProduct) && $product->isTransferedDependParamsToMainProduct) {
            $productId = $product->getActiveProduct()->product_id;
        }
		
		if($change_attr){
			foreach($attributes as $attrId => $attr) {            
				$ajaxResponse[] = '"id_' . $attrId . '":"' . json_value_encode($attr->selects, 1) . '"';
			}
        }

        if (!empty($amountOfUploads)) {
            $uploadPrice = JSFactory::getModel('NativeUploadsPricesFront')->getUploadPriceData($productId, $amountOfUploads);
            $product->setNativeUploadPrice($uploadPrice, $amountOfUploads);			
        }
		
		
        $product->getExtendsData(true);
		
        $pricefloat = $product->product_price_calculate;
        $price = precisionformatprice($pricefloat);
        $available = intval($product->getQty() > 0);
		$displaybuttons = intval(intval($product->getQty() > 0) || $jshopConfig->hide_buy_not_avaible_stock == 0);
        $ean = $product->getEan();
        $weight = formatweight($product->getWeight());
        $basicprice = precisionformatprice($product->getBasicPrice());

        $prodAttr2Table = JSFactory::getTable('ProductAttribut2');
        if ($ajaxPageFrom == 'product_list') {
			$calculatedPrice = $prodAttr2Table->calcAttrsWithOneTimeCostPriceType($product->product_id, $attributesDatas['attributeActive'], $pricefloat);
		}else{
			$calculatedPrice = $prodAttr2Table->calcAttrsWithOneTimeCostPriceType($product->product_id, $attributesDatas['attributeActive'], $pricefloat * $productQty);
		}
		if(isset($product->attribute_active_data->production_time) && $product->attribute_active_data->production_time){
			$production_time = $product->attribute_active_data->production_time;
		}else{
			$production_time = $product->production_time;
		}
				
		$nativeUploadPrice = $product->getNativeUploadPrice();		
		if ((!empty($nativeUploadPrice))&&($product->is_activated_price_per_consignment_upload_disable_quantity)) {		
            $amountOfUploads = $product->getNativeAmountOfUploads();
            $calculatedPrice = $nativeUploadPrice->modifyPrice($calculatedPrice, $amountOfUploads);            
        }
        		
        $ajaxResponse[] = '"calculatedPrice":"' . json_value_encode(formatprice($calculatedPrice)) . '"';
        $ajaxResponse[] = '"price":"' . json_value_encode($price) . '"';
        $ajaxResponse[] = '"pricefloat":"'.$pricefloat.'"';
        $ajaxResponse[] = '"currencySymbol":"' . $jshopConfig->currency_code . '"';
        $ajaxResponse[] = '"available":"' . $available . '"';
        $ajaxResponse[] = '"ean":"' . json_value_encode($ean) . '"';
        $ajaxResponse[] = '"available_text":"' . JText::_('COM_SMARTSHOP_STOCK_NOT_AVAILABLE') . '"';
        $ajaxResponse[] = '"displaybuttons":"' . $displaybuttons . '"';
        $ajaxResponse[] = '"production_time":"' . $production_time . '"';

        if ($jshopConfig->admin_show_product_basic_price) {
            $ajaxResponse[] = '"basicprice":"' . json_value_encode($basicprice) . '"';
        }

        if ($jshopConfig->product_show_weight) {
            $ajaxResponse[] = '"weight":"' . json_value_encode($weight) . '"';
        }

        if (isset($product->product_price_default) && $jshopConfig->product_list_show_price_default && $product->product_price_default > 0) {
            $ajaxResponse[] = '"pricedefault":"' . json_value_encode(formatprice($product->product_price_default)) . '"';
        }

        if ($jshopConfig->product_show_qty_stock) {
			$expirationQty = JSFactory::getModel('attrsfront')->countAttrDataValProduct($product->product_id, $product->attribute_active);
		  if($product->product_packing_type == 1 && $expirationQty){
             $qty_in_stock = $expirationQty;
		  }else{
			  $qty_in_stock = getDataProductQtyInStock($product);			  
		  }
            $ajaxResponse[] = '"qty":"' . json_value_encode(sprintQtyInStock($qty_in_stock)) . '"';
        }

        if ($available) {
            $ajaxResponse[] = '"available_text":"' . JText::_('COM_SMARTSHOP_STOCK_AVAILABLE') . '"';
			$available = JText::_('COM_SMARTSHOP_STOCK_AVAILABLE');
        } 
		
        $product->updateOtherPricesIncludeAllFactors();
        $addPriceWithDiscount = $product->getAddPriceWithDiscounts();
        if (!empty($addPriceWithDiscount)) {
            foreach($addPriceWithDiscount as $k => $v) {
                $ajaxResponse[] = '"pq_' . str_replace('.', '_', $v->product_quantity_start) . '":"' . json_value_encode(formatprice($v->price_wp ?? $v->price)) . '"';
            }
        } 
        if ($product->product_old_price) {
            $old_price = formatprice($product->product_old_price);
            $ajaxResponse[] = '"oldprice":"' . json_value_encode($old_price) . '"';
        }
		
        if ($jshopConfig->hide_delivery_time_out_of_stock) {
            $ajaxResponse[] = '"showdeliverytime":"' . $product->getDeliveryTimeId() . '"';            
        }

		if ($ajaxPageFrom == 'product_page' || ($ajaxPageFrom == 'product_list' && $jshopConfig->product_list_show_short_description)) {
            $product->short_description = ($jshopConfig->product_show_short_description) ? JHtml::_('content.prepare', $product->getTexts()->short_description) : '';
		}

        $user = JFactory::getUser();
        $cartBtnMarkup = '';
        if ($product->isShowCartSection() && !$jshopConfig->user_as_catalog) { 
            $cartBtnMarkup = renderTemplate([
                templateOverrideBlock('blocks','cart_product.php', 1)
            ], 'cart_product', [
                'product' => $product,
                'user' => $user,
                'jshopConfig' => $jshopConfig,
                'page_type' => $ajaxPageFrom
            ]);
        }
        $ajaxResponse[] = '"cart_button_markup":"' . json_value_encode($cartBtnMarkup, 1) . '"';
                
        $productWithUpload = $product->getEssenceWithActiveUpload();

        $uploadCommonSettings = JSFactory::getModel('upload')->getParams();
		
        if ($jshopConfig->use_extend_attribute_data && !$jshopConfig->user_as_catalog && $this->isChangedAttrIsDependent()) {
            $media = $product->getMedia();
			$demofiles = $product->getDemoFiles();

			$this->config = $jshopConfig;
			$this->demofiles = $demofiles; 
			$copy_pr = $product;
			ob_start();
			include  templateOverrideBlock('blocks', 'demofiles.php');
			$demofiles = ob_get_contents();
			ob_end_clean();	
			$product = $copy_pr;			
            $ajaxResponse[] = '"demofiles":"' . json_value_encode($demofiles, 1) . '"';
			
            if ($jshopConfig->admin_show_product_related) {
                $relatedProducts = $product->getRelatedProducts();

                $ajaxResponse[] = '"relatedProdsMarkup": ""';
                if (!empty($relatedProducts)) {
                    $this->related_prod = $relatedProducts;
					$copy_pr = $product;
					ob_start();
					include  templateOverrideBlock('blocks', 'related.php');
					$markUpOfRelatedProds = ob_get_contents();
					ob_end_clean();	
					$product = $copy_pr; 	
					$this->product = $product;
				
                    $ajaxResponse[] = '"relatedProdsMarkup": "' . json_value_encode($markUpOfRelatedProds, 1) . '"';  
                }
            }$product = $copy_pr;

            $ajaxResponse[] = '"productCodeMarkup": ""';
            if (!empty($product->getEan())) {
				$copy_pr = $product;
                $this->product = $product;
				ob_start();
				include  templateOverrideBlock('blocks', 'code.php');
				$markUpOfProdCode = ob_get_contents();
				ob_end_clean();    
				$product = $copy_pr; 	
                $this->product = $product;
                $ajaxResponse[] = '"productCodeMarkup": "' . json_value_encode($markUpOfProdCode, 1) . '"';
            }
            
			if (file_exists($pathToTmplProductImgThumb)) {
                $product->getDescription();
				
                $this->config = $jshopConfig;            
                $this->images = $media;                      
                $this->product = $product;             
                $this->noimage = $jshopConfig->noimage;             
                $this->image_product_path = $jshopConfig->image_product_live_path;  
                $this->video_product_path = $jshopConfig->video_product_live_path;  
                $this->video_image_preview_path = $jshopConfig->video_product_live_path;  
                $this->path_to_image = $jshopConfig->live_path . 'images/'; 
				$copy_pr = $product; 
				ob_start();
				include  templateOverrideBlock('blocks', 'media_product_block.php');
				$productMedia = ob_get_contents();
				ob_end_clean();           
				$product = $copy_pr;                   
                $this->product = $product;     
                $ajaxResponse[] = '"media_block":"' . json_value_encode($productMedia, 1) . '"';
            }

            $isSupportUpload = $uploadCommonSettings->is_allow_product_page && isset($productWithUpload->is_allow_uploads) && $productWithUpload->is_allow_uploads && ($productWithUpload->is_unlimited_uploads || $productWithUpload->max_allow_uploads >= 1);

            if ($uploadCommonSettings->is_allow_product_page && !empty($productWithUpload->product_id) && $product->isShowCartSection() && !$jshopConfig->user_as_catalog) {
                $isMultiUpload = $productWithUpload->max_allow_uploads >= 2 || $productWithUpload->is_unlimited_uploads;
                $maxFilesUploads = ($productWithUpload->is_unlimited_uploads) ? INF : $productWithUpload->max_allow_uploads;
                $productMaxQty = $productWithUpload->unlimited ? INF : (int)$productWithUpload->getFullQty();
                $default_count_product = 1;
                
				$this->config = $jshopConfig;            
                $this->product = $productWithUpload; 
                $this->isMultiUpload = $isMultiUpload;
                $this->maxFilesUploads = $maxFilesUploads;
                $this->productMaxQty = $productMaxQty;
                $this->default_count_product = $default_count_product;
                $this->isShowCartSection = $product->isShowCartSection();
                $this->isSupportUpload = $isSupportUpload;
				$copy_pr = $product;
				ob_start();
				include  templateOverrideBlock('blocks', 'default_prod_upload.php');
				$uploadMarkUp = ob_get_contents();
				ob_end_clean(); 
				$product = $copy_pr;
                $jsonUploadData = [
                    'markUp' => $uploadMarkUp,
                    'jsVariables' => [
                        'options' => [
                            'uploadData' => [
                                'upload_common_settings' => $uploadCommonSettings,
                                'isSupportUpload' => $isSupportUpload,
                                'isMultiUpload' => $isMultiUpload,
                                'productMaxQty' => (string)$productMaxQty,
                                'maxFilesUploads' => (string)$maxFilesUploads,
                                'is_required_upload' => $productWithUpload->is_required_upload,
                                'is_upload_independ_from_qty' => $productWithUpload->is_upload_independ_from_qty
                            ]
                        ]
                    ]
                ];
				if($this->isChangedAttrIsDependent()){
					$ajaxResponse[] = '"upload_data": ' . json_encode($jsonUploadData);
				}
            }

            if ($ajaxPageFrom == 'product_list') {
                ob_start();
				include templateOverrideBlock('blocks', 'product_list_quantity.php');
				$markupOfProductQuantity = ob_get_contents();
				ob_end_clean(); 
            } else {
                
				if($product->product_template == 'stone'){
					 $markupOfProductQuantity = renderTemplate([
						templateOverrideBlock('blocks', 'product_quantity_vernissage.php', 1)
					], 'product_quantity_vernissage', [
						'product' => $product,
						'default_count_product' => $productQty,
                        'jshopConfig' => $jshopConfig
					]);
				}else{
					$markupOfProductQuantity = renderTemplate([
						templateOverrideBlock('blocks', 'product_quantity.php', 1)
					], 'product_quantity', [
						'product' => $product,
						'default_count_product' => $productQty,
                        'jshopConfig' => $jshopConfig
					]);
				}
				
            }
            $ajaxResponse[] = '"quantity_markup": ' . json_encode($markupOfProductQuantity);
        } 

        if (empty($productWithUpload->product_id)) {
            $ajaxResponse[] = '"upload_data": ""';
        }
        
        $productWithActivePricesPerCons = $product->getEssenceWithActivePricesPerCons();
		if (!empty($productWithActivePricesPerCons->product_is_add_price) && !empty($productWithActivePricesPerCons->isShowBulkPrices())) {
            if ($addPriceWithDiscount[1]->product_quantity_start<$addPriceWithDiscount[0]->product_quantity_start) {
				$addPricesWithUserDiscount = array_reverse($addPriceWithDiscount); 
			} else {
				$addPricesWithUserDiscount = $addPriceWithDiscount;
			}
            $addPrices = new stdClass();
            $addPrices->product_add_prices = $addPricesWithUserDiscount;
			$units = JSFactory::getAllUnits();
			
		

			$addPrices->_display_price=1;
			$addPrices->is_show_bulk_prices=1;
			$addPrices->product_is_add_price=$productWithActivePricesPerCons->product_is_add_price;
			
            $addPrices->add_price_unit_id = $product->add_price_unit_id;
            $addPrices->product_add_price_unit = $product->product_add_price_unit;
			
            $pricePerConsigmentsPricesList = renderTemplate([
                templateOverrideBlock('blocks', 'bulk_prices.php', 1)
            ], 'bulk_prices', [
                'product' => $addPrices,
                'add_prices_with_user_discount' =>  $addPrices->product_add_prices,
                'jshopConfig' => $jshopConfig
            ]);

            $ajaxResponse[] = '"bulk_prices":"' . json_value_encode($pricePerConsigmentsPricesList, 1) . '"';
        }
        $extraFieldsMarkup = '';
        if ($jshopConfig->admin_show_product_extra_field) {
            $product->extra_field = $product->getExtraFields();

            $extraFieldsMarkup = renderTemplate([
                templateOverrideBlock('blocks', 'extra_fields.php', 1)
            ], 'extra_fields', [
                'product' => $product,
                'jshopConfig' => $jshopConfig
            ]) ?: '';
        }
        $ajaxResponse[] = '"extra_fields_markup":"' . json_value_encode($extraFieldsMarkup, 1) . '"';
		
        $modelOfFreeAttrCalcPriceFront = JSFactory::getModel('FreeAttrCalcPriceFront');
		$idsOfAttrsActvs = array_keys($product->free_attribute_active);
        $isIssetAtLeastOneNonEmptyFreeAttrCalcPriceParam = $modelOfFreeAttrCalcPriceFront->isIssetAtLeastOneIdInFormulaCalcBasicParams($idsOfAttrsActvs);
        $product->error_message = $product->error_message ? $product->error_message : [];
//print_r($product->error_message);die;
        if ($isProductQtyWasForbiddenFloat) {
            $product->error_message[] = JText::_('COM_SMARTSHOP_QUANTITY_MUST_BE_INTEGER');
        }
        
        if ($productQtyFromRequest < $minimumQty) {
            $product->error_message[] = JText::sprintf('COM_SMARTSHOP_QUANTITY_MUST_NOT_BE_LESS_FOR', $minimumQty);
        }

        if ($productQtyFromRequest > $maximumQty) {
            $product->error_message[] = JText::sprintf('COM_SMARTSHOP_QUANTITY_MUST_NOT_BE_MORE_FOR', $maximumQty);
        }
             
        if ($isIssetAtLeastOneNonEmptyFreeAttrCalcPriceParam) {
            $label = '';
            $suffix = '';
            $jshopFacpResult = JSFactory::getModel('ProductPriceTypeFront')->getCalcPriceType($product->product_price_type, $product->free_attribute_active);
            $systemMessages = '';

            if (isset($paramsOfFreeAttrCalcPrice->formula) && !empty($paramsOfFreeAttrCalcPrice->formula)) {
                $label = $paramsOfFreeAttrCalcPrice->label;
                $suffix = $paramsOfFreeAttrCalcPrice->suffix;
            }


            if (!empty($product->error_message)) {
                $systemMessages = generateMarkupList($product->error_message);
            } 

            $old_free_attribute_active = json_encode($product->old_free_attribute_active);
            if (empty($old_free_attribute_active)) {
                $old_free_attribute_active = '';
            }
            $ajaxResponse[] = '"jshop_facp_label":"' . $label . '"';
            $ajaxResponse[] = '"jshop_facp_result":"' . $jshopFacpResult . '"';
            $ajaxResponse[] = '"jshop_facp_suffix":"' . $suffix . '"';
            $ajaxResponse[] = '"jshop_facp_system_message":"' . $systemMessages . '"';
            $ajaxResponse[] = '"jshop_facp_old_free_attribute_active":' . $old_free_attribute_active;
            $ajaxResponse[] = '"is_use_extend_attr_data":' . $jshopConfig->use_extend_attribute_data;
        }

        $productusergroupPermissions = $product->getUsergroupPermissions();
        $wishlistBtnMarkup = '';
		
		if($this->input->getVar('fromPage') == 'product_list'){
			$show_wishlist_button = $jshopConfig->show_wishlist_button ;
		}else{
			$show_wishlist_button = 1;
		}
        if ($show_wishlist_button && $jshopConfig->enable_wishlist && !empty($productusergroupPermissions->is_usergroup_show_buy) && !$jshopConfig->user_as_catalog) {
            if ($ajaxPageFrom == 'product_page') {


                $pathesToTemplates = templateOverrideBlockAjax('blocks','wishlist_btn.php', 1);
                $templateName = 'wishlist_btn';
                $dataToInsert = [
                    'product' => $product,
                    'enable_wishlist' => $jshopConfig->enable_wishlist,
                    'productUsergroupPermissions' => $productusergroupPermissions,
                    'jshopConfig' => $jshopConfig,
					'show_wishlist_button' => $show_wishlist_button
                ];
                $templateF = 1;

                $wishlistBtnMarkup = renderTemplate([
                    templateOverrideBlock('blocks','wishlist_btn.php', 1)
                ], 'wishlist_btn', [
                    'product' => $product,
                    'enable_wishlist' => $jshopConfig->enable_wishlist,
                    'productUsergroupPermissions' => $productusergroupPermissions,
                    'jshopConfig' => $jshopConfig
                ]) ?: '';
            } elseif ($ajaxPageFrom == 'product_list') {
                $wishlistBtnMarkup = renderTemplate([
                    templateOverrideBlock('blocks','products_wishlist_btn.php', 1)
                ], 'products_wishlist_btn', [
                    'product' => $product,
                    'sefLinkToWishlistAdd' => SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1),
                    'jshopConfig' => $jshopConfig
                ]) ?: '';
            }
        }
        $ajaxResponse[] = '"wishlistBtnMarkup":"' . json_value_encode($wishlistBtnMarkup, 1) . '"';
		
		$dispatcher->triggerEvent('onBeforeDisplayAjaxAttrib_BeforeGeneratePricesBlock', [&$ajaxResponse, &$product]);
		$product->product_basic_price_show = 0;
        if ($jshopConfig->admin_show_product_basic_price && $jshopConfig->product_list_show_price_default) {
			$product->product_basic_price_show = 1;
		}
        $productPriceMarkup = '';
        if (!empty($productusergroupPermissions->is_usergroup_show_price)) {
            if ($ajaxPageFrom == 'product_page') {
				if($jshopConfig->template == 'vernissage' || $product->product_template == 'vernissage'|| $product->product_template == 'top' || $product->product_template == 'stone'){
					$price_file = 'vernissage_prices';
				}else{
					$price_file = 'prices';
				}
                $productPriceMarkup = renderTemplate([
                    templateOverrideBlock('blocks', $price_file.'.php', 1)
                ], $price_file, [
                    'product' => $product,
                    'config' => $jshopConfig,
                    'default_count_product' => $productQty,
                    'shippinginfo' => SEFLink($jshopConfig->shippinginfourl, 1),
                    'jshopConfig' => $jshopConfig
                ]) ?: '';
            } elseif ($ajaxPageFrom == 'product_list') {
                $productPriceMarkup = renderTemplate([
                    templateOverrideBlock('blocks', 'prices_product.php', 1)
                ], 'prices_product', [
                    'product' => $product,
                    'config' => $jshopConfig,
                    'show_base_price' => $jshopConfig->show_base_price_for_product_list,
                    'shippinginfo' => SEFLink($jshopConfig->shippinginfourl, 1),
                    'totalAjaxPrice' => $calculatedPrice,
                    'jshopConfig' => $jshopConfig
                ]) ?: '';
            }
        }
        $ajaxResponse[] = '"productPriceMarkup":"' . json_value_encode($productPriceMarkup, 1) . '"';

        if ($ajaxPageFrom == 'product_page' || ($ajaxPageFrom == 'product_list' && $jshopConfig->product_list_show_short_description)) {
            $productShortDescription = ($jshopConfig->product_show_short_description && $product->getTexts()->short_description) ?  contentReturn(JHtml::_('content.prepare', contentReplace($product->getTexts()->short_description))) : '';
            $prodFullDescription = $product->getTexts()->description ? contentReturn(JHtml::_('content.prepare', contentReplace($product->getTexts()->description))) : '';

            $ajaxResponse[] = '"productDescription":"' . json_value_encode(renderText($prodFullDescription), 1) . '"';
            $ajaxResponse[] = '"productShortDescription":"' . json_value_encode($productShortDescription, 1) . '"';
        }
		
		ExcludeAttributeForAttribute::getInstance()->onBeforeDisplayAjaxAttrib($ajaxResponse, $product);
		
		$buttons=JSFactory::getModel('buttons');

        if (!$jshopConfig->user_as_catalog) {
		    $show_buttons = $buttons->excludeButtonsForAttribute($attributeValues, $attributesDatas['attributeActive']);				
		    $ajaxResponse[] = '"show_buttons_cart":"' . json_value_encode($buttons->getButtonCartExlude()) . '"';
		    $ajaxResponse[] = '"show_buttons_upload":"' . json_value_encode($buttons->getButtonUploadExlude()) . '"';
        }

        $ajaxResponse[] = '"show_buttons_editor":"' . json_value_encode($buttons->getButtonEditorExlude()) . '"';
        $ajaxResponse[] = '"updated_product_weight_formated":"' . formatweight($product->getWeight()) . '"';
        $ajaxResponse[] = '"product_id":"' . $product->product_id . '"';
        $ajaxResponse[] = '"main_image_url":"' . $product->getUrlOfMainImage() . '"';
        $ajaxResponse[] = '"product_quantity":' . $productQty;
        
		$ajaxResponse[] = '"show_wishlist_button":"'. $show_wishlist_button . '"';

        $allAttrValues = [];
        if (!empty($attributes)) {
            $attrValueTable = JSFactory::getTable('AttributValue', 'jshop');
            $allAttrValues = $attrValueTable->getAllAttributeValues();
        }

         $reviewTable = JSFactory::getTable('review', 'jshop');
         $allow_review = $reviewTable->getAllowReview(null,$product->product_id);

        $select_review = '';

        if (!empty($allow_review)) {
            $reviewModel = JSFactory::getModel('reviewFront', 'jshop');
            $select_review = $reviewModel->generateRatingMarkup();
            $text_review = '';
        } else {
            $text_review = $reviewTable->getText();
        }
        $user = JFactory::getUser();
		$hide_buy = 0;
		
        $product->_display_price = getDisplayPriceForProduct($product->product_id, $product->getPriceCalculate());
         if (!$product->_display_price || $jshopConfig->user_as_catalog || ($jshopConfig->hide_buy_not_avaible_stock && $product->product_quantity <= 0)) {
             $hide_buy = 1;
         }
         $isMultiUploadProduct = (isset($productWithUpload->max_allow_uploads) && $productWithUpload->max_allow_uploads >= 2) || (isset($productWithUpload->is_unlimited_uploads) && $productWithUpload->is_unlimited_uploads);
         if(isset($productWithUpload->is_unlimited_uploads) && $productWithUpload->is_unlimited_uploads){
			 $maxFilesUploads = INF;
		 }else{
			 $maxFilesUploads = $productWithUpload->max_allow_uploads ?? 1;
		 }
		 $productMaxQty = $product->unlimited ? INF : (int)$product->getFullQty();
         $_production_calendar = JModelLegacy::getInstance("production_calendar", 'JshoppingModel')->getParams();
		
       // $buttons=JSFactory::getModel('buttons');
        // $show_buttons=$buttons->excludeButtonsForAttribute($attributeValues, $attributesDatas['attributeActive']);

        /*if (!empty($show_buttons['upload'])) {
            $ajaxResponse[] = '"upload_data": ""';
        }*/

        $usergroup_show_action = getUsergroupShowAction($product->product_id);

        $view = $this->getView('product', getDocumentType(), '', [
            'template_path' => viewOverride('product', 'product_' . $product->product_template.'.php')
        ]);
        $view->set('config', $jshopConfig);
        $view->set('jshopConfig', $jshopConfig);
        $view->set('component', 'Product_default');
        $view->set('image_path', $jshopConfig->live_path . '/images');
        $view->set('noimage', $jshopConfig->noimage);
        $view->set('image_product_path', $jshopConfig->image_product_live_path);
        $view->set('video_product_path', $jshopConfig->video_product_live_path);
        $view->set('video_image_preview_path', $jshopConfig->video_product_live_path);
        $view->set('product', $product);
        $view->set('images', $product->getMedia());
        $view->set('demofiles', $demofiles ?? []);
        $view->set('attributes', $attributes);
        $view->set('all_attr_values', $allAttrValues);
        $view->set('related_prod', $product->product_related);
        $view->set('path_to_image', $jshopConfig->live_path . 'images/');
        $view->set('live_path', JURI::root());
        $view->set('enable_wishlist', $jshopConfig->enable_wishlist);
        $view->set('action', SEFLink('index.php?option=com_jshopping&controller=cart&task=add', 1));
        $view->set('urlupdateprice', '/index.php?option=com_jshopping&controller=product&task=ajax_attrib_select_and_price&product_id=' . $product->product_id . '&ajax=1');
        $view->set('allow_review', $allow_review);
        $view->set('allow_reviews_uploads', $jshopConfig->allow_reviews_uploads);
        $view->set('review_max_uploads', $jshopConfig->review_max_uploads);
        $view->set('select_review', $select_review);
        $view->set('text_review', $text_review);
        $view->set('stars_count', $jshopConfig->max_mark);
        $view->set('parts_count', $jshopConfig->rating_starparts);
        $view->set('user', $user);
        $view->set('shippinginfo', SEFLink($jshopConfig->shippinginfourl, 1));
        $view->set('hide_buy', $hide_buy);
        $view->set('available', $available);
        $view->set('default_count_product', $default_count_product ?? 1);
        $view->set('display_price', getDisplayPriceForListProduct());
        $view->set('folder_list_products', 'list_products');
       // $view->set('back_value', $back_value);
        $view->set('displaybuttons', $displaybuttons);
        $view->set('upload_common_settings', $uploadCommonSettings);
        $view->set('sefLinkToWishlistAdd', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1));
        $view->set('link_to_ajax_upload_files', '/index.php?option=com_jshopping&controller=upload&task=ajaxUploadFile&product_id=' . $product->product_id);
        $view->set('cart_link', SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1, 1));
        $view->set('href_checkout', getCheckoutUrl(1, true));
        $view->set('href_wishlist', SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));

        $uploadCommonSettings = JSFactory::getModel('upload')->getParams();
        $isSupportUpload = isset($uploadCommonSettings->is_allow_product_page) && $uploadCommonSettings->is_allow_product_page && isset($productWithUpload->is_allow_uploads) && $productWithUpload->is_allow_uploads && ((isset($productWithUpload->is_unlimited_uploads) && $productWithUpload->is_unlimited_uploads )|| (isset($productWithUpload->max_allow_uploads) && $productWithUpload->max_allow_uploads >= 1));

        $view->set('isSupportUpload', $isSupportUpload);
        $view->set('isMultiUpload', $isMultiUploadProduct);
        $view->set('productMaxQty', $productMaxQty);
        $view->set('maxFilesUploads', $maxFilesUploads);
        $view->set('production_time', $_production_calendar->show_in_product);
        $view->set('smartLink', getSmartLinkForProductPage($product));
        $view->set('show_buttons', $show_buttons);
        $view->set('add_prices_with_user_discount', $addPrices->product_add_prices ?? []);
        $view->set('usergroup_show_action', $usergroup_show_action);
        $view->set('page_type', $ajaxPageFrom);
        $view->set('totalAjaxPrice', $calculatedPrice);
        $view->set('calculatedPrice', $calculatedPrice);
        $view->set('href_add', SEFLink('index.php?option=com_jshopping&controller=cart&task=add&ajax=1', 1, 1));
        $view->set('href_view', SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=display', 1, 1));
        $view->set('href_close', SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=close', 1, 1));
        $view->set('href_address_data', SEFLink('index.php?option=com_jshopping&controller=one_click_checkout&task=addressData&temp=0', 1, 1));
        $view->set('href_refresh', SEFLink('index.php?option=com_jshopping&controller=cart&task=refresh&ajax=1', 1, 1));
        $view->set('href_discount', SEFLink('index.php?option=com_jshopping&controller=cart&task=discountsave&ajax=1', 1, 1));
        $view->set('href_product', SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id=', 1, 1));
        $view->set('href_error_attr', SEFLink('index.php?option=com_jshopping&controller=cart_popup&task=error&category_id=_cid_&product_id=_pid_&message=_msg_', 1, 1));
        $view->set('confirm_remove', JText::_('COM_SMARTSHOP_CONFIRM_REMOVE'));
        $view->set('base_url', JURI::base());

		if(isset($view->_tmp_product_html_before_buttons)){
			$view->_tmp_product_html_before_buttons .= '<div id="jshop_facp_block" style="display: none"><span id="jshop_facp_label"></span> <span id="jshop_facp_result"></span><span id="jshop_facp_suffix"></span></div>';
		}else{
			$view->_tmp_product_html_before_buttons = '<div id="jshop_facp_block" style="display: none"><span id="jshop_facp_label"></span> <span id="jshop_facp_result"></span><span id="jshop_facp_suffix"></span></div>';
		}
        $modelFreeAtrrCalcPrice = JSFactory::getModel('freeattrcalcprice');
        $modelFreeAtrrCalcPrice->replaceInputValuesToDefault($view->product->freeattributes, $view->product->product_id);
        $isIssetAtLeastOneNonEmptyParam = $modelFreeAtrrCalcPrice->isIssetAtLeastOneNonEmptyParam();

         ExcludeAttributeForAttribute::getInstance()->onBeforeDisplayProductView($view);
        ExcludeButtonsForAttribute::getInstance()->onBeforeDisplayProductView($view);
        $dispatcher->triggerEvent('onBeforeDisplayProductView', [&$view]);
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplayProductView($view);
        ProductsMinMaxQuantityMambot::getInstance()->onBeforeDisplayProductView($view);
        $view->set('tax_info_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=productTaxInfo', 1));
		 $view->set('productQty', $productQty);
        $view->set('calculatedProductPrice', $product->getPriceCalculate($productQty));
        $view->set('productUsergroupPermissions', $product->getUsergroupPermissions());
        $view->set('isShowCartSection', $product->isShowCartSection());
        $view->set('sprintQtyInStock', sprintQtyInStock($qty_in_stock ?? 0));
        $view->set('printselectquantity_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantity', 1));
        $view->set('manufacturer_link', SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view&manufacturer_id=' . $product->product_manufacturer_id, 2));
      
        $view->set('weight', formatweight($product->getWeight()));
        $view->set('reviewsave', SEFLink('index.php?option=com_jshopping&controller=product&task=reviewsave'));
        $view->set('file_exists_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=file_exists_link'));
        $view->set('request_uri', jsFilterUrl($_SERVER['REQUEST_URI']));
        $view->set('printselectquantitycart_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=printSelectQuantityCart', 1));
        $view->set('toCheckout', SEFLink('index.php?option=com_jshopping&controller=product&task=toCheckout&productId=' . $product->product_id, 1));

		if ($jshopConfig->use_extend_attribute_data && !$jshopConfig->user_as_catalog) {
            $demofiles = $product->getDemoFiles();
			$view->set('demofiles', $product->getDemoFiles());
		}
        $view->set('show_wishlist_button', $show_wishlist_button);
        $view->set('sprintjstempfiles', sprintJsTemplateForNativeUploadedFiles($isMultiUploadProduct));
        $view->set('sprintjstemplatefornativeuploadedfiles_link', SEFLink('index.php?option=com_jshopping&controller=functions&task=sprintJsTemplateForNativeUploadedFiles', 1));

        $dispatcher->triggerEvent('onBeforeDisplayAjaxAttrib', [&$ajaxResponse, &$product]);
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplayAjaxAttrib($ajaxResponse, $product);
        if($if_react){
            print_r(json_encode(prepareView($view)));
        }else {
            echo '{' . implode(',', $ajaxResponse) . '}';
        }

        die();
    }

    protected function isChangedAttrIsDependent(): bool
    {
        $allAttrs = JSFactory::getAllAttributes(2);
        $change_attr = $this->input->get('change_attr');
        $isChangedAttrs = $this->input->get('change_attr', false, 'bool');

        return $isChangedAttrs && !empty($allAttrs['dependent'][$change_attr]);
    }

    protected function updateAjaxExtDataFreeAttrs(&$product, &$formulaCalcParams, &$ajaxResponse, $pathToFolderWithProdTmpls, &$freeattr, $change_attr)
    {
        $product->getListFreeAttributes();
        $free_attr = '';

        if (!empty($product->freeattributes)) {
            $freeattrNew = [];

            $view = new stdClass();
            $view->product = $this->product = $product;

            ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplayProductView($view);

            foreach ($product->freeattributes as $k => $prodFreeAttr) {
                $minValue = $prodFreeAttr->min_value ?: 0;
                $maxValue = $prodFreeAttr->max_value;
                $defaultValue = $prodFreeAttr->defaultValue;

                if ((!$change_attr || ($change_attr && (!$this->isChangedAttrIsDependent() || ($this->isChangedAttrIsDependent() && !$product->attribute_active_data->ext_data->is_use_additional_free_attrs == 1)))) && isset($freeattr[$prodFreeAttr->id]) && !empty($freeattr[$prodFreeAttr->id])) {
                    if ($freeattr[$prodFreeAttr->id] >= $minValue && $freeattr[$prodFreeAttr->id] <= ($maxValue ?: PHP_INT_MAX)) {
                        $defaultValue = $freeattr[$prodFreeAttr->id];
                    }
                }

                $defaultValue = $defaultValue ?: $minValue ?: $maxValue;
                $freeattrNew[$prodFreeAttr->id] = $product->freeattributes[$k]->value = $product->freeattributes[$k]->defaultValue = $product->freeattributes[$k]->default_value = $defaultValue;
            }
            
            $freeattr = $freeattrNew;
            $product->fillInputFieldsPropertyForFreeAttrs();

            $this->product = $product;
            $ajaxPageFrom = $this->input->getVar('fromPage', 'product_page');
            if($product->product_template == 'stone' && $ajaxPageFrom == 'product_page'){
                $file = 'stone_free_attribute.php';
            }else{
                $file = 'free_attribute.php';
            }
            ob_start();	
            include  templateOverrideBlock('blocks', $file);
            $free_attr = ob_get_contents();
            ob_end_clean();
        }
        
        $ajaxResponse[] = '"free_attrs":" ' . json_value_encode($free_attr, 1) . ' "';
    }
    
    public  function showmedia()
    {
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $media_id = JFactory::getApplication()->input->getInt('media_id');
        $file = JSFactory::getTable('productfiles', 'jshop');
        $file->load($media_id);

        $scripts_load = '<script src="' . JURI::root() . 'media/jui/js/jquery.min.js"></script> <script src="' . JURI::root() . 'components/com_jshopping/js/src/jquery/jquery.media.js"></script>';
        
        $this->config = $jshopConfig;
        $this->filename = $file->demo;
		$this->description = $file->demo_descr;
        $this->scripts_load = $scripts_load;
        
        $dispatcher->triggerEvent('onBeforeDisplayProductShowMediaView', [&$currentObj]);
		ob_start();
		include  templateOverrideBlock('blocks', 'playmedia.php');
		$playmedia = ob_get_contents();
		ob_end_clean();
		print_r($playmedia);
        die();
    }
	
	public function toCheckout()
    {
        include_once JPATH_ROOT . '/components/com_jshopping/controllers/cart.php';
        $cartController = new JshoppingControllerCart();
        $cartController->add();

        return $this->setRedirect(getCheckoutUrl(1, true));
    }
	
	function getNativeUploadPrice(): ?jshopNativeUploadsPrices
	{
		return $this->nativeUploadPrice;
	}

	function getNativeAmountOfUploads()
	{
		return $this->amountOfUploads;
	}
}
