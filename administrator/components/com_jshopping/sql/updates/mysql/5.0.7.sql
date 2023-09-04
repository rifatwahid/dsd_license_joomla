START TRANSACTION;
CREATE TABLE IF NOT EXISTS `#__jshopping_free_attr_default_values` (
  `id` SERIAL,
  `product_id` BIGINT NOT NULL,
  `attr_id` BIGINT NOT NULL,
  `default_value` TEXT,
  `is_fixed` BOOLEAN DEFAULT 0,
  `attr_activated` BOOLEAN DEFAULT 0,
  `showFreeAttrInput` BOOLEAN DEFAULT 0,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;
COMMIT;