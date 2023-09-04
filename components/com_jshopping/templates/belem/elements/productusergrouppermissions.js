import React, { useState } from '../../../js/react/node_modules/react';


const Productusergrouppermissions = (data) => {
    let [permissions, setPermissions] = useState('');

    let updatePermissions=(value)=> {
        setPermissions(value)
    };

    jQuery.ajax({
        method: "POST",
        url: data.link,
        data: { product:  JSON.stringify(data.product)}
    })
    .done(function( res ) {
        updatePermissions(permissions);
    });

    return (permissions);
}

export default Productusergrouppermissions;