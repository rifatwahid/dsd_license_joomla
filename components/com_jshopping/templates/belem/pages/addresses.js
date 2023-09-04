import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link,
    useParams,
    useLocation, browserHistory, useHistory
} from '../../../js/react/node_modules/react-router-dom';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import { getAddresses as getAddressesAction } from '../../../js/react/src/redux/modules/addresses';
import { getPageData as getPageDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
//import Post from './components/Post';
//import CreatePost from './components/CreatePost';


const Addresses = ( {pageData, getPageData}) => {
    var address = [];
    var tmp = [];
    const i = 0;
    var str = '';
    var s1 = '';
    var s2 = '';
    var s3 = '';
    var data = {};
    useEffect(() => {
        getPageData(window.location.href  + '?ajax=1');
    }, []);
    data = pageData;

    let deleteAddress = (delete_link) => {
            fetch(delete_link  + '&ajax=1', {
                method: "GET",
            }) .then(res => res.json())
                .then((result) => {
                    if(result.status == 1){
                        document.getElementById(result.id).remove();
                        Joomla.renderMessages({"success":[result.message]});
                    }else{
                        Joomla.renderMessages({"error":[result.message]});
                    }
                    setTimeout(function(){
                        document.getElementById("system-message-container").innerHTML = '';
                    }, 3000);

                })
    }
    let defaultAddress = (default_link) => {
            fetch(default_link  + '&ajax=1', {
                method: "GET",
            }) .then(res => res.json())
                .then((result) => {
                    if(result.status == 1){
                        fetch('index.php?option=com_jshopping&controller=user&task=addresses&ajax=1', {
                            method: "GET",
                        }) .then(res => res.json())
                            .then((result) => {
                                // updateData(result);
                                getPageData(window.location.href  + '?ajax=1');
                            })

                        Joomla.renderMessages({"success":[result.message]});
                    }else{
                        Joomla.renderMessages({"error":[result.message]});
                    }
                    setTimeout(function(){
                        document.getElementById("system-message-container").innerHTML = '';
                    }, 3000);

                })
    }

    function printAddress(userAddress) {
        address = [];
        if (data.configFields['street']['display'] || data.configFields['street_nr']['display']) {
            tmp = [];
            tmp = [(data.configFields['street']['display']) ? userAddress.street : '',
                (data.configFields['street_nr']['display']) ? userAddress.street_nr : '']
            address.push(tmp.join(' '));
        }
        if (data.configFields['zip']['display'] || data.configFields['city']['display']) {
            tmp = [];
            tmp = [(data.configFields['zip']['display']) ? userAddress.zip : '',
                (data.configFields['city']['display']) ? userAddress.city: '']
            address.push(tmp.join(' '));
        }
        if (data.configFields['country']['display']) {
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
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
if(data.addNewAddressLink) {
    element = <div className="front-user-addresses">
        <h1 className="hidden">{Joomla.JText._('COM_SMARTSHOP_ADDRESSES')}</h1>
        <div className="user-addresses">
            <Link to={data.addNewAddressLink} className="user-addresses__new mb-4 pt-3 pb-3 pl-5">
                + {Joomla.JText._('COM_SMARTSHOP_ADD_NEW_ADDRESS')}
            </Link>

            {(data.userAddresses.length > 0) ?
                <div className="user-addresses__list">
                    {data.userAddresses.map((userAddress, ind) =>
                        <div key={ind} id={userAddress.address_id}
                             className={(userAddress.is_default == 1) ? 'user-address mb-4 pt-3 pb-3 pl-5 user-address--default' : 'user-address mb-4 pt-3 pb-3 pl-5'}>
                            <p className="user-address__name">
                                {(data.configFields['f_name']['display']) ? userAddress.f_name + ' ' : ''}
                                {(data.configFields['l_name']['display']) ? userAddress.l_name : ''}
                            </p>
                            <p className="user-address__address">
                                {printAddress(userAddress)}
                            </p>

                            {(userAddress.is_default == 0) ?
                                // <Link to={userAddress.set_default_link} className="user-address__as-default">
                                   <span><span className="user-address__as-default btn-link" onClick={(e) => defaultAddress(userAddress.set_default_link)}>  {Joomla.JText._('COM_SMARTSHOP_SET_AS_DEFAULT')}</span>
                                    <span className="user-address__separator"> - </span></span>
                                // </Link>
                                : ''}

                            <Link to={userAddress.edit_link}
                               className="user-address__edit btn-link">{Joomla.JText._('COM_SMARTSHOP_EDIT')}</Link>

                            {(userAddress.is_default == 0) ?
                                <span><span className="user-address__separator"> - </span>
                                     <span onClick={(e) => deleteAddress(userAddress.delete_link)} className="user-address__delete btn-link">
                                         {Joomla.JText._('COM_SMARTSHOP_DELETE')}
                                     </span>
                                </span>
                                : ''}

                            {(userAddress.is_default == 1) ?
                                <div className="user-address__default">
                                    <p className="user-address__default-text">
                                        {Joomla.JText._('COM_SMARTSHOP_DEFAULT')}
                                    </p>
                                </div>
                                : ''}
                        </div>
                    )}

                    <input type="hidden" name={Joomla.getOptions('csrf.token')} value="1"/>
                </div>
                : ''}
        </div>

    </div>;
}
   return (element);
}

export default  connect(
        ({ pageData }) => ({ pageData: pageData.pageData }),
        {
            getPageData: getPageDataAction
        }
    )(Addresses);

