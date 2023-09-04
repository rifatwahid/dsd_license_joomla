CREATE TABLE  IF NOT EXISTS `#__jshopping_shipping_method_price_states` (
       `sh_method_state_id` int(11) NOT NULL AUTO_INCREMENT,
       `state_id` int(11) NOT NULL,
       `sh_pr_method_id` int(11) NOT NULL,
       PRIMARY KEY (`sh_method_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
