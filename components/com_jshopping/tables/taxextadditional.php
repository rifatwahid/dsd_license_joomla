<?php
/**
* @version      5.3.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopTaxExtAdditional extends JTableAvto {    
    
	const TABLE_NAME = 'jshopping_taxes_ext_additional_taxes';
	
	public $id = null;
    public $tax_id = null;
    public $zones = null;
    public $tax = null;
    public $firma_tax = null;    
    
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_taxes_ext_additional_taxes', 'id', $_db);
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
	
	public function getExttaxesColums(){
		$colums=$this->getListOfColumnsNames();
		return $colums;
	}
	
	public static function getListOfColumnsNames()
	{
		$db = \JFactory::getDBO();
		
		$sqlToGetAllAttrColumnsFromProdAttr = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` 
			WHERE `TABLE_SCHEMA` = '" . JFactory::getConfig()->get('db') . "' 
				AND `TABLE_NAME` = '" . $db->getPrefix() . static::TABLE_NAME . "'";
		$db->setQuery($sqlToGetAllAttrColumnsFromProdAttr);

		return $db->loadAssocList('', 'COLUMN_NAME') ?: [];
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
	
    public function addNewAditionalTaxFields($id,$languages,$post){
		$columns=$this->getListOfColumnsNames();
		
		foreach ($languages as $key=>$value){
			
			if (!in_array($value->lang_code,$columns)){
				$this->addColumn($value->lang_code);
			}
		}
		if (!$this->bind($post)) {
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_BIND'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&task=additional_taxes&back_tax_id=".$back_tax_id);
            return 0;
		}
		if (!$this->store()){
            \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SAVE_DATABASE'),'error');
            $this->setRedirect("index.php?option=com_jshopping&controller=exttaxes&task=additional_taxes&back_tax_id=".$back_tax_id);
            return 0; 
        }
        return $this->id;
	}
	
	public function getAllAdditionalTaxes($id=0){
		$jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO();
		$where="";
		if ($id>0) {$where = "WHERE id=".$id;}
		$columns_names=$this->getListOfColumnsNames();
		if (!in_array($lang->lang,(array)$columns_names)){
			$this->addColumn($lang->lang);
		}
        $query = "SELECT id,`".$lang->lang."` as name 
                  FROM `#__jshopping_taxes_ext_additional_taxes`
				  $where
                  ORDER BY id";
				  
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }

	public function loadTax($id=0){
		$jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO();
        $query = "SELECT * 
                  FROM `#__jshopping_taxes_ext_additional_taxes`
				  WHERE id=".$id."
                  ORDER BY id";
				  
        $db->setQuery($query);
        $row = $db->loadObject();
        return $row;
    }
}
