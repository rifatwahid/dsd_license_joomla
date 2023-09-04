<?php
/**
* @version      4.9.0 05.11.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerCategories extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );
        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        checkAccessController("categories");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("categories",$this->canDo);
    }
    
    function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();
        
        $dispatcher = \JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $_categories = JSFactory::getModel("categories");
        
        $context = "jshopping.list.admin.category";
        $limit = $mainframe->getUserStateFromRequest($context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int' );
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        $text_search = $mainframe->getUserStateFromRequest($context.'text_search', 'text_search', '');
        
        $filter = array("text_search" => $text_search);
        
        $categories = $_categories->getTreeAllCategories($filter, $filter_order, $filter_order_Dir);
        $total = count($categories);

        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
        
        $countproducts = $_categories->getAllCatCountProducts();
        $categories = array_slice($categories, $pagination->limitstart, $pagination->limit);
        $view = $this->getView("category", 'html');
        $view->setLayout("list");
		$view->set("canDo", $this->canDo);
        $view->set('categories', $categories);
        $view->set('countproducts', $countproducts);
        $view->set('pagination', $pagination);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
        $view->set('text_search', $text_search);
        $dispatcher->triggerEvent('onBeforeDisplayListCategoryView', array(&$view));
        $view->displayList();
    }
    
    function edit() {
        $jshopConfig = JSFactory::getConfig();
		//MODELS
		$_lang = \JSFactory::getModel("languages");
		$_access = JSFactory::getModel("access");
		$_categories = JSFactory::getModel("categories");
		//TEBLES
		$category = JSFactory::getTable("category","jshop");
		
        $categories_id_array = JFactory::getApplication()->input->getInt("category_id");
        $category->load($categories_id_array);        
        
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
        
        $nofilter = array();
        JFilterOutput::objectHTMLSafe( $category, ENT_QUOTES, $nofilter);

        if ($categories_id_array) {
            $parentid = $category->category_parent_id;
            $rows = $this->_getAllCategoriesLevel($category->category_parent_id, $category->ordering);
        } else {
            $category->category_publish = 1;
            $parentid = JFactory::getApplication()->input->getInt("catid");
            $rows = $this->_getAllCategoriesLevel($parentid);
        }

        $lists['templates'] = getTemplates('category', $category->category_template);
        $lists['onelevel'] = $rows;    
		
		$categories=$_categories->getTreeCategoryWithFirstSetElement(JText::_('COM_SMARTSHOP_TOP_LEVEL'),0,1,0);
        
        $lists['treecategories'] = JHTML::_('select.genericlist', $categories,'category_parent_id','class="inputbox form-select" size="1" onchange = "shopCategory.changeOrder()"','category_id','name', $parentid);
        $lists['parentid'] = $parentid;		
		$lists['access'] = $_access->getAccessGroupsSelect(0,$category->access);
        
        $view=$this->getView("category", 'html');
        $view->setLayout("edit");
		$view->set("canDo", $this->canDo);
        $view->set('category', $category);
        $view->set('lists', $lists);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditCategories', array(&$view));
        $view->displayEdit();
    }
    
    function save(){
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        require_once ($jshopConfig->path.'lib/image.lib.php');
        require_once ($jshopConfig->path.'lib/uploadfile.class.php');
        
        $dispatcher = \JFactory::getApplication();
        
        $_alias = JSFactory::getModel("alias"); 
        
        $_categories = JSFactory::getModel("categories");
        $category = JSFactory::getTable("category","jshop");
        if (!$_POST["category_id"]){
            $_POST['category_add_date'] = getJsDate();
        }
        if (!isset($_POST['category_publish'])){
            $_POST['category_publish'] = 0;
        }
        
        $post = JFactory::getApplication()->input->post->getArray();
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        if ($post['category_parent_id']==$post['category_id']) $post['category_parent_id'] = 0;
        
        $dispatcher->triggerEvent('onBeforeSaveCategory', array(&$post));

        foreach($languages as $lang){
            $post['name_'.$lang->language] = trim($post['name_'.$lang->language]);
            
            if (empty($post['alias_'.$lang->language])) {
                $post['alias_'.$lang->language] = $post['name_'.$lang->language];
            }
            
            $post['alias_'.$lang->language] = \JApplicationHelper::stringURLSafe($post['alias_'.$lang->language]);
            if ($post['alias_'.$lang->language]!="" && !$_alias->checkExistAlias1Group($post['alias_'.$lang->language], $lang->language, $post['category_id'], 0)){
                $post['alias_'.$lang->language] = strtolower($_alias->randomStringGenerator(10));
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_ALIAS_ALREADY_EXIST'),'error');
            }

            $post['description_'.$lang->language] = $_POST['description'.$lang->id];
			$post['short_description_'.$lang->language] = $_POST['short_description_'.$lang->language];
        }
        
        if (!$category->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=categories");
            return 0;
        }
        $edit = $category->category_id;
        $category->category_image = $post['category_image'];

        $this->_reorderCategory($category);
         
        if (!$category->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=categories");
            return 0;
        }
        
        $dispatcher->triggerEvent( 'onAfterSaveCategory', array(&$category) );
        
        $success = ($edit)?(JText::_('COM_SMARTSHOP_CATEGORY_SUCC_UPDATE')):(JText::_('COM_SMARTSHOP_CATEGORY_SUCC_ADDED'));
        
        if ($this->getTask()=='apply'){
            $this->setRedirect('index.php?option=com_jshopping&controller=categories&task=edit&category_id='.$category->category_id, $success);
        }else{
            $this->setRedirect('index.php?option=com_jshopping&controller=categories', $success);
        }
    }
    
    function order(){
        $id = JFactory::getApplication()->input->getInt("id");
        $move = JFactory::getApplication()->input->getInt("move");
        $table = JSFactory::getTable('category', 'jshop');
        $table->load($id);
        $table->move($move, 'category_parent_id="'.$table->category_parent_id.'"');
        $this->setRedirect("index.php?option=com_jshopping&controller=categories");
    }
    
    function saveorder(){
        $categories_id_array = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar( 'order', array(), 'post', 'array' );
        $category_parent_id = JFactory::getApplication()->input->getInt("category_parent_id");
        foreach ($categories_id_array as $k=>$id){
            $table = JSFactory::getTable('category', 'jshop');
            $table->load($id);
			//print_R($order);die;
			if ($table->ordering!=$order[$k]){
                $table->ordering = $order[$k];
				
                $table->store();
            }        
        }
        
        //$table = JSFactory::getTable('category', 'jshop');
       // $table->ordering = null;
        //$table->reorder('category_parent_id="'.$category_parent_id.'"');
                
        $this->setRedirect("index.php?option=com_jshopping&controller=categories");
    }

    function _getAllCategoriesLevel($parentId, $currentOrdering = 0){
        $jshopConfig = JSFactory::getConfig();
        $_categories = JSFactory::getModel("categories");
        $rows = $_categories->getSubCategories($parentId, "ordering");
        $first[] = JHTML::_('select.option', '0',JText::_('COM_SMARTSHOP_ORDERING_FIRST'),'ordering','name');
        $rows = array_merge($first,$rows);
        $currentOrdering = (!$currentOrdering) ? ($rows[count($rows) - 1]->ordering) : ($currentOrdering);
        return (JHTML::_('select.genericlist', $rows,'ordering','class="inputbox form-select" size="1"','ordering','name',$currentOrdering));
    }
    
    function _reorderCategory(&$category) {
        $_categories = JSFactory::getModel('categories');
		$_categories->orderingUp($category->category_parent_id,$category->ordering);		
        $category->ordering++;
    }
    
    function publish(){
        $this->publishCategory(1);
        $this->setRedirect('index.php?option=com_jshopping&controller=categories');
    }
    
    function unpublish(){
        $this->publishCategory(0);
        $this->setRedirect('index.php?option=com_jshopping&controller=categories');
    }
    
    function publishCategory($flag) {      
        $_categories = JSFactory::getModel("categories");
        $dispatcher = \JFactory::getApplication();
        $categories_id_array = JFactory::getApplication()->input->getVar("cid");
        $dispatcher->triggerEvent( 'onBeforePublishCategory', array(&$categories_id_array, &$flag) );
        foreach ($categories_id_array as $key => $value) {
			$_categories->setPublishFlag($flag,$value);            
        }

        $dispatcher->triggerEvent( 'onAfterPublishCategory', array(&$categories_id_array, &$flag) );
    }
    
    function remove(){
        $jshopConfig = JSFactory::getConfig();        
        $dispatcher = \JFactory::getApplication();
        $text = array();
        $categories_id_array = JFactory::getApplication()->input->getVar("cid");
        $_categories = JSFactory::getModel("categories");
        
        $dispatcher->triggerEvent('onBeforeRemoveCategory', array(&$categories_id_array));
        $allCatCountProducts = $_categories->getAllCatCountProducts();
        
        foreach($categories_id_array as $key=>$value){
            $category = JSFactory::getTable("category", "jshop");
            $category->load($value);
            $name_category = $category->getName();
            $childs = $category->getChildCategories();
            if ($allCatCountProducts[$value] || count($childs)){
                \JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_SMARTSHOP_CATEGORY_NO_DELETED', $name_category),'error');
                continue;
            }
			if ($value!=1){
				$_categories->deleteCategory($value);
				@unlink($jshopConfig->image_category_path.'/'.$category->category_image);
				$text[]= JText::sprintf('COM_SMARTSHOP_CATEGORY_DELETED', $name_category);
			}
        }
        
        $dispatcher->triggerEvent( 'onAfterRemoveCategory', array(&$categories_id_array) );
        
        $this->setRedirect('index.php?option=com_jshopping&controller=categories', implode('</li><li>',$text));
    }
    
    function sorting_cats_html(){
        $catid = JFactory::getApplication()->input->getVar('catid');
        print $this->_getAllCategoriesLevel($catid);
    die();    
    }
    
    function delete_foto(){
        //$modelOfCategories = JSFactory::getModel('categories');
        $catid = JFactory::getApplication()->input->getInt("catid");
        //$jshopConfig = JSFactory::getConfig();
        $category = JSFactory::getTable("category", "jshop");
        $category->load($catid);

        // if ($modelOfCategories->getTotalCountOfSameCategoryImage($category->category_image) <= 1) {
        //     @unlink($jshopConfig->image_category_path.'/'.$category->category_image);
        // }
        
        $category->category_image = "";
        $category->store();
        die();
    }
    
   function copy(){
        $jshopConfig = JSFactory::getConfig();		
        $text = array();
        $categories_id_array = JFactory::getApplication()->input->getVar('cid');
              
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeCopyProduct', array(&$categories_id_array) );
        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $_categories = JSFactory::getModel("categories");
             
        foreach ($categories_id_array as $key=>$value){
            $category = JSFactory::getTable('category', 'jshop');
            $category->load($value);
            $category->category_id = null;         
            $category->category_add_date = getJsDate();
           
            $category->store();
            $ordering = $_categories->getMaxOrderingByParentId($category->category_parent_id);
			$_categories->updateOrderingByCategoryId($category->category_id,$ordering);            
            $list = $_categories->getProductsToCategoriesByCategoryId($value);
        
            foreach($list as $val){                
				$_categories->addProductToCategory($val->product_id,$category->category_id,$val->product_ordering);                
            }
            
            $text[] = JText::sprintf('COM_SMARTSHOP_CATEGORY_COPY_TO', $value, $category->category_id)."<br>";
        }
        
        $dispatcher->triggerEvent('onAfterCopyCategory', array(&$categories_id_array));
        
        $this->setRedirect("index.php?option=com_jshopping&controller=categories", implode("</li><li>",$text));
    }
    
    
    
    function editlist(){
		$jshopConfig = JSFactory::getConfig();   
		//MODELS
        $_categories = JSFactory::getModel("categories");
		$_lang = \JSFactory::getModel("languages");
        $_access = JSFactory::getModel("access");
		$_publish = JSFactory::getModel('publish');		
		
        $categories_id_array = JFactory::getApplication()->input->getVar('cid');
        if (count($categories_id_array)==1){
            $this->setRedirect("index.php?option=com_jshopping&controller=categories&task=edit&category_id=".$categories_id_array[0]);
            return 0;
        }             
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onLoadEditListCategory', array());
        
		$lists['access'] = $_access->getAccessGroupsSelect(1);		        		
		$lists['category_publish'] = $_publish->getPublishSelectWithFirstFreeElement('category_publish');            
        $lists['treecategories'] = $_categories->getCategoriesTreeWithParentSelectedSelect();		
		$lists['categories'] = $_categories->getCategoriesTreeWithFirstFreeSelect();		
        $lists['templates'] = getTemplates('category', "", 1);
			
        $view=$this->getView("category", 'html');
        $view->setLayout("editlist");
		$view->set("canDo", $this->canDo);
        $view->set('lists', $lists);
        $view->set('cid', $categories_id_array);
        $view->set('config', $jshopConfig);        
        $view->set('etemplatevar', '');
        $dispatcher->triggerEvent('onBeforeDisplayEditListCategoryView', array(&$view) );
        $view->editGroup();
        
    }
    
    function savegroup(){
		$jshopConfig = JSFactory::getConfig();

		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforSaveListCategory', array() );
        
        $categories_id_array = JFactory::getApplication()->input->getVar('cid');
        $post = JFactory::getApplication()->input->post->getArray();
        
        foreach($categories_id_array as $id){
            $category = JSFactory::getTable('category', 'jshop');
            $category->load($id);
            if ($post['access']!=-1){
                $category->set('access', $post['access']);
            }
            if ($post['category_publish']!=-1){
                $category->set('category_publish', $post['category_publish']);
            }
            if (isset($post['category_template']) && $post['category_template'] != -1){
                $category->set('category_template', $post['category_template']);
            }
            if (isset($post['category_parent_id']) && $post['category_parent_id'] != -1){
                $category->set('category_parent_id', $post['category_parent_id']);
            }
            if (isset($post['products_page']) ){
                $category->set('products_page', $post['products_page']);
            }
            
            $category->store();
            unset($category);
        }

        $dispatcher->triggerEvent('onAfterSaveListCategoryEnd', array(&$categories_id_array, &$post) );
        $this->setRedirect("index.php?option=com_jshopping&controller=categories", JText::_('COM_SMARTSHOP_CATEGORY_SAVED'));
    }
}
?>