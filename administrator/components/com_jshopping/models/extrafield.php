<?php
/**
* @version      4.1.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class JshoppingModelExtraField extends JModelLegacy 
{
    public function productSave_setPostValues(&$post){
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->admin_show_product_extra_field){
            $_productfields = JSFactory::getModel("productFields");
            $list_productfields = $_productfields->getList(1);
            foreach($list_productfields as $v){
                if ($v->type==0 && !isset($post['extra_field_'.$v->id])){
                    $post['extra_field_'.$v->id] = '';
                }
            }
        }
	}
    
}
