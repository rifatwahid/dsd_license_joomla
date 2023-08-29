<?php
/**
* @version      4.8.0 04.06.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class JshoppingModelAttributprices extends JModelLegacy 
{
	public function productSave_setPostValues(&$post){
		if (is_array($post['attrib_price'])){
            if (count(array_unique($post['attrib_price']))>1) $post['different_prices'] = 1;
        }
		if (is_array($post['attrib_ind_price'])){
            $tmp_attr_ind_price = array();
            foreach($post['attrib_ind_price'] as $k=>$v){
                $tmp_attr_ind_price[] = $post['attrib_ind_price_mod'][$k].$post['attrib_ind_price'][$k];
            }
            if (count(array_unique($tmp_attr_ind_price))>1) $post['different_prices'] = 1;
        }
	}    
}
