<?php 

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../FrontMambot.php';
require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/jshEAFAhelper.php';
 
class ExcludeAttributeForAttribute extends FrontMambot
{
    protected static $instance;
    protected static $excludesAttrs;
    protected static $hide_attributes = [];
    protected static $ex_cart_attr;

    public function onBuildSelectAttribute(&$attributeValues, &$attributeActive, &$selects, &$options, &$attr_id, &$v)
    {
        if (empty($attributeActive) && !empty($attributeValues)) {
            foreach ($attributeValues as $attrId => $arrWithValues) {
                $attributeActive[$attrId] = $arrWithValues['0']->val_id;
            }
        }

        if (!isset(static::$excludesAttrs) || empty($excludesAttrs)) {
            static::$excludesAttrs = jshEAFAhelper::getExcludesAttr($attributeActive);
        }

        if (isset($attributeActive[$attr_id]) && isset(static::$excludesAttrs[$attr_id])) {///////////////TASK 5814   !empty  
            
            $jshopConfig = JSFactory::getConfig();
            $product = JSFactory::getTable('product', 'jshop');

            if (empty(static::$excludesAttrs[$attr_id])) {
                $options = [];
            }

            if (!empty($options)) {
                foreach ($options as $ko => $vo) {
                    if (isset(static::$excludesAttrs[$attr_id][$vo->val_id])) {
                        unset($options[$ko]);

                        if (isset($attributeActive[$attr_id]) && $attributeActive[$attr_id] == $vo->val_id) {
                            unset($attributeActive[$attr_id]);
                        }
                    } elseif (isset(static::$excludesAttrs[$attr_id]) && !in_array($attributeActive[$attr_id],static::$excludesAttrs[$attr_id])) {
                        if (isset(static::$excludesAttrs[$attr_id][$vo->val_id])) {
                            $attributeActive[$attr_id] = static::$excludesAttrs[$attr_id][$vo->val_id];
                        }
                    }
                }
            } elseif (isset($attributeActive[$attr_id])) {
                unset($attributeActive[$attr_id]);
            }

            if (empty($options)) {
                static::$hide_attributes[$attr_id] = $attr_id;
            }

            if ($v->attr_type == 1) {
                $attrimage = [];
                $_select_active = '';
                $_active_image = '';

                foreach ($options as $k2 => $v2) {
                    $attrimage[$v2->val_id] = $v2->image;
                }

                if (isset($attributeActive[$attr_id])) {
                    $_select_active = $attributeActive[$attr_id];
                }

                if (isset($attributeActive[$attr_id]) && isset($attrimage[$attributeActive[$attr_id]])) {
                    $_active_image = $attrimage[$attributeActive[$attr_id]];
                }

                $htmlAttrImage = '';
                if (!empty($_active_image)) {
                    $htmlAttrImage = '<span class="prod_attr_img">' . $product->getHtmlDisplayProdAttrImg($attr_id,
                        $_active_image) . '</span>';
                }

                $selects[$attr_id]->selects = !empty($options) ? JHTML::_('select.genericlist', $options,
                        'jshop_attr_id[' . $attr_id . ']',
                        'class = "inputbox form-select" size = "1"',
                        'val_id', 'value_name',
                        $_select_active) . $htmlAttrImage : '';

                $selects[$attr_id]->selects = str_replace(["\n", "\r", "\t"], '', $selects[$attr_id]->selects);

            } else {
                $radioseparator = '';

                $selects[$attr_id]->selects = !empty($options) ? sprintRadioList($options, 'jshop_attr_id[' . $attr_id . ']',
                    '', 'val_id', 'value_name',
                    $attributeActive[$attr_id], $radioseparator) : '';

                $selects[$attr_id]->selects = str_replace(["\n", "\r", "\t"], '', $selects[$attr_id]->selects);
            }

        }
		
    }

    public function onBeforeDisplayProductView(&$view)
    {
        $doc = JFactory::getDocument();
        $doc->addScriptOptions('attrHideIds', json_encode(self::$hide_attributes));
    }

    public function onBeforeDisplayAjaxAttrib(&$_rows, &$product)
    {
        $_rows[] = '"eafa_attr_hide":' . json_encode(self::$hide_attributes) . '';
    }

    public function onBeforeAddProductToCart( &$cart, &$product_id, &$quantity, &$attr_id, &$freeattributes, &$updateqty, &$errors, &$displayErrorMessage, &$additional_fields, &$usetriggers) 
    {
		$db = \JFactory::getDBO();
		foreach($attr_id as $id=>$val){
			$ex = jshEAFAhelper::getExcludesAttr([$id => $val]);
			foreach ($ex as $ex_attr_id => $ex_values) {
				
				$field = 'attr_' . $ex_attr_id;
				
				$sql = 'SHOW COLUMNS FROM `#__jshopping_products_attr` LIKE ' . $db->quote($field);
				$db->setQuery($sql);
				$attrField = $db->loadResult();
			
				if(!$attrField) continue;
				
				if (!isset($attr_id[$ex_attr_id])) {
					if (!is_array(self::$ex_cart_attr)) {
						self::$ex_cart_attr = [];
					}

					self::$ex_cart_attr[$ex_attr_id] = $ex_attr_id;
					$attr_id[$ex_attr_id] = -1;
				}elseif(isset($ex[$ex_attr_id][$val])){
					$attr_id[$ex_attr_id] = -2;
				}
			}
		}
        
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