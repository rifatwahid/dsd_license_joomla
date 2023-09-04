import React, { useState } from '../../../js/react/node_modules/react';


const Formattax = (data) => {
    let [percent, setPercent] = useState(data.percent);

    let updatePercent=(value)=> {
        setPercent(value)
    };

    // jQuery.ajax({
    //     method: "POST",
    //     url: data.link,
    //     data: { tax: data.percent}
    // })
    // .done(function( res ) {
    //     updatePercent(res);
    // });

    return (percent);
}

export default Formattax;