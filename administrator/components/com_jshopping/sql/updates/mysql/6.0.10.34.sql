CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'invoice_suffix', 'TEXT NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'next_invoice_number', 'INT(11) NOT NULL DEFAULT "0"');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_orders', 'invoice_number', 'VARCHAR(50) NOT NULL DEFAULT "0"');