import React, { useState } from '../../../js/react/node_modules/react';

const Producttaxinfo = (data) => {
    let [tax, setTax] = useState(data.tax);

    let updateTax=(value)=> {
        setTax(value)
    };

    jQuery.ajax({
        method: "POST",
        url: data.link,
        data: { tax: data.tax}
    })
    .done(function( res ) {
        tax = res;
        updateTax(tax);
    });

    return (tax);
}

export default Producttaxinfo;