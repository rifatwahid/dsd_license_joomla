<?php
/**
* @version      3.12.0 10.11.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelConfig extends JModelLegacy{
    
	public function deleteDisplayPricesById($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_config_display_prices` WHERE `id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		return $db->execute();
	}
      
	 public function getGeneralConfigLists(){
		$jshopConfig = JSFactory::getConfig();		
        $lists['languages'] = JHTML::_('select.genericlist', getAllLanguages(), 'defaultLanguage', 'class="form-select"', 'language', 'name', $jshopConfig->defaultLanguage);
		$config = new stdClass();
        include($jshopConfig->path.'lib/default_config.php');
		$_config_general = JSFactory::getModel("config_general");	
		$_config_other = JSFactory::getModel("config_other");	
		$lists['display_price_admin']=$_config_general->getSelect_DisplayPriceAdmin();
		$lists['display_price_front']=$_config_general->getSelect_DisplayPriceFront();		
		$lists['single_item_price']=$_config_general->getSelect_SingleItemPrice();	
		$lists['shop_mode']=$_config_other->getSelect_ShopMode();	
        $lists['template'] = getShopTemplatesSelect($jshopConfig->template);		
		return $lists;
	 }
	 
	 public function getGeneralConfigOtherConfig(){
		$jshopConfig = JSFactory::getConfig();		
        $config = new stdClass();
        include($jshopConfig->path.'lib/default_config.php');        
		return $other_config;
	 }
	 
	 public function getProductConfigLists(){		 
		$jshopConfig = JSFactory::getConfig();
		$_config_product = JSFactory::getModel('config_product');    
		$lists['displayprice_for_list_product']=$_config_product->getSelect_DisplayPriceForListProduct();
		$lists['category_sorting']=$_config_product->getSelect_CategorySorting();
		$lists['manufacturer_sorting']=$_config_product->getSelect_ManufacturerSorting();		
		$lists['product_sorting_direction']=$_config_product->getSelect_ProductSortingDirection();	
		$lists['attribut_dep_sorting_in_product']=$_config_product->getSelect_SortAttributesOfProductDependent();		
		$lists['attribut_nodep_sorting_in_product']=$_config_product->getSelect_SortAttributesOfProductIndependent();
		$lists['product_sorting']=$_config_product->getSelect_ProductSorting();
        if ($jshopConfig->admin_show_product_extra_field){
			$lists['product_list_display_extra_fields']=$_config_product->getSelect_ProductListDisplayExtraField();
			$lists['filter_display_extra_fields']=$_config_product->getSelect_FilterDisplayExtraFields();
			$lists['product_hide_extra_fields']=$_config_product->getSelect_ProductHideExtraFields();
			$lists['cart_display_extra_fields']=$_config_product->getSelect_CartDisplayExtraFields();
			$lists['pdf_display_extra_fields']=$_config_product->getSelect_PdfDisplayExtraFields();
			$lists['hide_extra_fields_images']=$_config_product->getSelect_HideExtraFieldsImages();
			$lists['mail_display_extra_fields']=$_config_product->getSelect_MailDisplayExtraFields();
        }
		$lists['units']=$_config_product->getSelect_Units();
		$lists['productlist_allow_buying']=$_config_product->getSelect_ProductlistAllowBuying();
		return $lists;
	 }
	 
	 public function getProductCheckoutLists(){
		$_config_checkout = JSFactory::getModel('config_checkout');    
		$lists['status']=$_config_checkout->getSelect_Status();
		$lists['default_country']=$_config_checkout->getSelect_DefaultCountry();		
		$lists['step_4_3']=$_config_checkout->getSelect_Step43();	
		return $lists;
	 }
	 
	 public function getShopFunctionsLists(){
		$_config_shopfunctions = JSFactory::getModel('config_shopfunctions');    
		$lists['shop_register_type']=$_config_shopfunctions->getSelect_ShopRegisterType();		
		return $lists; 
	 }
	 
	 public function getMediaLists(){
		$_config_shopfunctions = JSFactory::getModel('config_shopfunctions');    
		$lists['shop_register_type']=$_config_shopfunctions->getSelect_ShopRegisterType();		
		return $lists; 
	 }
	 
	 public function getConfigurationArrayCategoryProduct(){
		$array = array('allow_reviews_uploads','show_wishlist_button','productlist_allow_buying','not_redirect_in_cart_after_buy','show_plus_shipping_in_product','hide_product_not_avaible_stock','hide_buy_not_avaible_stock','show_sort_product','product_show_manufacturer_logo','product_show_weight',
                       'allow_reviews_prod', 'allow_reviews_only_registered', 'allow_reviews_only_buyers','hide_text_product_not_available', 'product_list_show_weight', 'product_list_show_manufacturer','show_product_code', 'show_product_list_filters',
                       'product_list_show_vendor','product_show_vendor','product_show_vendor_detail','product_show_button_back','product_list_show_product_code','attr_display_addprice', 'product_list_show_price_default');
		return $array;
	 }
	 
	 public function getConfigurationArrayCheckout(){
		$array = array(
            'hide_shipping_step', 'hide_payment_step',
            'sorting_country_in_alphabet','show_weight_order', 
            'discount_use_full_sum',"show_product_code_in_cart",'show_return_policy_in_email_order'
        );
		return $array;
	 }
	 
	private function getConfigurationPOST(){
		$post = JFactory::getApplication()->input->post->getArray();
		$post['count_products_to_page_tophits']=$post['count_products_to_page'];
		$post['count_products_to_page_toprating']=$post['count_products_to_page'];
		$post['count_products_to_page_label']=$post['count_products_to_page'];
		$post['count_products_to_page_bestseller']=$post['count_products_to_page'];
		$post['count_products_to_page_random']=$post['count_products_to_page'];
		$post['count_products_to_page_last']=$post['count_products_to_page'];	
		return $post;
	}
	
	private function setTab1Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
		$array = array('display_price_admin', 'display_price_front');
		foreach ($array as $key => $value) {
			if (!isset($post[$value])) $post[$value] = 0;
		}

		$result = [];
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		
		$result['product_price_precision'] = $post['product_price_precision'];
		return serialize($result);
	}
	
	private function setTab3Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		foreach($image_other_config as $k){
			$result[$k] = $post[$k];
		}
		$result['product_file_upload_count'] = $post['product_file_upload_count'];
		$result['product_image_upload_count'] = $post['product_image_upload_count'];
		$result['product_video_upload_count'] = $post['product_video_upload_count'];
		$result['video_allowed'] = implode(',', array_map('trim', explode(',', $post['video_allowed'])));
		$result['max_number_download_sale_file'] = $post['max_number_download_sale_file'];
		$result['max_day_download_sale_file'] = $post['max_day_download_sale_file'];
		return serialize($result);
	}	
	
	private function setTab5Configs(&$post){
		$vendor = JSFactory::getTable('vendor', 'jshop');
		$post = JFactory::getApplication()->input->post->getArray();
	
		$vendor->id = $post['vendor_id'];
		$vendor->bind($post);
		$vendor->main = 1;
		$vendor->store();
	}
	
	private function setTab6Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
		$array = $this->getConfigurationArrayCategoryProduct();
		foreach ($array as $key => $value) {
			if (!isset($post[$value])) $post[$value] = 0;
		}
		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		foreach($catprod_other_config as $k){
			$result[$k] = $post[$k];
		}
		return serialize($result);
	}	
	
	private function setTab7Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
		$array = $this->getConfigurationArrayCheckout();
		foreach($array as $key=>$value){
			if (!isset($post[$value])) $post[$value] = 0;
		}
		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		foreach($checkout_other_config as $k){
			$result[$k] = $post[$k];
		}
		return serialize($result);
	}	
	
	private function setTab8Configs(&$post){
		$jshopConfig = JSFactory::getConfig();		
        $array = array('without_shipping', 'without_payment', 'enable_wishlist', 'shop_user_guest','user_as_catalog', 'admin_show_product_basic_price','admin_show_attributes','admin_show_delivery_time','admin_show_product_video','admin_show_product_related','admin_show_product_sale_files','admin_show_product_demo_files','admin_show_product_bay_price','admin_show_product_basic_price', 'admin_show_product_labels', 'admin_show_product_extra_field','admin_show_freeattributes');
		$dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onSetTab8ConfigsAfterArray', array(&$array));
		foreach($array as $key => $value){
			if (!isset($post[$value])) $post[$value] = 0;
			echo "<br>".$value."=".$post[$value];
		}
		
		$post['without_shipping'] = intval(!$post['without_shipping']);
		$post['without_payment'] = intval(!$post['without_payment']);
		$post['display_preload'] = intval(!$post['display_preload']);

		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		foreach($adminfunction_other_config as $k){
			$result[$k] = $post[$k];
		}

		return serialize($result);
	}	

	private function setTab9Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
					
		foreach($fields_client_sys as $k=>$v){
			foreach($v as $v2){
				$post['field'][$k][$v2]['require'] = 1;
				$post['field'][$k][$v2]['display'] = 1;
			}
		}
		foreach($post['field'] as $k=>$v){
			foreach($v as $k2=>$v2){
				if (!$post['field'][$k][$k2]['display']){
					$post['field'][$k][$k2]['require'] = 0;
				}
			}
		} 
		return serialize($post['field']);     
	}	

	private function setTab10Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
		$array = array('savelog','savelogpaymentdata');
		foreach ($array as $key => $value) {
			if (!isset($post[$value])) $post[$value] = 0;
		}	

		$result = array();
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		foreach($other_config as $k){
			$result[$k] = $post[$k];
		}
		return serialize($result);
	}
	
	private function setTab11Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
		$dispatcher = \JFactory::getApplication();
		$array = [
			'client_allow_cancel_order', 'delivery_order_depends_delivery_product'
		];
		$dispatcher->triggerEvent('onBeforeSaveConfigTab11AfterArray', [&$array]);
		
		if (!$post['next_order_number']){
			unset($post['next_order_number']);
		}
		if (!$post['next_invoice_number']){
			unset($post['next_invoice_number']);
		}
		
		foreach($array as $key=>$value){
			if (!isset($post[$value])) $post[$value] = 0;
		}
		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}

		
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		foreach($checkout_other_config as $k){
			if ($k !== 'show_delivery_time_checkout' && 
				$k !== 'display_delivery_time_for_product_in_order_mail' &&
				$k !== 'show_delivery_date' && 
				$k !== 'display_agb'
			){
				$result[$k] = $post[$k];
			}
		}
		return serialize($result);
	}
	
	private function setTab12Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
        //$array = ['order_send_pdf_admin', 'order_send_pdf_client'];            
		if (!$post['next_order_number']){
			unset($post['next_order_number']);
		}            
		if (!$post['next_invoice_number']){
			unset($post['next_invoice_number']);
		}
		
		// foreach($array as $key=>$value){
		// 	if (!isset($post[$value])) $post[$value] = 0;
		// }
		
		$result = array();
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		foreach($pdf_hub_config as $k){
			$result[$k] = $post[$k];
		}        	
		return serialize($result);
	}
	
	private function setTab13Configs(&$post){
		$jshopConfig = JSFactory::getConfig();
	
		$array = [
			'storage_delete_uploads',
			'storage_delete_offers',
			'storage_delete_deliverynotes'
		];
		
		foreach($array as $key=>$value){
			if (!isset($post[$value])) $post[$value] = 0;
		}
	}
	
	public function saveConfigurations(){
		$jshopConfig = JSFactory::getConfig();
		$db = \JFactory::getDBO();
		$tab = JFactory::getApplication()->input->getVar('tab');
		$dispatcher = \JFactory::getApplication();		
		$post = $this->getConfigurationPOST();
		$extconf = array('imageheader'=>'header.jpg', 'imagefooter'=>'footer.jpg');
		$dispatcher->triggerEvent('onBeforeSaveConfig', array(&$post, &$extconf));
		
		if (isset($post['order_suffix']) && $post['order_suffix'] != '' && substr($post['order_suffix'], -1) != '-') {
            $post['order_suffix'] .= '-';
        }

        if (isset($post['delivery_note_suffix']) && $post['delivery_note_suffix'] != '' && substr($post['delivery_note_suffix'], -1) != '-') {
            $post['delivery_note_suffix'] .= '-';
        }		

		if ($tab == 1) {
			$post['other_config'] = $this->setTab1Configs($post);
			$post['other_config'] = $this->setTab10Configs($post);
		}
		if ($tab == 3) $post['other_config'] = $this->setTab3Configs($post);
		if ($tab == 5) $this->setTab5Configs($post);
		if ($tab == 6) $post['other_config'] = $this->setTab6Configs($post);
		if ($tab == 7) $post['other_config'] = $this->setTab7Configs($post);
		//shop function
		if ($tab == 8) $post['other_config'] = $this->setTab8Configs($post);
        if ($tab == 9) $post['fields_register'] = $this->setTab9Configs($post);
		//if ($tab == 10) $post['other_config'] = $this->setTab10Configs($post);
		if ($tab == 11) $post['other_config'] = $this->setTab11Configs($post);
		if ($tab == 12) $post['other_config'] = $this->setTab12Configs($post);		
		if ($tab == 13) $this->setTab13Configs($post);		

        if ($tab != 4){
		    $config = new jshopConfig($db);
		    $config->id = $jshopConfig->load_id;
		    if (!$config->bind($post)) {
			    \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			    $this->setRedirect('index.php?option=com_jshopping&controller=config');
			    return 0;
		    }
		    $config->transformPdfParameters();			
		    if (!$config->store()) {
			    \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')." ".$config->_error,'error');
			    $this->setRedirect('index.php?option=com_jshopping&controller=config');
			    return 0;
		    }            
        }

		if (isset($_FILES['header'])){
			if ($_FILES['header']['size']){
				@unlink($jshopConfig->path."images/".$extconf['imageheader']);
				move_uploaded_file($_FILES['header']['tmp_name'], $jshopConfig->path."images/".$extconf['imageheader']);
			}
		}
	
		if (isset($_FILES['footer'])){
			if ($_FILES['footer']['size']){
				@unlink($jshopConfig->path."images/".$extconf['imagefooter']);
				move_uploaded_file($_FILES['footer']['tmp_name'], $jshopConfig->path."images/".$extconf['imagefooter']);
			}
		}
        
        if (isset($post['update_count_prod_rows_all_cats']) && $tab==6 && $post['update_count_prod_rows_all_cats']){
            $count_products_to_page = intval($post['count_products_to_page']);
			$_categories = JSFactory::getModel("categories");
			$_categories->setCountToPage($count_products_to_page);
            $_manufacturers = JSFactory::getModel("manufacturers");
			$_manufacturers->setCountToPage($count_products_to_page);
        }

        $dispatcher->triggerEvent('onAfterSaveConfig', array());
	}
	
	public function getOtherConfigLists(){
		$jshopConfig = JSFactory::getConfig();
        $config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');		
		$_config_other = JSFactory::getModel("config_other");		
		$lists['tax_rule_for']=$_config_other->getSelect_TaxRuleFor();
		$lists['shop_mode']=$_config_other->getSelect_ShopMode();
		return $lists;
	}
	
	public function getOrdersConfigLists(){		
		$_config_orders = JSFactory::getModel("config_orders");		
		$lists['status']=$_config_orders->getSelect_Status();		
		return $lists;
	}
	
	public function getCurrencyConfigLists(){
		$_config_currency = JSFactory::getModel("config_currency");		
		$lists['currencies']=$_config_currency->getSelect_Currencies();
		$lists['format_currency']=$_config_currency->getSelect_FormatCurrency();		
		return $lists;
	}
	
	public function getOrderForPreviewPdf(){
		$order = JSFactory::getTable('order', 'jshop');
        $order->firma_name = "Firma";
        $order->f_name = "Fname";
        $order->l_name = 'Lname';
        $order->street = 'Street';
        $order->zip = "Zip"; 
        $order->city = "City";
        $order->country = "Country";
        $order->order_number = outputDigit(0,8);
        $order->order_date = strftime($jshopConfig->store_date_format, time());
        $order->products = array();
        $prod = new stdClass();
        $prod->product_name = "Product name";
        $prod->product_ean = "12345678";
        $prod->product_quantity = 1;
        $prod->product_item_price = 125;
        $prod->product_tax = 19;
        $order->products[] = $prod;
        $order->order_subtotal = 125;
        $order->order_shipping = 20;        
        $display_price = $jshopConfig->display_price_front;
        if ($display_price==0){
            $order->display_price = 0;
            $order->order_tax_list = array(19 => 23.15);
            $order->order_total = 145;
        }else{
            $order->display_price = 1;
            $order->order_tax_list = array(19 => 27.55);
            $order->order_total = 172.55;
        }
		return $order;
	}
	
	public function getStorageConfigLists(){			
		$_config_storage = JSFactory::getModel("config_storage");		
		$lists['storage_delete_uploads']=$_config_storage->getDeleteUploadsStatuses_select();		
		$lists['storage_delete_offers']=$_config_storage->getDeleteOffersStatuses_select();		
		$lists['storage_delete_deliverynotes']=$_config_storage->getDeleteDeliverynotesStatuses_select();		
		if (isSmartEditorEnabled()) {
			$lists['storage_delete_editor_temporary_folder']=$_config_storage->getDeleteEditorTemporaryFolderStatuses_select();//Delete Editor temporary folde		
			$lists['storage_delete_editor_print_files']=$_config_storage->getDeleteEditorPrintFilesStatuses_select();//Delete Editor print files
		}
		return $lists;
	}

}
?>