START TRANSACTION;
CREATE TABLE `#__jshopping_shipping_conditions` (
  `condition_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordering` int(11) NOT NULL,
  `rules` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `formula` text COLLATE utf8mb4_unicode_ci NOT NULL,  
  PRIMARY KEY (`condition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__jshopping_shipping_conditions_options` (
  `width_id` int(11) NOT NULL,
  `height_id` int(11) NOT NULL,
  `depth_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `#__jshopping_shipping_conditions_options` (`width_id`, `height_id`, `depth_id`) VALUES
(0, 0, 0);
	
COMMIT;

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_shipping_method_price_weight', 'condition_id', 'int(11) NOT NULL AFTER `sh_pr_method_id`');