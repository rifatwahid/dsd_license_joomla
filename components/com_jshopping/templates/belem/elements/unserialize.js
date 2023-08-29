import React, { useState } from '../../../js/react/node_modules/react';

const Unserialize = (data) => {
    let [sereliaze, setSereliaze] = useState(data.sdata);

    let updateSereliaze=(value)=> {
        setSereliaze(value)
    };

    jQuery.ajax({
        method: "POST",
        url: data.link,
        data: { sereliaze: data.sdata}
    })
        .done(function( res ) {
            updateSereliaze(res);
        });

    return (sereliaze);
}

export default Unserialize;