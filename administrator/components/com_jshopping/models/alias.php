<?php
/**
* @version      2.4.0 14.10.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelAlias extends JModelLegacy{
    
    public function checkExistAlias1Group($alias, $lang, $category_id, $manufacture_id){
        $db = \JFactory::getDBO();
        $query = "select category_id as id from #__jshopping_categories where `alias_".$lang."` = '".$db->escape($alias)."' and category_id!='".$db->escape($category_id)."' 
                  union
                  select manufacturer_id as id from #__jshopping_manufacturers where `alias_".$lang."` = '".$db->escape($alias)."' and manufacturer_id!='".$db->escape($manufacture_id)."'
                  ";
        $db->setQuery($query);
        $res = $db->loadResult();
        $reservedFirstAlias = JSFactory::getReservedFirstAlias();
        if ($res || in_array($alias, $reservedFirstAlias)){
            return 0;//error
        }else{
            return 1;//ok
        }
    }
    
    public function checkExistAlias2Group($alias, $lang, $product_id){
        $db = \JFactory::getDBO();
        $query = "select product_id from #__jshopping_products where `alias_".$lang."` = '".$db->escape($alias)."' and product_id!='".$db->escape($product_id)."'";
        $db->setQuery($query);
        $res = $db->loadResult();        
        if ($res){
            return 0;//error
        }else{
            return 1;//ok
        }
    }
	
	function randomStringGenerator($n) 
	{ 
		$generated_string = ""; 			  
		$domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
		  
		$len = strlen($domain); 
		for ($i = 0; $i < $n; $i++) 
		{ 
			$index = rand(0, $len - 1); 
			$generated_string = $generated_string . $domain[$index]; 
		} 
		  
		return $generated_string; 
	}
    
}