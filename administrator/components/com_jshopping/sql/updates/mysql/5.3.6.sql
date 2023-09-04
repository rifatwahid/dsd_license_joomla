START TRANSACTION;
CREATE TABLE IF NOT EXISTS `#__jshopping_products_prices_group`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_netto` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pgid` (`product_id` , `group_id`)
);
COMMIT;