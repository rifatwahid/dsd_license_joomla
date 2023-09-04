import React, { useState } from '../../../js/react/node_modules/react';


const Displaytotalcarttaxname = (data) => {
    let display_price = data.price;
    if (data.price != null) {
        display_price = dataJson.config.display_price_front_current;
    }

    if (display_price == 0) {
        return Joomla.JText._('COM_SMARTSHOP_INC_TAX');
    }

    return Joomla.JText._('COM_SMARTSHOP_PLUS_TAX');
}


export default Displaytotalcarttaxname;