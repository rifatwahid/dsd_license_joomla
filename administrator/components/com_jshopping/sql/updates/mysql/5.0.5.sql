CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_products_attr', 'low_stock_attr_notify_status', 'BOOLEAN DEFAULT 0');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_products_attr', 'low_stock_attr_notify_number', 'INT DEFAULT 0');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_products', 'product_show_cart', 'TINYINT(1) NOT NULL DEFAULT "1"');