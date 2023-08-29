<?php
/**
* @version      2.5.2 20.03.2012
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

class jshopState extends JTableAvto 
{
    public $ordering = null;

    function __construct( &$_db )
    {
        parent::__construct( '#__jshopping_states', 'state_id', $_db );
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

    function getStates($country_id) : array
    {
        $db = \JFactory::getDBO(); 
        $jshopConfig = JSFactory::getConfig();    
        $lang = JSFactory::getLang();  
        $ordering = "ordering";
        if ($jshopConfig->sorting_country_in_alphabet) $ordering = "name";      

        $query = "SELECT state_id,  `".$lang->get("name")."` as name FROM `#__jshopping_states` WHERE country_id='".$country_id."' AND state_publish=1 ORDER BY ".$ordering;
        $db->setQuery($query);                
        return $db->loadObjectList();
    }

    function getAllCountries($publish = 1) : array
    {
        $db = \JFactory::getDBO(); 
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();
        $where = ($publish)?(" WHERE country_publish = '1' "):(" ");
        $ordering = "ordering";
        if ($jshopConfig->sorting_country_in_alphabet) $ordering = "name";
        $query = "SELECT country_id, `".$lang->get("name")."` as name FROM `#__jshopping_countries` ".$where." ORDER BY ".$ordering;
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}
?>