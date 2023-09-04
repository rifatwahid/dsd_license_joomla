import React, { useState } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Checkout_address from '../elements/checkout_address.js';
import Shippings from '../elements/shippings.js';
import Payments from '../elements/payments.js';
import Previewfinish from '../elements/previewfinish.js';
import Default_quick_checkout_cart from '../pages/default_quick_checkout_cart.js';
import shopQuickCheckout from '../../../js/src/controllers/qcheckout/index.js';
import shopUserAddressesPopup from '../../../js/src/controllers/user/useraddressespopup.js';

const Default_one_click_checkout = () => {
    if(typeof dataJsonPopup == 'undefined' || dataJsonPopup == null){
        dataJsonPopup = Parser(jQuery('.dataJsonPopup').text());
    }
    var dataJson = dataJsonPopup;
    if (dataJson.ac_paym_method.payment_class != '') {
        jQuery(document).ready(function () {
            shopQuickCheckout.showPayment(dataJson.ac_paym_method.payment_class);
        });
    }
    let addresses = {'street' : [
            dataJson.user.street.trim(),
            dataJson.user.street_nr.trim()
        ],
        'city': [
            dataJson.user.zip.trim(),
            dataJson.user.city.trim(),
        ],
        'country' : [
            dataJson.user.country.trim()
        ]
    };
    function isThereAtLeastOneNotEmpty(arr) {
        let isThereAtLeastOneNotEmpty = false;

        if (typeof arr != 'undefined' && arr.length > 0) {
            arr.forEach(function (item) {
                    if(item != 'null') {
                        isThereAtLeastOneNotEmpty = true;
                    }
                }
            );
        }

        return isThereAtLeastOneNotEmpty;
    }
    if (dataJson.ac_paym_method.payment_class != '') {
        jQuery(document).ready(function () {
            shopQuickCheckout.showPayment(dataJson.ac_paym_method.payment_class);
        });
    }
    jQuery(document).ready(() => {
        jQuery('#payment_form').submit(function(e){
            return shopQuickCheckout.onSubmitForm(e.target, parseInt(dataJson.isUserAuthorized))
        });
        jQuery('#payment_form').change(function(e){
            shopQuickCheckout._refreshData()
        });
    });

    const element = '';
    //     <div className="shop shop-checkout one_click_checkout" id="shop-qcheckout" style="position:relative;">
    //     <h5>{Joomla.JText._('COM_SMARTSHOP_BUY_NOW')}: {dataJson.cart.products[0]['product_name']}</h5>
    //
    //     <h1 className="hidden">{Joomla.JText._('COM_SMARTSHOP_CHECKOUT')}</h1>
    //     <div id="qc_error" className={(dataJson.qc_error == 'null') ? 'display--none' : ''}>
    //         {dataJson.qc_error}
    //     </div>
    //
    //
    //     <Form action={dataJson.action} method="post" id="payment_form" name="quickCheckout" >
    //         <ul className="list-group cart-items">
    //
    //             <li className="list-group-item" id="cartProduct" data-toggle="modal" data-target="#delivery_step">
    //                 <div className="row" id="delivery_block">
    //
    //                     <div className="col-sm-3">
    //                         <Image className="img-fluid img-cart" width="80" src={urlToThumbImage}
    //                              alt={prod.name} />
    //                     </div>
    //
    //                     <div className="col">
    //                         {Joomla.JText._('COM_SMARTSHOP_CHECKOUT_SHIPMENT')}:
    //                         <span className="shipping_name">{dataJson.active_shipping_name}</span>
    //                     </div>
    //                 </div>
    //
    //             </li>
    //             <li className="list-group-item billingAddress" id="billingAddress__changeAddress"
    //                 onClick={() => {shopOneClickCheckout.openNav('checkout_address_step');shopUserAddressesPopup.setAddressTypeToHandler('billing')}}>
    //                 <div id="qc_address">
    //                     <div className="row">
    //                         <div className="col-sm-3">
    //                             {Joomla.JText._('COM_SMARTSHOP_BILL_ADDRESS')}
    //                         </div>
    //                         <div className="col" id="">
    //                             <p className="billingAddress__name">
    //                                 {dataJson.user.f_name + ' ' + dataJson.user.l_name}
    //                             </p>
    //
    //                             <p className="billingAddress__addresses">
    //         <span className="billingAddress__street">
	// 			{dataJson.user.street}
    //         </span>
    //
    //                                 <span className="billingAddress__street_nr">
	// 			{dataJson.user.street_nr}
	// 		</span>
    //
    //                                 {(isThereAtLeastOneNotEmpty(addresses['street']) && (isThereAtLeastOneNotEmpty(addresses['city'])  || isThereAtLeastOneNotEmpty(addresses['country']))) ?
    //                                     <span className="address-comma">,</span>
    //                                     : ''}
    //
    //                                 <span className="billingAddress__zip">
	// 			{dataJson.user.zip}
	// 		</span>
    //
    //                                 <span className="billingAddress__city">
	// 			{dataJson.user.city}
	// 		</span>
    //
    //                                 {(isThereAtLeastOneNotEmpty(addresses['country']) && (isThereAtLeastOneNotEmpty(addresses['city'])  || isThereAtLeastOneNotEmpty(addresses['street']))) ?
    //                                     <span className="address-comma">,</span>
    //                                     : ''}
    //
    //                                 <span className="billingAddress__country">
	// 			{dataJson.user.country}
	// 		</span>
    //                             </p>
    //                         </div>
    //
    //                         <input type="hidden" name="billingAddress_id"
    //                                value={dataJson.user.address_id} />
    //                     </div>
    //                     </div>
    //             </li>
    //             <li className="list-group-item shippingAddress" id="shippingAddress__changeAddress"
    //                 onClick={() => {shopOneClickCheckout.openNav('checkout_address_step');shopUserAddressesPopup.setAddressTypeToHandler('shipping')}}>
    //                 <div id="qc_address">
    //                     <div className="row">
    //                         <div className="col-sm-3">
    //                             {Joomla.JText._('COM_SMARTSHOP_SHIPPING_ADDRESS')}
    //                         </div>
    //                         <div className="col" id="">
    //                             <p className="shippingAddress__name">
    //                                 {dataJson.user.f_name + ' ' + dataJson.user.l_name}
    //                             </p>
    //
    //                             <p className="shippingAddress__addresses">
    //
	// 		<span className="shippingAddress__street">
	// 			{dataJson.user.street}
	// 		</span>
    //
    //                                 <span className="shippingAddress__street_nr">
	// 			{dataJson.user.street_nr}
	// 		</span>
    //
    //                                 {(isThereAtLeastOneNotEmpty(addresses['street']) && (isThereAtLeastOneNotEmpty(addresses['city'])  || isThereAtLeastOneNotEmpty(addresses['country']))) ?
    //                                     <span className="address-comma">,</span>
    //                                     : ''}
    //
    //                                 <span className="shippingAddress__zip">
	// 			{dataJson.user.zip}
	// 		</span>
    //
    //                                 <span className="shippingAddress__city">
	// 			{dataJson.user.city}
	// 		</span>
    //
    //                                 {(isThereAtLeastOneNotEmpty(addresses['country']) && (isThereAtLeastOneNotEmpty(addresses['city'])  || isThereAtLeastOneNotEmpty(addresses['street']))) ?
    //                                     <span className="address-comma">,</span>
    //                                     : ''}
    //
    //                                 <span className="shippingAddress__country">
	// 			{dataJson.user.country}
	// 		</span>
    //                             </p>
    //                             <input type="hidden" name="shippingAddress_id"
    //                                    value={dataJson.user.address_id} />
    //
    //                         </div>
    //                     </div>
    //                     </div>
    //
    //
    //             </li>
    //             <li className="list-group-item" onClick={() => shopOneClickCheckout.openNav('payment_step') }>
    //                 <div className="row">
    //                     <div className="col-sm-3">
    //                         {Joomla.JText._('COM_SMARTSHOP_CHECKOUT_PAYMENT')}
    //                     </div>
    //                     <div className="col" id="payment_name">
    //                         {dataJson.active_payment_name}
    //                     </div>
    //                 </div>
    //             </li>
    //             <li className="list-group-item" onClick={() => shopOneClickCheckout.openNav('total_step')}>
    //                 <div className="row">
    //                     <div className="col-sm-3">
    //                         {Joomla.JText._('COM_SMARTSHOP_ORDER_TOTAL')}
    //                     </div>
    //                     <div className="col" id="fullsumm">
    //                         <Formatprice price={dataJsonCart.fullsumm} />
    //                     </div>
    //                 </div>
    //             </li>
    //
    //         </ul>
    //         <div className="overlay" id="payment_step">
    //             {(dataJson.payment_step == 1) ?
    //                 <fieldset className="form-group">
    //                     <div className="_back mb-3 pt-3 pb-3 pl-1 btn btn-link pl-0"
    //                          onClick={() => shopOneClickCheckout.closeNav('payment_step') }>
    //                          {'< ' + Joomla.JText._('COM_SMARTSHOP_CHECKOUT_PAYMENT')}
    //                     </div>
    //                    <div id="qc_payments_methods">
    //                         {/*<One_click_payments />*/}
    //                     </div>
    //                 </fieldset>
    //                 : (dataJson.payment_step == 0 && dataJson.active_payment_class != '') ?
    //                     <input type="hidden" name="payment_method" value={dataJson.active_payment_class}
    //                            id="qc_payment_method_class"/>
    //                     : ''}
    //         </div>
    //         <div className="overlay" id="delivery_step">
    //             {(dataJson.jshopConfig.step_4_3 == 0 && dataJson.delivery_step > 0) ?
    //                 <fieldset className="form-group">
    //                     <div className="_back mb-3 pt-3 pb-3 pl-1 btn btn-link pl-0"
    //                          onClick={() => shopOneClickCheckout.closeNav('delivery_step')}>
    //                         {'< ' + Joomla.JText._('COM_SMARTSHOP_CHECKOUT_SHIPMENT')}
    //                     </div>
    //
    //                     <div id="qc_shippings_methods">
    //                         {/*<One_click_shippings />*/}
    //                     </div>
    //                 </fieldset>
    //                 : (dataJson.jshopConfig.step_4_3 == 0 && dataJson.delivery_step == 1 && dataJson.active_sh_pr_method_id > 0) ?
    //                     <input type="hidden" name="sh_pr_method_id" value={dataJson.active_sh_pr_method_id}
    //                            id="qc_sh_pr_method_id"/>
    //                     : ''}
    //
    //         </div>
    //
    //
    //         <div className="overlay" id="total_step">
    //             <div onClick={() => shopOneClickCheckout.closeNav('total_step') }
    //                  className="_back mb-3 pt-3 pb-3 pl-1 btn btn-link pl-0">
    //                 {'< ' + Joomla.JText._('COM_SMARTSHOP_ORDER_TOTAL')}
    //             </div>
    //             <ListGroup as="ul" className="list-unstyled">
    //                 {(dataJsonCart.hide_subtotal != null) ?
    //                     <ListGroup.Item as="li" className="price_products">
    //                         {Joomla.JText._('COM_SMARTSHOP_SUBTOTAL')+': '} <span
    //                         className="float-right"><Formatprice price={dataJsonCart.summ} /></span>
    //                     </ListGroup.Item>
    //                     : ''}
    //
    //                 {(dataJsonCart.discount > 0) ?
    //                     <ListGroup.Item as="li">
    //                         {Joomla.JText._('COM_SMARTSHOP_DISCOUNT') + ': '}<span
    //                         className="float-right"><Formatprice price={-dataJsonCart.discount} /></span>
    //                     </ListGroup.Item>
    //                     : ''}
    //
    //                 {(dataJsonCart.free_discount > 0) ?
    //                     <ListGroup.Item as="li">
    //                         {Joomla.JText._('COM_SMARTSHOP_DISCOUNT') + ': '}<span
    //                         className="float-right"><Formatprice price={dataJsonCart.free_discount} /></span>
    //                     </ListGroup.Item>
    //                     : ''}
    //
    //                 {(dataJsonCart.summ_delivery != null) ?
    //                     <ListGroup.Item as="li" className="summ_delivery">
    //                         {Joomla.JText._('COM_SMARTSHOP_SHIPPING_COSTS') + ': '}<span
    //                         className="float-right"><Formatprice price={dataJsonCart.summ_delivery} /></span>
    //                     </ListGroup.Item>
    //                     : ''}
    //
    //                 {(dataJsonCart.summ_package != null) ?
    //                     <ListGroup.Item as="li" className="summ_package">
    //                         {Joomla.JText._('COM_SMARTSHOP_PACKAGE_PRICE') + ': '}<span
    //                         className="float-right"><Formatprice price={dataJsonCart.summ_package} /></span>
    //                     </ListGroup.Item>
    //                     : ''}
    //
    //                 {(dataJsonCart.summ_payment != 0) ?
    //                     <ListGroup.Item as="li" className="summ_payment">
    //                         {dataJsonCart.payment_name + ': '}<span
    //                         className="float-right"><Formatprice price={dataJsonCart.summ_payment} /></span>
    //                     </ListGroup.Item>
    //                     : ''}
    //
    //                 {tax_list.map((value, v) =>
    //                     <ListGroup.Item as="li">
    //                         <Displaytotalcarttaxname price={null} />
    //                         {(dataJson.show_percent_tax == 1) ? <span><Formattax percent={percents[v]} link={dataJsonCart.formattax_link} />  %</span> :
    //                             <span className="float-right"><Formatprice price={(value)} /></span>}
    //                     </ListGroup.Item>
    //                 )}
    //                 {dataJsonCart._tmp_ext_html_after_show_total_tax}
    //
    //                 <ListGroup.Item as="li" className="fullsumm">
    //                     {Joomla.JText._('COM_SMARTSHOP_ORDER_TOTAL') + ': '}<span
    //                     className="float-right"><Formatprice price={dataJsonCart.fullsumm} /></span>
    //                 </ListGroup.Item>
    //
    //             </ListGroup>
    //         </div>
    //
    //         <Previewfinish />
    //     </Form>
    // </div>;

   return (element);
}
export default Default_one_click_checkout;
