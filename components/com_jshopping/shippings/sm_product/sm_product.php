<?php

class sm_product extends shippingextRoot
{

    public $version = 2;
    
    public function showShippingPriceForm($params, &$shipping_ext_row, &$template)
    {   
        include __DIR__ . '/shippingpriceform.php';
    }
    
    public function showConfigForm($config, &$shipping_ext, &$template)
    {
        $language = &JFactory::getLanguage();
        $language->load('addon_product_shipping' , JPATH_ROOT, $language->getTag(), true);   
        $shippings = JModelLegacy::getInstance('shippings', 'JshoppingModel');
        $list_shippings = $shippings->getAllShippings(0);        
        $first = [
            JHTML::_('select.option', '0', ' - - - ', 'shipping_id','name')
        ];     
        $select_priority_shipping = JHTML::_('select.genericlist', array_merge($first, $list_shippings), 'params[priority_shipping]', 'class="form-select"', 'shipping_id', 'name', $config['priority_shipping']);

        include __DIR__ . '/configform.php';
    }
	
    public function getPrices($cart, $params, $prices, &$shipping_ext_row, &$tableOfShippingMethodPrice, $shippingMethodId)
    {
		return $this->getPrice($cart, $params, $prices, $shipping_ext_row, $tableOfShippingMethodPrice, $shippingMethodId);
	}
	
    public function getPrice($cart, $params, $prices, &$shipping_ext_row, &$tableOfShippingMethodPrice, $shippingMethodId)
    {
		if (empty($cart->products)) {
			return [];
		}
    
        $productsIds = [];

        foreach($cart->products as $v) {
			if (!in_array($v['product_id'], $productsIds)) {
				$productsIds[] = $v['product_id'];

            }
        }        

        $modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
        $list = $modelOfProductsShipping->getByShippingAndProductsIds($shippingMethodId, $productsIds);

        $hprices = $pprices = [];
        foreach($list as $k => $v) {
            $hprices[] = $v->price;
            $pprices[] = $v->price_pack;
        }

        // adds standard prices for other products
        while(count($hprices) < count($productsIds)) {
            $hprices[] = -1;
            $pprices[] = -1;
        }
        
        //shipping
        foreach($hprices as $k => $v) {
            if ($v == -1) {
                $hprices[$k] = $prices['shipping'];
            }
        }

        if (!empty($hprices)) {
            $prices['shipping'] = max($hprices);

        }
        
        //packege
        foreach($pprices as $k => $v) {
            if ($v == -1) {
                $pprices[$k] = $prices['package'];
            }
        }

        if (!empty($pprices)) {
            $prices['package'] = max($pprices);

        }
        
        return $prices;
    }
}