<?php

defined('JPATH_BASE') or die;

Joomla\CMS\Form\FormHelper::loadFieldClass('list');

class JFormFieldModal_Manufacturer extends JFormFieldList
{	
	protected $type = 'Modal_Manufacturer';

	protected function getOptions()
	{
		$options = [];
		$manufacturers = $this->getManufacturers();
		$elementAttrs = $this->element->attributes();
		$values = (is_string($this->value)) ? explode(',', $this->value): $this->value;

		if (!empty($manufacturers)) {
			foreach($manufacturers as $manufacturer) {
				$options[] = (object)[
					'value' => $manufacturer->manufacturer_id,
					'text' => $manufacturer->name,
					'class' => ((string)$elementAttrs['class']),
					'selected' => in_array($manufacturer->manufacturer_id, $values),
					'onclick' => (string)$elementAttrs['onclick'],
					'onchange' => (string)$elementAttrs['onchange']
				];
			}
		}

		return $options;
	}

	protected function getManufacturers(): array
	{
		$lang = JFactory::getLanguage();		
		$db	= JFactory::getDbo();		
		$db->setQuery('SELECT `name_' . $lang->getTag() . '` as `name`, `manufacturer_id` FROM `#__jshopping_manufacturers`');
		$manufacturers = $db->loadObjectList() ?: [];

		return $manufacturers;
	}
}
