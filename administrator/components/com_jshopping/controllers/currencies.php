<?php
/**
* @version      4.1.0 03.11.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerCurrencies extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("currencies");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
        
    function display($cachable = false, $urlparams = false) {        
        $jshopConfig = JSFactory::getConfig();
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.currencies";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "currency_ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');

        $current_currency = JSFactory::getTable('currency', 'jshop');
        $current_currency->load($jshopConfig->mainCurrency);
        if ($current_currency->currency_value!=1){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_MAIN_CURRENCY_VALUE'),'error');    
        }
        
        $_currencies = JSFactory::getModel("currencies");
        $rows = $_currencies->getAllCurrencies(0, $filter_order, $filter_order_Dir);
        
        $view=$this->getView("currencies", 'html');
        $view->setLayout("list");        
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);        
        $view->set('config', $jshopConfig);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayCourencies', array(&$view));
        $view->displayList();
    }
    
    function edit() {                
        $currency = JSFactory::getTable('currency', 'jshop');
        $_currencies = JSFactory::getModel("currencies");
        $currency_id = JFactory::getApplication()->input->getInt('currency_id');
        $currency->load($currency_id);
        if ($currency->currency_value==0) $currency->currency_value = 1;
        $first[] = JHTML::_('select.option', '0',JText::_('COM_SMARTSHOP_ORDERING_FIRST'),'currency_ordering','currency_name');
        $rows = array_merge($first, $_currencies->getAllCurrencies() );
        $lists['order_currencies'] = JHTML::_('select.genericlist', $rows,'currency_ordering','class="inputbox form-select" size="1"','currency_ordering','currency_name',$currency->currency_ordering);
        $edit = ($currency_id)?($edit = 1):($edit = 0);
        JFilterOutput::objectHTMLSafe( $currency, ENT_QUOTES);
        
        $view=$this->getView("currencies", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('currency', $currency);        
        $view->set('lists', $lists);        
        $view->set('edit', $edit);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditCurrencies', array(&$view));        
        $view->displayEdit();
    }

    public function currency_options()
    {
        $dispatcher = \JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $_config = JSFactory::getModel('config'); 
        $lists = $_config->getCurrencyConfigLists();
        
        $view = $this->getView('currencies', 'html');
        $view->setLayout('currency_options');
        $view->set('canDo', $this->canDo);
        $view->set('lists', $lists);
        $view->set('jshopConfig', $jshopConfig);

        $dispatcher->triggerEvent('onBeforeEditConfigCurrency', [&$view]);
        $view->displayCurrencyOptions();
    }

    public function saveCurrencyOptions()
    {
        $configModel = JSFactory::getModel('config');
        $configModel->saveConfigurations();

        $this->setRedirect('index.php?option=com_jshopping&controller=currencies', JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
    }

    function save() {
        
        $dispatcher = \JFactory::getApplication();        
        $currency_id = JFactory::getApplication()->input->getInt("currency_id");
        $apply = JFactory::getApplication()->input->getVar("apply");
        $currency = JSFactory::getTable('currency', 'jshop');
        $post = $this->input->post->getArray();
        $post['currency_value'] = saveAsPrice($post['currency_value']);
        $dispatcher->triggerEvent( 'onBeforeSaveCurrencie', array(&$post) );
        if (!$currency->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=currencies");
            return 0;
        }
        if ($currency->currency_value==0) $currency->currency_value = 1;

        $this->_reorderCurrency($currency);
        if (!$currency->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=currencies");
            return 0;
        }
                
        $dispatcher->triggerEvent( 'onAfterSaveCurrencie', array(&$currency) );
        
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=currencies&task=edit&currency_id=".$currency->currency_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=currencies");
        }
                
    }

    function _reorderCurrency(&$currency) {
		$_currencies = JSFactory::getModel('currencies');	        
		$_currencies->moveUpCurrenciesOrdering($currency->currency_ordering);        
        $currency->currency_ordering++;
    }

    function order() {
		$order = JFactory::getApplication()->input->getVar("order");
        $cid = JFactory::getApplication()->input->getInt("id");
        $number = JFactory::getApplication()->input->getInt("number");
		$_currencies = JSFactory::getModel('currencies');	        
		$_currencies->orderingChange($order,$cid,$number);        
        $this->setRedirect("index.php?option=com_jshopping&controller=currencies");
    }

    function remove() {
        $cid = JFactory::getApplication()->input->getVar("cid");        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveCurrencie', array(&$cid) );
        $_currencies = JSFactory::getModel('currencies');
		$text=$_currencies->deleteCurrencies($cid);     
        $dispatcher->triggerEvent( 'onAfterRemoveCurrencie', array(&$cid) );        
        $this->setRedirect("index.php?option=com_jshopping&controller=currencies", $text); 
    }
    
    function publish(){
        $this->publishCurrency(1);
    }
    
    function unpublish(){
        $this->publishCurrency(0);
    }

    function publishCurrency($flag) {
        $cid = JFactory::getApplication()->input->getVar("cid");
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforePublishCurrencie', array(&$cid, &$flag) );
		$_currencies = JSFactory::getModel('currencies');
        $_currencies->publishCurrencies($cid,$flag);       
        $dispatcher->triggerEvent( 'onAfterPublishCurrencie', array(&$cid, &$flag) );        
        $this->setRedirect("index.php?option=com_jshopping&controller=currencies");
    }
    
    function setdefault(){
        $jshopConfig = JSFactory::getConfig();
        $cid = JFactory::getApplication()->input->getVar("cid");
        $db = \JFactory::getDBO();
        if ($cid[0]){
            $config = new jshopConfig($db);
            $config->id = $jshopConfig->load_id;
            $config->mainCurrency = $cid[0];
            $config->store();
        }
        $this->setRedirect("index.php?option=com_jshopping&controller=currencies");
    }
        
    
}

?>		