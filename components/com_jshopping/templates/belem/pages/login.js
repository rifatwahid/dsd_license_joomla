import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import { Link, Redirect, useHistory } from '../../../js/react/node_modules/react-router-dom';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Parser from '../../../js/react/node_modules/html-react-parser';
import {getLoginData as getLoginDataAction} from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

const Login = ({loginData, getLoginData}) => {
    const history = useHistory();
    const [historyHref, setHref] = useState('');
    const updateHref = (value) => {
        setHref(value);
    }

    let [dataSave, setStatus] = useState('');
    let updateStatus=(value)=> {
        setStatus(value);
    }

    let [data, setData] = useState('');
    let updateData=(value)=> {
        setData(value);
    };

     useEffect(() => {
            fetch('index.php?option=com_jshopping&controller=user&task=login&ajax=1' , {
                method: "GET",
            }) .then(res => res.json())
                .then((result) => {
                    updateData(result);
                });
        },
        []
    );

    const handleSubmit = (event) => {
            event.preventDefault();
            var queryString = jQuery('#loginForm').serialize();
            fetch(data.loginSaveLink + '?ajax=1&ajax=1' , {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: queryString
            }).then(res => res.json())
                .then((result) => {
                    if (result.status == 1) {
                        window.location.href = transformLink(result.link);
                    } else {
                        Joomla.renderMessages({"error": [result.message]});
                    }
                    setTimeout(function () {
                        document.getElementById("system-message-container").innerHTML = '';
                    }, 3000);
                })
    };

    function transformLink(link){
        var parts = link.split('?ajax=1');

        return parts[0];
    }



    if (dataSave.status == 1) return <Redirect to={dataSave.link} />;
    let element = <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
    if(data.component) {

         element = <div className="shop shop-login">

            <h1 className="shop-login__page-title">{Joomla.JText._('COM_SMARTSHOP_LOGIN')}</h1>

            <div className="row">
                <div className="col-md-6 login-form">
                    <Form method="post" id="loginForm"
                          action={data.loginSaveLink}
                          name="jlogin" onSubmit={handleSubmit}>
                        <Form.Group>
                            <Form.Label className="sr-only">{Joomla.JText._('COM_SMARTSHOP_USERNAME')}</Form.Label>
                            <Form.Control type="text" placeholder={Joomla.JText._('COM_SMARTSHOP_USERNAME')} id="jlusername"
                                          name="username"/>
                        </Form.Group>

                        <Form.Group>
                            <Form.Label className="sr-only">{Joomla.JText._('COM_SMARTSHOP_PASSWORD')}</Form.Label>
                            <Form.Control type="password" placeholder={Joomla.JText._('COM_SMARTSHOP_PASSWORD')}
                                          id="jlpassword" name="passwd"/>
                        </Form.Group>

                        <div className="row">
                            <div className="col-md-6">
                                <a className="small text-secondary" href={data.href_lost_pass}>
                                    {Joomla.JText._('COM_SMARTSHOP_PASSWORD_FORGOTTEN')}
                                </a>
                            </div>

                            <div className="col-md-6">
                                <div className="form-group">
                                    <Button variant="outline-primary" type="submit" bsPrefix="btn"
                                        // onClick={(e) => {e.preventDefault();login();}}
                                            className="btn-block">
                                        {Joomla.JText._('COM_SMARTSHOP_LOGIN')}
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="return" value={data.return}/>
                        {Parser(data.kt)}
                    </Form>
                </div>

                <div className="col-md-6 guest-info">
                    <p className="h4 text-secondary">{Joomla.JText._('COM_SMARTSHOP_GUEST_NO_ACCOUNT_TITLE')}</p>

                    {(data.cart.count_product > 0 && data.config.shop_user_guest == 1 && data.show_pay_without_reg == 1) ?

                        <div>
                            <p className="text-secondary">
                                {Joomla.JText._('COM_SMARTSHOP_GUEST_NO_ACCOUNT_TO_CHECKOUT_TEXT')}
                            </p>
                            <Link to={data.step2Link} className="btn btn-outline-secondary btn-block col-md-6 float-right"   >
                                {/*onClick={(e) => handleLink(e)}*/}
                                {Joomla.JText._('COM_SMARTSHOP_TO_CHECKOUT')}
                            </Link >
                        </div>

                        :
                        <div><p className="text-secondary">
                            {Joomla.JText._('COM_SMARTSHOP_GUEST_NO_ACCOUNT_TO_REGISTRATION_TEXT')}
                        </p>

                            <Link className="btn btn-outline-secondary btn-block col-md-6 float-right"
                                  to={data.href_register}>
                                {Joomla.JText._('COM_SMARTSHOP_REGISTER')}
                            </Link>
                        </div>
                    }
                </div>
            </div>

        </div>;
    }

    return element;
}
// export default  connect(
//     ({ loginData }) => ({ loginData: loginData.loginData }),
//     {
//         getLoginData: getLoginDataAction
//     }
// )(Login);
export default Login;