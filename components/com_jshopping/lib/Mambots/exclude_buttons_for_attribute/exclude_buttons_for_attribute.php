<?php 

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../FrontMambot.php';
require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/jshEAFAhelperbuttons.php';
 
class ExcludeButtonsForAttribute extends FrontMambot
{
    protected static $instance;
    protected static $excludesAttrs;
    protected static $hide_attributes = [];
	protected static $hide_buttons = [];
    protected static $ex_cart_attr;

    public function onBuildSelectAttribute(&$attributeValues, &$attributeActive){//, &$selects, &$options, &$attr_id, &$v
		$show_buttons['cart']=0;
		$show_buttons['upload']=0;
		$show_buttons['editor']=0;
				
        if (empty($attributeActive) && !empty($attributeValues)) {
            foreach ($attributeValues as $attrId => $arrWithValues) {
                $attributeActive[$attrId] = $arrWithValues['0']->val_id;
            }
        }

		
        if (!isset(static::$excludesAttrs)) {
            $excludesAttrs = jshEAFAhelperbuttons::getExcludesAttr($attributeActive);
        }

		if (in_array(0,$excludesAttrs)) $show_buttons['cart']=1;
		if (in_array(1,$excludesAttrs)) $show_buttons['upload']=1;
		if (in_array(2,$excludesAttrs)) $show_buttons['editor']=1;

		return $show_buttons;		
    }
	
	public function onBuildSelectAttributeCart(&$cart){
		foreach ($cart->products as $key=>$product){
			$attributeValues=$product['attributes_value'];
			$show_buttons['cart']=0;
			$show_buttons['upload']=0;
			$show_buttons['editor']=0;
			
			$attributeActive=array();
			foreach ($attributeValues as $attributeValue){
				$attributeActive[$attributeValue->attr_id]=$attributeValue->value_id;
			}
		
			if (empty($attributeValues) && !empty($attributeValues)) {
				foreach ($attributeValues as $attrId => $arrWithValues) {
					$attributeActive[$attrId] = $arrWithValues['0']->val_id;
				}
			}
			
			if (!isset(static::$excludesAttrs)) {		
				$excludesAttrs = jshEAFAhelperbuttons::getExcludesAttr($attributeActive);		
			}
			
			if (in_array(0,$excludesAttrs)) $show_buttons['cart']=1;
			if (in_array(1,$excludesAttrs)) $show_buttons['upload']=1;
			if (in_array(2,$excludesAttrs)) $show_buttons['editor']=1;

			$cart->products[$key]['buttons']=$show_buttons;
		}
    }

    public function onBeforeDisplayProductView(&$view)
    {
        $doc = JFactory::getDocument();
        $doc->addScriptOptions('attrHideIds', json_encode(self::$hide_attributes));	
    }

    public function onBeforeDisplayAjaxAttrib(&$_rows, &$product)
    {				
        $_rows[] = '"eafa_btn_hide":' . json_encode(self::$hide_attributes) . '';		
    }

    public function onBeforeAddProductToCart( &$cart, &$product_id, &$quantity, &$attr_id, &$freeattributes, &$updateqty, &$errors, &$displayErrorMessage, &$additional_fields, &$usetriggers) 
    {

        $ex = jshEAFAhelperbuttons::getExcludesAttr($attr_id);
		$show_buttons['cart']=0;
		$show_buttons['upload']=0;
		$show_buttons['editor']=0;
		if (in_array(0,$ex)) $show_buttons['cart']=1;
		if (in_array(1,$ex)) $show_buttons['upload']=1;
		if (in_array(2,$ex)) $show_buttons['editor']=1;
		return $show_buttons;
		/*
		echo "<pre>";		
print_r($ex);		
die("---");
        foreach ($ex as $ex_attr_id => $ex_values) {
            if (!isset($attr_id[$ex_attr_id])) {
                if (!is_array(self::$ex_cart_attr)) {
                    self::$ex_cart_attr = [];
                }

                self::$ex_cart_attr[$ex_attr_id] = $ex_attr_id;
                $attr_id[$ex_attr_id] = -1;
            }
        }*/
    }

    public function onBeforeSaveNewProductToCartBPC(&$cart, &$temp_product, &$product, &$errors, &$displayErrorMessage)
    {
        if (is_array(self::$ex_cart_attr) && isset($temp_product['attributes_value']) && is_array($temp_product['attributes_value'])) {
            $_attributes_value = [];

            foreach($temp_product['attributes_value'] as $k => $attr) {
                $attr_id = $attr->attr_id;

                if(!isset(self::$ex_cart_attr[$attr_id])) {
                    $_attributes_value[] = $attr;
                }
            }

            $temp_product['attributes_value'] = $_attributes_value;
        }

    }
	
	public function onBeforeDisplayProductListView(&$view)
    {
        $doc = JFactory::getDocument();
        $doc->addScriptOptions('attrHideIds',json_encode(self::$hide_attributes));	
    }
}