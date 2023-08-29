START TRANSACTION;
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
COMMIT;

CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_taxes_ext', 'zones_states', 'TEXT NOT NULL');