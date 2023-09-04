<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

abstract class ShippingFormRoot
{
    
    public $_errormessage = '';
    
    public abstract function showForm($shipping_id, $shippinginfo, $params);
    
    public function check($params, $sh_method)
    {
        return 1;
    }
    
    /**
    * Set message error check
    */
    public function setErrorMessage($msg)
    {
        $this->_errormessage = $msg;
    }
    
    /**
    * Get message error check
    */
    public function getErrorMessage()
    {
        return $this->_errormessage;
    }
	
    public function getParams()
    {
        return $this->_sh_params;
    }
    

    public function setParams($params)
    {
        $this->_sh_params = $params;
    }
    
    /**
    * list display params name shipping saved to order
    */
    public function getDisplayNameParams()
    {
        return [];
    }
    
    /**
    * exec before mail send
    */
    public function prepareParamsDispayMail(&$order, &$sh_method)
    {
    }

}
