START TRANSACTION;
ALTER TABLE `#__jshopping_orders` CHANGE `order_hash` `order_hash` VARCHAR(64);
COMMIT;