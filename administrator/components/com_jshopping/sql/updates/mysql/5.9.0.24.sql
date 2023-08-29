CREATE TABLE IF NOT EXISTS `#__jshopping_config_fields` ( 
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