<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class JshoppingViewPanel extends JViewLegacy{
	
	protected $canDo;
	
    function displayHome($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');		
		/// ALC
		if (JFactory::getUser()->authorise('core.admin', 'com_jshopping')){
			JToolBarHelper::preferences('com_jshopping');
		}
		///
        JToolBarHelper::title( JText::_("smartSHOP"), 'generic.png' );
        parent::display($tpl);
	}
    function displayInfo($tpl=null){		
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.options')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_ABOUT_AS'), 'generic.png' );
        parent::display($tpl);
    }
    function displayConfig($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.options')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_CONFIG'), 'generic.png' );
        if (JFactory::getUser()->authorise('core.admin')){
            JToolBarHelper::preferences('com_jshopping');
        }
        parent::display($tpl);
    }
    function displayOptions($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.options')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_OTHER_ELEMENTS'), 'generic.png' );
        parent::display($tpl);
    }
}
?>