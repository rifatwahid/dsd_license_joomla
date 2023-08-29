
DROP TABLE IF EXISTS `#__ee_editors_to_categories`;

CREATE TABLE `#__ee_editors_to_categories` (
  `id` int(11) NOT NULL,
  `editor_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `enable` int(11) NOT NULL,
  `userphoto_preload` int(11) NOT NULL DEFAULT '0',
  `serverphoto_preload` int(11) NOT NULL DEFAULT '0',
  `fotolia_preload` int(11) NOT NULL DEFAULT '0',
  `clipart_preload` int(11) NOT NULL DEFAULT '0',
  `pattern_preload` int(11) NOT NULL DEFAULT '0',
  `gallery_type` int(11) NOT NULL DEFAULT '0',
  `userphoto_preload_filter` int(11) NOT NULL DEFAULT '1',
  `serverphoto_preload_filter` int(11) NOT NULL DEFAULT '1',
  `fotolia_preload_filter` int(11) NOT NULL DEFAULT '1',
  `clipart_preload_filter` int(11) NOT NULL DEFAULT '1',
  `pattern_preload_filter` int(11) NOT NULL DEFAULT '1',
  `userphoto_preload_filter_menu` int(11) NOT NULL DEFAULT '1',
  `serverphoto_preload_filter_menu` int(11) NOT NULL DEFAULT '1',
  `fotolia_preload_filter_menu` int(11) NOT NULL DEFAULT '1',
  `clipart_preload_filter_menu` int(11) NOT NULL DEFAULT '1',
  `pattern_preload_filter_menu` int(11) NOT NULL DEFAULT '1',
  `userphoto_preload_filter_categories` int(11) NOT NULL DEFAULT '1',
  `serverphoto_preload_filter_categories` int(11) NOT NULL DEFAULT '1',
  `fotolia_preload_filter_categories` int(11) NOT NULL DEFAULT '1',
  `clipart_preload_filter_categories` int(11) NOT NULL DEFAULT '1',
  `pattern_preload_filter_categories` int(11) NOT NULL DEFAULT '1',
  `userphoto_preload_text` text NOT NULL,
  `serverphotos_preload_text` text NOT NULL,
  `cliparts_preload_text` text NOT NULL,
  `fotolia_preload_text` text NOT NULL,
  `patterns_preload_text` text NOT NULL,
  `userphoto_preload_text_en` varchar(255) NOT NULL,
  `userphoto_preload_text_de` varchar(255) NOT NULL,
  `userphoto_preload_text_es` varchar(255) NOT NULL,
  `userphoto_preload_text_it` varchar(255) NOT NULL,
  `userphoto_preload_text_pl` varchar(255) NOT NULL,
  `userphoto_preload_text_fr` varchar(255) NOT NULL,
  `userphoto_preload_text_nl` varchar(255) NOT NULL,
  `userphoto_preload_text_ru` varchar(255) NOT NULL,
  `userphoto_preload_text_sv` varchar(255) NOT NULL,
  `serverphotos_preload_text_en` varchar(255) NOT NULL,
  `serverphotos_preload_text_de` varchar(255) NOT NULL,
  `serverphotos_preload_text_es` varchar(255) NOT NULL,
  `serverphotos_preload_text_it` varchar(255) NOT NULL,
  `serverphotos_preload_text_pl` varchar(255) NOT NULL,
  `serverphotos_preload_text_fr` varchar(255) NOT NULL,
  `serverphotos_preload_text_nl` varchar(255) NOT NULL,
  `serverphotos_preload_text_ru` varchar(255) NOT NULL,
  `serverphotos_preload_text_sv` varchar(255) NOT NULL,
  `fotolia_preload_text_en` varchar(255) NOT NULL,
  `fotolia_preload_text_de` varchar(255) NOT NULL,
  `fotolia_preload_text_es` varchar(255) NOT NULL,
  `fotolia_preload_text_it` varchar(255) NOT NULL,
  `fotolia_preload_text_pl` varchar(255) NOT NULL,
  `fotolia_preload_text_fr` varchar(255) NOT NULL,
  `fotolia_preload_text_nl` varchar(255) NOT NULL,
  `fotolia_preload_text_ru` varchar(255) NOT NULL,
  `fotolia_preload_text_sv` varchar(255) NOT NULL,
  `patterns_preload_text_en` varchar(255) NOT NULL,
  `patterns_preload_text_de` varchar(255) NOT NULL,
  `patterns_preload_text_es` varchar(255) NOT NULL,
  `patterns_preload_text_it` varchar(255) NOT NULL,
  `patterns_preload_text_pl` varchar(255) NOT NULL,
  `patterns_preload_text_fr` varchar(255) NOT NULL,
  `patterns_preload_text_nl` varchar(255) NOT NULL,
  `patterns_preload_text_ru` varchar(255) NOT NULL,
  `patterns_preload_text_sv` varchar(255) NOT NULL,
  `cliparts_preload_text_en` varchar(255) NOT NULL,
  `cliparts_preload_text_de` varchar(255) NOT NULL,
  `cliparts_preload_text_es` varchar(255) NOT NULL,
  `cliparts_preload_text_it` varchar(255) NOT NULL,
  `cliparts_preload_text_pl` varchar(255) NOT NULL,
  `cliparts_preload_text_fr` varchar(255) NOT NULL,
  `cliparts_preload_text_nl` varchar(255) NOT NULL,
  `cliparts_preload_text_ru` varchar(255) NOT NULL,
  `cliparts_preload_text_sv` varchar(255) NOT NULL,
  `clipart_preload_text_en` varchar(255) NOT NULL,
  `clipart_preload_text_de` varchar(255) NOT NULL,
  `clipart_preload_text_fr` varchar(255) NOT NULL,
  `clipart_preload_text_it` varchar(255) NOT NULL,
  `clipart_preload_text_nl` varchar(255) NOT NULL,
  `clipart_preload_text_pl` varchar(255) NOT NULL,
  `clipart_preload_text_ru` varchar(255) NOT NULL,
  `uploadedfile_serverphotos` text NOT NULL,
  `uploadedfile_fotolia` text NOT NULL,
  `uploadedfile_patterns` text NOT NULL,
  `uploadedfile_cliparts` text NOT NULL,
  `avalible_editors` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `#__ee_editors_to_products`;

CREATE TABLE `#__ee_editors_to_products` (
  `id` int(11) NOT NULL,
  `editor_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `enable` int(11) NOT NULL DEFAULT '1',
  `open_type` int(11) NOT NULL DEFAULT '0',
  `epp_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `#__jshopping_addons` (
  `id` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `key` text NOT NULL,
  `version` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `name` varchar(255) NOT NULL,
  `uninstall` varchar(255) NOT NULL,
  `usekey` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_attr` (
  `attr_id` int(11) NOT NULL,
  `attr_ordering` int(11) NOT NULL DEFAULT '0',
  `attr_type` tinyint(1) NOT NULL,
  `independent` tinyint(1) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `allcats` tinyint(1) NOT NULL DEFAULT '1',
  `cats` text NOT NULL,
  `description_de-DE` text NOT NULL,
  `description_en-GB` text NOT NULL,
  `description_es-ES` text NOT NULL,
  `description_fr-FR` text NOT NULL,
  `description_it-IT` text NOT NULL,
  `description_nl-NL` text NOT NULL,
  `description_pl-PL` text NOT NULL,
  `description_ru-RU` text NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `description_sv-SE` text NOT NULL,
  `editor_field_id` int(11) NOT NULL DEFAULT '0',
  `group` tinyint(4) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `description_fr-CA` text NOT NULL,
  `ERPnr` text NOT NULL,
  `ERPsort` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_attr_groups` (
  `id` int(11) NOT NULL,
  `ordering` int(6) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `hide_title` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_attr_values` (
  `value_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL,
  `value_ordering` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `free_attr_id` varchar(255) NOT NULL,
  `product_linear_price` decimal(12,2) NOT NULL,
  `ERPnr` text NOT NULL,
  `ERPsort` text NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `editor_param_id` int(11) NOT NULL,
  `atribute_buton_editor_button` int(11) NOT NULL,
  `atribute_buton_cart_button` int(11) NOT NULL,
  `atribute_buton_upload_button` int(11) NOT NULL,
  `product_one_time_cost` tinyint(1) DEFAULT '0',
  `exclude_attribute_for_attribute` text NOT NULL,
  `exclude_buttons_for_attribute` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_cart_temp` (
  `id` int(11) NOT NULL,
  `id_cookie` varchar(255) NOT NULL,
  `cart` text NOT NULL,
  `type_cart` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_categories` (
  `category_id` int(11) NOT NULL,
  `category_image` varchar(255) DEFAULT NULL,
  `category_parent_id` int(11) NOT NULL DEFAULT '0',
  `category_publish` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `category_ordertype` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `category_template` varchar(64) DEFAULT NULL,
  `ordering` int(3) NOT NULL,
  `category_add_date` datetime DEFAULT NULL,
  `products_page` int(8) NOT NULL DEFAULT '12',
  `products_row` int(3) NOT NULL DEFAULT '3',
  `access` int(3) NOT NULL DEFAULT '1',
  `name_de-DE` varchar(255) NOT NULL,
  `alias_de-DE` varchar(255) NOT NULL,
  `short_description_de-DE` text NOT NULL,
  `description_de-DE` text NOT NULL,
  `meta_title_de-DE` varchar(255) NOT NULL,
  `meta_description_de-DE` text NOT NULL,
  `meta_keyword_de-DE` text NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `alias_en-GB` varchar(255) NOT NULL,
  `short_description_en-GB` text NOT NULL,
  `description_en-GB` text NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `short_description_es-ES` text NOT NULL,
  `description_es-ES` text NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `short_description_it-IT` text NOT NULL,
  `description_it-IT` text NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `short_description_fr-FR` text NOT NULL,
  `description_fr-FR` text NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `short_description_nl-NL` text NOT NULL,
  `description_nl-NL` text NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `short_description_pl-PL` text NOT NULL,
  `description_pl-PL` text NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `short_description_ru-RU` text NOT NULL,
  `description_ru-RU` text NOT NULL,
  `related_product_id` int(11) NOT NULL DEFAULT '0',
  `category_view_type` int(11) NOT NULL DEFAULT '0',
  `name_sv-SE` varchar(255) NOT NULL,
  `short_description_sv-SE` text NOT NULL,
  `description_sv-SE` text NOT NULL,
  `category_display_products_select` tinyint(1) NOT NULL DEFAULT '0',
  `robots_de-DE` int(11) NOT NULL DEFAULT '0',
  `robots_en-GB` int(11) NOT NULL DEFAULT '0',
  `robots_fr-FR` int(11) NOT NULL DEFAULT '0',
  `robots_it-IT` int(11) NOT NULL DEFAULT '0',
  `robots_nl-NL` int(11) NOT NULL DEFAULT '0',
  `robots_pl-PL` int(11) NOT NULL DEFAULT '0',
  `robots_ru-RU` int(11) NOT NULL DEFAULT '0',
  `name_fr-CA` varchar(255) NOT NULL,
  `short_description_fr-CA` text NOT NULL,
  `description_fr-CA` text NOT NULL,
  `robots_fr-CA` int(11) NOT NULL DEFAULT '0',
  `titelbar` varchar(150) NOT NULL,
  `meta_title_en-GB` varchar(255) NOT NULL,
  `meta_description_en-GB` text NOT NULL,
  `meta_keyword_en-GB` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_categories` (`category_id`, `category_image`, `category_parent_id`, `category_publish`, `category_ordertype`, `category_template`, `ordering`, `category_add_date`, `products_page`, `products_row`, `access`, `name_de-DE`, `alias_de-DE`, `short_description_de-DE`, `description_de-DE`, `meta_title_de-DE`, `meta_description_de-DE`, `meta_keyword_de-DE`, `name_en-GB`, `alias_en-GB`, `short_description_en-GB`, `description_en-GB`, `name_es-ES`, `short_description_es-ES`, `description_es-ES`, `name_it-IT`, `short_description_it-IT`, `description_it-IT`, `name_fr-FR`, `short_description_fr-FR`, `description_fr-FR`, `name_nl-NL`, `short_description_nl-NL`, `description_nl-NL`, `name_pl-PL`, `short_description_pl-PL`, `description_pl-PL`, `name_ru-RU`, `short_description_ru-RU`, `description_ru-RU`, `related_product_id`, `category_view_type`, `name_sv-SE`, `short_description_sv-SE`, `description_sv-SE`, `category_display_products_select`, `robots_de-DE`, `robots_en-GB`, `robots_fr-FR`, `robots_it-IT`, `robots_nl-NL`, `robots_pl-PL`, `robots_ru-RU`, `name_fr-CA`, `short_description_fr-CA`, `description_fr-CA`, `robots_fr-CA`, `titelbar`, `meta_title_en-GB`, `meta_description_en-GB`, `meta_keyword_en-GB`) VALUES
(1, NULL, 0, 0, 1, NULL, 1, '1970-01-01 00:00:00', 16, 3, 1, 'Userproducts', '', '', '', '', '', '', 'Userproducts', '', '', '', 'Userproducts', '', '', 'Userproducts', '', '', 'Userproducts', '', '', 'Userproducts', '', '', 'Userproducts', '', '', 'Userproducts', '', '', 0, 0, 'Userproducts', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 'Userproducts', '', '', 0, '', '', '', '');

CREATE TABLE `#__jshopping_categories_added_content` (
  `category_id` int(11) NOT NULL,
  `category_image_second` varchar(255) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_categories_shipping` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sh_pr_method_id` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_pack` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_category_prices_group` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `percent` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_config` (
  `id` int(11) NOT NULL,
  `count_products_to_page` int(4) NOT NULL DEFAULT '0',
  `count_products_to_row` int(2) NOT NULL DEFAULT '1',
  `count_category_to_row` int(2) NOT NULL DEFAULT '1',
  `image_category_width` int(4) NOT NULL DEFAULT '0',
  `image_category_height` int(4) NOT NULL DEFAULT '0',
  `image_payments_width` int(4) NOT NULL DEFAULT '0',
  `image_payments_height` int(4) NOT NULL DEFAULT '0',
  `image_shippings_width` int(4) NOT NULL DEFAULT '0',
  `image_shippings_height` int(4) NOT NULL DEFAULT '0',
  `image_product_width` int(4) NOT NULL DEFAULT '0',
  `image_product_height` int(4) NOT NULL DEFAULT '0',
  `image_product_full_width` int(4) NOT NULL DEFAULT '0',
  `image_product_full_height` int(4) NOT NULL DEFAULT '0',
  `video_product_width` int(4) NOT NULL DEFAULT '0',
  `video_product_height` int(4) NOT NULL DEFAULT '0',
  `review_product_width` int(4) NOT NULL DEFAULT '0',
  `review_product_height` int(4) NOT NULL DEFAULT '0',
  `review_product_full_width` int(4) NOT NULL DEFAULT '0',
  `review_product_full_height` int(4) NOT NULL DEFAULT '0',
  `adminLanguage` varchar(8) NOT NULL DEFAULT '',
  `defaultLanguage` varchar(8) NOT NULL DEFAULT '',
  `mainCurrency` int(4) NOT NULL,
  `decimal_count` tinyint(1) NOT NULL,
  `decimal_symbol` varchar(5) NOT NULL,
  `thousand_separator` varchar(5) NOT NULL,
  `currency_format` tinyint(1) NOT NULL,
  `use_rabatt_code` tinyint(1) NOT NULL,
  `enable_wishlist` tinyint(1) NOT NULL,
  `default_status_order` tinyint(1) NOT NULL,
  `order_number_type` varchar(50) NOT NULL,
  `store_address_format` varchar(32) NOT NULL,
  `store_date_format` varchar(32) NOT NULL,
  `contact_email` varchar(128) NOT NULL,
  `allow_reviews_prod` tinyint(1) NOT NULL,
  `allow_reviews_only_registered` tinyint(1) NOT NULL,
  `allow_reviews_only_buyers` tinyint(1) NOT NULL,
  `allow_reviews_manuf` tinyint(1) NOT NULL,
  `max_mark` int(11) NOT NULL,
  `summ_null_shipping` decimal(12,2) NOT NULL,
  `without_shipping` tinyint(1) NOT NULL,
  `without_payment` tinyint(1) NOT NULL,
  `pdf_parameters` varchar(32) NOT NULL,
  `next_order_number` int(11) NOT NULL DEFAULT '1',
  `shop_user_guest` tinyint(1) NOT NULL,
  `hide_product_not_avaible_stock` tinyint(1) NOT NULL,
  `user_as_catalog` tinyint(1) NOT NULL,
  `show_tax_in_product` tinyint(1) NOT NULL,
  `show_tax_product_in_cart` tinyint(1) NOT NULL,
  `show_plus_shipping_in_product` tinyint(1) NOT NULL,
  `show_plus_shipping_in_product_list` tinyint(1) NOT NULL,
  `hide_buy_not_avaible_stock` tinyint(1) NOT NULL,
  `show_sort_product` tinyint(1) NOT NULL,
  `show_count_select_products` tinyint(1) NOT NULL,
  `order_send_pdf_client` tinyint(1) NOT NULL,
  `order_send_pdf_admin` tinyint(1) NOT NULL,
  `show_delivery_time` tinyint(1) NOT NULL,
  `securitykey` varchar(128) NOT NULL,
  `product_show_manufacturer_logo` tinyint(1) NOT NULL,
  `product_show_manufacturer` tinyint(1) NOT NULL,
  `product_show_weight` tinyint(1) NOT NULL,
  `max_count_order_one_product` int(11) NOT NULL,
  `min_count_order_one_product` int(11) NOT NULL,
  `min_price_order` int(11) NOT NULL,
  `max_price_order` int(11) NOT NULL,
  `hide_tax` tinyint(1) NOT NULL,
  `licensekod` text NOT NULL,
  `product_attribut_first_value_empty` tinyint(1) NOT NULL,
  `show_hits` tinyint(1) NOT NULL,
  `show_registerform_in_logintemplate` tinyint(1) NOT NULL,
  `admin_show_product_basic_price` tinyint(1) NOT NULL,
  `admin_show_attributes` tinyint(1) NOT NULL,
  `admin_show_delivery_time` tinyint(1) NOT NULL,
  `admin_show_languages` tinyint(1) NOT NULL,
  `use_different_templates_cat_prod` tinyint(1) NOT NULL,
  `admin_show_product_video` tinyint(1) NOT NULL,
  `admin_show_product_related` tinyint(1) NOT NULL,
  `admin_show_product_demo_files` tinyint(1) NOT NULL,
  `admin_show_product_sale_files` tinyint(1) NOT NULL,
  `admin_show_product_bay_price` tinyint(1) NOT NULL,
  `admin_show_product_labels` tinyint(1) NOT NULL,
  `sorting_country_in_alphabet` tinyint(1) NOT NULL,
  `hide_text_product_not_available` tinyint(1) NOT NULL,
  `show_weight_order` tinyint(1) NOT NULL,
  `discount_use_full_sum` tinyint(1) NOT NULL,
  `show_cart_all_step_checkout` tinyint(1) NOT NULL,
  `use_plugin_content` tinyint(1) NOT NULL,
  `display_price_admin` tinyint(1) NOT NULL,
  `display_price_front` tinyint(1) NOT NULL,
  `product_list_show_weight` tinyint(1) NOT NULL,
  `product_list_show_manufacturer` tinyint(1) NOT NULL,
  `use_extend_tax_rule` tinyint(4) NOT NULL,
  `use_extend_display_price_rule` tinyint(4) NOT NULL,
  `fields_register` text NOT NULL,
  `template` varchar(128) NOT NULL,
  `show_product_code` tinyint(1) NOT NULL,
  `show_product_code_in_cart` tinyint(1) NOT NULL,
  `savelog` tinyint(1) NOT NULL,
  `savelogpaymentdata` tinyint(1) NOT NULL,
  `product_count_related_in_row` tinyint(4) NOT NULL DEFAULT '1',
  `category_sorting` tinyint(1) NOT NULL DEFAULT '1',
  `product_sorting` tinyint(1) NOT NULL DEFAULT '1',
  `product_sorting_direction` tinyint(1) NOT NULL DEFAULT '0',
  `show_product_list_filters` tinyint(1) NOT NULL,
  `admin_show_product_extra_field` tinyint(1) NOT NULL,
  `product_list_display_extra_fields` text NOT NULL,
  `filter_display_extra_fields` text NOT NULL,
  `product_hide_extra_fields` text NOT NULL,
  `default_country` int(11) NOT NULL,
  `show_return_policy_in_email_order` tinyint(1) NOT NULL,
  `client_allow_cancel_order` tinyint(1) NOT NULL,
  `admin_show_vendors` tinyint(1) NOT NULL,
  `vendor_order_message_type` tinyint(1) NOT NULL,
  `admin_not_send_email_order_vendor_order` tinyint(1) NOT NULL,
  `not_redirect_in_cart_after_buy` tinyint(1) NOT NULL,
  `product_show_vendor` tinyint(1) NOT NULL,
  `product_show_vendor_detail` tinyint(1) NOT NULL,
  `product_list_show_vendor` tinyint(1) NOT NULL,
  `admin_show_freeattributes` tinyint(1) NOT NULL,
  `product_show_button_back` tinyint(1) NOT NULL,
  `calcule_tax_after_discount` tinyint(1) NOT NULL,
  `product_list_show_product_code` tinyint(1) NOT NULL,
  `attr_display_addprice` tinyint(1) NOT NULL,
  `use_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `display_button_print` tinyint(1) NOT NULL,
  `hide_shipping_step` tinyint(1) NOT NULL,
  `hide_payment_step` tinyint(1) NOT NULL,
  `image_resize_type` tinyint(1) NOT NULL,
  `use_extend_attribute_data` tinyint(1) NOT NULL,
  `product_list_show_price_default` tinyint(1) NOT NULL,
  `show_base_price_for_product_list` tinyint(1) NOT NULL,
  `product_list_show_qty_stock` tinyint(1) NOT NULL,
  `product_show_qty_stock` tinyint(1) NOT NULL,
  `displayprice_for_list_product` tinyint(1) NOT NULL,
  `displayprice_for_product` tinyint(1) NOT NULL,
  `use_decimal_qty` tinyint(1) NOT NULL,
  `ext_tax_rule_for` tinyint(1) NOT NULL,
  `display_reviews_without_confirm` tinyint(1) NOT NULL,
  `manufacturer_sorting` tinyint(1) NOT NULL,
  `admin_show_units` tinyint(1) NOT NULL,
  `main_unit_weight` tinyint(3) NOT NULL,
  `create_alias_product_category_auto` tinyint(1) NOT NULL,
  `other_config` text NOT NULL,
  `cart_display_extra_fields` text NOT NULL,
  `pdf_display_extra_fields` text NOT NULL,
  `hide_extra_fields_images` text NOT NULL,
  `mail_display_extra_fields` text NOT NULL,
  `delivery_order_depends_delivery_product` tinyint(1) NOT NULL,
  `show_delivery_time_step5` tinyint(1) NOT NULL,
  `image_product_original_width` int(4) NOT NULL,
  `image_product_original_height` int(4) NOT NULL,
  `shop_mode` tinyint(1) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `finished_order_prefix` varchar(10) NOT NULL DEFAULT 'FN-',
  `count_manufacturer_to_page` int(11) NOT NULL,
  `count_vendors_to_page` int(11) NOT NULL,
  `count_vendors_to_row` int(11) NOT NULL DEFAULT '1',
  `sendmail_reviews_admin_email` tinyint(4) NOT NULL DEFAULT '1',
  `sendmail_reviews_admin_email_all_reviews` tinyint(4) NOT NULL DEFAULT '1',
  `sendmail_reviews_admin_email_require_confirmation` tinyint(4) NOT NULL DEFAULT '1',
  `sendmail_reviews_admin_email_from_guests` tinyint(4) NOT NULL DEFAULT '1',
  `offer_and_order_validity` int(11) DEFAULT NULL,
  `offer_and_order_invoice_data` tinyint(4) DEFAULT NULL,
  `offer_and_order_payment` int(11) DEFAULT '0',
  `offer_and_order_shipping` int(11) DEFAULT '0',
  `allow_offer_on_product_details_page` int(11) NOT NULL,
  `allow_offer_in_cart` int(11) NOT NULL,
  `order_suffix` text,
  `delivery_note_suffix` text,
  `offer_and_order_suffix` text,
  `delivery_times_on_product_page` int(11) DEFAULT '0',
  `delivery_times_on_product_listing` int(11) DEFAULT '0',
  `show_product_manufacturer_in_cart` int(11) DEFAULT '0',
  `pdf_header_file_name` varchar(255) DEFAULT 'header_default.jpg',
  `pdf_footer_file_name` varchar(255) DEFAULT 'footer_default.jpg',
  `show_wishlist_button` tinyint(1) NOT NULL,
  `allow_reviews_uploads` tinyint(1) NOT NULL,
  `show_create_account_block` int(1) NOT NULL DEFAULT '1',
  `productlist_allow_buying` tinyint(1) DEFAULT '0',
  `storage_delete_uploads` int(11) NOT NULL DEFAULT '0',
  `storage_delete_uploads_lastcheckday` int(11) NOT NULL DEFAULT '0',
  `storage_delete_offers` int(11) NOT NULL DEFAULT '0',
  `storage_delete_deliverynotes` int(11) NOT NULL DEFAULT '0',
  `storage_delete_editor_temporary_folder` int(11) NOT NULL DEFAULT '0',
  `storage_delete_editor_print_files` int(11) NOT NULL DEFAULT '0',
  `review_max_uploads` int(4) NOT NULL,
  `single_item_price` int(1) NOT NULL DEFAULT 1,
  `product_list_show_short_description` BOOLEAN NOT NULL DEFAULT false,
  `product_show_short_description` BOOLEAN NOT NULL DEFAULT false,
  `additional_admin_invoice_email` VARCHAR(100),
  `additional_admin_delivery_note_email` VARCHAR(100),
  `show_comment_box` TINYINT(1) NOT NULL DEFAULT 0,
  `display_checkout_button` TINYINT(1) NOT NULL DEFAULT 0,
  `show_shipping_costs_in_cart` TINYINT(1) NOT NULL DEFAULT 0,
  `is_show_eu_b2b_tax_msg_in_bill` BOOLEAN NOT NULL DEFAULT false,
  `eu_countries_to_show_b2b_msg` TEXT NULL,
  `eu_countries_selected_applies_to` TEXT NULL,
  `order_status_for_return` VARCHAR(100) NOT NULL,
  `invoice_suffix` TEXT NOT NULL,
  `next_invoice_number` INT(11) NOT NULL DEFAULT '0',
  `refund_suffix` TEXT NOT NULL,
  `next_refund_number` INT(11) NOT NULL DEFAULT '0',
  `additional_admin_refund_email` VARCHAR(100),
  `display_preloader` tinyint(1) NOT NULL DEFAULT '0',
  `video_autoplay` INT(1) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_config` (`id`, `count_products_to_page`, `count_products_to_row`, `count_category_to_row`, `image_category_width`, `image_category_height`, `image_payments_width`, `image_payments_height`, `image_shippings_width`, `image_shippings_height`, `image_product_width`, `image_product_height`, `image_product_full_width`, `image_product_full_height`, `video_product_width`, `video_product_height`, `review_product_width`, `review_product_height`, `review_product_full_width`, `review_product_full_height`, `adminLanguage`, `defaultLanguage`, `mainCurrency`, `decimal_count`, `decimal_symbol`, `thousand_separator`, `currency_format`, `use_rabatt_code`, `enable_wishlist`, `default_status_order`, `order_number_type`, `store_address_format`, `store_date_format`, `contact_email`, `allow_reviews_prod`, `allow_reviews_only_registered`, `allow_reviews_only_buyers`, `allow_reviews_manuf`, `max_mark`, `summ_null_shipping`, `without_shipping`, `without_payment`, `pdf_parameters`, `next_order_number`, `shop_user_guest`, `hide_product_not_avaible_stock`, `user_as_catalog`, `show_tax_in_product`, `show_tax_product_in_cart`, `show_plus_shipping_in_product`, `show_plus_shipping_in_product_list`, `hide_buy_not_avaible_stock`, `show_sort_product`, `show_count_select_products`, `order_send_pdf_client`, `order_send_pdf_admin`, `show_delivery_time`, `securitykey`, `product_show_manufacturer_logo`, `product_show_manufacturer`, `product_show_weight`, `max_count_order_one_product`, `min_count_order_one_product`, `min_price_order`, `max_price_order`, `hide_tax`, `licensekod`, `product_attribut_first_value_empty`, `show_hits`, `show_registerform_in_logintemplate`, `admin_show_product_basic_price`, `admin_show_attributes`, `admin_show_delivery_time`, `admin_show_languages`, `use_different_templates_cat_prod`, `admin_show_product_video`, `admin_show_product_related`, `admin_show_product_sale_files`, `admin_show_product_bay_price`, `admin_show_product_labels`, `sorting_country_in_alphabet`, `hide_text_product_not_available`, `show_weight_order`, `discount_use_full_sum`, `show_cart_all_step_checkout`, `use_plugin_content`, `display_price_admin`, `display_price_front`, `product_list_show_weight`, `product_list_show_manufacturer`, `use_extend_tax_rule`, `use_extend_display_price_rule`, `fields_register`, `template`, `show_product_code`, `show_product_code_in_cart`, `savelog`, `savelogpaymentdata`, `product_count_related_in_row`, `category_sorting`, `product_sorting`, `product_sorting_direction`, `show_product_list_filters`, `admin_show_product_extra_field`, `product_list_display_extra_fields`, `filter_display_extra_fields`, `product_hide_extra_fields`, `default_country`, `show_return_policy_in_email_order`, `client_allow_cancel_order`, `admin_show_vendors`, `vendor_order_message_type`, `admin_not_send_email_order_vendor_order`, `not_redirect_in_cart_after_buy`, `product_show_vendor`, `product_show_vendor_detail`, `product_list_show_vendor`, `admin_show_freeattributes`, `product_show_button_back`, `calcule_tax_after_discount`, `product_list_show_product_code`, `attr_display_addprice`, `use_ssl`, `display_button_print`, `hide_shipping_step`, `hide_payment_step`, `image_resize_type`, `use_extend_attribute_data`, `product_list_show_price_default`, `show_base_price_for_product_list`, `product_list_show_qty_stock`, `product_show_qty_stock`, `displayprice_for_list_product`, `displayprice_for_product`, `use_decimal_qty`, `ext_tax_rule_for`, `display_reviews_without_confirm`, `manufacturer_sorting`, `admin_show_units`, `main_unit_weight`, `create_alias_product_category_auto`, `other_config`, `cart_display_extra_fields`, `delivery_order_depends_delivery_product`, `show_delivery_time_step5`, `image_product_original_width`, `image_product_original_height`, `shop_mode`, `shop_id`, `finished_order_prefix`, `count_manufacturer_to_page`, `count_vendors_to_page`, `count_vendors_to_row`, `sendmail_reviews_admin_email`, `sendmail_reviews_admin_email_all_reviews`, `sendmail_reviews_admin_email_require_confirmation`, `sendmail_reviews_admin_email_from_guests`, `offer_and_order_validity`, `offer_and_order_invoice_data`, `offer_and_order_payment`, `offer_and_order_shipping`, `allow_offer_on_product_details_page`, `allow_offer_in_cart`, `order_suffix`, `delivery_note_suffix`, `offer_and_order_suffix`, `delivery_times_on_product_page`, `delivery_times_on_product_listing`, `show_product_manufacturer_in_cart`, `pdf_header_file_name`, `pdf_footer_file_name`, `show_wishlist_button`, `allow_reviews_uploads`, `show_create_account_block`, `productlist_allow_buying`, `storage_delete_uploads`, `storage_delete_uploads_lastcheckday`, `storage_delete_offers`, `storage_delete_deliverynotes`, `storage_delete_editor_temporary_folder`, `storage_delete_editor_print_files`, `review_max_uploads`, `single_item_price`, `product_list_show_short_description`, `product_show_short_description`, `additional_admin_invoice_email`, `additional_admin_delivery_note_email`,`admin_show_product_demo_files`, `display_checkout_button`, `show_shipping_costs_in_cart`,`pdf_display_extra_fields`,`hide_extra_fields_images`,`mail_display_extra_fields`,`order_status_for_return`) VALUES
(1, 12, 3, 4, 115, 100, 115, 100, 115, 100, 259, 290, 395, 440, 260, 240, 100, 100, 300, 300, 'en-GB', 'en-GB', 1, 2, ',', '.', 2, 1, 0, 1, '1', '%storename %address %city %zip', '%d.%m.%Y', 'test@test.com', 0, 0, 0, 0, 10, '-1.00', 0, 0, '208::208:30', 699, 1, 0, 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 1, '9c75e8a00e9281cdb49391105ae76355', 0, 0, 0, 0, 0, 0, 0, 0, 'd2FuZG1hbm4uZGU=', 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 0, 1, 0, 0, 1, 0, 0, 3, 0, 'a:3:{s:8:\"register\";a:17:{s:5:\"title\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"f_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"l_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"client_type\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"firma_name\";a:1:{s:7:\"display\";s:1:\"1\";}s:10:\"tax_number\";a:1:{s:7:\"display\";s:1:\"1\";}s:6:\"street\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:9:\"street_nr\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:3:\"zip\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:4:\"city\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"country\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:5:\"phone\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"mobil_phone\";a:1:{s:7:\"display\";s:1:\"1\";}s:3:\"fax\";a:1:{s:7:\"display\";s:1:\"1\";}s:5:\"email\";a:2:{s:7:\"require\";i:1;s:7:\"display\";i:1;}s:8:\"password\";a:2:{s:7:\"require\";i:1;s:7:\"display\";i:1;}s:10:\"password_2\";a:2:{s:7:\"require\";i:1;s:7:\"display\";i:1;}}s:7:\"address\";a:15:{s:5:\"title\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"f_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"l_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"client_type\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"firma_name\";a:1:{s:7:\"display\";s:1:\"1\";}s:10:\"tax_number\";a:1:{s:7:\"display\";s:1:\"1\";}s:6:\"street\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:9:\"street_nr\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:3:\"zip\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:4:\"city\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"country\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:5:\"phone\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"mobil_phone\";a:1:{s:7:\"display\";s:1:\"1\";}s:3:\"fax\";a:1:{s:7:\"display\";s:1:\"1\";}s:5:\"email\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}}s:11:\"editaccount\";a:0:{}}', 'base', 0, 1, 1, 1, 3, 1, 4, 0, 0, 1, 'a:0:{}', 'a:0:{}', 'a:0:{}', 81, 0, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 'a:67:{s:17:\"cart_back_to_shop\";s:4:\"list\";s:32:\"product_button_back_use_end_list\";s:1:\"0\";s:21:\"display_tax_id_in_pdf\";s:1:\"0\";s:13:\"image_quality\";s:3:\"100\";s:16:\"image_fill_color\";s:8:\"16777215\";s:26:\"product_price_qty_discount\";s:1:\"2\";s:16:\"rating_starparts\";s:1:\"2\";s:31:\"show_list_price_shipping_weight\";s:1:\"0\";s:23:\"product_price_precision\";s:1:\"2\";s:26:\"cart_decimal_qty_precision\";s:1:\"2\";s:25:\"default_frontend_currency\";s:1:\"0\";s:27:\"product_file_upload_via_ftp\";s:1:\"0\";s:25:\"product_file_upload_count\";s:1:\"1\";s:26:\"product_image_upload_count\";s:2:\"10\";s:26:\"product_video_upload_count\";s:1:\"3\";s:33:\"show_insert_code_in_product_video\";s:1:\"0\";s:29:\"max_number_download_sale_file\";s:1:\"3\";s:26:\"max_day_download_sale_file\";s:3:\"365\";s:34:\"order_display_new_digital_products\";s:1:\"1\";s:24:\"display_user_groups_info\";s:1:\"0\";s:18:\"display_user_group\";s:1:\"0\";s:47:\"display_delivery_time_for_product_in_order_mail\";s:1:\"1\";s:11:\"load_jquery\";s:1:\"0\";s:19:\"load_jquery_version\";s:5:\"1.6.2\";s:20:\"load_jquery_lightbox\";s:1:\"1\";s:15:\"load_javascript\";s:1:\"1\";s:8:\"load_css\";s:1:\"1\";s:3:\"tax\";s:1:\"1\";s:5:\"stock\";s:1:\"1\";s:18:\"show_delivery_date\";s:1:\"1\";s:27:\"show_delivery_time_checkout\";s:1:\"1\";s:25:\"show_manufacturer_in_cart\";s:1:\"0\";s:17:\"weight_in_invoice\";s:1:\"1\";s:19:\"shipping_in_invoice\";s:1:\"1\";s:18:\"payment_in_invoice\";s:1:\"1\";s:23:\"date_invoice_in_invoice\";s:1:\"1\";s:21:\"send_invoice_manually\";s:1:\"0\";s:11:\"display_agb\";s:1:\"1\";s:21:\"cart_basic_price_show\";s:1:\"0\";s:8:\"step_4_3\";s:1:\"0\";s:22:\"user_number_in_invoice\";s:1:\"1\";s:25:\"return_policy_for_product\";s:1:\"0\";s:13:\"no_return_all\";s:1:\"0\";s:23:\"tax_on_delivery_address\";s:1:\"1\";s:49:\"list_products_calc_basic_price_from_product_price\";s:1:\"0\";s:21:\"hide_from_basic_price\";s:1:\"0\";s:35:\"calc_basic_price_from_product_price\";s:1:\"0\";s:38:\"user_discount_not_apply_prod_old_price\";s:1:\"0\";s:6:\"advert\";s:1:\"0\";s:30:\"count_products_to_page_tophits\";s:2:\"12\";s:32:\"count_products_to_page_toprating\";s:2:\"12\";s:28:\"count_products_to_page_label\";s:2:\"12\";s:33:\"count_products_to_page_bestseller\";s:2:\"12\";s:29:\"count_products_to_page_random\";s:2:\"12\";s:27:\"count_products_to_page_last\";s:2:\"12\";s:29:\"count_products_to_row_tophits\";s:1:\"3\";s:31:\"count_products_to_row_toprating\";s:1:\"3\";s:27:\"count_products_to_row_label\";s:1:\"3\";s:32:\"count_products_to_row_bestseller\";s:1:\"3\";s:28:\"count_products_to_row_random\";s:1:\"3\";s:26:\"count_products_to_row_last\";s:1:\"3\";s:29:\"display_short_descr_multiline\";s:1:\"0\";s:25:\"count_manufacturer_to_row\";s:1:\"2\";s:31:\"attribut_dep_sorting_in_product\";s:16:\"V.value_ordering\";s:33:\"attribut_nodep_sorting_in_product\";s:16:\"V.value_ordering\";s:38:\"show_return_policy_text_in_email_order\";s:1:\"1\";s:30:\"show_return_policy_text_in_pdf\";s:1:\"1\";}', 'a:0:{}', 0, 1, 0, 0, 0, 0, 'FN-', 12, 12, 4, 1, 1, 1, 1, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 'header.jpg', 'footer.jpg', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, 1, 0, 0, '', '', '', ''),
(2, 12, 3, 4, 115, 100, 115, 100, 115, 100, 259, 290, 395, 440, 260, 240, 100, 100, 300, 300, 'de-DE', 'de-DE', 1, 2, ',', '.', 2, 1, 0, 1, '1', '%storename %address %city %zip', '%d.%m.%Y', '', 0, 0, 0, 0, 10, '-1.00', 0, 0, '208::208:30', 103, 1, 0, 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 1, '394f66f59a12c93ec42df845a5e68354', 0, 0, 0, 0, 0, 0, 0, 0, 'd2FuZG1hbm4uZGU=', 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 0, 1, 0, 0, 1, 0, 0, 3, 0, 'a:4:{s:8:\"register\";a:17:{s:5:\"title\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"f_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"l_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"client_type\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"firma_name\";a:1:{s:7:\"display\";s:1:\"1\";}s:10:\"tax_number\";a:1:{s:7:\"display\";s:1:\"1\";}s:6:\"street\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:9:\"street_nr\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:3:\"zip\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:4:\"city\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"country\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:5:\"phone\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"mobil_phone\";a:1:{s:7:\"display\";s:1:\"1\";}s:3:\"fax\";a:1:{s:7:\"display\";s:1:\"1\";}s:8:\"password\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"password_2\";a:2:{s:7:\"require\";i:1;s:7:\"display\";i:1;}}s:7:\"address\";a:25:{s:5:\"title\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"f_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"l_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"client_type\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"firma_name\";a:1:{s:7:\"display\";s:1:\"1\";}s:10:\"tax_number\";a:1:{s:7:\"display\";s:1:\"1\";}s:5:\"email\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"street\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:9:\"street_nr\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:3:\"zip\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:4:\"city\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"country\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:5:\"phone\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"mobil_phone\";a:1:{s:7:\"display\";s:1:\"1\";}s:3:\"fax\";a:1:{s:7:\"display\";s:1:\"1\";}s:7:\"d_title\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:8:\"d_f_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:8:\"d_l_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:12:\"d_firma_name\";a:1:{s:7:\"display\";s:1:\"1\";}s:8:\"d_street\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:5:\"d_zip\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"d_city\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"d_state\";a:1:{s:7:\"display\";s:1:\"1\";}s:9:\"d_country\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"d_phone\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}}s:11:\"editaccount\";a:24:{s:5:\"title\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"f_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"l_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"client_type\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"firma_name\";a:1:{s:7:\"display\";s:1:\"1\";}s:10:\"tax_number\";a:1:{s:7:\"display\";s:1:\"1\";}s:5:\"email\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"street\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:3:\"zip\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:4:\"city\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"country\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:5:\"phone\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"mobil_phone\";a:1:{s:7:\"display\";s:1:\"1\";}s:3:\"fax\";a:1:{s:7:\"display\";s:1:\"1\";}s:7:\"d_title\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:8:\"d_f_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:8:\"d_l_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:12:\"d_firma_name\";a:1:{s:7:\"display\";s:1:\"1\";}s:8:\"d_street\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:5:\"d_zip\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:6:\"d_city\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"d_state\";a:1:{s:7:\"display\";s:1:\"1\";}s:9:\"d_country\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"d_phone\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}}s:6:\"sender\";a:12:{s:9:\"s_a_title\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"s_a_f_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"s_a_l_name\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:14:\"s_a_firma_name\";a:1:{s:7:\"display\";s:1:\"1\";}s:9:\"s_a_email\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:10:\"s_a_street\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:13:\"s_a_street_nr\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"s_a_zip\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:8:\"s_a_city\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:11:\"s_a_country\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:9:\"s_a_phone\";a:2:{s:7:\"display\";s:1:\"1\";s:7:\"require\";s:1:\"1\";}s:7:\"s_a_fax\";a:1:{s:7:\"display\";s:1:\"1\";}}}', 'base', 0, 1, 1, 1, 3, 1, 4, 0, 0, 1, 'a:0:{}', 'a:0:{}', 'a:0:{}', 81, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 'a:67:{s:17:\"cart_back_to_shop\";s:4:\"list\";s:32:\"product_button_back_use_end_list\";s:1:\"0\";s:21:\"display_tax_id_in_pdf\";s:1:\"0\";s:13:\"image_quality\";s:3:\"100\";s:16:\"image_fill_color\";s:8:\"16777215\";s:26:\"product_price_qty_discount\";s:1:\"2\";s:16:\"rating_starparts\";s:1:\"2\";s:31:\"show_list_price_shipping_weight\";s:1:\"0\";s:23:\"product_price_precision\";s:1:\"2\";s:26:\"cart_decimal_qty_precision\";s:1:\"2\";s:25:\"default_frontend_currency\";s:1:\"0\";s:27:\"product_file_upload_via_ftp\";s:1:\"0\";s:25:\"product_file_upload_count\";s:1:\"1\";s:26:\"product_image_upload_count\";s:2:\"10\";s:26:\"product_video_upload_count\";s:1:\"3\";s:33:\"show_insert_code_in_product_video\";s:1:\"0\";s:29:\"max_number_download_sale_file\";s:1:\"3\";s:26:\"max_day_download_sale_file\";s:3:\"365\";s:34:\"order_display_new_digital_products\";s:1:\"1\";s:24:\"display_user_groups_info\";s:1:\"0\";s:18:\"display_user_group\";s:1:\"0\";s:47:\"display_delivery_time_for_product_in_order_mail\";s:1:\"1\";s:11:\"load_jquery\";s:1:\"0\";s:19:\"load_jquery_version\";s:5:\"1.6.2\";s:20:\"load_jquery_lightbox\";s:1:\"1\";s:15:\"load_javascript\";s:1:\"1\";s:8:\"load_css\";s:1:\"1\";s:3:\"tax\";s:1:\"1\";s:5:\"stock\";s:1:\"1\";s:18:\"show_delivery_date\";s:1:\"1\";s:27:\"show_delivery_time_checkout\";s:1:\"1\";s:25:\"show_manufacturer_in_cart\";s:1:\"0\";s:17:\"weight_in_invoice\";s:1:\"1\";s:19:\"shipping_in_invoice\";s:1:\"1\";s:18:\"payment_in_invoice\";s:1:\"1\";s:23:\"date_invoice_in_invoice\";s:1:\"1\";s:21:\"send_invoice_manually\";s:1:\"0\";s:11:\"display_agb\";s:1:\"1\";s:21:\"cart_basic_price_show\";s:1:\"0\";s:8:\"step_4_3\";s:1:\"0\";s:22:\"user_number_in_invoice\";s:1:\"1\";s:25:\"return_policy_for_product\";s:1:\"0\";s:13:\"no_return_all\";s:1:\"0\";s:23:\"tax_on_delivery_address\";s:1:\"1\";s:49:\"list_products_calc_basic_price_from_product_price\";s:1:\"0\";s:21:\"hide_from_basic_price\";s:1:\"0\";s:35:\"calc_basic_price_from_product_price\";s:1:\"0\";s:38:\"user_discount_not_apply_prod_old_price\";s:1:\"0\";s:6:\"advert\";s:1:\"0\";s:30:\"count_products_to_page_tophits\";s:2:\"12\";s:32:\"count_products_to_page_toprating\";s:2:\"12\";s:28:\"count_products_to_page_label\";s:2:\"12\";s:33:\"count_products_to_page_bestseller\";s:2:\"12\";s:29:\"count_products_to_page_random\";s:2:\"12\";s:27:\"count_products_to_page_last\";s:2:\"12\";s:29:\"count_products_to_row_tophits\";s:1:\"3\";s:31:\"count_products_to_row_toprating\";s:1:\"3\";s:27:\"count_products_to_row_label\";s:1:\"3\";s:32:\"count_products_to_row_bestseller\";s:1:\"3\";s:28:\"count_products_to_row_random\";s:1:\"3\";s:26:\"count_products_to_row_last\";s:1:\"3\";s:29:\"display_short_descr_multiline\";s:1:\"0\";s:25:\"count_manufacturer_to_row\";s:1:\"2\";s:31:\"attribut_dep_sorting_in_product\";s:16:\"V.value_ordering\";s:33:\"attribut_nodep_sorting_in_product\";s:16:\"V.value_ordering\";s:38:\"show_return_policy_text_in_email_order\";s:1:\"1\";s:30:\"show_return_policy_text_in_pdf\";s:1:\"1\";}', 'a:0:{}', 0, 1, 0, 0, 0, 1, 'FN-', 12, 12, 4, 1, 1, 1, 1, NULL, NULL, 0, 0, 0, 0, NULL, NULL, NULL, 0, 0, 0, 'header_default.jpg', 'footer_default.jpg', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, 1, 0, 0, '', '', '', '');

CREATE TABLE `#__jshopping_config_fields` ( 
	`id` SERIAL, 
	`name` varchar(50) NOT NULL, 
	`display` tinyint(4) NOT NULL DEFAULT '3' COMMENT '1 - register, 2 - address, 3 - both',
	`require` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 - register, 2 - address, 3 - both',
	`sorting` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_config_fields` (`id`, `name`, `display`, `require`, `sorting`) VALUES
	(1, 'title', 3, 0, 1),
	(2, 'f_name', 3, 1, 2),
	(3, 'l_name', 3, 1, 3),
	(4, 'm_name', 3, 0, 4),
	(5, 'client_type', 3, 0, 5),
	(6, 'firma_name', 3, 0, 6),
	(7, 'firma_code', 3, 0, 7),
	(8, 'tax_number', 3, 0, 8),
	(9, 'birthday', 3, 0, 9),
	(10, 'home', 3, 0, 10),
	(11, 'apartment', 3, 0, 11),
	(12, 'street', 3, 1, 12),
	(13, 'street_nr', 3, 0, 13),
	(14, 'zip', 3, 1, 14),
	(15, 'city', 3, 1, 15),
	(16, 'state', 3, 0, 16),
	(17, 'country', 3, 0, 17),
	(18, 'phone', 3, 0, 18),
	(19, 'mobil_phone', 3, 0, 19),
	(20, 'fax', 3, 0, 20),
	(21, 'ext_field_1', 3, 0, 21),
	(22, 'ext_field_2', 3, 0, 22),
	(23, 'ext_field_3', 3, 0, 23),
	(24, 'privacy_statement', 0, 0, 24);
	
CREATE TABLE `#__jshopping_config_display_prices` (
  `id` int(11) NOT NULL,
  `zones` text NOT NULL,
  `display_price` tinyint(1) NOT NULL,
  `display_price_firma` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_config_seo` (
  `id` int(11) NOT NULL,
  `alias` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `title_de-DE` varchar(255) NOT NULL,
  `keyword_de-DE` text NOT NULL,
  `description_de-DE` text NOT NULL,
  `title_en-GB` varchar(255) NOT NULL,
  `keyword_en-GB` text NOT NULL,
  `description_en-GB` text NOT NULL,
  `title_es-ES` varchar(255) NOT NULL,
  `keyword_es-ES` text NOT NULL,
  `description_es-ES` text NOT NULL,
  `title_it-IT` varchar(255) NOT NULL,
  `keyword_it-IT` text NOT NULL,
  `description_it-IT` text NOT NULL,
  `title_fr-FR` varchar(255) NOT NULL,
  `keyword_fr-FR` text NOT NULL,
  `description_fr-FR` text NOT NULL,
  `title_nl-NL` varchar(255) NOT NULL,
  `keyword_nl-NL` text NOT NULL,
  `description_nl-NL` text NOT NULL,
  `title_pl-PL` varchar(255) NOT NULL,
  `keyword_pl-PL` text NOT NULL,
  `description_pl-PL` text NOT NULL,
  `title_ru-RU` varchar(255) NOT NULL,
  `keyword_ru-RU` text NOT NULL,
  `description_ru-RU` text NOT NULL,
  `title_sv-SE` varchar(255) NOT NULL,
  `keyword_sv-SE` text NOT NULL,
  `description_sv-SE` text NOT NULL,
  `robots_de-DE` int(11) NOT NULL DEFAULT '0',
  `robots_en-GB` int(11) NOT NULL DEFAULT '0',
  `robots_fr-FR` int(11) NOT NULL DEFAULT '0',
  `robots_it-IT` int(11) NOT NULL DEFAULT '0',
  `robots_nl-NL` int(11) NOT NULL DEFAULT '0',
  `robots_pl-PL` int(11) NOT NULL DEFAULT '0',
  `robots_ru-RU` int(11) NOT NULL DEFAULT '0',
  `title_fr-CA` varchar(255) NOT NULL,
  `keyword_fr-CA` text NOT NULL,
  `description_fr-CA` text NOT NULL,
  `robots_fr-CA` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_config_seo` (`id`, `alias`, `ordering`, `title_de-DE`, `keyword_de-DE`, `description_de-DE`, `title_en-GB`, `keyword_en-GB`, `description_en-GB`, `title_es-ES`, `keyword_es-ES`, `description_es-ES`, `title_it-IT`, `keyword_it-IT`, `description_it-IT`, `title_fr-FR`, `keyword_fr-FR`, `description_fr-FR`, `title_nl-NL`, `keyword_nl-NL`, `description_nl-NL`, `title_pl-PL`, `keyword_pl-PL`, `description_pl-PL`, `title_ru-RU`, `keyword_ru-RU`, `description_ru-RU`, `title_sv-SE`, `keyword_sv-SE`, `description_sv-SE`, `robots_de-DE`, `robots_en-GB`, `robots_fr-FR`, `robots_it-IT`, `robots_nl-NL`, `robots_pl-PL`, `robots_ru-RU`, `title_fr-CA`, `keyword_fr-CA`, `description_fr-CA`, `robots_fr-CA`) VALUES
(1, 'category', 10, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(2, 'manufacturers', 20, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(3, 'cart', 30, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(4, 'wishlist', 40, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(5, 'login', 50, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(6, 'register', 60, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(7, 'editaccount', 70, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(8, 'myorders', 80, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(9, 'myaccount', 90, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(10, 'search', 100, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(11, 'search-result', 110, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(12, 'myorder-detail', 120, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(13, 'vendors', 130, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(14, 'content-agb', 140, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(15, 'content-return_policy', 150, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(16, 'content-shipping', 160, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(17, 'checkout-address', 170, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(18, 'checkout-payment', 180, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(19, 'checkout-shipping', 190, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(20, 'checkout-preview', 200, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(21, 'lastproducts', 210, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(22, 'randomproducts', 220, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(23, 'bestsellerproducts', 230, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(24, 'labelproducts', 240, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(25, 'topratingproducts', 250, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(26, 'tophitsproducts', 260, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(27, 'all-products', 270, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0),
(28, 'content-privacy_statement', 161, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0);

CREATE TABLE `#__jshopping_config_statictext` (
  `id` int(11) NOT NULL,
  `alias` varchar(64) NOT NULL,
  `text_de-DE` text NOT NULL,
  `text_en-GB` text NOT NULL,
  `text_es-ES` text NOT NULL,
  `text_it-IT` text NOT NULL,
  `text_fr-FR` text NOT NULL,
  `text_nl-NL` text NOT NULL,
  `text_pl-PL` text NOT NULL,
  `text_ru-RU` text NOT NULL,
  `text_sv-SE` text NOT NULL,
  `use_for_return_policy` int(11) NOT NULL,
  `text_fr-CA` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_config_statictext` (`id`, `alias`, `text_de-DE`, `text_en-GB`, `text_es-ES`, `text_it-IT`, `text_fr-FR`, `text_nl-NL`, `text_pl-PL`, `text_ru-RU`, `text_sv-SE`, `use_for_return_policy`, `text_fr-CA`) VALUES
(1, 'home', '', '', '', '', '', '', '', '', '', 0, ''),
(2, 'manufacturer', '', '', '', '', '', '', '', '', '', 0, '');
INSERT INTO `#__jshopping_config_statictext` (`id`, `alias`, `text_de-DE`, `text_en-GB`, `text_es-ES`, `text_it-IT`, `text_fr-FR`, `text_nl-NL`, `text_pl-PL`, `text_ru-RU`, `text_sv-SE`, `use_for_return_policy`, `text_fr-CA`) VALUES
(3, 'agb', '<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Dies sind keine rechtsverbindlichen AGB\'s.</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Sie dienen rein zu Demonstrationszwecken der </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Eine rechtliche Verpflichtung kann nicht abgeleitet werden.</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"> </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">**************************************</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">AGB</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Allgemeine Geschäftsbedingungen der<br /> GmbH für Warenlieferungen<br />Im weiteren  genannt<br />Stand: Februar 2009<br /><br />§ 1 Allgemeines – Geltungsbereich<br />(1)    Die nachstehenden Allgemeinen Geschäftsbedingungen (kurz: AGB) gelten für alle Lieferungen, Leistungen und Angebote der  an den Kunden. Kunde i. S. der Geschäftsbeziehungen sind Unternehmer.<br />(2)    Abweichende, entgegenstehende oder ergänzende Allgemeine Geschäfts-bedingungen des Kunden werden auch ohne ausdrücklichen Widerspruch nicht Vertragsbestandteil, es sei denn, ihrer Geltung wird ausdrücklich schriftlich zugestimmt. Sie werden auch dann nicht Vertragsinhalt, wenn die  in Kenntnis solcher Kundenbedingungen die Warenlieferung vorbehaltlos ausführt. Dies gilt auch für alle künftigen Warenlieferungen der  an den Kunden.<br />(3)    Falls die AGB dem Kunden nicht mit dem jeweiligen Angebot der  zugegangen sind oder bei anderer Gelegenheit vor oder bei Abschluss des jeweiligen Vertrages übergeben wurden, finden sie dennoch Anwendung, wenn der Käufer sie aus einer früheren oder anderen Geschäftsbeziehung kannte oder kennen musste.<br />(4)    Die  ist berechtigt, Informationen und Daten über den Kunden zu erheben, zu speichern, zu verarbeiten, zu nutzen und an Dritte insbesondere zum Zwecke des Forderungseinzugs oder des ausgelagerten Debitoren-managements zur Speicherung, Verarbeitung und Nutzung weiterzugeben.<br />     <br />§ 2 Vertragsschluss<br />(1)    Angebote der  sind – insbesondere hinsichtlich der Preise, Menge, Lieferfrist, Liefermöglichkeiten und Nebenleistungen - freibleibend. Technische Änderungen sowie Änderungen in Form, Farbe und/oder Gewicht bleiben im Rahmen des Zumutbaren vorbehalten. Die ausdrückliche Zusicherung von Eigenschaften bedarf der schriftlichen Bestätigung der .<br />(2)    Der Umfang der von der  zu erbringenden Leistungen wird allein durch die schriftlichen Verträge festgelegt.<br />(3)        Mit der Bestellung einer Ware erklärt der Kunde verbindlich, die bestellte Ware erwerben zu wollen. Die  ist berechtigt, das in der Bestellung liegende Vertragsangebot innerhalb von zwei Wochen nach Eingang bei der  anzunehmen. Die Annahme kann entweder schriftlich (auch durch Rechnung oder Lieferschein) oder durch Auslieferung der Ware an den Kunden erklärt werden.<br />(4)    Bestellt der Kunde die Ware auf elektronischem Wege, wird die  den Zugang der Bestellung unverzüglich bestätigen. Die Zugangsbestätigung stellt noch keine verbindliche Annahme der Bestellung dar. Die Zugangsbestätigung kann mit der Annahmeerklärung verbunden werden. Bei Bestellung auf elektronischem Wege wird der Vertragstext der  gespeichert und dem Kunden auf Verlangen nebst den vorliegenden AGB per E-Mail zugesandt.<br />(5)    Feste Lieferfristen bestehen nicht. Sofern abweichend hiervon ein fester Liefertermin vereinbart ist, hat der Kunde im Falle des Verzugs der Lieferung eine angemessene Nachfrist von in der Regel vier Wochen zu setzen. Der Vertragsschluss erfolgt unter dem Vorbehalt der richtigen und rechtzeitigen Selbstbelieferung durch Zulieferer der . Dies gilt nur für den Fall, dass die Nichtlieferung nicht von der  zu vertreten ist, insbesondere bei Abschluss eines kongruenten Deckungsgeschäftes mit dem Zulieferer der . Aufgrund von Kapazitätsengpässen ist nicht auszuschließen, dass die von der  angebotenen oder bei der  bestellten Produkte nicht zur vorgesehenen Lieferzeit – ggf. für längere Dauer – nicht verfügbar sind. Die Angebote und Auftragsbestätigungen stehen deshalb unter dem Vorbehalt der Produktverfügbarkeit. Über eine nachhaltige Nichtverfügbarkeit der Leistung wird der Kunde unverzüglich informiert. Die Gegenleistung wird zurückerstattet.<br />   <br />§ 3 Höhere Gewalt<br />Im Falle höherer Gewalt und aller sonst von der  nicht zu vertretender Hindernisse verlängern sich die Liefer- und Leistungsfristen angemessen. Wird infolge der Störung die Lieferung und/oder Abnahme um mehr als acht Wochen überschritten, so sind beide Teile zum Rücktritt berechtigt. Bei teilweisem oder vollständigem Wegfall der Bezugsquellen ist die  nicht verpflichtet, Ersatz bei fremden Vorlieferanten zu beschaffen. In diesem Fall ist die  berechtigt, die verfügbaren Warenmengen unter Berücksichtigung eines evtl. Eigenbedarfs zu verteilen.<br />   <br />§ 4 Versand<br />Die  behält sich die Wahl des Versandweges und der Versandart vor. Durch besondere Versandwünsche des Kunden verursachte Mehrkosten gehen zu dessen Lasten. Das gleiche gilt für nach Vertragsabschluß eintretende Erhöhungen der Frachtsätze, etwaige Mehrkosten für Umleitung, Lagerkosten etc., sofern nicht frachtfreie Lieferung vereinbart ist.<br />   <br />§ 5 Eigentumsvorbehalt<br />(1)    Die  behält sich das Eigentum an der Ware bis zur vollständigen Begleichung aller Forderungen aus einer laufenden Geschäftsbeziehung vor, einschließlich Nebenforderungen, Schadensersatzansprüchen und Einlösungen von Schecks. Der Eigentumsvorbehalt bleibt auch dann bestehen, wenn einzelne Forderungen in eine laufende Rechnung aufgenommen werden und der Saldo gezogen und anerkannt ist.<br />(2)    Der Kunde ist verpflichtet, der  einen Zugriff Dritter auf die Ware, etwa im Wege einer Pfändung, sowie etwaige Beschädigungen oder die Vernichtung der Ware unverzüglich mitzuteilen. Einen Besitzwechsel der Ware sowie den eigenen Wohnsitzwechsel hat der Kunde unverzüglich anzuzeigen.<br />(3)    Die  ist berechtigt, bei vertragswidrigem Verhalten des Kunden, insbesondere bei Zahlungsverzug oder bei Verletzung einer Pflicht nach Ziffer 2 dieser Bestimmung vom Vertrag zurückzutreten und die Ware heraus-zuverlangen.<br /> <br />Die Geltendmachung des Eigentumsvorbehalts stellt allein allerdings noch keine solche Rücktrittserklärung dar. Eine etwaige Warenrücknahme erfolgt immer nur sicherheitshalber; es liegt darin, auch wenn nachträglich Teilzahlungen gestattet wurden, kein Rücktritt vom Vertrag.<br />(4)    Der Kunde ist berechtigt, die Waren im ordentlichen Geschäftsgang weiterzuveräußern. Er tritt der  bereits jetzt alle Forderungen in Höhe des Rechnungsbetrages ab, die ihm durch die Weiterveräußerung gegen einen Dritten erwachsen. Die  nimmt die Abtretung an. Nach der Abtretung ist der Kunde zur Einziehung der Forderung ermächtigt. Die  behält sich vor, die Forderung selbst einzuziehen, sobald der Kunde seinen Zahlungsverpflichtungen nicht ordnungsgemäß nachkommt und in Zahlungsverzug gerät.<br />Erscheint der  die Verwirklichung ihrer Ansprüche als gefährdet, hat der Unternehmer auf eine entsprechende Aufforderung durch die  die Abtretung seinen Abnehmern mitzuteilen und der  alle erforderlichen Auskünfte und Unterlagen zur Verfügung zu stellen, die zur unmittelbaren Durchsetzung ihrer Rechte erforderlich sind.<br />(5)    Die Be- und Verarbeitung der Ware durch den Kunden erfolgt stets im Namen und Auftrag der . Erfolgt eine Verarbeitung mit der  nicht gehörenden Gegenständen, so erwirbt die  an der neuen Sache das Miteigentum im Verhältnis des Rechnungswerts der von der  gelieferten Ware zum Rechnungswert der sonstigen verarbeiteten Gegenstände.<br />Dasselbe gilt, wenn die Ware mit anderen, der  nicht gehörenden Gegenständen vermischt wird.<br />(6)    Übersteigt der Wert der  zustehenden Sicherheiten die zu sichernden Forderungen gegen den Kunden um mehr als 20%, so ist die  auf Verlangen des Kunden insoweit zur Freigabe von Sicherheiten verpflichtet. Die Auswahl der freizugebenden Sicherheiten erfolgt durch die .<br /> <br />§ 6 Vergütung<br />(1)    Der angebotene Kaufpreis ist der Nettopreis ab Lager. Die Versandkosten werden nach tatsächlichem Aufwand abgerechnet. Der Kunde kann den Kaufpreis per Nachnahme oder Rechnung leisten.<br />(2)    Die Zahlung hat innerhalb von 30 Tagen ab Rechnungsdatum ohne Abzug oder binnen 8 Tagen ab Rechnungsdatum mit 2 % Skonto zu erfolgen. Nach Ablauf von 30 Tagen ab Rechnungsdatum kommt der Kunde in Zahlungsverzug.<br />Der Kunde hat während des Verzugs die Geldschuld in Höhe von 8 % über dem Basiszinssatz zu verzinsen. Gegenüber dem Kunden behält sich  vor, einen höheren Verzugsschaden nachzuweisen und geltend zu machen.<br />(3)    Schecks werden nur erfüllungshalber unter Vorbehalt jederzeitiger Rückgabe und unter Ausschluss jeder Haftung für ordnungsgemäße Vorlage oder Protesterhebung hereingenommen. Schecks gelten erst nach vorbehaltloser Gutschrift durch die Bank der  als Zahlung.<br />(4)    Der Kunde darf nur mit unbestrittenen oder rechtskräftigen Forderungen aufrechnen bzw. diese mit Forderungen der  verrechnen. Zurückbehaltungsrechte darf der Kunde nur ausüben, wenn sein Gegenanspruch unbestritten oder rechtskräftig festgestellt ist.<br /> <br />§ 7 Gefahrübergang<br />(1)    Sämtliche Vereinbarungen zwischen der  und dem Kunden bezüglich der Beschaffenheit der von der  zu liefernden Waren sowie sämtliche sonstigen auf die Beschaffenheit dieser Waren bezogenen Erklärungen der  stellen keine Garantie gemäß § 433 BGB dar, es sei denn die  hat gegenüber dem Kunden eine gesonderte schriftliche Erklärung abgegeben, in der sie eine solche Garantie ausdrücklich übernimmt. Dasselbe gilt im Hinblick auf die Übernahme eines Beschaffungsrisikos durch die .<br />(2)    Alle Lieferungen erfolgen auf Kosten und Gefahr des Kunden.<br />(3)    Der Übergabe steht es gleich, wenn der Käufer im Verzug der Annahme ist.<br /><br />§ 8 Gewährleistung<br />(1)    Die Beschaffenheit der von der  zu liefernden Waren ergibt sich ausschließlich entweder aus den entsprechenden Vereinbarungen zwischen der  und dem Kunden oder aus den in § 434 Abs. 1 Satz 2 BGB genannten Umständen unter Ausschluß der in § 434 Abs. 1 Satz 3 BGB genannten Umständen. Muster und Proben der von der  zu liefernden Waren dienen nur der ungefähren Beschreibung dieser Waren.<br />(2)    Die  leistet für Mängel der Ware zunächst nach ihrer Wahl Gewähr durch Nachbesserung oder Ersatzlieferung.<br />(3)    Schlägt die Nacherfüllung fehl, kann der Kunde nur nach seiner Wahl Herabsetzung der Vergütung (Minderung) oder Rückgängigmachung des Vertrages (Rücktritt) verlangen. Bei nur geringfügiger Vertragswidrigkeit, ins- besondere bei nur geringfügigen Mängeln, steht dem Kunden jedoch kein Rücktrittsrecht zu.<br />(4)    Der Kunde muss der  offensichtliche Mängel unter Angabe von Rechnungsnummer, Produktnamen, Abmessung, Chargennummer, Materialmenge und Fehlerbeschreibung unverzüglich, spätestens innerhalb einer Frist von einer Woche ab Empfang der Ware schriftlich anzeigen; anderenfalls ist die Geltendmachung des Gewährleistungsanspruches ausgeschlossen. Zur Fristwahrung genügt die rechtzeitige Absendung. Den Kunden trifft die volle Beweislast für sämtliche Anspruchsvoraussetzungen, insbesondere für den Mangel selbst, für den Zeitpunkt der Feststellung des Mangels und für die Rechtzeitigkeit der Mängelrüge. Die  behält sich bis zum Abschluss der Reklamationsbearbeitung vor, das reklamierte Material zwecks Laborprüfungen vom Kunden zurückzufordern.<br />(5)    Wählt der Kunde wegen eines Rechts- oder Sachmangels nach gescheiterter Nacherfüllung den Rücktritt vom Vertrag, steht ihm daneben kein Schadens-ersatzanspruch wegen des Mangels zu. Wählt der Kunde nach gescheiterter Nacherfüllung Schadensersatz, verbleibt die Ware beim Kunden, wenn ihm dies zumutbar ist.<br />Der Schadensersatz beschränkt sich auf die Differenz zwischen Kaufpreis und Wert der mangelhaften Sache. Dies gilt nicht, wenn die  die Vertragsverletzung arglistig verursacht haben.<br />(6)    Die Gewährleistungsfrist beträgt ein Jahr ab Ablieferung der Ware. Bei gebrauchten Sachen beträgt die Verjährungsfrist ein Jahr ab Ablieferung der Ware. Dies gilt nicht, wenn der Kunde der  den Mangel nicht rechtzeitig angezeigt hat (Ziffer 4 dieser Bestimmung).<br />(7)    Als Beschaffenheit der Ware gilt grundsätzlich die Produktbeschreibung des Herstellers als vereinbart. Öffentliche Äußerungen, Anpreisungen oder Werbung des Herstellers stellen daneben keine vertragsgemäße Beschaffenheitsangabe der Ware dar.<br />(8)    Garantien im Rechtssinne erhält der Kunde durch die  nicht. Herstellergarantien bleiben hiervon unberührt.<br /> <br />§ 10 Haftungsbeschränkungen<br />(1)    Bei einer leicht fahrlässigen Pflichtverletzung beschränkt sich die Haftung der  auf den nach der Art der Ware vorhersehbaren, vertragstypischen, unmittelbaren Durchschnittsschaden. Dies gilt auch bei leicht fahrlässigen Pflichtverletzungen der gesetzlichen Vertreter oder Erfüllungsgehilfen der . Schadensersatzansprüche des Kunden – auch außervertraglicher Art – sind im Falle leicht fahrlässiger Pflichtverletzung durch die gesetzlichen Vertreter und andere Erfüllungsgehilfen der  ausgeschlossen, es sei denn, dass die Verletzung eine Pflicht betrifft, die für die Erreichung des Vertragszweckes von wesentlicher Bedeutung ist.<br />(2)    Die vorstehenden Haftungsbeschränkungen betreffen nicht Ansprüche des Kunden aus Produkthaftung. Weiter gelten die Haftungsbeschränkungen nicht bei unzurechenbaren Körper- oder Gesundheitsschäden oder bei Verlust des Lebens des Kunden.<br />(3)    Schadensersatzansprüche des Kunden wegen eines Mangels verjähren nach einem Jahr ab Ablieferung der Ware. Dies gilt nicht, wenn der  Arglist vorwerfbar ist.  <br /> <br />§ 11 Schlussbestimmungen<br />(1)    Gerichtsstand für alle Streitigkeiten ist der Geschäftssitz der . Auch dann, wenn zum Zeitpunkt der Klageerhebung durch die  der Kunde keinen allgemeinen Gerichtsstand in der Bundesrepublik Deutschland hat oder der Sitz oder Wohnsitz oder gewöhnlicher Aufenthalt nicht bekannt ist, ist Gerichtsstand der Sitz der . Erfüllungsort ist Vlotho.<br />(2)    Für die Vertragsbeziehungen der Parteien gilt ausschließlich das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts-übereinkommens vom 11.04.1980.<br />Die Regelungen der UN-Konvention zur Abtretung von Forderungen im internationalen Handelsverkehr gelten bereits jetzt aufschiebend bedingt auf den Moment deren Inkrafttretens als vereinbart.<br />(3)    Sollten einzelne Bestimmungen des Vertrages mit dem Kunden einschließlich dieser Allgemeinen Geschäftsbedingungen ganz oder teilweise unwirksam sein oder werden, so wird hierdurch die Gültigkeit der übrigen Bestimmungen nicht berührt. Die ganz oder teilweise unwirksame Regelung soll durch eine Regelung ersetzt werden, deren wirtschaftlicher Erfolg dem der unwirksamen möglichst nahe kommt.<br />Entsprechendes gilt für die Ausfüllung einer Lücke im Vertrag mit dem Kunden oder in diesen Allgemeinen Geschäftsbedingungen.  </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"><br />***************************************************</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"><br />Allgemeine Geschäftsbedingungen der<br /> GmbH für Beratungsleistungen<br />Im weiteren  genannt<br />Stand: Februar 2009<br /><br />§ 1 Allgemeines – Geltungsbereich<br />(1)    Die nachstehenden Allgemeinen Geschäftsbedingungen (kurz: AGB) gelten für alle Beratungsleistungen der  an den Kunden. Kunde i. S. der Geschäftsbeziehungen sind Unternehmer.<br />(2)    Der Umfang der von den Beratern zu erbringenden Leistungen wird allein durch die schriftlichen Verträge festgelegt.<br /><br /><br />§ 2 Vertragsschluss<br />(1)    Grundlage jedes Beratungsauftrages ist der unter der Geltung dieser AGB abgeschlossene schriftliche Beratungsvertrag. In diesem Vertrag sind sämtliche maßgeblichen Rahmendaten des Auftrages festzulegen, mindestens jedoch Art und Umfang der vertraglichen Leistungen, insbesondere, welche Nebenleistungen über die Beratungstätigkeit hinaus erbracht werden, die Vergütung und bei Fixgeschäften die Fertigstellungstermine.<br />(2)    Beratungsleistungen werden ausschließlich auf der Grundlage der vom Kunden bereitgestellten Informationen erbracht.<br /><br />§ 3 Vergütung<br />(1)    Alle Preisangaben verstehen sich zuzüglich der gesetzlichen Umsatzsteuer.<br />(2)    Die  steht ein Zurückbehaltungsrecht an den Unterlagen bis zum vollständigen Ausgleich der Vergütung durch den Kunden zu.<br />(3)    Die  hat Anspruch auf Ersatz sämtlicher Auslagen, die für die Erfüllung des Auftrages notwendig waren. Reisen und die Vergabe von Fremdleistungen sind mit dem Kunden vorher abzustimmen.<br />(4)    Die Vergütung ist bei Ablieferung der Arbeiten nach Rechnungsstellung fällig. Bei Ablieferung von Teilarbeiten ist die Vergütung jeweils bei Ablieferung der Teilarbeiten und Rechnungsstellung fällig. Die  ist berechtigt, Abschlagszahlungen entsprechend dem erbrachten Arbeitsaufwand zu verlangen. Auslagen und Kosten sind mit Rechnungsstellung fällig. Fällige Rechnungen sind ohne Abzug zahlbar.<br />(5)    Der Kunde hat während des Verzuges die Geldschuld in Höhe von 8 % über dem Basiszinssatz zu verzinsen. Gegenüber dem Kunden behält sich die  vor, einen höheren Verzugsschaden nachzuweisen und geltend zu machen.<br />(6)    Der Kunde darf nur mit unbestrittenen oder rechtskräftigen Forderungen aufrechnen bzw. diese mit Forderungen der  verrechnen. Zurückbehaltungsrechte darf der Kunde nur ausüben, wenn sein Gegenanspruch unbestritten oder rechtskräftig festgestellt ist.<br /><br />§ 4 Fremdleistungen<br />(1)    Die  ist berechtigt, die zur Auftragserfüllung notwendigen Fremdleistungen im Namen und für Rechnung des Kunden zu bestellen. Der Kunde ist verpflichtet, der  hierzu schriftliche Vollmacht zu erteilen.<br /> <br /><br />(2)    Soweit im Einzelfall Verträge über Fremdleistungen im Namen und für Rechnung der  abgeschlossen werden, verpflichtet sich der Kunde, die  im Innenverhältnis von sämtlichen Verbindlichkeiten freizustellen, die sich aus dem Vertragsabschluss ergeben, insbesondere von der Verpflichtung zur Zahlung des Preises für die Fremdleistung.<br /><br />§ 4 Mitwirkungspflichten<br />(1)    Der Kunde hat dafür Sorge zu tragen, dass der  sämtliche relevanten Informationen zugänglich gemacht werden, die für die Beratungsleistung erforderlich sind oder von der  als erforderlich angesehen werden.<br />(2)    Die  verpflichtet sich, alle Geschäfts- und Betriebsgeheimnisse des Kunden vertraulich zu behandeln und gegen unbefugte Kenntnisnahme Dritter zu schützen. Dies gilt auch für Geschäfts- und Betriebsgeheimnisse anderer Firmen, die der  im Rahmen ihrer Tätigkeit für den Kunden bekannt geworden sind.<br />(3)    Soweit die  bei der Durchführung des Beratungsvertrages Informationen oder Unterlagen zur Verfügung gestellt werden, wird die  diese ebenfalls streng vertraulich behandeln und ausschließlich zur Erfüllung der geforderten Beratungsleistungen verwenden. Die Unterlagen werden nach Abschluss der Beratungsleistung dem Kunden unverzüglich ausgehändigt.<br /><br />§ 5 Haftung<br />(1)    Die  haftet unbeschränkt für vorsätzliche und grob fahrlässige Pflichtverletzungen ihrer gesetzlichen Vertreter und sonstigen Erfüllungsgehilfen.<br />(2)    Für einfach fahrlässige Verletzungen von wesentlichen Vertragspflichten haftet die  der Höhe nach nur für vertragstypische vorhersehbare Schäden. Die  haftet nicht bei leicht fahrlässiger Verletzung sonstiger Vertragspflichten.<br />(3)    Rügen und Beanstandungen gleich welcher Art sind innerhalb von zwei Wochen nach Lieferung schriftlich gegenüber der  geltend zu machen. Danach gilt das Werk als vertragsgemäß und mängelfrei abgenommen.<br /><br />§ 6 Gewährleistung/Mängelbeseitigung<br />(1)    Die  führt alle Arbeiten mit größter Sorgfalt und unter Beachtung allgemeiner branchenspezifischer Grundsätze sowie unter Beachtung allgemein anerkannter technischer, betriebswirtschaftlicher und ökologischer Grundsätze durch.<br />(2)    Alle Empfehlungen und Prognosen erfolgen nach bestem Wissen und Gewissen. Gewährleistungen für den Inhalt solcher Empfehlungen und Prognosen übernimmt die  nicht.<br />(3)    Die  bietet Gewähr für die Leistungen, soweit sie für diese gemäß § 5 der AGB die Haftung übernimmt. Soweit Leistungen der  mit Mängeln behaftet sind, hat der Kunde Anspruch auf Beseitigung. Er kann zunächst Nachbesserung verlangen. Kann der Mangel durch wiederholte Nachbesserung nicht beseitigt werden, so ist der Kunde berechtigt, hinsichtlich der mangelhaften Leistung vom Vertrag zurückzutreten oder eine angemessene Herabsetzung der Vergütung zu verlangen. Der Anspruch auf Ersatz der Kosten, die zur Herstellung der ordnungsgemäßen Leistungen anfallen, ist für beide Seiten ausgeschlossen.<br /><br />§ 7 Annahmeverzug und unterlassene Mitwirkung<br />    Kommt der Kunde mit der Annahme der von der  angebotenen Leistungen in Verzug oder unterlässt der Kunde eine ihm obliegende Mitwirkung, trotz Mahnung und Fristsetzung durch die , so ist die  zur fristlosen Kündigung des Vertrages berechtigt. Die  behält einen Anspruch auf Ersatz der ihr durch den Verzug entstandenen Mehraufwendungen sowie des entstandenen Schadens. Dies gilt auch, wenn die  von einem Kündigungsrecht keinen Gebrauch macht.<br /><br />§ 8 Schlussbestimmungen<br />(1)    Gerichtsstand für alle Streitigkeiten ist der Geschäftssitz der . Auch dann, wenn zum Zeitpunkt der Klageerhebung durch die  der Kunde keinen allgemeinen Gerichtsstand in der Bundesrepublik Deutschland hat oder der Sitz oder Wohnsitz oder gewöhnlicher Aufenthalt nicht bekannt ist, ist Gerichtsstand der Sitz der . Erfüllungsort ist Vlotho.<br />(2)    Für die Vertragsbeziehungen der Parteien gilt ausschließlich das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts-übereinkommens vom 11.04.1980.<br />(3)    Sollten einzelne Bestimmungen des Vertrages mit dem Kunden einschließlich dieser Allgemeinen Geschäftsbedingungen ganz oder teilweise unwirksam sein oder werden, so wird hierdurch die Gültigkeit der übrigen Bestimmungen nicht berührt. Die ganz oder teilweise unwirksame Regelung soll durch eine Regelung ersetzt werden, deren wirtschaftlicher Erfolg dem der unwirksamen möglichst nahe kommt.<br />Entsprechendes gilt für die Ausfüllung einer Lücke im Vertrag mit dem Kunden oder in diesen Allgemeinen Geschäftsbedingungen. <br /> </p>', '', '', '', '', '', '', '', '<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Dies sind keine rechtsverbindlichen AGB\'s.</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Sie dienen rein zu Demonstrationszwecken der </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Eine rechtliche Verpflichtung kann nicht abgeleitet werden.</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"> </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">**************************************</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">AGB</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Allgemeine Geschäftsbedingungen der<br /> GmbH für Warenlieferungen<br />Im weiteren  genannt<br />Stand: Februar 2009<br /><br />§ 1 Allgemeines – Geltungsbereich<br />(1)    Die nachstehenden Allgemeinen Geschäftsbedingungen (kurz: AGB) gelten für alle Lieferungen, Leistungen und Angebote der  an den Kunden. Kunde i. S. der Geschäftsbeziehungen sind Unternehmer.<br />(2)    Abweichende, entgegenstehende oder ergänzende Allgemeine Geschäfts-bedingungen des Kunden werden auch ohne ausdrücklichen Widerspruch nicht Vertragsbestandteil, es sei denn, ihrer Geltung wird ausdrücklich schriftlich zugestimmt. Sie werden auch dann nicht Vertragsinhalt, wenn die  in Kenntnis solcher Kundenbedingungen die Warenlieferung vorbehaltlos ausführt. Dies gilt auch für alle künftigen Warenlieferungen der  an den Kunden.<br />(3)    Falls die AGB dem Kunden nicht mit dem jeweiligen Angebot der  zugegangen sind oder bei anderer Gelegenheit vor oder bei Abschluss des jeweiligen Vertrages übergeben wurden, finden sie dennoch Anwendung, wenn der Käufer sie aus einer früheren oder anderen Geschäftsbeziehung kannte oder kennen musste.<br />(4)    Die  ist berechtigt, Informationen und Daten über den Kunden zu erheben, zu speichern, zu verarbeiten, zu nutzen und an Dritte insbesondere zum Zwecke des Forderungseinzugs oder des ausgelagerten Debitoren-managements zur Speicherung, Verarbeitung und Nutzung weiterzugeben.<br />     <br />§ 2 Vertragsschluss<br />(1)    Angebote der  sind – insbesondere hinsichtlich der Preise, Menge, Lieferfrist, Liefermöglichkeiten und Nebenleistungen - freibleibend. Technische Änderungen sowie Änderungen in Form, Farbe und/oder Gewicht bleiben im Rahmen des Zumutbaren vorbehalten. Die ausdrückliche Zusicherung von Eigenschaften bedarf der schriftlichen Bestätigung der .<br />(2)    Der Umfang der von der  zu erbringenden Leistungen wird allein durch die schriftlichen Verträge festgelegt.<br />(3)        Mit der Bestellung einer Ware erklärt der Kunde verbindlich, die bestellte Ware erwerben zu wollen. Die  ist berechtigt, das in der Bestellung liegende Vertragsangebot innerhalb von zwei Wochen nach Eingang bei der  anzunehmen. Die Annahme kann entweder schriftlich (auch durch Rechnung oder Lieferschein) oder durch Auslieferung der Ware an den Kunden erklärt werden.<br />(4)    Bestellt der Kunde die Ware auf elektronischem Wege, wird die  den Zugang der Bestellung unverzüglich bestätigen. Die Zugangsbestätigung stellt noch keine verbindliche Annahme der Bestellung dar. Die Zugangsbestätigung kann mit der Annahmeerklärung verbunden werden. Bei Bestellung auf elektronischem Wege wird der Vertragstext der  gespeichert und dem Kunden auf Verlangen nebst den vorliegenden AGB per E-Mail zugesandt.<br />(5)    Feste Lieferfristen bestehen nicht. Sofern abweichend hiervon ein fester Liefertermin vereinbart ist, hat der Kunde im Falle des Verzugs der Lieferung eine angemessene Nachfrist von in der Regel vier Wochen zu setzen. Der Vertragsschluss erfolgt unter dem Vorbehalt der richtigen und rechtzeitigen Selbstbelieferung durch Zulieferer der . Dies gilt nur für den Fall, dass die Nichtlieferung nicht von der  zu vertreten ist, insbesondere bei Abschluss eines kongruenten Deckungsgeschäftes mit dem Zulieferer der . Aufgrund von Kapazitätsengpässen ist nicht auszuschließen, dass die von der  angebotenen oder bei der  bestellten Produkte nicht zur vorgesehenen Lieferzeit – ggf. für längere Dauer – nicht verfügbar sind. Die Angebote und Auftragsbestätigungen stehen deshalb unter dem Vorbehalt der Produktverfügbarkeit. Über eine nachhaltige Nichtverfügbarkeit der Leistung wird der Kunde unverzüglich informiert. Die Gegenleistung wird zurückerstattet.<br />   <br />§ 3 Höhere Gewalt<br />Im Falle höherer Gewalt und aller sonst von der  nicht zu vertretender Hindernisse verlängern sich die Liefer- und Leistungsfristen angemessen. Wird infolge der Störung die Lieferung und/oder Abnahme um mehr als acht Wochen überschritten, so sind beide Teile zum Rücktritt berechtigt. Bei teilweisem oder vollständigem Wegfall der Bezugsquellen ist die  nicht verpflichtet, Ersatz bei fremden Vorlieferanten zu beschaffen. In diesem Fall ist die  berechtigt, die verfügbaren Warenmengen unter Berücksichtigung eines evtl. Eigenbedarfs zu verteilen.<br />   <br />§ 4 Versand<br />Die  behält sich die Wahl des Versandweges und der Versandart vor. Durch besondere Versandwünsche des Kunden verursachte Mehrkosten gehen zu dessen Lasten. Das gleiche gilt für nach Vertragsabschluß eintretende Erhöhungen der Frachtsätze, etwaige Mehrkosten für Umleitung, Lagerkosten etc., sofern nicht frachtfreie Lieferung vereinbart ist.<br />   <br />§ 5 Eigentumsvorbehalt<br />(1)    Die  behält sich das Eigentum an der Ware bis zur vollständigen Begleichung aller Forderungen aus einer laufenden Geschäftsbeziehung vor, einschließlich Nebenforderungen, Schadensersatzansprüchen und Einlösungen von Schecks. Der Eigentumsvorbehalt bleibt auch dann bestehen, wenn einzelne Forderungen in eine laufende Rechnung aufgenommen werden und der Saldo gezogen und anerkannt ist.<br />(2)    Der Kunde ist verpflichtet, der  einen Zugriff Dritter auf die Ware, etwa im Wege einer Pfändung, sowie etwaige Beschädigungen oder die Vernichtung der Ware unverzüglich mitzuteilen. Einen Besitzwechsel der Ware sowie den eigenen Wohnsitzwechsel hat der Kunde unverzüglich anzuzeigen.<br />(3)    Die  ist berechtigt, bei vertragswidrigem Verhalten des Kunden, insbesondere bei Zahlungsverzug oder bei Verletzung einer Pflicht nach Ziffer 2 dieser Bestimmung vom Vertrag zurückzutreten und die Ware heraus-zuverlangen.<br /> <br />Die Geltendmachung des Eigentumsvorbehalts stellt allein allerdings noch keine solche Rücktrittserklärung dar. Eine etwaige Warenrücknahme erfolgt immer nur sicherheitshalber; es liegt darin, auch wenn nachträglich Teilzahlungen gestattet wurden, kein Rücktritt vom Vertrag.<br />(4)    Der Kunde ist berechtigt, die Waren im ordentlichen Geschäftsgang weiterzuveräußern. Er tritt der  bereits jetzt alle Forderungen in Höhe des Rechnungsbetrages ab, die ihm durch die Weiterveräußerung gegen einen Dritten erwachsen. Die  nimmt die Abtretung an. Nach der Abtretung ist der Kunde zur Einziehung der Forderung ermächtigt. Die  behält sich vor, die Forderung selbst einzuziehen, sobald der Kunde seinen Zahlungsverpflichtungen nicht ordnungsgemäß nachkommt und in Zahlungsverzug gerät.<br />Erscheint der  die Verwirklichung ihrer Ansprüche als gefährdet, hat der Unternehmer auf eine entsprechende Aufforderung durch die  die Abtretung seinen Abnehmern mitzuteilen und der  alle erforderlichen Auskünfte und Unterlagen zur Verfügung zu stellen, die zur unmittelbaren Durchsetzung ihrer Rechte erforderlich sind.<br />(5)    Die Be- und Verarbeitung der Ware durch den Kunden erfolgt stets im Namen und Auftrag der . Erfolgt eine Verarbeitung mit der  nicht gehörenden Gegenständen, so erwirbt die  an der neuen Sache das Miteigentum im Verhältnis des Rechnungswerts der von der  gelieferten Ware zum Rechnungswert der sonstigen verarbeiteten Gegenstände.<br />Dasselbe gilt, wenn die Ware mit anderen, der  nicht gehörenden Gegenständen vermischt wird.<br />(6)    Übersteigt der Wert der  zustehenden Sicherheiten die zu sichernden Forderungen gegen den Kunden um mehr als 20%, so ist die  auf Verlangen des Kunden insoweit zur Freigabe von Sicherheiten verpflichtet. Die Auswahl der freizugebenden Sicherheiten erfolgt durch die .<br /> <br />§ 6 Vergütung<br />(1)    Der angebotene Kaufpreis ist der Nettopreis ab Lager. Die Versandkosten werden nach tatsächlichem Aufwand abgerechnet. Der Kunde kann den Kaufpreis per Nachnahme oder Rechnung leisten.<br />(2)    Die Zahlung hat innerhalb von 30 Tagen ab Rechnungsdatum ohne Abzug oder binnen 8 Tagen ab Rechnungsdatum mit 2 % Skonto zu erfolgen. Nach Ablauf von 30 Tagen ab Rechnungsdatum kommt der Kunde in Zahlungsverzug.<br />Der Kunde hat während des Verzugs die Geldschuld in Höhe von 8 % über dem Basiszinssatz zu verzinsen. Gegenüber dem Kunden behält sich  vor, einen höheren Verzugsschaden nachzuweisen und geltend zu machen.<br />(3)    Schecks werden nur erfüllungshalber unter Vorbehalt jederzeitiger Rückgabe und unter Ausschluss jeder Haftung für ordnungsgemäße Vorlage oder Protesterhebung hereingenommen. Schecks gelten erst nach vorbehaltloser Gutschrift durch die Bank der  als Zahlung.<br />(4)    Der Kunde darf nur mit unbestrittenen oder rechtskräftigen Forderungen aufrechnen bzw. diese mit Forderungen der  verrechnen. Zurückbehaltungsrechte darf der Kunde nur ausüben, wenn sein Gegenanspruch unbestritten oder rechtskräftig festgestellt ist.<br /> <br />§ 7 Gefahrübergang<br />(1)    Sämtliche Vereinbarungen zwischen der  und dem Kunden bezüglich der Beschaffenheit der von der  zu liefernden Waren sowie sämtliche sonstigen auf die Beschaffenheit dieser Waren bezogenen Erklärungen der  stellen keine Garantie gemäß § 433 BGB dar, es sei denn die  hat gegenüber dem Kunden eine gesonderte schriftliche Erklärung abgegeben, in der sie eine solche Garantie ausdrücklich übernimmt. Dasselbe gilt im Hinblick auf die Übernahme eines Beschaffungsrisikos durch die .<br />(2)    Alle Lieferungen erfolgen auf Kosten und Gefahr des Kunden.<br />(3)    Der Übergabe steht es gleich, wenn der Käufer im Verzug der Annahme ist.<br /><br />§ 8 Gewährleistung<br />(1)    Die Beschaffenheit der von der  zu liefernden Waren ergibt sich ausschließlich entweder aus den entsprechenden Vereinbarungen zwischen der  und dem Kunden oder aus den in § 434 Abs. 1 Satz 2 BGB genannten Umständen unter Ausschluß der in § 434 Abs. 1 Satz 3 BGB genannten Umständen. Muster und Proben der von der  zu liefernden Waren dienen nur der ungefähren Beschreibung dieser Waren.<br />(2)    Die  leistet für Mängel der Ware zunächst nach ihrer Wahl Gewähr durch Nachbesserung oder Ersatzlieferung.<br />(3)    Schlägt die Nacherfüllung fehl, kann der Kunde nur nach seiner Wahl Herabsetzung der Vergütung (Minderung) oder Rückgängigmachung des Vertrages (Rücktritt) verlangen. Bei nur geringfügiger Vertragswidrigkeit, ins- besondere bei nur geringfügigen Mängeln, steht dem Kunden jedoch kein Rücktrittsrecht zu.<br />(4)    Der Kunde muss der  offensichtliche Mängel unter Angabe von Rechnungsnummer, Produktnamen, Abmessung, Chargennummer, Materialmenge und Fehlerbeschreibung unverzüglich, spätestens innerhalb einer Frist von einer Woche ab Empfang der Ware schriftlich anzeigen; anderenfalls ist die Geltendmachung des Gewährleistungsanspruches ausgeschlossen. Zur Fristwahrung genügt die rechtzeitige Absendung. Den Kunden trifft die volle Beweislast für sämtliche Anspruchsvoraussetzungen, insbesondere für den Mangel selbst, für den Zeitpunkt der Feststellung des Mangels und für die Rechtzeitigkeit der Mängelrüge. Die  behält sich bis zum Abschluss der Reklamationsbearbeitung vor, das reklamierte Material zwecks Laborprüfungen vom Kunden zurückzufordern.<br />(5)    Wählt der Kunde wegen eines Rechts- oder Sachmangels nach gescheiterter Nacherfüllung den Rücktritt vom Vertrag, steht ihm daneben kein Schadens-ersatzanspruch wegen des Mangels zu. Wählt der Kunde nach gescheiterter Nacherfüllung Schadensersatz, verbleibt die Ware beim Kunden, wenn ihm dies zumutbar ist.<br />Der Schadensersatz beschränkt sich auf die Differenz zwischen Kaufpreis und Wert der mangelhaften Sache. Dies gilt nicht, wenn die  die Vertragsverletzung arglistig verursacht haben.<br />(6)    Die Gewährleistungsfrist beträgt ein Jahr ab Ablieferung der Ware. Bei gebrauchten Sachen beträgt die Verjährungsfrist ein Jahr ab Ablieferung der Ware. Dies gilt nicht, wenn der Kunde der  den Mangel nicht rechtzeitig angezeigt hat (Ziffer 4 dieser Bestimmung).<br />(7)    Als Beschaffenheit der Ware gilt grundsätzlich die Produktbeschreibung des Herstellers als vereinbart. Öffentliche Äußerungen, Anpreisungen oder Werbung des Herstellers stellen daneben keine vertragsgemäße Beschaffenheitsangabe der Ware dar.<br />(8)    Garantien im Rechtssinne erhält der Kunde durch die  nicht. Herstellergarantien bleiben hiervon unberührt.<br /> <br />§ 10 Haftungsbeschränkungen<br />(1)    Bei einer leicht fahrlässigen Pflichtverletzung beschränkt sich die Haftung der  auf den nach der Art der Ware vorhersehbaren, vertragstypischen, unmittelbaren Durchschnittsschaden. Dies gilt auch bei leicht fahrlässigen Pflichtverletzungen der gesetzlichen Vertreter oder Erfüllungsgehilfen der . Schadensersatzansprüche des Kunden – auch außervertraglicher Art – sind im Falle leicht fahrlässiger Pflichtverletzung durch die gesetzlichen Vertreter und andere Erfüllungsgehilfen der  ausgeschlossen, es sei denn, dass die Verletzung eine Pflicht betrifft, die für die Erreichung des Vertragszweckes von wesentlicher Bedeutung ist.<br />(2)    Die vorstehenden Haftungsbeschränkungen betreffen nicht Ansprüche des Kunden aus Produkthaftung. Weiter gelten die Haftungsbeschränkungen nicht bei unzurechenbaren Körper- oder Gesundheitsschäden oder bei Verlust des Lebens des Kunden.<br />(3)    Schadensersatzansprüche des Kunden wegen eines Mangels verjähren nach einem Jahr ab Ablieferung der Ware. Dies gilt nicht, wenn der  Arglist vorwerfbar ist.  <br /> <br />§ 11 Schlussbestimmungen<br />(1)    Gerichtsstand für alle Streitigkeiten ist der Geschäftssitz der . Auch dann, wenn zum Zeitpunkt der Klageerhebung durch die  der Kunde keinen allgemeinen Gerichtsstand in der Bundesrepublik Deutschland hat oder der Sitz oder Wohnsitz oder gewöhnlicher Aufenthalt nicht bekannt ist, ist Gerichtsstand der Sitz der . Erfüllungsort ist Vlotho.<br />(2)    Für die Vertragsbeziehungen der Parteien gilt ausschließlich das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts-übereinkommens vom 11.04.1980.<br />Die Regelungen der UN-Konvention zur Abtretung von Forderungen im internationalen Handelsverkehr gelten bereits jetzt aufschiebend bedingt auf den Moment deren Inkrafttretens als vereinbart.<br />(3)    Sollten einzelne Bestimmungen des Vertrages mit dem Kunden einschließlich dieser Allgemeinen Geschäftsbedingungen ganz oder teilweise unwirksam sein oder werden, so wird hierdurch die Gültigkeit der übrigen Bestimmungen nicht berührt. Die ganz oder teilweise unwirksame Regelung soll durch eine Regelung ersetzt werden, deren wirtschaftlicher Erfolg dem der unwirksamen möglichst nahe kommt.<br />Entsprechendes gilt für die Ausfüllung einer Lücke im Vertrag mit dem Kunden oder in diesen Allgemeinen Geschäftsbedingungen.  </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"><br />***************************************************</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"><br />Allgemeine Geschäftsbedingungen der<br /> GmbH für Beratungsleistungen<br />Im weiteren  genannt<br />Stand: Februar 2009<br /><br />§ 1 Allgemeines – Geltungsbereich<br />(1)    Die nachstehenden Allgemeinen Geschäftsbedingungen (kurz: AGB) gelten für alle Beratungsleistungen der  an den Kunden. Kunde i. S. der Geschäftsbeziehungen sind Unternehmer.<br />(2)    Der Umfang der von den Beratern zu erbringenden Leistungen wird allein durch die schriftlichen Verträge festgelegt.<br /><br /><br />§ 2 Vertragsschluss<br />(1)    Grundlage jedes Beratungsauftrages ist der unter der Geltung dieser AGB abgeschlossene schriftliche Beratungsvertrag. In diesem Vertrag sind sämtliche maßgeblichen Rahmendaten des Auftrages festzulegen, mindestens jedoch Art und Umfang der vertraglichen Leistungen, insbesondere, welche Nebenleistungen über die Beratungstätigkeit hinaus erbracht werden, die Vergütung und bei Fixgeschäften die Fertigstellungstermine.<br />(2)    Beratungsleistungen werden ausschließlich auf der Grundlage der vom Kunden bereitgestellten Informationen erbracht.<br /><br />§ 3 Vergütung<br />(1)    Alle Preisangaben verstehen sich zuzüglich der gesetzlichen Umsatzsteuer.<br />(2)    Die  steht ein Zurückbehaltungsrecht an den Unterlagen bis zum vollständigen Ausgleich der Vergütung durch den Kunden zu.<br />(3)    Die  hat Anspruch auf Ersatz sämtlicher Auslagen, die für die Erfüllung des Auftrages notwendig waren. Reisen und die Vergabe von Fremdleistungen sind mit dem Kunden vorher abzustimmen.<br />(4)    Die Vergütung ist bei Ablieferung der Arbeiten nach Rechnungsstellung fällig. Bei Ablieferung von Teilarbeiten ist die Vergütung jeweils bei Ablieferung der Teilarbeiten und Rechnungsstellung fällig. Die  ist berechtigt, Abschlagszahlungen entsprechend dem erbrachten Arbeitsaufwand zu verlangen. Auslagen und Kosten sind mit Rechnungsstellung fällig. Fällige Rechnungen sind ohne Abzug zahlbar.<br />(5)    Der Kunde hat während des Verzuges die Geldschuld in Höhe von 8 % über dem Basiszinssatz zu verzinsen. Gegenüber dem Kunden behält sich die  vor, einen höheren Verzugsschaden nachzuweisen und geltend zu machen.<br />(6)    Der Kunde darf nur mit unbestrittenen oder rechtskräftigen Forderungen aufrechnen bzw. diese mit Forderungen der  verrechnen. Zurückbehaltungsrechte darf der Kunde nur ausüben, wenn sein Gegenanspruch unbestritten oder rechtskräftig festgestellt ist.<br /><br />§ 4 Fremdleistungen<br />(1)    Die  ist berechtigt, die zur Auftragserfüllung notwendigen Fremdleistungen im Namen und für Rechnung des Kunden zu bestellen. Der Kunde ist verpflichtet, der  hierzu schriftliche Vollmacht zu erteilen.<br /> <br /><br />(2)    Soweit im Einzelfall Verträge über Fremdleistungen im Namen und für Rechnung der  abgeschlossen werden, verpflichtet sich der Kunde, die  im Innenverhältnis von sämtlichen Verbindlichkeiten freizustellen, die sich aus dem Vertragsabschluss ergeben, insbesondere von der Verpflichtung zur Zahlung des Preises für die Fremdleistung.<br /><br />§ 4 Mitwirkungspflichten<br />(1)    Der Kunde hat dafür Sorge zu tragen, dass der  sämtliche relevanten Informationen zugänglich gemacht werden, die für die Beratungsleistung erforderlich sind oder von der  als erforderlich angesehen werden.<br />(2)    Die  verpflichtet sich, alle Geschäfts- und Betriebsgeheimnisse des Kunden vertraulich zu behandeln und gegen unbefugte Kenntnisnahme Dritter zu schützen. Dies gilt auch für Geschäfts- und Betriebsgeheimnisse anderer Firmen, die der  im Rahmen ihrer Tätigkeit für den Kunden bekannt geworden sind.<br />(3)    Soweit die  bei der Durchführung des Beratungsvertrages Informationen oder Unterlagen zur Verfügung gestellt werden, wird die  diese ebenfalls streng vertraulich behandeln und ausschließlich zur Erfüllung der geforderten Beratungsleistungen verwenden. Die Unterlagen werden nach Abschluss der Beratungsleistung dem Kunden unverzüglich ausgehändigt.<br /><br />§ 5 Haftung<br />(1)    Die  haftet unbeschränkt für vorsätzliche und grob fahrlässige Pflichtverletzungen ihrer gesetzlichen Vertreter und sonstigen Erfüllungsgehilfen.<br />(2)    Für einfach fahrlässige Verletzungen von wesentlichen Vertragspflichten haftet die  der Höhe nach nur für vertragstypische vorhersehbare Schäden. Die  haftet nicht bei leicht fahrlässiger Verletzung sonstiger Vertragspflichten.<br />(3)    Rügen und Beanstandungen gleich welcher Art sind innerhalb von zwei Wochen nach Lieferung schriftlich gegenüber der  geltend zu machen. Danach gilt das Werk als vertragsgemäß und mängelfrei abgenommen.<br /><br />§ 6 Gewährleistung/Mängelbeseitigung<br />(1)    Die  führt alle Arbeiten mit größter Sorgfalt und unter Beachtung allgemeiner branchenspezifischer Grundsätze sowie unter Beachtung allgemein anerkannter technischer, betriebswirtschaftlicher und ökologischer Grundsätze durch.<br />(2)    Alle Empfehlungen und Prognosen erfolgen nach bestem Wissen und Gewissen. Gewährleistungen für den Inhalt solcher Empfehlungen und Prognosen übernimmt die  nicht.<br />(3)    Die  bietet Gewähr für die Leistungen, soweit sie für diese gemäß § 5 der AGB die Haftung übernimmt. Soweit Leistungen der  mit Mängeln behaftet sind, hat der Kunde Anspruch auf Beseitigung. Er kann zunächst Nachbesserung verlangen. Kann der Mangel durch wiederholte Nachbesserung nicht beseitigt werden, so ist der Kunde berechtigt, hinsichtlich der mangelhaften Leistung vom Vertrag zurückzutreten oder eine angemessene Herabsetzung der Vergütung zu verlangen. Der Anspruch auf Ersatz der Kosten, die zur Herstellung der ordnungsgemäßen Leistungen anfallen, ist für beide Seiten ausgeschlossen.<br /><br />§ 7 Annahmeverzug und unterlassene Mitwirkung<br />    Kommt der Kunde mit der Annahme der von der  angebotenen Leistungen in Verzug oder unterlässt der Kunde eine ihm obliegende Mitwirkung, trotz Mahnung und Fristsetzung durch die , so ist die  zur fristlosen Kündigung des Vertrages berechtigt. Die  behält einen Anspruch auf Ersatz der ihr durch den Verzug entstandenen Mehraufwendungen sowie des entstandenen Schadens. Dies gilt auch, wenn die  von einem Kündigungsrecht keinen Gebrauch macht.<br /><br />§ 8 Schlussbestimmungen<br />(1)    Gerichtsstand für alle Streitigkeiten ist der Geschäftssitz der . Auch dann, wenn zum Zeitpunkt der Klageerhebung durch die  der Kunde keinen allgemeinen Gerichtsstand in der Bundesrepublik Deutschland hat oder der Sitz oder Wohnsitz oder gewöhnlicher Aufenthalt nicht bekannt ist, ist Gerichtsstand der Sitz der . Erfüllungsort ist Vlotho.<br />(2)    Für die Vertragsbeziehungen der Parteien gilt ausschließlich das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts-übereinkommens vom 11.04.1980.<br />(3)    Sollten einzelne Bestimmungen des Vertrages mit dem Kunden einschließlich dieser Allgemeinen Geschäftsbedingungen ganz oder teilweise unwirksam sein oder werden, so wird hierdurch die Gültigkeit der übrigen Bestimmungen nicht berührt. Die ganz oder teilweise unwirksame Regelung soll durch eine Regelung ersetzt werden, deren wirtschaftlicher Erfolg dem der unwirksamen möglichst nahe kommt.<br />Entsprechendes gilt für die Ausfüllung einer Lücke im Vertrag mit dem Kunden oder in diesen Allgemeinen Geschäftsbedingungen. <br /> </p>', 0, '<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Dies sind keine rechtsverbindlichen AGB\'s.</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Sie dienen rein zu Demonstrationszwecken der </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Eine rechtliche Verpflichtung kann nicht abgeleitet werden.</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"> </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">**************************************</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">AGB</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\">Allgemeine Geschäftsbedingungen der<br /> GmbH für Warenlieferungen<br />Im weiteren  genannt<br />Stand: Februar 2009<br /><br />§ 1 Allgemeines – Geltungsbereich<br />(1)    Die nachstehenden Allgemeinen Geschäftsbedingungen (kurz: AGB) gelten für alle Lieferungen, Leistungen und Angebote der  an den Kunden. Kunde i. S. der Geschäftsbeziehungen sind Unternehmer.<br />(2)    Abweichende, entgegenstehende oder ergänzende Allgemeine Geschäfts-bedingungen des Kunden werden auch ohne ausdrücklichen Widerspruch nicht Vertragsbestandteil, es sei denn, ihrer Geltung wird ausdrücklich schriftlich zugestimmt. Sie werden auch dann nicht Vertragsinhalt, wenn die  in Kenntnis solcher Kundenbedingungen die Warenlieferung vorbehaltlos ausführt. Dies gilt auch für alle künftigen Warenlieferungen der  an den Kunden.<br />(3)    Falls die AGB dem Kunden nicht mit dem jeweiligen Angebot der  zugegangen sind oder bei anderer Gelegenheit vor oder bei Abschluss des jeweiligen Vertrages übergeben wurden, finden sie dennoch Anwendung, wenn der Käufer sie aus einer früheren oder anderen Geschäftsbeziehung kannte oder kennen musste.<br />(4)    Die  ist berechtigt, Informationen und Daten über den Kunden zu erheben, zu speichern, zu verarbeiten, zu nutzen und an Dritte insbesondere zum Zwecke des Forderungseinzugs oder des ausgelagerten Debitoren-managements zur Speicherung, Verarbeitung und Nutzung weiterzugeben.<br />     <br />§ 2 Vertragsschluss<br />(1)    Angebote der  sind – insbesondere hinsichtlich der Preise, Menge, Lieferfrist, Liefermöglichkeiten und Nebenleistungen - freibleibend. Technische Änderungen sowie Änderungen in Form, Farbe und/oder Gewicht bleiben im Rahmen des Zumutbaren vorbehalten. Die ausdrückliche Zusicherung von Eigenschaften bedarf der schriftlichen Bestätigung der .<br />(2)    Der Umfang der von der  zu erbringenden Leistungen wird allein durch die schriftlichen Verträge festgelegt.<br />(3)        Mit der Bestellung einer Ware erklärt der Kunde verbindlich, die bestellte Ware erwerben zu wollen. Die  ist berechtigt, das in der Bestellung liegende Vertragsangebot innerhalb von zwei Wochen nach Eingang bei der  anzunehmen. Die Annahme kann entweder schriftlich (auch durch Rechnung oder Lieferschein) oder durch Auslieferung der Ware an den Kunden erklärt werden.<br />(4)    Bestellt der Kunde die Ware auf elektronischem Wege, wird die  den Zugang der Bestellung unverzüglich bestätigen. Die Zugangsbestätigung stellt noch keine verbindliche Annahme der Bestellung dar. Die Zugangsbestätigung kann mit der Annahmeerklärung verbunden werden. Bei Bestellung auf elektronischem Wege wird der Vertragstext der  gespeichert und dem Kunden auf Verlangen nebst den vorliegenden AGB per E-Mail zugesandt.<br />(5)    Feste Lieferfristen bestehen nicht. Sofern abweichend hiervon ein fester Liefertermin vereinbart ist, hat der Kunde im Falle des Verzugs der Lieferung eine angemessene Nachfrist von in der Regel vier Wochen zu setzen. Der Vertragsschluss erfolgt unter dem Vorbehalt der richtigen und rechtzeitigen Selbstbelieferung durch Zulieferer der . Dies gilt nur für den Fall, dass die Nichtlieferung nicht von der  zu vertreten ist, insbesondere bei Abschluss eines kongruenten Deckungsgeschäftes mit dem Zulieferer der . Aufgrund von Kapazitätsengpässen ist nicht auszuschließen, dass die von der  angebotenen oder bei der  bestellten Produkte nicht zur vorgesehenen Lieferzeit – ggf. für längere Dauer – nicht verfügbar sind. Die Angebote und Auftragsbestätigungen stehen deshalb unter dem Vorbehalt der Produktverfügbarkeit. Über eine nachhaltige Nichtverfügbarkeit der Leistung wird der Kunde unverzüglich informiert. Die Gegenleistung wird zurückerstattet.<br />   <br />§ 3 Höhere Gewalt<br />Im Falle höherer Gewalt und aller sonst von der  nicht zu vertretender Hindernisse verlängern sich die Liefer- und Leistungsfristen angemessen. Wird infolge der Störung die Lieferung und/oder Abnahme um mehr als acht Wochen überschritten, so sind beide Teile zum Rücktritt berechtigt. Bei teilweisem oder vollständigem Wegfall der Bezugsquellen ist die  nicht verpflichtet, Ersatz bei fremden Vorlieferanten zu beschaffen. In diesem Fall ist die  berechtigt, die verfügbaren Warenmengen unter Berücksichtigung eines evtl. Eigenbedarfs zu verteilen.<br />   <br />§ 4 Versand<br />Die  behält sich die Wahl des Versandweges und der Versandart vor. Durch besondere Versandwünsche des Kunden verursachte Mehrkosten gehen zu dessen Lasten. Das gleiche gilt für nach Vertragsabschluß eintretende Erhöhungen der Frachtsätze, etwaige Mehrkosten für Umleitung, Lagerkosten etc., sofern nicht frachtfreie Lieferung vereinbart ist.<br />   <br />§ 5 Eigentumsvorbehalt<br />(1)    Die  behält sich das Eigentum an der Ware bis zur vollständigen Begleichung aller Forderungen aus einer laufenden Geschäftsbeziehung vor, einschließlich Nebenforderungen, Schadensersatzansprüchen und Einlösungen von Schecks. Der Eigentumsvorbehalt bleibt auch dann bestehen, wenn einzelne Forderungen in eine laufende Rechnung aufgenommen werden und der Saldo gezogen und anerkannt ist.<br />(2)    Der Kunde ist verpflichtet, der  einen Zugriff Dritter auf die Ware, etwa im Wege einer Pfändung, sowie etwaige Beschädigungen oder die Vernichtung der Ware unverzüglich mitzuteilen. Einen Besitzwechsel der Ware sowie den eigenen Wohnsitzwechsel hat der Kunde unverzüglich anzuzeigen.<br />(3)    Die  ist berechtigt, bei vertragswidrigem Verhalten des Kunden, insbesondere bei Zahlungsverzug oder bei Verletzung einer Pflicht nach Ziffer 2 dieser Bestimmung vom Vertrag zurückzutreten und die Ware heraus-zuverlangen.<br /> <br />Die Geltendmachung des Eigentumsvorbehalts stellt allein allerdings noch keine solche Rücktrittserklärung dar. Eine etwaige Warenrücknahme erfolgt immer nur sicherheitshalber; es liegt darin, auch wenn nachträglich Teilzahlungen gestattet wurden, kein Rücktritt vom Vertrag.<br />(4)    Der Kunde ist berechtigt, die Waren im ordentlichen Geschäftsgang weiterzuveräußern. Er tritt der  bereits jetzt alle Forderungen in Höhe des Rechnungsbetrages ab, die ihm durch die Weiterveräußerung gegen einen Dritten erwachsen. Die  nimmt die Abtretung an. Nach der Abtretung ist der Kunde zur Einziehung der Forderung ermächtigt. Die  behält sich vor, die Forderung selbst einzuziehen, sobald der Kunde seinen Zahlungsverpflichtungen nicht ordnungsgemäß nachkommt und in Zahlungsverzug gerät.<br />Erscheint der  die Verwirklichung ihrer Ansprüche als gefährdet, hat der Unternehmer auf eine entsprechende Aufforderung durch die  die Abtretung seinen Abnehmern mitzuteilen und der  alle erforderlichen Auskünfte und Unterlagen zur Verfügung zu stellen, die zur unmittelbaren Durchsetzung ihrer Rechte erforderlich sind.<br />(5)    Die Be- und Verarbeitung der Ware durch den Kunden erfolgt stets im Namen und Auftrag der . Erfolgt eine Verarbeitung mit der  nicht gehörenden Gegenständen, so erwirbt die  an der neuen Sache das Miteigentum im Verhältnis des Rechnungswerts der von der  gelieferten Ware zum Rechnungswert der sonstigen verarbeiteten Gegenstände.<br />Dasselbe gilt, wenn die Ware mit anderen, der  nicht gehörenden Gegenständen vermischt wird.<br />(6)    Übersteigt der Wert der  zustehenden Sicherheiten die zu sichernden Forderungen gegen den Kunden um mehr als 20%, so ist die  auf Verlangen des Kunden insoweit zur Freigabe von Sicherheiten verpflichtet. Die Auswahl der freizugebenden Sicherheiten erfolgt durch die .<br /> <br />§ 6 Vergütung<br />(1)    Der angebotene Kaufpreis ist der Nettopreis ab Lager. Die Versandkosten werden nach tatsächlichem Aufwand abgerechnet. Der Kunde kann den Kaufpreis per Nachnahme oder Rechnung leisten.<br />(2)    Die Zahlung hat innerhalb von 30 Tagen ab Rechnungsdatum ohne Abzug oder binnen 8 Tagen ab Rechnungsdatum mit 2 % Skonto zu erfolgen. Nach Ablauf von 30 Tagen ab Rechnungsdatum kommt der Kunde in Zahlungsverzug.<br />Der Kunde hat während des Verzugs die Geldschuld in Höhe von 8 % über dem Basiszinssatz zu verzinsen. Gegenüber dem Kunden behält sich  vor, einen höheren Verzugsschaden nachzuweisen und geltend zu machen.<br />(3)    Schecks werden nur erfüllungshalber unter Vorbehalt jederzeitiger Rückgabe und unter Ausschluss jeder Haftung für ordnungsgemäße Vorlage oder Protesterhebung hereingenommen. Schecks gelten erst nach vorbehaltloser Gutschrift durch die Bank der  als Zahlung.<br />(4)    Der Kunde darf nur mit unbestrittenen oder rechtskräftigen Forderungen aufrechnen bzw. diese mit Forderungen der  verrechnen. Zurückbehaltungsrechte darf der Kunde nur ausüben, wenn sein Gegenanspruch unbestritten oder rechtskräftig festgestellt ist.<br /> <br />§ 7 Gefahrübergang<br />(1)    Sämtliche Vereinbarungen zwischen der  und dem Kunden bezüglich der Beschaffenheit der von der  zu liefernden Waren sowie sämtliche sonstigen auf die Beschaffenheit dieser Waren bezogenen Erklärungen der  stellen keine Garantie gemäß § 433 BGB dar, es sei denn die  hat gegenüber dem Kunden eine gesonderte schriftliche Erklärung abgegeben, in der sie eine solche Garantie ausdrücklich übernimmt. Dasselbe gilt im Hinblick auf die Übernahme eines Beschaffungsrisikos durch die .<br />(2)    Alle Lieferungen erfolgen auf Kosten und Gefahr des Kunden.<br />(3)    Der Übergabe steht es gleich, wenn der Käufer im Verzug der Annahme ist.<br /><br />§ 8 Gewährleistung<br />(1)    Die Beschaffenheit der von der  zu liefernden Waren ergibt sich ausschließlich entweder aus den entsprechenden Vereinbarungen zwischen der  und dem Kunden oder aus den in § 434 Abs. 1 Satz 2 BGB genannten Umständen unter Ausschluß der in § 434 Abs. 1 Satz 3 BGB genannten Umständen. Muster und Proben der von der  zu liefernden Waren dienen nur der ungefähren Beschreibung dieser Waren.<br />(2)    Die  leistet für Mängel der Ware zunächst nach ihrer Wahl Gewähr durch Nachbesserung oder Ersatzlieferung.<br />(3)    Schlägt die Nacherfüllung fehl, kann der Kunde nur nach seiner Wahl Herabsetzung der Vergütung (Minderung) oder Rückgängigmachung des Vertrages (Rücktritt) verlangen. Bei nur geringfügiger Vertragswidrigkeit, ins- besondere bei nur geringfügigen Mängeln, steht dem Kunden jedoch kein Rücktrittsrecht zu.<br />(4)    Der Kunde muss der  offensichtliche Mängel unter Angabe von Rechnungsnummer, Produktnamen, Abmessung, Chargennummer, Materialmenge und Fehlerbeschreibung unverzüglich, spätestens innerhalb einer Frist von einer Woche ab Empfang der Ware schriftlich anzeigen; anderenfalls ist die Geltendmachung des Gewährleistungsanspruches ausgeschlossen. Zur Fristwahrung genügt die rechtzeitige Absendung. Den Kunden trifft die volle Beweislast für sämtliche Anspruchsvoraussetzungen, insbesondere für den Mangel selbst, für den Zeitpunkt der Feststellung des Mangels und für die Rechtzeitigkeit der Mängelrüge. Die  behält sich bis zum Abschluss der Reklamationsbearbeitung vor, das reklamierte Material zwecks Laborprüfungen vom Kunden zurückzufordern.<br />(5)    Wählt der Kunde wegen eines Rechts- oder Sachmangels nach gescheiterter Nacherfüllung den Rücktritt vom Vertrag, steht ihm daneben kein Schadens-ersatzanspruch wegen des Mangels zu. Wählt der Kunde nach gescheiterter Nacherfüllung Schadensersatz, verbleibt die Ware beim Kunden, wenn ihm dies zumutbar ist.<br />Der Schadensersatz beschränkt sich auf die Differenz zwischen Kaufpreis und Wert der mangelhaften Sache. Dies gilt nicht, wenn die  die Vertragsverletzung arglistig verursacht haben.<br />(6)    Die Gewährleistungsfrist beträgt ein Jahr ab Ablieferung der Ware. Bei gebrauchten Sachen beträgt die Verjährungsfrist ein Jahr ab Ablieferung der Ware. Dies gilt nicht, wenn der Kunde der  den Mangel nicht rechtzeitig angezeigt hat (Ziffer 4 dieser Bestimmung).<br />(7)    Als Beschaffenheit der Ware gilt grundsätzlich die Produktbeschreibung des Herstellers als vereinbart. Öffentliche Äußerungen, Anpreisungen oder Werbung des Herstellers stellen daneben keine vertragsgemäße Beschaffenheitsangabe der Ware dar.<br />(8)    Garantien im Rechtssinne erhält der Kunde durch die  nicht. Herstellergarantien bleiben hiervon unberührt.<br /> <br />§ 10 Haftungsbeschränkungen<br />(1)    Bei einer leicht fahrlässigen Pflichtverletzung beschränkt sich die Haftung der  auf den nach der Art der Ware vorhersehbaren, vertragstypischen, unmittelbaren Durchschnittsschaden. Dies gilt auch bei leicht fahrlässigen Pflichtverletzungen der gesetzlichen Vertreter oder Erfüllungsgehilfen der . Schadensersatzansprüche des Kunden – auch außervertraglicher Art – sind im Falle leicht fahrlässiger Pflichtverletzung durch die gesetzlichen Vertreter und andere Erfüllungsgehilfen der  ausgeschlossen, es sei denn, dass die Verletzung eine Pflicht betrifft, die für die Erreichung des Vertragszweckes von wesentlicher Bedeutung ist.<br />(2)    Die vorstehenden Haftungsbeschränkungen betreffen nicht Ansprüche des Kunden aus Produkthaftung. Weiter gelten die Haftungsbeschränkungen nicht bei unzurechenbaren Körper- oder Gesundheitsschäden oder bei Verlust des Lebens des Kunden.<br />(3)    Schadensersatzansprüche des Kunden wegen eines Mangels verjähren nach einem Jahr ab Ablieferung der Ware. Dies gilt nicht, wenn der  Arglist vorwerfbar ist.  <br /> <br />§ 11 Schlussbestimmungen<br />(1)    Gerichtsstand für alle Streitigkeiten ist der Geschäftssitz der . Auch dann, wenn zum Zeitpunkt der Klageerhebung durch die  der Kunde keinen allgemeinen Gerichtsstand in der Bundesrepublik Deutschland hat oder der Sitz oder Wohnsitz oder gewöhnlicher Aufenthalt nicht bekannt ist, ist Gerichtsstand der Sitz der . Erfüllungsort ist Vlotho.<br />(2)    Für die Vertragsbeziehungen der Parteien gilt ausschließlich das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts-übereinkommens vom 11.04.1980.<br />Die Regelungen der UN-Konvention zur Abtretung von Forderungen im internationalen Handelsverkehr gelten bereits jetzt aufschiebend bedingt auf den Moment deren Inkrafttretens als vereinbart.<br />(3)    Sollten einzelne Bestimmungen des Vertrages mit dem Kunden einschließlich dieser Allgemeinen Geschäftsbedingungen ganz oder teilweise unwirksam sein oder werden, so wird hierdurch die Gültigkeit der übrigen Bestimmungen nicht berührt. Die ganz oder teilweise unwirksame Regelung soll durch eine Regelung ersetzt werden, deren wirtschaftlicher Erfolg dem der unwirksamen möglichst nahe kommt.<br />Entsprechendes gilt für die Ausfüllung einer Lücke im Vertrag mit dem Kunden oder in diesen Allgemeinen Geschäftsbedingungen.  </p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"><br />***************************************************</p>\r\n<p style=\"margin-top: 12px; margin-right: 0px; margin-bottom: 12px; margin-left: 0px; padding: 0px;\"><br />Allgemeine Geschäftsbedingungen der<br /> GmbH für Beratungsleistungen<br />Im weiteren  genannt<br />Stand: Februar 2009<br /><br />§ 1 Allgemeines – Geltungsbereich<br />(1)    Die nachstehenden Allgemeinen Geschäftsbedingungen (kurz: AGB) gelten für alle Beratungsleistungen der  an den Kunden. Kunde i. S. der Geschäftsbeziehungen sind Unternehmer.<br />(2)    Der Umfang der von den Beratern zu erbringenden Leistungen wird allein durch die schriftlichen Verträge festgelegt.<br /><br /><br />§ 2 Vertragsschluss<br />(1)    Grundlage jedes Beratungsauftrages ist der unter der Geltung dieser AGB abgeschlossene schriftliche Beratungsvertrag. In diesem Vertrag sind sämtliche maßgeblichen Rahmendaten des Auftrages festzulegen, mindestens jedoch Art und Umfang der vertraglichen Leistungen, insbesondere, welche Nebenleistungen über die Beratungstätigkeit hinaus erbracht werden, die Vergütung und bei Fixgeschäften die Fertigstellungstermine.<br />(2)    Beratungsleistungen werden ausschließlich auf der Grundlage der vom Kunden bereitgestellten Informationen erbracht.<br /><br />§ 3 Vergütung<br />(1)    Alle Preisangaben verstehen sich zuzüglich der gesetzlichen Umsatzsteuer.<br />(2)    Die  steht ein Zurückbehaltungsrecht an den Unterlagen bis zum vollständigen Ausgleich der Vergütung durch den Kunden zu.<br />(3)    Die  hat Anspruch auf Ersatz sämtlicher Auslagen, die für die Erfüllung des Auftrages notwendig waren. Reisen und die Vergabe von Fremdleistungen sind mit dem Kunden vorher abzustimmen.<br />(4)    Die Vergütung ist bei Ablieferung der Arbeiten nach Rechnungsstellung fällig. Bei Ablieferung von Teilarbeiten ist die Vergütung jeweils bei Ablieferung der Teilarbeiten und Rechnungsstellung fällig. Die  ist berechtigt, Abschlagszahlungen entsprechend dem erbrachten Arbeitsaufwand zu verlangen. Auslagen und Kosten sind mit Rechnungsstellung fällig. Fällige Rechnungen sind ohne Abzug zahlbar.<br />(5)    Der Kunde hat während des Verzuges die Geldschuld in Höhe von 8 % über dem Basiszinssatz zu verzinsen. Gegenüber dem Kunden behält sich die  vor, einen höheren Verzugsschaden nachzuweisen und geltend zu machen.<br />(6)    Der Kunde darf nur mit unbestrittenen oder rechtskräftigen Forderungen aufrechnen bzw. diese mit Forderungen der  verrechnen. Zurückbehaltungsrechte darf der Kunde nur ausüben, wenn sein Gegenanspruch unbestritten oder rechtskräftig festgestellt ist.<br /><br />§ 4 Fremdleistungen<br />(1)    Die  ist berechtigt, die zur Auftragserfüllung notwendigen Fremdleistungen im Namen und für Rechnung des Kunden zu bestellen. Der Kunde ist verpflichtet, der  hierzu schriftliche Vollmacht zu erteilen.<br /> <br /><br />(2)    Soweit im Einzelfall Verträge über Fremdleistungen im Namen und für Rechnung der  abgeschlossen werden, verpflichtet sich der Kunde, die  im Innenverhältnis von sämtlichen Verbindlichkeiten freizustellen, die sich aus dem Vertragsabschluss ergeben, insbesondere von der Verpflichtung zur Zahlung des Preises für die Fremdleistung.<br /><br />§ 4 Mitwirkungspflichten<br />(1)    Der Kunde hat dafür Sorge zu tragen, dass der  sämtliche relevanten Informationen zugänglich gemacht werden, die für die Beratungsleistung erforderlich sind oder von der  als erforderlich angesehen werden.<br />(2)    Die  verpflichtet sich, alle Geschäfts- und Betriebsgeheimnisse des Kunden vertraulich zu behandeln und gegen unbefugte Kenntnisnahme Dritter zu schützen. Dies gilt auch für Geschäfts- und Betriebsgeheimnisse anderer Firmen, die der  im Rahmen ihrer Tätigkeit für den Kunden bekannt geworden sind.<br />(3)    Soweit die  bei der Durchführung des Beratungsvertrages Informationen oder Unterlagen zur Verfügung gestellt werden, wird die  diese ebenfalls streng vertraulich behandeln und ausschließlich zur Erfüllung der geforderten Beratungsleistungen verwenden. Die Unterlagen werden nach Abschluss der Beratungsleistung dem Kunden unverzüglich ausgehändigt.<br /><br />§ 5 Haftung<br />(1)    Die  haftet unbeschränkt für vorsätzliche und grob fahrlässige Pflichtverletzungen ihrer gesetzlichen Vertreter und sonstigen Erfüllungsgehilfen.<br />(2)    Für einfach fahrlässige Verletzungen von wesentlichen Vertragspflichten haftet die  der Höhe nach nur für vertragstypische vorhersehbare Schäden. Die  haftet nicht bei leicht fahrlässiger Verletzung sonstiger Vertragspflichten.<br />(3)    Rügen und Beanstandungen gleich welcher Art sind innerhalb von zwei Wochen nach Lieferung schriftlich gegenüber der  geltend zu machen. Danach gilt das Werk als vertragsgemäß und mängelfrei abgenommen.<br /><br />§ 6 Gewährleistung/Mängelbeseitigung<br />(1)    Die  führt alle Arbeiten mit größter Sorgfalt und unter Beachtung allgemeiner branchenspezifischer Grundsätze sowie unter Beachtung allgemein anerkannter technischer, betriebswirtschaftlicher und ökologischer Grundsätze durch.<br />(2)    Alle Empfehlungen und Prognosen erfolgen nach bestem Wissen und Gewissen. Gewährleistungen für den Inhalt solcher Empfehlungen und Prognosen übernimmt die  nicht.<br />(3)    Die  bietet Gewähr für die Leistungen, soweit sie für diese gemäß § 5 der AGB die Haftung übernimmt. Soweit Leistungen der  mit Mängeln behaftet sind, hat der Kunde Anspruch auf Beseitigung. Er kann zunächst Nachbesserung verlangen. Kann der Mangel durch wiederholte Nachbesserung nicht beseitigt werden, so ist der Kunde berechtigt, hinsichtlich der mangelhaften Leistung vom Vertrag zurückzutreten oder eine angemessene Herabsetzung der Vergütung zu verlangen. Der Anspruch auf Ersatz der Kosten, die zur Herstellung der ordnungsgemäßen Leistungen anfallen, ist für beide Seiten ausgeschlossen.<br /><br />§ 7 Annahmeverzug und unterlassene Mitwirkung<br />    Kommt der Kunde mit der Annahme der von der  angebotenen Leistungen in Verzug oder unterlässt der Kunde eine ihm obliegende Mitwirkung, trotz Mahnung und Fristsetzung durch die , so ist die  zur fristlosen Kündigung des Vertrages berechtigt. Die  behält einen Anspruch auf Ersatz der ihr durch den Verzug entstandenen Mehraufwendungen sowie des entstandenen Schadens. Dies gilt auch, wenn die  von einem Kündigungsrecht keinen Gebrauch macht.<br /><br />§ 8 Schlussbestimmungen<br />(1)    Gerichtsstand für alle Streitigkeiten ist der Geschäftssitz der . Auch dann, wenn zum Zeitpunkt der Klageerhebung durch die  der Kunde keinen allgemeinen Gerichtsstand in der Bundesrepublik Deutschland hat oder der Sitz oder Wohnsitz oder gewöhnlicher Aufenthalt nicht bekannt ist, ist Gerichtsstand der Sitz der . Erfüllungsort ist Vlotho.<br />(2)    Für die Vertragsbeziehungen der Parteien gilt ausschließlich das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts-übereinkommens vom 11.04.1980.<br />(3)    Sollten einzelne Bestimmungen des Vertrages mit dem Kunden einschließlich dieser Allgemeinen Geschäftsbedingungen ganz oder teilweise unwirksam sein oder werden, so wird hierdurch die Gültigkeit der übrigen Bestimmungen nicht berührt. Die ganz oder teilweise unwirksame Regelung soll durch eine Regelung ersetzt werden, deren wirtschaftlicher Erfolg dem der unwirksamen möglichst nahe kommt.<br />Entsprechendes gilt für die Ausfüllung einer Lücke im Vertrag mit dem Kunden oder in diesen Allgemeinen Geschäftsbedingungen. <br /> </p>');
INSERT INTO `#__jshopping_config_statictext` (`id`, `alias`, `text_de-DE`, `text_en-GB`, `text_es-ES`, `text_it-IT`, `text_fr-FR`, `text_nl-NL`, `text_pl-PL`, `text_ru-RU`, `text_sv-SE`, `use_for_return_policy`, `text_fr-CA`) VALUES
(4, 'return_policy', '<div><strong>Belehrung über das Rückgaberecht bei Fernabsatzverträgen</strong></div>\r\n<div> </div>\r\n<div>(1) Rückgaberecht:</div>\r\n<div>Sie können die erhaltene Ware ohne Angabe von Gründen innerhalb von zwei Wochen durch Rücksendung der Ware zurückgegeben. </div>\r\n<div>Die Frist beginnt nach Erhalt dieser Belehrung in Textform (z.B. als Brief, Fax, E-Mail), jedoch nicht vor Eingang der Ware beim Empfänger (bei der wiederkehrenden Lieferung gleichartiger Waren nicht vor Eingang der ersten Teillieferung) und auch nicht vor Erfüllung unserer Informationspflichten gemäß Artikel 246 § 2 in Verbindung mit § 1 Abs. 1 und 2 EGBGB, sowie unserer Pflichten gemäß § 312e Abs. 1 Satz 1 BGB i.V.m. Artikel 246 § 3 EGBGB. </div>\r\n<div>Nur bei nicht paketversandfähiger Ware (z.B. bei sperrigen Gütern) können Sie die Rückgabe auch durch Rücknahmeverlangen in Textform erklären. Zur Wahrung der Frist genügt die rechtzeitige Absendung der Ware oder des Rücknahmeverlangens. In jedem Falle erfolgt die Rücksendung auf unsere Kosten und Gefahr. Die Rücksendung oder das Rücknahmeverlangen hat zu erfolgen an:</div>\r\n<div> </div>\r\n<div> GmbH, </div>\r\n<div>Fax: , E-Mail: .</div>\r\n<div> </div>\r\n<div>(2) Ein Rückgaberecht besteht nicht bei der Lieferung von Waren, die nach Spezifikationen des Kunden angefertigt werden oder eindeutig auf die persönlichen Bedürfnisse zugeschnitten sind oder die auf Grund ihrer Beschaffenheit nicht für eine Rücksendung geeignet sind.</div>\r\n<div> </div>\r\n<div>(3) Rückgabefolgen:</div>\r\n<div>Im Falle einer wirksamen Rückgabe sind die beiderseits empfangenen Leistungen zurückzu¬gewähren und ggf. gezogene Nutzungen herauszugeben. Bei einer Verschlechterung der Ware und Nutzungen (z.B. Gebrauchsvorteile), die nicht oder teilweise nicht oder nur in verschlechtertem Zustand herausgeben werden können, müssen Sie  uns insoweit Wertersatz leisten.</div>\r\n<div>Für die Verschlechterung der Ware und für gezogene Nutzungen müssen Sie  Wertersatz nur leisten, soweit die Nutzungen</div>\r\n<div>oder die Verschlechterung auf einen Umgang mit der Ware zurückzuführen ist, der über die Prüfung der Eigenschaften</div>\r\n<div>und der Funktionsweise hinausgeht. Unter „Prüfung der Eigenschaften und der Funktionsweise“ versteht man das Testen und Ausprobieren der jeweiligen Ware, wie es etwa im Ladengeschäft möglich und üblich ist.</div>\r\n<div>Im Übrigen können Sie die Pflicht zum Wertersatz für eine durch die bestimmungsgemäße Ingebrauchnahme der Sache entstandene Verschlechterung vermeiden, indem Sie die Ware nicht wie Ihr Eigentum in Gebrauch neh¬men und alles unterlassen, was deren Wert beeinträchtigt.</div>\r\n<div>Verpflichtungen zur Erstattung von Zahlungen müssen innerhalb von 30 Tagen erfüllt werden. Die Frist beginnt für Sie mit der Absendung der Ware oder des Rücknahmeverlangens, für uns mit dem Empfang.</div>\r\n<div> </div>\r\n<div>Auf Wunsch erhält der Kunde der  GmbH einen kostenlosen Retouren-Aufkleber für die Rücksendung der Ware. Hierfür genügt eine kurze E-Mail an .</div>\r\n<div> </div>\r\n<div><strong>Ende der Rückgabebelehrungen</strong></div>\r\n<div> </div>', '', '', '', '', '', '', '', '<div><strong>Belehrung über das Rückgaberecht bei Fernabsatzverträgen</strong></div>\r\n<div> </div>\r\n<div>(1) Rückgaberecht:</div>\r\n<div>Sie können die erhaltene Ware ohne Angabe von Gründen innerhalb von zwei Wochen durch Rücksendung der Ware zurückgegeben. </div>\r\n<div>Die Frist beginnt nach Erhalt dieser Belehrung in Textform (z.B. als Brief, Fax, E-Mail), jedoch nicht vor Eingang der Ware beim Empfänger (bei der wiederkehrenden Lieferung gleichartiger Waren nicht vor Eingang der ersten Teillieferung) und auch nicht vor Erfüllung unserer Informationspflichten gemäß Artikel 246 § 2 in Verbindung mit § 1 Abs. 1 und 2 EGBGB, sowie unserer Pflichten gemäß § 312e Abs. 1 Satz 1 BGB i.V.m. Artikel 246 § 3 EGBGB. </div>\r\n<div>Nur bei nicht paketversandfähiger Ware (z.B. bei sperrigen Gütern) können Sie die Rückgabe auch durch Rücknahmeverlangen in Textform erklären. Zur Wahrung der Frist genügt die rechtzeitige Absendung der Ware oder des Rücknahmeverlangens. In jedem Falle erfolgt die Rücksendung auf unsere Kosten und Gefahr. Die Rücksendung oder das Rücknahmeverlangen hat zu erfolgen an:</div>\r\n<div> </div>\r\n<div> GmbH, Valdorfer Straße 100, 32602 Vlotho, Deutschland,</div>\r\n<div>Fax: , E-Mail: .</div>\r\n<div> </div>\r\n<div>(2) Ein Rückgaberecht besteht nicht bei der Lieferung von Waren, die nach Spezifikationen des Kunden angefertigt werden oder eindeutig auf die persönlichen Bedürfnisse zugeschnitten sind oder die auf Grund ihrer Beschaffenheit nicht für eine Rücksendung geeignet sind.</div>\r\n<div> </div>\r\n<div>(3) Rückgabefolgen:</div>\r\n<div>Im Falle einer wirksamen Rückgabe sind die beiderseits empfangenen Leistungen zurückzu¬gewähren und ggf. gezogene Nutzungen herauszugeben. Bei einer Verschlechterung der Ware und Nutzungen (z.B. Gebrauchsvorteile), die nicht oder teilweise nicht oder nur in verschlechtertem Zustand herausgeben werden können, müssen Sie  uns insoweit Wertersatz leisten.</div>\r\n<div>Für die Verschlechterung der Ware und für gezogene Nutzungen müssen Sie  Wertersatz nur leisten, soweit die Nutzungen</div>\r\n<div>oder die Verschlechterung auf einen Umgang mit der Ware zurückzuführen ist, der über die Prüfung der Eigenschaften</div>\r\n<div>und der Funktionsweise hinausgeht. Unter „Prüfung der Eigenschaften und der Funktionsweise“ versteht man das Testen und Ausprobieren der jeweiligen Ware, wie es etwa im Ladengeschäft möglich und üblich ist.</div>\r\n<div>Im Übrigen können Sie die Pflicht zum Wertersatz für eine durch die bestimmungsgemäße Ingebrauchnahme der Sache entstandene Verschlechterung vermeiden, indem Sie die Ware nicht wie Ihr Eigentum in Gebrauch neh¬men und alles unterlassen, was deren Wert beeinträchtigt.</div>\r\n<div>Verpflichtungen zur Erstattung von Zahlungen müssen innerhalb von 30 Tagen erfüllt werden. Die Frist beginnt für Sie mit der Absendung der Ware oder des Rücknahmeverlangens, für uns mit dem Empfang.</div>\r\n<div> </div>\r\n<div>Auf Wunsch erhält der Kunde der  GmbH einen kostenlosen Retouren-Aufkleber für die Rücksendung der Ware. Hierfür genügt eine kurze E-Mail an .</div>\r\n<div> </div>\r\n<div><strong>Ende der Rückgabebelehrungen</strong></div>\r\n<div> </div>', 0, '<div><strong>Belehrung über das Rückgaberecht bei Fernabsatzverträgen</strong></div>\r\n<div> </div>\r\n<div>(1) Rückgaberecht:</div>\r\n<div>Sie können die erhaltene Ware ohne Angabe von Gründen innerhalb von zwei Wochen durch Rücksendung der Ware zurückgegeben. </div>\r\n<div>Die Frist beginnt nach Erhalt dieser Belehrung in Textform (z.B. als Brief, Fax, E-Mail), jedoch nicht vor Eingang der Ware beim Empfänger (bei der wiederkehrenden Lieferung gleichartiger Waren nicht vor Eingang der ersten Teillieferung) und auch nicht vor Erfüllung unserer Informationspflichten gemäß Artikel 246 § 2 in Verbindung mit § 1 Abs. 1 und 2 EGBGB, sowie unserer Pflichten gemäß § 312e Abs. 1 Satz 1 BGB i.V.m. Artikel 246 § 3 EGBGB. </div>\r\n<div>Nur bei nicht paketversandfähiger Ware (z.B. bei sperrigen Gütern) können Sie die Rückgabe auch durch Rücknahmeverlangen in Textform erklären. Zur Wahrung der Frist genügt die rechtzeitige Absendung der Ware oder des Rücknahmeverlangens. In jedem Falle erfolgt die Rücksendung auf unsere Kosten und Gefahr. Die Rücksendung oder das Rücknahmeverlangen hat zu erfolgen an:</div>\r\n<div> </div>\r\n<div> GmbH, ,</div>\r\n<div>Fax: , E-Mail: .</div>\r\n<div> </div>\r\n<div>(2) Ein Rückgaberecht besteht nicht bei der Lieferung von Waren, die nach Spezifikationen des Kunden angefertigt werden oder eindeutig auf die persönlichen Bedürfnisse zugeschnitten sind oder die auf Grund ihrer Beschaffenheit nicht für eine Rücksendung geeignet sind.</div>\r\n<div> </div>\r\n<div>(3) Rückgabefolgen:</div>\r\n<div>Im Falle einer wirksamen Rückgabe sind die beiderseits empfangenen Leistungen zurückzu¬gewähren und ggf. gezogene Nutzungen herauszugeben. Bei einer Verschlechterung der Ware und Nutzungen (z.B. Gebrauchsvorteile), die nicht oder teilweise nicht oder nur in verschlechtertem Zustand herausgeben werden können, müssen Sie  uns insoweit Wertersatz leisten.</div>\r\n<div>Für die Verschlechterung der Ware und für gezogene Nutzungen müssen Sie  Wertersatz nur leisten, soweit die Nutzungen</div>\r\n<div>oder die Verschlechterung auf einen Umgang mit der Ware zurückzuführen ist, der über die Prüfung der Eigenschaften</div>\r\n<div>und der Funktionsweise hinausgeht. Unter „Prüfung der Eigenschaften und der Funktionsweise“ versteht man das Testen und Ausprobieren der jeweiligen Ware, wie es etwa im Ladengeschäft möglich und üblich ist.</div>\r\n<div>Im Übrigen können Sie die Pflicht zum Wertersatz für eine durch die bestimmungsgemäße Ingebrauchnahme der Sache entstandene Verschlechterung vermeiden, indem Sie die Ware nicht wie Ihr Eigentum in Gebrauch neh¬men und alles unterlassen, was deren Wert beeinträchtigt.</div>\r\n<div>Verpflichtungen zur Erstattung von Zahlungen müssen innerhalb von 30 Tagen erfüllt werden. Die Frist beginnt für Sie mit der Absendung der Ware oder des Rücknahmeverlangens, für uns mit dem Empfang.</div>\r\n<div> </div>\r\n<div>Auf Wunsch erhält der Kunde der  GmbH einen kostenlosen Retouren-Aufkleber für die Rücksendung der Ware. Hierfür genügt eine kurze E-Mail an .</div>\r\n<div> </div>\r\n<div><strong>Ende der Rückgabebelehrungen</strong></div>\r\n<div> </div>'),
(5, 'order_email_descr', '', '', '', '', '', '', '', '', '', 0, ''),
(6, 'order_finish_descr', '', '', '', '', '', '', '', '', '', 1, ''),
(7, 'shipping', '<div>Mustertext der :</div>\r\n<div> </div>\r\n<div>Versandarten- und -kosten</div>\r\n<div> </div>\r\n<div>Unser Partner XXXX sorgt für einen sicheren und schnellen Transport an Ihre Wunschadresse. </div>\r\n<div>Egal ob nach Hause, ins Büro oder zur Abgabe bei den Nachbarn, die zuverlässige Zustellung hat für uns höchste Priorität. </div>\r\n<div>Ihre bestellte Ware wird immer sicher und bedarfsgerecht verpackt (Schutzfolien, Eckenschutz etc.). Transportschäden kommen so gut wie nicht vor. Sollte trotzdem einmal etwas beschädigt ankommen, bitten wir Sie, das Paket nicht anzunehmen und direkt an uns zurück zu schicken.</div>\r\n<div> </div>\r\n<div>Die Versand- und Verpackungskosten richten sich nach Abmessung und Gewicht der zu versendenden Ware (siehe Tabelle). Daraus ergeben sich drei Versandstaffelungen:</div>\r\n<div>Standard x,xx €</div>\r\n<div>Sperrgut x,xx €</div>\r\n<div>Spedition xx,xx €</div>\r\n<div> </div>\r\n<div>Expressversand per XXX xx,xx €</div>\r\n<div>Hierbei wird die Ware innerhalb Deutschlands bei einer Bestellung vor 12:00 Uhr am folgenden Werktag bis 12 Uhr (Montag bis Freitag) ausgeliefert. Bei einer Bestellung nach 12:00 Uhr erfolgt die Lieferung am übernächsten Werktag bis 12:00 Uhr.</div>\r\n<div> </div>\r\n<div>Bitte beachten Sie, dass die Lieferung an gesetzlichen Feiertagen (abhängig vom Bundesland) nicht erfolgen kann. </div>\r\n<div> </div>\r\n<div>Sollte die Bestellung Produkte enthalten, für die unterschiedliche Versandkosten berechnet werden, wird jeweils der höchste Versandkostenpreis einmalig berechnet.</div>\r\n<div> </div>\r\n<div> </div>\r\n<div> </div>\r\n<div>Die Versandkostenpreise in der Versandkostenübersicht gelten ausschließlich für folgende Produkte: Produkt 1, Produkt 2, Produkt 3, Produkt 4. </div>\r\n<div>Alle anderen Produkte wie Proukt 5 und Produkt 6 werden bis zu einem Gewicht von 30 kg per XXX Standardversand verschickt.</div>\r\n<div>Versandpartner ist XXX.</div>\r\n<div>Die entsprechende Versandnummer wird Ihnen mit der Versandbestätigung mitgeteilt. So können Sie jederzeit den Stand der Bearbeitung bzw. der Auslieferung unter www.XXX.de abrufen.</div>\r\n<div> </div>\r\n<div>Die Produkte werden in stabilen Versandkartonagen geliefert. Somit ist Ihre bestellte Ware bestmöglich geschützt.</div>\r\n<div>Wir können nicht nachträglich verschiedene Aufträge zu einer Lieferung zusammenfassen!</div>', '', '', '', '', '', '', '', '<div>Mustertext der :</div>\r\n<div> </div>\r\n<div>Versandarten- und -kosten</div>\r\n<div> </div>\r\n<div>Unser Partner XXXX sorgt für einen sicheren und schnellen Transport an Ihre Wunschadresse. </div>\r\n<div>Egal ob nach Hause, ins Büro oder zur Abgabe bei den Nachbarn, die zuverlässige Zustellung hat für uns höchste Priorität. </div>\r\n<div>Ihre bestellte Ware wird immer sicher und bedarfsgerecht verpackt (Schutzfolien, Eckenschutz etc.). Transportschäden kommen so gut wie nicht vor. Sollte trotzdem einmal etwas beschädigt ankommen, bitten wir Sie, das Paket nicht anzunehmen und direkt an uns zurück zu schicken.</div>\r\n<div> </div>\r\n<div>Die Versand- und Verpackungskosten richten sich nach Abmessung und Gewicht der zu versendenden Ware (siehe Tabelle). Daraus ergeben sich drei Versandstaffelungen:</div>\r\n<div>Standard x,xx €</div>\r\n<div>Sperrgut x,xx €</div>\r\n<div>Spedition xx,xx €</div>\r\n<div> </div>\r\n<div>Expressversand per XXX xx,xx €</div>\r\n<div>Hierbei wird die Ware innerhalb Deutschlands bei einer Bestellung vor 12:00 Uhr am folgenden Werktag bis 12 Uhr (Montag bis Freitag) ausgeliefert. Bei einer Bestellung nach 12:00 Uhr erfolgt die Lieferung am übernächsten Werktag bis 12:00 Uhr.</div>\r\n<div> </div>\r\n<div>Bitte beachten Sie, dass die Lieferung an gesetzlichen Feiertagen (abhängig vom Bundesland) nicht erfolgen kann. </div>\r\n<div> </div>\r\n<div>Sollte die Bestellung Produkte enthalten, für die unterschiedliche Versandkosten berechnet werden, wird jeweils der höchste Versandkostenpreis einmalig berechnet.</div>\r\n<div> </div>\r\n<div> </div>\r\n<div> </div>\r\n<div>Die Versandkostenpreise in der Versandkostenübersicht gelten ausschließlich für folgende Produkte: Produkt 1, Produkt 2, Produkt 3, Produkt 4. </div>\r\n<div>Alle anderen Produkte wie Proukt 5 und Produkt 6 werden bis zu einem Gewicht von 30 kg per XXX Standardversand verschickt.</div>\r\n<div>Versandpartner ist XXX.</div>\r\n<div>Die entsprechende Versandnummer wird Ihnen mit der Versandbestätigung mitgeteilt. So können Sie jederzeit den Stand der Bearbeitung bzw. der Auslieferung unter www.XXX.de abrufen.</div>\r\n<div> </div>\r\n<div>Die Produkte werden in stabilen Versandkartonagen geliefert. Somit ist Ihre bestellte Ware bestmöglich geschützt.</div>\r\n<div>Wir können nicht nachträglich verschiedene Aufträge zu einer Lieferung zusammenfassen!</div>', 0, '<div>Mustertext der :</div>\r\n<div> </div>\r\n<div>Versandarten- und -kosten</div>\r\n<div> </div>\r\n<div>Unser Partner XXXX sorgt für einen sicheren und schnellen Transport an Ihre Wunschadresse. </div>\r\n<div>Egal ob nach Hause, ins Büro oder zur Abgabe bei den Nachbarn, die zuverlässige Zustellung hat für uns höchste Priorität. </div>\r\n<div>Ihre bestellte Ware wird immer sicher und bedarfsgerecht verpackt (Schutzfolien, Eckenschutz etc.). Transportschäden kommen so gut wie nicht vor. Sollte trotzdem einmal etwas beschädigt ankommen, bitten wir Sie, das Paket nicht anzunehmen und direkt an uns zurück zu schicken.</div>\r\n<div> </div>\r\n<div>Die Versand- und Verpackungskosten richten sich nach Abmessung und Gewicht der zu versendenden Ware (siehe Tabelle). Daraus ergeben sich drei Versandstaffelungen:</div>\r\n<div>Standard x,xx €</div>\r\n<div>Sperrgut x,xx €</div>\r\n<div>Spedition xx,xx €</div>\r\n<div> </div>\r\n<div>Expressversand per XXX xx,xx €</div>\r\n<div>Hierbei wird die Ware innerhalb Deutschlands bei einer Bestellung vor 12:00 Uhr am folgenden Werktag bis 12 Uhr (Montag bis Freitag) ausgeliefert. Bei einer Bestellung nach 12:00 Uhr erfolgt die Lieferung am übernächsten Werktag bis 12:00 Uhr.</div>\r\n<div> </div>\r\n<div>Bitte beachten Sie, dass die Lieferung an gesetzlichen Feiertagen (abhängig vom Bundesland) nicht erfolgen kann. </div>\r\n<div> </div>\r\n<div>Sollte die Bestellung Produkte enthalten, für die unterschiedliche Versandkosten berechnet werden, wird jeweils der höchste Versandkostenpreis einmalig berechnet.</div>\r\n<div> </div>\r\n<div> </div>\r\n<div> </div>\r\n<div>Die Versandkostenpreise in der Versandkostenübersicht gelten ausschließlich für folgende Produkte: Produkt 1, Produkt 2, Produkt 3, Produkt 4. </div>\r\n<div>Alle anderen Produkte wie Proukt 5 und Produkt 6 werden bis zu einem Gewicht von 30 kg per XXX Standardversand verschickt.</div>\r\n<div>Versandpartner ist XXX.</div>\r\n<div>Die entsprechende Versandnummer wird Ihnen mit der Versandbestätigung mitgeteilt. So können Sie jederzeit den Stand der Bearbeitung bzw. der Auslieferung unter www.XXX.de abrufen.</div>\r\n<div> </div>\r\n<div>Die Produkte werden in stabilen Versandkartonagen geliefert. Somit ist Ihre bestellte Ware bestmöglich geschützt.</div>\r\n<div>Wir können nicht nachträglich verschiedene Aufträge zu einer Lieferung zusammenfassen!</div>'),
(8, 'order_email_descr_end', '', '', '', '', '', '', '', '', '', 0, ''),
(9, 'privacy_statement', '', '', '', '', '', '', '', '', '', 0, ''),
(10, 'cart', '', '', '', '', '', '', '', '', '', 0, '');

CREATE TABLE `#__jshopping_content` (
  `id` int(11) NOT NULL,
  `lang` text NOT NULL,
  `content` text NOT NULL,
  `link` text NOT NULL,
  `type` TINYINT(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_countries` (
  `country_id` int(11) NOT NULL,
  `country_publish` tinyint(4) NOT NULL,
  `ordering` smallint(6) NOT NULL,
  `country_code` varchar(5) NOT NULL,
  `country_code_2` varchar(5) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_countries` (`country_id`, `country_publish`, `ordering`, `country_code`, `country_code_2`, `name_en-GB`, `name_de-DE`, `name_es-ES`, `name_it-IT`, `name_fr-FR`, `name_nl-NL`, `name_pl-PL`, `name_ru-RU`, `name_sv-SE`, `name_fr-CA`) VALUES
(1, 1, 1, 'AFG', 'AF', 'Afghanistan', 'Afghanistan', 'Afghanistan', 'Afghanistan', 'Afghanistan', 'Afghanistan', 'Afghanistan', 'Afghanistan', 'Afghanistan', 'Afghanistan'),
(2, 1, 2, 'ALB', 'AL', 'Albania', 'Albanien', 'Albania', 'Albania', 'Albanien', 'Albanien', 'Albanien', 'Albanien', 'Albanien', 'Albanien'),
(3, 1, 3, 'DZA', 'DZ', 'Algeria', 'Algerien', 'Algeria', 'Algeria', 'Algerien', 'Algerien', 'Algerien', 'Algerien', 'Algerien', 'Algerien'),
(4, 1, 4, 'ASM', 'AS', 'American Samoa', 'Amerikanisch-Samoa', 'American Samoa', 'American Samoa', 'Amerikanisch-Samoa', 'Amerikanisch-Samoa', 'Amerikanisch-Samoa', 'Amerikanisch-Samoa', 'Amerikanisch-Samoa', 'Amerikanisch-Samoa'),
(5, 1, 5, 'AND', 'AD', 'Andorra', 'Andorra', 'Andorra', 'Andorra', 'Andorra', 'Andorra', 'Andorra', 'Andorra', 'Andorra', 'Andorra'),
(6, 1, 6, 'AGO', 'AO', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola', 'Angola'),
(7, 1, 7, 'AIA', 'AI', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla', 'Anguilla'),
(8, 1, 8, 'ATA', 'AQ', 'Antarctica', 'Antarktis', 'Antarctica', 'Antarctica', 'Antarktis', 'Antarktis', 'Antarktis', 'Antarktis', 'Antarktis', 'Antarktis'),
(9, 1, 9, 'ATG', 'AG', 'Antigua and Barbuda', 'Antigua und Barbuda', 'Antigua and Barbuda', 'Antigua and Barbuda', 'Antigua und Barbuda', 'Antigua und Barbuda', 'Antigua und Barbuda', 'Antigua und Barbuda', 'Antigua und Barbuda', 'Antigua und Barbuda'),
(10, 1, 10, 'ARG', 'AR', 'Argentina', 'Argentinien', 'Argentina', 'Argentina', 'Argentinien', 'Argentinien', 'Argentinien', 'Argentinien', 'Argentinien', 'Argentinien'),
(11, 1, 11, 'ARM', 'AM', 'Armenia', 'Armenien', 'Armenia', 'Armenia', 'Armenien', 'Armenien', 'Armenien', 'Armenien', 'Armenien', 'Armenien'),
(12, 1, 12, 'ABW', 'AW', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba', 'Aruba'),
(13, 1, 13, 'AUS', 'AU', 'Australia', 'Australien', 'Australia', 'Australia', 'Australien', 'Australien', 'Australien', 'Australien', 'Australien', 'Australien'),
(14, 1, 14, 'AUT', 'AT', 'Austria', 'Österreich', 'Austria', 'Austria', 'Österreich', 'Österreich', 'Österreich', 'Österreich', 'Österreich', 'Österreich'),
(15, 1, 15, 'AZE', 'AZ', 'Azerbaijan', 'Aserbaidschan', 'Azerbaijan', 'Azerbaijan', 'Aserbaidschan', 'Aserbaidschan', 'Aserbaidschan', 'Aserbaidschan', 'Aserbaidschan', 'Aserbaidschan'),
(16, 1, 16, 'BHS', 'BS', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas', 'Bahamas'),
(17, 1, 17, 'BHR', 'BH', 'Bahrain', 'Bahrain', 'Bahrain', 'Bahrain', 'Bahrain', 'Bahrain', 'Bahrain', 'Bahrain', 'Bahrain', 'Bahrain'),
(18, 1, 18, 'BGD', 'BD', 'Bangladesh', 'Bangladesch', 'Bangladesh', 'Bangladesh', 'Bangladesch', 'Bangladesch', 'Bangladesch', 'Bangladesch', 'Bangladesch', 'Bangladesch'),
(19, 1, 19, 'BRB', 'BB', 'Barbados', 'Barbados', 'Barbados', 'Barbados', 'Barbados', 'Barbados', 'Barbados', 'Barbados', 'Barbados', 'Barbados'),
(20, 1, 20, 'BLR', 'BY', 'Belarus', 'Weissrussland', 'Belarus', 'Belarus', 'Weissrussland', 'Weissrussland', 'Weissrussland', 'Weissrussland', 'Weissrussland', 'Weissrussland'),
(21, 1, 21, 'BEL', 'BE', 'Belgium', 'Belgien', 'Belgium', 'Belgium', 'Belgien', 'Belgien', 'Belgien', 'Belgien', 'Belgien', 'Belgien'),
(22, 1, 22, 'BLZ', 'BZ', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize', 'Belize'),
(23, 1, 23, 'BEN', 'BJ', 'Benin', 'Benin', 'Benin', 'Benin', 'Benin', 'Benin', 'Benin', 'Benin', 'Benin', 'Benin'),
(24, 1, 24, 'BMU', 'BM', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda', 'Bermuda'),
(25, 1, 25, 'BTN', 'BT', 'Bhutan', 'Bhutan', 'Bhutan', 'Bhutan', 'Bhutan', 'Bhutan', 'Bhutan', 'Bhutan', 'Bhutan', 'Bhutan'),
(26, 1, 26, 'BOL', 'BO', 'Bolivia', 'Bolivien', 'Bolivia', 'Bolivia', 'Bolivien', 'Bolivien', 'Bolivien', 'Bolivien', 'Bolivien', 'Bolivien'),
(27, 1, 27, 'BIH', 'BA', 'Bosnia and Herzegowina', 'Bosnien und Herzegowina', 'Bosnia and Herzegowina', 'Bosnia and Herzegowina', 'Bosnien und Herzegowina', 'Bosnien und Herzegowina', 'Bosnien und Herzegowina', 'Bosnien und Herzegowina', 'Bosnien und Herzegowina', 'Bosnien und Herzegowina'),
(28, 1, 28, 'BWA', 'BW', 'Botswana', 'Botsuana', 'Botswana', 'Botswana', 'Botsuana', 'Botsuana', 'Botsuana', 'Botsuana', 'Botsuana', 'Botsuana'),
(29, 1, 29, 'BVT', 'BV', 'Bouvet Island', 'Bouvetinsel', 'Bouvet Island', 'Bouvet Island', 'Bouvetinsel', 'Bouvetinsel', 'Bouvetinsel', 'Bouvetinsel', 'Bouvetinsel', 'Bouvetinsel'),
(30, 1, 30, 'BRA', 'BR', 'Brazil', 'Brasilien', 'Brazil', 'Brazil', 'Brasilien', 'Brasilien', 'Brasilien', 'Brasilien', 'Brasilien', 'Brasilien'),
(31, 1, 31, 'IOT', 'IO', 'British Indian Ocean Territory', 'Britisches Territorium im Indischen Ozean', 'British Indian Ocean Territory', 'British Indian Ocean Territory', 'Britisches Territorium im Indischen Ozean', 'Britisches Territorium im Indischen Ozean', 'Britisches Territorium im Indischen Ozean', 'Britisches Territorium im Indischen Ozean', 'Britisches Territorium im Indischen Ozean', 'Britisches Territorium im Indischen Ozean'),
(32, 1, 32, 'BRN', 'BN', 'Brunei Darussalam', 'Brunei', 'Brunei Darussalam', 'Brunei Darussalam', 'Brunei', 'Brunei', 'Brunei', 'Brunei', 'Brunei', 'Brunei'),
(33, 1, 33, 'BGR', 'BG', 'Bulgaria', 'Bulgarien', 'Bulgaria', 'Bulgaria', 'Bulgarien', 'Bulgarien', 'Bulgarien', 'Bulgarien', 'Bulgarien', 'Bulgarien'),
(34, 1, 34, 'BFA', 'BF', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso', 'Burkina Faso'),
(35, 1, 35, 'BDI', 'BI', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi', 'Burundi'),
(36, 1, 36, 'KHM', 'KH', 'Cambodia', 'Kambodscha', 'Cambodia', 'Cambodia', 'Kambodscha', 'Kambodscha', 'Kambodscha', 'Kambodscha', 'Kambodscha', 'Kambodscha'),
(37, 1, 37, 'CMR', 'CM', 'Cameroon', 'Kamerun', 'Cameroon', 'Cameroon', 'Kamerun', 'Kamerun', 'Kamerun', 'Kamerun', 'Kamerun', 'Kamerun'),
(38, 1, 38, 'CAN', 'CA', 'Canada', 'Kanada', 'Canada', 'Canada', 'Kanada', 'Kanada', 'Kanada', 'Kanada', 'Kanada', 'Kanada'),
(39, 1, 39, 'CPV', 'CV', 'Cape Verde', 'Kap Verde', 'Cape Verde', 'Cape Verde', 'Kap Verde', 'Kap Verde', 'Kap Verde', 'Kap Verde', 'Kap Verde', 'Kap Verde'),
(40, 1, 40, 'CYM', 'KY', 'Cayman Islands', 'Cayman-Inseln', 'Cayman Islands', 'Cayman Islands', 'Cayman-Inseln', 'Cayman-Inseln', 'Cayman-Inseln', 'Cayman-Inseln', 'Cayman-Inseln', 'Cayman-Inseln'),
(41, 1, 41, 'CAF', 'CF', 'Central African Republic', 'Zentralafrikanische Republik', 'Central African Republic', 'Central African Republic', 'Zentralafrikanische Republik', 'Zentralafrikanische Republik', 'Zentralafrikanische Republik', 'Zentralafrikanische Republik', 'Zentralafrikanische Republik', 'Zentralafrikanische Republik'),
(42, 1, 42, 'TCD', 'TD', 'Chad', 'Tschad', 'Chad', 'Chad', 'Tschad', 'Tschad', 'Tschad', 'Tschad', 'Tschad', 'Tschad'),
(43, 1, 43, 'CHL', 'CL', 'Chile', 'Chile', 'Chile', 'Chile', 'Chile', 'Chile', 'Chile', 'Chile', 'Chile', 'Chile'),
(44, 1, 44, 'CHN', 'CN', 'China', 'China', 'China', 'China', 'China', 'China', 'China', 'China', 'China', 'China'),
(45, 1, 45, 'CXR', 'CX', 'Christmas Island', 'Christmas Island', 'Christmas Island', 'Christmas Island', 'Christmas Island', 'Christmas Island', 'Christmas Island', 'Christmas Island', 'Christmas Island', 'Christmas Island'),
(46, 1, 46, 'CCK', 'CC', 'Cocos (Keeling) Islands', 'Kokosinseln (Keeling)', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', 'Kokosinseln (Keeling)', 'Kokosinseln (Keeling)', 'Kokosinseln (Keeling)', 'Kokosinseln (Keeling)', 'Kokosinseln (Keeling)', 'Kokosinseln (Keeling)'),
(47, 1, 47, 'COL', 'CO', 'Colombia', 'Kolumbien', 'Colombia', 'Colombia', 'Kolumbien', 'Kolumbien', 'Kolumbien', 'Kolumbien', 'Kolumbien', 'Kolumbien'),
(48, 1, 48, 'COM', 'KM', 'Comoros', 'Komoren', 'Comoros', 'Comoros', 'Komoren', 'Komoren', 'Komoren', 'Komoren', 'Komoren', 'Komoren'),
(49, 1, 49, 'COG', 'CG', 'Congo', 'Kongo, Republik', 'Congo', 'Congo', 'Kongo, Republik', 'Kongo, Republik', 'Kongo, Republik', 'Kongo, Republik', 'Kongo, Republik', 'Kongo, Republik'),
(50, 1, 50, 'COK', 'CK', 'Cook Islands', 'Cookinseln', 'Cook Islands', 'Cook Islands', 'Cookinseln', 'Cookinseln', 'Cookinseln', 'Cookinseln', 'Cookinseln', 'Cookinseln'),
(51, 1, 51, 'CRI', 'CR', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica', 'Costa Rica'),
(52, 1, 52, 'CIV', 'CI', 'Cote D\'Ivoire', 'Elfenbeinküste', 'Cote D\'Ivoire', 'Cote D\'Ivoire', 'Elfenbeinküste', 'Elfenbeinküste', 'Elfenbeinküste', 'Elfenbeinküste', 'Elfenbeinküste', 'Elfenbeinküste'),
(53, 1, 53, 'HRV', 'HR', 'Croatia', 'Kroatien', 'Croatia', 'Croatia', 'Kroatien', 'Kroatien', 'Kroatien', 'Kroatien', 'Kroatien', 'Kroatien'),
(54, 1, 54, 'CUB', 'CU', 'Cuba', 'Kuba', 'Cuba', 'Cuba', 'Kuba', 'Kuba', 'Kuba', 'Kuba', 'Kuba', 'Kuba'),
(55, 1, 55, 'CYP', 'CY', 'Cyprus', 'Zypern', 'Cyprus', 'Cyprus', 'Zypern', 'Zypern', 'Zypern', 'Zypern', 'Zypern', 'Zypern'),
(56, 1, 56, 'CZE', 'CZ', 'Czech Republic', 'Tschechien', 'Czech Republic', 'Czech Republic', 'Tschechien', 'Tschechien', 'Tschechien', 'Tschechien', 'Tschechien', 'Tschechien'),
(57, 1, 57, 'DNK', 'DK', 'Denmark', 'Dänemark', 'Denmark', 'Denmark', 'Dänemark', 'Dänemark', 'Dänemark', 'Dänemark', 'Dänemark', 'Dänemark'),
(58, 1, 58, 'DJI', 'DJ', 'Djibouti', 'Dschibuti', 'Djibouti', 'Djibouti', 'Dschibuti', 'Dschibuti', 'Dschibuti', 'Dschibuti', 'Dschibuti', 'Dschibuti'),
(59, 1, 59, 'DMA', 'DM', 'Dominica', 'Dominica', 'Dominica', 'Dominica', 'Dominica', 'Dominica', 'Dominica', 'Dominica', 'Dominica', 'Dominica'),
(60, 1, 60, 'DOM', 'DO', 'Dominican Republic', 'Dominikanische Republik', 'Dominican Republic', 'Dominican Republic', 'Dominikanische Republik', 'Dominikanische Republik', 'Dominikanische Republik', 'Dominikanische Republik', 'Dominikanische Republik', 'Dominikanische Republik'),
(61, 1, 61, 'TMP', 'TL', 'East Timor', 'Osttimor', 'East Timor', 'East Timor', 'Osttimor', 'Osttimor', 'Osttimor', 'Osttimor', 'Osttimor', 'Osttimor'),
(62, 1, 62, 'ECU', 'EC', 'Ecuador', 'Ecuador', 'Ecuador', 'Ecuador', 'Ecuador', 'Ecuador', 'Ecuador', 'Ecuador', 'Ecuador', 'Ecuador'),
(63, 1, 63, 'EGY', 'EG', 'Egypt', 'Ägypten', 'Egypt', 'Egypt', 'Ägypten', 'Ägypten', 'Ägypten', 'Ägypten', 'Ägypten', 'Ägypten'),
(64, 1, 64, 'SLV', 'SV', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador', 'El Salvador'),
(65, 1, 65, 'GNQ', 'GQ', 'Equatorial Guinea', 'Äquatorial-Guinea', 'Equatorial Guinea', 'Equatorial Guinea', 'Äquatorial-Guinea', 'Äquatorial-Guinea', 'Äquatorial-Guinea', 'Äquatorial-Guinea', 'Äquatorial-Guinea', 'Äquatorial-Guinea'),
(66, 1, 66, 'ERI', 'ER', 'Eritrea', 'Eritrea', 'Eritrea', 'Eritrea', 'Eritrea', 'Eritrea', 'Eritrea', 'Eritrea', 'Eritrea', 'Eritrea'),
(67, 1, 67, 'EST', 'EE', 'Estonia', 'Estland', 'Estonia', 'Estonia', 'Estland', 'Estland', 'Estland', 'Estland', 'Estland', 'Estland'),
(68, 1, 68, 'ETH', 'ET', 'Ethiopia', 'Äthiopien', 'Ethiopia', 'Ethiopia', 'Äthiopien', 'Äthiopien', 'Äthiopien', 'Äthiopien', 'Äthiopien', 'Äthiopien'),
(69, 1, 69, 'FLK', 'FK', 'Falkland Islands (Malvinas)', 'Falklandinseln', 'Falkland Islands (Malvinas)', 'Falkland Islands (Malvinas)', 'Falklandinseln', 'Falklandinseln', 'Falklandinseln', 'Falklandinseln', 'Falklandinseln', 'Falklandinseln'),
(70, 1, 70, 'FRO', 'FO', 'Faroe Islands', 'Färöer', 'Faroe Islands', 'Faroe Islands', 'Färöer', 'Färöer', 'Färöer', 'Färöer', 'Färöer', 'Färöer'),
(71, 1, 71, 'FJI', 'FJ', 'Fiji', 'Fidschi', 'Fiji', 'Fiji', 'Fidschi', 'Fidschi', 'Fidschi', 'Fidschi', 'Fidschi', 'Fidschi'),
(72, 1, 72, 'FIN', 'FI', 'Finland', 'Finnland', 'Finland', 'Finland', 'Finnland', 'Finnland', 'Finnland', 'Finnland', 'Finnland', 'Finnland'),
(73, 1, 73, 'FRA', 'FR', 'France', 'Frankreich', 'France', 'France', 'Frankreich', 'Frankreich', 'Frankreich', 'Frankreich', 'Frankreich', 'Frankreich'),
(74, 1, 74, 'FXX', 'FX', 'France Metropolitan', 'Frankreich, Metropolitan', 'France Metropolitan', 'France Metropolitan', 'Frankreich, Metropolitan', 'Frankreich, Metropolitan', 'Frankreich, Metropolitan', 'Frankreich, Metropolitan', 'Frankreich, Metropolitan', 'Frankreich, Metropolitan'),
(75, 1, 75, 'GUF', 'GF', 'French Guiana', 'Französisch-Guyana', 'French Guiana', 'French Guiana', 'Französisch-Guyana', 'Französisch-Guyana', 'Französisch-Guyana', 'Französisch-Guyana', 'Französisch-Guyana', 'Französisch-Guyana'),
(76, 1, 76, 'PYF', 'PF', 'French Polynesia', 'Franz. Polynesien', 'French Polynesia', 'French Polynesia', 'Franz. Polynesien', 'Franz. Polynesien', 'Franz. Polynesien', 'Franz. Polynesien', 'Franz. Polynesien', 'Franz. Polynesien'),
(77, 1, 77, 'ATF', 'TF', 'French Southern Territories', 'Französiche Süd- und Antarktisgebiete', 'French Southern Territories', 'French Southern Territories', 'Französiche Süd- und Antarktisgebiete', 'Französiche Süd- und Antarktisgebiete', 'Französiche Süd- und Antarktisgebiete', 'Französiche Süd- und Antarktisgebiete', 'Französiche Süd- und Antarktisgebiete', 'Französiche Süd- und Antarktisgebiete'),
(78, 1, 78, 'GAB', 'GA', 'Gabon', 'Gabun', 'Gabon', 'Gabon', 'Gabun', 'Gabun', 'Gabun', 'Gabun', 'Gabun', 'Gabun'),
(79, 1, 79, 'GMB', 'GM', 'Gambia', 'Gambia', 'Gambia', 'Gambia', 'Gambia', 'Gambia', 'Gambia', 'Gambia', 'Gambia', 'Gambia'),
(80, 1, 80, 'GEO', 'GE', 'Georgia', 'Georgien', 'Georgia', 'Georgia', 'Georgien', 'Georgien', 'Georgien', 'Georgien', 'Georgien', 'Georgien'),
(81, 1, 81, 'DEU', 'DE', 'Germany', 'Deutschland', 'Germany', 'Germany', 'Deutschland', 'Deutschland', 'Deutschland', 'Deutschland', 'Deutschland', 'Deutschland'),
(82, 1, 82, 'GHA', 'GH', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana', 'Ghana'),
(83, 1, 83, 'GIB', 'GI', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar', 'Gibraltar'),
(84, 1, 84, 'GRC', 'GR', 'Greece', 'Griechenland', 'Greece', 'Greece', 'Griechenland', 'Griechenland', 'Griechenland', 'Griechenland', 'Griechenland', 'Griechenland'),
(85, 1, 85, 'GRL', 'GL', 'Greenland', 'Grönland', 'Greenland', 'Greenland', 'Grönland', 'Grönland', 'Grönland', 'Grönland', 'Grönland', 'Grönland'),
(86, 1, 86, 'GRD', 'GD', 'Grenada', 'Grenada', 'Grenada', 'Grenada', 'Grenada', 'Grenada', 'Grenada', 'Grenada', 'Grenada', 'Grenada'),
(87, 1, 87, 'GLP', 'GP', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe', 'Guadeloupe'),
(88, 1, 88, 'GUM', 'GU', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam', 'Guam'),
(89, 1, 89, 'GTM', 'GT', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala', 'Guatemala'),
(90, 1, 90, 'GIN', 'GN', 'Guinea', 'Guinea', 'Guinea', 'Guinea', 'Guinea', 'Guinea', 'Guinea', 'Guinea', 'Guinea', 'Guinea'),
(91, 1, 91, 'GNB', 'GW', 'Guinea-bissau', 'Guinea-Bissau', 'Guinea-bissau', 'Guinea-bissau', 'Guinea-Bissau', 'Guinea-Bissau', 'Guinea-Bissau', 'Guinea-Bissau', 'Guinea-Bissau', 'Guinea-Bissau'),
(92, 1, 92, 'GUY', 'GY', 'Guyana', 'Guyana', 'Guyana', 'Guyana', 'Guyana', 'Guyana', 'Guyana', 'Guyana', 'Guyana', 'Guyana'),
(93, 1, 93, 'HTI', 'HT', 'Haiti', 'Haiti', 'Haiti', 'Haiti', 'Haiti', 'Haiti', 'Haiti', 'Haiti', 'Haiti', 'Haiti'),
(94, 1, 94, 'HMD', 'HM', 'Heard and Mc Donald Islands', 'Heard und McDonaldinseln', 'Heard and Mc Donald Islands', 'Heard and Mc Donald Islands', 'Heard und McDonaldinseln', 'Heard und McDonaldinseln', 'Heard und McDonaldinseln', 'Heard und McDonaldinseln', 'Heard und McDonaldinseln', 'Heard und McDonaldinseln'),
(95, 1, 95, 'HND', 'HN', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras', 'Honduras'),
(96, 1, 96, 'HKG', 'HK', 'Hong Kong', 'Hong Kong', 'Hong Kong', 'Hong Kong', 'Hong Kong', 'Hong Kong', 'Hong Kong', 'Hong Kong', 'Hong Kong', 'Hong Kong'),
(97, 1, 97, 'HUN', 'HU', 'Hungary', 'Ungarn', 'Hungary', 'Hungary', 'Ungarn', 'Ungarn', 'Ungarn', 'Ungarn', 'Ungarn', 'Ungarn'),
(98, 1, 98, 'ISL', 'IS', 'Iceland', 'Island', 'Iceland', 'Iceland', 'Island', 'Island', 'Island', 'Island', 'Island', 'Island'),
(99, 1, 99, 'IND', 'IN', 'India', 'Indien', 'India', 'India', 'Indien', 'Indien', 'Indien', 'Indien', 'Indien', 'Indien'),
(100, 1, 100, 'IDN', 'ID', 'Indonesia', 'Indonesien', 'Indonesia', 'Indonesia', 'Indonesien', 'Indonesien', 'Indonesien', 'Indonesien', 'Indonesien', 'Indonesien'),
(101, 1, 101, 'IRN', 'IR', 'Iran (Islamic Republic of)', 'Iran', 'Iran (Islamic Republic of)', 'Iran (Islamic Republic of)', 'Iran', 'Iran', 'Iran', 'Iran', 'Iran', 'Iran'),
(102, 1, 102, 'IRQ', 'IQ', 'Iraq', 'Irak', 'Iraq', 'Iraq', 'Irak', 'Irak', 'Irak', 'Irak', 'Irak', 'Irak'),
(103, 1, 103, 'IRL', 'IE', 'Ireland', 'Irland', 'Ireland', 'Ireland', 'Irland', 'Irland', 'Irland', 'Irland', 'Irland', 'Irland'),
(104, 1, 104, 'ISR', 'IL', 'Israel', 'Israel', 'Israel', 'Israel', 'Israel', 'Israel', 'Israel', 'Israel', 'Israel', 'Israel'),
(105, 1, 105, 'ITA', 'IT', 'Italy', 'Italien', 'Italy', 'Italy', 'Italien', 'Italien', 'Italien', 'Italien', 'Italien', 'Italien'),
(106, 1, 106, 'JAM', 'JM', 'Jamaica', 'Jamaika', 'Jamaica', 'Jamaica', 'Jamaika', 'Jamaika', 'Jamaika', 'Jamaika', 'Jamaika', 'Jamaika'),
(107, 1, 107, 'JPN', 'JP', 'Japan', 'Japan', 'Japan', 'Japan', 'Japan', 'Japan', 'Japan', 'Japan', 'Japan', 'Japan'),
(108, 1, 108, 'JOR', 'JO', 'Jordan', 'Jordanien', 'Jordan', 'Jordan', 'Jordanien', 'Jordanien', 'Jordanien', 'Jordanien', 'Jordanien', 'Jordanien'),
(109, 1, 109, 'KAZ', 'KZ', 'Kazakhstan', 'Kasachstan', 'Kazakhstan', 'Kazakhstan', 'Kasachstan', 'Kasachstan', 'Kasachstan', 'Kasachstan', 'Kasachstan', 'Kasachstan'),
(110, 1, 110, 'KEN', 'KE', 'Kenya', 'Kenia', 'Kenya', 'Kenya', 'Kenia', 'Kenia', 'Kenia', 'Kenia', 'Kenia', 'Kenia'),
(111, 1, 111, 'KIR', 'KI', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati', 'Kiribati'),
(112, 1, 112, 'PRK', 'KP', 'Korea Democratic People\'s Republic of', 'Korea Demokratische Volksrepublik', 'Korea Democratic People\'s Republic of', 'Korea Democratic People\'s Republic of', 'Korea Demokratische Volksrepublik', 'Korea Demokratische Volksrepublik', 'Korea Demokratische Volksrepublik', 'Korea Demokratische Volksrepublik', 'Korea Demokratische Volksrepublik', 'Korea Demokratische Volksrepublik'),
(113, 1, 113, 'KOR', 'KR', 'Korea Republic of', 'Korea', 'Korea Republic of', 'Korea Republic of', 'Korea', 'Korea', 'Korea', 'Korea', 'Korea', 'Korea'),
(114, 1, 114, 'KWT', 'KW', 'Kuwait', 'Kuwait', 'Kuwait', 'Kuwait', 'Kuwait', 'Kuwait', 'Kuwait', 'Kuwait', 'Kuwait', 'Kuwait'),
(115, 1, 115, 'KGZ', 'KG', 'Kyrgyzstan', 'Kirgistan', 'Kyrgyzstan', 'Kyrgyzstan', 'Kirgistan', 'Kirgistan', 'Kirgistan', 'Kirgistan', 'Kirgistan', 'Kirgistan'),
(116, 1, 116, 'LAO', 'LA', 'Lao People\'s Democratic Republic', 'Laos', 'Lao People\'s Democratic Republic', 'Lao People\'s Democratic Republic', 'Laos', 'Laos', 'Laos', 'Laos', 'Laos', 'Laos'),
(117, 1, 117, 'LVA', 'LV', 'Latvia', 'Lettland', 'Latvia', 'Latvia', 'Lettland', 'Lettland', 'Lettland', 'Lettland', 'Lettland', 'Lettland'),
(118, 1, 118, 'LBN', 'LB', 'Lebanon', 'Libanon', 'Lebanon', 'Lebanon', 'Libanon', 'Libanon', 'Libanon', 'Libanon', 'Libanon', 'Libanon'),
(119, 1, 119, 'LSO', 'LS', 'Lesotho', 'Lesotho', 'Lesotho', 'Lesotho', 'Lesotho', 'Lesotho', 'Lesotho', 'Lesotho', 'Lesotho', 'Lesotho'),
(120, 1, 120, 'LBR', 'LR', 'Liberia', 'Liberia', 'Liberia', 'Liberia', 'Liberia', 'Liberia', 'Liberia', 'Liberia', 'Liberia', 'Liberia'),
(121, 1, 121, 'LBY', 'LY', 'Libyan Arab Jamahiriya', 'Libyen', 'Libyan Arab Jamahiriya', 'Libyan Arab Jamahiriya', 'Libyen', 'Libyen', 'Libyen', 'Libyen', 'Libyen', 'Libyen'),
(122, 1, 122, 'LIE', 'LI', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein', 'Liechtenstein'),
(123, 1, 123, 'LTU', 'LT', 'Lithuania', 'Litauen', 'Lithuania', 'Lithuania', 'Litauen', 'Litauen', 'Litauen', 'Litauen', 'Litauen', 'Litauen'),
(124, 1, 124, 'LUX', 'LU', 'Luxembourg', 'Luxemburg', 'Luxembourg', 'Luxembourg', 'Luxemburg', 'Luxemburg', 'Luxemburg', 'Luxemburg', 'Luxemburg', 'Luxemburg'),
(125, 1, 125, 'MAC', 'MO', 'Macau', 'Makao', 'Macau', 'Macau', 'Makao', 'Makao', 'Makao', 'Makao', 'Makao', 'Makao'),
(126, 1, 126, 'MKD', 'MK', 'Macedonia The Former Yugoslav Republic of', 'Mazedonien', 'Macedonia The Former Yugoslav Republic of', 'Macedonia The Former Yugoslav Republic of', 'Mazedonien', 'Mazedonien', 'Mazedonien', 'Mazedonien', 'Mazedonien', 'Mazedonien'),
(127, 1, 127, 'MDG', 'MG', 'Madagascar', 'Madagaskar', 'Madagascar', 'Madagascar', 'Madagaskar', 'Madagaskar', 'Madagaskar', 'Madagaskar', 'Madagaskar', 'Madagaskar'),
(128, 1, 128, 'MWI', 'MW', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi', 'Malawi'),
(129, 1, 129, 'MYS', 'MY', 'Malaysia', 'Malaysia', 'Malaysia', 'Malaysia', 'Malaysia', 'Malaysia', 'Malaysia', 'Malaysia', 'Malaysia', 'Malaysia'),
(130, 1, 130, 'MDV', 'MV', 'Maldives', 'Malediven', 'Maldives', 'Maldives', 'Malediven', 'Malediven', 'Malediven', 'Malediven', 'Malediven', 'Malediven'),
(131, 1, 131, 'MLI', 'ML', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali', 'Mali'),
(132, 1, 132, 'MLT', 'MT', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta', 'Malta'),
(133, 1, 133, 'MHL', 'MH', 'Marshall Islands', 'Marshallinseln', 'Marshall Islands', 'Marshall Islands', 'Marshallinseln', 'Marshallinseln', 'Marshallinseln', 'Marshallinseln', 'Marshallinseln', 'Marshallinseln'),
(134, 1, 134, 'MTQ', 'MQ', 'Martinique', 'Martinique', 'Martinique', 'Martinique', 'Martinique', 'Martinique', 'Martinique', 'Martinique', 'Martinique', 'Martinique'),
(135, 1, 135, 'MRT', 'MR', 'Mauritania', 'Mauretanien', 'Mauritania', 'Mauritania', 'Mauretanien', 'Mauretanien', 'Mauretanien', 'Mauretanien', 'Mauretanien', 'Mauretanien'),
(136, 1, 136, 'MUS', 'MU', 'Mauritius', 'Mauritius', 'Mauritius', 'Mauritius', 'Mauritius', 'Mauritius', 'Mauritius', 'Mauritius', 'Mauritius', 'Mauritius'),
(137, 1, 137, 'MYT', 'YT', 'Mayotte', 'Mayott', 'Mayotte', 'Mayotte', 'Mayott', 'Mayott', 'Mayott', 'Mayott', 'Mayott', 'Mayott'),
(138, 1, 138, 'MEX', 'MX', 'Mexico', 'Mexiko', 'Mexico', 'Mexico', 'Mexiko', 'Mexiko', 'Mexiko', 'Mexiko', 'Mexiko', 'Mexiko'),
(139, 1, 139, 'FSM', 'FM', 'Micronesia Federated States of', 'Mikronesien', 'Micronesia Federated States of', 'Micronesia Federated States of', 'Mikronesien', 'Mikronesien', 'Mikronesien', 'Mikronesien', 'Mikronesien', 'Mikronesien'),
(140, 1, 140, 'MDA', 'MD', 'Moldova Republic of', 'Moldawien', 'Moldova Republic of', 'Moldova Republic of', 'Moldawien', 'Moldawien', 'Moldawien', 'Moldawien', 'Moldawien', 'Moldawien'),
(141, 1, 141, 'MCO', 'MC', 'Monaco', 'Monaco', 'Monaco', 'Monaco', 'Monaco', 'Monaco', 'Monaco', 'Monaco', 'Monaco', 'Monaco'),
(142, 1, 142, 'MNG', 'MN', 'Mongolia', 'Mongolei', 'Mongolia', 'Mongolia', 'Mongolei', 'Mongolei', 'Mongolei', 'Mongolei', 'Mongolei', 'Mongolei'),
(143, 1, 143, 'MSR', 'MS', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat', 'Montserrat'),
(144, 1, 144, 'MAR', 'MA', 'Morocco', 'Marokko', 'Morocco', 'Morocco', 'Marokko', 'Marokko', 'Marokko', 'Marokko', 'Marokko', 'Marokko'),
(145, 1, 145, 'MOZ', 'MZ', 'Mozambique', 'Mosambik', 'Mozambique', 'Mozambique', 'Mosambik', 'Mosambik', 'Mosambik', 'Mosambik', 'Mosambik', 'Mosambik'),
(146, 1, 146, 'MMR', 'MM', 'Myanmar', 'Myanmar', 'Myanmar', 'Myanmar', 'Myanmar', 'Myanmar', 'Myanmar', 'Myanmar', 'Myanmar', 'Myanmar'),
(147, 1, 147, 'NAM', 'NA', 'Namibia', 'Namibia', 'Namibia', 'Namibia', 'Namibia', 'Namibia', 'Namibia', 'Namibia', 'Namibia', 'Namibia'),
(148, 1, 148, 'NRU', 'NR', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru', 'Nauru'),
(149, 1, 149, 'NPL', 'NP', 'Nepal', 'Nepal', 'Nepal', 'Nepal', 'Nepal', 'Nepal', 'Nepal', 'Nepal', 'Nepal', 'Nepal'),
(150, 1, 150, 'NLD', 'NL', 'Netherlands', 'Niederlande', 'Netherlands', 'Netherlands', 'Niederlande', 'Niederlande', 'Niederlande', 'Niederlande', 'Niederlande', 'Niederlande'),
(151, 1, 151, 'ANT', 'AN', 'Netherlands Antilles', 'Niederländisch-Antillen', 'Netherlands Antilles', 'Netherlands Antilles', 'Niederländisch-Antillen', 'Niederländisch-Antillen', 'Niederländisch-Antillen', 'Niederländisch-Antillen', 'Niederländisch-Antillen', 'Niederländisch-Antillen'),
(152, 1, 152, 'NCL', 'NC', 'New Caledonia', 'Neukaledonien', 'New Caledonia', 'New Caledonia', 'Neukaledonien', 'Neukaledonien', 'Neukaledonien', 'Neukaledonien', 'Neukaledonien', 'Neukaledonien'),
(153, 1, 153, 'NZL', 'NZ', 'New Zealand', 'Neuseeland', 'New Zealand', 'New Zealand', 'Neuseeland', 'Neuseeland', 'Neuseeland', 'Neuseeland', 'Neuseeland', 'Neuseeland'),
(154, 1, 154, 'NIC', 'NI', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua', 'Nicaragua'),
(155, 1, 155, 'NER', 'NE', 'Niger', 'Niger', 'Niger', 'Niger', 'Niger', 'Niger', 'Niger', 'Niger', 'Niger', 'Niger'),
(156, 1, 156, 'NGA', 'NG', 'Nigeria', 'Nigeria', 'Nigeria', 'Nigeria', 'Nigeria', 'Nigeria', 'Nigeria', 'Nigeria', 'Nigeria', 'Nigeria'),
(157, 1, 157, 'NIU', 'NU', 'Niue', 'Niue', 'Niue', 'Niue', 'Niue', 'Niue', 'Niue', 'Niue', 'Niue', 'Niue'),
(158, 1, 158, 'NFK', 'NF', 'Norfolk Island', 'Norfolkinsel', 'Norfolk Island', 'Norfolk Island', 'Norfolkinsel', 'Norfolkinsel', 'Norfolkinsel', 'Norfolkinsel', 'Norfolkinsel', 'Norfolkinsel'),
(159, 1, 159, 'MNP', 'MP', 'Northern Mariana Islands', 'Nördliche Marianen', 'Northern Mariana Islands', 'Northern Mariana Islands', 'Nördliche Marianen', 'Nördliche Marianen', 'Nördliche Marianen', 'Nördliche Marianen', 'Nördliche Marianen', 'Nördliche Marianen'),
(160, 1, 160, 'NOR', 'NO', 'Norway', 'Norwegen', 'Norway', 'Norway', 'Norwegen', 'Norwegen', 'Norwegen', 'Norwegen', 'Norwegen', 'Norwegen'),
(161, 1, 161, 'OMN', 'OM', 'Oman', 'Oman', 'Oman', 'Oman', 'Oman', 'Oman', 'Oman', 'Oman', 'Oman', 'Oman'),
(162, 1, 162, 'PAK', 'PK', 'Pakistan', 'Pakistan', 'Pakistan', 'Pakistan', 'Pakistan', 'Pakistan', 'Pakistan', 'Pakistan', 'Pakistan', 'Pakistan'),
(163, 1, 163, 'PLW', 'PW', 'Palau', 'Palau', 'Palau', 'Palau', 'Palau', 'Palau', 'Palau', 'Palau', 'Palau', 'Palau'),
(164, 1, 164, 'PAN', 'PA', 'Panama', 'Panama', 'Panama', 'Panama', 'Panama', 'Panama', 'Panama', 'Panama', 'Panama', 'Panama'),
(165, 1, 165, 'PNG', 'PG', 'Papua New Guinea', 'Papua-Neuguinea', 'Papua New Guinea', 'Papua New Guinea', 'Papua-Neuguinea', 'Papua-Neuguinea', 'Papua-Neuguinea', 'Papua-Neuguinea', 'Papua-Neuguinea', 'Papua-Neuguinea'),
(166, 1, 166, 'PRY', 'PY', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay', 'Paraguay'),
(167, 1, 167, 'PER', 'PE', 'Peru', 'Peru', 'Peru', 'Peru', 'Peru', 'Peru', 'Peru', 'Peru', 'Peru', 'Peru'),
(168, 1, 168, 'PHL', 'PH', 'Philippines', 'Philippinen', 'Philippines', 'Philippines', 'Philippinen', 'Philippinen', 'Philippinen', 'Philippinen', 'Philippinen', 'Philippinen'),
(169, 1, 169, 'PCN', 'PN', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairn', 'Pitcairn'),
(170, 1, 170, 'POL', 'PL', 'Poland', 'Polen', 'Poland', 'Poland', 'Polen', 'Polen', 'Polen', 'Polen', 'Polen', 'Polen'),
(171, 1, 171, 'PRT', 'PT', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portugal', 'Portugal'),
(172, 1, 172, 'PRI', 'PR', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico', 'Puerto Rico'),
(173, 1, 173, 'QAT', 'QA', 'Qatar', 'Katar', 'Qatar', 'Qatar', 'Katar', 'Katar', 'Katar', 'Katar', 'Katar', 'Katar'),
(174, 1, 174, 'REU', 'RE', 'Reunion', 'Reunion', 'Reunion', 'Reunion', 'Reunion', 'Reunion', 'Reunion', 'Reunion', 'Reunion', 'Reunion'),
(175, 1, 175, 'ROM', 'RO', 'Romania', 'Rumänien', 'Romania', 'Romania', 'Rumänien', 'Rumänien', 'Rumänien', 'Rumänien', 'Rumänien', 'Rumänien'),
(176, 1, 176, 'RUS', 'RU', 'Russian Federation', 'Russische Föderation', 'Russian Federation', 'Russian Federation', 'Russische Föderation', 'Russische Föderation', 'Russische Föderation', 'Russische Föderation', 'Russische Föderation', 'Russische Föderation'),
(177, 1, 177, 'RWA', 'RW', 'Rwanda', 'Ruanda', 'Rwanda', 'Rwanda', 'Ruanda', 'Ruanda', 'Ruanda', 'Ruanda', 'Ruanda', 'Ruanda'),
(178, 1, 178, 'KNA', 'KN', 'Saint Kitts and Nevis', 'St. Kitts und Nevis', 'Saint Kitts and Nevis', 'Saint Kitts and Nevis', 'St. Kitts und Nevis', 'St. Kitts und Nevis', 'St. Kitts und Nevis', 'St. Kitts und Nevis', 'St. Kitts und Nevis', 'St. Kitts und Nevis'),
(179, 1, 179, 'LCA', 'LC', 'Saint Lucia', 'St. Lucia', 'Saint Lucia', 'Saint Lucia', 'St. Lucia', 'St. Lucia', 'St. Lucia', 'St. Lucia', 'St. Lucia', 'St. Lucia'),
(180, 1, 180, 'VCT', 'VC', 'Saint Vincent and the Grenadines', 'St. Vincent und die Grenadinen', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', 'St. Vincent und die Grenadinen', 'St. Vincent und die Grenadinen', 'St. Vincent und die Grenadinen', 'St. Vincent und die Grenadinen', 'St. Vincent und die Grenadinen', 'St. Vincent und die Grenadinen'),
(181, 1, 181, 'WSM', 'WS', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa', 'Samoa'),
(182, 1, 182, 'SMR', 'SM', 'San Marino', 'San Marino', 'San Marino', 'San Marino', 'San Marino', 'San Marino', 'San Marino', 'San Marino', 'San Marino', 'San Marino'),
(183, 1, 183, 'STP', 'ST', 'Sao Tome and Principe', 'Sao Tomé und Príncipe', 'Sao Tome and Principe', 'Sao Tome and Principe', 'Sao Tomé und Príncipe', 'Sao Tomé und Príncipe', 'Sao Tomé und Príncipe', 'Sao Tomé und Príncipe', 'Sao Tomé und Príncipe', 'Sao Tomé und Príncipe'),
(184, 1, 184, 'SAU', 'SA', 'Saudi Arabia', 'Saudi-Arabien', 'Saudi Arabia', 'Saudi Arabia', 'Saudi-Arabien', 'Saudi-Arabien', 'Saudi-Arabien', 'Saudi-Arabien', 'Saudi-Arabien', 'Saudi-Arabien'),
(185, 1, 185, 'SEN', 'SN', 'Senegal', 'Senegal', 'Senegal', 'Senegal', 'Senegal', 'Senegal', 'Senegal', 'Senegal', 'Senegal', 'Senegal'),
(186, 1, 186, 'SYC', 'SC', 'Seychelles', 'Seychellen', 'Seychelles', 'Seychelles', 'Seychellen', 'Seychellen', 'Seychellen', 'Seychellen', 'Seychellen', 'Seychellen'),
(187, 1, 187, 'SLE', 'SL', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone', 'Sierra Leone'),
(188, 1, 188, 'SGP', 'SG', 'Singapore', 'Singapur', 'Singapore', 'Singapore', 'Singapur', 'Singapur', 'Singapur', 'Singapur', 'Singapur', 'Singapur'),
(189, 1, 189, 'SVK', 'SK', 'Slovakia (Slovak Republic)', 'Slowakei', 'Slovakia (Slovak Republic)', 'Slovakia (Slovak Republic)', 'Slowakei', 'Slowakei', 'Slowakei', 'Slowakei', 'Slowakei', 'Slowakei'),
(190, 1, 190, 'SVN', 'SI', 'Slovenia', 'Slowenien', 'Slovenia', 'Slovenia', 'Slowenien', 'Slowenien', 'Slowenien', 'Slowenien', 'Slowenien', 'Slowenien'),
(191, 1, 191, 'SLB', 'SB', 'Solomon Islands', 'Salomonen', 'Solomon Islands', 'Solomon Islands', 'Salomonen', 'Salomonen', 'Salomonen', 'Salomonen', 'Salomonen', 'Salomonen'),
(192, 1, 192, 'SOM', 'SO', 'Somalia', 'Somalia', 'Somalia', 'Somalia', 'Somalia', 'Somalia', 'Somalia', 'Somalia', 'Somalia', 'Somalia'),
(193, 1, 193, 'ZAF', 'ZA', 'South Africa', 'Republik Südafrika', 'South Africa', 'South Africa', 'Republik Südafrika', 'Republik Südafrika', 'Republik Südafrika', 'Republik Südafrika', 'Republik Südafrika', 'Republik Südafrika'),
(194, 1, 194, 'SGS', 'GS', 'South Georgia and the South Sandwich Islands', 'Südgeorgien und die Südlichen Sandwichinseln', 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands', 'Südgeorgien und die Südlichen Sandwichinseln', 'Südgeorgien und die Südlichen Sandwichinseln', 'Südgeorgien und die Südlichen Sandwichinseln', 'Südgeorgien und die Südlichen Sandwichinseln', 'Südgeorgien und die Südlichen Sandwichinseln', 'Südgeorgien und die Südlichen Sandwichinseln'),
(195, 1, 195, 'ESP', 'ES', 'Spain', 'Spanien', 'Spain', 'Spain', 'Spanien', 'Spanien', 'Spanien', 'Spanien', 'Spanien', 'Spanien'),
(196, 1, 196, 'LKA', 'LK', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka', 'Sri Lanka'),
(197, 1, 197, 'SHN', 'SH', 'St. Helena', 'St. Helena', 'St. Helena', 'St. Helena', 'St. Helena', 'St. Helena', 'St. Helena', 'St. Helena', 'St. Helena', 'St. Helena'),
(198, 1, 198, 'SPM', 'PM', 'St. Pierre and Miquelon', 'St. Pierre und Miquelon', 'St. Pierre and Miquelon', 'St. Pierre and Miquelon', 'St. Pierre und Miquelon', 'St. Pierre und Miquelon', 'St. Pierre und Miquelon', 'St. Pierre und Miquelon', 'St. Pierre und Miquelon', 'St. Pierre und Miquelon'),
(199, 1, 199, 'SDN', 'SD', 'Sudan', 'Sudan', 'Sudan', 'Sudan', 'Sudan', 'Sudan', 'Sudan', 'Sudan', 'Sudan', 'Sudan'),
(200, 1, 200, 'SUR', 'SR', 'Suriname', 'Suriname', 'Suriname', 'Suriname', 'Suriname', 'Suriname', 'Suriname', 'Suriname', 'Suriname', 'Suriname'),
(201, 1, 201, 'SJM', 'SJ', 'Svalbard and Jan Mayen Islands', 'Svalbard und Jan Mayen', 'Svalbard and Jan Mayen Islands', 'Svalbard and Jan Mayen Islands', 'Svalbard und Jan Mayen', 'Svalbard und Jan Mayen', 'Svalbard und Jan Mayen', 'Svalbard und Jan Mayen', 'Svalbard und Jan Mayen', 'Svalbard und Jan Mayen'),
(202, 1, 202, 'SWZ', 'SZ', 'Swaziland', 'Swasiland', 'Swaziland', 'Swaziland', 'Swasiland', 'Swasiland', 'Swasiland', 'Swasiland', 'Swasiland', 'Swasiland'),
(203, 1, 203, 'SWE', 'SE', 'Sweden', 'Schweden', 'Sweden', 'Sweden', 'Schweden', 'Schweden', 'Schweden', 'Schweden', 'Schweden', 'Schweden'),
(204, 1, 204, 'CHE', 'CH', 'Switzerland', 'Schweiz', 'Switzerland', 'Switzerland', 'Schweiz', 'Schweiz', 'Schweiz', 'Schweiz', 'Schweiz', 'Schweiz'),
(205, 1, 205, 'SYR', 'SY', 'Syrian Arab Republic', 'Syrien', 'Syrian Arab Republic', 'Syrian Arab Republic', 'Syrien', 'Syrien', 'Syrien', 'Syrien', 'Syrien', 'Syrien'),
(206, 1, 206, 'TWN', 'TW', 'Taiwan', 'Taiwan', 'Taiwan', 'Taiwan', 'Taiwan', 'Taiwan', 'Taiwan', 'Taiwan', 'Taiwan', 'Taiwan'),
(207, 1, 207, 'TJK', 'TJ', 'Tajikistan', 'Tadschikistan', 'Tajikistan', 'Tajikistan', 'Tadschikistan', 'Tadschikistan', 'Tadschikistan', 'Tadschikistan', 'Tadschikistan', 'Tadschikistan'),
(208, 1, 208, 'TZA', 'TZ', 'Tanzania United Republic of', 'Tansania', 'Tanzania United Republic of', 'Tanzania United Republic of', 'Tansania', 'Tansania', 'Tansania', 'Tansania', 'Tansania', 'Tansania'),
(209, 1, 209, 'THA', 'TH', 'Thailand', 'Thailand', 'Thailand', 'Thailand', 'Thailand', 'Thailand', 'Thailand', 'Thailand', 'Thailand', 'Thailand'),
(210, 1, 210, 'TGO', 'TG', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo', 'Togo'),
(211, 1, 211, 'TKL', 'TK', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau', 'Tokelau'),
(212, 1, 212, 'TON', 'TO', 'Tonga', 'Tonga', 'Tonga', 'Tonga', 'Tonga', 'Tonga', 'Tonga', 'Tonga', 'Tonga', 'Tonga'),
(213, 1, 213, 'TTO', 'TT', 'Trinidad and Tobago', 'Trinidad und Tobago', 'Trinidad and Tobago', 'Trinidad and Tobago', 'Trinidad und Tobago', 'Trinidad und Tobago', 'Trinidad und Tobago', 'Trinidad und Tobago', 'Trinidad und Tobago', 'Trinidad und Tobago'),
(214, 1, 214, 'TUN', 'TN', 'Tunisia', 'Tunesien', 'Tunisia', 'Tunisia', 'Tunesien', 'Tunesien', 'Tunesien', 'Tunesien', 'Tunesien', 'Tunesien'),
(215, 1, 215, 'TUR', 'TR', 'Turkey', 'Türkei', 'Turkey', 'Turkey', 'Türkei', 'Türkei', 'Türkei', 'Türkei', 'Türkei', 'Türkei'),
(216, 1, 216, 'TKM', 'TM', 'Turkmenistan', 'Turkmenistan', 'Turkmenistan', 'Turkmenistan', 'Turkmenistan', 'Turkmenistan', 'Turkmenistan', 'Turkmenistan', 'Turkmenistan', 'Turkmenistan'),
(217, 1, 217, 'TCA', 'TC', 'Turks and Caicos Islands', 'Turks- und Caicosinseln', 'Turks and Caicos Islands', 'Turks and Caicos Islands', 'Turks- und Caicosinseln', 'Turks- und Caicosinseln', 'Turks- und Caicosinseln', 'Turks- und Caicosinseln', 'Turks- und Caicosinseln', 'Turks- und Caicosinseln'),
(218, 1, 218, 'TUV', 'TV', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu', 'Tuvalu'),
(219, 1, 219, 'UGA', 'UG', 'Uganda', 'Uganda', 'Uganda', 'Uganda', 'Uganda', 'Uganda', 'Uganda', 'Uganda', 'Uganda', 'Uganda'),
(220, 1, 220, 'UKR', 'UA', 'Ukraine', 'Ukraine', 'Ukraine', 'Ukraine', 'Ukraine', 'Ukraine', 'Ukraine', 'Ukraine', 'Ukraine', 'Ukraine'),
(221, 1, 221, 'ARE', 'AE', 'United Arab Emirates', 'Vereinigte Arabische Emirate', 'United Arab Emirates', 'United Arab Emirates', 'Vereinigte Arabische Emirate', 'Vereinigte Arabische Emirate', 'Vereinigte Arabische Emirate', 'Vereinigte Arabische Emirate', 'Vereinigte Arabische Emirate', 'Vereinigte Arabische Emirate'),
(222, 1, 222, 'GBR', 'GB', 'United Kingdom', 'Vereinigtes Königreich', 'United Kingdom', 'United Kingdom', 'Vereinigtes Königreich', 'Vereinigtes Königreich', 'Vereinigtes Königreich', 'Vereinigtes Königreich', 'Vereinigtes Königreich', 'Vereinigtes Königreich'),
(223, 1, 223, 'USA', 'US', 'United States', 'USA', 'United States', 'United States', 'USA', 'USA', 'USA', 'USA', 'USA', 'USA'),
(224, 1, 224, 'UMI', 'UM', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands'),
(225, 1, 225, 'URY', 'UY', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay', 'Uruguay'),
(226, 1, 226, 'UZB', 'UZ', 'Uzbekistan', 'Usbekistan', 'Uzbekistan', 'Uzbekistan', 'Usbekistan', 'Usbekistan', 'Usbekistan', 'Usbekistan', 'Usbekistan', 'Usbekistan'),
(227, 1, 227, 'VUT', 'VU', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu', 'Vanuatu'),
(228, 1, 228, 'VAT', 'VA', 'Vatican City State (Holy See)', 'Vatikanstadt', 'Vatican City State (Holy See)', 'Vatican City State (Holy See)', 'Vatikanstadt', 'Vatikanstadt', 'Vatikanstadt', 'Vatikanstadt', 'Vatikanstadt', 'Vatikanstadt'),
(229, 1, 229, 'VEN', 'VE', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela', 'Venezuela'),
(230, 1, 230, 'VNM', 'VN', 'Viet Nam', 'Vietnam', 'Viet Nam', 'Viet Nam', 'Vietnam', 'Vietnam', 'Vietnam', 'Vietnam', 'Vietnam', 'Vietnam'),
(231, 1, 231, 'VGB', 'VG', 'Virgin Islands (British)', 'Britische Jungferninseln', 'Virgin Islands (British)', 'Virgin Islands (British)', 'Britische Jungferninseln', 'Britische Jungferninseln', 'Britische Jungferninseln', 'Britische Jungferninseln', 'Britische Jungferninseln', 'Britische Jungferninseln'),
(232, 1, 232, 'VIR', 'VI', 'Virgin Islands (U.S.)', 'Vereinigte Staaten von Amerika', 'Virgin Islands (U.S.)', 'Virgin Islands (U.S.)', 'Vereinigte Staaten von Amerika', 'Vereinigte Staaten von Amerika', 'Vereinigte Staaten von Amerika', 'Vereinigte Staaten von Amerika', 'Vereinigte Staaten von Amerika', 'Vereinigte Staaten von Amerika'),
(233, 1, 233, 'WLF', 'WF', 'Wallis and Futuna Islands', 'Wallis und Futuna', 'Wallis and Futuna Islands', 'Wallis and Futuna Islands', 'Wallis und Futuna', 'Wallis und Futuna', 'Wallis und Futuna', 'Wallis und Futuna', 'Wallis und Futuna', 'Wallis und Futuna'),
(234, 1, 234, 'ESH', 'EH', 'Western Sahara', 'Westsahara', 'Western Sahara', 'Western Sahara', 'Westsahara', 'Westsahara', 'Westsahara', 'Westsahara', 'Westsahara', 'Westsahara'),
(235, 1, 235, 'YEM', 'YE', 'Yemen', 'Jemen', 'Yemen', 'Yemen', 'Jemen', 'Jemen', 'Jemen', 'Jemen', 'Jemen', 'Jemen'),
(236, 1, 236, 'YUG', 'YU', 'Yugoslavia', 'Yugoslavia', 'Yugoslavia', 'Yugoslavia', 'Yugoslavia', 'Yugoslavia', 'Yugoslavia', 'Yugoslavia', 'Yugoslavia', 'Yugoslavia'),
(237, 1, 237, 'ZAR', 'ZR', 'Zaire', 'Zaire', 'Zaire', 'Zaire', 'Zaire', 'Zaire', 'Zaire', 'Zaire', 'Zaire', 'Zaire'),
(238, 1, 238, 'ZMB', 'ZM', 'Zambia', 'Sambia', 'Zambia', 'Zambia', 'Sambia', 'Sambia', 'Sambia', 'Sambia', 'Sambia', 'Sambia'),
(239, 1, 239, 'ZWE', 'ZW', 'Zimbabwe', 'Simbabwe', 'Zimbabwe', 'Zimbabwe', 'Simbabwe', 'Simbabwe', 'Simbabwe', 'Simbabwe', 'Simbabwe', 'Simbabwe');

CREATE TABLE `#__jshopping_coupons` (
  `coupon_id` int(11) NOT NULL,
  `coupon_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'value_or_percent',
  `coupon_code` varchar(100) NOT NULL DEFAULT '',
  `coupon_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_id` int(11) NOT NULL,
  `used` int(11) NOT NULL,
  `count_use` int(11) NOT NULL,
  `for_user_id` int(11) NOT NULL,
  `coupon_start_date` date DEFAULT NULL,
  `coupon_expire_date` date DEFAULT NULL,
  `finished_after_used` int(11) NOT NULL,
  `coupon_publish` tinyint(4) NOT NULL DEFAULT '0',
  `shop_id` int(11) NOT NULL,
  `for_prod_price_to` decimal(12,2) NOT NULL DEFAULT '0.00',
  `for_prod_price_from` decimal(12,2) NOT NULL DEFAULT '0.00',
  `for_editor_id` int(11) NOT NULL DEFAULT '0',
  `for_label_id` int(11) NOT NULL DEFAULT '0',
  `for_manufacturer_id` int(11) NOT NULL DEFAULT '0',
  `for_vendor_id` int(11) NOT NULL DEFAULT '0',
  `for_user_group_id` int(11) NOT NULL DEFAULT '0',
  `once_for_each_user` int(11) NOT NULL DEFAULT '0',
  `min_sum_for_use` decimal(12,2) NOT NULL DEFAULT '0.00',
  `limited_use` int(11) NOT NULL DEFAULT '0',
  `free_shipping` int(11) NOT NULL DEFAULT '0',
  `free_payment` int(11) NOT NULL DEFAULT '0',
  `limited_count` int(11) NOT NULL DEFAULT '0',
  `not_use_for_product_with_old_price` int(11) NOT NULL DEFAULT '0',
  `min_count_in_cart` int(11) NOT NULL DEFAULT '0',
  `except_manufacturer_id` varchar(255) NOT NULL,
  `for_product_id` varchar(255) NOT NULL,
  `except_product_id` varchar(255) NOT NULL,
  `for_category_id` varchar(255) NOT NULL,
  `except_category_id` varchar(255) NOT NULL,
  `except_label_id` varchar(255) NOT NULL,
  `only_numbers` varchar(255) NOT NULL,
  `only_for_guests` varchar(255) NOT NULL,
  `except_vendor_id` varchar(255) NOT NULL,
  `except_user_group_id` varchar(255) NOT NULL,
  `for_currencies` varchar(255) NOT NULL,
  `for_product_fields` varchar(255) NOT NULL,
  `coupon_desc` varchar(511) NOT NULL,
  `for_product_ean` varchar(255) NOT NULL,
  `for_product_name_type` tinyint(1) NOT NULL,
  `for_product_name_de-DE` varchar(255) NOT NULL,
  `for_product_name_en-GB` varchar(255) NOT NULL,
  `for_product_name_es-ES` varchar(255) NOT NULL,
  `for_product_name_it-IT` varchar(255) NOT NULL,
  `for_product_name_fr-FR` varchar(255) NOT NULL,
  `for_product_name_nl-NL` varchar(255) NOT NULL,
  `for_product_name_pl-PL` varchar(255) NOT NULL,
  `for_product_name_ru-RU` varchar(255) NOT NULL,
  `for_product_name_sv-SE` varchar(255) NOT NULL,
  `for_product_name_fr-CA` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_coupons_users_rest` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `coupon_id` int(11) NOT NULL DEFAULT '0',
  `rest` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_currencies` (
  `currency_id` int(11) NOT NULL,
  `currency_name` varchar(64) NOT NULL DEFAULT '',
  `currency_code` varchar(20) NOT NULL DEFAULT '',
  `currency_code_iso` varchar(3) NOT NULL DEFAULT '',
  `currency_ordering` int(11) NOT NULL DEFAULT '0',
  `currency_value` decimal(14,6) NOT NULL DEFAULT '0.000000',
  `currency_publish` tinyint(1) NOT NULL DEFAULT '0',
  `currency_code_num` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_currencies` (`currency_id`, `currency_name`, `currency_code`, `currency_code_iso`, `currency_ordering`, `currency_value`, `currency_publish`, `currency_code_num`) VALUES
(1, 'Euro', '€', 'EUR', 5, '1.000000', 1, ''),
(2, 'CHF', 'CHF', 'CHF', 6, '1.000000', 1, ''),
(3, 'USD', '$', 'USD', 3, '1.350000', 1, '');

CREATE TABLE `#__jshopping_delivery_times` (
  `id` int(11) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `days` decimal(8,2) NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_expanding_products` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `array_filds` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `#__jshopping_free_attr` (
  `id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `type` tinyint(3) NOT NULL,
  `file_type` varchar(255) DEFAULT '',
  `show_quanity` smallint(1) NOT NULL DEFAULT '0',
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `description_de-DE` text NOT NULL,
  `description_en-GB` text NOT NULL,
  `description_es-ES` text NOT NULL,
  `description_fr-FR` text NOT NULL,
  `description_it-IT` text NOT NULL,
  `description_nl-NL` text NOT NULL,
  `description_pl-PL` text NOT NULL,
  `description_ru-RU` text NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `description_sv-SE` text NOT NULL,
  `editor_field_id` text NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `description_fr-CA` text NOT NULL,
  `type_for_editor` int(11) DEFAULT NULL,
  `show_unit` BOOLEAN NOT NULL DEFAULT FALSE,
  `unit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_free_attribute_calcule_price` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `params` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_free_attribute_calcule_price` (`id`, `name`, `params`) VALUES
(2, 'free_attribute', 'a:20:{s:9:\"variables\";a:4:{s:8:\"width_id\";s:1:\"0\";s:9:\"height_id\";s:1:\"0\";s:8:\"depth_id\";s:1:\"0\";s:5:\"var_1\";s:1:\"0\";}s:14:\"variablesNames\";a:1:{s:5:\"var_1\";s:9:\"Variable1\";}s:9:\"width_def\";s:0:\"\";s:10:\"height_def\";s:0:\"\";s:9:\"depth_def\";s:0:\"\";s:9:\"var_1_def\";s:0:\"\";s:9:\"width_min\";s:0:\"\";s:10:\"height_min\";s:0:\"\";s:9:\"depth_min\";s:0:\"\";s:9:\"var_1_min\";s:0:\"\";s:9:\"width_max\";s:0:\"\";s:10:\"height_max\";s:0:\"\";s:9:\"depth_max\";s:0:\"\";s:9:\"var_1_max\";s:0:\"\";s:10:\"width_step\";s:0:\"\";s:11:\"height_step\";s:0:\"\";s:10:\"depth_step\";s:0:\"\";s:10:\"var_1_step\";s:0:\"\";s:18:\"pricetypes_formula\";a:1:{i:100500;s:3:\"111\";}s:23:\"pricetypes_formula_name\";a:1:{i:100500;s:13:\"One-time cost\";}}');

CREATE TABLE `#__jshopping_free_attr_default_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `attr_id` bigint(20) NOT NULL,
  `default_value` text,
  `is_fixed` tinyint(1) DEFAULT '0',
  `attr_activated` tinyint(1) DEFAULT '0',
  `showFreeAttrInput` tinyint(1) DEFAULT '0',
  `min_value` text,
  `max_value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_import_export` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `params` text NOT NULL,
  `endstart` int(11) NOT NULL,
  `steptime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_languages` (
  `id` int(11) NOT NULL,
  `language` varchar(32) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `publish` int(11) NOT NULL,
  `ordering` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_languages` (`id`, `language`, `name`, `publish`, `ordering`) VALUES
(1, 'en-GB', 'English', 1, 0);

CREATE TABLE `#__jshopping_lieferant` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `anumber` varchar(255) NOT NULL,
  `publish` tinyint(1) NOT NULL,
  `ordering` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `#__jshopping_lieferant_tmpl` (
  `lang` varchar(32) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `#__jshopping_manufacturers` (
  `manufacturer_id` int(11) NOT NULL,
  `manufacturer_url` varchar(255) NOT NULL,
  `manufacturer_logo` varchar(255) NOT NULL,
  `manufacturer_publish` tinyint(1) NOT NULL,
  `products_page` int(11) NOT NULL,
  `products_row` int(11) NOT NULL DEFAULT '3',
  `ordering` int(6) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `alias_de-DE` varchar(255) NOT NULL,
  `short_description_de-DE` text NOT NULL,
  `description_de-DE` text NOT NULL,
  `meta_title_de-DE` varchar(255) NOT NULL,
  `meta_description_de-DE` text NOT NULL,
  `meta_keyword_de-DE` text NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `alias_en-GB` varchar(255) NOT NULL,
  `short_description_en-GB` text NOT NULL,
  `description_en-GB` text NOT NULL,
  `meta_title_en-GB` varchar(255) NOT NULL,
  `meta_description_en-GB` text NOT NULL,
  `meta_keyword_en-GB` text NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `alias_es-ES` varchar(255) NOT NULL,
  `short_description_es-ES` text NOT NULL,
  `description_es-ES` text NOT NULL,
  `meta_title_es-ES` varchar(255) NOT NULL,
  `meta_description_es-ES` text NOT NULL,
  `meta_keyword_es-ES` text NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `alias_it-IT` varchar(255) NOT NULL,
  `short_description_it-IT` text NOT NULL,
  `description_it-IT` text NOT NULL,
  `meta_title_it-IT` varchar(255) NOT NULL,
  `meta_description_it-IT` text NOT NULL,
  `meta_keyword_it-IT` text NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `alias_fr-FR` varchar(255) NOT NULL,
  `short_description_fr-FR` text NOT NULL,
  `description_fr-FR` text NOT NULL,
  `meta_title_fr-FR` varchar(255) NOT NULL,
  `meta_description_fr-FR` text NOT NULL,
  `meta_keyword_fr-FR` text NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `alias_nl-NL` varchar(255) NOT NULL,
  `short_description_nl-NL` text NOT NULL,
  `description_nl-NL` text NOT NULL,
  `meta_title_nl-NL` varchar(255) NOT NULL,
  `meta_description_nl-NL` text NOT NULL,
  `meta_keyword_nl-NL` text NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `alias_pl-PL` varchar(255) NOT NULL,
  `short_description_pl-PL` text NOT NULL,
  `description_pl-PL` text NOT NULL,
  `meta_title_pl-PL` varchar(255) NOT NULL,
  `meta_description_pl-PL` text NOT NULL,
  `meta_keyword_pl-PL` text NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `alias_ru-RU` varchar(255) NOT NULL,
  `short_description_ru-RU` text NOT NULL,
  `description_ru-RU` text NOT NULL,
  `meta_title_ru-RU` varchar(255) NOT NULL,
  `meta_description_ru-RU` text NOT NULL,
  `meta_keyword_ru-RU` text NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `alias_sv-SE` varchar(255) NOT NULL,
  `short_description_sv-SE` text NOT NULL,
  `description_sv-SE` text NOT NULL,
  `meta_title_sv-SE` varchar(255) NOT NULL,
  `meta_description_sv-SE` text NOT NULL,
  `meta_keyword_sv-SE` text NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `alias_fr-CA` varchar(255) NOT NULL,
  `short_description_fr-CA` text NOT NULL,
  `description_fr-CA` text NOT NULL,
  `meta_title_fr-CA` varchar(255) NOT NULL,
  `meta_description_fr-CA` text NOT NULL,
  `meta_keyword_fr-CA` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_native_uploads_prices` (
  `id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `from_item` int(11) NOT NULL DEFAULT '0',
  `to_item` int(11) NOT NULL DEFAULT '0',
  `percent` decimal(18,6) DEFAULT '0.000000',
  `price` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `calculated_price` decimal(18,6) NOT NULL DEFAULT '0.000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_offer_and_order` (
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_tax_ext` text NOT NULL,
  `order_shipping` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_payment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency_code` varchar(20) NOT NULL DEFAULT '',
  `currency_code_iso` varchar(3) NOT NULL DEFAULT '',
  `currency_exchange` decimal(14,6) NOT NULL DEFAULT '0.000000',
  `order_status` varchar(1) NOT NULL DEFAULT '',
  `order_created` tinyint(1) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_m_date` datetime DEFAULT NULL,
  `shipping_method_id` int(11) NOT NULL DEFAULT '0',
  `payment_method_id` int(11) NOT NULL DEFAULT '0',
  `payment_params` text NOT NULL,
  `payment_params_data` text NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `order_add_info` text NOT NULL,
  `title` tinyint(1) NOT NULL DEFAULT '0',
  `f_name` varchar(255) NOT NULL DEFAULT '',
  `l_name` varchar(255) NOT NULL DEFAULT '',
  `firma_name` varchar(255) NOT NULL DEFAULT '',
  `client_type` tinyint(1) NOT NULL,
  `client_type_name` varchar(100) NOT NULL,
  `firma_code` varchar(100) NOT NULL,
  `tax_number` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `street` varchar(100) NOT NULL DEFAULT '',
  `home` varchar(20) NOT NULL,
  `apartment` varchar(20) NOT NULL,
  `zip` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `country` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `mobil_phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL DEFAULT '',
  `ext_field_1` varchar(255) NOT NULL,
  `ext_field_2` varchar(255) NOT NULL,
  `ext_field_3` varchar(255) NOT NULL,
  `d_title` tinyint(1) NOT NULL DEFAULT '0',
  `d_f_name` varchar(255) NOT NULL DEFAULT '',
  `d_l_name` varchar(255) NOT NULL DEFAULT '',
  `d_firma_name` varchar(255) NOT NULL DEFAULT '',
  `d_email` varchar(255) NOT NULL DEFAULT '',
  `d_street` varchar(100) NOT NULL DEFAULT '',
  `d_home` varchar(20) NOT NULL,
  `d_apartment` varchar(20) NOT NULL,
  `d_zip` varchar(20) NOT NULL DEFAULT '',
  `d_city` varchar(100) NOT NULL DEFAULT '',
  `d_state` varchar(100) NOT NULL DEFAULT '',
  `d_country` int(11) NOT NULL,
  `d_phone` varchar(30) NOT NULL DEFAULT '',
  `d_mobil_phone` varchar(20) NOT NULL,
  `d_fax` varchar(20) NOT NULL DEFAULT '',
  `d_ext_field_1` varchar(255) NOT NULL,
  `d_ext_field_2` varchar(255) NOT NULL,
  `d_ext_field_3` varchar(255) NOT NULL,
  `pdf_file` varchar(50) NOT NULL,
  `order_hash` varchar(32) NOT NULL DEFAULT '',
  `file_hash` varchar(64) NOT NULL DEFAULT '',
  `file_stat_downloads` text NOT NULL,
  `order_custom_info` text NOT NULL,
  `display_price` tinyint(1) NOT NULL,
  `vendor_type` tinyint(1) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `lang` varchar(16) NOT NULL,
  `transaction` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `shipping_tax` decimal(8,2) NOT NULL DEFAULT '19.00',
  `payment_tax` decimal(8,2) NOT NULL DEFAULT '19.00',
  `delivery_time` varchar(100) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `delivery_times_id` int(11) NOT NULL,
  `ip` text NOT NULL,
  `partner_id` int(11) NOT NULL,
  `user_un` text NOT NULL,
  `pattern_report_status` int(11) NOT NULL DEFAULT '0',
  `pattern_percent_price` double NOT NULL DEFAULT '0',
  `valid_to` datetime NOT NULL,
  `projectname` text,
  `show_invoice_date` int(1) NOT NULL DEFAULT '1',
  `status_email` int(2) NOT NULL DEFAULT '0',
  `offer_status` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_offer_and_order_item` (
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_ean` varchar(50) NOT NULL DEFAULT '',
  `product_name` varchar(100) NOT NULL DEFAULT '',
  `product_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `product_item_price` decimal(12,2) NOT NULL,
  `product_item_one_time_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `product_tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `product_attributes` text NOT NULL,
  `product_freeattributes` text NOT NULL,
  `attributes` text NOT NULL,
  `freeattributes` text NOT NULL,
  `files` text NOT NULL,
  `weight` float(8,4) NOT NULL DEFAULT '0.0000',
  `thumb_image` varchar(255) NOT NULL DEFAULT '',
  `vendor_id` int(11) NOT NULL,
  `delivery_times_id` int(11) NOT NULL,
  `extra_fields` text NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `product_id_for_order` int(11) NOT NULL,
  `uploaded_files` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_orders` (
  `order_id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `order_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_tax_ext` text NOT NULL,
  `order_shipping` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_payment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `order_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency_code` varchar(20) NOT NULL DEFAULT '',
  `currency_code_iso` varchar(3) NOT NULL DEFAULT '',
  `currency_exchange` decimal(14,6) NOT NULL DEFAULT '0.000000',
  `order_status` tinyint(4) NOT NULL,
  `order_created` tinyint(1) NOT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_m_date` datetime DEFAULT NULL,
  `shipping_method_id` int(11) NOT NULL DEFAULT '0',
  `payment_method_id` int(11) NOT NULL DEFAULT '0',
  `payment_params` text NOT NULL,
  `payment_params_data` text NOT NULL,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `order_add_info` text NOT NULL,
  `pdf_file` varchar(50) NOT NULL,
  `order_hash` varchar(64) NOT NULL DEFAULT '',
  `file_hash` varchar(64) NOT NULL DEFAULT '',
  `file_stat_downloads` text NOT NULL,
  `order_custom_info` text NOT NULL,
  `display_price` tinyint(1) NOT NULL,
  `vendor_type` tinyint(1) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `lang` varchar(16) NOT NULL,
  `transaction` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `shipping_tax` decimal(8,2) NOT NULL DEFAULT '19.00',
  `payment_tax` decimal(8,2) NOT NULL DEFAULT '19.00',
  `delivery_time` varchar(100) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `delivery_times_id` int(11) NOT NULL,
  `ip` text NOT NULL,
  `partner_id` int(11) NOT NULL,
  `user_un` text NOT NULL,
  `pattern_report_status` int(11) NOT NULL DEFAULT '0',
  `pattern_percent_price` double NOT NULL DEFAULT '0',
  `klarna_invoice_url` varchar(255) NOT NULL,
  `billsafe_instruction` text,
  `shipping_tax_ext` text NOT NULL,
  `payment_tax_ext` text NOT NULL,
  `order_package` decimal(12,2) NOT NULL,
  `package_tax_ext` text NOT NULL,
  `delivery_date` datetime NOT NULL,
  `invoice_date` datetime NOT NULL,
  `taxes_ext` int(11) NOT NULL,
  `shipping_params` text NOT NULL,
  `shipping_params_data` text NOT NULL,
  `product_stock_removed` tinyint(1) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `sender_address` tinyint(1) NOT NULL,
  `provisionsprogrammID` int(11) NOT NULL,
  `caldera_status` int(11) NOT NULL DEFAULT '0',
  `caldera_ANSWER` text,
  `projectname` varchar(100) DEFAULT ' ',
  `invoice_number` VARCHAR(50) NOT NULL DEFAULT '0',
  `order_address_id` BIGINT UNSIGNED,
  `shippings` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jshopping_order_addresses` (
  `id` SERIAL,
  `user_id` int(11) NOT NULL,
  `m_name` varchar(255) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `title` tinyint(1) NOT NULL DEFAULT 0,
  `firma_name` varchar(100) NOT NULL,
  `client_type` tinyint(1) DEFAULT 0,
  `firma_code` varchar(100) NOT NULL,
  `tax_number` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `home` varchar(20) NOT NULL,
  `apartment` varchar(20) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` int(11) DEFAULT 0,
  `phone` varchar(20) NOT NULL,
  `mobil_phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `ext_field_1` varchar(255) NOT NULL,
  `ext_field_2` varchar(255) NOT NULL,
  `ext_field_3` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `street_nr` varchar(16) NOT NULL,
  `d_title` tinyint(1) NOT NULL,
  `d_f_name` varchar(255) NOT NULL,
  `d_l_name` varchar(255) NOT NULL,
  `d_firma_name` varchar(100) NOT NULL,
  `d_email` varchar(255) NOT NULL,
  `d_street` varchar(255) NOT NULL,
  `d_home` varchar(20) NOT NULL,
  `d_apartment` varchar(20) NOT NULL,
  `d_zip` varchar(20) NOT NULL,
  `d_city` varchar(100) NOT NULL,
  `d_state` varchar(100) NOT NULL,
  `d_country` int(11) NOT NULL,
  `d_phone` varchar(20) NOT NULL,
  `d_client_type` tinyint(1) DEFAULT 0,
  `d_firma_code` varchar(100) NOT NULL,
  `d_tax_number` varchar(100) NOT NULL,
  `d_mobil_phone` varchar(20) NOT NULL,
  `d_fax` varchar(20) NOT NULL,
  `d_ext_field_1` varchar(255) NOT NULL,
  `d_ext_field_2` varchar(255) NOT NULL,
  `d_ext_field_3` varchar(255) NOT NULL,
  `d_m_name` varchar(255) NOT NULL,
  `d_birthday` date NOT NULL,
  `d_street_nr` varchar(16) NOT NULL,
  `shippings` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jshopping_updates_info` (
	`id` SERIAL,
	`is_copied_user_addresses_to_new_table` tinyint(1) NOT NULL DEFAULT 0,
	`is_copied_orders_addresses_to_new_table` tinyint(1) NOT NULL DEFAULT 0,
	`is_updated_product_price_preview` tinyint(1) NOT NULL DEFAULT 0,
	`is_updated_product_price_group` tinyint(1) NOT NULL DEFAULT 0,
	`is_moved_free_attr_calc_price` tinyint(1) NOT NULL DEFAULT 0,
  `is_installed_new_media_video_for_product` tinyint(1) NOT NULL DEFAULT 0,
  `is_installed_new_media_img_for_product` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_order_history` (
  `order_history_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `order_status_id` tinyint(1) NOT NULL DEFAULT '0',
  `status_date_added` datetime DEFAULT NULL,
  `customer_notify` int(1) DEFAULT '0',
  `comments` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_ean` varchar(50) NOT NULL DEFAULT '',
  `product_name` varchar(100) NOT NULL DEFAULT '',
  `product_quantity` decimal(12,2) NOT NULL DEFAULT '0.00',
  `product_item_price` decimal(12,2) NOT NULL,
  `product_tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `product_attributes` text NOT NULL,
  `product_freeattributes` text NOT NULL,
  `attributes` text NOT NULL,
  `freeattributes` text NOT NULL,
  `files` text NOT NULL,
  `weight` float(8,4) NOT NULL DEFAULT '0.0000',
  `thumb_image` varchar(255) NOT NULL DEFAULT '',
  `vendor_id` int(11) NOT NULL,
  `delivery_times_id` int(4) NOT NULL,
  `extra_fields` text NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `adtprodnum` int(11) NOT NULL,
  `prodnum` int(11) NOT NULL,
  `issetadtprod` int(11) NOT NULL,
  `basicprice` decimal(12,2) NOT NULL,
  `basicpriceunit` varchar(255) NOT NULL,
  `design_name` varchar(255) NOT NULL,
  `price_from_frontend_plus_images` tinyint(4) NOT NULL,
  `pattern_report_sum` text NOT NULL,
  `pattern_report_status` text NOT NULL,
  `pattern_report_date` datetime NOT NULL,
  `product_id_for_order` int(11) NOT NULL,
  `reorder` varchar(50) DEFAULT NULL,
  `reorder_num` int(11) DEFAULT NULL,
  `total_price` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_order_items_native_uploads_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `file` text,
  `preview` text,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_order_status` (
  `status_id` int(11) NOT NULL,
  `status_code` char(1) NOT NULL DEFAULT '',
  `name_en-GB` varchar(100) NOT NULL DEFAULT '',
  `name_de-DE` varchar(100) NOT NULL DEFAULT '',
  `name_es-ES` varchar(100) NOT NULL,
  `name_it-IT` varchar(100) NOT NULL,
  `name_fr-FR` varchar(100) NOT NULL,
  `name_nl-NL` varchar(100) NOT NULL,
  `name_pl-PL` varchar(100) NOT NULL,
  `name_ru-RU` varchar(100) NOT NULL,
  `name_sv-SE` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_text` text,
  `order_status_image` text NOT NULL,
  `name_fr-CA` varchar(100) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `is_allowed_status_for_cancellation` BOOLEAN DEFAULT FALSE,
  `is_generate_invoice` BOOLEAN DEFAULT false,
  `is_send_invoice_to_customer` BOOLEAN DEFAULT false,
  `is_send_invoice_to_admin` BOOLEAN DEFAULT false,
  `is_generate_delivery_note` BOOLEAN DEFAULT false,
  `is_send_delivery_note_to_customer` BOOLEAN DEFAULT false,
  `is_send_delivery_note_to_admin` BOOLEAN DEFAULT false,
  `is_generate_refund_note` tinyint(1) DEFAULT '0',
  `is_send_refund_note_to_customer` tinyint(1) DEFAULT '0',
  `is_send_refund_note_to_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_order_status` (`status_id`, `status_code`, `name_en-GB`, `name_de-DE`, `name_es-ES`, `name_it-IT`, `name_fr-FR`, `name_nl-NL`, `name_pl-PL`, `name_ru-RU`, `name_sv-SE`, `email`, `email_text`, `order_status_image`, `name_fr-CA`, `color`) VALUES
(1, 'P', 'Pending', 'Offen', 'Pending', 'Pending', 'Offen', 'Offen', 'Offen', 'Offen', 'Offen', '', 'a:4:{s:10:"text_de-DE";s:290:"<p>Guten Tag {first_name} {last_name},</p> <p>Der Status Ihrer Bestellung {order_number} wurde geändert.<br />Neuer Status: {order_status}</p> <p>Einzelheiten:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_en-GB";s:282:"<p>Hello {first_name} {last_name},</p><p>Status of your order number {order_number} has changed<br />New status is: {order_status}</p> <p>Order details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_nl-NL";s:279:"<p>Hallo {first_name} {last_name},</p> <p>De status van uw bestelling {order_number} is gewijzigd.<br />Nieuwe status: {order_status}</p> <p>Details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_ru-RU";s:333:"<p>Здравствуйте {first_name} {last_name},</p> <p>Статус вашего заказа {order_number} был изменен.<br />Новый статус: {order_status}</p> <p>Детали:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";}', '204176b361d5990083bbe52b8a49cb44.jpg', 'Offen', NULL),
(2, 'C', 'Confirmed', 'Bestätigt', 'Confirmed', 'Confirmed', 'Bestätigt', 'Bestätigt', 'Bestätigt', 'Bestätigt', 'Bestätigt', '', 'a:4:{s:10:"text_de-DE";s:290:"<p>Guten Tag {first_name} {last_name},</p> <p>Der Status Ihrer Bestellung {order_number} wurde geändert.<br />Neuer Status: {order_status}</p> <p>Einzelheiten:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_en-GB";s:282:"<p>Hello {first_name} {last_name},</p><p>Status of your order number {order_number} has changed<br />New status is: {order_status}</p> <p>Order details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_nl-NL";s:279:"<p>Hallo {first_name} {last_name},</p> <p>De status van uw bestelling {order_number} is gewijzigd.<br />Nieuwe status: {order_status}</p> <p>Details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_ru-RU";s:333:"<p>Здравствуйте {first_name} {last_name},</p> <p>Статус вашего заказа {order_number} был изменен.<br />Новый статус: {order_status}</p> <p>Детали:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";}', '', 'Bestätigt', NULL),
(3, 'X', 'Cancelled', 'Abgebrochen', 'Cancelled', 'Cancelled', 'Abgebrochen', 'Abgebrochen', 'Abgebrochen', 'Abgebrochen', 'Abgebrochen', '', 'a:4:{s:10:"text_de-DE";s:290:"<p>Guten Tag {first_name} {last_name},</p> <p>Der Status Ihrer Bestellung {order_number} wurde geändert.<br />Neuer Status: {order_status}</p> <p>Einzelheiten:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_en-GB";s:282:"<p>Hello {first_name} {last_name},</p><p>Status of your order number {order_number} has changed<br />New status is: {order_status}</p> <p>Order details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_nl-NL";s:279:"<p>Hallo {first_name} {last_name},</p> <p>De status van uw bestelling {order_number} is gewijzigd.<br />Nieuwe status: {order_status}</p> <p>Details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_ru-RU";s:333:"<p>Здравствуйте {first_name} {last_name},</p> <p>Статус вашего заказа {order_number} был изменен.<br />Новый статус: {order_status}</p> <p>Детали:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";}', '', 'Abgebrochen', NULL),
(4, 'R', 'Refunded', 'Gutschrift', 'Refunded', 'Refunded', 'Gutschrift', 'Gutschrift', 'Gutschrift', 'Gutschrift', 'Gutschrift', '', 'a:4:{s:10:"text_de-DE";s:290:"<p>Guten Tag {first_name} {last_name},</p> <p>Der Status Ihrer Bestellung {order_number} wurde geändert.<br />Neuer Status: {order_status}</p> <p>Einzelheiten:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_en-GB";s:282:"<p>Hello {first_name} {last_name},</p><p>Status of your order number {order_number} has changed<br />New status is: {order_status}</p> <p>Order details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_nl-NL";s:279:"<p>Hallo {first_name} {last_name},</p> <p>De status van uw bestelling {order_number} is gewijzigd.<br />Nieuwe status: {order_status}</p> <p>Details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_ru-RU";s:333:"<p>Здравствуйте {first_name} {last_name},</p> <p>Статус вашего заказа {order_number} был изменен.<br />Новый статус: {order_status}</p> <p>Детали:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";}', '', 'Gutschrift', NULL),
(5, 'S', 'Shipped', 'Gesendet', 'Shipped', 'Shipped', 'Gesendet', 'Gesendet', 'Gesendet', 'Gesendet', 'Gesendet', '', 'a:4:{s:10:"text_de-DE";s:290:"<p>Guten Tag {first_name} {last_name},</p> <p>Der Status Ihrer Bestellung {order_number} wurde geändert.<br />Neuer Status: {order_status}</p> <p>Einzelheiten:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_en-GB";s:282:"<p>Hello {first_name} {last_name},</p><p>Status of your order number {order_number} has changed<br />New status is: {order_status}</p> <p>Order details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_nl-NL";s:279:"<p>Hallo {first_name} {last_name},</p> <p>De status van uw bestelling {order_number} is gewijzigd.<br />Nieuwe status: {order_status}</p> <p>Details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_ru-RU";s:333:"<p>Здравствуйте {first_name} {last_name},</p> <p>Статус вашего заказа {order_number} был изменен.<br />Новый статус: {order_status}</p> <p>Детали:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";}', '', 'Gesendet', NULL),
(6, 'O', 'Paid', 'Bezahlt', 'Paid', 'Paid', 'Bezahlt', 'Bezahlt', 'Bezahlt', 'Bezahlt', 'Bezahlt', '', 'a:4:{s:10:"text_de-DE";s:290:"<p>Guten Tag {first_name} {last_name},</p> <p>Der Status Ihrer Bestellung {order_number} wurde geändert.<br />Neuer Status: {order_status}</p> <p>Einzelheiten:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_en-GB";s:282:"<p>Hello {first_name} {last_name},</p><p>Status of your order number {order_number} has changed<br />New status is: {order_status}</p> <p>Order details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_nl-NL";s:279:"<p>Hallo {first_name} {last_name},</p> <p>De status van uw bestelling {order_number} is gewijzigd.<br />Nieuwe status: {order_status}</p> <p>Details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_ru-RU";s:333:"<p>Здравствуйте {first_name} {last_name},</p> <p>Статус вашего заказа {order_number} был изменен.<br />Новый статус: {order_status}</p> <p>Детали:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";}', '', 'Bezahlt', NULL),
(7, 'F', 'Complete', 'Abgeschlossen', 'Complete', 'Complete', 'Abgeschlossen', 'Abgeschlossen', 'Abgeschlossen', 'Abgeschlossen', 'Abgeschlossen', '', 'a:4:{s:10:"text_de-DE";s:290:"<p>Guten Tag {first_name} {last_name},</p> <p>Der Status Ihrer Bestellung {order_number} wurde geändert.<br />Neuer Status: {order_status}</p> <p>Einzelheiten:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_en-GB";s:282:"<p>Hello {first_name} {last_name},</p><p>Status of your order number {order_number} has changed<br />New status is: {order_status}</p> <p>Order details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_nl-NL";s:279:"<p>Hallo {first_name} {last_name},</p> <p>De status van uw bestelling {order_number} is gewijzigd.<br />Nieuwe status: {order_status}</p> <p>Details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_ru-RU";s:333:"<p>Здравствуйте {first_name} {last_name},</p> <p>Статус вашего заказа {order_number} был изменен.<br />Новый статус: {order_status}</p> <p>Детали:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";}', '', 'Abgeschlossen', NULL),
(8, 'M', 'Reversal', 'Storno', 'Reversal', 'Reversal', 'Reversal', 'Reversal', 'Reversal', 'Reversal', 'Reversal', '', 'a:4:{s:10:"text_de-DE";s:290:"<p>Guten Tag {first_name} {last_name},</p> <p>Der Status Ihrer Bestellung {order_number} wurde geändert.<br />Neuer Status: {order_status}</p> <p>Einzelheiten:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_en-GB";s:282:"<p>Hello {first_name} {last_name},</p><p>Status of your order number {order_number} has changed<br />New status is: {order_status}</p> <p>Order details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_nl-NL";s:279:"<p>Hallo {first_name} {last_name},</p> <p>De status van uw bestelling {order_number} is gewijzigd.<br />Nieuwe status: {order_status}</p> <p>Details:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";s:10:"text_ru-RU";s:333:"<p>Здравствуйте {first_name} {last_name},</p> <p>Статус вашего заказа {order_number} был изменен.<br />Новый статус: {order_status}</p> <p>Детали:<br />{order_detail_url}<br />{company}<br />{address}<br />{zip} {city}<br />{country}<br />{comment}<br />{phone}<br />{fax}</p>";}', '', 'Storno', NULL);

CREATE TABLE `#__jshopping_payment_method` (
  `payment_id` int(11) NOT NULL,
  `name_en-GB` varchar(100) NOT NULL,
  `description_en-GB` text NOT NULL,
  `name_de-DE` varchar(100) NOT NULL,
  `description_de-DE` text NOT NULL,
  `payment_code` varchar(32) NOT NULL,
  `payment_class` varchar(100) NOT NULL,
  `payment_publish` tinyint(1) NOT NULL,
  `payment_ordering` int(11) NOT NULL,
  `payment_params` text NOT NULL,
  `payment_status` int(11) NOT NULL,
  `payment_type` tinyint(4) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_type` tinyint(1) NOT NULL DEFAULT '1',
  `tax_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `show_descr_in_email` tinyint(1) NOT NULL,
  `name_es-ES` varchar(100) NOT NULL,
  `description_es-ES` text NOT NULL,
  `name_it-IT` varchar(100) NOT NULL,
  `description_it-IT` text NOT NULL,
  `name_fr-FR` varchar(100) NOT NULL,
  `description_fr-FR` text NOT NULL,
  `name_nl-NL` varchar(100) NOT NULL,
  `description_nl-NL` text NOT NULL,
  `name_pl-PL` varchar(100) NOT NULL,
  `description_pl-PL` text NOT NULL,
  `name_ru-RU` varchar(100) NOT NULL,
  `description_ru-RU` text NOT NULL,
  `usergroup` varchar(50) NOT NULL,
  `name_sv-SE` varchar(100) NOT NULL,
  `description_sv-SE` text NOT NULL,
  `countries` varchar(255) NOT NULL,
  `show_bank_in_order` tinyint(1) NOT NULL DEFAULT '1',
  `order_description` text NOT NULL,
  `scriptname` varchar(100) NOT NULL,
  `name_fr-CA` varchar(100) NOT NULL,
  `description_fr-CA` text NOT NULL,
  `shop_id` int(11) NOT NULL,
  `not_sending_messages_on_statuses` text,
  `usergroup_id` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_payment_method` (`payment_id`, `name_en-GB`, `description_en-GB`, `name_de-DE`, `description_de-DE`, `payment_code`, `payment_class`, `payment_publish`, `payment_ordering`, `payment_params`, `payment_status`, `payment_type`, `price`, `price_type`, `tax_id`, `image`, `show_descr_in_email`, `name_es-ES`, `description_es-ES`, `name_it-IT`, `description_it-IT`, `name_fr-FR`, `description_fr-FR`, `name_nl-NL`, `description_nl-NL`, `name_pl-PL`, `description_pl-PL`, `name_ru-RU`, `description_ru-RU`, `usergroup`, `name_sv-SE`, `description_sv-SE`, `countries`, `show_bank_in_order`, `order_description`, `scriptname`, `name_fr-CA`, `description_fr-CA`, `shop_id`, `not_sending_messages_on_statuses`, `usergroup_id`) VALUES
(1, 'Cash on delivery', '', 'Nachnahme', '', 'bank', 'pm_bank', 1, 1, '', 1, 1, '4.00', 0, 1, '', 0, 'Cash on delivery', '', 'Cash on delivery', '', 'Nachnahme', '', 'Nachnahme', '', 'Nachnahme', '', 'Nachnahme', '', '', 'Nachnahme', '', '', 1, '', '', 'Nachnahme', '', 0, NULL, '0,1'),
(2, 'Advance payment', '', 'Vorauskasse', '<p>Beschreibung</p>', 'PO', 'pm_purchase_order', 1, 2, '', 1, 1, '0.00', 1, 1, '', 1, 'Advance payment', '', 'Advance payment', '', 'Vorauskasse', '', 'Vorauskasse', '', 'Vorauskasse', '', 'Vorauskasse', '', '', 'Vorauskasse', '', '', 1, '', '', 'Vorauskasse', '', 0, NULL, '0,1');

CREATE TABLE `#__jshopping_payment_trx` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `transaction` varchar(255) NOT NULL,
  `rescode` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_payment_trx_data` (
  `id` int(11) NOT NULL,
  `trx_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products` (
  `product_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `product_ean` varchar(32) NOT NULL,
  `product_quantity` decimal(12,2) NOT NULL,
  `unlimited` tinyint(1) NOT NULL,
  `product_availability` varchar(128) NOT NULL,
  `product_date_added` datetime DEFAULT NULL,
  `date_modify` datetime DEFAULT NULL,
  `product_publish` tinyint(1) NOT NULL DEFAULT '0',
  `product_tax_id` tinyint(3) NOT NULL DEFAULT '0',
  `currency_id` int(4) NOT NULL DEFAULT '0',
  `product_template` varchar(64) NOT NULL DEFAULT 'default',
  `product_url` varchar(255) NOT NULL DEFAULT '',
  `product_old_price` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `product_buy_price` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `product_price` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `min_price` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `different_prices` tinyint(1) NOT NULL DEFAULT '0',
  `product_weight` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `product_thumb_image` varchar(255) NOT NULL,
  `product_name_image` varchar(255) NOT NULL,
  `product_full_image` varchar(255) NOT NULL,
  `product_manufacturer_id` int(11) NOT NULL DEFAULT '0',
  `product_is_add_price` tinyint(1) NOT NULL DEFAULT '0',
  `add_price_unit_id` int(3) NOT NULL DEFAULT '0',
  `average_rating` float(4,2) NOT NULL DEFAULT '0.00',
  `reviews_count` int(11) NOT NULL DEFAULT '0',
  `delivery_times_id` int(4) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `weight_volume_units` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `basic_price_unit_id` int(3) NOT NULL DEFAULT '0',
  `label_id` int(11) NOT NULL DEFAULT '0',  
  `access` int(3) NOT NULL DEFAULT '1',
  `name_de-DE` varchar(255) NOT NULL,
  `short_description_de-DE` text NOT NULL,
  `description_de-DE` text NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `short_description_en-GB` text NOT NULL,
  `description_en-GB` text NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `short_description_es-ES` text NOT NULL,
  `description_es-ES` text NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `short_description_it-IT` text NOT NULL,
  `description_it-IT` text NOT NULL,
  `pic` text NOT NULL,
  `file` text NOT NULL,
  `xml` text NOT NULL,
  `pr_id` int(11) NOT NULL,
  `product_type` int(11) NOT NULL,
  `product_packing_type` INT(4) NOT NULL DEFAULT '0',
  `product_created_type` tinyint(4) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `short_description_fr-FR` text NOT NULL,
  `description_fr-FR` text NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `short_description_nl-NL` text NOT NULL,
  `description_nl-NL` text NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `short_description_pl-PL` text NOT NULL,
  `description_pl-PL` text NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `short_description_ru-RU` text NOT NULL,
  `description_ru-RU` text NOT NULL,
  `editor_id` int(11) NOT NULL DEFAULT '0',
  `product_type_view` int(11) NOT NULL DEFAULT '0',
  `wishlist` int(11) NOT NULL DEFAULT '0',
  `pattern_price` double NOT NULL DEFAULT '0',
  `name_sv-SE` varchar(255) NOT NULL,
  `short_description_sv-SE` text NOT NULL,
  `description_sv-SE` text NOT NULL,
  `template_user_name` text NOT NULL,
  `usergroup_show_product` VARCHAR(100) NOT NULL DEFAULT '*',
  `usergroup_show_price` VARCHAR(100) NOT NULL DEFAULT '*',
  `usergroup_show_buy` VARCHAR(100) NOT NULL DEFAULT '*',
  `usergroup_alttext_price` text NOT NULL,
  `usergroup_alttext_buy` text NOT NULL,
  `max_count_product` int(11) NOT NULL DEFAULT '0',
  `min_count_product` int(11) NOT NULL DEFAULT '0',
  `product_linear_price` decimal(12,2) NOT NULL,
  `product_mindestpreis` decimal(12,2) NOT NULL,
  `related_editor_id` int(11) NOT NULL DEFAULT '0',
  `robots_de-DE` int(11) NOT NULL DEFAULT '0',
  `robots_en-GB` int(11) NOT NULL DEFAULT '0',
  `robots_fr-FR` int(11) NOT NULL DEFAULT '0',
  `robots_it-IT` int(11) NOT NULL DEFAULT '0',
  `robots_nl-NL` int(11) NOT NULL DEFAULT '0',
  `robots_pl-PL` int(11) NOT NULL DEFAULT '0',
  `robots_ru-RU` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `print_preset` int(11) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `short_description_fr-CA` text NOT NULL,
  `description_fr-CA` text NOT NULL,
  `robots_fr-CA` int(11) NOT NULL DEFAULT '0',
  `price_without_pattern` double NOT NULL,
  `editor_product_print_id` int(11) NOT NULL,
  `editor_in_iframe` tinyint(1) NOT NULL,
  `descriptionFromDataParams` text NOT NULL,
  `complex_calculation_delivery` tinyint(1) NOT NULL,
  `complex_single_price` decimal(10,2) NOT NULL,
  `epp_id` int(11) NOT NULL DEFAULT '0',
  `parrams` text NOT NULL,
  `jsproduct_with_editor_for_image` text NOT NULL,
  `created_in_editor` datetime NOT NULL,
  `low_stock_notify_status` tinyint(1) DEFAULT '0',
  `low_stock_number` int(11) DEFAULT '0',
  `product_show_cart` tinyint(1) NOT NULL DEFAULT '1',
  `product_price_type` int(11) NOT NULL DEFAULT '0',
  `product_price_for_qty_type` int(11) NOT NULL DEFAULT '0',
  `qtydiscount` int(1) NOT NULL DEFAULT '0',
  `min_result` decimal(11,2) DEFAULT NULL,
  `use_product_shipping` tinyint(1) NOT NULL DEFAULT '1',
  `one_time_cost` double DEFAULT '0',
  `is_allow_uploads` int(11) NOT NULL DEFAULT '0',
  `max_allow_uploads` int(11) NOT NULL DEFAULT '1',
  `is_unlimited_uploads` int(11) NOT NULL DEFAULT '0',
  `is_upload_independ_from_qty` tinyint(1) DEFAULT '0',
  `is_use_additional_free_attrs` tinyint(1) DEFAULT '0',
  `is_use_additional_shippings` BOOLEAN DEFAULT 0,
  `is_use_additional_files` BOOLEAN DEFAULT 0,
  `is_use_additional_media` BOOLEAN DEFAULT 0,
  `is_use_additional_customize` BOOLEAN DEFAULT 0,
  `is_use_additional_usergroup_permission` BOOLEAN NOT NULL DEFAULT 0,
  `preview_total_price` decimal(18,6) NOT NULL DEFAULT '0.000000',
  `is_activated_price_per_consignment_upload` tinyint(1) NOT NULL DEFAULT '0',
  `preview_calculated_weight` float DEFAULT NULL,
  `alias_en-GB` varchar(255) NOT NULL,
  `meta_title_en-GB` varchar(255) NOT NULL,
  `meta_description_en-GB` text NOT NULL,
  `meta_keyword_en-GB` text NOT NULL,
  `is_required_upload` BOOLEAN NOT NULL DEFAULT FALSE,
  `is_show_bulk_prices` BOOLEAN NOT NULL DEFAULT TRUE,
  `production_time` SMALLINT NOT NULL,
  `temp_data` TEXT NOT NULL,
  `expiration_date` DATE NULL DEFAULT NULL,
  `is_use_additional_description` BOOLEAN NOT NULL DEFAULT 0,
  `is_use_additional_characteristics` BOOLEAN DEFAULT 0,
  `is_use_additional_related_products` BOOLEAN DEFAULT 0,
  `is_use_additional_details` BOOLEAN DEFAULT false,
  `quantity_select` varchar(255) NOT NULL,
  `factory` VARCHAR(255) NOT NULL,
  `storage` VARCHAR(255) NOT NULL,
  `equal_steps` TINYINT(1) NOT NULL,
  `one_click_buy` TINYINT(1) NOT NULL,
  `is_activated_price_per_consignment_upload_disable_quantity` BOOLEAN NOT NULL DEFAULT 0,
  `publish_editor_pdf` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_added_content` (
  `product_id` int(11) UNSIGNED NOT NULL,
  `description_info_nl-NL` text CHARACTER SET latin1 NOT NULL,
  `description_info_en-GB` text CHARACTER SET latin1 NOT NULL,
  `description_info_es-ES` text CHARACTER SET latin1 NOT NULL,
  `description_info_fr-FR` text CHARACTER SET latin1 NOT NULL,
  `description_info_de-DE` text CHARACTER SET latin1 NOT NULL,
  `description_info_it-IT` text CHARACTER SET latin1 NOT NULL,
  `description_info_pl-PL` text CHARACTER SET latin1 NOT NULL,
  `description_info_ru-RU` text CHARACTER SET latin1 NOT NULL,
  `description_info_sv-SE` text CHARACTER SET latin1 NOT NULL,
  `description_en-GB` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_attr` (
  `product_attr_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `buy_price` decimal(12,2) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) NOT NULL,
  `count` int(11) NOT NULL,
  `unlimited` tinyint(1) NOT NULL,
  `ean` varchar(100) NOT NULL,
  `weight` decimal(12,4) NOT NULL,
  `weight_volume_units` decimal(14,4) NOT NULL,
  `ext_attribute_product_id` int(11) NOT NULL,
  `attr_11` int(11) NOT NULL,
  `attr_13` int(11) NOT NULL,
  `attr_14` int(11) NOT NULL,
  `attr_16` int(11) NOT NULL,
  `attr_17` int(11) NOT NULL,
  `attr_18` int(11) NOT NULL,
  `attr_19` int(11) NOT NULL,
  `attr_20` int(11) NOT NULL,
  `attr_21` int(11) NOT NULL,
  `attr_22` int(11) NOT NULL,
  `attr_23` int(11) NOT NULL,
  `attr_24` int(11) NOT NULL,
  `attr_25` int(11) NOT NULL,
  `attr_26` int(11) NOT NULL,
  `attr_28` int(11) NOT NULL,
  `attr_31` int(11) NOT NULL,
  `attr_32` int(11) NOT NULL,
  `attr_33` int(11) NOT NULL,
  `attr_34` int(11) NOT NULL,
  `attr_35` int(11) NOT NULL,
  `attr_36` int(11) NOT NULL,
  `attr_37` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `attr_38` int(11) NOT NULL,
  `attr_9999` int(11) NOT NULL,
  `attr_background_value` text NOT NULL,
  `sorting` int(11) DEFAULT '1',
  `low_stock_attr_notify_status` tinyint(1) DEFAULT '0',
  `low_stock_attr_notify_number` int(11) DEFAULT '0',
  `expiration_date` DATE NULL DEFAULT NULL,  
  `production_time` SMALLINT(6) NOT NULL,
  `product_price_type` int(11) NOT NULL DEFAULT '0',
  `add_price_unit_id` int(3) NOT NULL DEFAULT '0',
  `qtydiscount` int(1) NOT NULL DEFAULT '0',
  `product_packing_type` INT(4) NOT NULL DEFAULT '0',
  `factory` VARCHAR(255) NOT NULL,
  `storage` VARCHAR(255) NOT NULL,
  `product_tax_id` tinyint(3) NOT NULL DEFAULT '0',
  `product_manufacturer_id` int(11) NOT NULL DEFAULT '0',
  `delivery_times_id` int(4) NOT NULL DEFAULT '0',
  `label_id` int(11) NOT NULL DEFAULT '0',
  `quantity_select` varchar(255) NOT NULL,
  `max_count_product` int(11) NOT NULL DEFAULT '0',
  `min_count_product` int(11) NOT NULL DEFAULT '0',
  `basic_price_unit_id` int(3) NOT NULL DEFAULT '0',
  `equal_steps` TINYINT(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_attr2` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL,
  `attr_value_id` int(11) NOT NULL,
  `price_mod` char(1) NOT NULL,
  `addprice` decimal(12,6) NOT NULL,
  `weight` decimal(12,4) NOT NULL,
  `sorting` int(11) DEFAULT '1',
  `price_type` int(11) NOT NULL DEFAULT '0',
  `expiration_date` DATE NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_extra_fields` (
  `id` int(11) NOT NULL,
  `allcats` tinyint(1) NOT NULL,
  `cats` text NOT NULL,
  `type` tinyint(1) NOT NULL,
  `group` tinyint(4) NOT NULL,
  `ordering` int(6) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `description_de-DE` text NOT NULL,
  `description_en-GB` text NOT NULL,
  `description_es-ES` text NOT NULL,
  `description_fr-FR` text NOT NULL,
  `description_it-IT` text NOT NULL,
  `description_nl-NL` text NOT NULL,
  `description_pl-PL` text NOT NULL,
  `description_ru-RU` text NOT NULL,
  `multilist` tinyint(1) NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `description_sv-SE` text NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `description_fr-CA` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_extra_field_groups` (
  `id` int(11) NOT NULL,
  `ordering` int(6) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_extra_field_values` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `ordering` int(6) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_files` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `demo` varchar(255) NOT NULL,
  `demo_descr_de-DE` varchar(255) NOT NULL,
  `demo_descr_en-GB` varchar(255) NOT NULL,
  `demo_descr_es-ES` varchar(255) NOT NULL,
  `demo_descr_it-IT` varchar(255) NOT NULL,
  `demo_descr_fr-FR` varchar(255) NOT NULL,
  `demo_descr_nl-NL` varchar(255) NOT NULL,
  `demo_descr_pl-PL` varchar(255) NOT NULL,
  `demo_descr_ru-RU` varchar(255) NOT NULL,
  `demo_descr_sv-SE` varchar(255) NOT NULL,
  `demo_descr_fr-CA` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `file_descr_de-DE` varchar(255) NOT NULL,
  `file_descr_en-GB` varchar(255) NOT NULL,
  `file_descr_es-ES` varchar(255) NOT NULL,
  `file_descr_it-IT` varchar(255) NOT NULL,
  `file_descr_fr-FR` varchar(255) NOT NULL,
  `file_descr_nl-NL` varchar(255) NOT NULL,
  `file_descr_pl-PL` varchar(255) NOT NULL,
  `file_descr_ru-RU` varchar(255) NOT NULL,
  `file_descr_sv-SE` varchar(255) NOT NULL,
  `file_descr_fr-CA` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_free_attr` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `attr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `image_thumb` varchar(255) NOT NULL DEFAULT '',
  `image_name` varchar(255) NOT NULL DEFAULT '',
  `image_full` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `ordering` tinyint(4) NOT NULL,
  `image_url` TEXT NOT NULL,
  `video_code` TEXT NOT NULL,
  `type` INT NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_option` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_prices` (
  `price_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discount` decimal(18,6) NOT NULL,
  `product_quantity_start` double(18,6) NOT NULL,
  `product_quantity_finish` double(18,6) NOT NULL,
  `usergroup` int(10) NOT NULL DEFAULT '0',
  `price` decimal(18,6) NOT NULL,
  `start_discount` decimal(18,6) NOT NULL,
  `usergroup_prices` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_prices_group` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_netto` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) NOT NULL,
  `product_is_add_price` tinyint(1) NOT NULL DEFAULT '0',
  `add_price_unit_id` int(3) NOT NULL DEFAULT '0',
  `total_calculated_price_without_tax` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_relations` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_related_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_relations2` (
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_related_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  `review` text NOT NULL,
  `mark` int(11) NOT NULL,
  `publish` tinyint(1) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `reviewfile` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_shipping` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sh_pr_method_id` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_pack` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_to_categories` (
  `product_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `product_ordering` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_products_videos` (
  `video_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `video_name` varchar(255) NOT NULL DEFAULT '',
  `video_preview` varchar(255) NOT NULL,
  `video_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_product_labels` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `image_de-DE` varchar(255) NOT NULL,
  `image_en-GB` varchar(255) NOT NULL,
  `image_fr-FR` varchar(255) NOT NULL,
  `image_it-IT` varchar(255) NOT NULL,
  `image_pl-PL` varchar(255) NOT NULL,
  `image_nl-NL` varchar(255) NOT NULL,
  `image_ru-RU` varchar(255) NOT NULL,
  `image_fr-CA` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_product_labels` (`id`, `name`, `image`, `name_de-DE`, `name_en-GB`, `name_fr-FR`, `name_it-IT`, `name_pl-PL`, `name_nl-NL`, `name_ru-RU`, `name_fr-CA`, `image_de-DE`, `image_en-GB`, `image_fr-FR`, `image_it-IT`, `image_pl-PL`, `image_nl-NL`, `image_ru-RU`, `image_fr-CA`) VALUES
(1, 'New', 'new.png', 'New', 'New', 'New', 'New', 'New', 'New', 'New', 'New', '', '', '', '', '', '', '', ''),
(2, 'Sale', 'sale.png', 'Sale', 'Sale', 'Sale', 'Sale', 'Sale', 'Sale', 'Sale', 'Sale', '', '', '', '', '', '', '', '');

DROP TABLE IF EXISTS `#__jshopping_search`;
CREATE TABLE IF NOT EXISTS `#__jshopping_search` (
  `keyword` text NOT NULL,
  `links` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `lang_variable` text NOT NULL,
  `keyword_en-GB` text NOT NULL,
  `keyword_de-DE` text NOT NULL,
  `keyword_es-ES` text NOT NULL,
  `keyword_fr-FR` text NOT NULL,
  `keyword_it-IT` text NOT NULL,
  `keyword_nl-NL` text NOT NULL,
  `keyword_pl-PL` text NOT NULL,
  `keyword_ru-RU` text NOT NULL,
  `keyword_sv-SE` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;
INSERT INTO `#__jshopping_search` (`keyword`, `links`, `id`, `title`, `lang_variable`, `keyword_en-GB`, `keyword_de-DE`, `keyword_es-ES`, `keyword_fr-FR`, `keyword_it-IT`, `keyword_nl-NL`, `keyword_pl-PL`, `keyword_ru-RU`, `keyword_sv-SE`) VALUES
('TAXES', 'index.php?option=com_jshopping&controller=taxes', 1, 'Options / Taxes', 'COM_SMARTSHOP_PANEL_TAXES', 'Taxes', 'Steuersätze', 'Impuestos', 'Taxes', 'Tasse', 'Belastingen', 'Podatki', 'Налоги', 'Skatter'),
('EXTENDED TAX RULE FOR', 'index.php?option=com_jshopping&controller=exttaxes&back_tax_id=1', 2, 'Options / Taxes / Extended rule tax', 'COM_SMARTSHOP_EXTENDED_TAX_RULE_FOR', 'Extended tax rule for', 'Erweiterte steuerliche Regelung für', 'Impuesto extendido por', 'Règles d`imposition étendues', '', '', '', 'Расширенные налоговые правила для', 'Utökad skatteregel för'),
('OPTIONS', 'index.php?option=com_jshopping&controller=other', 3, 'Options', 'COM_SMARTSHOP_OTHER_ELEMENTS', 'Options', 'Optionen', 'Opciones', 'Options', 'Opzioni', 'Opties', 'Opcje', 'Опции', 'Alternativ'),
('MANUFACTURERS', 'index.php?option=com_jshopping&controller=manufacturers', 4, 'Options / Manufacturers', 'COM_SMARTSHOP_SEOPAGE_MANUFACTURERS', 'Manufacturers', 'Hersteller', 'Fabricantes', 'Fabricants', 'Produttori', 'Fabrikanten', '', 'Производитель', 'Tillverkare'),
('COUPONS', 'index.php?option=com_jshopping&controller=coupons', 5, 'Options / Coupons', 'COM_SMARTSHOP_MENU_COUPONS', 'Coupons', 'Gutscheine', 'Cupones', 'Coupons', 'Buoni sconto', 'Coupons', 'Kupony', 'Купоны', 'Kuponger'),
('CURRENCIES,CURRENCY', 'index.php?option=com_jshopping&controller=currencies', 6, 'Options / Currencies', 'COM_SMARTSHOP_PANEL_CURRENCIES,COM_SMARTSHOP_CURRENCY_PARAMETERS', 'Currencies Currency', 'Währungen Währung', 'Monedas Moneda', 'Monnaies Monnaie', 'Valute Valuta', 'Valuta Valuta', 'Waluty Waluta', 'Валюта Валюта', 'Valutor Valuta'),
('PAYMENTS', 'index.php?option=com_jshopping&controller=payments', 7, 'Options / Payments', 'COM_SMARTSHOP_PANEL_PAYMENTS', 'Payments', 'Bezahlungsart', 'Pagos', 'Paiements', 'Pagamenti', 'Betaalmethodes', 'Płatności', 'Способ оплаты', 'Betalningar'),
('SHIPPING METHODS', 'index.php?option=com_jshopping&controller=shippings', 8, 'Options / panel shippings', 'COM_SMARTSHOP_PANEL_SHIPPINGS', 'Shipping methods', 'Versandarten', 'Metodos de envío', 'Moyens d`expédition', 'Metodi di spedizione', 'Verzendmethoden', 'Wysyłka', 'Способ доставки', 'Fraktsätt'),
('SHIPPING PRICES', 'index.php?option=com_jshopping&controller=shippingsprices', 9, 'Options / Shipping prices', 'COM_SMARTSHOP_PANEL_SHIPPINGS_PRICES', 'Shipping prices', 'Versandkosten', 'Precios de envío', 'Tarifs d`expédition', 'Prezzi spedizione', 'Verzendkosten', 'Ceny sposobów wysyłki', 'Цены на доставку', 'Shipping prices'),
('DELIVERY TIME', 'index.php?option=com_jshopping&controller=deliverytimes', 10, 'Options / Delivery time', 'COM_SMARTSHOP_DELIVERY_TIME', 'Delivery time', 'Lieferzeit', 'Tiempo de entrega', 'Délai de livraison', 'Tempo di spedizione', 'Aflevertijd', 'Czas dostawy', 'Срок поставки', 'Leveranstid'),
('ORDER STATUS', 'index.php?option=com_jshopping&controller=orderstatus', 11, 'Options / Order status', 'COM_SMARTSHOP_PANEL_ORDER_STATUS', 'Order status', 'Bestellstatus', 'Estado de pedidos', 'Statut commande', 'Stato dell ordine', 'Bestelstatus', 'Stany zamówień', 'Статус заказа', 'Order status'),
('COUNTRY LIST,COUNTRIES,COUNTRY', 'index.php?option=com_jshopping&controller=countries', 12, 'Options / Countries', 'COM_SMARTSHOP_PANEL_COUNTRIES,COM_SMARTSHOP_COUNTRIES,COM_SMARTSHOP_FIELD_COUNTRY', 'Country list Countries Country', 'Länderauswahl Länder Land', 'Lista de paises Países País', 'Liste pays Pays Pays', 'Lista nazioni Nazioni Nazione', 'Landenlijst Landen Land', 'Lista krajów Kraje Kraj', 'Список стран Страны Страна', 'Länder Länder Land'),
('ATTRIBUTES', 'index.php?option=com_jshopping&controller=attributes', 13, 'Options / Attributes', 'COM_SMARTSHOP_PANEL_ATTRIBUTES', 'Attributes', 'Attribute', 'Atributos', 'Attributs', 'Attributi', 'Attributen', 'Atrybuty', 'Атрибуты', 'Attribut'),
('FREE ATTRIBUTES', 'index.php?option=com_jshopping&controller=freeattributes', 14, 'Options / Free attributes', 'COM_SMARTSHOP_FREE_ATTRIBUTES', 'Free attributes', 'Freie Attribute', 'Atributos libres', 'Attributs Libres', 'Attributi liberi', 'Vrije attributen', '', 'Свободные атрибуты', 'Fria attribut'),
('UNITS OF MEASURE,UNITS', 'index.php?option=com_jshopping&controller=units', 15, 'Options / Unit measure', 'COM_SMARTSHOP_LIST_UNITS_MEASURE', 'Units of measure', 'Messeinheiten', 'Unidades de medidas', 'Unités de mesure', 'Unit&agrave; di misura', 'Maateenheden', 'jednostki miary', 'Единицы измерения', 'Lista över måttenheter'),
('USER GROUPS,USERGROUPS', 'index.php?option=com_jshopping&controller=usergroups', 16, 'Options / Usergroups', 'COM_SMARTSHOP_PANEL_USERGROUPS,COM_SMARTSHOP_USERGROUPS', 'User groups Usergroups', 'Benutzergruppen Benutzergruppen', 'Grupos de usuarios Grupos de usuarios', 'Groupes d`utilisateurs Groupe d`utilisateurs', 'Gruppi utenti Gruppi Utenti', 'Gebruikersgroepen Gebruikersgroep', 'Grupy użytkowników Grupy użytkowników', 'Группы пользователей Группы', 'Användargrupper Användargrupper'),
('PRODUCT COMMENTS,REVIEW', 'index.php?option=com_jshopping&controller=reviews', 18, 'Options / Panel reviews', 'COM_SMARTSHOP_PANEL_REVIEWS', 'Product comments', 'Produkt Kommentar', 'Comentarios de artículos', 'Commentaires produit', 'Commenti sui prodotti', 'Artikelcommentaar', 'Komentarze produktów', 'Отзывы о товарах', 'Produkt kommentarer'),
('PRODUCT LABELS,LABELS', 'index.php?option=com_jshopping&controller=productlabels', 19, 'Options / LIST PRODUCT LABELS', 'COM_SMARTSHOP_LIST_PRODUCT_LABELS', 'Product labels', 'Produktetiketten', 'Etiquetas de artículos', 'Etiquettes produit', 'Etichette prodotti', 'Artikellabels', 'Etykiety produktu', 'Метки товара', 'Produkt etiketter'),
('PRODUCT CHARACTERISTICS,CHARACTERISTICS', 'index.php?option=com_jshopping&controller=productfields', 20, 'Options / PRODUCT EXTRA FIELDS', 'COM_SMARTSHOP_PRODUCT_EXTRA_FIELDS,COM_SMARTSHOP_EXTRA_FIELDS', 'Product Characteristics Characteristics', 'Produktcharakteristik Charakteristik', 'Características del artículo Características', 'Caractéristiques produit Caractéristiques', 'Caratteristiche Prodotto Caratteristiche', 'Artikelkenmerken Kenmerken', 'Charakterystyki produktu Charakterystyki', 'Характеристики товаров Характеристики', 'Produkt egenskaper Egenskaper'),
('IMPORT & EXPORT,IMPORT,EXPORT,IMPORT EXPORT,IMPORTEXPORT', 'index.php?option=com_jshopping&controller=productfields', 21, 'Options / Panel Import Export', 'COM_SMARTSHOP_PANEL_IMPORT_EXPORT,COM_SMARTSHOP_TEMPLATE_CREATOR_TEMPLATE_IMPORT,COM_SMARTSHOP_TEMPLATE_CREATOR_TEMPLATE_EXPORT', 'Import & Export Import Export', 'Import & Export Import Export', 'Importar y Exportar  ', 'Import & Export  ', 'Importa & Esporta  ', 'Import & Export  ', 'Import & Eksport  ', 'Импорт и Экспорт  ', 'Import & Export  '),
('ADDONS', 'index.php?option=com_jshopping&controller=addons', 22, 'Options / Addons', 'COM_SMARTSHOP_ADDONS', 'Addons', 'Addons', 'Complementos', '', '', '', '', 'Дополнения', 'Tillägg'),
('FORMULA CALCULATOR,FORMULA,CALCULATOR,FORMULACALCULATOR', 'index.php?option=com_jshopping&controller=formula_calculation', 23, 'Options / FACP FORMULA', 'COM_SMARTSHOP_FREE_ATTRIBUTE_CALCULE_PRICE,COM_SMARTSHOP_FACP_FORMULA', 'Formula calculator Formula', 'Formelrechner Formel', '', '', '', '', '', 'Формула калькулятор Формула', ''),
('OFFER', 'index.php?option=com_jshopping&controller=offer_and_order', 24, 'Options / OFFER AND ORDER ANGEBOT', 'COM_SMARTSHOP_OFFER_AND_ORDER_ANGEBOT', 'Offer', 'Angebot', '', '', '', '', '', '', ''),
('UPLOAD', 'index.php?option=com_jshopping&controller=upload', 25, 'Options / Upload', 'COM_SMARTSHOP_UPLOAD', 'Video file', 'Video wählen', 'Archivo de video', 'Fichier vidéo', 'Seleziona video', 'Video bestand', 'Plik video', 'Выберите видео', 'Video fil'),
('SHOP FUNCTIONS,FUNCTIONS', 'index.php?option=com_jshopping&controller=config', 26, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION', 'COM_SMARTSHOP_SHOP_FUNCTION', 'Shop functions', 'Shop-Funktionen', 'Funciones de tienda', 'Fonctions de la boutique', 'Funzioni Negozio', 'Winkelfunctionaliteit', 'Funkcje sklepu', 'Функции магазина', 'Butiksfunktioner'),
('GENERAL', 'index.php?option=com_jshopping&controller=config&task=general', 27, 'COM_SMARTSHOP_CONFIG / General', 'COM_SMARTSHOP_PAYMENT_GENERAL', 'General', 'Allgemein', 'General', 'Général', 'Generale', 'Algemeen', 'Ogólne', 'Главный', 'Allmän'),
('PRODUCT', 'index.php?option=com_jshopping&controller=config&task=catprod', 28, 'COM_SMARTSHOP_CONFIG / Product', 'COM_SMARTSHOP_OC_CART_BACK_TO_SHOP_PRODUCT', 'Product', 'Produkt', '', '', '', '', '', 'Продукт', ''),
('CHECKOUT', 'index.php?option=com_jshopping&controller=config&task=checkout', 29, 'COM_SMARTSHOP_CONFIG / Checkout', 'COM_SMARTSHOP_CHECKOUT', 'Checkout', 'Kasse', 'Caja Realizar Pedido', 'Passer la commande', 'Pagamento', 'Afrekenen', 'Zamówienie', 'Заказ', 'Checka ut'),
('FIELDS REGISTRATION', 'index.php?option=com_jshopping&controller=config&task=fieldregister', 30, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_REGISTER_FIELDS', 'COM_SMARTSHOP_REGISTER_FIELDS', 'Fields Registration', 'Registrierungsfelder', 'Campos de registro', 'Champs de l`enregistrement', 'Campi di registrazione', 'Registratievelden', 'Pola rejestracyjne', 'Поля регистрации', 'Registreringsfält'),
('CURRENCY', 'index.php?option=com_jshopping&controller=config&task=currency', 31, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CURRENCY_PARAMETERS', 'COM_SMARTSHOP_CURRENCY_PARAMETERS', 'Currency', 'Währung', 'Moneda', 'Monnaie', 'Valuta', 'Valuta', 'Waluta', 'Валюта', 'Valuta'),
('MEDIA', 'index.php?option=com_jshopping&controller=config&task=image', 32, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS', 'COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS', 'Media', 'Medien', 'Media', 'Media', 'Media', 'Media', 'Media', 'Media', 'Media'),
('CONTENT', 'index.php?option=com_jshopping&controller=config&task=content', 33, 'COM_SMARTSHOP_CONFIG / Content', 'COM_SMARTSHOP_CONTENT', 'Content', 'Inhalt', 'Content', 'Content', 'Content', 'Content', 'Content', 'Content', 'Content'),
('SHOP INFO', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 34, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO', 'COM_SMARTSHOP_STORE_INFO', 'Shop info', 'Shop Informationen', 'Info de la tienda', 'Infos sur la boutique', 'Informazioni negozio', 'Winkelinformatie', 'Info o sklepie', 'Информация о магазине', 'Butik info'),
('OTHER CONFIG', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 35, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC', 'COM_SMARTSHOP_OC', 'Other config', 'Andere Config', 'Otra configuración', '', '', '', '', 'Другие параметры', 'Annan Konfiguration'),
('ORDERS', 'index.php?option=com_jshopping&controller=config&task=orders', 36, 'COM_SMARTSHOP_CONFIG / Orders', 'COM_SMARTSHOP_MENU_ORDERS', 'Orders', 'Bestellungen', 'Pedidos', 'Commandes', 'Ordini', 'Bestellingen', 'Zamówienia', 'Заказы', 'Order'),
('PDF HUB', 'index.php?option=com_jshopping&controller=config&task=pdf', 37, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF', 'COM_SMARTSHOP_CONFIGURATION_PDF', 'PDF hub', 'PDF-Hub', 'PDF hub', 'PDF hub', 'PDF hub', 'PDF hub', 'PDF hub', 'PDF hub', 'PDF hub'),
('WISHLIST', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 38, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Enable wishlist', 'COM_SMARTSHOP_SEOPAGE_WISHLIST', 'Wishlist', 'Wunschliste', 'Lista de deseos', 'Liste de souhaits', 'Lista dei desideri', 'Wenslijst', '', 'Список пожеланий', 'Önskelista'),
('PURCHASE WITHOUT REGISTERING', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 39, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Purchase without registering', 'COM_SMARTSHOP_PURCHASE_WITHOUT_REGISTERING', 'Purchase without registering', 'Kauf ohne Registrierung', 'Comprar sin registrarse', 'Achat sans inscription', 'Acquista senza registrazione', 'Bestellen zonder registratie', 'Zakupy bez rejestracji', 'Покупка без регистрации', 'Handla utan registrering'),
('USE AS CATALOG', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 40, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / COM_SMARTSHOP_USER_AS_CATALOG', 'COM_SMARTSHOP_USER_AS_CATALOG', 'Use as catalog', 'Verwendung des Shop als Katalog', 'Usar como catálogo', 'Utiliser la boutique comme catalogue', 'Usa come catalogo (carrello disattivato)', 'Als catalogus gebruiken', 'Użytkownik jako katalog', 'Использовать как каталог', 'Använd som katalog'),
('SHIPPINGS', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 41, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Shippings', 'COM_SMARTSHOP_SHIPPINGS', 'Shippings', 'Lieferungsart', 'Envíos', 'Livraison', 'Spedizioni', 'Verzending', 'Dostawa', 'Доставка', 'Leverans'),
('PAYMENTS', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 42, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Payments', 'COM_SMARTSHOP_PANEL_PAYMENTS', 'Payments', 'Bezahlungsart', 'Pagos', 'Paiements', 'Pagamenti', 'Betaalmethodes', 'Płatności', 'Способ оплаты', 'Betalningar'),
('TAX', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 43, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Tax', 'COM_SMARTSHOP_VAT', 'TAX', 'MwSt', 'IMPUESTO', 'TVA', 'IVA', 'BTW', 'VAT', 'VAT', 'Moms'),
('STOCK', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 44, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Stock', 'COM_SMARTSHOP_STOCK', 'Stock', 'Lager', 'Stock', '', '', '', '', 'Склад', 'Lager'),
('ATTRIBUTES', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 45, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Attributes', 'COM_SMARTSHOP_PANEL_ATTRIBUTES', 'Attributes', 'Attribute', 'Atributos', 'Attributs', 'Attributi', 'Attributen', 'Atrybuty', 'Атрибуты', 'Attribut'),
('FREE ATTRIBUTES', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 46, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Free attributes', 'COM_SMARTSHOP_FREE_ATTRIBUTES', 'Free attributes', 'Freie Attribute', 'Atributos libres', 'Attributs Libres', 'Attributi liberi', 'Vrije attributen', '', 'Свободные атрибуты', 'Fria attribut'),
('DELIVERY TIME', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 47, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Delivery time', 'COM_SMARTSHOP_DELIVERY_TIME', 'Delivery time', 'Lieferzeit', 'Tiempo de entrega', 'Délai de livraison', 'Tempo di spedizione', 'Aflevertijd', 'Czas dostawy', 'Срок поставки', 'Leveranstid'),
('VIDEOS', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 48, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / COM_SMARTSHOP_PRODUCT_VIDEOS', 'COM_SMARTSHOP_PRODUCT_VIDEOS', 'Videos', 'Videos', 'Videos', 'Vidéos', 'Video', 'Videos', 'Filmy', 'Видео', 'Video'),
('RELATED PRODUCTS', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 50, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / COM_SMARTSHOP_PRODUCTS_RELATED', 'COM_SMARTSHOP_PRODUCTS_RELATED', 'Amount of related products in a row', 'Anzahl von ähnlichen Produkten in der Reihe', 'Cantidad de artículos relacionados por fila', 'Nombre de produits similaires par ligne', 'Quantit&agrave; di prodotti correlati per riga', 'Aantal gerelateerde artikelen in een rij', 'Ilość podobnych produktów w rzędzie', 'Количество сопутствующих товаров в ряду', 'Antal relaterade produkter på en rad'),
('FILES', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 51, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Files', 'COM_SMARTSHOP_FILES', 'Files', 'Dateien', 'Archivos', 'Fichiers', 'Files', 'Bestanden', 'Pliki', 'Файлы', 'Filer'),
('LABEL', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 52, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Label', 'COM_SMARTSHOP_LABEL', 'Label', 'Etikett', 'Etiqueta', 'Etiquette', 'Etichetta', 'Label', 'Etykieta', 'Метка', 'Etikett'),
('PURCHASE PRICE', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 53, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / COM_SMARTSHOP_PRODUCT_BUY_PRICE', 'COM_SMARTSHOP_PRODUCT_BUY_PRICE', 'Purchase price', 'Einkaufspreis', 'Precio de compra', 'Prix de vente', 'Prezzo d&acute;ordine', 'Inkoopsprijs', 'Cena zakupu', 'Закупочная цена', 'Inköpsspris'),
('BASIC PRICE', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 54, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / Basic price', 'COM_SMARTSHOP_BASIC_PRICE', 'Basic price', 'Grundpreis', 'Precio básico', 'Prix de base', 'Prezzo base', 'Basisprijs', 'Cena podstawowa', 'Базовая цена', 'Baspris'),
('CHARACTERISTICS', 'index.php?option=com_jshopping&controller=config&task=adminfunction', 55, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_SHOP_FUNCTION / COM_SMARTSHOP_EXTRA_FIELDS', 'COM_SMARTSHOP_EXTRA_FIELDS', 'Characteristics', 'Charakteristik', 'Características', 'Caractéristiques', 'Caratteristiche', 'Kenmerken', 'Charakterystyki', 'Характеристики', 'Egenskaper'),
('SHOP ADMINISTRATOR E-MAIL,SHOP ADMINISTRATOR EMAIL ', 'index.php?option=com_jshopping&controller=config&task=general', 56, 'COM_SMARTSHOP_CONFIG / General / COM_SMARTSHOP_EMAIL_ADMIN', 'COM_SMARTSHOP_EMAIL_ADMIN', 'Shop administrator E-Mail', 'Emailadresse Shop-Administrator', 'Email del administrador de la tienda', 'E-mail administrateur de la boutique', 'E-mail amministratore negozio', 'Winkeladministrator E-Mail', 'E-Mail administratora sklepu', 'E-mail администратора магазина', 'Butikens administrators E-post'),
('DEFAULT LANGUAGE FOR COPYING', 'index.php?option=com_jshopping&controller=config&task=general', 57, 'COM_SMARTSHOP_CONFIG / General / COM_SMARTSHOP_DEFAULT_LANG_FOR_COPY', 'COM_SMARTSHOP_DEFAULT_LANG_FOR_COPY', 'Default language for copying', 'Standard-Sprache für Kopieren', 'Idioma por defecto para copiar', 'Langue par défaut pour copie', 'Lingua predefinita per la copia', 'Standaardtaal voor kopieren', 'Domyslny język kopiowania', 'Язык по умолчанию для копирования', 'Förvalt språk för kopiering'),
('TEMPLATE', 'index.php?option=com_jshopping&controller=config&task=general', 58, 'COM_SMARTSHOP_CONFIG / General / Template', 'COM_SMARTSHOP_TEMPLATE', 'Template', 'Template für Kategorie', 'Plantilla', 'Template', 'Template per le categorie', 'Template', 'Szablon', 'Шаблон категории', 'Template'),
('DISPLAYING PRICES IN THE ADMIN AREA', 'index.php?option=com_jshopping&controller=config&task=general', 59, 'COM_SMARTSHOP_CONFIG / General / COM_SMARTSHOP_DISPLAY_PRICE_ADMIN', 'COM_SMARTSHOP_DISPLAY_PRICE_ADMIN', 'Displaying prices in the admin area', 'Angezeigte Preise im Admin-Bereich', 'Mostrar precios en el área de admnistración', 'Afficher les prix dans la partie admin', 'Visualizza prezzi nell amministrazione', 'Toon prijzen in de admin console', 'Prezentacja cen w zapleczu', 'Отображение цен в админке', 'Visar pris i admin'),
('DISPLAYING PRICES IN THE FRONT AREA', 'index.php?option=com_jshopping&controller=config&task=general', 60, 'COM_SMARTSHOP_CONFIG / General / COM_SMARTSHOP_DISPLAY_PRICE_FRONT', 'COM_SMARTSHOP_DISPLAY_PRICE_FRONT', 'Displaying prices in the front end', 'Angezeigte Preise im Frontend', 'Mostrar precios en el front end', 'Afficher les prix dans la partie client', 'Visualizza prezzi nel front end', 'Toon prijzen op de front end', 'Prezentacja cen na stronie', 'Отображение цен на сайте', 'Visar pris på framsidan'),
('DATE FORMAT', 'index.php?option=com_jshopping&controller=config&task=general', 61, 'COM_SMARTSHOP_CONFIG / General / COM_SMARTSHOP_IE_UNICSV_FIELDDATEFORMAT', 'COM_SMARTSHOP_IE_UNICSV_FIELDDATEFORMAT', 'Date format', 'Datumsformat', '', '', '', '', '', '', ''),
('PRODUCT PRICE PRECISION', 'index.php?option=com_jshopping&controller=config&task=general', 62, 'COM_SMARTSHOP_CONFIG / General / COM_SMARTSHOP_OC_PRODUCT_PRICE_PRECISION', 'COM_SMARTSHOP_OC_PRODUCT_PRICE_PRECISION', 'Product price precision', 'Produktpreis Dezimalstellen', 'Precisión del precio del artículo', '', '', '', '', 'Точность цены продукта', 'Exakt produktpris'),
('NOT REDIRECT IN CART AFTER BUY', 'index.php?option=com_jshopping&controller=config&task=catprod', 63, 'COM_SMARTSHOP_CONFIG / Product / Not redirect in cart after buy', 'COM_SMARTSHOP_NOT_REDIRECT_IN_CART_AFTER_BUY', 'Not redirect in cart after buy', 'Nach dem Kauf nicht zurück zum Warenkorb', 'No redirigir al carrito después de comprar', 'Ne pas rediriger vers le panier après votre achat', 'Non andare al carrello dopo un acquisto', 'Niet terugkeren naar winkelwagen na aankoop', '', 'Не переходить в корзину после покупки', 'Inte omdirigera kundvagnen efter köp'),
('HIDE PRODUCTS WHICH ARE NOT AVALIBLE ON THE STOCK', 'index.php?option=com_jshopping&controller=config&task=catprod', 64, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_HIDE_PRODUCT_NOT_AVAIBLE_STOCK', 'COM_SMARTSHOP_HIDE_PRODUCT_NOT_AVAIBLE_STOCK', 'Hide products which are not avaible on the stock', 'Verbergen der Produkte, die nicht ab Lager lieferbar sind', 'Ocultar artículos que no tengan stock', 'Masquer les produits qui ne sont pas en stock', 'Nascondi i prodotti che non sono disponibili in magazzino', 'Artikelen die niet op voorraad zijn, zijn niet zichtbaar in de shop', 'Ukryj produkty, których nie ma w magazynie', 'Скрыть товары, которые не доступны на складе', 'Göm produkter som inte lagerförs'),
('HIDE BUY BUTTON WHEN PRODUCT ISN\'T AVALIBLE IN STOCK', 'index.php?option=com_jshopping&controller=config&task=catprod', 65, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_HIDE_BUY_PRODUCT_NOT_AVAIBLE_STOCK\r\n', 'COM_SMARTSHOP_HIDE_BUY_PRODUCT_NOT_AVAIBLE_STOCK', 'Hide buy button when product isn t available in stock', 'Deaktivieren des Button  Zum Warenkorb hinzufügen , wenn das Produkt nicht lieferbar ist', 'Ocultar botón comprar cuando el artículo no esté en stock', 'Masquer le bouton  Acheter  lorsque le produit n`est pas en stock', 'Nascondi pulsante di acquisto quando il prodotto non &egrave; disponibile in magazzino', 'Verberg de  koop  knop wanneer een artikel niet op voorraad is', 'Ukryj przycisk Kup jeżeli danego produktu nie ma w magazynie', 'Скрыть кнопку купить, если товара нет на складе', 'Göm köpknapp när produkt inte är i lager'),
('HIDE TEXT PRODUCT IS NOT AVALIBLE', 'index.php?option=com_jshopping&controller=config&task=catprod', 66, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_HIDE_HIDE_TEXT_PRODUCT_NOT_AVAILABLE', 'COM_SMARTSHOP_HIDE_HIDE_TEXT_PRODUCT_NOT_AVAILABLE', 'Hide text product is not avalible', '', '', '', '', '', '', '', ''),
('DISPLAY WEIGHT IN', 'index.php?option=com_jshopping&controller=config&task=catprod', 67, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_DISPLAY_WEIGHT_AS', 'COM_SMARTSHOP_DISPLAY_WEIGHT_AS', 'Display weight in', 'Anzeige Gewicht in', 'Mostrar Peso en', 'Afficher le Poids en', '', '', '', 'Показать вес в', 'Visa vikt i'),
('AMOUNT OF PRODUCTS ON PAGE', 'index.php?option=com_jshopping&controller=config&task=catprod', 68, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_COUNT_PRODUCTS_PAGE', 'COM_SMARTSHOP_COUNT_PRODUCTS_PAGE', 'Amount of products on page', 'Produktmenge pro Seite', 'Cantidad de artículos en página', 'Nombre d`articles par page', 'Numero di prodotti per pagina', 'Aantal artikelen per pagina', 'Ilość produktów na stronie', 'Количество товаров на странице', 'Antal produkter på sidan'),
('AMOUNT OF MANUFACTURERS ON PAGE', 'index.php?option=com_jshopping&controller=config&task=catprod', 69, 'COM_SMARTSHOP_CONFIG / PRODUCT / COM_SMARTSHOP_COUNT_MANUFACTURER_PAGE', 'COM_SMARTSHOP_COUNT_MANUFACTURER_PAGE', 'Amount of manufacturers on page', 'Hersteller Menge pro Seite', 'Cantidad de fabricantes por página', '', '', '', '', '', ''),
('CATEGORY SORTING', 'index.php?option=com_jshopping&controller=config&task=catprod', 71, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_ORDERING_CATEGORY', 'COM_SMARTSHOP_ORDERING_CATEGORY', 'Category sorting', 'Kategorien sortieren', 'Ordenar categoría', 'Tri catégories', 'Ordina Categoria', 'Categorie volgorde', 'Kolejność kategorii', 'Сортировка категорий', 'Kategori ordning'),
('MANUFACTURER SORTING', 'index.php?option=com_jshopping&controller=config&task=catprod', 72, 'COM_SMARTSHOP_CONFIG / Product / Manufacturer sorting', 'COM_SMARTSHOP_MANUFACTURER_SORTING', 'Manufacturer sorting', 'Hersteller sortieren', 'Ordenamiento de fabricante', 'Tri Fabriquants', '', '', '', 'Сортировка производителей', 'Sortering tillverkare'),
('PRODUCT SORTING', 'index.php?option=com_jshopping&controller=config&task=catprod', 73, 'COM_SMARTSHOP_CONFIG / Product / Product sorting', 'COM_SMARTSHOP_PRODUCT_SORTING', 'Product sorting', 'Produkte sortieren', 'Ordenar artículo', 'Tri produits', 'Ordina Prodotto', 'Artikel volgorde', 'Sortowanie produktów', 'Сортировка товаров', 'Produktordning'),
('SORT DIRECTION PRODUCT', 'index.php?option=com_jshopping&controller=config&task=catprod', 74, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_PRODUCT_SORTING_DIRECTION', 'COM_SMARTSHOP_PRODUCT_SORTING_DIRECTION', 'Sort direction Product', 'Richtung von der Produktsortierung', 'Dirección de orden del artículo', 'Sens du tri produits', 'Direzione Ordine Prodotto', 'Sorteren op artikel', 'Kierunek sortowania produktów', 'Направление сортировки товаров', 'Sorteringsordning Produkt'),
('SHOW PRODUCT WEIGHT', 'index.php?option=com_jshopping&controller=config&task=catprod', 75, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_SHOW_WEIGHT_PRODUCT', 'COM_SMARTSHOP_SHOW_WEIGHT_PRODUCT', 'Show product weight', 'Produktgewicht anzeigen', 'Mostrar peso del artículo', 'Afficher le poids de l`article', 'Mostra peso del prodotto', 'Toon gewicht artikel', 'Pokaż wagę produktu', 'Показать вес товара', 'Visa produktens vikt'),
('SHOW PRODUCT CODE', 'index.php?option=com_jshopping&controller=config&task=catprod', 76, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_SHOW_EAN_PRODUCT', 'COM_SMARTSHOP_SHOW_EAN_PRODUCT', 'Show product Code', 'Artikelnummer anzeigen', 'Mostrar código de artículo', 'Afficher code produit', 'Mostra Codice prodotto', 'Toon artikelcode', 'Pokaż kod produktu', 'Показать Код товара', 'Visa artikelnummer'),
('QUANTITY IN STOCK', 'index.php?option=com_jshopping&controller=config&task=catprod', 77, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_QTY_IN_STOCK', 'COM_SMARTSHOP_QTY_IN_STOCK', 'Quantity in stock', 'Menge auf Lager', 'Cantidad en stock', 'Quantité En Stock', '', '', '', 'Количество на складе', 'Antal i lager'),
('SHOW PRICE', 'index.php?option=com_jshopping&controller=config&task=catprod', 78, 'COM_SMARTSHOP_CONFIG / Product / Show price ', 'COM_SMARTSHOP_SHOW_PRICE', 'Show description price', 'Beschreibung Preis anzeigen', 'Mostrar descripción de precio', 'Montrer description prix', 'Mostra descrizione del Prezzo', 'Toon beschrijving prijs', '', 'Показывать описание цены', 'Visa beskrivning pris'),
('SHOW PLUS SHIPPING', 'index.php?option=com_jshopping&controller=config&task=catprod', 79, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_SHOW_PLUS_SHIPPING', 'COM_SMARTSHOP_SHOW_PLUS_SHIPPING', 'Show  Plus shipping ', ' Versandkosten  anzeigen', 'Mostrar  Más gastos de envío ', 'Afficher ', 'Mostra  Pi&ugrave; Spedizione ', 'Toon  Inclusief verzendkosten ', 'Pokaż  plus koszty wysyłki ', 'Показать  плюс доставка ', 'Visa  Plus frakt '),
('SHOW THE EXTRA PRICE FOR THE ATTRIBUTES', 'index.php?option=com_jshopping&controller=config&task=catprod', 80, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_PRODUCT_ATTRIBUT_ADD_PRICE_DISPLAY', 'COM_SMARTSHOP_PRODUCT_ATTRIBUT_ADD_PRICE_DISPLAY', 'Show the extra price for the attributes', 'Mehrpreis für Attribute anzeigen', 'Mostrar el precio extra por los atributos', 'Afficher le prix pour les attributs', 'Mostra il prezzo extra per gli attributi', 'Toon het extra bedrag voor de attributen', '', 'Показать дополнительную цену для атрибутов', 'Visa extrapris för attributen'),
('SORT ATTRIBUTES OF PRODUCT DEPENDENT', 'index.php?option=com_jshopping&controller=config&task=catprod', 81, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_PRODUCT_ATTRIBUT_SORTING \r\n / COM_SMARTSHOP_DEPENDENT', 'COM_SMARTSHOP_PRODUCT_ATTRIBUT_SORTING COM_SMARTSHOP_DEPENDENT', 'Sort attributes of product Dependent', 'Produktattribute sortieren Abhängig', 'Dependiente', 'Dépendant', 'Dipendente', 'Afhankelijk', '', 'Сортировка атрибутов в продукте Зависимый', 'Beroende'),
('SORT ATTRIBUTES OF PRODUCT INDEPENDENT', 'index.php?option=com_jshopping&controller=config&task=catprod', 82, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_PRODUCT_ATTRIBUT_SORTING / COM_SMARTSHOP_INDEPENDENT', 'COM_SMARTSHOP_PRODUCT_ATTRIBUT_SORTING COM_SMARTSHOP_INDEPENDENT', 'Sort attributes of product Independent', 'Produktattribute sortieren unabhängig', '', '', '', '', '', 'Сортировка атрибутов в продукте Независимый', ''),
('SHOW PRODUCT CODE', 'index.php?option=com_jshopping&controller=config&task=catprod', 83, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_SHOW_EAN_PRODUCT', 'COM_SMARTSHOP_SHOW_EAN_PRODUCT', 'Show product Code', 'Artikelnummer anzeigen', 'Mostrar código de artículo', 'Afficher code produit', 'Mostra Codice prodotto', 'Toon artikelcode', 'Pokaż kod produktu', 'Показать Код товара', 'Visa artikelnummer'),
('QUANTITY IN STOCK', 'index.php?option=com_jshopping&controller=config&task=catprod', 84, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_QTY_IN_STOCK', 'COM_SMARTSHOP_QTY_IN_STOCK', 'Quantity in stock', 'Menge auf Lager', 'Cantidad en stock', 'Quantité En Stock', '', '', '', 'Количество на складе', 'Antal i lager'),
('SHOW PRICE', 'index.php?option=com_jshopping&controller=config&task=catprod', 85, 'COM_SMARTSHOP_CONFIG / Product / Show price', 'COM_SMARTSHOP_SHOW_PRICE', 'Show description price', 'Beschreibung Preis anzeigen', 'Mostrar descripción de precio', 'Montrer description prix', 'Mostra descrizione del Prezzo', 'Toon beschrijving prijs', '', 'Показывать описание цены', 'Visa beskrivning pris'),
('SHOW PLUS SHIPPING', 'index.php?option=com_jshopping&controller=config&task=catprod', 86, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_SHOW_PLUS_SHIPPING', 'COM_SMARTSHOP_SHOW_PLUS_SHIPPING', 'Show  Plus shipping ', ' Versandkosten  anzeigen', 'Mostrar  Más gastos de envío ', 'Afficher ', 'Mostra  Pi&ugrave; Spedizione ', 'Toon  Inclusief verzendkosten ', 'Pokaż  plus koszty wysyłki ', 'Показать  плюс доставка ', 'Visa  Plus frakt '),
('SHOW PRICE FOR BASIC MEMBERS', 'index.php?option=com_jshopping&controller=config&task=catprod', 87, 'COM_SMARTSHOP_CONFIG / Product / COM_SMARTSHOP_SHOW_DEFAULT_PRICE', 'COM_SMARTSHOP_SHOW_DEFAULT_PRICE', 'Show Price for Basic members', 'Preis für Basic-Mitglieder anzeigen', 'Mostrar precio para los miembros de base', 'Prix Par Défaut', '', '', '', 'Показывать цену без скидки', 'Visa pris för basmedlemmar'),
('HIDE SHIPPING STEP USE FIRST', 'index.php?option=com_jshopping&controller=config&task=checkout', 88, 'COM_SMARTSHOP_CONFIG / Checkout / COM_SMARTSHOP_HIDE_SHIPPING_STEP', 'COM_SMARTSHOP_HIDE_SHIPPING_STEP', 'Hide shipping step (use first)', 'Versandschritt ausblenden (oberste Versandart wird verwendet)', 'Ocultar paso envío (usar primero)', 'Passer l`étape de la livraison', 'Nascondi Passo di spedizione', 'Verberg verzendmethode stap (eerst gebruiken)', '', 'Скрыть шаг доставки (использовать первый)', ''),
('HIDE PAYMENT STEP USE FIRST', 'index.php?option=com_jshopping&controller=config&task=checkout', 89, 'COM_SMARTSHOP_CONFIG / Checkout / COM_SMARTSHOP_HIDE_PAYMENT_STEP', 'COM_SMARTSHOP_HIDE_PAYMENT_STEP', 'Hide payment step (use first)', 'Zahlungsschritt ausblenden (oberste Bezahlart wird verwendet)', 'Ocultar paso pago (usar primero)', 'Passer l`étape du paiement', 'Nascondi Passo di pagamento', 'Verberg betaalmethode stap (eerst gebruiken)', '', 'Скрыть шаг оплаты (использовать первый)', ''),
('SORT OF COUNTRY IN ALPHABETICAL ORDER', 'index.php?option=com_jshopping&controller=config&task=checkout', 90, 'COM_SMARTSHOP_CONFIG / Checkout / COM_SMARTSHOP_SORTING_COUNTRY_IN_ALPHABET', 'COM_SMARTSHOP_SORTING_COUNTRY_IN_ALPHABET', 'Sort of the country in alphabetical order', 'Sortieren des Landes in alphabetischer Reihenfolge', 'Ordenar por pais en orden alfabético', 'Tri des pays par ordre alphabétique', 'Ordina le nazioni in ordine alfabetico', 'Toon de landen in alfabetische volgorde', 'Sortuj kraje alfabetycznie', 'Сортировать страны в алфавитном порядке', 'Sortera ländrena i alfabetisk ordning'),
('DEFAULT COUNTRY', 'index.php?option=com_jshopping&controller=config&task=checkout', 91, 'COM_SMARTSHOP_CONFIG / Checkout / COM_SMARTSHOP_DEFAULT_COUNTRY', 'COM_SMARTSHOP_DEFAULT_COUNTRY', 'Default country', 'Standard-Land', 'Pais predeterminado', 'Pays par défaut', 'Nazione predefinita', 'Standaard land', 'Domyślny kraj', 'Страна по умолчанию', 'Förvalt land'),
('SHOW PRODUCT WEIGHT', 'index.php?option=com_jshopping&controller=config&task=checkout', 92, 'COM_SMARTSHOP_CONFIG / Checkout / COM_SMARTSHOP_SHOW_WEIGHT_PRODUCT', 'COM_SMARTSHOP_SHOW_WEIGHT_PRODUCT', 'Show product weight', 'Produktgewicht anzeigen', 'Mostrar peso del artículo', 'Afficher le poids de l`article', 'Mostra peso del prodotto', 'Toon gewicht artikel', 'Pokaż wagę produktu', 'Показать вес товара', 'Visa produktens vikt'),
('SHOW PRODUCT CODE', 'index.php?option=com_jshopping&controller=config&task=checkout', 93, 'COM_SMARTSHOP_CONFIG / Checkout / COM_SMARTSHOP_SHOW_EAN_PRODUCT', 'COM_SMARTSHOP_SHOW_EAN_PRODUCT', 'Show product Code', 'Artikelnummer anzeigen', 'Mostrar código de artículo', 'Afficher code produit', 'Mostra Codice prodotto', 'Toon artikelcode', 'Pokaż kod produktu', 'Показать Код товара', 'Visa artikelnummer'),
('BASIC PRICE', 'index.php?option=com_jshopping&controller=config&task=checkout', 94, 'COM_SMARTSHOP_CONFIG / Checkout / Basic price ', 'COM_SMARTSHOP_BASIC_PRICE', 'Basic price', 'Grundpreis', 'Precio básico', 'Prix de base', 'Prezzo base', 'Basisprijs', 'Cena podstawowa', 'Базовая цена', 'Baspris'),
('USED DECIMAL QUANTITY', 'index.php?option=com_jshopping&controller=config&task=checkout', 95, 'COM_SMARTSHOP_CONFIG / Checkout / COM_SMARTSHOP_USE_DECIMAL_QTY', 'COM_SMARTSHOP_USE_DECIMAL_QTY', 'Used decimal quantity', 'Dezimalstellen anzeigen', 'Cantidad decimal usado', 'Autoriser l`utilisation de décimales pour les quantités', '', '', '', 'Используется десятичное количество', 'Kvantitetspris'),
('TERMS OF SERVICE', 'index.php?option=com_jshopping&controller=config&task=checkout', 96, 'COM_SMARTSHOP_CONFIG / Checkout / Terms of Service ', 'COM_SMARTSHOP_TERMS_OF_SERVICE', 'Terms of Service', 'AGB', 'Terms of Service', 'Terms of Service', 'Terms of Service', 'Terms of Service', 'Terms of Service', 'Terms of Service', 'Terms of Service'),
('SHOW RETURN POLICY IN EMAIL ORDER URL', 'index.php?option=com_jshopping&controller=config&task=checkout', 97, 'COM_SMARTSHOP_CONFIG / Checkout / COM_SMARTSHOP_RETURN_POLICY', 'COM_SMARTSHOP_RETURN_POLICY', 'Return policy', 'Rückgabebedingungen', 'Política de devoluciones', 'Politique de retour', 'Informazioni sulla restituzione', 'Retourbeleid', 'Zasady zwrotów', 'Условия возврата', 'Retur policy'),
('REGISTER', 'index.php?option=com_jshopping&controller=config&task=fieldregister', 98, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_REGISTER_FIELDS / COM_SMARTSHOP_SEOPAGE_REGISTER', 'COM_SMARTSHOP_SEOPAGE_REGISTER', 'Register', 'Registrierung', 'Registrar', 'Enregistrement', 'Registrazione', 'Registreren', '', 'Регистрация', 'Registrera'),
('ADDRESS', 'index.php?option=com_jshopping&controller=config&task=fieldregister', 99, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_REGISTER_FIELDS / COM_SMARTSHOP_SEOPAGE_CHECKOUT-ADDRESS', 'COM_SMARTSHOP_SEOPAGE_CHECKOUT-ADDRESS', 'Address', 'Rechnungsadresse', 'Dirección', 'Adresse', 'Indirizzo', 'Adres', '', 'Адрес', 'Adress'),
('CHANGE MY DETAILS', 'index.php?option=com_jshopping&controller=config&task=fieldregister', 100, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_REGISTER_FIELDS / COM_SMARTSHOP_EDIT_ACCOUNT', 'COM_SMARTSHOP_EDIT_ACCOUNT', 'Change my details', 'Datenänderung', 'Cambiar mis detalles', 'Changer mes détails', 'Cambia i miei dati', 'Wijzig mijn gegevens', 'Zmień moje dane', 'Изменить мои данные', 'Ändra mina detaljer'),
('MAIN CURRENCY', 'index.php?option=com_jshopping&controller=config&task=currency', 101, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_PANEL_CURRENCIES / COM_SMARTSHOP_MAIN_CURRENCY', 'COM_SMARTSHOP_MAIN_CURRENCY', 'Main currency', 'Hauptwährung', 'Moneda principal', 'Monnaie principale', 'Valuta predefinita', 'Primaire valuta', 'Główna waluta', 'Главная валюта', 'Huvudvaluta'),
('DECIMALS', 'index.php?option=com_jshopping&controller=config&task=currency', 102, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_PANEL_CURRENCIES / COM_SMARTSHOP_DECIMAL_COUNT', 'COM_SMARTSHOP_DECIMAL_COUNT', 'Decimals :', 'Dezimalzahlen :', 'Decimales: ', 'Décimales :', 'Decimali :', 'Decimalen :', 'Dziesiętne :', 'Десятичные знаки :', 'Decimaler :'),
('DECIMAL SYMBOL', 'index.php?option=com_jshopping&controller=config&task=currency', 103, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_PANEL_CURRENCIES / COM_SMARTSHOP_DECIMAL_SYMBOL', 'COM_SMARTSHOP_DECIMAL_SYMBOL', 'Decimal symbol :', 'Dezimalsymbol :', 'Símbolo decimal: ', 'Symbole décimal :', 'Simbolo decimali :', 'Decimaal symbool :', 'Symbol dziesiętny :', 'Разделитель целой и дробной части:', 'Decimal symbol :'),
('THOUSANDS SEPARATOR', 'index.php?option=com_jshopping&controller=config&task=currency', 104, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_PANEL_CURRENCIES / COM_SMARTSHOP_THOUSAND_SEPARATOR', 'COM_SMARTSHOP_THOUSAND_SEPARATOR', 'Thousands separator :', 'Tausend Trenner:', 'Separador de miles: ', 'Séparateur des milliers :', 'Separatore migliaia :', 'Duizendtal separator :', 'Separator tysięczny :', 'Разделитель тысяч:', 'Tusentals separator :'),
('FORMAT', 'index.php?option=com_jshopping&controller=config&task=currency', 105, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_PANEL_CURRENCIES / COM_SMARTSHOP_IE_UNICSV_FIELDFORMAT', 'COM_SMARTSHOP_IE_UNICSV_FIELDFORMAT', 'Format', 'Format', '', '', '', '', '', '', ''),
('CATEGORY THUMBNAIL WIDTH', 'index.php?option=com_jshopping&controller=config&task=image', 106, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_CATEGORY_WIDTH', 'COM_SMARTSHOP_IMAGE_CATEGORY_WIDTH', 'Category thumbnail width', 'Kategorie Vorschaubildbreite', 'Ancho miniatura de la categoría', 'Largeur vignette catégorie', 'Larghezza per l&acute;anteprima della categoria', 'Categorie thumbnail breedte', 'Szerokość miniatury kategorii', 'Ширина превью категории', 'Kategori thumbnail bredd'),
('CATEGORY THUMBNAIL HEIGHT', 'index.php?option=com_jshopping&controller=config&task=image', 107, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_CATEGORY_HEIGHT', 'COM_SMARTSHOP_IMAGE_CATEGORY_HEIGHT', 'Category thumbnail height', 'Kategorie Vorschaubildhöhe', 'Alto miniatura de la categoría', 'Hauteur vignette catégorie', 'Altezza per l&acute;anteprima della categoria', 'Categorie thumbnail hoogte', 'Wysokość miniatury kategorii', 'Высота превью категории', 'Kategori tumnagel höjd'),
('PRODUCT THUMBNAIL WIDTH', 'index.php?option=com_jshopping&controller=config&task=image', 108, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_PRODUCT_THUMB_WIDTH', 'COM_SMARTSHOP_IMAGE_PRODUCT_THUMB_WIDTH', 'Product thumbnail width', 'Produkt Vorschaubildbreite', 'Ancho miniatura del artículo', 'Largeur vignette article', 'Larghezza per l&acute;anteprima del prodotto', 'Artikel thumbnail breedte', 'Szerokość miniatury produktu', 'Ширина превью товара', 'Produkt thumbnail bredd'),
('PRODUCT THUMBNAIL HEIGHT', 'index.php?option=com_jshopping&controller=config&task=image', 109, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_PRODUCT_THUMB_HEIGHT', 'COM_SMARTSHOP_IMAGE_PRODUCT_THUMB_HEIGHT', 'Product thumbnail height', 'Produkt Vorschaubildhöhe', 'Alto miniatura del artículo', 'Hauteur vignette article', 'Altezza per l&acute;anteprima del prodotto', 'Artikel thumbnail hoogte', 'Wysokosć miniatury produktu', 'Высота превью товара', 'Produkt thumbnail höjd'),
('PRODUCT IMAGE WIDTH', 'index.php?option=com_jshopping&controller=config&task=image', 110, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_PRODUCT_FULL_WIDTH', 'COM_SMARTSHOP_IMAGE_PRODUCT_FULL_WIDTH', 'Product image width', 'Produkt Bildbreite', 'Ancho imagen del artículo', 'Largeur image article', 'Larghezza per l&acute;immagine del prodotto', 'Artikel afbeelding breedte', 'Szerokość zdjęcia produktu', 'Ширина изображения товара', 'Produktbild bredd'),
('PRODUCT IMAGE HEIGHT', 'index.php?option=com_jshopping&controller=config&task=image', 111, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_PRODUCT_FULL_HEIGHT', 'COM_SMARTSHOP_IMAGE_PRODUCT_FULL_HEIGHT', 'Product image height', 'Produkt Bildhöhe', 'Alto imagen del artículo', 'Hauteur image article', 'Altezza per l&acute;immagine del prodotto', 'Artikel afbeelding hoogte', 'Wysokość zdjęcia produktu', 'Высота изображения товара', 'Produktbild höjd'),
('WIDTH ORIGINAL IMAGE', 'index.php?option=com_jshopping&controller=config&task=image', 112, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_PRODUCT_ORIGINAL_WIDTH', 'COM_SMARTSHOP_IMAGE_PRODUCT_ORIGINAL_WIDTH', 'Width original image', 'Breite Originalbild', 'Ancho original de imagen', '', '', '', '', 'Ширина оригинального изображения', ''),
('HEIGHT ORIGINAL IMAGE', 'index.php?option=com_jshopping&controller=config&task=image', 113, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_PRODUCT_ORIGINAL_HEIGHT', 'COM_SMARTSHOP_IMAGE_PRODUCT_ORIGINAL_HEIGHT', 'Height original image', 'Höhe Originalbild', 'Alto de original imagen', '', '', '', '', 'Высота оригинального изображения', ''),
('PRODUCT VIDEO WIDTH', 'index.php?option=com_jshopping&controller=config&task=image', 114, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_VIDEO_PRODUCT_WIDTH', 'COM_SMARTSHOP_VIDEO_PRODUCT_WIDTH', 'Product video width', 'Breite für Video-Größe', 'Ancho de video del artículo', 'Largeur video', 'Larghezza video del prodotto', 'Artikel video breedte', 'Szerokość filmu produktu', 'Ширина видео товара', 'Produktvideo bredd'),
('PRODUCT VIDEO HEIGHT', 'index.php?option=com_jshopping&controller=config&task=image', 115, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_VIDEO_PRODUCT_HEIGHT', 'COM_SMARTSHOP_VIDEO_PRODUCT_HEIGHT', 'Product video height', 'Höhe für Video-Größe', 'Alto de video del artículo', 'Hauteur vidéo', 'Altezza video del prodotto', 'Artikel video hoogte', 'Wysokość filmu produktu', 'Высота видео товара', 'Produktvideo höjd'),
('RESIZE TYPE', 'index.php?option=com_jshopping&controller=config&task=image', 116, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_IMAGE_RESIZE_TYPE', 'COM_SMARTSHOP_IMAGE_RESIZE_TYPE', 'Resize type', 'Resize Typ', 'Tipo de Redimensionamiento', 'Type Redimensionnement', 'Tipo di ridimensionamento', 'Formaat aanpassen', '', 'Изменение размера', 'Ändra typstorlek'),
('IMAGE QUALITY', 'index.php?option=com_jshopping&controller=config&task=image', 117, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_OC_IMAGE_QUALITY', 'COM_SMARTSHOP_OC_IMAGE_QUALITY', 'Image quality', 'Bildqualität', 'Calidad de imagen', '', '', '', '', 'Качество изображения', 'Bildkvalitet'),
('IMAGE FILL COLOR', 'index.php?option=com_jshopping&controller=config&task=image', 118, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_OC_IMAGE_FILL_COLOR', 'COM_SMARTSHOP_OC_IMAGE_FILL_COLOR', 'Image fill color', 'Bild Füllfarbe', 'Color de relleno de imagen', '', '', '', '', 'Цвет заливки изображения', 'Ange bildens färg'),
('PRODUCT FILE UPLOAD COUNT', 'index.php?option=com_jshopping&controller=config&task=image', 119, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_OC_PRODUCT_FILE_UPLOAD_COUNT', 'COM_SMARTSHOP_OC_PRODUCT_FILE_UPLOAD_COUNT', 'Product file upload count', 'Anzahl der Uploads von Produktdateien', 'Contar subir archivos del artículo', '', '', '', '', 'Количество файлов продукта', 'Antal uppladdade produktfiler'),
('PRODUCT IMAGE UPLOAD COUNT', 'index.php?option=com_jshopping&controller=config&task=image', 120, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_OC_PRODUCT_IMAGE_UPLOAD_COUNT', 'COM_SMARTSHOP_OC_PRODUCT_IMAGE_UPLOAD_COUNT', 'Product image upload count', 'Anzahl der Uploads von Produktbildern', 'Contar subir imágenes del artículo', '', '', '', '', 'Количество изображений продукта', 'Antal uppladdade produktbilder'),
('PRODUCT VIDEO UPLOAD COUNT', 'index.php?option=com_jshopping&controller=config&task=image', 121, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_OC_PRODUCT_VIDEO_UPLOAD_COUNT', 'COM_SMARTSHOP_OC_PRODUCT_VIDEO_UPLOAD_COUNT', 'Product video upload count', 'Anzahl der Uploads von Produktvideos', 'Contar subir video del artículo', '', '', '', '', 'Количество видео продукта', 'Antal uppladdade produktvideor'),
('MAX TOTAL OF DOWNLOAD SALE FILE', 'index.php?option=com_jshopping&controller=config&task=image', 122, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_OC_MAX_NUMBER_DOWNLOAD_SALE_FILE', 'COM_SMARTSHOP_OC_MAX_NUMBER_DOWNLOAD_SALE_FILE', 'Max total of download sale file', 'Maximale Summe der Download-Verkaufsdatei', 'Total máx de descargas archivo en venta', '', '', '', '', 'Максимальное количество скачиваний файла для продажи', 'Max total nerladdade försäljningsfiler'),
('MAX TOTAL OF DAY SALE FILE', 'index.php?option=com_jshopping&controller=config&task=image', 123, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / COM_SMARTSHOP_OC_MAX_DAY_DOWNLOAD_SALE_FILE', 'COM_SMARTSHOP_OC_MAX_DAY_DOWNLOAD_SALE_FILE', 'Max total of day sale file', 'Maximale Summe der Tagesverkaufsdatei', 'Max total of day sale file', '', '', '', '', 'Максимальное количество дней файла для продажи', 'Max total of dagliga försäljningsfiler'),
('PRIVACY POLICY', 'index.php?option=com_jshopping&controller=config&task=content', 124, 'COM_SMARTSHOP_CONFIG / Content / Privacy Policy', 'COM_SMARTSHOP_PRIVACY_POLICY', 'Privacy Policy', 'Datenschutzerklärung', 'Privacy Policy', 'Privacy Policy', 'Privacy Policy', 'Privacy Policy', 'Privacy Policy', 'Privacy Policy', 'Privacy Policy'),
('TERMS OF SERVICE', 'index.php?option=com_jshopping&controller=config&task=content', 125, 'COM_SMARTSHOP_CONFIG / Content / Terms of Service ', 'COM_SMARTSHOP_TERMS_OF_SERVICE', 'Terms of Service', 'AGB', 'Terms of Service', 'Terms of Service', 'Terms of Service', 'Terms of Service', 'Terms of Service', 'Terms of Service', 'Terms of Service'),
('RETURN POLICY', 'index.php?option=com_jshopping&controller=config&task=content', 126, 'COM_SMARTSHOP_CONFIG / Content / Return Policy', 'COM_SMARTSHOP_RETURN_POLICY', 'Return policy', 'Rückgabebedingungen', 'Política de devoluciones', 'Politique de retour', 'Informazioni sulla restituzione', 'Retourbeleid', 'Zasady zwrotów', 'Условия возврата', 'Retur policy'),
('ORDER SUCCESS PAGE', 'index.php?option=com_jshopping&controller=config&task=content', 127, 'COM_SMARTSHOP_CONFIG / Content / Order Success Page ', 'COM_SMARTSHOP_ORDER_SUCCESS_PAGE', 'Order Success Page', 'Bestellbestätigungsseite', 'Order Success Page', 'Order Success Page', 'Order Success Page', 'Order Success Page', 'Order Success Page', 'Order Success Page', 'Order Success Page'),
('SHIPPING INFORMATION', 'index.php?option=com_jshopping&controller=config&task=content', 128, 'COM_SMARTSHOP_CONFIG / Content / Shipping information', 'COM_SMARTSHOP_SHIPPING_INFORMATION', 'Shipping information', 'Informationen zur Lieferung', 'Información de envío', 'Informations sur la livraison', 'Informazioni spedizione', 'Verzendmethode', 'Informacje o wysyłce', 'Информация о доставке', 'Leveransinformation'),
('SHOP NAME', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 129, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_STORE_NAME', 'COM_SMARTSHOP_STORE_NAME', 'Shop name', 'Shopname', 'Nombre de la tienda', 'Nom de la boutique', 'Nome negozio', 'Winkelnaam', 'Nazwa sklepu', 'Название магазина', 'Butikens namn'),
('COMPANY NAME', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 130, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_STORE_COMPANY', 'COM_SMARTSHOP_STORE_COMPANY', 'Company name', 'Firmenname', 'Nombre de la empresa', 'Nom de la société', 'Nome societ&agrave;', 'Bedrijfsnaam', 'Nazwa firmy', 'Название компании', 'Företagets namn'),
('URL', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 131, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / URL', 'COM_SMARTSHOP_IE_UNICSV_FIELDFORMATURL', 'URL', 'URL', '', '', '', '', '', '', '');
INSERT INTO `#__jshopping_search` (`keyword`, `links`, `id`, `title`, `lang_variable`, `keyword_en-GB`, `keyword_de-DE`, `keyword_es-ES`, `keyword_fr-FR`, `keyword_it-IT`, `keyword_nl-NL`, `keyword_pl-PL`, `keyword_ru-RU`, `keyword_sv-SE`) VALUES
('LOGO', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 132, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_LOGO', 'COM_SMARTSHOP_LOGO', 'Manufacturer logo', 'Hersteller Logo', 'Logo del fabricante', 'Logo fabricant', 'Logo produttore', 'Fabrikant logo', 'Logo producenta', 'Лого производителя', 'Leverantörs logo'),
('ADDRESS', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 133, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_SEOPAGE_CHECKOUT-ADDRESS', 'COM_SMARTSHOP_SEOPAGE_CHECKOUT-ADDRESS', 'Address', 'Rechnungsadresse', 'Dirección', 'Adresse', 'Indirizzo', 'Adres', '', 'Адрес', 'Adress'),
('CITY', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 134, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_FIELD_CITY', 'COM_SMARTSHOP_FIELD_CITY', 'City', 'Stadt', 'Ciudad', 'Ville', 'Citt&agrave;', 'Stad', 'Miasto', 'Город', 'Ort'),
('POSTAL CODE', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 135, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_FIELD_ZIP', 'COM_SMARTSHOP_FIELD_ZIP', 'Postal Code', 'Postleitzahl', 'C.P.', 'CP', 'CAP', 'Postcode', 'Kod pocztowy', 'Почтовый индекс', 'Postnr'),
('STATE PROVINCE REGION', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 136, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_STORE_STATE', 'COM_SMARTSHOP_STORE_STATE', 'State/Province/Region', 'Land/Provinz/Gebiet', 'Estado Provincia Región', 'Etat / Province / Région', 'Provincia', 'Provincie', 'Województwo/Stan/Prowincja/Region', 'Регион', 'Län'),
('COUNTRY', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 137, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_FIELD_COUNTRY', 'COM_SMARTSHOP_FIELD_COUNTRY', 'Country', 'Land', 'País', 'Pays', 'Nazione', 'Land', 'Kraj', 'Страна', 'Land'),
('CONTACT INFO FIRST NAME LAST NAME MIDDLE NAME PHONE FAX EMAIL E-MAIL', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 138, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_CONTACT_INFO', 'COM_SMARTSHOP_CONTACT_INFO COM_SMARTSHOP_CONTACT_FIRSTNAME COM_SMARTSHOP_CONTACT_LASTNAME COM_SMARTSHOP_CONTACT_MIDDLENAME COM_SMARTSHOP_CONTACT_PHONE COM_SMARTSHOP_CONTACT_FAX COM_SMARTSHOP_CONTACT_EMAIL', 'Contact info First name Last name Middle name Phone Fax Email', 'Kontaktdaten Vorname Nachname Zusatz Telefon Telefax E-Mail', 'Info de contacto Nombre Apellido(s) Segundo nombre Teléfono Fax Email', 'Info de contact Prénom Nom Deuxième prénom Téléphone Fax Email', 'Informazioni contatto Nome Cognome Secondo nome Telefono Fax Email', 'Contactinformatie Voornaam Achternaam Tussenvoegsel Telefoon Fax E-mail', 'Osoba kontaktowa Imię Nazwisko Drugie imię Telefon Fax Email', 'Контактная информация Имя Фамилия Отчество Телефон Факс Email', 'Kontakt info Förnamn Efternamn Mellannamn Tel Fax E-post'),
('BANK NAME BANK IDENTIFICATION CODE ACCOUNT NUMBER PAYEE IBAN BIC SWIFT CODE', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 139, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_BANK', 'COM_SMARTSHOP_BANK COM_SMARTSHOP_BENEF_BANK COM_SMARTSHOP_BENEF_BANK_INFO COM_SMARTSHOP_BENEF_BIC COM_SMARTSHOP_BENEF_CONTO COM_SMARTSHOP_BENEF_IBAN COM_SMARTSHOP_BIC_BIC COM_SMARTSHOP_BENEF_SWIFT', 'Bank Beneficiary bank Beneficiary bank info Bank identification code Account number IBAN BIC SWIFT Code', 'Bankverbindung Beneficiary Bank Beneficiary Bank info Bankleitzahl Kontonummer IBAN BIC SWIFT Code', 'Banco Banco beneficiario Info de banco beneficiario Código de identificación del banco Número de cuenta IBAN BIC SWIFT Code', 'Banque Banque du bénéficiaire Info banque du bénéficiaire Code d`identification de la banque Numéro de compte IBAN  Code SWIFT', 'Banca Banca d&acute;appoggio Informazioni banca d&acute;appoggio Codice identificativo banca Numero conto corrente IBAN  Codice SWIFT', 'Bank Banknaam begunstigde Info bank begunstigde Bank identification code (BIC) Rekeningnummer IBAN  SWIFT Code', 'Bank Bank beneficjanta Bank beneficjanta info Kod banku beneficjanta Nr rachunku IBAN  Kod SWIFT', 'Банк Beneficiary Bank Beneficiary Bank info Bank identifier code Account number IBAN BIC SWIFT Code', 'Bank Beneficiary bank Beneficiary bank info Bank identification code Kontonummer IBAN  SWIFT Code'),
('INTERMEDIARY BANK NAME SWIFT CODE IDENTIFICATION NR TAX NR ADDITIONAL INFORMATION', 'index.php?option=com_jshopping&controller=config&task=storeinfo', 140, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_STORE_INFO / COM_SMARTSHOP_INTERM_BANK', 'COM_SMARTSHOP_INTERM_BANK COM_SMARTSHOP_INTERM_NAME COM_SMARTSHOP_BENEF_BANK_NAME COM_SMARTSHOP_INTERM_SWIFT', 'Intermediary bank Name Name SWIFT Code', 'Intermediary Bank Name Bank SWIFT Code', 'Intermediary bank Nombre Nombre Código SWIFT', 'Banque intermédiaire Nom Nom Code SWIFT', 'Banca intermediaria Nome Nome banca d&acute;appoggio Codice SWIFT', 'Tussenpersoon bij de bank Naam Banknaam SWIFT Code', 'Bank pośredniczący Nazwa Nazwa Kod SWIFT', 'Intermediary Bank Имя Имя SWIFT Code', 'Intermediary bank Namn Namn SWIFT kod'),
('CART DECIMAL QTY PRECISION', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 141, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_OC_CART_DECIMAL_QTY_PRECISION', 'COM_SMARTSHOP_OC_CART_DECIMAL_QTY_PRECISION', 'Cart decimal qty precision', 'Dezimalstellen im Warenkorb', 'Precisión decimal  en cantidad carrito', '', '', '', '', 'Точность количества', 'Exakt antal decimaler i varukorg'),
('LOAD LIGHTBOX', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 142, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_OC_LOAD_JQUERY_LIGHTBOX', 'COM_SMARTSHOP_OC_LOAD_JQUERY_LIGHTBOX', 'Load Lightbox', 'Lightbox laden', 'Cargar Lightbox', '', '', '', '', 'Использовать Lightbox', 'Ladda Lightbox'),
('LOAD JAVASCRIPT', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 143, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_OC_LOAD_JAVASCRIPT', 'COM_SMARTSHOP_OC_LOAD_JAVASCRIPT', 'Load javascript', 'Javascript laden', 'Cargar javascript', '', '', '', '', 'Загружать javascript', 'Ladda javascript'),
('LOAD CSS', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 144, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_OC_LOAD_CSS', 'COM_SMARTSHOP_OC_LOAD_CSS', 'Load css', 'CSS laden', 'Cargar css', '', '', '', '', 'Использовать CSS', 'Ladda css'),
('CALCULATE BASIC PRICE FROM PRODUCT PRICE LIST PRODUCT', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 145, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_OC_LIST_PRODUCTS_CALC_BASIC_PRICE_FROM_PRODUCT_PRICE', 'COM_SMARTSHOP_OC_LIST_PRODUCTS_CALC_BASIC_PRICE_FROM_PRODUCT_PRICE', 'Calculate basic price from product price (List products)', 'Grundpreis von Produktpreis berechnen (Produkte auflisten)', 'Calcular el precio básico a partir del precio del artículo (Lista artículos)', '', '', '', '', 'Расчет базовой цены от цены продукта (список продуктов)', ''),
('CALCULATE BASIC PRICE FROM PRODUCT PRICE', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 146, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_OC_CALC_BASIC_PRICE_FROM_PRODUCT_PRICE', 'COM_SMARTSHOP_OC_CALC_BASIC_PRICE_FROM_PRODUCT_PRICE', 'Calculate basic price from product price', 'Grundpreis von Produktpreis berechnen', 'Calcular el precio básico a partir del precio del artículo', '', '', '', '', 'Расчет базовой цены от цены продукта', ''),
('SAVE INFO TO LOGFILE', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 147, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_SAVE_INFO_TO_LOG', 'COM_SMARTSHOP_SAVE_INFO_TO_LOG', 'Save info to Logfile', 'Logdatei speichern', 'Guardar información en archivo Log', 'Sauvegarder l`info dans fichier log', 'Salva informazioni nel file di Log', 'Bewaar info in een Logbestand', 'Zapisz info do pliku log', 'Сохранить информацию в лог-файл', 'Spara info till loggfil'),
('SAVE INFO PAYMENT TO LOGFILE', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 148, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_SAVE_PAYMENTINFO_TO_LOG', 'COM_SMARTSHOP_SAVE_PAYMENTINFO_TO_LOG', 'Save info Payment to Logfile', 'Logdatei für Zahlungsvorgang speichern', 'Guardar info del pago en archivo Log', 'Sauvegarder les infos paiement dans le fichier log', 'Salva informazioni sui pagamenti nel file di Log', 'Bewaar betaalgegevens in een Logbestand', 'Zapisz info o metodzie płatności do pliku log', 'Сохранить информацию об Оплате в лог-файл', 'Spara betalningsinfo till loggfil'),
('SECURITY KEY', 'index.php?option=com_jshopping&controller=config&task=otherconfig', 149, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_OC / COM_SMARTSHOP_SECURITYKEY', 'COM_SMARTSHOP_SECURITYKEY', 'Security key', 'Sicherheitsschlüssel', 'Clave de seguridad', 'Clé de sécurité', 'Security key', 'Veiligheidscode', 'Klucz zabezpieczeń', 'Ключ безопасности', 'Säkerhetsnyckel'),
('DFEFAULT ORDER STATUS', 'index.php?option=com_jshopping&controller=config&task=orders', 150, 'COM_SMARTSHOP_CONFIG / Orders / COM_SMARTSHOP_DEFAULT_ORDER_STATUS', 'COM_SMARTSHOP_DEFAULT_ORDER_STATUS', 'Default order status', 'Standard Bestellungsstatus', 'Estado del pedido por defecto', 'Statut de la commande par défaut', 'Stato ordine predefinito', 'Standaard bestelstatus', 'Domyślny stan zamówienia', 'Статус заказа по умолчанию', 'Aktuella språkets status'),
('NEXT ORDER WILL START NUMBERING FROM', 'index.php?option=com_jshopping&controller=config&task=orders', 151, 'COM_SMARTSHOP_CONFIG / Orders / COM_SMARTSHOP_NEXT_ORDER_NUMBER', 'COM_SMARTSHOP_NEXT_ORDER_NUMBER', 'Next order will start numbering from', 'Nächste Bestellnummer beginnt von', 'Siguiente pedido empezará con la numeración desde', 'La prochaine commande commencera au n°', 'Il prossimo ordine inzier&agrave; dal numero', 'De eerstvolgende bestelling start met nummer', 'Następne zamówienie otrzyma nr', 'Нумерация следующего заказа будет начата с', 'Nästa order startar numrering från'),
('SUFFIX FOR NUMBER', 'index.php?option=com_jshopping&controller=config&task=orders', 152, 'COM_SMARTSHOP_CONFIG / Orders / COM_SMARTSHOP_SUFFIX', 'COM_SMARTSHOP_SUFFIX', 'Suffix for number', 'Suffix für Nummer', '', '', '', '', '', '', ''),
('CUSTOMER MAY CANCEL AN ORDER', 'index.php?option=com_jshopping&controller=config&task=orders', 153, 'COM_SMARTSHOP_CONFIG / Orders / COM_SMARTSHOP_CLIENT_ALLOW_CANCEL_ORDER', 'COM_SMARTSHOP_CLIENT_ALLOW_CANCEL_ORDER', 'Customer may cancel an order', 'Der Kunde kann eine Bestellung stornieren', 'El cliente puede cancelar un pedido', 'Le client peut annuler une commande', 'Il cliente pu&ograve; cancellare l ordine', 'Klant mag order annuleren', 'Klient może anulować zamówienie', 'Клиент может отменить заказ', 'Kunden kan annullera en beställning'),
('MAXIMUM ORDER PRICE', 'index.php?option=com_jshopping&controller=config&task=orders', 156, 'COM_SMARTSHOP_CONFIG / Orders / COM_SMARTSHOP_ERROR_MAX_SUM_ORDER', 'COM_SMARTSHOP_ERROR_MAX_SUM_ORDER', 'Maximum order price', 'Maximaler Bestellpreis', 'Precio máximo del pedido', 'Montant maximum de la commande', 'Prezzo massimo per effettuare l ordine', 'Maximum orderbedrag', 'Maksymalna kwota zamówienia', 'Максимальния сумма заказа', 'Maximum order pris'),
('MINIMUM ORDER PRICE', 'index.php?option=com_jshopping&controller=config&task=orders', 157, 'COM_SMARTSHOP_CONFIG / Orders / COM_SMARTSHOP_ERROR_MIN_SUM_ORDER', 'COM_SMARTSHOP_ERROR_MIN_SUM_ORDER', 'Minimum order price', 'Mindestbestellpreis', 'Precio mínimo del pedido', 'Montant minimum de la commande', 'Prezzo minimi per effettuare l ordine', 'Minimum orderbedrag', 'Minimalna kwota zamówienia', 'Минимальная сумма заказа', 'Minimum order pris'),
('DELIVERY OF ORDER DEPENDS ON DELIVERY OF PRODUCT', 'index.php?option=com_jshopping&controller=config&task=orders', 158, 'COM_SMARTSHOP_CONFIG / Orders / COM_SMARTSHOP_DELIVERY_ORDER_DEPENDS_DELIVERY_PRODUCT', 'COM_SMARTSHOP_DELIVERY_ORDER_DEPENDS_DELIVERY_PRODUCT', 'Delivery of order depends on delivery of product', 'Die Lieferung der Bestellung hängt von der Lieferung des Produkts ab', 'Entrega de la orden depende de la entrega del artículo', '', '', '', '', 'Доставка заказа зависит от доставки продукции', 'Orderns leveranstid berör på produktens leveranstid'),
('FREE SHIPPING FOR ORDER (PRICE) OVER', 'index.php?option=com_jshopping&controller=config&task=orders', 159, 'COM_SMARTSHOP_CONFIG / Orders / COM_SMARTSHOP_NULL_SIHPPING', 'COM_SMARTSHOP_NULL_SIHPPING', 'Free shipping for order (price) over', 'Kostenlose Lieferung ab', 'Envío gratuito para pedidos (precio) por encima de', 'Frais d`envoi offerts pour une commande supérieure à', 'Spedizione gratuita su ordine superiore', 'Gratis verzending bij een totaal bestelbedrag boven', 'Darmowa wysyłka przy zamówieniu o wartości ponad', 'Бесплатная доставка для заказов больше', 'Fraktfritt för order (pris) över'),
('PDF INVOICE TO THE CUSTOMER', 'index.php?option=com_jshopping&controller=config&task=pdf', 160, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_ORDER_SEND_PDF_CLIENT', 'COM_SMARTSHOP_ORDER_SEND_PDF_CLIENT', 'PDF invoice to the customer', 'PDF-Rechnung an den Kunden senden', 'Factura en PDF para el cliente', 'Envoi de la facture PDF au client', 'Ricevuta PDF al cliente', 'PDF factuur naar de klant', 'Faktura w PDF dla klienta', 'Присылать PDF-счет для заказчика', 'PDF faktura till kunden'),
('PDF INVOICE TO THE ADMIN', 'index.php?option=com_jshopping&controller=config&task=pdf', 161, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_ORDER_SEND_PDF_ADMIN', 'COM_SMARTSHOP_ORDER_SEND_PDF_ADMIN', 'PDF invoice to the admin', 'PDF-Rechnung an den Shopbetreiber senden', 'Factura en PDF para el administrador', 'Envoi de la facture PDF à l`administrateur', 'Ricevuta PDF all amministratore', 'PDF factuur naar de admin', 'Faktura w PDF dla admina', 'Присылать PDF-счет для админа', 'PDF faktura till admin'),
('SEND INVOICE MANUALLY', 'index.php?option=com_jshopping&controller=config&task=pdf', 162, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_SENT_INVOICE_MANUALLY', 'COM_SMARTSHOP_SENT_INVOICE_MANUALLY', 'Send invoice manually', 'Rechnung manuell schicken', 'Enviar factura manualmente', '', '', '', '', 'Отправлять счета-фактуру вручную', ''),
('INVOICE DATE', 'index.php?option=com_jshopping&controller=config&task=pdf', 163, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_INVOICE_DATE', 'COM_SMARTSHOP_INVOICE_DATE', 'Invoice Date', 'Rechnungs-Datum', 'Fecha de la factura', '', '', '', '', 'Дата счета-фактуры', 'Faktura datum'),
('WEIGHT IN INVOICE', 'index.php?option=com_jshopping&controller=config&task=pdf', 164, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_SHOW_WEIGHT_IN_INVOICE', 'COM_SMARTSHOP_SHOW_WEIGHT_IN_INVOICE', 'Weight in invoice', 'Gewicht in Rechnung', 'Peso en factura', '', '', '', '', 'Вес в счет-фактуре', 'Vikt på faktura'),
('SHIPPING IN INVOICE', 'index.php?option=com_jshopping&controller=config&task=pdf', 165, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_SHOW_SHIPPING_IN_INVOICE', 'COM_SMARTSHOP_SHOW_SHIPPING_IN_INVOICE', 'Shipping in invoice', 'Versand in Rechnung', 'Envío de factura', '', '', '', '', 'Доставка в счет-фактуре', 'Transportsätt på faktura'),
('PAYMENT IN INVOICE', 'index.php?option=com_jshopping&controller=config&task=pdf', 166, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_SHOW_PAYMENT_IN_INVOICE', 'COM_SMARTSHOP_SHOW_PAYMENT_IN_INVOICE', 'Payment in invoice', 'Zahlung in Rechnung', 'Pago de factura', '', '', '', '', 'Оплата в счет-фактуре', 'Betaningssätt på faktura'),
('CUSTOMER NUMBER IN INVOICE', 'index.php?option=com_jshopping&controller=config&task=pdf', 167, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_SHOW_USER_NUMBER_IN_INVOICE', 'COM_SMARTSHOP_SHOW_USER_NUMBER_IN_INVOICE', 'Сustomer number in invoice', 'Kundennummer in Rechnung', 'Número de cliente en factura', '', '', '', '', 'Номер клиента в счете', ''),
('SHOW \'RETRN POLICY\' IN PDF', 'index.php?option=com_jshopping&controller=config&task=pdf', 168, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / COM_SMARTSHOP_SHOW_RETURN_POLICY_IN_EMAIL_ORDER', 'COM_SMARTSHOP_SHOW_RETURN_POLICY_IN_EMAIL_ORDER', 'Show  return policy  in email order', '', 'Mostrar  política de devoluciones  en email del pedido', 'Afficher  Politique de retour  dans la commande email', 'Mostra  Informazioni sulla restituzione  nell email dell ordine', 'Toon  retourvoorwaarden  in e-mail bestelling', 'Pokaż ', 'Показать условие возврата в емейле заказа', 'Vira  returpolicy  i ordermail'),
('HEADER WIDTH HEIGHT FOOTER PREVIEW PDF', 'index.php?option=com_jshopping&controller=config&task=pdf', 169, 'COM_SMARTSHOP_CONFIG / COM_SMARTSHOP_CONFIGURATION_PDF / PDF Config', 'COM_SMARTSHOP_PDF_HEADER COM_SMARTSHOP_IMAGE_WIDTH COM_SMARTSHOP_IMAGE_HEIGHT COM_SMARTSHOP_PDF_FOOTER COM_SMARTSHOP_PDF_PREVIEW', 'Header Width Height Footer Preview pdf', 'Kopfzeile Bildbreite Bildhöhe Fußzeile Preview PDF', 'Cabecera Ancho Alto Pie Vista previa pdf', 'Entête de page Largeur Hauteur Pied de page Prévisualiser pdf', 'Header, immagine alla testa del PDF Larghezza immagine Altezza immagine Footer, immagine a piede del PDF Anteprima pdf', 'Header Breedte Hoogte Footer Toon pdf', 'Nagłówek Szerokość Wysokośc Stopka Podgląd pdf', 'Верхний колонтитул Ширина изображения Высота изображения Нижний колонтитул Просмотреть pdf', 'Huvud Bredd Höjd Fot Granska pdf'),
('CASH ON DELIVERY', 'index.php?option=com_jshopping&controller=payments&task=edit&payment_id=1', 170, 'COM_SMARTSHOP_OTHER_ELEMENTS / Payments / Cash on Delivery', '', '', '', '', '', '', '', '', '', ''),
('ADVANCE PAYMENT', 'index.php?option=com_jshopping&controller=payments&task=edit&payment_id=2', 171, 'COM_SMARTSHOP_OTHER_ELEMENTS / Payments / Advance payment', '', '', '', '', '', '', '', '', '', ''),
('DELIVERY METHOD PRICES', 'index.php?option=com_jshopping&controller=shippingsprices&shipping_id_back=1', 172, 'COM_SMARTSHOP_OTHER_ELEMENTS / COM_SMARTSHOP_PANEL_SHIPPINGS / COM_SMARTSHOP_SHIPPING_PRICES\r\n', 'COM_SMARTSHOP_SHIPPING_PRICES', 'Delivery methods prices', 'Lieferungsart Preise', 'Precios de los métodos de envío', 'Prix des moyens de livraison', 'Prezzo metodo di spedizione', 'Prijzen verzendmethodes', 'Ceny sposobów wysyłki', 'Цены способов доставки', 'Transportsätt priser'),
('STANDARD', 'index.php?option=com_jshopping&controller=shippings&task=edit&shipping_id=1', 173, 'COM_SMARTSHOP_OTHER_ELEMENTS / COM_SMARTSHOP_PANEL_SHIPPINGS / Standard', '', '', '', '', '', '', '', '', '', ''),
('EXPRESS', 'index.php?option=com_jshopping&controller=shippings&task=edit&shipping_id=2', 174, 'COM_SMARTSHOP_OTHER_ELEMENTS / COM_SMARTSHOP_PANEL_SHIPPINGS / Express', '', '', '', '', '', '', '', '', '', ''),
('STANDARD', 'index.php?option=com_jshopping&controller=shippingsprices&task=edit&sh_pr_method_id=1&shipping_id_back=0', 175, 'COM_SMARTSHOP_OTHER_ELEMENTS / Shipping prices / Standard', '', '', '', '', '', '', '', '', '', ''),
('EXPRESS', 'index.php?option=com_jshopping&controller=shippingsprices&task=edit&sh_pr_method_id=2&shipping_id_back=0', 176, 'COM_SMARTSHOP_OTHER_ELEMENTS / Shipping prices / Express', '', '', '', '', '', '', '', '', '', ''),
('PENDING CONFIRMED CANCELLED REFUNDED SHIPPED PAID COMPLETE REVERSAL', 'index.php?option=com_jshopping&controller=orderstatus', 177, 'COM_SMARTSHOP_OTHER_ELEMENTS / Order status', '', '', '', '', '', '', '', '', '', ''),
('HOURS KG LITER PCS M²', 'index.php?option=com_jshopping&controller=units', 178, 'COM_SMARTSHOP_OTHER_ELEMENTS / COM_SMARTSHOP_LIST_UNITS_MEASURE', '', '', '', '', '', '', '', '', '', ''),
('NEW SALE', 'index.php?option=com_jshopping&controller=productlabels', 179, 'COM_SMARTSHOP_OTHER_ELEMENTS / COM_SMARTSHOP_LIST_PRODUCT_LABELS', '', '', '', '', '', '', '', '', '', ''),
('RETURN STATUS', 'index.php?option=com_jshopping&controller=returnstatus', 180, 'Options / Return status', 'COM_SMARTSHOP_PANEL_RETURN_STATUS', 'Return status', 'Rückgabestatus', 'Return status', 'Return status', 'Return status', 'Return status', 'Return status', 'Return status', 'Return status');

CREATE TABLE `#__jshopping_search_blocklinks` (
  `id` int(11) NOT NULL,
  `link` text NOT NULL,
  `link2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_search_blocklinks` (`id`, `link`, `link2`) VALUES
(1, 'index.php?option=com_jshopping&controller=config&task=get_content', ''),
(2, 'index.php?option=com_jshopping&controller=offer_and_order&task=show', ''),
(3, 'index.php?option=com_jshopping&controller=offer_and_order&task=edit', ''),
(4, 'index.php?option=com_jshopping&controller=orders&task=show', ''),
(5, 'index.php?option=com_jshopping&controller=other&task=display', ''),
(6, 'index.php?option=com_jshopping&controller=products&task=search_related', ''),
(8, 'index.php?option=com_jshopping&controller=shippingsprices&task=edit', '');

CREATE TABLE `#__jshopping_shipping_ext_calc` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `params` text NOT NULL,
  `shipping_method` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_shipping_ext_calc` (`id`, `name`, `alias`, `description`, `params`, `shipping_method`, `published`, `ordering`) VALUES
(1, 'StandartWeight', 'sm_standart_weight', 'StandartWeight', '', '', 1, 1),
(2, 'Product', 'sm_product', 'Product', '', '', 1, 0);

CREATE TABLE `#__jshopping_shipping_method` (
  `shipping_id` int(11) NOT NULL,
  `name_en-GB` varchar(100) NOT NULL DEFAULT '',
  `description_en-GB` text NOT NULL,
  `name_de-DE` varchar(100) NOT NULL DEFAULT '',
  `description_de-DE` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `payments` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL,
  `ordering` int(6) NOT NULL DEFAULT '0',
  `name_es-ES` varchar(100) NOT NULL,
  `description_es-ES` text NOT NULL,
  `name_it-IT` varchar(100) NOT NULL,
  `description_it-IT` text NOT NULL,
  `name_fr-FR` varchar(100) NOT NULL,
  `description_fr-FR` text NOT NULL,
  `name_nl-NL` varchar(100) NOT NULL,
  `description_nl-NL` text NOT NULL,
  `name_pl-PL` varchar(100) NOT NULL,
  `description_pl-PL` text NOT NULL,
  `name_ru-RU` varchar(100) NOT NULL,
  `description_ru-RU` text NOT NULL,
  `name_sv-SE` varchar(100) NOT NULL,
  `description_sv-SE` text NOT NULL,
  `alias` varchar(100) NOT NULL,
  `params` text NOT NULL,
  `name_fr-CA` varchar(100) NOT NULL,
  `description_fr-CA` text NOT NULL,
  `shop_id` int(11) NOT NULL,
  `usergroup_id` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_shipping_method` (`shipping_id`, `name_en-GB`, `description_en-GB`, `name_de-DE`, `description_de-DE`, `published`, `payments`, `image`, `ordering`, `name_es-ES`, `description_es-ES`, `name_it-IT`, `description_it-IT`, `name_fr-FR`, `description_fr-FR`, `name_nl-NL`, `description_nl-NL`, `name_pl-PL`, `description_pl-PL`, `name_ru-RU`, `description_ru-RU`, `name_sv-SE`, `description_sv-SE`, `alias`, `params`, `name_fr-CA`, `description_fr-CA`, `shop_id`, `usergroup_id`) VALUES
(1, 'Standard', '', 'Standardversand', '<p>tesdf</p>', 1, '', '', 1, 'Standard', '', 'Standard', '', 'Standardversand', '', 'Standardversand', '', 'Standardversand', '', 'Standardversand', '', 'Standardversand', '', '', 'N;', 'Standardversand', '', 0, '0,1'),
(2, 'Express', '', 'Express', '', 1, '', '', 2, 'Express', '', 'Express', '', 'Express', '', 'Express', '', 'Express', '', 'Express', '', 'Express', '', '', '', 'Express', '', 0, '0,1');

CREATE TABLE `#__jshopping_shipping_method_price` (
  `sh_pr_method_id` int(11) NOT NULL,
  `shipping_method_id` int(11) NOT NULL,
  `shipping_tax_id` int(11) NOT NULL DEFAULT '0',
  `shipping_stand_price` decimal(12,2) NOT NULL,
  `params` text NOT NULL,
  `delivery_times_id` int(11) NOT NULL,
  `package_tax_id` int(11) NOT NULL,
  `package_stand_price` decimal(12,2) NOT NULL,  
  `name_en-GB` varchar(100) NOT NULL DEFAULT '',
  `description_en-GB` text NOT NULL,
  `name_de-DE` varchar(100) NOT NULL DEFAULT '',
  `description_de-DE` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `payments` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL,
  `ordering` int(6) NOT NULL DEFAULT '0',
  `name_es-ES` varchar(100) NOT NULL,
  `description_es-ES` text NOT NULL,
  `name_it-IT` varchar(100) NOT NULL,
  `description_it-IT` text NOT NULL,
  `name_fr-FR` varchar(100) NOT NULL,
  `description_fr-FR` text NOT NULL,
  `name_nl-NL` varchar(100) NOT NULL,
  `description_nl-NL` text NOT NULL,
  `name_pl-PL` varchar(100) NOT NULL,
  `description_pl-PL` text NOT NULL,
  `name_ru-RU` varchar(100) NOT NULL,
  `description_ru-RU` text NOT NULL,
  `name_sv-SE` varchar(100) NOT NULL,
  `description_sv-SE` text NOT NULL,
  `alias` varchar(100) NOT NULL,
  `name_fr-CA` varchar(100) NOT NULL,
  `description_fr-CA` text NOT NULL,
  `shop_id` int(11) NOT NULL,
  `usergroup_id` text,
  `shipping_type` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1 - price from shop\r\n2 - price from API'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_shipping_method_price` (`sh_pr_method_id`, `shipping_method_id`, `shipping_tax_id`, `shipping_stand_price`, `params`, `delivery_times_id`, `package_tax_id`, `package_stand_price`,`name_en-GB`,`description_en-GB`,`name_de-DE`,`description_de-DE`,`published`,`payments`,`image`,`ordering`,`name_es-ES`,`description_es-ES`,`name_it-IT`,`description_it-IT`,`name_fr-FR`,`description_fr-FR`,`name_nl-NL`,`description_nl-NL`,`name_pl-PL`,`description_pl-PL`,`name_ru-RU`,`description_ru-RU`,`name_sv-SE`,`description_sv-SE`,`alias`,`name_fr-CA`,`description_fr-CA`,`shop_id`,`usergroup_id`) VALUES
(1, 1, 1, '10.00', 's:0:\"\";', 2, 1, '0.00', 'Standard', '', 'Standardversand', '<p>tesdf</p>', 1, '', '', 1, 'Standard', '', 'Standard', '', 'Standardversand', '', 'Standardversand', '', 'Standardversand', '', 'Standardversand', '', 'Standardversand', '', '', 'Standardversand', '', 0, '0,1'),
(2, 2, 1, '25.00', '', 0, 0, '0.00', 'Express', '', 'Express', '', 1, '', '', 2, 'Express', '', 'Express', '', 'Express', '', 'Express', '', 'Express', '', 'Express', '', 'Express', '', '',  'Express', '', 0, '0,1');

CREATE TABLE `#__jshopping_shipping_method_price_countries` (
  `sh_method_country_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `sh_pr_method_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_shipping_method_price_countries` (`sh_method_country_id`, `country_id`, `sh_pr_method_id`) VALUES
(240, 239, 2),
(241, 238, 2),
(242, 237, 2),
(243, 236, 2),
(244, 235, 2),
(245, 234, 2),
(246, 233, 2),
(247, 232, 2),
(248, 231, 2),
(249, 230, 2),
(250, 229, 2),
(251, 228, 2),
(252, 227, 2),
(253, 226, 2),
(254, 225, 2),
(255, 224, 2),
(256, 223, 2),
(257, 222, 2),
(258, 221, 2),
(259, 220, 2),
(260, 219, 2),
(261, 218, 2),
(262, 217, 2),
(263, 216, 2),
(264, 215, 2),
(265, 214, 2),
(266, 213, 2),
(267, 212, 2),
(268, 211, 2),
(269, 210, 2),
(270, 209, 2),
(271, 208, 2),
(272, 207, 2),
(273, 206, 2),
(274, 205, 2),
(275, 204, 2),
(276, 203, 2),
(277, 202, 2),
(278, 201, 2),
(279, 200, 2),
(280, 199, 2),
(281, 198, 2),
(282, 197, 2),
(283, 196, 2),
(284, 195, 2),
(285, 194, 2),
(286, 193, 2),
(287, 192, 2),
(288, 191, 2),
(289, 190, 2),
(290, 189, 2),
(291, 188, 2),
(292, 187, 2),
(293, 186, 2),
(294, 185, 2),
(295, 184, 2),
(296, 183, 2),
(297, 182, 2),
(298, 181, 2),
(299, 180, 2),
(300, 179, 2),
(301, 178, 2),
(302, 177, 2),
(303, 176, 2),
(304, 175, 2),
(305, 174, 2),
(306, 173, 2),
(307, 172, 2),
(308, 171, 2),
(309, 170, 2),
(310, 169, 2),
(311, 168, 2),
(312, 167, 2),
(313, 166, 2),
(314, 165, 2),
(315, 164, 2),
(316, 163, 2),
(317, 162, 2),
(318, 161, 2),
(319, 160, 2),
(320, 159, 2),
(321, 158, 2),
(322, 157, 2),
(323, 156, 2),
(324, 155, 2),
(325, 154, 2),
(326, 153, 2),
(327, 152, 2),
(328, 151, 2),
(329, 150, 2),
(330, 149, 2),
(331, 148, 2),
(332, 147, 2),
(333, 146, 2),
(334, 145, 2),
(335, 144, 2),
(336, 143, 2),
(337, 142, 2),
(338, 141, 2),
(339, 140, 2),
(340, 139, 2),
(341, 138, 2),
(342, 137, 2),
(343, 136, 2),
(344, 135, 2),
(345, 134, 2),
(346, 133, 2),
(347, 132, 2),
(348, 131, 2),
(349, 130, 2),
(350, 129, 2),
(351, 128, 2),
(352, 127, 2),
(353, 126, 2),
(354, 125, 2),
(355, 124, 2),
(356, 123, 2),
(357, 122, 2),
(358, 121, 2),
(359, 120, 2),
(360, 119, 2),
(361, 118, 2),
(362, 117, 2),
(363, 116, 2),
(364, 115, 2),
(365, 114, 2),
(366, 113, 2),
(367, 112, 2),
(368, 111, 2),
(369, 110, 2),
(370, 109, 2),
(371, 108, 2),
(372, 107, 2),
(373, 106, 2),
(374, 105, 2),
(375, 104, 2),
(376, 103, 2),
(377, 102, 2),
(378, 101, 2),
(379, 100, 2),
(380, 99, 2),
(381, 98, 2),
(382, 97, 2),
(383, 96, 2),
(384, 95, 2),
(385, 94, 2),
(386, 93, 2),
(387, 92, 2),
(388, 91, 2),
(389, 90, 2),
(390, 89, 2),
(391, 88, 2),
(392, 87, 2),
(393, 86, 2),
(394, 85, 2),
(395, 84, 2),
(396, 83, 2),
(397, 82, 2),
(398, 81, 2),
(399, 80, 2),
(400, 79, 2),
(401, 78, 2),
(402, 77, 2),
(403, 76, 2),
(404, 75, 2),
(405, 74, 2),
(406, 73, 2),
(407, 72, 2),
(408, 71, 2),
(409, 70, 2),
(410, 69, 2),
(411, 68, 2),
(412, 67, 2),
(413, 66, 2),
(414, 65, 2),
(415, 64, 2),
(416, 63, 2),
(417, 62, 2),
(418, 61, 2),
(419, 60, 2),
(420, 59, 2),
(421, 58, 2),
(422, 57, 2),
(423, 56, 2),
(424, 55, 2),
(425, 54, 2),
(426, 53, 2),
(427, 52, 2),
(428, 51, 2),
(429, 50, 2),
(430, 49, 2),
(431, 48, 2),
(432, 47, 2),
(433, 46, 2),
(434, 45, 2),
(435, 44, 2),
(436, 43, 2),
(437, 42, 2),
(438, 41, 2),
(439, 40, 2),
(440, 39, 2),
(441, 38, 2),
(442, 37, 2),
(443, 36, 2),
(444, 35, 2),
(445, 34, 2),
(446, 33, 2),
(447, 32, 2),
(448, 31, 2),
(449, 30, 2),
(450, 29, 2),
(451, 28, 2),
(452, 27, 2),
(453, 26, 2),
(454, 25, 2),
(455, 24, 2),
(456, 23, 2),
(457, 22, 2),
(458, 21, 2),
(459, 20, 2),
(460, 19, 2),
(461, 18, 2),
(462, 17, 2),
(463, 16, 2),
(464, 15, 2),
(465, 14, 2),
(466, 13, 2),
(467, 12, 2),
(468, 11, 2),
(469, 10, 2),
(470, 9, 2),
(471, 8, 2),
(472, 7, 2),
(473, 6, 2),
(474, 5, 2),
(475, 4, 2),
(476, 3, 2),
(477, 2, 2),
(478, 1, 2),
(957, 150, 3),
(958, 1, 1),
(959, 63, 1),
(960, 2, 1),
(961, 3, 1),
(962, 4, 1),
(963, 5, 1),
(964, 6, 1),
(965, 7, 1),
(966, 8, 1),
(967, 9, 1),
(968, 65, 1),
(969, 10, 1),
(970, 11, 1),
(971, 12, 1),
(972, 15, 1),
(973, 68, 1),
(974, 13, 1),
(975, 16, 1),
(976, 17, 1),
(977, 18, 1),
(978, 19, 1),
(979, 21, 1),
(980, 22, 1),
(981, 23, 1),
(982, 24, 1),
(983, 25, 1),
(984, 26, 1),
(985, 27, 1),
(986, 28, 1),
(987, 29, 1),
(988, 30, 1),
(989, 231, 1),
(990, 31, 1),
(991, 32, 1),
(992, 33, 1),
(993, 34, 1),
(994, 35, 1),
(995, 40, 1),
(996, 43, 1),
(997, 44, 1),
(998, 45, 1),
(999, 50, 1),
(1000, 51, 1),
(1001, 57, 1),
(1002, 81, 1),
(1003, 59, 1),
(1004, 60, 1),
(1005, 58, 1),
(1006, 62, 1),
(1007, 64, 1),
(1008, 52, 1),
(1009, 66, 1),
(1010, 67, 1),
(1011, 69, 1),
(1012, 70, 1),
(1013, 71, 1),
(1014, 72, 1),
(1015, 73, 1),
(1016, 74, 1),
(1017, 76, 1),
(1018, 77, 1),
(1019, 75, 1),
(1020, 78, 1),
(1021, 79, 1),
(1022, 80, 1),
(1023, 82, 1),
(1024, 83, 1),
(1025, 86, 1),
(1026, 84, 1),
(1027, 85, 1),
(1028, 87, 1),
(1029, 88, 1),
(1030, 89, 1),
(1031, 90, 1),
(1032, 91, 1),
(1033, 92, 1),
(1034, 93, 1),
(1035, 94, 1),
(1036, 95, 1),
(1037, 96, 1),
(1038, 99, 1),
(1039, 100, 1),
(1040, 102, 1),
(1041, 101, 1),
(1042, 103, 1),
(1043, 98, 1),
(1044, 104, 1),
(1045, 105, 1),
(1046, 106, 1),
(1047, 107, 1),
(1048, 235, 1),
(1049, 108, 1),
(1050, 36, 1),
(1051, 37, 1),
(1052, 38, 1),
(1053, 39, 1),
(1054, 109, 1),
(1055, 173, 1),
(1056, 110, 1),
(1057, 115, 1),
(1058, 111, 1),
(1059, 46, 1),
(1060, 47, 1),
(1061, 48, 1),
(1062, 49, 1),
(1063, 113, 1),
(1064, 112, 1),
(1065, 53, 1),
(1066, 54, 1),
(1067, 114, 1),
(1068, 116, 1),
(1069, 119, 1),
(1070, 117, 1),
(1071, 118, 1),
(1072, 120, 1),
(1073, 121, 1),
(1074, 122, 1),
(1075, 123, 1),
(1076, 124, 1),
(1077, 127, 1),
(1078, 125, 1),
(1079, 128, 1),
(1080, 129, 1),
(1081, 130, 1),
(1082, 131, 1),
(1083, 132, 1),
(1084, 144, 1),
(1085, 133, 1),
(1086, 134, 1),
(1087, 135, 1),
(1088, 136, 1),
(1089, 137, 1),
(1090, 126, 1),
(1091, 138, 1),
(1092, 139, 1),
(1093, 140, 1),
(1094, 141, 1),
(1095, 142, 1),
(1096, 143, 1),
(1097, 145, 1),
(1098, 146, 1),
(1099, 147, 1),
(1100, 148, 1),
(1101, 149, 1),
(1102, 152, 1),
(1103, 153, 1),
(1104, 154, 1),
(1105, 150, 1),
(1106, 151, 1),
(1107, 155, 1),
(1108, 156, 1),
(1109, 157, 1),
(1110, 159, 1),
(1111, 158, 1),
(1112, 160, 1),
(1113, 161, 1),
(1114, 14, 1),
(1115, 61, 1),
(1116, 162, 1),
(1117, 163, 1),
(1118, 164, 1),
(1119, 165, 1),
(1120, 166, 1),
(1121, 167, 1),
(1122, 168, 1),
(1123, 169, 1),
(1124, 170, 1),
(1125, 171, 1),
(1126, 172, 1),
(1127, 193, 1),
(1128, 174, 1),
(1129, 177, 1),
(1130, 175, 1),
(1131, 176, 1),
(1132, 191, 1),
(1133, 238, 1),
(1134, 181, 1),
(1135, 182, 1),
(1136, 183, 1),
(1137, 184, 1),
(1138, 203, 1),
(1139, 204, 1),
(1140, 185, 1),
(1141, 186, 1),
(1142, 187, 1),
(1143, 239, 1),
(1144, 188, 1),
(1145, 189, 1),
(1146, 190, 1),
(1147, 192, 1),
(1148, 195, 1),
(1149, 196, 1),
(1150, 197, 1),
(1151, 178, 1),
(1152, 179, 1),
(1153, 198, 1),
(1154, 180, 1),
(1155, 199, 1),
(1156, 194, 1),
(1157, 200, 1),
(1158, 201, 1),
(1159, 202, 1),
(1160, 205, 1),
(1161, 207, 1),
(1162, 206, 1),
(1163, 208, 1),
(1164, 209, 1),
(1165, 210, 1),
(1166, 211, 1),
(1167, 212, 1),
(1168, 213, 1),
(1169, 42, 1),
(1170, 56, 1),
(1171, 214, 1),
(1172, 215, 1),
(1173, 216, 1),
(1174, 217, 1),
(1175, 218, 1),
(1176, 219, 1),
(1177, 220, 1),
(1178, 97, 1),
(1179, 224, 1),
(1180, 225, 1),
(1181, 223, 1),
(1182, 226, 1),
(1183, 227, 1),
(1184, 228, 1),
(1185, 229, 1),
(1186, 221, 1),
(1187, 232, 1),
(1188, 222, 1),
(1189, 230, 1),
(1190, 233, 1),
(1191, 20, 1),
(1192, 234, 1),
(1193, 236, 1),
(1194, 237, 1),
(1195, 41, 1),
(1196, 55, 1);

CREATE TABLE `#__jshopping_shipping_method_price_weight` (
  `sh_pr_weight_id` int(11) NOT NULL,
  `sh_pr_method_id` int(11) NOT NULL,
  `condition_id` int(11) NOT NULL,
  `shipping_price` decimal(12,2) NOT NULL,
  `shipping_package_price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_sort_val_attrs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `attr_val_id` bigint(20) DEFAULT NULL,
  `frontend_sorting` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_taxes` (
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(50) NOT NULL DEFAULT '',
  `tax_value` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_taxes` (`tax_id`, `tax_name`, `tax_value`) VALUES
(1, 'Normal', '19.00');

CREATE TABLE `#__jshopping_taxes_ext` (
  `id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `zones` text NOT NULL,
  `zones_states` text NOT NULL,
  `tax` decimal(12,2) NOT NULL,
  `firma_tax` decimal(12,2) NOT NULL,
  `taxes_ext` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_unit` (
  `id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '1',
  `name_de-DE` varchar(255) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_es-ES` varchar(255) NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `name_sv-SE` varchar(255) NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `unit_number_format` TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_unit` (`id`, `qty`, `name_de-DE`, `name_en-GB`, `name_es-ES`, `name_it-IT`, `name_fr-FR`, `name_nl-NL`, `name_pl-PL`, `name_ru-RU`, `name_sv-SE`, `name_fr-CA`) VALUES
(1, 1, 'Kg', 'Kg', 'Kg', 'Kg', 'Kg', 'Kg', 'Kg', 'Kg', 'Kg', 'Kg'),
(2, 1, 'Liter', 'Liter', 'Liter', 'Liter', 'Liter', 'Liter', 'Liter', 'Liter', 'Liter', 'Liter'),
(3, 1, 'St.', 'pcs.', 'pcs.', 'pcs.', 'St.', 'St.', 'St.', 'St.', 'St.', 'St.'),
(4, 1, 'M²', 'M²', 'M²', 'M²', 'M²', 'M²', 'M²', 'M²', 'M²', 'M²'),
(5, 1, 'Stunden', 'Hours', 'Hours', 'Hours', 'Hours', 'Hours', 'Hours', 'Hours', 'Stunden', 'Stunden');

CREATE TABLE `#__jshopping_upload` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allow_files_types` text,
  `allow_files_size` DOUBLE NOT NULL DEFAULT '0',
  `is_allow_product_page` int(11) NOT NULL DEFAULT '0',
  `is_allow_cart_page` int(11) NOT NULL DEFAULT '0',
  `upload_design` int(11) NOT NULL DEFAULT '0',
  `order_status_for_upload` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_upload` (`id`, `allow_files_types`, `is_allow_product_page`, `is_allow_cart_page`, `upload_design`, `order_status_for_upload`) VALUES
(1, 'jpeg,jpg,gif,png,pdf,ai,svg,zip', 0, 0, 0, '');

CREATE TABLE `#__jshopping_usergroups` (
  `usergroup_id` int(11) NOT NULL,
  `usergroup_name` varchar(64) NOT NULL,
  `usergroup_discount` decimal(12,2) NOT NULL,
  `usergroup_description` text NOT NULL,
  `usergroup_is_default` tinyint(1) NOT NULL,
  `name_en-GB` varchar(255) NOT NULL,
  `name_de-DE` varchar(255) NOT NULL,
  `description_en-GB` text NOT NULL,
  `description_de-DE` text NOT NULL,
  `name_fr-FR` varchar(255) NOT NULL,
  `description_fr-FR` text NOT NULL,
  `name_it-IT` varchar(255) NOT NULL,
  `description_it-IT` text NOT NULL,
  `name_nl-NL` varchar(255) NOT NULL,
  `description_nl-NL` text NOT NULL,
  `name_pl-PL` varchar(255) NOT NULL,
  `description_pl-PL` text NOT NULL,
  `name_ru-RU` varchar(255) NOT NULL,
  `description_ru-RU` text NOT NULL,
  `name_fr-CA` varchar(255) NOT NULL,
  `description_fr-CA` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_usergroups` (`usergroup_id`, `usergroup_name`, `usergroup_discount`, `usergroup_description`, `usergroup_is_default`, `name_en-GB`, `name_de-DE`, `description_en-GB`, `description_de-DE`, `name_fr-FR`, `description_fr-FR`, `name_it-IT`, `description_it-IT`, `name_nl-NL`, `description_nl-NL`, `name_pl-PL`, `description_pl-PL`, `name_ru-RU`, `description_ru-RU`, `name_fr-CA`, `description_fr-CA`) VALUES
(1, 'Default', '0.00', 'Default', 1, 'Default', 'Default', '', '', 'Default', '', 'Default', '', 'Default', '', 'Default', '', 'Default', '', 'Default', '');

CREATE TABLE `#__jshopping_users` (
  `user_id` int(11) NOT NULL,
  `usergroup_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `shipping_id` int(11) NOT NULL,
  `u_name` varchar(150) NOT NULL,
  `number` varchar(32) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `sender_address` tinyint(1) NOT NULL,
  `block` tinyint(1) NOT NULL DEFAULT 0,
  `credit_limit` decimal(18,6) NOT NULL DEFAULT 0,
  `open_amount` decimal(18,6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__jshopping_users_addresses` (
  `address_id` SERIAL,
  `user_id` BIGINT NOT NULL,
  `title` tinyint(1) NOT NULL DEFAULT 0,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `firma_name` varchar(100) NOT NULL,
  `client_type` tinyint(1) DEFAULT 0,
  `firma_code` varchar(100) NOT NULL,
  `tax_number` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `home` varchar(20) NOT NULL,
  `apartment` varchar(20) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` int(11) DEFAULT 0,
  `phone` varchar(20) NOT NULL,
  `mobil_phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `ext_field_1` varchar(255) NOT NULL,
  `ext_field_2` varchar(255) NOT NULL,
  `ext_field_3` varchar(255) NOT NULL,
  `m_name` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `street_nr` varchar(16) NOT NULL,
  `is_default` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'default for shipping',
  `is_default_bill` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'default for billing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_vendors` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` int(11) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `benef_bank_info` varchar(64) NOT NULL,
  `benef_bic` varchar(64) NOT NULL,
  `benef_conto` varchar(64) NOT NULL,
  `benef_payee` varchar(64) NOT NULL,
  `benef_iban` varchar(64) NOT NULL,
  `benef_bic_bic` varchar(64) NOT NULL,
  `benef_swift` varchar(64) NOT NULL,
  `interm_name` varchar(64) NOT NULL,
  `interm_swift` varchar(64) NOT NULL,
  `identification_number` varchar(64) NOT NULL,
  `tax_number` varchar(64) NOT NULL,
  `additional_information` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `main` tinyint(1) NOT NULL,
  `publish` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_shipping_conditions` (
  `condition_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordering` int(11) NOT NULL,
  `rules` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `formula` text COLLATE utf8mb4_unicode_ci NOT NULL, 
  `rule_apply` INT(1) NOT NULL DEFAULT '0',  
  PRIMARY KEY (`condition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__jshopping_shipping_conditions_options` (
  `width_id` int(11) NOT NULL,
  `height_id` int(11) NOT NULL,
  `depth_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `#__jshopping_shipping_conditions_options` (`width_id`, `height_id`, `depth_id`) VALUES
(0, 0, 0);

CREATE TABLE `#__jshopping_order_packages` (
  `id` SERIAL,
  `package_provider` varchar(250) NOT NULL DEFAULT '',
  `package_tracking` varchar(250) NOT NULL DEFAULT '',
  `package_status` varchar(250) NOT NULL DEFAULT '',
  `products` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `package` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__jshopping_return_packages` (
  `id` SERIAL,
  `package_status` varchar(250) NOT NULL DEFAULT '',
  `products` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `package` int(11) NOT NULL DEFAULT '1',
  `customer_comment` text NOT NULL,
  `admin_notice` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 CREATE TABLE `#__jshopping_return_status` (
  `status_id` SERIAL ,
  `name_en-GB` varchar(100) NOT NULL DEFAULT '',
  `name_de-DE` varchar(100) NOT NULL DEFAULT '',
  `name_es-ES` varchar(100) NOT NULL DEFAULT '',
  `name_it-IT` varchar(100) NOT NULL DEFAULT '',
  `name_fr-FR` varchar(100) NOT NULL DEFAULT '',
  `name_nl-NL` varchar(100) NOT NULL DEFAULT '',
  `name_pl-PL` varchar(100) NOT NULL DEFAULT '',
  `name_ru-RU` varchar(100) NOT NULL DEFAULT '',
  `name_sv-SE` varchar(100) NOT NULL DEFAULT '',
 PRIMARY KEY (status_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_return_status` (`status_id`, `name_en-GB`, `name_de-DE`, `name_es-ES`, `name_it-IT`, `name_fr-FR`, `name_nl-NL`, `name_pl-PL`, `name_ru-RU`, `name_sv-SE`) VALUES
(1, 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order', 'Accidental order'),
(2, 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available', 'Better price available'),
(3, 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged', 'The shipping box or envelope isn’t damaged, but the item is damaged'),
(4, 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date', 'Missed estimated delivery date'),
(5, 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories', 'Missing parts or accessories'),
(6, 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged', 'The shipping box or envelope and item are both damaged'),
(7, 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent', 'Wrong item sent'),
(8, 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly', 'Defective or does not work properly'),
(9, 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered', 'Arrived in addition to what was ordered'),
(10, 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted', 'No longer needed or wanted'),
(11, 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase', 'Unauthorised purchase'),
(12, 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate', 'Description on the website was not accurate'),
(13, 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery', 'Damaged during delivery'),
(14, 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate', 'Performance or quality not adequate'),
(15, 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose', 'Incompatible or not useful for intended purpose'),
(16, 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging', 'Damaged due to inappropriate packaging'),
(17, 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system', 'Part not compatible with the existing system'),
(18, 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install', 'Excessive installation or did not install'),
(19, 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given', 'No reason given');

CREATE TABLE IF NOT EXISTS `#__jshopping_return_packages_products` (
  `id` SERIAL,
  `package_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `return_status_id` int(11) NOT NULL,
  `customer_comment` text NOT NULL,
  `admin_notice` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `#__users` 
  ADD `hide_pd5_password` VARCHAR(100) NULL;

ALTER TABLE `#__jshopping_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alias` (`alias`),
  ADD KEY `name` (`name`);

ALTER TABLE `#__jshopping_attr`
  ADD PRIMARY KEY (`attr_id`),
  ADD KEY `group` (`group`),
  ADD KEY `attr_ordering` (`attr_ordering`),
  ADD KEY `attr_type` (`attr_type`),
  ADD KEY `independent` (`independent`),
  ADD KEY `allcats` (`allcats`);

ALTER TABLE `#__jshopping_attr_groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_attr_values`
  ADD PRIMARY KEY (`value_id`),
  ADD KEY `attr_id` (`attr_id`),
  ADD KEY `value_ordering` (`value_ordering`);

ALTER TABLE `#__jshopping_cart_temp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cookie` (`id_cookie`),
  ADD KEY `type_cart` (`type_cart`);

ALTER TABLE `#__jshopping_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `sort_add_date` (`category_add_date`),
  ADD KEY `category_parent_id` (`category_parent_id`),
  ADD KEY `category_publish` (`category_publish`),
  ADD KEY `category_ordertype` (`category_ordertype`),
  ADD KEY `category_template` (`category_template`),
  ADD KEY `ordering` (`ordering`),
  ADD KEY `category_add_date` (`category_add_date`),
  ADD KEY `products_page` (`products_page`),
  ADD KEY `products_row` (`products_row`),
  ADD KEY `access` (`access`),
  ADD KEY `category_publish_2` (`category_publish`,`access`);

ALTER TABLE `#__jshopping_categories_added_content`
  ADD PRIMARY KEY (`category_id`);

ALTER TABLE `#__jshopping_categories_shipping`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_category_prices_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cgid` (`category_id`,`group_id`);

ALTER TABLE `#__jshopping_config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_config_display_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `display_price` (`display_price`),
  ADD KEY `display_price_firma` (`display_price_firma`);

ALTER TABLE `#__jshopping_config_seo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alias` (`alias`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_config_statictext`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alias` (`alias`),
  ADD KEY `use_for_return_policy` (`use_for_return_policy`);

ALTER TABLE `#__jshopping_content`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_countries`
  ADD PRIMARY KEY (`country_id`),
  ADD KEY `country_publish` (`country_publish`),
  ADD KEY `ordering` (`ordering`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `country_code_2` (`country_code_2`);

ALTER TABLE `#__jshopping_coupons`
  ADD PRIMARY KEY (`coupon_id`),
  ADD KEY `coupon_type` (`coupon_type`),
  ADD KEY `coupon_code` (`coupon_code`),
  ADD KEY `tax_id` (`tax_id`),
  ADD KEY `used` (`used`),
  ADD KEY `for_user_id` (`for_user_id`),
  ADD KEY `coupon_publish` (`coupon_publish`),
  ADD KEY `coupon_start_date` (`coupon_start_date`),
  ADD KEY `coupon_expire_date` (`coupon_expire_date`),
  ADD KEY `finished_after_used` (`finished_after_used`);

ALTER TABLE `#__jshopping_coupons_users_rest`
  ADD PRIMARY KEY (`user_id`,`coupon_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `#__jshopping_currencies`
  ADD PRIMARY KEY (`currency_id`),
  ADD KEY `currency_code_iso` (`currency_code_iso`),
  ADD KEY `currency_code_num` (`currency_code_num`),
  ADD KEY `currency_ordering` (`currency_ordering`),
  ADD KEY `currency_publish` (`currency_publish`);

ALTER TABLE `#__jshopping_delivery_times`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_expanding_products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_free_attr`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_free_attribute_calcule_price`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `#__jshopping_free_attr_default_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `#__jshopping_import_export`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publish` (`publish`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_manufacturers`
  ADD PRIMARY KEY (`manufacturer_id`),
  ADD KEY `manufacturer_publish` (`manufacturer_publish`),
  ADD KEY `products_page` (`products_page`),
  ADD KEY `products_row` (`products_row`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_native_uploads_prices`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_offer_and_order`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

ALTER TABLE `#__jshopping_offer_and_order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD UNIQUE KEY `order_item_id` (`order_item_id`);

ALTER TABLE `#__jshopping_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `currency_code_iso` (`currency_code_iso`),
  ADD KEY `order_status` (`order_status`),
  ADD KEY `order_created` (`order_created`),
  ADD KEY `shipping_method_id` (`shipping_method_id`),
  ADD KEY `delivery_times_id` (`delivery_times_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `display_price` (`display_price`),
  ADD KEY `vendor_type` (`vendor_type`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `lang` (`lang`);

ALTER TABLE `#__jshopping_order_history`
  ADD PRIMARY KEY (`order_history_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_status_id` (`order_status_id`),
  ADD KEY `status_date_added` (`status_date_added`),
  ADD KEY `customer_notify` (`customer_notify`);

ALTER TABLE `#__jshopping_order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `delivery_times_id` (`delivery_times_id`),
  ADD KEY `vendor_id` (`vendor_id`);

ALTER TABLE `#__jshopping_order_items_native_uploads_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `#__jshopping_order_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `status_code` (`status_code`);

ALTER TABLE `#__jshopping_payment_method`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payment_code` (`payment_code`),
  ADD KEY `payment_publish` (`payment_publish`),
  ADD KEY `payment_ordering` (`payment_ordering`),
  ADD KEY `payment_type` (`payment_type`),
  ADD KEY `price_type` (`price_type`),
  ADD KEY `tax_id` (`tax_id`);

ALTER TABLE `#__jshopping_payment_trx`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `transaction` (`transaction`),
  ADD KEY `rescode` (`rescode`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `order_id_2` (`order_id`),
  ADD KEY `transaction_2` (`transaction`),
  ADD KEY `rescode_2` (`rescode`),
  ADD KEY `status_id_2` (`status_id`);

ALTER TABLE `#__jshopping_payment_trx_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trx_id` (`trx_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `key` (`key`),
  ADD KEY `trx_id_2` (`trx_id`),
  ADD KEY `order_id_2` (`order_id`),
  ADD KEY `key_2` (`key`);

ALTER TABLE `#__jshopping_products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_manufacturer_id` (`product_manufacturer_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `product_ean` (`product_ean`),
  ADD KEY `unlimited` (`unlimited`),
  ADD KEY `product_publish` (`product_publish`),
  ADD KEY `product_tax_id` (`product_tax_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `product_price` (`product_price`),
  ADD KEY `min_price` (`min_price`),
  ADD KEY `add_price_unit_id` (`add_price_unit_id`),
  ADD KEY `average_rating` (`average_rating`),
  ADD KEY `reviews_count` (`reviews_count`),
  ADD KEY `delivery_times_id` (`delivery_times_id`),
  ADD KEY `hits` (`hits`),
  ADD KEY `basic_price_unit_id` (`basic_price_unit_id`),
  ADD KEY `label_id` (`label_id`),
  ADD KEY `access` (`access`);

ALTER TABLE `#__jshopping_products_added_content`
  ADD PRIMARY KEY (`product_id`);

ALTER TABLE `#__jshopping_products_attr`
  ADD PRIMARY KEY (`product_attr_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `ext_attribute_product_id` (`ext_attribute_product_id`);

ALTER TABLE `#__jshopping_products_attr2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `attr_id` (`attr_id`),
  ADD KEY `attr_value_id` (`attr_value_id`),
  ADD KEY `price_mod` (`price_mod`);

ALTER TABLE `#__jshopping_products_extra_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group` (`group`),
  ADD KEY `allcats` (`allcats`),
  ADD KEY `type` (`type`),
  ADD KEY `multilist` (`multilist`),
  ADD KEY `group_2` (`group`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_products_extra_field_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_products_extra_field_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_products_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_products_free_attr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `attr_id` (`attr_id`);

ALTER TABLE `#__jshopping_products_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_products_option`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prodkey` (`product_id`,`key`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `#__jshopping_products_prices`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_quantity_start` (`product_quantity_start`),
  ADD KEY `product_quantity_finish` (`product_quantity_finish`);

ALTER TABLE `#__jshopping_products_prices_group`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_products_relations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`,`product_related_id`),
  ADD KEY `product_id_2` (`product_id`),
  ADD KEY `product_related_id` (`product_related_id`);

ALTER TABLE `#__jshopping_products_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_email` (`user_email`),
  ADD KEY `mark` (`mark`),
  ADD KEY `publish` (`publish`),
  ADD KEY `ip` (`ip`);

ALTER TABLE `#__jshopping_products_shipping`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_products_to_categories`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_id_2` (`product_id`,`category_id`,`product_ordering`),
  ADD KEY `product_ordering` (`product_ordering`);

ALTER TABLE `#__jshopping_products_videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `video_id` (`video_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `#__jshopping_product_labels`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_search_blocklinks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jshopping_shipping_ext_calc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alias` (`alias`),
  ADD KEY `published` (`published`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_shipping_method`
  ADD PRIMARY KEY (`shipping_id`),
  ADD KEY `alias` (`alias`),
  ADD KEY `published` (`published`),
  ADD KEY `ordering` (`ordering`);

ALTER TABLE `#__jshopping_shipping_method_price`
  ADD PRIMARY KEY (`sh_pr_method_id`),
  ADD KEY `shipping_method_id` (`shipping_method_id`),
  ADD KEY `shipping_tax_id` (`shipping_tax_id`),
  ADD KEY `package_tax_id` (`package_tax_id`),
  ADD KEY `delivery_times_id` (`delivery_times_id`);

ALTER TABLE `#__jshopping_shipping_method_price_countries`
  ADD PRIMARY KEY (`sh_method_country_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `sh_pr_method_id` (`sh_pr_method_id`),
  ADD KEY `sh_method_country_id` (`sh_method_country_id`,`country_id`,`sh_pr_method_id`),
  ADD KEY `country_id_2` (`country_id`,`sh_pr_method_id`),
  ADD KEY `sh_method_country_id_2` (`sh_method_country_id`,`country_id`);

ALTER TABLE `#__jshopping_shipping_method_price_weight`
  ADD PRIMARY KEY (`sh_pr_weight_id`),
  ADD KEY `sh_pr_method_id` (`sh_pr_method_id`),
  ADD KEY `sh_pr_weight_id` (`sh_pr_weight_id`,`sh_pr_method_id`);

ALTER TABLE `#__jshopping_sort_val_attrs`
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `#__jshopping_taxes`
  ADD PRIMARY KEY (`tax_id`);

ALTER TABLE `#__jshopping_taxes_ext`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_id` (`tax_id`);

ALTER TABLE `#__jshopping_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qty` (`qty`);

ALTER TABLE `#__jshopping_upload`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `#__jshopping_usergroups`
  ADD PRIMARY KEY (`usergroup_id`),
  ADD KEY `usergroup_name` (`usergroup_name`),
  ADD KEY `usergroup_is_default` (`usergroup_is_default`);

ALTER TABLE `#__jshopping_users`
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `u_name` (`u_name`),
  ADD KEY `usergroup_id` (`usergroup_id`),
  ADD KEY `usergroup_id_2` (`usergroup_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `shipping_id` (`shipping_id`);

ALTER TABLE `#__jshopping_vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country` (`country`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `email` (`email`),
  ADD KEY `main` (`main`),
  ADD KEY `publish` (`publish`);

ALTER TABLE `#__ee_editors_to_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `editor_id` (`editor_id`);

ALTER TABLE `#__ee_editors_to_products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__ee_editors_to_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__ee_editors_to_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_attr`
  MODIFY `attr_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_attr_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_attr_values`
  MODIFY `value_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_cart_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `#__jshopping_categories_added_content`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_categories_shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_category_prices_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `#__jshopping_config_display_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_config_seo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

ALTER TABLE `#__jshopping_config_statictext`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `#__jshopping_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

ALTER TABLE `#__jshopping_coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_currencies`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `#__jshopping_delivery_times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_expanding_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_free_attr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_free_attribute_calcule_price`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `#__jshopping_free_attr_default_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_import_export`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `#__jshopping_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `#__jshopping_manufacturers`
  MODIFY `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_native_uploads_prices`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_offer_and_order`
  MODIFY `order_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_offer_and_order_item`
  MODIFY `order_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_order_history`
  MODIFY `order_history_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_order_items_native_uploads_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_order_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `#__jshopping_payment_method`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `#__jshopping_payment_trx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_payment_trx_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_added_content`
  MODIFY `product_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_attr`
  MODIFY `product_attr_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_attr2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_extra_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_extra_field_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_extra_field_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_free_attr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_prices`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_prices_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_products_videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_product_labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `#__jshopping_search_blocklinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `#__jshopping_shipping_ext_calc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `#__jshopping_shipping_method`
  MODIFY `shipping_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `#__jshopping_shipping_method_price`
  MODIFY `sh_pr_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `#__jshopping_shipping_method_price_countries`
  MODIFY `sh_method_country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1197;

ALTER TABLE `#__jshopping_shipping_method_price_weight`
  MODIFY `sh_pr_weight_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_sort_val_attrs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_taxes`
  MODIFY `tax_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `#__jshopping_taxes_ext`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__jshopping_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `#__jshopping_upload`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `#__jshopping_usergroups`
  MODIFY `usergroup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `#__jshopping_vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `#__jshopping_production_calendar` (
  `id` int(11) NOT NULL,
  `working_days` text,
  `extra_working_days` text,
  `extra_weekend_days` text,
  `show_in_product` tinyint(1) NOT NULL DEFAULT '0',
  `show_in_product_list` tinyint(1) NOT NULL DEFAULT '0',
  `show_in_cart_checkout` tinyint(1) NOT NULL DEFAULT '0',
  `production_time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__jshopping_production_calendar` (`id`, `working_days`, `extra_working_days`, `extra_weekend_days`, `show_in_product`, `show_in_product_list`, `show_in_cart_checkout`, `production_time`) VALUES
(1, NULL, '[]', '[]', 0, 0, 0, 0);

ALTER TABLE `#__jshopping_production_calendar` ADD PRIMARY KEY (`id`);
INSERT INTO `#__jshopping_updates_info`(`is_copied_user_addresses_to_new_table`, `is_copied_orders_addresses_to_new_table`, `is_updated_product_price_preview`, `is_updated_product_price_group`, `is_moved_free_attr_calc_price`, `is_installed_new_media_video_for_product`, `is_installed_new_media_img_for_product`) VALUES(1, 1, 1, 1, 1, 1, 1);

CREATE TABLE IF NOT EXISTS `#__jshopping_products_media`(
	`id` SERIAL,
	`product_id` BIGINT NOT NULL,
	`media_title` TEXT,
	`media_src` TEXT COMMENT 'link, file name, code and etc. to src',
	`media_preview` TEXT COMMENT 'link, file name, code and etc. to peview (media_preview == thumb)',
	`media_src_abstract_type` BIGINT NOT NULL COMMENT 'Type of src. Link, name, code',
	`media_preview_abstract_type` BIGINT NOT NULL COMMENT 'Type of src preview. Link, name, code',
	`media_abstract_type` BIGINT NOT NULL COMMENT 'Abstract type of row. Video/image/audio',
	`ordering` INT DEFAULT 0,
	`is_main` TINYINT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS `#__jshopping_abstracts_types`(
	`id` SERIAL,
	`type_name` TEXT
);

INSERT INTO `#__jshopping_abstracts_types`(`type_name`)
VALUES
('image'),
('video'),
('audio'),
('link'),
('name'),
('code');

CREATE TABLE IF NOT EXISTS `#__jshopping_states` (
    `state_id` int(11) NOT NULL auto_increment,
    `country_id` int(11) NOT NULL,
    `state_publish` tinyint(4) NOT NULL,
    `ordering` smallint(6) NOT NULL,
    `name_en-GB` varchar(255) NOT NULL,
    `name_de-DE` varchar(255) NOT NULL,
    `name_ru-RU` varchar(255) NOT NULL,
    PRIMARY KEY  (`state_id`));
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (1, 81, 1, 27, 'Baden-Wurttemberg', 'Baden-Wurttemberg', 'Baden-Wurttemberg');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (2, 81, 1, 29, 'Bayern', 'Bayern', 'Bayern');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (3, 81, 1, 32, 'Brandenburg', 'Brandenburg', 'Brandenburg');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (4, 14, 1, 35, 'Burgenland', 'Burgenland', 'Burgenland');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (5, 14, 1, 36, 'Karnten', 'Karnten', 'Karnten');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (7, 81, 1, 31, 'Berlin', 'Berlin', 'Berlin');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (8, 81, 1, 44, 'Hessen', 'Hessen', 'Hessen');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (57, 223, 1, 57, 'Alaska', 'Alaska', 'Alaska');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (56, 223, 1, 56, 'Alabama', 'Alabama', 'Alabama');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (12, 81, 1, 33, 'Bremen', 'Bremen', 'Bremen');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (13, 81, 1, 34, 'Hamburg', 'Hamburg', 'Hamburg');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (14, 81, 1, 45, 'Mecklenburg-Vorpommern', 'Mecklenburg-Vorpommern', 'Mecklenburg-Vorpommern');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (15, 81, 1, 46, 'Niedersachsen', 'Niedersachsen', 'Niedersachsen');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (16, 81, 1, 47, 'Nordrhein-Westfalen', 'Nordrhein-Westfalen', 'Nordrhein-Westfalen');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (17, 81, 1, 48, 'Rheinland-Pfalz', 'Rheinland-Pfalz', 'Rheinland-Pfalz');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (18, 81, 1, 49, 'Saarland', 'Saarland', 'Saarland');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (19, 81, 1, 50, 'Sachsen', 'Sachsen', 'Sachsen');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (20, 81, 1, 51, 'Sachsen-Anhalt', 'Sachsen-Anhalt', 'Sachsen-Anhalt');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (21, 81, 1, 52, 'Schleswig-Holstein', 'Schleswig-Holstein', 'Schleswig-Holstein');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (22, 81, 1, 53, 'Thuringen', 'Thuringen', 'Thuringen');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (23, 14, 1, 37, 'Niederosterreich', 'Niederosterreich', 'Niederosterreich');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (24, 14, 1, 38, 'Oberosterreich', 'Oberosterreich', 'Oberosterreich');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (25, 14, 1, 39, 'Salzburg', 'Salzburg', 'Salzburg');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (26, 14, 1, 40, 'Steiermark', 'Steiermark', 'Steiermark');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (27, 14, 1, 41, 'Tirol', 'Tirol', 'Tirol');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (28, 14, 1, 42, 'Vorarlberg', 'Vorarlberg', 'Vorarlberg');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (29, 14, 1, 43, 'Wien', 'Wien', 'Wien');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (30, 204, 1, 1, 'Aargau', 'Aargau', 'Aargau');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (31, 204, 1, 2, 'Appenzell Ausserrhoden', 'Appenzell Ausserrhoden', 'Appenzell Ausserrhoden');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (32, 204, 1, 3, 'Appenzell Innerrhoden', 'Appenzell Innerrhoden', 'Appenzell Innerrhoden');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (33, 204, 1, 4, 'Basel-Landschaft', 'Basel-Landschaft', 'Basel-Landschaft');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (34, 204, 1, 5, 'Basel-Stadt', 'Basel-Stadt', 'Basel-Stadt');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (35, 204, 1, 6, 'Bern', 'Bern', 'Bern');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (36, 204, 1, 7, 'Freiburg', 'Freiburg', 'Freiburg');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (37, 204, 1, 8, 'Genf', 'Genf', 'Genf');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (38, 204, 1, 9, 'Glarus', 'Glarus', 'Glarus');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (39, 204, 1, 10, 'Graubunden', 'Graubunden', 'Graubunden');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (40, 204, 1, 11, 'Jura', 'Jura', 'Jura');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (41, 204, 1, 12, 'Luzern', 'Luzern', 'Luzern');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (42, 204, 1, 13, 'Neuenburg', 'Neuenburg', 'Neuenburg');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (43, 204, 1, 14, 'Nidwalden', 'Nidwalden', 'Nidwalden');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (44, 204, 1, 15, 'Obwalden', 'Obwalden', 'Obwalden');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (45, 204, 1, 16, 'Sankt Gallen', 'Sankt Gallen', 'Sankt Gallen');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (46, 204, 1, 17, 'Schaffhausen', 'Schaffhausen', 'Schaffhausen');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (47, 204, 1, 18, 'Schwyz', 'Schwyz', 'Schwyz');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (48, 204, 1, 19, 'Solothurn', 'Solothurn', 'Solothurn');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (49, 204, 1, 20, 'Thurgau', 'Thurgau', 'Thurgau');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (50, 204, 1, 21, 'Ticino', 'Ticino', 'Ticino');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (51, 204, 1, 22, 'Uri', 'Uri', 'Uri');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (52, 204, 1, 23, 'Waadt', 'Waadt', 'Waadt');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (53, 204, 1, 24, 'Wallis', 'Wallis', 'Wallis');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (54, 204, 1, 25, 'Zug', 'Zug', 'Zug');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (55, 204, 1, 26, 'Zurich', 'Zurich', 'Zurich');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (58, 223, 1, 58, 'American Samoa', 'American Samoa', 'American Samoa');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (59, 223, 1, 59, 'Arizona', 'Arizona', 'Arizona');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (60, 223, 1, 60, 'Arkansas', 'Arkansas', 'Arkansas');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (61, 223, 1, 61, 'California', 'California', 'California');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (62, 223, 1, 62, 'Colorado', 'Colorado', 'Colorado');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (63, 223, 1, 63, 'Connecticut', 'Connecticut', 'Connecticut');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (64, 223, 1, 64, 'Delaware', 'Delaware', 'Delaware');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (65, 223, 1, 65, 'District of Columbia', 'District of Columbia', 'District of Columbia');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (66, 223, 1, 66, 'Florida', 'Florida', 'Florida');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (67, 223, 1, 67, 'Georgia', 'Georgia', 'Georgia');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (68, 223, 1, 68, 'Guam', 'Guam', 'Guam');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (69, 223, 1, 69, 'Hawaii', 'Hawaii', 'Hawaii');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (70, 223, 1, 70, 'Idaho', 'Idaho', 'Idaho');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (71, 223, 1, 71, 'Illinois', 'Illinois', 'Illinois');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (72, 223, 1, 72, 'Indiana', 'Indiana', 'Indiana');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (73, 223, 1, 73, 'Iowa', 'Iowa', 'Iowa');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (74, 223, 1, 74, 'Kansas', 'Kansas', 'Kansas');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (75, 223, 1, 75, 'Kentucky', 'Kentucky', 'Kentucky');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (76, 223, 1, 76, 'Louisiana', 'Louisiana', 'Louisiana');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (77, 223, 1, 77, 'Maine', 'Maine', 'Maine');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (78, 223, 1, 78, 'Maryland', 'Maryland', 'Maryland');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (79, 223, 1, 79, 'Massachusetts', 'Massachusetts', 'Massachusetts');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (80, 223, 1, 80, 'Michigan', 'Michigan', 'Michigan');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (81, 223, 1, 81, 'Minnesota', 'Minnesota', 'Minnesota');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (82, 223, 1, 82, 'Mississippi', 'Mississippi', 'Mississippi');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (83, 223, 1, 83, 'Missouri', 'Missouri', 'Missouri');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (84, 223, 1, 84, 'Montana', 'Montana', 'Montana');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (85, 223, 1, 85, 'Nebraska', 'Nebraska', 'Nebraska');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (86, 223, 1, 86, 'Nevada', 'Nevada', 'Nevada');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (87, 223, 1, 87, 'New Hampshire', 'New Hampshire', 'New Hampshire');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (88, 223, 1, 88, 'New Jersey', 'New Jersey', 'New Jersey');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (89, 223, 1, 89, 'New Mexico', 'New Mexico', 'New Mexico');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (90, 223, 1, 90, 'New York', 'New York', 'New York');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (91, 223, 1, 91, 'North Carolina', 'North Carolina', 'North Carolina');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (92, 223, 1, 92, 'North Dakota', 'North Dakota', 'North Dakota');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (93, 223, 1, 93, 'Northern Marianas Islands ', 'Northern Marianas Islands ', 'Northern Marianas Islands ');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (94, 223, 1, 94, 'Ohio', 'Ohio', 'Ohio');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (95, 223, 1, 95, 'Oklahoma', 'Oklahoma', 'Oklahoma');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (96, 223, 1, 96, 'Oregon', 'Oregon', 'Oregon');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (97, 223, 1, 97, 'Pennsylvania', 'Pennsylvania', 'Pennsylvania');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (98, 223, 1, 98, 'Puerto Rico', 'Puerto Rico', 'Puerto Rico');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (99, 223, 1, 99, 'Rhode Island', 'Rhode Island', 'Rhode Island');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (100, 223, 1, 100, 'South Carolina', 'South Carolina', 'South Carolina');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (101, 223, 1, 101, 'South Dakota', 'South Dakota', 'South Dakota');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (102, 223, 1, 102, 'Tennessee', 'Tennessee', 'Tennessee');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (103, 223, 1, 103, 'Texas', 'Texas', 'Texas');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (104, 223, 1, 104, 'Utah', 'Utah', 'Utah');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (105, 223, 1, 105, 'Vermont', 'Vermont', 'Vermont');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (106, 223, 1, 106, 'Virginia ', 'Virginia ', 'Virginia ');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (107, 223, 1, 107, 'Virgin Islands', 'Virgin Islands', 'Virgin Islands');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (108, 223, 1, 108, 'Washington', 'Washington', 'Washington');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (109, 223, 1, 109, 'West Virginia', 'West Virginia', 'West Virginia');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (110, 223, 1, 110, 'Wisconsin', 'Wisconsin', 'Wisconsin');
INSERT INTO `#__jshopping_states` (`state_id`, `country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES (111, 223, 1, 111, 'Wyoming', 'Wyoming', 'Wyoming');

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
  `refund_date` date,
  `pdf_date` date,
  `pdf_file` VARCHAR(50) NOT NULL DEFAULT '',
  `refund_number` VARCHAR(50) NOT NULL DEFAULT '0',
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


DELETE FROM `#__jshopping_states` WHERE `country_id` = 38;
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '1', 'Ontario', 'Ontario', 'Онтарио');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '2', 'Quebec', 'Quebec', 'Квебек');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '3', 'Nova Scotia', 'Nova Scotia', 'Новая Шотландия');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '4', 'New Brunswick', 'New Brunswick', 'Нью-Брансуик');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '5', 'Manitoba', 'Manitoba', 'Манитоба');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '6', 'British Columbia', 'British Columbia', 'Британская Колумбия');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '7', 'Prince Edward Island', 'Prince Edward Island', 'Остров Принца Эдуарда');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '8', 'Saskatchewan', 'Saskatchewan', 'Саскачеван');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '9', 'Alberta', 'Alberta', 'Альберта');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '10', 'Newfoundland and Labrador', 'Newfoundland and Labrador', 'Ньюфаундленд и Лабрадор');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '11', 'Northwest Territories', 'Northwest Territories', 'Северо-Западные территории');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '12', 'Yukon', 'Yukon', 'Юкон');
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`) VALUES ('38', '1', '13', 'Nunavut', 'Nunavut', 'Нунавут');

CREATE TABLE `#__jshopping_taxes_ext_additional_taxes` (
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__jshopping_taxes_ext_additional_taxes`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `#__jshopping_taxes_ext_additional_taxes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

CREATE TABLE  IF NOT EXISTS `#__jshopping_shipping_method_price_states` (
       `sh_method_state_id` int(11) NOT NULL AUTO_INCREMENT,
       `state_id` int(11) NOT NULL,
       `sh_pr_method_id` int(11) NOT NULL,
       PRIMARY KEY (`sh_method_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;