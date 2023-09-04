<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/exclude_attribute_for_attribute.php';
require_once JPATH_ROOT . '/components/com_jshopping/lib/constants.php';

class JshoppingModelAttrsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_attr';

    public function getByAttrId(string $attr_id, array $columnsToGet = ['*'])
    {
        $result = null;

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $sqlQuery = 'SELECT ' . $stringOfSearchColumns . ' FROM `' . self::TABLE_NAME . '` WHERE `attr_id` = \'' . $db->escape($attr_id) . '\'';

            $db->setQuery($sqlQuery);
            $result = $db->loadObject();
        }
        
        return $result;
    }

    public function getAllAttributes(int $groupordering = 1,$attr_id=0,$attr_type=0,$attr_ids=[])
    {
		$jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO();
		$where='';

        //if ($groupordering) {
        $ordering = 'G.ordering';
        //}        
		if ($attr_id) {
			$where.= '(';
			if (is_array($attr_id)){
				foreach($attr_id AS $key=>$value){
					if (strlen($where) > 1) $where.=' OR ';
					$where.=' A.attr_id='.$key;
				}
			}else{
				$where.=' A.attr_id='.$attr_id;
			}
			$where.= ')';
		}
		if (!empty($attr_ids)) {
			$where.="AND A.attr_id IN (".implode(',', $attr_ids).")";
		}
		
		if($attr_type == 1){
			if(strlen($where) > 0) $where.=' AND ';
			$where.=' A.`independent`=1';
			if($ordering || strlen($ordering) > 0){
				$ordering.= ',';
			}
			$ordering.= $this->orderAttrIndependent();
		}elseif($attr_type == 2){			
			if(strlen($where) > 0) $where.=' AND ';
			$where.=' A.`independent`=0';
			if($ordering || strlen($ordering) > 0){
				$ordering.= ',';
			}
			$ordering.= $this->orderAttrDependent();
		}elseif(!$ordering){
			$ordering = 'A.attr_ordering';
		}
		
		if(strlen($where) == 0) $where=' 1 ';
		
        $query = "SELECT A.attr_id, A.`" . $lang->get('name') . "` as name, A.`" . $lang->get('description') . "` as description, A.attr_type, A.independent, A.allcats, A.cats, A.attr_ordering, G.`" . $lang->get('name') . "` as groupname, G.`hide_title` 
                  FROM `#__jshopping_attr` as A 
				  left join `#__jshopping_attr_groups` as G on A.`group` = G.id
				  WHERE $where
                  ORDER BY " . $ordering;
				  
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if (!empty($rows)) {
            foreach($rows as $k => $v) {
                $rows[$k]->cats = ($v->allcats) ? [] : unserialize($v->cats);
            }
        }
        return $rows;
    }

    /**
     *  @var array $attrs   - [attrId => attrValId]
     */
    public function separateAttrsByTypes(array $attrs): object
    {
        $result = new stdClass;
        $result->depends = [];
        $result->independs = [];

        if (!empty($attrs)) {
           // $allAttrs = JSFactory::getAllAttributes(1);
            $dependsAttrs = [];
            $independsAttrs = [];

            foreach($attrs as $k => $v) {
				$allAttrs = JSFactory::getAllAttributes(1, $k);
                if ($allAttrs[$k]->independent == 0) {
                    $dependsAttrs[$k] = $v;
                } else {
                    $independsAttrs[$k] = $v;
                }
            }

            $result->depends = $dependsAttrs;
            $result->independs = $independsAttrs;
        }

        return $result;
    }

    public function generateHtmlImgOfProdAttr(int $attr_id, ?string $img): string
    {
        $jshopConfig = JSFactory::getConfig();

        if (!empty($img)) {
            $path = $jshopConfig->image_attributes_live_path;
        } else {
            return '';
        }

        $urlimg = getPatchProductImage($img, '', 1);
        
        return '<img id="prod_attr_img_' . $attr_id . '" src="' . $urlimg . '" alt="" />';
    }

    public function selectAllWhereAttrTypeMoreFor(int $moreFor)
    {
        return $this->select(['*'], ['attr_type > ' . $moreFor]);
    }

    public function getDependAttrs($productId, $isUseExpirationDate = true)
    {
        $sortedProductAttrs = [];

        if (!empty($productId)) {
            $attrTypeId = 2;
            $sortedProductAttrs = JSFactory::getModel('ProductAttrsFront')->getByProductIdAndOrderBy($productId);//,[],$this->orderAttributesDependent()
            $attrsTypes = $this->selectAllWhereAttrTypeMoreFor($attrTypeId);

            $exist_new_attrs = 0;
           
            foreach ($sortedProductAttrs as $key => $sortedProdAttr) {
				if($isUseExpirationDate && !empty((int)$sortedProdAttr->expiration_date) && $sortedProdAttr->expiration_date < date('Y-m-d') ){
					unset($sortedProductAttrs[$key]);
					continue;
				}				
                foreach ($attrsTypes as $attrType) {
                    $attrPrefixWithId = 'attr_' . $attrType->attr_id;				
                
                    if (isset($sortedProdAttr->$attrPrefixWithId)) {
                        $exist_new_attrs = $attrType->attr_type;				
                    }
                }
                
                if ($exist_new_attrs > 0) {
                    $sortedProductAttrs[$key]->attr_type = $attrType->attr_type;
                }
            }	
        }
        		
		return $sortedProductAttrs;
    }

    public function getRequireAttrsIdsByProdId(int $productId)
    {
        $require = [];
        $jshopConfig = JSFactory::getConfig();

        if (!$jshopConfig->admin_show_attributes) {
            return $require;
        }

        $allattribs = JSFactory::getAllAttributes(2);
        $dependAttrs = $allattribs['dependent'];
        $independAttrs = $allattribs['independent'];
     
        if (!empty($dependAttrs)) {
            $prodAttribVal = $this->getDependAttrs($productId);
            if (!empty($prodAttribVal)) {
                $prodAtrtib = $prodAttribVal[array_key_first($prodAttribVal)];
                foreach($dependAttrs as $attrib) {
                    $field = 'attr_' . $attrib->attr_id;

                    if ($attrib->attr_type != 3 && $prodAtrtib->$field) {
                        $require[] = $attrib->attr_id;
					}
                }
            }
        }
        
        if (!empty($independAttrs)) {
            $prodAttribVal2 = JSFactory::getModel('ProductAttrs2Front')->getIndependAttrs($productId);			
			if (!empty($prodAttribVal2)) {
				foreach($prodAttribVal2 as $attrib) {
					if(!empty((int)$attrib->expiration_date) && $attrib->expiration_date < date('Y-m-d')) {
                        continue;
                    }
                    
					if (!in_array($attrib->attr_id, $require)) {
						$require[] = $attrib->attr_id;
					}
				}
			}
        }

        return $require;
    }

    public function getAttrsValsByProdAndAttrIds(int $productId, int $attr_id, array $other_attr = [], int $onlyExistProduct = 0, int $useSortFromDragAndDrop = 0): array
    {
		$list = $this->getAttrsValsByProdAndAttrIdsList($productId, $attr_id, $other_attr, $onlyExistProduct, $useSortFromDragAndDrop);	
		$_product = JSFactory::getTable('product');
		$_product->load($productId);
		if($_product->product_packing_type == 1){
			$_list = [];
			if(!empty($list)){
				foreach($list as $k=>$v){ 
					if(($v->expiration_date && $v->expiration_date != 0) && ($_product->unlimited || (isset($v->unlimited) && $v->unlimited > 0) || $v->count > 0)){
							$_list[] = $v;
							break;
					}else{
						$_list[] = $v;
					}
					
				}
			}
			return $_list;
		}else{
			return $list;
		}
			
    }

    public function getAttrsVals(int $productId, int $attr_id, array $other_attr = [], int $onlyExistProduct = 0, int $useSortFromDragAndDrop = 0): array
    {
		$list = $this->getAttrsValsByProdAndAttrIdsList($productId, $attr_id, $other_attr, $onlyExistProduct, $useSortFromDragAndDrop);
		
		$_product = JSFactory::getTable('product');
		$_product->load($productId);
		$_list = [];
		if($_product->product_packing_type == 1){
			if(!empty($list)){
				foreach($list as $k=>$v){
					if($product->unlimited || $v->count > 0 || $v->unlimited){
						if($v->expiration_date && $v->expiration_date != 0){
							$_list[] = $v->val_id;
							
						}
					}
				}
			}
		}
		return $_list;
			
    }

    public function getAttrsValsByProdAndAttrIdsList(int $productId, int $attr_id, array $other_attr = [], int $onlyExistProduct = 0, int $useSortFromDragAndDrop = 0): array
    {
        $allattribs = JSFactory::getAllAttributes(1,$attr_id);
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO();
		
		if ($allattribs[$attr_id]->independent == 0) {
			$order_by=$this->orderAttributesDependent();
		}else{
			$order_by=$this->orderAttributesIndependent();
		}
        $query = "select PA.price_mod as price_mod,PA.sorting, PA.weight as attrib_ind_weight, PA.attr_value_id as val_id, PA.`expiration_date`, V.`{$lang->get('name')}` as value_name, V.image, price_mod, addprice , PA.`price_type`
                    from #__jshopping_products_attr2 as PA INNER JOIN #__jshopping_attr_values as V ON PA.attr_value_id=V.value_id
                    where PA.product_id = '{$productId}' and PA.attr_id='{$attr_id}'
                    ".$order_by;

        if ($allattribs[$attr_id]->independent == 0) {			
            $where = '';

            foreach($other_attr as $k => $v) {
            	if ( !empty($v) ) {
            		$where .= " and PA.attr_{$k} = '{$v}'";
            	}
            }

            if ($onlyExistProduct) {
                $where .= ' and ( PA.count > 0 OR PA.unlimited = 1) ';
            }

            $field = 'attr_' . $attr_id;
			$sql = 'SHOW COLUMNS FROM `#__jshopping_products_attr` LIKE ' . $db->quote($field);
			$db->setQuery($sql);
			$attrField = $db->loadResult();
			
			if(!$attrField) return array();
            $query = "SELECT distinct PA.sorting,PA.price,PA.count,PA.product_attr_id,PA.ean,PA.{$field} as val_id, PA.`expiration_date`, V.`{$lang->get('name')}` as value_name, V.image 
                      FROM `#__jshopping_products_attr` as PA 
                      INNER JOIN #__jshopping_attr_values as V ON PA.$field = V.value_id
                      WHERE PA.product_id = '{$productId}' {$where}
					  GROUP BY `val_id`
                      ".$order_by;
            if ($useSortFromDragAndDrop == 1) {
                $query = "SELECT distinct PA.sorting,PA.price,PA.count,PA.product_attr_id,PA.ean,PA.{$field} AS `val_id`, PA.`expiration_date`, V.`{$lang->get('name')}` AS `value_name`, `V`.`image`
                    FROM `#__jshopping_products_attr` AS `PA`
                    INNER JOIN `#__jshopping_attr_values` AS `V` ON `PA`.{$field} = `V`.`value_id`
                    INNER JOIN `#__jshopping_sort_val_attrs` AS `sortVal` ON `PA`.{$field} = `sortVal`.`attr_val_id` AND `PA`.`product_id` = `sortVal`.`product_id`
                    WHERE `PA`.`product_id` = '{$productId}' {$where}
					GROUP BY `val_id`
                    ".$order_by;
            }   
        }

        $db->setQuery($query);
        $list = $db->loadObjectList() ?: [];
		
		/*EXCLUDE ATTRS*/
		$list=$db->loadObjectList();
		require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/jshEAFAhelper.php';
		jshEAFAhelper::excludeAttrsValuesOnProductPage($attr_id,$other_attr,$list);		
		/*EXCLUDE ATTRS*/		
		
		if(count($list) > 0){
			foreach($list as $k=>$val){
				if((int)$val->expiration_date){
					$d1 = $val->expiration_date;
					$d2 = date('Y-m-d');
				
					if($d1 < $d2){
						unset($list[$k]);
					} 						
				}
				if(isset($list[$k]) && $list[$k] && isset($val->ext_attribute_product_id)){
					$query = "SELECT `usergroup_show_product`
							FROM `#__jshopping_products` 
							WHERE `product_id` = '{$val->ext_attribute_product_id}'";
					$db->setQuery($query);
					$list[$k]->usergroup_show_product = $db->loadResult();
					
					$query = "SELECT `usergroup_show_price`
							FROM `#__jshopping_products` 
							WHERE `product_id` = '{$val->ext_attribute_product_id}'";
					$db->setQuery($query);
					$list[$k]->usergroup_show_price = $db->loadResult();
					
					$query = "SELECT `usergroup_show_buy`
							FROM `#__jshopping_products` 
							WHERE `product_id` = '{$val->ext_attribute_product_id}'";
					$db->setQuery($query);
					$list[$k]->usergroup_show_buy = $db->loadResult();
					
					$query = "SELECT `is_use_additional_usergroup_permission`
							FROM `#__jshopping_products` 
							WHERE `product_id` = '{$val->ext_attribute_product_id}'";
					$db->setQuery($query);
					$list[$k]->is_use_additional_usergroup_permission = $db->loadResult();
				}
				
			}
		}
		$dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeReturnAttrsVals', [&$productId, &$attr_id, &$list]);
		
		return $list;
    }

    public function buildSelectAttributes(array $attributeValues, array &$attributeActive, ?int $currencyId, ?int $productTaxId, $isEnabledExcludeAttr = true)
    {
        $jshopConfig = JSFactory::getConfig();
        if (!$jshopConfig->admin_show_attributes) {
            return [];
        }

        $dispatcher = \JFactory::getApplication();
        $attrib = JSFactory::getAllAttributes(1, 0, array_keys($attributeActive));
        $userShop = JSFactory::getUserShop();
        $selects = [];
        foreach ($attrib as $k => $v) {
            $attr_id = $v->attr_id;
            if (isset($attributeValues[$attr_id])) {/////////////TASK 5814 && $attributeValues[$attr_id] 
                $_firstval = $attributeActive[$attr_id] ?? 0;

                $selects[$attr_id] = new stdClass();
                $selects[$attr_id]->attr_id = $attr_id;
                $selects[$attr_id]->attr_name = $v->name;
                $selects[$attr_id]->attr_description = $v->description;
                $selects[$attr_id]->groupname = $v->groupname;
                $selects[$attr_id]->hide_title = $v->hide_title;
                $selects[$attr_id]->firstval = $_firstval;
                $options = $attributeValues[$attr_id];
                $attrimage = [];

                foreach($options as $k2 => $v2) {
                    $attrimage[$v2->val_id] = $v2->image;
                    $addPrice = $v2->addprice ?? 0;
                    $addPrice = getPriceFromCurrency($addPrice, $currencyId);
                    $addPrice = getPriceCalcParamsTax($addPrice, $productTaxId);

                    if ($userShop->percent_discount && $v2->price_type != 100500) {
                        $addPrice = getPriceDiscount($addPrice, $userShop->percent_discount);
                    }

					if($v2->expiration_date){
						$selects[$attr_id]->expiration = 1;
					}else{
						$selects[$attr_id]->expiration = 0;
					}
                    $options[$k2]->addprice = $addPrice;
                }

                if ($v->attr_type == 2 || $v->attr_type == 3) {
                    foreach($options as $k2 => $v2) {
                        if ($v2->image) {
                            $options[$k2]->value_name = "<img src='" . getPatchProductImage($v2->image, '', 1) . "' alt='' /> {$v2->value_name}";
                        }
                    }

                    if ($jshopConfig->attr_display_addprice) {
                        foreach($options as $k2 => $v2) {
                            if (($v2->price_mod == '+' || $v2->price_mod == '-' || $jshopConfig->attr_display_addprice_all_sign) && $v2->addprice > 0) {
                                $ext_price_info = ' (' . $v2->price_mod . formatprice($v2->addprice) . ')';
                                $options[$k2]->value_name .= $ext_price_info;
                            }
                        }
                    }

                    $radioseparator = '';
                    foreach($options as $k2 => $v2) {
                        $options[$k2]->value_name = "<span class='radio_attr_label'>{$v2->value_name}</span>";
                    }

                    if ($v->attr_type == 2) {
                        // attribut type radio
                        $selectsMarkup = sprintRadioList($options, 'jshop_attr_id[' . $attr_id . ']', '', 'val_id', 'value_name', $attributeActive[$attr_id], $radioseparator);
                    } else {
                        // attribut type hidden
                        $selectsMarkup = sprintHiddenList($options, 'jshop_attr_id[' . $attr_id . ']', '', 'val_id', 'value_name', $attributeActive[$attr_id], $radioseparator);
                    }
                    
                    $selects[$attr_id]->selects = $selectsMarkup;
                } else {

                    if ($v->attr_type == 1 || $v->attr_type == 4) {
                        if ($jshopConfig->attr_display_addprice) {
                            foreach($options as $k2 => $v2) {
                                if (isset($v2->price_mod) && ($v2->price_mod == '+' || $v2->price_mod == '-' || $jshopConfig->attr_display_addprice_all_sign) && $v2->addprice > 0) {
                                    $ext_price_info = ' (' . $v2->price_mod . formatprice($v2->addprice) . ')';
                                    $options[$k2]->value_name .= $ext_price_info;
                                }
                            }
                        }
                    }

                    switch ($v->attr_type) {
                        case 1:                
                            if ($jshopConfig->product_attribut_first_value_empty) {
                                $options = array_merge([
                                    JHTML::_('select.option', '0', JText::_('COM_SMARTSHOP_SELECT'), 'val_id','value_name')
                                ], $options);
                            }
    
                            $_active_image = '';
                            if (isset($attributeActive[$attr_id]) && isset($attrimage[$attributeActive[$attr_id]])) {
                                $_active_image = $attrimage[$attributeActive[$attr_id]];
                            }
    
                            $_select_active = $attributeActive[$attr_id] ?? '';

                            $htmlProdAttrImg = '';
                            if (!empty($_active_image)) {
                                $htmlProdAttrImg = JSFactory::getModel('AttrsFront')->generateHtmlImgOfProdAttr($attr_id, $_active_image);
                                $htmlProdAttrImg = "<span class='prod_attr_img'>{$htmlProdAttrImg}</span>";
                            }
                            $selects[$attr_id]->selects = JHTML::_('select.genericlist', $options, 'jshop_attr_id[' . $attr_id . ']', 'class = "inputbox form-select" size = "1"','val_id','value_name', $_select_active) . $htmlProdAttrImg;
                        break; 
                        case 4:                        
                            $selects[$attr_id]->selects = sprintCheckboxList($options, 'jshop_attr_id[' . $attr_id . ']', 'onchange="shopProductAttributes.setNewValue(\'' . $attr_id . '\', this.value,this.id);"', 'val_id', 'value_name', $attributeActive[$attr_id], $radioseparator);
                        break;
                    }
                }

                if ($v->attr_type == 1 || $v->attr_type == 2 || $v->attr_type == 3 || $v->attr_type == 4) {
                    $selects[$attr_id]->selects = str_replace(["\n","\r","\t"], '', $selects[$attr_id]->selects);
                    $selects[$attr_id]->attr_type=$v->attr_type;
                }

                if ($isEnabledExcludeAttr) {
                    ExcludeAttributeForAttribute::getInstance()->onBuildSelectAttribute($attributeValues, $attributeActive, $selects, $options, $attr_id, $v);					
					ExcludeButtonsForAttribute::getInstance()->onBuildSelectAttribute($attributeValues, $attributeActive, $selects, $options, $attr_id, $v);					
                }

                $dispatcher->triggerEvent('onBuildSelectAttribute', [&$attributeValues, &$attributeActive, &$selects, &$options, &$attr_id, &$v]);
            }
        }

        $grname = '';
        foreach($selects as $k => $v) {
            $selects[$k]->grshow = 0;

            if ($v->groupname != $grname) {
                $grname = $v->groupname;
                $selects[$k]->grshow = 1;
            }
        }
        
        return $selects;
    }

    public function getActiveData(int $productId, ?array $selectedAttrs = [])
    {
        $jshopConfig = JSFactory::getConfig();
        $data = [
            'attributeValues' => [],
            'attributeActive' => [],
            'attributeSelected' => []
        ];

        $requireAttrs = JSFactory::getModel('AttrsFront')->getRequireAttrsIdsByProdId($productId);
        $activeAttrs = [];

        foreach($requireAttrs as $attr_id) {
            $optionsOfAttrs = JSFactory::getModel('AttrsFront')->getAttrsValsByProdAndAttrIds($productId, $attr_id, $activeAttrs, $jshopConfig->hide_product_not_avaible_stock, 1);			
			require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/jshEAFAhelper.php';
			jshEAFAhelper::excludeAttrsValuesOnProductPageAjax($attr_id,$selectedAttrs,$optionsOfAttrs);
			
			$data['attributeValues'][$attr_id] = $optionsOfAttrs;
			
			$attributOrm = JSFactory::getTable('attribut', 'jshop');
            $typeAttribut = $attributOrm->getTypeAttribut($attr_id);
            $activeAttrs[$attr_id] = 0;

			if (!$jshopConfig->product_attribut_first_value_empty && $typeAttribut != 4 && !empty($optionsOfAttrs[array_key_first($optionsOfAttrs)]->val_id)) {
                $activeAttrs[$attr_id] = $optionsOfAttrs[array_key_first($optionsOfAttrs)]->val_id;
            }
            
            if (isset($selectedAttrs[$attr_id])) {
                $testActived = 0;

                foreach($optionsOfAttrs as $option) {
                    if (!is_array($selectedAttrs[$attr_id]) && $option->val_id == $selectedAttrs[$attr_id]) {
                        $testActived = 1;
                    } elseif (is_array($selectedAttrs[$attr_id])) {
                        foreach($selectedAttrs[$attr_id] as $val) {
                            if($option->val_id == $val)  {
                                $testActived = 1;
                            }
                        }
                    }
                }
                
                if ($testActived) {
                    $activeAttrs[$attr_id] = $selectedAttrs[$attr_id];
                }
            }			
        }

        if (count($requireAttrs) == count($activeAttrs)) {
            $data['attributeActive'] = $activeAttrs;
        }

        $data['attributeSelected'] = $activeAttrs;				
        return $data;
    }

    public function calcPriceOfIndepentent($priceToModify, $activeFreeAttrs, $productId, ?array $independentAttrs)
	{
		if (!empty($independentAttrs)) {
            $modelOfFreeAttrCalcPriceAdmin = JSFactory::getModel('FreeAttrCalcPrice');
			$paramsOfFreeAttrCalcPriceAdmin = $modelOfFreeAttrCalcPriceAdmin->getParameters();

			// Generate variables for formula eval
			if ($paramsOfFreeAttrCalcPriceAdmin->freeAttrsParamsVarsIds) {
				foreach($paramsOfFreeAttrCalcPriceAdmin->freeAttrsParamsVarsIds as $freeAttrId => $freeAttrVal) {
					$varName = 'var' . $freeAttrId;                    
                    $freeAttrValue = $activeFreeAttrs[$freeAttrId] ?? $paramsOfFreeAttrCalcPriceAdmin->freeAttrsParamsVarsIds[$freeAttrId] ?? 0;
                    ${$varName} = saveAsPrice($freeAttrValue);
                }
			}
			// end

            $modelOfProductAttrs2Front = JSFactory::getModel('ProductAttrs2Front');
			foreach($independentAttrs as $indepAttrId => $indepAttrVal) {
				if(is_array($indepAttrVal)){
					foreach($indepAttrVal as $indepAttrValId):
						$independentsData = false;
						if($indepAttrValId){
							$independentsData = $modelOfProductAttrs2Front->getByProductAndAttrAndAttrValIds($productId, $indepAttrId, $indepAttrValId);
						}
						if (!empty($independentsData) && $independentsData->price_type != ONE_TIME_COST_PRICE_TYPE_ID) {
							$formula = $paramsOfFreeAttrCalcPriceAdmin->priceTypes['formula'][$independentsData->price_type];		
							$freeAttrsParamsIds = $paramsOfFreeAttrCalcPriceAdmin->freeAttrsParamsIds;

							$width = saveAsPrice($activeFreeAttrs[$freeAttrsParamsIds['width_id']]);
							$height = saveAsPrice($activeFreeAttrs[$freeAttrsParamsIds['height_id']]);
							$depth = saveAsPrice($activeFreeAttrs[$freeAttrsParamsIds['depth_id']]);		
							
							if (!empty($formula)) {
								eval('$addprice = ' . $formula . ';');
								$addprice *= $independentsData->addprice;
							} else {
								$addprice = $independentsData->addprice;
							}
							
							$priceToModify = getModifyPriceByMode($independentsData->price_mod, $priceToModify, $addprice);
						}
					endforeach;
				}else{
					$indepAttrValId = $indepAttrVal;
					$independentsData = $modelOfProductAttrs2Front->getByProductAndAttrAndAttrValIds($productId, $indepAttrId, $indepAttrValId);
					
					if (!empty($independentsData) && $independentsData->price_type != ONE_TIME_COST_PRICE_TYPE_ID) {
						$formula = $paramsOfFreeAttrCalcPriceAdmin->priceTypes['formula'][$independentsData->price_type];		
						$freeAttrsParamsIds = $paramsOfFreeAttrCalcPriceAdmin->freeAttrsParamsIds;

						$width = saveAsPrice(isset($activeFreeAttrs[$freeAttrsParamsIds['width_id']]) ? $activeFreeAttrs[$freeAttrsParamsIds['width_id']] : null);
						$height = saveAsPrice(isset($activeFreeAttrs[$freeAttrsParamsIds['height_id']]) ? $activeFreeAttrs[$freeAttrsParamsIds['height_id']] : null);
						$depth = saveAsPrice(isset($activeFreeAttrs[$freeAttrsParamsIds['height_id']]) ? $activeFreeAttrs[$freeAttrsParamsIds['depth_id']] : null);		
						
						if (!empty($formula)) {
							eval('$addprice = ' . $formula . ';');
							$addprice *= $independentsData->addprice;
						} else {
							$addprice = $independentsData->addprice;
						}
						
						$priceToModify = getModifyPriceByMode($independentsData->price_mod, $priceToModify, $addprice);
					}
				}								
			}
		}

		return $priceToModify;
    }
	
    public function excludeAttrs($activeAttrs) {

        if (!empty($activeAttrs)) {
            require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/exclude_attribute_for_attribute/jshEAFAhelper.php';
			require_once JPATH_ROOT . '/administrator/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/jshEAFAhelperbuttons.php';
            $listOfExcludeAttrs = jshEAFAhelper::getExcludesAttr($activeAttrs);

            if (!empty($activeAttrs)) {
                foreach($activeAttrs as $attrId => &$attrValId) {
                    if ((isset($listOfExcludeAttrs[$attrId]) && empty($listOfExcludeAttrs[$attrId])) || isset($listOfExcludeAttrs[$attrId][$attrValId])) {
                        unset($activeAttrs[$attrId]);
                    }
                }
            }
        }

        return $activeAttrs;
    }
	
	public function countAttrExpirationData($product_id, $attribute_active){
		$count_attr_expiration_date = 0;
		if(!empty($attribute_active)){
			foreach($attribute_active as $key=>$val){							
				$attrs = $this->getAttrsValsByProdAndAttrIdsList($product_id, $key);
				foreach($attrs as $k=>$value){
					if($value->count > 0){
						if($value->unlimited == 1 && $value->expiration_date != 0){
							$count_attr_expiration_date = INF;
							break;
						}elseif($value->expiration_date && $value->expiration_date != 0){
							$count_attr_expiration_date += $value->count;
						}
					}
				}
			}
		}
		return $count_attr_expiration_date;
	}
	
	public function countAttrExpirationDataVal($product_id, $attribute_active){
		$count_attr_expiration_date = 0;
		if(!empty($attribute_active)){
			foreach($attribute_active as $key=>$val){							
				$attrs = $this->getAttrsValsByProdAndAttrIdsList($product_id, $key);
	
				foreach($attrs as $k=>$value){
					if($value->count > 0){
						if($value->unlimited == 1 && $value->expiration_date != 0 && $value->val_id == $val){
							$count_attr_expiration_date = INF;
							break;
						}elseif($value->expiration_date && $value->expiration_date != 0 && $value->val_id == $val){
							$count_attr_expiration_date += $value->count;
						}
					}
				}
			}
		}	
		return $count_attr_expiration_date;
	}
	
	public function countAttrDataVal($product_id, $attribute_active){
		$count_attr_expiration_date = 0;		
		if(!empty($attribute_active)){
			foreach($attribute_active as $key=>$val){							
				$attrs = $this->getAttrsValsByProdAndAttrIdsList($product_id, $key);
				foreach($attrs as $k=>$value){
					if($value->count > 0){
						if($value->unlimited == 1 && $value->val_id == $val){
							$count_attr_expiration_date = INF;
							break;
						}elseif($value->expiration_date && $value->val_id == $val){
							$count_attr_expiration_date += $value->count;
						}
					}else{
						break;
					}
				}
			}
		}
		return $count_attr_expiration_date;
	}
	
	public function countAttrDataValProduct($product_id, $attribute_active){
		$count_attr_expiration_date = 0;
		if(!empty($attribute_active)){
			foreach($attribute_active as $key=>$val){							
				$attrs = $this->getAttrsValsByProdAndAttrIdsList($product_id, $key);
				foreach($attrs as $k=>$value){
					if(isset($value->count) && $value->count > 0){
						if(isset($value->expiration_date) && isset($value->unlimited) && $value->unlimited == 1 && $value->expiration_date){
							$count_attr_expiration_date = INF;
							break;
						}elseif($value->expiration_date){
							$count_attr_expiration_date += $value->count;
						}
					}
				}
			}
		}
		return $count_attr_expiration_date;
	}
	
	public function getDependAttrsAdmin($productId, $isUseExpirationDate = true)
    {
        $sortedProductAttrs = [];

        if (!empty($productId)) {
            $attrTypeId = 2;
			$orderBy = 'sorting';
			if($isUseExpirationDate){				
				$orderBy = 'expiration_date, sorting';
			}
            $sortedProductAttrs = JSFactory::getModel('ProductAttrsFront')->getByProductIdAndOrderBy($productId, [], $orderBy);
            $attrsTypes = $this->selectAllWhereAttrTypeMoreFor($attrTypeId);

            $exist_new_attrs = 0;
            foreach ($sortedProductAttrs as $key => $sortedProdAttr) {
						
                foreach ($attrsTypes as $attrType) {
                    $attrPrefixWithId = 'attr_' . $attrType->attr_id;				
                
                    if (isset($sortedProdAttr->$attrPrefixWithId)) {
                        $exist_new_attrs = $attrType->attr_type;				
                    }
                }
                
                if ($exist_new_attrs > 0) {
                    $sortedProductAttrs[$key]->attr_type = $attrType->attr_type;
                }
            }	
        }
        		
		return $sortedProductAttrs;
    }
	
	public function orderAttributesDependent(){
		$jshopConfig = JSFactory::getConfig();
		switch($jshopConfig->attribut_dep_sorting_in_product){
		case 'V.value_ordering':
			$order_by="ORDER BY `PA`.`sorting`";
			break;
		case 'value_name':
			$order_by="ORDER BY `value_name`";
			break;
		case 'PA.price':
			$order_by="ORDER BY `price`";
			break;
		case 'PA.ean':
			$order_by="ORDER BY `ean`";
			break;
		case 'PA.count':
			$order_by="ORDER BY `count`";
			break;
		case 'PA.product_attr_id':
			$order_by="ORDER BY PA.`product_attr_id`";
			break;
		default:
			$order_by="ORDER BY `PA`.`sorting`";
		}
		return $order_by;
	}
	
	public function orderAttributesIndependent(){
		$jshopConfig = JSFactory::getConfig();
		switch($jshopConfig->attribut_nodep_sorting_in_product){
		case 'V.value_ordering':
			$order_by="ORDER BY `sorting`";
			break;
		case 'value_name':
			$order_by="ORDER BY value_name";
			break;
		case 'addprice':
			$order_by="ORDER BY addprice";
			break;
		case 'PA.id':
			$order_by="ORDER BY sorting";
			break;
		default:
			$order_by="ORDER BY `sorting`";
		}
		return $order_by;
	}
	public function orderAttrDependent(){
		$jshopConfig = JSFactory::getConfig();
		switch($jshopConfig->attribut_dep_sorting_in_product){
		case 'V.value_ordering':
			$order_by=" A.`attr_ordering`";
			break;
		case 'value_name':
			$order_by=" name";
			break;
		case 'PA.product_attr_id':
			$order_by=" A.`attr_id`";
			break;
		default:
			$order_by=" A.`attr_ordering`";
		}
		return $order_by;
	}
	
	public function orderAttrIndependent(){
		$jshopConfig = JSFactory::getConfig();
		switch($jshopConfig->attribut_nodep_sorting_in_product){
		case 'V.value_ordering':
			$order_by=" A.`attr_ordering`";
			break;
		case 'value_name':
			$order_by=" name";
			break;
		/*case 'addprice':
			$order_by=" PA.`addprice`";
			break;*/
		case 'PA.id':
			$order_by=" A.attr_id";
			break;
		default:
			$order_by=" A.`attr_ordering`";
		}
		return $order_by;
	}
}