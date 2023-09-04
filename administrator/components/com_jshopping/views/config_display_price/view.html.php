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

class JshoppingViewConfig_display_price extends JViewLegacy
{
    function displayList($tpl=null){        
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_CONFIG_DISPLAY_PRICE_LIST'), 'generic.png' );
        JToolBarHelper::custom( "back", 'back', 'back', JText::_('COM_SMARTSHOP_CONFIG'), false);
        JToolBarHelper::addNew();
        JToolBarHelper::deleteList();        
        parent::display($tpl);
	}
    function displayEdit($tpl=null){        
        JToolBarHelper::title( $temp=($this->row->id) ? (JText::_('COM_SMARTSHOP_EDIT')) : (JText::_('COM_SMARTSHOP_NEW')), 'generic.png' );
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::apply();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
}
?>