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

class JshoppingViewProduct_fields extends JViewLegacy{
	
	protected $canDo;
	
    function displayList($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.productfields'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_PRODUCT_EXTRA_FIELDS'), 'generic.png' ); 
		if ($this->canDo->get('core.create')){
			JToolBarHelper::addNew();
		}
		if ($this->canDo->get('core.delete')){
			JToolBarHelper::deleteList();
		}
        
		if ($this->canDo->get('core.create')){
			JToolBarHelper::custom("addgroup", "folder", "folder", JText::_('COM_SMARTSHOP_GROUP'), false);        
		}

		JToolBarHelper::spacer();
		JToolbarHelper::custom('custom_options', 'options.png', 'options_f2.png', JText::_('COM_SMARTSHOP_CHARACTERISTICS_SETTINGS'), false, false);

        parent::display($tpl);
	}
    function displayEdit($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.productfields'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( $temp = ($this->row->id) ? (JText::_('COM_SMARTSHOP_EDIT').' / '.$this->row->{JSFactory::getLang()->get('name')}) : (JText::_('COM_SMARTSHOP_NEW')), 'generic.png' );
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
	function displayConfigurations($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.productfields'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_CHARACTERISTICS_SETTINGS'), 'generic.png' ); 
        if ($this->canDo->get('core.options')){
			JToolBarHelper::apply('configurations_apply');
		}
        JToolBarHelper::cancel();     
        parent::display($tpl);
	}
}
?>