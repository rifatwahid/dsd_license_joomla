<?php
/**
* @version      4.1.0 20.09.2012
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.controller');

require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/admin_exclude_attribute_for_attribute.php';
require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/admin_exclude_buttons_for_attribute.php';

class JshoppingControllerAttributesValues extends JControllerLegacy{
	
	protected $canDo;

    public function __construct( $config = array() )
    {
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("attributesvalues");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

    public function display($cachable = false, $urlparams = false)
    {
		$attr_id = JFactory::getApplication()->input->getInt("attr_id");		
        $jshopConfig = JSFactory::getConfig();
        
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.attr_values";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "value_ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
		$_attributValues = JSFactory::getModel("AttributValue");
		$rows = $_attributValues->getAllValues($attr_id, $filter_order, $filter_order_Dir);
		$_attribut = JSFactory::getModel("attribut");

		$attr_name = $_attribut->getName($attr_id);
		$view=$this->getView("attributesvalues", 'html');
        $view->setLayout("list");
		$view->set("canDo", $this->canDo ?? '');
        $view->set('rows', $rows);        
        $view->set('attr_id', $attr_id);
        $view->set('config', $jshopConfig);
        $view->set('attr_name', $attr_name);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayAttributesValues', array(&$view));
		$view->displayList(); 
	}
	
	public function edit() 
    {
		$value_id = JFactory::getApplication()->input->getInt("value_id");
		$attr_id = JFactory::getApplication()->input->getInt("attr_id");
        
		$jshopConfig = JSFactory::getConfig();
		
        $attributValue = JSFactory::getTable('attributValue', 'jshop');
        $attributValue->load($value_id);
        
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;	
        
        JFilterOutput::objectHTMLSafe($attributValue, ENT_QUOTES);
		
		$view=$this->getView("attributesvalues", 'html');
        $view->setLayout("edit");		
		$view->set("canDo", $this->canDo ?? '');
        $view->set('attributValue', $attributValue);        
        $view->set('attr_id', $attr_id);        
        $view->set('config', $jshopConfig);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        AdminExcludeAttributeForAttribute::getInstance()->onBeforeEditAtributesValues($view);        
		AdminExcludeButtonsForAttribute::getInstance()->onBeforeEditAtributesValues($view);
        $dispatcher->triggerEvent('onBeforeEditAtributesValues', array(&$view));
		$view->displayEdit();
	}
    
	public function save() 
    {
        $jshopConfig = JSFactory::getConfig();
        require_once ($jshopConfig->path.'lib/uploadfile.class.php');
        $_attributvalue = JSFactory::getModel('attributvalue');
		
        $dispatcher = \JFactory::getApplication();
                
		$value_id = JFactory::getApplication()->input->getInt("value_id");
		$attr_id = JFactory::getApplication()->input->getInt("attr_id");
        
        $post = $this->input->post->getArray();
        $attributValue = JSFactory::getTable('attributValue', 'jshop');
        
        AdminExcludeAttributeForAttribute::getInstance()->onBeforeSaveAttributValue($post);
        $dispatcher->triggerEvent( 'onBeforeSaveAttributValue', array(&$post) );
		AdminExcludeButtonsForAttribute::getInstance()->onBeforeSaveAttributValue($post);
        $dispatcher->triggerEvent( 'onBeforeSaveAttributValue', array(&$post) );        
        
        if (!$value_id){
            $post['value_ordering'] = $_attributvalue->getNextOrdering($attr_id);
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
                
        $dispatcher->triggerEvent( 'onAfterSaveAttributValue', array(&$attributValue) );
                
		if ($this->getTask()=='apply'){ 
            $this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&task=edit&attr_id=".$attr_id."&value_id=".$attributValue->value_id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&attr_id=".$attr_id);
        }
	}
	
	public function remove()
    {
        $isAllDeletedSuccess = true;
		$attrsValIdsToDel = $this->input->get('cid');
		$attrId = $this->input->get('attr_id');
        $jshopConfig = JSFactory::getConfig();		
		$modelOfAttrValues = JSFactory::getModel('attributvalue');
        $dispatcher = \JFactory::getApplication();
        $successText = JText::_('COM_SMARTSHOP_ATTRIBUT_VALUE_DELETED');
        $modelOfProductAttrs2 = JSFactory::getModel('ProductAttrs2');
        $modelOfProductAttrs = JSFactory::getModel('ProductAttrs');

        $dispatcher->triggerEvent('onBeforeRemoveAttributValue', [&$attrsValIdsToDel]);

        $successDeletedAttrsValuesIds = [];
		foreach ($attrsValIdsToDel as $value) {			
            $image = $modelOfAttrValues->getImage($value);
            if (!$modelOfAttrValues->isExistsAttrValueWithSameImageByValueId($value)) {
                @unlink("{$jshopConfig->image_attributes_path}/{$image}");
            }
            $isDeleted = $modelOfAttrValues->deleteAttrValue($value);

            if ($isDeleted) {
                $successDeletedAttrsValuesIds[] = $value;
            } else {
                $isAllDeletedSuccess = false;
                $successText = '';
            }
        }

        $modelOfProductAttrs2->deleteByAttrsValuesIds($successDeletedAttrsValuesIds);
        $modelOfProductAttrs->deleteAttrsWithProductByAttrsIdsAndValuesIds($attrId, $attrsValIdsToDel);

        if (!$isAllDeletedSuccess) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ATTRIBUT_VALUE_FAILED_DELETED'),'error');
        }
                
        $dispatcher->triggerEvent('onAfterRemoveAttributValue', [&$attrsValIdsToDel]);
		
		$this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&attr_id={$attrId}", $successText);
	}
	
	public function order()
    {
		$_attributvalue = JSFactory::getModel('attributvalue');
		$order = JFactory::getApplication()->input->getVar("order");
		$cid = JFactory::getApplication()->input->getInt("id");
		$number = JFactory::getApplication()->input->getInt("number");
		$attr_id = JFactory::getApplication()->input->getInt("attr_id");		
		$_attributvalue->orderingChange($order,$cid,$number);
		$this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&attr_id=".$attr_id, $text);
	}
    
    public function saveorder()
    {
        $cid = JFactory::getApplication()->input->getVar('cid', array(), 'post', 'array');
        $order = JFactory::getApplication()->input->getVar('order', array(), 'post', 'array');
        $attr_id = JFactory::getApplication()->input->getInt("attr_id");

        foreach($cid as $k=>$id){
            $table = JSFactory::getTable('attributValue', 'jshop');
            $table->load($id);
            if ($table->value_ordering!=$order[$k]){
                $table->value_ordering = $order[$k];
                $table->store();
            }
        }

        $this->setRedirect("index.php?option=com_jshopping&controller=attributesvalues&attr_id=".$attr_id);
    }
    
    public function back()
    {
        $this->setRedirect("index.php?option=com_jshopping&controller=attributes");
    }
    
    public function delete_foto()
    {
        //$jshopConfig = JSFactory::getConfig();
        
        $id = JFactory::getApplication()->input->getInt("id");
        //$modelOfAttrValue = JSFactory::getModel('attributvalue');
        $attributValue = JSFactory::getTable('attributValue', 'jshop');
        $attributValue->load($id);

        // if (!$modelOfAttrValue->isExistsAttrValueWithSameImageByValueId($id)) {
        //     @unlink($jshopConfig->image_attributes_path."/".$attributValue->image);
        // }
        $attributValue->image = "";
        $attributValue->store();
        die();               
    }

    public function copy()
    {
        $idsOfValuesAttrsForCopy = JFactory::getApplication()->input->getVar('cid');
        $attrId = JFactory::getApplication()->input->getVar('attr_id');

        if ( !empty($idsOfValuesAttrsForCopy) && !empty($attrId) ) {
            $_attributvalue = JSFactory::getModel('AttributValue');

            foreach($idsOfValuesAttrsForCopy as $key => $valueIdForCopy) {
                $_attributvalue->copyAttrValue($attrId, $valueIdForCopy);
            }
        }

        $this->setRedirect('index.php?option=com_jshopping&controller=attributesvalues&attr_id=' . $attrId);
    }        
}