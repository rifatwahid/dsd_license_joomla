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

class JshoppingViewAddonkey extends JViewLegacy
{
    function display($tpl=null){
        
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_ENTER_LICENSE_KEY'), 'generic.png' ); 
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();
        
        parent::display($tpl);
	}
}
?>