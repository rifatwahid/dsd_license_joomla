CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'allow_offer_on_product_details_page', 'INT NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'allow_offer_in_cart', 'INT NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_order_status', 'color', 'VARCHAR(20)');