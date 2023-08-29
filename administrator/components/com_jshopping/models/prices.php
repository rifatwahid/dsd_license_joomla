<?php
/**
* @version      4.7.0 28.10.2019
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelPrices extends JModelLegacy
{
	public function deleteOldPrices($product_id=0){
		$db = \JFactory::getDBO();
		$query = "delete from #__jshopping_products_prices_group where product_id='".$db->escape($product_id)."' ";
		$db->setQuery($query);
		$db->execute();
		$query = "delete from #__jshopping_products_prices where product_id='".$db->escape($product_id)."' ";
		$db->setQuery($query);
		$db->execute();
	}
	public function addUsergroupPrices($product_id,$post){
		$db = \JFactory::getDBO();
		$_products = JSFactory::getModel("products");

		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_jshopping/models');
		$modelOfProductsFront = JSFactory::getModel('ProductsFront');

		if ($post['add_usergroups_prices_usergroup_list']>0){
			foreach ($post['add_usergroups_prices_usergroup_list'] as $key=>$value){
				////////////////////////////////////////different_prices
				$post['add_usergroups_prices_different_prices'] = 0;//[$key]
				if (isset($post['add_usergroups_prices_product_is_add_price_list'][$key]) && $post['add_usergroups_prices_product_is_add_price_list'][$key]) $post['add_usergroups_prices_different_prices'] = 1;
				if (!isset($post['add_usergroups_prices_product_is_add_price_list'][$key])) $post['add_usergroups_prices_product_is_add_price_list'][$key] = 0;
				////////////////////////////////////////
				$post['add_usergroups_prices_product_price_list'][$key] = saveAsPrice($post['add_usergroups_prices_product_price_list'][$key]);
				$post['add_usergroups_prices_product_price2_list'][$key] = saveAsPrice($post['add_usergroups_prices_product_price2_list'][$key]);
				$post['add_usergroups_prices_product_old_price_list'][$key] = saveAsPrice($post['add_usergroups_prices_product_old_price_list'][$key]);
				$query = "select * from #__jshopping_products_prices_group where product_id='".$db->escape($product_id)."' and group_id='".(int)$post['add_usergroups_prices_usergroup_list'][$key]."'";
				$db->setQuery($query);			
				$add_usergroups_prices = $db->loadObjectList();
				if (count($add_usergroups_prices)>0){
					$query = "delete from #__jshopping_products_prices_group where product_id='".$db->escape($product_id)."' and group_id='".(int)$post['add_usergroups_prices_usergroup_list'][$key]."'";
					$db->setQuery($query);
					$db->execute();
				}
				
				$totalCalculatedPriceWithoutTax = null;
				if (isset($post['add_usergroups_prices_product_price_list'][$key])) {
					$totalCalculatedPriceWithoutTax = $modelOfProductsFront->calculateProductDataByProductId($product_id, 1, $db->escape($post['add_usergroups_prices_product_price_list'][$key]),true,false)['calculatedPrice'];
				}
                if(is_array($post['add_usergroups_prices_usergroup_list'][$key])){
                    foreach($post['add_usergroups_prices_usergroup_list'][$key] as $add_usergroups_prices_usergroup_list){
                        $query = "insert into #__jshopping_products_prices_group set product_id='" . $db->escape($product_id) . "', group_id='" . (int)$add_usergroups_prices_usergroup_list . "', price='" . $db->escape($post['add_usergroups_prices_product_price_list'][$key]) . "', price_netto='" . $db->escape($post['add_usergroups_prices_product_price2_list'][$key]) . "', old_price='" . $db->escape($post['add_usergroups_prices_product_old_price_list'][$key]) . "', product_is_add_price='" . $db->escape($post['add_usergroups_prices_product_is_add_price_list'][$key]) . "', add_price_unit_id='" . $db->escape($post['add_usergroups_prices_add_price_unit_id_list'][$key]) . "', `total_calculated_price_without_tax` = " . $totalCalculatedPriceWithoutTax;
                        $db->setQuery($query);
                        $db->execute();
                    }
                }else {
                    $query = "insert into #__jshopping_products_prices_group set product_id='" . $db->escape($product_id) . "', group_id='" . (int)$post['add_usergroups_prices_usergroup_list'][$key] . "', price='" . $db->escape($post['add_usergroups_prices_product_price_list'][$key]) . "', price_netto='" . $db->escape($post['add_usergroups_prices_product_price2_list'][$key]) . "', old_price='" . $db->escape($post['add_usergroups_prices_product_old_price_list'][$key]) . "', product_is_add_price='" . $db->escape($post['add_usergroups_prices_product_is_add_price_list'][$key]) . "', add_price_unit_id='" . $db->escape($post['add_usergroups_prices_add_price_unit_id_list'][$key]) . "', `total_calculated_price_without_tax` = " . $totalCalculatedPriceWithoutTax;
                    $db->setQuery($query);
                    $db->execute();
                }
				//Add prices			
				if ($post['add_usergroups_prices_product_is_add_price_list'][$key]){
					$_products->saveAditionalPrice($product_id, $post['add_usergroups_prices_product_add_discount_list'][$key], $post['add_usergroups_prices_quantity_start_list'][$key], $post['add_usergroups_prices_quantity_finish_list'][$key], $post['add_usergroups_prices_product_add_price_list'][$key], $post['add_usergroups_prices_start_discount_list'][$key],$post['add_usergroups_prices_usergroup_list'][$key],1);
				}
				/////////////
			}
		}

		if ($post['add_usergroup_price']>0){
			$post['add_usergroups_prices_different_prices'] = 0;
			if (isset($post['add_usergroups_prices_product_is_add_price']) && $post['add_usergroups_prices_product_is_add_price']) $post['add_usergroups_prices_different_prices'] = 1;
			if (!isset($post['add_usergroups_prices_product_is_add_price'])) $post['add_usergroups_prices_product_is_add_price'] = 0;
			$post['add_usergroups_prices_product_price'] = saveAsPrice($post['add_usergroups_prices_product_price']);
			$post['add_usergroups_prices_product_price2'] = saveAsPrice($post['add_usergroups_prices_product_price2']);
			$post['add_usergroups_prices_product_old_price'] = saveAsPrice($post['add_usergroups_prices_product_old_price']);
			$query = "select * from #__jshopping_products_prices_group where product_id='".$db->escape($product_id)."' and group_id='".(int)$post['add_usergroups_prices_usergroup']."'";
			$db->setQuery($query);			
			$add_usergroups_prices = $db->loadObjectList();
			if (count($add_usergroups_prices)>0){
				$query = "delete from #__jshopping_products_prices_group where product_id='".$db->escape($product_id)."' and group_id='".(int)$post['add_usergroups_prices_usergroup']."'";
				$db->setQuery($query);
				$db->execute();
			}

			$totalCalculatedPriceWithoutTax = null;
			if (isset($post['add_usergroups_prices_product_price'])) {
				$totalCalculatedPriceWithoutTax = $modelOfProductsFront->calculateProductDataByProductId($product_id, 1, $db->escape($post['add_usergroups_prices_product_price']))['product']->total_price_without_tax;
			}

			$query = "insert into #__jshopping_products_prices_group set product_id='".$db->escape($product_id)."', group_id='".(int)$post['add_usergroups_prices_usergroup']."', price='".$db->escape($post['add_usergroups_prices_product_price'])."', price_netto='".$db->escape($post['add_usergroups_prices_product_price2'])."', old_price='".$db->escape($post['add_usergroups_prices_product_old_price'])."', product_is_add_price='".$db->escape($post['add_usergroups_prices_product_is_add_price'])."', add_price_unit_id='".$db->escape($post['add_usergroups_prices_add_price_unit_id'])."', `total_calculated_price_without_tax` = " . $totalCalculatedPriceWithoutTax;
            $db->setQuery($query);
            $db->execute();
			//Add prices			
			if ($post['add_usergroups_prices_product_is_add_price']){
				$_products->saveAditionalPrice($product_id, $post['add_usergroups_prices_product_add_discount'], $post['add_usergroups_prices_quantity_start'], $post['add_usergroups_prices_quantity_finish'], $post['add_usergroups_prices_product_add_price'], $post['add_usergroups_prices_start_discount'],$post['add_usergroups_prices_usergroup'],1);
			}
			/////////////
			
		}	
	}
	
	public function getProductPricesByProductId($extProdIdFrom){
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_products_prices` WHERE `product_id` = " . (int)$extProdIdFrom;
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	
	public function calculePricesWithTax($product_price,$current_product_tax_value){
		$jshopConfig = JSFactory::getConfig();
		 if ($jshopConfig->display_price_admin==0){
			return formatEPrice($product_price / (1 + $current_product_tax_value / 100));
		}else{
			return formatEPrice($product_price * (1 + $current_product_tax_value / 100));
		}
	}
	
	public function getProductAttrPriceTypeSelect($default = 0, $name = 'product_price_type', $class = 'inputbox', $id = '', $showOneTimeCost = false) 
    {
        $oneTimePriceTypeArrKey = 100500;
        $model = JSFactory::getModel("freeattrcalcprice");
        $params = $model->getAddonParameters();
        $html = '<select name="'.$name.'" class="'.$class.' form-select"';
        if ($id != '') $html .= ' id="'.$id.'"';
        $html .= '>';
        $selected = ($default == 0) ? ' selected="selected"' : '';
        $html .= '<option value="0"'.$selected.'>'.JText::_('COM_SMARTSHOP_SELECT').'</option>';
        if (isset($params['pricetypes_formula']) && count($params['pricetypes_formula'])) {
            foreach ($params['pricetypes_formula'] as $key => $value) {
                if (($value == '' && $key != $oneTimePriceTypeArrKey) || ($key == $oneTimePriceTypeArrKey && !$showOneTimeCost)) continue;
                $selected = ($default == $key) ? ' selected="selected"' : '';
                $html .= '<option value="'.$key.'"'.$selected.'>'.$params['pricetypes_formula_name'][$key].'</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

	public function getProductAttrPriceTypeSelect_getAttrPriceList($default = -1, $name = 'product_price_type', $class = 'inputbox', $id = '', $showOneTimeCost = false) 
    {
        $oneTimePriceTypeArrKey = 100500;
        $model = JSFactory::getModel("freeattrcalcprice");
        $params = $model->getAddonParameters();
        $html = '<select name="'.$name.'" class="'.$class.' form-select"';
        if ($id != '') $html .= ' id="'.$id.'"';
        $html .= '>';
        $selected = ($default == -1) ? ' selected="selected"' : '';
		$html .= '<option value="-1"'.$selected.'>- - -</option>';
        $html .= '<option value="0">'.JText::_('COM_SMARTSHOP_SELECT').'</option>';
        if (isset($params['pricetypes_formula']) && count($params['pricetypes_formula'])) {
            foreach ($params['pricetypes_formula'] as $key => $value) {
                if (($value == '' && $key != $oneTimePriceTypeArrKey) || ($key == $oneTimePriceTypeArrKey && !$showOneTimeCost)) continue;
                $selected = ($default == $key) ? ' selected="selected"' : '';
                $html .= '<option value="'.$key.'"'.$selected.'>'.$params['pricetypes_formula_name'][$key].'</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
	
	 public function getProductPriceTypeSelect($default = 0, $name = 'product_price_type', $class = 'inputbox', $id = 'product_price_type') 
    {
        $html = '<select name="'.$name.'" class="'.$class.' form-select"';
        if ($id != '') $html .= ' id="'.$id.'"';
        $html .= '>';
        $selected = ($default == 0) ? ' selected="selected"' : '';
        $html .= '<option value="0"'.$selected.'>'.JText::_('COM_SMARTSHOP_PRICE_FOR_ONE').'</option>';
        $selected = ($default == 1) ? ' selected="selected"' : '';
        $html .= '<option value="1"'.$selected.'>'.JText::_('COM_SMARTSHOP_PRICE_FOR_M2').'</option>';
        $html .= '</select>';
        return $html;
    }
	
	public function getProductPriceTypeSelectQty($default = 0, $name = 'product_price_for_qty_type', $class = 'inputbox', $id = 'product_price_for_qty_type') 
    {
        $html = '<select name="'.$name.'" class="'.$class.' form-select"';
        if ($id != '') $html .= ' id="'.$id.'"';
        $html .= '>';
        $selected = ($default == 0) ? ' selected="selected"' : '';
        $html .= '<option value="0"'.$selected.'>'.JText::_('COM_SMARTSHOP_PRICE_QTY_FOR_ONE').'</option>';
        $selected = ($default == 1) ? ' selected="selected"' : '';
        $html .= '<option value="1"'.$selected.'>'.JText::_('COM_SMARTSHOP_PRICE_QTY_FOR_M2').'</option>';
        $html .= '</select>';
        return $html;
	}
	
	public function getProductPricesGroupByProductId($productId)
	{
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_products_prices_group` WHERE `product_id` = " . $db->q($productId);
		$db->setQuery($query);
		return $db->loadAssocList();
	}

	public function copyProductPricesGroup(int $fromId, int $toId)
	{
		$result = false;
		$productsPricesGroupFrom = $this->getProductPricesGroupByProductId($fromId);

		if (!empty($productsPricesGroupFrom)) {
			foreach ($productsPricesGroupFrom as $productPriceGroupFrom) {
				unset($productPriceGroupFrom['id']);
				$productPriceGroupFrom['product_id'] = $toId;
				$isSuccess = $this->addProductPriceGroup($productPriceGroupFrom);

				if ($isSuccess) {
					$result = $isSuccess;
				}
			}
		}

		return $result;
	}

	public function addProductPriceGroup(array $columnData)
	{
		$result = false;

		if (!empty($columnData)) {
			$db = \JFactory::getDBO();
			$columnsNames = $db->qn(array_keys($columnData));
			$columnsValues = $db->q(array_values($columnData));
			$sql = 'INSERT INTO `#__jshopping_products_prices_group`(' . implode(',', $columnsNames) . ') VALUES(' . implode(',', $columnsValues) . ');';
			$db->setQuery($sql);
			$result = $db->execute($sql);
		}

		return $result;
	}

	public function copyProductPrices(int $fromId, int $toId)
	{
		$result = false;
		$productsPricesFrom = $this->getProductPricesByProductId($fromId);

		if (!empty($productsPricesFrom)) {
			foreach ($productsPricesFrom as $productPriceFrom) {
				unset($productPriceFrom['price_id']);
				$productPriceFrom['product_id'] = $toId;
				$isSuccess = $this->addProductPrice($productPriceFrom);

				if ($isSuccess) {
					$result = $isSuccess;
				}
			}
		}

		return $result;
	}

	public function addProductPrice(array $columnData)
	{
		$result = false;

		if (!empty($columnData)) {
			$db = \JFactory::getDBO();
			$columnsNames = $db->qn(array_keys($columnData));
			$columnsValues = $db->q(array_values($columnData));
			$sql = 'INSERT INTO `#__jshopping_products_prices`(' . implode(',', $columnsNames) . ') VALUES(' . implode(',', $columnsValues) . ');';
			$db->setQuery($sql);
			$result = $db->execute($sql);
		}

		return $result;
	}

	public function deleteProductPricesGroupByProductId(int $productId)
	{
		$db = \JFactory::getDBO();
		$sql = 'DELETE FROM ' . $db->qn('#__jshopping_products_prices_group') . ' WHERE `product_id` = ' . $db->escape($productId);
		$db->setQuery($sql);

		return $db->execute();
	}
}