START TRANSACTION;

CREATE TABLE `#__jshopping_production_calendar` (
  `id` int(11) NOT NULL,
  `working_days` text,
  `extra_working_days` text,
  `extra_weekend_days` text,
  `show_in_product` tinyint(1) NOT NULL DEFAULT '0',
  `show_in_product_list` tinyint(1) NOT NULL DEFAULT '0',
  `show_in_cart_checkout` tinyint(1) NOT NULL DEFAULT '0',
  `production_time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_production_calendar` (`id`, `working_days`, `extra_working_days`, `extra_weekend_days`, `show_in_product`, `show_in_product_list`, `show_in_cart_checkout`, `production_time`) VALUES
(1, NULL, '[]', '[]', 0, 0, 0, 0);

ALTER TABLE `#__jshopping_production_calendar` ADD PRIMARY KEY (`id`);

COMMIT;

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_products', 'production_time', 'SMALLINT NOT NULL');