import React, { useState } from '../../../js/react/node_modules/react';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link,
    useParams,
    useLocation, browserHistory
} from '../../../js/react/node_modules/react-router-dom';
const Myaccount = () => {
    let [dataLogout, setLogout] = useState('');
    let updateLogout=(value)=> {
        setLogout(value);
    }
    function logout(link){
        fetch(link  + '&ajax=1', {
            method: "GET",
        }) .then(res => res.json())
            .then((result) => {
                if(result == 1){ updateLogout(1); }
            });
    }
    if (dataLogout) return <Redirect to={dataLogout} />;
    const element = <div className="shop shop-account">

        <h1 className="shop-account__page-title mb-4">{Joomla.JText._('COM_SMARTSHOP_MY_ACCOUNT')}</h1>

        <div className="myAccountCards">
            <div className="row">

                <Link to={dataJson.addressesLink}
                   className="col-md-3 myAccountCards__card">
                    <div className="card border-0 mb-4 shadow">
                        <div className="icon-dark text-dark address"></div>
                        <div className="card-body text-dark pt-0">
                            <div className="text-value">
                                <h5 className="card-title mb-0">{Joomla.JText._('COM_SMARTSHOP_ADDRESSES')}</h5>
                            </div>
                        </div>
                    </div>
                </Link>

                {/*<Link to={dataJson.profileEditLink}*/}
                <a href={dataJson.profileEditLink}
                   className="col-md-3 myAccountCards__card">
                    <div className="card border-0 mb-4 shadow">
                        <div className="icon-dark text-dark authentication"></div>
                        <div className="card-body text-dark pt-0">
                            <div className="text-value">
                                <h5 className="card-title mb-0">{Joomla.JText._('COM_SMARTSHOP_AUTHENTICATION')}</h5>
                            </div>
                        </div>
                    </div>
                </a>

                <Link to={dataJson.ordersLink}
                   className="col-md-3 myAccountCards__card">
                    <div className="card border-0 mb-4 shadow">
                        <div className="icon-dark text-dark orders"></div>
                        <div className="card-body text-dark pt-0">
                            <div className="text-value">
                                <h5 className="card-title mb-0">{Joomla.JText._('COM_SMARTSHOP_MY_ORDERS')}</h5>
                            </div>
                        </div>
                    </div>
                </Link>

                {(dataJson.config.allow_offer_on_product_details_page == 1 || dataJson.config.allow_offer_in_cart == 1) ?
                <Link to={dataJson.offerAndOrderLink}
                   className="col-md-3 myAccountCards__card">
                    <div className="card border-0 mb-4 shadow">
                        <div className="icon-dark text-dark offers"></div>
                        <div className="card-body text-dark pt-0">
                            <div className="text-value">
                                <h5 className="card-title mb-0">{Joomla.JText._('COM_SMARTSHOP_OFFER_AND_ORDER_MY_OFFER')}</h5>
                            </div>
                        </div>
                    </div>
                </Link>
                : '' }

                {(dataJson.config.enable_wishlist == 1) ?
                <Link to={dataJson.wishlistLink}
                   className="col-md-3 myAccountCards__card">
                    <div className="card border-0 mb-4 shadow">
                        <div className="icon-dark text-dark wishlist"></div>
                        <div className="card-body text-dark pt-0">
                            <div className="text-value">
                                <h5 className="card-title mb-0">{Joomla.JText._('COM_SMARTSHOP_WISHLIST')}</h5>
                            </div>
                        </div>
                    </div>
                </Link>
                : ''}

                {(dataJson.isSmartEditorEnabled == 1) ?
                <Link to={dataJson.editorWishlistLink}
                   className="col-md-3 myAccountCards__card">
                    <div className="card border-0 mb-4 shadow">
                        <div className="icon-dark text-dark smarteditor-wishlist"></div>
                        <div className="card-body text-dark pt-0">
                            <div className="text-value">
                                <h5 className="card-title mb-0">{Joomla.JText._('COM_SMARTSHOP_SAVED_DESIGNS')}</h5>
                            </div>
                        </div>
                    </div>
                </Link>
                : ''}
            </div>
            <div className="row">
                {/*<a href="" onClick={(e) => {e.preventDefault();logout(dataJson.logoutLink);}}*/}
                {/*   className="col-md-12">*/}
                {/*    {Joomla.JText._('COM_SMARTSHOP_LOGOUT')}*/}
                {/*</a>*/}
                <a href={dataJson.logoutLink} className="col-md-12">
                    {Joomla.JText._('COM_SMARTSHOP_LOGOUT')}
                </a>
            </div>
        </div>

    </div>;

   return (element);
}
export default Myaccount;
