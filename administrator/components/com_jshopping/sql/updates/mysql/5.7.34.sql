CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_generate_invoice', 'BOOLEAN DEFAULT false');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_send_invoice_to_customer', 'BOOLEAN DEFAULT false');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_send_invoice_to_admin', 'BOOLEAN DEFAULT false');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_generate_delivery_note', 'BOOLEAN DEFAULT false');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_send_delivery_note_to_customer', 'BOOLEAN DEFAULT false');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'is_send_delivery_note_to_admin', 'BOOLEAN DEFAULT false');

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'additional_admin_invoice_email', 'VARCHAR(100)');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'additional_admin_delivery_note_email', 'VARCHAR(100)');