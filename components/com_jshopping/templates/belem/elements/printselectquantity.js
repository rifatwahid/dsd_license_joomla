import React, { useState , useEffect} from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';


var ajax = null;
const Printselectquantity = (data) => {
    let [qty, setQty] = useState('');

    let updateQty=(value)=> {
        setQty(value)
    };

    useEffect(() => {
        ajax = jQuery.ajax({
            method: "POST",
            url: data.link,
            data: {product :data.product, equal_steps :data.equal_steps, quantity_select:data.quantity_select, default_count_product:data.default_count_product}
        })
        .done(function( res ) {
            updateQty(res.replace('selected', 'value'));
        });
    }, []);

    return (Parser(qty));
}

export default Printselectquantity;