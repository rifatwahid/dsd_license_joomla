START TRANSACTION;
ALTER TABLE `#__jshopping_config` DROP COLUMN `product_list_show_min_price`;
COMMIT;