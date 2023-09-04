import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link,
    useParams,
    browserHistory,
    useLocation
} from '../../../js/react/node_modules/react-router-dom';
import App from '../../../js/react/src/App';
import Listorder from '../pages/listorder';
import Order from '../pages/order';
import Login from '../pages/login';
import Register from '../pages/register';
import Addresses from '../pages/addresses';
import Newaddress from '../pages/newaddress';
import Editaddress from '../pages/editaddress';
import Myaccount from '../pages/myaccount';
import Groupsinfo from '../pages/groupsinfo';
import Logout from '../pages/logout';
import Addressesmodal from '../pages/addresses_modal';
import Cart from '../pages/cart';
import Wishlist from '../pages/wishlist';
import Category_default from '../pages/category_default';
import Maincategory from '../pages/maincategory';
import Manufacturers from '../pages/manufacturers';
import Products_manufacturer from '../pages/products_manufacturer';
import Products from '../pages/products';
import Product_default from '../pages/product_default';
import Created_offer_and_order from '../pages/created_offer_and_order';
import Myoffer_and_order from '../pages/myoffer_and_order';
import Form_search from '../pages/form_search';
import Noresult_search from '../pages/noresult_search';
import Products_search from '../pages/products_search';
import Default_quick_checkout from '../pages/default_quick_checkout';
import Finish from '../pages/finish';
import Wishlist_btn from '../elements/wishlist_btn';
import Sprint_atribute from '../elements/sprint_atribute';
import Default_one_click_checkout from '../pages/_default_one_click_checkout';
import Newroute from '../pages/newroute';

 const Routes = () => {
    let [links, setLinks] = useState('');
    let updateData=(value)=> {
        setLinks(value);
    }
    useEffect(() => {
        fetch(dataJson.generatePathLink , {
            method: "GET",
        }) .then(res => res.json())
            .then((result) => {
                updateData(result);
            });
        },
        []
    );
    let element = '';
    if(links.cart) {
        element = <Route  component={App}>
            <Route exact component={Cart} path={links.cart}/>
        <Route exact component={Category_default} path={links.category_view}/>
        <Route exact component={Maincategory} path={links.category}/>
        <Route exact component={Manufacturers} path={links.manufacturer}/>
        <Route exact component={Products_manufacturer} path={links.manufacturer_view}/>
        <Route exact component={Created_offer_and_order} path={links.offer_created}/>
        <Route exact component={Myoffer_and_order} path={links.myoffer_and_order}/>
        <Route exact component={Product_default} path={links.product}/>
        <Route exact component={Products} path={links.products}/>
        <Route exact component={Products} path={links.tophits}/>
        <Route exact component={Products} path={links.toprating}/>
        <Route exact component={Products} path={links.label}/>
        <Route exact component={Products} path={links.bestseller}/>
        <Route exact component={Products} path={links.random}/>
        <Route exact component={Products} path={links.last}/>
        <Route exact component={Products} path={links.custom}/>
        <Route exact component={Default_quick_checkout} path={links.qcheckout}/>
        <Route exact component={Finish} path={links.qcheckout_finish}/>
        <Route exact component={Form_search} path={links.search}/>
        <Route exact component={Products_search} path={links.search_result}/>
        <Route exact component={Login} path={links.login}/>
        <Route exact component={Register} path={links.register}/>
        <Route exact component={Addresses} path={links.addresses}/>
        <Route exact component={Newaddress} path={links.addNewAddress}/>
        <Route exact component={Editaddress} path={links.editAddress}/>
        <Route exact component={Listorder} path={links.orders}/>
        <Route exact component={Order} path={links.order}/>
        <Route exact component={Myaccount} path={links.myaccount}/>
        <Route exact component={Groupsinfo} path={links.groupsinfo}/>
        <Route exact component={Addressesmodal} path={links.addressPopup}/>
        <Route exact component={Wishlist} path={links.wishlist}/>
            </Route>
        ;
    }
    return (element);
}

export default Routes;