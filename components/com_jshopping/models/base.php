<?php
/**
* @version      4.11.1 11.09.2015
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die();

include_once JPATH_JOOMSHOPPING . '/controllers/base.php';

abstract class jshopBase extends \JObject
{

    private $error;
    public $errors = [];

    public function setErrors(array $errors)
    {
        if (!empty($errors)) {
            foreach ($errors as $error) {
                array_push($this->errors, $error);
            }

            return true;
        }

        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function clearErrors()
    {
        $this->errors = [];
    }
	
    public function setError($error)
    {
        $this->error = $error;
        $this->setErrors([$error]);
    }
    
    public function getError($i = NULL, $toString = true)
    {
        return $this->error;
    }
    
    public function getView(string $name)
    {
		
		$jshopConfig = JSFactory::getConfig();		
		include_once JPATH_JOOMSHOPPING . '/views/' . $name . '/view.html.php';
		$viewClass = 'JshoppingView' . $name;
        $view = new $viewClass([
            'template_path' => "{$jshopConfig->template_path}{$jshopConfig->template}/{$name}"
        ]);

        return $view;
    }

    public function select(array $columnsToGet = ['*'], $where = "", string $afterWhere = '',  bool $isloadObjectList = true , ?int $limit = null, int $limitStart = 0)
    {
        $result = [];

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $sqlWhere = '';

            if (is_array($where) && !empty($where)) {
                $sqlWhere = ' WHERE ';
                $sqlWhere .= implode(' AND ', $where);
            }elseif(is_string($where) && strlen($where) > 0){
				$sqlWhere = ' WHERE '.$where;
			}

            $sqlQuery = "SELECT {$stringOfSearchColumns} FROM " . static::TABLE_NAME . " {$sqlWhere} {$afterWhere}";
            
            if (isset($limit)) {
                $db->setQuery($sqlQuery, $limitStart, $limit);
            } else {
                $db->setQuery($sqlQuery);
            }

            $result = ($isloadObjectList) ? $db->loadObjectList() : $db->loadObject();

            if (empty($result)) {
                $result = [];
            }
        }

        return $result;
    }
    
    public function insert(array $columnsNames, array $columnsValues): bool
    {
        $result = false;

        if (!empty($columnsNames) && !empty($columnsValues) && count($columnsNames) == count($columnsValues)) {
            $db = \JFactory::getDBO();
            $sqlQuery = 'INSERT INTO ' . static::TABLE_NAME . '(' . implode(',', $columnsNames) . ') VALUES (' . implode(',', $columnsValues) . ')';
            $db->setQuery($sqlQuery);
            $result = $db->execute();
        }

        return $result;
    }
}