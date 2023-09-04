START TRANSACTION;
ALTER TABLE `#__jshopping_config` DROP COLUMN `demo_type`, DROP COLUMN`radio_attr_value_vertical`;
COMMIT;