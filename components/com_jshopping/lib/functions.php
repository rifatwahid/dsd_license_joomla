<?php
/**
* @version      4.9.0 09.01.2015
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Pagination\PaginationObject;
defined('_JEXEC') or die('Restricted access');


if (!class_exists('parseString')) {
    include_once __DIR__ . '/parse_string.php';
}

if (!class_exists('shopItemMenu')) {
    include_once __DIR__ . '/shop_item_menu.php';
}

if (!class_exists('JTableAvto')) {
    include_once __DIR__ . '/jtableauto.php';
}

if (!function_exists('getJsFrontRequestController')) {
    function getJsFrontRequestController() {
        $input = JFactory::getApplication()->input;

        $controller = $input->getCmd('controller');
    
        if (empty($controller)) {
            $controller = $input->getCmd('view');
    
            if (!empty($controller)) {
                $input->set('controller', $controller);
            }
        }
    
        if (!$controller) {
            $controller = 'category';
        }
    
        \JFactory::getApplication()->triggerEvent('onAfterGetJsFrontRequestController', [&$controller]);
    
        return $controller;
    }
}

if (!function_exists('js_add_trigger')) {
    function js_add_trigger($vars = [], $name = '') {
        list(,$caller) = debug_backtrace();    
    
        $callerClass = (isset($caller['class'])) ? $caller['class'] : '';
        $callerFunction = (isset($caller['function'])) ? $caller['function'] : '';
        $callerObj = (isset($caller['object'])) ? $caller['object'] : '';   
        $triggerName = 'on' . ucfirst($callerClass) . ucfirst($callerFunction) . ucfirst($name);
    
        \JFactory::getApplication()->triggerEvent($triggerName, [&$callerObj, &$vars]);
        return $vars;
    }
}

if (!function_exists('setMetaData')) {
    function setMetaData($title, $keyword, $description, $params = null) {
        $config = JFactory::getConfig();
        $document =JFactory::getDocument();
    
        if ($title == '' && $params && $params->get('page_title') != '') {
            $title = $params->get('page_title');
        }
    
        if ($keyword == '' && $params && $params->get('menu-meta_keywords') != '') {
            $keyword = $params->get('menu-meta_keywords');
        }
    
        if ($description=='' && $params && $params->get('menu-meta_description') != '') {
            $description = $params->get('menu-meta_description');
        }
    
        if ($config->get('sitename_pagetitles') == 1) {
            $title = "{$config->get('sitename')} - {$title}";
        }
    
        if ($config->get('sitename_pagetitles') == 2) {
            $title = "{$title} - {$config->get('sitename')}";
        }
    
        $document->setTitle($title);
        $document->setMetadata('keywords', $keyword);  
        $document->setMetadata('description', $description);
    }
}

if (!function_exists('parseArrayToParams')) {
    function parseArrayToParams($array) {
        $str = '';
    
        foreach ($array as $key => $value) {
            $str .= "{$key}={$value} \n";
        }
    
        return $str;
    }
}

if (!function_exists('parseParamsToArray')) {
    function parseParamsToArray($string) {
        $temp = explode("\n",$string);
    
        foreach ($temp as $key => $value) {
            if (!$value) {
                continue;
            }
    
            $temp2 = explode('=', $value);
            $array[$temp2['0']] = $temp2[1];
        }
    
        return $array;
    }
}

if (!function_exists('getParseParamsSerialize')) {
    function getParseParamsSerialize($data) {
        if ($data != '') {
            return unserialize($data);
        }
    
        return [];
    }
}

if (!function_exists('outputDigit')) {
    function outputDigit($digit, $count_null) {
        $length = strlen(strval($digit));
    
        for ($i = 0; $i < $count_null - $length; $i++) {
            $digit = '0' . $digit;
        }
    
        return $digit;
    }
}

if (!function_exists('splitValuesArrayObject')) {
    function splitValuesArrayObject($array_object,$property_name) {
        $return = '';
    
        if (is_array($array_object)) {
            foreach($array_object as $key => $value) {
                $return .= $array_object[$key]->$property_name . ', ';
            }
            
            $return = '( ' . substr($return, 0, strlen($return) - 2) . ' )';
        }
    
        return $return;
    }
}

if (!function_exists('getTextNameArrayValue')) {
    function getTextNameArrayValue($names, $values) {
        $return = '';
    
        foreach ($names as $key => $value) {
            $return .= "{$names[$key]}: {$values[$key]} \n";
        }
    
        return $return;
    }
}

if (!function_exists('strToHex')) {
    function strToHex($string) {
        $hex = '';
    
        for ($i = 0;$i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
    
        return $hex;
    }
}

if (!function_exists('hexToStr')) {
    function hexToStr($hex){
        $string='';
    
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
    
        return $string;
    }
}

if (!function_exists('insertValueInArray')) {
    function insertValueInArray($value, &$array) {
        if ($key = array_search($value, $array)) {
            return $key;
        }
    
        $array[$value] = $value;
        asort($array);
        return $key-1;
    }
}

if (!function_exists('appendExtendPathWay')) {
    function appendExtendPathWay($array, $page) {
        $app =JFactory::getApplication();
        $pathway = $app->getPathway();
        \JFactory::getApplication()->triggerEvent('onBeforeAppendExtendPathWay', [&$array, &$page, &$pathway]);
        foreach($array as $cat) {
            $pathway->addItem($cat->name, SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id=' . $cat->category_id, 1));
        }
        \JFactory::getApplication()->triggerEvent('onAfterAppendExtendPathWay', [&$array, &$page, &$pathway]);
    }
}

if (!function_exists('appendPathWay')) {
    function appendPathWay($page, $url = '') {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
    
        \JFactory::getApplication()->triggerEvent('onBeforeAppendPathWay', [&$page, &$url, &$pathway]);
        if ($url != '') {
            $pathway->addItem($page, $url);
        } else {
            $pathway->addItem($page);
        }
    
        \JFactory::getApplication()->triggerEvent('onAfterAppendPathWay', [&$page, &$url, &$pathway]);
    }
}

if (!function_exists('getMainCurrencyCode')) {
    function getMainCurrencyCode() {
        $jshopConfig = JSFactory::getConfig();
        $currency = JSFactory::getTable('currency', 'jshop');
        $currency->load($jshopConfig->mainCurrency);
    
        return $currency->currency_code;
    }
}

if (!function_exists('formatprice')) {
    function formatprice($price, $currency_code = null, $currency_exchange = 0, $style_currency = 0) {
        $jshopConfig = JSFactory::getConfig();
		\JFactory::getApplication()->triggerEvent('onBeforeTotalFormatPrice', [&$jshopConfig]);
        if ($currency_exchange) {
            $price = $price * $jshopConfig->currency_value;
        }
        if ($jshopConfig->formatprice_style_currency_span && $style_currency != -1) {
            $style_currency = 1;
        }
        
        if (!$currency_code) {
            $currency_code = $jshopConfig->currency_code;
        }
    
        $price = number_format($price, $jshopConfig->decimal_count, $jshopConfig->decimal_symbol, $jshopConfig->thousand_separator);
                     
        if ($style_currency == 1) {
            $base = JSFactory::getModel('checkout', 'jshop');
			$currency_code = renderTemplate([
                    templateOverrideBlock('blocks', 'currency_code.php', 1)
                ], 'currency_code', [
                    'currency_code' => $currency_code
                ]) ?: '';	
        }
    
        $return = str_replace('Symb', $currency_code, str_replace('00', $price, $jshopConfig->format_currency[$jshopConfig->currency_format]));
        extract(js_add_trigger(get_defined_vars(), 'after'));
    
        return $return;	
	}	
}
if (!function_exists('precisionformatprice')) {
    function precisionformatprice($price, $currency_code = null, $currency_exchange = 0, $style_currency = 0) {
		$nprice=$price;
        $jshopConfig = JSFactory::getConfig();
		\JFactory::getApplication()->triggerEvent('onBeforeFormatPrice', [$price,$currency_code,$currency_exchange,$style_currency]);		
        if ($currency_exchange) {
            $nprice = $nprice * $jshopConfig->currency_value;
        }
        if ($jshopConfig->formatprice_style_currency_span && $style_currency != -1) {
            $style_currency = 1;
        }
        
        if (!$currency_code) {
            $currency_code = $jshopConfig->currency_code;
        }
    
        $nprice = number_format($nprice, $jshopConfig->decimal_count, $jshopConfig->decimal_symbol, $jshopConfig->thousand_separator);
                     
        if ($style_currency == 1) {
            $base = JSFactory::getModel('checkout', 'jshop');
			$currency_code = renderTemplate([
                    templateOverrideBlock('blocks', 'currency_code.php', 1)
                ], 'currency_code', [
                    'currency_code' => $currency_code
                ]) ?: '';	
        }
    
        $return = str_replace('Symb', $currency_code, str_replace('00', $nprice, $jshopConfig->format_currency[$jshopConfig->currency_format]));
        extract(js_add_trigger(get_defined_vars(), 'after'));
		\JFactory::getApplication()->triggerEvent('onAfterFormatPrice', [&$return,$price,$currency_code,$currency_exchange,$style_currency]);
        return $return;
    }
}

if (!function_exists('formatEPrice')) {
    function formatEPrice($price) {
        $jshopConfig = JSFactory::getConfig();
        return number_format($price, $jshopConfig->product_price_precision, '.', '');
    }
}

if (!function_exists('formatdate')) {
    function formatdate($date, $showtime = 0) {
        $jshopConfig = JSFactory::getConfig();
        $format = $jshopConfig->store_date_format;
    
        if ($showtime) {
            $format = $format . ' %H:%M:%S';
        }
    
        return strftime($format, strtotime($date));
    }
}

if (!function_exists('formattax')) {
    function formattax($val) {
        $jshopConfig = JSFactory::getConfig();
        $val = floatval($val);
    
        return str_replace('.', $jshopConfig->decimal_symbol, $val);
    }
}

if (!function_exists('formatweight')) {
    function formatweight($val, $unitid = 0, $show_unit = 1) {
        $jshopConfig = JSFactory::getConfig();
    
        if (!$unitid) {
            $unitid = $jshopConfig->main_unit_weight;
        }
    
        $sufix = '';
        $units = JSFactory::getAllUnits();
        $unit = $units[$unitid];
    
        if ($show_unit) {
            $sufix = ' ' . $unit->name;
        }
    
        $val = floatval($val);
        return str_replace('.', $jshopConfig->decimal_symbol, $val) . $sufix;
    }
}

if (!function_exists('formatqty')) {
    function formatqty($val) {
        return floatval($val);
    }
}

if (!function_exists('sprintCurrency')) {
    function sprintCurrency($id, $field = 'currency_code') {
        $all_currency = JSFactory::getAllCurrency();
        return $all_currency[$id]->$field;
    }
}

if (!function_exists('sprintUnitWeight')) {
    function sprintUnitWeight() {
        $jshopConfig = JSFactory::getConfig();
        $units = JSFactory::getAllUnits();
        $unit = $units[$jshopConfig->main_unit_weight];
    
        return $unit->name;
    }
}

if (!function_exists('getAllLanguages')&&(version_compare(JVERSION, '3.999.999', 'le'))) {
    /**
    * get system language
    * 
    * @param int $client (0 - site, 1 - admin)
    */
    function getAllLanguages($client = 0) {
        $pattern = '#(.*?)\(#is';
        $client	= JApplicationHelper::getClientInfo($client);
        $rows = [];
        jimport('joomla.filesystem.folder');
        $path = JLanguage::getLanguagePath($client->path);
        $dirs = JFolder::folders( $path );

        foreach($dirs as $dir) {
            $files = JFolder::files("{$path}/{$dir}", '^([-_A-Za-z]*)\.xml$');

            foreach($files as $file) {
                $data = JApplicationHelper::parseXMLLangMetaFile("{$path}/{$dir}/{$file}");
                $row = new StdClass();
				//print_r($data['name']);die;
                $row->descr = $data['name'] ?? '';
                $row->language = substr($file, 0, -4);
                $row->lang = substr($row->language, 0, 2);
                $row->name = $data['name'] ?? '';
                preg_match($pattern, $row->name, $matches);

                if (isset($matches['1'])) {
                    $row->name = trim($matches['1']);
                }

                if (!is_array($data)) {
                    continue;
                }

                $rows[] = $row;
            }
        }
        return $rows;
    }
}
if (!function_exists('getAllLanguages')&&(!version_compare(JVERSION, '3.999.999', 'le'))) {
    /**
    * get system language
    * 
    * @param int $client (0 - site, 1 - admin)
    */
	
    function getAllLanguages($client = 0) {
        $pattern = '#(.*?)\(#is';
        $client	= JApplicationHelper::getClientInfo($client);
        $rows = [];
        jimport('joomla.filesystem.folder');
        $path = LanguageHelper::getLanguagePath($client->path);		
        $dirs = JFolder::folders( $path );
        foreach($dirs as $dir) {
            $files = JFolder::files("{$path}/{$dir}", '^([-_A-Za-z]*)\.xml$');
            foreach($files as $file) {
                $data = \JInstaller::parseXMLInstallFile("{$path}/{$dir}/{$file}");
                $row = new StdClass();
                $row->descr = $data['name'];
                $row->language = $dir;
                $row->lang = substr($dir, 0, 2);
                $row->name = $data['name'];
                preg_match($pattern, $row->name, $matches);

                if (isset($matches['1'])) {
                    $row->name = trim($matches['1']);
                }

                if (!is_array($data)) {
                    continue;
                }

                $rows[$row->lang] = $row;
            }
        }		
        return $rows;
    }
}

if (!function_exists('installNewLanguages')) {
    function installNewLanguages($defaultLanguage = '', $show_message = 1) {
        $db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();
        $session =JFactory::getSession();
        $joomlaLangs = getAllLanguages();
        $checkedlanguage = $session->get('jshop_checked_language');
    
        if (is_array($checkedlanguage)) {
            $newlanguages = 0;
    
            foreach($joomlaLangs as $lang) {
                if (!in_array($lang->language, $checkedlanguage)) {
                    $newlanguages++;
                }  
            }
    
            if ($newlanguages == 0) {
                return 0;
            }
        }
        
        $query = 'select * from #__jshopping_languages';
        $db->setQuery($query);
        $shopLangs = $db->loadObjectList();
        $shopLangsTag = [];
    
        foreach($shopLangs as $lang) {
            $shopLangsTag[] = $lang->language;
        }
    
        if (!$defaultLanguage) {
            $defaultLanguage = $jshopConfig->defaultLanguage;
        }
        
        $checkedlanguage = [];
        $installed_new_lang = 0;
        
        foreach($joomlaLangs as $lang) {
            $checkedlanguage[] = $lang->language;
    
            if (!in_array($lang->language, $shopLangsTag)) {
                $ml = JSFactory::getLang();
    
                if ($ml->addNewFieldLandInTables($lang->language, $defaultLanguage)) {
                    $installed_new_lang = 1;
					$query = "SELECT * FROM #__jshopping_languages ORDER BY ordering DESC";$db->setQuery($query);$ordering=$db->loadObjectList();
                    $query = "insert into #__jshopping_languages set `language` = '{$db->escape($lang->language)}', `name` = '{$db->escape($lang->name)}', `publish` = '1', `ordering` = ".++$ordering[0]->ordering;
                    $db->setQuery($query);
                    $db->execute();
    
                    if ($show_message) {
                        \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_INSTALLED_NEW_LANGUAGES') . ': ' . $lang->name,'error');
                    }
                }
            }
        }     
    
        $session->set('jshop_checked_language', $checkedlanguage);
        return 1;
    }
}

if (!function_exists('recurseTree')) {
    function recurseTree($cat, $level, $all_cats, &$categories, $is_select) {
        $probil = '';
    
        if ($is_select) {
            for ($i = 0; $i < $level; $i++) {
                $probil .= '-- ';
            }
    
            $cat->name = ($probil . $cat->name);
            $categories[] = JHTML::_('select.option', $cat->category_id, $cat->name, 'category_id','name');
        } else {
            $cat->level = $level;
            $categories[] = $cat;
        }
    
        foreach ($all_cats as $categ) {
            if ($categ->category_parent_id == $cat->category_id) {
                recurseTree($categ, ++$level, $all_cats, $categories, $is_select);
                $level--;
            }
        }
    
        return $categories;
    }
}


if (!function_exists('buildTreeCategory')) {
    function buildTreeCategory($publish = 1, $is_select = 1, $access = 1) {
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $user = JFactory::getUser();
        $where = [];
        $add_where = '';
    
        if ($publish){
            $where[] = "category_publish = '1'";
        }
        if ($access){
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $where[] = " access IN ({$groups})";
        }
    
        if (!empty($where)) {
            $add_where = ' where ' . implode(' and ', $where);
        }
    
        $query = "SELECT `{$lang->get('name')}` as name, category_id, category_parent_id, category_publish FROM `#__jshopping_categories`
                     {$add_where} ORDER BY category_parent_id, ordering";
        $db->setQuery($query);
        $all_cats = $db->loadObjectList();
        
        $categories = [];
        if(!empty($all_cats)) {
            foreach ($all_cats as $key => $value) {
                if(!$value->category_parent_id) {
                    recurseTree($value, 0, $all_cats, $categories, $is_select);
                }
            }
        }
    
        return $categories;
    }
}

if (!function_exists('_getCategoryParent')) {
    function _getCategoryParent($cat, $parent) {
        $res = [];
    
        foreach($cat as $obj) {
            if ($obj->category_parent_id == $parent) {
                $res[] = $obj;
            }
        } 
    
        return $res;
    }
}


if (!function_exists('_getResortCategoryTree')) {
    function _getResortCategoryTree(&$cats, $allcats) {
        foreach($cats as $k => $v) {
            $cats_sub = _getCategoryParent($allcats, $v->category_id);
    
            if (!empty($cats_sub)) {
                _getResortCategoryTree($cats_sub, $allcats);
            }
    
            $cats[$k]->subcat = $cats_sub;
        }
    }
}

if (!function_exists('getTreeCategory')) {
    /**
    * get Tree category
    * @param int $publish
    * @return array
    */
    function getTreeCategory($publish = 1, $access = 1) {
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $user = JFactory::getUser();
        $lang = JSFactory::getLang();
        $where = [];
        $add_where = '';

        if ($publish) {
            $where[] = "category_publish = '1'";
        }

        if ($access) {
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $where[] = " access IN ({$groups})";
        }

        if (!empty($where)) {
            $add_where = ' where ' . implode(' and ', $where);
        }

        $query = "SELECT `{$lang->get('name')}` as name, category_id, category_parent_id FROM `#__jshopping_categories` {$add_where} ORDER BY category_parent_id, ordering";
        $db->setQuery($query);
        $allcats = $db->loadObjectList();
            
        $cats = _getCategoryParent($allcats, 0);
        _getResortCategoryTree($cats, $allcats);
        
        return $cats;
    }
}

if (!function_exists('checkMyDate')) {
    /**
    * check date Format date yyyy-mm-dd
    */
    function checkMyDate($date) {
        if (trim($date) == '') {
            return false;
        }
        $arr = explode('-', $date);

        return checkdate($arr['1'], $arr['2'], $arr['0']);
    }
}

