<?php
/**
* @version      4.1.0 25.11.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelConditionsAdmin extends JModelLegacy{ 
	
    function getAllConditions($publish = 1, $order = null, $orderDir = null) {
        $db = \JFactory::getDBO(); 
        $ordering = 'ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT * 
                  FROM `#__jshopping_shipping_conditions`
                  ORDER BY ".$ordering;
        $db->setQuery($query);
        return $db->loadObjectList();
    }
	
	public function getRules($rules){
	    $r = [];
		//foreach($rules as $k=>$rule){
			//$r[] = json_decode($rules);
			$r = json_decode($rules);
		//}
		$formulas = '';
		//print_r($rules);die;
		//foreach($r as $k=>$value){ 
			$operator = '';
			$formula = '';
			if(isset($r->children) && !empty($r->children)){
				if($r->logicalOperator == 'and'){
					$operator = ' && ';				
				}elseif($r->logicalOperator == 'or'){
					$operator = ' || ';	
				}		
				foreach($r->children as $num=>$child){
					if($child->type == 'query-builder-rule'){
						$formula .= $this->getFormula($child, $operator, $formula);
					}elseif($child->type == 'query-builder-group'){
						if(strlen($formula) > 1){$formula .= $operator;}
						$formula .= $this->getGroupFormula($child, $operator, $formula);						
					}
				}
				$formulas = $formula;
			}
		//}
		return $formulas;
   }
   public function getGroupFormula($children, $operator, $formula){
		$f = '';
		if($children->query->logicalOperator == 'and'){
			$op = ' && ';				
		}elseif($children->query->logicalOperator == 'or'){
			$op = ' || ';	
		}
		
		foreach($children->query->children as $child){
			if($child->type == 'query-builder-rule'){
				$f .= $this->getFormula($child, $op, $f);
			}elseif($child->type == 'query-builder-group'){ 
				if(strlen($f) > 1){$f .= $op;}
				$f .= $this->getGroupFormula($child, $op, $formula);
			}
		}
		$f = ' ('.$f.')';
		return $f;
   }
   public function getFormula($value, $operator, $formula){
		$f = '';
		if($value->query->operator == '='){
			$v_operator = '==';
		}else{
			$v_operator = $value->query->operator;
		}
		if($value->query->rule == 'perimeter'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$min+$max+$median';
				$f .= $v_operator;
				$f .= $value->query->value;
			}
		}elseif($value->query->rule == 'area'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$max*$median';
				$f .= $v_operator;
				$f .= $value->query->value;
			}
		}elseif($value->query->rule == 'volume'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$max*$median*$min';
				$f .= $v_operator;
				$f .= $value->query->value;
			}
		}elseif($value->query->rule == 'formula'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= $value->query->value;
			}
		}elseif($value->query->rule == 'operation_whd'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$width';
				$f .= $value->query->operation_1_2;
				$f .= '$height';
				$f .= $value->query->operation_2_3;
				$f .= '$depth';
				$f .= $v_operator;
				$f .= $value->query->value;
			}
		}elseif($value->query->rule == 'operation_mmm'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$min';
				$f .= $value->query->operation_1_2;
				$f .= '$median';
				$f .= $value->query->operation_2_3;
				$f .= '$max';
				$f .= $v_operator;
				$f .= $value->query->value;
			}
		}else{
			if($value->query->value){			
				if(strlen($formula) > 0 && $value->query->value){ $f .= $operator;}
				$f .= '$'.$value->query->rule;
				$f .= $v_operator;
				$f .= $value->query->value;
			}
		}
	return $f;
   }
   
   function save($data){
	    $db = \JFactory::getDBO(); 
		
		if($data['condition_id']){
			$query = "UPDATE `#__jshopping_shipping_conditions` SET `name`=".$db->quote($data['name']).", `ordering`=".$db->quote($data['ordering']).", `rules`=".$db->quote($data['rules']).", `formula`=".$db->quote($data['formula']).", `rule_apply`=".$db->quote($data['rule_apply'])." WHERE `condition_id`=".$data['condition_id'];
			$db->setQuery($query);
			$db->execute();
			return $data['condition_id'];
		}else{			
			$query = "INSERT INTO `#__jshopping_shipping_conditions` SET `name`=".$db->quote($data['name']).", `ordering`=".$db->quote($data['ordering']).", `rules`=".$db->quote($data['rules']).", `formula`=".$db->quote($data['formula']).", `rule_apply`=".$db->quote($data['rule_apply']);
			$db->setQuery($query);
			$db->execute();
			return $db->insertid();
		}
		
   }
   
   function deleteShippingCondition($condition_id){
	    $db = \JFactory::getDBO(); 
		$query = "delete from #__jshopping_shipping_conditions where condition_id='".$db->escape($condition_id)."'";
        $db->setQuery($query);
        return $db->execute();
   }
   
   function deleteShippingConditionPrice($condition_id){
	    $db = \JFactory::getDBO(); 
		$query = "delete from #__jshopping_shipping_method_price_weight where condition_id='".$db->escape($condition_id)."'";
        $db->setQuery($query);
        return $db->execute();	
   }

	function getOptions(){
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_shipping_conditions_options` WHERE 1";
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	function saveConditionsOptions($data){
		$db = \JFactory::getDBO();
		$obj = $this->getOptions();
		if(!empty($obj)){
			$query = "UPDATE `#__jshopping_shipping_conditions_options` SET `width_id`=".$db->quote($data['width_id']).", `height_id`=".$db->quote($data['height_id']).", `depth_id`=".$db->quote($data['depth_id']);
			$db->setQuery($query);
			$db->execute();
		}else{
			$query = "INSERT IMTO `#__jshopping_shipping_conditions_options` SET `width_id`=".$db->quote($data['width_id']).", `height_id`=".$db->quote($data['height_id']).", `depth_id`=".$db->quote($data['depth_id']);
			$db->setQuery($query);
			$db->execute();
		}
	}

}
