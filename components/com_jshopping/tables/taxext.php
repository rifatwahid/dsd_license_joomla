<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopTaxExt extends JTableAvto {    
    
	const TABLE_NAME = 'jshopping_taxes_ext';
	
	public $id = null;
    public $tax_id = null;
    public $zones = null;
    public $tax = null;
    public $firma_tax = null;    
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_taxes_ext', 'id', $_db);
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
	
    public function setZones($zones)
    {
        $this->zones = serialize($zones);
    }
    
    public function getZones()
    {
        return !empty($this->zones) ? unserialize($this->zones) : [];
    }
	
	public function getExttaxesColums(){
		$colums=$this->getListOfColumnsNames();
		return $colums;
		/*echo "<pre>";
		print_r($colums);
		die();
		
		$db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT * FROM `#__jshopping_attr` WHERE attr_id = '".$db->escape($attr_id)."'";
        $db->setQuery($query);
        return $db->loadResult();*/
	}
	
	public static function getListOfColumnsNames()
	{
		$db = \JFactory::getDBO();
		
		$sqlToGetAllAttrColumnsFromProdAttr = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` 
			WHERE `TABLE_SCHEMA` = '" . JFactory::getConfig()->get('db') . "' 
				AND `TABLE_NAME` = '" . $db->getPrefix() . static::TABLE_NAME . "' 
				AND `COLUMN_NAME` LIKE 'additional_tax_%' 
				AND DATA_TYPE = 'text'";

		$db->setQuery($sqlToGetAllAttrColumnsFromProdAttr);

		return $db->loadAssocList('', 'COLUMN_NAME') ?: [];
	}
	
	public function getNextAdditionalTaxId(){
		$colums=$this->getListOfColumnsNames();
		return 1;
	}
	
	public function addNewAditionalTaxFields($id,$languages,$post){
		$taxExtAdditional = JSFactory::getTable('taxextadditional', 'jshop');
		
		$colums=$this->getListOfColumnsNames();
		foreach ($languages as $key=>$value){
			$field="";
			//if ()
			
		}
		die();
	}
	
	private function addColumn($title){
		$db = \JFactory::getDBO();
		
		$sql="ALTER TABLE ". $db->getPrefix() .static::TABLE_NAME ." ADD `".$title."` TEXT";
		$db->setQuery($sql);
		$db->execute();
	}
	
	public function deleteColumn($title){
		$db = \JFactory::getDBO();
		
		$sql="ALTER TABLE ". $db->getPrefix() .static::TABLE_NAME ." DROP COLUMN `".$title."`";
		$db->setQuery($sql);
		$db->execute();
	}
	
	public function addAditionalTaxesId($id){
		$colums=$this->getListOfColumnsNames();
		if (!in_array("additional_tax_".$id,$colums)){
			$this->addColumn("additional_tax_".$id);
		}
	}
}