if (!function_exists('getThisURLMainPageShop')) {
    function getThisURLMainPageShop() {
        $shopMainPageItemid = getShopMainPageItemid();
        $Itemid = JFactory::getApplication()->input->getInt('Itemid');
    
        return ($shopMainPageItemid == $Itemid && $Itemid != 0);
    }
}

if (!function_exists('getShopMainPageItemid')) {
    function getShopMainPageItemid() {
        static $Itemid;
    
        if (!isset($Itemid)) {
            $shim = shopItemMenu::getInstance();
            $Itemid = $shim->getShop();
    
            if (!$Itemid) {
                $Itemid = $shim->getProducts();
            }
        }
    
        return $Itemid;
    }
}

if (!function_exists('getShopManufacturerPageItemid')) {
    function getShopManufacturerPageItemid() {
        static $Itemid;
    
        if (!isset($Itemid)) {
            $shim = shopItemMenu::getInstance();
            $Itemid = $shim->getManufacturer();
        }
    
        return $Itemid;
    }
}

if (!function_exists('getShopCategoryPageItemid')) {
    function getShopCategoryPageItemid($category_id) {
        $shim = shopItemMenu::getInstance();
		$Items = $shim->getListCategory();
		
		if(isset($Items[$category_id])){
			$Itemid = $Items[$category_id];
		}
		
		if(!isset($Itemid) || !$Itemid){
			return 0;//getShopMainPageItemid();
		}
        
        return $Itemid;
    }
}

if (!function_exists('getShopProductPageItemid')) {
    function getShopProductPageItemid($product_id) {
        $shim = shopItemMenu::getInstance();
		$Items = $shim->getListProduct();
		if(isset($Items[$product_id])){
			$Itemid = $Items[$product_id];
		}
		
		if(!isset($Itemid) || !$Itemid){
			return 0;
		}
        
        return $Itemid;
    }
}

if (!function_exists('getDefaultItemid')) {
    function getDefaultItemid() {
        return getShopMainPageItemid();
    }
}

if (!function_exists('checkUserLogin')) {
    function checkUserLogin($ajax = 0){
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
    
        if (!$user->id) {
            $mainframe = JFactory::getApplication();
            $return = base64_encode($_SERVER['REQUEST_URI']);
            $session = JFactory::getSession();
            $session->set('return', $return);
            if($ajax){
                $order   = array("&ajax=1", "?ajax=1");
                $return = str_replace($order, '', $return);
                $session->set('return', $return);
                $data = [];
                $data['redirect'] = SEFLink('index.php?option=com_jshopping&controller=user&task=login', 1, 1, $jshopConfig->use_ssl);
                print_r(json_encode($data));die;
            }
            $mainframe->redirect(SEFLink('index.php?option=com_jshopping&controller=user&task=login', 1, 1, $jshopConfig->use_ssl));
            exit();
        }
    
        return 1;
    }
}

if (!function_exists('addLinkToProducts')) {
    function addLinkToProducts(&$products, $default_category_id = 0, $useDefaultItemId = 0) {    
        foreach($products as $key => $value) {		            
            $category_id = (!$default_category_id) ? $products[$key]->category_id : $default_category_id;
            $category_id = $category_id ?: 0;
			$Itemid = getShopCategoryPageItemid($category_id);
			$productItemid = getShopProductPageItemid($products[$key]->product_id);
			if($productItemid){
				$Itemid = $productItemid;
			}
            $products[$key]->product_link = SEFLink("index.php?option=com_jshopping&controller=product&task=view&category_id={$category_id}&product_id={$products[$key]->product_id}", $useDefaultItemId, 0, null, $Itemid);
            $products[$key]->checkout_link = SEFLink("index.php?option=com_jshopping&controller=product&task=toCheckout&productId={$products[$key]->product_id}", $useDefaultItemId);
            $products[$key]->buy_link = '';	
		 }
    }
}

if (!function_exists('getJHost')) {
    function getJHost() {
        return $_SERVER['HTTP_HOST'];
    }
}

if (!function_exists('searchChildCategories')) {
    function searchChildCategories($category_id, $all_categories, &$cat_search) {
        foreach ($all_categories as $all_cat) {
            if($all_cat->category_parent_id == $category_id) {
                searchChildCategories($all_cat->category_id, $all_categories, $cat_search);
                $cat_search[] = $all_cat->category_id;
            }
        }
    }
}

if (!function_exists('SEFLink')) {
    /**
    * set Sef Link
    * 
    * @param string $link
    * @param int $useDefaultItemId - (0 - current itemid, 1 - shop page itemid, 2 -manufacturer itemid)
    * @param int $redirect
    */
    function SEFLink($link, $useDefaultItemId = 0, $redirect = 0, $ssl = null, $userItemId = 0) {
        $app = JFactory::getApplication();
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher =\JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadJshopSefLink', [&$link, &$useDefaultItemId, &$redirect, &$ssl]);
        $defaultItemid = getDefaultItemid();

        if (!empty($userItemId)) {
            $Itemid = $userItemId;
        } elseif ($useDefaultItemId == 2) {
            $Itemid = getShopManufacturerPageItemid();

            if (!$Itemid) {
                $Itemid = $defaultItemid;
            }
        } elseif ($useDefaultItemId == 1) {
            $Itemid = $defaultItemid;
        } else {
            $Itemid = getShopPageItemid($link);
			if(!$Itemid){
				$Itemid = JFactory::getApplication()->input->getInt('Itemid');
			}
            if (!$Itemid) {
                $Itemid = $defaultItemid;
            }
        }

        $dispatcher->triggerEvent('onAfterLoadJshopSefLinkItemid', [&$Itemid, &$link, &$useDefaultItemId, &$redirect, &$ssl]);
        if ($Itemid && !preg_match('/Itemid=/', $link)) {  
            $sp = '&';

            if (!preg_match('/\?/', $link)) {
                $sp = '?'; 
            }

            $link .= "{$sp}Itemid={$Itemid}";
        }

        $link = JRoute::_($link, (($redirect) ? (false) : (true)), $ssl);
        
        if ($app->isClient('administrator')){
            $link = str_replace('/administrator', '', $link);
        }
        
        return $link;
    }
}

if (!function_exists('compareX64')) {
    function compareX64($a, $b) {
        return base64_encode($a) == $b;
    }
}

if (!function_exists('replaceNbsp')) {
    function replaceNbsp($string) {
        return (str_replace(' ', '_', $string));
    }
}

if (!function_exists('replaceToNbsp')) {
    function replaceToNbsp($string) {
        return (str_replace('_', ' ', $string));
    }
}

if (!function_exists('replaceWWW')) {
    function replaceWWW($str) {
        return str_replace('www.', '', $str);
    }
}


if (!function_exists('sprintRadioList')) {
    function sprintRadioList($list, $name, $params, $key, $val, $actived = null, $separator = ' ') {
        $html = '';
        $smartShopConfig = JSFactory::getConfig();
        $id = str_replace('[', '', $name);
        $id = str_replace(']', '', $id);    
		
		$html = renderTemplate([
                templateOverrideBlock('blocks', 'sprint_radio_list.php', 1)
            ], 'sprint_radio_list', [
                'lst' => $list,
                'name' => $name,
                'params' => $params,
                'key' => $key,
                'val' => $val,
                'actived' => $actived,
                'separator' => $separator,
                'id' => $id
            ]);			
    
        return $html;
    }
}


if (!function_exists('saveToLog')) {
    function saveToLog($file, $text) {
        $jshopConfig = JSFactory::getConfig();
    
        if (!$jshopConfig->savelog) {
            return 0;
        }
    
        if ($file == 'paymentdata.log' && !$jshopConfig->savelogpaymentdata) {
            return 0;
        }
    
        $f = fopen($jshopConfig->log_path.$file, 'a+');
        fwrite($f, date('Y-m-d H:i:s') . ' ' . $text . "\r\n");
        fclose($f);
    
        return 1;
    }
}

if (!function_exists('displayTextJSC')) {
    function displayTextJSC(){
        $conf = JSFactory::getConfig();
    
        if (getJsFrontRequestController() != 'content' && !compareX64(replaceWWW(getJHost()), $conf->licensekod)){
            echo $conf->copyrightText;
        }
    }
}


if (!function_exists('filterHTMLSafe')) {
    function filterHTMLSafe(&$mixed, $quote_style = ENT_QUOTES, $exclude_keys = '') {
        if (is_object($mixed)) {
            foreach (get_object_vars($mixed) as $k => $v) {
                if (is_array($v) || is_object($v) || $v == NULL) {
                    continue;
                }
    
                if (is_string($exclude_keys) && $k == $exclude_keys) {
                    continue;
                } elseif (is_array($exclude_keys) && in_array($k, $exclude_keys)) {
                    continue;
                }
    
                $mixed->$k = htmlspecialchars($v, $quote_style, 'UTF-8');
            }
        }
    }
}


if (!function_exists('saveAsPrice')) {
    function saveAsPrice($val){
        $val = str_replace(',', '.', $val);
        preg_match('/-?[0-9]+(\.[0-9]+)?/', $val, $matches);
		
        return floatval($matches['0'] ?? '');
    }
}

if (!function_exists('getPriceDiscount')) {
    function getPriceDiscount($price, $discount) {
        return $price - ($price * $discount / 100);
    }
}

if (!function_exists('getSeoSegment')) {
    function getSeoSegment($str) {
        return str_replace(':', '-', $str);
    }
}

if (!function_exists('setPrevSelLang')) {
    function setPrevSelLang($lang) {
        $session =JFactory::getSession();
        $session->set('js_history_sel_lang', $lang);
    }
}

if (!function_exists('getPrevSelLang')) {
    function getPrevSelLang() {
        $session = JFactory::getSession();
        return $session->get('js_history_sel_lang');
    }
}

if (!function_exists('setFilterAlias')) {
    function setFilterAlias($alias) {
        $alias = str_replace(' ', '-', $alias);
        $alias = (string) preg_replace('/[\x00-\x1F\x7F<>"\'$#%&\?\/\.\)\(\{\}\+\=\[\]\\\,:;]/', '', $alias);
        $alias = (string) strtolower($alias);
    
        return $alias;
    }
}

if (!function_exists('showMarkStar')) {
    function showMarkStar($rating) {
        $jshopConfig = JSFactory::getConfig();
        $star_count = $jshopConfig->max_mark;
        $star_type = $jshopConfig->rating_starparts;    
    
		$html = renderTemplate([
                templateOverrideBlock('blocks', 'show_mark_star.php', 1)
            ], 'show_mark_star', [
                'type' => $star_type,
                'stars' => $star_count,
                'rating' => (int)$rating
            ]);
        return $html;
    }
}

if (!function_exists('getNameImageLabel')) {
    function getNameImageLabel($id, $type = 1) {
        static $listLabels;
    
        $jshopConfig = JSFactory::getConfig();
        if (!$jshopConfig->admin_show_product_labels) {
            return '';
        }
    
        if (!is_array($listLabels)) {
            $productLabel = JSFactory::getTable('productLabel', 'jshop');
            $listLabels = $productLabel->getListLabels();
        }
    
        $obj = $listLabels[$id];
        if(!$obj->image && $type == 1 && $obj->img) {
            $obj->image = $obj->img;
        }
        
        if ($type == 1) {
            return $obj->image;
        }
            
        return $obj->name;
    }
}


if (!function_exists('getPriceFromCurrency')) {
    function getPriceFromCurrency($price, $currency_id = 0, $current_currency_value = 0) {
        $jshopConfig = JSFactory::getConfig();
    
        if ($currency_id) {
            $all_currency = JSFactory::getAllCurrency();
            $value = $all_currency[$currency_id]->currency_value;
    
            if (!$value) {
                $value = 1;
            }
    
            $pricemaincurrency = $price / $value;
        }else{
            $pricemaincurrency = $price;
        }
    
        if (!$current_currency_value) {
            $current_currency_value = $jshopConfig->currency_value;
        }
    
        return $pricemaincurrency * $current_currency_value;
    }
}

if (!function_exists('listProductUpdateData')) {
    function listProductUpdateData($products, $setUrl = 0) {
        $jshopConfig = JSFactory::getConfig();
        $taxes = JSFactory::getAllTaxes();
    
        if ($jshopConfig->product_list_show_manufacturer) {
            $manufacturers = JSFactory::getAllManufacturer();
        }
    
        if ($jshopConfig->delivery_times_on_product_listing) {
            $deliverytimes = JSFactory::getAllDeliveryTime();
        }
        
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();   
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        
        foreach($products as $key => $value) {
            $dispatcher->triggerEvent('onListProductUpdateDataProduct', [&$products, &$key, &$value]);
             
            if ($value->tax_id) {
                $products[$key]->tax = $taxes[$value->tax_id];
            }
            
            if ($jshopConfig->product_list_show_manufacturer && $value->product_manufacturer_id && isset($manufacturers[$value->product_manufacturer_id])) {
                $products[$key]->manufacturer = $manufacturers[$value->product_manufacturer_id];
            } else {
                $products[$key]->manufacturer = new stdClass();
                $products[$key]->manufacturer->name = '';
            }  
    
            if ($jshopConfig->admin_show_product_extra_field) {
                $products[$key]->extra_field = getProductExtraFieldForProduct($value);
            } else {
                $products[$key]->extra_field = '';
            }
    
            $products[$key]->vendor = '';
            $dispatcher->triggerEvent('onListProductUpdateDataMiddle', [&$products,$value,$key]);
    
            if ($jshopConfig->hide_delivery_time_out_of_stock && $products[$key]->product_quantity <= 0) {
                $products[$key]->delivery_times_id = 0;
                $value->delivery_times_id = 0;
            }
    
            if ($jshopConfig->delivery_times_on_product_listing && $value->delivery_times_id) {
                $products[$key]->delivery_time = $deliverytimes[$value->delivery_times_id];
            } else {
                $products[$key]->delivery_time = '';
            }
    
            $products[$key]->_display_price = getDisplayPriceForProduct($products[$key]->product_id, $products[$key]->product_price);
            if (!$products[$key]->_display_price) {
                $products[$key]->product_old_price = 0;
                $products[$key]->product_price_default = 0;
                $products[$key]->basic_price_info['price_show'] = 0;
                $products[$key]->tax = 0;
                $jshopConfig->show_plus_shipping_in_product = 0;
            }
    
            if ($jshopConfig->product_list_show_qty_stock) {
                $products[$key]->qty_in_stock = getDataProductQtyInStock($products[$key]);
            }
    
            $image = getPatchProductImage($products[$key]->image);
            $products[$key]->product_name_image = getPatchProductImage($image, '', 1);
            $products[$key]->product_thumb_image = getPatchProductImage($image, '', 1);
    
            if (!$image) {
                $image = $jshopConfig->path_to_no_img;
            }
    
            $products[$key]->image = getPatchProductImage($image, '', 1);
            $products[$key]->template_block_product = 'product.php';
    
            if (!$jshopConfig->admin_show_product_labels) {
                $products[$key]->label_id = null;
            }
    
            if ($products[$key]->label_id) {
                $image = getNameImageLabel($products[$key]->label_id);
    
                if ($image) {
                    $products[$key]->_label_image = getPatchProductImage($image, '', 1);
                }
    
                $products[$key]->_label_name = getNameImageLabel($products[$key]->label_id, 2);
            }
    
            if (!empty($products[$key]->preview_total_price)) {
                $prod = &$products[$key];
                $prod->preview_total_price = getPriceCalcParamsTax($prod->preview_total_price, $prod->tax_id);
            }
        }
        
        if ($setUrl) {
            addLinkToProducts($products, 0, 1);
        }
        addDescriptionToProducts($products);
          
        $dispatcher->triggerEvent('onListProductUpdateData', [&$products]);
    
        return $products;
    }
}

if (!function_exists('getProductBasicPriceInfo')) {
    function getProductBasicPriceInfo($obj, $price) {
        $jshopConfig = JSFactory::getConfig();
        $price_show = $obj->weight_volume_units!=0;
    
        if (!$jshopConfig->admin_show_product_basic_price || $price_show == 0) {
            return [
                'price_show' => 0
            ];
        }
    
        $units = JSFactory::getAllUnits();
        $unit = $units[$obj->basic_price_unit_id];
        $basic_price = $price / $obj->weight_volume_units * $unit->qty;
    
        return [
            'price_show' => $price_show, 
            'basic_price' => $basic_price, 
            'name' => $unit->name, 
            'unit_qty' => $unit->qty
        ];
    }
}


if (!function_exists('getProductExtraFieldForProduct')) {
    function getProductExtraFieldForProduct($product) {
        $fields = JSFactory::getAllProductExtraField();
        $fieldvalues = JSFactory::getAllProductExtraFieldValue();
        $displayfields = JSFactory::getDisplayListProductExtraFieldForCategory($product->category_id);
        $rows = [];
    
        foreach($displayfields as $field_id) {
            $field_name = "extra_field_{$field_id}";
    
            if ($fields[$field_id]->type == 0) {
                if ($product->$field_name != 0) {
                    $listid = explode(',', $product->$field_name);
                    $tmp = [];
    
                    foreach($listid as $extrafiledvalueid) {
                        $tmp[] = $fieldvalues[$extrafiledvalueid];
                    }
    
                    $extra_field_value = implode(', ', $tmp);
                    $rows[$field_id] = [
                        'name' => $fields[$field_id]->name, 
                        'description' => $fields[$field_id]->description, 
                        'value' => $extra_field_value
                    ];
                }
            } else {
                if ($product->$field_name != '') {
                    $rows[$field_id] = [
                        'name' => $fields[$field_id]->name, 
                        'description' => $fields[$field_id]->description, 
                        'value' => $product->$field_name
                    ];
                }
            }
        }
    
        return $rows;
    }
}

if (!function_exists('getPriceTaxRatioForProducts')) {
    function getPriceTaxRatioForProducts($products, $group = 'tax') {
        $prodtaxes = [];
    
        foreach($products as $k => $v) {
            if (!isset($prodtaxes[$v[$group]])) {
                $prodtaxes[$v[$group]] = 0;
            }
    
            $prodtaxes[$v[$group]]+= $v['price'] * $v['quantity'];
        }
    
        $sumproducts = array_sum($prodtaxes);  
    
        foreach($prodtaxes as $k => $v) {
            if ($sumproducts > 0) {
                $prodtaxes[$k] = $v/$sumproducts;
            } else {
                $prodtaxes[$k] = 0;
            }
        }
    
        return $prodtaxes;
    }
}

if (!function_exists('getFixBrutopriceToTax')) {
    function getFixBrutopriceToTax($price, $tax_id) {
        $jshopConfig = JSFactory::getConfig();
    
        if ($jshopConfig->no_fix_brutoprice_to_tax == 1) {
            return $price;
        }
    
        $taxoriginal = JSFactory::getAllTaxesOriginal();
        $taxes = JSFactory::getAllTaxes();
        $tax = $taxes[$tax_id];
        $tax2 = $taxoriginal[$tax_id];
    
        if ($tax != $tax2) {
            $price = $price / (1 + $tax2 / 100);
            $price = $price * (1 + $tax / 100);    
        }
    
        return $price;
    }
}

