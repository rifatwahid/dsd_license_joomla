<?php

defined('_JEXEC') or die('Restricted access');

class JshoppingModelBathProductEdit extends JModelLegacy
{
    const ACTIONS = [
        0 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_ADD',
        1 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_DELETE',
        2 => 'COM_SMARTSHOP_BATH_PRODUCT_EDIT_REPLACE'
    ];

    const CODES = [
        'ADD' => 0,
        'DELETE' => 1,
        'REPLACE' => 2
    ];

    public function getMarkUps(string $name, array $attrs = [], int $default = -1, $customActions = []): string
    {
        $options = [];
        $actions = $customActions ?: static::ACTIONS;
        array_walk($actions, function ($actionName, $key) use(&$options) {
            $options[] = JHTML::_('select.option',  $key, $actionName);
        });

        if (isset($attrs['class'])) {
            $attrs['class'] .= ' form-select';
        } else {
            $attrs['class'] = 'form-select';
        }

        array_walk($attrs, function (&$value, $key) {
            $value = $key . '="' .$value . '"';
        });
        $attrs = implode(' ', array_values($attrs));
        $select = JHTML::_('select.genericlist', $options, $name, $attrs, 'value', 'text', $default, '', true);

        return $select;
    }
}