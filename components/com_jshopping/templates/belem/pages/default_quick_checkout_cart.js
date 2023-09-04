import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Formatprice from '../elements/formatprice.js';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import Displaytotalcarttaxname from '../elements/displaytotalcarttaxname.js';
import Formattax from '../elements/formattax.js';
import Cartproduct from '../elements/cartproduct.js';

const Default_quick_checkout_cart = (props) => {
    let data = props.data;
    let tax_list = Object.keys(data.tax_list).map((key) => data.tax_list[key]);
    let percents = Object.keys(data.tax_list);
    const result = Object.keys(data.products).map((key) => data.products[key]);
    const keys = Object.keys(data.products);
    const element = <div className="checkout-cart">
        <div className="cart-products">
            <ul className="list-group">
                {(data.products != null) ?
                    result.map((prod, key_id) =>
                           <Cartproduct prod={prod} key={key_id} data={data} />
                    )
                : ''}
            </ul>
        </div>

        <div className="row my-4">
            <div className="col-md-6 col-lg-7">
                {(data.config.show_weight_order == 1 && data.formatweight > 0) ?
                    Joomla.JText._('COM_SMARTSHOP_WEIGHT') + ': ' + data.formatweight
                : '' }
            </div>

            <div className="col">
                <ListGroup as="ul" className="list-unstyled">
                    {(data.hide_subtotal != null) ?
                        <ListGroup.Item as="li" className="price_products" key={1}>
                        {Joomla.JText._('COM_SMARTSHOP_SUBTOTAL')+': '} <span
                        className="float-right"><Formatprice price={data.summ} data={data}/></span>
                    </ListGroup.Item>
                    : ''}

                    {(data.discount > 0) ?
                    <ListGroup.Item as="li" key={2}>
                        {Joomla.JText._('COM_SMARTSHOP_DISCOUNT') + ': '}<span
                        className="float-right"><Formatprice price={-data.discount} data={data}/></span>
                    </ListGroup.Item>
                    : ''}

                    {(data.free_discount > 0) ?
                        <ListGroup.Item as="li" key={3}>
                            {Joomla.JText._('COM_SMARTSHOP_DISCOUNT') + ': '}<span
                            className="float-right"><Formatprice price={data.free_discount} data={data}/></span>
                        </ListGroup.Item>
                        : ''}

                    {(data.summ_delivery != null) ?
                        <ListGroup.Item as="li" className="summ_delivery" key={4}>
                            {Joomla.JText._('COM_SMARTSHOP_SHIPPING_COSTS') + ': '}<span
                            className="float-right"><Formatprice price={data.summ_delivery} data={data}/></span>
                        </ListGroup.Item>
                    : ''}

                    {(data.summ_package != null) ?
                        <ListGroup.Item as="li" className="summ_package" key={5}>
                            {Joomla.JText._('COM_SMARTSHOP_PACKAGE_PRICE') + ': '}<span
                            className="float-right"><Formatprice price={data.summ_package} data={data}/></span>
                        </ListGroup.Item>
                    : ''}

                    {(data.summ_payment != 0) ?
                        <ListGroup.Item as="li" className="summ_payment" key={6}>
                           {data.payment_name + ': '}<span
                            className="float-right"><Formatprice price={data.summ_payment} data={data}/></span>
                        </ListGroup.Item>
                    : ''}

                    {tax_list.map((value, v) =>
                        <ListGroup.Item as="li" className="tax_list_value" >
                           <Displaytotalcarttaxname price={null} />
                                {(data.show_percent_tax == 1) ? <span><Formattax percent={percents[v]} link={data.formattax_link} />  %</span> :
                                    <span className="float-right"><Formatprice price={(value)} data={data}/></span>}
                        
                        </ListGroup.Item>

                    )}
                    {data._tmp_ext_html_after_show_total_tax}

                    <ListGroup.Item as="li" className="fullsumm" key={8}>
                        {Joomla.JText._('COM_SMARTSHOP_ORDER_TOTAL') + ': '}<span
                        className="float-right"><Formatprice price={data.fullsumm} data={data}/></span>
                    </ListGroup.Item>

                </ListGroup>
            </div>

        </div>
    </div>;

   return (element);
}
export default Default_quick_checkout_cart;
