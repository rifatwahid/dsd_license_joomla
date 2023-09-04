<?php
/**
* @version      4.7.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class JshoppingViewOrders extends JViewLegacy{
	
	protected $canDo;
	
    function displayList($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.orders')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_ORDER_LIST'), 'generic.png');

		if ($this->canDo->get('smartshop.orders.changestatus')){		
			JToolBarHelper::custom('change_status', 'pencil-2', '', JText::_('COM_SMARTSHOP_STATUS_CHANGE'));
		}
		if ($this->canDo->get('core.delete') AND $this->canDo->get('smartshop.orders.delete')){		
			JToolBarHelper::deleteList();
		}
        parent::display($tpl);
	}
    function displayEdit($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.orders')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title($this->order->order_number, 'generic.png');
		if (($this->canDo->get('core.create') OR $this->canDo->get('core.edit')) AND ($this->canDo->get('smartshop.orders.create') OR $this->canDo->get('smartshop.orders.create'))){
			JToolBarHelper::save();
		}
        JToolBarHelper::cancel();
        parent::display($tpl);
    }
    function displayShow($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.orders')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title($this->order->order_number, 'generic.png');
        JToolBarHelper::back();
		if ($this->canDo->get('smartshop.orders.sendmail')){
			JToolBarHelper::custom('send', 'mail', 'mail', JText::_('COM_SMARTSHOP_SEND_MAIL'), false);
		}
        parent::display($tpl);
    }
    function displayTrx($tpl = null){		
        $this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.orders')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title($this->order->order_number."/ ".JText::_('COM_SMARTSHOP_TRANSACTION'), 'generic.png');
        JToolBarHelper::back();
        parent::display($tpl);
    }
}
?>
