<?php
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
class JshoppingViewEmail_hub extends JViewLegacy
{
	function displayEmailHub($tpl=null){
		JToolBarHelper::title(JText::_('COM_SMARTSHOP_EMAIL_HUB'), 'generic.png' );				
		parent::display($tpl);	
	}    
	
	function displayTemplateCreator($tpl=null){
		JToolBarHelper::title(JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR'), 'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::spacer();		
		parent::display($tpl);	
	}  
	
	function displayTemplateCreatorNew($tpl=null){				
		JToolBarHelper::title(JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR').' / '.JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_NEW'), 'generic.png' );
		JToolBarHelper::apply();				
		parent::display($tpl);	
	}   
}
?>