<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelShippingMethodPriceWeightFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_shipping_method_price_weight';

    public function getPricesByShippingPriceMethodId(int $shippingPriceMethodId, string $orderDir = 'asc')
    {
		$db = \JFactory::getDBO();
		
		$query = "SELECT sh_pr.*, c.`formula`, c.`rule_apply` FROM `#__jshopping_shipping_method_price_weight` as sh_pr
			LEFT JOIN `#__jshopping_shipping_conditions` as c ON sh_pr.`condition_id`=c.`condition_id`
			WHERE sh_pr.`sh_pr_method_id`=".$shippingPriceMethodId." ORDER BY  c.`ordering`, sh_pr.`sh_pr_weight_id`";
		$db->setQuery($query);
		return $db->loadObjectList();
    }
}
