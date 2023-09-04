<?php

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

class JshoppingViewProduct_edit extends JViewLegacy
{
	
	protected $canDo;

    public function display($tpl = null)
    {
		$this->isHaveAccess();

        JText::script('COM_SMARTSHOP_NO_DEPENDENT_ATTRIBUTE_SELECTED');

        $title = JText::_('COM_SMARTSHOP_NEW_PRODUCT');
        if (!empty($this->edit)) {
            $title = JText::_('COM_SMARTSHOP_EDIT_PRODUCT');
            
            if (!$this->product_attr_id) {
                $title .= ' "' . $this->product->name . '"';
            }
        }

        JToolBarHelper::title($title, 'generic.png' );
        JToolBarHelper::save();
        JToolBarHelper::apply();

        if (!isset($this->product_attr_id) || !$this->product_attr_id) {
            JToolBarHelper::spacer();
            JToolBarHelper::spacer();
            JToolBarHelper::cancel();
        }

        parent::display($tpl);
	}

    public function editGroup($tpl = null)
    {
		$this->isHaveAccess();

        JToolBarHelper::title(JText::_('COM_SMARTSHOP_EDIT_PRODUCT'), 'generic.png');
        JToolBarHelper::apply('massSave');
        JToolBarHelper::save('massSaveAndClose');
        JToolBarHelper::cancel();

        parent::display($tpl);
    }

    public function displayDepAttrsMassEdit($tpl = null)
    {
        $this->isHaveAccess();

        JToolBarHelper::title(Text::_('COM_SMARTSHOP_EDIT_DEPEND_ATTRS'), 'generic.png');
        JToolBarHelper::save('saveDependAttrEditList');
        JToolBarHelper::cancel();

        parent::display($tpl);
    }

    protected function isHaveAccess()
    {
        $this->canDo = JHelperContent::getActions('com_jshopping','jshopping', $this->item->id ?? '');

		if (!$this->canDo->get('smartshop.categories')) {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }
    }
}
