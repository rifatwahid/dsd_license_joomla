CREATE TABLE IF NOT EXISTS `#__jshopping_return_packages_products` (
  `id` SERIAL,
  `package_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `return_status_id` int(11) NOT NULL,
  `customer_comment` text NOT NULL,
  `admin_notice` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'order_status_for_return', 'VARCHAR(100) NOT NULL');
