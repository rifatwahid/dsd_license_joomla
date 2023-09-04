-- HOW USE:
-- CALL addFieldIfNotExists('realPrefix_tableName', 'newColumnName', 'TINYINT(1) NOT NULL DEFAULT 1');
-- CALL addFieldIfNotExistsWithUnknownPrefixTable('justTableNameWithoutPrefix', 'newColumnName', 'TINYINT(1) NOT NULL DEFAULT 1');

-- EXAMPLES:
-- CALL addFieldIfNotExists('das_jshopping_products', 'newColumnName', 'TINYINT(1) NOT NULL DEFAULT 1');
-- CALL addFieldIfNotExistsWithUnknownPrefixTable('jshopping_products', 'newColumnName', 'TINYINT(1) NOT NULL DEFAULT 1');

DROP PROCEDURE IF EXISTS addFieldIfNotExistsWithUnknownPrefixTable;
DROP PROCEDURE IF EXISTS addFieldIfNotExists;
DROP FUNCTION IF EXISTS isFieldExisting;

CREATE FUNCTION isFieldExisting (table_name_IN VARCHAR(100), field_name_IN VARCHAR(100)) 
RETURNS INT
RETURN (
    SELECT COUNT(COLUMN_NAME) 
    FROM INFORMATION_SCHEMA.columns 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = table_name_IN 
    AND COLUMN_NAME = field_name_IN
);

CREATE PROCEDURE addFieldIfNotExists (
    IN table_name_IN VARCHAR(100)
    , IN field_name_IN VARCHAR(100)
    , IN field_definition_IN VARCHAR(100)
)
BEGIN

    SET @isFieldThere = isFieldExisting(table_name_IN, field_name_IN);
    IF (@isFieldThere = 0) THEN

        SET @ddl = CONCAT('ALTER TABLE `', table_name_IN, '` ADD COLUMN `', field_name_IN, '` ', field_definition_IN);

        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

    END IF;

END;

CREATE PROCEDURE addFieldIfNotExistsWithUnknownPrefixTable(
    IN table_name_IN VARCHAR(100)
    , IN field_name_IN VARCHAR(100)
    , IN field_definition_IN VARCHAR(100)
)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE tableName VARCHAR(100);
    DECLARE dataBaseName VARCHAR(100) DEFAULT DATABASE();
    DECLARE cur1 CURSOR FOR (SELECT `TABLE_NAME` FROM (
        SELECT `TABLE_NAME`, `TABLE_SCHEMA` FROM `information_schema`.`TABLES` WHERE `TABLE_NAME` LIKE CONCAT('%\_', table_name_IN)
    ) AS `inform` WHERE `inform`.`TABLE_SCHEMA` = dataBaseName);
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur1;

        read_loop: LOOP
            FETCH cur1 INTO tableName;

            IF done THEN
                LEAVE read_loop;
            END IF;
            
            CALL addFieldIfNotExists(tableName, field_name_IN, field_definition_IN);
        END LOOP;

    CLOSE cur1;
END;


CREATE FUNCTION isValueExisting (table_name_IN VARCHAR(100), field_name_IN VARCHAR(100), value_IN VARCHAR(100)) 
RETURNS BIGINT
BEGIN
    DECLARE result BIGINT;

    SET @query = CONCAT('SELECT COUNT(', field_name_IN, ') FROM ', table_name_IN, ' WHERE ', field_name_IN, ' = ''', value_IN, '''');
    PREPARE stmt FROM @query;
    EXECUTE stmt INTO result;
    DEALLOCATE PREPARE stmt;

    RETURN result;
END;

CREATE PROCEDURE insertValueIfNotExists (
    IN table_name_IN VARCHAR(100)
    , IN field_name_IN VARCHAR(100)
    , IN value_IN VARCHAR(100)
	, IN query TEXT
)
BEGIN
	
    SET @isFieldThere = isValueExisting(table_name_IN, field_name_IN, value_IN);
    IF (@isFieldThere = 0) THEN

        SET @ddl = query;

        PREPARE stmt FROM @ddl;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

    END IF;

END;

CREATE PROCEDURE insertValueIfNotExistsWithUnknownPrefixTable(
    IN table_name_IN VARCHAR(100)
    , IN field_name_IN VARCHAR(100)
    , IN value_IN VARCHAR(100)
	, IN query TEXT
)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE tableName VARCHAR(100);
    DECLARE dataBaseName VARCHAR(100) DEFAULT DATABASE();
    DECLARE cur1 CURSOR FOR (SELECT `TABLE_NAME` FROM (
        SELECT `TABLE_NAME`, `TABLE_SCHEMA` FROM `information_schema`.`TABLES` WHERE `TABLE_NAME` LIKE CONCAT('%', table_name_IN)
    ) AS `inform` WHERE `inform`.`TABLE_SCHEMA` = dataBaseName);
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur1;

        read_loop: LOOP
            FETCH cur1 INTO tableName;

            IF done THEN
                LEAVE read_loop;
            END IF;

            SET @preparedQuery = REPLACE(query, '#__', tableName);
            
            CALL insertValueIfNotExists(tableName, field_name_IN, value_IN, @preparedQuery);
        END LOOP;

    CLOSE cur1;
END;