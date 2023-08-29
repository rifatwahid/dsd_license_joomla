import React, { useState } from '../../../js/react/node_modules/react';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import { Redirect } from '../../../js/react/node_modules/react-router-dom';


const Cart_product = (props) => {
    let data = props.data;
    function oneClickCheckout() {
        jQuery('#to').val('one_click_buy');
        shopOneClickCheckout.add('"' + data.product.product_id + '"','"' + data.category_id + '"');
    }
    let [modalBody, setModalBody] = useState('');
    let setBody=(value)=> {
        setModalBody(value);
    }
    let [dataRedirect, setStatus] = useState('');
    let updateStatus=(value)=> {
        setStatus(value);
    }
    function getModalBody(){
        jQuery('#to').val('one_click_buy');
        shopOneClickCheckout.add(data.product.product_id, data.product.category_id);
        fetch('index.php?option=com_jshopping&controller=one_click_checkout&task=display&ajax=1' , {
            method: "POST",
            headers: {'Content-Type': 'text/html'},
        }) .then((result) => {
                setBody(result);
            });
    }
    function toCart(id, type) {
        event.preventDefault();
        const form = jQuery(id);
        var queryString = jQuery(id).serialize();
        let href = 'index.php?option=com_jshopping&controller=cart&task=add&to=cart&ajax=1';

        fetch(href, {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: queryString
        }).then(res => res.json())
            .then((result) => {
                if (result.count_product > 0) {
                    updateStatus(type);
                }

            });

    }
    if (dataRedirect == 1){
        return <Redirect to={data.cart_link} />;
    }
    const element =
        (typeof data.hide_buy != 'undefined' || data.hide_buy == 0) ?
            <div><Button type="submit" variant="outline-primary" className="btn btn-block btn-add-product-to-cart" onClick={(e) => {event.preventDefault();toCart('form#productForm', 1)}}>
                {Joomla.JText._('COM_SMARTSHOP_ADD_TO_CART')}
            </Button>
            {(typeof data.product.one_click_buy != 'undefined' && data.product.one_click_buy != 0 && data.user.id > 0 &&  data.page_type != 'product_list') ?
                <a href="#" class="btn btn-outline-primary btn-block btn-one-click-checkout" data-toggle="modal" data-target="#one_click_buy_window" onClick={(e) => {getModalBody();}}>
                    {Joomla.JText._('COM_SMARTSHOP_ONE_CLICK_BUY')}
                </a>
            : ''}
                <div className="modal" id="one_click_buy_window" tabIndex="-1" role="dialog" aria-labelledby=""
                     aria-hidden="true">
                    <div className="modal-dialog modal-dialog-centered one_click_buy_window__modal-dialog"
                         role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title one_click_buy_window__title">{Joomla.JText._('COM_SMARTSHOP_BUY_NOW')}</h5>

                                <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div className="modal-body">
                                {modalBody}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        : '';

    return (element);
}

export default Cart_product;