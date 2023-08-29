START TRANSACTION;
UPDATE `#__jshopping_config` SET `use_extend_attribute_data` = 1;
UPDATE `#__jshopping_config` SET `shop_user_guest` = 1;
COMMIT;