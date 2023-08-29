import React, { useState } from '../../../js/react/node_modules/react';

const SprintQtyInStock = (data) => {
   if (Array.isArray(data.qty_in_stock) == true) {
        return data.qty_in_stock;
    } else {
        if (data.qty_in_stock['unlimited'] == 1) {
            return Joomla.JText._('COM_SMARTSHOP_UNLIMITED');
        }
        return data.qty_in_stock['qty'];
    }
}

export default SprintQtyInStock;