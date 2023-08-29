<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class JshoppingViewProduction_calendar extends JViewLegacy
{
    protected $access;

    public function display($tpl = null)
    {
        $this->access = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        if ((!$this->access->get('smartshop.options'))OR(!$this->access->get('smartshop.options.coupons'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));

        JToolBarHelper::title( JText::_('COM_SMARTSHOP_PRODUCTION_CALENDAR'), 'generic.png'); 

        if ($this->access->get('core.edit') OR $this->access->get('core.create')){
            JToolBarHelper::save();
            JToolBarHelper::apply();
        }

        JToolBarHelper::cancel();

        if ($this->access->get('core.options')){
            JToolbarHelper::custom('custom_options', 'options.png', 'options_f2.png', JText::_('COM_SMARTSHOP_PRODUCTION_CALENDAR_OPTIONS'), false, false);
        }

        parent::display($tpl);
    }

    public function displayModal($tpl = null) 
    {
        $this->access = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        if ((!$this->access->get('smartshop.options'))OR(!$this->access->get('smartshop.options.coupons'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));

        parent::display($tpl);
    }

    public function displayConfigurations($tpl=null){        
        $this->access = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        if ((!$this->access->get('smartshop.options'))OR(!$this->access->get('smartshop.options.coupons'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));

        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_CONFIGURATIONS_COUPON'), 'generic.png' ); 

        if ($this->access->get('core.edit') OR $this->access->get('core.create')){
            JToolBarHelper::apply('configurations_apply');
        }

        JToolBarHelper::cancel();

        parent::display($tpl);
	}
}