<?php
/**
* @version      4.7.0 15.09.2020
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelPost extends JModelLegacy
{
	
	public function replaceFloatDelimiter($arr=array(),$p=0) 
    {
		if ($p) print_r($arr);
        foreach ($arr as $key=>$value){
			if (is_array($value)||is_object($value)){
				$arr[$key]=$this->replaceFloatDelimiter($value,1);
			}else{				
				if (is_numeric(str_replace(',','.',$value))) $arr[$key]=str_replace(',','.',$value);
			}
		}
		
		return $arr;
    }
}