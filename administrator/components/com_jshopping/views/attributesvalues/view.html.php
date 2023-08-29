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

class JshoppingViewAttributesvalues extends JViewLegacy
{
    public function displayList($tpl=null)
    {        
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_LIST_ATTRIBUT_VALUES'), 'generic.png' );
        JToolBarHelper::custom( "back", 'arrow-left', 'arrow-left', JText::_('COM_SMARTSHOP_RETURN_TO_ATTRIBUTES'), false);
        JToolBarHelper::addNew();
        JToolBarHelper::custom('copy', 'copy', 'copy_f2.png', JText::_('JLIB_HTML_BATCH_COPY'));
        JToolBarHelper::deleteList();        
        parent::display($tpl);
	}
    
    public function displayEdit($tpl=null)
    {
        JToolBarHelper::title( $temp = ($this->attributValue->value_id) ? (JText::_('COM_SMARTSHOP_EDIT_ATTRIBUT_VALUE').' / '.$this->attributValue->{JSFactory::getLang()->get('name')}) : (JText::_('COM_SMARTSHOP_NEW_ATTRIBUT_VALUE')), 'generic.png' ); 
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::apply();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();
        parent::display($tpl);
    }
}