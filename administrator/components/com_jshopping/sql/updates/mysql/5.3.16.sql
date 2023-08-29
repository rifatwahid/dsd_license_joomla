START TRANSACTION;
ALTER TABLE `#__jshopping_config` MODIFY `use_ssl` TINYINT(1) DEFAULT 0;
UPDATE `#__jshopping_config` SET `use_ssl` = 0;
COMMIT;