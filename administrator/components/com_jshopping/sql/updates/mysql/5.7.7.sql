START TRANSACTION;


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

COMMIT;