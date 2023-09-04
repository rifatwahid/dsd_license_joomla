import React, { useState } from '../../../js/react/node_modules/react';

const Sprintbasicprice = (data) => {
    let [price, setPrice] = useState(data.prod['price']);

    let updatePrice=(value)=> {
        setPrice(value)
    };

    jQuery.ajax({
        method: "POST",
        url: data.link,
        data: { prod: data.prod}
    })
        .done(function( res ) {
            price = res;
            updatePrice(price);
        });

    return (price);
}

export default Sprintbasicprice;