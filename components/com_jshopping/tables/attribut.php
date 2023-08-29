<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class jshopAttribut extends JTableAvto
{

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_attr', 'attr_id', $_db);
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
	
    public function getName($attr_id)
    {
        $langName = JSFactory::getLang()->get('name');
        $modelOfAttrsFront = JSFactory::getModel('AttrsFront');
        return $modelOfAttrsFront->getByAttrId($attr_id, ['`' . $langName . '` as name'])->name ?: '';
    }

    public function getAllAttributes($groupordering = 1,$attr_id=0,$attr_type=0)
    {
        $modelOfAttrsFront = JSFactory::getModel('AttrsFront');
        return $modelOfAttrsFront->getAllAttributes($groupordering,$attr_id,$attr_type);
    }
    
    public function getTypeAttribut($attr_id)
    {
        $modelOfAttrsFront = JSFactory::getModel('AttrsFront');
        return $modelOfAttrsFront->getByAttrId($attr_id, ['attr_type'])->attr_type ?: '';
    }
    
    public function setCategorys($cats)
    {
        $this->cats = serialize($cats);
    }
      
    public function getCategorys()
    {
        return !empty($this->cats) ? unserialize($this->cats) : [];
    }

}