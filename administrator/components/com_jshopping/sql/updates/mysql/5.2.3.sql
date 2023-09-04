CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_item', 'reorder', 'varchar(50) NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_item', 'reorder_num', 'int(11) NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_payment_method', 'payment_status', 'INT NOT NULL AFTER `payment_params`');