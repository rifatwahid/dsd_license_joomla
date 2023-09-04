<?php
/**
* @version      5.5.19 06.03.2020
* @author       DURST-Software
* @package      smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

class JshoppingModelProduction_calendar extends JModelLegacy
{
	public function getParams()
	{
		$db = \JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->qn('#__jshopping_production_calendar'))
			->where($db->qn('id') .'='. 1);
		$db->setQuery($query);
		return $db->loadObject();
	}

    public function saveParams($params)
	{
		$db = \JFactory::getDBO();

		$data = new stdClass();
		$data->id = 1;

		if (!empty($params['working_days'])) {
			$data->working_days = $db->escape($params['working_days']);
		}

		if (!empty($params['extra_working_days'])) {
			$data->extra_working_days = $params['extra_working_days'];
		}

		if (!empty($params['extra_weekend_days'])) {
			$data->extra_weekend_days = $params['extra_weekend_days'];
		}

		if (isset($params['show_in_product'])) {
			$data->show_in_product = $params['show_in_product'];
		}

		if (isset($params['show_in_product_list'])) {
			$data->show_in_product_list = $params['show_in_product_list'];
		}
		
		if (isset($params['show_in_cart_checkout'])) {
			$data->show_in_cart_checkout = $params['show_in_cart_checkout'];
		}

		if (isset($params['production_time'])) {
			$data->production_time = $params['production_time'];
		}

        $db->updateObject('#__jshopping_production_calendar', $data, 'id');
        
	}

	public function savedays($days){
		$db = \JFactory::getDBO();
		$query = "update #__jshopping_production_calendar set working_days='[".$days."]'";
		$db->setQuery($query);
		$db->execute();
		
	}
	
	public function calculateDelivery($days)
	{
		$params = $this->getParams();
		$working_days = json_decode($params->working_days);
        $extra_working_days = json_decode($params->extra_working_days);
        $extra_weekend_days = json_decode($params->extra_weekend_days);

		$i = 1;
		while ($days) {
			$day = JFactory::getDate("now +$i day");
			$today = $day->format('Y-m-d');
			$week = ($day->dayofweek == 7) ? 0 : $day->dayofweek;

			$i++;

			if (in_array($today, $extra_working_days)) {
				$days--;
				continue;
			}

			if (in_array($today, $extra_weekend_days)) {
				continue;
			}

			if (in_array($week, $working_days)) {
				 $days--;continue;
			}
			
			
		}
		return $i - 1;
	}
}