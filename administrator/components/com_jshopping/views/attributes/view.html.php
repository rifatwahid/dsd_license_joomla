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

class JshoppingViewAttributes extends JViewLegacy{

	protected $canDo;

    function displayList($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.attributes'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_LIST_ATTRIBUTES'), 'generic.png' ); 
		if ($this->canDo->get('core.create')){
			JToolBarHelper::addNew();			
			JToolBarHelper::custom('copy', 'copy', 'copy_f2.png', JText::_('JLIB_HTML_BATCH_COPY'));   
		}
		if ($this->canDo->get('core.delete')){
			JToolBarHelper::deleteList();        
		}
        JToolBarHelper::spacer();        
		if ($this->canDo->get('core.options')){
			JToolBarHelper::custom("addgroup", "folder", "folder", JText::_('COM_SMARTSHOP_GROUP'), false);
		}
        parent::display($tpl);	
    }

    function displayEdit($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.attributes'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( $temp = ($this->attribut->attr_id) ? (JText::_('COM_SMARTSHOP_EDIT_ATTRIBUT').' / '.$this->attribut->{JSFactory::getLang()->get('name')}) : (JText::_('COM_SMARTSHOP_NEW_ATTRIBUT')), 'generic.png' );
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