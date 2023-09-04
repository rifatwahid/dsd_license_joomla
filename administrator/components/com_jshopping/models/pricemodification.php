<?php
/**
* @version      4.7.0 28.10.2019
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelPricemodification extends JModelLegacy
{
	public function getModificationArray(){
		$price_modification = array();
        $price_modification[] = JHTML::_('select.option', '+','+', 'id','name');
        $price_modification[] = JHTML::_('select.option', '-','-', 'id','name');
        $price_modification[] = JHTML::_('select.option', '*','*', 'id','name');
        $price_modification[] = JHTML::_('select.option', '/','/', 'id','name');
		return $price_modification;
	}
}