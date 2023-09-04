<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopProductAttribut extends JTableAvto 
{
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_products_attr', 'product_attr_id', $_db);
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
    
    public function check()
    {
        return 1;
    }
    
    public function deleteAttributeForProduct()
    {
        return JSFactory::getModel('ProductAttrsFront')->deleteByProdId($this->product_id);
    }
    
    public function deleteAttribute($id)
    {
        $this->load($id);
        
        if (!empty($this->ext_attribute_product_id)) {
            JSFactory::getModel('ProductAttrs2Front')->deleteByProdId($this->ext_attribute_product_id);
        }
        
        JSFactory::getModel('ProductAttrsFront')->deleteByProdAttrId($id);
    }
}
