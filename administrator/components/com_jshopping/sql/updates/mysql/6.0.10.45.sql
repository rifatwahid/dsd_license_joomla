INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '1', 'Ontario', 'Ontario', 'Онтарио'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Ontario'
);
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '2', 'Quebec', 'Quebec', 'Квебек'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Quebec'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '3', 'Nova Scotia', 'Nova Scotia', 'Новая Шотландия'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Nova Scotia'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '4', 'New Brunswick', 'New Brunswick', 'Нью-Брансуик'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'New Brunswick'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '5', 'Manitoba', 'Manitoba', 'Манитоба'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Manitoba'
);
INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '6', 'British Columbia', 'British Columbia', 'Британская Колумбия'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'British Columbia'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '7', 'Prince Edward Island', 'Prince Edward Island', 'Астров Принца Эдуарда'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Prince Edward Island'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '8', 'Saskatchewan', 'Saskatchewan', 'Саскачеван'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Saskatchewan'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '9', 'Alberta', 'Alberta', 'Альберта'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Alberta'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '10', 'Newfoundland and Labrador', 'Newfoundland and Labrador', 'Ньюфаундленд и Лабрадор'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Newfoundland and Labrador'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '11', 'Northwest Territories', 'Northwest Territories', 'Северо-Западные территории'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Northwest Territories'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '12', 'Yukon', 'Yukon', 'Юкон'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Yukon'
);

INSERT INTO `#__jshopping_states` (`country_id`, `state_publish`, `ordering`, `name_en-GB`, `name_de-DE`, `name_ru-RU`)
SELECT '38', '1', '13', 'Nunavut', 'Nunavut', 'Нунавут'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `#__jshopping_states`
    WHERE `country_id` = '38' AND `name_en-GB` = 'Nunavut'
);


CREATE TABLE IF NOT EXISTS `#__jshopping_taxes_ext_additional_taxes` (
  `id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__jshopping_taxes_ext_additional_taxes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;