START TRANSACTION;
CREATE TABLE IF NOT EXISTS `#__jshopping_sort_val_attrs`(
	`id` SERIAL,
	`product_id` BIGINT,
	`attr_val_id` BIGINT,
	`frontend_sorting` BIGINT,
	PRIMARY KEY (`id`)
);
COMMIT;