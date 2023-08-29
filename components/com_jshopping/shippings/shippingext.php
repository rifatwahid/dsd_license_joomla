<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

abstract class ShippingExtRoot{
    
    /**
    * Show form Shipping price
    * 
    * @param mixed $params - shipping price params
    * @param mixed $shipping_ext_row - exstension row
    * @param mixed $template - template view object
    */
    abstract function showShippingPriceForm($params, &$shipping_ext_row, &$template);
    
    /**
    * show form config
    * 
    * @param mixed $config - config extension
    * @param mixed $shipping_ext - object jshopShippingExt
    * @param mixed $template - template view object
    */    
    abstract function showConfigForm($config, &$shipping_ext, &$template);
}