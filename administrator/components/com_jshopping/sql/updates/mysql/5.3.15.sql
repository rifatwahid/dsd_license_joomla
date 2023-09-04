START TRANSACTION;
ALTER TABLE `#__jshopping_config` DROP COLUMN `show_buy_in_category`, DROP COLUMN `product_list_show_price_description`;
COMMIT;