<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopProductAttribut2 extends JTableAvto
{
    
    public $id = null;
    public $product_id = null;
    public $attr_id = null;
    public $attr_value_id = null;
    public $price_mod = null;
    public $addprice = null;
    
    public $_price_mod_allow = [
        '+',
        '-',
        '*',
        '/',
        '=',
        '%'
    ];
    
    public function __construct( &$_db )
    {
        parent::__construct('#__jshopping_products_attr2', 'id', $_db);
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
	
    public function check()
    {        
        if (!in_array($this->price_mod, $this->_price_mod_allow)) {
            $this->price_mod = $this->_price_mod_allow['0'];
        }

        if (!$this->product_id || !$this->attr_id || !$this->attr_value_id) {
            return 0;
        }

        return 1;
    }
    
    public function deleteAttributeForProduct()
    {
        return JSFactory::getModel('ProductAttrs2Front')->deleteByProdId($this->product_id);
    }

    public function calcAttrsWithOneTimeCostPriceTypeOnly($productId, $attrValuesIds = [], $addTax=1)
    {
		JSFactory::getModel('TaxesFront')->checkAddTax($addTax);
		
        $priceOneTimeCost = 0;
        $isEmpty = true;

        if (!empty($attrValuesIds)) {
            foreach ($attrValuesIds as $value) {
                if (!empty($value)) {
                    $isEmpty = false;
                }
            }
        }
         
		if ($isEmpty) {
			$attrValuesIds = [];
        }		
        
        if (!empty($attrValuesIds)) {
            $attrsValues = $this->getByAttrValIds($attrValuesIds, '`price_type` = 100500 AND `product_id` = ' . $productId);
        } else {
            $attrsValues = $this->getByProdId($productId, '`price_type` = 100500 AND (`sorting` = 0 OR `sorting` = 1)');
        }

        \JFactory::getApplication()->triggerEvent('onAfterGetAttrsValuesForCalcOneTimeCostPriceTypeOnly', [&$attrsValues, &$attrValuesIds, &$productId, &$priceOneTimeCost]);

        if (!empty($attrsValues)) {
            
            foreach ($attrsValues as $attrValArrInfo) {
                $addprice = (float)$attrValArrInfo['addprice'];
                $priceMode = $attrValArrInfo['price_mod'];

                $priceOneTimeCost = getModifyPriceByMode($priceMode, $priceOneTimeCost, $addprice);
            }

        }

		if ($addTax){
			$jshopConfig = JSFactory::getConfig(); 
			$product = JSFactory::getTable('product');
			$product->load($productId);
			$taxes = JSFactory::getAllTaxes();
			//print_r($taxes);die;
			$product->product_tax = $taxes[$product->product_tax_id] ?? 0;
			if ($jshopConfig->display_price_admin == 1) {
				if ($jshopConfig->display_price_front == 0) $priceOneTimeCost = $priceOneTimeCost+$priceOneTimeCost * ($product->product_tax / 100);
			} else {
				if ($jshopConfig->display_price_front == 1) $priceOneTimeCost = $priceOneTimeCost+$priceOneTimeCost / ((100 + $product->product_tax) / 100);
			}		
		}

        return $priceOneTimeCost;
	}
	
    public function calcAttrsWithOneTimeCostPriceType($productId, $attrValuesIds, $price, $addTax=1)
    {
		$attrValuesIds = $attrValuesIds ?? [];
		$one_time_price=$this->calcAttrsWithOneTimeCostPriceTypeOnly($productId, $attrValuesIds, $addTax);
		
        $product = JSFactory::getTable('product');
        $product->load($productId);
				
		$price += $one_time_price;
		
        $price += $product->getTempData($product)['total_price'];
        
        return $price;
    }



    public function getByAttrValIds($valuesIdArr, $where = '')
    {
        $db = \JFactory::getDBO();
        $valuesIdStr = '';

		foreach($valuesIdArr as $v) {
			if (is_array($v) && !empty($v)) {
				foreach($v as $attr) {					
					if($attr > 0){
						if(strlen($valuesIdStr) > 0) {
                            $valuesIdStr .= ',';
                        }

						$valuesIdStr .= $attr;
					}
				}
			} else {
				if($v > 0) {
					if (strlen($valuesIdStr) > 0) {
                        $valuesIdStr .= ',';
                    }	

					if (!empty($v)) { 
                        $valuesIdStr .= $v; 
                    }
				}
			}
		}

        $query = 'SELECT * FROM `#__jshopping_products_attr2` WHERE `attr_value_id` IN (' . $valuesIdStr . ')';
        if (!empty($where)) {
            $query .= ' AND ' . $where;
        }

        $db->setQuery($query);

        return $db->loadAssocList('attr_value_id');
    }

    public function getByProdId($productId, $where = '')
    {
        $db = \JFactory::getDBO();
        $query = 'SELECT * FROM `#__jshopping_products_attr2` WHERE `product_id` = ' . (int)$db->escape($productId);

        if (!empty($where)) {
            $query .= ' AND ' . $where;
        }

        $db->setQuery($query);

        return $db->loadAssocList('attr_value_id');
    }
    
}