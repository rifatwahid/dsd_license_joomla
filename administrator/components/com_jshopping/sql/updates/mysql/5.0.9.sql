START TRANSACTION;
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'image_payments_width', "INT(4) NOT NULL DEFAULT '115'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'image_payments_height', "INT(4) NOT NULL DEFAULT '100'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'image_shippings_width', "INT(4) NOT NULL DEFAULT '115'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'image_shippings_height', "INT(4) NOT NULL DEFAULT '100'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_product_labels', 'image_de-DE', 'VARCHAR(255) NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_product_labels', 'image_en-GB', 'VARCHAR(255) NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_product_labels', 'image_fr-FR', 'VARCHAR(255) NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_product_labels', 'image_it-IT', 'VARCHAR(255) NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_product_labels', 'image_pl-PL', 'VARCHAR(255) NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_product_labels', 'image_nl-NL', 'VARCHAR(255) NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_product_labels', 'image_ru-RU', 'VARCHAR(255) NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_product_labels', 'image_fr-CA', 'VARCHAR(255) NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'offer_and_order_validity', 'INT');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'offer_and_order_invoice_data', 'TINYINT');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'offer_and_order_payment', 'INT DEFAULT 0');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'offer_and_order_shipping', 'INT DEFAULT 0');

UPDATE `#__jshopping_order_status` SET `name_en-GB`="Reversal" WHERE `status_id`=8 AND `name_de-DE`="Storno";
UPDATE `#__jshopping_unit` SET `name_en-GB`="Hours" WHERE `id`=5 AND `name_de-DE`="Stunden";

CREATE TABLE IF NOT EXISTS `#__jshopping_offer_and_order_item`(
  `order_item_id` SERIAL NOT NULL,
  `order_id` INT NOT NULL DEFAULT 0,
  `product_id` INT NOT NULL DEFAULT 0,
  `product_ean` varchar(50) NOT NULL DEFAULT "",
  `product_name` varchar(100) NOT NULL DEFAULT "",
  `product_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `product_item_price` decimal(12,2) NOT NULL,
  `product_tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `product_attributes` text NOT NULL,
  `product_freeattributes` text NOT NULL,
  `attributes` text NOT NULL,
  `freeattributes` text NOT NULL,
  `files` text NOT NULL,
  `weight` float(8,4) NOT NULL DEFAULT 0.0000,
  `thumb_image` varchar(255) NOT NULL DEFAULT "",
  `vendor_id` INT NOT NULL,
  `delivery_times_id` INT NOT NULL,
  `extra_fields` text NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `product_id_for_order` INT NOT NULL,
  PRIMARY KEY (`order_item_id`)
);

CREATE TABLE IF NOT EXISTS `#__jshopping_offer_and_order`(
  `order_id` SERIAL NOT NULL,
  `order_number` varchar(50) NOT NULL DEFAULT "0",
  `user_id` INT NOT NULL DEFAULT 0,
  `order_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_tax_ext` text NOT NULL,
  `order_shipping` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_payment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency_code` varchar(20) NOT NULL DEFAULT "",
  `currency_code_iso` varchar(3) NOT NULL DEFAULT "",
  `currency_exchange` decimal(14,6) NOT NULL DEFAULT 0.000000,
  `order_status` varchar(1) NOT NULL DEFAULT "",
  `order_created` tinyint(1) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_m_date` datetime DEFAULT NULL,
  `shipping_method_id` INT NOT NULL DEFAULT 0,
  `payment_method_id` INT NOT NULL DEFAULT 0,
  `payment_params` text NOT NULL,
  `payment_params_data` text NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT "",
  `order_add_info` text NOT NULL,
  `title` tinyint(1) NOT NULL DEFAULT 0,
  `f_name` varchar(255) NOT NULL DEFAULT "",
  `l_name` varchar(255) NOT NULL DEFAULT "",
  `firma_name` varchar(255) NOT NULL DEFAULT "",
  `client_type` tinyint(1) NOT NULL,
  `client_type_name` varchar(100) NOT NULL,
  `firma_code` varchar(100) NOT NULL,
  `tax_number` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT "",
  `street` varchar(100) NOT NULL DEFAULT "",
  `home` varchar(20) NOT NULL,
  `apartment` varchar(20) NOT NULL,
  `zip` varchar(20) NOT NULL DEFAULT "",
  `city` varchar(100) NOT NULL DEFAULT "",
  `state` varchar(100) NOT NULL DEFAULT "",
  `country` INT NOT NULL,
  `phone` varchar(20) NOT NULL DEFAULT "",
  `mobil_phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL DEFAULT "",
  `ext_field_1` varchar(255) NOT NULL,
  `ext_field_2` varchar(255) NOT NULL,
  `ext_field_3` varchar(255) NOT NULL,
  `d_title` tinyint(1) NOT NULL DEFAULT 0,
  `d_f_name` varchar(255) NOT NULL DEFAULT "",
  `d_l_name` varchar(255) NOT NULL DEFAULT "",
  `d_firma_name` varchar(255) NOT NULL DEFAULT "",
  `d_email` varchar(255) NOT NULL DEFAULT "",
  `d_street` varchar(100) NOT NULL DEFAULT "",
  `d_home` varchar(20) NOT NULL,
  `d_apartment` varchar(20) NOT NULL,
  `d_zip` varchar(20) NOT NULL DEFAULT "",
  `d_city` varchar(100) NOT NULL DEFAULT "",
  `d_state` varchar(100) NOT NULL DEFAULT "",
  `d_country` INT NOT NULL,
  `d_phone` varchar(30) NOT NULL DEFAULT "",
  `d_mobil_phone` varchar(20) NOT NULL,
  `d_fax` varchar(20) NOT NULL DEFAULT "",
  `d_ext_field_1` varchar(255) NOT NULL,
  `d_ext_field_2` varchar(255) NOT NULL,
  `d_ext_field_3` varchar(255) NOT NULL,
  `pdf_file` varchar(50) NOT NULL,
  `order_hash` varchar(32) NOT NULL DEFAULT "",
  `file_hash` varchar(64) NOT NULL DEFAULT "",
  `file_stat_downloads` text NOT NULL,
  `order_custom_info` text NOT NULL,
  `display_price` tinyint(1) NOT NULL,
  `vendor_type` tinyint(1) NOT NULL,
  `vendor_id` INT NOT NULL,
  `lang` varchar(16) NOT NULL,
  `transaction` text NOT NULL,
  `status` INT NOT NULL DEFAULT 0,
  `shipping_tax` decimal(8,2) NOT NULL DEFAULT 19.00,
  `payment_tax` decimal(8,2) NOT NULL DEFAULT 19.00,
  `delivery_time` varchar(100) NOT NULL,
  `coupon_id` INT NOT NULL,
  `delivery_times_id` INT NOT NULL,
  `ip` text NOT NULL,
  `partner_id` INT NOT NULL,
  `user_un` text NOT NULL,
  `pattern_report_status` INT NOT NULL DEFAULT 0,
  `pattern_percent_price` double NOT NULL DEFAULT 0,
  `valid_to` DATETIME NOT NULL,
  `projectname` TEXT,
  `show_invoice_date` int(1) NOT NULL DEFAULT '1',
  `status_email` int(2) NOT NULL DEFAULT '0',
  `offer_status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`)
);

COMMIT;