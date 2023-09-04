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

class jshopShippingMethod extends JTableAvto 
{

    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_shipping_method_price', 'sh_pr_method_id', $_db);
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
        $id = JSFactory::getModel('ShippingMethod')->getByAlias($alias)->shipping_id ?? 0;
        return $this->load($id);
    }
    
    public function getAllShippingMethods($publish = 1) 
    {
        return JSFactory::getModel('ShippingMethod')->getAll($publish);
    }

    public function getAllShippingMethodsCountry($country_id, $payment_id, $publish = 1,$usergroup = '', $state_id = 0)
    {
        return JSFactory::getModel('ShippingMethod')->getAllShippingMethodsCountry($country_id, $payment_id, $publish, $usergroup, $state_id);
    }
    
    public function getPayments()
    {
		extract(js_add_trigger(get_defined_vars()));
        return empty($this->payments) ? [] : explode(',', $this->payments);
    }
    
    public function setPayments($payments)
    {
        $payments = (array)$payments;
        foreach($payments as $v) {
            if ($v == 0) {
                $payments = [];
                break;
            }
        }

		extract(js_add_trigger(get_defined_vars()));
        $this->payments = implode(',', $payments);
    }
	
    public function setParams($params)
    {
        $this->params = serialize($params);
    }
    
    public function getParams()
    {        
        return empty($this->params) ? [] : unserialize($this->params);
    }
    
    public function getShippingForm($alias = null)
    {
        if (is_null($alias)) {
            $alias = $this->alias;
        }

        return JSFactory::getModel('ShippingMethod')->getShippingForm($alias);
    }
    
    public function loadShippingForm($shipping_id, $shippinginfo, $params)
    {
        return JSFactory::getModel('ShippingMethod')->renderShippingForm($shipping_id, $shippinginfo, $params);
    }

    public function getShippingPriceId($shipping_id, $country_id, $publish = 1)
    {
        return JSFactory::getModel('ShippingMethod')->getShippingPriceId($shipping_id, $country_id, $publish);
    }

}
