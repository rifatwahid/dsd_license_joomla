<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class JshoppingViewStates extends JViewLegacy
{
	protected $canDo;
    public function __construct($config = [])
    {
        parent::__construct($config);        
    }

    public function displayList($tpl = null) : void
    {
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_STATES'), 'generic.png');
        JToolBarHelper::addNew();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::deleteList();
        parent::display($tpl);
	}
    
    public function displayEdit($tpl = null) : void
    {
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        JToolBarHelper::title($temp = ($this->edit) ? (JText::_('COM_SMARTSHOP_STATES_EDIT')) : (JText::_('COM_SMARTSHOP_STATES_NEW')), 'generic.png'); 
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::apply();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
}
?>