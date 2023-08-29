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

class JshoppingViewCategory extends JViewLegacy
{
	protected $canDo;
	
    function displayList($tpl=null){      		
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');				
		if (!$this->canDo->get('smartshop.categories')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        
        JToolBarHelper::title(  JText::_('COM_SMARTSHOP_TREE_CATEGORY'), 'generic.png' );
		if ($this->canDo->get('core.create') AND $this->canDo->get('smartshop.categories.create')){		
			JToolBarHelper::addNew();		
			}
		if ($this->canDo->get('core.copy') AND $this->canDo->get('smartshop.categories.create')){		
			JToolBarHelper::custom('copy', 'copy', 'copy_f2.png', JText::_('JLIB_HTML_BATCH_COPY'));
		}
		if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.categories.copy')){		
			JToolBarHelper::editList('editlist');
		}
		if ($this->canDo->get('core.publish') AND $this->canDo->get('smartshop.categories.publish')){		
			JToolBarHelper::publishList();				
			JToolBarHelper::unpublishList();
		}
        if ($this->canDo->get('core.delete') AND $this->canDo->get('smartshop.categories.delete')){		
			JToolBarHelper::deleteList();
		}
        parent::display($tpl);
	}
    function displayEdit($tpl=null){		
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');	
		if (!$this->canDo->get('smartshop.categories')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));		
        JToolBarHelper::title( ($this->category->category_id) ? ( JText::_('COM_SMARTSHOP_EDIT_CATEGORY').' / '.$this->category->{JSFactory::getLang()->get('name')}) : ( JText::_('COM_SMARTSHOP_NEW_CATEGORY')), 'generic.png' ); 
		if (($this->canDo->get('core.create') OR $this->canDo->get('core.edit')) AND ($this->canDo->get('smartshop.categories.create') OR $this->canDo->get('smartshop.categories.create'))){
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
        JToolBarHelper::cancel();        
        parent::display($tpl);
    }
    function editGroup($tpl=null){		
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');		
		if (!$this->canDo->get('smartshop.categories')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title(JText::_('COM_SMARTSHOP_EDIT_CATEGORY'), 'generic.png');
		if (($this->canDo->get('core.create') OR $this->canDo->get('core.edit')) AND ($this->canDo->get('smartshop.categories.create') OR $this->canDo->get('smartshop.categories.create'))){
			JToolBarHelper::save("savegroup");
		}
        JToolBarHelper::cancel();
        parent::display($tpl);
    }
}
?>