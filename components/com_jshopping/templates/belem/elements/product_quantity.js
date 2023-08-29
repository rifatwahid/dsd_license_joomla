import React, { useState, useCallback } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Printselectquantity from '../elements/printselectquantity.js';
import shopProduct from '../../../js/src/controllers/product/index.js';

const Product_quantity = (data) => {
    var dataJson = data.data;

    const element = <div><Form.Label htmlFor="quantity" className="col p-0">
        {Joomla.JText._('COM_SMARTSHOP_QUANTITY')}
    </Form.Label>
    <div className="col-4 p-0">
        {(typeof dataJson.product.quantity_select == 'undefined' || dataJson.product.quantity_select == '') ?
            <Form.Control type="number" name="quantity" id="quantity"
                         defaultValue={dataJson.default_count_product}/>
            :
                <Form.Control as="select" className="inputbox" name="quantity" id="quantity">
                    <Printselectquantity product={dataJson.product} link={dataJson.printselectquantity_link} quantity_select={dataJson.product.quantity_select} equal_steps={dataJson.product.equal_steps} default_count_product={dataJson.product_quantity} />
                </Form.Control>
           }

        {dataJson._tmp_qty_unit}
    </div></div>;

    return (element);
}

export default Product_quantity;