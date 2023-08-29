<?php
/**
* @version      4.9.2 14.03.2015
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

error_reporting(error_reporting() & ~E_NOTICE);

require_once __DIR__ . '/shop_core_file.php'; 

class JSFactory
{
    public static function getConfig()
    {
        static $config;

        if (!is_object($config)) {
            JPluginHelper::importPlugin('jshopping');
            $dispatcher = \JFactory::getApplication();
            $db = \JFactory::getDBO();
            $config = new jshopConfig($db);
            $pathToUserConfig = __DIR__ . '/user_config.php';

            include __DIR__ . '/default_config.php';

            if (file_exists($pathToUserConfig)) {
                include $pathToUserConfig;
            }

            $dispatcher->triggerEvent('onBeforeLoadJshopConfig', [$config]);
            $config->load($config->load_id);
            $config->loadOtherConfig();
            $config->loadCurrencyValue();
            $config->loadFrontLand();
            $config->loadLang();

            list($config->pdf_header_width, $config->pdf_header_height, $config->pdf_footer_width, $config->pdf_footer_height) = explode(':', $config->pdf_parameters);
            if (!$config->allow_reviews_prod) {
                unset($config->sorting_products_field_select['5']);
                unset($config->sorting_products_name_select['5']);
                unset($config->sorting_products_field_s_select['5']);
                unset($config->sorting_products_name_s_select['5']);
            }

            if ($config->user_as_catalog) {
                $config->show_buy_in_category = 0;
            }

            $config->controler_buy_qty = 0;
            if ($config->hide_product_not_avaible_stock || $config->hide_buy_not_avaible_stock) {
                $config->controler_buy_qty = 1;
            }

            $config->display_price_front_current = $config->getDisplayPriceFront();// 0 - Brutto, 1 - Netto

            if (empty($config->template)) {
                $config->template = 'default';
            }

            $config->show_product_code_in_order = 0;
            if ($config->show_product_code || $config->show_product_code_in_cart) {
                $config->show_product_code_in_order = 1;
            }

            if ($config->admin_show_vendors == 0) {
                $config->vendor_order_message_type = 0; //0 - none, 1 - mesage, 2 - order if not multivendor
                $config->admin_not_send_email_order_vendor_order = 0;
                $config->product_show_vendor = 0;
                $config->product_show_vendor_detail = 0;
            }

            $config->copyrightText = '<span id="mxcpr"><a target="_blank" href="https://www.webdesigner-profi.de/">Copyright MAXXmarketing Webdesigner GmbH</a></span>';
            $config->image_cut = 0;
            $config->image_fill = 0;
            
            if ($config->image_resize_type == 0) {
                $config->image_cut = 1;
                $config->image_fill = 2;
            } elseif ($config->image_resize_type == 1) {
                $config->image_cut = 0;
                $config->image_fill = 2;
            }

            if (!$config->tax) {
                $config->show_tax_in_product = 0;
                $config->show_tax_product_in_cart = 0;
                $config->hide_tax = 1;
            }

            if (!$config->admin_show_delivery_time) {
                $config->delivery_times_on_product_page = 0;
                $config->delivery_times_on_product_listing = 0;

                $config->show_delivery_time_checkout = 0;
                $config->show_delivery_time_step5 = 0;
                $config->display_delivery_time_for_product_in_order_mail = 0;
                $config->show_delivery_date = 0;
            }

			if (!$config->admin_show_product_basic_price) {
                $config->cart_basic_price_show = 0;
            }

            $config->use_ssl = intval($config->use_ssl);

            $dispatcher->triggerEvent('onLoadJshopConfig', [&$config]);
        }

        $config->updateHeaderAndFooterPdfsNames();
        return $config;
    }

    public static function getUserShop($isUseCache = true)
    {
        static $usershop;

        if (!is_object($usershop) || !$isUseCache) {
            $user = JFactory::getUser();
            $db = \JFactory::getDBO();
            require_once JPATH_ROOT . '/components/com_jshopping/tables/usershop.php';

            $usershop = new jshopUserShop($db);
            $usershop->percent_discount = 0;

            if(!empty($user->id)) {
                if(!$usershop->isUserInShop($user->id)) {
                    $usershop->addUserToTableShop($user);
                }

                if (empty($usershop->address_id)) {
                    JSFactory::getModel('UserAddressesFront')->setDefaultAddressIfNotExistsByUserId($user->id);
                }

                $usershop->load($user->id);
                $usershop->percent_discount = $usershop->getDiscount();
            }

            \JFactory::getApplication()->triggerEvent('onAfterGetUserShopJSFactory', [&$usershop]);
        }

        return $usershop;
    }

    public static function getUserShopGuest()
    {
        static $userguest;

        if (!is_object($userguest)) {
            require_once JPATH_ROOT . '/components/com_jshopping/models/userguest.php';

            $userguest = new jshopUserGust();
            $userguest->load();
            $userguest->percent_discount = 0;
            \JFactory::getApplication()->triggerEvent('onAfterGetUserShopGuestJSFactory', [&$userguest]);
        }

        return $userguest;
    }
    
    public static function getUser()
    {
        $user = JFactory::getUser();

        if (!empty($user->id)) {
            return JSFactory::getUserShop();
        }

        return JSFactory::getUserShopGuest();
    }

    public static function loadCssFiles()
    {
        static $load;

        $jshopConfig = JSFactory::getConfig();

        $jshopConfig = JSFactory::getConfig();
        $nameOfActiveTmpl = getSiteTemplateActive();
		$pathToJoomlaTmpl = JPATH_ROOT . "/templates/{$nameOfActiveTmpl}/css/custom.css";
		
        if (!$jshopConfig->load_css) {
            return 0;
        }

        if (!$load) {
            $document = JFactory::getDocument();
            $jshopConfig = JSFactory::getConfig();

            if ($jshopConfig->shop_mode) {
                $document->addStyleSheet(JURI::root() . "components/com_jshopping/templates/{$jshopConfig->template}/css/index.css");
            } else {
                $document->addStyleSheet(JURI::root() . "components/com_jshopping/templates/{$jshopConfig->template}/css/index.min.css");
            }
			if(file_exists($pathToJoomlaTmpl)) {
				$document->addStyleSheet(JURI::root() . "/templates/{$nameOfActiveTmpl}/css/custom.css"); 
			}
            $load = 1;
        }			
    }

    public static function loadJsFiles(){
        static $load;

        if (!$load) {
            $jshopConfig = JSFactory::getConfig();
            $document = JFactory::getDocument();

            JHtml::_('behavior.core');
            JHtml::_('bootstrap.framework');

            if ($jshopConfig->load_javascript){

                if ($jshopConfig->shop_mode) {
                    $document->addScript(JURI::root().'components/com_jshopping/js/src/jquery/jquery.media.js');
                    $document->addScript(JURI::root().'components/com_jshopping/js/src/deprecated.js');
                    $document->addScript(JURI::root().'components/com_jshopping/js/src/index.js', [], [
                        'type' => 'module'
                    ]);
                } else {
                    $document->addScript(JURI::root().'components/com_jshopping/js/dist/index.min.js');
                }

                $document->addScript(JURI::root().'components/com_jshopping/js/src/jquery/jquery-ui.js');
            }

            $load = 1;
        }
    }

    public static function loadJsFilesLightBox()
    {
        static::loadJsFilesLightBoxNew();
    }

    public static function loadJsFilesLightBoxNew()
    {
        static $load;

        $jshopConfig = JSFactory::getConfig();
        if (!$jshopConfig->load_jquery_lightbox) {
            return 0;
        }

        if (!$load) {
            $document = JFactory::getDocument();
            if ($jshopConfig->shop_mode) {
                $document->addScript(JURI::root() . 'components/com_jshopping/js/src/jquery/photoswipe.min.js');
                $document->addScript(JURI::root() . 'components/com_jshopping/js/src/jquery/photoswipe-ui-default.min.js');
            }
            $load = 1;
        }        
    }

    public static function loadJsFilesLightBoxOld()
    {
        static $load;

        $jshopConfig = JSFactory::getConfig();

        if (!$jshopConfig->load_jquery_lightbox) {
            return 0;
        }

        if (!$load) {
            $document = JFactory::getDocument();
            $document->addScript(JURI::root() . 'components/com_jshopping/js/src/jquery/jquery.lightbox-0.5.pack.js');
            $document->addStyleSheet(JURI::root() . 'components/com_jshopping/css/jquery.lightbox-0.5.css');
            $document->addScriptDeclaration('function initJSlightBox(){
                jQuery("a.lightbox").lightBox({
                    imageLoading: "' . JURI::root() . 'components/com_jshopping/images/loading.gif",
                    imageBtnClose: "' . JURI::root() . 'components/com_jshopping/images/close.gif",
                    imageBtnPrev: "' . JURI::root() . 'components/com_jshopping/images/prev.gif",
                    imageBtnNext: "' . JURI::root() . 'components/com_jshopping/images/next.gif",
                    imageBlank: "' . JURI::root() . 'components/com_jshopping/images/blank.gif",
                    txtImage: "' . JText::_('COM_SMARTSHOP_IMAGE') . '",
                    txtOf: "' . JText::_('COM_SMARTSHOP_OF') . '"
                });
            }
            jQuery(function() { initJSlightBox(); });');
            $load = 1;
        }
    } 
    
    public static function reloadConfigFieldTLF()
    {
        $jshopConfig = JSFactory::getConfig();
        $reload = [
            'user_field_client_type',
            'user_field_title',
            'sorting_products_name_select',
            'sorting_products_name_s_select',
            'count_product_select'
        ];

        foreach($reload as $field) {
            $tmp = $jshopConfig->$field;

            foreach($tmp as $k=>$v){
                if (defined($v)) {
                    $tmp[$k] = constant($v);
                }
            }

            $jshopConfig->$field = $tmp;
        }
    }

    public static function loadLanguageFile($langtag = '')
    {
		JFactory::getLanguage()->load('com_jshopping');
    }

    public static function loadExtLanguageFile($extname, $langtag = '')
    {
        $lang = JFactory::getLanguage();

        if ($langtag == '') {
            $langtag = $lang->getTag();
        }

        $pathToLangTag = JPATH_ROOT . "/components/com_jshopping/lang/{$extname}/{$langtag}.php";
        $pathToLangFiles = JPATH_ROOT . "/components/com_jshopping/lang/{$extname}/en-GB.php";

        if(file_exists($pathToLangTag)) {
            require_once $pathToLangTag;
        } elseif (file_exists($pathToLangFiles)) {
            require_once $pathToLangFiles;
        }
            
		JFactory::getLanguage()->load('com_jshopping');
    }

    public static function loadAdminLanguageFile($langtag = '')
    {
		JFactory::getLanguage()->load('com_jshopping');
    }

    public static function loadExtAdminLanguageFile($extname, $langtag = '')
    {
        $lang = JFactory::getLanguage();

        if ($langtag == '') {
            $langtag = $lang->getTag();
        }

        $pathToLangTag = JPATH_ROOT . "/administrator/components/com_jshopping/lang/{$extname}/{$langtag}.php";
        $pathToLangFile = JPATH_ROOT . "/administrator/components/com_jshopping/lang/{$extname}/en-GB.php";

        if(file_exists($pathToLangTag)) {
            require_once $pathToLangTag;
        } else {
            require_once $pathToLangFile;
        }
    }

    public static function getLang($langtag = "")
    {
        static $ml;

        if (!is_object($ml) || $langtag != '') {
            $jshopConfig = JSFactory::getConfig();
            $ml = new multiLangField();

            if ($langtag == '') {
                $langtag = $jshopConfig->getLang();
            }

            $ml->setLang($langtag);
            \JFactory::getApplication()->triggerEvent('onAfterGetLangJSFactory', [&$ml, &$langtag]);
        }

        return $ml;
    }

    public static function getReservedFirstAlias()
    {
        static $alias;

        if (!is_array($alias)) {
            jimport('joomla.filesystem.folder');
            $files = JFolder::files(JPATH_ROOT . '/components/com_jshopping/controllers');
            $alias = [];

            foreach($files as $file) {
                $alias[] = str_replace('.php', '', $file);
            }
        }

        return $alias;
    }

    public static function getAliasCategory()
    {
        static $alias;
        if (!is_array($alias)) {
            $db = \JFactory::getDBO();
            $lang = JSFactory::getLang();
            $dbquery = "select category_id as id, `{$lang->get('alias')}` as alias from #__jshopping_categories where `{$lang->get('alias')}` != ''"; 
            $db->setQuery($dbquery);
            $rows = $db->loadObjectList();
            $alias = [];

            foreach($rows as $row) {
                $alias[$row->id] = $row->alias;
            }
            unset($rows);
        }

        return $alias;
    }

    public static function getAliasManufacturer()
    {
        static $alias;

        if (!is_array($alias)) {
            $db = \JFactory::getDBO();
            $lang = JSFactory::getLang();
            $dbquery = "select manufacturer_id as id, `{$lang->get('alias')}` as alias from #__jshopping_manufacturers where `{$lang->get('alias')}` != ''";
            $db->setQuery($dbquery);
            $rows = $db->loadObjectList();
            $alias = [];

            foreach($rows as $row) {
                $alias[$row->id] = $row->alias;
            }
            unset($rows);
        }

        return $alias;
    }

    public static function getAliasProduct()
    {
        static $alias;

        if (!is_array($alias)){
            $db = \JFactory::getDBO();
            $lang = JSFactory::getLang();
            $dbquery = "select product_id as id, `{$lang->get('alias')}` as alias from #__jshopping_products where `{$lang->get('alias')}` != ''"; 
            $db->setQuery($dbquery);
            $rows = $db->loadObjectList();
            $alias = [];

            foreach($rows as $k => $row) {
                $alias[$row->id] = $row->alias;
                unset($rows[$k]);
            }
            unset($rows);
        }
        
        return $alias;
    }

    public static function getAllAttributes($resformat = 0,$attr_id=0, $attr_ids=[])
    {
        //static $attributes;
        if (!isset($attributes) || !is_array($attributes)) {
            $_attrib = JSFactory::getTable('attribut', 'jshop');
			$ind_attributes = $_attrib->getAllAttributes(1,$attr_id,1, $attr_ids);
			$dep_attributes = $_attrib->getAllAttributes(1,$attr_id,2, $attr_ids);
			$attributes = array_merge($dep_attributes,$ind_attributes);
        }
		if ($resformat == 0) {
            return $attributes;
        }

        if ($resformat == 1) {
            $attributes_format1 = [];

            foreach($attributes as $v) {
                $attributes_format1[$v->attr_id] = $v;
            }

            return $attributes_format1;
        }

        if ($resformat == 2) {
            $attributes_format2 = [
                'independent' => [],
                'dependent' => []
            ];
			
			$ind_attributes = $_attrib->getAllAttributes(1,$attr_id,1, $attr_ids);
			$dep_attributes = $_attrib->getAllAttributes(1,$attr_id,2, $attr_ids);
			if(isset($ind_attributes)){
				foreach($ind_attributes as $v) {
					$attributes_format2['independent'][$v->attr_id] = $v;
				}
			}
			if(isset($dep_attributes)){
				foreach($dep_attributes as $v) {
					$attributes_format2['dependent'][$v->attr_id] = $v;
				}
			}

            return $attributes_format2;
        }
    }

    public static function getAllUnits()
    {
        static $rows;

        if (!is_array($rows)){
            $_unit = JSFactory::getTable('unit', 'jshop');
            $rows = $_unit->getAllUnits();
        }

        return $rows;
    }
    
    public static function getAllTaxesOriginal()
    {
        static $rows;

        if (!is_array($rows)) {
            $_tax = JSFactory::getTable('tax', 'jshop');
            $_rows = $_tax->getAllTaxes();
            $rows = [];

            foreach($_rows as $row) {
                $rows[$row->tax_id] = $row->tax_value;
            }
        }

        return $rows;
    }
    
    public static function getAllTaxes($isUseCache = true)
    {
        static $rows;

        if (!is_array($rows) || !$isUseCache){
            $jshopConfig = JSFactory::getConfig();
            $dispatcher = \JFactory::getApplication();
            $_tax = JSFactory::getTable('tax', 'jshop');
			$_taxextadditional = JSFactory::getTable('taxextadditional', 'jshop');
			$additional_taxes=$_taxextadditional->getAllAdditionalTaxes();
            $rows = JSFactory::getAllTaxesOriginal();

            if ($jshopConfig->use_extend_tax_rule) {
                $country_id = 0;
                $adv_user = JSFactory::getUserShop();
				JSFactory::getModel('UsersFront')->checkClientType($adv_user);
				
                $country_id = $adv_user->country ?? 0;

                if ($adv_user->user_id && $jshopConfig->tax_on_delivery_address && isset($adv_user->delivery_adress) && $adv_user->delivery_adress && isset($adv_user->d_country) && $adv_user->d_country){
                    $country_id = $adv_user->d_country;
                }

                $client_type = $adv_user->client_type ?? '';
                $enter_tax_id = $adv_user->tax_number ?? '';

                if (!$country_id) {
                    $adv_user = JSFactory::getUserShopGuest();
                    $country_id = $adv_user->country ?? 0;

                    if ($adv_user->user_id && $jshopConfig->tax_on_delivery_address && $adv_user->delivery_adress && $adv_user->d_country){
                        $country_id = $adv_user->d_country;
                    }

                    $client_type = $adv_user->client_type ?? '';
                    $enter_tax_id = $adv_user->tax_number ?? '';
                }

                if ($country_id) {
                    $_rowsext = $_tax->getExtTaxes();
                    $dispatcher->triggerEvent('beforeGetAllTaxesRowsext', [&$_rowsext, &$country_id, &$adv_user, &$rows]);
					$rows_tmp=[];
                    foreach($_rowsext as $v) {
                        if (in_array($country_id,(array)$v->countries)) {
                            if ($jshopConfig->ext_tax_rule_for == 1) {
                                if ($enter_tax_id){
                                    $rows[$v->tax_id] = $v->firma_tax;
                                } else {
                                    $rows[$v->tax_id] = $v->tax;
                                }    
                            } else {
                                if ($client_type == 2) {
                                    $rows[$v->tax_id] = $v->firma_tax;
                                } else {
                                    $rows[$v->tax_id] = $v->tax;
                                }
                            } 
							/*foreach ($additional_taxes as $key=>$value){
										$addtaxname="additional_tax_".$value->id;
										$rows[$addtaxname="additional_tax_".$value->id]=$v->$addtaxname;
										$rows_tmp[$addtaxname="additional_tax_".$value->id]=$v->$addtaxname;
									}*/
                        }
                    }

					$post = JFactory::getApplication()->input->post->getArray();//$post = JFactory::getApplication()->input->get('post');							
					$_rows = $_tax->getAllTaxes();
					$rows = [];
					foreach($_rows as $row){
						$rows[$row->tax_id] = $row->tax_value;
					}
					foreach($rows_tmp as $key=>$value){
						$rows[$key]=$value;
					}
					unset($_rows);
					
					$result = unserialize($jshopConfig->other_config);
					$shippingAddress_id =  $post['shippingAddress_id'] ?? 0;	
					$billingAddress_id = $post['billingAddress_id'] ?? 0;
					if(isset($_REQUEST['controller']) && $_REQUEST['controller'] == 'qcheckout' && !empty($post) && ((isset($result['tax_on_delivery_address']) && $result['tax_on_delivery_address'] == 1 && $shippingAddress_id) || (!isset($result['tax_on_delivery_address']) || $result['tax_on_delivery_address'] == 0) && $billingAddress_id)){	
						if(isset($result['tax_on_delivery_address']) && $result['tax_on_delivery_address'] == 1){
							$client_state = getState($shippingAddress_id);	
							$country_id = getCountry($shippingAddress_id);
						}else{				
							$client_state = getState($billingAddress_id);	
							$country_id = getCountry($billingAddress_id);
						}		
					}elseif(isset($_REQUEST['controller']) && $_REQUEST['controller'] == 'qcheckout' && !empty($post)){ 
						
						$client_state = $post['state'] ?? '';	
						$country_id = $post['country'] ?? '';
					}else{	
						$client_state = $adv_user->state;
						$country_id = $adv_user->country;
						if (!$country_id){
							$country_id = $jshopConfig->default_country;
						}
					}
					$state_val=$_GET["id"];
					if (isset($state_val)){
						$client_state = $_GET["state"];
						$country_id = $_GET["country_id"];
					}
					$client_type = $adv_user->client_type;
					$enter_tax_id = $adv_user->tax_number != "";
					$state = JTable::getInstance('state', 'jshop');

					if ($country_id)
					{
						$_rowsext = $_tax->getExtTaxes();
						
						foreach($_rowsext as $key=>$data)
						{				
							if ($data->zones_states)
							{
								$state_array = array();
								$_states = array();
								$states = unserialize($data->zones_states);
								if ($states)
								{
									foreach($states as $id_country=>$_state)
									{
										foreach($state->getStates($id_country) as $data_state)
										{
											$_states[$data_state->state_id]=$data_state->name;
										}
										foreach($_state as $id_state)
										{
											$state_array[$id_country][]=trim($_states[$id_state]);
										}
									}
								}					
								$_rowsext[$key]->states = isset($state_array[$country_id]) ? $state_array[$country_id] : [];
							} else {
								$_rowsext[$key]->states = array();
							}
							
							//IF NO STATES FOR THIS COUNTRY							
							if (in_array($country_id,(array)$data->countries) && (!count($data->states)))
							{
								if ($jshopConfig->ext_tax_rule_for==1)
								{
									if ($enter_tax_id)
									{
										$rows[$data->tax_id] = $data->firma_tax;
									}else{
										$rows[$data->tax_id] = $data->tax;
									}    
								}else{
									if ($client_type==2)
									{
										$rows[$data->tax_id] = $data->firma_tax;
									}else{
										$rows[$data->tax_id] = $data->tax;
									}
								}
							}
							
						}

						foreach($_rowsext as $key=>$data)
						{
							if(!empty($data->states) && count($data->states))
							{
								if(in_array($country_id,(array)$data->countries) && in_array(trim($client_state),(array)$data->states))
								{
									if ($jshopConfig->ext_tax_rule_for==1)
									{
										if ($enter_tax_id)
										{
											$rows[$data->tax_id] = $data->firma_tax;
										} else {
											$rows[$data->tax_id] = $data->tax;
										}
									} else {
										if ($client_type==2)
										{
											$rows[$data->tax_id] = $data->firma_tax;
										}else{
											$rows[$data->tax_id] = $data->tax;
										}
									}
								
							
									foreach ($additional_taxes as $key=>$value){
										$addtaxname="additional_tax_".$value->id;
										$rows[$addtaxname="additional_tax_".$value->id][$data->tax_id]=$data->$addtaxname;
									}
								}
								
							}
						}
						
					}

                    $dispatcher->triggerEvent('afterGetAllTaxesRowsext', [&$_rowsext, &$country_id, &$adv_user, &$rows]);
                    unset($_rowsext);
                }
            }

            $dispatcher->triggerEvent('afterGetAllTaxes', [&$rows]);
        }	
        return $rows;
    }

    public static function getAllManufacturer()
    {
        static $rows;

        if (!is_array($rows)) {
            $db = \JFactory::getDBO();
            $lang = JSFactory::getLang();
            $dispatcher = \JFactory::getApplication();
            $adv_result = "manufacturer_id as id, `{$lang->get('name')}` as name, manufacturer_logo, manufacturer_url";
            $dispatcher->triggerEvent('onBeforeQueryGetAllManufacturer', [&$adv_result]);
            $query = "select {$adv_result} from #__jshopping_manufacturers where manufacturer_publish = '1'";
            $db->setQuery($query);
            $_rows = $db->loadObjectList();
            $rows = [];

            foreach($_rows as $row) {
                $rows[$row->id] = $row;
            }

            unset($_rows);
        }

        return $rows;
    }

    public static function getMainVendor()
    {
        static $row;

        if (!isset($row)) {
            $row = JSFactory::getTable('vendor', 'jshop');
            $row->loadMain();
        }

        return $row;
    }

    public static function getAllVendor()
    {
        static $rows;

        if (!is_array($rows)) {
            $db = \JFactory::getDBO();
            $query = 'select id, shop_name, l_name, f_name from #__jshopping_vendors';
            $db->setQuery($query);
            $_rows = $db->loadObjectList();
            $rows = [];
            $mainvendor = JSFactory::getMainVendor();
            $rows['0'] = $mainvendor;

            foreach($_rows as $row) {
                $rows[$row->id] = $row;
            }

            unset($_rows);
        }

        return $rows;
    }

    public static function getAllDeliveryTime()
    {
        static $rows;

        if (!is_array($rows)){
            $db = \JFactory::getDBO();
            $lang = JSFactory::getLang();
            $query = "select id, `{$lang->get('name')}` as name from #__jshopping_delivery_times";
            $db->setQuery($query);
            $_rows = $db->loadObjectList();
            $rows = [];

            foreach($_rows as $row) {
                $rows[$row->id] = $row->name;
            }

            unset($_rows);
        }

        return $rows;
    }

    public static function getAllDeliveryTimeDays()
    {
        static $rows;

        if (!is_array($rows)) {
            $db = \JFactory::getDBO();
            $lang = JSFactory::getLang();
            $query = 'select id, days from #__jshopping_delivery_times';
            $db->setQuery($query);
            $_rows = $db->loadObjectList();
            $rows = [];

            foreach($_rows as $row) {
                $rows[$row->id] = $row->days;
            }

            unset($_rows);
        }
        
        return $rows;
    }

    public static function getAllProductExtraField()
    {
        static $list;

        if (!is_array($list)) {
            $productfield = JSFactory::getTable('productfield', 'jshop');
            $list = $productfield->getList();
        }

        return $list;
    }

    public static function getAllProductExtraFieldValue()
    {
        static $list;

        if (!is_array($list)) {
            $productfieldvalue = JSFactory::getTable('productfieldvalue', 'jshop');
            $list = $productfieldvalue->getAllList(1);
        }

        return $list;
    }

    public static function getAllProductExtraFieldValueDetail()
    {
        static $list;

        if (!is_array($list)) {
            $productfieldvalue = JSFactory::getTable('productfieldvalue', 'jshop');
            $list = $productfieldvalue->getAllList(2);
        }

        return $list;
    }

    public static function getAllProductExtraFieldValueDetails()
    {
        static $list;

        if (!is_array($list)) {
            $productfieldvalue = JSFactory::getTable('productfieldvalue', 'jshop');
            $list = $productfieldvalue->getAllListDetails(2);
        }

        return $list;
    }

    public static function getDisplayListProductExtraFieldForCategory($cat_id)
    {
        static $listforcat;

        if (!isset($listforcat[$cat_id])) {
            $fields = [];
            $list = JSFactory::getAllProductExtraField();

            foreach($list as $val) {
                if ($val->allcats) {
                    $fields[] = $val->id;
                } else {
                    if (in_array($cat_id,(array)$val->cats)) {
                        $fields[] = $val->id;
                    }
                }
            }

            $jshopConfig = JSFactory::getConfig();
            $config_list = $jshopConfig->getProductListDisplayExtraFields();

            foreach($fields as $k=>$val) {
                if (!in_array($val,(array)$config_list)) {
                    unset($fields[$k]);
                }
            }

            $listforcat[$cat_id] = $fields;
        }

        return $listforcat[$cat_id];
    }

    public static function getDisplayFilterExtraFieldForCategory($cat_id)
    {
        static $listforcat;

        if (!isset($listforcat[$cat_id])) {
            $fields = [];
            $list = JSFactory::getAllProductExtraField();

            foreach($list as $val) {
                if ($val->allcats) {
                    $fields[] = $val->id;
                } else {
                    if (in_array($cat_id,(array)$val->cats)) {
                        $fields[] = $val->id;
                    }
                }
            }
            
            $jshopConfig = JSFactory::getConfig();
            $config_list = $jshopConfig->getFilterDisplayExtraFields();

            foreach($fields as $k => $val) {
                if (!in_array($val,(array)$config_list)) {
                    unset($fields[$k]);
                }
            }

            $listforcat[$cat_id] = $fields;
        }

        return $listforcat[$cat_id];
    }

    public static function getAllCurrency()
    {
        static $list;

        if (!is_array($list))
        {
            $currency =JSFactory::getTable('currency', 'jshop');
            $_list = $currency->getAllCurrencies();
            $list = [];

            foreach($_list as $row) {
                $list[$row->currency_id] = $row;
            }
        }

        return $list;
    }

    public static function getShippingExtList($for_shipping = 0)
    {
        static $list;

        if (!is_array($list)) {
            $jshopConfig = JSFactory::getConfig();
            $path = $jshopConfig->path . 'shippings';
            $shippingext = JSFactory::getTable('shippingext', 'jshop');
            $_list = $shippingext->getList(1);
            $list = [];

            foreach($_list as $row) {
                $extname = $row->alias;
                $filepatch = "{$path}/{$extname}/{$extname}.php";

                if (file_exists($filepatch)) {
                    include_once($filepatch);
                    $row->exec = new $extname();
                    $list[$row->id] = $row;
                } else {
                    \JFactory::getApplication()->enqueueMessage("Load ShippingExt '{$extname}' error.",'error');
                }
            }
        }

        if ($for_shipping == 0) {
            return $list;
        }

        $returnlist = [];

        foreach($list as $row) {
            $sm = [];

            if ($row->shipping_method != '') {
                $sm = unserialize($row->shipping_method);
            }

            if(!isset($sm[$for_shipping])) {
                $sm[$for_shipping] = 1;
            }

            if ($sm[$for_shipping] !== '0') {
                $returnlist[] = $row;
            }
        }

        return $returnlist;
    }
    
    public static function getTable($type, $prefix = 'jshop', $config = [])
    {
        \JFactory::getApplication()->triggerEvent('onJSFactoryGetTable', [&$type, &$prefix, &$config]);
        $table = JTable::getInstance($type, $prefix, $config);
        \JFactory::getApplication()->triggerEvent('onAfterJSFactoryGetTable', [&$table, &$type, &$prefix, &$config]);

        return $table;
    }
    
    public static function getModel($type, $prefix = 'JshoppingModel', $config = [])
    {
        \JFactory::getApplication()->triggerEvent('onJSFactoryGetModel', [&$type, &$prefix, &$config]);
        $model = JModelLegacy::getInstance($type, $prefix, $config);
        \JFactory::getApplication()->triggerEvent('onAfterJSFactoryGetModel', [&$model, &$type, &$prefix, &$config]);

        return $model;
    }

    public static function getRepository()
    {
        static $repository;

        if (!isset($repository)) {
            require_once __DIR__ . '/repository.php';
            $repository = new Repository();
        }

        return $repository;
    }

    /**
     * @return FlashData
     */
    public static function getFlashData()
    {
        static $flashData;

        if (empty($flashData)) {
            $storage = JFactory::getSession();
            $flashData = new FlashData($storage);
        }

        return $flashData;
    }

    public static function getVideoHostings()
    {
        static $videoHostings;

        if (empty($videoHostings)) {
            include_once __DIR__ . '/videohostings/videohostings.php';
            $videoHostings = new VideoHostings();
        }

        return $videoHostings;
    }

    public static function getJSUri()
    {
        static $JSUri;

        if (empty($JSUri)) {
            include_once __DIR__ . '/jsuri.php';
            $JSUri = new JSUri();
        }

        return $JSUri;
    }

    public static function getFfmpegCli()
    {
        static $ffmpegcli;

        if (empty($ffmpegcli)) {
            include_once __DIR__ . '/ffmpeg/ffmpegcli.php';
            $ffmpegcli = new FfmpegCli();
        }

        return $ffmpegcli;
    }

    public function getUploader()
    {
        include_once __DIR__ . '/uploader.php';
        return new Uploader();
    }

    public static function getService(string $folderName, string $serviceName = '')
    {
        $serviceName = $serviceName ?: $folderName;
        $folderPath = JPATH_ROOT . '/components/com_jshopping/Services/' . $folderName;
        $className = $serviceName . 'Service';
        $servicePath = $folderPath . '/' . $className . '.php';

        if (JFolder::exists($folderPath) && JFile::exists($servicePath)) {
            require_once $servicePath;
            $service = new $className();

            return $service;
        }

        throw new \Exception('Service ' . $className . 'not found');
    }
}
