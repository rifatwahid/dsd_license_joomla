<?php

defined('_JEXEC') or die('Restricted access');
include_once __DIR__ . '/lib/factory.php';
include_once __DIR__ . '/lib/shop_item_menu.php';

function jshoppingBuildRoute(&$query){
    $segments = [];
    JSFactory::loadLanguageFile();
    $lang = isset($query['lang']) ? $query['lang'] : '';
    $shim = ShopItemMenu::getInstance($lang);
    \JPluginHelper::importPlugin('jshoppingrouter');
    $dispatcher = \JFactory::getApplication();
    $dispatcher->triggerEvent('onBeforeBuildRoute', array(&$query, &$segments));
    $categoryitemidlist = $shim->getListCategory();
    $productitemidlist = $shim->getListProduct();
    $app = \JFactory::getApplication();
    $menu = $app->getMenu();
	$jshopConfig = JSFactory::getConfig();
	$current_lang = $jshopConfig->getLang();

    if (isset($query['controller']) && $query['controller'] == "qcheckout") {
        $db = \JFactory::getDBO();
        $groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
        $db->setQuery("SELECT id, link FROM #__menu WHERE `type` = 'component' AND published = 1 AND link like '%option=com_jshopping&controller=qcheckout%' AND client_id = 0 AND (language='*' OR language='".$current_lang."') AND access IN (".$groups.") LIMIT 1");
	    $item = $db->loadObject();

        if ($item instanceof stdClass && isset($item->id) && $item->id) {
            $query['Itemid'] = $item->id;
		    unset($query['controller']);
        }
	}
    if (isset($query['controller']) && $query['controller'] == "user" && isset($query['task'])) {
        $db = \JFactory::getDBO();
        $groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
        $db->setQuery("SELECT id, link FROM #__menu WHERE `type` = 'component' AND published = 1 AND link like '%option=com_jshopping&view=user&task=".$query['task']."%' AND client_id = 0 AND (language='*' OR language='".$current_lang."') AND access IN (".$groups.") LIMIT 1");
	    $item = $db->loadObject();

        if ($item instanceof stdClass && isset($item->id) && $item->id) {
            $query['Itemid'] = $item->id;
		    unset($query['controller']);
        }
	}
    if (isset($query['view']) && !isset($query['controller'])){
        $query['controller'] = $query['view'];
    }
    unset($query['view']);

    if (isset($query['controller'])){
        $controller = $query['controller'];
    }else{
        $controller = "";
    }
    if (in_array($controller, array('category', 'manufacturer', 'vendor'))){
        unset($query['layout']);
    }
    
    if (isset($query['Itemid']) && $query['Itemid']){
        $clearQuery = 1;
        $menuItem = $menu->getItem($query['Itemid']);
        if (isset($query['controller']) && $query['controller']=='category' && isset($query['task']) && $query['task']!='' && (!isset($menuItem->query['category_id']) || !$menuItem->query['category_id'])){
            $clearQuery = 0;
        }
        if (isset($query['controller']) && $query['controller']=='product' && isset($query['task']) && $query['task']!=''){
         if(isset($menuItem->query['product_id'])){
			$clearQuery = 0;
		 }elseif(isset($productitemidlist[$query['product_id']])){
			 $query['Itemid'] = $productitemidlist[$query['product_id']];
			 $menuItem = $menu->getItem($query['Itemid']);
			 $clearQuery = 0;
		 
		 }
		 
        }
        if (isset($query['controller']) && $query['controller']=='manufacturer' && isset($query['task']) && $query['task']!='' && (!isset($menuItem->query['manufacturer_id']) || !$menuItem->query['manufacturer_id'])){
            $clearQuery = 0;
        }
        if (isset($query['controller']) && $query['controller']!=$menuItem->query['view'] && ($query['controller']!='product' || ($query['controller']=='product' && $menuItem->query['product_id'] != $query['product_id']))){
            $clearQuery = 0;
        }
        if (isset($query['task'])&& ($query['controller']!='product' || ($query['controller']=='product' && $menuItem->query['product_id'] != $query['product_id'])) && isset($menuItem->query['task']) && $menuItem->query['task'] && $query['task']!=$menuItem->query['task']){
            $clearQuery = 0;
        }
        if ($clearQuery){                
            foreach($menuItem->query as $k=>$v){
                if ($k=='option') continue;
                if (isset($query[$k]) && $query[$k]==$v){
                    unset($query[$k]);
                }
                if ($k=='view' && isset($query['controller']) && $query['controller']==$v){
                    unset($query['controller']);
                }
            }
        }
    }

    if ($controller=="category" && isset($query['task']) && isset($query['category_id']) && $query['task']=="view" && $query['category_id']){
        if (isset($categoryitemidlist[$query['category_id']])){
            $query['Itemid'] = $categoryitemidlist[$query['category_id']];
            unset($query['controller']);
            unset($query['category_id']);
            unset($query['task']);
        }else{
            $catalias = JSFactory::getAliasCategory();
            if (isset($catalias[$query['category_id']])){
                $segments[] = $catalias[$query['category_id']];
                unset($query['controller']);
                unset($query['task']); 
                unset($query['category_id']);
            }
        }
    }
    if ($controller=="product" && isset($query['task']) && isset($query['product_id']) && $query['task']=="view" && $query['product_id']){
       
	   if (isset($productitemidlist[$query['product_id']])){
		    $prodalias = \JSFactory::getAliasProduct($lang);
			$catalias = \JSFactory::getAliasCategory($lang);
			
            $query['Itemid'] = $productitemidlist[$query['product_id']];
			
            if(isset($query['controller'])) unset($query['controller']);
			if(isset($query['task'])) unset($query['task']);
			if(isset($query['category_id'])) unset($query['category_id']);
			if(isset($query['product_id'])) unset($query['product_id']);
			
        }else{
            $prodalias = \JSFactory::getAliasProduct($lang);
			$catalias = \JSFactory::getAliasCategory($lang);
			
	
   
			if (isset($categoryitemidlist[$query['category_id']]) && isset($prodalias[$query['product_id']])){
				$segments[] = $prodalias[$query['product_id']];            
			}elseif (isset($catalias[$query['category_id']]) && isset($prodalias[$query['product_id']])){
				$segments[] = $catalias[$query['category_id']];
				$segments[] = $prodalias[$query['product_id']];            
			}
	
			if (isset($categoryitemidlist[$query['category_id']])){
				$query['Itemid'] = $categoryitemidlist[$query['category_id']];		            
			}
			if(!$query['Itemid']){
				$db = \JFactory::getDBO();
				$groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
				$db->setQuery("SELECT id, link FROM #__menu WHERE `type` = 'component' AND published = 1 AND link like '%option=com_jshopping&view=products%'  AND (language='*' OR language='".$current_lang."')  LIMIT 1");
				$item = $db->loadObject();
				
				if ($item instanceof stdClass && isset($item->id) && $item->id) {
					$query['Itemid'] = $item->id;
					unset($query['controller']);
				}
			
			}
			if(isset($query['controller'])) unset($query['controller']);
			if(isset($query['task'])) unset($query['task']);
			if(isset($query['category_id'])) unset($query['category_id']);
			if(isset($query['product_id'])) unset($query['product_id']);
		}
    }

    if ($controller=="manufacturer" && isset($query['task']) && $query['task']=="view" && isset($query['manufacturer_id']) && $query['manufacturer_id']){
        $manufactureritemidlist = $shim->getListManufacturer();
        if (isset($manufactureritemidlist[$query['manufacturer_id']])){
            $query['Itemid'] = $manufactureritemidlist[$query['manufacturer_id']];
            unset($query['controller']);
            unset($query['task']);
            unset($query['manufacturer_id']);
        }else{
            $manalias = JSFactory::getAliasManufacturer();
            if (isset($manalias[$query['manufacturer_id']])){
                $segments[] = $manalias[$query['manufacturer_id']];
                unset( $query['controller'] );
                unset( $query['task'] ); 
                unset( $query['manufacturer_id'] );
            }
        }
    }

    if ($controller=="content" && isset($query['task']) && $query['task']=="view" && isset($query['page']) && $query['page']){
        $contentitemidlist = $shim->getListContent();
        if (isset($contentitemidlist[$query['page']])){
            $query['Itemid'] = $contentitemidlist[$query['page']];
            unset($query['controller']);
            unset($query['task']);
            unset($query['page']);
        }
    }

    if (isset($query['controller'])){
        $segments[] = $query['controller'];
        unset($query['controller']);
    }

    if (isset($query['task']) && $query['task']!='') {
        $segments[] = $query['task'];
        unset($query['task']);
    }

    if ($controller=="category" || $controller=="product"){
        if (isset($query['category_id'])) {
            $segments[] = $query['category_id'];
            unset($query['category_id']);
        }

        if (isset($query['product_id'])) {
            $segments[] = $query['product_id'];
            unset($query['product_id']);
        }
    }

    if ($controller=="manufacturer"){
        if (isset($query['manufacturer_id'])) {
            $segments[] = $query['manufacturer_id'];
            unset($query['manufacturer_id']);
        }
    }

    if ($controller=="content"){
        if (isset($query['page'])) {
            $segments[] = $query['page'];
            unset($query['page']);
        }
    }

    $dispatcher->triggerEvent('onAfterBuildRoute', array(&$query, &$segments));
    return $segments;
}

