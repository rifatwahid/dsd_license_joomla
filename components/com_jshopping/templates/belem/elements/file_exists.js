import React, { useState } from '../../../js/react/node_modules/react';


const File_exists = (data) => {
    let [exist, setExist] = useState('1');

    let updateExist=(value)=> {
        setExist(value)
    };

    jQuery.ajax({
        method: "POST",
        url: data.link,
        data: { file: data.file}
    })
    .done(function( res ) {
        updateExist(res);
    });

    return (percent);
}

export default File_exists;