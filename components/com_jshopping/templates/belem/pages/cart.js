import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import {Route, Link, Redirect, useParams, useLocation, browserHistory, useHistory} from '../../../js/react/node_modules/react-router-dom';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Formatprice from '../elements/formatprice.js';
import Producttaxinfo from '../elements/producttaxinfo.js';
import Fast_admin_links from '../elements/fast_admin_links.js';
import Isurl from '../elements/isurl.js';
import Cart_upload from '../elements/cart_upload.js';
import Printselectquantitycart from '../elements/printselectquantitycart.js';
import Displaytotalcarttaxname from '../elements/displaytotalcarttaxname.js';
import Formattax from '../elements/formattax.js';
import Form_create_offer from '../elements/form_create_offer.js';
import Sprint_atribute  from '../elements/sprint_atribute.js';
import uploadImage from '../../../js/src/common/upload_image/index.js';
import { getCartData as getCartDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";

const Cart = ({cartData, getCartData}) => {
    var data = cartData;
    var key_id = 0;
    useEffect(() => {
        getCartData(window.location.href + '?ajax=1&ajax=1');
    }, []);

    const strTrim = (str) => {
        if(str == 'undefined') {
            return str.trim();
        }
        return '';
    }
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

    if(data.component){
    let tax_list = Object.keys(data.tax_list).map((key) => data.tax_list[key]);
    let percents = Object.keys(data.tax_list);

    const handleSubmit = event => {
        event.preventDefault();
        var queryString = jQuery('#updateCartForm').serialize();
        fetch(data.refresh_link + '?rajax=1' + '&rajax=1', {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: queryString
        }) .then(res => res.json())
            .then((result) => {
                getCartData(window.location.href + '?ajax=1&ajax=1');
            });
    }
    const result = Object.keys(data.products).map((key) => data.products[key]);
    const keys = Object.keys(data.products);
    let urlToThumbImage = '';
    let ids = 0;
    const isUrl = (thumb_image) => {
       var pattern = new RegExp('^(https?:\\/\\/)?'+
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+
            '((\\d{1,3}\\.){3}\\d{1,3}))'+
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+
            '(\\?[;&a-z\\d%_.~+=-]*)?'+
            '(\\#[-a-z\\d_]*)?$','i');
        return pattern.test(thumb_image);
    }
    jQuery(document).ready(() => {
       jQuery('.goToCheckout').click(function(){
           return shopCart.validateUploadedImagesInCart();
       });
    });
    function removeCart(link){
        event.preventDefault();
        fetch(link + '?rajax=1' + '&rajax=1', {
            method: "GET",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }) .then(res => res.json())
            .then((result) => {
                if(result){
                    getCartData(window.location.href + '?ajax=1&ajax=1');
                }
            });
    }
    function setDiscount(){
        event.preventDefault();
        var queryString = jQuery('form[name=rabatt]').serialize();
        fetch(data.discountsave_link + '?rajax=1' + '&rajax=1', {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: queryString
        }) .then(res => res.json())
            .then((result) => {
                if(result){
                    getCartData(window.location.href + '?ajax=1&ajax=1');
                }
            });
    }
    var cl = (data.config_address_fields == 1 || data.config_register_fields == 1) ? 'col-md-6 col-lg-6 pl-0' : 'col';
    element = <div className="shop shop-cart" id="comjshop">
        <h1 className="hidden">{Joomla.JText._('COM_SMARTSHOP_CART')}</h1>
        {(Object.keys(data.products).length > 0) ?
            <div>
        <Form action={data.refresh_link}
              id="updateCartForm" method="post" name="updateCart">

            <ListGroup as="ul" variant="flush" className="list-group cart-items">
                {result.map((prod, i) =>
                    <ListGroup.Item as="li" key={i} className="cart-items__item border" id={"cartProduct"+keys[i]}>
                        <div className="row">
                            <div className="col-sm-3">
                                <Link to={prod['href']}>
                                    <Image className="img-fluid img-cart" src={prod['image']}
                                               alt={nl2br(prod['product_name'])}/>
                                </Link>
                            </div>

                            <div className="col">
                                <div className="media-body row">
                                    <div className="col-md-6 col-lg-7">
                                        <ul variant="flush" className="list-unstyled">

                                            <li className="font-weight-bold form-control border-0 pl-0">
                                                <Link className="text-body" to={prod['href']}>
                                                    {prod['product_name']}
                                                </Link>
                                            </li>

                                            {(data.config.show_product_code_in_cart == 1 && prod['ean'] != "" ) ?
                                                <li className="text-muted small border-0 m-0 p-0">
                                                    {Joomla.JText._('COM_SMARTSHOP_PRODUCT_CODE')}:
                                                    {prod['ean']}
                                                </li>
                                            : ''}

                                            { (data.config.show_product_manufacturer_in_cart == 1 && prod['manufacturer_info']['name'] != null) ?
                                            <li className="manufacturer_name  border-0 m-0 p-0">
                                                {Joomla.JText._('COM_SMARTSHOP_MANUFACTURER')}:
                                                <span>{prod['manufacturer_info']['name']}</span>
                                            </li>
                                            : ''}

                                            {(prod['manufacturer'] != '') ?
                                            <li className="text-muted small  border-0 m-0 p-0">
                                                {Joomla.JText._('COM_SMARTSHOP_MANUFACTURER')}:
                                                {prod['manufacturer']}
                                            </li>
                                            : ''}
                                            {(prod['editor_attr'].length > 0) ?
                                                <li className="list_attribute  border-0 m-0 p-0">
                                                    prod['editor_attr'].map((val, ind){
                                                        <p className="jshop_cart_attribute"><span className="name">{val}</span></p>
                                                     } )
                                                </li>
                                            : ''}
                                            {(prod.attributes_display != null || prod._mirror_editor_display.length > 0 || prod.free_attributes_display.length > 0 || prod.extra_fields_display.length > 0) ?
                                                <li className="text-muted small  border-0 m-0 p-0">
                                                    <Sprint_atribute atribute={prod.attributes_display} />
                                                    {(prod._mirror_editor_display.length > 0) ? Parser(prod._mirror_editor_display) : ''}
                                                    {(prod.free_attributes_display.length > 0) ? Parser(prod.free_attributes_display) : ''}
                                                    {(prod.extra_fields_display.length > 0) ? Parser(prod.extra_fields_display) : ''}
                                                </li>
                                            : ''}
                                            {(data.config.show_delivery_time_step5 == 1 && prod['delivery_times_id'] != 0) ?
                                            <li className="text-muted small border-0 m-0 p-0">
                                                {Joomla.JText._('COM_SMARTSHOP_DELIVERY_TIME')}: {data.deliverytimes[prod['delivery_times_id']]}
                                            </li>
                                            : ''}

                                            {(data.production_time != null && prod['production_time'] > 0) ?
                                                <li className="text-small text-muted border-0 m-0 p-0">
                                                    {Joomla.JText._('COM_SMARTSHOP_PRODUCTION_TIME')}: {prod['production_time']+' '+Joomla.JText._('COM_SMARTSHOP_DAYS') }
                                                </li>
                                            : '' }
                                            <li className="border-0 m-0 p-0" >
                                               <Cart_upload prod={prod} data={data} key_id={keys[i]}/>
                                            </li>

                                                {(prod['is_product_from_editor'] == 1) ?
                                                    <li className="list-group-itemcart-item__back-to-editor border-0 m-0 p-0">
                                                        <Link to={prod['href']}
                                                           className="cart-item__back-to-editor--href">
                                                              {Joomla.JText._('COM_SMARTSHOP_BACK_TO_DESIGNING_YOUR_PRODUCT')}
                                                        </Link>
                                                    </li>
                                                : ''}
                                        </ul>
                                    </div>
                                    <div className="col-md-3 col-lg-2 text-center">
                                        <div className="d-flex align-items-center flex-md-column">
                                            <div className="form-group flex-fill mb-0">
                                                <Form.Label htmlFor={"quantity[" + keys[i] + "]"} className="sr-only">
                                                    {Joomla.JText._('COM_SMARTSHOP_QUANTITY')}
                                                </Form.Label>

                                                {(typeof prod['quantity_select'] == 'undefined' || prod['quantity_select'] == '') ?
                                                    <Form.Control type="number" name={"quantity[" + keys[i] + "]"}
                                                           id={"quantity[" + keys[i] + "]"}
                                                           defaultValue={prod['quantity']}
                                                           onBlur ={(e) => uploadImage.updateQuantityWhenChangeProductQuantity(keys[i], e.target)}
                                                           className="form-control text-center"/>
                                                    :  <Printselectquantitycart data={data} quantity_select={prod['quantity_select']} product_id={prod['product_id']} default_count_product={prod['quantity']} name = {"quantity["+keys[i]+"]"} key_id={keys[i]} />
                                                }
                                            </div>

                                            <div className="cart-item-action flex-fill px-4 mt-md-2">
                                                <a href="#" onClick={handleSubmit}
                                                   className="text-primary d-block mb-2">
                                                    {Joomla.JText._('COM_SMARTSHOP_UPDATE')}
                                                </a>

                                                <a href=""
                                                   onClick={(e) => {if(window.confirm(Joomla.JText._('COM_SMARTSHOP_REMOVE_CONFIRM'))){e.preventDefault();
                                                       removeCart(prod['href_delete']);}else{e.preventDefault();}}}
                                                   className="text-danger d-block">
                                                    {Joomla.JText._('COM_SMARTSHOP_REMOVE')}
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                    <div className="col-md-3 form-control border-0 text-md-right smartshop_cart_price_tax_cell">
                                        <span className="d-block">
                                            <Formatprice price={(prod['price1']) ? prod['price1'] : prod['total_price']} link={data.price_format_link} data={data}/>
                                        </span>

                                        {(data.config.show_tax_product_in_cart == 1) ?
                                            <span>
                                            <span className="d-block mt-1">
                                                <Producttaxinfo tax={prod['tax']} link={data.tax_info_link} />
                                            </span>
                                            {data._tmp_ext_html_after_show_product_tax}
                                            </span>
                                            : ''
                                        }
                                        <span
                                            className={(!data.config.single_item_price || data.config.single_item_price == 0) ? "small text-muted hidden" : "small text-muted" }>
                                            <Formatprice price={(prod['price']) ? prod['price'] : prod['aprice']} link={data.price_format_link} data={data}/>
									    </span>
                                        {(data.config.show_tax_product_in_cart = 1 && data.config.single_item_price == 1) ?
                                            <span className="small text-muted d-block mt-1">
                                                <Producttaxinfo tax={prod['tax']} link={data.tax_info_link} />
                                                {data._tmp_ext_html_after_show_product_tax_single_item_price}
                                            </span>
                                        : '' }
                                        {(data.config.cart_basic_price_show == 1 && prod['basicprice'] > 0 && data.config.config.single_item_price == 1) ?
                                        <span className="small text-muted d-block mt-1">
                                            <Sprintbasicprice prod={prod} link={data.sprintbasicprice_link} />
										</span>
                                         : '' }
                                    </div>


                                </div>
                            </div>
                        </div>
                    </ListGroup.Item>
                )
                }

            </ListGroup>
            <Fast_admin_links />
        </Form>

                <div className="row my-4">
                    <div className="col-md-6 col-lg-7">
                        {(data.use_rabatt == 1) ?
                        <Form id="discount cartDiscountForm" name="rabatt" method="post"
                              action={data.discountsave_link}>
                            <div className="form-row align-items-center">
                                <label htmlFor="rabatt" className="h6 pl-4 pt-3 mb-3">
                                    {Joomla.JText._('COM_SMARTSHOP_HAVE_A_DISCOUNT_CODE')}
                                </label>

                                <div className="col-md-8 mb-4">
                                    <Form.Control type="text" className="form-control mt-1" id="rabatt" name="rabatt"
                                           placeholder={Joomla.JText._('COM_SMARTSHOP_CODE')} />
                                </div>

                                <div className="col-md-4 mb-4">
                                    <Button  variant="outline-primary" type="submit" className="btn-block mt-1" onClick={(e) => setDiscount()}>
                                        {Joomla.JText._('COM_SMARTSHOP_APPLY_DISCOUNT')}
                                    </Button>
                                </div>
                            </div>
                        </Form>
                        : ''}
                    </div>

                    <div className="col cart-calculation-block">
                        <ul className="list-unstyled">

                            {(data.hide_subtotal == 0) ?
                            <li className="list-group-item subtotal" key={1}>
                                {Joomla.JText._('COM_SMARTSHOP_SUBTOTAL')}: <span
                                className="float-right"><Formatprice price={data.summ} link={data.price_format_link}  data={data}/></span>
                            </li>
                            : ''}
                            {(data.config.show_shipping_costs_in_cart == 1 && typeof data.summ_delivery != 'undefined') ?
                                <li className="list-group-item summ_delivery" key={2}>
                                    {Joomla.JText._('COM_SMARTSHOP_SHIPPING_COSTS')}: <span className="float-right">{data.summ_delivery}</span>
                                </li>
                                : ''}

                            {(data.discount > 0) ?
                                <li className="list-group-item discount" key={3}>
                                    {Joomla.JText._('COM_SMARTSHOP_DISCOUNT')}:
                                    <span className="float-right"><Formatprice price={-data.discount} link={data.price_format_link}  data={data}/></span>
                                </li>
                            : ''}

                            {(data.free_discount > 0) ?
                                <li className="list-group-item free_discount" key={4}>
                                    {Joomla.JText._('COM_SMARTSHOP_DISCOUNT')}: <span
                                  className="float-right"><Formatprice price={-data.free_discount} link={data.price_format_link}  data={data}/></span>
                                </li>
                            : '' }

                            {(data.config.hide_tax == 0) ?
                                tax_list.map((value, v) =>
                                <li className="list-group-item tax_list_value" key={5}>
                                    <Displaytotalcarttaxname price={null} />
                                    {(data.show_percent_tax == 1) ? <span> <Formattax percent={percents[v]} link={data.formattax_link} /> %:
                                        <span className="float-right"><Formatprice price={value} link={data.price_format_link}  data={data}/></span></span> : ''}
                                </li>
                               ) : ''}

                            {data._tmp_ext_html_after_show_total_tax}
                            <li  className="list-group-item fullsumm" key={6}>
                                {Joomla.JText._('COM_SMARTSHOP_ORDER_TOTAL')}: <span
                                className="float-right"><Formatprice price={(data.fullsumm)} link={data.price_format_link}  data={data}/></span>
                            </li>

                        </ul>
                        {(data.config.show_shipping_costs_in_cart == 1) ?
                        <Form name="shipping_cart" action="">
                            <div className="row pt-2">
                                <div className="col-md-5 col-lg-5 align-middle pt-1">
                                    {Joomla.JText._('COM_SMARTSHOP_SHIPPING_FOR')}
                                </div>

                                <div className="col-md-7 col-lg-7 pl-0">
                                    <div className="row">
                                        <div className={cl}  dangerouslySetInnerHTML={{__html:data.select_countries}} />
                                        {(data.config_address_fields == 1 || data.config_register_fields == 1) ?
                                        	<div className="col-md-6 col-lg-6  pl-0">
                                                <div>
                                                    <input type="text" name="state" id="state"
                                                           placeholder={Joomla.JText._('COM_SMARTSHOP_STATE')}
                                                           defaultValue={data.user.state}
                                                           onBlur={(e) => shopCart.getShippingPrice("country", jQuery("#country").val(), e.target.value())}
                                                           className="input"/>
                                                </div>
                                            </div>
                                            : ''}
                                    </div>
                                </div>
                            </div>
                        </Form>
                            : ''}
                    </div>
                </div>
                
                <Form_create_offer data={data} />
                <Link className="btn btn-outline-primary my-4 float-sm-right goToCheckout"
                   to={data.href_checkout+'&rajax=1'}>
                    {Joomla.JText._('COM_SMARTSHOP_GO_TO_CHECKOUT')}
                </Link>

                {(data._tmp_ext_html_before_discount) ? Parser(data._tmp_ext_html_before_discount) : ''}

            </div>
            :
            <p>
                {Joomla.JText._('COM_SMARTSHOP_CART_IS_EMPTY')}
            </p>
        }


    </div>;


    }
    return (element);
}
export default  connect(
    ({ cartData }) => ({ cartData: cartData.cartData }),
    {
        getCartData: getCartDataAction
    }
)(Cart);

