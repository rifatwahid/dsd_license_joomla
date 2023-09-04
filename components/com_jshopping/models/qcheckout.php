<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelQCheckout extends jshopBase
{
    public function setSessionError(string $error): bool
    {
        $session = JFactory::getSession();
        $sessErrors = $session->get('qc_error');

        if (strpos($sessErrors, $error) === false) {
            if (!empty($sessErrors)) {
                $sessErrors .= '<br />';
            } else {
                $sessErrors = '';
            }
        
            $session->set('qc_error', $sessErrors . $error);
            return true;
        }

        return false;
    }

    public function parseParamsName(string $name)
    {
        $name = str_replace('params', '', $name);
        $fields = explode('[', $name);
        $fields[1] = str_replace(']', '', $fields[1]);
        $fields[2] = str_replace(']', '', $fields[2]);
        unset($fields[0]);
        
        return $fields;
    } 

    public function prepareViewProductShipping(&$view)
    {
        $modelOfProductsShipping = JSFactory::getModel('ProductsShipping');
        $cart = JModelLegacy::getInstance('cart', 'jshop');
		$cartName = (isset($view->cartName) && $view->cartName) ? $view->cartName : 'cart';
        $cart->load($cartName);


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
        $shippingsOfCartProducts = array_filter($view->shipping_methods, function ($item) use($idsOfShPrMethodsOfProducts, $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
            if((in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts) && !in_array($item->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts)) || (!in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts) && !in_array($item->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))){
                return true;
            }
        });

		if(empty($shippingsOfCartProducts)){
            $listOfProductShippings = $modelOfProductsShipping->getByProductsIds($productsIds, ['*'], true, true);
            $idsOfShPrMethodsOfProducts = getListSpecifiedAttrsFromArray($listOfProductShippings, 'sh_pr_method_id') ?: [];
            $idsOfShPrMethodsOfProducts = array_unique($idsOfShPrMethodsOfProducts);

            $_shippingsOfCartProducts = array_filter($view->shipping_methods, function ($item) use($idsOfShPrMethodsOfProducts, $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
                if((in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts)) || (!in_array($item->sh_pr_method_id, $idsOfShPrMethodsOfProducts) && !in_array($item->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))){
                    return true;
                }
            });

            $shippingsOfCartProducts[0] = $_shippingsOfCartProducts[0];
            $shippingsOfCartProducts[0]->shippings[] = $_shippingsOfCartProducts[0]->sh_pr_method_id;
            $shippingsOfCartProducts[0]->sh_pr_method_id = $_shippingsOfCartProducts[0]->sh_pr_method_id;
            $shippingsOfCartProducts[0]->shipping_method_id = $_shippingsOfCartProducts[0]->sh_pr_method_id;
            $shippingsOfCartProducts[0]->name = JText::_('COM_SMARTSHOP_COMPLEX_SHIPPING');
            $nonUniqueIdsOfShPrMethodsOfProducts = [];
            $nonUniqueIdsOfShPrMethodsOfProducts[] = 567895;
            foreach($_shippingsOfCartProducts as $key=>$val){
                if($key != 0){
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

        $view->shipping_methods = array_filter($shippingsOfCartProducts, function ($item) use($nonUniqueIdsOfShPrMethodsOfProducts, &$totalShippingCost,  $allIdsOfShPrMethodsOfProducts, $iIdsOfShPrMethodsNoProducts) {
            $totalShippingCost += $item->shipping_stand_price;
            if(in_array($item->sh_pr_method_id, $nonUniqueIdsOfShPrMethodsOfProducts) || (!in_array($item->sh_pr_method_id, $nonUniqueIdsOfShPrMethodsOfProducts) && !in_array($item->sh_pr_method_id, $iIdsOfShPrMethodsNoProducts))){
                return true;
            }
        });

        if (empty($view->shipping_methods) && !empty($shippingsOfCartProducts)) {

            $firstShipping = reset($shippingsOfCartProducts);

            $shippingMethodPrice = JTable::getInstance('shippingMethodPrice', 'jshop');
            $shippingMethodPrice->load($firstShipping->sh_pr_method_id);

            $firstShipping->shipping_stand_price = $shippingMethodPrice->shipping_stand_price = $totalShippingCost;
            $calculatedSum = $shippingMethodPrice->calculateSum($cart);
            $firstShipping->calculeprice = $shippingMethodPrice->calculeprice = $calculatedSum['shipping'];

            $view->shipping_methods[] = $firstShipping;
        }

       if (empty($view->active_shipping) || (!in_array($view->active_shipping, $nonUniqueIdsOfShPrMethodsOfProducts)  && in_array($view->active_shipping, $iIdsOfShPrMethodsNoProducts)) && !empty($view->shipping_methods)) {
            $view->active_shipping = reset($view->shipping_methods)->sh_pr_method_id;
        }
    }

    function preparedShippingFields($data){
        foreach($data as $k=>$val){
            if(isset($data['d_'.$k])){
                $data[$k] = $data['d_'.$k];
            }
        }
        $data['is_default'] = 0;

        return $data;
    }

}