<?php

class jshEAFAhelperbuttons{

    const PLG = 'exclude_buttons_for_attribute';
    private static $init, $helper, $allAttributeValues;

    public function __construct($vars)    {
        $this->setVars($vars);
    }

    public function setVars($vars)    {
        foreach ($vars as $name => $value) {
            $this->setVar($name, $value);
        }
    }

    public function setVar($name, $value)    {
        $this->{$name} = $value;
    }

    public static function init($vars = [])    {
        self::$helper = new jshEAFAhelperbuttons($vars);
        return self::$helper;
    }

    public static function id($name)    {
        return self::PLG . '_' . $name;
    }

    public static function jshPath($admin = false)    {
        $admin = $admin ? '/administrator' : '';
        return JPATH_ROOT . $admin . '/components/com_jshopping';
    }

    public static function tmplPath()    {        
        return JPATH_JOOMSHOPPING_ADMIN . '/views/tmpls_elements/exclude_buttons_for_attribute/';
    }

    public static function tmpl($name, $params = [])    {
        $file = self::tmplPath() . '/' . $name . '.php';
        $html = '';

        if (file_exists($file)) {
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
    public static function getAllAttributeValuesList(){
        $eafa_field = self::PLG;
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT value_id, image, `".$lang->get("name")."` as name, attr_id, value_ordering, $eafa_field FROM `#__jshopping_attr_values` ORDER BY value_ordering, value_id";
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public static function getAllAttrValues() {
        if(!isset(self::$allAttributeValues)){
            $values = self::getAllAttributeValuesList();
            if ($values && is_array($values)) {
                foreach ($values as $v) {
                    if (!isset(self::$allAttributeValues[$v->attr_id])) {
                        self::$allAttributeValues[$v->attr_id] = [];
                    }                    
                    self::$allAttributeValues[$v->attr_id][$v->value_id] = $v;
                }
            }
        }

        return self::$allAttributeValues;
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public static function getAllAttributeValuesListOpt($attrs_id_list,$attrs_value_id_list){
        $eafa_field = self::PLG;
        $db = \JFactory::getDBO();        
        $query = "SELECT value_id, attr_id, $eafa_field FROM `#__jshopping_attr_values` WHERE attr_id in $attrs_id_list AND value_id in $attrs_value_id_list AND `$eafa_field`!='a:0:{}'";
        $db->setQuery($query);

        return $db->loadObjectList();
    }
	
	public static function getAllAttrValuesOpt($attrs_id_list,$attrs_value_id_list) {
        if(!isset(self::$allAttributeValues)){
            $values = self::getAllAttributeValuesListOpt($attrs_id_list,$attrs_value_id_list);
            if ($values && is_array($values)) {
                foreach ($values as $v) {
                    if (!isset(self::$allAttributeValues[$v->attr_id])) {
                        self::$allAttributeValues[$v->attr_id] = [];
                    }                    
                    self::$allAttributeValues[$v->attr_id][$v->value_id] = $v;
                }
            }
        }

        return self::$allAttributeValues;
    }
	
	public static function getExcludesAttrIdList($attributeActive)
	{
		$list="";
		foreach ($attributeActive as $key=>$value)
		{
			if (!empty($list)) $list.=",";
			$list.=$key;
		}
		return "(".$list.")";
	}
	
	public static function getExcludesAttrValueIdList($attributeActive)
	{
		$list="";
		foreach ($attributeActive as $key=>$value)
		{
			if (!empty($list)) $list.=",";
			$list.=$value;
		}
		return "(".$list.")";
	}

    public static function getExcludesAttr($attributeActive){
		$excludes = [];
		if (!empty($attributeActive))
		{        
			$attrs_id_list = jshEAFAhelperbuttons::getExcludesAttrIdList($attributeActive);			
			$attrs_value_id_list = jshEAFAhelperbuttons::getExcludesAttrValueIdList($attributeActive);			
			//echo "$attrs_value_id_list";
			$all_attr_values = jshEAFAhelperbuttons::getAllAttrValuesOpt($attrs_id_list,$attrs_value_id_list);		
			//echo "<pre><hr>attributeActive:<br>";print_r($attributeActive);			
			foreach($attributeActive as $k=>$v){
				if(isset($all_attr_values[$k][$v])) {//echo "<hr><hr><hr>k=".$k." v=".$v;
					$values = $all_attr_values[$k][$v]->{self::PLG};				
					//echo "<hr>all_attr_values[k][v]: <br>";print_r($all_attr_values[$k][$v]);					
					//echo "<hr>values: <br>";print_r($values);										
					$attr_values = $values ? unserialize($values) : [];

					if(!$attr_values){
						$buttons_array = [];
					}else{
						foreach ($attr_values as $k=>$v){						
							if (!in_array($k,$excludes)) $excludes[]=$k;
						}
					}				
				}
			}		
		}
		//echo "<hr><hr>all_attr_values: <br>";print_r($all_attr_values);
		//print_r($excludes);
		//die();
        return $excludes;
    }
	
    public static function getExcludesAttr1($attributeActive){
        $excludes = [];
        $all_attr_values = jshEAFAhelperbuttons::getAllAttrValues();

        foreach($attributeActive as $k=>$v){
            if(isset($all_attr_values[$k][$v])) {
                $_excludes_values = jshEAFAhelperbuttons::getAttrValues($all_attr_values[$k][$v]);
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

    public static function getAttrValues($attributValue)    {
        $values = $attributValue->{self::PLG};
        $attr_values = $values ? unserialize($values) : [];
        if(!$attr_values){
            $attr_values = [];
        }
        return $attr_values;
    }

    public static function setAttrValues(&$attributValue, $values)    {
        $values = serialize($values);

        if(is_object($attributValue)) {
            return $attributValue->{self::PLG} = $values;
        }elseif(is_array($attributValue)){
            return $attributValue[self::PLG] = $values;
        }else{
            return false;
        }
    }

    public static function addPostAttrValues(&$post)    {
        $values = [];
        if(isset($post['eafa_btn_ids'])){
            foreach($post['eafa_btn_ids'] as $v){
                $values[$v] = [];
                if(isset($post['eafa_btn_values'][$v])){
                    foreach($post['eafa_btn_values'][$v] as $av){
                        $values[$v][$av] = (int)$av;
                    }
                }
            }
        }
        self::setAttrValues($post,$values);
    }

    public static function addSelectsAdminView(&$view)    {
        $attr_values = self::getAttrValues($view->attributValue);
        $attrs_ids = [];

        foreach($attr_values as $attr_id=>$values){
            $attrs_ids[] = $attr_id;
        }	
					
        $buttons=array('cart','upload','editor');		

        $view->etemplatevar .= self::tmpl('edit_buttons_line', [
            'attrs_ids' => $attrs_ids,
            'buttons' => $buttons
        ]);
    }

}

jshEAFAhelperbuttons::init();