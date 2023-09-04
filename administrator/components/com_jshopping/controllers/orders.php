<?php
/**
* @version      4.8.0 22.10.2014
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerOrders extends JControllerLegacy{
	
	protected $canDo;    

    function __construct( $config = array() ){
        parent::__construct( $config );
        $this->registerTask('add', 'edit' );
        checkAccessController("orders");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');        
        addSubmenu("orders",$this->canDo);
        JPluginHelper::importPlugin('jshoppingorder');
    }

    function display($cachable = false, $urlparams = false){
        $jshopConfig = JSFactory::getConfig();
		$_delivery = JSFactory::getModel("delivery");
		$_shippings = JSFactory::getModel("shippings");	
		$_orders = JSFactory::getModel("orders");
		$_orderstatus = JSFactory::getModel("orderstatus");
		$_filters = JSFactory::getModel("filters");	
		$_payments = JSFactory::getModel("payments");			
		$dispatcher = \JFactory::getApplication();
        
        $mainframe = JFactory::getApplication();        
        $context = "jshopping.list.admin.orders";
        $limit = $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
        $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );        
        $client_id = JFactory::getApplication()->input->getInt('client_id',0);
        $coupon_id = JFactory::getApplication()->input->getInt('coupon_id',0);
        
        $status_id = $mainframe->getUserStateFromRequest( $context.'status_id', 'status_id', 0 );
        $year = $mainframe->getUserStateFromRequest( $context.'year', 'year', 0 );
        $month = $mainframe->getUserStateFromRequest( $context.'month', 'month', 0 );
        $day = $mainframe->getUserStateFromRequest( $context.'day', 'day', 0 );
        $notfinished = $mainframe->getUserStateFromRequest( $context.'notfinished', 'notfinished', 0 );
        $text_search = $mainframe->getUserStateFromRequest( $context.'text_search', 'text_search', '' );
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "order_number", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "desc", 'cmd');
        
        $filter = [
            'status_id' => $status_id, 
            'user_id' => $client_id, 
            'coupon_id' => $coupon_id, 
            'year' => $year, 
            'month' => $month, 
            'day' => $day, 
            'text_search' => $text_search, 
            'notfinished' => $notfinished
        ];    
        $dispatcher->triggerEvent('onBeforeDisplayListOrderAdminAfterFilterSet', array(&$filter));

        $total = $_orders->getCountAllOrders($filter);        
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);
        $rows = $_orders->getAllOrders($pageNav->limitstart, $pageNav->limit, $filter, $filter_order, $filter_order_Dir);
		
		$list_order_status = $_orderstatus->getListOrderStatusNames();
		$list_status_order = $_orderstatus->getListOrderStatus();
		
        $lists = [
            'status_orders' => $_orders->getAllOrderStatus(),
            'changestatus' => $_orders->getSelect_changestatus($status_id),
            'notfinished' => $_filters->getFilter_notfinished(),
            'year' => $_filters->getFilter_year($year),
            'month' => $_filters->getFilter_month($month),
            'day' => $_filters->getFilter_day($day)
        ];
		
        $payments_list = $_payments->getListNamePaymens(0);          
		$shippings_list = $_shippings->getListNameShippings(0);
		$total = $_orders->buildOrdersList($rows, $payments_list);
		$_shippings->setShippingNames($rows);
		$_delivery->setDeliveryNotes($rows);
        
        $dispatcher->triggerEvent('onBeforeDisplayListOrderAdmin', array(&$rows));
		
		$view=$this->getView("orders", 'html');
        $view->setLayout("list");
		$view->set("canDo", $this->canDo);
        $view->set('rows', $rows); 
        $view->set('lists', $lists); 
        $view->set('pageNav', $pageNav); 
        $view->set('text_search', $text_search); 
        $view->set('filter', $filter);                
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
        $view->set('list_order_status', $list_order_status);
        $view->set('list_status_order', $list_status_order);
        $view->set('client_id', $client_id);
        $view->set('total', $total);
        $view->_tmp_order_list_html_end = '';
        $dispatcher->triggerEvent('onBeforeShowOrderListView', array(&$view));
		$view->displayList(); 
    }

    function loadtaxorder(){
        $post = $this->input->post->getArray();
        $data_order = (array)$post['data_order'];
        $products = (array)$data_order['product'];

        $_orders = JSFactory::getModel("orders");
        $taxes_array = $_orders->loadtaxorder($data_order, $products);
        print json_encode($taxes_array);
        die;
    }
    
    function show(){
        $order_id = JFactory::getApplication()->input->getInt("order_id");        
        $jshopConfig = JSFactory::getConfig();
        $_orders = JSFactory::getModel("orders");		
		$_deliverytimes = JSFactory::getModel("deliverytimes");
		$_shippings = JSFactory::getModel("shippings");     
		
		$order = $_orders->getOrder($order_id);		
        $order_items = $order->getAllItems();		
		$order_packages=JSFactory::getModel("orderpackages")->loadPackages($order_id);
		$return_packages=JSFactory::getModel("returnpackages")->loadPackages($order_id);
        
		$order->order_tax_list = $order->getTaxExt();
        $order->weight = $order->getWeightItems();
        $order_history = $order->getHistory();		
		$_deliverytimes->getOrderDeliveryTime($order);
		$_deliverytimes->getOrderDeliveryDate($order);		
		$stat_download = $order->getFilesStatDownloads(1);        
        $order->shipping_info = $_shippings->setOrderShipping($order);		
		
		
		//ADDITIONAL PARRAMS
		$tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields["address"];
        $count_filed_delivery = $jshopConfig->getEnableDeliveryFiledRegistration('address');
		$lists['status'] = JHTML::_('select.genericlist', $_orders->getAllOrderStatus(),'order_status','class = "inputbox form-select" size = "1" id = "order_status"','status_id','name', $order->order_status);        
		$first = array(0 => JText::_('COM_SMARTSHOP_ORDEREDIT_NO_REASON'));
		$_returnStatusList = array_merge($first, $_orders->getAllReturnStatus());
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeDisplayOrderAdmin', array(&$order, &$order_items, &$order_history) );        
        
        $print = JFactory::getApplication()->input->getInt("print");
        
        $view=$this->getView("orders", 'html');
        $view->setLayout("show");
		$view->set("canDo", $this->canDo);
        $view->set('config', $jshopConfig); 
        $view->set('order', $order); 
        $view->set('order_history', $order_history); 
        $view->set('order_items', $order_items); 
        $view->set('lists', $lists); 
        $view->set('print', $print);
        $view->set('config_fields', $config_fields);
        $view->set('count_filed_delivery', $count_filed_delivery);   
		$view->set('order_packages', $order_packages);		
		$view->set('return_packages', $return_packages);
		$view->set('return_status_list', $_returnStatusList);
        $view->_tmp_ext_discount = '';
        $view->_tmp_ext_shipping_package = '';
		$view->set('stat_download', $stat_download);
        
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeShowOrder', array(&$view,&$order,&$order_items));
        $view->displayShow();
    }

    function printOrder(){
        JFactory::getApplication()->input->set("print", 1);
        $this->show();
    }
    
    function update_one_status(){
        $this->_updateStatus(JFactory::getApplication()->input->getVar('order_id'),JFactory::getApplication()->input->getVar('order_status'),JFactory::getApplication()->input->getVar('status_id'),JFactory::getApplication()->input->getVar('notify',0),JFactory::getApplication()->input->getVar('comments',''),JFactory::getApplication()->input->getVar('include',''),1);
    }
    
    function update_status(){
        $this->_updateStatus(JFactory::getApplication()->input->getVar('order_id'),JFactory::getApplication()->input->getVar('order_status'),JFactory::getApplication()->input->getVar('status_id'),JFactory::getApplication()->input->getVar('notify',0),JFactory::getApplication()->input->getVar('comments',''),JFactory::getApplication()->input->getVar('include',''),0);        
    }    
    
    function _updateStatus($order_id, $order_status, $status_id, $notify, $comments, $include, $view_order){
        $mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
		$_orderhistory = JSFactory::getModel("orderhistory"); 
		$_orders = JSFactory::getModel("orders"); 		
		$_mails = JSFactory::getModel("mails");
		
        $client_id = JFactory::getApplication()->input->getInt('client_id',0);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeChangeOrderStatusAdmin', array(&$order_id, &$order_status, &$status_id, &$notify, &$comments, &$include, &$view_order));
        
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);        
        
        JSFactory::loadLanguageFile($order->getLang());
        $prev_order_status = $order->order_status;
        $order->order_status = $order_status;
        $order->order_m_date = getJsDate();
        $order->store();
        $order->getAllItems();
        JSFactory::getModel('OrdersFront')->prepareToPdf($order);
                
        $_orders->stockUpdate($order);
		$_orderhistory->addHistory($order_id,$order_status,$notify,$comments);
		$dispatcher->triggerEvent('onBeforeChangeOrderStatusAdminAfterAddHistory', array(&$order,$info));        


		$lang = JSFactory::getLang($order->getLang());
        $new_status = JSFactory::getTable('orderStatus', 'jshop'); 
        $new_status->load($order_status);
        $comments = ($include)?($comments):('');
        $name = $lang->get('name');
        
        $shop_item_id = getShopMainPageItemid();
        $juri = JURI::getInstance();
        $liveurlhost = $juri->toString( array("scheme",'host', 'port'));
        $app = JFactory::getApplication();
        $router = $app->getRouter();
        $uri = $router->build('index.php?option=com_jshopping&controller=user&task=order&order_id='.$order_id."&Itemid=".$shop_item_id);
        $url = $uri->toString();
        $order_details_url = $liveurlhost.str_replace('/administrator', '', $url);
        if ($order->user_id==-1){
            $order_details_url = '';
        }

        $mailfrom = $mainframe->getCfg('mailfrom');
        $fromname = $mainframe->getCfg('fromname');
        
        JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_jshopping/models');
        $_checkout = JSFactory::getModel('checkout', 'jshop');
		
		$email_texts = unserialize($new_status->email_text);
		$email_text = '';
		if($email_texts['text_'.$lang->lang]){$email_text = $email_texts['text_'.$lang->lang];}
        $message = $_checkout->getMessageChangeStatusOrder($order, $new_status->$name, $info, $order_details_url, $comments, $email_text);

        //message client
		//$_mails->
        //if ($notify){
			$ishtml = true;
            $subject = JText::sprintf('COM_SMARTSHOP_ORDER_STATUS_CHANGE_SUBJECT', $order->order_number);
			$dispatcher->triggerEvent('onBeforeSendClientMailOrderStatus', array(&$message, &$order, &$comments, &$new_status, &$info, &$order_details_url, &$ishtml, &$mailfrom, &$fromname, &$subject));

			//$dataForTemplate = array('emailSubject'=>$subject, 'emailBod'=>$message);
			//$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');           
			// $mailer = JFactory::getMailer();
            // $mailer->setSender(array($mailfrom, $fromname));
            // $mailer->addRecipient($order->email);
            // $mailer->setSubject($subject);
            // $mailer->setBody($message);
            // $mailer->isHTML($ishtml);
            // $send = $mailer->Send();

            $modelOfOrdersStatusFront = JSFactory::getModel('OrdersStatusFront');
            $modelOfOrdersStatusFront->sendEmailWhenChangeOrderStatus($order, $subject, $message, '&nbsp;', $notify, false);
        //}
		$dispatcher->triggerEvent( 'onAfterChangeOrderStatusAdminAfterSendMessageToCustomer', array(&$message, &$order, &$order_id, &$order_status, &$status_id, &$notify, &$comments, &$include, &$view_order));
        
        JSFactory::loadAdminLanguageFile();
        
        $dispatcher->triggerEvent( 'onAfterChangeOrderStatusAdmin', array(&$order_id, &$order_status, &$status_id, &$notify, &$comments, &$include, &$view_order, &$prev_order_status) );
        
        if ($view_order)
            $this->setRedirect("index.php?option=com_jshopping&controller=orders&task=show&order_id=".$order_id, JText::_('COM_SMARTSHOP_ORDER_STATUS_CHANGED'));
        else
            $this->setRedirect("index.php?option=com_jshopping&controller=orders&client_id=".$client_id, JText::_('COM_SMARTSHOP_ORDER_STATUS_CHANGED'));
    }
    
    function finish(){
		$jshopConfig = JSFactory::getConfig();
        $order_id = JFactory::getApplication()->input->getInt("order_id");
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $order->order_created = 1;
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeAdminFinishOrder', array(&$order));
        $order->store();
        $dispatcher->triggerEvent('onAfterAdminFinishOrder', [&$order]);
        
        JSFactory::loadLanguageFile($order->getLang());
        JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_jshopping/models');
        $_checkout = JSFactory::getModel('checkout', 'jshop');

        if ($jshopConfig->send_order_email){
            $_checkout->sendOrderEmail($order_id, 1);
        }
        
        JSFactory::loadAdminLanguageFile();
        $this->setRedirect("index.php?option=com_jshopping&controller=orders", JText::_('COM_SMARTSHOP_ORDER_FINISHED'));
    }

    function remove(){
        $client_id = JFactory::getApplication()->input->getInt('client_id',0);
        $cid = JFactory::getApplication()->input->getVar("cid");
        $dispatcher = \JFactory::getApplication();        
        $dispatcher->triggerEvent( 'onBeforeRemoveOrder', array(&$cid) );
        $_orders = JSFactory::getModel('orders');		
        if (count($cid)){      
            $tmp=$_orders->deleteOrders($cid);    
            $dispatcher->triggerEvent( 'onAfterRemoveOrder', array(&$cid) );
        }
        if (count($tmp)){
            $text = JText::sprintf('COM_SMARTSHOP_ORDER_DELETED_ID', implode(",",$tmp));
        }else{
            $text = "";
        }
        $this->setRedirect("index.php?option=com_jshopping&controller=orders&client_id=".$client_id, $text);
    }
    
    function edit(){
        loadingStatesScripts();
        $mainframe = JFactory::getApplication();
		$_orders = JSFactory::getModel("orders");
        $order_id = JFactory::getApplication()->input->getVar("order_id");
        $client_id = JFactory::getApplication()->input->getInt('client_id',0);
        $lang = JSFactory::getLang();        
        $jshopConfig = JSFactory::getConfig();
        $order = JSFactory::getTable('order', 'jshop');		
		$refunds = JSFactory::getModel("refund");
        $order->load($order_id);
        $name = $lang->get("name");
        
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditOrdersBefore', array(&$order));
		
		JText::script('COM_SMARTSHOP_PLUS_TAX');
        $order_items = $order->getAllItems();
		foreach($order_items as $key=>$value){
			$jshop_attr_id=unserialize($order_items[$key]->attributes);
			$product = JSFactory::getTable('product', 'jshop');
			$product->load($order_items[$key]->product_id,true,false);
			$order_items[$key]->one_time_cost = JSFactory::getTable('ProductAttribut2')->calcAttrsWithOneTimeCostPriceTypeOnly($product->product_id, $jshop_attr_id, getPriceCalcParamsTax($product->product_price, $product->product_tax_id));			
			
		}
		
		$order_packages=JSFactory::getModel("orderpackages")->loadPackages($order_id);
        
		$return_packages=JSFactory::getModel("returnpackages")->loadPackages($order_id);
		
        $_languages = JSFactory::getModel("languages");
        $languages = $_languages->getAllLanguages(1);        
        
        $select_language = JHTML::_('select.genericlist', $languages, 'lang', 'class = "inputbox form-select" style="float:none"','language', 'name', $order->lang);
        
        $country = JSFactory::getTable('country', 'jshop');
        $countries = $country->getAllCountries();
        $select_countries = JHTML::_('select.genericlist', $countries, 'country', 'class = "inputbox form-select"','country_id', 'name', $order->country );
        $select_d_countries = JHTML::_('select.genericlist', $countries, 'd_country', 'class = "inputbox form-select"','country_id', 'name', $order->d_country);
        
		$option_title = array();
        foreach($jshopConfig->user_field_title as $key=>$value){
            if ($key>0) $option_title[] = JHTML::_('select.option', $key, JText::_($value), 'title_id', 'title_name');
        }    
        $select_titles = JHTML::_('select.genericlist', $option_title,'title','class = "inputbox form-select"','title_id','title_name', $order->title);
        $select_d_titles = JHTML::_('select.genericlist', $option_title,'d_title','class = "inputbox endes form-select"','title_id','title_name', $order->d_title);
        
		$order->birthday = getDisplayDate($order->birthday, $jshopConfig->field_birthday_format);
        $order->d_birthday = getDisplayDate($order->d_birthday, $jshopConfig->field_birthday_format);
		
        $client_types = array(); 
        foreach ($jshopConfig->user_field_client_type as $key => $value) {
            $client_types[] = JHTML::_('select.option', $key, JText::_($value), 'id', 'name' );
        }
        $select_client_types = JHTML::_('select.genericlist', $client_types,'client_type','class = "inputbox form-select" onchange="shopHelper.toggleFirm(this.value)"','id','name', $order->client_type);

        $jshopConfig->user_field_client_type[0]="";
        if (isset($jshopConfig->user_field_client_type[$order->client_type])){
            $order->client_type_name = JText::_($jshopConfig->user_field_client_type[$order->client_type]);
        }else{
            $order->client_type_name = '';
        }
        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields["address"];
        $count_filed_delivery = $jshopConfig->getEnableDeliveryFiledRegistration('address');
        
        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $pm_method->load($order->payment_method_id);
        $order->payment_name = $pm_method->$name;
        
        $order->order_tax_list = $order->getTaxExt();
        
        $_currency = JSFactory::getModel("currencies");
        $currency_list = $_currency->getAllCurrencies();
        $order_currency = 0;
        foreach($currency_list as $k=>$v){
            if ($v->currency_code_iso==$order->currency_code_iso) $order_currency = $v->currency_id;
        }
        $select_currency = JHTML::_('select.genericlist', $currency_list, 'currency_id','class = "inputbox form-select"','currency_id','currency_code', $order_currency);
        
        $display_price_list = array();
        $display_price_list[] = JHTML::_('select.option', 0, JText::_('COM_SMARTSHOP_PRODUCT_BRUTTO_PRICE'), 'id', 'name');
        $display_price_list[] = JHTML::_('select.option', 1, JText::_('COM_SMARTSHOP_PRODUCT_NETTO_PRICE'), 'id', 'name');
        $display_price_select = JHTML::_('select.genericlist', $display_price_list, 'display_price', 'class="form-select" onchange="shopOrderAndOffer.updateOrderTotal();"', 'id', 'name', $order->display_price);
        
        $_shippings = JSFactory::getModel("shippings");
        $shippings_list = $_shippings->getAllShippings(0);
        $shippings_select = JHTML::_('select.genericlist', $shippings_list, 'shipping_method_id', 'class="form-select"', 'shipping_id', 'name', $order->shipping_method_id);
        
        $_payments = JSFactory::getModel("payments");
        $payments_list = $_payments->getAllPaymentMethods(0);
        $payments_select = JHTML::_('select.genericlist', $payments_list, 'payment_method_id', 'class="form-select"', 'payment_id', 'name', $order->payment_method_id);
        
        $deliverytimes = JSFactory::getAllDeliveryTime();
        $first=array(0=>"- - -");
		$delivery_time_select = JHTML::_('select.genericlist', array_merge($first,$deliverytimes), 'order_delivery_times_id', 'class="form-select"', 'id', 'name', $order->delivery_times_id);
        
        $_users = JSFactory::getModel('users');
        $users_list = $_users->getUsers();
        $first = array(0=>'- - -');
        $users_list_select = JHTML::_('select.genericlist', array_merge($first,$users_list), 'user_id', 'class="form-select" onchange="shopOrderAndOffer.updateShippingForUser(this.value);"', 'user_id', 'name', $order->user_id);

		$first = array(0 => JText::_('COM_SMARTSHOP_ORDEREDIT_NO_REASON'));
		$_returnStatusList = array_merge($first, $_orders->getAllReturnStatus());
		$return_status = JHTML::_('select.genericlist', $_returnStatusList,'return_package_status[]','class = "inputbox form-select" size = "1" id = "return_package_status"','status_id','name', 0);        
		$refund_list = $refunds->getList($order_id);

        filterHTMLSafe($order);
        foreach($order_items as $k=>$v){
            JFilterOutput::objectHTMLSafe($order_items[$k]);
        }

		if (method_exists('JHtmlBehavior', 'calendar')) {
            JHtmlBehavior::calendar();
        }
        $view=$this->getView("orders", 'html');
        $view->setLayout("edit");
		$view->set("canDo", $this->canDo);
        $view->set('config', $jshopConfig); 
        $view->set('order', $order);  
        $view->set('order_items', $order_items); 
        $view->set('config_fields', $config_fields);
        $view->set('etemplatevar', '');
        $view->set('count_filed_delivery', $count_filed_delivery);
        $view->set('order_id',$order_id);
        $view->set('select_countries', $select_countries);
        $view->set('select_d_countries', $select_d_countries);
		$view->set('select_titles', $select_titles);
        $view->set('select_d_titles', $select_d_titles);
        $view->set('select_client_types', $select_client_types);
        $view->set('select_currency', $select_currency);
        $view->set('display_price_select', $display_price_select);
        $view->set('shippings_select', $shippings_select);
        $view->set('payments_select', $payments_select);
        $view->set('select_language', $select_language);
        $view->set('delivery_time_select', $delivery_time_select);
        $view->set('users_list_select', $users_list_select);
        $view->set('client_id', $client_id);
		$view->set('order_packages', $order_packages);
		$view->set('return_packages', $return_packages);
		$view->set('return_status', $return_status);
		$view->set('return_status_list', $_returnStatusList);
		$view->set('refunds', $refund_list);
		
        $dispatcher->triggerEvent('onBeforeEditOrders', array(&$view));
        $view->displayEdit();
    }

    function save(){        
        $jshopConfig = JSFactory::getConfig();
        $post = JFactory::getApplication()->input->post->getArray();
        $client_id = JFactory::getApplication()->input->getInt('client_id',0);          
        $file_generete_pdf_order = $jshopConfig->file_generete_pdf_order;
      
        $dispatcher = \JFactory::getApplication();
        
        $order_id = intval($post['order_id']);
        $_orders = JSFactory::getModel("orders");
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        if (!$order_id){
            $order->user_id = -1;
            $order->order_date = getJsDate();
            $orderNumber = $jshopConfig->next_order_number;
            $jshopConfig->updateNextOrderNumber();
            $order->order_number = $order->formatOrderNumber($orderNumber);
            $order->order_hash = md5(time().$order->order_total.$order->user_id);
            $order->file_hash = md5(time().$order->order_total.$order->user_id."hashfile");
            $order->ip_address = $_SERVER['REMOTE_ADDR'];
            $order->order_status = $jshopConfig->default_status_order;
        }
		$order->order_m_date = getJsDate();
        $order_created_prev = $order->order_created;
        if ($post['birthday']) $post['birthday'] = getJsDateDB($post['birthday'], $jshopConfig->field_birthday_format);
        if ($post['d_birthday']) $post['d_birthday'] = getJsDateDB($post['d_birthday'], $jshopConfig->field_birthday_format);
		if ($post['invoice_date']) $post['invoice_date'] = getJsDateDB($post['invoice_date'], $jshopConfig->store_date_format);
        
        if (!$jshopConfig->hide_tax){
            $post['order_tax'] = 0;
            $order_tax_ext = array();
            if (isset($post['tax_percent'])){
                foreach($post['tax_percent'] as $k=>$v){
                    if ($post['tax_percent'][$k]!="" || $post['tax_value'][$k]!=""){
                        $order_tax_ext[number_format($post['tax_percent'][$k],2)] = $post['tax_value'][$k];
                    }
                }
            }
            $post['order_tax_ext'] = serialize($order_tax_ext);
            $post['order_tax'] = number_format(array_sum($order_tax_ext),2);
        }
       
        $currency = JSFactory::getTable('currency', 'jshop');
        $currency->load($post['currency_id']);
        $post['currency_code'] = $currency->currency_code;
        $post['currency_code_iso'] = $currency->currency_code_iso;
        $post['currency_exchange'] = $currency->currency_value;

        $dispatcher->triggerEvent('onBeforeSaveOrder', array(&$post, &$file_generete_pdf_order));

        if (!empty($post)) {
            $orderShippingAddresses = [];
        
            foreach ($post as $name => $postData) {
                if (strpos($name, 'd_') !== false) {
                    $orderShippingAddresses[$name] = $postData;
                }
            }
        
            if (!empty($orderShippingAddresses)) {
                $tableOfOrderAddress = JSFactory::getTable('OrderAddress');
                $tableOfOrderAddress->bindShippingAndBillingAddresses($orderShippingAddresses, $post);
                $isAddressStored = $tableOfOrderAddress->store();
        
                if ($isAddressStored && !empty($tableOfOrderAddress->id)) {
                    $order->order_address_id = $tableOfOrderAddress->id;
                }
            }
        }
		
		$refund = $post['refund'];
		JSFactory::getModel("refund")->save($order_id, $refund);
		$post=JSFactory::getModel('post')->replaceFloatDelimiter($post);
        $order->bind($post);
		$order->delivery_times_id = $post['order_delivery_times_id'];
        $order->store();
        $order_id = $order->order_id;
        $order_items = $order->getAllItems();
		//print_r($order_id);die;
		//$order_items = $order->setOneTimeCost($order_items);
		JSFactory::getModel("orderpackages")->savePackages($order_id,$post);
		JSFactory::getModel("returnpackages")->savePackages($order_id,$post);
		
        $_orders->saveOrderItem($order_id, $post, $order_items);
        			
        $order->items = null;        
		$dispatcher->triggerEvent('onBeforeStoreOrder', array(&$order));
        $order->store();
        
        JSFactory::loadLanguageFile($order->getLang());
        JSFactory::getModel('OrderItemsNativeUploadsFilesAdmin')->updateFromForm($post['updateUploadedFiles']);
        $modelOfOrderStatus = JSFactory::getModel('Orderstatus');
        $ordersStatusesWithSupportGenerateInvoice = $modelOfOrderStatus->getAllWitchSupport('is_generate_invoice');
        $orderStatusesWithSupportGenerateDeliveryNote = $modelOfOrderStatus->getAllWitchSupport('is_generate_delivery_note');

        $isSupportGenerateInvoice = array_key_exists($order->order_status, $ordersStatusesWithSupportGenerateInvoice);
        $isSupportGenerateDeliveryNote = array_key_exists($order->order_status, $orderStatusesWithSupportGenerateDeliveryNote);

        if ($isSupportGenerateDeliveryNote || $isSupportGenerateInvoice) {
            $order->load($order_id);
            $order->items = null;
            $order->products = $order->getAllItems();
			//$order->products = $order->setOneTimeCost($order->products);
            JSFactory::loadLanguageFile($order->getLang());
            $lang = JSFactory::getLang($order->getLang());
            
            $order->order_date = strftime($jshopConfig->store_date_format, strtotime($order->order_date));
            $order->order_tax_list = $order->getTaxExt();

            $shippingMethod = JSFactory::getTable('shippingMethodPrice', 'jshop');
            $shippingMethod->load($order->shipping_method_id);
            
            $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
            $pm_method->load($order->payment_method_id);
            
            $name = $lang->get("name");
            $description = $lang->get("description");
            $order->shipping_information = $shippingMethod->$name;
            $order->payment_name = $pm_method->$name;
            $order->payment_information = $order->payment_params;
            
            include_once($file_generete_pdf_order);
            $order->pdf_file = generatePdf($order, $isSupportGenerateDeliveryNote, $isSupportGenerateInvoice);
            $order->insertPDF();
        }

        if ($order->order_created == 1 && $order_created_prev == 0) {
            $order->items = null;
            JSFactory::loadLanguageFile($order->getLang());
            JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_jshopping/models');
            $_checkout = JSFactory::getModel('checkout', 'jshop');
            if ($jshopConfig->send_order_email){
                $_checkout->sendOrderEmail($order_id, 1); 
            }
        }
        
        JSFactory::loadAdminLanguageFile();
        $dispatcher->triggerEvent('onAfterSaveOrder', array(&$order, &$file_generete_pdf_order) );
        $this->setRedirect("index.php?option=com_jshopping&controller=orders&client_id=".$client_id);
    }
    
	function stat_file_download_clear(){        
        $order_id = JFactory::getApplication()->input->getInt("order_id");
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $order->file_stat_downloads = '';
        $order->store();
        $this->setRedirect("index.php?option=com_jshopping&controller=orders&task=show&order_id=".$order_id);
    }
    
    function send(){
        $order_id = JFactory::getApplication()->input->getInt("order_id");
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        JSFactory::loadLanguageFile($order->getLang());
        JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_jshopping/models');
        $_checkout = JSFactory::getModel('checkout', 'jshop');
        $_checkout->sendOrderEmail($order_id, 1);
        JSFactory::loadAdminLanguageFile();
        $this->setRedirect("index.php?option=com_jshopping&controller=orders&task=show&order_id=".$order_id, JText::_('COM_SMARTSHOP_MAIL_HAS_BEEN_SENT'));
    }
    
    function transactions(){
        $order_id = JFactory::getApplication()->input->getInt("order_id");
        $jshopConfig = JSFactory::getConfig();
        
        $_orders = JSFactory::getModel("orders");
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $rows = $order->getListTransactions();
        
        $_list_order_status = $_orders->getAllOrderStatus();
        $list_order_status = array();
        foreach($_list_order_status as $v){
            $list_order_status[$v->status_id] = $v->name;
        }
        
        $view = $this->getView("orders", 'html');
        $view->setLayout("transactions");
		$view->set("canDo", $this->canDo);
        $view->set('config', $jshopConfig); 
        $view->set('order', $order);
        $view->set('rows', $rows);
        $view->set('list_order_status', $list_order_status);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeShowOrderTransactions', array(&$view));
        $view->displayTrx();   
    }
    
    function cancel(){
        $client_id = JFactory::getApplication()->input->getInt('client_id',0);
        $this->setRedirect("index.php?option=com_jshopping&controller=orders&client_id=".$client_id);
    }
	//Product shipping

   ///////////////////////////

    public function changeStatus()
    {
        $ids = explode(',', JFactory::getApplication()->input->getVar('cid'));
        $order_status = JFactory::getApplication()->input->getInt('change_status_status_id');
        $notify_users = JFactory::getApplication()->input->getInt('change_status_notify_customer'); 

        if (is_array($ids)) {
            foreach($ids as $id) {
                $this->_updateStatus($id, $order_status, '', $notify_users, '', '', 0);
            }
        }

        $this->setRedirect('index.php?option=com_jshopping&controller=orders', JText::_('COM_SMARTSHOP_ORDER_STATUS_CHANGED'));
    }

    public function deleteUploadedFile()
    {
        $result = [
            'success' => true
        ];
        $idOfOrderNativeUpload = $this->input->get('id');

        if (!empty($idOfOrderNativeUpload)) {
            $modelOfOrderItemsNativeUploadsFiles = JSFactory::getModel('OrderItemsNativeUploadsFilesAdmin');
            $result['success'] = $modelOfOrderItemsNativeUploadsFiles->deleteUploadedFileFromDbAndFileById($idOfOrderNativeUpload);
        }

        echo json_encode($result);
        die;
    }
}
