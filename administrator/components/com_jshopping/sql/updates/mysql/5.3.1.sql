START TRANSACTION;
CREATE TABLE IF NOT EXISTS `#__jshopping_upload` (`id` SERIAL PRIMARY KEY);
DROP TABLE `#__jshopping_upload`;

CREATE TABLE IF NOT EXISTS `#__jshopping_upload` (
  `id` SERIAL PRIMARY KEY,
  `allow_files_types` TEXT,
  `is_allow_product_page` INT NOT NULL DEFAULT 0,
  `is_allow_cart_page` INT NOT NULL DEFAULT 0,
  `upload_design` INT NOT NULL DEFAULT 0
);

INSERT INTO `#__jshopping_upload`(`allow_files_types`) VALUES('jpeg,jpg,gif,png,pdf,ai,svg,zip');

CREATE TABLE IF NOT EXISTS `#__jshopping_order_items_native_uploads_files`(
	`id` SERIAL PRIMARY KEY,
	`order_id` INT,
	`order_item_id` INT,
	`qty` INT,
	`file` TEXT,
	`preview` TEXT,
	`description` TEXT
);

CREATE TABLE IF NOT EXISTS `#__jshopping_free_attribute_calcule_price` (
  `id` SERIAL PRIMARY KEY, 
  `name` varchar(100) NOT NULL, 
  `params` longtext NOT NULL
) AUTO_INCREMENT = 2;

DELETE FROM `#__jshopping_free_attribute_calcule_price` WHERE `params` = '';
INSERT INTO `#__jshopping_free_attribute_calcule_price`(`name`, `params`) VALUES('free_attribute', 'a:20:{s:9:"variables";a:4:{s:8:"width_id";s:1:"0";s:9:"height_id";s:1:"0";s:8:"depth_id";s:1:"0";s:5:"var_1";s:1:"0";}s:14:"variablesNames";a:1:{s:5:"var_1";s:9:"Variable1";}s:9:"width_def";s:0:"";s:10:"height_def";s:0:"";s:9:"depth_def";s:0:"";s:9:"var_1_def";s:0:"";s:9:"width_min";s:0:"";s:10:"height_min";s:0:"";s:9:"depth_min";s:0:"";s:9:"var_1_min";s:0:"";s:9:"width_max";s:0:"";s:10:"height_max";s:0:"";s:9:"depth_max";s:0:"";s:9:"var_1_max";s:0:"";s:10:"width_step";s:0:"";s:11:"height_step";s:0:"";s:10:"depth_step";s:0:"";s:10:"var_1_step";s:0:"";s:18:"pricetypes_formula";a:1:{i:100500;s:3:"111";}s:23:"pricetypes_formula_name";a:1:{i:100500;s:13:"One-time cost";}}');
COMMIT;