<?php
/**
* @version      2.7.3 20.01.2011
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerLicenseKeyAddon extends JControllerLegacy{
	
	protected $canDo;    
    
    function __construct( $config = array() ){
        parent::__construct( $config );

        $this->registerTask( 'add',   'edit' );
        $this->registerTask( 'apply', 'save' );
        checkAccessController("licensekeyaddon");
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);        
        addSubmenu("other",$this->canDo);        
    }

	function display($cachable = false, $urlparams = false){
        $alias = JFactory::getApplication()->input->getVar("alias");
		$back = JFactory::getApplication()->input->getVar("back");
		$addon = JSFactory::getTable('addon', 'jshop');
		$addon->loadAlias($alias);		

		$view = $this->getView("addonkey", 'html');
        $view->set('row', $addon);
        $view->set('back', $back);
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayLicenseKeyAddons', array(&$view));
		$view->display();
	}
	
	function save() {
        $addon = JSFactory::getTable('addon', 'jshop');
        $post = $this->input->post->getArray();
		if (!$addon->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
			$this->setRedirect("index.php?option=com_jshopping");
			return 0;
		}
	
		if (!$addon->store()) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
			$this->setRedirect("index.php?option=com_jshopping");
			return 0;
		}
		
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onAfterSaveLicenseKeyAddons', array(&$addon));
		
        $this->setRedirect(base64_decode($post['back']));
	}
    
    function cancel(){
        $post = $this->input->post->getArray();
        $this->setRedirect(base64_decode($post['back']));
    }
}
?>