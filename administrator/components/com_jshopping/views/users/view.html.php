<?php
/**
* @version      4.6.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

use Joomla\CMS\Language\Text;

class JshoppingViewUsers extends JViewLegacy
{
	protected $canDo;
	
    function displayList($tpl=null){        
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.users')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_USER_LIST'), 'generic.png' );
		if ($this->canDo->get('core.delete') AND $this->canDo->get('smartshop.users.delete')){		
			JToolBarHelper::deleteList();        
		}
        parent::display($tpl);
	}
    function displayEdit($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.users')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        $title =  JText::_('COM_SMARTSHOP_USERS')." / ";
        if ($this->user->user_id){
            $title.=$this->user->u_name;
        }else{
            $title.= JText::_('COM_SMARTSHOP_NEW');
        }
        JToolBarHelper::title($title, 'generic.png');
		if (($this->canDo->get('core.create') OR $this->canDo->get('core.edit')) AND ($this->canDo->get('smartshop.users.create') OR $this->canDo->get('smartshop.users.create'))){
			JToolBarHelper::save();
			JToolBarHelper::apply();
		}
        JToolBarHelper::cancel();
        parent::display($tpl);
	}
	
	public function displayEditAddress($tpl = null): void
	{
		JToolBarHelper::save('editAddressSave');
		JToolBarHelper::title(Text::_('COM_SMARTSHOP_EDIT_ADDRESS'), 'generic.png');
		JToolBarHelper::cancel('edit');
		
		parent::display($tpl);
	}

	public function displayAddNewAddress($tpl = null): void
	{
		JToolBarHelper::save('newAddressSave');
		JToolBarHelper::title(Text::_('COM_SMARTSHOP_ADD_NEW_ADDRESS'), 'generic.png');
		JToolBarHelper::cancel('edit');
		
		parent::display($tpl);
	}
}
?>