START TRANSACTION;
DELETE FROM `#__jshopping_payment_method` WHERE `payment_id` IN(3,4,5);
COMMIT;