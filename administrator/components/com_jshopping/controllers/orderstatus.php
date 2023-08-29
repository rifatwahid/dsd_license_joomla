<?php
/**
* @version      2.9.4 31.07.2010
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerOrderStatus extends JControllerLegacy{
	
	protected $canDo;    
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("orderstatus");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');        
        addSubmenu("other",$this->canDo);
    }

	function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.orderstatus";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "status_id", 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd');
        
		$_orders = JSFactory::getModel("orders");
		$rows = $_orders->getAllOrderStatus($filter_order, $filter_order_Dir);

		$view=$this->getView("orderstatus", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);
        $view->set('filter_order', $filter_order);
        $view->set('filter_order_Dir', $filter_order_Dir);        
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayOrderStatus', array(&$view));
		$view->displayList();
	}
	
	function edit(){

		$status_id = JFactory::getApplication()->input->getInt("status_id");
		$order_status = JSFactory::getTable('orderStatus', 'jshop');
		$order_status->load($status_id);
		$edit = ($status_id)?($edit = 1):($edit = 0);
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;
			
		include_once(JPATH_COMPONENT_SITE . '/views/qcheckout/view.html.php');
		$view2 = new JshoppingViewQcheckout([
            "template_path"=>viewOverride('emails',"statusorder.php")
        ]);
       $view2->setLayout("statusorder");
       $message = array();
        if (!isset($order_status->email_text) || empty($order_status->email_text)){
            foreach($languages as $lang){ 
				$language = JFactory::getLanguage();
                $order = new stdClass();
                $vendorinfo = new stdClass();
                
				$language->load('com_jshopping', JPATH_SITE, 'de-DE', true);
				$order->f_name = '{first_name}';
				$order->l_name = '{last_name}';
				$order->order_number = '{order_number}';
				$order_status1 = '{order_status}';
				$order_detail = '{order_detail_url}';
				$vendorinfo->company_name = '{company}';
				$vendorinfo->adress = '{address}';
				$vendorinfo->zip = '{zip}';
				$vendorinfo->city = '{city}';
				$vendorinfo->country = '{country}';
				$vendorinfo->phone = '{phone}';
				$vendorinfo->fax = '{fax}';
				$comment = '{comment}';
				$view2->set('order', $order);
				$view2->set('order_status', $order_status1);
				$view2->set('order_detail', $order_detail);
				$view2->set('vendorinfo', $vendorinfo);
				$view2->set('comment', $comment);
				$view2->set('language', $lang->language);
				$message[$lang->language] = $view2->loadTemplate();
            }
        } else {
            $text = unserialize($order_status->email_text);
            foreach($languages as $lang){
                $message[$lang->language] = $text['text_'.$lang->language];
            }
			
        }
        JFilterOutput::objectHTMLSafe( $order_status, ENT_QUOTES);
		$view=$this->getView("orderstatus", 'html');
        $view->setLayout("edit");		
		$view->set('canDo', $canDo ?? '');
		$view->set('message', $message);
        $view->set('order_status', $order_status);        
        $view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('etemplatevar', '');
        $view->set('multilang', $multilang);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditOrderStatus', array(&$view));
		$view->displayEdit();
	}
	
	function save() {
	    $mainframe = JFactory::getApplication();
		$status_id = JFactory::getApplication()->input->getInt("status_id");
		$order_status = JSFactory::getTable('orderStatus', 'jshop');
        $post = $this->input->post->getArray();
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveOrderStatus', array(&$post) );        
		
         $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        
        $data = array();
        foreach ($languages as $lang){
            $data['email_text']['text_'.$lang->language] = $_POST['text_'.$lang->language];
        }
        $post['email_text'] = serialize($data['email_text']);
		if (!$order_status->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=orderstatus");
			return 0;
		}
		
	
		if (!$order_status->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=orderstatus");
			return 0;
		}
        
        $dispatcher->triggerEvent( 'onAfterSaveOrderStatus', array(&$order_status) );
		
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=orderstatus&task=edit&status_id=".$order_status->status_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=orderstatus");
        }
		
	}
	
	function remove() {		
		$text = '';		
		$cid = JFactory::getApplication()->input->getVar("cid");        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveOrderStatus', array(&$cid));
		$_orderstatus = JSFactory::getModel('orderstatus');
		$text.=$_orderstatus->deleteOrderstatus($cid);
        $dispatcher->triggerEvent( 'onAfterRemoveOrderStatus', array(&$cid));        
		$this->setRedirect("index.php?option=com_jshopping&controller=orderstatus", $text);
	} 
    
	
    private function getConstantTranslate($fileArray, $constant){
        $constantMatch = preg_grep('/(define[ ]*[(])([ ]*)(["\'])('.$constant.')(["\'][ ]*,)/', $fileArray);
        
        if (is_array($constantMatch) && count($constantMatch)){
            $constantRow = array_shift($constantMatch);
            if (!empty($constantRow)){
                $splitConstant = preg_split('/("[ ]*,[ ]*"|\'[ ]*,[ ]*\'|"[ ]*,[ ]*\'|\'[ ]*,[ ]*"|"[ ]*[)]|\'[ ]*[)])/', $constantRow);
                
                if (is_array($splitConstant) && count($splitConstant) > 2){
                    return $splitConstant[1];
                }
            }
        }
		
		if (defined($constant)){
            return constant($constant);
        }else{
            return $constant;
        }
    } 
    
}

?>