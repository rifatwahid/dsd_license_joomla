<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class JshoppingViewConditions extends JViewLegacy{
	
	protected $canDo;
		
    function displayList($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		JToolBarHelper::title(  JText::_('COM_SMARTSHOP_CONDITIONS'), 'generic.png' ); 		
		 //JToolbarHelper::custom('conditions_options', 'options.png', 'options_f2.png', JText::_('COM_SMARTSHOP_OPTIONS'), false, false);
		JToolBarHelper::addNew();
		JToolBarHelper::deleteList();      
        JToolBarHelper::custom( "back", 'folder', 'folder', JText::_('COM_SMARTSHOP_LIST_SHIPPINGS'), false);   
		
        parent::display($tpl);
	}
    
    function displayEdit($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::apply();
		JToolBarHelper::spacer();		
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
	
    public function displayOptions($tpl = null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		JToolBarHelper::title(JText::_('COM_SMARTSHOP_OPTIONS'), 'generic.png');
		JToolBarHelper::save('saveConditionsOptions');
        parent::display($tpl);
    }
}
?>