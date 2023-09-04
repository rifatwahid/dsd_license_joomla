START TRANSACTION;
ALTER TABLE `#__jshopping_products_attr2` CHANGE `addprice` `addprice` DECIMAL(12,6) NOT NULL;
COMMIT;