import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Showmarkstar from '../elements/showmarkstar.js';
import Prices from '../elements/prices.js';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

const Product_info = (data) => {
    var _data = data.datas;
    const element = <div>
        <h1>{data.product.name}</h1>
        {(_data.allow_review && data.product.reviews_count > 0) ?
            <Showmarkstar rating={data.product.average_rating} />
            : ''
        }
        <div id="product-details__prices">
            {(data.product._display_price != 0) ?
                <Prices product={data.product} datas={_data}/>
          : ''}
        </div>

        {(_data.config.product_show_manufacturer > 0 && data.product.manufacturer_info.name != '') ?
            <div className="manufacturer_name">
                {Joomla.JText._('COM_SMARTSHOP_MANUFACTURER')}: <span>{data.product.manufacturer_info.name}</span>
            </div>
            : ''}

        {(_data.config.product_show_manufacturer_logo == 1 && _data.product.manufacturer_info.manufacturer_logo != "") ?
            <div className="manufacturer_logo">
                <a href={_data.manufacturer_link}>
                    <Image src={_data.config.image_manufs_live_path + "/" + _data.product.manufacturer_info.manufacturer_logo}
                    alt={nl2br(_data.product.manufacturer_info.name)}
                    title={nl2br(_data.product.manufacturer_info.name)} />
                </a>
            </div>
        : ''}

        <div id="product-details__short-description">
            {(typeof data.product.short_description != 'undefined' && data.product.short_description != null && _data.config.product_show_short_description == 1 && data.product.short_description != '') ?
            // <p className="mb-4 text-muted">
            //     {
                    Parser(data.product.short_description)
                // }
            // </p>
           : ''}
        </div>
    </div>;

    return (element);
}

export default Product_info;