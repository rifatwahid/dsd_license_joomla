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

class JshoppingControllerConditions extends JControllerLegacy{
	
	protected $canDo;

    function __construct( $config = array() ){
        parent::__construct( $config );
        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("conditions");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("other",$this->canDo);
    }
    
    function display($cachable = false, $urlparams = false){		
		$mainframe = JFactory::getApplication();
        $context = 'jshoping.list.admin.conditions';
        $filter_order = $mainframe->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
        
		$_conditions = JSFactory::getModel("conditionsadmin");
		$rows = $_conditions->getAllConditions(0, $filter_order, $filter_order_Dir);
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $context = "jshoping.list.admin.conditions";
        
        $condition_id_back = JFactory::getApplication()->input->getInt("condition_id_back");      
                        
		$view = $this->getView("conditions", 'html');
        $view->setLayout("list");		
		$view->set('canDo', $canDo ?? '');
		$view->set('rows', $rows);
        $view->set('condition_id_back', $condition_id_back);
		
		$view->displayList(); 
	}
    
    function edit(){
        $jshopConfig = JSFactory::getConfig();
		$_conditions = JSFactory::getTable('conditions');
        $condition_id = JFactory::getApplication()->input->getInt('condition_id');
        $_conditions->load($condition_id);
        $condition_id_back = JFactory::getApplication()->input->getInt("condition_id_back");   
		$types = array('price'=>JText::_('COM_SMARTSHOP_PRICE'), 'weight'=>JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT'),'width'=>JText::_('COM_SMARTSHOP_WIDTH'), 'height'=>JText::_('COM_SMARTSHOP_HEIGHT'), 'depth'=>JText::_('COM_SMARTSHOP_DEPTH'),'min_side'=>JText::_('COM_SMARTSHOP_SHORTEST_SIDE'), 'max_side'=>JText::_('COM_SMARTSHOP_LONGEST_SIDE'), 'median_side'=>JText::_('COM_SMARTSHOP_MEDIAN_SIDE'), 'perimeter'=>JText::_('COM_SMARTSHOP_PERIMETER').' ($max_side+$median_side+$min_side)', 'area'=>JText::_('COM_SMARTSHOP_AREA').' ($max_side*$median_side)', 'volume'=>JText::_('COM_SMARTSHOP_VOLUME').' ($max_side*$median_side*$min_side)');
		
		
		$_freeAttributes = JTable::getInstance('freeattribut', 'jshop');
		$conditions = JSFactory::getModel("conditionsadmin");
		
		$nullFreeAttr = array();
		$nullFreeAttr[0] = new stdClass();
		$nullFreeAttr[0]->id = 0;
		$nullFreeAttr[0]->name = ' - - - - ';
		$freeAttributes = array_merge($nullFreeAttr, $_freeAttributes->getAll());
		$data_options = $conditions->getOptions();
				
		$view=$this->getView("conditions", 'html');
        $view->setLayout("condition_edit");
        $view->set('types', $types);
        $view->set('condition', $_conditions);
        $view->set('freeAttributes', $freeAttributes);
        $view->set('data_options', $data_options);
        $view->displayEdit();		
    }
	
	function save(){ 	
		$conditions = JSFactory::getModel('conditionsadmin');
		$_conditions = JSFactory::getTable('conditions');
    	$condition_id = JFactory::getApplication()->input->getInt("condition_id");
        $condition_id_back = JFactory::getApplication()->input->getInt("condition_id_back");
		
		$post = $this->input->post->getArray();         
		$post['formula'] = '';
		$post['rules'] = $_POST['conditions_edit'];
		if($post['rules']){
			$post['formula'] = $conditions->getRules($post['rules']);
		}
		$conditions->saveConditionsOptions($post);
		$_conditions->condition_id = $conditions->save($post);
		if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=conditions&task=edit&condition_id=".$_conditions->condition_id."&condition_id_back=".$condition_id_back); 
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=conditions&condition_id_back=".$condition_id_back);
        }
	}
	
	function getConditionData(){
		$id = JFactory::getApplication()->input->getInt("condition_id");		
		$conditions = JSFactory::getTable('conditions');
        $conditions->load($id);
		print $conditions->rules; die;
		
	}

	function remove(){
		$cid = JFactory::getApplication()->input->getVar("cid");
		$conditions = JSFactory::getModel("conditionsadmin");
        
		$text = '';
		foreach ($cid as $key => $value) {
			if ($conditions->deleteShippingCondition($value)) {
				$text .= JText::_('COM_SMARTSHOP_CONDITION_DELETED');
				$conditions->deleteShippingConditionPrice($value);
			} else {
				$text .= JText::_('COM_SMARTSHOP_ERROR_CONDITION_DELETED');
			}
		}
        		
		$this->setRedirect("index.php?option=com_jshopping&controller=conditions", $text);
	}
   
	public function conditions_options(){		
		$_freeAttributes = JTable::getInstance('freeattribut', 'jshop');
		$conditions = JSFactory::getModel("conditionsadmin");
		
		$nullFreeAttr = array();
		$nullFreeAttr[0] = new stdClass();
		$nullFreeAttr[0]->id = 0;
		$nullFreeAttr[0]->name = ' - - - - ';
		$freeAttributes = array_merge($nullFreeAttr, $_freeAttributes->getAll());
		$data_options = $conditions->getOptions();
		
		$view=$this->getView("conditions", 'html');
        $view->setLayout("condition_options");
        $view->set('freeAttributes', $freeAttributes);
        $view->set('data_options', $data_options);
        $view->displayOptions();
	}
	
	public function saveConditionsOptions(){
		$conditions = JSFactory::getModel("conditionsadmin");
		$post = $this->input->post->getArray();         
		
		$conditions->saveConditionsOptions($post);
		$this->setRedirect("index.php?option=com_jshopping&controller=conditions");		
	}
	public function back(){
		$this->setRedirect("index.php?option=com_jshopping&controller=shippingsprices");
	}
}
?>