if (!function_exists('getPriceCalcParamsTax')) {
    function getPriceCalcParamsTax($price, $tax_id, $products = []) {
        $jshopConfig = JSFactory::getConfig();
        $taxes = JSFactory::getAllTaxes();
    
        if ($tax_id == -1) {
            $prodtaxes = getPriceTaxRatioForProducts($products);
        }
    
        if ($jshopConfig->display_price_admin == 0 && $tax_id > 0) {
            $price = getFixBrutopriceToTax($price, $tax_id);
        }
    
        if ($jshopConfig->display_price_admin == 0 && $tax_id == -1) {
            $prices = [];
            $prodtaxesid = getPriceTaxRatioForProducts($products,'tax_id');
    
            foreach($prodtaxesid as $k => $v) {            
                $prices[$k] = getFixBrutopriceToTax($price * $v, $k);
            }
    
            $price = array_sum($prices);
        }
    
        if ($tax_id > 0) {
            $tax = $taxes[$tax_id];
			foreach ($taxes as $key=>$val){
				if (substr($key,0,15)=="additional_tax_"){
					$tax+=(float)$val[$tax_id];
				}
			}
        } elseif ($tax_id == -1) {
            $prices = [];
            foreach($prodtaxes as $k => $v) {
                $prices[] = [
                    'tax' => $k, 
                    'price' => $price * $v
                ];
            }
        } else {
            $taxlist = array_values($taxes);
            $tax = $taxlist[0];
        }
    
        if ($jshopConfig->display_price_admin == 1 && $jshopConfig->display_price_front_current == 0) {
            if ($tax_id == -1) {
                $price = 0;
    
                foreach($prices as $v){
                    $price+= $v['price'] * (1 + $v['tax'] / 100);
                }
            } else {
                $price = $price * (1 + $tax / 100);
            }
        }
    
        if ($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 1) {//FIRMA
            if ($tax_id == -1) {
                $price = 0;
    
                foreach($prices as $v) {
                    $price+= $v['price'] / (1 + $v['tax'] / 100);
                }
            }else{
                $price = $price / (1 + $tax / 100);
            }
        }
			
        return $price;
    }
}

if (!function_exists('changeDataUsePluginContent')) {
    function changeDataUsePluginContent(&$data, $type)  {
        $mainframe =JFactory::getApplication();
        $dispatcher =\JFactory::getApplication();
        JPluginHelper::importPlugin('content');
        $obj = new stdClass();
        $params = $mainframe->getParams('com_content');
        
        if ($type == 'product') {
            $obj->product_id = $data->product_id;
        }
    
        if ($type == 'category') {
            $obj->category_id = $data->category_id;
        }
    
        if ($type == 'manufacturer') {
            $obj->manufacturer_id = $data->manufacturer_id;
        }
    
        if (!isset($data->name)) {
            $data->name = '';
        }
    
        $obj->text = $data->description;
        $obj->title = $data->name;
        $results = $dispatcher->triggerEvent('onContentPrepare', ['com_content.article', &$obj, &$params, 0]);
        $data->description = $obj->text;
    
        return 1;
    }
}

if (!function_exists('productTaxInfo')) {
    function productTaxInfo($tax, $display_price = null) {
        if (!isset($display_price)) {
            $jshopConfig = JSFactory::getConfig();
            $display_price = $jshopConfig->display_price_front_current;
        }
    
        if ($display_price == 0) {
            return JText::sprintf('COM_SMARTSHOP_INC_PERCENT_TAX', formattax($tax));
        }
            
        return JText::sprintf('COM_SMARTSHOP_PLUS_PERCENT_TAX', formattax($tax));
    }
}

if (!function_exists('productAdditionalTaxInfo')) {
    function productAdditionalTaxInfo($key,$tax, $display_price = null) {
		$tmp=explode('_',substr($key,15,strlen($key)));
		$tax_title=JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name." ";
		$percent=$tmp[1];						
        if (!isset($display_price)) {
            $jshopConfig = JSFactory::getConfig();
            $display_price = $jshopConfig->display_price_front_current;
        }
    
        if ($display_price == 0) {
            return JText::sprintf('COM_SMARTSHOP_INC_PERCENT_ADDITIONAL_TAX', formattax($tax)).$tax_title;
        }
            
        return JText::sprintf('COM_SMARTSHOP_PLUS_PERCENT_ADDITIONAL_TAX', formattax($tax)).$tax_title;
    }
}

if (!function_exists('displayTotalCartTaxName')) {
    function displayTotalCartTaxName($display_price = null) {
        if (!isset($display_price)) {
            $jshopConfig = JSFactory::getConfig();
            $display_price = $jshopConfig->display_price_front_current;
        }
    
        if ($display_price == 0) {
            return JText::_('COM_SMARTSHOP_INC_TAX');
        }
    
        return JText::_('COM_SMARTSHOP_PLUS_TAX');
    }
}

if (!function_exists('displayTotalCartTax')) {
    function displayTotalCartTax($display_price = null) {
        if (!isset($display_price)) {
            $jshopConfig = JSFactory::getConfig();
            $display_price = $jshopConfig->display_price_front_current;
        }
    
        if ($display_price == 0) {
            return JText::_('COM_SMARTSHOP_INC');
        }
    
        return JText::_('COM_SMARTSHOP_PLUS');
    }
}

if (!function_exists('getPriceTaxValue')) {
    function getPriceTaxValue($price, $tax, $price_netto = 0) {
        if ($price_netto == 0) {
            $tax_value = $price * $tax / (100 + $tax);
        } else {
            $tax_value = $price * $tax / 100;
        }
    
        return $tax_value;
    }
}

if (!function_exists('getCorrectedPriceForQueryFilter')) {
    function getCorrectedPriceForQueryFilter($price) {
        $jshopConfig = JSFactory::getConfig();
    
        $taxes = JSFactory::getAllTaxes();
        $taxlist = array_values($taxes);
        $tax = $taxlist['0'];
		$price = $price ? $price : 0;
    
        if ($jshopConfig->display_price_admin == 1 && $jshopConfig->display_price_front_current == 0) {
            $price = $price / (1 + $tax / 100);
        }
    
        if ($jshopConfig->display_price_admin == 0 && $jshopConfig->display_price_front_current == 1) {
            $price = $price * (1 + $tax / 100);
        }
        if(!$price) $price = 0;
        $price = $price / $jshopConfig->currency_value;

        return $price;
    }
}

if (!function_exists('updateAllprices')) {
    function updateAllprices($ignore = []) {
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load();
        $cart->updateCartProductPrice();
        $sh_pr_method_id = $cart->getShippingPrId();
    
        if ($sh_pr_method_id) {
            $shipping_method_price = JSFactory::getTable('shippingMethodPrice', 'jshop');
            $shipping_method_price->load($sh_pr_method_id);
            $prices = $shipping_method_price->calculateSum($cart);
            $cart->setShippingsDatas($prices, $shipping_method_price);
        }
    
        $payment_method_id = $cart->getPaymentId();
        if ($payment_method_id) {
            $paym_method = JSFactory::getTable('paymentmethod', 'jshop');
            $paym_method->load($payment_method_id);
            $paym_method->setCart($cart);
            $price = $paym_method->getPrice();
            $cart->setPaymentDatas($price, $paym_method);
        }
        
        $cart = JSFactory::getModel('cart', 'jshop');
        $cart->load('wishlist');
        $cart->updateCartProductPrice();
    }
}

if (!function_exists('setNextUpdatePrices')) {
    function setNextUpdatePrices() {
        $session =JFactory::getSession();
        $session->set('js_update_all_price', 1);
    }
}

if (!function_exists('getMysqlVersion')) {
    function getMysqlVersion() {
        $session =JFactory::getSession();
        $mysqlversion = $session->get('js_get_mysqlversion');
    
        if ($mysqlversion == '') {
            $db = \JFactory::getDBO(); 
            $query = 'select version() as v';
            $db->setQuery($query);
            $mysqlversion = $db->loadResult();
            preg_match('/\d+\.\d+\.\d+/',$mysqlversion,$matches);
            $mysqlversion = $matches['0'];
            $session->set('js_get_mysqlversion', $mysqlversion);
        }    
    
        return $mysqlversion;    
    }
}

if (!function_exists('filterAllowValue')) {
    function filterAllowValue($data, $type) {
    
        if ($type == 'int+') {
            if (is_array($data)) {
                foreach($data as $k => $v) {
                    $v = intval($v);
    
                    if ($v > 0) {
                        $data[$k] = $v;
                    } else {
                        unset($data[$k]);
                    }
                }
            }
        }
        
        if ($type == 'array_int_k_v+') {
            if (is_array($data)) {
                foreach($data as $k => $v) {
                    $k = intval($k);
    
                    if (is_array($v)) {
                        foreach($v as $k2 => $v2) {
                            $k2 = intval($k2);
                            $v2 = intval($v2);
    
                            if ($v2 > 0) {
                                $data[$k][$k2] = $v2;
                            } else {
                                unset($data[$k][$k2]);
                            }
                        }
                    }
                }
            }
        }
        
        return $data;
    }
}

if (!function_exists('getListFromStr')) {
    function getListFromStr($stelist) {
        if (is_array($stelist)) {
            return filterAllowValue($stelist, 'int+');
        }

        if (preg_match('/\,/', $stelist)) {
            return filterAllowValue(explode(',', $stelist), 'int+');
        }
    }
}

if (!function_exists('willBeUseFilter')) {
    function willBeUseFilter($filters) {
        $res = 0;    
    
        if (isset($filters['price_from']) && !empty($filters['price_from'])) $res = 1;
        if (isset($filters['price_to']) && !empty($filters['price_to'])) $res = 1;
        if (isset($filters['categorys']) && !empty($filters['categorys'])) $res = 1;
        if (isset($filters['manufacturers']) && !empty($filters['manufacturers'])) $res = 1;
        if (isset($filters['vendors']) && !empty($filters['vendors'])) $res = 1;    
        if (isset($filters['labels']) && !empty($filters['labels'])) $res = 1;
        if (isset($filters['extra_fields']) && !empty($filters['extra_fields'])) $res = 1;
        \JFactory::getApplication()->triggerEvent('onAfterWillBeUseFilterFunc', [&$filters, &$res]);
    
        return $res;
    }
}

if (!function_exists('getQueryListProductsExtraFields')) {
    /**
    * spec function additional query for product list 
    */
    function getQueryListProductsExtraFields() {
        $query = '';
        $list = JSFactory::getAllProductExtraField();
        $jshopConfig = JSFactory::getConfig();
        $config_list = $jshopConfig->getProductListDisplayExtraFields();

        foreach($list as $v) {
            if (in_array($v->id, $config_list)) {
                $query .= ", prod.`extra_field_{$v->id}` ";
            }
        }

        return $query;
    }
}

if (!function_exists('getLicenseKeyAddon')) {
    function getLicenseKeyAddon($alias) {
        static $keys;
    
        if (!isset($keys)) {
            $keys = [];
        }
    
        if (!isset($keys[$alias])) {
            $addon = JSFactory::getTable('addon', 'jshop');
            $keys[$alias] = $addon->getKeyForAlias($alias);
        }
    
        return $keys[$alias];
    }
}

if (!function_exists('getQuerySortDirection')) {
    function getQuerySortDirection($fieldnum, $ordernum) {
        $dir = 'ASC';
    
        if ($ordernum) {
            $dir = 'DESC';
    
            if ($fieldnum == 5 || $fieldnum == 6) {
                $dir = 'ASC';
            }
        } else {
            $dir = 'ASC';
    
            if ($fieldnum == 5 || $fieldnum == 6) {
                $dir = 'DESC';
            }
        }
    
        return $dir;
    }
}

if (!function_exists('getImgSortDirection')) {
    function getImgSortDirection($fieldnum, $ordernum) {
        if ($ordernum) {
            return 'arrow_down.gif';
        }
    
        return 'arrow_up.gif';
    }
}

if (!function_exists('printContent')) {
    function printContent() {
        $print = JFactory::getApplication()->input->getInt('print'); 
        $link =  str_replace('&', '&amp;', $_SERVER['REQUEST_URI']);	
		
		$html = renderTemplate([
                templateOverrideBlock('blocks', 'print_content.php', 1)
            ], 'print_content', [
                'link' => $link,
                'print' =>  $print
            ]);
            
        echo $html;
    }
}

if (!function_exists('getPageHeaderOfParams')) {
    function getPageHeaderOfParams(&$params) {
        $header = '';
    
        if ($params->get('show_page_heading') && $params->get('page_heading')) {
            $header = $params->get('page_heading');
        }
    
        return $header;
    }
}

if (!function_exists('getMessageJson')) {
    function getMessageJson() {
        $errors = class_exists('JError') ? JError::getErrors() : [];
        $rows = [];
    
        foreach($errors as $e) {
            $message = str_replace('<br/>', "\n", $e->get('message'));
            $rows[] = [
                'level' => $e->get('level'),
                'code' => $e->get('code'), 
                'message' => $message
            ];
        }
    
        return json_encode($rows);
    }
}

if (!function_exists('getOkMessageJson')) {
    function getOkMessageJson($cart) {
        $errors = class_exists('JError') ? JError::getErrors() : [];
    
        if (!empty($errors)) {
            return getMessageJson(); 
        }
        return json_encode(prepareView($cart));
    }
} 

if (!function_exists('getAccessGroups')) {
    function getAccessGroups() {
        $db = \JFactory::getDBO(); 
        $query = 'select id,title,rules from #__viewlevels order by ordering';
        $db->setQuery($query);
        $accessgroups = $db->loadObjectList();
    
        return $accessgroups;
    }
}

if (!function_exists('getDisplayPriceShop')) {
    function getDisplayPriceShop() {
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        $display_price = 1;
    
        if ($jshopConfig->displayprice == 1) {
            $display_price = 0;
        } elseif($jshopConfig->displayprice == 2 && !$user->id) {
            $display_price = 0;
        }
    
        return $display_price;
    }
}

if (!function_exists('getDisplayPriceForProduct')) {
    function getDisplayPriceForProduct($product_id, $price) {
		$db = \JFactory::getDBO();        
        $shopUser = JSFactory::getUser();
	    $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        $display_price = 1;			
		
		$modelOfProductsFront = JSFactory::getModel('ProductsFront');
		$params = [
			'usergroup_id' => JSFactory::getUser()->usergroup_id
		];
		$productTable = $modelOfProductsFront->getProductWithDefaultAttrs($product_id, $params);
		$isUsergroupPermissionShowPrice = $productTable->getUsergroupPermissions()->is_usergroup_show_price ?? false;

		if (!$isUsergroupPermissionShowPrice) {
			$display_price = 0;
		}
        
        return $display_price;
    }
}

if (!function_exists('getDisplayPriceForListProduct')) {
    function getDisplayPriceForListProduct() {
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        $display_price = 1;
    
        if ($jshopConfig->displayprice_for_list_product == 1) {
            $display_price = 0;
        } 
        
        return $display_price;
    }
}

if (!function_exists('getDocumentType')) {
    function getDocumentType() {
        return JFactory::getDocument()->getType();
    }
}

if (!function_exists('sprintAtributeInCart')) {
    function sprintAtributeInCart($atribute) {
        JPluginHelper::importPlugin('jshoppingproducts');
       
        $smartShopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $attributes = [];
            
        if (!empty($atribute)) {
            foreach($atribute as $k => $attr) {		
                $dispatcher->triggerEvent('beforeSprintAtributeInCart', [&$attr]);
        
                if ($attr->attr_type == 4) {
                    $attributes[$attr->attr_id][] = $attr;
                } else {
                    $attributes[$attr->attr_id] = $attr;
                }
            }
        }
    
        $view = new JViewLegacy();
        $view->atribute = $attributes;
        $dispatcher->triggerEvent('beforeSprintAtributeInCartView', array(&$view) );

        $html = renderTemplate([
            templateOverrideBlock('blocks', 'sprint_atribute.php', 1)
        ], 'sprint_atribute', [
            'atribute' => $view->atribute
        ]);

        return $html;
    }
}

if (!function_exists('getAtributeInCart')) {
    function getAtributeInCart($atribute) {
        JPluginHelper::importPlugin('jshoppingproducts');

        $smartShopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $attributes = [];

        if (!empty($atribute)) {
            foreach($atribute as $k => $attr) {
                $dispatcher->triggerEvent('beforeSprintAtributeInCart', [&$attr]);

                if ($attr->attr_type == 4) {
                    $attributes[$attr->attr_id][] = $attr;
                } else {
                    $attributes[$attr->attr_id] = $attr;
                }
            }
        }

        $view = new JViewLegacy();
        $view->atribute = $attributes;
        $dispatcher->triggerEvent('beforeSprintAtributeInCartView', array(&$view) );

        return $view->atribute;
    }
}

if (!function_exists('sprintFreeAtributeInCart')) {
    function sprintFreeAtributeInCart($freeatribute, $product_id, $prod_id_of_additional_val = 0) {
        JPluginHelper::importPlugin('jshoppingproducts');
    
        $smartShopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
		if($prod_id_of_additional_val){
			$db = JFactory::getDBO();
			$db->setQuery('SELECT `is_use_additional_free_attrs` FROM `#__jshopping_products` WHERE `product_id` =' . $prod_id_of_additional_val);
			if(!$db->loadResult()){	
				$prod_id_of_additional_val = $product_id;
			}
		}else{
			$prod_id_of_additional_val = $product_id;
		}
		
		$freeatribute = excludeHiddenAttr($freeatribute, $prod_id_of_additional_val);
		

        if(!empty($freeatribute)){
            foreach($freeatribute as $attr) {
                $dispatcher->triggerEvent('beforeSprintFreeAtributeInCart', [&$attr]);
            }
        }

     	$html = renderTemplate([
			templateOverrideBlock('blocks', 'sprint_free_atribute.php', 1)
		], 'sprint_free_atribute', [
			'freeatribute' => $freeatribute,
			'product_id' => $product_id
		]);
		
        return $html;
    }
}

if (!function_exists('sprintFreeExtraFiledsInCart')) {
    function sprintFreeExtraFiledsInCart($extra_fields) {
        JPluginHelper::importPlugin('jshoppingproducts');
        
        $smartShopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
    
        foreach($extra_fields as $field) {
            $dispatcher->triggerEvent('beforeSprintExtraFieldsInCart', [&$field]);
        }
    
     	$html = renderTemplate([
			templateOverrideBlock('blocks', 'sprint_free_extra_fields.php', 1)
		], 'sprint_free_extra_fields', [
			'extra_fields' => $extra_fields
		]);
    
        return $html;
    }
}

if (!function_exists('sprintAtributeInOrder')) {
    function sprintAtributeInOrder($atribute, $type = 'html') {
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher =\JFactory::getApplication();    
        $dispatcher->triggerEvent('beforeSprintAtributeInOrder', [&$atribute, $type]);
        $html = $atribute;
    
        if ($type == 'html') {
            $html = nl2br($atribute);
        }
    
        return $html;
    }
}


