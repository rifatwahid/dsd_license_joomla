CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'refund_suffix', "TEXT NOT NULL");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'next_refund_number', "INT(11) NOT NULL DEFAULT '0'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'additional_admin_refund_email', "VARCHAR(100)");

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_refunds', 'refund_date', "date");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_refunds', 'pdf_date', "date");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_refunds', 'pdf_file', "VARCHAR(50) NOT NULL DEFAULT ''");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_refunds', 'refund_number', "VARCHAR(50) NOT NULL DEFAULT '0'");

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_generate_refund_note', "tinyint(1) DEFAULT '0'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_send_refund_note_to_customer', "tinyint(1) DEFAULT '0'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_send_refund_note_to_admin', "tinyint(1) DEFAULT '0'");

