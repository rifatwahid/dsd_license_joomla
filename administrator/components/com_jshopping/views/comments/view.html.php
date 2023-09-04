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

class JshoppingViewComments extends JViewLegacy{
	
	protected $canDo;
	
    function displayList($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.reviews'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_LIST_PRODUCT_REVIEWS'), 'generic.png' ); 
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
		JToolbarHelper::custom('custom_options', 'options.png', 'options_f2.png', JText::_('COM_SMARTSHOP_OPTIONS_REVIEW'), false, false);
        parent::display($tpl);
	}
    function displayEdit($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.reviews'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( (isset($this->edit) && $this->edit) ? ( JText::_('COM_SMARTSHOP_EDIT_REVIEW')) : ( JText::_('COM_SMARTSHOP_NEW_REVIEW')), 'generic.png' ); 
        if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
			JToolBarHelper::apply();
		}
        JToolBarHelper::cancel();
        parent::display($tpl);
    }
	
	function displayConfigurations($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.reviews'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_LIST_PRODUCT_REVIEWS_CONFIGURATIONS'), 'generic.png' );         
		if ($this->canDo->get('core.options')){
			JToolBarHelper::apply('configurations_apply');
		}
        JToolBarHelper::cancel();
        parent::display($tpl);
	}
}
?>