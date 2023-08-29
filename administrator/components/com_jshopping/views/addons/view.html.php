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

class JshoppingViewAddons extends JViewLegacy{
	
	protected $canDo;
	
    function displayList($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.addons'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_ADDONS'), 'generic.png' );
        parent::display($tpl);
	}
    
    function displayEdit($tpl = null){        
        $this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.addons'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		JToolBarHelper::title(JText::_('COM_SMARTSHOP_ADDONS')." / ".JText::_('COM_SMARTSHOP_CONFIG').' / '.$this->row->name, 'generic.png' );
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
    
    function displayInfo($tpl = null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '' );
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.addons'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_ADDONS')." / ".JText::_('COM_SMARTSHOP_DESCRIPTION').' / '.$this->row->name, 'generic.png' );
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
    
    function displayVersion($tpl = null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.addons'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_ADDONS')." / ".JText::_('COM_SMARTSHOP_VERSION').' / '.$this->row->name, 'generic.png' );
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
    
}
?>