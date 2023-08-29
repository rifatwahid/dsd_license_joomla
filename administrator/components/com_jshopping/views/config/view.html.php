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

class JshoppingViewConfig extends JViewLegacy
{
	protected $canDo;
		
    function display($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        $layout = $this->getLayout();
        $title = JText::_('COM_SMARTSHOP_EDIT_CONFIG');
        
        $exttitle = '';
        switch ($layout){
            case 'general': $exttitle = JText::_('COM_SMARTSHOP_GENERAL_PARAMETERS'); break;
            case 'categoryproduct': $exttitle = JText::_('COM_SMARTSHOP_CAT_PROD'); break;
            case 'checkout': $exttitle = JText::_('COM_SMARTSHOP_CHECKOUT'); break;
            case 'fieldregister': $exttitle = JText::_('COM_SMARTSHOP_REGISTER_FIELDS'); break;
            case 'currency': $exttitle = JText::_('COM_SMARTSHOP_CURRENCY_PARAMETERS'); break;
            case 'image': $exttitle = JText::_('COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS'); break;
            case 'storeinfo': $exttitle = JText::_('COM_SMARTSHOP_STORE_INFO'); break;
            case 'adminfunction': $exttitle = JText::_('COM_SMARTSHOP_SHOP_FUNCTION'); break;			
			case 'storage': $exttitle = JText::_('COM_SMARTSHOP_STORAGE'); break;
			
        }
        if ($exttitle!=''){
            $title .= ' / '.$exttitle;
        }
        
        JToolBarHelper::title($title, 'generic.png' );
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')){
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();
		if ($this->canDo->get('core.options')){
			JToolBarHelper::custom('panel', 'home', 'home', JText::_('COM_SMARTSHOP_PANEL'), false);
		}
		JToolBarHelper::divider();
        if (JFactory::getUser()->authorise('core.admin')){
            JToolBarHelper::preferences('com_jshopping');        
            JToolBarHelper::divider();
        }
        parent::display($tpl);
	}

	public function displayListContent($tpl = null)
    {
        $this->canDo = JHelperContent::getActions('com_jshopping', 'jshopping', $this->item->id ?? '');

        JToolBarHelper::title(JText::_('COM_SMARTSHOP_CONTENT'), 'generic.png');        
		if ($this->canDo->get('core.edit') OR $this->canDo->get('core.create')) {
            JToolBarHelper::save('contentSaveAndClose');
			JToolBarHelper::apply('contentApplyAndRedirect');
			JToolBarHelper::spacer();        
        }
        JToolBarHelper::cancel();
		JToolBarHelper::spacer();
        
		if ($this->canDo->get('core.options')) {
			JToolBarHelper::custom('panel', 'home', 'home', JText::_('COM_SMARTSHOP_PANEL'), false);
        }
        
        parent::display($tpl);
    }
	
    function displayFields($tpl=null){
		$this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');
        $layout = $this->getLayout();
        $title = JText::_('COM_SMARTSHOP_EDIT_CONFIG');
        
        $exttitle = '';
        switch ($layout){
            case 'fieldregister': $exttitle = JText::_('COM_SMARTSHOP_REGISTER_FIELDS'); break;
            
        }
        if ($exttitle!=''){
            $title .= ' / '.$exttitle;
        }
        
        JToolBarHelper::title($title, 'generic.png' );
	
		if ($this->canDo->get('core.options')){
			JToolBarHelper::custom('panel', 'home', 'home', JText::_('COM_SMARTSHOP_PANEL'), false);
		}
		JToolBarHelper::divider();
        if (JFactory::getUser()->authorise('core.admin')){
            JToolBarHelper::preferences('com_jshopping');        
            JToolBarHelper::divider();
        }
        parent::display($tpl);
	}
}
