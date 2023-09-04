CREATE TABLE IF NOT EXISTS `#__jshopping_config_fields` ( 
	`id` SERIAL, 
	`name` varchar(50) NOT NULL, 
	`display` tinyint(4) NOT NULL DEFAULT '3' COMMENT '1 - register, 2 - address, 3 - both',
	`require` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 - register, 2 - address, 3 - both',
	`sorting` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
DELETE FROM `#__jshopping_config_fields` WHERE `id` < 25;
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
	
UPDATE `#__jshopping_config_fields` SET `sorting`=1 WHERE `id`=1 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=2 WHERE `id`=2 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=3 WHERE `id`=3 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=4 WHERE `id`=4 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=5 WHERE `id`=5 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=6 WHERE `id`=6 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=7 WHERE `id`=7 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=8 WHERE `id`=8 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=9 WHERE `id`=9 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=10 WHERE `id`=10 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=11 WHERE `id`=11 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=12 WHERE `id`=12 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=13 WHERE `id`=13 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=14 WHERE `id`=14 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=15 WHERE `id`=15 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=16 WHERE `id`=16 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=17 WHERE `id`=17 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=18 WHERE `id`=18 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=19 WHERE `id`=19 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=20 WHERE `id`=20 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=21 WHERE `id`=21 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=22 WHERE `id`=22 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=23 WHERE `id`=23 AND `sorting`=0;
UPDATE `#__jshopping_config_fields` SET `sorting`=24 WHERE `id`=24 AND `sorting`=0;