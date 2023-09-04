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

class JshoppingViewProduct_list extends JViewLegacy{
	
	protected $canDo;
	
    function display($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
		if (!$this->canDo->get('smartshop.categories')) throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        JToolBarHelper::title( JText::_('COM_SMARTSHOP_LIST_PRODUCT'), 'generic.png' ); 
		if ($this->canDo->get('core.create') AND $this->canDo->get('smartshop.products.create')){		
			JToolBarHelper::addNew();
		}
		if ($this->canDo->get('core.copy') AND $this->canDo->get('smartshop.products.copy')){		
			JToolBarHelper::custom('copy', 'copy', 'copy_f2.png', JText::_('JLIB_HTML_BATCH_COPY'));
		}
		if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.products.edit')){		
			JToolBarHelper::editList('editlist');		
		}
		if ($this->canDo->get('core.publish') AND $this->canDo->get('smartshop.products.publish')){		
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}
		if ($this->canDo->get('core.delete') AND $this->canDo->get('smartshop.products.delete')){		
			JToolBarHelper::deleteList();
		}
        parent::display($tpl);
	}
    function displaySelectable($tpl=null){
        parent::display($tpl);
    }
}
?>