if (!function_exists('sprintFreeAtributeInOrder')) {
    function sprintFreeAtributeInOrder($freeatribute, $type = 'html') {
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('beforeSprintFreeAtributeInOrder', [&$freeatribute, $type]);
        $html = $freeatribute;
    
        if ($type == 'html') {
            $html = nl2br($freeatribute);
        }
    
        return $html;
    }
}


if (!function_exists('sprintExtraFiledsInOrder')) {
    function sprintExtraFiledsInOrder($extra_fields, $type = 'html') {
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher =\JFactory::getApplication();
        $dispatcher->triggerEvent('beforeSprintExtraFieldsInOrder', [&$extra_fields, $type]);
        $html = $extra_fields;
    
        if ($type == 'html') {
            $html = nl2br($extra_fields);
        }
    
        return $html;
    }
}

if (!function_exists('separateExtraFields')) {
    function separateExtraFields(array $extraFields, string $separator = ': ', $type = 'html', bool $isIncludeImage = false): string 
    {
        $result = '';

        if (!empty($extraFields)) {
            $dispatcher = \JFactory::getApplication();
            $dispatcher->triggerEvent('beforeSeparateExtraFields', [&$extraFields, &$type, &$separator]);

            $extraFields = array_map(function ($extraField) use ($separator, $isIncludeImage) {
                $extraField = (object)$extraField;
                $result = $extraField->name . $separator . $extraField->value;

                if (!$isIncludeImage) {
                    $result = preg_replace('/<.*><img .*><\/.*>/', '', $result);
                }
                return $result;
            }, $extraFields);
            $result = implode("\n", $extraFields);
            
            if ($type == 'html') {
                $result = nl2br($result);
            }

            $dispatcher->triggerEvent('afterSeparateExtraFields', [&$result, &$type, &$separator]);
        }
    
        return $result;
    }
}

if (!function_exists('separatePdfExtraFieldsWithUseCharactParams')) {
    function separatePdfExtraFieldsWithUseCharactParams(array $extraFields, string $separator = ': ', $type = 'html', bool $isIncludeImage = false): string 
    {
        $config = JSFactory::getConfig();
        $displayExtraFields = $config->getPdfDisplayExtraFields();
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('beforeSeparatePdfExtraFieldsWithUseCharactParams', [&$extraFields, &$type, &$separator, &$displayExtraFields]);
        
        $extraFields = array_filter($extraFields, function ($extraField) use($displayExtraFields) {
            return in_array($extraField->id, $displayExtraFields);
        });

        $result = separateExtraFields($extraFields, $separator, $type, $isIncludeImage);
        $dispatcher->triggerEvent('afterSeparatePdfExtraFieldsWithUseCharactParams', [$extraFields, &$result, &$type, &$separator, &$displayExtraFields]);

        return $result;
    }
}

if (!function_exists('separateMailExtraFieldsWithUseCharactParams')) {
    function separateMailExtraFieldsWithUseCharactParams(array $extraFields, string $separator = ': ', $type = 'html'): string 
    {
        $config = JSFactory::getConfig();
        $displayExtraFields = $config->getMailDisplayExtraFields();
        $dispatcher = \JFactory::getApplication();
        $isIncludeImage = false;
        $dispatcher->triggerEvent('beforeSeparateMailExtraFieldsWithUseCharactParams', [&$extraFields, &$type, &$separator, &$displayExtraFields]);
        
        $extraFields = array_filter($extraFields, function ($extraField) use($displayExtraFields) {
            return in_array($extraField->id, $displayExtraFields);
        });

        if (!in_array('mails', $config->getHideExtraFieldsImages())) {
            $isIncludeImage = true;
        }

        $result = separateExtraFields($extraFields, $separator, $type, $isIncludeImage);
        $dispatcher->triggerEvent('afterSeparateMailExtraFieldsWithUseCharactParams', [$extraFields, &$result, &$type, &$separator, &$displayExtraFields]);

        return $result;
    }
}

if (!function_exists('separateExtraFieldsWithUseHideImageCharactParams')) {
    function separateExtraFieldsWithUseHideImageCharactParams(array $extraFields, string $location = '', string $separator = ': ', $type = 'html'): string 
    {
        $config = JSFactory::getConfig();
        $displayExtraFields = $config->getHideExtraFieldsImages();
        $dispatcher = \JFactory::getApplication();
        $isIncludeImage = false;
        $dispatcher->triggerEvent('separateExtraFieldsWithUseHideImageCharactParams', [&$extraFields, &$type, &$separator, &$displayExtraFields]);

        if (!in_array($location, $displayExtraFields)) {
            $isIncludeImage = true;
        }

        $result = separateExtraFields($extraFields, $separator, $type, $isIncludeImage);
        $dispatcher->triggerEvent('afterSeparatePdfExtraFieldsWithUseCharactParams', [$extraFields, &$result, &$type, &$separator, &$displayExtraFields]);

        return $result;
    }
}

if (!function_exists('sprintEditorFiledsInOrder')) {
    function sprintEditorFiledsInOrder($prod, $type = 'html') {
		
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher =\JFactory::getApplication();
        $dispatcher->triggerEvent('beforeSprintEditorFieldsInOrder', [$prod,&$extra_fields, $type]);
        $html = $extra_fields;	
		$jshopConfig = &JSFactory::getConfig();
		$jshopConfig->loadCurrencyValue();
		$db = & JFactory::getDBO();
		$lang = &JFactory::getLanguage();
		$db->setQuery('SELECT `short_description_' . $lang->getTag() . '` as short_description FROM #__jshopping_products where product_id=' . $prod->product_id . ' AND editor_id>0');
		$p = $db->loadObjectList(); 
		if(!empty($p)){ ?>
			<div class="list_attribute">
			<?php                                        
			$prices = explode('<br>', $p[0]->short_description);
			foreach ($prices as $single_price) {
				if (strpos($single_price, '::') > 0) {
					$sum = substr($single_price, 0, strpos($single_price, '::'));
					$title = substr($single_price, strpos($single_price, '::') + 2, strlen($single_price));
				   /* echo "<p class=\"jshop_cart_attribute\"><span class=\"name\">$title</span> : <span class=\"value\">" . formatprice($sum * $jshopConfig->currency_value) . "</span></p>";*/
					if (($type == 'html')AND(trim($title)!="")) {
						$html.= "<p class=\"jshop_cart_attribute\"><span class=\"name\">$title</span>";
					}
					if (($type == 'pdf')AND(trim($title)!="")) {
						$html.= "\n$title";
					}
				}
			}
			?>
			</div>
		<?php }
        if ($type == 'pdf') {
            $dispatcher->triggerEvent('afterSprintEditorFieldsInOrder', [$prod,&$html]);
        }
		if ($type == 'html') {
            $html = nl2br($html);
        }
        return $html;
    }
}

if (!function_exists('sprintBasicPrice')) {
    function sprintBasicPrice($prod) {
        if (is_object($prod)) {
            $prod = (array)$prod;
        }
    
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('beforeSprintBasicPrice', [&$prod]);
        $html = '';
    
        if ($prod['basicprice'] > 0) {
            $html = formatprice($prod['basicprice']) . ' / ' . $prod['basicpriceunit'];
        }
    
        return $html;
    }
}

if (!function_exists('sprintPreviewNativeUploadedFiles')) {
    function sprintPreviewNativeUploadedFiles($arrWithUploadedData) {
        JPluginHelper::importPlugin('jshoppingproducts');
        $smartShopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();

        if(!empty($arrWithUploadedData['files'])) {
            foreach ($arrWithUploadedData['files'] as $key => $fileName) {
                if (!file_exists($smartShopConfig->files_upload_path . "/" . $fileName)) {
                    unset($arrWithUploadedData['files'][$key]);
                }
            }
        }
        if(!empty($arrWithUploadedData['previews'])) {
            foreach ($arrWithUploadedData['previews'] as $key => $fileName) {
                if (!file_exists($smartShopConfig->files_upload_path."/".$fileName)){
                    unset($arrWithUploadedData['previews'][$key]);
                }
            }
        }

		$html = renderTemplate([
			templateOverrideBlock('blocks', 'native_uploads_previews.php', 1)
		], 'native_uploads_previews', [
			'uploadData' => $arrWithUploadedData
		]);
    
        $dispatcher->triggerEvent('beforeReturnHtmlPreviewNativeUploadedFiles', [&$view, &$html, &$arrWithUploadedData]);
    
        return $html;
    }
}

if (!function_exists('sprintJsTemplateForNativeUploadedFiles')) {
    function sprintJsTemplateForNativeUploadedFiles($isMultiUpload) {
        JPluginHelper::importPlugin('jshoppingproducts');
        $smartShopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $uploadCommonSettings = JSFactory::getModel('upload')->getParams();
        
        $html = renderTemplate([
                templateOverrideBlock('blocks', 'native_upload_js_template.php', 1)
            ], 'native_upload_js_template', [
                'isMultiUpload' => $isMultiUpload,
                'uploadCommonSettings' =>  $uploadCommonSettings
            ]);
           ;
        $dispatcher->triggerEvent('beforeReturnHtmlJsTemplateForNativeUploadedFiles', [&$view, &$html]);
    
        return $html;
    }
}

if (!function_exists('sprintJsTemplateForNativeUploadedOrderFiles')) {
    function sprintJsTemplateForNativeUploadedOrderFiles($isMultiUpload, $uploadBlockNumber) {
        JPluginHelper::importPlugin('jshoppingproducts');
        $smartShopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $uploadCommonSettings = JSFactory::getModel('upload')->getParams();

        $html = renderTemplate([
                templateOverrideBlock('blocks', 'native_upload_order_js_template.php', 1)
            ], 'native_upload_order_js_template', [
                'isMultiUpload' => $isMultiUpload,
                'uploadCommonSettings' =>  $uploadCommonSettings,
                'uploadBlockNumber' => $uploadBlockNumber
            ]);
           ;
        $dispatcher->triggerEvent('beforeReturnHtmlJsTemplateForNativeUploadedFiles', [&$view, &$html]);

        return $html;
    }
}

if (!function_exists('getDataProductQtyInStock')) {
    function getDataProductQtyInStock($product) {
        $qty = $product->product_quantity;
    
        if ($product instanceof jshopProduct) {
            $qty = $product->getQty();
        }
    
        $qty = floatval($qty);
        $qty_in_stock = [
            'qty' => $qty, 
            'unlimited' => $product->unlimited
        ];
    
        if ($qty_in_stock['qty'] < 0) {
            $qty_in_stock['qty'] = 0;
        }
    
        return $qty_in_stock;
    }
}

if (!function_exists('sprintQtyInStock')) {
    function sprintQtyInStock($qty_in_stock) {
        if (!is_array($qty_in_stock)) {
            return $qty_in_stock;
        } else {
            if ($qty_in_stock['unlimited']) {
                return JText::_('COM_SMARTSHOP_UNLIMITED');
            }
    
            return $qty_in_stock['qty'];
        }
    }
}

if (!function_exists('getBuildFilterListProduct')) {
    function getBuildFilterListProduct($contextfilter, $no_filter = []) {
        $mainframe =JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        
        $category_id = JFactory::getApplication()->input->getInt('category_id');
        $manufacturer_id = JFactory::getApplication()->input->getInt('manufacturer_id');
        $label_id = JFactory::getApplication()->input->getInt('label_id');
        $vendor_id = JFactory::getApplication()->input->getInt('vendor_id');
        $price_from = saveAsPrice(JFactory::getApplication()->input->getVar('price_from'));
        $price_to = saveAsPrice(JFactory::getApplication()->input->getVar('price_to'));
        $number_of_products = saveAsPrice(JFactory::getApplication()->input->getVar('number_of_products'));	
        $products_list = JFactory::getApplication()->input->getVar('products_list');	
        
        $categorys = $mainframe->getUserStateFromRequest( $contextfilter . 'categorys', 'categorys', []);
        $categorys = filterAllowValue($categorys, 'int+');
        $tmpcd = getListFromStr(JFactory::getApplication()->input->getVar('category_id')); 
    
        if (is_array($tmpcd) && !$categorys) {
            $categorys = $tmpcd;
        }
        
        $manufacturers = $mainframe->getUserStateFromRequest( $contextfilter . 'manufacturers', 'manufacturers', []);
        $manufacturers = filterAllowValue($manufacturers, 'int+');
        $tmp = getListFromStr(JFactory::getApplication()->input->getVar('manufacturer_id'));    
        if (is_array($tmp) && !$manufacturers) {
            $manufacturers = $tmp;
        }
        
        $labels = $mainframe->getUserStateFromRequest($contextfilter . 'labels', 'labels', []);
        $labels = filterAllowValue($labels, 'int+');
        $tmplb = getListFromStr(JFactory::getApplication()->input->getVar('label_id'));    
        if (is_array($tmplb) && !$labels) {
            $labels = $tmplb;
        }
        
        $vendors = $mainframe->getUserStateFromRequest( $contextfilter . 'vendors', 'vendors', []);
        $vendors = filterAllowValue($vendors, 'int+');
        $tmp = getListFromStr(JFactory::getApplication()->input->getVar('vendor_id'));    
        if (is_array($tmp) && !$vendors) {
            $vendors = $tmp;
        }
        
        if ($jshopConfig->admin_show_product_extra_field) {
            $extra_fields = $mainframe->getUserStateFromRequest($contextfilter . 'extra_fields', 'extra_fields', []);
            $extra_fields = filterAllowValue($extra_fields, 'array_int_k_v+');
        }
    
        $fprice_from = $mainframe->getUserStateFromRequest($contextfilter . 'fprice_from', 'fprice_from');
        $fprice_from = saveAsPrice($fprice_from);
    
        if (!$fprice_from) {
            $fprice_from = $price_from;
        }
    
        $fprice_to = $mainframe->getUserStateFromRequest($contextfilter . 'fprice_to', 'fprice_to');
        $fprice_to = saveAsPrice($fprice_to);
        if (!$fprice_to) {
            $fprice_to = $price_to;
        }
        
        $filters = [
            'categorys' => $categorys,
            'manufacturers' => $manufacturers,
            'price_from' => $fprice_from,
            'price_to' => $fprice_to,
            'products_list' => $products_list ?: null,
            'number_of_products' => $number_of_products,
            'labels' => $labels,
            'vendors' => $vendors
        ];
    
        if ($jshopConfig->admin_show_product_extra_field) {
            if (is_array($extra_fields)) {
                $filters['extra_fields'][] = reset($extra_fields);
            } else {
                $filters['extra_fields'][] = $extra_fields;
            }
        }
    
        if ($category_id && !$filters['categorys']) {
            if (is_array($category_id)) {
                $filters['categorys'][] = reset($category_id);
            } else {
                $filters['categorys'][] = $category_id;
            }
        }
    
        if ($manufacturer_id && !$filters['manufacturers']) {
            if (is_array($manufacturer_id)) {
                $filters['manufacturers'][] = reset($manufacturer_id);
            } else {
                $filters['manufacturers'][] = $manufacturer_id;
            }
        }
    
        if ($label_id && !$filters['labels']) {
            if (is_array($label_id)) {
                $filters['labels'][] = reset($label_id);
            } else {
                $filters['labels'][] = $label_id;
            }
        }
    
        if ($vendor_id && !$filters['vendors']) {
            if (is_array($vendor_id)) {
                $filters['vendors'][] = reset($vendor_id);
            } else {
                $filters['vendors'][] = $vendor_id;
            }
        }
    
        if (is_array($filters['vendors'])) {
            $main_vendor = JSFactory::getMainVendor();
    
            foreach($filters['vendors'] as $vid) {
                if ($vid == $main_vendor->id) {
                    $filters['vendors'][] = 0;
                }
            }
        }
    
        foreach($no_filter as $filterkey) {
            unset($filters[$filterkey]);
        }
    
        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher =\JFactory::getApplication();
        $dispatcher->triggerEvent('afterGetBuildFilterListProduct', [&$filters]);
    
        return $filters;
    }
}

if (!function_exists('fixRealVendorId')) {
    function fixRealVendorId($id) {
        if ($id == 0) {
            $mainvendor = JSFactory::getMainVendor();
            $id = $mainvendor->id;
        }
    
        return $id;
    }
}

if (!function_exists('xhtmlUrl')) {
    function xhtmlUrl($url, $filter = 1) {
        if ($filter) {
            $url = jsFilterUrl($url);
        }
    
        $url = str_replace('&', '&amp;', $url);
    
        return $url;
    }
}

if (!function_exists('jsFilterUrl')) {
    function jsFilterUrl($url) {
        return strip_tags($url);
    }
}

if (!function_exists('getJsDate')) {
    function getJsDate($date = 'now', $format = 'Y-m-d H:i:s', $local = true) {
        $config = JFactory::getConfig();
        $date = JFactory::getDate($date, $config->get('offset'));
    
        return $date->format($format, $local);
    }
}

if (!function_exists('getCalculateDeliveryDay')) {
    function getCalculateDeliveryDay($day, $date = null) {
        if (!$date) {
            $date = getJsDate();
        }
    
        $time = intval(strtotime($date) + $day * 86400);
    
        return date('Y-m-d H:i:s', $time);
    }
}

if (!function_exists('datenull')) {
    function datenull($date) {
        return (substr($date, 0, 1) == '0');
    }
}

if (!function_exists('file_get_content_curl')) {
    function file_get_content_curl($url, $timeout = 5) {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $str = curl_exec($ch);
            curl_close($ch);
    
            return $str;
        }
    }
}

if (!function_exists('getJsDateDB')) {
    function getJsDateDB($str, $format = '%d.%m.%Y') {
        $f = str_replace(['%d', '%m', '%Y'], ['dd', 'mm', 'yyyy'], $format);
        $pos = [strpos($f, 'y'), strpos($f, 'm'), strpos($f, 'd')];
        $date = substr($str, $pos['0'], 4) . '-' . substr($str, $pos['1'], 2) . '-' . substr($str, $pos['2'], 2);
    
        return $date;
    }
}

if (!function_exists('getDisplayDate')) {
    function getDisplayDate($date, $format = '%d.%m.%Y') {
        if (datenull($date)) {
            return '';
        }
    
        $adate = [substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2)];
        $str = str_replace(['%Y', '%m', '%d'], $adate, $format);
        return $str;
    }
}

if (!function_exists('getPatchProductImage')) {
    function getPatchProductImage($name, $prefix = '', $patchtype = 0,$image_thumb="") {
        $jshopConfig = JSFactory::getConfig();
    
        if ($name == '' || JSFactory::getJSUri()->isUrl($name)) {
            return $name;
        }
    
        if ($prefix != '' && ($prefix != 'thumb_' && $prefix != 'thumb')) {
            $name = "{$prefix}_{$name}";
        }
        
        if ($image_thumb != ''){
            $name = $image_thumb;
        }

        $name = ltrim($name, '/');
    
        if (($patchtype == 1)&&(strpos($name,'ttp:')!=1)&&(strpos($name,'ttps:')!=1)) {
            $name = '/' . $name;
        }
    
        if ($patchtype == 2) {
            $name = "{$jshopConfig->image_product_path}/{$name}";
        }

        if ($patchtype == 3) {
            $name = JUri::base() . $name;
        }

        if ($patchtype == 4) {
            $name = JPATH_ROOT . '/' . $name;
        }
		
        if ($patchtype == 5) {
			if(strpos($name, '/') == false){
				$name = "{$jshopConfig->demo_product_live_path}/{$name}";
			}
        }
    
        return $name;
    }
}

