<?php
/**
* @version      4.1.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

use Joomla\CMS\Factory;

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class JshoppingModelFreeAttribut extends JModelLegacy 
{
    
    public function getNameAttrib($id) 
	{
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $query = "SELECT `".$lang->get("name")."` as name FROM `#__jshopping_free_attr` WHERE id = '".$db->escape($id)."'";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadResult();
    }

    public function getAll($order = null, $orderDir = null) 
	{
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO(); 
        $ordering = 'ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT id, `".$lang->get("name")."` as name, ordering, required FROM `#__jshopping_free_attr` ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);        
        return $db->loadObjectList();
    }
	
  	/**
    *	Get free product attributes
    *
    *	@param int $productId
    *
    *	@return array
    */
	public function getProductFreeAttributes($productId)
	{
        $db = \JFactory::getDBO();
        $query = 'SELECT `product_id`, `attr_id` FROM `#__jshopping_products_free_attr` WHERE `product_id` = ' . $db->escape($productId);
        $db->setQuery($query);
      
        return $db->loadAssocList();
    }  
	
  	/**
    *	Set for $productId free attribute identifiers from $attrIdOrArrWithAttrsIds
    *
    *	@param int $productId
    *	@param array|int $attrIdOrArrWithAttrsIds
    *
    *	@return boolean
    */
    public function setFreeAttributesForProduct($productId, $attrIdOrArrWithAttrsIds)
    {
        if ( !empty($attrIdOrArrWithAttrsIds) && is_numeric($productId) ) {

            $db = \JFactory::getDBO();
            $queryForSprintf = 'INSERT INTO `#__jshopping_products_free_attr`(`product_id`, `attr_id`) VALUES( ' . $db->escape($productId) . ', %d )';

            if ( is_array($attrIdOrArrWithAttrsIds) ) {
                foreach($attrIdOrArrWithAttrsIds as $key => $arr) {

                    if ( isset($arr['attr_id']) ) {
                        $query = sprintf($queryForSprintf, $arr['attr_id']);
                        $db->setQuery($query);
                        $db->execute();
                    }
                }
				
              	return true;
            } else {
                $query = sprintf($queryForSprintf, $attrIdOrArrWithAttrsIds);
                $db->setQuery($query);
              
                return $db->execute();              
            }   
        } 
        
        return false;
    }
	
	public function deleteFreeattribut($cid){
		$_dbdelete = JSFactory::getModel('dbdelete');
		$text = '';
		foreach ($cid as $key => $value) {		
			$value = intval($value);
			$_dbdelete->deleteItems("#__jshopping_free_attr","id",$value);
			$_dbdelete->deleteItems("#__jshopping_products_free_attr","attr_id",$value);
		}
		return $text;
	}
	
	public function getFreeatributesArray($_table_product){
		$jshopConfig = JSFactory::getConfig();
		if ($jshopConfig->admin_show_freeattributes){            
            $listfreeattributes = $this->getAll();
            $activeFreeAttribute = $_table_product->getListFreeAttributes();
            $listIdActiveFreeAttribute = array();
            foreach($activeFreeAttribute as $_obj){
                $listIdActiveFreeAttribute[] = $_obj->id;
            }            
            foreach($listfreeattributes as $k=>$v){
                if (in_array($v->id, $listIdActiveFreeAttribute)){
                    $listfreeattributes[$k]->pactive = 1;
                }
            }
			return $listfreeattributes;
        }		
	}

    public function deleteAllFreeAttrsWithDefaultValuesByProdId(int $productId)
    {
        $_dbdelete = JSFactory::getModel('dbdelete');
        $_dbdelete->deleteItems("#__jshopping_free_attr_default_values", 'product_id', $productId);
		$_dbdelete->deleteItems("#__jshopping_products_free_attr", 'product_id', $productId);
    }

    public function deleteProdFreeAttrsWithDefaultValuesByFreeAttrsAndProdId(int $productId, array $ids): bool
    {
        $isSuccess = true;

        if (!empty($ids) && !empty($productId)) {
            $db = Factory::getDbo();
            $implodedFreeAttrsIds = implode(', ', $ids);
            $sqlDelete = 'DELETE FROM `#__jshopping_products_free_attr` WHERE `attr_id` IN(' . $implodedFreeAttrsIds . ') AND `product_id` = ' . $productId;
            $db->setQuery($sqlDelete);
            $isSuccess = $db->execute();

            if ($isSuccess) {
                $sqlDelete = 'DELETE FROM `#__jshopping_free_attr_default_values` WHERE `attr_id` IN(' . $implodedFreeAttrsIds . ') AND `product_id` = ' . $productId;
                $db->setQuery($sqlDelete);
                $isSuccess = $db->execute();
            }
        }

        return $isSuccess;
    }
    
}
