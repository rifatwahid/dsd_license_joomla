<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class multiLangField
{
    
    public $table = '';
    public $lang = '';
    public $tableFields = [];
    
    public function __construct()
    {
        $this->_LoadTableFields();        
    }
    
    public function setTable($table)
    {
        $this->table = $table;
    }
    
    public function setLang($lang)
    {
        $this->lang = $lang;
    }
    
    public function get($field)
    {
        return "{$field}_{$this->lang}";
    }
    
    public function getListFields()
    {
        $array = [];

        if ($this->table) {
            $array = $this->tableFields[$this->table];    
        }

        return $array;
    }
    
    /**
    * get build guery multi language fields
    * @return strin query ml fiels
    */
    public function getBuildQuery()
    {
        $query = [];
        $fields = $this->getListFields();

        foreach($fields as $field) {
            $query[] = "`{$this->get($field['0'])}` as {$field['0']}";
        }

        return implode(', ', $query);
    }
    
    public function addNewFieldLandInTables($lang, $defaultLang = '')
    {
        $finish = 1;
        $db = \JFactory::getDBO();

        foreach($this->tableFields as $table_name_end => $table) {
            $tableNameWithPrefix = "{$db->getPrefix()}jshopping_{$table_name_end}";
            $table_name = '#__jshopping_' . $table_name_end;
            
            $list_name_field = [];
            $query = 'SHOW FIELDS FROM `' . $table_name . '`';   
            $db->setQuery( $query );
            $fields = $db->loadObjectList();

            foreach($fields as $field) {
                $list_name_field[] = $field->Field;
            }
            
            //filter existent field
            foreach($table as $k => $field) {
                if (in_array("{$field['0']}_{$lang}", $list_name_field)) {
                    unset($table[$k]);
                }
            }
            
            $sql_array_add_field = [];
            $sqlWithAddFieldIfNotExists = [];
            foreach($table as $field) {
                $name = "{$field['0']}_{$lang}";;
                $sql_array_add_field[] = "ADD `{$name}` {$field['1']}";
                $sqlWithAddFieldIfNotExists[] = 'CALL addFieldIfNotExists("'. $tableNameWithPrefix . '", "' . $name . '", "' . $field[1] . '");';
            }
            
            $sql_array_update_field = [];
            foreach($table as $field) {
                $name = "{$field['0']}_{$lang}";
                $name2 = "{$field['0']}_{$defaultLang}";

                if (in_array($name2, $list_name_field)) {
                    $sql_array_update_field[] = " `{$name}` = `{$name2}`";
                }
            }            
            
            if (!empty($sqlWithAddFieldIfNotExists)) {        
                /*        
                $query = "ALTER TABLE `{$table_name}` " . implode(', ', $sql_array_add_field);  
                $db->setQuery($query);
                if (!$db->execute()) {
                    JError::raiseWarning(500, 'Error install new language:<br>' . $db->getErrorMsg());
                    $finish = 0;
                } 
                */

                foreach ($sqlWithAddFieldIfNotExists as $query) {
                    $db->setQuery($query);
                    $db->execute();
                }
                               
                //copy information
                if ($defaultLang != '' && !empty($sql_array_update_field)) {
                    $query = "update `{$table_name}` set " . implode(', ', $sql_array_update_field);
                    $db->setQuery($query);

                    if (!$db->execute()) {
                        //JError::raiseWarning(500, 'Error copy new language:<br>' . $db->getErrorMsg());
						throw new Exception('Error copy new language:<br>' . $db->getErrorMsg(),500);
                        $finish = 0;
                    }
                }
            }
        }
        
        return $finish;
    }
    
    /**
    * Static list Table and Fields
    */
    public function _LoadTableFields()
    {
        $this->tableFields['countries'] = [
            ['name', 'varchar(255) NOT NULL']
        ];
        
        $this->tableFields['shipping_method'] = [
            ['name', 'varchar(100) NOT NULL'],
            ['description', 'text NOT NULL']
        ];
        
        $this->tableFields['payment_method'] = [
            ['name', 'varchar(100) NOT NULL'],
            ['description', 'text NOT NULL']
        ];
        
        $this->tableFields['order_status'] = [
            ['name', 'varchar(100) NOT NULL']
        ];
        
        $this->tableFields['delivery_times'] = [
            ['name', 'varchar(255) NOT NULL']
        ];
        
        $this->tableFields['unit'] = [
            ['name', 'varchar(255) NOT NULL']
        ];        
        
        $this->tableFields['attr'] = [
            ['name', 'varchar(255) NOT NULL'],
            ['description', 'text NOT NULL']
        ];
        
        $this->tableFields['attr_values'] = [
            ['name', 'varchar(255) NOT NULL']
        ];
        
        $this->tableFields['attr_groups'] = [
            ['name', 'varchar(255) NOT NULL']
        ];
        
        $this->tableFields['products_extra_fields'] = [
            ['name', 'varchar(255) NOT NULL'],
            ['description', 'text NOT NULL']
        ];
        
        $this->tableFields['products_extra_field_values'] = [
            ['name', 'varchar(255) NOT NULL']
        ];
        
        $this->tableFields['products_extra_field_groups'] = [
            ['name', 'varchar(255) NOT NULL']
        ];
        
        $this->tableFields['free_attr'] = [
            ['name', 'varchar(255) NOT NULL'],
            ['description', 'text NOT NULL']
        ];

        $this->tableFields['product_labels'] = [
            ['name', 'varchar(255) NOT NULL'],
            ['image', 'varchar(255) NOT NULL'],
        ];
        
        $this->tableFields['manufacturers'] = [
            ['name', 'varchar(255) NOT NULL'],
            ['alias', 'varchar(255) NOT NULL'],
            ['short_description', 'text NOT NULL'],
            ['description', 'text NOT NULL'],
            ['meta_title', 'varchar(255) NOT NULL'],
            ['meta_description', 'text NOT NULL'],
            ['meta_keyword', 'text NOT NULL']
        ];
        
        $this->tableFields['categories'] = [
            ['name', "varchar(255) NOT NULL"],
            ['alias', "varchar(255) NOT NULL"],
            ['short_description', "text NOT NULL"],
            ['description', "text NOT NULL"],
            ['meta_title', "varchar(255) NOT NULL"],
            ['meta_description', "text NOT NULL"],
            ['meta_keyword', "text NOT NULL"]
        ];
        
        $this->tableFields['products'] = [
            ['name', 'varchar(255) NOT NULL'],
            ['alias', 'varchar(255) NOT NULL'],
            ['short_description', 'text NOT NULL'],
            ['description', 'text NOT NULL'],
            ['meta_title', 'varchar(255) NOT NULL'],
            ['meta_description', 'text NOT NULL'],
            ['meta_keyword', 'text NOT NULL'],
            ['robots', 'text NOT NULL'],
        ];
        
        $this->tableFields['usergroups'] = [
            ['name', 'varchar(255) NOT NULL'],
            ['description', 'text NOT NULL']
        ];

        $this->tableFields['config_seo'] = [
            ['title', 'varchar(255) NOT NULL'],
            ['keyword', 'text NOT NULL'],
            ['description', 'text NOT NULL'],
            ['robots', 'INT(11) NOT NULL DEFAULT 0'],
        ];

        $this->tableFields['search'] = [
            ['keyword', 'text NOT NULL']
        ];

        $this->tableFields['products_added_content'] = [
            ['description', 'text NOT NULL']
        ];

        $this->tableFields['coupons'] = [
            ['for_product_name', 'text NOT NULL']
        ];

        $this->tableFields['config_statictext'] = [
            ['text', 'text NOT NULL']
        ];

        $this->tableFields['shipping_method_price'] = [
            ['name', 'varchar(100) NOT NULL'],
            ['description', 'text NOT NULL']
        ];
        
        $this->tableFields['states'] = [
            ['name', 'varchar(255) NOT NULL']
        ];
        
        $this->tableFields['products_files'] = [
            ['demo_descr', 'varchar(255) NOT NULL'],
            ['file_descr', 'varchar(255) NOT NULL']
        ];

        $currentObj = $this;
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onLoadMultiLangTableField', [&$currentObj]);
    }
    
}