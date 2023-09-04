START TRANSACTION;
ALTER TABLE `#__jshopping_config` CHANGE `displayprice` `displayprice_for_list_product` TINYINT(1) NOT NULL;
COMMIT;

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'displayprice_for_product', 'TINYINT(1) NOT NULL AFTER `displayprice_for_list_product`');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'show_plus_shipping_in_product_list', 'TINYINT(1) NOT NULL AFTER `show_plus_shipping_in_product`');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'show_base_price_for_product_list', 'TINYINT(1) NOT NULL AFTER `product_list_show_price_default`');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'show_product_manufacturer_in_cart', 'INT DEFAULT 0');