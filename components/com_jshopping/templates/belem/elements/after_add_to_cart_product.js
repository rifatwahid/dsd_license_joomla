import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';


const After_add_to_cart_product = (props) => {
    let data = props.data;
    const element =
        ((typeof data.hide_buy == 'undefined' || data.hide_buy == 0) && data.isShowCartSection == 1) ?
            <div className="tmpProductHtmlAfterAddToCart__wrapper">
                {(typeof data._tmp_product_html_after_add_to_cart != 'undefined' && data._tmp_product_html_after_add_to_cart.length > 0) ?
                    Parser(data._tmp_product_html_after_add_to_cart)
                :  ''}
            </div>
        : '';

    return (element);
}

export default After_add_to_cart_product;