if (!function_exists('getDBFieldNameFromConfig')) {
    function getDBFieldNameFromConfig($name) {
        $lang = JSFactory::getLang();
        $tmp = explode('.', $name);
        $field = $tmp['0'];
        $res = '';
    
        if (count($tmp) > 1) {
            $res = $tmp['0'] . '.';
            $field = $tmp['1'];
        }
        $tmp2 = explode(':', $field);
    
        if (count($tmp2) > 1 && $tmp2['0'] == 'ml'){
            $res .= "`{$lang->get($tmp2['1'])}`";
        } else {
            $res .= "`{$field}`";
        }
    
        return $res;
    }
}

if (!function_exists('json_value_encode')) {
    function json_value_encode($val, $textfix = 0) {
        if ($textfix) {
            $val = str_replace(array("\n","\r","\t"), '', $val);
        }
    
        $val = str_replace('"', '\"', $val);
        return $val;
    }
}

if (!function_exists('getRoundPriceProduct')) {
    function getRoundPriceProduct($price) {
        $jshopConfig = JSFactory::getConfig();
        
        if ($jshopConfig->price_product_round) {
            $price = round($price, $jshopConfig->decimal_count);
        }
        
        return $price;
    }
}

if (!function_exists('getFileType')) {
    function getFileType($imagePath){
        $path_parts=pathinfo($imagePath);
        return strtoupper($path_parts['extension']);
    }
}

if (!function_exists('imageSizes')) {
    function imageSizes($imagePath) {	
        switch (getFileType($imagePath)){
            case 'JPG':
            case 'JPEG':
            case 'PNG':
            case 'GIF':
                $imageSizes = getimagesize($imagePath);
    
                if ( !empty($imageSizes) ) {
                    $imageW = $imageSizes['0'];
                    $imageH = $imageSizes['1'];
    
                    return [
                        'width' => $imageW,
                        'height' => $imageH,
                        'sizes' => "{$imageW}x{$imageH}"
                    ];        
                }
        }
    }
}

if (!function_exists('getListSpecifiedAttrsFromArray')) {
    function getListSpecifiedAttrsFromArray($array, $attrName) {
        $result = [];
    
        if ( is_array($array) ) {
            foreach($array as $key => $obj) {
                if ( is_object($obj) && isset($obj->$attrName) ) {
                    $result[] = $obj->$attrName;
                }
            }        
        }
    
        return $result;
    }
}

if (!function_exists('getListOfValuesByArrKey')) {
    function getListOfValuesByArrKey($array, $keyName) {
        $result = [];
    
        if ( is_array($array) ) {
            foreach($array as $arr) {
                if (isset($arr[$keyName])) {
                    $result[] = $arr[$keyName];
                }
            }        
        }
    
        return $result;
    }
}

if (!function_exists('getChangedArrKeyOnObjVal')) {
    function getChangedArrKeyOnObjVal($array, $attrName) {
        $result = [];
    
        if ( is_array($array) ) {
            foreach($array as $key => $obj) {
                if ( is_object($obj) && isset($obj->$attrName) ) {
                    $result[$obj->$attrName] = $obj;
                }
            }        
        }
    
        return $result;    
    }
}

if (!function_exists('getArrsWhereValueEqual')) {
    function getArrsWhereValueEqual(array $list, $keyName, $equalVal, bool $isStrictComparison = false) {
        $result = [];
    
        foreach ($list as $key => $element) {
            $element = is_object($element) ? (array) $element : $element;
    
            if (array_key_exists($keyName, $element)) {
                $isEqual = $isStrictComparison ? $element[$keyName] === $equalVal : $element[$keyName] == $equalVal;
    
                if ($isEqual) {
                    $result[] = $list[$key];
                }
            }
        }
    
        return $result;
    }
}

if (!function_exists('renderTemplate')) {
    /**
    *   @param array $pathesToTemplates
    *   @param string $templateName
    *   @param array $dataToInsert
    *
    *   @return string
    */
    function renderTemplate(array $pathesToTemplates, $templateName, array $dataToInsert = [], $templateF = '') {
        $template = new JViewLegacy();
        
        foreach($pathesToTemplates as $key => $templatePath) {
            if($templateF){
                $template->addTemplatePath(viewOverride($templateF,$templateName));			
            }else{
                if ( !empty($templatePath) ) {
                    $template->addTemplatePath($templatePath);
                }
            }
        }

        $template->setLayout($templateName);

        if (!empty($dataToInsert)) {
            foreach ($dataToInsert as $key => $value) {
                $template->set($key, $value);
            }
        }

        return $template->loadTemplate();
    }
}
if (!function_exists('renderTemplateReact')) {
    /**
    *   @param array $pathesToTemplates
    *   @param string $templateName
    *   @param array $dataToInsert
    *
    *   @return string
    */
    function renderTemplateReact(array $pathesToTemplates, $templateName, array $dataToInsert = [], $templateF = '') {
        $template = new JViewLegacy();
        $document = JFactory::getDocument();

        foreach($pathesToTemplates as $key => $templatePath) {
            if($templateF){
                $template->addTemplatePath(viewOverride($templateF,$templateName));
            }else{
                if ( !empty($templatePath) ) {
                    $template->addTemplatePath($templatePath);
                }
            }
        }

        $template->setLayout($templateName);

        if (!empty($dataToInsert)) {
            foreach ($dataToInsert as $key => $value) {
                $template->set($key, $value);
            }
        }
        $document->addScriptDeclaration('const data'.$dataToInsert['component'].'='.json_encode($template));

        return $template->loadTemplate();
    }
}

if (!function_exists('renderTemplateEmail')) {
    function renderTemplateEmail($templateName, array $dataToInsert = [], $templateF = 'emails') {
        $template = new JViewLegacy();
        if($template){
            $template->addTemplatePath(viewOverride($templateF, $templateName.'.php'));
        }
        
        $template->setLayout($templateName);
        
        if (!empty($dataToInsert)) {
            foreach ($dataToInsert as $key => $value) {
                $template->set($key, $value);
            }
        }
        
        return $template->loadTemplate();
    }
}

if (!function_exists('getAddonParams')) {
    /**
    *   @param string $alias - addon alias
    *
    *   @return array
    */
    function getAddonParams($alias) {
        $addon = JTable::getInstance('addon', 'jshop');
        $addon->loadAlias($alias);
        
        return $addon->getParams();     
    }
}

if (!function_exists('getCurrentLangTag')) {
    /**
    *   @return string
    */
    function getCurrentLangTag() {
        $lang = JFactory::getLanguage();
        
        return $lang->getTag();
    }   
}

if (!function_exists('includeIniLangFiles')) {
    function includeIniLangFiles($extensionName, $langTag = '', $pathToLangFolder = JPATH_ROOT) {
        $language = &JFactory::getLanguage();
        $langTag = $langTag ?: $language->getTag();
    
        return $language->load($extensionName, $pathToLangFolder, $langTag, true);
    }
}

if (!function_exists('loadLangsFiles')) {
    function loadLangsFiles(string $side = 'site', ?string $langTag = null, bool $isReload = false, bool $isReloadOverrideBySideArgumentType = true) {
        $pathToLangs = ($side == 'site') ? JPATH_ROOT : JPATH_ADMINISTRATOR;
        $language = JFactory::getLanguage();
        $langTag = $langTag ?: $language->getTag();

        if ($isReloadOverrideBySideArgumentType ) {
            $pathToOverrideFile = $pathToLangs . '/language/overrides/' . $langTag . '.override.ini';

            $reflectedLang = new ReflectionClass($language);
            $property = $reflectedLang->getProperty('override');
            $property->setAccessible(true);
            $property->setValue($language, \JLanguageHelper::parseIniFile($pathToOverrideFile, false));
        }
        
        return $language->load('com_jshopping', $pathToLangs, $langTag, $isReload);     
    }
}

if ( !function_exists('loadJsFilesLightBoxAddonOfferAndOrder') ) {
    function loadJsFilesLightBoxAddonOfferAndOrder() {
        static $load;

        if(!$load){
            $document = JFactory::getDocument();
            $usershop = JSFactory::getUserShop();
            $jshopConfig = JSFactory::getConfig();

            $document->addScriptDeclaration('var emailShareDefaultEmail = \'' . (string)$usershop->email . '\';');

            if ($jshopConfig->shop_mode) {
                $document->addScript(JURI::root() . 'components/com_jshopping/js/src/jquery/photoswipe.addon_offer_and_order.min.js');
                $document->addScript(JURI::root() . 'components/com_jshopping/js/src/jquery/photoswipe-ui-default.min.js');
            }

            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_EMAIL_SHARE_SUCCESS_MESSAGE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_EMAIL_SHARE_ERROR_MESSAGE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_EMAIL_SHARE_EMAIL_CHECK_ERROR_MESSAGE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_BUTTON_CLOSE_TITLE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_BUTTON_MAIL_TITLE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_BUTTON_DOWNLOAD_TITLE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_BUTTON_FULLSCREEN_TITLE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_BUTTON_ZOOM_TITLE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_BUTTON_PREV_TITLE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_BUTTON_NEXT_TITLE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_EMAIL_SHARE_TITLE', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_EMAIL_SHARE_CANCEL', true);
            JText::script('COM_SMARTSHOP_OFFER_AND_ORDER_EMAIL_SHARE_SEND', true);
            
            $load = 1;
        }  
    }      
}

if (!function_exists('getJoomlaTemplateName')) {
    function getJoomlaTemplateName($side = 'site') {
        return getJoomlaHomeTemplateName($side);
    }
}

if (!function_exists('getJoomlaHomeTemplateName')) {
    function getJoomlaHomeTemplateName($side = 'site') {
        $db = \JFactory::getDBO();
    
        $sql = 'SELECT `template` FROM `#__template_styles` WHERE `home` = 1 AND `client_id` = ';
        if ( $side === 'site' ) {
            $sql .= 0;
        } else {
            $sql .= 1;
        }
    
        $db->setQuery($sql);
    
        return $db->loadResult();
    }
}

if (!function_exists('getJoomlaTemplatesNames')) {
    function getJoomlaTemplatesNames($side = 'site') {
        $db = \JFactory::getDBO();
        $result = [];
    
        $sql = 'SELECT `template` FROM `#__template_styles` WHERE `client_id` = ';
        $sql .= ($side === 'site') ? 0 : 1;
        $queryResult = $db->setQuery($sql)->loadRowList();
    
        if (!empty($queryResult)) {
            foreach ($queryResult as $names) {
                if (isset($names['0'])) {
                    $result[] = $names['0'];
                }
            }
        }
    
        return $result;
    }
}

if (!function_exists('getFilesList')) {
    function getFilesList(string $pathToFolder) {
        $result = [];
    
        if (file_exists($pathToFolder)) {
            if ($resource = opendir($pathToFolder)) {
    
                while (false !== ($fileName = readdir($resource))) {
                    $pathToFile = "{$pathToFolder}/{$fileName}";
    
                    if (is_file($pathToFile)) {
                        $result[] = $fileName;
                    }
                }
    
            }
        }
    
        return $result;
    }
}

if (!function_exists('getListOfTmplsCssFiles')) {
    function getListOfTmplsCssFiles()
    {
        $groups = [
            [
                'items' => [
                    ' - ' . JText::_('COM_SMARTSHOP_ADDON_SHOPS_NOT_SET') . ' - '
                ]
            ]
        ];

        $tmplsNames = getJoomlaTemplatesNames();
        if (!empty($tmplsNames)) {
            $groupNumb = 1;
            $elementNumb = 1;

            foreach($tmplsNames as $tmplName) {
                if (!empty($tmplName)) {
                    $urlCssFolder = '/templates/' . $tmplName . '/css';
                    $folderFiles = getFilesList(JPATH_ROOT . $urlCssFolder);

                    $groups[$groupNumb]['url'] = $urlCssFolder;
                    $groups[$groupNumb]['tmplName'] = $tmplName;
                    $groups[$groupNumb]['text'] = $tmplName;
                    if (!empty($folderFiles)) {
                        foreach($folderFiles as $fileName) {
                            $groups[$groupNumb]['items'][$elementNumb] = $fileName;
                            $elementNumb++;
                        }
                    }
                }

                $groupNumb++;
            }
        }

        return $groups;
    }
}

if (!function_exists('raiseMsgForUser')) {
    function raiseMsgForUser($isSuccessMsg, $msgs) {

        $successMsg = $msgs['success'] ?: '';
        $failMsg = $msgs['fail'] ?: '';
        $codeFailMsg = $msgs['failCode'] ?: 0;
    
        if ($isSuccessMsg) {
            JFactory::getApplication()->enqueueMessage($successMsg);
        } else {
            throw new \Exception($failMsg, $codeFailMsg);
        }
        
    }
}

if (!function_exists('raiseMsgsWithOneTypeStatus')) {
    function raiseMsgsWithOneTypeStatus(array $msgs, string $type = 'message') {
    
        if (!empty($msgs) ) {
            $app = JFactory::getApplication();
    
            foreach($msgs as $msg) {
                if (!empty($msg)) {
                    $app->enqueueMessage($msg, $type);
                }
            }
    
            return true;
        }
    
        return false;
    }
}

if (!function_exists('redirectMsgsWithOneTypeStatus')) {
    function redirectMsgsWithOneTypeStatus(array $msgs, string $url, string $type = 'message', bool $isUseSef = true) {
        raiseMsgsWithOneTypeStatus($msgs, $type);
        $url = $isUseSef ? SEFLink($url, 0, 1, JSFactory::getConfig()->use_ssl) : $url;
        JFactory::getApplication()->redirect($url); 
    }
}

if (!function_exists('raiseWarningRedirect')) {
    function raiseWarningRedirect($warningMessageText, $url, $errorCode = '') {
        //JError::raiseWarning($errorCode, $warningMessageText);
		\JFactory::getApplication()->enqueueMessage($warningMessageText);
        JFactory::getApplication()->redirect(SEFLink($url, 0, 1, JSFactory::getConfig()->use_ssl));         
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($chars), 0, $length);
    }
}

if (!function_exists('sprintHiddenList')) {
    function sprintHiddenList($list, $name, $params, $key, $val, $actived = null, $separator = ' ') {
        $html = '';
        $smartShopConfig = JSFactory::getConfig();
        $id = str_replace('[', '', $name);
        $id = str_replace(']', '', $id);    
		
		$html = renderTemplate([
                templateOverrideBlock('blocks', 'sprint_hidden_list.php', 1)
            ], 'sprint_hidden_list', [
                'lst' => $list,
                'name' => $name,
                'params' => $params,
                'key' => $key,
                'val' => $val,
                'actived' => $actived,
                'separator' => $separator,
                'id' => $id
            ]);		
    
        return $html;
    }
}

if (!function_exists('sprintCheckboxList')) {
    function sprintCheckboxList($list, $name, $params, $key, $val, $actived = null, $separator = ' ') {
        $html = '';
        $smartShopConfig = JSFactory::getConfig();
        $id = str_replace('[', '', $name);
        $id = str_replace(']', '', $id);     
	
		$html = renderTemplate([
                templateOverrideBlock('blocks', 'sprint_checkbox_list.php', 1)
            ], 'sprint_checkbox_list', [
                'lst' => $list,
                'name' => $name,
                'params' => $params,
                'key' => $key,
                'val' => $val,
                'actived' => $actived,
                'separator' => $separator,
                'id' => $id
            ]);
			
        return $html;
    }
}

if (!function_exists('getSmartLinkForListProducts')) {
    function getSmartLinkForListProducts($productId) {
        $db = \JFactory::getDBO();
        $db->setQuery('SELECT `editor_id`, `open_type` , `epp_id` FROM `#__ee_editors_to_products` WHERE `product_id` =' . $productId);
        $ee = $db->loadObject();
        $db->setQuery('SELECT `editor_id`, `product_type_view` FROM `#__jshopping_products` WHERE `product_id` =' . $productId);
        $jprod_ee = $db->loadObject();
        $smart_link = '';
    
        if (!empty($ee->editor_id) && ($ee->open_type == 0)) {
            $smart_link = JRoute::_("index.php?option=com_expresseditor&task=editor&editor_id={$ee->editor_id}&product_id={$productId}&product_id={$ee->epp_id}");
        } elseif (!empty($jprod_ee->editor_id) && ( $jprod_ee->product_type_view == 0)) {
            $smart_link = JRoute::_("index.php?option=com_expresseditor&task=editor&editor_id={$jprod_ee->editor_id}&product_id={$productId}");
        }
    
        return $smart_link;
    }
}

if (!function_exists('getSmartLinkForProductPage')) {
    function getSmartLinkForProductPage($product) {
        $db = \JFactory::getDBO();
        $db->setQuery('SELECT * FROM `#__ee_editors_to_products` WHERE `product_id` = ' . $product->product_id);
        $ee = $db->loadObject();
        $smart_link = '';
    
        if (!empty($ee->editor_id) && ($ee->product_type_view == 0) && ($ee->enable == 1)) {
            $smart_link = JRoute::_("index.php?option=com_expresseditor&task=editor&editor_id={$ee->editor_id}&product_id={$ee->product_id}&product={$product}&epp_id={$ee->epp_id}");
        } elseif (!empty($product->editor_id) && ($product->product_type_view == 0)) {
            $smart_link = JRoute::_("index.php?option=com_expresseditor&task=editor&editor_id={$product->editor_id}&product_id={$product->product_id}&product={$product}&epp_id={$ee->epp_id}");
        }
    
        return $smart_link;
    }
}

if (!function_exists('getAdditionalProdIdIfAdditionalFreeAttrsActivated')) {
    function getAdditionalProdIdIfAdditionalFreeAttrsActivated($parentProdId, $attrsIds) {
        $productTable = JSFactory::getTable('product');
        $loadProductStatus = $productTable->load($parentProdId);
        $productTable->setAttributeActive($attrsIds);
        
        if ($loadProductStatus && $productTable->isUseAdditionalFreeAttrs() && !empty($productTable->getAdditionalProductId())) {
            return $productTable->getAdditionalProductId();
        }
    
        return 0;
    }
}

if (!function_exists('generateSortingHtmlSelectForProduct')) {
    function generateSortingHtmlSelectForProduct($numbOfSelectedVal = 1, int $sortingVariants = 1) {
        $jshopConfig = JSFactory::getConfig();
        $selects = $jshopConfig->sorting_products_name_s_select;
        $sorts = [];
    
        if ($sortingVariants == 1) {
            $selects = $jshopConfig->sorting_products_name_select;
        }
    
        foreach($selects as $key => $name){
            $sorts[] = JHTML::_('select.option', $key, JText::_($name), 'sort_id', 'sort_value');
        }
    
        return JHTML::_('select.genericlist', $sorts, 'order', 'class = "inputbox form-select" size = "1" onchange = "submitListProductFilters()"','sort_id', 'sort_value', $numbOfSelectedVal);
    }
}

