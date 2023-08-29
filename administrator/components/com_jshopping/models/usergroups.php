<?php
/**
* @version      4.1.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelUsergroups extends JModelLegacy{ 

    public function getAllUsergroups($order = null, $orderDir = null){
        $ordering = "usergroup_id";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        
        $db = \JFactory::getDBO(); 
        $query = "SELECT * FROM `#__jshopping_usergroups` ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	public function getAllUsergroupsWithGuest(){
		$usergroups = $this->getAllUsergroups();	
		$group_new = new stdClass();
		$group_new->usergroup_name=JText::_('COM_SMARTSHOP_USERGROUPS_GUEST');
		$group_new->usergroup_id=0;
		array_unshift($usergroups,$group_new);
		return $usergroups;
	}

    public function resetDefaultUsergroup(){
        $db = \JFactory::getDBO(); 
        $query = "SELECT `usergroup_id` FROM `#__jshopping_usergroups` WHERE `usergroup_is_default`= '1'";
        $db->setQuery($query);
        $usergroup_default = $db->loadResult();
        $query = "UPDATE `#__jshopping_usergroups` SET `usergroup_is_default` = '0'";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $db->execute();
    }

    public function setDefaultUsergroup($usergroup_id){
        $db = \JFactory::getDBO(); 
        $query = "UPDATE `#__jshopping_usergroups` SET `usergroup_is_default` = '1' WHERE `usergroup_id`= '".$db->escape($usergroup_id)."'";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $db->execute();
    }

    public function getDefaultUsergroup(){
        $db = \JFactory::getDBO(); 
        $query = "SELECT `usergroup_id` FROM `#__jshopping_usergroups` WHERE `usergroup_is_default`= '1'";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadResult();
    }
	public function getAllUsergroupsSelect($order = null, $orderDir = null){
        $ordering = "usergroup_id";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        
        $db = \JFactory::getDBO(); 
        $query = "SELECT * FROM `#__jshopping_usergroups` ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        $res=$db->loadObjectList();
		array_unshift($res,JText::_('COM_SMARTSHOP_USERGROUPS_GUEST'));		
		return $res;
    }
	public function getAllUsergroupsPrices($product_id){
		$db = \JFactory::getDBO(); 
        $query = "SELECT * FROM `#__jshopping_products_prices_group` as PP LEFT JOIN `#__jshopping_usergroups`  as UG ON PP.group_id=UG.usergroup_id WHERE PP.product_id= '".$product_id."'";        
        $db->setQuery($query);
        return $db->loadObjectList();
	}
	
	public function getUsergroupNameById($id){
		$db = \JFactory::getDBO(); 
		$query = "SELECT `usergroup_name` FROM `#__jshopping_usergroups` WHERE `usergroup_id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		return $db->loadResult();			
	}
	
	public function deleteUsergroupById($id){
		$db = \JFactory::getDBO(); 
		$query = "DELETE FROM `#__jshopping_usergroups` WHERE `usergroup_id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		return $db->execute();
	}
	
	public function getUsergroupsList($name = 'add_usergroups_prices_usergroup[100500]'){
		$usergroups = $this->getAllUsergroupsWithGuest();									
		return JHTML::_('select.genericlist', $usergroups, $name,'class = "inputbox form-select" size = "1"','usergroup_id','usergroup_name',0);		
	}

	public function productEditList_getUsergroupsList($name = 'add_usergroups_prices_usergroup')
	{
		$list = [
			JHTML::_('select.option', -1, '- - -', 'usergroup_id', $name)
		];

        foreach($this->getAllUsergroupsWithGuest() as $usergroup) {
            $list[] = JHTML::_('select.option', $usergroup->usergroup_id, $usergroup->usergroup_name, 'usergroup_id', $name);
        }

		return JHTML::_('select.genericlist', $list, $name, 'class = "inputbox form-select" size = "1"', 'usergroup_id', $name);	
	}
	
	public function getUsergroupsPrices($product_id,$product_add_prices){
		$_table_productprice = JSFactory::getTable('productPrice', 'jshop');
		$usergroups = $this->getAllUsergroupsWithGuest();	
		foreach ($usergroups as $key=>$value){
			if ($value->usergroup_id==0){
				foreach($_table_productprice->getAddPrices((int)$product_id,$value->usergroup_id,1) as $k=>$v){
					array_push($product_add_prices[$value->usergroup_id],$v);        
				}
			}else{
				$product_add_prices[$value->usergroup_id]=  $_table_productprice->getAddPrices((int)$product_id,$value->usergroup_id,1);      
			}
			//$product_add_prices[$value->usergroup_id] = array_reverse($product_add_prices[$value->usergroup_id]);
		}
		return $product_add_prices;
	}
	
	public function getUsergroupsShowProduct($product_id, $usergroup_show_product){
		$db = \JFactory::getDBO(); 
		$usergroups = $this->getAllUsergroupsWithGuest();
				
		if(!$product_id){
			$usergroup_show_product = '*';
		}	
		
		$groups = explode(' , ', $usergroup_show_product);
		
		$first = array();
		$first[] = JHTML::_('select.option', '*', JText::_('COM_SMARTSHOP_ALL'), 'usergroup_id','usergroup_name'); 
        return JHTML::_('select.genericlist',  array_merge($first, $usergroups), 'usergroup_show_product[]', 'class="inputbox" multiple="multiple" size="'.count($usergroups) .'"', 'usergroup_id', 'usergroup_name', $groups);
	}
	
	public function getUsergroupsShowPrice($product_id, $usergroup_show_price){
		$db = \JFactory::getDBO(); 
		$usergroups = $this->getAllUsergroupsWithGuest();
			
		if(!$product_id){
			$usergroup_show_price = '*';
		}	
		
		$groups = explode(' , ', $usergroup_show_price);
		
		$first = array();
		$first[] = JHTML::_('select.option', '*', JText::_('COM_SMARTSHOP_ALL'), 'usergroup_id','usergroup_name');
		return JHTML::_('select.genericlist',  array_merge($first, $usergroups), 'usergroup_show_price[]', 'class="inputbox" multiple="multiple" size="'.count($usergroups).'"', 'usergroup_id', 'usergroup_name', $groups);
	}

	public function getUsergroupsAddPrice($product_id, $usergroup_show_price){
		$db = \JFactory::getDBO();
		$usergroups = $this->getAllUsergroupsWithGuest();

		if(!$product_id){
			$usergroup_show_price = '*';
		}

		$groups = explode(' , ', $usergroup_show_price);

		$first = array();
		$first[] = JHTML::_('select.option', '*',JText::_('COM_SMARTSHOP_ALL'), 'usergroup_id','usergroup_name');
		return JHTML::_('select.genericlist',  array_merge($first, $usergroups), 'add_usergroups_prices_usergroup_list[100500]', 'class="inputbox" multiple="multiple" size="'.count($usergroups).'"', 'usergroup_id', 'usergroup_name', $groups);
	}
	
	public function getUsergroupsShowActions($product_id, $usergroup_show_buy){
		$db = \JFactory::getDBO(); 
		$usergroups = $this->getAllUsergroupsWithGuest();
		
		if(!$product_id){
			$usergroup_show_buy = '*';
		}	
		$groups = explode(' , ', $usergroup_show_buy);

		$first = array();
		$first[] = JHTML::_('select.option', '*',JText::_('COM_SMARTSHOP_ALL'), 'usergroup_id','usergroup_name');
		return JHTML::_('select.genericlist',   array_merge($first, $usergroups), 'usergroup_show_buy[]', 'class="inputbox form-select" multiple="multiple" size="'.count($usergroups).'"', 'usergroup_id', 'usergroup_name', $groups);
	}

	public function getAllUserGroupsIdsWithGuest()
	{
		$db = \JFactory::getDBO(); 
        $query = "SELECT `usergroup_id` FROM `#__jshopping_usergroups` order by `usergroup_id`";        
        $db->setQuery($query);
		$result = [0];
		$row = $db->loadRowList() ?: [];
		foreach ($row as $items) {
			foreach ($items as $item2) {
				$result[] = $item2;
			}
		}
		
        return $result;
	}

	public function addToShippingPayment($usergoup_id)
	{
		$db = \JFactory::getDBO(); 
		$_shippings = JSFactory::getModel("shippings");
		$_payments = JSFactory::getModel('payments');
        $shrows = $_shippings->getAllShippingPrices(0);
        $prows = $_payments->getAllPaymentMethods(0);
       
	    foreach($shrows as $k=>$val){
			$query = "UPDATE `#__jshopping_shipping_method_price` SET `usergroup_id`=".$db->quote($val->usergroup_id.','.$usergoup_id)." WHERE `sh_pr_method_id`=".$val->sh_pr_method_id;        
			$db->setQuery($query);
			$db->execute();
		}
		
		foreach($prows as $k=>$val){ 
			$query = "UPDATE `#__jshopping_payment_method` SET `usergroup_id`=".$db->quote($val->usergroup_id.','.$usergoup_id)." WHERE `payment_id`=".$val->payment_id;        
			$db->setQuery($query);
			$db->execute();
		}
		
		
		
		
		
	}
}
?>