<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerUpload extends JControllerLegacy{
	
	protected $canDo;
	
    public function __construct($config = [])    {
        parent::__construct($config);             
        checkAccessController('upload');
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu('other',$this->canDo);
    }
	
    public function display($cachable = false, $urlparams = false)
    {
		$_upload = JSFactory::getModel('upload', 'JshoppingModel');
        $params = $_upload->getParams();
		$order = JModelLegacy::getInstance("orders", 'JshoppingModel');
		$statusList = $order->getAllOrderStatus();
        $view = $this->getView('upload', 'html');

		$view->set('params', $params);
        $view->setLayout('edit');
		$view->set("statusList", $statusList);
		$view->set("canDo", $this->canDo);
        $view->display();
    }

    public function save()
    {
        $this->saveConfig('save');
    }
    
    public function apply()
    {
        $this->saveConfig();
    }
    
    private function saveConfig($task = 'apply')
    {
        $postParams = JFactory::getApplication()->input->post->getArray();
        $_upload = JSFactory::getModel('upload', 'JshoppingModel');
        $_upload->saveParams($postParams);
		
        if ($task == 'apply') {
            $this->setRedirect('index.php?option=com_jshopping&controller=upload');
        } else {
            $this->cancel();
        }
    }

    public function cancel()
    {
		$this->setRedirect('index.php?option=com_jshopping&controller=other');
	}
}