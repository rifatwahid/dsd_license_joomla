START TRANSACTION;
DELETE FROM `#__jshopping_languages`;
ALTER TABLE `#__jshopping_coupons` CHANGE `coupon_start_date` `coupon_start_date` DATE NULL, CHANGE `coupon_expire_date` `coupon_expire_date` DATE NULL;
COMMIT;