function jshoppingParseRoute(&$segments){
    $vars = [];
    JSFactory::loadLanguageFile();
    $reservedFirstAlias = \JSFactory::getReservedFirstAlias();
    $menu = \JFactory::getApplication()->getMenu();
    $menuItem = $menu->getActive();
    \JPluginHelper::importPlugin('jshoppingrouter');
    $dispatcher = \JFactory::getApplication();
    $dispatcher->triggerEvent('onBeforeParseRoute', array(&$vars, &$segments));
    foreach($segments as $k=>$v){
        $segments[$k] = getSeoSegment($v);
    }
    if (!isset($segments[1])){
        $segments[1] = '';
    }

    if (!isset($menuItem->query['controller']) && isset($menuItem->query['view'])){
        $menuItem->query['controller'] = $menuItem->query['view'];
    }

    if (isset($menuItem->query['controller'])){
        if ($menuItem->query['controller']=="cart"){
            $vars['controller'] = "cart";
            $vars['task'] = $segments[0];
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }
        if ($menuItem->query['controller']=="wishlist"){
            $vars['controller'] = "wishlist";
            $vars['task'] = $segments[0];
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }
        if ($menuItem->query['controller']=="search"){
            $vars['controller'] = "search";
            $vars['task'] = $segments[0];
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }
        if ($menuItem->query['controller']=="user" && $menuItem->query['task']==""){
            $vars['controller'] = "user";
            $vars['task'] = $segments[0];
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }
        if ($menuItem->query['controller']=="qcheckout"){
            $vars['controller'] = "qcheckout";
            $vars['task'] = $segments[0];
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }
        if ($menuItem->query['controller']=="vendor" && $menuItem->query['task']==""){
            $vars['controller'] = "vendor";
            $vars['task'] = $segments[0];
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }
        if ($menuItem->query['controller']=="content" && $menuItem->query['task']=="view"){
            $vars['controller'] = "content";
            if (count($segments)==2){
                $vars['task'] = $segments[0];
                $vars['page'] = $segments[1];
            }else{
                $vars['page'] = $segments[0];
            }
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }
        if (isset($menuItem->query['controller']) && $menuItem->query['controller']=="category" && isset($menuItem->query['category_id']) && $menuItem->query['category_id'] && $segments[1]==""){
            $prodalias = \JSFactory::getAliasProduct();
            $product_id = array_search($segments[0], $prodalias, true);
            if (!$product_id){
                throw new \Exception(\JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'), 404);
            }

            $vars['controller'] = "product";
            $vars['task'] = "view";
            $vars['category_id'] = $menuItem->query['category_id'];
            $vars['product_id'] = $product_id;
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }
    }

    if ($segments[0] && !in_array($segments[0], $reservedFirstAlias)){
        $catalias = \JSFactory::getAliasCategory();
        $category_id = array_search($segments[0], $catalias, true);
        if ($category_id && $segments[1]==""){
            $vars['controller'] = "category";
            $vars['task'] = "view";
            $vars['category_id'] = $category_id;
            $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
            $segments = [];
            return $vars;
        }

        if ($category_id && $segments[1]!=""){
            $prodalias = \JSFactory::getAliasProduct();
            $product_id = array_search($segments[1], $prodalias, true);
            if (!$product_id){
                throw new \Exception(\JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'), 404);
            }
            if ($category_id && $product_id){
                $vars['controller'] = "product";
                $vars['task'] = "view";
                $vars['category_id'] = $category_id;
                $vars['product_id'] = $product_id;
                $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
                $segments = [];
                return $vars;
            }
        }

        if (!$category_id && $segments[1]==""){
            $manalias = \JSFactory::getAliasManufacturer();
            $manufacturer_id = array_search($segments[0], $manalias, true);
            if ($manufacturer_id){
                $vars['controller'] = "manufacturer";
                $vars['task'] = "view";
                $vars['manufacturer_id'] = $manufacturer_id;
                $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
                $segments = [];
                return $vars;
            }
        }

        throw new \Exception(\JText::_('COM_SMARTSHOP_PAGE_NOT_FOUND'), 404);
    }else{
        $vars['controller'] = $segments[0];
        $vars['task'] = $segments[1];

        if ($vars['controller']=="category" && $vars['task']=="view"){
            $vars['category_id'] = $segments[2];
        }

        if ($vars['controller']=="product" && $vars['task']=="view"){
            $vars['category_id'] = $segments[2];
            $vars['product_id'] = $segments[3];
        }

        if ($vars['controller']=="product" && $vars['task']=="ajax_attrib_select_and_price"){
            $vars['product_id'] = $segments[2];
        }

        if ($vars['controller']=="manufacturer" && isset($segments[2])){
            $vars['manufacturer_id'] = $segments[2];
        }

        if ($vars['controller']=="content"){
            $vars['page'] = $segments[2];
        }
    }

    $dispatcher->triggerEvent('onAfterParseRoute', array(&$vars, &$segments));
    $segments = [];
    return $vars;
}