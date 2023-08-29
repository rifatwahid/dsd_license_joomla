import React, { useState } from '../../../js/react/node_modules/react';

let ajax = null;
const Formatprice = (props) => {
    let data = props.data;
    if (data.component) {
        function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {

            let sign = number < 0 ? '-' : '';

            let s_number = Math.abs(parseInt(number = (+number || 0).toFixed(decimals))) + "";
            let len = s_number.length;
            let tchunk = len > 3 ? len % 3 : 0;

            let ch_first = (tchunk ? s_number.substr(0, tchunk) + thousands_sep : '');
            let ch_rest = s_number.substr(tchunk)
                .replace(/(\d\d\d)(?=\d)/g, '$1' + thousands_sep);
            let ch_last = decimals ?
                dec_point + (Math.abs(number) - s_number)
                    .toFixed(decimals)
                    .slice(2) :
                '';

            return sign + ch_first + ch_rest + ch_last;
        }

        let currency_code = data.config.currency_code;
        let price = number_format(props.price, data.config.decimal_count, data.config.decimal_symbol, data.config.thousand_separator);
        let str1 = data.config.format_currency[data.config.currency_format].replace('00', price)
        let result = str1.replace('Symb', currency_code);

        return result;

    }else{
        return '';
    }
}
export default Formatprice;