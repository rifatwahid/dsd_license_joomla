START TRANSACTION;
DELETE FROM `#__jshopping_import_export` WHERE `alias` = 'simpleexport';
DELETE FROM `#__jshopping_import_export` WHERE `alias` = 'simpleimport';
COMMIT;