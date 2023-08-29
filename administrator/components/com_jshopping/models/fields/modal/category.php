<?php

defined('JPATH_BASE') or die;

Joomla\CMS\Form\FormHelper::loadFieldClass('list');

class JFormFieldModal_Category extends JFormFieldList
{	
	protected $type = 'Modal_Category';

	protected function getOptions()
	{
		$options = [];
		$categories = $this->getCategories();
		$elementAttrs = $this->element->attributes();
		$values = (is_string($this->value)) ? explode(',', $this->value): $this->value;
		array_unshift($categories, (object)[
			'name' => "&nbsp;",
			'category_id' => ''
		]);

		if (!empty($categories)) {
			foreach($categories as $category) {
				$options[] = (object)[
					'value' => $category->category_id,
					'text' => $category->name,
					'class' => ((string)$elementAttrs['class']),
					'selected' => in_array($category->category_id, $values),
					'onclick' => (string)$elementAttrs['onclick'],
					'onchange' => (string)$elementAttrs['onchange']
				];
			}
		}

		return $options;
	}

	protected function getCategories(): array
	{
		$db	= JFactory::getDbo();
		$lang = JFactory::getLanguage();
		$db->setQuery('SELECT `name_' . $lang->getTag() . '` as `name`, `category_id` FROM `#__jshopping_categories` WHERE `category_publish` = 1');
		$categories = $db->loadObjectList() ?: [];	

		return $categories;
	}
}