if (!function_exists('generateProductHtmlCountSelectFilter')) {
    function generateProductHtmlCountSelectFilter($limit, $productPages) {
        $jshopConfig = JSFactory::getConfig();
        $product_count = [];
    
        insertValueInArray($productPages, $jshopConfig->count_product_select);
        foreach ($jshopConfig->count_product_select as $key => $value){
            $product_count[] = JHTML::_('select.option', $key, JText::_($value), 'count_id', 'count_value');
        }
    
        return JHTML::_('select.genericlist', $product_count, 'limit', 'class = "inputbox form-select" size = "1" onchange = "submitListProductFilters()"','count_id', 'count_value', $limit);
    }
}

if (!function_exists('generateManufacturerHtmlSelect')) {
    function generateManufacturerHtmlSelect(array $manufacturers, int $activeManufactureVal = 0, string $selectName = 'manufacturers[]', string $attrs = 'onchange = "submitListProductFilters()"') {
        $first_manufacturer = [];
        $first_manufacturer[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_ALL'), 'id', 'name');
    
        foreach($manufacturers as $manufacturerObj) {
    
            if (!isset($manufacturerObj->id) && isset($manufacturerObj->manufacturer_id)) {
                $manufacturerObj->id = $manufacturerObj->manufacturer_id;
            } else {
                continue;
            }
        }
    
        return JHTML::_('select.genericlist', array_merge($first_manufacturer, $manufacturers), $selectName, 'class = "inputbox form-select" ' . $attrs, 'id', 'name', $activeManufactureVal);
    }
}

if (!function_exists('generateMarkupList')) {
    function generateMarkupList(array $msgs, string $ulAttrs = '', string $liAttrs = '') {
        $result = '';
    
        if (!empty($msgs)) {
            $result = "<ul {$ulAttrs}>";
    
            foreach ($msgs as $msg) {
                $result .= "<li {$liAttrs}>{$msg}</li>";
            }
    
            $result .= '</ul>';
        }
    
        return $result;
    }
}

if (!function_exists('checkIfArrayKeysNotEmpty')) {
    /**
    *   @return - true = not empty, false = empty
    */
    function checkIfArrayKeysNotEmpty($array, $keyArray1, $keyArray2) {

        if (!empty($array[$keyArray1]) && !empty($array[$keyArray2])) {
            return true;
        }

        return false;
    }
}

if (!function_exists('getPathToShopOrRewriteTmpls')) {
    function getPathToShopOrRewriteTmpls($view_name, $file = '') {
        $jshopConfig = JSFactory::getConfig();
        $pathToAddonViewRewrite = JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/com_jshopping/' . $view_name;
        $pathToAddonView = "{$jshopConfig->template_path}{$jshopConfig->template}/{$view_name}";
        $pathToBaseView = "{$jshopConfig->template_path}base/{$view_name}";
        $pathToBelemView = "{$jshopConfig->template_path}{$jshopConfig->template}/pages";

        $_belempath = "{$jshopConfig->template_path}{$jshopConfig->template}pages/{$file}.js";


        if(file_exists($_belempath)){
            return $pathToBelemView;
        }elseif ( file_exists($pathToAddonViewRewrite) ) {
            return $pathToAddonViewRewrite;
        }elseif( file_exists($pathToBaseView) ){
			return $pathToBaseView;
		}
    
        return $pathToAddonView;   
    }
}

if (!function_exists('generateCategoryListHtmlSelect')) {
    function generateCategoryListHtmlSelect(array $categories, string $attrs, string $id = 'category_id') {
        $firstElement = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_SEARCH_ALL_CATEGORIES'), $id, 'name');
    
        foreach($categories as $categoryObj) {
            if (!isset($categoryObj->id) && isset($categoryObj->category_id)) {
                $categoryObj->$id = $categoryObj->category_id;
            } else {
                continue;
            }
        }
    
        array_unshift($categories, $firstElement);
        return JHTML::_('select.genericlist', $categories, $id, 'class = "inputbox form-select" size = "1" ' . $attrs, $id, 'name' );
    }
}

if (!function_exists('transformDescrsTextsToModule')) {
    function transformDescrsTextsToModule(array &$descObjects) {
        if (!empty($descObjects) && is_array($descObjects)) {
            foreach($descObjects as &$descObj) {
                if (is_object($descObj)) {
    
                    if (!empty($descObj->description)) {
                        $descObj->description = JHtml::_('content.prepare', $descObj->description);
                    }
            
                    if (!empty($descObj->short_description)) {
                        $descObj->short_description = JHtml::_('content.prepare', $descObj->short_description);
                    }
                    
                }
            }
        }
    }
}

if (!function_exists('getModifyPriceByMode')) {
    function getModifyPriceByMode(string $priceMode, $priceToModify, $addPrice, &$additionalPriceToModify = null)
    {
        if (!empty($priceMode)) {
            switch($priceMode) {
                case '+':
                    if (isset($additionalPriceToModify)) { 
                        $additionalPriceToModify += $addPrice;
                    }

                    $priceToModify += $addPrice;
                    break;
                case '-':
                    if (isset($additionalPriceToModify)) { 
                        $additionalPriceToModify -= $addPrice;
                    }

                    $priceToModify -= $addPrice;
                    break;
                case '*':
                    if (isset($additionalPriceToModify)) { 
                        $additionalPriceToModify *= $addPrice;
                    }

                    $priceToModify *= $addPrice;
                    break;
                case '/':
                    if (isset($additionalPriceToModify)) { 
                        $additionalPriceToModify /= $addPrice;
                    }

                    $priceToModify /= $addPrice;
                    break;
                case '%':
                    if (isset($additionalPriceToModify)) { 
                        $additionalPriceToModify *= $addPrice / 100;
                    }

                    $priceToModify *= $addPrice / 100;
                    break;
                case '=':
                    if (isset($additionalPriceToModify)) { 
                        $additionalPriceToModify = $addPrice;
                    }

                    $priceToModify =  $addPrice;
                    break;
            }
        }

        return $priceToModify;
    }
}

if (!function_exists('percentageReduction')) {
    function percentageReduction($value, $procent) {
        return getPriceDiscount($value, $procent);
    }
}

if (!function_exists('getDiscountPrice')) {
    function getDiscountPrice($price, $discount) {
        return ($price * $discount) / 100;
    }
}

if (!function_exists('getSiteTemplateActive')) {
    function getSiteTemplateActive(){
        return getJoomlaHomeTemplateName('site');
    }
}

if (!function_exists('templateOverride')) {
    function templateOverride($folder, $file = '', $onlyFolder = 0) {	
        $shopConfig = JSFactory::getConfig();
        $nameOfActiveTmpl = getSiteTemplateActive();

        if(!$shopConfig->template){
            $shopConfig->load($shopConfig->load_id);
        }

        $pathToJoomlaTmpl = JPATH_ROOT . "/templates/{$nameOfActiveTmpl}/html/com_jshopping/{$folder}";
        $pathToShopTmpl = JPATH_ROOT . "/components/com_jshopping/templates/{$shopConfig->template}/{$folder}";
        $pathToBaseTmpl = JPATH_ROOT . "/components/com_jshopping/templates/base/{$folder}";
		
        $returnPathToJoomlaTmpl =  $pathToJoomlaTmpl;
        $returnPathToShopTmpl = $pathToShopTmpl;
        $returnPathToBaseTmpl = $pathToBaseTmpl;

        if (!empty($file)) {
            $pathToJoomlaTmpl .= "/{$file}";
            $pathToShopTmpl .= "/{$file}";
            $pathToBaseTmpl .= "/{$file}";
			if(!$onlyFolder){
				$returnPathToJoomlaTmpl .= "/{$file}";
				$returnPathToShopTmpl .= "/{$file}";
				$returnPathToBaseTmpl .= "/{$file}";
			}
        }
		
        if(file_exists($pathToJoomlaTmpl)) {
            return $returnPathToJoomlaTmpl; 
        }elseif(file_exists($pathToShopTmpl)){
			return $returnPathToShopTmpl;
		}else{
			return $returnPathToBaseTmpl;
		}
        
        return $returnPathToShopTmpl;
    }
}

if (!function_exists('viewOverride')) {
    function viewOverride($folder, $file) {	
        $smartShopConfig = JSFactory::getConfig();
        $_jtemplate = getSiteTemplateActive();
        $_jpath = JPATH_ROOT . "/templates/{$_jtemplate}/html/com_jshopping/{$folder}";
        $_spath = JPATH_ROOT . "/components/com_jshopping/templates/{$smartShopConfig->template}/{$folder}";
        $_basepath = JPATH_ROOT . "/components/com_jshopping/templates/base/{$folder}";
        $_belempath = JPATH_ROOT . "/components/com_jshopping/templates/{$smartShopConfig->template}/pages";
        $belem_file1 = substr($file, 0, -3).'js';
        $belem_file = substr($file, 0, -4).'_'.$folder.'.js';

        if(file_exists("{$_jpath}/{$file}")) {
            $p = $_jpath;
        }elseif(file_exists("{$_spath}/{$file}")){
            $p = $_spath;
		}elseif(file_exists("{$_belempath}/{$belem_file}") || file_exists("{$_belempath}/{$belem_file1}")){
            $p = $_belempath;
		}else{
            $p = $_basepath;
		}
        ///print_R($p);die;
         return $p;
    }
}

if (!function_exists('viewOverrideAjax')) {
    function viewOverrideAjax($folder, $file) {
        $smartShopConfig = JSFactory::getConfig();
        $_jtemplate = getSiteTemplateActive();
        $_jpath = JPATH_ROOT . "/templates/{$_jtemplate}/html/com_jshopping/{$folder}";
        $_spath = JPATH_ROOT . "/components/com_jshopping/templates/{$smartShopConfig->template}/{$folder}";
        $_basepath = JPATH_ROOT . "/components/com_jshopping/templates/base/{$folder}";
        $_belempath = JPATH_ROOT . "/components/com_jshopping/templates/{$smartShopConfig->template}/elements";
        $belem_file1 = substr($file, 0, -3).'js';
        $belem_file = substr($file, 0, -4).'_'.$folder.'.js';

        if(file_exists("{$_jpath}/{$file}")) {
            $p = $_jpath;
        }elseif(file_exists("{$_spath}/{$file}")){
            $p = $_spath;
		}elseif(file_exists("{$_belempath}/{$belem_file}") || file_exists("{$_belempath}/{$belem_file1}")){
            $p = $_belempath;
		}else{
            $p = $_basepath;
		}
         return $p;
    }
}

if (!function_exists('isIssetAndNotEmptyString')) {
    function isIssetAndNotEmptyString($data) {
        if (isset($data) && $data !== '') {
            return true;
        }
    
        return false;
    }
}

if (!function_exists('countOfNonEmptyValues')) {
    function countOfNonEmptyValues($values) {
        $count = 0;
    
        if (!empty($values)) {
            foreach ($values as $value) {
                if (!empty($value)) {
                    $count++;
                }
            }
        }
    
        return $count;
    }
}

if (!function_exists('getMaxDayDeliveryOfProducts')) {
    function getMaxDayDeliveryOfProducts($products) {
        $result = 0;

        $filtered = array_filter(
            array_map(function ($prod) {
                $prod = (object)$prod;
    
                if ($delivery_id = $prod->delivery_times_id) {
                    $delivery = JTable::getInstance('deliveryTimes', 'jshop');
                    $delivery->load($delivery_id);
                    return $delivery->days;
                }
    
            }, $products)
            , function ($day) {
                return $day;
            }
        );
        
        if (!empty($filtered)) {
            $result = max($filtered);
        }

        return $result;
    }
}

if (!function_exists('executeQuery')) {
    function executeQuery($query, array $pdoOptions = [], string $dbType = 'mysql')
    {
        if (!empty($query) && !empty($dbType)) {
            $config = new JConfig();

            if (empty($pdoOptions)) {
                $pdoOptions = [
                    PDO::ATTR_ERRMODE	=> PDO::ERRMODE_EXCEPTION,
                ];
            }

            $pdoOptions[PDO::ATTR_EMULATE_PREPARES] = true;

            $dsn = "{$dbType}:host={$config->host};dbname={$config->db}";
            $pdo = new PDO($dsn, $config->user, $config->password, $pdoOptions);

            try {
                return $pdo->prepare($query)->execute();
            } catch (PDOException $e) {
                throw new JDatabaseExceptionExecuting($query, $e->getMessage(), 1, $e);
            }
        }

        return false;
    }
}


if (!function_exists('groupByArrayKeyVal')) {
    function groupByArrayKeyVal(array $items, string $arrKeyName) {
        $result = [];
    
        if (!empty($items)) {
        
            foreach ($items as $item) {
                if (!empty($item) && isset($item[$arrKeyName])) {
                    $arrKeyVal = $item[$arrKeyName];
                    $result[$arrKeyVal][] = $item;
                }
            }
    
        }
    
        return $result;
    }
}

if (!function_exists('deletePropertiesFromObjectList')) {
    function deletePropertiesFromObjectList(array $objectList, array $propertiesNames) {

        if (!empty($objectList) && is_array($objectList) && !empty($propertiesNames)) {
            foreach ($objectList as &$object) {
                foreach ($propertiesNames as $properyName) {
                    if (property_exists($object, $properyName)) {
                        unset($object->$properyName);
                    }
                }
            }
        }
    
        return $objectList;
    }
}

if (!function_exists('getSubElementsButNotFirst')) {
    function getSubElementsButNotFirst(array $elements, ?string $keyOfVal = null) {
        $result = [];
    
        if (!empty($elements)) {
            foreach ($elements as $subElements) {
                if (!empty($subElements) && count($subElements) >= 1) {
                    $iteration = 0;
    
                    foreach ($subElements as $element) {
                        if ($iteration != 0) {
                            
                            if (!empty($keyOfVal) && isset($element[$keyOfVal])) {
                                $result[] = $element[$keyOfVal];
                            } else {
                                $result[] = $element;
                            }
    
                        }
    
                        $iteration++;
                    }
                }
            }
        }
    
        return $result;
    }
} 

if (!function_exists('isTableExists')) {
    function isTableExists(string $tableName) {
        $db = \JFactory::getDBO();
        $tables = $db->getTableList();
        $tableNameWithPrefix = $db->replacePrefix($tableName);
    
        if (in_array($tableNameWithPrefix, $tables)) {
            return true;			
        }
    
        return false;
    }
}

if (!function_exists('roundPrice')) {
    function roundPrice($value) {
		if ($value>0.01){
			$krok = 0.01 * 100;
			
			$value = round($value, 2);
			$value_temp = round(100 * ($value - floor($value)));
			
			$min_val = floor($value_temp / $krok);
			$left_val = $min_val * $krok;
			$right_val = ($min_val + 1) * $krok;
			$center = floor((($left_val + $right_val) / 2) + 1);
			
			if ($value_temp < $center) {
				$rest = ($left_val / 100);
			} else {
				$rest = ($right_val / 100);
			}
			
			return floor($value) + $rest;
		} else {
			return $value;
		}
	}
}

if (!function_exists('getCheckoutUrl')) {
    function getCheckoutUrl($sef = 1, bool $checkLogin = false) {
        $app = JFactory::getApplication();
        $siteMenu = $app->getMenu();
        $menuItems = $siteMenu->getItems([], []);
        $jshopConfig = JSFactory::getConfig();
        $url = 'index.php?option=com_jshopping&controller=qcheckout&view=qcheckout';
        $itemId = 0;
    
        if (!empty($menuItems)) {
            foreach($menuItems as $item) {
                if ($item->route == 'checkout' || $item->route == 'qcheckout') {
                    $url .= '&itemId=' . $item->id;
                    $itemId = $item->id;
                    break;
                }
            }
        }

        if ($checkLogin && $jshopConfig->shop_user_guest == 1) {
            $url .= '&check_login=1';
        }

        if ($sef) {
            return SEFLink($url, 1, 0, $jshopConfig->use_ssl, $itemId);
        }
    
        return $url;
    }
}

if (!function_exists('isUserAuthorized')) {
    function isUserAuthorized() {
        $user = JSFactory::getUser();
        return (!empty($user->user_id) && $user->user_id != -1);
    }
}

if (!function_exists('getContentOfFile')) {
    /**
     * @param $data - [['variableName' => 'variableValue']]; 
     */
    function getContentOfFile(string $pathToFile, array $data = []) {
        $result = '';

        if (file_exists($pathToFile)) {
            if (!empty($data)) {
                foreach ($data as $variableName => $variableValue) {
                    $$variableName = $variableValue;
                }
            }

            ob_start();
                include $pathToFile;
                $result = ob_get_contents() ?: '';
            ob_end_clean(); 
        }

        return $result;
    }
}

if (!function_exists('setSeoMetaData')) {
    function setSeoMetaData(?string $seoAlias = '') {
        if (!empty($seoAlias)) {
            $seo = JSFactory::getTable('seo', 'jshop');
            $mainframe = JFactory::getApplication();
            $params=$mainframe->getParams();	
            $seodata = $seo->loadData($seoAlias);
            setMetaData($seodata->title ?? '', $seodata->keyword ?? '', $seodata->description ?? '', $params);
        }
    }
}

if (!function_exists('getTypeContentOfLink')) {
    function getTypeContentOfLink(string $link) {
        $header = get_headers($link, 1);
        $result = [];
    
        if (!empty($header)) {
            $contentTypes = explode('/', $header['Content-Type']);
            $result['type'] = $contentTypes['0'];
            $result['format'] = explode(';', $contentTypes['1'])['0'];
        }
    
        return $result;
    }
}

if (!function_exists('getInfoAboutSource')) {
    /**
     * @return array [
     *      'abstractName' => 'image/video/audio',
     *      'format' => 'png/jpg/mp3/videohosting and etc.'
     *      'sourceType' => 'link/file',
     *      'hostingName' => 'youtube/vimeo/dailymotion'
     * ];
     */
    function getInfoAboutSource(string $source): array 
    {
        $result = [];

        if (!empty($source)) {
            $isResultFinded = false;
            $pathInfo = pathinfo($source);
            $extensionName = $pathInfo['extension'];
            $isUrl = (strpos($source, 'http://') !== false || strpos($source, 'https://') !== false || strpos($source, 'www.') !== false);
            $type = $isUrl ? 'link' : (file_exists($source) ? 'file' : 'name');

            $formats = [
                'image' => [
                    'tiff',
                    'tif',
                    'bmp',
                    'jpg',
                    'jpeg',
                    'gif',
                    'png'
                ],
                'video' => [
                    'webm',
                    'mpg',
                    'mp2',
                    'mpeg',
                    'mpe',
                    'mpv',
                    'avi',
                    'flv',
                    'swf',
                    'mp4'
                ],
                'audio' => [
                    'm4a',
                    'flac',
                    'mp3',
                    'wav',
                    'wma',
                    'ogg'
                ],
            ];

            foreach ($formats as $abstrName => $formats) {
                if ( in_array($extensionName, $formats) ) {
                    $result = [
                        'name' => $pathInfo['filename'],
                        'abstrName' => $abstrName,
                        'format' => $extensionName,
                        'sourceType' => $type
                    ];

                    $isResultFinded = true;
                    break;
                }
            }

            if (!$isResultFinded && $isUrl) {

                $provider = JSFactory::getVideoHostings()->getProviderInstanceByUrl($source);
                
                if (!empty($provider) && !empty($provider::HOSTING_NAME)) {
                    $result = [
                        'name' => $provider->getIdFromUrl($source),
                        'abstrName' => 'video',
                        'format' => 'videohosting',
                        'sourceType' => 'link',
                        'hostingName' => $provider::HOSTING_NAME
                    ];

                    $isResultFinded = true;
                }
            }
        }
        
        return $result;
    }
}

if (!function_exists('execCmdCommand')) {
    function execCmdCommand(string $programName, string $code) {
        $ocName = substr(php_uname(), 0, 7);
    
        if ($ocName == 'Windows') {
            $start = (!empty($programName)) ? "start /B {$programName} " : '';
            $command = "{$start} {$code} 2>&1";
        } else {
            $start = (!empty($programName)) ? "/usr/bin/{$programName} " : '';
            $command = "{$start} {$code} > /dev/null &";
        }
    
        exec($command, $output, $return_var);
    
        return !$return_var;
    }
}

if (!function_exists('isSmartEditorEnabled')) {
    function isSmartEditorEnabled() {
        return JComponentHelper::isEnabled('com_expresseditor');
    }
}

 if (!function_exists('checkUserCreditLimit')) {
	function checkUserCreditLimit(){
		$jshopConfig = JSFactory::getConfig();
		$user = JSFactory::getUser();

		if ($user->user_id && isset($user->credit_limit) && $user->credit_limit > 0) {
			$cart = JModelLegacy::getInstance('cart', 'jshop');
			$cart->load();
			$cart->getSum();
			
			$credit_limit = $user->credit_limit - $user->open_amount;
			if($credit_limit < $cart->summ){
				$return = base64_encode($_SERVER['REQUEST_URI']);
				$session = JFactory::getSession();
				$session->set('return', $return);
				
				raiseWarningRedirect( JText::sprintf('COM_SMARTSHOP_USER_CREDIT_LIMIT_MESSAGE', formatprice($credit_limit)), SEFLink('index.php?option=com_jshopping&controller=cart', 1, 1, $jshopConfig->use_ssl));
				exit();
			}
		}
		return 1;
	}
}
 if (!function_exists('checkUserBlock')) {
	function checkUserBlock(){
		$jshopConfig = JSFactory::getConfig();
		$user = JSFactory::getUser();

		if ($user->user_id && isset($user->block) && $user->block) {
			$mainframe = JFactory::getApplication();
			$return = base64_encode($_SERVER['REQUEST_URI']);
			$session = JFactory::getSession();
			$session->set('return', $return);
			raiseWarningRedirect( JText::_('COM_SMARTSHOP_USER_BLOCKED_MESSAGE'), SEFLink('index.php?option=com_jshopping&controller=cart', 1, 1, $jshopConfig->use_ssl));
			exit();
		}
		return 1;
	}
}
 if (!function_exists('checkUsergroupProductBlock')) {
	function checkUsergroupProductBlock($product_id, $category_id){
		$jshopConfig = JSFactory::getConfig();
		$user = JSFactory::getUser();
		$db = \JFactory::getDBO();

        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $params = [
            'usergroup_id' => JSFactory::getUser()->usergroup_id
        ];
        $productTable = $modelOfProductsFront->getProductWithDefaultAttrs($product_id, $params);

        if (!empty($productTable->pr_id) && !empty($productTable->xml)) {
            return 1;
        }

        $isUsergroupPermissionsShowProduct = $productTable->getUsergroupPermissions()->is_usergroup_show_product ?? false;
		
		if (!$isUsergroupPermissionsShowProduct) {
			JFactory::getApplication()->redirect(SEFLink('index.php?option=com_jshopping&controller=products', 1, 1, $jshopConfig->use_ssl));
			die;
        }

		return 1;
	}
}
 if (!function_exists('getUsergroupShowAction')) {
	function getUsergroupShowAction($product_id){
        $modelOfProductsFront = JSFactory::getModel('ProductsFront');
        $params = [
            'usergroup_id' => JSFactory::getUser()->usergroup_id
        ];
        $productTable = $modelOfProductsFront->getProductWithDefaultAttrs($product_id, $params);
        $isUsergroupPermissionsShowBuy = $productTable->getUsergroupPermissions()->is_usergroup_show_buy ?? false;
		
		if (!$isUsergroupPermissionsShowBuy) {
			return 0;
        }
        
		return 1;
	}
}
 if (!function_exists('printFreeAttrUnit')) {
	function printFreeAttrUnit($attr_id, $product_id){
		$db = \JFactory::getDBO();
		
		$query = 'select `unit_id`, `show_unit` from `#__jshopping_free_attr` WHERE `id`='.$attr_id;
        $db->setQuery($query);
        $unit_data = $db->loadObject();
		if($unit_data->show_unit){			
			require_once JPATH_ROOT . '/administrator/components/com_jshopping/models/units.php';
			$units = JSFactory::getModel('units', 'JshoppingModel');
			if(!$unit_data->unit_id){
				$query = "SELECT `basic_price_unit_id`	FROM `#__jshopping_products` WHERE product_id = " . $product_id;
				$db->setQuery($query);
				$unit_data->unit_id = $db->loadResult();
			}
			$unit_name = $units->getUnitById($unit_data->unit_id);
			
			return $unit_name;
			
		}else{
			return '';
		}
	}
}

if (!function_exists('isDomainSupportsSsl')) {
    function isDomainSupportsSsl()
    {
        $stream = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true
            ]
        ]);

        $read = fopen($this->getHttpsUri(), 'rb', false, $stream);
        $cont = stream_context_get_params($read);

        return !empty($cont['options']['ssl']['peer_certificate']);
    }
}

