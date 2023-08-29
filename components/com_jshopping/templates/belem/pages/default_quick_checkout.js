import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import {Link, Redirect, useParams, useLocation, browserHistory, useHistory} from '../../../js/react/node_modules/react-router-dom';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Checkout_address from '../elements/checkout_address.js';
import Shippings from '../elements/shippings.js';
import Payments from '../elements/payments.js';
import Previewfinish from '../elements/previewfinish.js';
import Default_quick_checkout_cart from '../pages/default_quick_checkout_cart.js';
import shopQuickCheckout from '../../../js/src/controllers/qcheckout/index.js';
import { getQcheckoutData as getQcheckoutDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

const Default_quick_checkout = ({qcheckoutData, getQcheckoutData}) => {
    // let data = qcheckoutData;
    // useEffect(() => {
    //     getQcheckoutData(window.location.href + '?ajax=1&ajax=1');
    // }, []);
    let [data, setData] = useState('');
    let updateData=(value)=> {
        setData(value);
    };
    useEffect(() => {
            fetch(window.location.href + '?ajax=1&ajax=1' , {
                method: "GET",
            }) .then(res => res.json())
                .then((result) => {
                    updateData(result);
                });
        },
        []
    );
    if(data.redirect){ return <Redirect to={data.redirect}/>}
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

    if(data.component == 'Default_quick_checkout') {
        window.shopQuickCheckout.options.jshopConfig = data.config;
        shopUser.setFields(data.jsConfigFields);
        element = <div className="shop shop-checkout" id="shop-qcheckout">
            <h1 className="hidden">{Joomla.JText._('COM_SMARTSHOP_CHECKOUT')}</h1>
            <div id="qc_error" className={(data.qc_error == null) ? 'display--none' : ''}>
                {data.qc_error}
            </div>

            {/*<?php if (!empty($this->qc_error)) {*/}
            {/*    $this->session->clear('qc_error');*/}
            {/*}?>*/}

            <Form action={data.action} method="post" id="payment_form" name="quickCheckout">

                <Checkout_address data={data}/>

                {(data.jshopConfig.step_4_3 == 1 && data.delivery_step == 1) ?
                    <fieldset className="form-group">
                        <legend>
                            {Joomla.JText._('COM_SMARTSHOP_CHECKOUT_SHIPMENT')}
                        </legend>

                        <div id="qc_shippings_methods">
                            <Shippings data={data}/>
                        </div>
                    </fieldset>
                    : (data.jshopConfig.step_4_3 == 1 && data.delivery_step == 'null' && (data.active_sh_pr_method_id > 0)) ?
                        <input type="hidden" name="sh_pr_method_id" value={data.active_sh_pr_method_id}
                               id="qc_sh_pr_method_id"/>
                        : ''}

                {(data.payment_step == 1) ?
                    <fieldset className="form-group">
                        <legend>
                            {Joomla.JText._('COM_SMARTSHOP_CHECKOUT_PAYMENT')}
                        </legend>

                        <div id="qc_payments_methods">
                            <Payments data={data} />
                        </div>
                    </fieldset>
                    : (data.payment_step == 0 && data.active_payment_class != '') ?
                        <input type="radio" style="display:none;" name="payment_method"
                               value={data.active_payment_class}
                               id="qc_payment_method_class" checked/>
                        : ''}

                {(data.jshopConfig.step_4_3 == 0 && data.delivery_step > 0) ?
                    <fieldset className="form-group">
                        <legend>
                            {Joomla.JText._('COM_SMARTSHOP_CHECKOUT_SHIPMENT')}
                        </legend>

                        <div id="qc_shippings_methods">
                            <Shippings data={data}/>
                        </div>
                    </fieldset>
                    : (data.jshopConfig.step_4_3 == 0 && data.delivery_step == 1 && data.active_sh_pr_method_id > 0) ?
                        <input type="hidden" name="sh_pr_method_id" value={data.active_sh_pr_method_id}
                               id="qc_sh_pr_method_id"/>
                        : ''}

                <h4 className="pb-2 font-weight-normal">
                    {Joomla.JText._('COM_SMARTSHOP_CHECK_ORDER')}
                </h4>
                <Default_quick_checkout_cart data={data}/>
                <Previewfinish data={data}/>
            </Form>
        </div>;


    }
    return (element);
}
// export default  connect(
//     ({ qcheckoutData }) => ({ qcheckoutData: qcheckoutData.qcheckoutData }),
//     {
//         getQcheckoutData: getQcheckoutDataAction
//     }
// )(Default_quick_checkout);
export default Default_quick_checkout;
