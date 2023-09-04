<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopProductPrice extends JTableAvto
{
    
	public $price_id = null;
	public $product_id = null;
	public $discount = null;
	public $product_quantity_start = null;
	public $product_quantity_finish = null;
	
	public function __construct(&$_db)
	{
        parent::__construct('#__jshopping_products_prices', 'price_id', $_db);
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
	
	public function getAddPrices(int $product_id, int $usergroup = 0, int $usergroup_prices = 0)
	{       
        return JSFactory::getModel('ProductsPricesFront')->getAddPrices($product_id, $usergroup, $usergroup_prices);
    }
	
	public function getAddPricesFront(int $product_id, int $usergroup = 0, int $usergroup_prices = 0)
	{   
		return JSFactory::getModel('ProductsPricesFront')->getAddPricesFront($product_id, $usergroup, $usergroup_prices);
    }
}
