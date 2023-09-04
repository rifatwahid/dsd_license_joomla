<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class JshoppingViewLicense extends JViewLegacy
{
	protected $canDo;
    public function __construct($config = [])
    {
        parent::__construct($config);        
		JToolBarHelper::title(JText::_('COM_SMARTSHOP_PANEL_LICENSE'), 'generic.png');
    }

    public function displayList($tpl = null) : void
    {
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_PANEL_LICENSE'), 'generic.png');
        parent::display($tpl);
	}

}
?>