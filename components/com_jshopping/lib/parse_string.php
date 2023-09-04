<?php
/**
* @version      4.8.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class parseString
{
    public $string = null;
    public $params = null;
    public $separator = null;
    
    public function __construct($value, $separator = "\n")
    {
        $this->separator = $separator;

        if (is_array($value)) {
            $this->params = $value;
        } else {
            if (is_string($value)) {
                $this->string = $value;
            } else {
                return;
            }
        }
    }

    public function parseStringToParams()
    {
        if (!$this->string) {
            return '';
        }

        $params = explode($this->separator, $this->string);
		foreach($params as $param) {
            if ($param != '') {
                $pos = strpos($param, '=');
                
				$ext_param = [
                    substr($param, 0, $pos), 
                    substr($param, $pos + 1)
                ];

				if (!$ext_param[0]) {
                    continue;
                }

				$this->params[trim($ext_param['0'])] = trim($ext_param['1']);
			}
        }

		return $this->params;
    }

    public function splitParamsToString()
    {
        $this->string = '';

        foreach($this->params as $key=>$value) {
            $this->string .= trim($key) . '=' . trim($value) . $this->separator;
        }

        return $this->string;
    }

    public function parseStringToParams2()
    {
        $params = explode($this->separator,$this->string);

        foreach($params as $param) {
            if(!$param) {
                continue;
            }

            $this->params[trim($param)] = trim($param);
        }
    }

    public function getArrayObject($key_name)
    {
        $this->parseStringToParams2();
        $arr_ret = [];

        if (empty($this->params)) {
            return null;
        }

        foreach($this->params as $param) {
            $obj->$key_name = $param;
            $arr_ret[] = $obj;
            unset($obj);
        }

        return $arr_ret;
    }

    public function splitParamsToString2()
    {
        $this->string = $this->separator;

        foreach($this->params as $key => $value) {
            $this->string .= $value . $this->separator; 
        }

        return $this->string;
    }
}
