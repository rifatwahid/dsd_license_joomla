<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_ROOT . '/administrator/components/com_jshopping/models/conditionsadmin.php';
require_once JPATH_ROOT . '/administrator/components/com_jshopping/models/production_calendar.php';

class JshoppingModelShippingsFront extends jshopBase
{
    public function getPreparedShippings(object $adv_user, int $id_country, jshopConfig $jshopConfig, jshopCart &$cart)
    {
        $session = JFactory::getSession();
		
        $shippingmethod = JTable::getInstance('shippingMethod', 'jshop');
        $shippingmethodprice = JTable::getInstance('shippingMethodPrice', 'jshop');
        $modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
            
        if ($jshopConfig->show_delivery_time_checkout) {
            $deliverytimes = JSFactory::getAllDeliveryTime();
        }
        
        if ($jshopConfig->show_delivery_date) {
            $deliverytimedays = JSFactory::getAllDeliveryTimeDays();
        }
        $idOfActiveShipping = $cart->getShippingPrId();
        $shippings = $shippingmethodprice->getAllShippingMethodsCountry($id_country, $cart->getPaymentId(), 1, $adv_user->usergroup_id, $adv_user->state) ?: [];

        foreach ($cart->products as &$productCart) {
            if (!empty($productCart['is_use_additional_shippings']) && !empty($productCart['prod_id_of_additional_val']) && $modelOfProductsShipping->isAtLeastOneEnabledForProduct($productCart['prod_id_of_additional_val'])) {
                $productCart['product_id'] = $productCart['prod_id_of_additional_val'];
            }
        }

		$productDeliveryDay = getMaxDayDeliveryOfProducts($cart->products);
        $productionCalendar = JSFactory::getModel('production_calendar');
        $listOfWorkingDays = json_decode($productionCalendar->getParams()->working_days);

        $maxProductionTime = JSFactory::getService('ProductionCalendar')->getMaxProductionTime($cart->products);
		$isActiveShippingIdExists = false;
        foreach($shippings as $key => $value) {		
            $shippingmethodprice->load($value->sh_pr_method_id);	

            $prices = $shippingmethodprice->calculateSum($cart);
			$_shipping_prices['shipping_price_' . $value->sh_pr_method_id] = $prices;
	
			if($prices['shipping'] <= 0 && $value->shipping_type == 2){
				unset($shippings[$key]);
				continue;
			}
            $shippings[$key]->shipping = $prices['shipping'];
            $shippings[$key]->calculeprice = $prices['shipping'] + $prices['package'];
            $shippings[$key]->package = $prices['package'];
            $shippings[$key]->delivery = ($jshopConfig->show_delivery_time_checkout) ? $deliverytimes[$value->delivery_times_id] : '';
            $shippings[$key]->delivery_date_f = '';

            if ($jshopConfig->show_delivery_date) {

                $day = $deliverytimedays[$value->delivery_times_id];
                $deliveryDay = $day + $productDeliveryDay;
			    if (!empty($listOfWorkingDays)) {
                    $deliveryDay = $productionCalendar->calculateDelivery($deliveryDay + $maxProductionTime);
                } 
				$shippings[$key]->delivery_date = getCalculateDeliveryDay($deliveryDay);
                $shippings[$key]->delivery_date_f = formatdate($shippings[$key]->delivery_date);
             
            }

            $params = ($value->sh_pr_method_id == $idOfActiveShipping) ? $cart->getShippingParams() : [];   
			$shippings[$key]->form = $shippingmethod->loadShippingForm($value->sh_pr_method_id , $value, $params);
			
			if ($value->sh_pr_method_id == $idOfActiveShipping) {
				$isActiveShippingIdExists = true;
			}
        }  
		$session->set('all_shipping_prices', $_shipping_prices);        

        $result = [
        	'shippings' => $shippings
		];
		
		$result['active_shipping'] = reset($shippings)->sh_pr_method_id;
		if (!empty($idOfActiveShipping) && $isActiveShippingIdExists) {
			$result['active_shipping'] = $idOfActiveShipping;
		}
		$productsIds = array_reduce($cart->products, function($carry, $productCart) use ($modelOfProductsShipping) {
            $isAdditionalShipping = (!empty($productCart['is_use_additional_shippings']) && !empty($productCart['prod_id_of_additional_val']) && $modelOfProductsShipping->isAtLeastOneEnabledForProduct($productCart['prod_id_of_additional_val']));
            $carry[] = ($isAdditionalShipping) ? $productCart['prod_id_of_additional_val'] : $productCart['product_id'];

            return $carry;
        });
        $listOfProductShippings = $modelOfProductsShipping->getByProductsIds($productsIds, ['*'], true);
        $allListOfProductShippings = $modelOfProductsShipping->getByProductsIds($productsIds, ['*'], false);
        $listOfProductNoShippings = $modelOfProductsShipping->getByProductsIdsNoInclude($productsIds, ['*']);

        $idsOfShPrMethodsOfProducts = getListSpecifiedAttrsFromArray($listOfProductShippings, 'sh_pr_method_id') ?: [];
        $allIdsOfShPrMethodsOfProducts = getListSpecifiedAttrsFromArray($allListOfProductShippings, 'sh_pr_method_id') ?: [];
        $iIdsOfShPrMethodsNoProducts = getListSpecifiedAttrsFromArray($listOfProductNoShippings, 'sh_pr_method_id') ?: [];

        $nonUniqueIdsOfShPrMethodsOfProducts = (count($cart->products) >= 2 && $idsOfShPrMethodsOfProducts != array_unique($idsOfShPrMethodsOfProducts)) ? array_diff_assoc($idsOfShPrMethodsOfProducts, array_unique($idsOfShPrMethodsOfProducts)) : $idsOfShPrMethodsOfProducts;

		$result['shippings'] = $this->sortShippingMethodsByProducts($result['shippings'], $cart, $productsIds);
		
		$result['shippings'] = $this->getAllShippingMethodsConditions($result['shippings'], $cart);
		// Get only shippings of cart`s products.
        $shippingsOfCartProducts = array_filter($result['shippings'], function ($item) use($idsOfShPrMethodsOfProducts, $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
            if((in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts) && !in_array($item->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))){
                return true;
            }
        });
		if(empty($shippingsOfCartProducts)){
            $listOfProductShippings = $modelOfProductsShipping->getByProductsIds($productsIds, ['*'], true, true);
            $idsOfShPrMethodsOfProducts = getListSpecifiedAttrsFromArray($listOfProductShippings, 'sh_pr_method_id') ?: [];
            $idsOfShPrMethodsOfProducts = array_unique($idsOfShPrMethodsOfProducts);

            $_shippingsOfCartProducts = array_filter($result['shippings'], function ($item) use($idsOfShPrMethodsOfProducts, $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
                if((in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts)) || (!in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts) && !in_array($item->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))){
                    return true;
                }
            });

		//print_r($listOfProductShippings );die;
			if(empty($_shippingsOfCartProducts)) return false;
			$_shCartPrs = reset($_shippingsOfCartProducts);
			$_shCartPrsKey = array_key_first($_shippingsOfCartProducts);
			//if(empty($_shippingsOfCartProducts)) return false;
            $shippingsOfCartProducts[0] = $_shCartPrs;
            $shippingsOfCartProducts[0]->shippings[] = $_shCartPrs->sh_pr_method_id;
            $shippingsOfCartProducts[0]->sh_pr_method_id = $_shCartPrs->sh_pr_method_id;
            $shippingsOfCartProducts[0]->shipping_method_id = $_shCartPrs->sh_pr_method_id;
            $shippingsOfCartProducts[0]->name = JText::_('COM_SMARTSHOP_COMPLEX_SHIPPING');
            $nonUniqueIdsOfShPrMethodsOfProducts = [];
            $nonUniqueIdsOfShPrMethodsOfProducts[] = 567895;
			foreach($_shippingsOfCartProducts as $key=>$val){
                if($key != $_shCartPrsKey){
                    $shippingsOfCartProducts[0]->shipping_stand_price += $_shippingsOfCartProducts[$key]->shipping_stand_price;
                    $shippingsOfCartProducts[0]->calculeprice += $_shippingsOfCartProducts[$key]->calculeprice;
                    $shippingsOfCartProducts[0]->package_stand_price += $_shippingsOfCartProducts[$key]->package_stand_price;
                    $shippingsOfCartProducts[0]->shippings[] = $_shippingsOfCartProducts[$key]->sh_pr_method_id;

                    $shippingsOfCartProducts[0]->sh_pr_method_id .= '_'.$_shippingsOfCartProducts[$key]->sh_pr_method_id;
                    $shippingsOfCartProducts[0]->shipping_method_id .= '_'.$_shippingsOfCartProducts[$key]->sh_pr_method_id;
                }
            }

        }
            
        $totalShippingCost = 0;
        $result['shippings'] = array_filter($shippingsOfCartProducts, function ($item) use($nonUniqueIdsOfShPrMethodsOfProducts, &$totalShippingCost,  $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
            $totalShippingCost += $item->shipping_stand_price;
			
            if(in_array($item->sh_pr_method_id, $nonUniqueIdsOfShPrMethodsOfProducts)){ 
                return true;
            }
        });
		
        if (empty($result['shippings']) && !empty($shippingsOfCartProducts)) {

            $firstShipping = reset($shippingsOfCartProducts);

            $shippingMethodPrice = JTable::getInstance('shippingMethodPrice', 'jshop');
            $shippingMethodPrice->load($firstShipping->sh_pr_method_id);
            $firstShipping->shipping_stand_price = $shippingMethodPrice->shipping_stand_price = $totalShippingCost;
			
            $calculatedSum = $shippingMethodPrice->calculateSum($cart);
			if(count(explode('_', $firstShipping->sh_pr_method_id)) <= 1 || !$firstShipping->calculeprice){				
				$firstShipping->calculeprice = $shippingMethodPrice->calculeprice = $calculatedSum['shipping'];
			}

            $result['shippings'][] = $firstShipping;
        }
        if (empty($result['active_shipping']) || (!in_array($result['active_shipping'], $nonUniqueIdsOfShPrMethodsOfProducts)) && !empty($result['shippings'])){
            $result['active_shipping'] = reset($result['shippings'])->sh_pr_method_id;
        }

		return $result;
    }
	
	 public function getPreparedShippingsCart(object $adv_user, int $id_country, jshopConfig $jshopConfig, jshopCart &$cart)
    {
        $shippingmethod = JTable::getInstance('shippingMethod', 'jshop');
        $shippingmethodprice = JTable::getInstance('shippingMethodPrice', 'jshop');
        $modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
            
        if ($jshopConfig->show_delivery_time_checkout) {
            $deliverytimes = JSFactory::getAllDeliveryTime();
        }
        
        if ($jshopConfig->show_delivery_date) {
            $deliverytimedays = JSFactory::getAllDeliveryTimeDays();
        }
        $idOfActiveShipping = intval($cart->getShippingPrId());
        $shippings = $shippingmethodprice->getAllShippingMethodsCountry($id_country, $cart->getPaymentId(), 1, $adv_user->usergroup_id, $adv_user->state) ?: [];

        foreach ($cart->products as &$productCart) {
            if (!empty($productCart['is_use_additional_shippings']) && !empty($productCart['prod_id_of_additional_val']) && $modelOfProductsShipping->isAtLeastOneEnabledForProduct($productCart['prod_id_of_additional_val'])) {
                $productCart['product_id'] = $productCart['prod_id_of_additional_val'];
            }
        }

		
        $productDeliveryDay = getMaxDayDeliveryOfProducts($cart->products);
        $productionCalendar = JSFactory::getModel('production_calendar');
        $listOfWorkingDays = json_decode($productionCalendar->getParams()->working_days);

        $maxProductionTime = JSFactory::getService('ProductionCalendar')->getMaxProductionTime($cart->products);
		$isActiveShippingIdExists = false;
        foreach($shippings as $key => $value) {
            $shippingmethodprice->load($value->sh_pr_method_id);
            $prices = $shippingmethodprice->calculateSum($cart);
            $shippings[$key]->calculeprice = $prices['shipping'] + $prices['package'];
            $shippings[$key]->delivery = ($jshopConfig->show_delivery_time_checkout) ? $deliverytimes[$value->delivery_times_id] : '';
            $shippings[$key]->delivery_date_f = '';

            $params = ($value->sh_pr_method_id == $idOfActiveShipping) ? $cart->getShippingParams() : [];   
			//$shippings[$key]->form = $shippingmethod->loadShippingForm($value->shipping_id, $value, $params);
			
			if ($value->sh_pr_method_id == $idOfActiveShipping) {
				$isActiveShippingIdExists = true;
			}
        }        

        $result = [
        	'shippings' => $shippings
		];
		$result['active_shipping'] = reset($shippings)->sh_pr_method_id;
		/*if (!empty($idOfActiveShipping) && $isActiveShippingIdExists) {
			$result['active_shipping'] = $idOfActiveShipping;
		}
		*/
				
		$productsIds = array_reduce($cart->products, function($carry, $productCart) use ($modelOfProductsShipping) {
            $isAdditionalShipping = (!empty($productCart['is_use_additional_shippings']) && !empty($productCart['prod_id_of_additional_val']) && $modelOfProductsShipping->isAtLeastOneEnabledForProduct($productCart['prod_id_of_additional_val']));
            $carry[] = ($isAdditionalShipping) ? $productCart['prod_id_of_additional_val'] : $productCart['product_id'];

            return $carry;
        });
		 $listOfProductShippings = $modelOfProductsShipping->getByProductsIds($productsIds, ['*'], true);        
        $allListOfProductShippings = $modelOfProductsShipping->getByProductsIds($productsIds, ['*'], false);
        $listOfProductNoShippings = $modelOfProductsShipping->getByProductsIdsNoInclude($productsIds, ['*']);

        $idsOfShPrMethodsOfProducts = getListSpecifiedAttrsFromArray($listOfProductShippings, 'sh_pr_method_id') ?: [];
        $allIdsOfShPrMethodsOfProducts = getListSpecifiedAttrsFromArray($allListOfProductShippings, 'sh_pr_method_id') ?: [];
        $iIdsOfShPrMethodsNoProducts = getListSpecifiedAttrsFromArray($listOfProductNoShippings, 'sh_pr_method_id') ?: [];

        $nonUniqueIdsOfShPrMethodsOfProducts = (count($cart->products) >= 2 && $idsOfShPrMethodsOfProducts != array_unique($idsOfShPrMethodsOfProducts)) ? array_diff_assoc($idsOfShPrMethodsOfProducts, array_unique($idsOfShPrMethodsOfProducts)) : $idsOfShPrMethodsOfProducts;

        // Get only shippings of cart`s products.
        $result['shippings'] = $this->sortShippingMethodsByProducts($result['shippings'], $cart, $productsIds);
		$result['shippings'] = $this->getAllShippingMethodsConditions($result['shippings'], $cart);
        $shippingsOfCartProducts = array_filter($result['shippings'], function ($item) use($idsOfShPrMethodsOfProducts, $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
            if((in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts) && !in_array($item->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))){
                return true;
            }
        });
		
		if(empty($shippingsOfCartProducts)){
            $listOfProductShippings = $modelOfProductsShipping->getByProductsIds($productsIds, ['*'], true, true);
            $idsOfShPrMethodsOfProducts = getListSpecifiedAttrsFromArray($listOfProductShippings, 'sh_pr_method_id') ?: [];
            $idsOfShPrMethodsOfProducts = array_unique($idsOfShPrMethodsOfProducts);

            $_shippingsOfCartProducts = array_filter($result['shippings'], function ($item) use($idsOfShPrMethodsOfProducts, $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
                if((in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts))){
                    return true;
                }
            });


			if(empty($_shippingsOfCartProducts)) return false;
            $_shCartPrs = reset($_shippingsOfCartProducts);
			$_shCartPrsKey = array_key_first($_shippingsOfCartProducts);
			$shippingsOfCartProducts[0] = $_shCartPrs;
            $shippingsOfCartProducts[0]->shippings[] = $_shCartPrs->sh_pr_method_id;
            $shippingsOfCartProducts[0]->sh_pr_method_id = $_shCartPrs->sh_pr_method_id;
            $shippingsOfCartProducts[0]->shipping_method_id = $_shCartPrs->sh_pr_method_id;
            $shippingsOfCartProducts[0]->name = JText::_('COM_SMARTSHOP_COMPLEX_SHIPPING');
            $nonUniqueIdsOfShPrMethodsOfProducts = [];
            $nonUniqueIdsOfShPrMethodsOfProducts[] = 567895;
            foreach($_shippingsOfCartProducts as $key=>$val){
                if($key != $_shCartPrsKey){
                    $shippingsOfCartProducts[0]->shipping_stand_price += $_shippingsOfCartProducts[$key]->shipping_stand_price;
                    $shippingsOfCartProducts[0]->calculeprice += $_shippingsOfCartProducts[$key]->calculeprice;
                    $shippingsOfCartProducts[0]->package_stand_price += $_shippingsOfCartProducts[$key]->package_stand_price;
                    $shippingsOfCartProducts[0]->shippings[] = $_shippingsOfCartProducts[$key]->sh_pr_method_id;

                    $shippingsOfCartProducts[0]->sh_pr_method_id .= '_'.$_shippingsOfCartProducts[$key]->sh_pr_method_id;
                    $shippingsOfCartProducts[0]->shipping_method_id .= '_'.$_shippingsOfCartProducts[$key]->sh_pr_method_id;
                }
            }
			$result['active_shipping'] = $shippingsOfCartProducts[0]->sh_pr_method_id;

        }
		
        $totalShippingCost = 0;

        $result['shippings'] = array_filter($shippingsOfCartProducts, function ($item) use($nonUniqueIdsOfShPrMethodsOfProducts, &$totalShippingCost,  $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
            $totalShippingCost += $item->shipping_stand_price;
            if(in_array($item->sh_pr_method_id, $nonUniqueIdsOfShPrMethodsOfProducts)){
                return true;
            }
        });

        if (empty($result['shippings']) && !empty($shippingsOfCartProducts)) {

            $firstShipping = reset($shippingsOfCartProducts);

            $shippingMethodPrice = JTable::getInstance('shippingMethodPrice', 'jshop');
            $shippingMethodPrice->load($firstShipping->sh_pr_method_id);

            $firstShipping->shipping_stand_price = $shippingMethodPrice->shipping_stand_price = $totalShippingCost;
            $calculatedSum = $shippingMethodPrice->calculateSum($cart);
            if(count(explode('_', $firstShipping->sh_pr_method_id)) <= 1 || !$firstShipping->calculeprice){				
				$firstShipping->calculeprice = $shippingMethodPrice->calculeprice = $calculatedSum['shipping'];
			}
			
			$result['shippings'][] = $firstShipping;
        }
       if (empty($result['active_shipping']) || (!in_array($result['active_shipping'], $nonUniqueIdsOfShPrMethodsOfProducts)) && !empty($result['shippings'])){
            $result['active_shipping'] = reset($result['shippings'])->sh_pr_method_id;
        }
		
		return $result;
    }
	
	public function sortShippingMethodsByProducts($shippings, $cart, $productsIds){
		
		$modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
				
		$productsByShipping = [];
		if(!empty($shippings)){
			foreach($shippings as $key=>$shipping){
				$shipping_method_price = JSFactory::getTable('ShippingMethodPrice');	
				$shipping_method_price->load($shipping->sh_pr_method_id);
				
				$shippings[$key]->shipping_products = $modelOfProductsShipping->getProductsByShippingId($shipping->sh_pr_method_id, $productsIds);
				
				if(empty($shippings[$key]->shipping_products )){
					unset($shippings[$key]);
					continue;
				}
				
				$productsByShipping[$key] = count($shippings[$key]->shipping_products);
							
			}
			
			arsort($productsByShipping);
			//print_r($productsByShipping);die;
			$_shippings = [];
			foreach($productsByShipping as $k => $val){
				$_shippings[] = $shippings[$k];
			}			
			
			return $_shippings;
		}
		return $shippings;
	}
	
	public function getAllShippingMethodsConditions($shippings, $cart){
		
		$conditions = JSFactory::getModel('conditionsadmin');		
        $modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
		$options =  $conditions->getOptions();
		$width_id = 0;
		$height_id = 0;
		$depth_id = 0;
		
		if(!empty($options)){
			$width_id = $options->width_id;
			$height_id = $options->height_id;
			$depth_id = $options->depth_id;
		}
		$productsIds = array_reduce($cart->products, function($carry, $productCart) {
            $carry[] = $productCart['product_id'];
			return $carry;
        });
		
		$productsByShipping = [];
		if(!empty($shippings)){
			foreach($shippings as $key=>$shipping){
				$shipping_method_price = JSFactory::getTable('ShippingMethodPrice');	
				$shipping_method_price->load($shipping->sh_pr_method_id);
				
				$params = array($width_id, $height_id, $depth_id);
				
				//$productsByShipping = array_merge($productsByShipping, $shippings[$key]->shipping_products);
				
				$sides = $cart->getMinMedianMaxFreeattrValProducts($params);
				
				$weightSum = $cart->getWeightProducts();
				$weight = $weightSum;
				$width = $cart->getFreeattrValProducts($width_id, 1);
				$height = $cart->getFreeattrValProducts($height_id, 2);
				$depth = $cart->getSumValProducts($depth_id);
				$price = $cart->price_product;		
				$max_side = $sides['max'];
				$median_side = $sides['median'];
				$min_side = $sides['min'];	
				$max = $sides['max'];
				$median = $sides['median'];
				$min = $sides['min'];				
			
				$shippingPrices = $shipping_method_price->getPrices('desc');
				
				if(!count($shippingPrices)){
					$flag = 1;
					continue;
				}else{
					
					$flag = 0;
					foreach($shippingPrices as $shippingPrice) { 
						
						if($shippingPrice->formula){
							if($shippingPrice->rule_apply == 1){ 
								$sides = $cart->getMinMedianMaxFreeattrValProducts($params, $shippings[$key]->shipping_products);
				
								$weightSum = $cart->getWeightProducts($shippings[$key]->shipping_products);
								$weight = $weightSum;
								$width = $cart->getFreeattrValProducts($width_id, 1, $shippings[$key]->shipping_products);
								$height = $cart->getFreeattrValProducts($height_id, 2, $shippings[$key]->shipping_products);
								$depth = $cart->getSumValProducts($depth_id, $shippings[$key]->shipping_products);
								//change $shippings[$key]->shipping_products
								$price = $cart->getPriceProds($shippings[$key]->shipping_products); //price_product;
								$max_side = $sides['max'];
								$median_side = $sides['median'];
								$min_side = $sides['min'];	
								$max = $sides['max'];
								$median = $sides['median'];
								$min = $sides['min'];				
			
							}		
							
							eval('$formula = ' . $shippingPrice->formula . ';');
							if ($formula) {
								$flag = 1;
								continue;
							}
						}
					}
				}
				if($flag == 0){
					unset($shippings[$key]);
				}
			}
		}
		
		return $shippings;
	}
}