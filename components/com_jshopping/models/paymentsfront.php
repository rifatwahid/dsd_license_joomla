<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelPaymentsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_payment_method';

    public function getPreparedPayments(object $adv_user, jshopConfig $jshopConfig, jshopCart &$cart): array
    {
        $paymentmethod = JTable::getInstance('paymentmethod', 'jshop');
        $shippingId = (int)$cart->getShippingId() ?? 0;
        $allPaymentsMethods = $paymentmethod->getAllPaymentMethods(1, $shippingId, $adv_user->usergroup_id);
        $preparedPayments = [];
        $activePaymentId = $this->getActivePaymentId($allPaymentsMethods, $cart, $adv_user);

        foreach($allPaymentsMethods as $loopPaymentMethod) {
            $preparedPayment = new stdClass();
            $paymentmethod->load($loopPaymentMethod->payment_id); 
            $scriptname = $loopPaymentMethod->scriptname ?: $loopPaymentMethod->payment_class;  

            $paymentsysdata = $paymentmethod->getPaymentSystemData($scriptname);
            $preparedPayment->existentcheckform = 0;
            if ($paymentsysdata->paymentSystem) {
                $preparedPayment->existentcheckform = 1;
                $preparedPayment->payment_system = $paymentsysdata->paymentSystem;
            }

            $preparedPayment->form = '';
            $preparedPayment->name = $loopPaymentMethod->name;
            $preparedPayment->payment_id = $loopPaymentMethod->payment_id;
            $preparedPayment->payment_class = $loopPaymentMethod->payment_class;
            $preparedPayment->scriptname = $loopPaymentMethod->scriptname;
            $preparedPayment->payment_description = $loopPaymentMethod->description;
            $preparedPayment->price_type = $loopPaymentMethod->price_type;
            $preparedPayment->image = $loopPaymentMethod->image;
            $preparedPayment->price_add_text = '';
            $preparedPayment->calculeprice = $loopPaymentMethod->price;

            if ($loopPaymentMethod->price_type != 2) {
                $preparedPayment->calculeprice = getPriceCalcParamsTax($loopPaymentMethod->price * $jshopConfig->currency_value, $loopPaymentMethod->tax_id, $cart->products);
            }

            if ($preparedPayment->calculeprice != 0) {
                $symbol = ($preparedPayment->calculeprice > 0) ? '+': '';
                $preparedPayment->price_add_text = ($loopPaymentMethod->price_type == 2) ? $symbol . $preparedPayment->calculeprice . '%' : $symbol . formatprice($preparedPayment->calculeprice);
            }

            $params = [];
            if ($activePaymentId == $loopPaymentMethod->payment_id) {
                $params = $cart->getPaymentParams() ?: [];
                $params['activePaymentId'] = $activePaymentId;
            }

            $parseString = new parseString($loopPaymentMethod->payment_params);
            $pmconfig = $parseString->parseStringToParams();

            if ($preparedPayment->existentcheckform && $activePaymentId == $loopPaymentMethod->payment_id) {
                $preparedPayment->form = $paymentmethod->loadPaymentForm($preparedPayment->payment_system, $params, $pmconfig);
            }

            $preparedPayments[] = $preparedPayment;
        }

        return [
            'payments' => $preparedPayments, 
            'active_payment' => $activePaymentId
        ];
    }

    public function getActivePaymentId($allPaymentsMethods, jshopCart $cart, object $shopUser)
    {
        $activePaymentId = intval($cart->getPaymentId());
        
        if (empty($activePaymentId) && !empty($allPaymentsMethods)) {
            $listOfPaymentIds = getListSpecifiedAttrsFromArray($allPaymentsMethods, 'payment_id');

            if (in_array($shopUser->payment_id, $listOfPaymentIds)) {
                $activePaymentId = $shopUser->payment_id;
            } else {
                $activePaymentId = reset($allPaymentsMethods)->payment_id;
            }
        }

        return $activePaymentId;
    }

    public function getPaymentClassForPaymentsArray(array $payments, int $payment_id): string
    {
        $paymentClassName = '';

        foreach ($payments as $payment) {
            if ($payment->payment_id == $payment_id) {
                $paymentClassName = $payment->payment_class ?: '';
                break;
            }
        }

        return $paymentClassName;
    }

    public function getIdByPaymentClass(string $paymentClass)
    {
        $result = null;

        if (!empty($paymentClass)) {
            $db = \JFactory::getDBO();
            $query = "SELECT `payment_id` FROM `" . static::TABLE_NAME . "` WHERE payment_class = '" . $db->escape($paymentClass) . "'";
            extract(js_add_trigger(get_defined_vars(), 'query'));
            $db->setQuery($query);
            $result = $db->loadResult();
        }

        return $result;
    }

    public function renderPaymentForm($payment_system, $params, $pmconfig)
    {
        ob_start();
        $payment_system->showPaymentForm($params, $pmconfig);
        $html = ob_get_contents();
        ob_get_clean();

        return $html;
    }

    public function getAllMethods(int $publish = 1, int $shipping_id = 0, $usergroup = '')
    {
        $db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();
        $lang = JSFactory::getLang();
        $query_where = $publish ? "WHERE payment_publish = '1'" : '';		
        $orderBy = 'ORDER BY payment_ordering';
        $select = "SELECT payment_id, `".$lang->get("name")."` as name, `".$lang->get("description")."` as description , usergroup_id,payment_code, payment_class, scriptname, payment_publish, payment_ordering, payment_params, payment_type, price, price_type, tax_id, image FROM `#__jshopping_payment_method`";
        $query = "$select $query_where $orderBy";
        extract(js_add_trigger(get_defined_vars(), 'query'));
        $db->setQuery($query);
        $rows = $db->loadObjectList();		

		foreach($rows as $key => $value) {
			if (!in_array($usergroup,explode(',', $value->usergroup_id))) {
				unset($rows[$key]);
			}
        }
        
        if ($shipping_id && $jshopConfig->step_4_3) {
            $sh = JSFactory::getTable('shippingMethod', 'jshop');            
            $sh->load($shipping_id);
            $payments = $sh->getPayments();

            if (!empty($payments)) {
                foreach($rows as $k => $v) {
                    if (!in_array($v->payment_id, $payments)) unset($rows[$k]);
                }
                $rows = array_values($rows);
            }
        }

        return $rows;
    }

    public function getParamsByPaymentClass(string $classname) 
    {
        $db = \JFactory::getDBO(); 
        $query = "SELECT payment_params FROM `#__jshopping_payment_method` WHERE payment_class = '" . $db->escape($classname) . "'";
        extract(js_add_trigger(get_defined_vars(), 'query'));
        $db->setQuery($query);
        $params_str = $db->loadResult();
        $parseString = new parseString($params_str);
        $params = $parseString->parseStringToParams();

        return $params;
    }
}