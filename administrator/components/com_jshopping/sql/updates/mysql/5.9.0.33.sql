
CREATE TABLE `#__jshopping_refunds` (
  `refund_id` SERIAL,
  `order_id` int(11) NOT NULL,
  `refund_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `refund_subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `refund_tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `refund_tax_ext` text NOT NULL,
  `refund_shipping` decimal(12,2) NOT NULL DEFAULT '0.00',
  `refund_payment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `refund_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency_exchange` decimal(14,6) NOT NULL DEFAULT '0.000000',
  `shipping_tax` decimal(8,2) NOT NULL DEFAULT '19.00',
  `payment_tax` decimal(8,2) NOT NULL DEFAULT '19.00',
  `coupon_id` int(11) NOT NULL,
  `pattern_percent_price` double NOT NULL DEFAULT '0',
  `taxes_ext` int(11) NOT NULL,
  `refund_package` decimal(12,2) NOT NULL,
   PRIMARY KEY (`refund_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__jshopping_refund_item` (
  `refund_item_id` SERIAL,
  `refund_id` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `product_item_price` decimal(12,2) NOT NULL,
  `product_tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `weight` float(8,4) NOT NULL DEFAULT '0.0000',
  `basicprice` decimal(12,2) NOT NULL,
  `basicpriceunit` varchar(255) NOT NULL,
  `pattern_report_sum` text NOT NULL,
  `total_price` double DEFAULT '0',
  `order_item_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_attributes` text NOT NULL,
  `product_freeattributes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
