CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'allow_reviews_uploads', "TINYINT(1) NOT NULL DEFAULT '0'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_products_reviews', 'reviewfile', 'TEXT NOT NULL');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'review_product_width', "INT(4) NOT NULL DEFAULT '100'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'review_product_height', "INT(4) NOT NULL DEFAULT '100'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'review_product_full_width', "INT(4) NOT NULL DEFAULT '300'");
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'review_product_full_height', "INT(4) NOT NULL DEFAULT '300'");
