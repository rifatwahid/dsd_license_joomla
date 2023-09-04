<?php
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
class JshoppingViewFormula_calculation extends JViewLegacy{
	
	protected $canDo;
	
	function display($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if ((!$this->canDo->get('smartshop.options'))OR(!$this->canDo->get('smartshop.options.formulacalculator'))) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
       // JToolBarHelper::title( $temp = ($this->edit) ? (JText::_('COM_SMARTSHOP_DELIVERY_TIME_EDIT').' / '.$this->deliveryTimes->{JSFactory::getLang()->get('name')}) : (JText::_('COM_SMARTSHOP_DELIVERY_TIME_NEW')), 'generic.png' ); 
	   if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
	   }
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
}
?>