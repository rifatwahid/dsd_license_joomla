CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_coupons', 'count_use', 'INT(11) NOT NULL');

UPDATE `#__jshopping_coupons` as c
SET  c.`count_use`=(SELECT count(o.`coupon_id`)
                            FROM `#__jshopping_orders` as o
                            WHERE o.`coupon_id` = c.`coupon_id`)
