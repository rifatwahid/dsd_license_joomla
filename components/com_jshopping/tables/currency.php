<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopCurrency extends JTableAvto 
{
    
	public $currency_id = null;
	public $currency_name = null;
    public $currency_code = null;
	public $currency_code_iso = null;
	public $currency_ordering = null;
	public $currency_value = null;
	public $currency_publish = null;
    
	public function __construct(&$_db)
	{
        parent::__construct('#__jshopping_currencies', 'currency_id', $_db);
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
	
	public function getAllCurrencies(int $publish = 1): array
	{
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
		return JSFactory::getModel('CurrenciesFront')->getAllCurrencies($publish);
	}
}