<?php

defined('JPATH_BASE') or die;

Joomla\CMS\Form\FormHelper::loadFieldClass('list');

class JFormFieldModal_Vendor extends JFormFieldList
{	
	protected $type = 'Modal_Vendor';

	protected function getOptions()
	{
		$options = [];
		$vendors = $this->getVendors();
		$elementAttrs = $this->element->attributes();
		$values = (is_string($this->value)) ? explode(',', $this->value): $this->value;

		if (!empty($vendors)) {
			foreach($vendors as $vendor) {
				$options[] = (object)[
					'value' => $vendor->id,
					'text' => $vendor->name,
					'class' => ((string)$elementAttrs['class']),
					'selected' => in_array($vendor->id, $values),
					'onclick' => (string)$elementAttrs['onclick'],
					'onchange' => (string)$elementAttrs['onchange']
				];
			}
		}

		return $options;
	}

	protected function getVendors(): array
	{	
		$db	= JFactory::getDbo();		
		$db->setQuery('SELECT `shop_name` as `name`, `id` FROM `#__jshopping_vendors`');
		$vendors = $db->loadObjectList() ?: [];	

		return $vendors;
	}
}
