import React, { useState } from '../../../js/react/node_modules/react';
import sprintf from '../../../js/react/node_modules/sprintf-js';
import Formatprice from '../elements/formatprice.js';
import Parser from '../../../js/react/node_modules/html-react-parser';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';

const Prices = (data) => {
    var dataJson = data.datas;
    const product = data.product;
    const pricesProduct = (data.product.product_id != null) ? data.product : dataJson.product;

    const element = <div className="d-flex align-items-center justify-content-between my-4">
        <ListGroup as="ul" variant="flush" className="list-unstyled">

            {(pricesProduct.product_old_price > 0) ?
                <ListGroup.Item as="li" className="text-danger font-weight-light border-0 pl-0">
                    <del><Formatprice data={dataJson} price={pricesProduct.product_old_price} /></del>
                </ListGroup.Item>
            : ''}

            <ListGroup.Item as="li" className="h4 border-0 pl-0">
                {(dataJson.config.single_item_price) ?
                <span id="block_price">
                    <Formatprice data={dataJson} price={pricesProduct.product_price_calculate} />
                </span>
                : ''}

                {(pricesProduct.product_basic_price_show) ?
                <span className="font-weight-light text-muted text-small"> (<Formatprice data={dataJson} price={pricesProduct.product_basic_price_calculate} /> / {pricesProduct.product_basic_price_unit_name})
                </span>
                : ''}

                {(dataJson.calculatedProductPrice) ?
                    <div id="product-current-price">
                        <Formatprice calculatedProductPrice={1} data={dataJson} price={dataJson.calculatedProductPrice} />
                    </div>
                    : ''
                }

            </ListGroup.Item>

            {((dataJson.config.show_tax_in_product == 1 && pricesProduct.product_tax > 0) || dataJson.config.show_plus_shipping_in_product == 1) ?
                <ListGroup.Item as="li" className="text-muted font-weight-light border-0 pl-0 pt-0">
                    {(dataJson.isShowProductTax > 0) ?
                        <Producttaxinfo tax={pricesProduct.product_tax} link={dataJson.tax_info_link} />
                    : ''}

                    {(dataJson.config.show_plus_shipping_in_product == 1)  ?
                        Parser(Joomla.JText._('COM_SMARTSHOP_PLUS_SHIPPING').replace('%s', dataJson.shippinginfo))
                    : ''}
                </ListGroup.Item>
            : ''}

        </ListGroup>
    </div>;

    return (element);
}

export default Prices;