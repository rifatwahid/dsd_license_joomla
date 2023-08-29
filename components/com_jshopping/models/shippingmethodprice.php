<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelShippingMethodPrice extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_shipping_method_price';

    public function getByShippingPriceMethodsIds(array $methodsIds, array $columnsToGet = ['*'])
    {
        $result = '';

        if (!empty($columnsToGet) && !empty($methodsIds)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $stringOfMethodsIds = implode(', ', $methodsIds);
            $sqlQuery = "SELECT {$stringOfSearchColumns} FROM `#__jshopping_shipping_method_price` WHERE `sh_pr_method_id` IN ({$stringOfMethodsIds})";

            $db->setQuery($sqlQuery);
            $result = $db->loadObject();
        }
        
        return $result;
    }

    public function getPricesWeight($sh_pr_method_id, $id_country, &$cart)
    {
        $db = \JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();

        $query = "SELECT (sh_pr_weight.shipping_price + sh_pr_weight.shipping_package_price) AS shipping_price, sh_pr_weight.shipping_weight_from, sh_pr_weight.shipping_weight_to, sh_price.shipping_tax_id
                  FROM `" . static::TABLE_NAME . "` AS sh_price
                  INNER JOIN `#__jshopping_shipping_method_price_weight` AS sh_pr_weight ON sh_pr_weight.sh_pr_method_id = sh_price.sh_pr_method_id
                  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_countr ON sh_pr_weight.sh_pr_method_id = sh_pr_countr.sh_pr_method_id
                  WHERE sh_price.sh_pr_method_id = '{$db->escape($sh_pr_method_id)}'AND sh_pr_countr.country_id = '{$db->escape($id_country)}' 
                  ORDER BY sh_pr_weight.shipping_weight_from";
        $db->setQuery($query);
        $list = $db->loadObjectList() ?: [];

        if (!empty($list)) {
            foreach($list as $k => $v) {
                $list[$k]->shipping_price = $list[$k]->shipping_price * $jshopConfig->currency_value;            
                $list[$k]->shipping_price = getPriceCalcParamsTax($list[$k]->shipping_price, $list[$k]->shipping_tax_id, $cart->products);
            }
        }

        return $list; 
    }
	
	public function getByShippingId(int $shippingId, array $columnsToGet = ['*']): object
    {
        $result = '';

        if (!empty($columnsToGet)) {
            $stringOfSearchColumns = implode(', ', $columnsToGet);
            $sqlQuery = 'SELECT ' . $stringOfSearchColumns . ' FROM `' . self::TABLE_NAME . '` WHERE `shipping_id` = ' . $shippingId;

            $db = \JFactory::getDBO();
            $db->setQuery($sqlQuery);
            $result = $db->loadObject();
        }
        
        return $result;
    }

    public function getAdditionalDataByCountryId(int $countryId, array $columnsToGet = ['*'], string $queryAdditional = '', bool $getPublishedShippingMethod = true): array
    {
        $result = [];

        if (!empty($columnsToGet)) {
            $db = \JFactory::getDBO();
            $stringOfSearchColumns = implode(', ', $columnsToGet);

            $sqlQuery = "SELECT {$stringOfSearchColumns} FROM `" . self::TABLE_NAME . "` AS sh_method
                                INNER JOIN `#__jshopping_shipping_method_price` AS sh_pr_method ON sh_method.shipping_id = sh_pr_method.shipping_method_id
                                INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
                                INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
                                WHERE countries.country_id = '{$db->escape($countryId)}' AND sh_method.published = '" . (int)$getPublishedShippingMethod . "'";

            if (!empty($queryAdditional)) {
                $sqlQuery .= ' ' . $queryAdditional;
            }

            $db->setQuery($sqlQuery);
            return $db->loadObjectList();
        }

        return $result;
    }

    public function isCorectShippingMethodForPayment(int $paymentId, int $shipping_id): bool
    {
        $shipping_method = JTable::getInstance('shippingmethod', 'jshop');
        $shipping_method->load($shipping_id);
        
        if ($shipping_method->payments == '') {
            return true;
        }

        $shipping_payments = $shipping_method->getPayments();        
        if ($paymentId && !in_array($paymentId, $shipping_payments)) {
            return false;
        }
        
        return true;
    }

    public function getShippingForm(?string $alias)
    {
        $jshopConfig = JSFactory::getConfig();
        $script = str_replace(['.', '/'], '', $alias);
        $patch = "{$jshopConfig->path}shippingform/{$script}/{$script}.php";

        if (!empty($script) && file_exists($patch)) {
            include_once $patch;
            $data = new $script();
        } else {
            $data = null;
        }

        return $data;
    }

    public function renderShippingForm($shipping_id, $shippinginfo, $params): string
    {
        $shippingForm = $this->getShippingForm($shippinginfo->alias);

        if (!empty($shippingForm)) {
            ob_start();
            $shippingForm->showForm($shipping_id, $shippinginfo, $params);
            $html = ob_get_contents();
            ob_get_clean();
        }

        return $html ?: '';
    }

    public function getAll(int $publish = 1)
    {
        $lang = JSFactory::getLang();
        $where = ($publish == 1) ? ["WHERE published = '1'"] : [];
        $select = [
            '*',
            "`{$lang->get('name')}` as name",
            "`{$lang->get("description")}` as description"
        ];

        return $this->select($select, $where, 'ORDER BY ordering');
    }

     public function getAllShippingMethodsCountry($country_id, $payment_id, $publish = 1, $usergroup = '', $state = '')
    {
        $db = \JFactory::getDBO(); 
        $lang = JSFactory::getLang();
		$jshopConfig = JSFactory::getConfig();
        $query_where = ($publish) ? "AND sh_pr_method.published = '1'" : '';

		if ($payment_id && $jshopConfig->step_4_3 == 0) {
			$query_where .= " AND (sh_pr_method.payments='' OR FIND_IN_SET(" . $payment_id . ", sh_pr_method.payments) ) ";
        }

        if ((int)$state > 0) {
            $query = "SELECT `sh_pr_method_id` FROM `#__jshopping_shipping_method_price_states` WHERE `state_id`=".$state;
            $db->setQuery($query);
            $sh_method_list = $db->loadColumn();
        }elseif($state != ''){
            $query = "SELECT sh.`sh_pr_method_id` 
                FROM `#__jshopping_shipping_method_price_states` as sh 
                LEFT JOIN `#__jshopping_states` as st ON st.`state_id`=sh.`state_id` 
                WHERE st.`{$lang->get('name')}`=".$db->quote($state);
            $db->setQuery($query);
            $sh_method_list = $db->loadColumn();
        }

        $query = "SELECT *, sh_pr_method.`{$lang->get('name')}` as name, `{$lang->get('description')}` as description FROM `#__jshopping_shipping_method_price` AS sh_pr_method
                  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
                  INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
                  WHERE countries.country_id = '{$db->escape($country_id)}' {$query_where}
                  ORDER BY sh_pr_method.ordering";
		extract(js_add_trigger(get_defined_vars(), 'query'));
	
        $db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach($rows as $key => $value) {
			$rows[$key]->shipping_method_id = $rows[$key]->sh_pr_method_id;
			if (!in_array($usergroup,explode(',', $value->usergroup_id))) {
				unset($rows[$key]);
                continue;
            }

            $query = "SELECT `sh_pr_method_id` 
                FROM `#__jshopping_shipping_method_price_states`  
                WHERE `sh_pr_method_id`=".$db->quote($value->sh_pr_method_id);
            $db->setQuery($query);
            $list = $db->loadColumn();

            if(!empty($sh_method_list) && !empty($list)){
                if(!in_array($value->sh_pr_method_id ,$sh_method_list) ){
                    unset($rows[$key]);
                }
            }else{
                $query = "SELECT `sh_pr_method_id` 
                FROM `#__jshopping_shipping_method_price_states`  
                WHERE `sh_pr_method_id`=".$db->quote($value->sh_pr_method_id);
                $db->setQuery($query);
                $list = $db->loadColumn();
                if(!empty($list)) {
                    unset($rows[$key]);
                }
            }
		}
        return $rows;
    }

    public function getShippingPriceId($shipping_id, $country_id, $publish = 1)
    {
        $db = \JFactory::getDBO(); 
        $query_where = ($publish) ? ("AND sh_method.published = '1'") : '';
        $query = "SELECT sh_pr_method.sh_pr_method_id FROM `#__jshopping_shipping_method` AS sh_method
                  INNER JOIN `#__jshopping_shipping_method_price` AS sh_pr_method ON sh_method.shipping_id = sh_pr_method.shipping_method_id
                  INNER JOIN `#__jshopping_shipping_method_price_countries` AS sh_pr_method_country ON sh_pr_method_country.sh_pr_method_id = sh_pr_method.sh_pr_method_id
                  INNER JOIN `#__jshopping_countries` AS countries  ON sh_pr_method_country.country_id = countries.country_id
                  WHERE countries.country_id = '{$db->escape($country_id)}' and sh_method.shipping_id=" . intval($shipping_id) . "  {$query_where}";
        extract(js_add_trigger(get_defined_vars(), 'query'));
        $db->setQuery($query);

        return (int)$db->loadResult();
    }
}