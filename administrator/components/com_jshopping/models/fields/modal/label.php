<?php

defined('JPATH_BASE') or die;

Joomla\CMS\Form\FormHelper::loadFieldClass('list');

class JFormFieldModal_Label extends JFormFieldList
{	
	protected $type = 'Modal_Label';

	protected function getOptions()
	{
		$options = [];
		$labels = $this->getLabels();
		$elementAttrs = $this->element->attributes();
		$values = (is_string($this->value)) ? explode(',', $this->value): $this->value;

		if (!empty($labels)) {
			foreach($labels as $label) {
				$options[] = (object)[
					'value' => $label->id,
					'text' => $label->name,
					'class' => ((string)$elementAttrs['class']),
					'selected' => in_array($label->id, $values),
					'onclick' => (string)$elementAttrs['onclick'],
					'onchange' => (string)$elementAttrs['onchange']
				];
			}
		}

		return $options;
	}

	protected function getLabels(): array
	{
		$lang = JFactory::getLanguage();		
		$db	= JFactory::getDbo();		
		$db->setQuery('SELECT `name_' . $lang->getTag() . '` as `name`, `id` FROM `#__jshopping_product_labels`');
		$labels = $db->loadObjectList() ?: [];

		return $labels;
	}
}
