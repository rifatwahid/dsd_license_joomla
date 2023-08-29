import React, { useState } from '../../../js/react/node_modules/react';

const Sprint_atribute = (data) => {
    let dataJson = data;
    let values = '';
    let attr;
    let v;
    function setValue(values, v){
        (values.length > 0) ? values += '; ' : '';
        values += v.value;
        return values;
    }
    let element = '';
    if(typeof dataJson.atribute != 'undefined')
    {
        const result = Object.keys(dataJson.atribute).map((key) => dataJson.atribute[key]);

        element = (typeof dataJson.atribute != 'undefined' && dataJson.atribute != null) ?
            <div className="list_attribute">
                {result.map((attr, ins) =>
                    (attr.attr_type != 3 && typeof attr.length == 'undefined') ?
                        <p className="jshop_cart_attribute list_attribute__item" key={ins}>
                            <span className="name">{attr.attr}</span>: <span className="value">{attr.value}</span>
                        </p>
                        : (typeof attr.length != 'undefined') ?
                        <span  key={ins}>
                            values = ''
                            attr.map((v, i) =>
                                (v.value.length > 0) ?
                                   setValue(values, v)
                                : ''
                        )
                        <p className="jshop_cart_attribute list_attribute__item">
                                    <span className="name">{v.attr}</span>: <span
                            className="value">{values}</span>
                        </p>
                    </span>
                        : ''
                )
                }

            </div>
            : '';
    }

    return (element);
}
export default Sprint_atribute;