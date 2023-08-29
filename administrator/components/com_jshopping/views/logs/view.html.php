<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class JshoppingViewLogs extends JViewLegacy{
    
    function displayList($tpl = null){        
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_LOGS'), 'generic.png');
        parent::display($tpl);
	}
    
    function displayEdit($tpl = null){
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_LOGS')." / ".$this->filename, 'generic.png');
        JToolBarHelper::back();
        parent::display($tpl);
    }
}
?>