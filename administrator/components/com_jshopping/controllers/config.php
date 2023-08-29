<?php
/**
* @version      4.9.0 31.01.2015
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerConfig extends JControllerLegacy{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct($config);
        $this->registerTask('apply', 'save');
		$this->registerTask( 'add',   'template_creator_new' );        
        checkAccessController("config");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '' );
        addSubmenu("config",$this->canDo);  
	
   }

    function display($cachable = false, $urlparams = false){
		$_search = JSFactory::getModel('search');    
		$text_search=JFactory::getApplication()->input->getVar('text_search');
		$rows=$_search->getSearchResults();		
		$rows=$_search->scanLinks($text_search,$rows);				
		$_search->getResultInCurrentLanguage($rows);
				
        $jshopConfig = JSFactory::getConfig();        
        $current_currency = JSFactory::getTable('currency', 'jshop');
        $current_currency->load($jshopConfig->mainCurrency);
        if ($current_currency->currency_value!=1){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_MAIN_CURRENCY_VALUE'),'error');    
        }
        $view=$this->getView("panel", 'html');
        $view->setLayout("config"); 
		$view->set("canDo", $this->canDo);
		$view->set("rows", $rows);
		$view->set("text_search", $text_search);
        $view->displayConfig();
    }
    
    function general(){
		$_config = JSFactory::getModel('config');  
		$this->addJsToConfigPages();
		
		$lists=$_config->getGeneralConfigLists();
		$other_config=$_config->getGeneralConfigOtherConfig();	    
    	
		$view=$this->getView("config", 'html');
        $view->setLayout("general");
		$view->set("canDo", $this->canDo);
        $view->set('etemplatevar', '');
		$view->set("lists", $lists);
        $view->set("other_config", $other_config);
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigGeneral', array(&$view));
        $view->display();
    }
    
    function catprod(){
		$_config = JSFactory::getModel('config');    
		$lists=$_config->getProductConfigLists(); 
		$this->addJsToConfigPages();
            
        $view=$this->getView("config", 'html');
        $view->setLayout("categoryproduct");
		$view->set("canDo", $this->canDo);
        $view->set("lists", $lists);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigCatProd', array(&$view));
        $view->display();
    }
    
    function checkout(){
		$_config = JSFactory::getModel('config');    
		$lists=$_config->getProductCheckoutLists(); 
		$this->addJsToConfigPages();

        $view=$this->getView("config", 'html');
        $view->setLayout("checkout");
		$view->set("canDo", $this->canDo);
        $view->set("lists", $lists);         
        $view->set('etemplatevar', '');        

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigCheckout', array(&$view));
        $view->display();
    }

    function fieldregister(){
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root() . 'administrator/components/com_jshopping/js/src/scripts/SortableJS/Sortable.js');
        $_config_fields = JSFactory::getModel('config_fields');
        $jshopConfig = JSFactory::getConfig(); 
		$this->addJsToConfigPages();
        $view = $this->getView("config", 'html');
        $view->setLayout("fieldregister");
		$view->set("canDo", $this->canDo);
        $config = new stdClass();
        include($jshopConfig->path.'lib/default_config.php');

        $current_fields = $_config_fields->getAllFields();
      
        $view->set("fields", $fields_client);
        $view->set("current_fields", $current_fields);
        $view->set("fields_sys", $fields_client_sys);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigFieldRegister', array(&$view));
        $view->displayFields();
    }

    function adminfunction(){        
        $_config = JSFactory::getModel('config'); 
		$this->addJsToConfigPages();    
		$lists=$_config->getShopFunctionsLists();
		$jshopConfig=JSFactory::getConfig();
		
        $view=$this->getView("config", 'html');
        $view->setLayout("adminfunction");
		$view->set("canDo", $this->canDo);
        $view->set("jshopConfig", $jshopConfig);
		$view->set("lists", $lists);
        $view->set('etemplatevar', '');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigAdminFunction', array(&$view));
        $view->display();
    }
    
    function image(){
		$jshopConfig = JSFactory::getConfig(); 
		$this->addJsToConfigPages();
        $config = new stdClass();
		include($jshopConfig->path.'lib/default_config.php');
		$_config_media = JSFactory::getModel('config_media');    
		$select_resize_type=$_config_media->getSelect_SelectResizeType();
    	
    	$view=$this->getView("config", 'html');
        $view->setLayout("image");
		$view->set("canDo", $this->canDo);
        $view->set("select_resize_type", $select_resize_type);
		$view->set("other_config_checkbox", $other_config_checkbox);
		$view->set("other_config_select", $other_config_select);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigImage', array(&$view));
        $view->display();
    }
    
    function storeinfo(){
    	$jshopConfig = JSFactory::getConfig(); 
		$this->addJsToConfigPages();
        $vendor = JSFactory::getTable('vendor', 'jshop');
        $vendor->loadMain();
    	$_countries = JSFactory::getModel("countries");
		$countries = $_countries->getAllCountries(0);	
	    $first = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_SELECT'), 'country_id', 'name' );
		array_unshift($countries, $first);
		$lists['countries'] = JHTML::_('select.genericlist', $countries, 'country', 'class="inputbox form-select"', 'country_id', 'name', $vendor->country);
        
        $nofilter = array();
        JFilterOutput::objectHTMLSafe($vendor, ENT_QUOTES, $nofilter);
        
    	$view=$this->getView("config", 'html');
        $view->setLayout("storeinfo");
		$view->set("canDo", $this->canDo);
        $view->set("lists", $lists); 
		$view->set("vendor", $vendor);
        $view->set('etemplatevar', '');
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditConfigStoreInfo', array(&$view));
        $view->display();
    }
    
    function save(){
        $post = JFactory::getApplication()->input->post->getArray();
        $tab = JFactory::getApplication()->input->getVar('tab');
        $allowedStatusesForCancellation = JFactory::getApplication()->input->getVar('allowed_status_for_cancellation', []);

        $orderStatusModel = JSFactory::getModel('OrderStatus');
		$_config = JSFactory::getModel('config');   

        if ($tab == 12) {
            $statusesIds = [
                'is_generate_invoice' => $post['is_generate_invoice'] ?: [],
                'is_send_invoice_to_customer' => $post['is_send_invoice_to_customer'] ?: [],
                'is_send_invoice_to_admin' => $post['is_send_invoice_to_admin'] ?: [],
                'is_generate_delivery_note' => $post['is_generate_delivery_note'] ?: [],
                'is_send_delivery_note_to_customer' => $post['is_send_delivery_note_to_customer'] ?: [],
                'is_send_delivery_note_to_admin' => $post['is_send_delivery_note_to_admin'] ?: [],
                'is_generate_refund_note' => $post['is_generate_refund_note'] ?: [],
                'is_send_refund_note_to_customer' => $post['is_send_refund_note_to_customer'] ?: [],
                'is_send_refund_note_to_admin' => $post['is_send_refund_note_to_admin'] ?: []
            ];
    
            $orderStatusModel->switchAllDefinedColumnsTo(array_keys($statusesIds), 0);
            $orderStatusModel->switchAllDefinedColumnsByIdTo($statusesIds, 1);
        }

        if ($tab == 11) {
            $orderStatusModel->switchToDisableAllCancellationStatuses();
            $orderStatusModel->switchToEnabledCancellationStatuses($allowedStatusesForCancellation);
        }

        $_config->saveConfigurations();

        if ($this->getTask()=='apply'){
            switch ($tab){
                case 1: $task = "general"; break;
                case 2: $task = "currency"; break;
                case 3: $task = "image"; break;
                case 5: $task = "storeinfo"; break;
                case 6: $task = "catprod"; break;
                case 7: $task = "checkout"; break;
                case 8: $task = "adminfunction"; break;
                case 9: $task = "fieldregister"; break;
                case 10: $task = "otherconfig"; break;
                case 11: $task = "orders"; break;
                case 12: $task = "pdf"; break;
				case 13: $task = "storage"; break;
            }
            $this->setRedirect('index.php?option=com_jshopping&controller=config&task='.$task, JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
        }else{
		    $this->setRedirect('index.php?option=com_jshopping&controller=config', JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
        }
    }
    
    function preview_pdf(){        
        $dispatcher = \JFactory::getApplication(); 
		$this->addJsToConfigPages();
		$jshopConfig = JSFactory::getConfig();
        $jshopConfig->currency_code = "USD";
        $file_generete_pdf_order = $jshopConfig->file_generete_pdf_order;		
		
		$_config = JSFactory::getModel('config');    
		$order=$_config->getOrderForPreviewPdf();		
       
        $dispatcher->triggerEvent('onBeforeCreateDemoPreviewPdf', array(&$order, &$file_generete_pdf_order));
        require_once($file_generete_pdf_order);
		$order->pdf_file = generatePdf($order, $jshopConfig);
		
		header("Location: ".$jshopConfig->pdf_orders_live_path."/".$order->pdf_file);
		die();
	}
    
    public function content()
    {
		$this->addJsToConfigPages();
		$_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages();
        $_content = JSFactory::getModel('content');				
		$pagebuilder_enabled = $_content->ifPageBuilderEnabled();
		
		$class = '';
		if(!$pagebuilder_enabled){
			$class = 'hidden';
		}
		$rows = $_content->getList();   				
        $view=$this->getView('config', 'html');
        $view->setLayout('listcontent');     
		$view->set('canDo', $this->canDo);		
		$view->set('languages', $languages);         
        $view->set('rows', $rows);         		
        $view->set('pbclass', $class);        
        $view->displayListContent();    
    }

    public function contentApplyAndRedirect()
    {
        $this->contentSave();
        return $this->setRedirect('index.php?option=com_jshopping&controller=config&task=content', JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
    }

    public function contentSaveAndClose()
    {
        $this->contentSave();
		return $this->setRedirect('index.php?option=com_jshopping&controller=config', JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
    }

    public function contentSave()
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $modelOfContent = JSFactory::getModel('content');		
        $languages = JSFactory::getModel('languages')->getAllLanguages();
        return $modelOfContent->storeList($post, $languages);
    }
	
	public function get_content()
    {
		$_content = JSFactory::getModel("content");
		$post = $this->input->post->getArray();
		$per_page = 10;
        [$page, $lang, $type] = explode(',', $post['filterOpts']);

		if ($type == 2) {		
			$content_count = $_content->getPBCount($lang);   
			$contents = $_content->getPBList($page, $per_page, $lang);  
		} else {	
			$content_count = $_content->getContentCount($lang);   
			$contents = $_content->getContentList($page, $per_page, $lang);
		}  
        $pages = ceil($content_count / $per_page);

		$view = $this->getView('config', 'html');
		$view->setLayout('listjoomlacontent');  
		$view->set('canDo', $this->canDo);
		$view->set('contents', $contents);               
		$view->set('pages', $pages); 		
		$view->set('current_page', $page);         
		$view->display();    
		exit;
	}
	
	function email_hub($cachable = false, $urlparams = false){
        $jshopConfig = JSFactory::getConfig();    
		$this->addJsToConfigPages();             
        $view=$this->getView("email_hub", 'html');
        $view->setLayout("email_hub");	
		$view->set("canDo", $this->canDo);
        $view->displayEmailHub();
    }
	
	function template_creator($cachable = false, $urlparams = false){
        $jshopConfig = JSFactory::getConfig();      
		$this->addJsToConfigPages();           
        $view=$this->getView("email_hub", 'html');
        $view->setLayout("template_creator");
		$view->set("canDo", $this->canDo);
		$view->set("data",$data);		
        $view->displayTemplateCreator();
    }
	
	function template_creator_new($cachable = false, $urlparams = false){
        $jshopConfig = JSFactory::getConfig();                
        $view=$this->getView("email_hub", 'html');
        $view->setLayout("template_creator_new");
		$view->set("canDo", $this->canDo);
		$view->set("data",$data);		
        $view->displayTemplateCreatorNew();
    }

    public function orders()
    {		
        $orderStatusModel = JSFactory::getModel('OrderStatus');
        $configModel = JSFactory::getModel('config');  
        $orderModel = JSFactory::getModel('orders');  
        $dispatcher = \JFactory::getApplication();
        $allOrdersStatus = $orderStatusModel->getListOrderStatus();
		$jshopConfig=JSFactory::getConfig();
		$next_invoice_number = $jshopConfig->next_invoice_number ?: $orderModel->getCountAllOrders([]);
		$next_refund_number = $jshopConfig->next_refund_number ?: $orderModel->getCountAllOrders([]);

        $namesOfOrderStatus = [];
        $idsOfOrAllowedCancellationOderStatus = [];

        if (!empty($allOrdersStatus)) {
            foreach ($allOrdersStatus as $status) {
                $namesOfOrderStatus[$status->status_id] = $status->name;

                if (!empty($status->is_allowed_status_for_cancellation)) {
                    $idsOfOrAllowedCancellationOderStatus[$status->status_id] = $status->status_id;
                }
            }
        }

		$this->addJsToConfigPages();
		$orderConfigLists = $configModel->getOrdersConfigLists();        
    	$view = $this->getView('config', 'html');
        $view->setLayout('orders');
		$view->set('canDo', $this->canDo);
        $view->set('lists', $orderConfigLists); 		
        $view->set('namesOfOrderStatus', $namesOfOrderStatus); 	
        $view->set('idsOfOrAllowedCancellationOderStatus', $idsOfOrAllowedCancellationOderStatus);
        $view->set('next_invoice_number', $next_invoice_number);
        $view->set('next_refund_number', $next_refund_number);
        
        $dispatcher->triggerEvent('onBeforeEditConfigOrdersShow', [&$view]);

        $view->display();
    }

    public function pdf()
    {
        $modelOfOrderStatus = JSFactory::getModel('Orderstatus');
        $allOrders = $modelOfOrderStatus->getListOrderStatus();

        $invoicesAndDeliveryNoteData = array_reduce($allOrders, function ($carry, $item) {
            $columnsNames = [
                'is_generate_invoice' => 'generateInvoice',
                'is_send_invoice_to_customer' => 'sendInvoiceToCustomer',
                'is_send_invoice_to_admin' => 'sendInvoiceToAdmin',
                'is_generate_delivery_note' => 'generateDeliveryNote',
                'is_send_delivery_note_to_customer' => 'sendDeliveryNoteToCustomer',
                'is_send_delivery_note_to_admin' => 'sendDeliveryNoteToAdmin',
                'is_generate_refund_note' => 'generateRefundNote',
                'is_send_refund_note_to_customer' => 'sendRefundNoteToCustomer',
                'is_send_refund_note_to_admin' => 'sendRefundNoteToAdmin'
            ];

            foreach($item as $columnName => $value) {
                if (array_key_exists($columnName, $columnsNames)) {
                    $keyName = $columnsNames[$columnName];
                    $carry[$keyName] = $carry[$keyName] ?? [];

                    if (!empty($value)) {
                        $carry[$keyName][$item->status_id] = $item->name;
                    }
                }
            }

            $carry['allStatuses'][$item->status_id] = $item->name;

            return $carry;
        });

		$_config_pdf = JSFactory::getModel("config_pdf"); 
		$this->addJsToConfigPages();
		$extconf = $_config_pdf->getHeaderFooterImagesNamesArray();
		$_config_pdf->checkNeedRemoveImages();
		$header_img = $_config_pdf->getHeaderImage();
		$footer_img = $_config_pdf->getFooterImage();        
		$view=$this->getView("config", 'html');
        $view->setLayout("pdf");
		$view->set("canDo", $this->canDo);
		$view->set("header_img",$header_img);
        $view->set("footer_img",$footer_img);
        $view->set('invoicesAndDeliveryNoteData', $invoicesAndDeliveryNoteData);
        $view->display();
    }

    public function storage(){		
		$_config = JSFactory::getModel('config');   
		$this->addJsToConfigPages();  
		$lists=$_config->getStorageConfigLists();
        
    	$view=$this->getView("config", 'html');
        $view->setLayout("storage");
		$view->set("canDo", $this->canDo);
        $view->set("lists", $lists); 
        $view->display();
    }	

    public function addJsToConfigPages(){			
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration('
            document.addEventListener("DOMContentLoaded", function () {
                let subMenuEl = document.querySelector("#sidebar #submenu .active .jssubmenu");

                if (!subMenuEl) {
                    let jSubMenu = document.querySelector(".jssubmenu");

                    if (jSubMenu) {
                        let activeSubMenuEl = document.querySelector("#sidebar #submenu .active");

                        if (activeSubMenuEl) {
                            activeSubMenuEl.insertAdjacentHTML("beforeend", jSubMenu.innerHTML);
                            jSubMenu.classList.add("config");
                        }
                    }
                }
            });
        ');
    }

    public function edit_fields(){
        $_config_fields = JSFactory::getTable('config_fields');
        $field_id = JFactory::getApplication()->input->getInt('field_id');

        $_config_fields->load($field_id);
        $view=$this->getView("config_fields", 'html');
        $view->setLayout("editfields");
        $view->set("row", $_config_fields);
        $view->set("canDo", $this->canDo);
        $view->set("field_id",$field_id);
        $view->displayEdit();
    }

    
   	
}