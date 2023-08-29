import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link,
    Redirect,
    useParams,
    useLocation, browserHistory, useHistory
} from '../../../js/react/node_modules/react-router-dom';
import Product_default from '../../../templates/belem/pages/product_default';
import ListGroup from '../../../js/react/node_modules/react-bootstrap/ListGroup';
import nl2br from '../../../js/react/node_modules/react-nl2br';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Formatprice from '../elements/formatprice.js';
import Isurl from '../elements/isurl.js';
import Showmarkstar from '../elements/showmarkstar.js';
import { getWishlistData as getWishlistDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";

const Wishlist = ({wishlistData, getWishlistData}) => {
    var data = wishlistData;
    let urlToThumbImage = '';
    let isUrl = 0;
    let ids = 0;
    let thumb_image;

    useEffect(() => {
        getWishlistData(window.location.href + '&ajax=1?ajax=1');
    }, []);

    let [dataSave, setStatus] = useState('');
    let updateStatus=(value)=> {
        setStatus(value);
    }
    function handleDelete(href) {
        fetch(href  + '&rajax=1', {
            method: "GET",
        }) .then(res => res.json())
            .then((result) => {
                getWishlistData(window.location.href + '&ajax=1?ajax=1');
            });
    }
    function toCart(href) {
        fetch(href  + '&rajax=1', {
            method: "GET",
        }) .then(res => res.json())
            .then((result) => {
                if(result == 1){ updateStatus(1); }
            });
    }

    if (dataSave) return <Redirect to={data.href_to_cart} />;
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
    if(typeof data.component != 'undefined') {
        let result = Object.keys(data.products).map((key) => data.products[key]);
        let keys = Object.keys(data.products);

        element = <div className="shop wishlist">
            <h1 className="wishlist__page-title">{Joomla.JText._('COM_SMARTSHOP_WISHLIST')}</h1>

            <div className="row">
                {(result.length > 0) ?

                    result.map((prod, i) =>

                        <div className="col-sm-6 col-md-4 col-lg-3 card-group mb-5" key={i}>
                            <div className="card">
                                <Link to={prod['href']}>
                                    <Image className="img-fluid img-cart" src={prod['image']}
                                           alt={nl2br(prod['product_name'])}/>
                                </Link>

                                <div className="card-body text-body">
                                    <Link to={prod['href']} className="text-body">
                                        <h5 className="card-title">{prod['product_name']}</h5>
                                    </Link>

                                    {(prod['manufacturer'] != '') ?
                                        <p className="card-text text-muted small">{prod['manufacturer']}</p>
                                        : ''}
                                    {(prod.attributes_display.length > 0 || prod._mirror_editor_display.length > 0 || prod.free_attributes_display.length > 0 || prod.extra_fields_display.length > 0) ?
                                        <ListGroup.Item as="li"
                                                        className="list-group-item text-muted small  border-0 m-0 p-0">
                                            {(prod.attributes_display.length > 0) ? Parser(prod.attributes_display) : ''}
                                            {(prod._mirror_editor_display.length > 0) ? Parser(prod._mirror_editor_display) : ''}
                                            {(prod.free_attributes_display.length > 0) ? Parser(prod.free_attributes_display) : ''}
                                            {(prod.extra_fields_display.length > 0) ? Parser(prod.extra_fields_display) : ''}
                                        </ListGroup.Item>
                                        : ''}

                                    <span
                                        className="d-block text-muted small mb-2">{Joomla.JText._('COM_SMARTSHOP_QUANTITY')}: {prod['quantity']}{prod['_qty_unit']}</span>

                                    <Link to={prod['href']} className="text-body">
                                        <Formatprice price={prod['price']} data={data} link={data.price_format_link}/>
                                    </Link>

                                    {(prod['reviews_count'] > 0) ?
                                        <Showmarkstar rating={prod['average_rating']}/>
                                        : ''}

                                </div>

                                <div className="mx-auto w-100 p-3 m-3">
                                    <a className="btn btn-outline-danger btn-block mb-3"
                                       href=""
                                       onClick={(e) => {
                                           if (window.confirm(Joomla.JText._('COM_SMARTSHOP_MODAL_REMOVE_ITEM_FROM_WISHLIST'))) {
                                               e.preventDefault();
                                               handleDelete(prod['href_delete']);
                                           }
                                       }}>{Joomla.JText._('COM_SMARTSHOP_REMOVE')}</a>
                                    <a className="btn btn-outline-primary btn-block"
                                       href="" onClick={(e) => {e.preventDefault();toCart(prod['remove_to_cart'])}}>{Joomla.JText._('COM_SMARTSHOP_ADD_TO_CART')}</a>
                                </div>

                            </div>
                        </div>
                    )


                    :
                    <p>{Joomla.JText._('COM_SMARTSHOP_WISHLIST_EMPTY')}</p>
                }
            </div>
        </div>;
    }
   return (element);
}
export default  connect(
    ({ wishlistData }) => ({ wishlistData: wishlistData.wishlistData }),
    {
        getWishlistData: getWishlistDataAction
    }
)(Wishlist);
