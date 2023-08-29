<?php
/**
* @version      3.10.0 20.12.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class jshopAttributValue extends JTableAvto
{

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_attr_values', 'value_id', $_db);
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
	
    public function getName(int $value_id)
    {
        $lang = JSFactory::getLang();
        $select = '`' . $lang->get('name') . '` as name';
        
        return JSFactory::getModel('AttrsValuesFront')->getByValueId($value_id, [$select])->name ?: '';
    }
    
    public function getAllValues(int $attr_id) 
    {
        return JSFactory::getModel('AttrsValuesFront')->getAllValues($attr_id);
    }
    
    /**
    * get All Atribute value
    * @param $resulttype (0 - ObjectList, 1 - array {id->name}, 2 - array(id->object) )
    * 
    * @param mixed $resulttype
    */
    public function getAllAttributeValues(int $resulttype = 0,$product_id = 0)
	{
		if ($product_id) return JSFactory::getModel('AttrsValuesFront')->getAllAttributeValuesByProductID($resulttype,$product_id);
			else return JSFactory::getModel('AttrsValuesFront')->getAllAttributeValues($resulttype);
    }
       
}