<?php
/**
* @version      4.9.2 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\Event\Event;
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/free_attrs_default_values/products_free_attrs_default_values_mambot.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/exclude_attribute_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/exclude_buttons_for_attribute.php';


class jshopProduct extends JTableAvto
{
    protected const ENABLED = 1;
    protected const DISABLED = 0;

    public $attribute_active_data = null;
    public $pricePerConsignmentDiscount = null;
	
	public $total_tax_rate = 0;
    public $tax_source = '';

    protected $nativeUploadPrice = null;
    protected $amountOfUploads = 0;

    public function __construct(&$db)
    {   
        parent::__construct('#__jshopping_products', 'product_id', $db);
        JPluginHelper::importPlugin('jshoppingproducts');
		$db = Factory::getDbo();
		
		$currentObj=$this;
		$dispatcher = \JFactory::getApplication();          
        $dispatcher->triggerEvent('onProductTableConstructor', [&$currentObj]);
		
		$tableName = $db->replacePrefix('#__jshopping_products');
		$columns = $db->getTableColumns($tableName);

		if (!array_key_exists('publish_editor_pdf', $columns)) {			
			try {
				$alterQuery = "ALTER TABLE " . $db->quoteName($tableName) . " ADD COLUMN " . $db->quoteName('publish_editor_pdf') . " int(1) NOT NULL DEFAULT '0'";
				$db->setQuery($alterQuery);
				$db->execute();
			} catch (Exception $e) {				
				echo $e->getMessage();
			}
		}
    }

	public function bind($src, $ignore = Array())
	{
	
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key])||$src[$key]=="")&&($value->Extra=="auto_increment")){
				unset($fields[$key]);
			}
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')&&(strtoupper(substr($value->Type,0,4))!='DATE')){					
					$src[$key]=0;
				}
				if ((strtoupper(substr($value->Type,0,4))=='DATE')){					
					$src[$key] = date('Y-m-d H:i:s');
				}
			}						
		}
		/*
		$src['add_usergroups_prices_product_price']=0;
		$src['add_usergroups_prices_product_price2']=0;
		$src['add_usergroups_prices_quantity_start'][0]=0;
		$src['add_usergroups_prices_quantity_finish'][0]=0;
		$src['add_usergroups_prices_product_add_discount'][0]=0;
		$src['add_usergroups_prices_product_add_price'][0]=0;
		$src['add_usergroups_prices_start_discount'][0]=0;
		
		$src['product_demo_descr_0']=0;
		$src['product_file_descr_0']=0;
		
		$src['attrib_price']=0;
		$src['attrib_ind_id']=0;
		$src['attrib_ind_price']=0;
		$src['attrib_ind_price_mod']=0;
		$src['freeattribut']=0;
		/*
		//echo "FIELDS:<pre>";		
		//print_r($fields);		
		//echo "<pre>";print_r($src);die();
		$s="INSERT INTO #__jshopping_products ";
		$c=" (";
		$v=" (";
		foreach ($src as $key=>$value){
			if (is_array($value)){
			echo $key;die();
			}
			if ($c!= " (") {
				$c=$c.",";
				$v=$v.",";
			}
			$c=$c.$key;
			$v=$v.$value;			
		}
		$s=$s.$c.") VALUES ".$v.")";
		//echo $s;die();
		$fields = (parent::getTableFields()) ?: [];		
		
		
		*/
		/*
		$s="INSERT INTO o45ds_jshopping_products ";
		$c=" (";
		$v=" (";
		$fnew=array();
		foreach ($fields as $key=>$value){
			$fnew[$key]=$src[$key];
			if ($c!= " (") {
				$c.=",";
				$v.=",";
			}
			$c.="`".$key."`";
			if (isset($src[$key])){
			$v.='"'.$src[$key].'"';
			}else{$v.="`---`";}
			echo "<br>".$key."=".$src[$key];
		}
		$s=$s.$c.") VALUES ".$v.")";
		echo $s;die();
		$db = \JFactory::getDBO();
		$db->setQuery($s);
		

		$db->execute();
		return parent::bind($fnew, $ignore);
		/**/
		//echo "<pre>";print_r($src);die();
		return parent::bind($src, $ignore);
	}

    public function load($id = NULL, $reset = true, $front = true)
    {
        $isLoadedSuccess = parent::load($id, $reset);
        JSFactory::getModel('ProductsPricesGroupFront')->updateProductPriceByUserGroupPrice($this,$front);

        return $isLoadedSuccess;
    }

    public function getProductId(bool $bothMode = true)
    {
        if ($bothMode) {
			if(isset($this->attribute_active_data->ext_data) && $this->attribute_active_data->ext_data->product_id){
				return $this->attribute_active_data->ext_data->product_id;
			}else{
				return $this->product_id;
			}
        }

        return $this->product_id;
    }

    public function setNativeUploadPrice(?jshopNativeUploadsPrices $nativeUploadPrice, $amountOfUploads = 0)
    {
        $this->nativeUploadPrice = $nativeUploadPrice;
        $this->amountOfUploads =  $amountOfUploads;
    }

    public function getNativeUploadPrice(): ?jshopNativeUploadsPrices
    {
        return $this->nativeUploadPrice;
    }

    public function getNativeAmountOfUploads()
    {
        return $this->amountOfUploads;
    }
    
    public function setAttributeActive(?array $idsOfActiveAttrs, bool $isUseTransferDependParamsToProduct = true, $isFront = true)
    {
        $currentObj = $this;
        $this->original_price = $this->getPriceWithParams();
        $this->attribute_active = $idsOfActiveAttrs;

        if (!empty($this->attribute_active)) {
            $jshopConfig = JSFactory::getConfig();

            $this->attribute_active_data = new stdClass();
            $this->attribute_active_data->price_params = 0;
            
            $separatedAttrs = JSFactory::getModel('AttrsFront')->separateAttrsByTypes($idsOfActiveAttrs);
            $dependentAttrs = $separatedAttrs->depends;
            $independAttrs = $separatedAttrs->independs;
			foreach ($dependentAttrs as $key=>$value){
				if ($value==-1) unset($dependentAttrs[$key]);
			}
            if (!empty($dependentAttrs)) {

                $this->attribute_active_data = JSFactory::getModel('ProductAttrsFront')->getByProdIdAndAttrs($this->product_id, $dependentAttrs);

                if ($jshopConfig->use_extend_attribute_data == static::ENABLED && !empty($this->attribute_active_data->ext_attribute_product_id)) {
                    $this->attribute_active_data->ext_data = $this->getExtAttributeData($this->attribute_active_data->ext_attribute_product_id);
                    $this->product_is_add_price = $this->attribute_active_data->ext_data->product_is_add_price ?: $this->product_is_add_price;

                    if ($isUseTransferDependParamsToProduct) {
                        $this->transferDependParamsToProduct($isFront);
                    }
                }
            }
        }

        $dispatcher = \JFactory::getApplication();  
        $this->setWeightForActiveAttr($idsOfActiveAttrs, $this);         
        $dispatcher->triggerEvent('onAfterSetAttributeActive', [&$idsOfActiveAttrs, &$currentObj]);
    }

    protected function transferDependParamsToProduct($isFront = true)
    {
		$taxes = JSFactory::getAllTaxes();
        if (!empty($this->attribute_active_data->ext_data->parent_id)) {
            $dependProduct = $this->attribute_active_data->ext_data;
            
            if (!empty($dependProduct->is_use_additional_details)) {
                $this->product_tax_id = $dependProduct->product_tax_id ?: array_key_first($taxes);
                $this->product_manufacturer_id = $dependProduct->product_manufacturer_id;
                $this->delivery_times_id = $dependProduct->delivery_times_id;
                $this->label_id = $dependProduct->label_id;
                $this->no_return = $dependProduct->no_return;
                $this->max_count_product = $dependProduct->max_count_product;
                $this->min_count_product = $dependProduct->min_count_product;                
                $this->quantity_select = $dependProduct->quantity_select;
                $this->equal_steps = $dependProduct->equal_steps;
            }

            $this->product_price = $dependProduct->product_price;
            $this->product_quantity = $dependProduct->product_quantity;
            $this->unlimited = $dependProduct->unlimited;
            $this->low_stock_notify_status = $dependProduct->low_stock_notify_status;
            $this->low_stock_number = $dependProduct->low_stock_number;
            $this->product_ean = $dependProduct->product_ean;
            $this->product_weight = $dependProduct->product_weight;
            $this->expiration_date = $dependProduct->expiration_date;
            $this->production_time = $dependProduct->production_time;
            $this->weight_volume_units = $dependProduct->weight_volume_units;
            $this->product_old_price = $dependProduct->product_old_price;

            $this->product_is_add_price = $dependProduct->product_is_add_price;
            $this->is_activated_price_per_consignment_upload = $dependProduct->is_activated_price_per_consignment_upload;
            $this->product_price_type = $dependProduct->product_price_type;
            $this->qtydiscount = $dependProduct->qtydiscount;
            $this->basic_price_unit_id = $dependProduct->basic_price_unit_id;

            $this->isTransferedDependParamsToMainProduct = true;
            JSFactory::getModel('ProductsPricesGroupFront')->updateProductPriceByUserGroupPrice($this, $isFront);
        }
    }

    public function getPrice($prodQty = 1, $enableCurrency = 1, $enableUserDiscount = 1, $enableParamsTax = 1, $cartProduct = [], $isFullPrice = false, $inkl_free_attr = true)
    {
        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
		$dispatcher->triggerEvent('onBeforeTableProductGetPrice', [&$jshopConfig]);

        $prodQty = corectDefaultCount($this, $prodQty);
        if (empty($this->attribute_active_data)) {
            $this->attribute_active_data = new stdClass();
        }
        
        if (!isset($this->attribute_active_data->price)) {
            $this->attribute_active_data->price = $this->product_price;
        }
        
        //USERGROUP_PRICES
		$userShop = JSFactory::getUserShop();
        $groupPriceData = JSFactory::getModel('ProductsPricesGroupFront')->getByProductAndGroupIds($this->getProductId(), (int)$userShop->usergroup_id);

        $usergroup_prices = 0;
        $usergroup_id = 0;

		if (!empty($groupPriceData)) {
			$usergroup_id = (int)$userShop->usergroup_id;
			$usergroup_prices = 1;
		}
		//////////////////////
        $this->getPricesArray();                
        $this->product_price_wp = $this->product_price;
        $this->product_price_calculate = $this->getPriceWithParams();

        if (!empty($this->free_attribute_active) && $inkl_free_attr) {
            //$this->getListFreeAttributes();
            JSFactory::getModel('FreeAttrCalcPriceFront')->addErrorsAndReplaceFreeAttrsValsIfOutMinMaxQuota($this);
        }
        
        $dispatcher->triggerEvent('onBeforeCalculatePriceProduct', [&$prodQty, &$enableCurrency, &$enableUserDiscount, &$enableParamsTax, &$currentObj, &$cartProduct]);
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->onBeforeCalculatePriceProduct($prodQty, $enableCurrency, $enableUserDiscount, $enableParamsTax, $this, $cartProduct);

        if (!empty($this->product_is_add_price)) {
            $this->getAddPrices($usergroup_id, $usergroup_prices);
        } else {
            $this->product_add_prices = [];
        }
        
        $this->product_price_calculate1 = $this->product_price_calculate;
		
        $dispatcher->triggerEvent('onCalculatePriceProduct', [&$prodQty, &$enableCurrency, &$enableUserDiscount, &$enableParamsTax, &$currentObj, &$cartProduct]);
        ProductsFreeAttrsDefaultValuesMambot::getInstance()->onCalculatePriceProduct($prodQty, $enableCurrency, $enableUserDiscount, $enableParamsTax, $this, $cartProduct);
        $this->calculatePriceProduct($prodQty, $inkl_free_attr);
        $this->productQuantity = $prodQty ? $prodQty : 1;   
		
		if ((!isset($this->attribute_active_data->price) && !empty($jshopConfig->price_product_round)) || !empty($jshopConfig->price_product_round)) {
			if ($this->product_price_calculate>=1/pow(10,$jshopConfig->decimal_count)) $this->product_price_calculate = round($this->product_price_calculate, $jshopConfig->decimal_count);
		} else {
			$this->product_price_calculate = $this->attribute_active_data->price;
        }
        $dispatcher->triggerEvent('onCalculatePriceProduct2', [&$currentObj]);
        if ($enableCurrency) {
            $this->product_price_calculate = getPriceFromCurrency($this->product_price_calculate, $this->currency_id);
            $this->product_price_wp = getPriceFromCurrency($this->product_price_wp, $this->currency_id);
        }
        
        $this->total_price_without_tax = $this->product_price_calculate;
        if ($enableParamsTax) {
            $this->product_price_calculate = getPriceCalcParamsTax($this->product_price_calculate, $this->product_tax_id);
            $this->product_price_wp = getPriceCalcParamsTax($this->product_price_wp, $this->product_tax_id);
        }


        $nativeUploadPrice = $this->getNativeUploadPrice();		
        if ((!empty($nativeUploadPrice))&&(!$this->is_activated_price_per_consignment_upload_disable_quantity)) {
            $amountOfUploads = $this->getNativeAmountOfUploads();//echo "!!!".$amountOfUploads;
            $this->total_price_without_tax = $nativeUploadPrice->modifyPrice($this->total_price_without_tax, $amountOfUploads);
            $this->product_price_calculate = $nativeUploadPrice->modifyPrice($this->product_price_calculate, $amountOfUploads);
            $this->product_price_wp = $nativeUploadPrice->modifyPrice($this->product_price_wp, $amountOfUploads);
        }
        
        if ($enableUserDiscount && $userShop->percent_discount && $this->getUseUserDiscount()) {
            $this->product_price_default = $this->product_price_calculate;
            $this->product_price_calculate = getPriceDiscount($this->product_price_calculate, $userShop->percent_discount);
            $this->product_price_wp = getPriceDiscount($this->product_price_wp, $userShop->percent_discount);
        }

		$dispatcher->triggerEvent('onCalculatePriceProduct3', [&$jshopConfig]);
        if (!isset($jshopConfig->tax_before_quantity) || !$jshopConfig->tax_before_quantity) $this->product_price_calculate = roundPrice($this->product_price_calculate);//!!!!!!!!!!!!!!!! 
		$dispatcher->triggerEvent('onCalculatePriceProduct4', [&$jshopConfig]);
		
        if ($isFullPrice) {
            return $this->getPriceCalculate();
        }  
		$dispatcher->triggerEvent('onAfterTableProductGetPrice', [&$jshopConfig]);
        return $this->product_price_calculate;
    }
		
    protected function calculatePriceProduct($productQuantity, $inkl_free_attr = 1) 
    {
        $jshopConfig = JSFactory::getConfig();
        $dispatcher = \JFactory::getApplication();
        $modelOfAttrsFront = JSFactory::getModel('AttrsFront');
        $modelOfProductPriceTypeFront = JSFactory::getModel('ProductPriceTypeFront');
        $modelOfFreeAttrCalcPriceFront = JSFactory::getModel('FreeAttrCalcPriceFront');

        $activeAttrs = (isset($this->attribute_active) && is_array($this->attribute_active)) ? $this->attribute_active : [];

        $separatedDepentAndIndependAttrs = $modelOfAttrsFront->separateAttrsByTypes($activeAttrs);
        $dependentAttrs = $separatedDepentAndIndependAttrs->depends;
        $independentAttrs = $separatedDepentAndIndependAttrs->independs;

        $calculatedProductPriceType = 1;
        if ($modelOfFreeAttrCalcPriceFront->isProductHasAnyFreeAttributesForCalculation($this) && !$inkl_free_attr) {
            $calculatedProductPriceType = $modelOfProductPriceTypeFront->getCalcPriceType($this->product_price_type, []) ?: 1;
        }elseif($modelOfFreeAttrCalcPriceFront->isProductHasAnyFreeAttributesForCalculation($this)){
		    $calculatedProductPriceType = $modelOfProductPriceTypeFront->getCalcPriceType($this->product_price_type, $this->free_attribute_active) ?: 1;
        }

        if ($this->product_is_add_price == static::DISABLED) {
            $prodPrice = $this->attribute_active_data->price ?? $this->product_price;
            $this->attribute_active_data->price = $prodPrice * $calculatedProductPriceType;
        } else { /* Calculate if set price per consignment  */
			if (isset($this->productQty) && $this->productQty>$productQuantity) $qty=$this->productQty ? $this->productQty : 1; else $qty=$productQuantity ? $productQuantity : 1;

            $qtyFromFormula = $modelOfProductPriceTypeFront->calcQtyFromSelectedQtyFormula($this->qtydiscount, $qty, $calculatedProductPriceType);
            $pricePerConsignmentRow = $modelOfProductPriceTypeFront->getPricePerConsignmentRowByQty($this->product_add_prices, $qtyFromFormula);
            $true_discount = $pricePerConsignmentRow->true_discount ?? 0;
			$isDiscountAddConsignmentEmpty = empty($true_discount * 1);

            if (!empty($pricePerConsignmentRow->price_id)) {
                if (!$isDiscountAddConsignmentEmpty) {
                    $this->pricePerConsignmentDiscount = $true_discount;
                    $this->attribute_active_data->price *= $calculatedProductPriceType;
                } else {
                    $this->attribute_active_data->price = $pricePerConsignmentRow->price * $calculatedProductPriceType;
                }
            } else {
                $prodPrice = $this->attribute_active_data->price ?? $this->product_price;
                $this->attribute_active_data->price = $prodPrice * $calculatedProductPriceType;
            }
        }

        if (!empty($independentAttrs)) {
            $this->attribute_active_data->price = $modelOfAttrsFront->calcPriceOfIndepentent($this->attribute_active_data->price, $this->free_attribute_active, $this->product_id, $independentAttrs);
        }

        if (isset($this->pricePerConsignmentDiscount)) {
            $this->attribute_active_data->price = percentageReduction($this->attribute_active_data->price, $this->pricePerConsignmentDiscount);
        }

        $this->product_price_wp = $this->attribute_active_data->price;
        $this->product_price_calculate = $this->getPriceWithParams();

        if ($calculatedProductPriceType == 0) {
            if (!empty($this->product_mindestpreis) && ($this->product_price_calculate < $this->product_mindestpreis)) {
                $this->product_price_calculate = $this->product_mindestpreis;
            }

            return 0;
        }		
		
    }

    public function getAddPrices(int $usergroup_id = 0, int $usergroup_prices = 0)
    {
        $currentObj = $this;
        $smartShopConfig = JSFactory::getConfig();
        $productPriceOrm = JSFactory::getTable('productprice', 'jshop');
        $unitsOfMeasures = JSFactory::getAllUnits();
        $extProduct = $this->attribute_active_data->ext_data ?? 0;

        if (empty($this->add_price_unit_id)) {
            $this->add_price_unit_id = $smartShopConfig->product_add_price_default_unit;
        }

        if (!empty($extProduct)) {
            $this->original_price = $extProduct->product_price;
            $this->product_price = $extProduct->product_price;
            $this->min_price = $extProduct->min_price;

            if (!empty($extProduct->product_is_add_price)) {
                $extProduct->getAddPrices($usergroup_id, $usergroup_prices);
                $this->add_price_unit_id = $extProduct->add_price_unit_id;
                $this->product_add_prices = $extProduct->product_add_prices;
            }
        } 

        if (empty($extProduct->product_is_add_price)) {
            $this->product_add_prices = $productPriceOrm->getAddPricesFront($this->getProductId(), $usergroup_id, $usergroup_prices);
        }

        $unitOfMeasure = $unitsOfMeasures[$this->add_price_unit_id];
        $this->product_add_price_unit = $unitOfMeasure->name ?: JText::_('COM_SMARTSHOP_ST');

        $priceWithParams = $this->getPriceWithParams();

        $calcTotalPriceWithAttrsAndPricePerConsignment = function ($currentPrice, $pricePerConsignmentRow) {
            $pricePerConsignmentDiscount = null;
            $modelOfAttrsFront = JSFactory::getModel('AttrsFront');
            $modelOfProductPriceTypeFront = JSFactory::getModel('ProductPriceTypeFront');
            $modelOfFreeAttrCalcPriceFront = JSFactory::getModel('FreeAttrCalcPriceFront');

            $frontActiveAttrs = (isset($this->attribute_active) && is_array($this->attribute_active)) ? $this->attribute_active : [];
            $separatedDepentAndIndependAttrs = $modelOfAttrsFront->separateAttrsByTypes($frontActiveAttrs);
            $independentAttrs = $separatedDepentAndIndependAttrs->independs;

            $calculatedProductPriceType = 1;
            // if ($modelOfFreeAttrCalcPriceFront->isProductHasAnyFreeAttributesForCalculation($this)) {
            //     $calculatedProductPriceType = $modelOfProductPriceTypeFront->getCalcPriceType($this->product_price_type, $this->free_attribute_active) ?: 1;
            // }
            $isDiscountAddEmpty = empty($pricePerConsignmentRow->true_discount * 1);

            if (!$isDiscountAddEmpty) {
                $pricePerConsignmentDiscount = $pricePerConsignmentRow->true_discount;
                $currentPrice *= $calculatedProductPriceType;
            } else {
                $currentPrice = $pricePerConsignmentRow->price * $calculatedProductPriceType;
            }
            
            if (!empty($independentAttrs)) {
                $currentPrice = $modelOfAttrsFront->calcPriceOfIndepentent($currentPrice, $this->free_attribute_active, $this->product_id, $independentAttrs);
            }

            if (isset($pricePerConsignmentDiscount)) {
                $currentPrice = percentageReduction($currentPrice, $pricePerConsignmentDiscount);
            }

            return $currentPrice;
        };

        if (!empty($this->product_add_prices)) {
            foreach($this->product_add_prices as $key => $addPriceData) {
                
                $isDiscountAddEmpty = true;
                if ($smartShopConfig->product_price_qty_discount == static::ENABLED) {
                    $prodAddCalculatedPrice = $priceWithParams - $addPriceData->discount;
                } else {
                    $isDiscountAddEmpty = empty($this->product_add_prices[$key]->true_discount * 1);
                    $prodAddCalculatedPrice = $this->product_add_prices[$key]->price;
                }

                $this->product_add_prices[$key]->price_wp = $calcTotalPriceWithAttrsAndPricePerConsignment($priceWithParams, $addPriceData);
                $this->product_add_prices[$key]->price = null;
                if ($isDiscountAddEmpty) {
                    $this->product_add_prices[$key]->price = $prodAddCalculatedPrice;
                }
            }
        }

        \JFactory::getApplication()->triggerEvent('onAfterGetAddPricesProduct', [&$currentObj]);
    }

    public function getAddPriceWithDiscounts()
    {
        $addPrices = $this->product_add_prices;
        $userShop = JSFactory::getUserShop();
        $isUseUserDiscount = (!empty($userShop->percent_discount) && $this->getUseUserDiscount());

        foreach($addPrices as $addPrice) {
            $addPrice->price = getPriceCalcParamsTax($addPrice->price, $this->product_tax_id);
            $addPrice->price_wp = getPriceCalcParamsTax($addPrice->price_wp, $this->product_tax_id);

            if ($isUseUserDiscount) {
                $addPrice->price = getPriceDiscount($addPrice->price , $userShop->percent_discount);
                $addPrice->price_wp = getPriceDiscount($addPrice->price_wp , $userShop->percent_discount);
            }
        }
        

        return $addPrices;
    }
    
    public function fillInputFieldsPropertyForFreeAttrs()
    {
        $this->freeattributes = JSFactory::getModel('FreeAttrsFront')->fillInputFieldsProperty($this->freeattributes);
        return $this->freeattributes;
    }
    
    public function setFreeAttributeActive($freattribs)
    {
        $this->free_attribute_active = $freattribs;
    }
	
	public function setButtonsActive($buttons)
    {
        $this->buttons = $buttons;
    }
    
    public function getData($field)
    {
        return $this->attribute_active_data->ext_data->$field ?: $this->$field;
    }
    
    public function getRequireAttribute()
    {
        $currentObj=$this;
		$dispatcher = \JFactory::getApplication();          
        $dispatcher->triggerEvent('onProductTableBeforegetRequireAttribute', [&$currentObj]);
		return JSFactory::getModel('AttrsFront')->getRequireAttrsIdsByProdId($this->getProductId());
    }
    
    //get dependent attributs
    public function getAttributes($isUseExpirationDate = true)
    {
        return JSFactory::getModel('AttrsFront')->getDependAttrs($this->getProductId(), $isUseExpirationDate);
    }
    
    //get independent attributs
    public function getAttributes2()
    {
        return JSFactory::getModel('ProductAttrs2Front')->getIndependAttrs($this->getProductId());
    }   
    
    //get attrib values
    public function getAttribValue(int $attr_id, array $other_attr = [], int $onlyExistProduct = 0, int $useSortFromDragAndDrop = 0): array
    {
        return JSFactory::getModel('AttrsFront')->getAttrsValsByProdAndAttrIds($this->product_id, $attr_id, $other_attr, $onlyExistProduct, $useSortFromDragAndDrop);
    }
    
    public function getAttributesDatas(?array $selectedAttrs = [], ?int $usergroupId = null)
    {
        $productId = $this->getProductId() ?: 0;
        $attrsData = JSFactory::getModel('AttrsFront')->getActiveData($productId, $selectedAttrs);

        if (isset($usergroupId) && !empty($attrsData['attributeValues'])) {

            foreach ($attrsData['attributeValues'] as $attrId => $prodAttrs) {
                if (!empty($prodAttrs)) {
                    foreach ($prodAttrs as $key => $attr) {
                        $attrUsergroups =  (isset($attr->usergroup_show_product) && trim($attr->usergroup_show_product) != '') ? explode(' , ', $attr->usergroup_show_product) : [];
                        $isDeletedActiveAttr = false;
                        $isDeletedSelectedAttr = false;

                        if (!empty($attr->is_use_additional_usergroup_permission) && !in_array($usergroupId, $attrUsergroups) && $attrUsergroups['0'] != '*') {

                            if (isset($attrsData['attributeActive'][$attrId]) && $attrsData['attributeActive'][$attrId] == $attrsData['attributeValues'][$attrId][$key]->val_id) {
                                unset($attrsData['attributeActive'][$attrId]);
                                $isDeletedActiveAttr = true;
                            }

                            if (isset($attrsData['attributeSelected'][$attrId]) && $attrsData['attributeSelected'][$attrId] == $attrsData['attributeValues'][$attrId][$key]->val_id) {
                                unset($attrsData['attributeSelected'][$attrId]);
                                $isDeletedSelectedAttr = true;
                            }

                            unset($attrsData['attributeValues'][$attrId][$key]);

                            if ($isDeletedActiveAttr && !empty($attrsData['attributeValues'][$attrId])) {
                                $attrsData['attributeActive'][$attrId] = reset($attrsData['attributeValues'][$attrId])->val_id;
                            }

                            if ($isDeletedSelectedAttr && !empty($attrsData['attributeValues'][$attrId])) {
                                $attrsData['attributeSelected'][$attrId] = reset($attrsData['attributeValues'][$attrId])->val_id;
                            }

                            if (empty($attrsData['attributeValues'][$attrId])) {
                                unset($attrsData['attributeValues'][$attrId]);
                            }

                            $attrsData['attributeValues'][$attrId] = array_values($attrsData['attributeValues'][$attrId]);
                        }
                    }
                }
            }

        }

        return $attrsData;
    }
    
    public function getPIDCheckQtyValue(): string
    {
        if (isset($this->attribute_active_data->product_attr_id)) {
            return 'A:' . $this->attribute_active_data->product_attr_id;
        }

        return 'P:' . $this->product_id;
    }
    
    public function getAdditionalProductId()
    {
        return $this->attribute_active_data->ext_data->product_id ?: 0;
    }
        
    public function isUseAdditionalFreeAttrs() 
    {
        $isUseAdditionalFreeAttrs = $this->attribute_active_data->ext_data->is_use_additional_free_attrs ?? null;
        return (isset($isUseAdditionalFreeAttrs) && $isUseAdditionalFreeAttrs == 1);
    }
    
    public function getListFreeAttributes($isGetAdditionalFreeAttrsIfIsActivated = true)
    {
        $product_id = ($isGetAdditionalFreeAttrsIfIsActivated && $this->isUseAdditionalFreeAttrs() && !empty($this->getAdditionalProductId())) ? $this->getAdditionalProductId() : $this->product_id;
        $this->freeattributes = JSFactory::getModel('ProductsFreeAttrsFront')->getFreeAttrsByProductId($product_id);
        return $this->freeattributes;
    }
    
    /**
    * use after getListFreeAttributes()
    */
    public function getRequireFreeAttribute()
    {
       return JSFactory::getModel('FreeAttrsFront')->parseRequireFreeAttrs($this->freeattributes);
    }

    public function getCategories($type_result = 0)
    {
        if (!isset($this->product_categories) && !empty($this->getProductId())) {
            $this->product_categories = JSFactory::getModel('ProductsToCategoriesFront')->getByProductId($this->getProductId());
        }

        return ($type_result == 1) ? getListSpecifiedAttrsFromArray($this->product_categories, 'category_id') : $this->product_categories;
    }

    public function getName() 
    {
        $name = JSFactory::getLang()->get('name');
        return $this->$name;
    }

    public function getPriceWithParams()
    { 
        return $this->attribute_active_data->price ?? $this->product_price;
    }
    
    public function getEan()
    {  
        return $this->attribute_active_data->ean ?? $this->product_ean;
    }
    
    public function getQty()
    {
		$jshopConfig = JSFactory::getConfig();
		//if (!$this->product_quantity && !$jshopConfig->stock) return 1;
        if ($this->unlimited) {
            return 1;
        }
		if($this->attribute_active_data && isset($this->attribute_active_data->unlimited) && $this->attribute_active_data->unlimited){
			return INF;
		}else{
			return $this->attribute_active_data->count ?? $this->product_quantity;		
		}
    }
    
    public function getWeight(bool $includeFormula = true)
    {   
		$weight = isset($this->attribute_active_data->weight) ? $this->attribute_active_data->weight : $this->product_weight;
		
        if ($includeFormula) {
            return $this->calculateWeightByFormula($weight) ?? $weight;
        }
		
        return $weight;
    }

    protected function calculateWeightByFormula($weight = null)
    {
        $calculatedProductPriceType = null;
        $modelOfFreeAttrCalcPriceFront = JSFactory::getModel('FreeAttrCalcPriceFront');
        $modelOfProductPriceTypeFront = JSFactory::getModel('ProductPriceTypeFront');

        if ($modelOfFreeAttrCalcPriceFront->isProductHasAnyFreeAttributesForCalculation($this)) {
            $calculatedProductPriceType = $modelOfProductPriceTypeFront->getCalcPriceType($this->product_price_type, $this->free_attribute_active) ?: 1;
        }

        if (isset($calculatedProductPriceType)) {
            $prodQty = $this->productQuantity ? $this->productQuantity : 1;
			if (isset($weight)){
				$weight = $prodQty * ($weight);
			}else{
				$weight = $prodQty * ($this->product_weight);
			}

            return $calculatedProductPriceType * $weight;
        }
    }
    
    public function getWeight_volume_units()
    {
        if (isset($this->attribute_active_data->weight_volume_units) && $this->attribute_active_data->weight_volume_units > 0) {
            return $this->attribute_active_data->weight_volume_units;
        }

        return $this->weight_volume_units;
    }
    
    public function getQtyInStock()
    {
        if ($this->unlimited) {
            return 1;
        }

        $qtyInStock = floatval($this->getQty());

        if ($qtyInStock < 0) {
            $qtyInStock = 0;
        }

        return $qtyInStock;
    }
    
    public function getOldPrice()
    {
        return $this->attribute_active_data->old_price ?? $this->product_old_price;
    }

    public function getImages()
    {
        $this->getMedia();
    }

    public function getMedia()
    {
        $productId = !empty($this->attribute_active_data->ext_data->is_use_additional_media) ? $this->attribute_active_data->ext_data->product_id : $this->product_id;

        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
        $modelOfProductMediaFront = JSFactory::getModel('ProductsMediaFront');
        $allMedia = $modelOfProductMediaFront->getByProductId($productId) ?: [];

        return $allMedia;
    }

    public function getUrlOfMainImage()
    {
        $shopConfig = JSFactory::getConfig();
        $imgName = JUri::base() . $shopConfig->path_to_no_img;
        if (!empty($this->attribute_active_data->ext_data->is_use_additional_media) && !empty($this->attribute_active_data->ext_data->image)) {
            $imgName = $this->attribute_active_data->ext_data->image;
        } elseif (!empty($this->image)) {
            $imgName = $this->image;
        }

        return getPatchProductImage($imgName, '', 1);
    }

    public function getVideos(): array
    {
        return [];
    }
    
    public function getFiles()
    {
        if (!JSFactory::getConfig()->admin_show_product_demo_files && !JSFactory::getConfig()->admin_show_product_sale_files) {
            return [];
        }

        if (!empty($this->attribute_active_data->ext_data)) {
            $list = $this->attribute_active_data->ext_data->getFiles();

            if (!empty($list)) {
                return $list;
            }
        }

        return JSFactory::getModel('ProductsFilesFront')->getFilesByProductId($this->getProductId());
    }
    
    public function getDemoFiles()
    {
        if (!JSFactory::getConfig()->admin_show_product_demo_files) {
            return [];
        }

        $modelOfProductsFilesFront = JSFactory::getModel('ProductsFilesFront');
        $productId = !empty(isset($this->attribute_active_data->ext_data->is_use_additional_files) && $this->attribute_active_data->ext_data->is_use_additional_files && !empty($this->attribute_active_data->ext_data->product_id)) ? $this->attribute_active_data->ext_data->product_id : $this->product_id;
        $list = $modelOfProductsFilesFront->getDemoFilesByProductId($productId) ?: [];

        return $list;
    }
    
    public function getSaleFiles()
    {
        if (!JSFactory::getConfig()->admin_show_product_sale_files) {
            return [];
        }

        $modelOfProductsFilesFront = JSFactory::getModel('ProductsFilesFront');
        $productId = !empty($this->attribute_active_data->ext_data->is_use_additional_files && !empty($this->attribute_active_data->ext_data->product_id)) ? $this->attribute_active_data->ext_data->product_id : $this->product_id;
        $list = $modelOfProductsFilesFront->getFilesByProductId($productId) ?: [];

        return $list ?: [];
    }
    
    public function getManufacturerInfo()
    {
        $manufacturers = JSFactory::getAllManufacturer();

        if (isset($manufacturers[$this->product_manufacturer_id])) {
            return $manufacturers[$this->product_manufacturer_id];
        }
    }
    
    public function getVendorInfo()
    {
        $vendors = JSFactory::getAllVendor();

        if (isset($vendors[$this->vendor_id])) {
            return $vendors[$this->vendor_id];
        }
    }

    /**
    * get first category for product
    */    
    public function getCategory(bool $supportExtAttr = true) 
    {
        return JSFactory::getModel('ProductsToCategoriesFront')->getFirstProductCategory($this->product_id);
    }
    
    public function getFullQty()
    {
        if ($this->unlimited) {
            return 1;
        }

        $tmp = JSFactory::getModel('ProductAttrsFront')->getSqlFunctionsResultByProdId($this->getProductId());

        if ($tmp->countattr > 0) {
			return $tmp->qty;
        }

        return $this->product_quantity;
    }
    
    public function getMinimumPrice()
    {
        $jshopConfig = JSFactory::getConfig();
        $min_price = $this->product_price;

        $tmp = JSFactory::getModel('ProductAttrsFront')->getSqlFunctionsResultByProdId($this->getProductId());

        if ($tmp->countattr > 0) {
            $min_price = $tmp->min_price;
        }
        
        $productIndepAttrs = JSFactory::getModel('ProductAttrs2Front')->getByproductId($this->getProductId());
        
        if (!empty($productIndepAttrs)) {
            $tmpprice = [];

            foreach($productIndepAttrs as $val) {

                if ($val->price_type == ONE_TIME_COST_PRICE_TYPE_ID) {
                    continue;
                }

                $tmpprice[] = getModifyPriceByMode($val->price_mod, $min_price, $val->addprice);
            }

            $min_price = min($tmpprice);
        }

        $max_discount = JSFactory::getModel('ProductsPricesFront')->getSqlFunctionsResultByProdId($this->getProductId())->max_discount ?: null;

        if ($max_discount) {
            if ($jshopConfig->product_price_qty_discount == 1) {
                $min_price = $min_price - $max_discount;
            } else {
                $min_price = $min_price - ($min_price * $max_discount / 100);
            }
        }

        return $min_price;
    }
    
    public function getExtendsData($firstInput = true, $ink_free_attr = true)
    {
		$product_weight=$this->product_weight;
        if ($firstInput) $this->getRelatedProducts();
        $this->getDescription();
        $this->getTax();

        $this->getPricePreview($ink_free_attr);
        $this->getDeliveryTime();
        $this->getPricesArray();
		$this->product_weight=$product_weight;
    }


    public function getPricesArray()
    {
        $jshopConfig = JSFactory::getConfig(); 
		$quantity = $this->product_quantity;

        if ($quantity == 0) {
            $quantity = 1;
        }
		
		$currentObj = $this;
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onProductTableBeforegetPricesArray', array(&$currentObj));
		if (!isset($currentObj->skip)||($currentObj->skip!=1)){
			$taxes = JSFactory::getAllTaxes();
			$this->product_tax = $taxes[$this->product_tax_id];
		}
        

        if ($jshopConfig->display_price_admin == 1) {          
            $prices['price_without_vat'] = $this->product_price;
            $prices['price_additional_vat_to_single_price'] = $this->product_price * ($this->product_tax / 100);
            $prices['price_including_vat'] = $prices['price_without_vat'] + $prices['price_additional_vat_to_single_price'];
            $prices['total_price_without_vat'] = $this->product_price * $quantity;
            $prices['total_price_additional_vat_to_single_price'] = ($this->product_price * ($this->product_tax / 100)) * $quantity;
        } else {
            $prices['price_without_vat'] = $this->product_price / ((100 + $this->product_tax) / 100);
            $prices['price_additional_vat_to_single_price'] = $this->product_price-$this->product_price / ((100 + $this->product_tax) / 100);
            $prices['price_including_vat'] = $this->product_price;
            $prices['total_price_without_vat'] = (($this->product_price * $quantity) / ((100 + $this->product_tax) / 100));
            $prices['total_price_additional_vat_to_single_price'] = ($this->product_price * $quantity)-$prices['total_price_without_vat'];
            
        }

        $prices['total_price_including_vat'] = ($prices['total_price_without_vat'] + $prices['total_price_additional_vat_to_single_price']);
        $prices['total_price_additional_one_time_cost_attrs'] = $this->calcAttrsWithOneTimeCostPriceType();
        $this->prices_variables = $prices;

        return $this->prices_variables;
    }

    public function getDeliveryTimeId($globqty = 0)
    {
        $jshopConfig = JSFactory::getConfig();
        $qty = $this->getQty();

        if ($globqty) {
            $qty = $this->product_quantity;
        }

        if ($jshopConfig->hide_delivery_time_out_of_stock && $qty <= 0) {
            $this->delivery_times_id = 0;
        }

        return $this->delivery_times_id;
    }
    
    public function getDeliveryTime($globqty = 0)
    {
        $jshopConfig = JSFactory::getConfig();
        $deliveryTimeId = $this->getDeliveryTimeId($globqty);
        $this->delivery_time = '';

        if ($jshopConfig->delivery_times_on_product_page && $deliveryTimeId) {
            $deliveryTimes = JSFactory::getTable('deliveryTimes', 'jshop');
            $deliveryTimes->load($deliveryTimeId);
            $this->delivery_time = $deliveryTimes->getName();
        }

        return $this->delivery_time;
    }

    public function getDescription() 
    {
        $lang = JSFactory::getLang();
        $name = $lang->get('name');
        $short_description = $lang->get('short_description');
        $description = $lang->get('description');
        
        $this->name = $this->$name;
        $this->short_description = JHtml::_('content.prepare', str_replace('src="images/','src="/images/',$this->$short_description));
        $this->description = JHtml::_('content.prepare', str_replace('src="images/','src="/images/',$this->$description));        
	}

    public function getTexts() 
    {
        $texts = new stdClass();
        $lang = JSFactory::getLang();
        $name = $lang->get('name');
        $currentObj = $this;
        $short_description = $lang->get('short_description');
        $description = $lang->get('description');

        $productEssence = !empty($this->attribute_active_data->ext_data->is_use_additional_description) ? $this->attribute_active_data->ext_data : $this;
        
        $texts->name = $productEssence->$name ?: '';
        $texts->short_description = str_replace('src="images/','src="/images/', $productEssence->$short_description) ?: '';
        $texts->description = str_replace('src="images/','src="/images/', $productEssence->$description) ?: '';  

        \JFactory::getApplication()->triggerEvent('onBeforeGetProductTexts', [&$texts, &$currentObj]);

        return $texts;
    }
    
    public function getPricePreview($ink_free_attr = true)
    {
		$qty = 1;
		if(isset($this->qty)){
			$qty = $this->qty;
		}elseif(isset($this->min_count_product)){
			$qty = $this->min_count_product;
		}
        
        $this->product_price_calculate = $this->getPrice($qty, 1, 1, 1, [], false, $ink_free_attr);

        if ($this->product_is_add_price) {
            $this->product_add_prices = array_reverse($this->product_add_prices);
        }

        $this->updateOtherPricesIncludeAllFactors();
    }
    
    public function getUseUserDiscount()
    {
        return (JSFactory::getConfig()->user_discount_not_apply_prod_old_price && $this->product_old_price > 0) ? 0 : 1;
    }
    
    public function getExtAttributeData($pid)
	{
		$product = JSFactory::getTable('product', 'jshop');
        $product->load($pid);
        
		return $product;
    }
		 
    public function getPriceCalculate($quantity=0,$get_price_calc=0)
    {
        $quantity = $quantity ?: $this->min_count_product ?: 1;

        if (empty($this->product_price_calculate)||$get_price_calc==1) {
            $this->getPrice($quantity);
        }

        return $this->calcAttrsWithOneTimeCostPriceType($quantity);
    }

    public function getTempData($product)
    {
        $temp = [
            'list' => [],
            'total_price' => 0
        ];

        if (!empty($product->temp_data)) {
            $temp = json_decode($product->temp_data, true);
        }
        
        return $temp;
    }

    protected function calcAttrsWithOneTimeCostPriceType($quantity=0)
    {
		if ($quantity==0){
			$quantity=$this->product_quantity;
		}
		$product_price_calculate = $this->product_price_calculate ?? 0;
        $fullProductPrice = $product_price_calculate * $quantity;
		$userShop = JSFactory::getUserShop();
		JSFactory::getModel('UsersFront')->checkClientType($userShop);
		$client_type=$userShop->client_type ?? 0;
		$jshopConfig = JSFactory::getConfig();
		$addTax=1;
		if ($client_type == 2) {
			if ($jshopConfig->display_price_front_current == 1) {
				$addTax=0;
			}
		}
        $prodAttr2Table = JSFactory::getTable('ProductAttribut2');
        $sumOfAttrOneTimeCostPriceType = $prodAttr2Table->calcAttrsWithOneTimeCostPriceType($this->getProductId(false), $this->attribute_active ?? [], $fullProductPrice,$addTax);

        return $sumOfAttrOneTimeCostPriceType;
    }
    
    public function getBasicPriceInfo()
    {
        $currentObj = $this;
        $this->product_basic_price_show = ($this->weight_volume_units != 0);

        if (!$this->product_basic_price_show) {
            return 0;
        }

        $jshopConfig = JSFactory::getConfig();
        $units = JSFactory::getAllUnits();
        $unit = $units[$this->basic_price_unit_id];        

        if ($jshopConfig->calc_basic_price_from_product_price) {
            $this->product_basic_price_wvu = $this->weight_volume_units;
        } else {
            $this->product_basic_price_wvu = $this->getWeight_volume_units();
        }

        $this->product_basic_price_weight = $unit->qty > 0 ? $this->product_basic_price_wvu / $unit->qty : $this->product_basic_price_wvu;

        if ($jshopConfig->calc_basic_price_from_product_price) {
            $this->product_basic_price_calculate = $this->product_basic_price_weight > 0 ? $this->product_price_wp / $this->product_basic_price_weight : $this->product_price_wp;
        } else {
            $this->product_basic_price_calculate = $this->product_basic_price_weight > 0 ? $this->product_price_calculate1 / $this->product_basic_price_weight : $this->product_price_calculate1;
        }

        $this->product_basic_price_unit_name = $unit->name;
        $this->product_basic_price_unit_qty = $unit->qty;
        \JFactory::getApplication()->triggerEvent('onAfterGetBasicPriceInfoProduct', [&$currentObj]);
        
        return 1;
    }
    
    public function getBasicPrice()
    {
        if (!isset($this->product_basic_price_wvu)) {
            $this->getBasicPriceInfo();
        }

        return $this->product_basic_price_calculate ?? 0;
    }
    
    public function getBasicWeight()
    {
        if (!isset($this->product_basic_price_wvu)) {
            $this->getBasicPriceInfo();
        }

        return $this->product_basic_price_weight;
    }
    
    public function getBasicPriceUnit()
    {
        if (!isset($this->product_basic_price_wvu)) {
            $this->getBasicPriceInfo();
        }

        return $this->product_basic_price_unit_name;
    }
    
    public function getTax()
    {
        $currentObj = $this;
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeGetTaxProductBefore', [&$currentObj]);
		if (!isset($currentObj->skip)||($currentObj->skip!=1)){
			$taxes = JSFactory::getAllTaxes();
			$product_tax_id = $this->product_tax_id ?: array_key_first($taxes);
			$this->product_tax = $taxes[$product_tax_id];        
			$dispatcher->triggerEvent('onBeforeGetTaxProduct', [&$currentObj]);
		}
        return $this->product_tax;
    }
    
    public function updateOtherPricesIncludeAllFactors()
    {
        $currentObj = $this;
        $userShop = JSFactory::getUserShop();
        
        $this->product_old_price = $this->getOldPrice();
        $this->product_old_price = getPriceFromCurrency($this->product_old_price, $this->currency_id);
        $this->product_old_price = getPriceCalcParamsTax($this->product_old_price, $this->product_tax_id);

        if ($this->getUseUserDiscount()) {
            $this->product_old_price = getPriceDiscount($this->product_old_price, $userShop->percent_discount);
        }
        
        if (is_array($this->product_add_prices)) {
            foreach ($this->product_add_prices as $key => $value) {
                $this->product_add_prices[$key]->price = getPriceFromCurrency($this->product_add_prices[$key]->price, $this->currency_id);
                $this->product_add_prices[$key]->price = getPriceCalcParamsTax($this->product_add_prices[$key]->price, $this->product_tax_id);

                if ($this->getUseUserDiscount()) {
                    $this->product_add_prices[$key]->price = getPriceDiscount($this->product_add_prices[$key]->price, $userShop->percent_discount);
                }
            }
        }

        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('updateOtherPricesIncludeAllFactors', [&$currentObj]);
    }

    public function getExtraFields($type = 1)
    {
        return $this->getCharacteristics($type);
    }

    public function getCharacteristics($type = 1)
    {
        $categoriesIds = getListSpecifiedAttrsFromArray($this->getCategories(), 'category_id');

        $jshopConfig = JSFactory::getConfig();
        $hide_fields = $jshopConfig->getProductHideExtraFields();
        $cart_fields = $jshopConfig->getCartDisplayExtraFields();
        $fieldvalues = JSFactory::getAllProductExtraFieldValue();
        $fieldvaluesDetail = JSFactory::getAllProductExtraFieldValueDetail();
        $fieldvaluesDetails = JSFactory::getAllProductExtraFieldValueDetails();
        $allCharacteristics = JSFactory::getAllProductExtraField();

        $prodCharacts = [];
        foreach($allCharacteristics as $key=>$prodCharact) {
            if (($type == 1 && in_array($prodCharact->id, $hide_fields)) || ($type == 2 && !in_array($prodCharact->id, $cart_fields))) {
                continue;
            }
            if ($prodCharact->allcats == 1) {
                $prodCharacts[] = $prodCharact;
            } else {
                $insert = false;

                foreach($categoriesIds as $categoryId) {
                    if (in_array($categoryId, $prodCharact->cats)) {
                        $insert = true;
                    }
                }

                if ($insert) {
                    $prodCharacts[] = $prodCharact;
                }
            }
        }

        $rows = [];
        $grname = '';
        $productWithActiveCharacteristics = (!empty($this->attribute_active_data->ext_data->is_use_additional_characteristics)) ? $this->attribute_active_data->ext_data : $this;
        foreach($prodCharacts as $field) {
            $field_id = $field->id;
            $field_name = 'extra_field_' . $field_id;
            $value = $productWithActiveCharacteristics->$field_name ?? null;

            if ($field->type == 0) {
                if (!empty($productWithActiveCharacteristics->$field_name)) {
                    $listid = explode(',', $productWithActiveCharacteristics->$field_name);
                    $tmp = [];

                    foreach($listid as $extrafiledvalueid) {
                        $tmpField = isset($fieldvalues[$extrafiledvalueid]) ? $fieldvalues[$extrafiledvalueid] : '';
                        if(isset($fieldvaluesDetails[$field->id][$extrafiledvalueid]->image) && $fieldvaluesDetails[$field->id][$extrafiledvalueid]->image) {
                            $tmpField .= "<span><img class='extrafiledvalueimg' src='" . getPatchProductImage($fieldvaluesDetails[$field->id][$extrafiledvalueid]->image, '', 1) . "' ></span>";
                        }
                        $tmp[] = $tmpField;
                    }

                    $extra_field_value = implode($jshopConfig->multi_charactiristic_separator, $tmp);
                    $value = $extra_field_value;
                }
            }

            if (!empty($productWithActiveCharacteristics->$field_name)) {
                $temp = [
                    'id' => $field_id, 
                    'name' => $allCharacteristics[$field_id]->name, 
                    'description' => $allCharacteristics[$field_id]->description, 
                    'groupname' => $allCharacteristics[$field_id]->groupname,
                    'value' => $value,
                    //'display' => $display,
                    'grshow' => 0,
                    'image' => $allCharacteristics[$field_id]->image ?? ''
                ];
                $temp['display'] = separateExtraFieldsWithUseHideImageCharactParams([$temp], 'product');

                if ($temp['groupname'] != $grname) {
                    $grname = $temp['groupname'];
                    $temp['grshow'] = 1;
                }

                $rows[] = $temp;
            }

        }
        
        return $rows;
    }
    
    public function getRelatedProducts()
    {
        if (!JSFactory::getConfig()->admin_show_product_related) {
            $this->product_related = [];

            return $this->product_related;
        }

        $activeProduct = $this->getActiveProduct();
        $productId = (!empty($activeProduct->parent_id) && !empty($activeProduct->is_use_additional_related_products)) ? $activeProduct->product_id : $this->product_id;
		
		$currentObj = $this;
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeGetRelatedProducts', [&$currentObj, $productId]);
		
		
		
        $this->product_related = JSFactory::getModel('ProductsRelationsFront')->getRelatedProducts($productId);

        if(!empty($this->product_related)){
            foreach($this->product_related as $k=>$product) {
                $this->product_related[$k]->isShowCartSection = $product->isShowCartSection();
            }
        }

        return $this->product_related;
    }
    
    public function getLastProducts($count, $array_categories = null, $filters = [])
    {
        return JSFactory::getModel('ProductsFront')->getLastProducts($count, $array_categories, $filters);
    }
    
    public function getRandProducts($count, $array_categories = null, $filters = [])
    {
        return JSFactory::getModel('ProductsFront')->getRandProducts($count, $array_categories, $filters);
    }    
    
    public function getBestSellers($count, $categories = null, $filters = [])
    {
        return JSFactory::getModel('ProductsFront')->getBestSellers($count, $categories, $filters);
    }

	public function getCustom($count, $array_categories = null, $filters = [], $order_query = 'ORDER BY name')
    {
        return JSFactory::getModel('ProductsFront')->getCustom($count, $array_categories, $filters, $order_query);
    }
    
    public function getProductLabel($label_id, $count, $array_categories = null, $filters = [], $order_query = 'ORDER BY name')
    {
        return JSFactory::getModel('ProductsFront')->getProdsWithLabels($label_id, $count, $array_categories, $filters, $order_query);
    }
    
    public function getTopRatingProducts($count, $array_categories = null, $filters = [])
    {
        return JSFactory::getModel('ProductsFront')->getTopRatingProds($count, $array_categories, $filters);
    }
    
    public function getTopHitsProducts($count = null, $array_categories = null, $filters = [])
    { 
        return JSFactory::getModel('ProductsFront')->getTopHitsProds($count, $array_categories, $filters); 
    }
    
    public function getAllProducts($filters = [], $order = null, $orderby = null, $limitstart = 0, $limit = 0)
    {
        return JSFactory::getModel('ProductsFront')->getAllProducts($filters, $order, $orderby, $limitstart, $limit);
    }     
    
    public function getCountAllProducts($filters = null) 
    {
        return JSFactory::getModel('ProductsFront')->getCount($filters);
    }

    public function getReviews($limitstart = 0, $limit = 20) 
    {
        $currentObj = $this;
        $reviews = JSFactory::getModel('ProductsReviewsFront')->getReviewsByProductId($this->product_id, 1, $limitstart, $limit);
        \JFactory::getApplication()->triggerEvent('onAfterGetReviewsProduct', array(&$currentObj, &$reviews, &$limitstart, &$limit));

        return $reviews;
    }
    
    public function getReviewsCount()
    {
        $currentObj = $this;
        $reviewsCount = JSFactory::getModel('ProductsReviewsFront')->getReviewsCountByProductId($this->_db->escape($this->product_id));
        \JFactory::getApplication()->triggerEvent('onAfterGetReviewsCountProduct', [&$currentObj, &$reviewsCount]);

        return $reviewsCount;
    }

    public function getAverageRating() 
    {
        $currentObj = $this;
        $ratingAvr = JSFactory::getModel('ProductsReviewsFront')->getAverageRatingByProductId($this->_db->escape($this->product_id));
        \JFactory::getApplication()->triggerEvent('onAfterGetAverageRatingProduct', [&$currentObj, &$ratingAvr]);

        return $ratingAvr;
    }
    
    public function loadAverageRating()
    {
        $this->average_rating = $this->getAverageRating();

        if (!$this->average_rating) {
            $this->average_rating = 0;
        }
    }
    
    public function loadReviewsCount()
    {
        $this->reviews_count = $this->getReviewsCount();
    }
    
    public function getBuildSelectAttributes(array $attributeValues, array &$attributeActive, $isEnabledExcludeAttr = true)
    {
        return JSFactory::getModel('AttrsFront')->buildSelectAttributes($attributeValues, $attributeActive, $this->currency_id, $this->product_tax_id, $isEnabledExcludeAttr);
    }

    public function getHtmlDisplayProdAttrImg(int $attr_id, ?string $img): string
    {
        return JSFactory::getModel('AttrsFront')->generateHtmlImgOfProdAttr($attr_id, $img);
    }

    public function setWeightForActiveAttr(&$attribs, &$product)
    {
        if (!is_null($product->attribute_active_data) && is_object($product->attribute_active_data)) {
            if (!isset($product->attribute_active_data->weight)) {
                       $product->attribute_active_data->weight = $product->product_weight;
           }

            JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_jshopping/models/');            
            $product->attribute_active_data->weight += JSFactory::getModel('Attribut')->getExtendedWeight($product->product_id, $attribs);
        }
    }

    public function getDefaultValueForFreeAttribute($attr_id, $isGetAdditionalValsOfFreeAttr = false)
    {
        $productId = ($this->isUseAdditionalFreeAttrs() && !empty($this->getAdditionalProductId()) && $isGetAdditionalValsOfFreeAttr) ? $this->getAdditionalProductId() : $this->product_id;
        return JSFactory::getModel('FreeAttrDefaultValuesFront')->getDefaultValueByProductAndAttrIds($productId, $attr_id);
    }
    
    public function transformDescrTextToModule()
    {
        if (!empty($this->description)) {
            $this->description = JHtml::_('content.prepare', $this->description);
        }

        if (!empty($this->short_description)) {
            $this->short_description = JHtml::_('content.prepare', $this->short_description);
        }
    }

    public function getEssenceWithActivePricesPerCons()
    {
        $result = null;

        if (!empty($this->attribute_active_data->ext_data->product_is_add_price)) {
            $result = $this->attribute_active_data->ext_data;
        } elseif (!empty($this->product_is_add_price)) {
            $result = $this;
        }

        return $result;
    }

    public function getEssenceWithActiveUpload()
    {
        $result = new stdClass;
        $isAdditionalProductSupportUploads = !empty($this->attribute_active_data->ext_data->product_id) && $this->attribute_active_data->ext_data->is_allow_uploads && ($this->attribute_active_data->ext_data->is_unlimited_uploads || $this->attribute_active_data->ext_data->max_allow_uploads >= 1);
        $isProductSupportUploads = $this->is_allow_uploads && ($this->is_unlimited_uploads || $this->max_allow_uploads >= 1);
        
        if (!empty($this->attribute_active_data->ext_data->is_use_additional_customize)) {
            if ($isAdditionalProductSupportUploads) {
                $result = $this->attribute_active_data->ext_data;
            }
        } elseif ($isProductSupportUploads) {
            $result = $this;
        }

        return $result;
    }

    public function getUsergroupPermissions()
    {
        $user = JSFactory::getUser();
        $result = new stdClass();
        $essenceWithUsergroupPermission = $this;

        if (!empty($this->attribute_active_data->ext_data->product_id) && !empty($this->attribute_active_data->ext_data->is_use_additional_usergroup_permission) ) {
            $essenceWithUsergroupPermission = $this->attribute_active_data->ext_data;
        }

        $result->product_id = $essenceWithUsergroupPermission->product_id;
        $result->usergroup_show_product = (isset($essenceWithUsergroupPermission->usergroup_show_product) && trim($essenceWithUsergroupPermission->usergroup_show_product) != '') ? $essenceWithUsergroupPermission->usergroup_show_product  : null;
        $result->usergroup_show_price = (isset($essenceWithUsergroupPermission->usergroup_show_price) && trim($essenceWithUsergroupPermission->usergroup_show_price) != '') ? $essenceWithUsergroupPermission->usergroup_show_price : null;
        $result->usergroup_show_buy = (isset($essenceWithUsergroupPermission->usergroup_show_buy) && trim($essenceWithUsergroupPermission->usergroup_show_buy) != '') ? $essenceWithUsergroupPermission->usergroup_show_buy : null;

        $result->is_usergroup_show_product = isset($result->usergroup_show_product) && (in_array($user->usergroup_id, explode(' , ', $result->usergroup_show_product)) || $result->usergroup_show_product == '*') ? true : false;
        $result->is_usergroup_show_price = isset($result->usergroup_show_price) && (in_array($user->usergroup_id, explode(' , ', $result->usergroup_show_price)) || $result->usergroup_show_price == '*') ? true : false;
        $result->is_usergroup_show_buy = isset($result->usergroup_show_buy) && (in_array($user->usergroup_id, explode(' , ', $result->usergroup_show_buy)) || $result->usergroup_show_buy == '*') ? true : false;

        return $result;
    }

    /**
     * @return - return current product if exists or additional product
     */
    public function getActiveProduct()
    {
        $result = $this;

        if (isset($this->attribute_active_data->ext_data) && $this->attribute_active_data->ext_data->product_id) {
            $result = $this->attribute_active_data->ext_data;
        }

        return $result;
    }

    public function isShowCartSection(): bool
    {
        $jshopConfig = JSFactory::getConfig();
        $productWithUpload = !empty($this->getEssenceWithActiveUpload()->product_id) ? $this->getEssenceWithActiveUpload() : $this;
        $activeProduct = $this->getActiveProduct();
        $productShowCartFieldStatus = $activeProduct->product_show_cart;
        $isAdditionalProductWithDisabledCustomizeField = (!empty($activeProduct->parent_id) && empty($activeProduct->is_use_additional_customize));
        $productShowCartFieldStatus = $isAdditionalProductWithDisabledCustomizeField ? $this->product_show_cart : $productShowCartFieldStatus;
        $productUsergroupPermissions = $this->getUsergroupPermissions();

        $isShowCartSection = ($productShowCartFieldStatus && $productUsergroupPermissions->is_usergroup_show_buy && (!$jshopConfig->stock) || (($productShowCartFieldStatus || (isset($productWithUpload->isSupportUpload) && $productWithUpload->isSupportUpload)) && (!$jshopConfig->hide_buy_not_avaible_stock || ($jshopConfig->hide_buy_not_avaible_stock && ($this->unlimited > 0))||($jshopConfig->hide_buy_not_avaible_stock && (($this->product_quantity>0) || $this->unlimited > 0)))) && $productUsergroupPermissions->is_usergroup_show_buy);

        return $isShowCartSection;
    }

    public function isShowBulkPrices(): bool
    {
        $activeProduct = $this->getActiveProduct();
        $isShowBulkPrices = $activeProduct->is_show_bulk_prices;

        $isAdditionalProductWithDisabledCustomizeField = (!empty($activeProduct->parent_id) && empty($activeProduct->is_use_additional_customize));
        $isShowBulkPrices = $isAdditionalProductWithDisabledCustomizeField ? $this->is_show_bulk_prices : $isShowBulkPrices;

        return (bool)$isShowBulkPrices;
    }

    public function isFromEditor(): bool
    {
        $createdDate = ($this->created_in_editor * 1);
        $isFromEditor = (!empty($createdDate) && $createdDate > 1970) || !empty($this->xml);
        return $isFromEditor;
    }
	
	public function addObjectFunction($rows)
	{
		$rows2=array();
		foreach ($rows as $key=>$value){
			$product_table = JSFactory::getTable('product', 'jshop'); 
			$product_table->load($value->product_id);
			$rows2[$key]=$product_table;			
			foreach ($value as $key2=>$value2){
				$rows2[$key]->$key2=$value2;
			}
		}	
		return $rows2;
	}
	
	public function getPriceTax($product_tax,$product_price)
    {
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->display_price_admin == 1) {
			$tax = $product_price * ($product_tax / 100);
		} else {
			$tax = $product_price / ((100 + $product_tax) / 100);
		}	
		return $tax;
    }
	public function getTotalWithTax($product_tax,$product_price)
    {
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->display_price_admin == 1) {
			$tax = $product_price * ($product_tax / 100);
			return $product_price+$tax;
		} else {
			$tax = $product_price / ((100 + $product_tax) / 100);
			return $product_price;
		}	
		
    }
	 public function getAttributesAdmin($isUseExpirationDate = true)
    {
        return JSFactory::getModel('AttrsFront')->getDependAttrsAdmin($this->getProductId(), $isUseExpirationDate);
    }
}
