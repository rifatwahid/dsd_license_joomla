import React, { useState } from '../../../js/react/node_modules/react';
import sprintf from '../../../js/react/node_modules/sprintf-js';
import Formatprice from '../elements/formatprice.js';
import Parser from '../../../js/react/node_modules/html-react-parser';

const Prices_product = (props) => {
    const product = props.product;
    const data = props.data;
    const pricesProduct = (product.product_id != null) ? product : props.product;
    let pr = '';
    if(data.totalAjaxPrice){
        pr = data.totalAjaxPrice;
    }else if(pricesProduct.product_price_calculate){
        pr = pricesProduct.product_price_calculate;
    }else{
        pr = pricesProduct.product_price;
    }
    const element = <div><a href={pricesProduct.product_link} className="text-body">
        <span className="cart-product__price">
            <Formatprice data={data} price={pr} link={data.price_format_link} />
        </span>

        {(pricesProduct.product_old_price > 0) ?
        <s className="cart-product__price-old"> (
            <Formatprice data={data} price={pricesProduct.product_old_price} link={data.price_format_link} />)
        </s>
        : ''}

        {(dataJson.show_base_price > 0) ?
        <span className="font-weight-light text-muted text-small basic_price">
             (<Formatprice data={data} price={pricesProduct.product_basic_price_calculate} link={data.price_format_link} />{(pricesProduct.product_basic_price_unit_name) ? ' / '+pricesProduct.product_basic_price_unit_name : ''})
        </span>
        : ''}

    </a>

     {(data.config.show_plus_shipping_in_product_list == 1) ?
    <div className="cart-product__plus-shipping-data">
        <span className="cart-product__plus-shipping">
            {Parser(Joomla.JText._('COM_SMARTSHOP_PLUS_SHIPPING').replace('%s', data.shippinginfo))}
        </span>
    </div>
     : ''}
     </div>;

    return (element);
}

export default Prices_product;