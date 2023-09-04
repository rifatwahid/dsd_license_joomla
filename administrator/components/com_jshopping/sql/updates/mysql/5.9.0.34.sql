ALTER TABLE `#__jshopping_config` ADD `invoice_suffix` TEXT NOT NULL;
ALTER TABLE `#__jshopping_config` ADD `next_invoice_number` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `#__jshopping_orders` ADD `invoice_number` VARCHAR(50) NOT NULL DEFAULT '0'; 