START TRANSACTION;
UPDATE `#__jshopping_products` SET `usergroup_show_product`='*',`usergroup_show_price`='*',`usergroup_show_buy`='*';
COMMIT;