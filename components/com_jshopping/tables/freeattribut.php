<?php
/**
* @version      2.8.0 12.03.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class jshopFreeAttribut extends JTableAvto
{
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_free_attr', 'id', $_db);
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
	
    public function getName(int $attrId): string
    {
        return JSFactory::getModel('FreeAttrsFront')->getName($attrId);
    }
    
    public function getAll() 
    { 
        return JSFactory::getModel('FreeAttrsFront')->getAll();
    }
    
    public function getAllNames()
    {
        return JSFactory::getModel('FreeAttrsFront')->getAllNames();
    }

    public function getParams() 
    {
        return JSFactory::getModel('FreeAttrCalcPriceFront')->getParams();
    }    
}
