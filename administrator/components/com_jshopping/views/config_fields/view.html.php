<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class JshoppingViewConfig_fields extends JViewLegacy
{
	protected $canDo;

     function displayEdit($tpl=null){
		 //print_r($this->canDo);die;
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.configuration.registration')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( $temp=(isset($this->edit) && $this->edit) ? (JText::_('COM_SMARTSHOP_EDIT_CONFIG_FIELDS')) : (JText::_('COM_SMARTSHOP_NEW_COUPON')), 'generic.png' ); 
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
        JToolBarHelper::cancel();
        parent::display($tpl);
    }
}
?>