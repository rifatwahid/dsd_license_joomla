<?php
/**
* @version      4.9.0 13.06.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.html.pagination');
class JShoppingControllerProduct_List_Selectable extends JControllerLegacy {
	
	protected $canDo;
	
	function display($cachable = false, $urlparams = false){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        checkAccessController("product_list_selectable");
		//JHTML::_('behavior.framework');
		$app = JFactory::getApplication();		
		$jshopConfig = JSFactory::getConfig();
		$_products = JSFactory::getModel('Products', 'JShoppingModel');

		$context = "jshoping.list.admin.product";
		$limit = $app->getUserStateFromRequest($context.'limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		if (isset($_GET['category_id']) && $_GET['category_id'] === "0"){
			$app->setUserState($context.'category_id', 0);
			$app->setUserState($context.'manufacturer_id', 0);
			$app->setUserState($context.'label_id', 0);
			$app->setUserState($context.'publish', 0);
			$app->setUserState($context.'text_search', '');
		}

		$category_id = $app->getUserStateFromRequest($context.'category_id', 'category_id', 0, 'int');
		$manufacturer_id = $app->getUserStateFromRequest($context.'manufacturer_id', 'manufacturer_id', 0, 'int');
		$label_id = $app->getUserStateFromRequest($context.'label_id', 'label_id', 0, 'int');
		$publish = $app->getUserStateFromRequest($context.'publish', 'publish', 0, 'int');
		$text_search = $app->getUserStateFromRequest($context.'text_search', 'text_search', '');
        $eName = JFactory::getApplication()->input->getVar('e_name');
		$jsfname = JFactory::getApplication()->input->getVar('jsfname');
        $eName = preg_replace('#[^A-Z0-9\-\_\[\]]#i', '', $eName);        
        if (!$jsfname) $jsfname = 'selectProductBehaviour';
		
		$filter = array("category_id" => $category_id,"manufacturer_id" => $manufacturer_id,"label_id" => $label_id,"publish" => $publish,"text_search" => $text_search);
		$total = $_products->getCountAllProducts($filter);
		$pagination = new JPagination($total, $limitstart, $limit);
		$rows = $_products->getAllProducts($filter, $pagination->limitstart, $pagination->limit);

        $parentTop = new stdClass();
		$parentTop->category_id = 0;
		$parentTop->name = " - ".JText::_('COM_SMARTSHOP_CATEGORY')." - ";
		$categories_select = buildTreeCategory(0,1,0);
		
		array_unshift($categories_select, $parentTop);  
		  
		$lists['treecategories'] = JHTML::_('select.genericlist', $categories_select, 'category_id', 'class="form-select" style="width: 150px;" onchange="document.adminForm.submit();"', 'category_id', 'name', $category_id);
		
		$manuf1 = array();
        $manuf1[0] = new stdClass();
		$manuf1[0]->manufacturer_id = '0';
		$manuf1[0]->name = " - ".JText::_('COM_SMARTSHOP_NAME_MANUFACTURER')." - ";
        $manufs = JSFactory::getModel('Manufacturers', 'JShoppingModel')->getList();

		$manufs = array_merge($manuf1, $manufs);
		$lists['manufacturers'] = JHTML::_('select.genericlist', $manufs, 'manufacturer_id', 'class="form-select" style="style="width: 150px;" onchange="document.adminForm.submit();"', 'manufacturer_id', 'name', $manufacturer_id);

		if ($jshopConfig->admin_show_product_labels) {
			$alllabels = JSFactory::getModel('ProductLabels', 'JShoppingModel')->getList();
			$first = array();
			$first[] = JHTML::_('select.option', '0', " - ".JText::_('COM_SMARTSHOP_LABEL')." - ", 'id','name');        
			$lists['labels'] = JHTML::_('select.genericlist', array_merge($first, $alllabels), 'label_id', 'class="form-select" style="width: 100px;" onchange="document.adminForm.submit();"','id','name', $label_id);
		}

		$f_option = array();
		$f_option[] = JHTML::_('select.option', 0, " - ".JText::_('COM_SMARTSHOP_SHOW')." - ", 'id', 'name');
		$f_option[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PUBLISH'), 'id', 'name');
		$f_option[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_UNPUBLISH'), 'id', 'name');
		$lists['publish'] = JHTML::_('select.genericlist', $f_option, 'publish', 'class="form-select" style="width: 100px;" onchange="document.adminForm.submit();"', 'id', 'name', $publish);
		
		$view = $this->getView('product_list', 'html');
        $view->setLayout("selectable");
		$view->set("canDo", $this->canDo);
		$view->set('rows', $rows);
		$view->set('lists', $lists);
		$view->set('category_id', $category_id);
		$view->set('manufacturer_id', $manufacturer_id);
		$view->set('pagination', $pagination);
		$view->set('text_search', $text_search);
        $view->set('config', $jshopConfig);        
		$view->set('eName', $eName);		
		$view->set('jsfname', $jsfname);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayProductListSelectable', array(&$view));
		$view->displaySelectable();
	}
}
?>		