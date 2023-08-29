CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'sendmail_reviews_admin_email', 'TINYINT NOT NULL DEFAULT "1"');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'sendmail_reviews_admin_email_all_reviews', 'TINYINT NOT NULL DEFAULT "1"');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'sendmail_reviews_admin_email_require_confirmation', 'TINYINT NOT NULL DEFAULT "1"');
CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_config', 'sendmail_reviews_admin_email_from_guests', 'TINYINT NOT NULL DEFAULT "1"');