<?php
/**
* @version      4.9.0 03.12.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerProductFields extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("productfields");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    function custom_options($cachable = false, $urlparams = false) {
		$jshopConfig = JSFactory::getConfig();
        
        $displayprice = array();
        $displayprice[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_YES'), 'id', 'value');
        $displayprice[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_NO'), 'id', 'value');
        $displayprice[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_ONLY_REGISTER_USER'), 'id', 'value');
        $lists['displayprice'] = JHTML::_('select.genericlist', $displayprice, 'displayprice','class="form-select"','id','value', $jshopConfig->displayprice ?? '');
        
        $catsort = array();
        $catsort[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_SORT_MANUAL'), 'id','value');
        $catsort[] = JHTML::_('select.option', 2, JText::_('COM_SMARTSHOP_SORT_ALPH'), 'id','value');
        $lists['category_sorting'] = JHTML::_('select.genericlist', $catsort, 'category_sorting','class="form-select"','id','value', $jshopConfig->category_sorting);
        $lists['manufacturer_sorting'] = JHTML::_('select.genericlist', $catsort, 'manufacturer_sorting','class="form-select"','id','value', $jshopConfig->manufacturer_sorting);
        
        $sortd = array();
        $sortd[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_A_Z'), 'id','value');
        $sortd[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_Z_A'), 'id','value');
        $lists['product_sorting_direction'] = JHTML::_('select.genericlist', $sortd, 'product_sorting_direction','class="form-select"','id','value', $jshopConfig->product_sorting_direction);
        
        $opt = array();
        $opt[] = JHTML::_('select.option', 'V.value_ordering', JText::_('COM_SMARTSHOP_SORT_MANUAL'), 'id','value');
        $opt[] = JHTML::_('select.option', 'value_name', JText::_('COM_SMARTSHOP_SORT_ALPH'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.price', JText::_('COM_SMARTSHOP_SORT_PRICE'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.ean', JText::_('COM_SMARTSHOP_EAN_PRODUCT'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.count', JText::_('COM_SMARTSHOP_QUANTITY_PRODUCT'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.product_attr_id', JText::_('COM_SMARTSHOP_SPECIFIED_IN_PRODUCT'), 'id','value');
        $lists['attribut_dep_sorting_in_product'] = JHTML::_('select.genericlist', $opt, 'attribut_dep_sorting_in_product','class="form-select"','id','value', $jshopConfig->attribut_dep_sorting_in_product);
        
        $opt = array();
        $opt[] = JHTML::_('select.option', 'V.value_ordering', JText::_('COM_SMARTSHOP_SORT_MANUAL'), 'id','value');
        $opt[] = JHTML::_('select.option', 'value_name', JText::_('COM_SMARTSHOP_SORT_ALPH'), 'id','value');
        $opt[] = JHTML::_('select.option', 'addprice', JText::_('COM_SMARTSHOP_SORT_PRICE'), 'id','value');
        $opt[] = JHTML::_('select.option', 'PA.id', JText::_('COM_SMARTSHOP_SPECIFIED_IN_PRODUCT'), 'id','value');
        $lists['attribut_nodep_sorting_in_product'] = JHTML::_('select.genericlist', $opt, 'attribut_nodep_sorting_in_product','class="form-select"','id','value', $jshopConfig->attribut_nodep_sorting_in_product);        
        
        $select = array();        
        foreach ($jshopConfig->sorting_products_name_select as $key => $value) {
            $select[] = JHTML::_('select.option', $key, JText::_($value), 'id', 'value');            
        }
        $lists['product_sorting'] = JHTML::_('select.genericlist',$select, "product_sorting", 'class="form-select"', 'id','value', $jshopConfig->product_sorting);
        
        if ($jshopConfig->admin_show_product_extra_field){
            $_productfields = JSFactory::getModel("productFields");
            $rows = $_productfields->getList();
            $lists['product_list_display_extra_fields'] = JHTML::_('select.genericlist', $rows, "product_list_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getProductListDisplayExtraFields() );
            $lists['filter_display_extra_fields'] = JHTML::_('select.genericlist', $rows, "filter_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getFilterDisplayExtraFields() );
            $lists['product_hide_extra_fields'] = JHTML::_('select.genericlist', $rows, "product_hide_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getProductHideExtraFields() );
            $lists['cart_display_extra_fields'] = JHTML::_('select.genericlist', $rows, "cart_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getCartDisplayExtraFields() );
            $lists['pdf_display_extra_fields'] = JHTML::_('select.genericlist', $rows, "pdf_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getPdfDisplayExtraFields() );
            $lists['hide_extra_fields_images'] = JHTML::_('select.genericlist', $_productfields->getPlacesOfHideCharactImages(), "hide_extra_fields_images[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getHideExtraFieldsImages() );
            $lists['mail_display_extra_fields'] = JHTML::_('select.genericlist', $rows, "mail_display_extra_fields[]", 'class="form-select" size="10" multiple = "multiple" ', 'id','name', $jshopConfig->getMailDisplayExtraFields() );
        }
        
        $_units = JSFactory::getModel("units");
        $list_units = $_units->getUnits();
        $lists['units'] = JHTML::_('select.genericlist',$list_units, "main_unit_weight", 'class="form-select"', 'id','name', $jshopConfig->main_unit_weight);        
            
        $view=$this->getView("product_fields", 'html');
        $view->setLayout("configurations");
		$view->set('canDo', $canDo ?? '');
		$view->set("config", $jshopConfig);
        $view->set("lists", $lists);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigCatProd', array(&$view));
        $view->displayConfigurations();
	}   
	function configurations_apply($cachable = false, $urlparams = false) {
		
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$post = $this->input->post->getArray();
	
			$config = new jshopConfig($db);
		    $config->id = $jshopConfig->load_id;
		    if (!$config->bind($post)) {
			    \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			    $this->setRedirect('index.php?option=com_jshopping&controller=productfields');
			    return 0;
		    }
            
           
                if (!isset($post['product_list_display_extra_fields'])) $post['product_list_display_extra_fields'] = array();
                if (!isset($post['filter_display_extra_fields'])) $post['filter_display_extra_fields'] = array();
                if (!isset($post['product_hide_extra_fields'])) $post['product_hide_extra_fields'] = array();
                if (!isset($post['cart_display_extra_fields'])) $post['cart_display_extra_fields'] = array();
                if (!isset($post['pdf_display_extra_fields'])) $post['pdf_display_extra_fields'] = array();
                if (!isset($post['hide_extra_fields_images'])) $post['hide_extra_fields_images'] = array();
                if (!isset($post['mail_display_extra_fields'])) $post['mail_display_extra_fields'] = array();
                $config->setProductListDisplayExtraFields($post['product_list_display_extra_fields']);
                $config->setFilterDisplayExtraFields($post['filter_display_extra_fields']);
                $config->setProductHideExtraFields($post['product_hide_extra_fields']);
                $config->setCartDisplayExtraFields($post['cart_display_extra_fields']);
                $config->setPdfDisplayExtraFields($post['pdf_display_extra_fields']);
                $config->setHideExtraFieldsImages($post['hide_extra_fields_images']);
                $config->setMailDisplayExtraFields($post['mail_display_extra_fields']);
           
		    
		    $config->transformPdfParameters();				
        	    
		    if (!$config->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')." ".$config->_error,'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=productfields');
			return 0;
		}
		$this->setRedirect('index.php?option=com_jshopping&controller=productfields',JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
	}
    function display($cachable = false, $urlparams = false){        
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.productfields";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "F.ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        $group = $mainframe->getUserStateFromRequest($context.'group', 'group', 0, 'int');
        $text_search = $mainframe->getUserStateFromRequest($context.'text_search', 'text_search', '');
        
        $filter = array("group"=>$group, "text_search"=>$text_search);
		
        $_categories = JSFactory::getModel("categories");
        $listCats = $_categories->getAllList(1);
        
        $_productfields = JSFactory::getModel("productFields");
		$rows = $_productfields->getList(0, $filter_order, $filter_order_Dir, $filter);
        foreach($rows as $k=>$v){
            if ($v->allcats){
                $rows[$k]->printcat = JText::_('COM_SMARTSHOP_ALL');
            }else{
                $catsnames = array();
                $_cats = unserialize($v->cats);
                foreach($_cats as $cat_id){
                    $catsnames[] = $listCats[$cat_id];
                    $rows[$k]->printcat = implode(", ", $catsnames);
                }
            }
        }
        
        $_productfieldvalues = JSFactory::getModel("productFieldValues");
        $vals = $_productfieldvalues->getAllList(2);
    
        foreach($rows as $k=>$v){
            if (isset($vals[$v->id])){
                if (is_array($vals[$v->id])){
                    $rows[$k]->count_option = count($vals[$v->id]);
                }else{
                    $rows[$k]->count_option = 0;
                }
            }else{
                $rows[$k]->count_option = 0;
            }    
        }
		$lists = array();
        $_productfieldgroups = JSFactory::getModel("productFieldGroups");
        $groups = $_productfieldgroups->getList();
        $groups0 = array();
        $groups0[] = JHTML::_('select.option', 0, "- ".JText::_('COM_SMARTSHOP_GROUP')." -", 'id', 'name');        
        $lists['group'] = JHTML::_('select.genericlist', array_merge($groups0, $groups),'group','class="form-select" onchange="document.adminForm.submit();"','id','name', $group);
        
        $types = array(JText::_('COM_SMARTSHOP_LIST'), JText::_('COM_SMARTSHOP_TEXT'));

        $view = $this->getView("product_fields", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
		$view->set('lists', $lists);
        $view->set('rows', $rows);
        $view->set('vals', $vals);
        $view->set('types', $types);
		$view->set('text_search', $text_search);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayProductField', array(&$view));
        $view->displayList();
    }
    
    function edit(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $productfield = JSFactory::getTable('productField', 'jshop');
        $productfield->load($id);
        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
        
        $all = array();
        $all[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_ALL'), 'id','value');
        $all[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_SELECTED'), 'id','value');
        if (!isset($productfield->allcats)) $productfield->allcats = 1;
        $lists['allcats'] = JHTML::_('select.radiolist', $all, 'allcats','onclick="shopCategory.toggle()"','id','value', $productfield->allcats);
        
        $categories_selected = $productfield->getCategorys();
        $categories = buildTreeCategory(0,1,0);
        $lists['categories'] = JHTML::_('select.genericlist', $categories,'category_id[]','class="inputbox form-select" size="10" multiple = "multiple"','category_id','name', $categories_selected);
        
        $type = array();
        $type[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_LIST'), 'id', 'value');
        $type[] = JHTML::_('select.option', -1, JText::_('COM_SMARTSHOP_MULTI_LIST'), 'id', 'value');
        $type[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_TEXT'), 'id', 'value');
        if (!isset($productfield->type)) $productfield->type = 0;
        if ($productfield->multilist) $productfield->type = -1;
        $lists['type'] = JHTML::_('select.radiolist', $type, 'type','','id','value', $productfield->type);
        
        $_productfieldgroups = JSFactory::getModel("productFieldGroups");
        $groups = $_productfieldgroups->getList();
        $groups0 = array();
        $groups0[] = JHTML::_('select.option', 0, "- - -", 'id', 'name');        
        $lists['group'] = JHTML::_('select.genericlist', array_merge($groups0, $groups),'group','class="inputbox form-select"','id','name', $productfield->group);
                                                    
        $view = $this->getView("product_fields", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        JFilterOutput::objectHTMLSafe($productfield, ENT_QUOTES);
        $view->set('row', $productfield);
        $view->set('lists', $lists);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditProductFields', array(&$view));
        $view->displayEdit();
    }

    function save(){                
        $id = JFactory::getApplication()->input->getInt("id");
        $productfield = JSFactory::getTable('productField', 'jshop');        
        $post = $this->input->post->getArray();
        if ($post['type']==-1){
            $post['type'] = 0;
            $post['multilist'] = 1;
        }else{
            $post['multilist'] = 0;
        }

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveProductField', array(&$post) );
		
		$_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);

        foreach($languages as $lang) {
            $post['description_' . $lang->language] = $_POST['description_' . $lang->language];
        }           
		if (!$productfield->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=productfields");
            return 0;
        }
        
        $categorys = $post['category_id'];
        if (!is_array($categorys)) $categorys = array();
        
        $productfield->setCategorys($categorys);
        
        if (!$id){
            $productfield->ordering = null;
            $productfield->ordering = $productfield->getNextOrder();            
        }

        if (!$productfield->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=productfields");
            return 0; 
        }
        
        if (!$id){
			$_productfields = JSFactory::getModel('productfields');
			$_productfields->addProductField($productfield->id);            
        }
        
        $dispatcher->triggerEvent( 'onAfterSaveProductField', array(&$productfield) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=productfields&task=edit&id=".$productfield->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=productfields");
        }
                        
    }

    public function remove()
    {
        $cid = JFactory::getApplication()->input->getVar('cid');       
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveProductField', [&$cid]);
		$modelOfProductfield = JSFactory::getModel('productfields');
		$text = $modelOfProductfield->deleteProductfields($cid);
        $dispatcher->triggerEvent( 'onAfterRemoveProductField', [&$cid]);   

        $this->setRedirect("index.php?option=com_jshopping&controller=productfields", implode('</li><li>', $text));
    }
    
    function order(){        
        $id = JFactory::getApplication()->input->getInt("id");
        $move = JFactory::getApplication()->input->getInt("move");        
        $productfield = JSFactory::getTable('productField', 'jshop');
        $productfield->load($id);
        $productfield->move($move);
        $this->setRedirect("index.php?option=com_jshopping&controller=productfields");
    }
    
    function saveorder(){
        $cid = JFactory::getApplication()->input->getVar( 'cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar( 'order', array(), 'post', 'array' );
        
        foreach ($cid as $k=>$id){
            $table = JSFactory::getTable('productField', 'jshop');
            $table->load($id);
            if ($table->ordering!=$order[$k]){
                $table->ordering = $order[$k];
                $table->store();
            }        
        }
        
        $table = JSFactory::getTable('productField', 'jshop');
        $table->ordering = null;
        $table->reorder();        
                
        $this->setRedirect("index.php?option=com_jshopping&controller=productfields");
    }
    
    function addgroup(){
        $this->setRedirect("index.php?option=com_jshopping&controller=productfieldgroups");
    }
    
}
?>		