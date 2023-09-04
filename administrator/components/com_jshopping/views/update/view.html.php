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

class JshoppingViewUpdate extends JViewLegacy
{
    function display($tpl=null){
        
        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_INSTALL_AND_UPDATE'), 'generic.png' );         
        
        parent::display($tpl);
	}
}
?>