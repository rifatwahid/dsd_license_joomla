import React, { useState } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Printselectquantity from '../elements/printselectquantity.js';
 import uploadImage from '../../../js/src/common/upload_image/index.js';
import shopProduct from '../../../js/src/controllers/product/index.js';
import shopProductFreeAttributes from '../../../js/src/controllers/product/freeattributes.js';

const Product_list_quantity = (props) => {
    let data = props.data;
    const element = <div className="form-group row product__quantity-row"><Form.Label htmlFor="quantity" className="col-6">
        {Joomla.JText._('COM_SMARTSHOP_QUANTITY')}
    </Form.Label>
    <div className="col-6">
        {(typeof props.product.quantity_select == 'undefined' || props.product.quantity_select == '') ?
            <Form.Control type="number" name="quantity" id="quantity"
                         defaultValue={props.product.productQuantity}/>
            :
                <Form.Control as="select" className="inputbox" name="quantity" id="quantity">
                     <Printselectquantity product={props.product} link={data.printselectquantity_link} quantity_select={props.product.quantity_select} equal_steps={props.product.equal_steps} default_count_product={props.product.productQuantity} />
                </Form.Control>
            }

        {data._tmp_qty_unit}
    </div></div>;

    return (element);
}

export default Product_list_quantity;