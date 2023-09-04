<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class JshoppingViewCurrencies extends JViewLegacy
{
	protected $canDo;
		
    public function displayList($tpl = null)
	{        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');

		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.currencies'))) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}

        JToolBarHelper::title(JText::_('COM_SMARTSHOP_LIST_CURRENCY'), 'generic.png'); 

		if ($this->canDo->get('core.options')) {
			JToolBarHelper::makeDefault("setdefault");
		}

		if ($this->canDo->get('core.create')) {
			JToolBarHelper::addNew();
		}

		if ($this->canDo->get('core.publish')) {
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}

		if ($this->canDo->get('core.delete')) {			
			JToolBarHelper::deleteList();        
		}

		JToolbarHelper::custom('currency_options', 'options.png', 'options_f2.png', JText::_('COM_SMARTSHOP_IE_UNICSV_SETTINGS'), false, false);
        parent::display($tpl);
	}

    public function displayEdit($tpl = null)
	{
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');

		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.currencies'))) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}

		$temp = ($this->edit) ? ((JText::_('COM_SMARTSHOP_EDIT_CURRENCY') . ' / ' . $this->currency->currency_name)) : (JText::_('COM_SMARTSHOP_NEW_CURRENCY'));

        JToolBarHelper::title($temp, 'generic.png'); 

		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')) {
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}

        JToolBarHelper::cancel();        
        parent::display($tpl);
    }

	public function displayCurrencyOptions($tpl = null)
	{
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');

		JToolBarHelper::title(JText::_('COM_SMARTSHOP_IE_UNICSV_SETTINGS'), 'generic.png'); 

		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')) {
			JToolBarHelper::save('saveCurrencyOptions');
		}

		JToolBarHelper::cancel();  
		parent::display($tpl);
	}
}
