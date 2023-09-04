<?php
/**
* @version      4.2.1 08.04.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class jshopProductField extends JTableAvto
{
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_products_extra_fields', 'id', $_db);
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
    
    /**
    * set categorys
    * 
    * @param array $cats
    */
    public function setCategorys($cats)
    {
        $this->cats = serialize($cats);
    }
    
    /**
    * get gategoryd
    * 
    * @return array
    */    
    public function getCategorys()
    {
        if (!empty($this->cats)) {
            return unserialize($this->cats);
        }

        return [];
    }
    
    public function getList($groupordering = 1)
    {
        return JSFactory::getModel('ProductsExtraFieldsFront')->getList($groupordering);
    }
    
}