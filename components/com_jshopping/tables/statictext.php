<?php
/**
* @version      4.6.0 21.05.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class jshopStaticText extends JTableAvto
{
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_config_statictext', 'id', $_db);
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
	
    public function loadData(string $alias)
    {
        return JSFactory::getModel('ConfigStaticTextsFront')->getByAlias($alias);
    }
    
    public function loadDataByIds(array $ids)
    {
        return JSFactory::getModel('ConfigStaticTextsFront')->getByIds($ids);
    }
    
    public function getReturnPolicyForProducts($products)
    {
        $productOption = JSFactory::getTable('productOption', 'jshop');
        $listrp = $productOption->getProductOptionList($products, 'return_policy');
        $listrp = array_unique($listrp);
        $tmp = $this->loadData('return_policy');
        $defidrp = intval($tmp->id);

        foreach($listrp as $k=>$v){
            if (!$v) $listrp[$k] = $defidrp;
        }
        
        return $this->loadDataByIds($listrp);
    }
    
}
