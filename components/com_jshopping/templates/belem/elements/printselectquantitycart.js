import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';


const Printselectquantitycart = (data) => {
    let [qty, setQty] = useState('');

    let updateQty=(value)=> {
        setQty(value)
    };

    jQuery.ajax({
        method: "POST",
        url: data.data.printselectquantitycart_link,
        data: {product_id:data.product_id,quantity_select:data.quantity_select, default_count_product:data.default_count_product, name: data.name, key_id: data.key_id}
    })
    .done(function( res ) {
        updateQty(res);
    });

    return (<div className="_count"  dangerouslySetInnerHTML={{__html:qty}} />);
}

export default Printselectquantitycart;