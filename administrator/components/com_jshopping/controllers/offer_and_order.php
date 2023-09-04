<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');


class JshoppingControllerOffer_and_order extends JControllerLegacy {
	
	protected $canDo;    
	
    public function __construct($config = array()) 
    {
        parent::__construct($config);
        $this->registerTask('add', 'edit');
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');        
        addSubmenu('addon_offer_and_order',$this->canDo);
    }

    public function display($cachable = false, $urlparams = []) 
    {
        $user_id = JFactory::getApplication()->input->getVar("user_id");
        $jshopConfig = JSFactory::getConfig();
        $mainframe = JFactory::getApplication();
        $context = "jshopping.list.admin.offer_and_orders";
        $limit = $mainframe->getUserStateFromRequest($context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');        

        $status_id = $mainframe->getUserStateFromRequest($context . 'status_id', 'status_id', 0);
        $year = $mainframe->getUserStateFromRequest($context . 'year', 'year', 0);
        $month = $mainframe->getUserStateFromRequest($context . 'month', 'month', 0);
        $day = $mainframe->getUserStateFromRequest($context . 'day', 'day', 0);
        $notfinished = $mainframe->getUserStateFromRequest($context . 'notfinished', 'notfinished', 0);
        $text_search = $mainframe->getUserStateFromRequest($context . 'text_search', 'text_search', '');
        $filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', "order_number", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', "desc", 'cmd');

        $filter = array("status_id" => $status_id, "year" => $year, "month" => $month, "day" => $day, "text_search" => $text_search, 'notfinished' => $notfinished);
		
		JPluginHelper::importPlugin('jshoppingorder');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayListOfferAndOrderAfterFilterSetAdmin', array(&$filter));		
		
        $_offer_and_order = $this->getModel("offer_and_order");

        $total = $_offer_and_order->getCountAllOrders($filter);
        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $rows = $_offer_and_order->getAllOrders($pageNav->limitstart, $pageNav->limit, $filter, $filter_order, $filter_order_Dir, $user_id);

        $firstYear = $_offer_and_order->getMinYear();
        $y_option = array();
        $y_option[] = JHTML::_('select.option', 0, " - - - ", 'id', 'name');
        for ($y = $firstYear; $y <= date("Y"); $y++) {
            $y_option[] = JHTML::_('select.option', $y, $y, 'id', 'name');
        }
        $lists['year'] = JHTML::_('select.genericlist', $y_option, 'year', 'class="form-select"', 'id', 'name', $year);

        $y_option = array();
        $y_option[] = JHTML::_('select.option', 0, " - - ", 'id', 'name');
        for ($y = 1; $y <= 12; $y++) {
            if ($y < 10)
                $y_month = "0" . $y;
            else
                $y_month = $y;
            $y_option[] = JHTML::_('select.option', $y_month, $y_month, 'id', 'name');
        }
        $lists['month'] = JHTML::_('select.genericlist', $y_option, 'month', 'class="form-select"', 'id', 'name', $month);

        $y_option = array();
        $y_option[] = JHTML::_('select.option', 0, " - - ", 'id', 'name');
        for ($y = 1; $y <= 31; $y++) {
            if ($y < 10)
                $y_day = "0" . $y;
            else
                $y_day = $y;
            $y_option[] = JHTML::_('select.option', $y_day, $y_day, 'id', 'name');
        }
        $lists['day'] = JHTML::_('select.genericlist', $y_option, 'day', 'class="form-select"', 'id', 'name', $day);

        $_users = JSFactory::getModel("users");
        jimport('joomla.html.pagination');
        $users_list = $_users->getAllUsers(0, 0);

        $y_user = array();
        $y_user[] = JHTML::_('select.option', 0, " - - - ", 'id', 'name');
        foreach ($users_list as $key => $user) {
            $y_user[] = JHTML::_('select.option', $user->user_id, $user->u_name, 'id', 'name');
        }
        $lists['user'] = JHTML::_('select.genericlist', $y_user, 'user_id', 'class="form-select"', 'id', 'name', $user_id);

        $dispatcher->triggerEvent('onBeforeDisplayListOfferAndOrderAdmin', array(&$rows));

        $view = $this->getView("offer_and_order", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);
        $view->set('lists', $lists);
        $view->set('pageNav', $pageNav);
        $view->set('text_search', $text_search);
        $view->set('filter', $filter);        
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);
        $view->set('list_order_status', $list_order_status ?? '');
        $dispatcher->triggerEvent('onBeforeShowOfferAndOrderListView', array(&$view));
        $view->displayList();
    }

    public function offer_options()
    {		
		$first = [
            0 => '- - -'
        ];

        $params = (array)JSFactory::getConfig();
        $shippingsList = JSFactory::getModel('shippings')->getAllShippings(0);
        $paymentsList = JSFactory::getModel('payments')->getAllPaymentMethods(0);
        $shippingsSelectHtml = JHTML::_('select.genericlist', array_merge($first, $shippingsList), 'params[offer_and_order_shipping]', 'class="form-select"', 'shipping_id', 'name', $params['offer_and_order_shipping']);
        $paymentsSelectHtml = JHTML::_('select.genericlist', array_merge($first, $paymentsList), 'params[offer_and_order_payment]', 'class="form-select"', 'payment_id', 'name', $params['offer_and_order_payment']);
        $view = $this->getView('offer_and_order', 'html');
        $view->setLayout('options');
		$view->set('canDo', $canDo);
        $view->set('shippings_select', $shippingsSelectHtml);
        $view->set('payments_select', $paymentsSelectHtml);
        $view->set('params', $params);
        $view->displayOptions();
    }

    public function saveOfferOptions()
    {
        $db = \JFactory::getDBO();		
		$jshopConfig = JSFactory::getConfig();
        $app = JFactory::getApplication();
        $params = JFactory::getApplication()->input->get('params');
        $jshopConfigTable = JSFactory::getTable('config');
        $jshopConfigTable->load($jshopConfig->load_id);
	
		if (!isset($params['allow_offer_on_product_details_page'])) {
            $params['allow_offer_on_product_details_page'] = 0;
        }

        if (!isset($params['allow_offer_in_cart'])) {
            $params['allow_offer_in_cart'] = 0;
        }
        
        if (isset($params['offer_and_order_suffix']) && $params['offer_and_order_suffix'] != '' && substr($params['offer_and_order_suffix'], -1) != '-') {
            $params['offer_and_order_suffix'] .= '-';
        }
        
        $jshopConfigTable->bind([
            'offer_and_order_suffix' => $params['offer_and_order_suffix']
        ]);
        $jshopConfigTable->store();

        $inputsNamesToMiss = ['offer_and_order_suffix'];
        $fieldsToUpdate = [];
        foreach($params as $tableColumnName => $value) {
            if (in_array($tableColumnName, $inputsNamesToMiss)) {
                continue;
            }

            $valueToInsert = !empty($value) ? $db->q($value) : $db->q('');
            $fieldsToUpdate[] = $db->qn($tableColumnName) . ' = ' . $valueToInsert;
        }

        if ( !empty($fieldsToUpdate) ) {
            $query = $db->getQuery(true);

            $query->update($db->qn('#__jshopping_config'))->set($fieldsToUpdate);
			$query->where("id=".$db->quote($jshopConfig->load_id));
            $db->setQuery($query);

            $isSaveSuccess = $db->execute();
        }

        raiseMsgForUser($isSaveSuccess , [
            'success' => JText::_('COM_SMARTSHOP_SETTINGS_SUCCESS_SAVED') . '!',
            'fail' => JText::_('COM_SMARTSHOP_SETTINGS_SUCCESS_FAIL') . '!'
        ]);

        return $app->redirect('index.php?option=com_jshopping&controller=offer_and_order');
    }

    public function show() 
    {
        $order_id = JFactory::getApplication()->input->getInt("order_id");
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $order->load($order_id);

        $name = $lang->get("name");
        $order_items = $order->getAllItems();
        $order->weight = $order->getWeightItems();

        $country = JTable::getInstance('country', 'jshop');
        $country->load($order->country);
        $field_country_name = $lang->get("name");
        $order->country = $country->$field_country_name;

        $d_country = JTable::getInstance('country', 'jshop');
        $d_country->load($order->d_country);
        $field_country_name = $lang->get("name");
        $order->d_country = $d_country->$field_country_name;

        $order->title = $jshopConfig->arr['title'][$order->title];
        $order->d_title = $jshopConfig->arr['title'][$order->d_title];

        $jshopConfig->user_field_client_type[0] = "";
        $order->client_type_name = $jshopConfig->user_field_client_type[$order->client_type];

        $order->order_tax_list = $order->getTaxExt();

        $tmp_fields = $jshopConfig->getListFieldsRegister();
        $config_fields = $tmp_fields["address"];
        $count_filed_delivery = $jshopConfig->getEnableDeliveryFiledRegistration('address');

        $display_info_only_product = 0;
 
        $display_block_change_order_status = $order->order_created;
     
        $deliverytimes = JSFactory::getAllDeliveryTime();
        $order->delivery_time_name = $deliverytimes[$order->delivery_times_id];
        if ($order->delivery_time_name == "") {
            $order->delivery_time_name = $order->delivery_time;
        }

        JPluginHelper::importPlugin('jshoppingorder');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayOfferAndOrderAdmin', array(&$order, &$order_items, &$order_history,&$display_block_change_order_status,&$display_info_only_product));

        $print = JFactory::getApplication()->input->getInt("print");

        $view = $this->getView("offer_and_order", 'html');
        $view->setLayout("show");
		$view->set('canDo', $canDo);
        $view->set('config', $jshopConfig);
        $view->set('order', $order);
        $view->set('order_history', $order_history);
        $view->set('order_items', $order_items);
        $view->set('lists', $lists);
        $view->set('print', $print);
        $view->set('config_fields', $config_fields);
        $view->set('count_filed_delivery', $count_filed_delivery);
        $view->set('display_info_only_product', $display_info_only_product);        
        $view->set('display_block_change_order_status', $display_block_change_order_status);
        JPluginHelper::importPlugin('jshoppingadmin');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeShowOfferAndOrder', array(&$view));
        $view->displayShow();
    }

    public function loadtaxorder()
    {
        $post = $this->input->post->getArray();
        $data_order = (isset($data_order['data_order'])) ? (array)$post['data_order'] : [];
        $products = (isset($data_order['product'])) ? (array)$data_order['product'] : [];
		
		if((array)json_decode($post['data_order'])){
			$data_order = (array)json_decode($post['data_order']);
		}
		if((array)json_decode($post['product'])){
			$products = (array)json_decode($post['product']);
		}
        $_offer_and_order = JSFactory::getModel('offer_and_order');
        $taxes_array = $_offer_and_order->loadtaxorder($data_order, $products);
        print json_encode($taxes_array);
        die;
    }    

    public function remove() 
    {
        $cid = JFactory::getApplication()->input->getVar("cid");
        JPluginHelper::importPlugin('jshoppingorder');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeRemoveOfferAndOrder', array(&$cid));
        if (count($cid)) {
			$_offer_and_order = JSFactory::getModel('offer_and_order');		
			$tmp=$_offer_and_order->deleteOffer_and_order($cid);  
            $dispatcher->triggerEvent('onAfterRemoveOfferAndOrder', array(&$cid));
        }
        if (count($tmp)) {
            $text = sprintf(JText::_('COM_SMARTSHOP_ORDER_DELETED_ID'), implode(",", $tmp));
        } else {
            $text = "";
        }
        $this->setRedirect("index.php?option=com_jshopping&controller=offer_and_order", $text);
    }

    public function edit()
    {	
        $mainframe = JFactory::getApplication();
        $order_id = JFactory::getApplication()->input->getVar("order_id");
        $client_id = JFactory::getApplication()->input->getInt('client_id',0);
        $lang = JSFactory::getLang();        
        $jshopConfig = JSFactory::getConfig();
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $order->load($order_id);
        $name = $lang->get("name");
		JPluginHelper::importPlugin('jshoppingadmin');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditOfferAndOrderFirst', array(&$order));
		JText::script('COM_SMARTSHOP_PLUS_TAX');
        $order_items = $order->getAllItems();
        
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
        
        $order->birthday = getDisplayDate($order->birthday ?? 0, $jshopConfig->field_birthday_format);
        $order->d_birthday = getDisplayDate($order->d_birthday ?? 0, $jshopConfig->field_birthday_format);
        
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

        filterHTMLSafe($order);
        foreach($order_items as $k=>$v){
            JFilterOutput::objectHTMLSafe($order_items[$k]);
        }

        $view = $this->getView("offer_and_order", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->set('config', $jshopConfig);
        $view->set('order', $order);
        $view->set('order_items', $order_items);
        $view->set('select_titles', $select_titles);
        $view->set('config_fields', $config_fields);
        $view->set('count_filed_delivery', $count_filed_delivery);
        $view->set('order_id', $order_id);
        $view->set('select_countries', $select_countries);
        $view->set('select_d_countries', $select_d_countries);
        $view->set('select_client_types', $select_client_types);
        $view->set('select_currency', $select_currency);
        $view->set('display_price_select', $display_price_select);
        $view->set('shippings_select', $shippings_select);
        $view->set('payments_select', $payments_select);
        $view->set('select_language', $select_language);
        $view->set('delivery_time_select', $delivery_time_select);        
        $dispatcher->triggerEvent('onBeforeEditOfferAndOrder', array(&$view));
        $view->displayEdit();        
    }    

    public function save() 
    {        
        $jshopConfig = JSFactory::getConfig();
        $post = JFactory::getApplication()->input->post->getArray();
        $file_generete_pdf_order = $jshopConfig->file_generete_pdf_order;
        JPluginHelper::importPlugin('jshoppingadmin');
        $dispatcher = \JFactory::getApplication();

        $order_id = intval($post['order_id']);
        $_offer_and_order = $this->getModel('offer_and_order');
        $order = JTable::getInstance('offer_and_order', 'jshop');
        $order->load($order_id);
        if (!$order_id) {
            $order->user_id = -1;
            $order->order_date = $order->order_m_date = date('Y-m-d H:i:s', time());
            $orderNumber = $jshopConfig->next_order_number;
            $jshopConfig->updateNextOrderNumber();
            $order->order_number = $order->formatOrderNumber($orderNumber);
            $order->order_hash = md5(time() . $order->order_total . $order->user_id);
            $order->file_hash = md5(time() . $order->order_total . $order->user_id . 'hashfile');
            $order->ip_address = $_SERVER['REMOTE_ADDR'];
            $order->order_status = $jshopConfig->default_status_order;
            $order->order_created = 1;
        }

        if (!$jshopConfig->hide_tax) {
            $post['order_tax'] = 0;
            $order_tax_ext = array();
            foreach ($post['tax_percent'] as $k => $v) {
                if ($post['tax_percent'][$k] != '' || $post['tax_value'][$k] != '') {
                    $order_tax_ext[number_format($post['tax_percent'][$k], 2)] = $post['tax_value'][$k];
                }
            }
            $post['order_tax_ext'] = serialize($order_tax_ext);
            $post['order_tax'] = number_format(array_sum($order_tax_ext), 2);
        }
		$products = [];
	
		if(!empty($post['product_id'])){
			foreach($post['product_id'] as $key=>$val){ 
				$products[$key]['product_id'] = $val;
                $products[$key]['ean'] = $post['product_ean'][$key];
                $products[$key]['product_name'] = $post['product_name'][$key];
                $products[$key]['quantity'] = $post['product_quantity'][$key];
                $products[$key]['price'] = $post['product_item_price'][$key];
                $products[$key]['tax'] = $post['product_tax'][$key];
				$products[$key]['product_attributes'] = $post['product_attributes'][$key];
                $products[$key]['product_freeattributes'] = $post['product_freeattributes'][$key];
                $products[$key]['weight'] = $post['weight'][$key];
                $products[$key]['thumb_image'] = $post['thumb_image'][$key];
                $products[$key]['delivery_times_id'] = $post['delivery_times_id'][$key];
                $products[$key]['vendor_id'] = $post['vendor_id'][$key];
				if($post['product_one_time_price'][$key] > 0) $products[$key]['product_one_time_price'] = $post['product_one_time_price'][$key]; else $products[$key]['product_one_time_price'] = 0;
                $products[$key]['one_time_cost'] = ($post['product_item_price'][$key] * $post['product_quantity'][$key]) + $post['product_one_time_price'][$key];
			}
		}
        $currency = JTable::getInstance('currency', 'jshop');
        $currency->load($post['currency_id']);
        $post['currency_code'] = $currency->currency_code;
        $post['currency_code_iso'] = $currency->currency_code_iso;
        $post['currency_exchange'] = $currency->currency_value;

        $dispatcher->triggerEvent('onBeforeSaveOfferAndOrder', array(&$post, &$file_generete_pdf_order));

        $order->bind($post, [], 1);
        $order->store();
        $order_id = $order->order_id;
        $order_items = $order->getAllItems();

        $isSaveSuccess = (bool)$order->saveOrderItem($products);
        raiseMsgForUser($isSaveSuccess, [
            'success' => 'Save success',
            'fail' => 'Success fail!'
        ]);

        JSFactory::loadLanguageFile($order->getLang());
        JSFactory::loadAdminLanguageFile();

        $this->updateOfferPdfFile($order->order_id, $jshopConfig);
        $dispatcher->triggerEvent('onAfterSaveOfferAndOrder', array(&$order, &$file_generete_pdf_order));
        $this->setRedirect('index.php?option=com_jshopping&controller=offer_and_order');
    }

    public function updateOfferPdfFile(&$orderId, &$jshopConfig)
    {
        include_once(templateOverride("pdf", "generete_pdf_offer_and_order.php"));//JPATH_COMPONENT_SITE . "/lib/generete_pdf_offer_and_order.php");

        $order = JTable::getInstance('offer_and_order', 'jshop');
        $order->load($orderId);

        $order->products = $order->getAllItems();
        $order->pdf_file = generateOfferAndOrderPdf($order, $jshopConfig);
        $order->insertPDF();        
        
        return $order->store();
    }    

    public function cancel() 
    {
        $this->setRedirect('index.php?option=com_jshopping&controller=offer_and_order');
    }

    public function relogin_to_frontend() 
    {
        checkUserLogin();
        $admin_user_id = JFactory::getApplication()->input->getInt('admin_user_id');
        $user_id = JFactory::getApplication()->input->getInt('user_id');
        $user = JFactory::getUser();
        $hide_pd5_password = md5(time() . $admin_user_id . $user_id);
        $user->hide_pd5_password = $hide_pd5_password;
        $user->save();
        $this->setRedirect(JURI::base() . '../index.php?option=com_jshopping&controller=offer_and_order&task=relogin&admin_user_id=' . intval($admin_user_id) . '&user_id=' . intval($user_id) . '&password=' . $hide_pd5_password);
    }

    public function send_offer_email() 
    {
        checkUserLogin();
        // INCLUDE LANG
        $app = JFactory::getApplication();
		if (!$app->get('mailonline', 1)){
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SENDING_MAIL'),'error');			
		}else{
			$user_id = JFactory::getApplication()->input->getVar('user_id');
			$order_id = JFactory::getApplication()->input->getVar('order_id');
			$user_shop = JSFactory::getTable('userShop', 'jshop');
			$user_shop->load($user_id);
			$order = JTable::getInstance('offer_and_order', 'jshop');
			$order->load($order_id);
			$order->status_email = intval($order->status_email) + 1;
			$order->store();
			$config = JFactory::getConfig();
			$data = $user_shop->getProperties();
			$data['fromname'] = $config->get('fromname');
			$data['mailfrom'] = $config->get('mailfrom');
			$data['sitename'] = $config->get('sitename');
			$data['siteurl'] = JUri::base();

			$emailSubject = sprintf(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SEND_EMAIL_HEAD'), $user_shop->f_name, $user_shop->l_name, $data['sitename'], $data['name']);
			$emailBody = sprintf(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SEND_EMAIL_BODY'), $user_shop->f_name, $user_shop->l_name, $data['sitename'], $data['username']);
			
			$dataForTemplate = array('emailSubject'=>$emailSubject, 'emailBod'=>$emailBody);
			$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
			
			$filename = $order->pdf_file;
			$mailer = JFactory::getMailer();
			$mailer->setSender(array($data['mailfrom'], $data['fromname']));
			$mailer->addRecipient($data['email']);
			$mailer->setSubject($emailSubject);
			$mailer->setBody($bodyEmailText);
			if (!empty($filename)) {
				$path = JPATH_SITE . '/components/com_jshopping/files/pdf_orders/' . $filename;
				if (file_exists($path)) {
					$mailer->addAttachment($path);
				}
			}
			$mailer->isHTML(true);
			$isMailSendSuccess = $mailer->Send();

			raiseMsgForUser($isMailSendSuccess, [
				'success' => JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SENT') . '!',
				'fail' => JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_EMAIL_SHARE_ERROR_MESSAGE') . '!'
			]);
			
		}
			$user_id = JFactory::getApplication()->input->getInt('user_id');
			$this->setRedirect('index.php?option=com_jshopping&controller=offer_and_order&user_id=' . intval($user_id));
    }

    public function create_new_order() 
    {
        $mainframe = JFactory::getApplication();
        $order_id = JFactory::getApplication()->input->getVar('order_id');
        $client_id = JFactory::getApplication()->input->getInt('client_id', 0);
        $lang = JSFactory::getLang();        
        $jshopConfig = JSFactory::getConfig();
		$dispatcher = \JFactory::getApplication();
        $order = JSFactory::getTable('offer_and_order', 'jshop');
        $order->load($order_id);
        if ($order->offer_status == 1) {
            $name = $lang->get('name');
			
			$dispatcher->triggerEvent('onBeforeCreateNewOrderOfferAndOrderAdmin', array(&$order));
            
            $order_items = $order->getAllItems();

            $_languages = JSFactory::getModel('languages');
            $languages = $_languages->getAllLanguages(1);

            $select_language = JHTML::_('select.genericlist', $languages, 'lang', 'class = "inputbox form-select"  style="float:none"', 'language', 'name', $order->lang);

            $country = JSFactory::getTable('country', 'jshop');
            $countries = $country->getAllCountries();
            $select_countries = JHTML::_('select.genericlist', $countries, 'country', 'class = "inputbox form-select"', 'country_id', 'name', $order->country);
            $select_d_countries = JHTML::_('select.genericlist', $countries, 'd_country', 'class = "inputbox form-select"', 'country_id', 'name', $order->d_country);

            $option_title = array();
            foreach ($jshopConfig->user_field_title as $key => $value) {
                if ($key > 0) {
                    $option_title[] = JHTML::_('select.option', $key, $value, 'title_id', 'title_name');
                }
            }
            $select_titles = JHTML::_('select.genericlist', $option_title, 'title', 'class = "inputbox form-select"', 'title_id', 'title_name', $order->title);
            $select_d_titles = JHTML::_('select.genericlist', $option_title, 'd_title', 'class = "inputbox endes form-select"', 'title_id', 'title_name', $order->d_title);

            $order->birthday = getDisplayDate($order->birthday, $jshopConfig->field_birthday_format);
            $order->d_birthday = getDisplayDate($order->d_birthday, $jshopConfig->field_birthday_format);

            $client_types = array();
            foreach ($jshopConfig->user_field_client_type as $key => $value) {
                $client_types[] = JHTML::_('select.option', $key, $value, 'id', 'name');
            }
            $select_client_types = JHTML::_('select.genericlist', $client_types, 'client_type', 'class = "inputbox form-select" onchange="showHideFieldFirm(this.value)"', 'id', 'name', $order->client_type);

            $jshopConfig->user_field_client_type[0] = '';
            if (isset($jshopConfig->user_field_client_type[$order->client_type])) {
                $order->client_type_name = $jshopConfig->user_field_client_type[$order->client_type];
            } else {
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
            foreach ($currency_list as $k => $v) {
                if ($v->currency_code_iso == $order->currency_code_iso) {
                    $order_currency = $v->currency_id;
                }
            }
            $select_currency = JHTML::_('select.genericlist', $currency_list, 'currency_id', 'class = "inputbox form-select"', 'currency_id', 'currency_code', $order_currency);

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
            $first = array(0 => '- - -');
            $delivery_time_select = JHTML::_('select.genericlist', array_merge($first, $deliverytimes), 'order_delivery_times_id', 'class="form-select"', 'id', 'name', $order->delivery_times_id);

            $_users = JSFactory::getModel('users');
            $users_list = $_users->getUsers();
            $first = array(0 => '- - -');
            $users_list_select = JHTML::_('select.genericlist', array_merge($first, $users_list), 'user_id', 'class="form-select" onchange="shopOrderAndOffer.updateShippingForUser(this.value);"', 'user_id', 'name', $order->user_id);

            filterHTMLSafe($order);
            foreach ($order_items as $k => $v) {
                JFilterOutput::objectHTMLSafe($order_items[$k]);
            }

            if (method_exists('JHtmlBehavior', 'calendar')) {
                JHtmlBehavior::calendar();
            }
            $view = $this->getView('offer_and_order', 'html');
            $view->setLayout('create_order');
			$view->set('canDo', $canDo);
            $view->set('config', $jshopConfig);
            $view->set('order', $order);
            $view->set('order_items', $order_items);
            $view->set('config_fields', $config_fields);
            $view->set('etemplatevar', '');
            $view->set('count_filed_delivery', $count_filed_delivery);
            $view->set('order_id', $order_id);
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

            $dispatcher = \JFactory::getApplication();
            $view->displayCreate();
        } else {
            $this->setRedirect('index.php?option=com_jshopping&controller=offer_and_order');
        }
    }

    public function saveorder() 
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $offer_id = intval($post['order_id']);
        $offer = JTable::getInstance('offer_and_order', 'jshop');
        $offer->load($offer_id);
        $offer->offer_status = 3;
        $offer->store();        
        $jshopConfig = JSFactory::getConfig();
        $client_id = JFactory::getApplication()->input->getInt('client_id', 0);
        $file_generete_pdf_order = $jshopConfig->file_generete_pdf_order;

        $dispatcher = \JFactory::getApplication();
        unset($post['order_id']);
        $order_id = intval($post['order_id']);
        $_offer_and_order = JSFactory::getModel('offer_and_order');
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        if (!$order_id) {
            $order->user_id = -1;
            $order->order_date = getJsDate();
            $orderNumber = $jshopConfig->next_order_number;
            $jshopConfig->updateNextOrderNumber();
            $order->order_number = $order->formatOrderNumber($orderNumber);
            $order->order_hash = md5(time() . $order->order_total . $order->user_id);
            $order->file_hash = md5(time() . $order->order_total . $order->user_id . 'hashfile');
            $order->ip_address = $_SERVER['REMOTE_ADDR'];
            $order->order_status = $jshopConfig->default_status_order;
        }
        $order->order_m_date = getJsDate();
        $order_created_prev = $order->order_created;
        if ($post['birthday'])
            $post['birthday'] = getJsDateDB($post['birthday'], $jshopConfig->field_birthday_format);
        if ($post['d_birthday'])
            $post['d_birthday'] = getJsDateDB($post['d_birthday'], $jshopConfig->field_birthday_format);
        if ($post['invoice_date'])
            $post['invoice_date'] = getJsDateDB($post['invoice_date'], $jshopConfig->store_date_format);

        if (!$jshopConfig->hide_tax) {
            $post['order_tax'] = 0;
            $order_tax_ext = array();
            if (isset($post['tax_percent'])) {
                foreach ($post['tax_percent'] as $k => $v) {
                    if ($post['tax_percent'][$k] != '' || $post['tax_value'][$k] != '') {
                        $order_tax_ext[number_format($post['tax_percent'][$k], 2)] = $post['tax_value'][$k];
                    }
                }
            }
            $post['order_tax_ext'] = serialize($order_tax_ext);
            $post['order_tax'] = number_format(array_sum($order_tax_ext), 2);
        }

        $currency = JSFactory::getTable('currency', 'jshop');
        $currency->load($post['currency_id']);
        $post['currency_code'] = $currency->currency_code;
        $post['currency_code_iso'] = $currency->currency_code_iso;
        $post['currency_exchange'] = $currency->currency_value;

        $dispatcher->triggerEvent('onBeforeSaveOrder', array(&$post, &$file_generete_pdf_order));

        $order->bind($post);
        $order->delivery_times_id = $post['order_delivery_times_id'];
        $order->store();
        $order_id = $order->order_id;
        $order_items = $order->getAllItems();
        $_offer_and_order->saveOrderItem($order_id, $post, $order_items);
		
        $order->items = null;        
		$dispatcher->triggerEvent('onBeforeSaveOrder2', array(&$order));
        $order->store();

        JSFactory::loadLanguageFile($order->getLang());

        $isSendOfferAndOrderToClient = true;
        $isSendOfferAndOrderToAdmin = true;
        if ($isSendOfferAndOrderToClient || $isSendOfferAndOrderToAdmin) {
            $order->load($order_id);
            $order->items = null;
            $order->products = $order->getAllItems();
            JSFactory::loadLanguageFile($order->getLang());
            $lang = JSFactory::getLang($order->getLang());

            $order->order_date = strftime($jshopConfig->store_date_format, strtotime($order->order_date));
            $order->order_tax_list = $order->getTaxExt();
            $country = JSFactory::getTable('country', 'jshop');
            $country->load($order->country);
            $field_country_name = $lang->get('name');
            $order->country = $country->$field_country_name;

            $d_country = JSFactory::getTable('country', 'jshop');
            $d_country->load($order->d_country);
            $field_country_name = $lang->get('name');
            $order->d_country = $d_country->$field_country_name;

            $shippingMethod = JSFactory::getTable('shippingMethodPrice', 'jshop');
            $shippingMethod->load($order->shipping_method_id);

            $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
            $pm_method->load($order->payment_method_id);

            $name = $lang->get('name');
            $description = $lang->get('description');
            $order->shipping_information = $shippingMethod->$name;
            $order->payment_name = $pm_method->$name;
            $order->payment_information = $order->payment_params;

            include_once($file_generete_pdf_order);
            $order->pdf_file = generatePdf($order);
            $order->insertPDF();
        }

        JSFactory::loadAdminLanguageFile();
        $this->setRedirect('index.php?option=com_jshopping&controller=orders&client_id=' . $client_id);  
    }

    public function trigger_offer_to_order() 
    {
        $user_id = JFactory::getApplication()->input->getVar('user_id');
        $order_id = JFactory::getApplication()->input->getVar('order_id');
        $this->do_offer_to_order($user_id, $order_id);
        $this->setRedirect('index.php?option=com_jshopping&controller=orders');
    }

    public function do_offer_to_order($user_id, $offer_id) 
    {
        checkUserLogin();
        $jshopConfig = JSFactory::getConfig();

        $offer = JTable::getInstance('offer_and_order', 'jshop');
        $offer->load($offer_id);

        $order = JSFactory::getTable('order', 'jshop');
        $arr_onliy_offer = array('order_id', 'status_email', 'order_number', 'valid_to', 'show_invoice_date', 'offer_status');


        foreach ($offer as $key => $value) {
            if (!in_array($key, $arr_onliy_offer)) {
                $order->$key = $value;
            }
        }

        $order->order_date = $order->order_m_date = date('Y-m-d H:i:s', time());
        $orderNumber = $jshopConfig->getNextOrderNumber();
        $jshopConfig->updateNextOrderNumber();
        if (!$order->order_number) {
            $order->order_number = $order->formatOrderNumber($orderNumber);
        }
        $order->order_hash = md5(time() . $order->order_total . $order->user_id);
        $order->file_hash = md5(time() . $order->order_total . $order->user_id . 'hashfile');
        $order->order_status = $jshopConfig->default_status_order;

        $pm_method = JSFactory::getTable('paymentMethod', 'jshop');
        $pm_method->load($order->payment_method_id);

        $sh_method = JSFactory::getTable('shippingMethodPrice', 'jshop');
        $sh_method->load($order->shipping_method_id);


        if ($jshopConfig->without_payment) {
            $pm_method->payment_type = 1;
            $paymentSystemVerySimple = 1;
        } else {
            $paymentsysdata = $pm_method->getPaymentSystemData();
            $payment_system = $paymentsysdata->paymentSystem;
            if ($paymentsysdata->paymentSystemVerySimple) {
                $paymentSystemVerySimple = 1;
            }
            if ($paymentsysdata->paymentSystemError) {
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_PAYMENT'),'error');
                return 0;
            }
        }

        $pm_params = $this->getPaymentParams();
        if (is_array($pm_params) && !$paymentSystemVerySimple) {
            $payment_system->setParams($pm_params);
            $payment_params_names = $payment_system->getDisplayNameParams();
            $pm_params_data = $payment_system->getPaymentParamsData($pm_params);
            $order->payment_params = getTextNameArrayValue($payment_params_names, $pm_params_data);
            $order->setPaymentParamsData($pm_params);
        }

        $sh_params = $this->getShippingParams();
        if (is_array($sh_params)) {
            $shippingForm = $sh_method->getShippingForm();
            if ($shippingForm) {
                $shippingForm->setParams($sh_params);
                $shipping_params_names = $shippingForm->getDisplayNameParams();
                $order->shipping_params = getTextNameArrayValue($shipping_params_names, $sh_params);
            }
            $order->setShippingParamsData($sh_params);
        }

        $order->order_created = 1;

        $order->store();
        $_offer_and_order = $this->getModel('offer_and_order');
        $_offer_and_order->saveOfferItemToOrderItem($offer, $order);
        $offer->delete($offer->offer_id);

        $order->getAllItems();

        $order_history = JSFactory::getTable('orderHistory', 'jshop');
        $order_history->order_id = $order->order_id;
        $order_history->order_status_id = $order->order_status;
        $order_history->status_date_added = getJsDate();
        $order_history->customer_notify = 1;
        $order_history->comments = "";
        $order_history->store();

        $modelOfOrderStatus = JSFactory::getModel('Orderstatus');
        $ordersStatusesWithSupportGenerateInvoice = $modelOfOrderStatus->getAllWitchSupport('is_generate_invoice');
        $orderStatusesWithSupportGenerateDeliveryNote = $modelOfOrderStatus->getAllWitchSupport('is_generate_delivery_note');
        $isSupportGenerateInvoice = array_key_exists($order->order_status, $ordersStatusesWithSupportGenerateInvoice);
        $isSupportGenerateDeliveryNote = array_key_exists($order->order_status, $orderStatusesWithSupportGenerateDeliveryNote);

        if ($isSupportGenerateDeliveryNote || $isSupportGenerateInvoice) {
            $file_generete_pdf_order = $jshopConfig->file_generete_pdf_order;
            include_once($file_generete_pdf_order);
            $order->pdf_file = generatePdf($order, $isSupportGenerateDeliveryNote, $isSupportGenerateInvoice);
            $order->insertPDF();
        }

        JSFactory::loadLanguageFile($order->getLang());
        JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_jshopping/models');
        $_checkout = JSFactory::getModel('checkout', 'jshop');
        if ($jshopConfig->send_order_email) {
            $_checkout->sendOrderEmail($order_id, 1);
        }
    }

    public function getPaymentDefault() 
    {
        $config = JSFactory::getConfig();
        return intval($config->offer_and_order_payment);
    }

    public function getShippingDefault() 
    {        
        $config = JSFactory::getConfig();
        return intval($config->offer_and_order_shipping);
    }

    public function getPaymentParams() 
    {
        return '';
    }

    public function getShippingParams() 
    {
        return '';
    }
	
	public function addItemRow(){
		$id = JFactory::getApplication()->input->getInt("id");
        $config = JSFactory::getConfig();
		$view = $this->getView('offer_and_order', 'html');
        $view->setLayout('item_row');           
	    $view->set('id', $id);
	    $view->set('config', $config);
		$row = $view->loadTemplate();
		print_r($row);die;
	}

}