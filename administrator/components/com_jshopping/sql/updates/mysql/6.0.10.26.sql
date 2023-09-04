CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'is_show_eu_b2b_tax_msg_in_bill', 'BOOLEAN NOT NULL DEFAULT false');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'eu_countries_to_show_b2b_msg', 'TEXT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'eu_countries_selected_applies_to', 'TEXT NULL');