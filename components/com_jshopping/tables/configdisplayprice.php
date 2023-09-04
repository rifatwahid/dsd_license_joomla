<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopConfigDisplayPrice extends JTableAvto 
{

    public $id = null;
    public $zones = null;
    public $display_price = null;
    public $display_price_firma = null;
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_config_display_prices', 'id', $_db);
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
	
    public function setZones($zones)
    {
        $this->zones = serialize($zones);
    }
    
    public function getZones()
    {
        return !empty($this->zones) ? unserialize($this->zones) : [];
    }
    
    public function getList(): array
    {
        return JSFactory::getModel('ConfigDisplayPricesFront')->getList();
    }
}