<?php
/**
* @version      2.7.0 26.12.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class jshopProductFieldValue extends JTableAvto
{
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_products_extra_field_values', 'id', $_db );
    }
	
	public function bind($src, $ignore = Array())
	{
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					$src[$key]=0;
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}
    
    public function getAllList($display = 0)
    {
        return JSFactory::getModel('ProductsExtraFieldValuesFront')->getAll($display);
    }

    public function getAllListDetails($display = 0)
    {
        return JSFactory::getModel('ProductsExtraFieldValuesFront')->getAllDetails($display);
    }
}
