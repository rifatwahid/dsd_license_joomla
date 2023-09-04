<?php
/**
* @version      4.6.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class JshoppingViewManufacturer extends JViewLegacy{
	
	protected $canDo;
	
    public function displayList($tpl = null)
    {        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.manufacturers'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_LIST_MANUFACTURERS'), 'generic.png' ); 
		if ($this->canDo->get('core.create')){
			JToolBarHelper::addNew();
		}
		if ($this->canDo->get('core.publish')){
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}
		if ($this->canDo->get('core.delete')){
			JToolBarHelper::deleteList();        
		}
		if ($this->canDo->get('core.options')){
			JToolBarHelper::spacer();
			JToolbarHelper::custom('manufacturerOptions', 'options.png', 'options_f2.png', JText::_('COM_SMARTSHOP_OPTIONS'), false, false);
		}
        parent::display($tpl);
    }
    
    public function displayEdit($tpl = null)
    {
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.manufacturers'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( $temp = ($this->edit) ? (JText::_('COM_SMARTSHOP_EDIT_MANUFACTURER').' / '.$this->manufacturer->{JSFactory::getLang()->get('name')}) : (JText::_('COM_SMARTSHOP_NEW_MANUFACTURER')), 'generic.png' ); 
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }

    public function displayOptions($tpl = null)
    {
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.manufacturers'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_MANUFACTURER_OPTIONS'), 'generic.png');
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save('saveManufacturerOptions');
		}
        JToolBarHelper::cancel();
        
        parent::display($tpl);
    }
}