START TRANSACTION;
CREATE TABLE IF NOT EXISTS `#__jshopping_native_uploads_prices` ( 
	`id` BIGINT NOT NULL AUTO_INCREMENT , 
	`product_id` BIGINT NOT NULL , 
	`from_item` INT NOT NULL DEFAULT '0' , 
	`to_item` INT NOT NULL DEFAULT '0' , 
	`percent` DECIMAL(18,6) NULL DEFAULT '0' , 
	`price` DECIMAL(18,6) NOT NULL DEFAULT '0' , 
	`calculated_price` DECIMAL(18,6) NOT NULL DEFAULT '0' ,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;
COMMIT;

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_products', 'is_activated_price_per_consignment_upload', 'BOOLEAN NOT NULL DEFAULT FALSE');