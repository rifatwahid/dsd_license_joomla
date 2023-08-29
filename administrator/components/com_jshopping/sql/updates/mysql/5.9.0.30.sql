CREATE TABLE IF NOT EXISTS `#__jshopping_return_packages` (
  `id` SERIAL,
  `package_status` varchar(250) NOT NULL DEFAULT '',
  `products` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `package` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
