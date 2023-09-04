<?php
/**
* @version      3.18.0 11.06.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

class jshopProductOption extends JTableAvto
{

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_products_option', 'id', $_db);
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
	
    public function getProductOption(int $product_id, int $key)
    {
        return JSFactory::getModel('ProductsOptionsFront')->getValByProductIdAndKey($product_id, $key);
    }
    
    public function getProductOptions(int $product_id)
    {
        return JSFactory::getModel('ProductsOptionsFront')->getValsByProductId($product_id);
    }
    
    public function getProductOptionList(array $productIds, int $key, int $setforallproducts = 1)
    {
        return JSFactory::getModel('ProductsOptionsFront')->getList($productIds, $key, $setforallproducts);
    }    
   
}
