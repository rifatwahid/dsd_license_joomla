<?php

defined('JPATH_BASE') or die;

Joomla\CMS\Form\FormHelper::loadFieldClass('list');

class JFormFieldModal_Product extends JFormFieldList
{	
	protected $type = 'Modal_Product';

	protected function getOptions()
	{
		$options = [];
		$products = $this->getProducts();
		$elementAttrs = $this->element->attributes();
		$values = (is_string($this->value)) ? explode(',', $this->value): $this->value;

		if (!empty($products)) {
			foreach($products as $product) {
				$options[] = (object)[
					'value' => $product->product_id,
					'text' => $product->name,
					'class' => ((string)$elementAttrs['class']),
					'selected' => in_array($product->product_id, $values),
					'onclick' => (string)$elementAttrs['onclick'],
					'onchange' => (string)$elementAttrs['onchange']
				];
			}
		}

		return $options;
	}

	protected function getProducts(): array
	{
		$lang = JFactory::getLanguage();		
		$db	= JFactory::getDbo();		
		$db->setQuery('SELECT `name_'.$lang->getTag().'` as `name`, `product_id` FROM `#__jshopping_products` WHERE `product_publish` = 1');
		$products = $db->loadObjectList() ?: [];

		return $products;
	}
}
