<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/free_attrs_default_values/admin_free_attrs_default_values_mambot.php';

class JshoppingControllerProducts extends JControllerLegacy
{
	
	protected $canDo;
    
    public function __construct( $config = []) 
    {
        parent::__construct($config);
        $this->registerTask('add', 'edit' );
        $this->registerTask('apply', 'save');
        checkAccessController("products");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("products",$this->canDo);
    }
    
    public function display($cachable = false, $urlparams = false)
    {    
        $mainframe = JFactory::getApplication();            
        $jshopConfig = JSFactory::getConfig();
        $_products = JSFactory::getModel("products");		
		$_manufacturers = JSFactory::getModel('manufacturers');
		$_labels = JSFactory::getModel('productLabels');		
        
        $context = "jshoping.list.admin.product";
        $limit = $mainframe->getUserStateFromRequest($context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int' );
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', $jshopConfig->adm_prod_list_default_sorting, 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', $jshopConfig->adm_prod_list_default_sorting_dir, 'cmd');
        		
        if (isset($_GET['category_id']) && $_GET['category_id']==="0"){            
            $mainframe->setUserState($context.'category_id', 0);
            $mainframe->setUserState($context.'manufacturer_id', 0);            
            $mainframe->setUserState($context.'label_id', 0);
            $mainframe->setUserState($context.'publish', 0);
            $mainframe->setUserState($context.'text_search', '');
        }
		
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayListProductsAfterSetUserState', array(&$mainframe));

        $category_id = $mainframe->getUserStateFromRequest($context.'category_id', 'category_id', 0, 'int');
        $manufacturer_id = $mainframe->getUserStateFromRequest($context.'manufacturer_id', 'manufacturer_id', 0, 'int');        
        $label_id = $mainframe->getUserStateFromRequest($context.'label_id', 'label_id', 0, 'int');
        $publish = $mainframe->getUserStateFromRequest($context.'publish', 'publish', 0, 'int');
        $text_search = $mainframe->getUserStateFromRequest($context.'text_search', 'text_search', '');
        if ($category_id && $filter_order=='category') $filter_order = 'product_id';
        
        $filter = [
            'category_id' => $category_id, 
            'manufacturer_id' => $manufacturer_id, 
            'label_id' => $label_id, 
            'publish' => $publish, 
            'text_search' => $text_search,
            'except_categories_id' => []
        ];
        $filter['except_categories_id'] = empty($category_id) ? [1] : [];
        $dispatcher->triggerEvent('onBeforeDisplayListProductsAfterFilter', array(&$filter));
		                
        $total = $_products->getCountAllProducts($filter);
        
        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
         
		$rows = $_products->getAllProductsRows($filter, $pagination->limitstart, $pagination->limit, $filter_order, $filter_order_Dir); 
		 
        $parentTop = new stdClass();
        $parentTop->category_id = 0;
        $parentTop->name = "- ".JText::_('COM_SMARTSHOP_CATEGORY')." -";
        $categories_select = buildTreeCategory(0,1,0);
        array_unshift($categories_select, $parentTop);    
		//Filters
        $lists['treecategories'] = JHTML::_('select.genericlist', $categories_select, 'category_id', 'class="chosen-select form-select" onchange="document.adminForm.submit();"', 'category_id', 'name', $category_id );        
        $lists['manufacturers'] = $_manufacturers->getManufacturerList();       
        $lists['labels']=$_labels->getLabelsList($label_id);
		
		$_publish = JSFactory::getModel('publish');
		$lists['publish']=$_publish->getProductsListFilterPublish();
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayListProducts', array(&$rows));
        
        $view=$this->getView("product_list", 'html');
        $view->set('rows', $rows);
        $view->set('lists', $lists);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
        $view->set('category_id', $category_id);
        $view->set('manufacturer_id', $manufacturer_id);
        $view->set('pagination', $pagination);
        $view->set('text_search', $text_search);
        $view->set('config', $jshopConfig);        
        $dispatcher->triggerEvent('onBeforeDisplayListProductsView', array(&$view));
        $view->display();        
    }
    
