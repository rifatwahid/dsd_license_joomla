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

class JshoppingControllerReturnStatus extends JControllerLegacy{
	
	protected $canDo;    
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("returnstatus");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');        
        addSubmenu("other",$this->canDo);
    }

	function display($cachable = false, $urlparams = false){
        $mainframe = JFactory::getApplication();
        $context = "jshoping.list.admin.returnstatus";
        $filter_order = $mainframe->getUserStateFromRequest($context.'filter_order', 'filter_order', "status_id", 'cmd') ?? '';
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', "asc", 'cmd') ?? '';
        
		$_orders = JSFactory::getModel("orders");
		$rows = $_orders->getAllReturnStatus($filter_order, $filter_order_Dir);

		$view=$this->getView("returnstatus", 'html');
        $view->setLayout("list");
		$view->set('canDo', $canDo ?? '');
        $view->set('rows', $rows);
        $view->set('filter_order', $filter_order ?? '');
        $view->set('filter_order_Dir', $filter_order_Dir ?? '');        
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayReturnStatus', array(&$view));
		$view->displayList();
	}
	
	function edit(){
		$status_id = JFactory::getApplication()->input->getInt("status_id");
		$return_status = JSFactory::getTable('returnStatus', 'jshop');
		$return_status->load($status_id);
		$edit = ($status_id)?($edit = 1):($edit = 0);
        $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        $multilang = count($languages)>1;			
		
        JFilterOutput::objectHTMLSafe( $return_status, ENT_QUOTES);
		$view=$this->getView("returnstatus", 'html');
        $view->setLayout("edit");		
		$view->set('canDo', $canDo ?? '');
        $view->set('return_status', $return_status);        
        $view->set('edit', $edit);
        $view->set('languages', $languages);
        $view->set('etemplatevar', '');
        $view->set('multilang', $multilang);
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeEditReturnStatus', array(&$view));
		$view->displayEdit();
	}
	
	function save() {
	    $mainframe = JFactory::getApplication();
		$status_id = JFactory::getApplication()->input->getInt("status_id");
		$return_status = JSFactory::getTable('returnStatus', 'jshop');
        $post = $this->input->post->getArray();
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeSaveReturnStatus', array(&$post) );        
		
         $_lang = \JSFactory::getModel("languages");
        $languages = $_lang->getAllLanguages(1);
        
        if (!$return_status->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=returnstatus");
			return 0;
		}
		
	
		if (!$return_status->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping&controller=returnstatus");
			return 0;
		}
        
        $dispatcher->triggerEvent( 'onAfterSaveReturnStatus', array(&$order_status) );
		
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=returnstatus&task=edit&status_id=".$return_status->status_id); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=returnstatus");
        }
		
	}
	
	function remove() {		
		$text = '';		
		$cid = JFactory::getApplication()->input->getVar("cid");        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent( 'onBeforeRemoveReturnStatus', array(&$cid));
		$_returnstatus = JSFactory::getModel('returnstatus');
		$text.=$_returnstatus->deleteReturnstatus($cid);
        $dispatcher->triggerEvent( 'onAfterRemoveReturnStatus', array(&$cid));        
		$this->setRedirect("index.php?option=com_jshopping&controller=returnstatus", $text);
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
	
	public function return_options()
    {
        $dispatcher = \JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
        $_returnstatus = JSFactory::getModel('returnstatus'); 
        $params = $_returnstatus->getParams();
		$order = JModelLegacy::getInstance("orders", 'JshoppingModel');
		$statusList = $order->getAllOrderStatus();
        
        $view = $this->getView('returnstatus', 'html');
        $view->setLayout('return_options');
        $view->set('canDo', $this->canDo);
        $view->set('statusList', $statusList);
        $view->set('params', $params);
		$view->displayReturnOptions();
    }	
	
	function saveReturnOptions(){
		
		$post = $this->input->post->getArray();
        $_returnstatus = JSFactory::getModel('returnstatus');
        $_returnstatus->saveConfigurations($post['order_status_for_return']);

        $this->setRedirect('index.php?option=com_jshopping&controller=returnstatus', JText::_('COM_SMARTSHOP_CONFIG_SUCCESS'));
	}
    
}

?>