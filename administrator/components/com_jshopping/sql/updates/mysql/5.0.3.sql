START TRANSACTION;
ALTER TABLE `#__jshopping_sort_val_attrs` ADD INDEX `product_id`(`product_id`);
ALTER TABLE `#__jshopping_sort_val_attrs` ADD INDEX `attr_val_id`(`attr_val_id`);
COMMIT;