<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class JshoppingViewOffer_and_order extends JViewLegacy {
	
	protected $canDo;

    public function displayList($tpl = null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.offer'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_LIST'), 'generic.png');
		if ($this->canDo->get('core.delete')){
			JToolBarHelper::deleteList();
		}
		if ($this->canDo->get('core.options')){
			JToolbarHelper::custom('offer_options', 'options.png', 'options_f2.png', JText::_('COM_SMARTSHOP_OPTIONS'), false, false);
		}
        parent::display($tpl);
    }

    public function displayEdit($tpl = null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.offer'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title($this->order->order_number, 'generic.png');
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
		}
        JToolBarHelper::cancel();
        parent::display($tpl);
    }

    public function displayCreate($tpl = null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.offer'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title("Create order", 'generic.png');
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save("saveorder");
		}
        JToolBarHelper::cancel();
        parent::display($tpl);
    }

    public function displayShow($tpl = null){		
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.offer'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title($this->order->order_number, 'generic.png');
        JToolBarHelper::back();
        parent::display($tpl);
    }

    public function displayOptions($tpl = null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id);
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.offer'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_OPTIONS'), 'generic.png');
		if ($this->canDo->get('core.options')){
			JToolBarHelper::save('saveOfferOptions');
		}
        parent::display($tpl);
    }

}