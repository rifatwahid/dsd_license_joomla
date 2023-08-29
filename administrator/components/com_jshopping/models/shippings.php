<?php
/**
* @version      4.7.1 22.10.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.modeladmin');

class JshoppingModelShippings extends JModelAdmin{
    
    function getForm($data = array(), $loadData = true){        
    }
    
    function getTable($type = 'shippingMethod', $prefix = 'jshop', $config = array()){
        return JSFactory::getTable($type, $prefix, $config);
    }

    function getAllShippings($publish = 1, $order = null, $orderDir = null) {
        $db = \JFactory::getDBO(); 
        $query_where = ($publish)?("WHERE published = '1'"):("");
        $lang = JSFactory::getLang();
        $ordering = 'ordering';
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        $query = "SELECT `sh_pr_method_id` as shipping_id, `".$lang->get('name')."` as name, `".$lang->get("description")."` as description, published, ordering  
                  FROM `#__jshopping_shipping_method_price` 
                  $query_where 
                  ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadObjectList();
    }

	function getAllShippingPricesByCountries($publish = 1, $shipping_method_id = 0, $order = null, $orderDir = null) {
		$shippings = $this->getAllShippingPrices($publish, 0);
		$this->shippingsByCountries($shippings);
		return $shippings;
	}
	
    function getAllShippingPrices($publish = 1, $shipping_method_id = 0, $order = null, $orderDir = null) {
        $db = \JFactory::getDBO(); 
		$shippings = array();
        $query_where = "";
        $query_where .= ($publish)?(" and shipping_price.published = '1'"):("");
        //$query_where .= ($shipping_method_id)?(" and shipping_price.shipping_method_id= '".$shipping_method_id."'"):("");
        
        $ordering = "shipping_price.sh_pr_method_id";
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        
        $lang = JSFactory::getLang();
        $query = "SELECT shipping_price.*, shipping_price.`".$lang->get('name')."` as name
                  FROM `#__jshopping_shipping_method_price` AS shipping_price
                  where (1=1) $query_where
                  ORDER BY ".$ordering;
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
		$shippings=$db->loadObjectList();
        return $shippings;
    }
    
    function getMaxOrdering(){
        $db = \JFactory::getDBO(); 
        $query = "select max(ordering) from `#__jshopping_shipping_method`";
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    function saveCountries($sh_pr_method_id, $countries){
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_shipping_method_price_countries` WHERE `sh_pr_method_id` = '" . $db->escape($sh_pr_method_id) . "'";
        $db->setQuery($query);
        $db->execute();
        if (!is_array($countries)) return 0;
        foreach($countries as $key => $value){
            $query = "INSERT INTO `#__jshopping_shipping_method_price_countries`
                      SET `country_id` = '" . $db->escape($value) . "', `sh_pr_method_id` = '" . $db->escape($sh_pr_method_id) . "'";
            $db->setQuery($query);
            $db->execute();
        }
    }

    function saveStates($sh_pr_method_id, $states){
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_shipping_method_price_states` WHERE `sh_pr_method_id` = '" . $db->escape($sh_pr_method_id) . "'";
        $db->setQuery($query);
        $db->execute();
        if (!is_array($states)) return 0;
        foreach($states as $key => $value){
            $query = "INSERT INTO `#__jshopping_shipping_method_price_states`
                      SET `state_id` = '" . $db->escape($value) . "', `sh_pr_method_id` = '" . $db->escape($sh_pr_method_id) . "'";
            $db->setQuery($query);
            $db->execute();
        }
    }

    function savePrices($sh_pr_method_id, $array_post) {        
        $db = \JFactory::getDBO();
        
        $query = "DELETE FROM `#__jshopping_shipping_method_price_weight` WHERE `sh_pr_method_id` = '".$db->escape($sh_pr_method_id)."'";
        $db->setQuery($query);
        $db->execute();
        
        if (!isset($array_post['shipping_price']) || !is_array($array_post['shipping_price'])) return 0;
        foreach($array_post['shipping_price'] as $key => $value){
            if(!$array_post['condition'][$key]){
                continue;
            }
            $sh_method = JSFactory::getTable('shippingMethodPriceWeight', 'jshop');            
            $sh_method->sh_pr_method_id = $sh_pr_method_id;
            $sh_method->shipping_price = saveAsPrice($array_post['shipping_price'][$key]);
            $sh_method->shipping_package_price = saveAsPrice($array_post['shipping_package_price'][$key]);
            $sh_method->condition_id = $array_post['condition'][$key];
            if (!$sh_method->store()) {
                \JFactory::getApplication()->enqueueMessage("Error saving to database" . $sh_method->_db->stderr(),'error');
            }
        }
    }
    
    function deletePriceWeight($sh_pr_weight_id) {
        $db = \JFactory::getDBO();
        $query = "DELETE FROM `#__jshopping_shipping_method_price_weight` WHERE `sh_pr_weight_id` = '".$db->escape($sh_pr_weight_id)."'";
        $db->setQuery($query);
        $db->execute();
    }
	
	function getListNameShippings($publish = 1){
        $_list = $this->getAllShippings($publish);
        $list = array();
        foreach($_list as $v){
            $list[$v->shipping_id] = $v->name;
        }
        return $list;
    }

	function uploadImage($post){
		$mainframe = JFactory::getApplication();
        $jshopConfig = JSFactory::getConfig();
		require_once ($jshopConfig->path.'lib/image.lib.php');
        require_once ($jshopConfig->path.'lib/uploadfile.class.php');
        $dispatcher = \JFactory::getApplication();
        
        $upload = new UploadFile($_FILES['image']);
        $upload->setAllowFile(array('jpeg','jpg','gif','png'));
        $upload->setDir($jshopConfig->image_shippings_path);
        $upload->setFileNameMd5(0);
        $upload->setFilterName(1);
        if ($upload->upload()){
            $name = $upload->getName();
            if ($post['old_image'] && $name!=$post['old_image']){
                @unlink($jshopConfig->image_shippings_path."/".$post['old_image']);
            }
            @chmod($jshopConfig->image_shippings_path."/".$name, 0777);
            
            if ($post['size_im_shippings'] < 3){
                if($post['size_im_shippings'] == 1){
                    $shippings_width_image = $jshopConfig->image_shippings_width; 
                    $shippings_height_image = $jshopConfig->image_shippings_height;
                }else{
                    $shippings_width_image = JFactory::getApplication()->input->getInt('shippings_width_image'); 
                    $shippings_height_image = JFactory::getApplication()->input->getInt('shippings_height_image');
                }

                $path_full = $jshopConfig->image_shippings_path."/".$name;
                $path_thumb = $jshopConfig->image_shippings_path."/".$name;
                if ($shippings_width_image || $shippings_height_image){
                    if (!ImageLib::resizeImageMagic($path_full, $shippings_width_image, $shippings_height_image, $jshopConfig->image_cut, $jshopConfig->image_fill, $path_thumb, $jshopConfig->image_quality, $jshopConfig->image_fill_color)) {
                        \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_CREATE_THUMBAIL'),'error');
                        saveToLog("error.log", "SaveCategory - Error create thumbail");
                    }
                }
                @chmod($jshopConfig->image_shippings_path."/".$name, 0777);
            }
            $shippings_image = $name;
            $dispatcher->triggerEvent('onAfterSaveShippingsImage', array(&$post, &$shippings_image, &$path_full, &$path_thumb));
        }else{
            $shippings_image = '';
            if ($upload->getError() != 4){
                \JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_UPLOADING_IMAGE'),'error');
                saveToLog("error.log", "SavePayments - Error upload image. code: ".$upload->getError());
            }
        }
        return $shippings_image;
    }
	
	function shippingsByCountries($rows){
		$db = \JFactory::getDBO(); 
		$lang = JSFactory::getLang();
        $_shippingsprices = JSFactory::getModel("shippingsprices");
		$query = "select MPC.sh_pr_method_id, C.`".$lang->get("name")."` as name from #__jshopping_shipping_method_price_countries as MPC 
                  left join #__jshopping_countries as C on C.country_id=MPC.country_id order by MPC.sh_pr_method_id, C.ordering";
        $db->setQuery($query);
        $list = $db->loadObjectList();
        $shipping_countries = array();        
        foreach($list as $smp){
            $shipping_countries[$smp->sh_pr_method_id][] = $smp->name;
        }
        unset($list);
        foreach($rows as $k=>$v){
            $rows[$k]->countries = "";
            $rows[$k]->states = "";
            if (is_array($shipping_countries[$v->sh_pr_method_id])){
                if (count($shipping_countries[$v->sh_pr_method_id])>10){
                    $tmp =  array_slice($shipping_countries[$v->sh_pr_method_id],0,10);
                    $rows[$k]->countries = implode(", ",$tmp)."...";
                }else{
                    $rows[$k]->countries = implode(", ",$shipping_countries[$v->sh_pr_method_id]);
                    if(count($shipping_countries[$v->sh_pr_method_id]) == 1){
                        $_states = $_shippingsprices->getShippingsPriceByStates($v->sh_pr_method_id, $lang->get("name"));
                        if(count($_states) > 10){
                            $_states =  array_slice($_states,0,10);
                            $rows[$k]->states = implode(", ",$_states)."...";
                        }else{
                            $rows[$k]->states = implode(", ",$_states);
                        }
                    }

                }                
            }
        }
	}
	function shippingsByProduct($product_id,$publish = 0, $shipping_method_id = 0, $order = null, $orderDir = null){
		$db = \JFactory::getDBO(); 
		$rows=$this->getAllShippingPricesByCountries($publish,$shipping_method_id);		
		$query = "select * from #__jshopping_products_shipping where product_id='".$db->escape($product_id)."'";
        $db->setQuery($query);
        $tmp = $db->loadObjectList();
		$products_shipping = array();
        foreach($tmp as $v){
            $products_shipping[$v->sh_pr_method_id] = $v;
        }
        
       /* foreach($rows as $k=>$v){
            if (!isset($products_shipping[$v->sh_pr_method_id])){
                $def = new stdClass();
                $def->sh_pr_method_id = $v->sh_pr_method_id;
                $def->published = 1;
                $def->price = -1; 
                $def->price_pack = -1; 
                $products_shipping[$v->sh_pr_method_id] = $def;
            }
        } */ 
		return $products_shipping;
	}
	function saveShippings($product_id,$post){
		$db = \JFactory::getDBO(); 
		$query = "delete from #__jshopping_products_shipping where product_id='".$db->escape($product_id)."'";
        $db->setQuery($query);
        $db->execute();
        
        if (!$post['spm_published']) $post['spm_published'] = array();        
        
        $count_enabled = 0;
        foreach($post['spm_published'] as $k=>$v){
            $sh_pr_method_id = $k;
            $published = $v;
            $price = $post['spm_price'][$k];
            $price_pack = $post['spm_pack_price'][$k];
            $query = "insert into #__jshopping_products_shipping set product_id='".$db->escape($product_id)."', sh_pr_method_id='".$db->escape($sh_pr_method_id)."', published='".$db->escape($published)."', price='-1', price_pack='-1'";
			$db->setQuery($query);
            $db->execute();
            if ($published){
                $count_enabled++;
            }
        }
	}
	
	public function deleteShippingById($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_shipping_method` WHERE `shipping_id` = '" . $db->escape($id) . "'";
		$db->setQuery($query);
		return $db->execute();
	}
	
	public function getShippingPricesByShippingId($id){
		$db = \JFactory::getDBO();
		$query = "SELECT `sh_pr_method_id` FROM `#__jshopping_shipping_method_price` WHERE `shipping_method_id` = '".$db->escape($value)."'";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public function deletePriceWeightByShippingId($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_shipping_method_price_weight` WHERE `sh_pr_method_id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		$db->execute();
	}
	
	public function deletePriceCountriesByShippingId($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_shipping_method_price_countries` WHERE `sh_pr_method_id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		$db->execute();                    
	}
	public function deleteShippingByShippingId($id){
		$db = \JFactory::getDBO();
		$query = "DELETE FROM `#__jshopping_shipping_method_price` WHERE `shipping_method_id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		$db->execute();
	}
	
	public function setOrderShipping($order){
		$shipping_params_data = unserialize($order->shipping_params_data);
		$shipping_ids = explode(',', $shipping_params_data['shipping_id'] ?? 0);
		$lang = JSFactory::getLang();
		$name = $lang->get("name");
		if(isset($order->shippings) && $order->shippings && count(explode('_', $order->shippings)) > 1){
		    return JText::_('COM_SMARTSHOP_COMPLEX_SHIPPING');
		}
		
		if(!empty($shipping_ids) && count($shipping_ids) > 0 && !empty($shipping_ids[0]) ){
			$shipping_information = '';
			foreach($shipping_ids as $id){
				$shippingMethod = JSFactory::getTable('shippingMethodPrice', 'jshop');
				$shippingMethod->load($id);
				if(strlen($shipping_information) > 0){$shipping_information .= ', ';}
				$shipping_information .= $shippingMethod->$name;
			}
			
			return $shipping_information;
		}else{
			$shippingMethod = JSFactory::getTable('shippingMethodPrice', 'jshop');
			$shippingMethod->load($order->shipping_method_id);
			return $shippingMethod->$name;
		}
   }
   
   public function setShippingNames($rows){	   
		if(!empty($rows)){
			foreach($rows as $order){
				$order->shipping_name = $this->setOrderShipping($order);
			}
		}
   }
   
   public function getRules($rules){
	    $r = [];
		foreach($rules as $k=>$rule){
			$r[] = json_decode($rules[$k]);
		}
		$formulas = [];
		foreach($r as $k=>$value){ 
			$operator = '';
			$formula = '';
			if(isset($value->children) && !empty($value->children)){
				if($value->logicalOperator == 'and'){
					$operator = ' && ';				
				}elseif($value->logicalOperator == 'or'){
					$operator = ' || ';	
				}		
				foreach($value->children as $num=>$child){
					if($child->type == 'query-builder-rule'){
						$formula .= $this->getFormula($child, $operator, $formula);
					}elseif($child->type == 'query-builder-group'){
						$formula .= $operator. $this->getGroupFormula($child, $operator, $formula);						
					}
				}
				$formulas[]= $formula;
			}
		}print_r($formulas);die;
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
				$f .= $op.$this->getGroupFormula($child, $op, $formula);
			}
		}
		$f = ' ('.$f.')';
		return $f;
   }
   public function getFormula($value, $operator, $formula){
		$f = '';
		if($value->query->rule == 'perimeter'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$min+$max+$medium';
				$f .= $value->query->operator;
				$f .= $value->query->value;
			}
		}elseif($value->query->rule == 'area'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$max*$medium';
				$f .= $value->query->operator;
				$f .= $value->query->value;
			}
		}elseif($value->query->rule == 'volume'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$max*$medium*$min';
				$f .= $value->query->operator;
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
				$f .= $value->query->operator;
				$f .= $value->query->value;
			}
		}elseif($value->query->rule == 'operation_mmm'){
			if($value->query->value){			
				if(strlen($formula) > 0){ $f .= $operator;}
				$f .= '$min';
				$f .= $value->query->operation_1_2;
				$f .= '$medium';
				$f .= $value->query->operation_2_3;
				$f .= '$max';
				$f .= $value->query->operator;
				$f .= $value->query->value;
			}
		}else{
			if($value->query->value){			
				if(strlen($formula) > 0 && $value->query->value){ $f .= $operator;}
				$f .= '$'.$value->query->rule;
				$f .= $value->query->operator;
				$f .= $value->query->value;
			}
		}
	return $f;
   }

}
?>