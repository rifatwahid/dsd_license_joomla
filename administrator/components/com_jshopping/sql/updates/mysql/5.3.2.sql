START TRANSACTION;
UPDATE `#__jshopping_payment_method` SET `payment_status`=1 WHERE `payment_status`=0;
COMMIT;