<?php
/**
* @version      2.9.4 25.11.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerTaxes extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("taxes");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }

    public function custom_options($cachable = false, $urlparams = false) 
    {
        $jshopConfig = JSFactory::getConfig();
        include $jshopConfig->path . 'lib/default_config.php';
        $countriesModel = JSFactory::getModel('countries');	
        
        $tax_rule_for = [
            JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_FIRMA_CLIENT'), 'id', 'name' ),
            JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_VAT_NUMBER'), 'id', 'name' )
        ];

        $euCountries = $jshopConfig->eu_countries_to_show_b2b_msg ? explode(',', $jshopConfig->eu_countries_to_show_b2b_msg): [];

        $lists = [
            'tax_rule_for' => JHTML::_('select.genericlist', $tax_rule_for, 'ext_tax_rule_for','class = "inputbox" size = "1"','id','name', $jshopConfig->ext_tax_rule_for),
            'countries' => JHTML::_('select.genericlist', $countriesModel->getAllCountries(0),'eu_countries_to_show_b2b_msg[]','class = "inputbox" size = "10", multiple = "multiple"', 'country_id','name', $euCountries),
            'applies_to' => JHTML::_('select.genericlist', $config->b2b_applies_to_options,'eu_countries_selected_applies_to','class = "inputbox"', 'id','name', [$jshopConfig->eu_countries_selected_applies_to], false, true)
        ];
		
		$view = $this->getView('taxes', 'html');
        $view->setLayout('configurations');
		$view->set('canDo', $canDo ?? '');
        $view->set('other_config', $other_config);
        $view->set('other_config_checkbox', $other_config_checkbox);
        $view->set('other_config_select', $other_config_select);
        $view->set('config', $jshopConfig);
        $view->set('etemplatevar', '');
		$view->set('lists', $lists);
        
        $view->displayConfigurations();
	} 

	function configurations_apply($cachable = false, $urlparams = false) {
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$post = $this->input->post->getArray();
	
	
		$array = array('tax_on_delivery_address','display_tax_id_in_pdf','hide_tax','calcule_tax_after_discount','show_tax_in_product','show_tax_product_in_cart');	
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
		$post['other_config'] = serialize($result);
        $post['eu_countries_to_show_b2b_msg'] = $post['eu_countries_to_show_b2b_msg'] ? implode(',', $post['eu_countries_to_show_b2b_msg']): '';
		
		$result = array();
		$config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		if ($jshopConfig->other_config!=''){
			$result = unserialize($jshopConfig->other_config);
		}

		$result['tax_on_delivery_address'] = $post['tax_on_delivery_address'];
		$result['display_tax_id_in_pdf'] = $post['display_tax_id_in_pdf'];
		$post['other_config'] = serialize($result);		
		
		
		
		$config = new jshopConfig($db);
		$config->id = $jshopConfig->load_id;
		
		if (!$config->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=taxes');
			return 0;
		}		
		if (!$config->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE')." ".$config->_error,'error');
			$this->setRedirect('index.php?option=com_jshopping&controller=taxes');
			return 0;
		} 
		$this->setRedirect('index.php?option=com_jshopping&controller=taxes',JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
	}
	
    function display($cachable = false, $urlparams = false) {        
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.taxes";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "tax_name", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
        $_taxes = JSFactory::getModel("taxes");
        $rows = $_taxes->getAllTaxes($filter_order, $filter_order_Dir);
        
        $view = $this->getView("taxes", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir); 
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayTaxes', array(&$view));
        $view->displayList();
    }
    
    function edit() {
        $tax_id = JFactory::getApplication()->input->getInt("tax_id");        
        $tax = JSFactory::getTable('tax', 'jshop');
        $tax->load($tax_id);
        $edit = ($tax_id)?($edit = 1):($edit = 0);
                
        $view=$this->getView("taxes", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        JFilterOutput::objectHTMLSafe( $tax, ENT_QUOTES);
        $view->set('tax', $tax); 
        $view->set('edit', $edit);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditTaxes', array(&$view)); 
        $view->displayEdit();
    }

    function save(){    
        $tax_id = JFactory::getApplication()->input->getInt("tax_id");
        $tax = JSFactory::getTable('tax', 'jshop');
        $post = $this->input->post->getArray(); 
        $post['tax_value'] = saveAsPrice($post['tax_value']);
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveTax', array(&$tax) );

        
        if (!$tax->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=taxes");
            return 0;
        }

        if (!$tax->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=taxes");
            return 0; 
        }
        
        $dispatcher->triggerEvent( 'onAfterSaveTax', array(&$tax) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=taxes&task=edit&tax_id=".$tax->tax_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=taxes");
        }
                        
    }

    function remove() {
		$_taxes = JSFactory::getModel("taxes");
        $cid = JFactory::getApplication()->input->getVar("cid");        
        $text = '';        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveTax', array(&$cid) );

        foreach ($cid as $key => $value) {
            $tax = JSFactory::getTable('tax', 'jshop');
            $tax->load($value);			            
            if($_taxes->getProductsCountByTaxId($value)) {
                $text .= JText::sprintf('COM_SMARTSHOP_TAX_NO_DELETED', $tax->tax_name)."<br>";
                continue;
            }
            if ($_taxes->deleteTaxById($value)){
                $text .= JText::sprintf('COM_SMARTSHOP_TAX_DELETED',$tax->tax_name)."<br>";
            }
			$_taxes->deleteTaxExtById($value);            
        }
        
        $dispatcher->triggerEvent( 'onAfterRemoveTax', array(&$cid) );
        
        $this->setRedirect("index.php?option=com_jshopping&controller=taxes", $text);
    }
    
}
?>