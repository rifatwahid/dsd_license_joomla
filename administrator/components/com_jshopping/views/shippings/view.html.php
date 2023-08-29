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

class JshoppingViewShippings extends JViewLegacy{
	
	protected $canDo;
    
    function displayList($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.shippings'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_LIST_SHIPPINGS'), 'generic.png' ); 
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
        parent::display($tpl);
	}
    
    function displayEdit($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.shippings'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( $temp = ($this->edit) ? (JText::_('COM_SMARTSHOP_EDIT_SHIPPING').' / '.$this->shipping->{JSFactory::getLang()->get('name')}) : (JText::_('COM_SMARTSHOP_NEW_SHIPPING')), 'generic.png' ); 
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
}
?>