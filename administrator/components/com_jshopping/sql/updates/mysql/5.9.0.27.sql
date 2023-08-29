ALTER TABLE `#__jshopping_products_prices`
    MODIFY `discount` decimal(18,6) NOT NULL,
    MODIFY `product_quantity_start` DOUBLE(18,6) NOT NULL,
    MODIFY `product_quantity_finish` DOUBLE(18,6) NOT NULL,
    MODIFY `price` decimal(18,6) NOT NULL,
    MODIFY `start_discount` decimal(18,6) NOT NULL;