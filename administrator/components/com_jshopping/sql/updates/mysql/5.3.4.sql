START TRANSACTION;
CREATE TABLE IF NOT EXISTS `#__jshopping_coupons_users_rest` (
    `user_id` int(11) NOT NULL DEFAULT '0',
    `coupon_id` int(11) NOT NULL DEFAULT '0',
    `rest` decimal(12,2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`user_id`,`coupon_id`),
    KEY `coupon_id` (`coupon_id`),
    KEY `user_id` (`user_id`)
);
COMMIT;