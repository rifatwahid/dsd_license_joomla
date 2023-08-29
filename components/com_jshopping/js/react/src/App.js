import './App.css';
import React, {useState, useCallback, useEffect} from '../node_modules/react';
import {
    BrowserRouter as Router,
    Route,
    browserHistory
} from '../node_modules/react-router-dom';
import Listorder from '../../../templates/belem/pages/listorder';
import Order from '../../../templates/belem/pages/order';
import Login from '../../../templates/belem/pages/login';
import Register from '../../../templates/belem/pages/register';
import Addresses from '../../../templates/belem/pages/addresses';
import Newaddress from '../../../templates/belem/pages/newaddress';
import Editaddress from '../../../templates/belem/pages/editaddress';
import Myaccount from '../../../templates/belem/pages/myaccount';
import Groupsinfo from '../../../templates/belem/pages/groupsinfo';
import Logout from '../../../templates/belem/pages/logout';
import Addressesmodal from '../../../templates/belem/pages/addresses_modal';
import Cart from '../../../templates/belem/pages/cart';
import Wishlist from '../../../templates/belem/pages/wishlist';
import Category_default from '../../../templates/belem/pages/category_default';
import Maincategory from '../../../templates/belem/pages/maincategory';
import Manufacturers from '../../../templates/belem/pages/manufacturers';
import Products_manufacturer from '../../../templates/belem/pages/products_manufacturer';
import Products from '../../../templates/belem/pages/products';
import Product_default from '../../../templates/belem/pages/product_default';
import Created_offer_and_order from '../../../templates/belem/pages/created_offer_and_order';
import Myoffer_and_order from '../../../templates/belem/pages/myoffer_and_order';
import Form_search from '../../../templates/belem/pages/form_search';
import Noresult_search from '../../../templates/belem/pages/noresult_search';
import Products_search from '../../../templates/belem/pages/products_search';
import Default_quick_checkout from '../../../templates/belem/pages/default_quick_checkout';
import Finish from '../../../templates/belem/pages/finish';
import Wishlist_btn from '../../../templates/belem/elements/wishlist_btn';
import Sprint_atribute from '../../../templates/belem/elements/sprint_atribute';
import Default_one_click_checkout from '../../../templates/belem/pages/_default_one_click_checkout';
import Newroute from '../../../templates/belem/pages/newroute';
import Step6 from '../../../templates/belem/pages/step6';
import Deleteaddress from '../../../templates/belem/elements/deleteaddress';
import queryString from '../../../js/react/node_modules/query-string';