if (!function_exists('getFromEditorXmlProductWidth')) {
    function getFromEditorXmlProductWidth($xml_name)
    {
        $path__xml = JPATH_SITE . "/flash/savedXML/loadXML/" . $xml_name . ".xml";
        if ($xml = simplexml_load_file($path__xml)) { 
            return (int)$xml->motives->widthMotives;
        }else{
			return false;
		}
    }
}

if (!function_exists('getFromEditorXmlProductHeight')) {
    function getFromEditorXmlProductHeight($xml_name)
    {
        $path__xml = JPATH_SITE . "/flash/savedXML/loadXML/" . $xml_name . ".xml";
        if ($xml = simplexml_load_file($path__xml)) {
            return (int)$xml->motives->heightMotives;
        }else{
			return false;
		}
    }
}

if (!function_exists('printFreeAttrQtyByUnit')) {
	function printFreeAttrQtyByUnit($value, $attr_id, $product_id){
		$db = \JFactory::getDBO();
		
		$query = 'select `unit_id`, `show_unit` from `#__jshopping_free_attr` WHERE `id`='.$attr_id;
        $db->setQuery($query);
        $unit_data = $db->loadObject();
		
		require_once JPATH_ROOT . '/administrator/components/com_jshopping/models/units.php';
		$units = JSFactory::getModel('units', 'JshoppingModel');
		if(!$unit_data->unit_id){
			$query = "SELECT `basic_price_unit_id`	FROM `#__jshopping_products` WHERE product_id = " . $product_id;
			$db->setQuery($query);
			$unit_data->unit_id = $db->loadResult();
		}
		$value = getUnitNumberFormat($unit_data->unit_id, $value);
		
		return $value;
			
		
	}
}

if (!function_exists('getUnitNumberFormat')) {
	function getUnitNumberFormat($unit_id, $number){
		$db = \JFactory::getDBO();
		if($unit_id){
		$query = 'select `unit_number_format` from `#__jshopping_unit` WHERE `id`='.$unit_id;
        $db->setQuery($query);
        $number_format = $db->loadResult();
		}
		$number = ($number_format == 1)?((int)$number):(number_format((float)$number, 2, '.', ''));
		
		return $number;
			
		
	}
}

if (!function_exists('generatePDF')) {
    function generatePDF($order, $isGenerateAndDeliveryNote = true, $isGenerateAndInvoice = true) {
        $pdfOrderName = $order->pdf_file ?: ($order->order_id . '_' . md5(uniqid(rand(0, 100))) . '.pdf');

        if ($isGenerateAndInvoice) {
            $pdfOrderName = generateInvoice($order);
        }

        if ($isGenerateAndDeliveryNote) {
            generateDeliveryNotepDF($order, $pdfOrderName);
        }

        

        return $pdfOrderName;
    }
}

if (!function_exists('generatePdfRefund')) {
    function generatePdfRefund($order, $isGenerateAndRefund = true, $refund = []) {
        $pdfOrderName = '';

        if ($isGenerateAndRefund) {
            $pdfOrderName = generateRefundpDF($order, $refund, $pdfOrderName);
        }

        return $pdfOrderName;
    }
}

if (!function_exists('generateInvoice')) {
    function generateInvoice($order) {
		$jshopConfig = JSFactory::getConfig();  
        $orderModel = JSFactory::getModel('orders');
		if(!$order->invoice_number){
			if(!$jshopConfig->next_invoice_number){
				$next_invoice_number = $orderModel->getCountAllOrders([]) + 1;
				$jshopConfig->updateNextInvoiceNumber($next_invoice_number);
			}else{
				$next_invoice_number = $jshopConfig->next_invoice_number;
				$jshopConfig->updateNextInvoiceNumber();				
			}
			$invoiceNumber = $jshopConfig->invoice_suffix . $next_invoice_number;		
			$order->invoice_number = $invoiceNumber;
			JSFactory::getModel('OrdersFront')->setInvoiceNumber($order->order_id, $order->invoice_number);
		}
        $orderObj = clone $order;

        if (!class_exists('JorderPDF')) {
            include_once templateOverride('pdf', 'generete_pdf_order.php');
        }

        $orderPdf = new JorderPDF();
        return $orderPdf->generate($orderObj, $isGenerateAndInvoice);
    }
}

if (!function_exists('generateDeliveryNotepDF')) {
    function generateDeliveryNotepDF($order, $pdfOrderName) {
        $orderObj = clone $order;

        if (!class_exists('JorderDeliveryNotePDF')) {
            include_once templateOverride('pdf', 'generete_pdf_delivery_note.php');
        }
        
        $deliveryNotePdfGenerator = new JorderDeliveryNotePDF();
        return $deliveryNotePdfGenerator->generate($orderObj, $pdfOrderName);
    }
}

if (!function_exists('generateRefundpDF')) {
    function generateRefundpDF($order, $refund, $pdfOrderName) {
        $orderObj = clone $order;

        if (!class_exists('JrefundPDF')) {
            include_once templateOverride('pdf', 'generete_pdf_refund.php');
        }
        
        $refundPdfGenerator = new JrefundPDF();
        return $refundPdfGenerator->generate($orderObj, $refund, $pdfOrderName);
    }
}

if (!function_exists('generateOfferAndOrderPdf')) {
    function generateOfferAndOrderPdf($order) {
        $orderObj = clone $order;

        if (!class_exists('JofferAndOrderPDF')) {
            include_once templateOverride('pdf', 'generete_pdf_offer_and_order.php');
        }

        $pdfGenerator = new JofferAndOrderPDF();

        return $pdfGenerator->generate($orderObj);
    }
}

if (!function_exists('printSelectQuantity')) {
    function printSelectQuantity($product, $defaultCount, $name = 'quantity', $isList = false) {
        $aq = correctStrSep($product->quantity_select);

        if ($product->equal_steps) {
            $quantitySelect = (($product->quantity_select > $product->product_quantity) && empty($product->unlimited)) || empty($product->quantity_select) ? 1: $product->quantity_select;
            $step = (float)$quantitySelect;
            
            $minQty = $product->min_count_product ?: $quantitySelect;
            if (empty($product->unlimited) && ($minQty > $product->product_quantity)) {
                $minQty = $product->product_quantity;
            }

            if (!empty($product->unlimited)) {
                $maxQty = $product->max_count_product ?: ($quantitySelect * 100);
            } else {
                $maxQty = $product->max_count_product ?: $product->product_quantity;

                if (($product->max_count_product > $product->product_quantity)) {
                    $maxQty = $product->product_quantity;
                }
            }
            
            $preciseMinQty = ceil($minQty / $step) * $step;
            $preciseMaxQty = floor($maxQty / $step) * $step;
            $quantityValues = [$minQty];

            if ($preciseMinQty < $preciseMaxQty) {
                $quantitySelect = (int)$product->quantity_select ?: 1;     
                $quantityValues = range(0, $maxQty, (int)$step);
                $quantityValues = array_combine($quantityValues, $quantityValues);
                $quantityValues = spliceArrayByKeys($quantityValues, $preciseMinQty, $preciseMaxQty);
            }

            $aq = correctStrSep(implode(',', $quantityValues));
        }

        if ($isList) {
           return JHTML::_('select.genericlist', $aq, $name,'class="inputbox form-control"','quantity','name', $defaultCount,'quantity');
        }

        return JHTML::_('select.genericlist', $aq, $name,'class="inputbox form-control" onchange="shopProductFreeAttributes.setData();uploadImage.updateQuantityWhenChangeProductQuantity(0, this);"','quantity','name', $defaultCount,'quantity');
    }
}

if (!function_exists('printSelectQuantityOptions')) {
    function printSelectQuantityOptions($product, $equal_steps, $defaultCount, $default_count_product, $name = 'quantity', $isList = 0) {
         $aq = correctStrSep($product['quantity_select']);

        if ($product['equal_steps']) {
            $quantitySelect = (($product['quantity_select'] > $product['product_quantity']) && empty($product['unlimited'])) || empty($product['quantity_select']) ? 1: $product['quantity_select'];
            $step = (float)$quantitySelect;

            $minQty = $product['min_count_product'] ?: $quantitySelect;
            if (empty($product['unlimited']) && ($minQty > $product['product_quantity'])) {
                $minQty = $product['product_quantity'];
            }

            if (!empty($product['unlimited'])) {
                $maxQty = $product['max_count_product'] ?: ($quantitySelect * 100);
            } else {
                $maxQty = $product['max_count_product'] ?: $product['product_quantity'];

                if (($product['max_count_product'] > $product['product_quantity'])) {
                    $maxQty = $product['product_quantity'];
                }
            }

            $preciseMinQty = ceil($minQty / $step) * $step;
            $preciseMaxQty = floor($maxQty / $step) * $step;
            $quantityValues = [$minQty];

            if ($preciseMinQty < $preciseMaxQty) {
                $quantitySelect = (int)$product['quantity_select'] ?: 1;
                $quantityValues = range(0, $maxQty, $step);
                $quantityValues = array_combine($quantityValues, $quantityValues);
                $quantityValues = spliceArrayByKeys($quantityValues, $preciseMinQty, $preciseMaxQty);
            }
            if ($isList) {
                $aq = correctStrSep(implode(',', $quantityValues));
            }else{
                $aq = $quantityValues;
                if(!$defaultCount || $defaultCount < reset($aq) || $defaultCount > array_key_last($aq)) $defaultCount = reset($aq);
            }
        }


		if ($isList) {
           return JHTML::_('select.genericlist', $aq, $name,'class="inputbox form-control"','quantity','name', $defaultCount,'quantity');
        }

        $options = '';
	    foreach($aq as $key=>$value) :
		    if($value):
			   $selected = '';
			   if($defaultCount == $value) $selected = "selected";
			   $options .= '<option value="'. $value .'" '.$selected.'>'.$value.'</option>';
		    endif;
	    endforeach;

		return $options;
		
    }
}

if (!function_exists('printSelectQuantityCart')) {
    function printSelectQuantityCart($product_id, $quantity_select, $default_count_product, $name, $key_id, $attrs = []) {
		$name = $name ?? 'quantity';
	   $product = JSFactory::getTable('product', 'jshop');
        $product->load($product_id);

        if (!empty($attrs)) {
            $product->setAttributeActive($attrs);
        }

        $isEnabledEqualSteps = $product->equal_steps;
        $aq = correctStrSep($quantity_select);

        if ($isEnabledEqualSteps) {
            $quantitySelect = (($product->quantity_select > $product->product_quantity) && empty($product->unlimited)) || empty($product->quantity_select) ? 1: $product->quantity_select;
            $step = $quantitySelect;

            $minQty = $product->min_count_product ?: $quantitySelect;
            if (empty($product->unlimited) && ($minQty > $product->product_quantity)) {
                $minQty = $product->product_quantity;
            }

            if (!empty($product->unlimited)) {
                $maxQty = $product->max_count_product ?: ($quantitySelect * 100);
            } else {
                $maxQty = $product->max_count_product ?: $quantitySelect;

                if (($product->max_count_product > $product->product_quantity) || empty($product->max_count_product)) {
                    $maxQty = $product->product_quantity;
                }
            }
            
            $preciseMinQty = ceil($minQty / $step) * $step;
            $preciseMaxQty = floor($maxQty / $step) * $step;
            $quantityValues = [$minQty];

            if ($preciseMinQty < $preciseMaxQty) {
                $quantitySelect = (int)$product->quantity_select ?: 1;     
                $quantityValues = range(0, $maxQty, $step);
                $quantityValues = array_combine($quantityValues, $quantityValues);
                $quantityValues = spliceArrayByKeys($quantityValues, $preciseMinQty, $preciseMaxQty);
            }

            $aq = correctStrSep(implode(',', $quantityValues));
        }

	    return JHTML::_('select.genericlist', $aq, $name,'class="inputbox form-control" onchange="uploadImage.updateQuantityWhenChangeProductQuantity(&#39;'.$key_id.'&#39; , this);"',$name,'name', $default_count_product,$name);		
    }
}

if (!function_exists('spliceArrayByKeys')) {
    function spliceArrayByKeys(array $arr, string $fromKey, string $toKey): array {
        $result = [];

        if (!empty($arr)) {
            $isFromKeyAlreadyBehind = $isToKeyAlreadyBehind = false;
            
            foreach ($arr as $key => $value) {
                $isFromKeyAlreadyBehind = ($key == $fromKey) ? true: $isFromKeyAlreadyBehind;
                $isToKeyAlreadyBehind = ($key == $toKey) ? true: $isToKeyAlreadyBehind;

                if ($isFromKeyAlreadyBehind || $isToKeyAlreadyBehind) {
                    $result[$key] = $value;
                }

                if ($isToKeyAlreadyBehind) {
                    break;
                }
            }
        }

        return $result;
    }
}

if (!function_exists('correctStrSep')) {    
	function correctStrSep($str)
    {
		$reg='/[^0-9]/';
		$astr = explode(',',$str);
		if(count($astr) > 0){
			$a = array();
			foreach($astr as $as){
				$as = preg_replace($reg,'',$as);
				if($as != '') $a[$as] = $as;
			}
			return $a;
		}	   
    }
}


