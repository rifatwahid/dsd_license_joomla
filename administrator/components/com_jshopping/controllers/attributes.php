<?php
/**
* @version      4.9.0 24.07.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerAttributes extends JControllerLegacy{
	
	protected $canDo;
    
    public function __construct( $config = array() ) {
        parent::__construct( $config );
        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("attributes");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

    public function display($cachable = false, $urlparams = false) {
        $mainframe = JFactory::getApplication();
		//MODELS
		$_attributes = JSFactory::getModel("attribut");
		
        $context = "jshoping.list.admin.attributes";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "A.attr_ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');            	
		
		$rows = $_attributes->getAllAttributesWithValues(0, null, $filter_order, $filter_order_Dir);
        
		$view = $this->getView("attributes", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayAttributes', array(&$view));
        $view->displayList();
    }

    public function edit() 
    {
        $jshopConfig = JSFactory::getConfig();        
		//MODELS
		$_lang = \JSFactory::getModel("languages");
		$_attributes = JSFactory::getModel("attribut");				
		$_categories = JSFactory::getModel("categories");	
		$_dependent_attributes = JSFactory::getModel("dependent_attributes");
		$_attributesgroups = JSFactory::getModel("attributesgroups");
		
		$languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
		
        $attr_id = JFactory::getApplication()->input->getInt("attr_id");	
        $attribut = JSFactory::getTable('attribut', 'jshop');
        $attribut->load($attr_id);

        if (!$attribut->independent) $attribut->independent = 0;    		
		if (!isset($attribut->allcats)) $attribut->allcats = 1;        
		
		$type_attribut=$_attributes->getAttributesTypesSelect($attribut->attr_type);				
		//$dependent_attribut=$_dependent_attributes->getDependentAttributesSelect($attribut->independent);		        
		//$lists['allcats']=$_attributes->allcatsSelect($attribut->allcats);        
        $categories_selected = $attribut->getCategorys();
		$lists['categories']=$_categories->getCategoriesTreeSelect("",$categories_selected);
		$lists['group']=$_attributesgroups->getAtributesGroupsWithFirstFreeSelect($attribut->group);
        
        JFilterOutput::objectHTMLSafe($attribut, ENT_QUOTES);
	    
        $view=$this->getView("attributes", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('attribut', $attribut);
        $view->set('type_attribut', $type_attribut);
        //$view->set('dependent_attribut', $dependent_attribut);
        $view->set('independentInputCheckedNumber', $attribut->independent);
        $view->set('allcatsInputCheckedNumber', $attribut->allcats);
        $view->set('etemplatevar', '');    
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('lists', $lists);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditAtribut', array(&$view, &$attribut));
        $view->displayEdit();		
    }
	
	public function save()
    {   
		//MODELS 
        $_attribut = JSFactory::getModel('attribut');
		$_attributvalue = JSFactory::getModel('attributvalue');
		$_lang = \JSFactory::getModel("languages");
		
		$attr_id = JFactory::getApplication()->input->getInt('attr_id');
        
        $dispatcher = \JFactory::getApplication();
        
        $attribut = JSFactory::getTable('attribut', 'jshop');    
        $post = $this->input->post->getArray();

        if (!empty($attr_id)) {
            $jsConfig = JSFactory::getConfig();

            if (isset($post['attr_type']) && ($post['attr_type'] == $jsConfig->attrs_types_code['hidden'])) {
                JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');

                if ($jsConfig->attrs_dep_indep_code['independ'] == $post['independent']) {
                    JSFactory::getModel('ProductAttrs2Front')->deleteForEachProdButNotFirstsAttrs($attr_id);
                } elseif ($jsConfig->attrs_dep_indep_code['depend'] == $post['independent']) {
                    JSFactory::getModel('ProductAttrsFront')->deleteForEachProdButNotFirstsAttrs($attr_id);
                }
            }
        }

		if($post['attr_type'] == 4) $post['independent'] = 1;
        
        $dispatcher->triggerEvent( 'onBeforeSaveAttribut', array(&$post) );
        
        if (!$attr_id){			            
            $post['attr_ordering'] = $_attribut->getNextOrdering();
        }
        
		$languages = $_lang->getAllLanguages();	
		foreach($languages as $lang){           
            $post['description_'.$lang->language] = $_POST['description_'.$lang->language];
        }
        if (!$attribut->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=attributes");
            return 0;
        }
        
        if (isset($post['category_id'])) 
            $categorys = $post['category_id'];
        else
            $categorys = '';
        
        if (!is_array($categorys)) $categorys = array();
        
        $attribut->setCategorys($categorys);
        
	
        if (!$attribut->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=attributes");
            return 0;
        }
        if (!$attr_id){
            $attr_id = $_attribut->addAttr($attribut->attr_id);
        }
		if ($attribut->attr_type==4){						
			$rows = $_attribut->getAttr($attr_id);
			if (count($rows)<1){		
				$post['attr_id']=$attr_id;
				$attributValue = JSFactory::getTable('attributValue', 'jshop');
				$post['value_ordering'] = $_attributvalue->getNextOrdering($attr_id);
						
				foreach ($languages as $key=>$value){
					$post['name_'.$value->lang_code]='not checked';		
				}
					
				if (!$attributValue->bind($post)) {
					\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
					$this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&attr_id=".$attr_id);
					return 0;
				}
						
				if (!$attributValue->store()) {
					\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
					$this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&attr_id=".$attr_id);
					return 0;
				}
				$attributValue = JSFactory::getTable('attributValue', 'jshop');
				$post['value_ordering']++;							
				foreach ($languages as $key=>$value){
					$post['name_'.$value->lang_code]='checked';		
				}
				
				if (!$attributValue->bind($post)) {
					\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
					$this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&attr_id=".$attr_id);
					return 0;
				}
						
				if (!$attributValue->store()) {
					\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
					$this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&attr_id=".$attr_id);
					return 0;
				}
			}
		}
               
        $dispatcher->triggerEvent( 'onAfterSaveAttribut', array(&$attribut) );
        
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=attributes&task=edit&attr_id=".$attr_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=attributes");
        }
        
	}
	
	public function remove() 
    {	
        //MODELS
		$_attribut = JSFactory::getModel('attribut');		
		$attrs_ids = JFactory::getApplication()->input->getVar("cid");        
       
		$dispatcher = \JFactory::getApplication();        
        $dispatcher->triggerEvent( 'onBeforeRemoveAttribut', array(&$attrs_ids) );     
        
        $text = $_attribut->removeAttrs($attrs_ids);
        
        $dispatcher->triggerEvent( 'onAfterRemoveAttribut', array(&$attrs_ids) );
        
		$this->setRedirect("index.php?option=com_jshopping&controller=attributes", $text);
	}
	
	public function order() 
    {
		//MODELS
		$_attribut = JSFactory::getModel('attribut');
		
		$order = JFactory::getApplication()->input->getVar("order");
		$cid = JFactory::getApplication()->input->getInt("id");
		$number = JFactory::getApplication()->input->getInt("number");
		$_attribut->orderingChange($order,$cid,$number);			
		$this->setRedirect("index.php?option=com_jshopping&controller=attributes");
	}
    
    public function saveorder()
    {
        $cid = JFactory::getApplication()->input->getVar('cid', array(), 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar('order', array(), 'post', 'array' );        
        
        foreach($cid as $k=>$id){
            $table = JSFactory::getTable('attribut', 'jshop');
            $table->load($id);
            if ($table->attr_ordering!=$order[$k]){
                $table->attr_ordering = $order[$k];
                $table->store();
            }
        }
                
        $this->setRedirect("index.php?option=com_jshopping&controller=attributes");
    }
    
    public function addgroup()
    {
        $this->setRedirect("index.php?option=com_jshopping&controller=attributesgroups");
    }

    public function copy()
    {
		//MODELS
		$_attribut = JSFactory::getModel('attribut');
		
        $arrWithAttrIdsForCopy = JFactory::getApplication()->input->getVar('cid');

        if ( !empty($arrWithAttrIdsForCopy) ) {
            $application = JFactory::getApplication();
            

            $_attribut->copyAttributes($arrWithAttrIdsForCopy);
            $application->redirect($_SERVER['REQUEST_URI']);
        }
    }    

}