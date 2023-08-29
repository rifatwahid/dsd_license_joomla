<?php
/**
* @version      3.9.1 20.08.2012
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class JshoppingControllerShippingsPrices extends JControllerLegacy{
	
	protected $canDo;

    function __construct( $config = array() ){
        parent::__construct( $config );
        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        $this->registerTask( 'conditions', 'conditions' );
        checkAccessController("shippingsprices");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    
    function display($cachable = false, $urlparams = false){		
		$_shippingsprices = JSFactory::getModel("shippingsprices");
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.shippingsprices";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "shipping_price.ordering", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
        $shipping_id_back = JFactory::getApplication()->input->getInt("shipping_id_back");
        $_shippings = JSFactory::getModel("shippings");
        $rows = $_shippings->getAllShippingPrices(0, $shipping_id_back, $filter_order, $filter_order_Dir);
        $currency = JSFactory::getTable('currency', 'jshop');
        $currency->load($jshopConfig->mainCurrency);
        
		$list=$_shippingsprices->getShippingsPriceByCountries($lang->get("name"));        
        
        $shipping_countries = array();        
        foreach($list as $smp){
            $shipping_countries[$smp->sh_pr_method_id][] = $smp->name;
        }
        unset($list);
        foreach($rows as $k=>$row){
            $rows[$k]->countries = "";
            $rows[$k]->states = "";
            $_states = $_shippingsprices->getShippingsPriceByStates($row->sh_pr_method_id, $lang->get("name"));
            if(!empty($_states)){
                $rows[$k]->states =  implode(", ",$_states);
            }
            if (is_array($shipping_countries[$row->sh_pr_method_id])){
                if (count($shipping_countries[$row->sh_pr_method_id])>10){
                    $tmp =  array_slice($shipping_countries[$row->sh_pr_method_id],0,10);
                    $rows[$k]->countries = implode(", ",$tmp)."...";
                }else{
                    $rows[$k]->countries = implode(", ",$shipping_countries[$row->sh_pr_method_id]);
                }                
            }
        }
                        
		$view = $this->getView("shippingsprices", 'html');
        $view->setLayout("list");		
		$view->set('canDo', $canDo ?? '');
		$view->set('rows', $rows);
        $view->set('currency', $currency);
        $view->set('shipping_id_back', $shipping_id_back);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayShippngsPrices', array(&$view));
		$view->displayList(); 
	}
    
    function edit(){
        $jshopConfig = JSFactory::getConfig();
        $sh_pr_method_id = JFactory::getApplication()->input->getInt('sh_pr_method_id');
        $shipping_id_back = JFactory::getApplication()->input->getInt("shipping_id_back");        
        $sh_method_price = JSFactory::getTable('shippingMethodPrice', 'jshop');
        $sh_method_price->load($sh_pr_method_id);
        $sh_method_price->prices = $sh_method_price->getPrices();
		$_lang = JSFactory::getModel('languages');
		$_conditions = JSFactory::getTable('conditions');
        $languages = $_lang->getAllLanguages(1);
		$conditions = $_conditions->getAll();
        $multilang = count($languages)>1;
		$_payments = JSFactory::getModel('payments');
        $list_payments = $_payments->getAllPaymentMethods(0);
        $ids_payments = array_keys($_payments->getListNamePaymens(0));
        $active_payments = $sh_method_price->getPayments();
        //$active_conditions = $sh_method_price->getActiveConditions($sh_pr_method_id);
        if (!count($active_payments)){
            $active_payments = array(0);
        }   
		$active_payments = (implode(',', $active_payments) == implode(',', $ids_payments)) ? '*': $active_payments;

        $first = array();
        $first[] = JHTML::_('select.option', '*', JText::_('COM_SMARTSHOP_ALL'), 'payment_id','name');
        
        $lists['payments'] = JHTML::_('select.genericlist', array_merge($first, $list_payments), 'listpayments[]', 'class="inputbox form-select" size="10" multiple = "multiple"', 'payment_id', 'name', $active_payments);
        $lists['conditions'] = JHTML::_('select.genericlist', $conditions, 'condition[]', 'class="inputbox form-select" ', 'condition_id', 'name', '');
        
       
        $nofilter = array();
        JFilterOutput::objectHTMLSafe($sh_method_price, ENT_QUOTES, $nofilter);
        $_usergroups = JSFactory::getModel("usergroups");
		$usergroups_list['*'] = JText::_('COM_SMARTSHOP_ALL');
		$usergroups_list = $usergroups_list + $_usergroups->getAllUsergroupsSelect();
		
        if ($jshopConfig->tax){        
		    $_taxes = JSFactory::getModel("taxes");
		    $all_taxes = $_taxes->getAllTaxes();
		    $list_tax = array();		
		    foreach ($all_taxes as $tax) {
			    $list_tax[] = JHTML::_('select.option', $tax->tax_id,$tax->tax_name . ' (' . $tax->tax_value . '%)','tax_id','tax_name');
		    }
            $list_tax[] = JHTML::_('select.option', -1,JText::_('COM_SMARTSHOP_PRODUCT_TAX_RATE'),'tax_id','tax_name');
            $lists['taxes'] = JHTML::_('select.genericlist', $list_tax,'shipping_tax_id','class="inputbox form-select"','tax_id','tax_name',$sh_method_price->shipping_tax_id);
            $lists['package_taxes'] = JHTML::_('select.genericlist', $list_tax,'package_tax_id','class="inputbox form-select"','tax_id','tax_name',$sh_method_price->package_tax_id);
        }
		$_shippings = JSFactory::getModel("shippings");
		$_countries = JSFactory::getModel("countries");		
		$_states = JSFactory::getModel("states");
        $actived = $sh_method_price->shipping_method_id;
        if (!$actived) $actived = $shipping_id_back;
        $states =$_states->getStatesByCountries(0, $sh_method_price->getCountries());
		$lists['shipping_methods'] = JHTML::_('select.genericlist', $_shippings->getAllShippings(0),'shipping_method_id','class = "inputbox form-select" size = "1"','shipping_id','name', $actived);
		$lists['countries'] = JHTML::_('select.genericlist', $_states->getAllCountries(0),'shipping_countries_id[]','class = "inputbox form-select" size = "10", multiple = "multiple" onChange="shopStates.getShippState(this);" ','country_id','name', $sh_method_price->getCountries());
		if(!empty($states)) {
            $lists['states'] = JHTML::_('select.genericlist', $states, 'shipping_states_id[]', 'class = "inputbox form-select" size = "10", multiple = "multiple" ', 'state_id', 'name', $sh_method_price->getStates());
        }
        if ($jshopConfig->admin_show_delivery_time) {
            $_deliveryTimes = JSFactory::getModel("deliveryTimes");
            $all_delivery_times = $_deliveryTimes->getDeliveryTimes();                
            $all_delivery_times0 = array();
            $all_delivery_times0[0] = new stdClass();
            $all_delivery_times0[0]->id = '0';
            $all_delivery_times0[0]->name = JText::_('COM_SMARTSHOP_NONE');        
            $lists['deliverytimes'] = JHTML::_('select.genericlist', array_merge($all_delivery_times0, $all_delivery_times),'delivery_times_id','class = "inputbox form-select"','id','name', $sh_method_price->delivery_times_id);
        }
        
        $currency = JSFactory::getTable('currency', 'jshop');
        $currency->load($jshopConfig->mainCurrency);        
        $extensions = JSFactory::getShippingExtList($actived);
        $sh_method_price->usergroup_id = ($sh_method_price->usergroup_id == implode(',', $_usergroups->getAllUserGroupsIdsWithGuest())) ? '*': $sh_method_price->usergroup_id;

		$view=$this->getView("shippingsprices", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
		$view->set('lists', $lists);
		$view->set('states', $states);
        $view->set('shipping_id_back', $shipping_id_back);
        $view->set('currency', $currency);
        $view->set('extensions', $extensions);
        $view->set('config', $jshopConfig);
        $view->set('etemplatevar', '');
		
		
		$view->set('usergroups_list', $usergroups_list);
		$view->set('params', $params ?? []);
		$view->set('sh_method_price', $sh_method_price);
        $view->set('languages', $languages);
        $view->set('multilang', $multilang);
        $view->set('types', $types ?? []);
        $view->set('conditions', $conditions);
        //$view->set('active_conditions', $active_conditions);
        $document = JFactory::getDocument();
        $document->addScriptOptions('urlShippStates', SEFLink('index.php?option=com_jshopping&controller=states&task=getListStatesAjax', 0, 0) );

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditShippingsPrices', array(&$view));
        $view->displayEdit();
    }
	
	function save(){        
		$shipping = JSFactory::getTable('shippingMethod', 'jshop');
    	$sh_method_id = JFactory::getApplication()->input->getInt("sh_method_id");
        $shipping_id_back = JFactory::getApplication()->input->getInt("shipping_id_back");
        
        $dispatcher = \JFactory::getApplication();
		
		$shipping_pr = JSFactory::getTable('shippingMethodPrice', 'jshop');
        $post = $this->input->post->getArray(); 
	
        $post['shipping_stand_price'] = saveAsPrice($post['shipping_stand_price']);
        $dispatcher->triggerEvent( 'onBeforeSaveShippingPrice', array(&$post) );
        
        $countries = JFactory::getApplication()->input->getVar('shipping_countries_id');
        $states = JFactory::getApplication()->input->getVar('shipping_states_id');

        if (!empty($post['usergroup_id']['0']) && $post['usergroup_id']['0'] == '*') {
            $usergroupsModel = JSFactory::getModel('usergroups');
            $post['usergroup_id'] = $usergroupsModel->getAllUserGroupsIdsWithGuest();
        }
		
		$post['usergroup_id']=implode(",",(array)$post['usergroup_id']);
        if (!isset($post['published'])) $post['published'] = 0;
        if (!$post['listpayments']) $post['listpayments'] = array();
        if (in_array('*', $post['listpayments'])){
			$_payments = JSFactory::getModel('payments');			
			$ids_payments = array_keys($_payments->getListNamePaymens(0));
			$post['listpayments'] = $ids_payments;
        }
		$post['payments'] = implode(',', $post['listpayments']);
		$shipping_pr->setPayments($post['listpayments']);
		$_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);

        foreach($languages as $lang) {
            $post['description_' . $lang->language] = $_POST['description' . $lang->id];
        }
		
		$_shippings = JSFactory::getModel('shippings');
				
		if (!$shipping_pr->bind($post)){
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=shippingsprices");
			return 0;
		}
        if (isset($post['sm_params']))
            $shipping_pr->setParams($post['sm_params']);
        else 
            $shipping_pr->setParams('');		
		
		if (!$shipping_pr->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=shippingsprices");
			return 0;
		}

        if(!$post['sh_pr_method_id']){
            $_shippingsProduct = JSFactory::getModel('productShippings');
            $_shippingsProduct->saveShippingProducts($shipping_pr->sh_pr_method_id);
        }

		$_shippings->savePrices($shipping_pr->sh_pr_method_id, $post);
		$_shippings->saveCountries($shipping_pr->sh_pr_method_id, $countries);
		$_shippings->saveStates($shipping_pr->sh_pr_method_id, $states);

        $dispatcher->triggerEvent( 'onAfterSaveShippingPrice', array(&$shipping_pr) );

		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=shippingsprices&task=edit&sh_pr_method_id=".$shipping_pr->sh_pr_method_id."&shipping_id_back=".$shipping_id_back); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=shippingsprices&shipping_id_back=".$shipping_id_back);
        }

	}

	function remove(){
		$cid = JFactory::getApplication()->input->getVar("cid");
		$_shippingsprices = JSFactory::getModel("shippingsprices");
        $shipping_id_back = JFactory::getApplication()->input->getInt("shipping_id_back");
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveShippingPrice', array(&$cid) );
		$text = '';
		foreach ($cid as $key => $value) {
			if ($_shippingsprices->deletePricesByShippingMethodId($value)) {
				$text .= JText::_('COM_SMARTSHOP_SHIPPING_DELETED');
				$_shippingsprices->deletePriceWeightByShippingMethodId($value);
				$_shippingsprices->deletePriceCountriesByShippingMethodId($value);
			} else {
				$text .= JText::_('COM_SMARTSHOP_ERROR_SHIPPING_DELETED');
			}
		}
        
        $dispatcher->triggerEvent( 'onAfterRemoveShippingPrice', array(&$cid) );
		
		$this->setRedirect("index.php?option=com_jshopping&controller=shippingsprices&shipping_id_back=".$shipping_id_back, $text);
	}
	
	public function  conditions(){
		 $this->setRedirect('index.php?option=com_jshopping&controller=conditions');
	}
	
    public function order() 
    {
		$order = JFactory::getApplication()->input->getVar('order');
		$cid = JFactory::getApplication()->input->getInt('id');
		$number = JFactory::getApplication()->input->getInt('number');
		$_shippingsprices = JSFactory::getModel('shippingsprices');	        
		$_shippingsprices->orderingChange($order,$cid,$number);
		$this->setRedirect('index.php?option=com_jshopping&controller=shippingsprices');
	}

    public function saveorder()
    {
        $cid = JFactory::getApplication()->input->getVar('cid', [], 'post', 'array' );
        $order = JFactory::getApplication()->input->getVar('order', [], 'post', 'array' );
       
        foreach($cid as $k=>$id){
            $table = JSFactory::getTable('shippingMethodPrice', 'jshop');
            $table->load($id);
            if ($table->ordering != $order[$k]) {
                $table->ordering = $order[$k];
                $table->store();
            }
        }

        $this->setRedirect('index.php?option=com_jshopping&controller=shippingsprices');
    }

    public function publish(): void
    {
        $this->publishShippingsPrices();
    }
    
    public function unpublish(): void
    {
        $this->publishShippingsPrices(false);
    }
	
    protected function publishShippingsPrices(bool $isPublish = true)
    {	
        $cid = JFactory::getApplication()->input->getVar('cid', []);   
        $shippingsPricesModel = JSFactory::getModel('Shippingsprices');	
        $dispatcher = \JFactory::getApplication();

        $dispatcher->triggerEvent('onBeforePublishShippingsPrices', [&$cid, &$isPublish]);
		$shippingsPricesModel->publishUnpublish($cid, $isPublish);		
		$dispatcher->triggerEvent('onAfterPublishShippingsPrices', [&$cid, &$isPublish]);	

		return $this->setRedirect('index.php?option=com_jshopping&controller=shippingsprices');
	}
}
?>