<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');

include_once JPATH_COMPONENT_SITE . '/shippings/shippingext.php';

class jshopShippingExt extends JTableAvto
{

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_shipping_ext_calc', 'id', $_db);
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

    public function loadFromAlias($alias)
    {
        $id = JSFactory::getModel('ShippingExtCalc')->getByAliasName($alias, ['id'])->id ?? 0;
        return $this->load($id);
    }
    
    public function load($id = null, $reset = true)
    {
        $return = parent::load($id, $reset);
        $jshopConfig = JSFactory::getConfig();
        $path = $jshopConfig->path . 'shippings';
        $extname = $this->alias;
        $filepatch = "{$path}/{$extname}/{$extname}.php";

        if (file_exists($filepatch)) {
            require_once $filepatch;
            $this->exec = new $extname();
        }else{
            \JFactory::getApplication()->enqueueMessage('Load ShippingExt ' . $extname . ' error.','error');
        }
        
        return $return;
    }
    
    public function getList($active = 0)
    {
        return JSFactory::getModel('ShippingExtCalc')->getAll($active);
    }
    
    public function setShippingMethod($data)
    {
        $this->shipping_method = serialize($data);
    }
    
    public function getShippingMethod()
    {
        return empty($this->shipping_method) ? [] : unserialize($this->shipping_method);
    }
    
    public function setParams($data)
    {
        $this->params = serialize($data);
    }
    
    public function getParams()
    {   
        return empty($this->params) ? [] : unserialize($this->params);
    }
}