    public function edit()
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . 'administrator/components/com_jshopping/js/src/scripts/SortableJS/Sortable.js');
        $jshopConfig = JSFactory::getConfig();        
        $lang = JSFactory::getLang();
        
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadEditProduct', array());
		
		//MODELS
		$_lang = \JSFactory::getModel("languages");
        $_productattrs = JSFactory::getModel("productattrs");
		$_products = JSFactory::getModel("products");
		$_usergroups = JSFactory::getModel("usergroups");		
		$_manufacturer =JSFactory::getModel('manufacturers');
		$_attribut = JSFactory::getModel('attribut');
		$_dependent_attributes = JSFactory::getModel('dependent_attributes');
		$_independent_attributes = JSFactory::getModel('independent_attributes');
		$_attribut_value =JSFactory::getModel('attributValue');
		$_pricemodification =JSFactory::getModel('pricemodification');
		$_deliveryTimes = JSFactory::getModel("deliveryTimes");
		$_units = JSFactory::getModel("units");
		$_labels = JSFactory::getModel("productLabels");
		$_access = JSFactory::getModel("access");
		$_currency = JSFactory::getModel("currencies");		
		$_freeattribut = JSFactory::getModel("freeattribut");
		$_extrafield = JSFactory::getModel("extrafield");
		$_categories = JSFactory::getModel("categories");
		$_taxes = JSFactory::getModel("taxes");
		$_productimage = JSFactory::getModel("productimage");
		$_productvideo = JSFactory::getModel("productvideo");
		$_productfile = JSFactory::getModel("productfile");
		$_prices = JSFactory::getModel("prices");
		$_shippings = JModelLegacy::getInstance('shippings', 'JshoppingModel');
		//TABLES
		$_table_product = JSFactory::getTable('product', 'jshop');
		$_table_productprice = JSFactory::getTable('productPrice', 'jshop');
		//JRequest
        $category_id = JFactory::getApplication()->input->getInt('category_id');
		$product_id = (int)JFactory::getApplication()->input->getInt('product_id');
        $product_attr_id = JFactory::getApplication()->input->getInt('product_attr_id'); 
				
		$tmpl_extra_fields = null;        
                       
        //parent product
		$product_id = (int)$_productattrs->getProductAttr($product_id,$product_attr_id, true);
        
		$dispatcher->triggerEvent('onLoadEditProductAfterGetParentProdctID', array(&$product_id));	
        
        $_table_product->load($product_id,true,false);
		$_table_product->product_add_prices[0] = $_table_productprice->getAddPrices((int)$product_id,0);        		
		
		//USERGROUPS_PRICES												
		$lists['usergroups'] = $_usergroups->getUsergroupsList();	
        $lists['attr_depend_usergroups'] = $_usergroups->getUsergroupsList('attr_depend_add_usergroups_prices_usergroup[100500]');			
        $_table_product->product_add_prices=$_usergroups->getUsergroupsPrices($product_id,$_table_product->product_add_prices);
		
		$productUsergroupPermissions = $_table_product->getUsergroupPermissions();
		$lists['usergroup_show_product'] = $_usergroups->getUsergroupsShowProduct($product_id, $productUsergroupPermissions->usergroup_show_product);
		$lists['usergroup_show_price'] = $_usergroups->getUsergroupsShowPrice($product_id, $productUsergroupPermissions->usergroup_show_price);
		$lists['usergroup_show_buy'] = $_usergroups->getUsergroupsShowActions($product_id, $productUsergroupPermissions->usergroup_show_buy);
		$lists['usergroup_add_price'] = $_usergroups->getUsergroupsAddPrice($product_id, $productUsergroupPermissions->usergroup_show_price);
        $name = $lang->get("name");
        $_table_product->name = $_table_product->$name;
        
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
 
        $nofilter = array();
        JFilterOutput::objectHTMLSafe( $_table_product, ENT_QUOTES, $nofilter);

        $edit = intval($product_id);     		
		
		$lists['media'] = $_table_product->getMedia();
		//$lists['videos']=$_productvideo->getProductVideosList($_table_product);
		$lists['files']=$_productfile->getProductFilesList($_table_product);
		
		$related_products=$_products->getRelatedProducts($product_id);
        $_products->setDefaultFields($_table_product);
        
        $modelOfNativeUploadsPricesAdmin = JSFactory::getModel('NativeUploadsPricesAdmin');
        $nativeUploadPrices = $modelOfNativeUploadsPricesAdmin->getByProductId($product_id) ?: [];

        //Attributes        
        $lists['attribs'] = $_table_product->getAttributesAdmin(true);
        $lists['ind_attribs'] = $_table_product->getAttributes2();
        $lists['attribs_values'] = $_attribut_value->getAllAttributeValues(2);    
		$lists['product_packing_type'] = $_products->getProductPackingTypeSelect($_table_product->product_packing_type); 
        $lists['attr_product_packing_type'] = $_products->getProductPackingTypeSelect($_table_product->product_packing_type, '', 'attr_product_packing_type');				
		$_dependent_attributes->getDependentAttributesList($_table_product,$lists);
		$_independent_attributes->getIndependentAttributesList($_table_product,$lists);
        //Additional parrams
		$_deliveryTimes->getDeliveryTimesList($_table_product->delivery_times_id,$lists);
        $_deliveryTimes->getDeliveryTimesList($_table_product->delivery_times_id,$lists, 'attr_deliverytimes', '', 'attr_delivery_times_id');
		$_units->getUnitsByProductIdLists($product_id,$lists);		
		$_labels->getlabelsLists($product_id,$lists);	
        $_labels->getlabelsLists($product_id,$lists, 'attr_labels', '', 'attr_labels');	
		$_access->getAccessGroupsLists($product_id,$lists);
		$_currency->getCurrencyLists($product_id,$lists);		
		$_taxes->getTaxesSelect($_table_product,$lists);
        $_taxes->getTaxesSelect($_table_product, $lists, 'attr_tax', '', 'attr_product_tax_id');

        //product extra field
        if ($jshopConfig->admin_show_product_extra_field) {
			$categories_selected=$_categories->getProductCategoriesSelected($_table_product);
            $categorys_id=$_categories->getSelectedArray($categories_selected);
            $tmpl_extra_fields = $this->_getHtmlProductExtraFields($categorys_id, $_table_product);
        }        
		$listfreeattributes=$_freeattribut->getFreeatributesArray($_table_product);
		$_manufacturer->getManufacturersSelect($_table_product,$lists);
		$_manufacturer->getManufacturersSelect($_table_product,$lists, 'attr_manufacturers', '', 'attr_product_manufacturer_id');
		
		$current_product_tax_value=$_taxes->getTaxById($_table_product->product_tax_id);		
        if ($product_id){
            $_table_product->product_price = formatEPrice($_table_product->product_price);            
            $_table_product->product_price2 = $_prices->calculePricesWithTax($_table_product->product_price,$current_product_tax_value);
        }else{
            $_table_product->product_price2 = '';
        }        
		
		$_categories->getProductCategoriesSelect($_table_product,$product_id,$lists);
		        
        $lists['templates'] = getTemplates('product', $_table_product->product_template);
        
        $_product_option = JSFactory::getTable('productOption', 'jshop');
        $product_options = $_product_option->getProductOptions($product_id);
        $_table_product->product_options = $product_options;
		
		$shippings = $_shippings->getAllShippingPricesByCountries(1, 0);		
		$products_shipping=$_shippings->shippingsByProduct($product_id,0, 0);
        
        JText::script('COM_SMARTSHOP_HIDDEN_ATTR_ADD_ERROR');
		
        $dispatcher->triggerEvent('onBeforeDisplayEditProduct', array(&$_table_product, &$related_products, &$lists, &$listfreeattributes, &$current_product_tax_value));
        $_upload = JModelLegacy::getInstance("upload", 'JshoppingModel');
        $uploadparams = $_upload->getParams();

        $view=$this->getView("product_edit", 'html');
        $view->setLayout("default");
		$view->set("canDo", $this->canDo);
		//Product shipping
		$view->set('rows', $shippings);
		$view->set('products_shipping', $products_shipping);
		/////////////////
        $view->set('product', $_table_product);
		$view->set('usergroups_prices', $_usergroups->getAllUsergroupsPrices($product_id));		
        $view->set('lists', $lists);
        $view->set('related_products', $related_products);
        $view->set('edit', $edit);
        $view->set('product_with_attribute', $product_with_attribute ?? 0);
        $view->set('tax_value', $current_product_tax_value);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('tmpl_extra_fields', $tmpl_extra_fields);
        $view->set('withouttax', $_taxes->withouttaxCheck());        
        $view->set('listfreeattributes', $listfreeattributes);
        $view->set('product_attr_id', $product_attr_id);
        $view->set('isPageWithAdditionalValues', ($product_attr_id) ? true : false);
        $view->set('jshopConfig', $jshopConfig);

        foreach($languages as $lang){
            $view->set('plugin_template_description_'.$lang->language, '');
        }

        $view->set('plugin_template_info', '');
        $view->set('plugin_template_attribute', '');
        $view->set('plugin_template_freeattribute', '');
        $view->set('plugin_template_images', '');
        $view->set('plugin_template_related', '');
        $view->set('plugin_template_files', '');
        $view->set('plugin_template_extrafields', '');
        $view->set('upload_product', $uploadparams->is_allow_product_page);
        $view->set('nativeUploadPrices', $nativeUploadPrices);
        
        $plugin_template_info = '';
        $view->set('productPriceTypeSelect', $_prices->getProductPriceTypeSelect($_table_product->product_price_type));
        $view->set('productAttrPriceTypeSelect', $_prices->getProductAttrPriceTypeSelect($_table_product->product_price_type, 'product_price_type'));
        $view->set('attrProductAttrPriceTypeSelect', $_prices->getProductAttrPriceTypeSelect($_table_product->product_price_type, '', 'inputbox', 'attr_product_price_type'));
        $view->set('product_price_for_qty_type', $_prices->getProductAttrPriceTypeSelect($_table_product->product_price_for_qty_type, 'product_price_for_qty_type'));
        
		$_independent_attributes->getIndependentAttributesView($lists,$view);  
        $this->addJsToEditProductPage();
        
        $dispatcher->triggerEvent('onBeforeDisplayEditProductView', array(&$view) );
        $view->set('arrWithHtmlRowsOfDefaultValues', AdminFreeAttrsDefaultValuesMambot::getInstance()->getPreparedInputsAndCheckboxesTemplateForFreeAttrsRows($view->listfreeattributes, $_table_product->product_id));
        $view->display();
    }
    
    public function save()
    {

        $jshopConfig = JSFactory::getConfig();
        require_once($jshopConfig->path.'lib/image.lib.php');
        require_once($jshopConfig->path.'lib/uploadfile.class.php');
		$lang = JSFactory::getLang();
        
        $dispatcher = \JFactory::getApplication();
        
        $post = JFactory::getApplication()->input->post->getArray();
		
		if(empty($post) || count($post) < 8){
			$data = json_decode(file_get_contents('php://input'), true);
			$post = $data['data'];
			$ajax = $post['ajax'];
		}
		$post['is_activated_price_per_consignment_upload_disable_quantity'] = (INT)$post['is_activated_price_per_consignment_upload_disable_quantity'];
 
 
        $product_attr_id = (int)$post['product_attr_id'];
        $_products = JSFactory::getModel("products"); 
        $_languages = JSFactory::getModel("languages");
		$_prices = JSFactory::getModel("prices");
		$_extrafield = JSFactory::getModel("extrafield");
		$_attributprices = JSFactory::getModel("attributprices");
		//$_productimage = JSFactory::getModel("productimage");
		$product = JSFactory::getTable('product', 'jshop');
        
        $dispatcher->triggerEvent('onBeforeDisplaySaveProductBeforeSetPostValues', array(&$post));
		
		$_products->productSave_setPostValues($post);		
		$_extrafield->productSave_setPostValues($post);		
        $_attributprices->productSave_setPostValues($post);
        $_languages->productSave_setPostValues($post);
        
        $dispatcher->triggerEvent('onBeforeDisplaySaveProduct', array(&$post, &$product) );
        AdminFreeAttrsDefaultValuesMambot::getInstance()->onBeforeDisplaySaveProduct($post, $product);
		$post['usergroup_show_product'] = !empty($post['usergroup_show_product']) ? implode(' , ', $post['usergroup_show_product']) : '';
        $post['usergroup_show_price'] = !empty($post['usergroup_show_price']) ? implode(' , ', $post['usergroup_show_price']) : '';
        $post['usergroup_show_buy'] = !empty($post['usergroup_show_buy']) ? implode(' , ', $post['usergroup_show_buy']) : '';
		
		
       
        if (!$product->bind($post)) {
			if($ajax){
				$ajax_return = [
                    'error' => 1,
                    'msg' => JText::_('COM_SMARTSHOP_ERROR_BIND'),
                    'redirect' => "index.php?option=com_jshopping&controller=products"
                ];				
				print json_encode($ajax_return);die;
			}else{
				\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
				$this->setRedirect("index.php?option=com_jshopping&controller=products");
				return 0;
			}
        }
        
		$_products->productSave_checkSetPrice($product);        
		//$_productimage->productSave_setPostImage($product, $post);
        
        if (!$product->store()){
            if($ajax){
				$ajax_return = [
                    'product_id' => $product->product_id,
                    'error' => 1,
                    'msg' => JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')."<br>".$product->_error,
                    'redirect' => "index.php?option=com_jshopping&controller=products&task=edit&product_id=".$product->product_id
                ];
				print json_encode($ajax_return);die;
			}else{
				\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')."<br>".$product->_error,'error');
				$this->setRedirect("index.php?option=com_jshopping&controller=products&task=edit&product_id=".$product->product_id);
				return 0;
			}
        }
        
        $product_id = $product->product_id;
        
		//Product shipping
		$_shippings = JModelLegacy::getInstance('shippings', 'JshoppingModel');
		$_shippings->saveShippings($product->product_id,$post);
        
        $_prices->deleteOldPrices($product_id);		
        
		$dispatcher->triggerEvent('onAfterSaveProduct', array(&$product));
        AdminFreeAttrsDefaultValuesMambot::getInstance()->onAfterSaveProduct($product);


		$_products->setMedia($product_id, $_FILES['media'], $post);

		/*
        if ($jshopConfig->admin_show_product_video && $product->parent_id==0) {
            $_products->uploadVideo($product, $product_id, $post);
        }

        $_products->uploadImages($product, $product_id, $post);
		*/

        if ($jshopConfig->admin_show_product_demo_files || $jshopConfig->admin_show_product_sale_files){
            $_products->handleSetFiles($product_id, $post);
        }

        $_products->saveAttributes($product, $product_id, $post);
        
        if ($jshopConfig->admin_show_freeattributes){
            $_products->saveFreeAttributes($product_id, $post['freeattribut']);
        }
      
        if ($post['product_is_add_price']){
            $_products->saveAditionalPrice($product_id, $post['product_add_discount'], $post['quantity_start'], $post['quantity_finish'], $post['product_add_price'], $post['start_discount']);
        }

        if ($product->parent_id==0){
            $_products->setCategoryToProduct($product_id, $post['category_id']);
        }
		
        /* Usergroups prices */		
		$_prices->addUsergroupPrices($product_id,$post);		
		/* Usergroups prices END */	
		
        $_products->saveRelationProducts($product, $product_id, $post);
        $_products->saveProductOptions($product_id, (array)$post['options']);
        $_products->saveProductExtOptions($product_id);
        
        $product->load($product->product_id,true,false);
        $product_attrs=$product->getAttributes();
        
        if($product_attrs){ 
            foreach ($product_attrs as $attr){
                if($attr->ext_attribute_product_id){
                    $_product = JSFactory::getTable('product', 'jshop');
                    $_product->load($attr->ext_attribute_product_id);  
                    $_product->product_price = $attr->price; 
                    $_product->store();                    
                }
            }
        }

        $_attributvalue = JSFactory::getModel('AttributValue');
        $_attributvalue->deleteValAttrsForSortTable($product->product_id);
        $_attributvalue->writeAttrsToSortTable($product->product_id, $post['attrib_id']);

        $product->load($product->product_id,true,false);
        $product->one_time_cost = JSFactory::getTable('ProductAttribut2')->calcAttrsWithOneTimeCostPriceType($product->product_id, $post['attrib_ind_value_id'], getPriceCalcParamsTax($product->product_price, $product->product_tax_id));
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $productCalculatedData = JSFactory::getModel('ProductsFront')->calculateProductDataByProductId($product->product_id);
        $product->preview_total_price = $productCalculatedData['calculatedPrice'];
        $product->preview_calculated_weight = $productCalculatedData['product']->getWeight();
        $product->store();          
        
        $modelOfNativeUploadsPricesAdmin = JSFactory::getModel('NativeUploadsPricesAdmin');
        $modelOfNativeUploadsPricesAdmin->deleteByProductId($product->product_id);
        if (!empty($post['is_activated_price_per_consignment_upload']) && !empty($post['nativeProgressUploads']['prices']['updates'])) {
            $modelOfNativeUploadsPricesAdmin->savePricesData($post['nativeProgressUploads']['prices']['updates'], $product->product_id);
        }
        
        $dispatcher->triggerEvent('onAfterSaveProductEnd', array(&$product->product_id) ); 
                       
if ($this->getTask()=='apply' || $post['task'] == 'apply'){
            if ($product->parent_id!=0){
				if($ajax){
					$ajax_return = [
						'product_attr_id' => $product_attr_id,
						'product_id' => 0,
						'error' => 0,
						'msg' => JText::_('COM_SMARTSHOP_PRODUCT_SAVED'),
						'redirect' => "index.php?option=com_jshopping&controller=products&task=edit&product_attr_id=".$product_attr_id
					];
					print json_encode($ajax_return);die;					
				}
                $this->setRedirect("index.php?option=com_jshopping&controller=products&task=edit&product_attr_id=".$product_attr_id, JText::_('COM_SMARTSHOP_PRODUCT_SAVED'));  
            }else{
				if($ajax){
					$ajax_return = [
						'product_id' => $product->product_id,
						'error' => 0,
						'msg' => JText::_('COM_SMARTSHOP_PRODUCT_SAVED'),
						'redirect' => "index.php?option=com_jshopping&controller=products&task=edit&product_id=".$product->product_id
					];
					print json_encode($ajax_return);die;					
				}
                $this->setRedirect("index.php?option=com_jshopping&controller=products&task=edit&product_id=".$product->product_id, JText::_('COM_SMARTSHOP_PRODUCT_SAVED'));
            }
        }else{
            if ($product->parent_id!=0){
                print "<script type='text/javascript'>window.close();</script>";            
                die();
            }
			if($ajax){
				$ajax_return = [
					'product_id' => 0,
					'error' => 0,
					'msg' => JText::_('COM_SMARTSHOP_PRODUCT_SAVED'),
					'redirect' => "index.php?option=com_jshopping&controller=products"
				];
				print json_encode($ajax_return);die;					
			}
            $this->setRedirect("index.php?option=com_jshopping&controller=products", JText::_('COM_SMARTSHOP_PRODUCT_SAVED'));
        }
    } 
    
    public function displayDependAttrEditList()
    {
        $dependAttrsId = json_decode($this->input->get('ids', '', 'json'));
        
        if (empty($dependAttrsId['0']->productAttrId) && empty($dependAttrsId['0']->extAttrProductId)) {
            return raiseWarningRedirect(Text::_('COM_SMARTSHOP_RECORDS_NOT_FOUND'), 'administrator/index.php?option=com_jshopping&controller=products');
        }
        
        $doc = JFactory::getDocument();
        $session = JFactory::getSession();
        $jshopConfig = JSFactory::getConfig();
        $pricesModel = JSFactory::getModel('prices');
        $deliveryTimes = JSFactory::getModel('deliverytimes');	
        $units = JSFactory::getModel('units');
        $productLabels = JSFactory::getModel('productlabels');
        $currencies = JSFactory::getModel('currencies');
        $modelOfProducts = JSFactory::getModel('products');
        $manufacturers = JSFactory::getModel('manufacturers');	
        $taxes = JSFactory::getModel('taxes');
        $usergroupsModel = JSFactory::getModel('usergroups');	

        $session->set('dependAttrsIdOfAttrEditList', $dependAttrsId);
        $lists = [
            'product_packing_type' => $modelOfProducts->getProductPackingTypeSelect__editList(-1),
            'manufacturers' => $manufacturers->getManufacturerListForEditList(),
            'tax' => $taxes->productEditList_getTaxList(),
            'usergroups' => $usergroupsModel->productEditList_getUsergroupsList()
        ];
        $deliveryTimes->getDeliveryTimesListForEditList($lists);   
        $units->getUnitsByProductIdLists(0, $lists);     
        $productLabels->productEditList_getLabelsList($lists);   	
        $currencies->productEditList_getCurrencyList($lists);	
        $productAttrPriceTypeSelect = $pricesModel->getProductAttrPriceTypeSelect_getAttrPriceList();

        $view = $this->getView('product_edit', 'html');
        $view->setLayout('dep_attrs_mass_edit');
        $view->set('productAttrPriceTypeSelect', $productAttrPriceTypeSelect);
        $view->set('jshopConfig', $jshopConfig);
        $view->set('lists', $lists);
        $view->displayDepAttrsMassEdit();
    }

    public function saveDependAttrEditList()
    {
        $originalPost = $post = $this->input->post->getArray();
        $session = JFactory::getSession();
        $dependAttrsId = $session->get('dependAttrsIdOfAttrEditList');
        
        if (empty($dependAttrsId['0']->productAttrId) && empty($dependAttrsId['0']->extAttrProductId)) {
            return raiseWarningRedirect(Text::_('COM_SMARTSHOP_RECORDS_NOT_FOUND'), 'administrator/index.php?option=com_jshopping&controller=products');
        }

        $pricesModel = JSFactory::getModel('prices');
        $productsModel = JSFactory::getModel('products');
        $product = JSFactory::getTable('product');
        $productsModel->productSave_setPostValues($post);
        $productAttribut = JSFactory::getTable('productAttribut', 'jshop');

        foreach ($dependAttrsId as $depAttrsIds) {
            $depAttrId = $depAttrsIds->extAttrProductId;
            $extAttrProductId = $depAttrsIds->productAttrId;

            $product->load($depAttrId, true, false);
            $productAttribut->load($extAttrProductId);

            if ($originalPost['product_price'] != '') {
                $product->product_price = $post['product_price'];
                $productAttribut->set("price", $product->product_price);
            }

            if (!empty($post['product_price2'])) {
                $product->product_price2 = $post['product_price2'];
            }

            if ($originalPost['product_old_price'] != '') {
                $product->product_old_price = $post['product_old_price'];
                $productAttribut->set("old_price", $product->product_old_price);
            }

            if (!empty($post['product_buy_price'])) {
                $product->product_buy_price = $post['product_buy_price'];
                $productAttribut->set("buy_price", $product->product_buy_price);
            }

            if (!empty($post['min_price'])) {
                $product->min_price = $post['min_price'];
            }

            // Price per consignment
            if (!empty($post['product_is_add_price'])) {
                $productsModel->saveAditionalPrice($depAttrId, $post['product_add_discount'], $post['quantity_start'], $post['quantity_finish'], $post['product_add_price'], $post['start_discount']);
                $product->product_is_add_price = (!empty($post['quantity_start']) && !empty($post['product_is_add_price']));
                $product->add_price_unit_id = $post['add_price_unit_id'];
                $productAttribut->set("add_price_unit_id", $product->add_price_unit_id);
            }

            // Price per consignment upload
            if (!empty($post['is_activated_price_per_consignment_upload'])) {
                $modelOfNativeUploadsPricesAdmin = JSFactory::getModel('NativeUploadsPricesAdmin');
                $modelOfNativeUploadsPricesAdmin->deleteByProductId($depAttrId);
                $isExistsNativeProgressUplodsPrices = !empty($post['nativeProgressUploads']['prices']['updates']);

                if ($isExistsNativeProgressUplodsPrices) {
                    $modelOfNativeUploadsPricesAdmin->savePricesData($post['nativeProgressUploads']['prices']['updates'], $depAttrId);
                }

                $product->is_activated_price_per_consignment_upload = ($isExistsNativeProgressUplodsPrices && $post['is_activated_price_per_consignment_upload']);
            }

            // Usergroups prices
            if (!empty($post['add_usergroup_price'])) {
                $pricesModel->deleteOldPrices($depAttrId);

                if ($post['add_usergroups_prices_usergroup'] != -1) {
                    $pricesModel->addUsergroupPrices($depAttrId, $post);
                }
            }

            if ($originalPost['weight_volume_units'] != '') {
                $product->weight_volume_units = $post['weight_volume_units'];
                $productAttribut->set("weight_volume_units", $product->weight_volume_units);

                if ($post['basic_price_unit_id'] != -1) {
                    $product->basic_price_unit_id = $post['basic_price_unit_id'];
                    $productAttribut->set('basic_price_unit_id', $product->basic_price_unit_id);
                }
            }

            if ($post['product_tax_id'] != -1) {
                $product->product_tax_id = $post['product_tax_id'];
                $productAttribut->set('product_tax_id', $product->product_tax_id);
            }

            if ($post['product_manufacturer_id'] != -1) {
                $product->product_manufacturer_id = $post['product_manufacturer_id'];
                $productAttribut->set('product_manufacturer_id', $product->product_manufacturer_id);
            }

            if ($post['delivery_times_id'] != -1) {
                $product->delivery_times_id = $post['delivery_times_id'];
                $productAttribut->set('delivery_times_id', $product->delivery_times_id);
            }

            if ($post['label_id'] != -1) {
                $product->label_id = $post['label_id'];
                $productAttribut->set('label_id', $product->label_id);
            }

            if ($originalPost['product_weight'] != '') {
                $product->product_weight = $post['product_weight'];
                $productAttribut->set("weight", $product->product_weight);
            }

            if ($originalPost['expiration_date'] != '') {
                $product->expiration_date = $post['expiration_date'];
                $productAttribut->set("expiration_date", $product->expiration_date);
            }

            if ($originalPost['product_ean'] != '') {
                $product->product_ean = $post['product_ean'];
                $productAttribut->set("ean", $product->product_ean);
            }

            if (isset($post['factory']) && $post['factory'] != '') {
                $product->factory = $post['factory'];
                $productAttribut->set('factory', $product->factory);
            }

            if (isset($post['storage']) && $post['storage'] != '') {
                $product->storage = $post['storage'];
                $productAttribut->set('storage', $product->storage);
            }

            if (isset($post['production_time']) && $post['production_time'] != '') {
                $product->production_time = $post['production_time'];
                $productAttribut->set("production_time", $product->production_time);
            }

            if ($originalPost['product_quantity'] != '') {
                $product->product_quantity = $post['product_quantity'];
                $productAttribut->set("count", $product->product_quantity);
            }

            if (!empty($post['unlimited'])) {
                $product->unlimited = $post['unlimited'];
                $productAttribut->set('unlimited', $product->unlimited);
            } elseif(!empty($post['low_stock_number'])) {
                $product->low_stock_number = $post['low_stock_number'];
                $product->low_stock_notify_status = $post['low_stock_notify_status'];
                $productAttribut->set('low_stock_attr_notify_status', $product->low_stock_notify_status);
                $productAttribut->set('low_stock_attr_notify_number', $product->low_stock_number);
            }

            if ($post['product_price_type'] != -1) {
                $product->product_price_type = $post['product_price_type'];
                $product->qtydiscount = $post['qtydiscount'];
                $productAttribut->set('product_price_type', $product->product_price_type);
                $productAttribut->set('qtydiscount', $product->qtydiscount);
            }

            if (isset($post['quantity_select']) && $post['quantity_select'] != '') {
                $product->quantity_select = $post['quantity_select'];
                $product->equal_steps = $post['equal_steps'];
                $productAttribut->set('quantity_select', $product->quantity_select);
                $productAttribut->set('equal_steps', $product->equal_steps);
            }

            $productsModel->saveProductOptions($depAttrId, $post['options']);
			$media= JSFactory::getModel('ProductsMediaFront')->handleUploadMedia($post['media']);
			if(!empty($media)){
				$product->is_use_additional_media = 1;
				$productsModel->setMedia($product->product_id, $_FILES['media'], $post);
			}
			
			

            if ($originalPost['max_count_product'] != '') {
                $product->max_count_product = $post['max_count_product'];
                $productAttribut->set('max_count_product', $product->max_count_product);
            }

            if ($originalPost['min_count_product'] != '') {
                $product->min_count_product = $post['min_count_product'];
                $productAttribut->set('min_count_product', $product->min_count_product);
            }

            $product->store();

            $product->load($depAttrId, true, false);
            $product->one_time_cost = JSFactory::getTable('ProductAttribut2')->calcAttrsWithOneTimeCostPriceType($product->product_id, $post['attrib_ind_value_id'], getPriceCalcParamsTax($product->product_price, $product->product_tax_id));
            JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
            $productCalculatedData = JSFactory::getModel('ProductsFront')->calculateProductDataByProductId($depAttrId);
            $product->preview_total_price = $productCalculatedData['calculatedPrice'];
            $product->preview_calculated_weight = $productCalculatedData['product']->getWeight();
            $product->store();

            if ($productAttribut->check()) {
                $productAttribut->store();
            }
        }

        die('<script>window.close();</script>');
    }
    
    public function publish()
    {        
        $this->_publishProduct(1);
        $this->setRedirect("index.php?option=com_jshopping&controller=products");
    }
    
    public function unpublish()
    {
        $this->_publishProduct(0);
        $this->setRedirect("index.php?option=com_jshopping&controller=products");
    }    
    
    public function _publishProduct($flag) 
    {
        $jshopConfig = JSFactory::getConfig();        
        $cid = JFactory::getApplication()->input->getVar('cid');        
        $_products = JSFactory::getModel("products");
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforePublishProduct', array(&$cid, &$flag) );
        foreach ($cid as $key => $value){
			$_products->updatePublish($value,$flag);            
        }
        
        $dispatcher->triggerEvent( 'onAfterPublishProduct', array(&$cid, &$flag) );
    }
    
    public function editlist()
    {
        $cid = Factory::getApplication()->input->getVar('cid') ?: [];
        if (count($cid) == 1) {
            return $this->setRedirect('index.php?option=com_jshopping&controller=products&task=edit&product_id=' . $cid[0]);
        }
        
        $jshopConfig = JSFactory::getConfig();   
        $dispatcher = Factory::getApplication();
        $dispatcher->triggerEvent( 'onLoadEditListProduct', []);
        $_pricemodification = JSFactory::getModel('pricemodification');
        $modelOfUsergroups = JSFactory::getModel('usergroups');
		$_units = JSFactory::getModel('units');		
        $_deliveryTimes = JSFactory::getModel('deliverytimes');		
		$_manufacturers = JSFactory::getModel('manufacturers');		
		$_categories = JSFactory::getModel('categories');
		$_publish = JSFactory::getModel('publish');		
		$_taxes = JSFactory::getModel('taxes');
		$_productlabels = JSFactory::getModel('productlabels');
		$_access = JSFactory::getModel('access');
		$_currencies = JSFactory::getModel('currencies');
		$modelOfProducts = JSFactory::getModel('products');
        $modelOfPrices = JSFactory::getModel('prices');
        $session = Factory::getSession();
        $modelOfCharacteristicsBathProductEdit = JSFactory::getModel('CharacteristicsBathProductEdit');
        $modelOfShippingsBathProductEdit = JSFactory::getModel('ShippingsBathProductEdit');
        $modelOfCustomizeBathProductEdit = JSFactory::getModel('CustomizeBathProductEdit');
        $modelOfUsergroupPermissionBathProductEdit = JSFactory::getModel('UsergroupPermissionBathProductEdit');
        $modelOfShippings = JModelLegacy::getInstance('shippings', 'JshoppingModel');
        $allTaxes = $_taxes->getAllTaxes();

        $isAfterMassSave = $dispatcher->input->getBool('afterMassSave', false);
        if ($isAfterMassSave && !empty($session->get('mass_edit_cids'))) {
            $cid = $session->get('mass_edit_cids');
            $session->clear('mass_edit_cids');
        }
        
        $list_tax = [
            JHTML::_('select.option', -1, '- - -','tax_id','tax_name')
        ];

        foreach($allTaxes as $tax) {
            $list_tax[] = JHTML::_('select.option', $tax->tax_id, $tax->tax_name . ' (' . $tax->tax_value . '%)','tax_id','tax_name');
        }
        $withoutTax = empty($allTaxes) ? 1: 0;
        
		$price_modification = $_pricemodification->getModificationArray();
        $lists = [
            'manufacturers' => $_manufacturers->getManufacturerListForEditList(),
            'price_mod_price' => JHTML::_('select.genericlist', $price_modification, 'mod_price', 'class="form-select"', 'id', 'name'),
            'price_mod_old_price' => JHTML::_('select.genericlist', $price_modification, 'mod_old_price', 'class="form-select"', 'id', 'name'),
            'product_publish' => $_publish->getPublishSelectWithFirstFreeElement('product_publish'),
            'tax' => JHTML::_('select.genericlist', $list_tax, 'product_tax_id', 'class = "inputbox form-select" size = "1"', 'tax_id', 'tax_name'),
            'templates' => getTemplates('product', '', 1),
            'product_packing_type' => $modelOfProducts->getProductPackingTypeSelect__editList(-1),
            'priceType' => $modelOfPrices->getProductAttrPriceTypeSelect_getAttrPriceList(),
            'add_price_units' => $_units->generateAddPriceUnitsList(0, true),
            'usergroup_add_price' => $modelOfUsergroups->getUsergroupsAddPrice('', ''),
            'add_usergroups_prices_add_price_units_add' => $_units->getUsergroupsAddPriceUnitsList(null, 'add_usergroups_prices_add_price_unit_id_list[100500]'),
            'usergroup_show_product' => $modelOfUsergroups->getUsergroupsShowProduct('*', ''),
	        'usergroup_show_price' => $modelOfUsergroups->getUsergroupsShowPrice('*', ''),
	        'usergroup_show_buy' => $modelOfUsergroups->getUsergroupsShowActions('*', '')
        ];
        
        $shippings = $modelOfShippings->getAllShippingPricesByCountries(1, 0);
		$_deliveryTimes->getDeliveryTimesListForEditList($lists);   
		$_units->getUnitsLists($lists, true);     
		$_productlabels->productEditList_getLabelsList($lists);   
		$_access->productEditList_getAccessList($lists);		
		$_currencies->productEditList_getCurrencyList($lists, true);		
        $_categories->productEditList_getCategoriesList($lists);

        $view = $this->getView('product_edit', 'html');
        $view->setLayout('mass_edit');        
		$view->set('canDo', $this->canDo);
        $view->set('lists', $lists);
        $view->set('cid', $cid);
        $view->set('config', $jshopConfig);        
        $view->set('withouttax', $withoutTax);        
        $view->set('isPageWithAdditionalValues', true);
        $view->set('isBatchEdit', true);
        $view->set('etemplatevar', '');
        $view->set('shippings', $shippings);
        $view->set('characteristics_action', $modelOfCharacteristicsBathProductEdit->getMarkUps('characteristics_action'));
        $view->set('shippings_action', $modelOfShippingsBathProductEdit->getMarkUps('shippings_action'));
        $view->set('customize_action', $modelOfCustomizeBathProductEdit->getMarkUps('customize_action'));
        $view->set('usergroup_permission_action', $modelOfUsergroupPermissionBathProductEdit->getMarkUps('usergroup_permission_action'));

        if ($jshopConfig->admin_show_freeattributes) {
            $this->batchEditOfFreeAttrs($view);
        }

        if ($jshopConfig->admin_show_product_related) {
            $this->batchEditOfRelatedProducts($view);
        }

        if (!empty($jshopConfig->admin_show_product_demo_files) || !empty($jshopConfig->admin_show_product_sale_files)) {
            $this->batchEditOfFiles($view);
        }

        if ($jshopConfig->admin_show_attributes) {
            $this->batchEditOfAttributes($view);
        }   

        $this->batchEditOfDescription($view);
        $this->batchEditOfMedia($view);

        if ($jshopConfig->admin_show_product_extra_field) {
            $view->set('fields', $this->getProductExtraFields([], null, true));
        }

        $dispatcher->triggerEvent('onBeforeDisplayEditListProductView', [&$view]);
        $view->editGroup();
    }

    protected function batchEditOfMedia($view): void
    {
        $modelOfMediaBathProductEdit = JSFactory::getModel('MediaBathProductEdit');
        $view->set('media_actions', $modelOfMediaBathProductEdit->getMarkUps('media_actions'));
    }

    protected function batchEditOfAttributes($view): void
    {
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . 'administrator/components/com_jshopping/js/src/scripts/SortableJS/Sortable.js');

        $_taxes = JSFactory::getModel("taxes");
        $_prices = JSFactory::getModel('prices');
        $modelOfUnits = JSFactory::getModel('Units');
        $_labels = JSFactory::getModel("productLabels");
        $_usergroups = JSFactory::getModel("usergroups");
        $_manufacturer =JSFactory::getModel('manufacturers');
        $_deliveryTimes = JSFactory::getModel("deliveryTimes");
        $_attribut_value = JSFactory::getModel('attributValue');
        $_dependent_attributes = JSFactory::getModel('dependent_attributes');
        $_independent_attributes = JSFactory::getModel('independent_attributes');
        $modelOfAttributesBatchProductEdit = JSFactory::getModel('AttributesBatchProductEdit');
        $attrsActions = $modelOfAttributesBatchProductEdit->getMarkUps('attrs_actions');
        
        $lists = $view->get('lists');
        $lists['attr_depend_usergroups'] = $_usergroups->getUsergroupsList('attr_depend_add_usergroups_prices_usergroup[100500]');
        $lists['attribs_values']  = $_attribut_value->getAllAttributeValues(2);
        $lists['attr_price_per_consignment_basic_price_unit_id']  = $modelOfUnits->getProductUnitsList(null, '', 'attr_price_per_consignment_basic_price_unit_id');
        $lists['attr_add_usergroups_prices_add_price_units']  = $modelOfUnits->getUsergroupsAddPriceUnitsList(null, 'attr_add_usergroups_prices_add_price_unit_id');
        $lists['attr_basic_price_unit_id'] = $modelOfUnits->getProductUnitsList(null, '', 'attr_basic_price_unit_id');

        $_labels->getlabelsLists(null, $lists, 'attr_labels', '', 'attr_labels');
        $_deliveryTimes->getDeliveryTimesList(null, $lists, 'attr_deliverytimes', '', 'attr_delivery_times_id');
        $_manufacturer->getManufacturersSelect(new stdClass, $lists, 'attr_manufacturers', '', 'attr_product_manufacturer_id');
        $_taxes->getTaxesSelect(new stdClass, $lists, 'attr_tax', '', 'attr_product_tax_id');
        
        $_dependent_attributes->getDependentAttributesList(new stdClass, $lists);
        $_independent_attributes->getIndependentAttributesList(new stdClass, $lists);

        $this->addJsToEditProductPage();
        JText::script('COM_SMARTSHOP_HIDDEN_ATTR_ADD_ERROR');

        $view->set('lists', $lists);
        $view->set('attrProductAttrPriceTypeSelect', $_prices->getProductAttrPriceTypeSelect(null, '', 'inputbox', 'attr_product_price_type'));
        $view->set('attrs_actions', $attrsActions);
    }

    protected function batchEditOfFiles($view): void
    {
        $modelOfFilesBatchProductEdit = JSFactory::getModel('FilesBatchProductEdit');
        $descriptionsAction = $modelOfFilesBatchProductEdit->getMarkUps('files_actions');
        
        $view->set('files_actions', $descriptionsAction);
    }

    protected function batchEditOfDescription($view): void
    {
        $modelOfDescriptionBatchProductEdit = JSFactory::getModel('DescriptionBatchProductEdit');
        $descriptionsAction = $modelOfDescriptionBatchProductEdit->getMarkUps('descriptions_action', [], 2, [
            2 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_REPLACE'
        ]);

        $modelOfLang = \JSFactory::getModel('languages');
        $shopLanguages = $modelOfLang->getAllLanguages(1);

        $view->set('languages', $shopLanguages);
        $view->set('descriptions_actions', $descriptionsAction);
    }

    protected function batchEditOfFreeAttrs($view): void
    {
        $modelOfFreeAttrsBatchProductEdit = JSFactory::getModel('FreeAttrsBatchProductEdit');
        $modelOfFreeAttr = JSFactory::getModel('FreeAttribut');
        $allFreeAttrs = $modelOfFreeAttr->getAll();
        $rowsWithDefaultValues = AdminFreeAttrsDefaultValuesMambot::getInstance()->getPreparedInputsAndCheckboxesTemplateForFreeAttrsRows($allFreeAttrs);
        $freeAttrsActions = $modelOfFreeAttrsBatchProductEdit->getMarkUps('freeattrs_action');

        $view->set('listfreeattributes', $allFreeAttrs);
        $view->set('arrWithHtmlRowsOfDefaultValues', $rowsWithDefaultValues);
        $view->set('freeattrs_actions', $freeAttrsActions);
    }

    protected function batchEditOfRelatedProducts($view): void
    {
        $modelOfFRelatedProductsBathEdit = JSFactory::getModel('RelatedProductsBathEdit');
        $relatedProductsActions = $modelOfFRelatedProductsBathEdit->getMarkUps('related_products_actions');

        $view->set('related_products_actions', $relatedProductsActions);
    }

    public function massSave()
    {
        $cids = Factory::getApplication()->input->post->get('cid');
        $session = Factory::getSession();
        $session->set('mass_edit_cids', $cids);

        $this->savegroup();
        $this->setRedirect('index.php?option=com_jshopping&controller=products&task=editlist&afterMassSave=true', JText::_('COM_SMARTSHOP_PRODUCT_SAVED'));
    }

    public function massSaveAndClose()
    {
        $this->savegroup();
        $this->setRedirect('index.php?option=com_jshopping&controller=products', JText::_('COM_SMARTSHOP_PRODUCT_SAVED'));
    }
    
    public function savegroup()
    {
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = JFactory::getApplication();
        $modelOfPrices = JSFactory::getModel('prices');
        $modelOfProducts = JSFactory::getModel('products');
        $modelOfNativeUploadsPricesAdmin = JSFactory::getModel('NativeUploadsPricesAdmin');
        $dispatcher->triggerEvent('onBeforSaveListProduct', []);
        $cid = JFactory::getApplication()->input->getVar('cid');
        $post = JFactory::getApplication()->input->post->getArray();

        $modelOfCharacteristicsBathProductEdit = JSFactory::getModel('CharacteristicsBathProductEdit');
        $modelOfShippingsBathProductEdit = JSFactory::getModel('ShippingsBathProductEdit');
        $modelOfCustomizeBathProductEdit = JSFactory::getModel('CustomizeBathProductEdit');
        $modelOfUsergroupPermissionBathProductEdit = JSFactory::getModel('UsergroupPermissionBathProductEdit');
        $modelOfFreeAttrsBatchProductEdit = JSFactory::getModel('FreeAttrsBatchProductEdit');
        $modelOfDescriptionBatchProductEdit = JSFactory::getModel('DescriptionBatchProductEdit');
        $modelOfRelatedProductsBathEdit = JSFactory::getModel('RelatedProductsBathEdit');
        $modelOfFilesBatchProductEdit = JSFactory::getModel('FilesBatchProductEdit');
        $modelOfAttributesBatchProductEdit = JSFactory::getModel('AttributesBatchProductEdit');
        $modelOfMediaBathProductEdit = JSFactory::getModel('MediaBathProductEdit');

        foreach($cid as $id) {
            $product = JSFactory::getTable('product', 'jshop');
            $product->load($id);

            if ($post['access'] != -1){
                $product->set('access', $post['access']);
            }

            if (isset($post['product_publish']) && $post['product_publish'] != -1) {
                $product->set('product_publish', $post['product_publish']);
            }

            if (isset($post['product_packing_type']) && $post['product_packing_type'] != -1) {
                $product->set('product_packing_type', $post['product_packing_type']);
            }

            if ($post['product_weight'] != '') {
                $product->set('product_weight', $post['product_weight']);
            }

            if (!empty($post['expiration_date'])) {
                $product->set('expiration_date', $post['expiration_date']);
            }

            if (isset($post['product_ean']) && $post['product_ean'] != '') {
                $product->set('product_ean', $post['product_ean']);
            }

            if ($post['product_quantity'] != '') {
                $product->set('product_quantity', $post['product_quantity']);
                $product->set('unlimited', 0);
            }  

            if (!empty($post['unlimited'])) {
                $product->set('product_quantity', 1);
                $product->set('unlimited', 1);
            }

            if (isset($post['factory']) && $post['factory'] != '') {
                $product->set('factory', $post['factory']);
            }

            if (isset($post['storage']) && $post['storage'] != '') {
                $product->set('storage', $post['storage']);
            }

            if (isset($post['product_template']) && $post['product_template'] != -1) {
                $product->set('product_template', $post['product_template']);
            }

            if (isset($post['product_tax_id']) && $post['product_tax_id'] != -1) {
                $product->set('product_tax_id', $post['product_tax_id']);
            }

            if (isset($post['product_manufacturer_id']) && $post['product_manufacturer_id'] != -1) {
                $product->set('product_manufacturer_id', $post['product_manufacturer_id']);
            }  

            if (isset($post['category_id']) && $post['category_id']){
                $modelOfProducts->setCategoryToProduct($id, $post['category_id']);
            }

            if (isset($post['production_time']) && $post['production_time'] != '') {
                $product->set('production_time', $post['production_time']);
            }

            if (isset($post['delivery_times_id']) && $post['delivery_times_id'] != -1) {
                $product->set('delivery_times_id', $post['delivery_times_id']);
            }
            if (isset($post['label_id']) && $post['label_id'] != -1) {
                $product->set('label_id', $post['label_id']);
            }

            if (!empty($post['options']['no_return'])) {
                $modelOfProducts->saveProductOptions($id, $post['options']);
            }

            if (!empty($post['equal_steps'])) {
                $product->set('equal_steps', $post['equal_steps']);
            }

            if (!empty($post['quantity_select'])) {
                $product->set('quantity_select', $post['quantity_select']);
            }

            if (isset($post['max_count_product'])  && $post['max_count_product'] != '') {
                $product->set('max_count_product', $post['max_count_product']);
            }

            if (isset($post['min_count_product']) && $post['min_count_product'] != '') {
                $product->set('min_count_product', $post['min_count_product']);
            }
            
            if ($post['product_price'] != '') {
                $oldprice = $product->product_price;
                $price = $modelOfProducts->getModPrice($product->product_price, saveAsPrice($post['product_price']), $post['mod_price']);
                $product->set('product_price', $price);
                if ($post['use_old_val_price']==1){
                    $product->set('product_old_price', $oldprice);
                }
            }

            if (isset($post['product_old_price']) && $post['product_old_price'] != '') {
                $price = $modelOfProducts->getModPrice($product->product_old_price, saveAsPrice($post['product_old_price']), $post['mod_old_price']);
                $product->set('product_old_price', $price);
            }

            if (isset($post['currency_id']) && $post['currency_id'] != -1) {                
                $product->set('currency_id', $post['currency_id']);
            }

            if (!empty($post['product_is_add_price'])) {
                $modelOfProducts->saveAditionalPrice($product->product_id, $post['product_add_discount'], $post['quantity_start'], $post['quantity_finish'], $post['product_add_price'], $post['start_discount']);
                $product->set('product_is_add_price', (!empty($post['quantity_start']) && !empty($post['product_is_add_price'])));
                $product->set('add_price_unit_id', $post['add_price_unit_id']);
            }
            
            if (!empty($post['is_activated_price_per_consignment_upload'])) {
                $product->set('is_activated_price_per_consignment_upload', true);
                $modelOfNativeUploadsPricesAdmin->deleteByProductId($product->product_id);
                if (!empty($post['nativeProgressUploads']['prices']['updates'])) {
                    $modelOfNativeUploadsPricesAdmin->savePricesData($post['nativeProgressUploads']['prices']['updates'], $product->product_id);
                }
            }

            if (!empty($post['add_usergroups_prices_product_price_list'])) {
                $modelOfPrices->deleteOldPrices($product->product_id);	
                $modelOfPrices->addUsergroupPrices($product->product_id, $post);	
            }

            if (isset($post['product_price_type']) && $post['product_price_type'] != -1) {
                $product->set('product_price_type', $post['product_price_type']);
            }

            if (isset($post['qtydiscount'])) {
                $product->set('qtydiscount', $post['qtydiscount']);
            }

            if (isset($post['product_buy_price']) && $post['product_buy_price'] != '') {
                $product->set('product_buy_price', $post['product_buy_price']);
            }

            if (isset($post['weight_volume_units']) && $post['weight_volume_units'] != '') {
                $product->set('weight_volume_units', $post['weight_volume_units']);
            }

            if (isset($post['basic_price_unit_id']) && $post['basic_price_unit_id'] != -1) {
                $product->set('basic_price_unit_id', $post['basic_price_unit_id']);
            }

            if (isset($post['low_stock_notify_status'])) {
                $product->set('low_stock_notify_status', $post['low_stock_notify_status']);
            }

            if (isset($post['low_stock_number']) && $post['low_stock_number'] != '') {
                $product->set('low_stock_number', $post['low_stock_number']);
            }

            if ($jshopConfig->admin_show_product_extra_field){              
                $modelOfCharacteristicsBathProductEdit->resolveActionOfProduct($product, $post['productfields'], $post['characteristics_action']);
            }

            if ($jshopConfig->admin_show_freeattributes) {
                $modelOfFreeAttrsBatchProductEdit->resolveActionOfProduct($product, $post, $post['freeattrs_action']);
            }

            if ($jshopConfig->admin_show_product_related) {
                $modelOfRelatedProductsBathEdit->resolveActionOfProduct($product, $post, $post['related_products_actions']);
            }

            if (!empty($jshopConfig->admin_show_product_demo_files) || !empty($jshopConfig->admin_show_product_sale_files)) {
                $modelOfFilesBatchProductEdit->resolveActionOfProduct($product, $post, $post['files_actions']);
            }

            if ($jshopConfig->admin_show_attributes) {
                $modelOfAttributesBatchProductEdit->resolveActionOfProduct($product, $post, $post['attrs_actions']);
            } 

            $modelOfShippingsBathProductEdit->resolveActionOfProduct($product, $post['spm_published'], $post['shippings_action']);
            $modelOfCustomizeBathProductEdit->resolveActionOfProduct($product, $post, $post['customize_action']);
            $modelOfUsergroupPermissionBathProductEdit->resolveActionOfProduct($product, $post, $post['usergroup_permission_action']);
            $modelOfDescriptionBatchProductEdit->resolveActionOfProduct($product, $post, $post['descriptions_action']);
            $modelOfMediaBathProductEdit->resolveActionOfProduct($product, $post, $post['media_actions']);
            
            $modelOfProducts->updatePriceAndQtyDependAttr($id, $post);
			$dispatcher->triggerEvent('onBeforSaveListProductBeforeStore', [&$product, &$post]);
            $product->store();

            $productCalculatedData = JSFactory::getModel('ProductsFront')->calculateProductDataByProductId($product->product_id);
            $product->preview_total_price = $productCalculatedData['calculatedPrice'];
            
            if ($post['product_price'] != '') {
                $mprice = $product->getMinimumPrice();
                $product->set('min_price', $mprice);
            }

            $product->date_modify = getJsDate();
            $product->store();
            unset($product);
        }

        $dispatcher->triggerEvent('onAfterSaveListProductEnd', [&$cid, &$post]);
    }

    public function copy()
    {
        $db = \JFactory::getDBO();
        $text = [];
        $productsIdsToCopy = JFactory::getApplication()->input->getVar('cid');
        $_freeattribut = JSFactory::getModel('FreeAttribut');
		$_categories = JSFactory::getModel('categories');
        $_productattrs = JSFactory::getModel('productattrs');
        $modelOfNativeUploadsPriceAdmin = JSFactory::getModel('NativeUploadsPricesAdmin');
        $modelOfProductMedia = JSFactory::getModel('ProductMedia');
        $modelOfProductsPrices = JSFactory::getModel('Prices');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeCopyProduct', [&$productsIdsToCopy]);
        
        $_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);
        $productModel = JSFactory::getModel('products');
        // Get all data about products
        $tables = ['attr', 'attr2', 'images', 'prices', 'relations', 'to_categories', 'videos', 'files', 'shipping'];
        foreach ($productsIdsToCopy as $key => $productIdToCopy) {
            $product = JSFactory::getTable('product', 'jshop');
            $product->load($productIdToCopy);
            $product->product_id = null;   
            
            foreach($languages as $lang) {
                $name_alias = 'alias_' . $lang->language;

                if (!empty($product->$name_alias)) {
                    $product->$name_alias = $product->$name_alias.date('ymdHis');
                }
            }
            
            $product->product_date_added = getJsDate();
            $product->date_modify = getJsDate();
            $product->average_rating = 0;
            $product->reviews_count = 0;
            $product->hits = 0;
            $product->store();

            $arrWithFreeAttrs = $_freeattribut->getProductFreeAttributes($productIdToCopy);
            $modelOfProductMedia->copyMediaInStorage($productIdToCopy, $product->product_id);
            $modelOfNativeUploadsPriceAdmin->duplicatePrices($productIdToCopy, $product->product_id);
            $_freeattribut->setFreeAttributesForProduct($product->product_id, $arrWithFreeAttrs);

			$array = $productModel->getProductAdditionalInfoById($productIdToCopy, $tables);                            
            $productModel->copyProductAdditionalInfo($tables, $array, $product->product_id);
            $modelOfProductsPrices->copyProductPricesGroup($productIdToCopy, $product->product_id);
            
            //change order in category
			$list = $_categories->getCategoriesByProductID($product->product_id);
                    
            foreach($list as $val) {
				$ordering = $_categories->getNextProductsToCategoryOrdering($val->category_id);
                $_categories->setOrderingForProductsToCategories($ordering, $val->category_id, $product->product_id);
            }
            
			$_productattrs->setExtAttributeForProductID($product->product_id, '0');
            $productModel->setProductAttr($productsIdsToCopy, $key, $productIdToCopy, $product);
        
            if (isset($product->product_id) && $product->product_id) {
                $_productattrs->deleteAttributes($product->product_id);                
                $attributes = $_productattrs->getProductsAttributes($productIdToCopy);               
                
			    $_attributvalue = JSFactory::getModel('AttributValue');
			    $_attributvalue->copyToSortTable($product->product_id, $productIdToCopy);
                if (!empty($attributes)) {
                    foreach ($attributes as $val) {
                        $extAttributeProduct = $this->createExtAttributeProduct($val['ext_attribute_product_id'], $product->product_id);
                        $val['ext_attribute_product_id'] = 0;
                        if ($extAttributeProduct){
                            $val['ext_attribute_product_id'] = $extAttributeProduct;
                        }

                        $db->setQuery($productModel->copyProductBuildQuery('attr', $val, $product->product_id));
                        $db->execute();
                    }
                }
            }
            
            $dispatcher->triggerEvent('onCopyProductEach', [&$productsIdsToCopy, &$key, &$productIdToCopy, &$product]);
            AdminFreeAttrsDefaultValuesMambot::getInstance()->onCopyProductEach($productsIdsToCopy, $key, $productIdToCopy, $product);
            $text[] = JText::sprintf('COM_SMARTSHOP_PRODUCT_COPY_TO', $productIdToCopy, $product->product_id) . '<br>';
        }
        
        $dispatcher->triggerEvent('onAfterCopyProduct', [&$productsIdsToCopy]);
        $this->setRedirect('index.php?option=com_jshopping&controller=products', implode('</li><li>', $text));
    }
    
    public function order()
    {
        $order = JFactory::getApplication()->input->getVar("order");
        $product_id = JFactory::getApplication()->input->getInt("product_id");
        $number = JFactory::getApplication()->input->getInt("number");
        $category_id = JFactory::getApplication()->input->getInt("category_id");
		$_products = JSFactory::getModel("products");        
        switch ($order) {
            case 'up':
				$_products->productOrderUp($category_id,$product_id,$number);                
                break;
            case 'down':
				$_products->productOrderDown($category_id,$product_id,$number);                                
        }   
        $this->setRedirect("index.php?option=com_jshopping&controller=products&category_id=".$category_id); 
    }
    
    public function saveorder()
    {        
		$_products = JSFactory::getModel("products");
        $category_id = JFactory::getApplication()->input->getInt("category_id");
        $cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar( 'order', array(), 'post', 'array' );        
        foreach($cid as $k=>$product_id){
			$_products->productSetOrdering($category_id,$product_id,$order[$k]);            
        }
        $this->setRedirect("index.php?option=com_jshopping&controller=products&category_id=".$category_id); 
    }
    
    public function remove()
    {                
        $text = [];
        $cid = $this->input->get('cid');
        $modelOfProducts = JSFactory::getModel('products');   
        $modelOfProductFile = JSFactory::getModel('productfile');
        $modelOfNativeUploadsPriceAdmin = JSFactory::getModel('NativeUploadsPricesAdmin');
        $modelOfProductMedia = JSFactory::getModel('ProductMedia');
        $reviewsModel = JSFactory::getModel('reviews');
        $modelOfProductPricesGroup = JSFactory::getModel('Prices');
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeRemoveProduct', [&$cid]);
		
        foreach($cid as $productId) {          
            $modelOfProductFile->deleteProductFiles($productId); 
            $modelOfProducts->deleteProductFromTables($productId);  
            $modelOfProductMedia->deleteByProductId($productId);
            $modelOfNativeUploadsPriceAdmin->deleteByProductId($productId);
            $reviewsModel->deleteFilesByProdId($productId);
            $reviewsModel->deleteByProductId($productId);
            $modelOfProductPricesGroup->deleteProductPricesGroupByProductId($productId);

            $text[]= JText::sprintf('COM_SMARTSHOP_PRODUCT_DELETED', $productId) . '<br>';
        }

        $dispatcher->triggerEvent( 'onAfterRemoveProduct', [&$cid]);
        AdminFreeAttrsDefaultValuesMambot::getInstance()->onAfterRemoveProduct($cid);

        $this->setRedirect("index.php?option=com_jshopping&controller=products", implode('</li><li>',$text));
    }
    
    public function cancel()
    {
        $this->setRedirect("index.php?option=com_jshopping&controller=products");
    }
    
    public function delete_foto()
    {	
        $result = true;
        $id = $this->input->get('id');
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $modelOfProductsMediaFront = JSFactory::getModel('ProductsMediaFront');

        // if (!$modelOfProductsMediaFront->isExistsMediaWithSameSrcById($id)) {
        //     $result = $modelOfProductsMediaFront->deleteById($id);
        // } else {
            $result = $modelOfProductsMediaFront->deleteMediaWithoutFilesByColumnName($id);
        //}
        
        echo $result;
        die;
    }
    
    public function delete_video()
    {
		$this->delete_foto();
    }
    
    public function delete_file()
    {
		$id = JFactory::getApplication()->input->getInt("id");
        $type = JFactory::getApplication()->input->getVar("type");
		$_productfile = JSFactory::getModel("productvideo"); 
		$_productfile->deleteFileByFileId($id,$type);        
        die();    
    }
    
    public function search_related()
    {
        $mainframe = JFactory::getApplication();        
        $jshopConfig = JSFactory::getConfig();        
        $_products = JSFactory::getModel("products");        
        
        $text_search = JFactory::getApplication()->input->getVar("text");
        $limitstart = JFactory::getApplication()->input->getInt("start");
        $no_id = JFactory::getApplication()->input->getInt("no_id");
        $limit = 20;
        
        $filter = array("without_product_id"=>$no_id, "text_search"=>$text_search,"except_categories_id"=>[1]);
        $total = $_products->getCountAllProducts($filter);
        $rows = $_products->getAllProducts($filter, $limitstart, $limit);
        $page = ceil($total/$limit);

        $view=$this->getView("product_list", 'html');
        $view->setLayout("search_related");
		$view->set("canDo", $this->canDo);
        $view->set('rows', $rows);
        $view->set('config', $jshopConfig);
        $view->set('limit', $limit);
        $view->set('pages', $page);
        $view->set('no_id', $no_id);
        $view->display();
        die();
    } 
    
    public function product_extra_fields()
    {
        $product_id = JFactory::getApplication()->input->getInt("product_id");
        $cat_id = JFactory::getApplication()->input->getVar("cat_id");
        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);
        
        $categorys = array();
        if (is_array($cat_id)){
            foreach($cat_id as $cid){
                $categorys[] = intval($cid);        
            }
        }        
        
        print $this->_getHtmlProductExtraFields($categorys, $product);
        die();    
    }
    
    public function _getHtmlProductExtraFields($categorys=array(), $product = null)
    {
        $fields = $this->getProductExtraFields($categorys, $product);
        $view = $this->getView("product_edit", 'html');
        $view->setLayout("extrafields_inner");
		$view->set("canDo", $this->canDo);
        $view->set('fields', $fields);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadTemplateHtmlProductExtraFields', [&$view]);
        return $view->loadTemplate();
    }

    protected function getProductExtraFields($categorys = [], $product = null, bool $isAddEmptyFirstOption = false): array
    {
        if($product === null) $product = new stdClass;
        $_productfields = JSFactory::getModel('productFields');
        $_productfieldvalues = JSFactory::getModel('productFieldValues');

        $list = $_productfields->getList(1);
        $listvalue = $_productfieldvalues->getAllList();
        
        $f_option = [
            JHTML::_('select.option', 0, " - - - ", 'id', 'name')
        ];

        if ($isAddEmptyFirstOption) {
            array_unshift($f_option, JHTML::_('select.option', -1, ' - ', 'id', 'name'));
        }
        
        $fields = [];
        foreach($list as $v){
            $insert = 0;
            if ($v->allcats==1){
                $insert = 1;
            }else{
                $cats = unserialize($v->cats);
                foreach($categorys as $catid){
                    if (in_array($catid, $cats)) $insert = 1;
                }
            }
            if ($insert){
                $obj = new stdClass();
                $obj->id = $v->id;
                $obj->name = $v->name;
                $obj->groupname = $v->groupname;
                $tmp = array();
                foreach($listvalue as $lv){
                    if ($lv->field_id==$v->id) $tmp[] = $lv;
                }                
                $name = 'extra_field_'.$v->id;
                if ($v->type==0){
                    if ($v->multilist==1){
                        $attr = 'multiple="multiple" size="10" class="form-select"';
                    }else{
                        $attr = ' class="form-select"';
                    }
                    $obj->values = JHTML::_('select.genericlist', array_merge($f_option, $tmp), 'productfields['.$name.'][]', $attr, 'id', 'name', explode(',',$product->$name));
                }else{
                    $obj->values = "<input type='text' name='".$name."' value='".$product->$name."' />";
                }
                $fields[] = $obj;
            }
        }

        return $fields;
    }

    public function getfilesale()
    {  
        $id = JFactory::getApplication()->input->getVar('id');
        $file = JSFactory::getTable('productFiles', 'jshop');
        $file->load($id);
        $file_name = getPatchProductImage($file->file, '', 4);

        if (!file_exists($file_name)) {
            \JFactory::getApplication()->enqueueMessage("Error. File not exist",'error');
            return 0;
        }

        ob_end_clean();
        @set_time_limit(0);
        $fp = fopen($file_name, "rb");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . (string)(filesize($file_name)));
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        header("Content-Transfer-Encoding: binary");

        while( (!feof($fp)) && (connection_status()==0) ){
            print(fread($fp, 1024*8));
            flush();
        }
        fclose($fp);
        die();
    }
    
    public function loadproductinfo()
    {        
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadInfoProduct', array());        
        $product_id = JFactory::getApplication()->input->getInt('product_id');
        $currency_id = JFactory::getApplication()->input->getInt('currency_id');
        $layout = JFactory::getApplication()->input->getVar('layout','productinfo_json');
                
        $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);
        $product->getDescription();
        
        $currency = JSFactory::getTable('currency', 'jshop');
        $currency->load($currency_id);
        if ($currency_id){
            $currency_value = $currency->currency_value;
        }else{
            $currency_value = 1;
        }
        $product_price = getPriceFromCurrency($product->product_price, $product->currency_id, $currency_value);
		$product->getPrice();
		$prices = $product->getPricesArray();
        $res = array();
        $res['product_id'] = $product->product_id;
        $res['product_ean'] = $product->product_ean;
        $res['product_price'] = $product_price;
        $res['delivery_times_id'] = $product->delivery_times_id;        
        $res['product_weight'] = $product->product_weight;
        $res['product_tax'] = $product->getTax();
        $res['product_name'] = $product->name;
        $res['thumb_image'] = getPatchProductImage($product->image,'thumb');
		
		$res['one_time_cost'] = $prices['total_price_additional_one_time_cost_attrs']; 

        $view=$this->getView("product_edit", 'html');
        $view->setLayout($layout);
		$view->set("canDo", $this->canDo);
        $view->set('res', $res);
        $view->set('edit', null);
        $view->set('product', $product);
        $dispatcher->triggerEvent('onBeforeDisplayLoadInfoProduct', array(&$view) );
        $view->display();
    die();
    }

    public function getvideocode() 
    {
        $video_id = JFactory::getApplication()->input->getInt('video_id');
        $productvideo = JSFactory::getTable('productvideo', 'jshop');
        $productvideo->load($video_id);
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterLoadVideoCodeForProduct', array(&$productvideo));
        
        $view=$this->getView('product_edit', 'html');
        $view->setLayout('product_video_code');
		$view->set("canDo", $this->canDo);
        $view->set('code', $productvideo->video_code);
        
        $dispatcher->triggerEvent('onBeforeDisplayVideoCodeForProduct', array(&$view) );
        $view->display();
        die();
    }

    public function getFilteredProducts()
    {
        $rows = [];
        $jshopConfig = JSFactory::getConfig();
        $mainframe = JFactory::getApplication();
        $input = $mainframe->input->getArray();

        $input['easy_products'] = $input['easy_products'] ?? true;
        $input['context'] = $input['context'] ?: 'all';
        $contextfilter = $this->getContextFilterName($input['context']);
        $mainframe->setUserState($contextfilter . 'categorys', $input['category_id'] ?: []);
        $mainframe->setUserState($contextfilter . 'manufacturers', $input['manufacturer_id'] ?: []);
        $mainframe->setUserState($contextfilter . 'labels', $input['label_id'] ?: []);
        $mainframe->setUserState($contextfilter . 'vendors', $input['vendor_id'] ?: []);
        $mainframe->setUserState($contextfilter . 'fprice_from', $input['price_from'] ?: '');
        $mainframe->setUserState($contextfilter . 'fprice_to', $input['price_to'] ?: '');

        $backupOfIsEnabledUsergroupCheckForGetBuildQueryListProductSimpleList = $jshopConfig->is_enabled_usergroup_check_for_get_build_query_list_product_simple_list;
        $jshopConfig->is_enabled_usergroup_check_for_get_build_query_list_product_simple_list = false;

        $product = JSFactory::getTable('product', 'jshop');
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $filters = getBuildFilterListProduct($contextfilter, []);
        $filters['categorys'] = (array)$filters['categorys'];
        $filters['manufacturers'] = (array)$filters['manufacturers'];
        $filters['labels'] = (array)$filters['labels'];
        $filters['vendors'] = (array)$filters['vendors'];
        $filters['extra_fields'] = (array)$filters['extra_fields'];

        switch ($input['context']) {
            case 'all':
                $allProducts = $product->getAllProducts($filters, null, null, 0, 0);
                break;
            case 'last':
                $allProducts = $product->getLastProducts(null, null, $filters);
                break;
            case 'random':
                $allProducts = $product->getRandProducts(null, null, $filters);
                break;
            case 'bestseller':
                $allProducts = $product->getBestSellers(null, null, $filters);
                break;
            case 'label':
                $allProducts = $product->getProductLabel($input['labels'], null, null, $filters);
                break;
            case 'toprating':
                $allProducts = $product->getTopRatingProducts(null, null, $filters);
                break;
            case 'tophits':
                $allProducts = $product->getTopHitsProducts(null, null, $filters);
                break;
        }

        if (!empty($allProducts)) {
            addLinkToProducts($allProducts, 0, 1);		
            if (!$input['easy_products']) {
                $allProducts = $modelOfProductsFront->buildProductDataOnFly($allProducts, false, true);
            }

            foreach($allProducts as $key => $loopProduct) {
                if (!$input['easy_products']) {
                    $loopProductUserGroupPermissions = $loopProduct->getUsergroupPermissions();
                    $allProducts[$key]->isShowCartSection = $loopProduct->isShowCartSection();
                    $allProducts[$key]->permissions = $loopProductUserGroupPermissions;
                }
                
                if ($loopProductUserGroupPermissions->is_usergroup_show_product || !$input['is_security_enabled']) {
                    $rows[] = $loopProduct;
                }
            }

            $jshopConfig->is_enabled_usergroup_check_for_get_build_query_list_product_simple_list = $backupOfIsEnabledUsergroupCheckForGetBuildQueryListProductSimpleList;
            
            if (!$input['easy_products']) {
                transformDescrsTextsToModule($rows);
            }
        }
        
        $response = [
            'products' => $rows
        ];

        echo json_encode($response);
        die;
    }

    protected function getContextFilterName(string $filterName): string
    {
        $arr = [
            'all' => 'jshoping.list.front.product.fulllist',
            'last' => 'jshoping.list.front.product.last',
            'random' => 'jshoping.list.front.product.random',
            'bestseller' => 'jshoping.list.front.product.bestseller',
            'label' => 'jshoping.list.front.product.label',
            'toprating' => 'jshoping.list.front.product.toprating',
            'tophits' => 'jshoping.list.front.product.tophits'
        ];

        $result = $arr[$filterName] ?? '';
        return $result;
    }
    
    protected function createExtAttributeProduct($extProdIdFrom, $newCopyProductId)
    {
        if (!empty($extProdIdFrom)) {
            $_freeattribut = JSFactory::getModel('FreeAttribut');
            $modelOfProductMedia = JSFactory::getModel('ProductMedia');
            $modelOfNativeUploadsPriceAdmin = JSFactory::getModel('NativeUploadsPricesAdmin');

            $productModel = JSFactory::getModel('products');
            $modelOfProducts = JSFactory::getModel('products');
            $sourceProduct = JSFactory::getTable('product');
            $modelOfPrice = JSFactory::getModel('prices');
            $sourceProduct->load($extProdIdFrom);
            
            $extProduct = JSFactory::getTable('product');
            $tableFields = $extProduct->getFields();

            foreach ($tableFields as $fieldName => $fieldInfo) {
                $extProduct->$fieldName = $sourceProduct->$fieldName;
            }

            $extProduct->product_id = null;
            $extProduct->parent_id = $newCopyProductId;
            $isStored = $extProduct->store();

            $arrWithFreeAttrs = $_freeattribut->getProductFreeAttributes($extProdIdFrom);
            $modelOfProductMedia->saveMediaWithFiles($extProdIdFrom, $extProduct->product_id);
            $modelOfNativeUploadsPriceAdmin->duplicatePrices($extProdIdFrom, $extProduct->product_id);
            $_freeattribut->setFreeAttributesForProduct($extProduct->product_id, $arrWithFreeAttrs);

            $tables = ['images', 'relations', 'videos', 'files'];
            $array = $productModel->getProductAdditionalInfoById($extProdIdFrom, $tables);                            
            $productModel->copyProductAdditionalInfo($tables, $array, $extProduct->product_id);

            $plug = null;
            AdminFreeAttrsDefaultValuesMambot::getInstance()->onCopyProductEach($plug, $plug, $extProdIdFrom, $extProduct);

            if ($isStored) {
                $prices = $modelOfPrice->getProductPricesByProductId($extProdIdFrom);
                return $modelOfProducts->copyProductPrices($extProduct, $prices);  
            }         
        }        

        return 0;
    }
   
    private function addJsToEditProductPage() 
    {     
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration('
            document.addEventListener("DOMContentLoaded", function () {
                shopProductFreeAttribute.getSelectView();
            });
        ');
    }
	
	public function ajaxRedirectProductPage(){
		
        $product_id = JFactory::getApplication()->input->getInt('product_id');
        $error = JFactory::getApplication()->input->getInt('error');
        $msg = JFactory::getApplication()->input->getVar('msg');
        $product_attr_id = JFactory::getApplication()->input->getInt('product_attr_id');
		
		if(!$product_id && !$product_attr_id){
			$this->setRedirect("index.php?option=com_jshopping&controller=products", $msg);  
		}else{
			if($product_id){
				$url = '&product_id='.$product_id;
			}elseif($product_attr_id){
				$url = '&product_attr_id='.$product_attr_id;
			}
			$this->setRedirect("index.php?option=com_jshopping&controller=products&task=edit".$url, $msg);  
	
		}
		}  
           
}