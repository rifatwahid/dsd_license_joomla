<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerFormula_calculation extends JControllerLegacy{
	
	protected $canDo;
	
	function __construct($config = array()){
        parent::__construct( $config );             
        checkAccessController("formula_calculation");      
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    
    function display($cachable = false, $urlparams = false){    
        $view=$this->getView("formula_calculation", 'html');
        $view->setLayout("edit");
		$view->set('canDo', $canDo ?? '');
        $view->display();
    }
	
	function save(){
        $this->saveConfig('save');
    }
    
    function apply(){
        $this->saveConfig();
    }
    
    private function saveConfig($task = 'apply'){
        $post = JFactory::getApplication()->input->post->getArray();
        $_freeattrcalcprice = JModelLegacy::getInstance("FreeAttrCalcPrice", 'JshoppingModel');
        $params = $post['params'];
        if (!is_array($params)) $params = array();
		
        $_freeattrcalcprice->saveParams($params);
		
        if ($task == 'apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=formula_calculation");
        } else {
            $this->setRedirect("index.php?option=com_jshopping&controller=other");
        }
    }
	function cancel(){
		$this->setRedirect("index.php?option=com_jshopping&controller=other");
	}
}
?>