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

class JshoppingViewProduct_field_groups extends JViewLegacy
{
    function displayList($tpl=null){
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_PRODUCT_EXTRA_FIELD_GROUPS'), 'generic.png' );
        JToolBarHelper::custom("back", 'arrow-left', 'arrow-left', JText::_('COM_SMARTSHOP_BACK_TO_PRODUCT_EXTRA_FIELDS'), false);
        JToolBarHelper::addNew();
        JToolBarHelper::deleteList();        
        parent::display($tpl);
	}
    function displayEdit($tpl=null){
        JToolBarHelper::title( $temp = ($this->row->id) ? (JText::_('COM_SMARTSHOP_EDIT').' / '.$this->row->{JSFactory::getLang()->get('name')}) : (JText::_('COM_SMARTSHOP_NEW')), 'generic.png' );
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::apply();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
}
?>