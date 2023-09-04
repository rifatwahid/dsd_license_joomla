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

class JshoppingViewLanguages_list extends JViewLegacy
{
    function display($tpl=null){
        
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_LIST_LANGUAGE'), 'generic.png' ); 
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        parent::display($tpl);
	}
}
?>