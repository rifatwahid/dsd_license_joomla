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

class JshoppingViewTaxes_ext extends JViewLegacy
{
    function displayList($tpl=null){        
        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_LIST_TAXES_EXT'), 'generic.png' );
        JToolBarHelper::custom( "back", 'folder', 'folder',  JText::_('COM_SMARTSHOP_LIST_TAXES'), false);
        JToolBarHelper::addNew();
        JToolBarHelper::deleteList();        
		JToolbarHelper::custom('additional_taxes', 'options.png', 'options_f2.png', JText::_('COM_SMARTSHOP_ADDITIONAL_TAXES'), false, false);
        parent::display($tpl);
	}
    
    function displayEdit($tpl=null){
        JToolBarHelper::title( $temp=($this->tax->id) ? ( JText::_('COM_SMARTSHOP_EDIT_TAX_EXT')) : ( JText::_('COM_SMARTSHOP_NEW_TAX_EXT')), 'generic.png' ); 
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
        parent::display($tpl);
    }
	
	function displayAdditional_taxes($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.taxes'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_ADDITIONAL_TAXES_LIST'), 'generic.png' ); 
		JToolBarHelper::custom( "exttaxes", 'folder', 'folder',  JText::_('COM_SMARTSHOP_BACK_TO_LIST_TAXES_EXT'), false);	
		JToolBarHelper::addNew('add_additional_taxes');
		if ($this->canDo->get('core.delete')){		
			JToolBarHelper::deleteList(JText::_('COM_SMARTSHOP_ARE_YOU_SURE_WANT_DELETE_THIS'),'add_additional_taxes_delete');        
		}
        parent::display($tpl);
	}
	
	function displayAdditional_taxes_edit($tpl=null){
        JToolBarHelper::title( $temp=(isset($this->tax->id) ? $this->tax->id : 0) ? ( JText::_('COM_SMARTSHOP_EDIT_ADDITIONAL_TAXE')) : ( JText::_('COM_SMARTSHOP_NEW_ADDITIONAL_TAXE')), 'generic.png' ); 
		JToolbarHelper::custom('additional_taxes', 'folder', 'folder', JText::_('COM_SMARTSHOP_ADDITIONAL_TAXES_LIST_OF_ADDITIONAL_TAXES'), false, false);
        JToolBarHelper::save('add_additional_taxes_save');
        JToolBarHelper::apply('add_additional_taxes_apply');
        //JToolBarHelper::cancel(JText::_('COM_SMARTSHOP_ADDITIONAL_TAXES_LIST_OF_ADDITIONAL_TAXES'));
        parent::display($tpl);
    }
}
?>