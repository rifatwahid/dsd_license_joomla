<?php

class JshoppingModelTaxesFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_taxes';

    public function getAll()
    {
        return $this->select(['*']);
    }
	
	public function checkAddTax(&$addTax){
		$userShop = JSFactory::getUserShop();
		JSFactory::getModel('UsersFront')->checkClientType($userShop);
		$client_type=$userShop->client_type ?? 0;
		$jshopConfig = JSFactory::getConfig();
		if ($client_type == 2) {
			if ($jshopConfig->display_price_front_current == 1) {
				$addTax=0;
			}
		}
	}
	
	public function getAddTax($addTax=1){
		$userShop = JSFactory::getUserShop();
		JSFactory::getModel('UsersFront')->checkClientType($userShop);
		$client_type=$userShop->client_type ?? 0;		
		$jshopConfig = JSFactory::getConfig();		
		if ($client_type == 2) {
			if (isset($jshopConfig->display_price_firma) && $jshopConfig->display_price_firma == 1) {
				$addTax=0;
			}
		}
		return $addTax;
	}	
}