function App() {
    let [links, setLinks] = useState('');
    let updateData=(value)=> {
        setLinks(value);
    }

    useEffect(() => {
            fetch('index.php?option=com_jshopping&controller=functions&task=generate_link' , {
                method: "GET",
            }) .then(res => res.json())
                .then((result) => {
                    updateData(result);
                });
        },
        []
    );

    let element =
        <div>
        <Route exact component={Newroute} path="/index.php"/>
    </div>;
    let el = '';
    if(links.cart) {
        if(links.categories && dataJson.component != 'Category_default' && dataJson.component != 'Maincategory'){
            links.categories.forEach(function(elem){el += '<Route exact component={Category_default} path={"'+elem+'"}/>';});
            links.categories.forEach(function(elem){el += '<Route exact component={Maincategory} path={"'+elem+'"}/>';});
        }
        if(links.productslinks){
            links.productslinks.forEach(function(elem){el += '<Route exact component={Product_default} path={"'+elem+'"}/>';});
        }


            if(dataJson.sef == '1'){
                element = <div>
                    {(dataJson.component != 'Cart') ?
                        <Route exact component={Cart} path={links.cart}/>
                        : ''}
                    {(dataJson.component != 'Cart') ?
                        <Route exact component={Cart} path={links.cart_view}/>
                    : ''}
                    {(dataJson.component != 'Category_default') ?
                        <Route exact component={Category_default} path={links.category_view}/>
                        : ''}
                    {(dataJson.component != 'Maincategory') ?
                        <Route exact component={Maincategory} path={links.category}/>
                        : ''}
                    {(dataJson.component != 'Manufacturers') ?
                        <Route exact component={Manufacturers} path={links.manufacturer}/>
                        : ''}
                    {/*{(dataJson.component != 'Products_manufacturer') ?*/}
                    {/*    <Route exact component={Products_manufacturer} path={links.manufacturer_view}/>*/}
                    {/*    : ''}*/}
                    {(dataJson.component != 'Created_offer_and_order') ?
                        <Route exact component={Created_offer_and_order} path={links.offer_created}/>
                        : ''}
                    {(dataJson.component != 'Myoffer_and_order') ?
                        <Route exact component={Myoffer_and_order} path={links.myoffer_and_order}/>
                        : ''}
                    {/*<Route exact component={Product_default}  path={links.product}/>*/}
                    {(dataJson.component != 'Products') ?
                        <Route exact component={Products} path={links.products}/>
                        : ''}
                    {(dataJson.component != 'Products') ?
                        <Route exact component={Products} path={links.tophits}/>
                        : ''}
                    {(dataJson.component != 'Products') ?
                        <Route exact component={Products} path={links.toprating}/>
                        : ''}
                    {(dataJson.component != 'Products') ?
                        <Route exact component={Products} path={links.label}/>
                        : ''}
                    {(dataJson.component != 'Created_offer_and_order') ?
                        <Route exact component={Products} path={links.bestseller}/>
                        : ''}
                    {(dataJson.component != 'Products') ?
                        <Route exact component={Products} path={links.random}/>
                        : ''}
                    {(dataJson.component != 'Products') ?
                        <Route exact component={Products} path={links.last}/>
                        : ''}
                    {(dataJson.component != 'Products') ?
                        <Route exact component={Products} path={links.custom}/>
                        : ''}
                    {(dataJson.component != 'Default_quick_checkout') ?
                        <Route exact component={Default_quick_checkout} path={links.qcheckout}/>
                        : ''}
                    {(dataJson.component != 'Finish') ?
                        <Route exact component={Finish} path={links.qcheckout_finish}/>
                        : ''}
                    {(dataJson.component != 'Form_search') ?
                        <Route exact component={Form_search} path={links.search}/>
                        : ''}
                    {(dataJson.component != 'Products_search') ?
                        <Route exact component={Products_search} path={links.search_result}/>
                        : ''}
                    {(dataJson.component != 'Login') ?
                        <Route exact component={Login} path={links.login}/>
                        : ''}
                    {(dataJson.component != 'Register') ?
                        <Route exact component={Register} path={links.register}/>
                        : ''}
                    {(dataJson.component != 'Addresses') ?
                        <Route exact component={Addresses} path={links.addresses}/>
                        : ''}
                    {(dataJson.component != 'Newaddress') ?
                        <Route exact component={Newaddress} path={links.addNewAddress}/>
                        : ''}
                    {(dataJson.component != 'Editaddress') ?
                        <Route exact component={Editaddress} path={links.editAddress}/>
                        : ''}
                    {(dataJson.component != 'Listorder') ?
                        <Route exact component={Listorder} path={links.orders}/>
                        : ''}
                    {(dataJson.component != 'Order') ?
                        <Route exact component={Order} path={links.order}/>
                        : ''}
                    {(dataJson.component != 'Myaccount') ?
                        <Route exact component={Myaccount} path={links.myaccount}/>
                        : ''}
                    {(dataJson.component != 'Groupsinfo') ?
                        <Route exact component={Groupsinfo} path={links.groupsinfo}/>
                        : ''}
                    {(dataJson.component != 'Addressesmodal') ?
                        <Route exact component={Addressesmodal} path={links.addressPopup}/>
                        : ''}
                    {(dataJson.component != 'Deleteaddress') ?
                        <Route exact component={Deleteaddress} path={links.deleteAddress}/>
                        : ''}
                    {(dataJson.component != 'Wishlist') ?
                        <Route exact component={Wishlist} path={links.wishlist}/>
                        : ''}
                    {(dataJson.component != 'Wishlist') ?
                        <Route exact component={Wishlist} path={links.wishlistView}/>
                        : ''}
                    {(dataJson.component != 'Category_default') ?
                        links.categories.map((elem, ind) => (
                            <Route exact component={Category_default} path={elem}/>
                        ))
                        : ''}
                    {(dataJson.component != 'Maincategory') ?
                        links.categories.map((elem, ind) => (
                            <Route exact component={Maincategory} path={elem}/>
                        ))
                        : ''}
                    {/*{(dataJson.component != 'Product_default') ?*/}
                    {links.productslinks.map((elem, ind) => (
                            // (element != window.location.href) :
                            <Route exact component={Product_default} path={elem}/>
                        ))}
                    {/*    : ''*/}
                    {/*}*/}
                    {/*{(dataJson.component != 'Products_manufacturer') ?*/}
                    {(typeof links.manufacturers != 'undefined') ?
                        links.manufacturers.map((elem, ind) => (
                            <Route exact component={Products_manufacturer} path={elem}/>
                        ))
                        : ''
                    }
                        {/*: ''*/}
                    {/*}*/}
                    <Route exact component={Step6} path={links.step6}/>
                    <Route exact component={Newroute} path="/index.php"/>
                </div>;
        }
    }

    if(dataJson.component == 'Listorder'){
        return (<Router  history={browserHistory}>
            {(dataJson.sef == 1) ? <Route exact component={Listorder} path={window.location.pathname} /> : ''}
            {element}
        </Router>);
   }else if(dataJson.component == 'Order'){
     return (<Router  history={browserHistory}>
             {(dataJson.sef == 1) ? <Route exact component={Order} path={window.location.pathname} /> : ''}
             {element}


     </Router>);
   }else if(dataJson.component == 'Login'){
        return (<Router  history={browserHistory}>
            {(dataJson.sef == 1) ? <Route exact component={Login} path={window.location.pathname} /> : ''}
            {element}
        </Router>);
   }else if(dataJson.component == 'Register'){
     return (<Router  history={browserHistory}>
         {(dataJson.sef == 1) ? <Route exact component={Register} path={window.location.pathname} /> : ''}
         {element}
     </Router>);
   }else if(dataJson.component == 'Addresses'){
     return (
        <Router  history={browserHistory}>
        {(dataJson.sef == 1) ? <Route exact component={Addresses} path={window.location.pathname}/> : ''}
            {element}
        </Router>);
   }else if(dataJson.component == 'Newaddress'){
        return (
            <Router  history={browserHistory}>
        {(dataJson.sef == 1) ? <Route exact component={Newaddress} path={window.location.pathname} /> : ''}
                {element}
            </Router>);
   }else if(dataJson.component == 'Editaddress'){
        return (
            <Router  history={browserHistory}>
        {(dataJson.sef == 1) ? <Route exact component={Editaddress} path={window.location.pathname} /> : ''}
                {element}
            </Router>);
   }else if(dataJson.component == 'Myaccount'){
        return (<Router  history={browserHistory}>
            {(dataJson.sef == 1) ? <Route exact component={Myaccount} path={window.location.pathname} /> : ''}
            {element}
        </Router>);
    }else if(dataJson.component == 'Groupsinfo'){
     return (<Groupsinfo />);
   }else if(dataJson.component == 'Logout'){
     return (<Logout />);
   }else if(dataJson.component == 'Addressesmodal'){
     return (<Addressesmodal />);
   }else if(dataJson.component == 'Cart'){
     return (
         <Router  history={browserHistory}>
        {(dataJson.sef == 1) ? <Route exact component={Cart} path={window.location.pathname} /> : ''}
             {element}
         </Router>);
   }else if(dataJson.component == 'Wishlist'){
        return (
            <Router  history={browserHistory}>
        {(dataJson.sef == 1) ? <Route exact component={() => <Wishlist parentUpdateData={updateData} links={links}/>} path={window.location.pathname} /> : ''}
                {/*<Route exact component={Wishlist} path={window.location.pathname} />*/}
                {element}
            </Router>);
   }else if(dataJson.component == 'Category_default'){
        return (<Router  history={browserHistory}>
                {(dataJson.sef == 1) ? <Route exact component={Category_default} path={window.location.pathname} /> : ''}
                {element}
            </Router>
        );
   }else if(dataJson.component == 'Maincategory'){
        return (<Router  history={browserHistory}>
                {(dataJson.sef == 1) ? <Route exact component={Maincategory} path={window.location.pathname} /> : ''}
                {element}
            </Router>
        );
   }else if(dataJson.component == 'Manufacturers'){
     return (<Router  history={browserHistory}>
         {(dataJson.sef == 1) ? <Route exact component={Manufacturers} path={window.location.pathname} /> : ''}
         {element}
     </Router>);
   }else if(dataJson.component == 'Products_manufacturer'){
     return (<Router  history={browserHistory}>
         {/*{(dataJson.sef == 1) ? <Route exact component={Products_manufacturer} path={window.location.pathname} /> : ''}*/}
         {element}
     </Router>);
   }else if(dataJson.component == 'Products'){
     return (<Router  history={browserHistory}>
        {(dataJson.sef == 1) ? <Route exact component={Products} path={window.location.pathname} /> : ''}
        {element}
         </Router>
     );
   }else if(dataJson.component == 'Product_default') {
         return (<Router history={browserHistory}>
        {/*{(dataJson.sef == 1) ? <Route exact component={Product_default} path={window.location.pathname} /> : ''}*/}
                {element}
            </Router>);
   }else if(dataJson.component == 'Created_offer_and_order'){
        return (
            <Router  history={browserHistory}>
                {(dataJson.sef == 1) ? <Route exact component={Created_offer_and_order} path={window.location.pathname} /> : ''}
                {element}
            </Router>
        );
   }else if(dataJson.component == 'Myoffer_and_order'){
     return (
         <Router  history={browserHistory}>
        {(dataJson.sef == 1) ? <Route exact component={Myoffer_and_order} path={window.location.pathname} /> : ''}
             {element}
         </Router>
    );
   }else if(dataJson.component == 'Form_search'){
        return (
            <Router  history={browserHistory}>
                {(dataJson.sef == 1) ? <Route exact component={Form_search} path={window.location.pathname} /> : ''}
                {element}
            </Router>
        );
   }else if(dataJson.component == 'Products_search'){
        return (
            <Router  history={browserHistory}>
                {(dataJson.sef == 1) ? <Route exact component={Products_search} path={window.location.pathname} /> : ''}
                {element}
            </Router>
        );
   }else if(dataJson.component == 'Noresult_search'){
     return (<Router  history={browserHistory}>
         {(dataJson.sef == 1) ? <Route exact component={Noresult_search} path={window.location.pathname} /> : ''}
         {element}
     </Router>);
   }else if(dataJson.component == 'Default_quick_checkout'){
        return (<Router  history={browserHistory}>
            {(dataJson.sef == 1) ? <Route exact component={Default_quick_checkout} path={window.location.pathname} /> : ''}
            {element}
        </Router>);
   }else if(dataJson.component == 'Finish'){
        return (<Router  history={browserHistory}>
            {(dataJson.sef == 1) ? <Route exact component={Finish} path={window.location.pathname} /> : ''}
            {element}
        </Router>);
   }else if(dataJson.component == 'Wishlist_btn'){
     return (<Wishlist_btn />);
   }else if(dataJson.component == 'sprint_atribute'){
     return (<Sprint_atribute />);
   }else if(typeof dataJsonPopup != 'undefined' && dataJsonPopup.component == 'default_one_click_checkout'){
     return (<Default_one_click_checkout />);
   }

}
export default  App;
