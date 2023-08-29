<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class JshoppingViewLangpackedit extends JViewLegacy
{
    function display($tpl = null){
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_LANG_PACK_EDITING'), 'generic.png' ); 
        JToolBarHelper::apply();      
        parent::display($tpl);
	}
}
?>