if (!function_exists('getCountry')) {
	function getCountry($shipping_adress_id){
		$db = \JFactory::getDBO();		
		$query = "SELECT `country` FROM `#__jshopping_users_addresses` WHERE `address_id`=".$shipping_adress_id;
		$db->setQuery($query);		
		return $db->loadResult();		 
	}
}

if (!function_exists('getState')) {
	function getState($shipping_adress_id){
		$db = \JFactory::getDBO();		
		$query = "SELECT `state` FROM `#__jshopping_users_addresses` WHERE `address_id`=".$shipping_adress_id;
		$db->setQuery($query);		
		return $db->loadResult();		 
	}
}

if (!function_exists('loadingStatesScripts')) { 
	function loadingStatesScripts()
    {
        $document = JFactory::getDocument();        
        $document->addScriptOptions('urlStates', SEFLink('index.php?option=com_jshopping&controller=states&task=statesAjax&ajax=1', 1, 1) );
    }
}	

if (!function_exists('loadingStatesScriptsAdmin')) { 
	function loadingStatesScriptsAdmin()
    {
        $document = JFactory::getDocument();        
        $document->addScriptOptions('urlStates2', SEFLink('index.php?option=com_jshopping&controller=states&task=states_ajax&ajax=1', 1, 1) );
		$document->addScriptOptions('exttaxes_id', JFactory::getApplication()->input->getInt("id"));
    }
}	

if (!function_exists('isThereAtLeastOneNotEmpty')) {
    function isThereAtLeastOneNotEmpty($array) {
        $isThereAtLeastOneNotEmpty = false; 

        if (!empty($array)) {
            foreach ($array as $item) {
                if (!empty($item)) {
                    $isThereAtLeastOneNotEmpty = true;
                    break;
                }
            }
        }

        return $isThereAtLeastOneNotEmpty;
    }
}

if (!function_exists('templateOverrideBlock')) {
    function templateOverrideBlock($folder, $file = '', $onlyFolder = 0) {	
        $shopConfig = JSFactory::getConfig();
        $nameOfActiveTmpl = getSiteTemplateActive();

        if(!$shopConfig->template){
            $shopConfig->load($shopConfig->load_id);
        }

        $pathToJoomlaTmpl = JPATH_ROOT . "/templates/{$nameOfActiveTmpl}/html/com_jshopping/{$folder}";
        $pathToShopTmpl = JPATH_ROOT . "/components/com_jshopping/templates/{$folder}";

        $returnPathToJoomlaTmpl =  $pathToJoomlaTmpl;
        $returnPathToShopTmpl = $pathToShopTmpl;

        if (!empty($file)) {
            $pathToJoomlaTmpl .= "/{$file}";
            $pathToShopTmpl .= "/{$file}";
			if(!$onlyFolder){
				$returnPathToJoomlaTmpl .= "/{$file}";
				$returnPathToShopTmpl .= "/{$file}";
			}
        }

        if(file_exists($pathToJoomlaTmpl)) {
            return $returnPathToJoomlaTmpl; 
        }
        
        return $returnPathToShopTmpl;
    }
}


if (!function_exists('templateOverrideBlockReact')) {
    function templateOverrideBlockReact($folder, $file = '', $onlyFolder = 0) {	
        $shopConfig = JSFactory::getConfig();
        $nameOfActiveTmpl = getSiteTemplateActive();

        if(!$shopConfig->template){
            $shopConfig->load($shopConfig->load_id);
        }

        $pathToJoomlaTmpl = JPATH_ROOT . "/templates/{$nameOfActiveTmpl}/html/com_jshopping/{$folder}";
        $pathToShopTmpl = JPATH_ROOT . "/components/com_jshopping/templates/{$folder}";
        $pathToReactpTmpl = JPATH_ROOT . "/components/com_jshopping/templates/{$shopConfig->template}/elements";

        $returnPathToJoomlaTmpl =  $pathToJoomlaTmpl;
        $returnPathToShopTmpl = $pathToShopTmpl;
        $returnPathToReactTmpl = $pathToReactpTmpl;
		$react_file = substr($file, 0, -3).'js';

        if (!empty($file)) {
            $pathToJoomlaTmpl .= "/{$file}";
            $pathToShopTmpl .= "/{$file}";
            $pathToReactpTmpl .= "/{$react_file}";
			if(!$onlyFolder){
				$returnPathToJoomlaTmpl .= "/{$file}";
				$returnPathToShopTmpl .= "/{$file}";
				$returnPathToReactTmpl .= "/{$react_file}";
			}
        }

        if(file_exists($pathToReactpTmpl)) {
            return $returnPathToReactTmpl; 
        }elseif(file_exists($pathToJoomlaTmpl)) {
            return $returnPathToJoomlaTmpl; 
        }
        
        return $returnPathToShopTmpl;
    }
}

if (!function_exists('loadJSLanguageKeys')) {
    function loadJSLanguageKeys()
    {

        $lang = JSFactory::getLang();

        $language_tag = $lang->lang;

        $jsFile = '/language/'.$language_tag.'/'.$language_tag.'.com_jshopping.ini';

        if (isset($jsFile))
        {
            $jsFile = JPATH_SITE . $jsFile;
        }
        else
        {
            return false;
        }

        if ($list = file($jsFile))
//        if ($jsContents = file_get_contents($jsFile))
        {

           if(!empty($list)){
                foreach($list as $val){
                    if($val){
                        $_consts = explode('="', $val);
                        if($_consts[0])
                            JText::script($_consts[0]);
                    }
                }
            }

        }
    }
}

if (!function_exists('loadAdminJSLanguageKeys')) {
    function loadAdminJSLanguageKeys()
    {

        $lang = JSFactory::getLang();

        $language_tag = $lang->lang;

        $jsFile = '/language/'.$language_tag.'/'.$language_tag.'.com_jshopping.ini';

        if (isset($jsFile))
        {
            $jsFile = JPATH_ADMINISTRATOR . $jsFile;
        }
        else
        {
            return false;
        }

        if ($list = file($jsFile))
//        if ($jsContents = file_get_contents($jsFile))
        {

           if(!empty($list)){
                foreach($list as $val){
                    if($val){
                        $_consts = explode('="', $val);
                        if($_consts[0])
                            JText::script($_consts[0]);
                    }
                }
            }

        }
    }
}
if (!function_exists('getLayoutName')) {
    function getLayoutName($folder, $file, $isPath = 0)
    {
        if(!$isPath){
            $path = viewOverride($folder, $file.'.php');
        }else{
            $path = $folder;
        }
        if(file_exists("{$path}/{$file}.php")) {
            return $file;
        }else{
            return 'index';
        }

    }
}

if (!function_exists('addDescriptionToProducts')) {
    function addDescriptionToProducts(&$products) {
        $jshopConfig = JSFactory::getConfig();

        foreach($products as $key => $value) {
            $product = JSFactory::getTable('product', 'jshop');
            $product->load($value->product_id);
            $product->short_description = $product->getTexts()->short_description;
        }

    }
}
if (!function_exists('prepareView')) {
    function prepareView($view) {
        if(isset($view->products) && $view->products){
            foreach($view->products as $key=>$value){
                $view->products[$key] = prepareView1($value,  $key);
            }
        }elseif(isset($view->rows)){
			foreach($view->rows as $key=>$value){
                $view->rows[$key] = prepareView1($value,  $key);
            }
		}else {
            foreach ($view as $key => $value) {
                $view->$key = prepareView1($value,  $key);
            }
        }
        return $view;

    }
}
if (!function_exists('prepareView1')) {
    function prepareView1($view,  $key) {
        if($view == INF){
            return 'INF';
        }else {
            if(!empty($view) && (is_array($view) || is_object($view))){
				foreach ($view as $key => $value) {
					if (is_array($value)) {
						foreach ($value as $k => $v) {
							if ($v == INF) {
								$value[$k] = 'INF';
								/*if(is_object($view)){
									$view->$key = $value[$k];
								}else{
									$view[$key] = $value[$k];
								}*/
								
							} elseif (is_object($v) || is_array($v)) {
								prepareView1($v, $k);
							}
						}
					} elseif (is_array($view) && ($value == INF)) {
						$view[$key] = 'INF';
					} elseif ($value == INF) {
						$view->$key = 'INF';
					}
				}
            }
            //}
        }
        return $view;

    }
    }
    if (!function_exists('templateOverrideBlockAjax')) {
        function templateOverrideBlockAjax($folder, $file = '', $onlyFolder = 0) {
            $smartShopConfig = JSFactory::getConfig();
            $_jtemplate = getSiteTemplateActive();
            $_jpath = JPATH_ROOT . "/templates/{$_jtemplate}/html/com_jshopping/{$folder}";
            $_spath = JPATH_ROOT . "/components/com_jshopping/templates/{$smartShopConfig->template}/{$folder}";
            $_basepath = JPATH_ROOT . "/components/com_jshopping/templates/base/{$folder}";
            $_belempath = JPATH_ROOT . "/components/com_jshopping/templates/{$smartShopConfig->template}/elements";
            $belem_file1 = substr($file, 0, -3).'js';
            $belem_file = substr($file, 0, -4).'_'.$folder.'.js';

            if(file_exists("{$_jpath}/{$file}")) {
                $p = $_jpath;
            }elseif(file_exists("{$_spath}/{$file}")){
                $p = $_spath;
            }elseif(file_exists("{$_belempath}/{$belem_file}") || file_exists("{$_belempath}/{$belem_file1}")){
                $p = $_belempath;
            }else{
                $p = $_basepath;
            }

            if(!$smartShopConfig->template){
                $smartShopConfig->load($smartShopConfig->load_id);
            }
            return $p;

        }
	}
	if (!function_exists('prepareText')) {
		function prepareText($text) {
			return str_replace('src="images/', 'src="/images/', $text);
		}
	}
if (!function_exists('prepareText')) {
    function prepareText($text) {
        return str_replace('src="images/', 'src="/images/', $text);
    }
}
if (!function_exists('corectDefaultCount')) {
    function corectDefaultCount($product, $defaultCount) {
		if(!isset($product->quantity_select)){ return $defaultCount;}
        $aq = correctStrSep($product->quantity_select);

        if ($product->equal_steps) {
            $quantitySelect = ((empty($product->unlimited)) || empty($product->quantity_select)) ? 1: $product->quantity_select;
            $step = (int)$product->quantity_select;

            $minQty = $product->min_count_product ?: $quantitySelect;
            if (empty($product->unlimited) && ($minQty > $product->product_quantity)) {
                $minQty = $product->product_quantity;
            }

            if (!empty($product->unlimited)) {
                $maxQty = $product->max_count_product ?: ($quantitySelect * 100);
            } else {
                $maxQty = $product->max_count_product ?: $product->product_quantity;

                if (($product->max_count_product > $product->product_quantity)) {
                    $maxQty = $product->product_quantity;
                }
            }

            $preciseMinQty = ceil($minQty / $step) * $step;
            $preciseMaxQty = floor($maxQty / $step) * $step;
            $quantityValues = [$minQty];

            if ($preciseMinQty < $preciseMaxQty) {
                $quantitySelect = (int)$product->quantity_select ?: 1;
                $quantityValues = range(0, $maxQty, $step);
                $quantityValues = array_combine($quantityValues, $quantityValues);
                $quantityValues = spliceArrayByKeys($quantityValues, $preciseMinQty, $preciseMaxQty);
            }
          //  print_r($quantityValues);die;
                $aq = $quantityValues;

        }
        if(!empty($aq) && ($defaultCount < reset($aq) || $defaultCount > array_key_last($aq) || !in_array($defaultCount, $aq))) $defaultCount = reset($aq);


        return $defaultCount;

    }
}

if (!function_exists('getMedianaValue')) {
    function getMedianaValue(array $arr) {
        $result = 0;
    
        if (!empty($arr)) {
            if (count($arr) == 1) {
                $result = reset($arr);
            } else {
                Arsort($arr);
                $keys = array_keys($arr);
                $key = $keys[(int)(count($keys)/2)];
                $result = $arr[$key];
            }
        }
    
        return $result;
    }
}

if (!function_exists('renderText')) {
    function renderText(string $text) {
        $application = JFactory::getApplication();
        $application->setBody($text);
        $application->triggerEvent('onAfterRender');
        $text = $application->getBody();
        $application->setBody('');
    
        return $text;
    }
}

if (!function_exists('isJoomla4')) {
    function isJoomla4() {
        return (Joomla\CMS\Version::MAJOR_VERSION == 4);
    }
}

if (!function_exists('redirectIfNotGuest')) {
    function redirectIfNotGuest(string $url = 'index.php?option=com_jshopping&view=user') {
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();

        if (!empty($user->id)) {
            $mainframe = JFactory::getApplication();
            $mainframe->redirect(SEFLink($url, 1, 1, $jshopConfig->use_ssl));
            die;
        }
    }
}

if (!function_exists('clearPathOfImage')) {
    function clearPathOfImage($source, bool $isClearJoomlaImgPrefix = true) {
        if (isJoomla4() && !empty($source) && is_string($source)) {
            $isUrl = JSFactory::getJSUri()->isUrl($source);

            if ($isUrl) {
                $data = parse_url($source);

                if (!empty($data['path'])) {
                    $source = ltrim($data['path'], '/\\');
                }
            } elseif($isClearJoomlaImgPrefix) {
                $parts = explode('#joomlaImage', $source);

                if (!empty($parts) && count($parts) >= 2 && !empty($parts['0'])) {
                    $source = urldecode($parts['0']);
                }
            }
        }

        return $source;
    }
}
if (!function_exists('_buildPaginationDataObject')) {
    function _buildPaginationDataObject($total, $limitstart, $limit){
        require_once JPATH_ROOT.'/libraries/src/Pagination/Pagination.php';
        $pagination = new JPagination($total, $limitstart, $limit);
        $pagenav = $pagination->getPagesLinks();
        $data = new \stdClass;
        $viewall = false;
        if ($limit === 0)
        {
            $viewall = true;
        }

        // Build the additional URL parameters string.
        $params = '';
        if (!empty($pagination->additionalUrlParams))
        {
            foreach ($pagination->additionalUrlParams as $key => $value)
            {
                $params .= '&' . $key . '=' . $value;
            }
        }

        $data->all = new PaginationObject(\JText::_('JLIB_HTML_VIEW_ALL'), $pagination->prefix);

        if (!$viewall)
        {
            $data->all->base = '0';
            $data->all->link = str_replace('ajax=1&','', \JRoute::_($params . '&' . $pagination->prefix . 'limitstart='));
            $data->all->link = str_replace('amp;','', $data->all->link);
        }

        // Set the start and previous data objects.
        $data->start    = new PaginationObject(\JText::_('JLIB_HTML_START'), $pagination->prefix);
        $data->previous = new PaginationObject(\JText::_('JPREV'), $pagination->prefix);

        if ($pagination->pagesCurrent > 1)
        {

            $page = ($pagination->pagesCurrent - 2) * $pagination->limit;

            if ($pagination->hideEmptyLimitstart)
            {
                $data->start->link = str_replace('ajax=1&','', \JRoute::_($params . '&' . $pagination->prefix . 'limitstart='));
            }
            else
            {
                $data->start->link = str_replace('ajax=1&','', \JRoute::_($params . '&' . $pagination->prefix . 'limitstart=0'));
            }
            $data->start->link = str_replace('amp;','', $data->start->link);

            $data->start->base    = '0';
            $data->previous->base = $page;

            if ($page === 0 && $pagination->hideEmptyLimitstart)
            {
                $data->previous->link = $data->start->link;
            }
            else
            {
                $data->previous->link = str_replace('ajax=1&','', \JRoute::_($params . '&' . $pagination->prefix . 'limitstart=' . $page));
                $data->previous->link = str_replace('amp;','', $data->previous->link);
            }
        }

        // Set the next and end data objects.
        $data->next = new PaginationObject(\JText::_('JNEXT'), $pagination->prefix);
        $data->end  = new PaginationObject(\JText::_('JLIB_HTML_END'), $pagination->prefix);

        if ($pagination->pagesCurrent < $pagination->pagesTotal)
        {
            $next = $pagination->pagesCurrent * $pagination->limit;
            $end  = ($pagination->pagesTotal - 1) * $pagination->limit;
            $data->next->base = $next;
            $data->next->link = str_replace('ajax=1&','', \JRoute::_($params . '&' . $pagination->prefix . 'limitstart=' . $next));
            $data->next->link = str_replace('amp;','', $data->next->link);
            $data->end->base  = $end;
            $data->end->link  = str_replace('ajax=1&','', \JRoute::_($params . '&' . $pagination->prefix . 'limitstart=' . $end));
            $data->end->link = str_replace('amp;','', $data->end->link);
        }

        $data->pages = array();
        $stop        = $pagination->pagesStop;

        for ($i = $pagination->pagesStart; $i <= $stop; $i++)
        {
            $offset = ($i - 1) * $pagination->limit;

            $data->pages[$i] = new PaginationObject($i, $pagination->prefix);

            if ($i != $pagination->pagesCurrent || $viewall)
            {
                $data->pages[$i]->base = $offset;

                if ($offset === 0 && $pagination->hideEmptyLimitstart)
                {
                    $data->pages[$i]->link = $data->start->link;
                }
                else
                {
                    $data->pages[$i]->link = str_replace('ajax=1&','', \JRoute::_($params . '&' . $pagination->prefix . 'limitstart=' . $offset));
                    $data->pages[$i]->link = str_replace('amp;','', $data->pages[$i]->link);
                }
            }
            else
            {
                $data->pages[$i]->active = true;
            }
        }

        return $data;
    }
}

if (!function_exists('excludeHiddenAttr')) {
    function excludeHiddenAttr($freeatribute, $product_id) {
        $freeAttrs = JSFactory::getModel('ProductsFreeAttrsFront')->getFreeAttrsByProductId($product_id);
		$hiddenFreeAttr = [];
		foreach($freeAttrs as $val){
			if($val->is_fixed && !$val->showFreeAttrInput){
				$hiddenFreeAttr[] = $val->id;
			}
		}
		if(!empty($hiddenFreeAttr)){
			foreach($freeatribute as $k=>$val){
				if(in_array($val->attr_id, $hiddenFreeAttr)){
					unset($freeatribute[$k]);
				}
			}
		}
		return $freeatribute;
    }
}
	if (!function_exists('contentReplace')) {
		function contentReplace($text) {
			return str_replace('@', '////', $text);
		}
	}
	if (!function_exists('contentReturn')) {
		function contentReturn($text) {
			return str_replace('////', '@', $text);
		}
	}
	
	if (!function_exists('getShopPageItemid')) {
		function getShopPageItemid($link) {
			$jshopConfig = JSFactory::getConfig();
			$current_lang = $jshopConfig->getLang();
			$db = \JFactory::getDBO();
			
			$_link = str_replace('controller', 'view', $link);
			$groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
			$query = "SELECT id, link FROM #__menu WHERE `type` = 'component' AND published = 1 AND (link like '%".$link."%' OR link like '%".$_link."%') AND client_id = 0 AND (language='*' OR language='".$current_lang."') AND access IN (".$groups.") LIMIT 1";
			$db->setQuery($query);
			$item = $db->loadObject();

			return $item->id ? $item->id : 0;

		}
	}