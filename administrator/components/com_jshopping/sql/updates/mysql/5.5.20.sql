/*
START TRANSACTION;
ALTER TABLE `#__jshopping_config` ADD `pdf_header_file_name` varchar(255) DEFAULT 'header_default.jpg';
ALTER TABLE `#__jshopping_config` ADD `pdf_footer_file_name` varchar(255) DEFAULT 'footer_default.jpg';
COMMIT;
*/