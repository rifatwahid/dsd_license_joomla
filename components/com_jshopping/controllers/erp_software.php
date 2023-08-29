<?php
defined('_JEXEC') or die;
class JshoppingControllerErp_software extends JshoppingControllerBase{

    public function __construct($config = array()){
        parent::__construct($config);
    }

    public function change_price(){
        $jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO();
        $jinput = JFactory::getApplication()->input->json->getRaw();
        $data = json_decode($jinput, true);
        $percent = (float)$data['pricePercent'];

        if($data['authentication'] && $percent && $data['authentication'] == $jshopConfig->securitykey){
            if($percent > 0) {
                $query = "UPDATE #__jshopping_products SET product_old_price=product_old_price+(product_old_price*$percent/100), product_buy_price=product_buy_price+(product_buy_price*$percent/100), product_price=product_price+(product_price*$percent/100), min_price=min_price+(min_price*$percent/100) ,
                    pattern_price=pattern_price+(pattern_price*$percent/100),product_linear_price=product_linear_price+(product_linear_price*$percent/100),product_mindestpreis=product_mindestpreis+(product_mindestpreis*$percent/100),price_without_pattern=price_without_pattern+(price_without_pattern*$percent/100),
                    complex_single_price=complex_single_price+(complex_single_price*$percent/100),one_time_cost=one_time_cost+(one_time_cost*$percent/100),preview_total_price=preview_total_price+(preview_total_price*$percent/100) WHERE 1";
                $db->setQuery($query);
                $db->execute();

                $query = "UPDATE #__jshopping_products_attr2 SET addprice=addprice+(addprice*$percent/100) WHERE 1";
                $db->setQuery($query);
                $db->execute();

                $query = "UPDATE #__jshopping_products_attr SET buy_price=buy_price+(buy_price*$percent/100),price=price+(price*$percent/100),old_price=old_price+(old_price*$percent/100) WHERE 1";
                $db->setQuery($query);
                $db->execute();

                $query = "UPDATE #__jshopping_products_prices_group SET price=price+(price*$percent/100),price_netto=price_netto+(price_netto*$percent/100),old_price=old_price+(old_price*$percent/100),total_calculated_price_without_tax=total_calculated_price_without_tax+(total_calculated_price_without_tax*$percent/100) WHERE 1";
                $db->setQuery($query);
                $db->execute();

                $query = "UPDATE #__jshopping_products_prices SET price=price+(price*$percent/100) WHERE 1";
                $db->setQuery($query);
                $db->execute();

                print_r('Prices update');die;
            }else{
                print_r('The percentage value should be positive');die;
            }
        }else{
           print_r('Authentication key is false');die;
        }

    }
}