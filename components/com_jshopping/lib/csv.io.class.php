<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class csv
{
    protected $delimit = ';';
    protected $text_qualifier = '"';
    
	public function setDelimit($val)
	{
        $this->delimit = $val;    
    }
    
	public function setTextQualifier($val)
	{
        $this->text_qualifier = $val;
    }    

	public function read($file)
	{
 		$rows = [];
		$fp = fopen ($file, 'r');
		 
        while ($data = fgetcsv($fp, 262144, $this->delimit, $this->text_qualifier) ) {
			$rows[] = $data;
		}

		fclose ($fp);

		return $rows;
 	}

	public function implodeCSV($data)
	{
        
    	$delimit = $this->delimit;
        
 		foreach($data as $k => $v) {
			$v = str_replace(["\n", "\r", "\t"], ' ', $v);
			 
            if ($this->text_qualifier != '') { 
 			    $v = str_replace($this->text_qualifier, $this->text_qualifier . $this->text_qualifier, $v);
			}
			
            if ($this->text_qualifier != '') { 
 			    if (strpos($v, $delimit) !== false || strpos($v, $this->text_qualifier) !== false) {
                    $v = $this->text_qualifier . $v . $this->text_qualifier; 
                }
            } else {
                if (strpos($v, $delimit) !== false) {
                    $v = str_replace($delimit, ' ', $v);
                }
            }
            
            $data[$k] = $v;
 		}

		return implode($delimit, $data);
 	}

	public function write($file, $mass2D)
	{
		$fp = fopen($file, 'w');
		 
 		if (!$fp) {
			return 0;
		}

		$countrow = count($mass2D);
		
 		foreach($mass2D as $k => $v) {
 			if (!is_array($v)) {
				return 0;
			}

			$str = $this->implodeCSV($v);
			
            if ($k < ($countrow-1)) {
				$str = $str . "\n";
			}

 			fwrite($fp, $str);
		}
		 
		fclose($fp);
		
		return 1;
 	}
            
}
