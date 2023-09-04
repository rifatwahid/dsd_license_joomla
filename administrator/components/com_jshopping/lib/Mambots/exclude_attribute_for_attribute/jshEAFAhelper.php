<?php

class jshEAFAhelper
{

    const PLG = 'exclude_attribute_for_attribute';
    private static $init, $helper, $allAttributeValues;

    public function __construct($vars)
    {
        $this->setVars($vars);
    }

    public function setVars($vars)
    {
        foreach ($vars as $name => $value) {
            $this->setVar($name, $value);
        }
    }

    public function setVar($name, $value)
    {
        $this->{$name} = $value;
    }

    public static function init($vars = [])
    {
        // if (!isset(self::$init)) {
        //     JSFactory::loadExtLanguageFile(self::PLG);
        // }

        self::$helper = new jshEAFAhelper($vars);
        return self::$helper;
    }

    public static function id($name)
    {
        return self::PLG . '_' . $name;
    }

    public static function jshPath($admin = false)
    {
        $admin = $admin ? '/administrator' : '';
        return JPATH_ROOT . $admin . '/components/com_jshopping';
    }

    public static function tmplPath()
    {
        //return self::jshPath() . '/addons/' . self::PLG . '/tmpl';
        return JPATH_JOOMSHOPPING_ADMIN . '/views/tmpls_elements/exclude_attribute_for_attribute/';
    }

    public static function tmpl($name, $params = [])
    {
        $file = self::tmplPath() . '/' . $name . '.php';
        $html = '';

        if (file_exists($file)) {
//            if(!isset($params['helper'])){
//                $params['helper'] = self::init();
//            }
            foreach($params as $k=>$v) {
                $$k = $v;
            }

            ob_start();
            include $file;
            $html = ob_get_contents();
            ob_end_clean();
        }

        return $html;
    }


    //ADMIN
    public static function getAllAttributeValuesList()
    {
        $eafa_field = self::PLG;
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT value_id, image, `".$lang->get("name")."` as name, attr_id, value_ordering, $eafa_field FROM `#__jshopping_attr_values` ORDER BY value_ordering, value_id";
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public static function getAllAttrValues()
    {
        if(!isset(self::$allAttributeValues)){
            $values = self::getAllAttributeValuesList();
            if ($values && is_array($values)) {
                foreach ($values as $v) {
                    if (!isset(self::$allAttributeValues[$v->attr_id])) {
                        self::$allAttributeValues[$v->attr_id] = [];
                    }
                    //$v->{self::PLG} = self::getAttrValues($v);
                    self::$allAttributeValues[$v->attr_id][$v->value_id] = $v;
                }
            }
        }

        return self::$allAttributeValues;
    }

    public static function getExcludesAttr($attributeActive)
    {
        $excludes = [];
        $all_attr_values = jshEAFAhelper::getAllAttrValues();

        foreach($attributeActive as $k=>$v){
            if(is_numeric($v) && isset($all_attr_values[$k][$v])) {
                $_excludes_values = jshEAFAhelper::getAttrValues($all_attr_values[$k][$v]);
                foreach($_excludes_values as $eak=>$ea_values){
                    if(count($ea_values) == 0){
                        $excludes[$eak] = [];
                        continue;
                    }else{
                        if(!isset($excludes[$eak])){
                            $excludes[$eak] = [];
                        }
                        foreach($ea_values as $av){
                            $excludes[$eak][$av] = $av;
                        }
                    }
                }
				
            }
        }
        return $excludes;
    }

    public static function getAttrValues($attributValue)
    {
        $values = $attributValue->{self::PLG};
        $attr_values = $values ? unserialize($values) : [];
        if(!$attr_values){
            $attr_values = [];
        }
        return $attr_values;
    }

    public static function setAttrValues(&$attributValue, $values)
    {
        $values = serialize($values);

        if(is_object($attributValue)) {
            return $attributValue->{self::PLG} = $values;
        }elseif(is_array($attributValue)){
            return $attributValue[self::PLG] = $values;
        }else{
            return false;
        }
    }

    public static function addPostAttrValues(&$post)
    {
        $values = [];
        if(isset($post['eafa_attr_ids'])){
            foreach($post['eafa_attr_ids'] as $v){
                $values[$v] = [];
                if(isset($post['eafa_attr_values'][$v])){
                    foreach($post['eafa_attr_values'][$v] as $av){
                        $values[$v][$av] = (int)$av;
                    }
                }
            }
        }
        self::setAttrValues($post,$values);
    }

    public static function addSelectsAdminView(&$view)
    {
        $attr_all_values = self::getAllAttrValues();
        $attrs = JModelLegacy::getInstance('attribut', 'JshoppingModel')->getAllAttributes(1);
        $attr_values = self::getAttrValues($view->attributValue);
        $attrs_ids = [];

        foreach($attr_values as $attr_id=>$values){
            $attrs_ids[] = $attr_id;
        }

        $view->etemplatevar .= self::tmpl('edit_attr_line', [
            'attrs' => $attrs,
            'attrs_ids' => $attrs_ids,
            'attr_values' => $attr_values,
            'attr_all_values' => $attr_all_values
        ]);
    }
	
	public static function excludeAttrsValuesOnProductPage($attr_id,$other_attr,&$res)
    {
        $excludesAttrs = self::getExcludesAttr($other_attr);
		foreach ($res as $key=>$value) {
            if (!empty($excludesAttrs[$attr_id])) {
                foreach ($excludesAttrs[$attr_id] as $exclude){
                    if ($value->val_id==$exclude){
                        unset($res[$key]);
                    }
                }
            }
		}
    }
	
	public static function excludeAttrsValuesOnProductPageAjax($attr_id,$other_attr,&$res)
    {
        $excludesAttrs = self::getExcludesAttr($other_attr);		
		foreach ($res as $key=>$value) {
            if (!empty($excludesAttrs[$attr_id])) {
                foreach ($excludesAttrs[$attr_id] as $exclude){
                    if ($value->val_id==$exclude){
                        unset($res[$key]);
                    }
                }
            }
		}		
    }

}

jshEAFAhelper::init();