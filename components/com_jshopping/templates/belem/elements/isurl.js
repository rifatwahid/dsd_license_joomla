import React, { useState } from '../../../js/react/node_modules/react';

const Isurl = (data) => {
    let [isUrl, setIsurl] = useState(1);

    let updateIsurl=(value)=> {
        setIsurl(value)
    };

    jQuery.ajax({
        method: "POST",
        url: data.link,
        data: { price: data.url}
    })
    .done(function( res ) {
        updateIsurl(res);
    });

    return (isUrl);
}

export default Isurl;