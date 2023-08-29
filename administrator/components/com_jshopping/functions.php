<?php
/**
* @version      4.7.0 31.05.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

function quickiconButton( $link, $image, $text ){
$jshopConfig = JSFactory::getConfig();
?>
    <div class="icon">
        <a href="<?php echo $link?>">
			<?php if (file_exists($jshopConfig->admin_path.'images/'.$image)){?>
				<img src="<?php print $jshopConfig->live_admin_path?>images/<?php print $image?>" alt="">
			<?php } else { 
				echo $image;
			}?>
            <span><?php echo $text?></span>
        </a>
    </div>
<?php
}

function getTemplates($type, $default, $first_empty = 0){
    $name = $type."_template";
    $folder = $type;

    $jshopConfig = JSFactory::getConfig();
    $temp = array();
    if($folder != 'belem') {
        $dir = $jshopConfig->template_path . $jshopConfig->template . "/" . $folder . "/";
        $dh = opendir($dir);
        if ($dh) {
            while (($file = readdir($dh)) !== false) {
                if (preg_match('~^' . $type . '_(.+)\.php~', $file, $matches)) {
                    $temp[] = $matches[1];
                }
            }
        }
        closedir($dh);
    }else{
        $temp[] = 'default';
    }
    $list = array();
    if ($first_empty){
        $list[] = JHTML::_('select.option', -1, "- - -", 'id', 'value');
    }else{
		if(!$default){
			$default = 'default';
		}
	}
    foreach($temp as $val){
        $list[] = JHTML::_('select.option', $val, $val, 'id', 'value');
    }

    return JHTML::_('select.genericlist', $list, $name,'class="inputbox form-select" size = "1"','id','value', $default);
}

function getShopTemplatesSelect($default){
    $jshopConfig = JSFactory::getConfig();
    $temp = array();
    $dir = $jshopConfig->template_path;
    $dh = opendir($dir);
    while(($file = readdir($dh)) !== false){
        if (is_dir($dir.$file) && $file!="." && $file!=".." && $file!='addons' && $file!='blocks'){
            $temp[] = $file;
        }
    }
    closedir($dh);
    $list = array();
    foreach($temp as $val){
        $list[] = JHTML::_('select.option', $val, $val, 'id', 'value');
    }
    return JHTML::_('select.genericlist', $list, "template",'class = "inputbox form-select" size = "1"','id','value', $default);
}

function getFileName($name) {
    // Get Extension
    $ext_file = strtolower(substr($name,strrpos($name,".")));
    // Generate name file
    $name_file = md5(uniqid(rand(),true));
    return $name_file . $ext_file;
}

function updateCountExtTaxRule(){
    $db = \JFactory::getDBO();
    $query = "SELECT count(id) FROM `#__jshopping_taxes_ext`";
    $db->setQuery($query);
    $count = $db->loadResult();

    $query = "update #__jshopping_config set use_extend_tax_rule='".$count."' where id='1'";
    $db->setQuery($query);
    $db->execute();
}

function updateCountConfigDisplayPrice(){
    $db = \JFactory::getDBO();
    $query = "SELECT count(id) FROM `#__jshopping_config_display_prices`";
    $db->setQuery($query);
    $count = $db->loadResult();

    $query = "update #__jshopping_config set use_extend_display_price_rule='".$count."' where id='1'";
    $db->setQuery($query);
    $db->execute();
}

function orderBlocked($order){
    if (!$order->order_created && time()-strtotime($order->order_date)<3600){
        return 1;
    }else{
        return 0;
    }
}

function addSubmenu($vName,$canDo){
    $user = JFactory::getUser();
    $dispatcher = \JFactory::getApplication();
	$doc = JFactory::getDocument();
	$doc->addScript(JUri::root() . 'administrator/components/com_jshopping/js/src/scripts/joomla_menu/joomla4_menu.js');
	$doc->addScriptDeclaration("document.onreadystatechange = function () {
    if (document.readyState == 'interactive') {
		function showJ4submenuByTime(){
			showJ4submenu('".$vName."')
		}
		setTimeout(showJ4submenuByTime,200)}}");
    $adminaccess = $user->authorise('core.admin', 'com_jshopping');
    $installaccess = $user->authorise('core.admin.install', 'com_jshopping');
    $linkToEditor = (isSmartEditorEnabled()) ? 'index.php?option=com_expresseditor' : 'index.php?option=com_jshopping&controller=service&task=redirectBackWithMsg&msgType=error&msg=' . rawurlencode(JText::_('COM_SMARTSHOP_COMPONENT_NOT_INSTALLED_OR_ACTIVATED'));   

    $menu = [];
    if ($canDo->get('smartshop.smarteditor')) $menu['smarteditor'] = array('smart | EDITOR', $linkToEditor, 0, 1);
    if ($canDo->get('smartshop.categories')) $menu['categories'] = array( JText::_('COM_SMARTSHOP_MENU_CATEGORIES'), 'index.php?option=com_jshopping&controller=categories', $vName == 'categories', 1);
    if ($canDo->get('smartshop.products')) $menu['products'] = array( JText::_('COM_SMARTSHOP_MENU_PRODUCTS'), 'index.php?option=com_jshopping&controller=products', $vName == 'products', 1);
    if ($canDo->get('smartshop.orders')) $menu['orders'] = array(  JText::_('COM_SMARTSHOP_MENU_ORDERS'), 'index.php?option=com_jshopping&controller=orders', $vName == 'orders', 1);
    if ($canDo->get('smartshop.users')) $menu['users'] = array( JText::_('COM_SMARTSHOP_MENU_CLIENTS'), 'index.php?option=com_jshopping&controller=users', $vName == 'users', 1);
    if ($canDo->get('smartshop.options')) $menu['other'] = array( JText::_('COM_SMARTSHOP_MENU_OTHER'), 'index.php?option=com_jshopping&controller=other', $vName == 'other', 1);
    if ($canDo->get('smartshop.configuration')) $menu['config'] = array(  JText::_('COM_SMARTSHOP_MENU_CONFIG'), 'index.php?option=com_jshopping&controller=config', $vName == 'config', $adminaccess );
    
    $dispatcher->triggerEvent('onBeforeAdminMenuDisplay', [&$menu, &$vName]);

    $menu['documentation'] = ['<p class="mt-2 mb-2 textToSHDocBlankTarget">' . Text::_('COM_SMARTSHOP_DOCUMENTATION') . '</p>', 'index.php?option=com_jshopping&controller=service&task=redirectToShopDocumentation', 0, 1];

    foreach($menu as $item) {
        if (!empty($item['3'])) {
			if (class_exists("JSubMenuHelper")) JSubMenuHelper::addEntry($item['0'], $item['1'], $item['2']);
				else \JHtmlSidebar::addEntry($item['0'], $item['1'], $item['2']);
        }
    }
}

function displayMainPanelIco($canDo){
    $user =  JFactory::getUser();
    $dispatcher = \JFactory::getApplication();
    $adminaccess = $user->authorise('core.admin', 'com_jshopping');
    $installaccess = $user->authorise('core.admin.install', 'com_jshopping');
    $linkToEditor = (isSmartEditorEnabled()) ? 'index.php?option=com_expresseditor' : 'index.php?option=com_jshopping&controller=service&task=redirectBackWithMsg&msgType=error&msg=' . rawurlencode(JText::_('COM_SMARTSHOP_COMPONENT_NOT_INSTALLED_OR_ACTIVATED'));

    $menu = [];
    if ($canDo->get('smartshop.smarteditor')) $menu['smarteditor'] = array('smart | EDITOR', $linkToEditor, '<i class="fas fa-paint-brush"></i>', 1);
    if ($canDo->get('smartshop.categories')) $menu['categories'] = array( JText::_('COM_SMARTSHOP_MENU_CATEGORIES'), 'index.php?option=com_jshopping&controller=categories', '<i class="fas fa-boxes"></i>', 1);
    if ($canDo->get('smartshop.products')) $menu['products'] = array( JText::_('COM_SMARTSHOP_MENU_PRODUCTS'), 'index.php?option=com_jshopping&controller=products', '<i class="fas fa-shopping-basket"></i>', 1);
    if ($canDo->get('smartshop.orders')) $menu['orders'] = array(  JText::_('COM_SMARTSHOP_MENU_ORDERS'), 'index.php?option=com_jshopping&controller=orders', '<i class="far fa-sticky-note"></i>', 1);
    if ($canDo->get('smartshop.users')) $menu['users'] = array( JText::_('COM_SMARTSHOP_MENU_CLIENTS'), 'index.php?option=com_jshopping&controller=users', '<i class="fas fa-users"></i>', 1);
    if ($canDo->get('smartshop.options')) $menu['other'] = array( JText::_('COM_SMARTSHOP_MENU_OTHER'), 'index.php?option=com_jshopping&controller=other', '<i class="fas fa-sliders-h"></i>', 1);
    if ($canDo->get('smartshop.configuration')) $menu['config'] = array(  JText::_('COM_SMARTSHOP_MENU_CONFIG'), 'index.php?option=com_jshopping&controller=config', '<i class="fas fa-tools"></i>', $adminaccess );

    $dispatcher->triggerEvent( 'onBeforeAdminMainPanelIcoDisplay', [&$menu]);

    $menu['documentation'] = ['<p class="textToSHDocBlankTarget">' . Text::_('COM_SMARTSHOP_DOCUMENTATION') . '</p>', 'index.php?option=com_jshopping&controller=service&task=redirectToShopDocumentation', '<i class="fas fa-book"></i>', 1];

    foreach($menu as $item){
        if ($item[3]){
            quickiconButton($item[1], $item[2], $item[0]);
        }
    }
}

function displayOptionPanelIco($canDo=array()){
    $jshopConfig = JSFactory::getConfig();
    $user = JFactory::getUser();
    $dispatcher = \JFactory::getApplication();
    $adminaccess = $user->authorise('core.admin', 'com_jshopping');	
    $menu = array();
	if ($canDo->get('smartshop.options.manufacturers')) $menu['manufacturers'] = array( JText::_('COM_SMARTSHOP_MENU_MANUFACTURERS'), 'index.php?option=com_jshopping&controller=manufacturers', '<i class="fas fa-industry panelicons"></i>', 1);
    if ($canDo->get('smartshop.options.coupons')) $menu['coupons'] = array( JText::_('COM_SMARTSHOP_MENU_COUPONS'), 'index.php?option=com_jshopping&controller=coupons', '<i class="fas fa-ticket-alt"></i>', 1);
    if ($canDo->get('smartshop.options.currencies')) $menu['currencies'] = array( JText::_('COM_SMARTSHOP_PANEL_CURRENCIES'), 'index.php?option=com_jshopping&controller=currencies', '<i class="far fa-money-bill-alt"></i>', 1);
    if ($canDo->get('smartshop.options.taxes')) $menu['taxes'] = array( JText::_('COM_SMARTSHOP_PANEL_TAXES'), 'index.php?option=com_jshopping&controller=taxes', '<i class="fas fa-cash-register"></i>', $jshopConfig->tax);
    if ($canDo->get('smartshop.options.payments')) $menu['payments'] = array( JText::_('COM_SMARTSHOP_PANEL_PAYMENTS'), 'index.php?option=com_jshopping&controller=payments', '<i class="far fa-credit-card"></i>', ($adminaccess && 1));
    if ($canDo->get('smartshop.options.shippingsprices')) $menu['shippingsprices'] = array( JText::_('COM_SMARTSHOP_PANEL_SHIPPINGS'), 'index.php?option=com_jshopping&controller=shippingsprices', '<i class="fas fa-dolly"></i>', 1);
    if ($canDo->get('smartshop.options.deliverytimes')) $menu['deliverytimes'] = array( JText::_('COM_SMARTSHOP_PANEL_DELIVERY_TIME'), 'index.php?option=com_jshopping&controller=deliverytimes', '<i class="fas fa-shipping-fast"></i>', 1);
    if ($canDo->get('smartshop.options.production_calendar')) $menu['production_calendar'] = array(JText::_('COM_SMARTSHOP_PRODUCTION_CALENDAR'), 'index.php?option=com_jshopping&controller=production_calendar', '<i class="fas fa-calendar-alt"></i>', 1);
    if ($canDo->get('smartshop.options.orderstatus')) $menu['orderstatus'] = array( JText::_('COM_SMARTSHOP_PANEL_ORDER_STATUS'), 'index.php?option=com_jshopping&controller=orderstatus', '<i class="fas fa-tasks"></i>', 1);
    if ($canDo->get('smartshop.options.countries')) $menu['countries'] = array( JText::_('COM_SMARTSHOP_PANEL_COUNTRIES'), 'index.php?option=com_jshopping&controller=countries', '<i class="fas fa-globe"></i>', 1);
    if ($canDo->get('smartshop.options.attributes')) $menu['attributes'] = array( JText::_('COM_SMARTSHOP_PANEL_ATTRIBUTES'), 'index.php?option=com_jshopping&controller=attributes', '<i class="fas fa-align-justify"></i>', 1);
    if ($canDo->get('smartshop.options.freeattributes')) $menu['freeattributes'] = array( JText::_('COM_SMARTSHOP_FREE_ATTRIBUTES'), 'index.php?option=com_jshopping&controller=freeattributes', '<i class="fas fa-align-center"></i>', 1);
    if ($canDo->get('smartshop.options.units')) $menu['units'] = array( JText::_('COM_SMARTSHOP_PANEL_UNITS_MEASURE'), 'index.php?option=com_jshopping&controller=units', '<i class="fas fa-ruler-combined"></i>', 1);
    if ($canDo->get('smartshop.options.usergroups')) $menu['usergroups'] = array( JText::_('COM_SMARTSHOP_PANEL_USERGROUPS'), 'index.php?option=com_jshopping&controller=usergroups', '<i class="fas fa-users"></i>', 1);    
    if ($canDo->get('smartshop.options.reviews')) $menu['reviews'] = array( JText::_('COM_SMARTSHOP_PANEL_REVIEWS'), 'index.php?option=com_jshopping&controller=reviews', '<i class="far fa-comments"></i>', 1);
    if ($canDo->get('smartshop.options.productlabels')) $menu['productlabels'] = array( JText::_('COM_SMARTSHOP_PANEL_PRODUCT_LABELS'), 'index.php?option=com_jshopping&controller=productlabels', '<i class="fas fa-tags"></i>', 1);
    if ($canDo->get('smartshop.options.productfields')) $menu['productfields'] = array( JText::_('COM_SMARTSHOP_PANEL_PRODUCT_EXTRA_FIELDS'), 'index.php?option=com_jshopping&controller=productfields', '<i class="fas fa-ruler"></i>', 1);
    if ($canDo->get('smartshop.options.importexport')) $menu['importexport'] = array( JText::_('COM_SMARTSHOP_PANEL_IMPORT_EXPORT'), 'index.php?option=com_jshopping&controller=importexport', '<i class="fas fa-sync"></i>', 1);
    if ($canDo->get('smartshop.options.addons')) $menu['addons'] = array( JText::_('COM_SMARTSHOP_ADDONS'), 'index.php?option=com_jshopping&controller=addons', '<i class="fas fa-cogs"></i>', $adminaccess);
	if ($canDo->get('smartshop.options.logs')) $menu['logs'] = array( JText::_('COM_SMARTSHOP_LOGS'), 'index.php?option=com_jshopping&controller=logs', '<i class="fas fa-inbox"></i>', $jshopConfig->shop_mode==1 && $adminaccess);
	if ($canDo->get('smartshop.options.formulacalculator')) $menu['formula_calc'] = array(JText::_('COM_SMARTSHOP_FREE_ATTRIBUTE_CALCULE_PRICE'), 'index.php?option=com_jshopping&controller=formula_calculation', '<i class="fas fa-calculator"></i>', 1);
    if ($canDo->get('smartshop.options.offer')) $menu['addon_offer_and_order'] = array(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_ANGEBOT'), 'index.php?option=com_jshopping&controller=offer_and_order', '<i class="fas fa-receipt"></i>', 1);    
	if ($canDo->get('smartshop.options.upload')) $menu['upload'] = array(JText::_('COM_SMARTSHOP_UPLOAD'), 'index.php?option=com_jshopping&controller=upload', '<i class="fas fa-upload"></i>', 1);	
	if ($canDo->get('smartshop.options.states')) $menu['states'] = [JText::_('COM_SMARTSHOP_STATES'), 'index.php?option=com_jshopping&controller=states', '<i class="fas fa-clipboard-list"></i>', 1];
	if ($canDo->get('smartshop.options.returnstatus')) $menu['returnstatus'] = array( JText::_('COM_SMARTSHOP_PANEL_RETURN_STATUS'), 'index.php?option=com_jshopping&controller=returnstatus', '<i class="fas fa-th-list"></i>', 1);
	if ($canDo->get('smartshop.options.license')) $menu['license'] = array( JText::_('COM_SMARTSHOP_PANEL_LICENSE'), 'index.php?option=com_jshopping&controller=license', '<i class="fas fa-file-contract"></i>', 1);
    
    $dispatcher->triggerEvent('onBeforeAdminOptionPanelIcoDisplay', array(&$menu,$canDo));

    foreach($menu as $item){
        if ($item[3]){
            quickiconButton($item[1], $item[2], $item[0]);
        }
    }
}

function getItemsOptionPanelMenu($canDo){
    $jshopConfig = JSFactory::getConfig();
    $user = JFactory::getUser();
    $dispatcher = \JFactory::getApplication();
    $adminaccess = $user->authorise('core.admin', 'com_jshopping');

    $menu = array();
    if ($canDo->get('smartshop.options.manufacturers')) $menu['manufacturers'] = array( JText::_('COM_SMARTSHOP_MENU_MANUFACTURERS'), 'index.php?option=com_jshopping&controller=manufacturers', '<i class="fas fa-industry panelicons"></i>', 1);
    if ($canDo->get('smartshop.options.coupons')) $menu['coupons'] = array( JText::_('COM_SMARTSHOP_MENU_COUPONS'), 'index.php?option=com_jshopping&controller=coupons', '<i class="fas fa-ticket-alt"></i>', 1);
    if ($canDo->get('smartshop.options.currencies')) $menu['currencies'] = array( JText::_('COM_SMARTSHOP_PANEL_CURRENCIES'), 'index.php?option=com_jshopping&controller=currencies', '<i class="far fa-money-bill-alt"></i>', 1);
    if ($canDo->get('smartshop.options.taxes')) $menu['taxes'] = array( JText::_('COM_SMARTSHOP_PANEL_TAXES'), 'index.php?option=com_jshopping&controller=taxes', '<i class="fas fa-cash-register"></i>', 1);
    if ($canDo->get('smartshop.options.payments')) $menu['payments'] = array( JText::_('COM_SMARTSHOP_PANEL_PAYMENTS'), 'index.php?option=com_jshopping&controller=payments', '<i class="far fa-credit-card"></i>', ($adminaccess && 1));
    if ($canDo->get('smartshop.options.shippingsprices')) $menu['shippingsprices'] = array( JText::_('COM_SMARTSHOP_PANEL_SHIPPINGS'), 'index.php?option=com_jshopping&controller=shippingsprices', '<i class="fas fa-dolly"></i>', 1);
    if ($canDo->get('smartshop.options.deliverytimes')) $menu['deliverytimes'] = array( JText::_('COM_SMARTSHOP_PANEL_DELIVERY_TIME'), 'index.php?option=com_jshopping&controller=deliverytimes', '<i class="fas fa-shipping-fast"></i>', 1);
    if ($canDo->get('smartshop.options.production_calendar')) $menu['production_calendar'] = array(JText::_('COM_SMARTSHOP_PRODUCTION_CALENDAR'), 'index.php?option=com_jshopping&controller=production_calendar', '<i class="fas fa-calendar-alt"></i>', 1);
    if ($canDo->get('smartshop.options.orderstatus')) $menu['orderstatus'] = array( JText::_('COM_SMARTSHOP_PANEL_ORDER_STATUS'), 'index.php?option=com_jshopping&controller=orderstatus', '<i class="fas fa-tasks"></i>', 1);
    if ($canDo->get('smartshop.options.countries')) $menu['countries'] = array( JText::_('COM_SMARTSHOP_PANEL_COUNTRIES'), 'index.php?option=com_jshopping&controller=countries', '<i class="fas fa-globe"></i>', 1);
    if ($canDo->get('smartshop.options.attributes')) $menu['attributes'] = array( JText::_('COM_SMARTSHOP_PANEL_ATTRIBUTES'), 'index.php?option=com_jshopping&controller=attributes', '<i class="fas fa-align-justify"></i>', 1);
    if ($canDo->get('smartshop.options.freeattributes')) $menu['freeattributes'] = array( JText::_('COM_SMARTSHOP_FREE_ATTRIBUTES'), 'index.php?option=com_jshopping&controller=freeattributes', '<i class="fas fa-align-center"></i>', 1);
    if ($canDo->get('smartshop.options.units')) $menu['units'] = array( JText::_('COM_SMARTSHOP_PANEL_UNITS_MEASURE'), 'index.php?option=com_jshopping&controller=units', '<i class="fas fa-ruler-combined"></i>', 1);
    if ($canDo->get('smartshop.options.usergroups')) $menu['usergroups'] = array( JText::_('COM_SMARTSHOP_PANEL_USERGROUPS'), 'index.php?option=com_jshopping&controller=usergroups', '<i class="fas fa-users"></i>', 1);    
    if ($canDo->get('smartshop.options.reviews')) $menu['reviews'] = array( JText::_('COM_SMARTSHOP_PANEL_REVIEWS'), 'index.php?option=com_jshopping&controller=reviews', '<i class="far fa-comments"></i>', 1);
    if ($canDo->get('smartshop.options.productlabels')) $menu['productlabels'] = array( JText::_('COM_SMARTSHOP_PANEL_PRODUCT_LABELS'), 'index.php?option=com_jshopping&controller=productlabels', '<i class="fas fa-tags"></i>', 1);
    if ($canDo->get('smartshop.options.productfields')) $menu['productfields'] = array( JText::_('COM_SMARTSHOP_PANEL_PRODUCT_EXTRA_FIELDS'), 'index.php?option=com_jshopping&controller=productfields', '<i class="fas fa-ruler"></i>', 1);    
    if ($canDo->get('smartshop.options.importexport')) $menu['importexport'] = array( JText::_('COM_SMARTSHOP_PANEL_IMPORT_EXPORT'), 'index.php?option=com_jshopping&controller=importexport', '<i class="fas fa-sync"></i>', 1);
    if ($canDo->get('smartshop.options.addons')) $menu['addons'] = array( JText::_('COM_SMARTSHOP_ADDONS'), 'index.php?option=com_jshopping&controller=addons', '<i class="fas fa-cogs"></i>', $adminaccess);
    if ($canDo->get('smartshop.options.logs')) $menu['logs'] = array( JText::_('COM_SMARTSHOP_LOGS'), 'index.php?option=com_jshopping&controller=logs', '<i class="fas fa-inbox"></i>', $jshopConfig->shop_mode==1 && $adminaccess);
    if ($canDo->get('smartshop.options.formulacalculator')) $menu['formula_calc'] = array( JText::_('COM_SMARTSHOP_FREE_ATTRIBUTE_CALCULE_PRICE'), 'index.php?option=com_jshopping&controller=formula_calculation', '<i class="fas fa-calculator"></i>', 1);
    if ($canDo->get('smartshop.options.offer')) $menu['addon_offer_and_order'] = array(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_ANGEBOT'), 'index.php?option=com_jshopping&controller=offer_and_order', '<i class="fas fa-receipt"></i>', 1);
    if ($canDo->get('smartshop.options.upload')) $menu['upload'] = array(JText::_('COM_SMARTSHOP_UPLOAD'), 'index.php?option=com_jshopping&controller=upload', '<i class="fas fa-upload"></i>', 1);
	if ($canDo->get('smartshop.options.states')) $menu['states'] = [JText::_('COM_SMARTSHOP_STATES'), 'index.php?option=com_jshopping&controller=states', '<i class="fas fa-clipboard-list"></i>', 1];
	if ($canDo->get('smartshop.options.returnstatus')) $menu['returnstatus'] = array( JText::_('COM_SMARTSHOP_PANEL_RETURN_STATUS'), 'index.php?option=com_jshopping&controller=returnstatus', '<i class="fas fa-th-list"></i>', 1);
    if ($canDo->get('smartshop.options.license')) $menu['license'] = array( JText::_('COM_SMARTSHOP_PANEL_LICENSE'), 'index.php?option=com_jshopping&controller=license', '<i class="fas fa-file-contract"></i>', 1);
	
    $dispatcher->triggerEvent( 'onBeforeAdminOptionPanelMenuDisplay', array(&$menu,$canDo) );

    return $menu;
}

function displayConfigPanelIco($canDo){
    $jshopConfig = JSFactory::getConfig();
    $user = JFactory::getUser();
    $dispatcher = \JFactory::getApplication();

    $menu = array();
    if ($canDo->get('smartshop.configuration.shopfunctions')) $menu['adminfunction'] = array( JText::_('COM_SMARTSHOP_SHOP_FUNCTION'), 'index.php?option=com_jshopping&controller=config&task=adminfunction', '<i class="fas fa-tools"></i>', 1);
    if ($canDo->get('smartshop.configuration.general')) $menu['general'] = array( JText::_('COM_SMARTSHOP_GENERAL_PARAMETERS'), 'index.php?option=com_jshopping&controller=config&task=general', '<i class="fas fa-cogs"></i>', 1);
    if ($canDo->get('smartshop.configuration.product')) $menu['catprod'] = array( JText::_('COM_SMARTSHOP_CAT_PROD'), 'index.php?option=com_jshopping&controller=config&task=catprod', '<i class="fas fa-shopping-basket"></i>', 1);
    if ($canDo->get('smartshop.configuration.checkout')) $menu['checkout'] = array( JText::_('COM_SMARTSHOP_CHECKOUT'), 'index.php?option=com_jshopping&controller=config&task=checkout', '<i class="fas fa-box-open"></i>', 1);
    if ($canDo->get('smartshop.configuration.registration')) $menu['fieldregister'] = array( JText::_('COM_SMARTSHOP_REGISTER_FIELDS'), 'index.php?option=com_jshopping&controller=config&task=fieldregister', '<i class="fas fa-clipboard-list"></i>', 1);
    if ($canDo->get('smartshop.configuration.media')) $menu['image'] = array( JText::_('COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS'), 'index.php?option=com_jshopping&controller=config&task=image', '<i class="fas fa-photo-video"></i>', 1);
	if ($canDo->get('smartshop.configuration.content')) $menu['content'] = array( JText::_('COM_SMARTSHOP_CONTENT'), 'index.php?option=com_jshopping&controller=config&task=content', '<i class="far fa-newspaper"></i>', 1);
    if ($canDo->get('smartshop.configuration.shopinfo')) $menu['storeinfo'] = array( JText::_('COM_SMARTSHOP_STORE_INFO'), 'index.php?option=com_jshopping&controller=config&task=storeinfo', '<i class="fas fa-barcode"></i>', 1);
    if ($canDo->get('smartshop.configuration.orders')) $menu['orders'] = array( JText::_('COM_SMARTSHOP_ORDERS'), 'index.php?option=com_jshopping&controller=config&task=orders', '<i class="fas fa-tasks"></i>', 1);
    if ($canDo->get('smartshop.configuration.pdfpub')) $menu['pdf'] = array( JText::_('COM_SMARTSHOP_CONFIGURATION_PDF'), 'index.php?option=com_jshopping&controller=config&task=pdf', '<i class="fas fa-mail-bulk"></i>', 1);
	if ($canDo->get('smartshop.configuration.storage')) $menu['storage'] = array( JText::_('COM_SMARTSHOP_STORAGE'), 'index.php?option=com_jshopping&controller=config&task=storage', '<i class="fas fa-database"></i>', 1);
    $dispatcher->triggerEvent( 'onBeforeAdminConfigPanelIcoDisplay', array(&$menu) );

    foreach($menu as $item){
        if ($item['3'] && !empty($item['1']) ){
            quickiconButton($item['1'], $item['2'], $item['0']);
        }
    }
}

function getItemsConfigPanelMenu($canDo){
    $jshopConfig = JSFactory::getConfig();
    $user = JFactory::getUser();
    $dispatcher = \JFactory::getApplication();

    $menu = array();
    if ($canDo->get('smartshop.configuration.shopfunctions'))$menu['adminfunction'] = array( JText::_('COM_SMARTSHOP_SHOP_FUNCTION'), 'index.php?option=com_jshopping&controller=config&task=adminfunction', '<i class="fas fa-tools"></i>', 1);
    if ($canDo->get('smartshop.configuration.general'))$menu['general'] = array( JText::_('COM_SMARTSHOP_GENERAL_PARAMETERS'), 'index.php?option=com_jshopping&controller=config&task=general', '<i class="fas fa-cogs"></i>', 1);
    if ($canDo->get('smartshop.configuration.product'))$menu['catprod'] = array( JText::_('COM_SMARTSHOP_CAT_PROD'), 'index.php?option=com_jshopping&controller=config&task=catprod', '<i class="fas fa-shopping-basket"></i>', 1);    
    if ($canDo->get('smartshop.configuration.checkout'))$menu['checkout'] = array( JText::_('COM_SMARTSHOP_CHECKOUT'), 'index.php?option=com_jshopping&controller=config&task=checkout', '<i class="fas fa-box-open"></i>', 1);
    if ($canDo->get('smartshop.configuration.registration'))$menu['fieldregister'] = array( JText::_('COM_SMARTSHOP_REGISTER_FIELDS'), 'index.php?option=com_jshopping&controller=config&task=fieldregister', '<i class="fas fa-clipboard-list"></i>', 1);
    if ($canDo->get('smartshop.configuration.media'))$menu['image'] = array( JText::_('COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS'), 'index.php?option=com_jshopping&controller=config&task=image', '<i class="fas fa-photo-video"></i>', 1);
    if ($canDo->get('smartshop.configuration.content'))$menu['content'] = array( JText::_('COM_SMARTSHOP_CONTENT'), 'index.php?option=com_jshopping&controller=config&task=content', '<i class="far fa-newspaper"></i>', 1);    
    if ($canDo->get('smartshop.configuration.shopinfo'))$menu['storeinfo'] = array( JText::_('COM_SMARTSHOP_STORE_INFO'), 'index.php?option=com_jshopping&controller=config&task=storeinfo', '<i class="fas fa-barcode"></i>', 1);
	if ($canDo->get('smartshop.configuration.orders'))$menu['orders'] = array( JText::_('COM_SMARTSHOP_ORDERS'), 'index.php?option=com_jshopping&controller=config&task=orders', '<i class="fas fa-tasks"></i>', 1);
	if ($canDo->get('smartshop.configuration.pdfpub'))$menu['pdf'] = array( JText::_('COM_SMARTSHOP_CONFIGURATION_PDF'), 'index.php?option=com_jshopping&controller=config&task=pdf', '<i class="fas fa-mail-bulk"></i>', 1);
    if ($canDo->get('smartshop.configuration.storage')) $menu['storage'] = array( JText::_('COM_SMARTSHOP_STORAGE'), 'index.php?option=com_jshopping&controller=config&task=storage', '<i class="fas fa-database"></i>', 1);
    $dispatcher->triggerEvent( 'onBeforeAdminConfigPanelMenuDisplay', array(&$menu) );

    return $menu;
}


function checkAccessController($name){
    $mainframe = JFactory::getApplication();
    $user = JFactory::getUser();

    $adminaccess = $user->authorise('core.admin', 'com_jshopping');
    $installaccess = $user->authorise('core.admin.install', 'com_jshopping');

    $access = array();
    $access["config"] = $user->authorise('core.admin', 'com_jshopping')==1;
    $access["payments"] = $user->authorise('core.admin', 'com_jshopping')==1;
    $access["shippings"] = $user->authorise('core.admin', 'com_jshopping')==1;
    $access["shippingsprices"] = $user->authorise('core.admin', 'com_jshopping')==1;    
    $access["addons"] = $user->authorise('core.admin', 'com_jshopping')==1;
    $access["logs"] = $user->authorise('core.admin', 'com_jshopping')==1;
    $access["update"] = $user->authorise('core.admin.install', 'com_jshopping')==1;

    $dispatcher = \JFactory::getApplication();
    $dispatcher->triggerEvent('onBeforeAdminCheckAccessController', array(&$access));

    if (isset($access[$name]) && !$access[$name]){
        $mainframe->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
        return 0;
    }
}

function displaySubmenuOptions($active, $canDo){
    include(JPATH_COMPONENT_ADMINISTRATOR."/views/panel/tmpl/options_submenu.php");
}

function displaySubmenuConfigs($active, $canDo){
    include(JPATH_COMPONENT_ADMINISTRATOR."/views/config/tmpl/submenu.php");
}

function getIdVendorForCUser(){
	static $id;
	$jshopConfig = JSFactory::getConfig();
    if (!$jshopConfig->admin_show_vendors) return 0;
    if (!isset($id)){
		$id = JSFactory::getMainVendor();
		$dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onAftergetIdVendorForCUser', array(&$id));
    }
    return $id;
}

function checkAccessVendorToProduct($id_vendor_cuser, $product_id){
    $mainframe = JFactory::getApplication();
    $product = JSFactory::getTable('product', 'jshop');
    $product->load($product_id);
    if ($product->vendor_id!=$id_vendor_cuser){
        $mainframe->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
        return 0;
    }
}

function SEFLinkFromAdmin($link, $fullurl = 0, $langprefix=''){
    $config =JFactory::getConfig();
    $app = JApplication::getInstance('site');
    $router = $app->getRouter();
    if (!preg_match('/Itemid/', $link)){
        $Itemid = getDefaultItemid();
        if (preg_match('/\?/', $link)) $sp = "&"; else $sp = "?";
        $link.=$sp.'Itemid='.$Itemid;
    }
    $uri = $router->build($link);
    $url = $uri->toString();
    $url = str_replace('/administrator', '', $url);
    if ($langprefix!=''){
        if ($config->get('sef_rewrite')){
            $url = "/".$langprefix.$url;
        }else{
            $url = str_replace("index.php", "index.php/".$langprefix, $url);
        }
    }
    if ($fullurl){
        $juri = JURI::getInstance();
        $liveurlhost = $juri->toString( array("scheme",'host', 'port'));
        $url = $liveurlhost.$url;
    }
return $url;
}

function displaySubSubmenuConfigs($active=""){
    include(JPATH_COMPONENT_ADMINISTRATOR."/views/config/tmpl/subsubmenu.php");
}
function getItemsConfigPanelSubMenu(){
    $jshopConfig = JSFactory::getConfig();
    $menu = array();
    $menu['email_hub'] = array( JText::_('COM_SMARTSHOP_EMAIL_HUB'), 'index.php?option=com_jshopping&controller=config&task=email_hub', '<i class="fas fa-mail-bulk"></i>', 1);
    $menu['template_creator'] = array( JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR'), 'index.php?option=com_jshopping&controller=config&task=template_creator', '<i class="far fa-file-powerpoint"></i>', 1);
    return $menu;
}

/**
*   @param string $nameOfColumn
*   @param string $tableName
*
*   @return boolean
*/
function isTableColumnExists($nameOfColumn, $tableName)
{
    $dbo = JFactory::getDbo();

    $sql = 'SHOW COLUMNS FROM ' . $dbo->quoteName($tableName) . ' LIKE ' . $dbo->quote($nameOfColumn);
    $dbo->setQuery($sql);

    if ( empty($dbo->loadResult()) ) {
        return false;
    } else {
        return true;
    }
}
