import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Parser from '../../../js/react/node_modules/html-react-parser';
import ShopUserAddressesPopup from '../../../js/src/controllers/user/useraddressespopup.js';
import Search from '../elements/search.js';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';


const Addressesmodal = () => {

    var address = [];
    var tmp = [];
    const i = 0;
    var str = '';
    var s1 = '';
    var s2 = '';
    var s3 = '';
    let addressTypeTohandler = null;
    ShopUserAddressesPopup.setAddressTypeToHandler(dataJson.addrType);
    ShopUserAddressesPopup.addressTypeTohandler = dataJson.addrType;
    document.addEventListener("DOMContentLoaded", function () {
        parent.shopUserAddressesPopup.setUserAddresses(dataJson.addresses);
        parent.document.addEventListener("visibilitychange", function(e) {
            var isIframeDisplayes = false;

            try {
                isIframeDisplayes = parent.document.querySelector("#userAddressesPopup").style.display == "block";
            } catch (error) {}

            if (!document.hidden && isIframeDisplayes) {
                location.reload();
            }
        });

    });

    function printAddress(userAddress) {
        address = [];
        if (dataJson.configFields['street']['display'] || dataJson.configFields['street_nr']['display']) {
            tmp = [];
            tmp = [(dataJson.configFields['street']['display']) ? userAddress.street : '',
             (dataJson.configFields['street_nr']['display']) ? userAddress.street_nr : '']
            address.push(tmp.join(' '));
        }
        if (dataJson.configFields['zip']['display'] || dataJson.configFields['city']['display']) {
            tmp = [];
            tmp = [(dataJson.configFields['zip']['display']) ? userAddress.zip : '',
                (dataJson.configFields['city']['display']) ? userAddress.city: '']
            address.push(tmp.join(' '));
        }
        if (dataJson.configFields['country']['display']) {
            address.push(userAddress.country);
        }
        str = '';
        address.map((item, ind) =>
            (item != '' && item != ' ' ) ?
                    str += item + ', '
                    : str += ' '
        )
        str = str.trim();

        return str.substring(str.length - 1, 0)+' ';
    }
    const handleClick = (event) => {
        event.stopPropagation();
    };
    const handleChange = (event) => {
        event.stopPropagation();
    };
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;

    jQuery(document).ready(function(){
        element = <div className="container-popup addressPopup">
            <Form action={dataJson.addressPopup} method="post" id="adminForm" name="adminForm" className="addressModal">

                {/*<div className="addressPopup__search clearfix">*/}
                {/*  <Search text={dataJson.searchText}/>*/}
                {/*</div>*/}

                <Button variant="link" onClick={(e) => open(dataJson.addNewAddressLink, '_blank', '')}
                        className="user-addresses__new mb-4 pt-3 pb-3 pl-5" id="addressPopupAddNewAddress">
                    + {Joomla.JText._('COM_SMARTSHOP_ADD_NEW_ADDRESS')}
                </Button>

                {(!dataJson.addresses) ?
                    <div className="alert alert-no-items addressPopup__no-items">
                        {Joomla.JText._('JGLOBAL_NO_MATCHING_RESULTS')}
                    </div>
                    :
                    <div className="addressPopup__tbody">
                        {dataJson.addresses.map((userAddress, ins) =>
                            (typeof dataJson.addrType == 'undefined' || dataJson.addrType == null) ?
                                <div key={ins}
                                     className='user-address border border-secondary mb-3 pt-2 pb-2 pl-5 pr-3 addressPopup__address'
                                     data-address-id={userAddress.address_id} onClick={(e) => {
                                    parent.shopUserAddressesPopup.setAddressTypeToHandler('billing');
                                    parent.shopUserAddressesPopup.addressTypeTohandler = 'billing';
                                    parent.shopUserAddressesPopup.runAddressHandler(e.target);
                                }}>
                                    <p className="user-address__name">
                                        {(dataJson.configFields['f_name']['display']) ? userAddress.f_name + ' ' : ''}
                                        {(dataJson.configFields['l_name']['display']) ? userAddress.l_name : ''}
                                    </p>
                                    <p className="user-address__address mb-1">
                                        {printAddress(userAddress)}
                                    </p>
                                    <Button variant="link" onClick={(e) => {
                                        open(userAddress.edit_link_popup, '_blank', '');
                                        e.stopPropagation();
                                    }}
                                            className="user-address__edit btn btn-link pl-0">{' ' + Joomla.JText._('COM_SMARTSHOP_EDIT')}</Button>
                                </div>
                                :
                                <div key={ins}
                                     className='user-address border border-secondary mb-3 pt-2 pb-2 pl-5 pr-3 addressPopup__address'
                                     data-address-id={userAddress.address_id} onClick={(e) => {
                                    parent.shopUserAddressesPopup.setAddressTypeToHandler(dataJson.addrType);
                                    parent.shopUserAddressesPopup.addressTypeTohandler = dataJson.addrType;
                                    parent.shopUserAddressesPopup.runAddressHandler(e.target);
                                }}>
                                    <p className="user-address__name">
                                        {(dataJson.configFields['f_name']['display']) ? userAddress.f_name + ' ' : ''}
                                        {(dataJson.configFields['l_name']['display']) ? userAddress.l_name : ''}
                                    </p>
                                    <p className="user-address__address mb-1">
                                        {printAddress(userAddress)}
                                    </p>
                                    <Button variant="link" onClick={(e) => {
                                        open(userAddress.edit_link_popup, '_blank', '');
                                        e.stopPropagation();
                                    }}
                                            className="user-address__edit btn btn-link pl-0">{' ' + Joomla.JText._('COM_SMARTSHOP_EDIT')}</Button>
                                </div>
                        )}
                        <input type="hidden" name={Joomla.getOptions('csrf.token')} value="1"/>
                    </div>
                }


                <div className="addressPopup__pagination">
                    <div className="addressPopup__pagination-links"
                         dangerouslySetInnerHTML={{__html: dataJson.pagesLinks}}/>
                    <div className="addressPopup__pagination-limit"
                         dangerouslySetInnerHTML={{__html: dataJson.limitBox}}/>
                </div>
            </Form>
        </div>;

    });

   return (element);
}
export default Addressesmodal;
