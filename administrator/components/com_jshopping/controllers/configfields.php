<?php
/**
* @version      4.9.0 31.01.2015
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

use Joomla\Utilities\ArrayHelper;

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerConfigfields extends JControllerAdmin{
	
	protected $canDo;
    
    function __construct( $config = array() ){
        parent::__construct($config);
        $this->registerTask('apply', 'save');
        checkAccessController("config");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        addSubmenu("config",$this->canDo);  
	
   }

    function edit(){
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
    
    public function save(){
        $_config_fields = JSFactory::getTable('config_fields');
        $post = $this->input->post->getArray();

        if(!$_config_fields->bind($post)) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=configfields&task=edit&field_id=".$post['id']);
            return 0;
        }

        if (!$_config_fields->store()) {
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=configfields&task=edit&field_id=".$post['id']);
            return 0;
        }
        if ($this->getTask()=='apply'){
            $this->setRedirect("index.php?option=com_jshopping&controller=configfields&task=edit&field_id=".$_config_fields->id);
        }else{
            $this->setRedirect("index.php?option=com_jshopping&controller=config&task=fieldregister");
        }
    }

    public function cancel(){
        $this->setRedirect("index.php?option=com_jshopping&controller=config&task=fieldregister");
    }
    public function save_order()
    {	$input = JFactory::getApplication()->input;
		$post_array = $input->getArray($_POST);
		$ids = $post_array['field_id'];
		$config_fields = JSFactory::getModel("config_fields");
		$return = $config_fields->saveOrder($ids);
	    $this->setRedirect("index.php?option=com_jshopping&controller=config&task=fieldregister");
       die;

    }
   	
}