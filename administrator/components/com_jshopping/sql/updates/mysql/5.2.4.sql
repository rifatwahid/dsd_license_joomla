START TRANSACTION;
CREATE TABLE IF NOT EXISTS `#__jshopping_categories_shipping`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `sh_pr_method_id` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `price` decimal(12, 2) NOT NULL,
  `price_pack` decimal(12, 2) NOT NULL,
  PRIMARY KEY (`id`)
);
INSERT INTO `#__jshopping_shipping_ext_calc` (`name`, `alias`, `description`, `published`) VALUES ('Product', 'sm_product', 'Product', 1);
COMMIT;

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_products', 'use_product_shipping', 'TINYINT(1) NOT NULL DEFAULT 1');